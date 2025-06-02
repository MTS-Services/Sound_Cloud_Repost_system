<a
    {{ $attributes->merge(['href' => '', 'title' => '', 'target' => '_self', 'class' => 'inline-flex items-center capitalize text-sm font-medium  transition-all text-text-light-gray-primary hover:text-text-light-primary transition-all ease-in-out duration-300']) }}>
    {{ $slot }}
</a>
