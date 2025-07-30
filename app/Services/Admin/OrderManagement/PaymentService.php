<?php

namespace App\Services\Admin\OrderManagement;

use App\Models\Payment;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Distributions\F;

class PaymentService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getPayments($orderBy = 'sort_order', $order = 'asc')
    {
        return Payment::orderBy($orderBy, $order)->latest();
    }
    public function getPayment(string $encryptedId)
    {
        return Payment::where('id', decrypt($encryptedId))->first();
    }
    public function createPayment(array $data)
    {
       
        $data['creater_id'] = user()->id;
        return Payment::create($data);
    }
    public function toggleStatusS(Payment $payment)
    {
        // logic to toggle the payment status
        $payment->status = $payment->status === 'active' ? 'inactive' : 'active';
        $payment->save();
    }
      public function toggleStatus(Payment $payment): void
    {
        $payment->update([
            'status' => !$payment->status,
            'updater_id' => admin()->id,
            'updater_type' => get_class(admin())
        ]);
    }

}
