<?php

namespace Webkul\Admin\Http\Controllers\Facture;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Criteria\RequestCriteria;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Webkul\Admin\DataGrids\Facture\FactureDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Http\Requests\AttributeForm;
use Webkul\Admin\Http\Requests\MassDestroyRequest;
use Webkul\Admin\Http\Resources\QuoteResource;
use Webkul\Core\Traits\PDFHandler;
use Webkul\Lead\Repositories\LeadRepository;
use Webkul\Quote\Repositories\QuoteRepository;

class FactureController extends Controller
{
    use PDFHandler;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected QuoteRepository $quoteRepository,
        protected LeadRepository $leadRepository
    ) {
        request()->request->add(['entity_type' => 'quotes']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View|JsonResponse
    {
        
        if (request()->ajax()) {
            return datagrid(FactureDataGrid::class)->process();
        }

        return view('admin::factures.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $lead = $this->leadRepository->find(request('id'));

        return view('admin::factures.create', compact('lead'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AttributeForm $request): RedirectResponse
    {
        Event::dispatch('quote.create.before');

        $quote = $this->quoteRepository->create($request->all());
        $quote->type = 'facture';
        $quote->save();
        $leadId = request('lead_id');
         if (!empty($quote->items)) {

            foreach ($quote->items as $item) {

                if ($item->product->quantity >= $item->quantity && $item->quantity > 0) {
                    $item->product->quantity = $item->product->quantity - $item->quantity;
                    $item->product->quantity;
                    $item->product->update();
                     
                } else {
                    $quote->delete();
                    session()->flash('error', 'Votre produit ' . $item->name . ' est en rupture , quantité restante ' . $item->product->quantity);
                    return redirect()->route('admin.factures.create');
                }
            }
        }
        if ($leadId) {
            $lead = $this->leadRepository->find($leadId);

            $lead->quotes()->attach($quote->id);
        }

        Event::dispatch('quote.create.after', $quote);

        session()->flash('success', trans('admin::app.quotes.index.create-success'));

        return request()->query('from') === 'lead' && $leadId
            ? redirect()->route('admin.leads.view', ['id' => $leadId, 'from' => 'quotes'])
            : redirect()->route('admin.factures.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $quote = $this->quoteRepository->findOrFail($id);

        return view('admin::factures.edit', compact('quote'));
    }
// Fin transaction
public function update(AttributeForm $request, int $id): RedirectResponse
{
    Event::dispatch('quote.update.before', $id);

    // 1. Récupérer ancienne facture
    $oldQuote = $this->quoteRepository->with('items.product')->find($id);
    
    // 2. Préparer les données
    $oldQuantities = [];
    foreach ($oldQuote->items as $item) {
        if ($item->product_id) {
            $oldQuantities[$item->product_id] = $item->quantity;
        }
    }
    
    $items = $request->input('items', []);
    $newQuantities = [];
    foreach ($items as $item) {
        if (isset($item['product_id']) && isset($item['quantity'])) {
            $newQuantities[$item['product_id']] = (int) $item['quantity'];
        }
    }
// 3. Vérification des stocks
    $errors = [];
    foreach ($newQuantities as $productId => $newQty) {
        $product = \Webkul\Product\Models\Product::find($productId);
        if (!$product) continue;
        
        $oldQty = $oldQuantities[$productId] ?? 0;
        $difference = $newQty - $oldQty;
        
        
        
        if ($difference > 0) {
            // Augmentation demandée
            $stockAfterRestitution = $product->quantity + $oldQty;
            
            if ($newQty > $stockAfterRestitution) {
                $needed = $newQty - $stockAfterRestitution;
                
                $errors[] = "{$product->name}: demande {$newQty}, disponible {$stockAfterRestitution}";
            }
        }
    }
    
    if (!empty($errors)) {
        session()->flash('error', "Stock insuffisant:<br>" . implode("<br>", $errors));
        return redirect()->route('admin.factures.edit', $id);
    }
    
    // 4. Exécution
    try {
        // Restitution
        foreach ($oldQuantities as $productId => $oldQty) {
            $product = \Webkul\Product\Models\Product::find($productId);
            if ($product && $oldQty > 0) {
                $product->quantity += $oldQty;
                $product->update();
            }
        }

       
        
        // Update facture
        $quote = $this->quoteRepository->update($request->all(), $id);
        
        // Décrémentation
        foreach ($newQuantities as $productId => $newQty) {
            $product = \Webkul\Product\Models\Product::find($productId);
            if ($product && $newQty > 0) {
                $product->quantity -= $newQty;
                $product->update();
            }
        }
        
    } catch (\Exception $e) {
        // Rollback
        foreach ($oldQuantities as $productId => $oldQty) {
            $product = \Webkul\Product\Models\Product::find($productId);
            if ($product && $oldQty > 0) {
                $product->quantity -= $oldQty;
                $product->update();
            }
        }
        
        session()->flash('error', 'Erreur: ' . $e->getMessage());
        return redirect()->route('admin.factures.edit', $id);
    }
    
    // 5. Gestion leads
    $quote->leads()->detach();
    $leadId = request('lead_id');
    if ($leadId) {
        $lead = $this->leadRepository->find($leadId);
        $lead->quotes()->attach($quote->id);
    }

    Event::dispatch('quote.update.after', $quote);
    session()->flash('success', trans('admin::app.quotes.index.update-success'));

    return request()->query('from') === 'lead' && $leadId
        ? redirect()->route('admin.leads.view', ['id' => $leadId, 'from' => 'quotes'])
        : redirect()->route('admin.factures.index');
}
    /**
     * Search the quotes.
     */
    public function search(): AnonymousResourceCollection
    {
        $quotes = $this->quoteRepository
            ->pushCriteria(app(RequestCriteria::class))
            ->all();

        return QuoteResource::collection($quotes);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->quoteRepository->findOrFail($id);

        try {
            Event::dispatch('quote.delete.before', $id);

            $this->quoteRepository->delete($id);

            Event::dispatch('quote.delete.after', $id);

            return response()->json([
                'message' => trans('admin::app.quotes.index.delete-success'),
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => trans('admin::app.quotes.index.delete-failed'),
            ], 400);
        }
    }

    /**
     * Mass Delete the specified resources.
     */
    public function massDestroy(MassDestroyRequest $massDestroyRequest): JsonResponse
    {
        $quotes = $this->quoteRepository->findWhereIn('id', $massDestroyRequest->input('indices'));

        try {
            foreach ($quotes as $quotes) {
                Event::dispatch('quote.delete.before', $quotes->id);

                $this->quoteRepository->delete($quotes->id);

                Event::dispatch('quote.delete.after', $quotes->id);
            }

            return response()->json([
                'message' => trans('admin::app.quotes.index.delete-success'),
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => trans('admin::app.quotes.index.delete-failed'),
            ], 400);
        }
    }

    /**
     * Print and download the for the specified resource.
     */
    public function print($id): Response|StreamedResponse
    {
        $quote = $this->quoteRepository->findOrFail($id);

        return $this->downloadPDF(
            view('admin::quotes.pdf', compact('quote'))->render(),
            'Quote_'.$quote->subject.'_'.$quote->created_at->format('d-m-Y')
        );
    }
}
