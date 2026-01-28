<?php
/**
 * Admin Dashboard Template
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap ahoninmu-admin">
    <h1>Ahoninmu Japanese Learning - Dashboard</h1>
    
    <?php if (isset($_GET['generated'])): ?>
        <div class="notice notice-success is-dismissible">
            <p>Nhiệm vụ ngày hôm nay đã được tạo thành công!</p>
        </div>
    <?php endif; ?>
    
    <div class="ahoninmu-stats-cards">
        <div class="stat-card">
            <div class="stat-icon">👥</div>
            <div class="stat-content">
                <h3>Tổng người dùng</h3>
                <p class="stat-number"><?php echo number_format($total_users); ?></p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">📝</div>
            <div class="stat-content">
                <h3>Nhiệm vụ hoạt động</h3>
                <p class="stat-number"><?php echo number_format($total_missions); ?></p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">✅</div>
            <div class="stat-content">
                <h3>Hoàn thành hôm nay</h3>
                <p class="stat-number"><?php echo number_format($completed_today); ?></p>
            </div>
        </div>
    </div>
    
    <div class="ahoninmu-admin-section">
        <h2>Top 5 người dùng tháng này</h2>
        
        <?php if (empty($top_users)): ?>
            <p>Chưa có dữ liệu xếp hạng.</p>
        <?php else: ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>Hạng</th>
                        <th>Người dùng</th>
                        <th>Nhiệm vụ hoàn thành</th>
                        <th>Điểm</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($top_users as $index => $user): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo esc_html($user->display_name); ?></td>
                            <td><?php echo intval($user->missions_completed); ?></td>
                            <td><?php echo number_format($user->ranking_score, 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    
    <div class="ahoninmu-admin-section">
        <h2>Thao tác nhanh</h2>
        
        <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
            <input type="hidden" name="action" value="ahoninmu_generate_missions">
            <?php wp_nonce_field('ahoninmu_generate_missions'); ?>
            <p>
                <button type="submit" class="button button-primary">
                    Tạo nhiệm vụ cho ngày hôm nay
                </button>
            </p>
        </form>
        
        <h3>Shortcodes</h3>
        <p>Sử dụng các shortcode sau để hiển thị trên trang web:</p>
        <ul>
            <li><code>[ahoninmu_progress]</code> - Hiển thị tiến trình hoàn thành nhiệm vụ</li>
            <li><code>[ahoninmu_missions]</code> - Hiển thị danh sách nhiệm vụ hôm nay</li>
            <li><code>[ahoninmu_leaderboard]</code> - Hiển thị bảng xếp hạng</li>
        </ul>
    </div>
</div>
