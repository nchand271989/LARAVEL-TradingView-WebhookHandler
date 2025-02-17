<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Scenarios</h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-lg p-6">
                <a href="<?php echo e(route('scenarios.create')); ?>" class="bg-green-500 text-white px-4 py-2 rounded">Create Scenario</a>
                <table class="w-full mt-4 border">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="p-2 text-left">Name</th>
                            <th class="p-2 text-left">Ratio</th>
                            <th class="p-2 text-left">Auto Exit</th>
                            <th class="p-2 text-left">Stop Loss</th>
                            <th class="p-2 text-left">Target Profit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $scenarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $scenario): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="p-2"><?php echo e($scenario->name); ?></td>
                                <td class="p-2"><?php echo e($scenario->ratio ?? 'N/A'); ?></td>
                                <td class="p-2"><?php echo e($scenario->auto_exit ? 'Yes' : 'No'); ?></td>
                                <td class="p-2"><?php echo e($scenario->stop_loss ? 'Yes' : 'No'); ?></td>
                                <td class="p-2"><?php echo e($scenario->target_profit ? 'Yes' : 'No'); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
                <div class="mt-4"><?php echo e($scenarios->links()); ?></div>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH /home/newmoon/Documents/Projects/WebhookHandler/TradingView/LARAVEL-TradingView-WebhookHandler/resources/views/scenarios/index.blade.php ENDPATH**/ ?>