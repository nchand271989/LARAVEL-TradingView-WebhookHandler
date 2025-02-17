<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'columns' => [], 
    'items' => [], 
    'actions' => [],
    'detachRelations' => [], // New prop for detachRelations
    'editRoute' => null,
    'deleteRoute' => null,
    'topUpRoute' => null,
    'topUpWalletKey' => null,
    'key' => null,
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
    'columns' => [], 
    'items' => [], 
    'actions' => [],
    'detachRelations' => [], // New prop for detachRelations
    'editRoute' => null,
    'deleteRoute' => null,
    'topUpRoute' => null,
    'topUpWalletKey' => null,
    'key' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    $sortIcons = [
        'asc' => '⬆️', 
        'desc' => '⬇️',
    ];
    $currentSortBy = request('sortBy');
    $currentSortOrder = request('sortOrder') == 'asc' ? 'desc' : 'asc';
?>

<table class="table-fixed w-full border-collapse">
    <!-- Table Header -->
    <thead>
        <tr class="text-[13px] sm:text-sm bg-gray-200 text-gray-600 uppercase leading-normal">
            <?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $alignment = $loop->last ? 'text-right' : 'text-left';
                ?>

                <th class="py-3 px-6 <?php echo e($alignment); ?> <?php if($index === 'currencies' || $index === 'webhook-url'): ?> hidden sm:table-cell <?php endif; ?>">
                    <?php if(is_numeric($index)): ?>
                        
                        <?php echo e($label); ?>

                    <?php else: ?>
                        
                        <a href="?sortBy=<?php echo e($index); ?>&sortOrder=<?php echo e($currentSortOrder); ?>">
                            <?php echo e($label); ?><?php echo $currentSortBy === $index ? $sortIcons[request('sortOrder')] ?? '' : ''; ?>

                        </a>
                    <?php endif; ?>
                </th>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </tr>
    </thead>

    <!-- Table Body -->
    <tbody class="text-gray-600 text-sm">
        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr class="border-b border-gray-200 hover:bg-gray-100">
                <?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $alignment = $loop->last ? 'text-right' : 'text-left';
                    ?>
                    <td class="py-3 px-6 <?php echo e($alignment); ?> <?php if($index === 'currencies' || $index === 'webhook-url'): ?> hidden sm:table-cell <?php endif; ?>">
                    <?php if($index === 'name'): ?>
                        <?php echo e($item->name); ?><br/><span class="text-xs"><i>(<?php echo e($item->{$key}); ?>)</i></span>
                    <?php elseif($index === 'status'): ?>
                            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('status-toggle', [
                                'model' => $item,
                                'actions' => $actions,
                                'detachRelations' => $detachRelations, // Pass detachRelations here
                                'editRoute' => $editRoute,
                                'deleteRoute' => $deleteRoute,
                            ]);

$__html = app('livewire')->mount($__name, $__params, 'lw-2565369706-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                        <?php elseif($index == 'webhook-url'): ?>
                            <div>
                                <small class="text-gray-500 cursor-pointer text-blue-500 underline" onclick="copyToClipboard('<?php echo e(url('/api/' . env('HOOK_KEY') . '/' . $item->createdBy . '/' . $item->webhid . '/' . $item->strategy_id . '/' . $item->exchange_id   . '/' . $item->currency_id . '/' . hash('sha256', env('HASH_KEY') . $item->createdBy . $item->webhid . $item->strategy_id))); ?>')">
                                    Click here to copy URL
                                </small><br/>
                                <!-- Notification -->
                                <div id="copy-notification" class="fixed bottom-4 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-4 py-2 rounded-lg shadow-md hidden">
                                    Webhook URL Copied!
                                </div>
                                <!-- JavaScript -->
                                <script>
                                    function copyToClipboard(url) {
                                        navigator.clipboard.writeText(url).then(() => {
                                        let notification = document.getElementById('copy-notification');
                                        notification.classList.remove('hidden');
                                                                        
                                        
                                        // Hide notification after 2 seconds
                                        setTimeout(() => {
                                                notification.classList.add('hidden');
                                            }, 2000);
                                        }).catch(err => {
                                            console.error('Failed to copy:', err);
                                        });
                                    }
                                </script>
                            </div>
                        <?php elseif($index === 'currencies'): ?>
                            <div class="flex flex-wrap gap-2">
                                <?php $__currentLoopData = $item->currencies->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if (isset($component)) { $__componentOriginal6ad77814db6844366c1e7089b9401721 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6ad77814db6844366c1e7089b9401721 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.currency','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('currency'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e($currency->shortcode); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6ad77814db6844366c1e7089b9401721)): ?>
<?php $attributes = $__attributesOriginal6ad77814db6844366c1e7089b9401721; ?>
<?php unset($__attributesOriginal6ad77814db6844366c1e7089b9401721); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6ad77814db6844366c1e7089b9401721)): ?>
<?php $component = $__componentOriginal6ad77814db6844366c1e7089b9401721; ?>
<?php unset($__componentOriginal6ad77814db6844366c1e7089b9401721); ?>
<?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php elseif(session('is_admin') && $index === 'topup'): ?>
                            <form method="POST" action="<?php echo e(route($topUpRoute, $item->$topUpWalletKey)); ?>" class="inline">
                                <?php echo csrf_field(); ?>
                                <input type="number" name="amount" step="0.01" required placeholder="Amount" class="border p-1 rounded w-24">
                                <button type="submit" class="bg-blue-500 text-white px-2 py-1 rounded">Top Up</button>
                            </form>     
                        <?php else: ?>
                            <?php
                            $value = $item;
                            foreach(explode('->', $index) as $segment) {
                                $value = $value->$segment ?? null;
                            }
                            ?>
                            <?php echo e($value ?? ''); ?>

                        <?php endif; ?>
                    </td>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<?php /**PATH /home/newmoon/Documents/Projects/WebhookHandler/TradingView/LARAVEL-TradingView-WebhookHandler/resources/views/components/table.blade.php ENDPATH**/ ?>