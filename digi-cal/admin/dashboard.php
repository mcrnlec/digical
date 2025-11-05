<?php
/**
 * DigiCal Dashboard — Ultra Modern Luxury Design with Quick Actions on Top
 */
if (!defined('ABSPATH')) exit;

/**
 * Get dashboard statistics
 */
function digical_get_dashboard_stats() {
    global $wpdb;
    
    $stats = [
        'days'       => 0,
        'venues'     => 0,
        'speakers'   => 0,
        'first_day'  => null,
        'last_day'   => null,
    ];
    
    // Count days
    if (function_exists('digical_days_all_rows')) {
        $days = digical_days_all_rows();
        $stats['days'] = count($days);
        if (!empty($days)) {
            $first = $days[0];
            $last = end($days);
            $stats['first_day'] = isset($first['date']) ? digical_format_date($first['date']) : null;
            $stats['last_day'] = isset($last['date']) ? digical_format_date($last['date']) : null;
        }
    }
    
    // Count venues
    if (function_exists('digical_venues_all_rows')) {
        $venues = digical_venues_all_rows();
        $stats['venues'] = count($venues);
    }
    
    // Count speakers
    if (function_exists('digical_speakers_all_rows')) {
        $speakers = digical_speakers_all_rows();
        $stats['speakers'] = count($speakers);
    }
    
    return $stats;
}

/**
 * Format date from DDMMYYYY to readable format
 */
function digical_format_date($ddmmyyyy) {
    if (!preg_match('/^(\d{2})(\d{2})(\d{4})$/', (string)$ddmmyyyy, $m)) {
        return $ddmmyyyy;
    }
    return "{$m[1]}.{$m[2]}.{$m[3]}";
}

/**
 * Get day name for a date (Croatian)
 */
function digical_get_day_name($ddmmyyyy) {
    if (!preg_match('/^(\d{2})(\d{2})(\d{4})$/', (string)$ddmmyyyy, $m)) {
        return '';
    }
    $timestamp = mktime(0, 0, 0, (int)$m[2], (int)$m[1], (int)$m[3]);
    $days = ['Ponedjeljak', 'Utorak', 'Srijeda', 'Četvrtak', 'Petak', 'Subota', 'Nedjelja'];
    return $days[date('w', $timestamp) ?: 6];
}

/**
 * Get upcoming days (next 3)
 */
function digical_get_upcoming_days($limit = 3) {
    if (!function_exists('digical_days_all_rows')) {
        return [];
    }
    
    $days = digical_days_all_rows();
    
    // Convert today's date to YYYYMMDD format for proper comparison
    $today_date = date('Ymd'); // YYYYMMDD format
    $upcoming = [];
    
    foreach ($days as $day) {
        $day_str = $day['date'] ?? ''; // DDMMYYYY format from database
        
        // Convert DDMMYYYY to YYYYMMDD for proper date comparison
        if (preg_match('/^(\d{2})(\d{2})(\d{4})$/', $day_str, $m)) {
            $day_ymd = $m[3] . $m[2] . $m[1]; // Convert to YYYYMMDD
            
            // Compare as numbers for proper chronological order
            if ((int)$day_ymd >= (int)$today_date) {
                $upcoming[] = $day;
                if (count($upcoming) >= $limit) break;
            }
        }
    }
    
    return $upcoming;
}

/**
 * Render dashboard HTML - Ultra Modern with Fancy Styling
 */
