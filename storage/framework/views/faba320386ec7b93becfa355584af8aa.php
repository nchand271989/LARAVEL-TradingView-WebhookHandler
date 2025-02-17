<div>
    <button wire:click="toggleStatus" class="px-4 py-2 text-white rounded 
        <?php echo e($status === 'Active' ? 'bg-green-500 hover:bg-green-700' : 'bg-red-500 hover:bg-red-700'); ?>">
        <?php echo e($status); ?>

    </button>

    <!--[if BLOCK]><![endif]--><?php if(session()->has('error')): ?>
        <p class="text-red-500 mt-2"><?php echo e(session('error')); ?></p>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>
<?php /**PATH /home/newmoon/Documents/Projects/WebhookHandler/TradingView/LARAVEL-TradingView-WebhookHandler/resources/views/livewire/profile/status-toggle.blade.php ENDPATH**/ ?>