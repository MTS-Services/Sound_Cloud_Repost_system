<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Button extends Component
{


    public function render(): View|Closure|string
    {
        return view('components.button');
    }

}



// // Basic usage
// <x-gbutton>Click Me</x-gbutton>

// // With variants
// <x-gbutton variant="primary">Primary</x-gbutton>
// <x-gbutton variant="secondary">Secondary</x-gbutton>
// <x-gbutton variant="outline">Outline</x-gbutton>
// <x-gbutton variant="text">Text</x-gbutton>

// // With sizes
// <x-gbutton size="sm">Small</x-gbutton>
// <x-gbutton size="md">Medium</x-gbutton>
// <x-gbutton size="lg">Large</x-gbutton>

// // Full width
// <x-gbutton :full-width="true">Full Width</x-gbutton>

// // With additional attributes
// <x-gbutton onclick="myFunction()" class="my-custom-class">Custom Button</x-gbutton>

// // Disabled state
// <x-gbutton :disabled="true">Disabled</x-gbutton>

// // Form submit button
// <x-gbutton type="submit" variant="primary">Submit</x-gbutton>
