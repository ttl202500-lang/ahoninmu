<?php
/**
 * Admin Settings Template
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap ahoninmu-admin">
    <h1>Cài đặt</h1>
    
    <form method="post" action="options.php">
        <?php settings_fields('ahoninmu_settings'); ?>
        
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="ahoninmu_missions_per_day">Số nhiệm vụ mỗi ngày</label>
                </th>
                <td>
                    <input type="number" 
                           name="ahoninmu_missions_per_day" 
                           id="ahoninmu_missions_per_day" 
                           value="<?php echo esc_attr(get_option('ahoninmu_missions_per_day', 10)); ?>" 
                           min="1" 
                           max="20">
                    <p class="description">Số lượng nhiệm vụ được tạo mỗi ngày (mặc định: 10)</p>
                </td>
            </tr>
            
            <tr>
                <th scope="row">
                    <label for="ahoninmu_reset_time">Thời gian reset nhiệm vụ</label>
                </th>
                <td>
                    <input type="time" 
                           name="ahoninmu_reset_time" 
                           id="ahoninmu_reset_time" 
                           value="<?php echo esc_attr(get_option('ahoninmu_reset_time', '00:00')); ?>">
                    <p class="description">Thời gian trong ngày để reset nhiệm vụ (mặc định: 00:00)</p>
                </td>
            </tr>
        </table>
        
        <?php submit_button('Lưu cài đặt'); ?>
    </form>
    
    <hr>
    
    <div class="ahoninmu-admin-section">
        <h2>Hướng dẫn sử dụng</h2>
        
        <h3>Shortcodes</h3>
        <p>Sử dụng các shortcode sau để hiển thị nội dung trên trang web:</p>
        
        <h4>[ahoninmu_progress]</h4>
        <p>Hiển thị tiến trình hoàn thành nhiệm vụ của người dùng hiện tại.</p>
        <pre><code>[ahoninmu_progress]</code></pre>
        
        <h4>[ahoninmu_missions]</h4>
        <p>Hiển thị danh sách nhiệm vụ hôm nay.</p>
        <pre><code>[ahoninmu_missions]</code></pre>
        
        <h4>[ahoninmu_leaderboard]</h4>
        <p>Hiển thị bảng xếp hạng. Có thể tùy chỉnh số lượng người dùng hiển thị và tháng:</p>
        <pre><code>[ahoninmu_leaderboard limit="20"]
[ahoninmu_leaderboard month="2024-01"]</code></pre>
        
        <h3>Cron Job</h3>
        <p>Plugin tự động tạo nhiệm vụ mới mỗi ngày bằng WordPress Cron. Nếu cần tạo thủ công, vào trang Dashboard và nhấn nút "Tạo nhiệm vụ cho ngày hôm nay".</p>
    </div>
</div>
