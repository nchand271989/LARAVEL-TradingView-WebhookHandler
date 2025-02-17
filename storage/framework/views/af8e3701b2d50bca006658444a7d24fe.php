<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['id' => null, 'maxWidth' => 'sm']));

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

foreach (array_filter((['id' => null, 'maxWidth' => 'sm']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div id="<?php echo e($id); ?>" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg p-6 
                absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
        <div class="px-6 py-4">
            <h2 class="text-lg font-medium text-gray-900"><?php echo e($title); ?></h2>
            <p class="mt-2 text-sm text-gray-600"><?php echo e($content); ?></p>
        </div>
        <div class="flex justify-end px-6 py-2">
            <?php echo e($footer); ?>

        </div>
    </div>
</div>
<?php /**PATH /home/newmoon/Documents/Projects/WebhookHandler/TradingView/LARAVEL-TradingView-WebhookHandler/resources/views/components/dialog-modal.blade.php ENDPATH**/ ?>