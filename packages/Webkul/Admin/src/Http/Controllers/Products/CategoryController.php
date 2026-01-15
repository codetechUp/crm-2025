<?php

namespace Webkul\Admin\Http\Controllers\Products;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Product\Repositories\ProductCategoryRepository;

class CategoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected ProductCategoryRepository $productCategoryRepository)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
            return app(\Webkul\Admin\DataGrids\Product\ProductCategoryDataGrid::class)->process();
        }

        return view('admin::products.categories.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin::products.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(): RedirectResponse
    {
        $this->validate(request(), [
            'name' => 'required',
        ]);

        $this->productCategoryRepository->create(request()->all());

        session()->flash('success', trans('admin::app.products.categories.create-success'));

        return redirect()->route('admin.products.categories.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $category = $this->productCategoryRepository->findOrFail($id);

        return view('admin::products.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $id): RedirectResponse
    {
        $this->validate(request(), [
            'name' => 'required',
        ]);

        $this->productCategoryRepository->update(request()->all(), $id);

        session()->flash('success', trans('admin::app.products.categories.update-success'));

        return redirect()->route('admin.products.categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->productCategoryRepository->delete($id);

            return response()->json([
                'message' => trans('admin::app.products.categories.delete-success'),
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => trans('admin::app.products.categories.delete-failed'),
            ], 400);
        }
    }

    /**
     * Mass delete the specified resources.
     */
    public function massDestroy(): JsonResponse
    {
        $indices = request()->input('indices', []);

        if (empty($indices)) {
            return response()->json([
                'message' => trans('admin::app.products.categories.mass-delete-failed'),
            ], 400);
        }

        try {
            foreach ($indices as $id) {
                $this->productCategoryRepository->delete($id);
            }

            return response()->json([
                'message' => trans('admin::app.products.categories.mass-delete-success'),
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => trans('admin::app.products.categories.mass-delete-failed'),
            ], 400);
        }
    }
}
