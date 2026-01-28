<?php
/**
 * Template for displaying daily missions list
 * Variables available: $missions
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="ahoninmu-missions-container">
    <h2 class="ahoninmu-missions-title">Nhiệm vụ hôm nay</h2>
    
    <div class="ahoninmu-missions-list">
        <?php foreach ($missions as $index => $mission): ?>
            <div class="ahoninmu-mission-item <?php echo $mission->completed ? 'completed' : ''; ?>" data-mission-id="<?php echo esc_attr($mission->daily_mission_id); ?>">
                <div class="mission-number"><?php echo $index + 1; ?></div>
                
                <div class="mission-content">
                    <h3 class="mission-title">
                        <?php echo esc_html($mission->title); ?>
                        <?php if ($mission->completed): ?>
                            <span class="mission-status completed">✓ Đã hoàn thành</span>
                        <?php endif; ?>
                    </h3>
                    
                    <p class="mission-description"><?php echo esc_html($mission->description); ?></p>
                    
                    <div class="mission-meta">
                        <span class="mission-type">
                            <i class="icon-type"></i> 
                            <?php echo esc_html(ucfirst($mission->mission_type)); ?>
                        </span>
                        <span class="mission-difficulty difficulty-<?php echo esc_attr($mission->difficulty); ?>">
                            <i class="icon-difficulty"></i> 
                            <?php 
                            $difficulty_labels = array(
                                'easy' => 'Dễ',
                                'medium' => 'Trung bình',
                                'hard' => 'Khó'
                            );
                            echo isset($difficulty_labels[$mission->difficulty]) ? $difficulty_labels[$mission->difficulty] : $mission->difficulty;
                            ?>
                        </span>
                        <span class="mission-time">
                            <i class="icon-time"></i> 
                            <?php echo esc_html($mission->estimated_time); ?> phút
                        </span>
                        <span class="mission-points">
                            <i class="icon-points"></i> 
                            <?php echo esc_html($mission->points); ?> điểm
                        </span>
                    </div>
                    
                    <?php if ($mission->completed): ?>
                        <div class="mission-completion-info">
                            Hoàn thành lúc: <?php echo date_i18n('H:i', strtotime($mission->completed_at)); ?>
                            <?php if ($mission->completion_time): ?>
                                (<?php echo Ahoninmu_Leaderboard::format_time($mission->completion_time); ?>)
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="mission-actions">
                    <?php if (!$mission->completed): ?>
                        <?php if (!$mission->started_at): ?>
                            <button class="ahoninmu-btn btn-start" data-action="start" data-mission-id="<?php echo esc_attr($mission->daily_mission_id); ?>">
                                Bắt đầu
                            </button>
                        <?php else: ?>
                            <button class="ahoninmu-btn btn-complete" data-action="complete" data-mission-id="<?php echo esc_attr($mission->daily_mission_id); ?>">
                                Hoàn thành
                            </button>
                        <?php endif; ?>
                    <?php else: ?>
                        <span class="completed-checkmark">✓</span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
