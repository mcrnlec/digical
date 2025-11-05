<?php
if (!defined('ABSPATH')) exit;

/**
 * DigiCal — Titles & Roles DB layer + AJAX (loads on every request)
 * Manages speaker titles (Dr., Prof., etc.) and roles (Speaker, Moderator, etc.)
 */

global $wpdb;

if (!defined('DIGICAL_TITLES_TABLE')) {
    define('DIGICAL_TITLES_TABLE', $wpdb->prefix . 'digical_titles');
}
if (!defined('DIGICAL_ROLES_TABLE')) {
    define('DIGICAL_ROLES_TABLE', $wpdb->prefix . 'digical_roles');
}
if (!defined('DIGICAL_SPEAKERS_ROLES_TABLE')) {
    define('DIGICAL_SPEAKERS_ROLES_TABLE', $wpdb->prefix . 'digical_speakers_roles');
}

/* ---------- Permission Check ---------- */
function digical_config_permit() : bool {
    return is_user_logged_in() && current_user_can('manage_options');
}

/* ---------- Titles Table ---------- */
function digical_titles_ensure_table() {
    global $wpdb; $t = DIGICAL_TITLES_TABLE;
    $charset = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS `$t` (
        `id` VARCHAR(32) NOT NULL,
        `title` VARCHAR(255) NOT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        UNIQUE KEY `unique_title` (`title`),
        KEY `by_title` (`title`)
    ) $charset;";
    $wpdb->query($sql);
}

/* ---------- Roles Table ---------- */
function digical_roles_ensure_table() {
    global $wpdb; $t = DIGICAL_ROLES_TABLE;
    $charset = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS `$t` (
        `id` VARCHAR(32) NOT NULL,
        `role` VARCHAR(255) NOT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        UNIQUE KEY `unique_role` (`role`),
        KEY `by_role` (`role`)
    ) $charset;";
    $wpdb->query($sql);
}

/* ---------- Speakers-Roles Junction Table ---------- */
function digical_speakers_roles_ensure_table() {
    global $wpdb; $t = DIGICAL_SPEAKERS_ROLES_TABLE;
    $charset = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS `$t` (
        `id` VARCHAR(32) NOT NULL,
        `speaker_id` VARCHAR(32) NOT NULL,
        `role_id` VARCHAR(32) NOT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        UNIQUE KEY `unique_speaker_role` (`speaker_id`, `role_id`),
        KEY `by_speaker` (`speaker_id`),
        KEY `by_role` (`role_id`)
    ) $charset;";
    $wpdb->query($sql);
}

