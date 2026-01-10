<?php

namespace Webkul\Admin\DataGrids\Depense;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class DepenseDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     */
    public function prepareQueryBuilder(): Builder
    {
        $queryBuilder = DB::table('depenses')
            ->select(
                'depenses.id',
                'depenses.date',
                'depenses.category',
                'depenses.description',
                'depenses.montant',
                'depenses.note',
                'depenses.mode_paiement',
                'depenses.created_at',
                'users.id as user_id',
                'users.name as user_name'
            )
            ->leftJoin('users', 'depenses.user_id', '=', 'users.id')
            ->whereNull('depenses.deleted_at');

        if ($userIds = bouncer()->getAuthorizedUserIds()) {
            $queryBuilder->whereIn('depenses.user_id', $userIds);
        }

        $this->addFilter('id', 'depenses.id');
        $this->addFilter('date', 'depenses.date');
        $this->addFilter('category', 'depenses.category');
        $this->addFilter('mode_paiement', 'depenses.mode_paiement');
        $this->addFilter('user_name', 'users.name');
        $this->addFilter('created_at', 'depenses.created_at');

        return $queryBuilder;
    }

    /**
     * Prepare columns.
     */
    public function prepareColumns(): void
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.depenses.index.datagrid.id'),
            'type'       => 'integer',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'date',
            'label'      => trans('admin::app.depenses.index.datagrid.date'),
            'type'       => 'date',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => fn ($row) => core()->formatDate($row->date),
        ]);

        $this->addColumn([
            'index'      => 'category',
            'label'      => trans('admin::app.depenses.index.datagrid.category'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'description',
            'label'      => trans('admin::app.depenses.index.datagrid.description'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => false,
            'sortable'   => false,
        ]);

        $this->addColumn([
            'index'      => 'montant',
            'label'      => trans('admin::app.depenses.index.datagrid.montant'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => fn ($row) => core()->formatBasePrice($row->montant),
        ]);

        $this->addColumn([
            'index'      => 'mode_paiement',
            'label'      => trans('admin::app.depenses.index.datagrid.mode-paiement'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        /*$this->addColumn([
            'index'      => 'user_name',
            'label'      => trans('admin::app.depenses.index.datagrid.user'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => function ($row) {
                if (! $row->user_id) {
                    return 'N/A';
                }
                
                $route = route('admin.settings.users.edit', $row->user_id);
                return "<a href='{$route}' class='text-blue-600 hover:underline'>{$row->user_name}</a>";
            },
        ]);*/

        $this->addColumn([
            'index'      => 'created_at',
            'label'      => trans('admin::app.depenses.index.datagrid.created-at'),
            'type'       => 'date',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => fn ($row) => core()->formatDate($row->created_at, 'd M Y H:i'),
        ]);
    }

    /**
     * Prepare actions.
     */
    public function prepareActions(): void
    {
        if (bouncer()->hasPermission('depenses.edit')) {
            $this->addAction([
                'index'  => 'edit',
                'icon'   => 'icon-edit',
                'title'  => trans('admin::app.depenses.index.datagrid.edit'),
                'method' => 'GET',
                'url'    => fn ($row) => route('admin.depenses.edit', $row->id),
            ]);
        }

        if (bouncer()->hasPermission('depenses.delete')) {
            $this->addAction([
                'index'  => 'delete',
                'icon'   => 'icon-delete',
                'title'  => trans('admin::app.depenses.index.datagrid.delete'),
                'method' => 'DELETE',
                'url'    => fn ($row) => route('admin.depenses.delete', $row->id),
            ]);
        }
    }

    /**
     * Prepare mass actions.
     */
    public function prepareMassActions(): void
    {
        if (bouncer()->hasPermission('depenses.delete')) {
            $this->addMassAction([
                'title'  => trans('admin::app.depenses.index.datagrid.delete'),
                'method' => 'POST',
                'url'    => route('admin.depenses.mass_delete'),
            ]);
        }
    }
}