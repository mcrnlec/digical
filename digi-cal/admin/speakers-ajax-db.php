<?php
if (!defined('ABSPATH')) exit;

global $wpdb;

if (!defined('DIGICAL_SPEAKERS_TABLE')) {
    define('DIGICAL_SPEAKERS_TABLE', $wpdb->prefix . 'digical_speakers');
}

if (!function_exists('digical_uuid16')) {
    function digical_uuid16() {
        try { return bin2hex(random_bytes(8)); }
        catch (Throwable $e) { return substr(md5(uniqid('', true)), 0, 16); }
    }
}

function digical_speakers_permit() : bool {
    return is_user_logged_in() && current_user_can('manage_options');
}

function digical_speakers_ensure_table() {
    global $wpdb; 
    $t = DIGICAL_SPEAKERS_TABLE;
    $charset = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS `$t` (
        `id`         VARCHAR(32) NOT NULL PRIMARY KEY,
        `title`      VARCHAR(100) DEFAULT NULL,
        `first_name` VARCHAR(100) NOT NULL,
        `last_name`  VARCHAR(100) NOT NULL,
        `bio`        LONGTEXT DEFAULT NULL,
        `roles`      LONGTEXT DEFAULT NULL,
        `photo_id`   INT DEFAULT 0,
        `photo_url`  VARCHAR(500) DEFAULT '',
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        KEY `by_name` (`last_name`, `first_name`),
        KEY `by_created` (`created_at`)
    ) $charset;";
    
    $wpdb->query($sql);
    
    $columns = $wpdb->get_results("DESCRIBE `$t`", OBJECT_K);
    if (empty($columns['bio'])) {
        $wpdb->query("ALTER TABLE `$t` ADD COLUMN `bio` LONGTEXT DEFAULT NULL");
    }
    if (empty($columns['roles'])) {
        $wpdb->query("ALTER TABLE `$t` ADD COLUMN `roles` LONGTEXT DEFAULT NULL");
    }
    if (empty($columns['photo_id'])) {
        $wpdb->query("ALTER TABLE `$t` ADD COLUMN `photo_id` INT DEFAULT 0");
    }
    if (empty($columns['photo_url'])) {
        $wpdb->query("ALTER TABLE `$t` ADD COLUMN `photo_url` VARCHAR(500) DEFAULT ''");
    }
}

function digical_speakers_all_rows() {
    global $wpdb; 
    $t = DIGICAL_SPEAKERS_TABLE;
    
    digical_speakers_ensure_table();
    
    $sql = "SELECT * FROM `$t` ORDER BY last_name ASC, first_name ASC";
    $results = $wpdb->get_results($sql, ARRAY_A) ?: [];
    
    error_log("DigiCal: digical_speakers_all_rows returned " . count($results) . " rows");
    
    foreach ($results as &$row) {
        $row['roles'] = !empty($row['roles']) ? json_decode($row['roles'], true) : [];
        
        if (!empty($row['photo_id'])) {
            $row['photo_url'] = wp_get_attachment_url($row['photo_id']);
        } else {
            $row['photo_url'] = '';
        }
    }
    
    return $results;
}

function digical_speakers_get_by_id($id) {
    global $wpdb; 
    $t = DIGICAL_SPEAKERS_TABLE;
    digical_speakers_ensure_table();
    
    $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM `$t` WHERE id = %s", $id), ARRAY_A);
    
    if ($row) {
        $row['roles'] = !empty($row['roles']) ? json_decode($row['roles'], true) : [];
        if (!empty($row['photo_id'])) {
            $row['photo_url'] = wp_get_attachment_url($row['photo_id']);
        } else {
            $row['photo_url'] = '';
        }
    }
    
    return $row;
}

