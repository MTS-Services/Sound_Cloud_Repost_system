<?php

namespace App\Services\Admin\CreditManagement;

use App\Models\CreditTransaction;
use App\Models\Track;
use Illuminate\Support\Facades\Auth;

class CreditTransactionService
{
    public function getTransactions($orderBy = 'id', $order = 'asc')
    {
        return CreditTransaction::orderBy($orderBy, $order)->latest()->get();
    }

    public function getUserTotalCredits()
    {
        if (user()) {
            return number_format(CreditTransaction::where('receiver_urn', user()->urn)->sum('credits'), 0);
        }

        return '0';
    }

    public function getUserTransactions()
    {
        if (user()) {
            return CreditTransaction::where('receiver_urn', user()->urn)
                ->orWhere('sender_urn', user()->urn)
                ->latest()
                ->get();
        }

        return collect();
    }

    public function getUserTracks()
    {
        
      if(user()){ 
        return Track::where('user_urn', user()->urn)->latest()->get();
      }
            return collect();
       

    }
    public function getPurchase($orderBy = 'id', $order = 'asc')
    {
        return CreditTransaction::where($orderBy, $order)->with('receiver')->purchase()->latest();
    }
}
