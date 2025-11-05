<?php
/*
Plugin Name: DigiCal
Description: Conference calendar with backend management (DB-backed).
Version: 1.4
Author: DIGIT
*/

if (!defined('ABSPATH')) exit;

define('DIGICAL_PATH', plugin_dir_path(__FILE__));
define('DIGICAL_URL',  plugin_dir_url(__FILE__));

/* ----------------------------------------------------------------
 * Load DB + AJAX layers so admin-ajax.php always has handlers
 * ---------------------------------------------------------------- */
require_once DIGICAL_PATH . 'admin/days-ajax-db.php';
require_once DIGICAL_PATH . 'admin/venues-ajax-db.php';
require_once DIGICAL_PATH . 'admin/titles-roles-ajax-db.php';
require_once DIGICAL_PATH . 'admin/speakers-ajax-db.php';
require_once DIGICAL_PATH . 'includes/github-updater.php';

/* ----------------------------------------------------------------
 * Activation: ensure DB tables exist
 * ---------------------------------------------------------------- */
register_activation_hook(__FILE__, function () {
    if (function_exists('digical_days_ensure_table'))   digical_days_ensure_table();
    if (function_exists('digical_venues_ensure_table')) digical_venues_ensure_table();
    if (function_exists('digical_titles_ensure_table')) digical_titles_ensure_table();
    if (function_exists('digical_roles_ensure_table'))  digical_roles_ensure_table();
    if (function_exists('digical_speakers_roles_ensure_table')) digical_speakers_roles_ensure_table();
    if (function_exists('digical_speakers_ensure_table')) digical_speakers_ensure_table();
    if (function_exists('digical_update_speaker_table_schema')) digical_update_speaker_table_schema();
});

/* ----------------------------------------------------------------
 * On plugin load: update speaker table schema if needed
 * ---------------------------------------------------------------- */
add_action('plugins_loaded', function () {
    if (function_exists('digical_update_speaker_table_schema')) {
        digical_update_speaker_table_schema();
    }
});

/* ----------------------------------------------------------------
 * Section wrapper (fallback if custom file not present)
 * ---------------------------------------------------------------- */
function digical_safe_include_section_wrapper() {
    if (!function_exists('digical_section_wrapper')) {
        $file = DIGICAL_PATH . 'admin/section-wrapper.php';
        if (file_exists($file)) include_once $file;
    }
    if (!function_exists('digical_section_wrapper')) {
        function digical_section_wrapper($active, $content_html) {
            echo '<div class="wrap"><h1>DigiCal</h1>' . $content_html . '</div>';
        }
    }
}

/* ----------------------------------------------------------------
 * Admin Menu
 * ---------------------------------------------------------------- */
add_action('admin_menu', function () {

    // Top-level: General with Dashboard (WITH sidebar)
    add_menu_page(
        'DigiCal', 'DigiCal', 'manage_options', 'digical',
        function () {
            // Load dashboard functions
            require_once DIGICAL_PATH . 'admin/dashboard.php';
            
            digical_safe_include_section_wrapper();
            
            // Render dashboard with output buffering
            ob_start();
            if (function_exists('digical_render_dashboard')) {
                digical_render_dashboard();
            } else {
                echo '<p style="color: red;">Dashboard function not found.</p>';
            }
            $content = ob_get_clean();
            
            digical_section_wrapper('General', $content);
        },
        'dashicons-calendar-alt', 25
    );

    // Days UI
    add_submenu_page(
        'digical', 'Days', 'Days', 'manage_options', 'digical-days',
        function () {
            digical_safe_include_section_wrapper();
            ob_start();
            include DIGICAL_PATH . 'admin/days.php';
            $content = ob_get_clean();
            digical_section_wrapper('Days', $content);
        }
    );

    // Venues UI
    add_submenu_page(
        'digical', 'Venues', 'Venues', 'manage_options', 'digical-venues',
        function () {
            digical_safe_include_section_wrapper();
            ob_start();
            include DIGICAL_PATH . 'admin/venues.php';
            $content = ob_get_clean();
            digical_section_wrapper('Venues', $content);
        }
    );

    // Speakers UI
    add_submenu_page(
        'digical', 'Speakers', 'Speakers', 'manage_options', 'digical-speakers',
        function () {
            digical_safe_include_section_wrapper();
            ob_start();
            include DIGICAL_PATH . 'admin/speakers.php';
            $content = ob_get_clean();
            digical_section_wrapper('Speakers', $content);
        }
    );

    // Speakers Configuration (Titles & Roles)
    add_submenu_page(
        'digical-speakers', 'Configuration', 'Configuration', 'manage_options', 'digical-config',
        function () {
            digical_safe_include_section_wrapper();
            ob_start();
            include DIGICAL_PATH . 'admin/configuration.php';
            $content = ob_get_clean();
            digical_section_wrapper('Configuration', $content);
        }
    );

    // Dynamic per-day pages (hidden from WP submenu; linked from custom sidebar)
    if (function_exists('digical_days_all_rows')) {
        $days = digical_days_all_rows();
        foreach ($days as $d) {
            if (!isset($d['id'], $d['date'])) continue;

            $slug = 'digical-day-' . sanitize_key($d['id']);
            add_submenu_page(
                'digical',
                'Day ' . esc_html($d['date']),
                '',
                'manage_options',
                $slug,
                function () use ($d) {
                    digical_safe_include_section_wrapper();
                    $dateLabel = preg_match('/^(\d{2})(\d{2})(\d{4})$/', (string)$d['date'], $m)
                        ? "{$m[1]}.{$m[2]}.{$m[3]}"
                        : esc_html((string)$d['date']);

                    $content  = '<h2>Day ' . $dateLabel . '</h2>';
                    if (isset($d['start_time'], $d['end_time'])) {
                        $content .= '<p><strong>Start:</strong> ' . esc_html($d['start_time']) .
                                    ' &nbsp; <strong>End:</strong> ' . esc_html($d['end_time']) . '</p>';
                    }
                    $content .= '<p><a class="button button-primary" href="' .
                                esc_url( admin_url('admin.php?page=digical-days') ) .
                                '">Back to Days</a></p>';

                    digical_section_wrapper('Days', $content);
                }
            );
        }
    }
});

/* ----------------------------------------------------------------
 * Optional: redirect legacy/removed slugs safely
 * ---------------------------------------------------------------- */
add_action('admin_init', function () {
    if (!is_admin()) return;
    $page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : '';
    // Example legacy redirects can be added here if needed
    // if ($page === 'digical-speakers') { wp_safe_redirect(admin_url('admin.php?page=digical-days')); exit; }
});

/* ----------------------------------------------------------------
 * Admin CSS
 * ---------------------------------------------------------------- */
add_action('admin_enqueue_scripts', function () {
    $page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : '';
    if ($page && ($page === 'digical' || strpos($page, 'digical-') === 0)) {
        $css = DIGICAL_PATH . 'assets/css/admin.css';
        if (file_exists($css)) {
            wp_enqueue_style('digical-admin-css', DIGICAL_URL . 'assets/css/admin.css', [], '1.3');
        }
    }
});