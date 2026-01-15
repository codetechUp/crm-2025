<?php

namespace Webkul\Admin\DataGrids\Order;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class OrderDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     */
    public function prepareQueryBuilder(): Builder
    {
        $queryBuilder = DB::table('orders')
            ->addSelect(
                'orders.id',
                'orders.order_number',
                'orders.subject',
                'orders.status',
                'orders.has_production',
                'orders.expected_delivery_date',
                'orders.grand_total',
                'orders.created_at',
                'users.id as user_id',
                'users.name as sales_person',
                'persons.id as person_id',
                'persons.name as person_name'
            )
            ->leftJoin('users', 'orders.user_id', '=', 'users.id')
            ->leftJoin('persons', 'orders.person_id', '=', 'persons.id');

        if ($userIds = bouncer()->getAuthorizedUserIds()) {
            $queryBuilder->whereIn('orders.user_id', $userIds);
        }

        $this->addFilter('id', 'orders.id');
        $this->addFilter('order_number', 'orders.order_number');
        $this->addFilter('subject', 'orders.subject');
        $this->addFilter('status', 'orders.status');
        $this->addFilter('sales_person', 'users.name');
        $this->addFilter('person_name', 'persons.name');
        $this->addFilter('expected_delivery_date', 'orders.expected_delivery_date');
        $this->addFilter('created_at', 'orders.created_at');

        return $queryBuilder;
    }

    /**
     * Prepare columns.
     */
    public function prepareColumns(): void
    {
        $this->addColumn([
            'index'      => 'order_number',
            'label'      => trans('admin::app.orders.index.datagrid.order-number'),
            'type'       => 'string',
            'filterable' => true,
            'sortable'   => true,
            'closure'    => function ($row) {
                $route = route('admin.orders.show', $row->id);
                return "<a class=\"text-brandColor transition-all hover:underline\" href='{$route}'>{$row->order_number}</a>";
            },
        ]);

        $this->addColumn([
            'index'      => 'subject',
            'label'      => trans('admin::app.orders.index.datagrid.subject'),
            'type'       => 'string',
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'              => 'person_name',
            'label'              => trans('admin::app.orders.index.datagrid.person'),
            'type'               => 'string',
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
            'closure'    => function ($row) {
                $route = route('admin.contacts.persons.view', $row->person_id);
                return "<a class=\"text-brandColor transition-all hover:underline\" href='{$route}'>{$row->person_name}</a>";
            },
        ]);

        $this->addColumn([
            'index'              => 'sales_person',
            'label'              => trans('admin::app.orders.index.datagrid.sales-person'),
            'type'               => 'string',
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

        $this->addColumn([
            'index'              => 'status',
            'label'              => trans('admin::app.orders.index.datagrid.status'),
            'type'               => 'string',
            'sortable'           => true,
            'filterable'         => true,
            'filterable_type'    => 'dropdown',
            'filterable_options' => [
                [
                    'label' => trans('admin::app.orders.statuses.received'),
                    'value' => 'received',
                ],
                [
                    'label' => trans('admin::app.orders.statuses.registered'),
                    'value' => 'registered',
                ],
                [
                    'label' => trans('admin::app.orders.statuses.preparing'),
                    'value' => 'preparing',
                ],
                [
                    'label' => trans('admin::app.orders.statuses.processing'),
                    'value' => 'processing',
                ],
                [
                    'label' => trans('admin::app.orders.statuses.production'),
                    'value' => 'production',
                ],
                [
                    'label' => trans('admin::app.orders.statuses.finishing'),
                    'value' => 'finishing',
                ],
                [
                    'label' => trans('admin::app.orders.statuses.executed'),
                    'value' => 'executed',
                ],
                [
                    'label' => trans('admin::app.orders.statuses.delivered'),
                    'value' => 'delivered',
                ],
            ],
            'closure'    => function ($row) {
                $statusColors = [
                    'received'   => 'bg-blue-100 text-blue-800',
                    'registered' => 'bg-blue-100 text-blue-800',
                    'preparing'  => 'bg-yellow-100 text-yellow-800',
                    'processing' => 'bg-yellow-100 text-yellow-800',
                    'production' => 'bg-orange-100 text-orange-800',
                    'finishing'  => 'bg-purple-100 text-purple-800',
                    'executed'   => 'bg-purple-100 text-purple-800',
                    'delivered'  => 'bg-green-100 text-green-800',
                ];

                $color = $statusColors[$row->status] ?? 'bg-gray-100 text-gray-800';
                $label = trans("admin::app.orders.statuses.{$row->status}");

                return "<span class=\"inline-flex rounded-full px-2 py-1 text-xs font-semibold {$color}\">{$label}</span>";
            },
        ]);

        $this->addColumn([
            'index'      => 'expected_delivery_date',
            'label'      => trans('admin::app.orders.index.datagrid.expected-delivery'),
            'type'       => 'date',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => fn ($row) => $row->expected_delivery_date ? core()->formatDate($row->expected_delivery_date, 'd M Y') : '-',
        ]);

        $this->addColumn([
            'index'      => 'grand_total',
            'label'      => trans('admin::app.orders.index.datagrid.grand-total'),
            'type'       => 'string',
            'sortable'   => true,
            'filterable' => true,
            'closure'    => fn ($row) => core()->formatBasePrice($row->grand_total, 2),
        ]);

        $this->addColumn([
            'index'      => 'created_at',
            'label'      => trans('admin::app.orders.index.datagrid.created-at'),
            'type'       => 'date',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => fn ($row) => core()->formatDate($row->created_at),
        ]);
    }

    /**
     * Prepare actions.
     */
    public function prepareActions(): void
    {
        if (bouncer()->hasPermission('orders.view')) {
            $this->addAction([
                'index'  => 'view',
                'icon'   => 'icon-eye',
                'title'  => trans('admin::app.orders.index.datagrid.view'),
                'method' => 'GET',
                'url'    => fn ($row) => route('admin.orders.show', $row->id),
            ]);
        }

        if (bouncer()->hasPermission('orders.edit')) {
            $this->addAction([
                'index'  => 'edit',
                'icon'   => 'icon-edit',
                'title'  => trans('admin::app.orders.index.datagrid.edit'),
                'method' => 'GET',
                'url'    => fn ($row) => route('admin.orders.edit', $row->id),
            ]);
        }

        if (bouncer()->hasPermission('orders.delete')) {
            $this->addAction([
                'index'  => 'delete',
                'icon'   => 'icon-delete',
                'title'  => trans('admin::app.orders.index.datagrid.delete'),
                'method' => 'DELETE',
                'url'    => fn ($row) => route('admin.orders.delete', $row->id),
            ]);
        }
    }

    /**
     * Prepare mass actions.
     */
    public function prepareMassActions(): void
    {
        if (bouncer()->hasPermission('orders.delete')) {
            $this->addMassAction([
                'icon'   => 'icon-delete',
                'title'  => trans('admin::app.orders.index.datagrid.delete'),
                'method' => 'POST',
                'url'    => route('admin.orders.mass_delete'),
            ]);
        }
    }
}
