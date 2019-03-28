<?php

/**
Add Cron Schedule
**/
function ifklicked_schedule_cron() {
    if(!wp_next_scheduled('ifklicked_schedule_check')) {
        wp_schedule_event(time(), 'hourly', 'ifklicked_schedule_check');
    }
}
add_action('init', 'ifklicked_schedule_cron');

/**
Cron Function
**/
function ifklicked_schedule_compose() {
    // Variables
    $ifkopts    = get_option('ifklicked_schedule_option_name');
    $schedules  = array('one', 'two', 'three', 'four');
    $days       = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
    
    // Check different schedules
    foreach($schedules as $schedule) {
        // If schedule is enabled
        if(!empty($ifkopts['enable_schedule_'.$schedule])) {
            // Create day schedule array
            $scheduledays = array();
            
            // Check days
            foreach($days as $day) {
                if(!empty($ifkopts['cron_schedule_'.$schedule.'_'.$day])) {
                    $scheduledays[] .= $day;
                }
            }
            
            // Time
            $time   = $ifkopts['time_schedule_'.$schedule];
            $hour   = str_replace(array(':00AM', ':00PM'), '', $time);
            if(preg_match('/AM/', $time)) {
                $meridiem = 'AM';
            } else {
                $meridiem = 'PM';
            }
            
            // Set timezone and get current time
            date_default_timezone_set(get_option('timezone_string'));
            $todayday       = date('l');
            $todayhour      = date('h');
            $todayminute    = date('i');
            $todaymeridiem  = date('A');
            
            // Check if sending today for this schedule
            if(in_array($todayday, $scheduledays)) {
                // Check if sending this hour
                if($todayhour == $hour && $todaymeridiem == $meridiem) {
                    ifklicked_schedule_send_campaign($schedule);
                }
            } else {
                // We are not sending today. No worries.
            }
        } else {
            // Scheduling isn't enabled. So, let's not doing anything.
        }
    }
}
add_action('ifklicked_schedule_check', 'ifklicked_schedule_compose');