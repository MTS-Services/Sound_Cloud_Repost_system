<?php

namespace App\View\Components\Admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Header extends Component
{
    public $active;
    public $breadcrumb;

    public function __construct($active = null ,$breadcrumb = null)
    {
        $this->active = $active;
        $this->breadcrumb = $breadcrumb;
    }
    public function render(): View|Closure|string
    {
        return view('backend.admin.layouts.partials.header');
    }
}
