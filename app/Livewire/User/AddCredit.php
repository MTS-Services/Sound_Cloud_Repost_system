<?php

namespace App\Livewire\User;

use App\Models\Credit;
use App\Services\Admin\OrderManagement\OrderService;
use App\Services\Admin\PackageManagement\CreditService;
use App\Services\SoundCloud\SoundCloudService;
use Livewire\Component;

class AddCredit extends Component
{
    protected CreditService $creditService;
    protected OrderService $orderService;
    protected SoundCloudService $soundCloudService;
    public function boot(CreditService $creditService, OrderService $orderService, SoundCloudService $soundCloudService)
    {
        $this->creditService = $creditService;
        $this->orderService = $orderService;
        $this->soundCloudService = $soundCloudService;
    }

    public function mount()
    {
        $data =$this->soundCloudService->userTracksCount(user());
        dd($data);
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
