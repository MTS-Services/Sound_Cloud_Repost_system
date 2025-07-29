<?php

namespace App\Services\Admin\OrderManagement;

use App\Models\Order;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Distributions\F;

class OrderService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getOrders($orderBy = 'sort_order', $order = 'asc')
    {
        return Order::orderBy($orderBy, $order)->latest();
    }
    public function getOrder(string $encryptedId)
    {
        return Order::where('id', decrypt($encryptedId))->first();
    }
    public function createOrder(array $data)
    {
        $data['user_urn'] = user()->urn;
        $data['creater_id'] = user()->id;
        return Order::create($data);
    }
    public function toggleStatusS(Order $order)
    {
        // logic to toggle the order status
        $order->status = $order->status === 'active' ? 'inactive' : 'active';
        $order->save();
    }
      public function toggleStatus(Order $order): void
    {
        $order->update([
            'status' => !$order->status,
            'updater_id' => admin()->id,
            'updater_type' => get_class(admin())
        ]);
    }

}
