<?php

namespace App\View\Components\Admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Header extends Component
{
    public $active;

    public function __construct($active = null)
    {
        $this->active = $active;
    }
    public function render(): View|Closure|string
    {
        return view('backend.admin.layouts.partials.header');
    }
}
