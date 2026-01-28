# Ahoninmu Japanese Learning WordPress Plugin

A complete, professional WordPress plugin for learning Japanese with daily missions, rankings, and leaderboards.

## Features

### 1. Daily Mission System
- Generates approximately 10 different tasks each day
- Automatically updates with a new sequence of tasks the following day
- Tracks user completion of tasks
- Various mission types: vocabulary, writing, reading, listening, kanji, grammar, pronunciation, translation, conversation, quizzes, videos, and idioms

### 2. Ranking/Leaderboard System
- Ranks members based on the number of missions completed
- Factors in the time taken to complete tasks (faster completion = higher rank)
- Displays a monthly leaderboard
- Highlights the user in the #1 position with special styling
- Shows top 3 with medals (🥇 🥈 🥉)

### 3. User Interface & Experience
- Displays user notification: "Bạn đã hoàn thành X/Y nhiệm vụ ngày"
- Progress bar showing completion percentage
- Professional design suitable for a learning platform
- Responsive layout for mobile and desktop
- Real-time AJAX updates without page reload

## Installation

1. Upload the `ahoninmu-japanese-learning` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. The plugin will automatically:
   - Create necessary database tables
   - Add default missions
   - Set up daily cron job for mission reset

## Usage

### Shortcodes

Use these shortcodes to display content on your pages:

**Progress Notification:**
```
[ahoninmu_progress]
```
Shows the user's daily mission completion progress.

**Missions List:**
```
[ahoninmu_missions]
```
Displays today's missions with start/complete buttons.

**Leaderboard:**
```
[ahoninmu_leaderboard]
[ahoninmu_leaderboard limit="20"]
[ahoninmu_leaderboard month="2024-01"]
```
Shows the monthly leaderboard with optional parameters for limit and month.

### Admin Panel

Access the admin panel through **WordPress Admin > Ahoninmu Japanese**:

- **Dashboard**: View statistics and quick actions
- **Nhiệm vụ (Missions)**: Manage mission templates, add new missions
- **Bảng xếp hạng (Leaderboard)**: View monthly rankings
- **Cài đặt (Settings)**: Configure plugin settings

### Mission Management

1. Go to **Ahoninmu Japanese > Nhiệm vụ**
2. Add new missions with:
   - Title
   - Description
   - Mission type
   - Difficulty level (easy, medium, hard)
   - Points
   - Estimated time

### Daily Reset

The plugin automatically generates new daily missions at midnight using WordPress Cron. You can also manually generate missions from the Dashboard page.

## Database Tables

The plugin creates 4 tables:

- `wp_ahoninmu_missions` - Mission templates
- `wp_ahoninmu_daily_missions` - Daily mission assignments
- `wp_ahoninmu_user_progress` - User progress tracking
- `wp_ahoninmu_rankings` - Monthly user rankings

## Ranking Algorithm

The ranking score is calculated as:
```
ranking_score = (missions_completed × 100) - (average_time / 10)
```

This rewards users who complete more missions and complete them faster.

## Requirements

- WordPress 5.0 or higher
- PHP 7.0 or higher
- MySQL 5.6 or higher

## Development

### File Structure

```
ahoninmu-japanese-learning/
├── ahoninmu-japanese-learning.php (Main plugin file)
├── includes/
│   ├── class-ahoninmu-database.php
│   ├── class-ahoninmu-missions.php
│   ├── class-ahoninmu-leaderboard.php
│   ├── class-ahoninmu-shortcodes.php
│   └── class-ahoninmu-admin.php
├── assets/
│   ├── css/
│   │   ├── frontend.css
│   │   └── admin.css
│   └── js/
│       ├── frontend.js
│       └── admin.js
└── templates/
    ├── missions-list.php
    ├── progress-notification.php
    ├── leaderboard.php
    ├── admin-dashboard.php
    ├── admin-missions.php
    ├── admin-leaderboard.php
    └── admin-settings.php
```

## License

GPL v2 or later

## Author

Ahoninmu Team

## Support

For issues and questions, please visit: https://github.com/ttl202500-lang/ahoninmu
