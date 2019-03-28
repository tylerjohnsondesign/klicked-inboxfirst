<?php
/**
Required Files
**/
require_once(IFKLICKED_BASE_PATH.'admin/includes/settings.php');
require_once(IFKLICKED_BASE_PATH.'admin/includes/schedule.php');
require_once(IFKLICKED_BASE_PATH.'admin/includes/send.php');
require_once(IFKLICKED_BASE_PATH.'admin/includes/subscribe.php');
require_once(IFKLICKED_BASE_PATH.'admin/includes/forms.php');

/**
Run Classes
**/
if(is_admin()) {
    $klickedIFSettings = new klickedIFSettings();
    $klickedIFSchedule = new klickedIFSchedule();
    $klickedIFSend = new klickedIFSend();
    $klickedIFSubscribe = new klickedIFSubscribe();
    $klickedIFForms = new klickedIFForms();
}

/**
Admin Enqueue
**/
function ifklicked_admin_enqueue() {
    // Check for page
    if(isset($_GET['page'])) {
        // Variables
        $page = $_GET['page'];
        
        // Enqueue on Admin Pages
        if($page == 'inbox-first-schedule' || $page == 'inbox-first-send' || $page == 'inbox-first' || $page = 'inbox-first-forms' || $page == 'inbox-first-subscribe') {
            wp_enqueue_style('ifklicked-admin-css', IFKLICKED_BASE_URI.'admin/assets/admin.css', '', IFKLICKED_BASE_VERSION, '');
            wp_enqueue_script('ifklicked-admin-js', IFKLICKED_BASE_URI.'admin/assets/admin.js', array('jquery'), IFKLICKED_BASE_VERSION, 'true');
        }
        
        // Enqueue on Settings Page
        if($page == 'inbox-first' || $page == 'inbox-first-forms' || $page == 'inbox-first-subscribe') {
            wp_enqueue_style('ifklicked-settings-css', IFKLICKED_BASE_URI.'admin/assets/settings/settings.css', '', IFKLICKED_BASE_VERSION, '');
        }
        
        // Enqueue on Schedule Page
        if($page == 'inbox-first-schedule') {
            wp_enqueue_style('schedule-css', IFKLICKED_BASE_URI.'admin/assets/schedule/schedule.css', '', IFKLICKED_BASE_VERSION, '');
            wp_enqueue_script('schedule-js', IFKLICKED_BASE_URI.'admin/assets/schedule/schedule.js', array('jquery'), IFKLICKED_BASE_VERSION, 'true');
        }
        
        // Enqueue on Send Page
        if($page == 'inbox-first-send') {
            wp_enqueue_style('send-css', IFKLICKED_BASE_URI.'admin/assets/send/send.css', '', IFKLICKED_BASE_VERSION, '');
            wp_enqueue_script('send-js', IFKLICKED_BASE_URI.'admin/assets/send/send.js', array('jquery', 'jquery-ui-dialog', 'jquery-ui-datepicker'), IFKLICKED_BASE_VERSION, 'true');
            wp_enqueue_script('ajax-send', IFKLICKED_BASE_URI.'admin/assets/send/ajax-send.js', array('jquery'), IFKLICKED_BASE_VERSION, 'true');
            wp_localize_script('ajax-send', 'ifsend', array(
                'ajax_url'  => admin_url('admin-ajax.php'),
            ));
        }
        
        // Enqueue on Subscribe Page
        if($page == 'inbox-first-subscribe') {
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_media();
            wp_enqueue_script('admin-subscribe', IFKLICKED_BASE_URI.'admin/assets/subscribe/subscribe.js', array('jquery', 'wp-color-picker'), IFKLICKED_BASE_VERSION, 'true');
        }
        
    }
}
add_action('admin_enqueue_scripts', 'ifklicked_admin_enqueue', 99);