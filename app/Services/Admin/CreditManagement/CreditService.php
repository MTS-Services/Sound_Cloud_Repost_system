<?php

namespace App\Services\Admin\CreditManagement;


use App\Models\CreditTransaction;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreditService
{
    public function addCredit(array $data): Payment|null
    {
        try {
            return DB::transaction(function () use ($data) {
                $order = Order::create([
                    'user_urn' => $data['receiver_urn'],
                    'order_id' => generateOrderID(),
                    'source_id' => $data['source_id'],
                    'source_type' => $data['source_type'],
                    'credits' => $data['credits'],
                    'amount' => $data['amount'],
                    'type' => $data['order_type'],
                    'creater_id' => $data['creater_id'],
                    'creater_type' => $data['creater_type'],
                ]);

                CreditTransaction::create([
                    'receiver_urn' => $data['receiver_urn'],
                    'sender_urn' => $data['sender_urn'] ?? null,
                    'calculation_type' => $data['calculation_type'],
                    'source_id' => $order->id,
                    'source_type' => Order::class,
                    'transaction_type' => $data['transaction_type'],
                    'amount' => $data['amount'],
                    'credits' => $data['credits'],
                    'description' => $data['description'] ?? null,
                    'creater_id' => $data['creater_id'],
                    'creater_type' => $data['creater_type'],

                ]);

                $Payment = Payment::create([
                    'name' => $data['paid_by'] ?? 'System',
                    'email_address' => $data['email_address'] ?? null,
                    'address' => $data['address'] ?? null,
                    'postal_code' => $data['postal_code'] ?? null,
                    'reference' => $data['reference'] ?? null,
                    'user_urn' => $data['receiver_urn'],
                    'order_id' => $order->id,
                    'payment_method' => $data['payment_method'] ?? null,

                    'payment_gateway' => $data['payment_gateway'] ?? Payment::PAYMENT_GATEWAY_UNKNOWN,
                    'payment_provider_id' => $data['payment_provider_id'] ?? null,
                    'amount' => $data['amount'],
                    'credits_purchased' => $data['credits'],
                    'status' => $data['payment_status'],
                    'payment_intent_id' => $data['payment_intent_id'] ?? null,
                    'receipt_url' => $data['receipt_url'] ?? null,
                    'failure_reason' => $data['failure_reason'] ?? null,
                    'metadata' => $data['metadata'] ?? null,
                    'processed_at' => $data['processed_at'] ?? null,
                    'creater_id' => $data['creater_id'],
                    'creater_type' => $data['creater_type'],

                ]);

                return $Payment;
            });
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return null;
        }




    }

}
