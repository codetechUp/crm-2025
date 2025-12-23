<?php

namespace Webkul\Depense\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\Depense\Models\Depense::class,
    ];
}