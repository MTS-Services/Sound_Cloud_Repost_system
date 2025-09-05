<?php

namespace App\Livewire\User;

use App\Http\Requests\Admin\OrderManagement\OrderRequest;
use App\Models\Credit;
use App\Services\Admin\OrderManagement\OrderService;
use App\Services\Admin\PackageManagement\CreditService;
use Livewire\Component;

class AddCredit extends Component
{
    protected CreditService $creditService;
    protected OrderService $orderService;
    public function boot(CreditService $creditService, OrderService $orderService)
    {
        $this->creditService = $creditService;
        $this->orderService = $orderService;
    }

    public function buyCredits($envryptedCreditId)
    {
        try {
            $credit = $this->creditService->getCredit($envryptedCreditId);

            $validated['source_id'] = $credit->id;
            $validated['source_type'] = Credit::class;
            $validated['amount'] = $credit->price;
            $validated['credits'] = $credit->credits;
            $order = $this->orderService->createOrder($validated);
            // return redirect()->route('user.payment.method', encrypt($order->id));
            $this->redirectRoute('user.payment.method', encrypt($order->id), navigate: true);
        } catch (\Exception $e) {
            $this->dispatch('alert', type: 'error', message: $e->getMessage());
        }
    }
    public function render()
    {
        $data['activeCredits'] = $this->creditService->getCredits()->active()->get();
        return view('livewire.user.add-credit', $data);
    }
}
