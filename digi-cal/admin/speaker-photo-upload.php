<?php
/**
 * DigiCal Speaker Photo Upload Handler
 * File: admin/speakers-photo-upload.php
 * 
 * Handles AJAX photo uploads and updates database schema
 */

if (!defined('ABSPATH')) exit;

// ============ PHOTO UPLOAD AJAX HANDLER ============
add_action('wp_ajax_digical_upload_speaker_photo', 'digical_handle_speaker_photo_upload');

function digical_handle_speaker_photo_upload() {
    // Check permissions
    if (!current_user_can('manage_options')) {
        wp_send_json_error(['message' => 'Unauthorized'], 403);
    }

    // Check if file was uploaded
    if (empty($_FILES['file'])) {
        wp_send_json_error(['message' => 'No file uploaded']);
    }

    $file = $_FILES['file'];
    
    // Validate file type
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($file['type'], $allowed_types)) {
        wp_send_json_error(['message' => 'Invalid file type. Only images allowed.']);
    }

    // Validate file size (5MB max)
    if ($file['size'] > 5 * 1024 * 1024) {
        wp_send_json_error(['message' => 'File too large. Maximum 5MB.']);
    }

    // Get or create ConfCal Speakers folder
    $folder_name = isset($_POST['folder']) ? sanitize_text_field($_POST['folder']) : 'ConfCal Speakers';
    $folder_id = digical_get_or_create_folder($folder_name);

    // Include WordPress media functions
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');

    // Handle the upload with folder as parent
    $attachment_id = media_handle_upload('file', $folder_id);

    if (is_wp_error($attachment_id)) {
        wp_send_json_error(['message' => $attachment_id->get_error_message()]);
    }

    // Get the image URL
    $image_url = wp_get_attachment_url($attachment_id);

    error_log("DigiCal: Photo uploaded successfully. ID: $attachment_id, URL: $image_url");

    // Return success with attachment ID and URL
    wp_send_json_success([
        'id' => $attachment_id,
        'url' => $image_url
    ]);
}

/**
 * Get or create a folder in media library
 * Folders are stored as posts with post_type='attachment' and post_mime_type='application/x-folder'
 */
function digical_get_or_create_folder($folder_name) {
    global $wpdb;

    // Try to find existing folder
    $existing_folder = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT ID FROM {$wpdb->posts} 
             WHERE post_title = %s 
             AND post_type = 'attachment' 
             AND post_mime_type = 'application/x-folder'
             LIMIT 1",
            $folder_name
        )
    );

    if ($existing_folder) {
        return $existing_folder;
    }

    // Create new folder
    $folder_post = array(
        'post_type' => 'attachment',
        'post_title' => $folder_name,
        'post_mime_type' => 'application/x-folder',
        'post_status' => 'inherit',
    );

    $folder_id = wp_insert_post($folder_post);

    if (is_wp_error($folder_id)) {
        error_log("DigiCal: Failed to create folder: " . $folder_id->get_error_message());
        return 0;
    }

    error_log("DigiCal: Created folder '$folder_name' with ID: $folder_id");
    return $folder_id;
}

// ============ UPDATE SPEAKERS TABLE SCHEMA ============

function digical_update_speaker_table_schema() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'digical_speakers';
    $charset_collate = $wpdb->get_charset_collate();

    // Check if table exists
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        error_log("DigiCal: Speakers table does not exist");
        return;
    }

    $columns = $wpdb->get_results("DESCRIBE `$table_name`", OBJECT_K);

    // Add photo_id column if missing
    if (empty($columns['photo_id'])) {
        $wpdb->query("ALTER TABLE `$table_name` ADD COLUMN `photo_id` INT DEFAULT 0 AFTER `roles`");
        error_log("DigiCal: Added photo_id column to speakers table");
    }

    // Add photo_url column if missing
    if (empty($columns['photo_url'])) {
        $wpdb->query("ALTER TABLE `$table_name` ADD COLUMN `photo_url` VARCHAR(500) DEFAULT '' AFTER `photo_id`");
        error_log("DigiCal: Added photo_url column to speakers table");
    }
}

