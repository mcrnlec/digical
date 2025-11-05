<?php
if (!defined('ABSPATH')) exit;

global $wpdb;
if (!defined('DIGICAL_VENUES_TABLE')) {
    define('DIGICAL_VENUES_TABLE', $wpdb->prefix . 'digical_venues');
}

/* ============================
   Helpers (no nonce)
   ============================ */
if (!function_exists('digical_uuid16')) {
    function digical_uuid16() {
        try { return bin2hex(random_bytes(8)); }
        catch (Throwable $e) { return substr(md5(uniqid('', true)), 0, 16); }
    }
}
/* Minimal permission gate */
function digical_db_permit() : bool {
    return is_user_logged_in() && current_user_can('manage_options');
}

/* Small DB helpers */
function digical_venues_get_by_id($id) {
    global $wpdb; $t = DIGICAL_VENUES_TABLE;
    return $wpdb->get_row($wpdb->prepare("SELECT * FROM `$t` WHERE id = %s", $id), ARRAY_A);
}
function digical_venues_parent_address($pid) {
    if (!$pid) return '';
    $p = digical_venues_get_by_id($pid);
    return $p ? (string)$p['address'] : '';
}

/* ============================
   Table (with parent_id)
   ============================ */
function digical_venues_ensure_table() {
    global $wpdb; $t = DIGICAL_VENUES_TABLE;
    $charset = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS `$t` (
        `id`        VARCHAR(32) NOT NULL,
        `type`      VARCHAR(10) NOT NULL,       -- 'primary' | 'secondary'
        `name`      VARCHAR(255) NOT NULL,
        `address`   TEXT NOT NULL,
        `parent_id` VARCHAR(32) NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `by_type_name` (`type`,`name`),
        KEY `by_parent` (`parent_id`)
    ) $charset;";
    $wpdb->query($sql);

    $has_parent = $wpdb->get_var($wpdb->prepare("SHOW COLUMNS FROM `$t` LIKE %s", 'parent_id'));
    if (!$has_parent) {
        $wpdb->query("ALTER TABLE `$t` ADD `parent_id` VARCHAR(32) NULL DEFAULT NULL, ADD KEY `by_parent` (`parent_id`)");
    }
}

/* ============================
   Data layer
   ============================ */
function digical_venues_all_rows() {
    global $wpdb; $t = DIGICAL_VENUES_TABLE;
    // Group by the primary venue name; show primary first, then subs by their own name
    $sql = "
        SELECT v.id, v.type, v.name, v.address, v.parent_id,
               p.name    AS parent_name,
               p.address AS parent_address
        FROM `$t` v
        LEFT JOIN `$t` p ON p.id = v.parent_id
        ORDER BY
            COALESCE(p.name, v.name) ASC,
            CASE WHEN v.type = 'primary' THEN 0 ELSE 1 END,
            v.name ASC
    ";
    return $wpdb->get_results($sql, ARRAY_A) ?: [];
}
function digical_venues_insert_row($type, $name, $address, $parent_id = null) {
    global $wpdb; $t = DIGICAL_VENUES_TABLE; $id = digical_uuid16();
    $wpdb->insert($t, [
        'id'        => $id,
        'type'      => $type,
        'name'      => $name,
        'address'   => $address,
        'parent_id' => $parent_id ?: null,
    ], ['%s','%s','%s','%s','%s']);
    return $id;
}
function digical_venues_update_row($id, $type, $name, $address, $parent_id = null) {
    global $wpdb; $t = DIGICAL_VENUES_TABLE;
    return $wpdb->update($t, [
        'type'      => $type,
        'name'      => $name,
        'address'   => $address,
        'parent_id' => $parent_id ?: null,
    ], ['id' => $id], ['%s','%s','%s','%s'], ['%s']);
}
function digical_venues_delete_row($id) {
    global $wpdb; $t = DIGICAL_VENUES_TABLE;
    return $wpdb->delete($t, ['id'=>$id], ['%s']);
}

/* ============================
   Validation
   ============================ */
function digical_venue_type_clean($v) {
    $v = strtolower(trim((string)$v));
    // accept 'sub' from UI, normalize to 'secondary'
    if ($v === 'sub' || $v === 'sub-venue') $v = 'secondary';
    return in_array($v, ['primary','secondary'], true) ? $v : '';
}
function digical_venue_name_clean($v)    { return trim(wp_kses_post((string)$v)); }
function digical_venue_address_clean($v) { return trim(wp_kses_post((string)$v)); }
function digical_parent_clean($v)        { $v = trim((string)$v); return $v === '' ? null : sanitize_text_field($v); }

/* ============================
   AJAX (capability-only)
   ============================ */
add_action('wp_ajax_digical_db_probe_venues', function () {
    if (!digical_db_permit()) wp_send_json_error(['message'=>'Unauthorized'], 403);
    digical_venues_ensure_table();
    wp_send_json_success(['ok'=>true]);
});

