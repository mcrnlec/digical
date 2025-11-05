<?php
if (!defined('ABSPATH')) exit;

// Test 1: Basic output
$test_html = '<div style="padding: 20px; background: yellow; border: 3px solid red;">';
$test_html .= '<h2>DIAGNOSTIC TEST</h2>';
$test_html .= '<p>If you see this yellow box with red border, the file is loading.</p>';
$test_html .= '</div>';

// Include dashboard functions
$dashboard_path = DIGICAL_PATH . 'admin/dashboard.php';
$test_html .= '<p><strong>Dashboard path:</strong> ' . $dashboard_path . '</p>';
$test_html .= '<p><strong>File exists:</strong> ' . (file_exists($dashboard_path) ? 'YES ✓' : 'NO ✗') . '</p>';

if (file_exists($dashboard_path)) {
    require_once $dashboard_path;
    $test_html .= '<p><strong>Function exists:</strong> ' . (function_exists('digical_render_dashboard') ? 'YES ✓' : 'NO ✗') . '</p>';
    
    if (function_exists('digical_render_dashboard')) {
        $test_html .= '<p style="color: green;"><strong>Calling function now...</strong></p>';
        $dashboard_html = digical_render_dashboard();
        $test_html .= '<p><strong>Dashboard output length:</strong> ' . strlen($dashboard_html) . ' characters</p>';
        if (!empty($dashboard_html)) {
            $test_html .= $dashboard_html;
        }
    }
}

// Wrap in section
include DIGICAL_PATH . 'admin/section-wrapper.php';
digical_section_wrapper('General', $test_html);