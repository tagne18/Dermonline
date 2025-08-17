@props([
    'icon' => null, // SVG ou URL
    'title',
    'subtitle' => null,
    'actionUrl' => null,
    'actionLabel' => null,
    'actionTarget' => '_blank',
])
<div class="pharmacy-card">
    <span class="icon">
        @if($icon)
            {!! $icon !!}
        @else
            <svg class='w-6 h-6 text-green-500' fill='none' stroke='#24b47e' viewBox='0 0 24 24'><circle cx='12' cy='12' r='10' stroke='#24b47e' stroke-width='2' fill='#e5faf3'/><path d='M12 8v8M8 12h8' stroke='#24b47e' stroke-width='2' stroke-linecap='round'/></svg>
        @endif
    </span>
    <div>
        <div class="font-bold text-gray-800">{{ $title }}</div>
        @if($subtitle)
            <div class="text-gray-500 text-sm">{{ $subtitle }}</div>
        @endif
    </div>
    @if($actionUrl && $actionLabel)
        <a href="{{ $actionUrl }}" target="{{ $actionTarget }}" class="itineraire">{{ $actionLabel }}</a>
    @endif
</div>
