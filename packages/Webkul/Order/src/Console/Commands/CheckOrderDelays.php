<?php

namespace Webkul\Order\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use Webkul\Order\Notifications\OrderDelayNotification;
use Webkul\Order\Repositories\OrderRepository;
use Webkul\User\Models\User;

class CheckOrderDelays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:check-delays';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérifier les commandes en retard ou à risque et envoyer des alertes';

    /**
     * Order repository instance.
     *
     * @var \Webkul\Order\Repositories\OrderRepository
     */
    protected $orderRepository;

    /**
     * Create a new command instance.
     *
     * @param  \Webkul\Order\Repositories\OrderRepository  $orderRepository
     * @return void
     */
    public function __construct(OrderRepository $orderRepository)
    {
        parent::__construct();

        $this->orderRepository = $orderRepository;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Vérification des commandes en retard...');

        // Récupérer les commandes en retard
        $delayedOrders = $this->orderRepository->getDelayedOrders();
        
        // Récupérer les commandes à risque
        $atRiskOrders = $this->orderRepository->getOrdersAtRisk();

        $totalAlerts = $delayedOrders->count() + $atRiskOrders->count();

        if ($totalAlerts === 0) {
            $this->info('Aucune commande en retard ou à risque trouvée.');
            return 0;
        }

        // Récupérer les utilisateurs admin/direction pour les notifications
        $admins = User::whereHas('role', function ($query) {
            $query->where('name', 'Administrator');
        })->get();

        if ($admins->isEmpty()) {
            // Si pas d'admin spécifique, notifier tous les utilisateurs
            $admins = User::all();
        }

        // Envoyer les notifications pour les commandes en retard
        if ($delayedOrders->count() > 0) {
            $this->warn("Commandes en retard trouvées: {$delayedOrders->count()}");
            
            foreach ($delayedOrders as $order) {
                $this->line("  - Commande #{$order->order_number}: {$order->subject}");
                
                Notification::send($admins, new OrderDelayNotification($order, 'delayed'));
            }
        }

        // Envoyer les notifications pour les commandes à risque
        if ($atRiskOrders->count() > 0) {
            $this->warn("Commandes à risque trouvées: {$atRiskOrders->count()}");
            
            foreach ($atRiskOrders as $order) {
                $this->line("  - Commande #{$order->order_number}: {$order->subject}");
                
                Notification::send($admins, new OrderDelayNotification($order, 'at_risk'));
            }
        }

        $this->info("Total des alertes envoyées: {$totalAlerts}");

        return 0;
    }
}
