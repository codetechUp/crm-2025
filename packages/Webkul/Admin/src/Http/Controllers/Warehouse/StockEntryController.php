<?php

namespace Webkul\Admin\Http\Controllers\Warehouse;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Warehouse\Models\StockEntry;
use Webkul\Warehouse\Models\StockEntryItem;
use Webkul\Product\Models\Product;
use Webkul\Admin\DataGrids\Warehouse\StockEntryDataGrid;

class StockEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
           
            // DataGrid for Stock Entries would go here
            return datagrid(StockEntryDataGrid::class)->process();
        }

        return view('admin::warehouse.stock-entries.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin::warehouse.stock-entries.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(): RedirectResponse
    {
        $this->validate(request(), [
            'date_appro' => 'required|date',
            'person_id'  => 'required',
            'items'      => 'required|array',
            'items.*.product_id' => 'required',
            'items.*.quantity'   => 'required|numeric|min:1',
        ]);

        DB::transaction(function () {
            $stockEntry = StockEntry::create([
                'date_appro' => request('date_appro'),
                'person_id'  => request('person_id'),
                'notes'      => request('notes'),
                'user_id'    => auth()->user()->id,
            ]);

            foreach (request('items') as $item) {
                StockEntryItem::create([
                    'stock_entry_id' => $stockEntry->id,
                    'product_id'     => $item['product_id'],
                    'quantity'       => $item['quantity'],
                ]);

                // Increment Product stock
                $product = Product::find($item['product_id']);
                if ($product) {
                    $product->quantity += $item['quantity'];
                    $product->save();
                }
            }
        });

        session()->flash('success', trans('admin::app.warehouse.stock-entries.create-success'));

        return redirect()->route('admin.warehouse.stock_entries.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $stockEntry = StockEntry::findOrFail($id);
            
            // Note: Decide if we want to reverse the stock upon deletion. 
            // Usually, deleting an entry doesn't automatically reverse physical stock unless business rules say so.
            
            $stockEntry->delete();

            return response()->json([
                'message' => trans('admin::app.warehouse.stock-entries.delete-success'),
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => trans('admin::app.warehouse.stock-entries.delete-failed'),
            ], 400);
        }
    }
}
