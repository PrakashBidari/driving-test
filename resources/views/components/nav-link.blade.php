@props(['href', 'active' => false])

<a href="{{ $href }}"
   @click="sidebarOpen = false"
   {{ $attributes->class([
        'flex items-center gap-3 rounded-md px-3 py-2.5 font-medium transition',
        'bg-white/15 text-white' => $active,
        'text-brand-100/90 hover:bg-white/10 hover:text-white' => ! $active,
   ]) }}>
    <span class="text-base leading-none">{{ $icon }}</span>
    <span class="truncate">{{ $slot }}</span>
</a>