/* ---------- Titles CRUD ---------- */
function digical_titles_all_rows() {
    global $wpdb; $t = DIGICAL_TITLES_TABLE;
    return $wpdb->get_results("
        SELECT id, title, created_at
        FROM `$t`
        ORDER BY title ASC
    ", ARRAY_A) ?: [];
}

function digical_titles_insert_row($title) {
    global $wpdb; $t = DIGICAL_TITLES_TABLE;
    $title = trim(wp_kses_post($title));
    if ($title === '') return false;
    
    $id = digical_uuid16();
    $wpdb->insert($t, ['id' => $id, 'title' => $title], ['%s', '%s']);
    return $id;
}

function digical_titles_update_row($id, $title) {
    global $wpdb; $t = DIGICAL_TITLES_TABLE;
    $title = trim(wp_kses_post($title));
    if ($title === '') return false;
    
    return $wpdb->update($t, ['title' => $title], ['id' => $id], ['%s'], ['%s']);
}

function digical_titles_delete_row($id) {
    global $wpdb; $t = DIGICAL_TITLES_TABLE;
    return $wpdb->delete($t, ['id' => $id], ['%s']);
}

/* ---------- Roles CRUD ---------- */
function digical_roles_all_rows() {
    global $wpdb; $t = DIGICAL_ROLES_TABLE;
    return $wpdb->get_results("
        SELECT id, role, created_at
        FROM `$t`
        ORDER BY role ASC
    ", ARRAY_A) ?: [];
}

function digical_roles_insert_row($role) {
    global $wpdb; $t = DIGICAL_ROLES_TABLE;
    $role = trim(wp_kses_post($role));
    if ($role === '') return false;
    
    $id = digical_uuid16();
    $wpdb->insert($t, ['id' => $id, 'role' => $role], ['%s', '%s']);
    return $id;
}

function digical_roles_update_row($id, $role) {
    global $wpdb; $t = DIGICAL_ROLES_TABLE;
    $role = trim(wp_kses_post($role));
    if ($role === '') return false;
    
    return $wpdb->update($t, ['role' => $role], ['id' => $id], ['%s'], ['%s']);
}

function digical_roles_delete_row($id) {
    global $wpdb; $t = DIGICAL_ROLES_TABLE;
    return $wpdb->delete($t, ['id' => $id], ['%s']);
}

/* ---------- Speaker-Roles Junction CRUD ---------- */
function digical_speaker_roles_add($speaker_id, $role_id) {
    global $wpdb; $t = DIGICAL_SPEAKERS_ROLES_TABLE;
    $id = digical_uuid16();
    $wpdb->insert($t, ['id' => $id, 'speaker_id' => $speaker_id, 'role_id' => $role_id], ['%s', '%s', '%s']);
    return $id;
}

function digical_speaker_roles_remove($speaker_id, $role_id) {
    global $wpdb; $t = DIGICAL_SPEAKERS_ROLES_TABLE;
    return $wpdb->delete($t, ['speaker_id' => $speaker_id, 'role_id' => $role_id], ['%s', '%s']);
}

function digical_speaker_roles_get($speaker_id) {
    global $wpdb; $t = DIGICAL_SPEAKERS_ROLES_TABLE;
    return $wpdb->get_col($wpdb->prepare("
        SELECT role_id FROM `$t` WHERE speaker_id = %s
    ", $speaker_id)) ?: [];
}

/* ---------- AJAX: Titles ---------- */
add_action('wp_ajax_digical_get_titles', function() {
    if (!digical_config_permit()) wp_send_json_error(['message' => 'Unauthorized'], 403);
    digical_titles_ensure_table();
    wp_send_json_success(['titles' => digical_titles_all_rows()]);
});

add_action('wp_ajax_digical_add_title', function() {
    if (!digical_config_permit()) wp_send_json_error(['message' => 'Unauthorized'], 403);
    
    $title = isset($_POST['title']) ? sanitize_text_field($_POST['title']) : '';
    if ($title === '') wp_send_json_error(['message' => 'Title is required.']);
    
    digical_titles_ensure_table();
    $id = digical_titles_insert_row($title);
    
    if (!$id) wp_send_json_error(['message' => 'Failed to add title (may already exist).']);
    wp_send_json_success(['titles' => digical_titles_all_rows()]);
});

add_action('wp_ajax_digical_edit_title', function() {
    if (!digical_config_permit()) wp_send_json_error(['message' => 'Unauthorized'], 403);
    
    $id = isset($_POST['id']) ? sanitize_text_field($_POST['id']) : '';
    $title = isset($_POST['title']) ? sanitize_text_field($_POST['title']) : '';
    
    if ($id === '') wp_send_json_error(['message' => 'Missing ID.']);
    if ($title === '') wp_send_json_error(['message' => 'Title is required.']);
    
    digical_titles_ensure_table();
    $ok = digical_titles_update_row($id, $title);
    
    if ($ok === false) wp_send_json_error(['message' => 'Update failed.']);
    wp_send_json_success(['titles' => digical_titles_all_rows()]);
});

add_action('wp_ajax_digical_delete_title', function() {
    if (!digical_config_permit()) wp_send_json_error(['message' => 'Unauthorized'], 403);
    
    $id = isset($_POST['id']) ? sanitize_text_field($_POST['id']) : '';
    if ($id === '') wp_send_json_error(['message' => 'Missing ID.']);
    
    digical_titles_ensure_table();
    $ok = digical_titles_delete_row($id);
    
    if (!$ok) wp_send_json_error(['message' => 'Row not found.']);
    wp_send_json_success(['titles' => digical_titles_all_rows()]);
});

/* ---------- AJAX: Roles ---------- */
add_action('wp_ajax_digical_get_roles', function() {
    if (!digical_config_permit()) wp_send_json_error(['message' => 'Unauthorized'], 403);
    digical_roles_ensure_table();
    wp_send_json_success(['roles' => digical_roles_all_rows()]);
});

add_action('wp_ajax_digical_add_role', function() {
    if (!digical_config_permit()) wp_send_json_error(['message' => 'Unauthorized'], 403);
    
    $role = isset($_POST['role']) ? sanitize_text_field($_POST['role']) : '';
    if ($role === '') wp_send_json_error(['message' => 'Role is required.']);
    
    digical_roles_ensure_table();
    $id = digical_roles_insert_row($role);
    
    if (!$id) wp_send_json_error(['message' => 'Failed to add role (may already exist).']);
    wp_send_json_success(['roles' => digical_roles_all_rows()]);
});

add_action('wp_ajax_digical_edit_role', function() {
    if (!digical_config_permit()) wp_send_json_error(['message' => 'Unauthorized'], 403);
    
    $id = isset($_POST['id']) ? sanitize_text_field($_POST['id']) : '';
    $role = isset($_POST['role']) ? sanitize_text_field($_POST['role']) : '';
    
    if ($id === '') wp_send_json_error(['message' => 'Missing ID.']);
    if ($role === '') wp_send_json_error(['message' => 'Role is required.']);
    
    digical_roles_ensure_table();
    $ok = digical_roles_update_row($id, $role);
    
    if ($ok === false) wp_send_json_error(['message' => 'Update failed.']);
    wp_send_json_success(['roles' => digical_roles_all_rows()]);
});

add_action('wp_ajax_digical_delete_role', function() {
    if (!digical_config_permit()) wp_send_json_error(['message' => 'Unauthorized'], 403);
    
    $id = isset($_POST['id']) ? sanitize_text_field($_POST['id']) : '';
    if ($id === '') wp_send_json_error(['message' => 'Missing ID.']);
    
    digical_roles_ensure_table();
    $ok = digical_roles_delete_row($id);
    
    if (!$ok) wp_send_json_error(['message' => 'Row not found.']);
    wp_send_json_success(['roles' => digical_roles_all_rows()]);
});
