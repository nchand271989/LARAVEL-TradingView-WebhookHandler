<div class="space-x-2">
    <!--[if BLOCK]><![endif]--><?php if(session('is_admin') && $model->status=='Inactive'): ?>
        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $actions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <!--[if BLOCK]><![endif]--><?php if($action === 'Edit'): ?>
                <a href="<?php echo e(route($editRoute, $model)); ?>" class="hidden sm:inline-block text-blue-500 hover:underline">Edit</a>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            <!--[if BLOCK]><![endif]--><?php if($action === 'Delete'): ?>
                <form action="<?php echo e(route($deleteRoute, $model)); ?>" method="POST" class="hidden sm:inline-block">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="text-red-500 hover:underline">Delete</button>
                </form>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    <?php if(session('is_admin')): ?>
        <!-- Admin: Status Toggle Form -->
        <div class="inline hidden sm:inline">
            <button wire:click="toggleStatus" class="px-2 py-1 text-white text-sm font-semibold rounded focus:outline-none 
                <?php echo e($model->status === 'Active' ? 'bg-green-500 hover:bg-green-600' : 'bg-red-500 hover:bg-red-600'); ?>">
                <?php echo e($model->status); ?>

            </button>
            
            <!--[if BLOCK]><![endif]--><?php if(session()->has('error')): ?>
                <p class="text-red-500 mt-2"><?php echo e(session('error')); ?></p>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
        <!-- Mobile: Read-only Status -->
        <span class="px-2 py-1 text-white text-sm font-semibold rounded sm:hidden <?php echo e($model->status === 'Active' ? 'bg-green-500' : 'bg-red-500'); ?>">
            <?php echo e($model->status); ?>

        </span>
    <?php else: ?>
        <!-- Non-admin: Read-only Status -->
        <span class="px-2 py-1 text-white text-sm font-semibold rounded 
            <?php echo e($model->status === 'Active' ? 'bg-green-500' : 'bg-red-500'); ?>">
            <?php echo e($model->status); ?>

        </span>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>
<?php /**PATH /home/newmoon/Documents/Projects/WebhookHandler/TradingView/LARAVEL-TradingView-WebhookHandler/resources/views/livewire/status-toggle.blade.php ENDPATH**/ ?>