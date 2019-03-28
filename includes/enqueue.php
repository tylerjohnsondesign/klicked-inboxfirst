<?php
/**
Enqueue
**/
function ifklicked_enqueue() {
    // Enqueue subscribe script
    wp_enqueue_script('if-subscribe', IFKLICKED_BASE_URI.'assets/subscribe.js', array('jquery'), IFKLICKED_BASE_VERSION, 'true');
    wp_localize_script('if-subscribe', 'ifsub', array(
        'ajax_url'  => admin_url('admin-ajax.php'),
    ));
    
     // Enqueue styles and scripts for subscribe page
    $subscribe = get_option('ifklicked_subscribe_page');
    
    if($subscribe == '1' && is_page('subscribe')) {
        wp_enqueue_style('if-subscribe-base', IFKLICKED_BASE_URI.'templates/assets/base.css', '', IFKLICKED_BASE_VERSION, '');
        wp_enqueue_style('if-subscribe-style', IFKLICKED_BASE_URI.'templates/assets/styles.css', '', IFKLICKED_BASE_VERSION, '');
        wp_enqueue_script('if-subscribe-script', IFKLICKED_BASE_URI.'templates/assets/scripts.js', array('jquery'), IFKLICKED_BASE_VERSION, 'true');
        wp_enqueue_script('if-subscribe-custom', IFKLICKED_BASE_URI.'templates/assets/custom.js', array('jquery'), IFKLICKED_BASE_VERSION, 'true');
    }
}
add_action('wp_enqueue_scripts', 'ifklicked_enqueue', 99);