function digical_speakers_insert_row($title, $first_name, $last_name, $bio = '', $roles = [], $photo_id = '') {
    global $wpdb; 
    $t = DIGICAL_SPEAKERS_TABLE;
    
    digical_speakers_ensure_table();
    
    $id = digical_uuid16();
    
    if (is_array($roles)) {
        $roles = array_map('sanitize_text_field', $roles);
        $roles = array_filter($roles);
    } else {
        $roles = [];
    }
    $roles_json = !empty($roles) ? wp_json_encode($roles) : '[]';
    
    // Get photo URL if photo_id provided
    $photo_url = '';
    if (!empty($photo_id)) {
        $photo_url = wp_get_attachment_url(intval($photo_id));
        $photo_id = intval($photo_id);
    } else {
        $photo_id = 0;
    }
    
    error_log("DigiCal Insert Speaker: ID=$id, First=$first_name, Last=$last_name, Photo ID=$photo_id, Photo URL=$photo_url");
    
    $wpdb->insert($t, [
        'id'         => $id,
        'title'      => !empty(trim($title)) ? trim($title) : NULL,
        'first_name' => trim($first_name),
        'last_name'  => trim($last_name),
        'bio'        => !empty(trim($bio)) ? trim($bio) : '',
        'roles'      => $roles_json,
        'photo_id'   => $photo_id,
        'photo_url'  => $photo_url,
    ], ['%s', '%s', '%s', '%s', '%s', '%s', '%d', '%s']);
    
    if ($wpdb->last_error) {
        error_log('DigiCal Speaker Insert Error: ' . $wpdb->last_error);
        return false;
    }
    
    return $id;
}

function digical_speakers_update_row($id, $title, $first_name, $last_name, $bio = '', $roles = [], $photo_id = '') {
    global $wpdb; 
    $t = DIGICAL_SPEAKERS_TABLE;
    
    digical_speakers_ensure_table();
    
    if (is_array($roles)) {
        $roles = array_map('sanitize_text_field', $roles);
        $roles = array_filter($roles);
    } else {
        $roles = [];
    }
    $roles_json = !empty($roles) ? wp_json_encode($roles) : '[]';
    
    // Get photo URL if photo_id provided
    $photo_url = '';
    if (!empty($photo_id)) {
        $photo_url = wp_get_attachment_url(intval($photo_id));
        $photo_id = intval($photo_id);
    } else {
        $photo_id = 0;
    }

    $result = $wpdb->update($t, [
        'title'      => !empty(trim($title)) ? trim($title) : NULL,
        'first_name' => trim($first_name),
        'last_name'  => trim($last_name),
        'bio'        => !empty(trim($bio)) ? trim($bio) : '',
        'roles'      => $roles_json,
        'photo_id'   => $photo_id,
        'photo_url'  => $photo_url,
    ], ['id' => $id], ['%s', '%s', '%s', '%s', '%s', '%s', '%d', '%s'], ['%s']);
    
    if ($wpdb->last_error) {
        error_log('DigiCal Speaker Update Error: ' . $wpdb->last_error);
        return false;
    }
    
    return $result;
}

function digical_speakers_delete_row($id) {
    global $wpdb; 
    $t = DIGICAL_SPEAKERS_TABLE;
    digical_speakers_ensure_table();
    
    $result = $wpdb->delete($t, ['id' => $id], ['%s']);
    
    if ($wpdb->last_error) {
        error_log('DigiCal Speaker Delete Error: ' . $wpdb->last_error);
    }
    
    return $result;
}

function digical_speakers_delete_rows($ids) {
    if (!is_array($ids) || empty($ids)) return 0;
    global $wpdb; 
    $t = DIGICAL_SPEAKERS_TABLE;
    
    digical_speakers_ensure_table();
    
    $placeholders = implode(',', array_fill(0, count($ids), '%s'));
    $query = $wpdb->prepare("DELETE FROM `$t` WHERE id IN ($placeholders)", ...$ids);
    $result = $wpdb->query($query);
    
    if ($wpdb->last_error) {
        error_log('DigiCal Speaker Bulk Delete Error: ' . $wpdb->last_error);
    }
    
    return $result;
}

// ============ AJAX HANDLERS - SPEAKERS ============

add_action('wp_ajax_digical_get_speakers', function() {
    if (!digical_speakers_permit()) {
        wp_send_json_error(['message' => 'Unauthorized'], 403);
        return;
    }
    
    $speakers = digical_speakers_all_rows();
    wp_send_json_success(['speakers' => $speakers]);
});

add_action('wp_ajax_digical_add_speaker', function() {
    if (!digical_speakers_permit()) {
        wp_send_json_error(['message' => 'Unauthorized'], 403);
        return;
    }
    
    $title      = isset($_POST['title'])      ? sanitize_text_field($_POST['title']) : '';
    $first_name = isset($_POST['first_name']) ? sanitize_text_field($_POST['first_name']) : '';
    $last_name  = isset($_POST['last_name'])  ? sanitize_text_field($_POST['last_name']) : '';
    $bio        = isset($_POST['bio'])        ? wp_kses_post($_POST['bio']) : '';
    $roles      = isset($_POST['roles'])      ? (array)$_POST['roles'] : [];
    $photo_id   = isset($_POST['photo_id'])   ? sanitize_text_field($_POST['photo_id']) : '';
    
    if ($first_name === '' || $last_name === '') {
        wp_send_json_error(['message' => 'First name and last name are required.']);
        return;
    }
    
    $id = digical_speakers_insert_row($title, $first_name, $last_name, $bio, $roles, $photo_id);
    
    if (!$id) {
        wp_send_json_error(['message' => 'Failed to add speaker.']);
        return;
    }
    
    wp_send_json_success(['speakers' => digical_speakers_all_rows()]);
});

