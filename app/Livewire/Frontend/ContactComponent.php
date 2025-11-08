<?php

namespace App\Livewire\Frontend;

use App\Models\Contact;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ContactComponent extends Component
{
    public $name;
    public $email;
    public $subject;
    public $message;

    /**
     * Validation rules.
     */
    protected function rules(): array
    {
        return [
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * Mount lifecycle hook.
     */
    public function test()
    {
        dd('test');
    }
    public function mount(): void
    {
        // Pre-fill data if user is logged in
        // if (Auth::check()) {
        //     $this->name = Auth::user()->name;
        //     $this->email = Auth::user()->email;
        // }
    }

    /**
     * Handle form submission.
     */
    public function contactSubmit()
    {
        dd('submitted');
        // dd($this->validate());
        // $validated = $this->validate();

        Contact::create($validated);
        $this->reset(['subject', 'message']);
        $this->dispatch('alert', type: 'success', message: 'Your message has been sent successfully.');
        $this->redirectRoute('f.landing', navigate: true);
    }
    public function render()
    {
        // if (Auth::check() && Auth::user()->banned_at === null) {
        //     $this->redirectRoute('user.dashboard', navigate: true);
        // }

        return view('livewire.frontend.contact-component');
    }
}
