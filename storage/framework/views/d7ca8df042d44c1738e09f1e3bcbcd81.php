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
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('Markets & Assets')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumb Navigation -->
            <?php if (isset($component)) { $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.breadcrumb','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                <a href="<?php echo e(route('dashboard')); ?>">Dashboard</a>
                <span class="text-gray-500">Markets & Assets</span>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2)): ?>
<?php $attributes = $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2; ?>
<?php unset($__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale19f62b34dfe0bfdf95075badcb45bc2)): ?>
<?php $component = $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2; ?>
<?php unset($__componentOriginale19f62b34dfe0bfdf95075badcb45bc2); ?>
<?php endif; ?>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                
                <!-- Exchanges Section -->
                <div class="bg-white shadow-md rounded-lg p-6 flex flex-col">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-semibold text-gray-800">🌍 Exchanges</h3>
                        <?php if(session('is_admin')): ?>
                            <a href="<?php echo e(route('exchanges.create')); ?>" class="bg-green-500 text-white px-4 py-2 rounded text-sm hover:bg-green-600">
                                + Add Exchange
                            </a>
                        <?php endif; ?>
                    </div>
                    <p class="text-gray-600 mt-3 text-sm">
                        Manage different exchanges and their available currencies. Track and organize various trading platforms where digital assets are traded.
                    </p>
                    <ul class="mt-3 text-sm text-gray-700 space-y-2">
                        <li>💼 <b>Centralized & Decentralized Platforms</b> – Keep records of both traditional and decentralized exchanges.</li>
                        <li>🔄 <b>Active & Inactive Status</b> – Enable or disable exchanges based on availability and requirements.</li>
                        <li>💱 <b>Linked Currencies</b> – Associate only supported currencies with each exchange.</li>
                        <li>📊 <b>Market Insights</b> – Track active exchanges and supported currencies.</li>
                    </ul>

                    <!-- View All Button at Bottom -->
                    <div class="mt-4">
                        <a href="<?php echo e(route('exchanges.index')); ?>" class="text-blue-500 hover:underline flex items-center">
                            View All Exchanges →
                        </a>
                    </div>
                </div>

                <!-- Currencies Section -->
                <div class="bg-white shadow-md rounded-lg p-6 flex flex-col">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-semibold text-gray-800">💰 Currencies</h3>
                        <?php if(session('is_admin')): ?>
                            <a href="<?php echo e(route('currencies.create')); ?>" class="bg-green-500 text-white px-4 py-2 rounded text-sm hover:bg-green-600">
                                + Add Currency
                            </a>
                        <?php endif; ?>
                    </div>
                    <p class="text-gray-600 mt-3 text-sm">
                        Manage different digital currencies supported in exchanges. Maintain accurate records of active currencies.
                    </p>
                    <ul class="mt-3 text-sm text-gray-700 space-y-2">
                        <li>🔍 <b>Search & Filter</b> – Quickly find and manage currencies by name or shortcode.</li>
                        <li>🛠️ <b>Status Management</b> – Activate or deactivate currencies as per business needs.</li>
                        <li>🌎 <b>Global Support</b> – Maintain a diverse range of assets from various markets.</li>
                        <li>📈 <b>Live Market Integration</b> – Easily update currency data with real-time market changes.</li>
                    </ul>

                    <!-- View All Button at Bottom -->
                    <div class="mt-4">
                        <a href="<?php echo e(route('currencies.index')); ?>" class="text-blue-500 hover:underline flex items-center">
                            View All Currencies →
                        </a>
                    </div>
                </div>

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
<?php /**PATH /home/newmoon/Documents/Projects/WebhookHandler/TradingView/LARAVEL-TradingView-WebhookHandler/resources/views/markets-assets.blade.php ENDPATH**/ ?>