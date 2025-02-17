<td class="hidden sm:table-cell py-3 px-6 text-right">
    <!--[if BLOCK]><![endif]--><?php if(session('is_admin') && $item->status=='Inactive'): ?>
        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $actions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <!--[if BLOCK]><![endif]--><?php if($action === 'Edit'): ?>
                <a href="<?php echo e(route('exchanges.edit', $item)); ?>" class="text-blue-500 hover:underline">Edit</a>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            <!--[if BLOCK]><![endif]--><?php if($action === 'Delete'): ?>
                <form action="<?php echo e(route('exchanges.destroy', $item)); ?>" method="POST" class="inline-block">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="text-red-500 hover:underline">Delete</button>
                </form>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</td><?php /**PATH /home/newmoon/Documents/Projects/WebhookHandler/TradingView/LARAVEL-TradingView-WebhookHandler/resources/views/livewire/action-cell.blade.php ENDPATH**/ ?>