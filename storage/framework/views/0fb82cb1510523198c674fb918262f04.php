<svg width="40" height="40" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
    <!-- Coin Background with a Brighter Gold Gradient -->
    <defs>
        <radialGradient id="brightGold" cx="50%" cy="50%" r="50%">
            <stop offset="0%" style="stop-color:#FFD700; stop-opacity:1"/>  <!-- Bright gold center -->
            <stop offset="50%" style="stop-color:#FFC300; stop-opacity:1"/> <!-- Rich gold -->
            <stop offset="100%" style="stop-color:#FFD700; stop-opacity:1"/> <!-- Deeper gold -->
        </radialGradient>
        
        <!-- Inner Glow Effect -->
        <filter id="innerGlow" x="-50%" y="-50%" width="200%" height="200%">
            <feGaussianBlur in="SourceAlpha" stdDeviation="3" result="blur"/>
            <feOffset dx="0" dy="0"/>
            <feMerge>
                <feMergeNode in="blur"/>
                <feMergeNode in="SourceGraphic"/>
            </feMerge>
        </filter>
    </defs>

    <!-- Coin Base -->
    <circle cx="50" cy="50" r="45" fill="url(#brightGold)" stroke="#DAA520" stroke-width="4" filter="url(#innerGlow)"/>
    
    <!-- Engraved Shortcode (Centered Properly) -->
    <text x="50" y="58" font-size="20" font-weight="bold" text-anchor="middle" fill="black" font-family="Arial, sans-serif" stroke="#FFF" stroke-width="1.5" paint-order="stroke">
        <?php echo e(strtoupper($slot)); ?>

    </text>
</svg><?php /**PATH /home/newmoon/Documents/Projects/WebhookHandler/TradingView/LARAVEL-TradingView-WebhookHandler/resources/views/components/currency.blade.php ENDPATH**/ ?>