<?php
// Add meta boxes for custom fields like start/end times for Days, venue addresses, speaker details

add_action('add_meta_boxes', 'digical_add_meta_boxes');
function digical_add_meta_boxes() {
    add_meta_box('digical_day_times', 'Day Start/End Time', 'digical_day_times_callback', 'digical_day', 'normal', 'default');
    add_meta_box('digical_venue_details', 'Venue Details', 'digical_venue_details_callback', 'digical_venue', 'normal', 'default');
    add_meta_box('digical_speaker_details', 'Speaker Details', 'digical_speaker_details_callback', 'digical_speaker', 'normal', 'default');
}

function digical_day_times_callback($post) {
    wp_nonce_field('digical_save_day_times', 'digical_day_times_nonce');
    $start = get_post_meta($post->ID, 'start_time', true);
    $end = get_post_meta($post->ID, 'end_time', true);
    ?>
    <label for="digical_start_time">Start Time:</label>
    <input type="datetime-local" id="digical_start_time" name="digical_start_time" value="<?php echo esc_attr($start); ?>" />
    <br><br>
    <label for="digical_end_time">End Time:</label>
    <input type="datetime-local" id="digical_end_time" name="digical_end_time" value="<?php echo esc_attr($end); ?>" />
    <?php
}

function digical_venue_details_callback($post) {
    wp_nonce_field('digical_save_venue_details', 'digical_venue_details_nonce');
    $address = get_post_meta($post->ID, 'venue_address', true);
    $is_main = get_post_meta($post->ID, 'is_main_venue', true);
    ?>
    <label for="venue_address">Address:</label>
    <textarea id="venue_address" name="venue_address" rows="3" cols="40"><?php echo esc_textarea($address); ?></textarea>
    <br><br>
    <label for="is_main_venue">Is Main Venue?</label>
    <input type="checkbox" id="is_main_venue" name="is_main_venue" value="1" <?php checked($is_main, '1'); ?> />
    <?php
}

function digical_speaker_details_callback($post) {
    wp_nonce_field('digical_save_speaker_details', 'digical_speaker_details_nonce');
    $bio = get_post_meta($post->ID, 'speaker_bio', true);
    ?>
    <label for="speaker_bio">Biography:</label>
    <textarea id="speaker_bio" name="speaker_bio" rows="5" cols="50"><?php echo esc_textarea($bio); ?></textarea>
    <?php
}

// Save meta box data
add_action('save_post', 'digical_save_meta_boxes');
function digical_save_meta_boxes($post_id) {
    // Days start/end time
    if (isset($_POST['digical_day_times_nonce']) && wp_verify_nonce($_POST['digical_day_times_nonce'], 'digical_save_day_times')) {
        if (isset($_POST['digical_start_time'])) {
            update_post_meta($post_id, 'start_time', sanitize_text_field($_POST['digical_start_time']));
        }
        if (isset($_POST['digical_end_time'])) {
            update_post_meta($post_id, 'end_time', sanitize_text_field($_POST['digical_end_time']));
        }
    }
    // Venue details
    if (isset($_POST['digical_venue_details_nonce']) && wp_verify_nonce($_POST['digical_venue_details_nonce'], 'digical_save_venue_details')) {
        if (isset($_POST['venue_address'])) {
            update_post_meta($post_id, 'venue_address', sanitize_textarea_field($_POST['venue_address']));
        }
        $is_main = isset($_POST['is_main_venue']) ? '1' : '0';
        update_post_meta($post_id, 'is_main_venue', $is_main);
    }
    // Speaker bio
    if (isset($_POST['digical_speaker_details_nonce']) && wp_verify_nonce($_POST['digical_speaker_details_nonce'], 'digical_save_speaker_details')) {
        if (isset($_POST['speaker_bio'])) {
            update_post_meta($post_id, 'speaker_bio', sanitize_textarea_field($_POST['speaker_bio']));
        }
    }
}
