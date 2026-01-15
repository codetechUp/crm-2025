<?php

namespace Webkul\Order\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\Order\Models\Order::class,
        \Webkul\Order\Models\OrderItem::class,
        \Webkul\Order\Models\OrderStatusHistory::class,
    ];
}
