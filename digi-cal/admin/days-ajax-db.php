<?php
if (!defined('ABSPATH')) exit;

/**
 * DigiCal — Days DB layer + AJAX (loads on every request)
 */
global $wpdb;
if (!defined('DIGICAL_DAYS_TABLE')) {
    define('DIGICAL_DAYS_TABLE', $wpdb->prefix . 'digical_days');
}

/* ---------- Utils ---------- */
if (!function_exists('digical_uuid16')) {
    function digical_uuid16() {
        try { return bin2hex(random_bytes(8)); } catch (Throwable $e) { return substr(md5(uniqid('', true)), 0, 16); }
    }
}
function digical_time_norm($t) {
    $t = trim((string)$t);
    if (preg_match('/^\d{1,2}$/',$t)) return str_pad($t,2,'0',STR_PAD_LEFT).':00';
    if (preg_match('/^\d{1,2}:\d{2}$/',$t)) { [$h,$m]=explode(':',$t); return str_pad($h,2,'0',STR_PAD_LEFT).':'.$m; }
    return $t;
}
function digical_date_clean($ddmmyyyy) {
    return preg_replace('/\D+/', '', (string)$ddmmyyyy);
}
function digical_date_valid_ddmmyyyy($ddmmyyyy) {
    $d = digical_date_clean($ddmmyyyy);
    if (!preg_match('/^\d{8}$/', $d)) return false;
    $day   = (int) substr($d,0,2);
    $month = (int) substr($d,2,2);
    $year  = (int) substr($d,4,4);
    return checkdate($month,$day,$year);
}

/* ---------- Table ---------- */
function digical_days_ensure_table() {
    global $wpdb;
    $t = DIGICAL_DAYS_TABLE;
    $charset = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS `$t` (
        `id` VARCHAR(32) NOT NULL,
        `date` CHAR(8) NOT NULL,
        `start_time` CHAR(5) NOT NULL,
        `end_time` CHAR(5) NOT NULL,
        PRIMARY KEY (`id`),
        KEY `by_date_time` (`date`,`start_time`)
    ) $charset;";
    $wpdb->query($sql);
}

