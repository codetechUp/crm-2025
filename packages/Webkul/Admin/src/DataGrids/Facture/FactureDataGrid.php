<?php

namespace Webkul\Admin\DataGrids\Facture;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class FactureDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     */
    public function prepareQueryBuilder(): Builder
    {
        $tablePrefix = DB::getTablePrefix();

        $queryBuilder = DB::table('quotes')
         ->where('quotes.type', 'facture') 
            ->addSelect(
                'quotes.id',
                'quotes.subject',
                'quotes.expired_at',
                'quotes.sub_total',
                'quotes.discount_amount',
                'quotes.tax_amount',
                'quotes.adjustment_amount',
                'quotes.grand_total',
                'quotes.acompte', // Ajout du champ acompte
                DB::raw('(quotes.grand_total - quotes.acompte) as reste_a_payer'), // Calcul du reste à payer
                'quotes.created_at',
                'users.id as user_id',
                'users.name as sales_person',
                'persons.id as person_id',
                'persons.name as person_name',
                'quotes.expired_at as expired_quotes'
            )
            ->leftJoin('users', 'quotes.user_id', '=', 'users.id')
            ->leftJoin('persons', 'quotes.person_id', '=', 'persons.id');

        if ($userIds = bouncer()->getAuthorizedUserIds()) {
            $queryBuilder->whereIn('quotes.user_id', $userIds);
        }

        $this->addFilter('id', 'quotes.id');
        $this->addFilter('user', 'quotes.user_id');
        $this->addFilter('sales_person', 'users.name');
        $this->addFilter('person_name', 'persons.name');
        $this->addFilter('expired_at', 'quotes.expired_at');
        $this->addFilter('created_at', 'quotes.created_at');
        $this->addFilter('acompte', 'quotes.acompte');
        $this->addFilter('reste_a_payer', DB::raw('(quotes.grand_total - quotes.acompte)'));

        if (request()->input('expired_quotes.in') == 1) {
            $this->addFilter('expired_quotes', DB::raw('DATEDIFF(NOW(), '.$tablePrefix.'quotes.expired_at) >= '.$tablePrefix.'NOW()'));
        } else {
            $this->addFilter('expired_quotes', DB::raw('DATEDIFF(NOW(), '.$tablePrefix.'quotes.expired_at) < '.$tablePrefix.'NOW()'));
        }

        return $queryBuilder;
    }

    /**
     * Prepare columns.
     */
    public function prepareColumns(): void
    {
        $this->addColumn([
            'index'      => 'subject',
            'label'      => trans('admin::app.quotes.index.datagrid.subject'),
            'type'       => 'string',
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'              => 'sales_person',
            'label'              => trans('admin::app.quotes.index.datagrid.sales-person'),
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
            'index'              => 'person_name',
            'label'              => trans('admin::app.quotes.index.datagrid.person'),
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

                return "<a class=\"text-brandColor transition-all hover:underline\" href='".$route."'>".$row->person_name.'</a>';
            },
        ]);

        

       

        

        $this->addColumn([
            'index'      => 'grand_total',
            'label'      => trans('admin::app.quotes.index.datagrid.grand-total'),
            'type'       => 'string',
            'sortable'   => true,
            'filterable' => true,
            'closure'    => fn ($row) => core()->formatBasePrice($row->grand_total, 2),
        ]);

        $this->addColumn([
            'index'      => 'acompte',
            'label'      => 'Acompte',
            'type'       => 'string',
            'sortable'   => true,
            'filterable' => true,
            'closure'    => function ($row) {
                // Afficher l'acompte seulement s'il est > 0
                if ($row->acompte > 0) {
                    return core()->formatBasePrice($row->acompte, 2);
                }
                return '<span class="text-gray-400">-</span>';
            },
        ]);

      

         $this->addColumn([
            'index'      => 'reste_a_payer',
            'label'      => 'Reste à payer',
            'type'       => 'string',
            'closure'    => function ($row) {
                $reste = $row->reste_a_payer;
                $acompte  = (int) preg_replace('/\D/', '', $row->acompte);
                // Si l'acompte est > 0, afficher le reste à payer
                if ($row->acompte != '<span class="text-gray-400">-</span>') {
                    if ($reste == 0) {
                        return '<span class="text-green-600 font-semibold">'.core()->formatBasePrice($reste, 2).'</span>';
                    } else {
                        return core()->formatBasePrice($reste, 2);
                    }
                }
                // Si aucun acompte, afficher un tiret
                return '<span class="text-gray-400">-</span>';
            },
        ]);

        $this->addColumn([
            'index'      => 'created_at',
            'label'      => trans('admin::app.quotes.index.datagrid.created-at'),
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
        if (bouncer()->hasPermission('quotes.edit')) {
            $this->addAction([
                'index'  => 'edit',
                'icon'   => 'icon-edit',
                'title'  => trans('admin::app.quotes.index.datagrid.edit'),
                'method' => 'GET',
                'url'    => fn ($row) => route('admin.factures.edit', $row->id),
            ]);
        }

        if (bouncer()->hasPermission('quotes.print')) {
            $this->addAction([
                'index'  => 'print',
                'icon'   => 'icon-print',
                'title'  => trans('admin::app.quotes.index.datagrid.print'),
                'method' => 'GET',
                'url'    => fn ($row) => route('admin.quotes.print', $row->id),
            ]);
        }

        if (bouncer()->hasPermission('quotes.delete')) {
            $this->addAction([
                'index'  => 'delete',
                'icon'   => 'icon-delete',
                'title'  => trans('admin::app.quotes.index.datagrid.delete'),
                'method' => 'DELETE',
                'url'    => fn ($row) => route('admin.quotes.delete', $row->id),
            ]);
        }
    }

    /**
     * Prepare mass actions.
     */
    public function prepareMassActions(): void
    {
        $this->addMassAction([
            'icon'   => 'icon-delete',
            'title'  => trans('admin::app.quotes.index.datagrid.delete'),
            'method' => 'POST',
            'url'    => route('admin.quotes.mass_delete'),
        ]);

        $this->addMassAction([
            'icon'   => 'icon-delete',
            'title'  => trans('admin::app.quotes.index.datagrid.delete'),
            'method' => 'POST',
            'url'    => route('admin.quotes.mass_delete'),
        ]);
    }
}