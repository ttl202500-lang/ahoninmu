<?php
/**
 * Admin Missions Management Template
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap ahoninmu-admin">
    <h1>Quản lý nhiệm vụ</h1>
    
    <?php if (isset($_GET['success'])): ?>
        <div class="notice notice-success is-dismissible">
            <p>Nhiệm vụ đã được thêm thành công!</p>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['deleted'])): ?>
        <div class="notice notice-success is-dismissible">
            <p>Nhiệm vụ đã được xóa!</p>
        </div>
    <?php endif; ?>
    
    <div class="ahoninmu-admin-section">
        <h2>Thêm nhiệm vụ mới</h2>
        
        <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
            <input type="hidden" name="action" value="ahoninmu_add_mission">
            <?php wp_nonce_field('ahoninmu_add_mission'); ?>
            
            <table class="form-table">
                <tr>
                    <th><label for="title">Tiêu đề</label></th>
                    <td><input type="text" name="title" id="title" class="regular-text" required></td>
                </tr>
                <tr>
                    <th><label for="description">Mô tả</label></th>
                    <td><textarea name="description" id="description" rows="3" class="large-text" required></textarea></td>
                </tr>
                <tr>
                    <th><label for="mission_type">Loại nhiệm vụ</label></th>
                    <td>
                        <select name="mission_type" id="mission_type" required>
                            <option value="vocabulary">Từ vựng (Vocabulary)</option>
                            <option value="writing">Viết (Writing)</option>
                            <option value="reading">Đọc (Reading)</option>
                            <option value="listening">Nghe (Listening)</option>
                            <option value="kanji">Kanji</option>
                            <option value="grammar">Ngữ pháp (Grammar)</option>
                            <option value="pronunciation">Phát âm (Pronunciation)</option>
                            <option value="translation">Dịch (Translation)</option>
                            <option value="conversation">Hội thoại (Conversation)</option>
                            <option value="quiz">Bài kiểm tra (Quiz)</option>
                            <option value="video">Video</option>
                            <option value="idioms">Thành ngữ (Idioms)</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="difficulty">Độ khó</label></th>
                    <td>
                        <select name="difficulty" id="difficulty" required>
                            <option value="easy">Dễ</option>
                            <option value="medium" selected>Trung bình</option>
                            <option value="hard">Khó</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="points">Điểm</label></th>
                    <td><input type="number" name="points" id="points" value="10" min="1" required></td>
                </tr>
                <tr>
                    <th><label for="estimated_time">Thời gian ước tính (phút)</label></th>
                    <td><input type="number" name="estimated_time" id="estimated_time" value="10" min="1" required></td>
                </tr>
            </table>
            
            <p class="submit">
                <button type="submit" class="button button-primary">Thêm nhiệm vụ</button>
            </p>
        </form>
    </div>
    
    <div class="ahoninmu-admin-section">
        <h2>Danh sách nhiệm vụ</h2>
        
        <?php if (empty($missions)): ?>
            <p>Chưa có nhiệm vụ nào.</p>
        <?php else: ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th style="width: 50px;">ID</th>
                        <th>Tiêu đề</th>
                        <th>Loại</th>
                        <th>Độ khó</th>
                        <th>Điểm</th>
                        <th>Thời gian</th>
                        <th>Trạng thái</th>
                        <th style="width: 100px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($missions as $mission): ?>
                        <tr>
                            <td><?php echo $mission->id; ?></td>
                            <td><strong><?php echo esc_html($mission->title); ?></strong></td>
                            <td><?php echo esc_html($mission->mission_type); ?></td>
                            <td><?php echo esc_html($mission->difficulty); ?></td>
                            <td><?php echo $mission->points; ?></td>
                            <td><?php echo $mission->estimated_time; ?> phút</td>
                            <td>
                                <?php if ($mission->is_active): ?>
                                    <span class="status-active">Hoạt động</span>
                                <?php else: ?>
                                    <span class="status-inactive">Không hoạt động</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo wp_nonce_url(admin_url('admin-post.php?action=ahoninmu_delete_mission&id=' . $mission->id), 'ahoninmu_delete_mission_' . $mission->id); ?>" 
                                   class="button button-small" 
                                   onclick="return confirm('Bạn có chắc muốn xóa nhiệm vụ này?');">
                                    Xóa
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
