<?php

namespace Webkul\Admin\DataGrids\Warehouse;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class StockEntryDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     */
    public function prepareQueryBuilder(): Builder
    {
        $queryBuilder = DB::table('stock_entries')
            ->leftJoin('persons', 'stock_entries.person_id', '=', 'persons.id')
            ->leftJoin('users', 'stock_entries.user_id', '=', 'users.id')
            ->addSelect(
                'stock_entries.id',
                'stock_entries.date_appro',
                'persons.name as person_name',
                'users.name as user_name'
            );

        if ($userIds = bouncer()->getAuthorizedUserIds()) {
            $queryBuilder->whereIn('stock_entries.user_id', $userIds);
        }

        $this->addFilter('id', 'stock_entries.id');
        $this->addFilter('date_appro', 'stock_entries.date_appro');
        $this->addFilter('person_name', 'persons.name');
        $this->addFilter('user_name', 'users.name');

        return $queryBuilder;
    }

    /**
     * Prepare columns.
     */
    public function prepareColumns(): void
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.warehouse.stock-entries.datagrid.id'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'date_appro',
            'label'      => trans('admin::app.warehouse.stock-entries.datagrid.date'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'person_name',
            'label'      => trans('admin::app.warehouse.stock-entries.datagrid.supplier'),
            'type'       => 'string',
            'sortable'           => true,
            'filterable'         => true,
            'filterable_type'    => 'searchable_dropdown',
            'filterable_options' => [
                'repository' => \Webkul\Contact\Repositories\PersonRepository::class,
                'column'     => [
                    'label' => 'name',
                    'value' => 'name',
                ],
            ],
        ]);

        $this->addColumn([
            'index'      => 'user_name',
            'label'      => trans('admin::app.warehouse.stock-entries.datagrid.created-by'),
            'type'       => 'string',
            'sortable'           => true,
            'filterable'         => true,
            'filterable_type'    => 'searchable_dropdown',
            'filterable_options' => [
                'repository' => \Webkul\User\Repositories\UserRepository::class,
                'column'     => [
                    'label' => 'name',
                    'value' => 'name',
                ],
            ],
        ]);
    }

    /**
     * Prepare actions.
     */
    public function prepareActions(): void
    {
        if (bouncer()->hasPermission('warehouse.stock_entries.delete')) {
            $this->addAction([
                'index'  => 'delete',
                'icon'   => 'icon-delete',
                'title'  => trans('admin::app.warehouse.stock-entries.datagrid.delete'),
                'method' => 'DELETE',
                'url'    => fn ($row) => route('admin.warehouse.stock_entries.delete', $row->id),
            ]);
        }
    }
}
