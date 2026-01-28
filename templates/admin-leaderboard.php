<?php
/**
 * Admin Leaderboard Template
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap ahoninmu-admin">
    <h1>Bảng xếp hạng</h1>
    
    <div class="ahoninmu-admin-section">
        <form method="get" action="">
            <input type="hidden" name="page" value="ahoninmu-leaderboard">
            <label for="month">Chọn tháng:</label>
            <input type="month" name="month" id="month" value="<?php echo esc_attr($year_month); ?>">
            <button type="submit" class="button">Xem</button>
        </form>
    </div>
    
    <div class="ahoninmu-admin-section">
        <h2>Xếp hạng tháng <?php echo date_i18n('m/Y', strtotime($year_month . '-01')); ?></h2>
        
        <?php if (empty($leaders)): ?>
            <p>Chưa có dữ liệu xếp hạng cho tháng này.</p>
        <?php else: ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th style="width: 60px;">Hạng</th>
                        <th>Người dùng</th>
                        <th>Email</th>
                        <th>Nhiệm vụ hoàn thành</th>
                        <th>Tổng thời gian</th>
                        <th>Thời gian trung bình</th>
                        <th>Điểm</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($leaders as $index => $leader): ?>
                        <tr>
                            <td>
                                <?php 
                                $rank = $index + 1;
                                if ($rank == 1): ?>
                                    <strong style="color: #FFD700;">🥇 #1</strong>
                                <?php elseif ($rank == 2): ?>
                                    <strong style="color: #C0C0C0;">🥈 #2</strong>
                                <?php elseif ($rank == 3): ?>
                                    <strong style="color: #CD7F32;">🥉 #3</strong>
                                <?php else: ?>
                                    #<?php echo $rank; ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php echo get_avatar($leader->user_id, 32); ?>
                                <strong><?php echo esc_html($leader->display_name); ?></strong>
                            </td>
                            <td><?php echo esc_html($leader->user_email); ?></td>
                            <td><?php echo intval($leader->missions_completed); ?></td>
                            <td><?php echo Ahoninmu_Leaderboard::format_time($leader->total_time); ?></td>
                            <td><?php echo Ahoninmu_Leaderboard::format_time($leader->average_time); ?></td>
                            <td><strong><?php echo number_format($leader->ranking_score, 2); ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
