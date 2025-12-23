<?php

namespace Webkul\Admin\Http\Controllers\Depense;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Webkul\Admin\DataGrids\Depense\DepenseDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Http\Requests\MassDestroyRequest;
use Webkul\Depense\Repositories\DepenseRepository;
use Webkul\Admin\Http\Requests\DepenseForm;
use Webkul\Depense\Models\Depense as DepenseAll;

class DepenseController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
     public function __construct(
        protected DepenseRepository $depenseRepository
    ) {
        // Vous pouvez ajouter une liaison si nécessaire
        request()->request->add(['entity_type' => 'depenses']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
            return datagrid(DepenseDataGrid::class)->process();
        }

        return view('admin::depenses.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin::depenses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DepenseForm $request): RedirectResponse
    {
        $data = $request->all();
       
        DepenseAll::create($data);
         if (!empty($quote->items)) {

            foreach ($quote->items as $item) {

                if ($item->product->quantity >= $item->quantity && $item->quantity > 0) {
                    $item->product->quantity = $item->product->quantity - $item->quantity;
                    $item->product->quantity;
                    $item->product->update();
                } else {

                    session()->flash('error', 'Votre produit ' . $item->name . ' est en rupture , quantité restante ' . $item->product->quantity);

                    return redirect()->route('admin.factures.create');
                }
            }
        }

        session()->flash('success', trans('admin::app.depenses.create.create-success'));

        return redirect()->route('admin.depenses.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $depense = $this->depenseRepository->findOrFail($id);

        return view('admin::depenses.edit', compact('depense'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DepenseForm $request, int $id): RedirectResponse
    {
        $data = $request->all();
      $depense =DepenseAll::find($id);
        $depense->update($data);
        session()->flash('success', trans('admin::app.depenses.edit.update-success'));

        return redirect()->route('admin.depenses.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $depense = $this->depenseRepository->findOrFail($id);

        try {
            $this->depenseRepository->delete($id);

            return response()->json([
                'message' => trans('admin::app.depenses.index.delete-success'),
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => trans('admin::app.depenses.index.delete-failed'),
            ], 400);
        }
    }

    /**
     * Mass Delete the specified resources.
     */
    public function massDestroy(MassDestroyRequest $request): JsonResponse
    {
        $depenses = $this->depenseRepository->findWhereIn('id', $request->input('indices'));

        try {
            foreach ($depenses as $depense) {
                $this->depenseRepository->delete($depense->id);
            }

            return response()->json([
                'message' => trans('admin::app.depenses.index.mass-delete-success'),
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => trans('admin::app.depenses.index.mass-delete-failed'),
            ], 400);
        }
    }
}