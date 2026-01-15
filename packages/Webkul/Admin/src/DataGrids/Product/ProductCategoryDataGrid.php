<?php

namespace Webkul\Admin\DataGrids\Product;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class ProductCategoryDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     */
    public function prepareQueryBuilder(): Builder
    {
        $queryBuilder = DB::table('product_categories')
            ->addSelect(
                'product_categories.id',
                'product_categories.name',
                'product_categories.description'
            );

        $this->addFilter('id', 'product_categories.id');
        $this->addFilter('name', 'product_categories.name');
        $this->addFilter('description', 'product_categories.description');

        return $queryBuilder;
    }

    /**
     * Prepare columns.
     */
    public function prepareColumns(): void
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.products.categories.index.datagrid.id'),
            'type'       => 'string',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'name',
            'label'      => trans('admin::app.products.categories.index.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'description',
            'label'      => trans('admin::app.products.categories.index.datagrid.description'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);
    }

    /**
     * Prepare actions.
     */
    public function prepareActions(): void
    {
        $this->addAction([
            'index'  => 'edit',
            'icon'   => 'icon-edit',
            'title'  => trans('admin::app.products.categories.index.datagrid.edit'),
            'method' => 'GET',
            'url'    => fn ($row) => route('admin.products.categories.edit', $row->id),
        ]);

        $this->addAction([
            'index'  => 'delete',
            'icon'   => 'icon-delete',
            'title'  => trans('admin::app.products.categories.index.datagrid.delete'),
            'method' => 'DELETE',
            'url'    => fn ($row) => route('admin.products.categories.delete', $row->id),
        ]);
    }

    /**
     * Prepare mass actions.
     */
    public function prepareMassActions(): void
    {
        $this->addMassAction([
            'icon'   => 'icon-delete',
            'title'  => trans('admin::app.products.categories.index.datagrid.delete'),
            'method' => 'POST',
            'url'    => route('admin.products.categories.mass_delete'),
        ]);
    }
}
