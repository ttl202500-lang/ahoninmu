<?php
/**
 * Template for progress notification
 * Variables available: $summary
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="ahoninmu-progress-notification">
    <div class="progress-content">
        <div class="progress-icon">
            <?php if ((int)$summary['percentage'] === 100): ?>
                <span class="icon-trophy">🏆</span>
            <?php else: ?>
                <span class="icon-task">📝</span>
            <?php endif; ?>
        </div>
        
        <div class="progress-text">
            <p class="progress-message">
                Bạn đã hoàn thành <strong><?php echo $summary['completed']; ?>/<?php echo $summary['total']; ?></strong> nhiệm vụ ngày
            </p>
            
            <?php if ((int)$summary['percentage'] === 100): ?>
                <p class="progress-congrats">Chúc mừng! Bạn đã hoàn thành tất cả nhiệm vụ hôm nay! 🎉</p>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="progress-bar-container">
        <div class="progress-bar" style="width: <?php echo $summary['percentage']; ?>%">
            <span class="progress-percentage"><?php echo $summary['percentage']; ?>%</span>
        </div>
    </div>
</div>