add_action('wp_ajax_digical_db_get_venues', function () {
    if (!digical_db_permit()) wp_send_json_error(['message'=>'Unauthorized'], 403);
    digical_venues_ensure_table();
    wp_send_json_success(['venues' => digical_venues_all_rows()]);
});

/* ---- ADD ---- */
add_action('wp_ajax_digical_db_add_venue', function () {
    if (!digical_db_permit()) wp_send_json_error(['message'=>'Unauthorized'], 403);

    $type = digical_venue_type_clean($_POST['venue_type'] ?? '');
    $name = digical_venue_name_clean($_POST['venue_name'] ?? '');
    $addr = digical_venue_address_clean($_POST['venue_address'] ?? '');
    $pid  = digical_parent_clean($_POST['parent_id'] ?? '');

    if (!$type)                           wp_send_json_error(['message'=>'Invalid venue type (primary/sub-venue).']);
    if ($name === '')                     wp_send_json_error(['message'=>'Venue name is required.']);
    if ($type === 'secondary' && !$pid)   wp_send_json_error(['message'=>'Sub-venue must have a Primary.']);

    // Convenience: if new sub-venue address left blank, inherit from primary ONCE.
    if ($type === 'secondary' && $addr === '') {
        $addr = digical_venues_parent_address($pid);
    }
    if ($addr === '') wp_send_json_error(['message'=>'Venue address is required.']);

    digical_venues_ensure_table();
    digical_venues_insert_row($type, $name, $addr, $type === 'secondary' ? $pid : null);
    wp_send_json_success(['venues' => digical_venues_all_rows()]);
});

/* ---- EDIT ---- */
add_action('wp_ajax_digical_db_edit_venue', function () {
    if (!digical_db_permit()) wp_send_json_error(['message'=>'Unauthorized'], 403);

    $id   = sanitize_text_field($_POST['id'] ?? '');
    $type = digical_venue_type_clean($_POST['venue_type'] ?? '');
    $name = digical_venue_name_clean($_POST['venue_name'] ?? '');
    $addr = digical_venue_address_clean($_POST['venue_address'] ?? '');
    $pid  = digical_parent_clean($_POST['parent_id'] ?? '');

    if ($id === '')                       wp_send_json_error(['message'=>'Missing ID.']);
    if (!$type)                           wp_send_json_error(['message'=>'Invalid venue type.']);
    if ($name === '')                     wp_send_json_error(['message'=>'Venue name is required.']);
    if ($type === 'secondary' && !$pid)   wp_send_json_error(['message'=>'Sub-venue must have a Primary.']);

    $prev = digical_venues_get_by_id($id);
    if (!$prev) wp_send_json_error(['message'=>'Row not found.']);

    // If editing a sub-venue and address left blank, inherit primary ONCE (do not force afterwards).
    if ($type === 'secondary' && $addr === '') {
        $addr = digical_venues_parent_address($pid);
    }
    if ($addr === '') wp_send_json_error(['message'=>'Venue address is required.']);

    digical_venues_ensure_table();
    $ok = digical_venues_update_row($id, $type, $name, $addr, $type === 'secondary' ? $pid : null);
    if ($ok === false) wp_send_json_error(['message'=>'Update failed.']);

    // ONE-WAY PROPAGATION: only when a PRIMARY's address changed.
    if ($type === 'primary' && isset($prev['address']) && $addr !== $prev['address']) {
        global $wpdb; $t = DIGICAL_VENUES_TABLE;
        $wpdb->update($t, ['address' => $addr], ['parent_id' => $id], ['%s'], ['%s']);
    }

    wp_send_json_success(['venues' => digical_venues_all_rows()]);
});

/* ---- DELETE (single) ---- */
add_action('wp_ajax_digical_db_delete_venue', function () {
    if (!digical_db_permit()) wp_send_json_error(['message'=>'Unauthorized'], 403);
    $id = sanitize_text_field($_POST['id'] ?? '');
    if ($id === '') wp_send_json_error(['message'=>'Missing ID.']);
    digical_venues_ensure_table();
    $ok = digical_venues_delete_row($id);
    if (!$ok) wp_send_json_error(['message'=>'Row not found.']);
    wp_send_json_success(['venues' => digical_venues_all_rows()]);
});

/* ---- DELETE (bulk) ---- */
add_action('wp_ajax_digical_db_delete_venues', function () {
    if (!digical_db_permit()) wp_send_json_error(['message'=>'Unauthorized'], 403);

    $raw = $_POST['ids'] ?? [];
    if (is_string($raw)) { $ids = array_filter(array_map('trim', explode(',', $raw))); }
    else { $ids = array_values(array_filter(array_map('sanitize_text_field', (array)$raw))); }
    if (empty($ids)) wp_send_json_error(['message'=>'No IDs provided.']);

    global $wpdb; $t = DIGICAL_VENUES_TABLE;
    $placeholders = implode(',', array_fill(0, count($ids), '%s'));
    $sql = $wpdb->prepare("DELETE FROM `$t` WHERE id IN ($placeholders)", $ids);
    $wpdb->query($sql);

    wp_send_json_success(['venues' => digical_venues_all_rows()]);
});
