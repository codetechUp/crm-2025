<?php

namespace Webkul\Depense\Repositories;

use Illuminate\Container\Container;
use Illuminate\Support\Facades\Event;
use Webkul\Core\Eloquent\Repository;
use Webkul\Depense\Models\Depense as DepenseModel; // Import direct

class DepenseRepository extends Repository
{
    /**
     * Create a new repository instance.
     *
     * @param  \Illuminate\Container\Container  $container
     * @return void
     */
    public function __construct(Container $container)
    {
        parent::__construct($container);
    }

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return DepenseModel::class;
    }

    // ... reste des méthodes
}