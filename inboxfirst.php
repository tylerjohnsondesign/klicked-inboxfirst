<?php
/*
Plugin Name: InboxFirst by Klicked Media
Plugin URI: https://klicked.com
Description: Send Inbox First campaigns via a schedule or at the click of a button.
Version: 2.0.6
Author: Tyler Johnson
Author URI: http://tylerjohnsondesign.com/
Copyright: Tyler Johnson
Text Domain: ifklicked
Copyright © 2019 WP Developers. All Rights Reserved.
*/

/**
Disallow Direct Access to Plugin File
**/
if(!defined('WPINC')) { die; } 

/**
Get API Key
**/
$options = get_option('ifklicked_main_option_name');
$apikey = $options['ifklicked_api_key'];

/**
Constants 
**/
define('IFKLICKED_BASE_VERSION', '2.0.6');
define('IFKLICKED_BASE_PATH', trailingslashit(plugin_dir_path(__FILE__)));
define('IFKLICKED_BASE_URI', trailingslashit(plugin_dir_url(__FILE__)));
define('IFKLICKED_IBF_URL', 'http://if.inboxfirst.com/ga/api');
if(!empty($apikey)) {
    define('IFKLICKED_IBF_KEY', base64_decode($apikey)); 
} else {
    define('IFKLICKED_IBF_KEY', '');
}

/**
Updates
**/
require IFKLICKED_BASE_PATH.'updates/plugin-update-checker.php';
$KlickedMobUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://github.com/klickedmedia/inboxfirst-by-klicked',
    __FILE__,
    'inboxfirst-by-klicked'
);
// Authentication token
$KlickedMobUpdateChecker->setAuthentication('049e3a5256f906850ffa5cb9c60ddc3696b85e75');
// Set stable branch
$KlickedMobUpdateChecker->setBranch('master');

/**
On Activation
**/
function ifklicked__activate() {
    // Get subscribe page
    $page = get_page_by_path('subscribe');
    
    // Add subscribe page, if subscribe page doesn't exist
    if(empty($page)) {
        // Run the function
        ifklicked_add_subscribe_page();
        
        // Trigger permalink flush
        flush_rewrite_rules();
    }
}
register_activation_hook(__FILE__, 'ifklicked__activate');

/**
On Deactivation
**/
function ifklicked__deactivate() {
    // Check if we installed the subscribe page
    $subscribe = get_option('ifklicked_subscribe_page');
    
    // If we did install the subscribe page, remove it on deactivation
    if($subscribe == '1') {
        // Run the remove function
        ifklicked_remove_subscribe_page();
        
        // Clean up the permalinks
        flush_rewrite_rules();
    }
}
register_deactivation_hook(__FILE__, 'ifklicked__deactivate');

/**
Includes
**/
// Functions
require_once(IFKLICKED_BASE_PATH.'includes/functions.php');
// Settings
require_once(IFKLICKED_BASE_PATH.'admin/settings.php');
// Send
require_once(IFKLICKED_BASE_PATH.'includes/send.php');
// Cron
require_once(IFKLICKED_BASE_PATH.'includes/cron.php');
// Validation
require_once(IFKLICKED_BASE_PATH.'includes/validation.php');
// Forms
require_once(IFKLICKED_BASE_PATH.'includes/forms.php');
// Enqueue
require_once(IFKLICKED_BASE_PATH.'includes/enqueue.php');

/**
Add Subscribe Page
**/
function ifklicked_add_subscribe_page() {
    // Setup page
    $subpage = array(
        'Subscribe Page' => array(
            'title' => 'Subscribe',
            'slug'  => 'subscribe',
            'temp'  => 'subscribe.php',
        ),
    );
    
    // Create page
    $subscribe = wp_insert_post(array(
        'post_title'        => 'Subscribe',
        'post_type'         => 'page',
        'post_name'         => 'subscribe',
        'comment_status'    => 'closed',
        'ping_status'       => 'closed',
        'post_content'      => '',
        'post_status'       => 'publish',
        'menu_order'        => 0,
        'page_template'     => 'subscribe.php',
    ));
    
    // Save that we created a subscribe page
    update_option('ifklicked_subscribe_page', '1', true);
    update_post_meta($subscribe->ID, '_mobile_page_template', 'subscribe.php');
}


/**
Remove Subscribe Page
**/
function ifklicked_remove_subscribe_page() {
    // Get page ID
    $pageid = get_page_by_path('subscribe');
    
    // Delete page
    wp_delete_post($pageid->ID, true);
    
    // Save that we deleted the subscribe page
    update_option('ifklicked_subscribe_page', '0', true);
}