/* ---------- Data ---------- */
function digical_days_all_rows() {
    global $wpdb; $t = DIGICAL_DAYS_TABLE;
    return $wpdb->get_results("
        SELECT id, date, start_time, end_time
        FROM `$t`
        ORDER BY CONCAT(SUBSTR(date,5,4),SUBSTR(date,3,2),SUBSTR(date,1,2)) ASC, start_time ASC
    ", ARRAY_A) ?: [];
}
function digical_days_insert_row($date,$st,$et) {
    global $wpdb; $t = DIGICAL_DAYS_TABLE; $id = digical_uuid16();
    $wpdb->insert($t, ['id'=>$id,'date'=>$date,'start_time'=>$st,'end_time'=>$et], ['%s','%s','%s','%s']);
    return $id;
}
function digical_days_update_row($id,$date,$st,$et) {
    global $wpdb; $t = DIGICAL_DAYS_TABLE;
    return $wpdb->update($t, ['date'=>$date,'start_time'=>$st,'end_time'=>$et], ['id'=>$id], ['%s','%s','%s'], ['%s']);
}
function digical_days_delete_row($id) {
    global $wpdb; $t = DIGICAL_DAYS_TABLE;
    return $wpdb->delete($t, ['id'=>$id], ['%s']);
}

/* ---------- Nonce helper ---------- */
function digical_ajax_nonce_ok() {
    $nonce = $_REQUEST['nonce'] ?? $_REQUEST['_ajax_nonce'] ?? '';
    return $nonce && wp_verify_nonce($nonce, 'digical_nonce');
}

/* ---------- AJAX ---------- */
add_action('wp_ajax_digical_db_probe', function () {
    digical_days_ensure_table();
    wp_send_json_success(['ok'=>true,'msg'=>'probe']);
});

add_action('wp_ajax_digical_db_get_days', function () {
    if (!digical_ajax_nonce_ok()) wp_send_json_error(['message'=>'Bad/expired nonce — reload.']);
    digical_days_ensure_table();
    wp_send_json_success(['days' => digical_days_all_rows()]);
});

add_action('wp_ajax_digical_db_add_day', function () {
    if (!digical_ajax_nonce_ok()) wp_send_json_error(['message'=>'Bad/expired nonce — reload.']);
    $date = digical_date_clean($_POST['day_date'] ?? '');
    $st   = isset($_POST['start_time']) ? digical_time_norm($_POST['start_time']) : '';
    $et   = isset($_POST['end_time'])   ? digical_time_norm($_POST['end_time'])   : '';

    if (!digical_date_valid_ddmmyyyy($date)) wp_send_json_error(['message'=>'Invalid calendar date (ddmmyyyy).']);
    if (!preg_match('/^\d{2}:\d{2}$/',$st)) wp_send_json_error(['message'=>'Invalid start time (HH:MM).']);
    if (!preg_match('/^\d{2}:\d{2}$/',$et)) wp_send_json_error(['message'=>'Invalid end time (HH:MM).']);

    digical_days_ensure_table();
    digical_days_insert_row($date,$st,$et);
    wp_send_json_success(['days' => digical_days_all_rows()]);
});

add_action('wp_ajax_digical_db_edit_day', function () {
    if (!digical_ajax_nonce_ok()) wp_send_json_error(['message'=>'Bad/expired nonce — reload.']);
    $id   = isset($_POST['id']) ? sanitize_text_field($_POST['id']) : '';
    $date = digical_date_clean($_POST['day_date'] ?? '');
    $st   = isset($_POST['start_time']) ? digical_time_norm($_POST['start_time']) : '';
    $et   = isset($_POST['end_time'])   ? digical_time_norm($_POST['end_time'])   : '';

    if ($id==='') wp_send_json_error(['message'=>'Missing ID.']);
    if (!digical_date_valid_ddmmyyyy($date)) wp_send_json_error(['message'=>'Invalid calendar date (ddmmyyyy).']);
    if (!preg_match('/^\d{2}:\d{2}$/',$st)) wp_send_json_error(['message'=>'Invalid start time (HH:MM).']);
    if (!preg_match('/^\d{2}:\d{2}$/',$et)) wp_send_json_error(['message'=>'Invalid end time (HH:MM).']);

    digical_days_ensure_table();
    $ok = digical_days_update_row($id,$date,$st,$et);
    if ($ok===false) wp_send_json_error(['message'=>'Update failed.']);
    wp_send_json_success(['days' => digical_days_all_rows()]);
});

add_action('wp_ajax_digical_db_delete_day', function () {
    if (!digical_ajax_nonce_ok()) wp_send_json_error(['message'=>'Bad/expired nonce — reload.']);
    $id = isset($_POST['id']) ? sanitize_text_field($_POST['id']) : '';
    if ($id==='') wp_send_json_error(['message'=>'Missing ID.']);
    digical_days_ensure_table();
    $ok = digical_days_delete_row($id);
    if (!$ok) wp_send_json_error(['message'=>'Row not found.']);
    wp_send_json_success(['days' => digical_days_all_rows()]);
});

/* -------- Bulk delete (accepts ids[] or comma string) -------- */
add_action('wp_ajax_digical_db_delete_days', function () {
    if (!digical_ajax_nonce_ok()) wp_send_json_error(['message'=>'Bad/expired nonce — reload.']);

    $raw = $_POST['ids'] ?? [];
    if (is_string($raw)) {
        $ids = array_filter(array_map('trim', explode(',', $raw)));
    } else {
        $ids = array_values(array_filter(array_map('sanitize_text_field', (array)$raw)));
    }
    if (empty($ids)) wp_send_json_error(['message'=>'No IDs provided.']);

    global $wpdb; $t = DIGICAL_DAYS_TABLE;
    $placeholders = implode(',', array_fill(0, count($ids), '%s'));
    $sql = $wpdb->prepare("DELETE FROM `$t` WHERE id IN ($placeholders)", $ids);
    $wpdb->query($sql);

    wp_send_json_success(['days' => digical_days_all_rows()]);
});
