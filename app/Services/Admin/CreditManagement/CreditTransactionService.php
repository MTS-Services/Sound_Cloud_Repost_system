<?php

namespace App\Services\Admin\CreditManagement;

use App\Models\CreditTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        return CreditTransaction::where($orderBy, $order)->latest();
    }

    public function getUserTotalCredits()
    {
        $total_credits = 0;
        if (Auth::check()) {
            $user = Auth::user();

            $urnValue = $user->urn;
            $total_credits = CreditTransaction::where('receiver_urn', $urnValue)
                ->sum('credits');
            $total_credits = number_format($total_credits, 0);
        }

        return $total_credits;
    }
}
