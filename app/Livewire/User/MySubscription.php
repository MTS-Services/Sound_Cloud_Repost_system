<?php

namespace App\Livewire\User;

use App\Models\Payment;
use App\Models\UserPlan;
use App\Services\SoundCloud\SoundCloudService;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class MySubscription extends Component
{
    use WithPagination;

    public UserPlan|null $userPlan = null;
    public bool $showCancelModal = false;

    protected $listeners = ['confirmCancelSubscription' => 'cancelSubscription'];

    protected SoundCloudService $soundCloudService;

    public function mount()
    {
        $this->soundCloudService->refreshUserTokenIfNeeded(user());
        $this->loadUserPlan();
    }

    public function updated()
    {
        $this->soundCloudService->refreshUserTokenIfNeeded(user());
    }

    /**
     * Load active subscription for authenticated user.
     */
    private function loadUserPlan(): void
    {
        $this->userPlan = UserPlan::query()
            ->with(['user', 'plan', 'order'])
            ->where('user_urn', user()->urn)
            ->where('status', UserPlan::STATUS_ACTIVE)
            ->latest()
            ->first();
    }


    #[On('confirmCancelSubscription')]
    public function cancelSubscription(): void
    {
        if (!$this->userPlan) {
            session()->flash('error', 'You do not have an active subscription.');
            $this->showCancelModal = false;
            return;
        }

        // Example: call your service or internal logic
        $this->userPlan->update([
            'auto_renew' => false,
            'canceled_at' => now(),
        ]);

        $this->showCancelModal = false;

        $this->loadUserPlan();
        session()->flash('success', 'Your subscription auto-renew has been cancelled.');
    }

    public function render()
    {
        $payments = [];

        if ($this->userPlan) {
            $payments = Payment::query()
                ->where('order_id', $this->userPlan->order_id)
                ->where('status', 'succeeded')
                ->latest()
                ->paginate(10);
        }

        return view('livewire.user.my-subscription', [
            'payments' => $payments,
        ]);
    }
}