function digical_render_dashboard() {
    $stats = digical_get_dashboard_stats();
    $upcoming = digical_get_upcoming_days(3);
    ?>
    <div class="digical-dashboard">
        
        <!-- QUICK ACTIONS FIRST - TOP PRIORITY -->
        <div class="digical-quick-actions">
            <h3>🚀 Quick Actions</h3>
            <div class="digical-quick-actions-buttons">
                <a href="<?php echo esc_url(admin_url('admin.php?page=digical-days')); ?>" class="button button-primary">+ Add Day</a>
                <a href="<?php echo esc_url(admin_url('admin.php?page=digical-venues')); ?>" class="button button-primary">+ Add Venue</a>
                <a href="<?php echo esc_url(admin_url('admin.php?page=digical-speakers')); ?>" class="button button-primary">+ Add Speaker</a>
                <a href="<?php echo esc_url(admin_url('admin.php?page=digical-config')); ?>" class="button">⚙️ Configure</a>
            </div>
        </div>
        
        <!-- STATS CARDS HEADER -->
        <h3 style="margin-top: 45px; margin-bottom: 25px; font-size: 20px; font-weight: 800; color: #1a202c; letter-spacing: -0.5px;">📊 Conference Overview</h3>
        
        <!-- Stats Cards Grid -->
        <div class="digical-stats-grid">
            
            <!-- Days Card -->
            <div class="digical-stat-card">
                <div class="digical-stat-icon">📅</div>
                <div class="digical-stat-content">
                    <h3>Conference Days</h3>
                    <p class="digical-stat-number"><?php echo esc_html($stats['days']); ?></p>
                    <?php if ($stats['first_day'] && $stats['last_day']): ?>
                        <p class="digical-stat-meta"><?php echo esc_html($stats['first_day']); ?> → <?php echo esc_html($stats['last_day']); ?></p>
                    <?php else: ?>
                        <p class="digical-stat-meta">No dates configured</p>
                    <?php endif; ?>
                </div>
                <a href="<?php echo esc_url(admin_url('admin.php?page=digical-days')); ?>" class="digical-stat-link">Manage →</a>
            </div>
            
            <!-- Venues Card -->
            <div class="digical-stat-card">
                <div class="digical-stat-icon">📍</div>
                <div class="digical-stat-content">
                    <h3>Venues</h3>
                    <p class="digical-stat-number"><?php echo esc_html($stats['venues']); ?></p>
                    <p class="digical-stat-meta">Configured locations</p>
                </div>
                <a href="<?php echo esc_url(admin_url('admin.php?page=digical-venues')); ?>" class="digical-stat-link">Manage →</a>
            </div>
            
            <!-- Speakers Card -->
            <div class="digical-stat-card">
                <div class="digical-stat-icon">🎤</div>
                <div class="digical-stat-content">
                    <h3>Speakers</h3>
                    <p class="digical-stat-number"><?php echo esc_html($stats['speakers']); ?></p>
                    <p class="digical-stat-meta">Registered speakers</p>
                </div>
                <a href="<?php echo esc_url(admin_url('admin.php?page=digical-speakers')); ?>" class="digical-stat-link">Manage →</a>
            </div>
            
        </div>
        
        <!-- Upcoming Days Section -->
        <h3 style="margin-top: 45px; margin-bottom: 25px; font-size: 20px; font-weight: 800; color: #1a202c;">📌 Upcoming Conference Days</h3>
        
        <?php if (!empty($upcoming)): ?>
            <div class="digical-upcoming-list">
                <?php foreach ($upcoming as $day): ?>
                    <?php 
                        $date = $day['date'] ?? '';
                        $start = $day['start_time'] ?? '';
                        $end = $day['end_time'] ?? '';
                        $day_name = digical_get_day_name($date);
                        $formatted_date = digical_format_date($date);
                    ?>
                    <div class="digical-upcoming-item">
                        <div class="digical-upcoming-date">
                            <div class="digical-upcoming-day"><?php echo esc_html($day_name); ?></div>
                            <div class="digical-upcoming-date-display"><?php echo esc_html($formatted_date); ?></div>
                        </div>
                        <div class="digical-upcoming-times">
                            ⏰ <?php echo esc_html($start); ?> - <?php echo esc_html($end); ?>
                        </div>
                        <a href="<?php echo esc_url(admin_url('admin.php?page=digical-day-' . sanitize_key($day['id'] ?? ''))); ?>" class="button button-small">Configure</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="digical-empty-state">
                No upcoming days yet. <a href="<?php echo esc_url(admin_url('admin.php?page=digical-days')); ?>">Create your first day →</a>
            </div>
        <?php endif; ?>
        
    </div>
    <?php
}