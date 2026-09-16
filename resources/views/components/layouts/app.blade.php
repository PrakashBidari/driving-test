{{-- Thin wrapper so plain (non-Livewire) pages like the profile screen can use
     <x-layouts.app> while sharing the exact same shell Livewire pages get via
     the "layouts::app" component_layout in resources/views/layouts/app.blade.php. --}}
@include('layouts.app', ['title' => $title ?? null, 'slot' => $slot])
