@props([
    'name',
    'address',
    'itineraire',
    'icon' => null
])
<div class="card mb-3 shadow-sm" style="max-width: 100%; min-width: 220px;">
  <div class="row g-0 align-items-center">
    <div class="col-auto px-3 py-2">
      @if($icon)
        {!! $icon !!}
      @else
        <svg width="28" height="28" fill="none" stroke="#24b47e" viewBox="0 0 24 24">
          <circle cx="12" cy="12" r="10" stroke="#24b47e" stroke-width="2" fill="#e5faf3"/>
          <path d="M12 8v8M8 12h8" stroke="#24b47e" stroke-width="2" stroke-linecap="round"/>
        </svg>
      @endif
    </div>
    <div class="col">
      <div class="card-body py-2 px-2">
        <h5 class="card-title mb-1" style="font-size:1.08rem">{{ $name }}</h5>
        <p class="card-text mb-2" style="font-size:0.94rem; color:#555">{{ $address }}</p>
        <a href="{{ $itineraire }}" target="_blank" class="btn btn-success btn-sm">Itinéraire</a>
      </div>
    </div>
  </div>
</div>
