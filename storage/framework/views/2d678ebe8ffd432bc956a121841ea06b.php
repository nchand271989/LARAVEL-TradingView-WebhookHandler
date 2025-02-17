<!-- Breadcrumb Navigation -->
<nav class="mx-6 text-black mb-2 text-sm">
    <ol class="list-reset flex items-center">
        <?php
            // Extract individual elements from the slot content
            preg_match_all('/<[^>]+>[^<]*<\/[^>]+>/', $slot, $matches);
            $links = $matches[0];
        ?>

        <?php $__currentLoopData = $links; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                // Add classes dynamically
                if (str_starts_with($link, '<a')) {
                    $link = str_replace('<a', '<a class="text-blue-500 hover:underline"', $link);
                } elseif (str_starts_with($link, '<span')) {
                    $link = str_replace('<span', '<span class="text-gray-500"', $link);
                }
            ?>

            <li class="inline-block"><?php echo $link; ?></li>
            <?php if($index < count($links) - 1): ?>
                <li class="mx-2 text-gray-500">/</li>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ol>
</nav>
<?php /**PATH /home/newmoon/Documents/Projects/WebhookHandler/TradingView/LARAVEL-TradingView-WebhookHandler/resources/views/components/breadcrumb.blade.php ENDPATH**/ ?>