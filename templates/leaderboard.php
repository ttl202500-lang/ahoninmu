<?php
/**
 * Template for leaderboard display
 * Variables available: $leaders, $current_user_id, $user_rank
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="ahoninmu-leaderboard-container">
    <h2 class="ahoninmu-leaderboard-title">
        Bảng xếp hạng tháng <?php echo date_i18n('m/Y', strtotime($month . '-01')); ?>
    </h2>
    
    <?php if ($current_user_id && $user_rank): ?>
        <div class="user-rank-info">
            <p>Xếp hạng của bạn: <strong class="rank-number">#<?php echo $user_rank; ?></strong></p>
        </div>
    <?php endif; ?>
    
    <?php if (empty($leaders)): ?>
        <p class="no-data">Chưa có dữ liệu xếp hạng cho tháng này.</p>
    <?php else: ?>
        <div class="leaderboard-table-wrapper">
            <table class="ahoninmu-leaderboard-table">
                <thead>
                    <tr>
                        <th class="col-rank">Hạng</th>
                        <th class="col-user">Người dùng</th>
                        <th class="col-missions">Nhiệm vụ hoàn thành</th>
                        <th class="col-time">Thời gian trung bình</th>
                        <th class="col-score">Điểm</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($leaders as $index => $leader): ?>
                        <?php 
                        $rank = $index + 1;
                        $is_current_user = ($current_user_id === $leader->user_id);
                        $is_first = ($rank === 1);
                        $row_class = $is_current_user ? ' current-user' : '';
                        if ($is_first) {
                            $row_class .= ' first-place';
                        }
                        ?>
                        <tr class="leaderboard-row<?php echo $row_class; ?>">
                            <td class="col-rank">
                                <?php if ($is_first): ?>
                                    <span class="rank-badge first">🥇 #1</span>
                                <?php elseif ($rank === 2): ?>
                                    <span class="rank-badge second">🥈 #2</span>
                                <?php elseif ($rank === 3): ?>
                                    <span class="rank-badge third">🥉 #3</span>
                                <?php else: ?>
                                    <span class="rank-number">#<?php echo $rank; ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="col-user">
                                <div class="user-info">
                                    <?php echo get_avatar($leader->user_id, 32); ?>
                                    <span class="user-name"><?php echo esc_html($leader->display_name); ?></span>
                                    <?php if ($is_current_user): ?>
                                        <span class="badge-you">Bạn</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="col-missions">
                                <strong><?php echo intval($leader->missions_completed); ?></strong>
                            </td>
                            <td class="col-time">
                                <?php echo Ahoninmu_Leaderboard::format_time($leader->average_time); ?>
                            </td>
                            <td class="col-score">
                                <strong class="score-value"><?php echo number_format($leader->ranking_score, 2); ?></strong>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
