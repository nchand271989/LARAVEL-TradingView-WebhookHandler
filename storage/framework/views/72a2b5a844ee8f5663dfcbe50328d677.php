<div class="container mt-5">
    <div class="row">
        <?php $__currentLoopData = $groupedOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <!-- Primary Card for Currency -->
                <div class="card shadow-sm">
                    <div class="card-header bg-info text-left p-4">
                        <h5 class="card-title text-xl text-[#0061ff] font-extrabold mb-0"><?php echo e($currency['currencies']); ?></h5>
                    </div>
                    <div class="card-body">
                        <?php $__currentLoopData = $currency['webhooks']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $webhook): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <!-- Nested Card for Webhook -->
                            <div class="card mb-2 shadow-sm border-light bg-[#FBFBF9]">
                                <div class="card-header bg-light p-3">
                                    <h6 class="card-title mb-0 text-[#009675]"><?php echo e($webhook['webhook']); ?></h6>
                                </div>
                                <div class="card-body flex flex-wrap gap-4 p-4 ">
                                    <?php $__currentLoopData = $webhook['rules']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <!-- Nested Card for Rule with Order Count and Balance -->
                                        <a href="<?php echo e(route('graph.index', ['wallet_id' => $rule['wallet_id']])); ?>">
                                            <div class="card mb-3 shadow-sm border-light w-full sm:w-auto bg-[#F5F5F5]">
                                                <div class="card-header bg-light p-3">
                                                    <h6 class="card-title mb-0 text-[#008c02]"><?php echo e($rule['rule']); ?></h6>
                                                </div>
                                                <div class="card-body p-3">
                                                    <p class="card-text">Number of Orders: <strong><?php echo e($rule['trade_count']); ?></strong></p>
                                                    <!-- Display the balance -->
                                                    <p class="card-text">
                                                        P&L: 
                                                        <strong style="color: <?php echo e($rule['p&L'] < 0 ? 'red' : 'green'); ?>">
                                                            <?php echo e(number_format($rule['p&L'], 8)); ?>

                                                        </strong>
                                                    </p>


                                                    <p class="card-text">Balance: <strong><?php echo e($rule['balance']); ?></strong></p>
                                                </div>
                                            </div>
                                        </a>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php /**PATH /home/newmoon/Documents/Projects/WebhookHandler/TradingView/LARAVEL-TradingView-WebhookHandler/resources/views/components/welcome.blade.php ENDPATH**/ ?>