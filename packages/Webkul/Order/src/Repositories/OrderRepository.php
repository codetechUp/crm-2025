<?php

namespace Webkul\Order\Repositories;

use Illuminate\Support\Facades\DB;
use Webkul\Core\Eloquent\Repository;
use Webkul\Order\Models\OrderStatusHistoryProxy;

class OrderRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return 'Webkul\Order\Contracts\Order';
    }

    /**
     * Create order with items
     *
     * @param array $data
     * @return \Webkul\Order\Contracts\Order
     */
    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            // Créer la commande
            $order = parent::create([
                'subject'                => $data['subject'],
                'description'            => $data['description'] ?? null,
                'has_production'         => $data['has_production'] ?? false,
                'expected_delivery_date' => $data['expected_delivery_date'] ?? null,
                'sub_total'              => $data['sub_total'] ?? 0,
                'discount_percent'       => $data['discount_percent'] ?? 0,
                'discount_amount'        => $data['discount_amount'] ?? 0,
                'tax_amount'             => $data['tax_amount'] ?? 0,
                'grand_total'            => $data['grand_total'] ?? 0,
                'notes'                  => $data['notes'] ?? null,
                'person_id'              => $data['person_id'],
                'user_id'                => $data['user_id'] ?? auth()->id(),
            ]);

            // Créer les items (optionnel pour le formulaire simplifié)
            if (isset($data['items']) && is_array($data['items']) && count($data['items']) > 0) {
                foreach ($data['items'] as $item) {
                    if (empty($item['product_id'])) {
                        continue;
                    }

                    $sku = $item['sku'] ?? '';
                    
                    $product = app(\Webkul\Product\Repositories\ProductRepository::class)->find($item['product_id']);

                    if (empty($sku)) {
                        $sku = $product ? $product->sku : '';
                    }

                    $name = $product ? $product->name : '';
                    $order->items()->create([
                        'product_id' => $item['product_id'],
                        'name'       => $name,
                        'sku'        => $sku,
                        'quantity'   => $item['quantity'] ?? 1,
                        'price'      => $item['price'] ?? 0,
                        'total'      => $item['total'] ?? 0,
                    ]);
                }
            }

            // Créer l'historique initial
            $this->addStatusHistory($order, $order->status, 'Commande créée');

            DB::commit();

            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update order
     *
     * @param array $data
     * @param int $id
     * @return \Webkul\Order\Contracts\Order
     */
    public function update(array $data, $id)
    {
        DB::beginTransaction();

        try {
            $order = $this->find($id);

            // Mettre à jour la commande
            $order->update([
                'subject'                => $data['subject'],
                'description'            => $data['description'] ?? null,
                'has_production'         => $data['has_production'] ?? false,
                'expected_delivery_date' => $data['expected_delivery_date'] ?? null,
                'sub_total'              => $data['sub_total'] ?? 0,
                'discount_percent'       => $data['discount_percent'] ?? 0,
                'discount_amount'        => $data['discount_amount'] ?? 0,
                'tax_amount'             => $data['tax_amount'] ?? 0,
                'grand_total'            => $data['grand_total'] ?? 0,
                'notes'                  => $data['notes'] ?? null,
                'person_id'              => $data['person_id'],
            ]);

            // Supprimer les anciens items et recréer
            if (isset($data['items']) && is_array($data['items'])) {
                $order->items()->delete();

                foreach ($data['items'] as $item) {
                    if (empty($item['product_id'])) {
                        continue;
                    }

                    $sku = $item['sku'] ?? '';
                    
                    $product = app(\Webkul\Product\Repositories\ProductRepository::class)->find($item['product_id']);

                    if (empty($sku)) {
                        $sku = $product ? $product->sku : '';
                    }

                    $name = $product ? $product->name : '';
                    $order->items()->create([
                        'product_id' => $item['product_id'],
                        'name'       => $name,
                        'sku'        => $sku,
                        'quantity'   => $item['quantity'] ?? 1,
                        'price'      => $item['price'] ?? 0,
                        'total'      => $item['total'] ?? 0,
                    ]);
                }
            }

            DB::commit();

            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update order status
     *
     * @param int $id
     * @param string $status
     * @param string|null $notes
     * @return \Webkul\Order\Contracts\Order
     */
    public function updateStatus($id, $status, $notes = null)
    {
        $order = $this->find($id);
        
        $oldStatus = $order->status;
        $order->update(['status' => $status]);

        // Si le statut est "delivered", enregistrer la date de livraison
        if ($status === 'delivered' && !$order->actual_delivery_date) {
            $order->update(['actual_delivery_date' => now()]);
        }

        // Ajouter à l'historique
        $this->addStatusHistory($order, $status, $notes);

        return $order;
    }

    /**
     * Add status to history
     *
     * @param \Webkul\Order\Contracts\Order $order
     * @param string $status
     * @param string|null $notes
     * @return void
     */
    protected function addStatusHistory($order, $status, $notes = null)
    {
        OrderStatusHistoryProxy::create([
            'order_id'   => $order->id,
            'status'     => $status,
            'notes'      => $notes,
            'changed_by' => auth()->id(),
            'changed_at' => now(),
        ]);
    }

    /**
     * Get delayed orders
     *
     * @param array $exclude
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDelayedOrders($exclude = [])
    {
        return $this->model
            ->where('status', '!=', 'delivered')
            ->whereNotNull('expected_delivery_date')
            ->where('expected_delivery_date', '<', now())
            ->whereNotIn('id', $exclude)
            ->get();
    }

    /**
     * Get orders at risk (3 days before expected delivery)
     *
     * @param array $exclude
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getOrdersAtRisk($exclude = [])
    {
        $threeDaysFromNow = now()->addDays(3);

        return $this->model
            ->where('status', '!=', 'delivered')
            ->whereNotNull('expected_delivery_date')
            ->whereBetween('expected_delivery_date', [now(), $threeDaysFromNow])
            ->whereNotIn('id', $exclude)
            ->get();
    }
}
