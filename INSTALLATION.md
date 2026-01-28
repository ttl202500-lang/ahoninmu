# Ahoninmu Japanese Learning Plugin - Installation & Usage Guide

## Quick Start Guide

### Installation

1. **Upload Plugin:**
   - Copy the entire `ahoninmu-japanese-learning` folder to `/wp-content/plugins/` directory
   - Or upload via WordPress Admin → Plugins → Add New → Upload Plugin

2. **Activate Plugin:**
   - Go to WordPress Admin → Plugins
   - Find "Ahoninmu Japanese Learning" in the list
   - Click "Activate"

3. **Initial Setup:**
   - Upon activation, the plugin automatically:
     - Creates 4 database tables
     - Adds 15 default Japanese learning missions
     - Schedules daily cron job for midnight
   - Go to **Ahoninmu Japanese** menu in WordPress Admin to verify setup

### Creating Pages

Create the following pages in WordPress:

#### 1. My Missions Page
```
Page Title: My Daily Missions
Content:
[ahoninmu_progress]
[ahoninmu_missions]
```

#### 2. Leaderboard Page
```
Page Title: Leaderboard
Content:
[ahoninmu_leaderboard]
```

#### 3. Dashboard Page (Optional)
```
Page Title: Learning Dashboard
Content:
<h2>Welcome to Ahoninmu Japanese Learning!</h2>
[ahoninmu_progress]
<hr>
<h3>Today's Missions</h3>
[ahoninmu_missions]
<hr>
<h3>This Month's Top Learners</h3>
[ahoninmu_leaderboard limit="10"]
```

### Admin Configuration

#### Add Custom Missions
1. Go to **Ahoninmu Japanese → Nhiệm vụ** (Missions)
2. Fill in the "Add New Mission" form:
   - **Title**: e.g., "Học 15 từ vựng JLPT N5"
   - **Description**: Detailed task description
   - **Mission Type**: vocabulary, writing, reading, etc.
   - **Difficulty**: easy, medium, or hard
   - **Points**: Reward points (10-25)
   - **Estimated Time**: Time in minutes
3. Click "Thêm nhiệm vụ" (Add Mission)

#### View Leaderboard
1. Go to **Ahoninmu Japanese → Bảng xếp hạng** (Leaderboard)
2. Select month to view rankings
3. See user statistics and rankings

#### Configure Settings
1. Go to **Ahoninmu Japanese → Cài đặt** (Settings)
2. Set:
   - **Missions per day**: How many daily missions (default: 10)
   - **Reset time**: When to generate new missions (default: 00:00)
3. Save settings

### User Workflow

1. **User logs in** to WordPress
2. **Views missions** page with shortcode `[ahoninmu_missions]`
3. **Clicks "Bắt đầu"** (Start) on a mission
4. **Completes the learning task** offline or in another app
5. **Clicks "Hoàn thành"** (Complete) when done
6. **System tracks**:
   - Completion status
   - Time taken
   - Updates ranking automatically
7. **User views progress** with `[ahoninmu_progress]`
8. **Checks ranking** on leaderboard page

### Daily Reset

- **Automatic**: Plugin generates new missions at midnight via WP Cron
- **Manual**: Admin can click "Tạo nhiệm vụ cho ngày hôm nay" on Dashboard

### Shortcode Reference

#### `[ahoninmu_progress]`
Shows user's daily progress notification.

**Example:**
```
[ahoninmu_progress]
```

#### `[ahoninmu_missions]`
Displays today's mission list with interactive buttons.

**Example:**
```
[ahoninmu_missions]
```

#### `[ahoninmu_leaderboard]`
Shows monthly leaderboard.

**Parameters:**
- `limit`: Number of users to show (default: 20)
- `month`: Specific month in YYYY-MM format (default: current month)

**Examples:**
```
[ahoninmu_leaderboard]
[ahoninmu_leaderboard limit="10"]
[ahoninmu_leaderboard month="2024-01" limit="50"]
```

### Troubleshooting

#### Missions Not Generating
- Check if WP Cron is enabled: `define('DISABLE_WP_CRON', false);` in wp-config.php
- Manually trigger: Dashboard → "Tạo nhiệm vụ cho ngày hôm nay"

#### Users Can't See Missions
- Ensure users are logged in
- Check page has `[ahoninmu_missions]` shortcode

#### Leaderboard Empty
- Users need to complete missions first
- Rankings update after each mission completion

### Database Tables

The plugin creates these tables:

1. **`wp_ahoninmu_missions`**: Mission templates
2. **`wp_ahoninmu_daily_missions`**: Daily assignments
3. **`wp_ahoninmu_user_progress`**: User completion tracking
4. **`wp_ahoninmu_rankings`**: Monthly rankings

### Uninstallation

To completely remove the plugin:

1. **Deactivate** the plugin
2. **Delete** the plugin
3. Database tables are automatically removed via `uninstall.php`

### Support

For issues, questions, or contributions:
- GitHub: https://github.com/ttl202500-lang/ahoninmu
- Report bugs via GitHub Issues

### License

GPL v2 or later
