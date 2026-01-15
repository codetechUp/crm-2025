<?php

namespace Webkul\Admin\Http\Controllers\Order;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Event;
use Illuminate\View\View;
use Webkul\Admin\DataGrids\Order\OrderDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Http\Requests\MassDestroyRequest;
use Webkul\Order\Repositories\OrderRepository;

class OrderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected OrderRepository $orderRepository
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
            return datagrid(OrderDataGrid::class)->process();
        }

        return view('admin::orders.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin::orders.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(): RedirectResponse
    {
        $data = request()->validate([
            'subject'                => 'required|string|max:255',
            'description'            => 'nullable|string',
            'person_id'              => 'required|exists:persons,id',
            'has_production'         => 'nullable|boolean',
            'expected_delivery_date' => 'nullable|date',
            'notes'                  => 'nullable|string',
            'sub_total'              => 'nullable|numeric',
            'discount_percent'       => 'nullable|numeric',
            'discount_amount'        => 'nullable|numeric',
            'tax_amount'             => 'nullable|numeric',
            'grand_total'            => 'nullable|numeric',
            'items'                  => 'nullable|array',
            'items.*.product_id'     => 'required|exists:products,id',
            'items.*.quantity'       => 'required|numeric|min:0',
            'items.*.price'          => 'required|numeric|min:0',
            'items.*.total'          => 'nullable|numeric',
            'items.*.name'           => 'nullable|string',
        ]);

        if (! isset($data['items'])) {
            $data['items'] = [];
        }

        Event::dispatch('order.create.before');

        $order = $this->orderRepository->create($data);

        Event::dispatch('order.create.after', $order);

        session()->flash('success', trans('admin::app.orders.index.create-success'));

        return redirect()->route('admin.orders.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): View
    {
        $order = $this->orderRepository->with(['items.product', 'person', 'user', 'statusHistory.changedBy'])->findOrFail($id);

        return view('admin::orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $order = $this->orderRepository->with(['items.product'])->findOrFail($id);

        return view('admin::orders.edit', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $id): RedirectResponse
    {

        $data = request()->validate([
            'subject'                => 'required|string|max:255',
            'description'            => 'nullable|string',
            'person_id'              => 'required|exists:persons,id',
            'has_production'         => 'nullable|boolean',
            'expected_delivery_date' => 'nullable|date',
            'notes'                  => 'nullable|string',
            'sub_total'              => 'nullable|numeric',
            'discount_percent'       => 'nullable|numeric',
            'discount_amount'        => 'nullable|numeric',
            'tax_amount'             => 'nullable|numeric',
            'grand_total'            => 'nullable|numeric',
            'items'                  => 'nullable|array',
            'items.*.product_id'     => 'required|exists:products,id',
            'items.*.quantity'       => 'required|numeric|min:0',
            'items.*.price'          => 'required|numeric|min:0',
            'items.*.total'          => 'nullable|numeric',
            'items.*.name'           => 'nullable|string',

        ]);

        if (! isset($data['items'])) {
            $data['items'] = [];
        }

        Event::dispatch('order.update.before', $id);

        $order = $this->orderRepository->update($data, $id);

        Event::dispatch('order.update.after', $order);

        session()->flash('success', trans('admin::app.orders.index.update-success'));

        return redirect()->route('admin.orders.index');
    }

    /**
     * Update order status.
     */
    public function updateStatus(int $id): RedirectResponse
    {
        $data = request()->validate([
            'status' => 'required|string',
            'notes'  => 'nullable|string',
        ]);

        $order = $this->orderRepository->updateStatus($id, $data['status'], $data['notes'] ?? null);

        session()->flash('success', trans('admin::app.orders.index.status-updated'));

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->orderRepository->findOrFail($id);

        try {
            Event::dispatch('order.delete.before', $id);

            $this->orderRepository->delete($id);

            Event::dispatch('order.delete.after', $id);

            return response()->json([
                'message' => trans('admin::app.orders.index.delete-success'),
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => trans('admin::app.orders.index.delete-failed'),
            ], 400);
        }
    }

    /**
     * Mass Delete the specified resources.
     */
    public function massDestroy(MassDestroyRequest $massDestroyRequest): JsonResponse
    {
        $orders = $this->orderRepository->findWhereIn('id', $massDestroyRequest->input('indices'));

        try {
            foreach ($orders as $order) {
                Event::dispatch('order.delete.before', $order->id);

                $this->orderRepository->delete($order->id);

                Event::dispatch('order.delete.after', $order->id);
            }

            return response()->json([
                'message' => trans('admin::app.orders.index.delete-success'),
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => trans('admin::app.orders.index.delete-failed'),
            ], 400);
        }
    }
}
