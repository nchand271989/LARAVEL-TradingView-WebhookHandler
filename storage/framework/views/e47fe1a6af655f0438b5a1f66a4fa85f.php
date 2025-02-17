<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'action' => '', 
    'placeholder' => 'Search...'
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
    'action' => '', 
    'placeholder' => 'Search...'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>
<form method="GET" action="<?php echo e($action); ?>" class="flex w-full sm:w-auto space-x-2">
    <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="<?php echo e($placeholder); ?>" class="border rounded p-2 flex-1">
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Search</button>
</form><?php /**PATH /home/newmoon/Documents/Projects/WebhookHandler/TradingView/LARAVEL-TradingView-WebhookHandler/resources/views/components/search.blade.php ENDPATH**/ ?>