<?php

namespace App\Services\Admin\CreditManagement;

use App\Models\CreditTransaction;
use App\Models\Track;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;
use App\Models\Credit;

class CreditTransactionService
{
    public function getTransactions($orderBy = 'id', $order = 'asc')
    {
        return CreditTransaction::orderBy($orderBy, $order)->latest();
    }
    public function getTransaction(string $encryptedValue, string $field = 'id'): CreditTransaction | Collection
    {
        return CreditTransaction::where($field, decrypt($encryptedValue))->first();
    }
    public function toggleStatus(CreditTransaction $credit): void
    {
        $credit->status = $credit->status === 'processing' ? 'success' : 'cancelled';
        $credit->save();
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

        if (user()) {
            return Track::where('user_urn', user()->urn)->latest()->get();
        }
        return collect();
    }
    public function getPurchase($orderBy = 'id', $order = 'asc')
    {
        return CreditTransaction::where($orderBy, $order)->with('receiver')->purchase()->latest();
    }


     public static function getWeeklyChangeByUrn(string $userUrn, int $days = 3): float
    {
        // Current range: today + previous ($days - 1) days
        $currentStart = Carbon::today()->subDays($days - 1);
        $currentEnd   = Carbon::today()->endOfDay();

        $currentSum = Credit::where('id', $userUrn)
            ->whereBetween('created_at', [$currentStart, $currentEnd])
            ->sum('credits');

        // Same range last week
        $lastWeekStart = $currentStart->copy()->subWeek();
        $lastWeekEnd   = $currentEnd->copy()->subWeek();

        $lastWeekSum = Credit::where('id', $userUrn)
            ->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])
            ->sum('credits');

        // Calculate percentage change
        if ($lastWeekSum > 0) {
            return round((($currentSum - $lastWeekSum) / $lastWeekSum) * 100, 2);
        }

        return $currentSum > 0 ? 100.0 : 0.0;
    }
}
