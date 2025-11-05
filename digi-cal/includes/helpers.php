<?php
// Example date formatting helper for future use
function digical_format_datetime($datetime) {
    return date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($datetime));
}
