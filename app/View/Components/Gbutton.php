<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Gbutton extends Component
{

    public string $variant;
    public string $size;
    public bool $fullWidth;
    public string $type;
    public bool $disabled;
    /**
     * Create a new component instance.
     */
    public function __construct(
        string $variant = 'primary',
        string $size = 'md',
        bool $fullWidth = false,
        string $type = 'button',
        bool $disabled = false
    ) {
        $this->variant = $variant;
        $this->size = $size;
        $this->fullWidth = $fullWidth;
        $this->type = $type;
        $this->disabled = $disabled;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.gbutton');
    }

    public function getButtonClasses(): string
    {
        $baseStyles = 'font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 rounded-lg inline-flex items-center justify-center cursor-pointer disabled:cursor-not-allowed';

        $variantStyles = [
            'primary' => 'bg-orange-600 text-white hover:bg-orange-500 active:bg-orange-700 disabled:bg-orange-500 disabled:text-gray-50 disabled:cursor-not-allowed',
            'secondary' => 'bg-orange-100 text-orange-800 hover:bg-orange-200 active:bg-orange-300 disabled:bg-gray-100 disabled:text-gray-400',
            'outline' => 'border border-orange-500 text-orange-500 hover:bg-orange-50 active:bg-orange-100 disabled:border-gray-300 disabled:text-gray-400',
            'text' => 'text-orange-500 hover:bg-orange-50 active:bg-orange-100 disabled:text-gray-400',
        ];

        $sizeStyles = [
            'sm' => 'px-3 py-1.5 text-sm',
            'md' => 'px-4 py-2 text-base',
            'lg' => 'px-6 py-3 text-lg',
        ];

        $widthStyles = $this->fullWidth ? 'w-full' : '';

        return trim("{$baseStyles} {$variantStyles[$this->variant]} {$sizeStyles[$this->size]} {$widthStyles}");
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
