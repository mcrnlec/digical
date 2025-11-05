<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="wrap">
    <h1>Day Plan: <?php echo esc_html($day->post_title); ?></h1>
    <p>Build the schedule for this day here.</p>
    <?php
    // Fetch Venues
    $venues = get_posts(['post_type' => 'digical_venue', 'numberposts' => -1]);
    // Fetch Speakers
    $speakers = get_posts(['post_type' => 'digical_speaker', 'numberposts' => -1]);

    ?>
    <form method="post" action="">
        <table class="form-table">
            <tr>
                <th>Session Title</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Venue</th>
                <th>Speaker</th>
            </tr>
            <tr>
                <td><input type="text" name="session_title[]" required></td>
                <td><input type="time" name="session_start[]" required></td>
                <td><input type="time" name="session_end[]" required></td>
                <td>
                    <select name="session_venue[]">
                        <?php foreach ($venues as $venue): ?>
                            <option value="<?php echo esc_attr($venue->ID); ?>"><?php echo esc_html($venue->post_title); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <select name="session_speaker[]">
                        <?php foreach ($speakers as $speaker): ?>
                            <option value="<?php echo esc_attr($speaker->ID); ?>"><?php echo esc_html($speaker->post_title); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <!-- Add JS dynamically to add more rows (implement later) -->
        </table>
        <p><button type="button" id="add-session-row" class="button">Add Session</button></p>
        <p><input type="submit" name="save_day_plan" class="button-primary" value="Save Plan"></p>
    </form>
</div>

<script>
jQuery(document).ready(function ($) {
    $('#add-session-row').click(function () {
        var row = $('table.form-table tr:last').clone();
        row.find('input').val('');
        $('table.form-table').append(row);
    });
});
</script>
