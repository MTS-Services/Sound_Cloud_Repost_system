<?php

namespace App\Services\Admin\CreditManagement;

use App\Models\CreditTransaction;

class CreditTransactionService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public function getPurchase($orderBy = 'id', $order = 'asc')
    {
        return CreditTransaction::where($orderBy, $order)->purchase()->latest();
    }
}