add_action('wp_ajax_digical_edit_speaker', function() {
    if (!digical_speakers_permit()) {
        wp_send_json_error(['message' => 'Unauthorized'], 403);
        return;
    }
    
    $id         = isset($_POST['id'])         ? sanitize_text_field($_POST['id']) : '';
    $title      = isset($_POST['title'])      ? sanitize_text_field($_POST['title']) : '';
    $first_name = isset($_POST['first_name']) ? sanitize_text_field($_POST['first_name']) : '';
    $last_name  = isset($_POST['last_name'])  ? sanitize_text_field($_POST['last_name']) : '';
    $bio        = isset($_POST['bio'])        ? wp_kses_post($_POST['bio']) : '';
    $roles      = isset($_POST['roles'])      ? (array)$_POST['roles'] : [];
    $photo_id   = isset($_POST['photo_id'])   ? sanitize_text_field($_POST['photo_id']) : '';
    
    if ($id === '' || $first_name === '' || $last_name === '') {
        wp_send_json_error(['message' => 'Missing required fields.']);
        return;
    }
    
    $ok = digical_speakers_update_row($id, $title, $first_name, $last_name, $bio, $roles, $photo_id);
    
    if ($ok === false) {
        wp_send_json_error(['message' => 'Update failed.']);
        return;
    }
    
    wp_send_json_success(['speakers' => digical_speakers_all_rows()]);
});

add_action('wp_ajax_digical_delete_speaker', function() {
    if (!digical_speakers_permit()) {
        wp_send_json_error(['message' => 'Unauthorized'], 403);
        return;
    }
    
    $id = isset($_POST['id']) ? sanitize_text_field($_POST['id']) : '';
    if ($id === '') {
        wp_send_json_error(['message' => 'Missing speaker ID.']);
        return;
    }
    
    $ok = digical_speakers_delete_row($id);
    
    if (!$ok) {
        wp_send_json_error(['message' => 'Delete failed.']);
        return;
    }
    
    wp_send_json_success(['speakers' => digical_speakers_all_rows()]);
});

add_action('wp_ajax_digical_delete_speakers', function() {
    if (!digical_speakers_permit()) {
        wp_send_json_error(['message' => 'Unauthorized'], 403);
        return;
    }
    
    $ids = isset($_POST['ids']) ? (array)$_POST['ids'] : [];
    if (empty($ids) || count($ids) < 2) {
        wp_send_json_error(['message' => 'Select at least 2 speakers.']);
        return;
    }
    
    $ids = array_map('sanitize_text_field', $ids);
    $deleted = digical_speakers_delete_rows($ids);
    
    if ($deleted === false) {
        wp_send_json_error(['message' => 'Bulk delete failed.']);
        return;
    }
    
    wp_send_json_success(['speakers' => digical_speakers_all_rows()]);
});

// ============ PHOTO UPLOAD AJAX HANDLER ============
add_action('wp_ajax_digical_upload_speaker_photo', function() {
    if (!digical_speakers_permit()) {
        wp_send_json_error(['message' => 'Unauthorized'], 403);
        return;
    }

    if (empty($_FILES['file'])) {
        wp_send_json_error(['message' => 'No file uploaded']);
    }

    $file = $_FILES['file'];
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    
    if (!in_array($file['type'], $allowed_types)) {
        wp_send_json_error(['message' => 'Invalid file type. Only images allowed.']);
    }

    if ($file['size'] > 5 * 1024 * 1024) {
        wp_send_json_error(['message' => 'File too large. Maximum 5MB.']);
    }

    require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');

    $attachment_id = media_handle_upload('file', 0);

    if (is_wp_error($attachment_id)) {
        wp_send_json_error(['message' => $attachment_id->get_error_message()]);
    }

    $image_url = wp_get_attachment_url($attachment_id);
    
    error_log("DigiCal: Photo uploaded successfully. ID: $attachment_id, URL: $image_url");

    wp_send_json_success([
        'id' => $attachment_id,
        'url' => $image_url
    ]);
});