// ============ HELPER FUNCTION: Get all speakers with photo URLs ============

function digical_speakers_all_rows_with_photos() {
    $speakers = digical_speakers_all_rows();
    
    foreach ($speakers as &$speaker) {
        if (!empty($speaker['photo_id'])) {
            $speaker['photo_url'] = wp_get_attachment_url($speaker['photo_id']);
        } else {
            $speaker['photo_url'] = '';
        }
    }
    
    return $speakers;
}

// ============ OVERRIDE AJAX HANDLERS FOR SPEAKERS ============

// Override the add_speaker handler to include photo support
remove_all_actions('wp_ajax_digical_add_speaker');
add_action('wp_ajax_digical_add_speaker', function() {
    if (!current_user_can('manage_options')) {
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
    
    $id = digical_speakers_insert_row_with_photo($title, $first_name, $last_name, $bio, $roles, $photo_id);
    
    if (!$id) {
        wp_send_json_error(['message' => 'Failed to add speaker.']);
        return;
    }
    
    wp_send_json_success(['speakers' => digical_speakers_all_rows_with_photos()]);
});

// Override the edit_speaker handler to include photo support
remove_all_actions('wp_ajax_digical_edit_speaker');
add_action('wp_ajax_digical_edit_speaker', function() {
    if (!current_user_can('manage_options')) {
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
    
    $ok = digical_speakers_update_row_with_photo($id, $title, $first_name, $last_name, $bio, $roles, $photo_id);
    
    if ($ok === false) {
        wp_send_json_error(['message' => 'Update failed.']);
        return;
    }
    
    wp_send_json_success(['speakers' => digical_speakers_all_rows_with_photos()]);
});

// ============ SPEAKER INSERT/UPDATE FUNCTIONS WITH PHOTO ============

function digical_speakers_insert_row_with_photo($title, $first_name, $last_name, $bio = '', $roles = [], $photo_id = '') {
    global $wpdb; 
    $t = $wpdb->prefix . 'digical_speakers';
    
    digical_speakers_ensure_table();
    digical_update_speaker_table_schema();
    
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
    }
    
    error_log("DigiCal Insert Speaker: ID=$id, First=$first_name, Last=$last_name, Photo ID=$photo_id");
    
    $wpdb->insert($t, [
        'id'         => $id,
        'title'      => !empty(trim($title)) ? trim($title) : NULL,
        'first_name' => trim($first_name),
        'last_name'  => trim($last_name),
        'bio'        => !empty(trim($bio)) ? trim($bio) : '',
        'roles'      => $roles_json,
        'photo_id'   => !empty($photo_id) ? intval($photo_id) : 0,
        'photo_url'  => $photo_url,
    ], ['%s', '%s', '%s', '%s', '%s', '%s', '%d', '%s']);
    
    if ($wpdb->last_error) {
        error_log('DigiCal Speaker Insert Error: ' . $wpdb->last_error);
        return false;
    }
    
    return $id;
}

function digical_speakers_update_row_with_photo($id, $title, $first_name, $last_name, $bio = '', $roles = [], $photo_id = '') {
    global $wpdb; 
    $t = $wpdb->prefix . 'digical_speakers';
    
    digical_speakers_ensure_table();
    digical_update_speaker_table_schema();
    
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
    }

    $result = $wpdb->update($t, [
        'title'      => !empty(trim($title)) ? trim($title) : NULL,
        'first_name' => trim($first_name),
        'last_name'  => trim($last_name),
        'bio'        => !empty(trim($bio)) ? trim($bio) : '',
        'roles'      => $roles_json,
        'photo_id'   => !empty($photo_id) ? intval($photo_id) : 0,
        'photo_url'  => $photo_url,
    ], ['id' => $id], ['%s', '%s', '%s', '%s', '%s', '%s', '%d', '%s'], ['%s']);
    
    if ($wpdb->last_error) {
        error_log('DigiCal Speaker Update Error: ' . $wpdb->last_error);
        return false;
    }
    
    return $result;
}