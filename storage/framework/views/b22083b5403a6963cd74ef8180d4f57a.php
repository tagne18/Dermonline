<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name',
    'address',
    'itineraire',
    'icon' => null
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'name',
    'address',
    'itineraire',
    'icon' => null
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>
<div class="card mb-3 shadow-sm" style="max-width: 100%; min-width: 220px;">
  <div class="row g-0 align-items-center">
    <div class="col-auto px-3 py-2">
      <?php if($icon): ?>
        <?php echo $icon; ?>

      <?php else: ?>
        <svg width="28" height="28" fill="none" stroke="#24b47e" viewBox="0 0 24 24">
          <circle cx="12" cy="12" r="10" stroke="#24b47e" stroke-width="2" fill="#e5faf3"/>
          <path d="M12 8v8M8 12h8" stroke="#24b47e" stroke-width="2" stroke-linecap="round"/>
        </svg>
      <?php endif; ?>
    </div>
    <div class="col">
      <div class="card-body py-2 px-2">
        <h5 class="card-title mb-1" style="font-size:1.08rem"><?php echo e($name); ?></h5>
        <p class="card-text mb-2" style="font-size:0.94rem; color:#555"><?php echo e($address); ?></p>
        <a href="<?php echo e($itineraire); ?>" target="_blank" class="btn btn-success btn-sm">Itinéraire</a>
      </div>
    </div>
  </div>
</div>
<?php /**PATH /home/tagne/Desktop/DSL/dermonline/resources/views/components/pharmacy-card.blade.php ENDPATH**/ ?>