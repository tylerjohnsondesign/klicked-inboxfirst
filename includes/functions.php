<?php
/**
Get Lists
**/
function ifklicked_get_lists($key) {
    if(empty($key)) {
        $key = IFKLICKED_IBF_KEY;
    }
    
    if(!empty($key)) {
        // Define variables
        $url = IFKLICKED_IBF_URL.'/v2/mailing_lists';
        $ch  = curl_init($url);

        // Setup cURL to get lists
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $key);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute the command
        $response_raw   = curl_exec($ch);
        $error_test     = curl_error($ch);
        $err            = curl_errno($ch);

        // Close the connection
        curl_close($ch);

        // Check for HTTP errors
        if($err != 0) {
            $iferror = 'ERROR: cURL - ' . $err . $error_test;
            return $iferror;
        }

        // Return IF's response
        $results = json_decode($response_raw, true);
        return $results;
    } else {
        return false;
    }
}

/**
Get Pages
**/
function ifklicked_get_templates() {
    // WP_Query arguments
    $args = array(
        'post_type'              => array( 'page' ),
        'post_status'            => array( 'publish' ),
        'nopaging'               => true,
    );

    // The Query
    $templates = new WP_Query($args);

    // Array
    $newtemplates = array();

    // The Loop
    if ($templates->have_posts()) { 
        while ($templates->have_posts()) {
            $templates->the_post();
            
            $information = array(
                'id'    => get_the_ID(),
                'title' => get_the_title(),
            );

            // Compose
            $newtemplates[] = $information;

        }

        // Restore original Post Data
        wp_reset_postdata();

        return $newtemplates;
    } else {
        // Don't do anything.
    }
}

/**
Get Segmentats
**/
function ifklicked_get_segments($key) {
    if(empty($key)) {
        $key = IFKLICKED_IBF_KEY;
    }
    
    if(!empty($key)) {
        // Define variables
        $url = IFKLICKED_IBF_URL.'/v2/segmentation_criterias';
        $ch  = curl_init($url);

        // Setup cURL to get lists
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $key);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute the command
        $response_raw   = curl_exec($ch);
        $error_test     = curl_error($ch);
        $err            = curl_errno($ch);

        // Close the connection
        curl_close($ch);

        // Check for HTTP errors
        if($err != 0) {
            $iferror = 'ERROR: cURL - ' . $err . $error_test;
            return $iferror;
        }

        // Return IF's response
        $results = json_decode($response_raw, true);
        return $results;
    } else {
        return false;
    }
}

/**
Get Times
**/
function ifklicked_get_times($now) {
    if($now == 'true') {
        $time = array('Now', '12:00AM', '1:00AM', '2:00AM', '3:00AM', '4:00AM', '5:00AM', '6:00AM', '7:00AM', '8:00AM', '9:00AM', '10:00AM', '11:00AM', '12:00PM', '1:00PM', '2:00PM', '3:00PM', '4:00PM', '5:00PM', '6:00PM', '7:00PM', '8:00PM', '9:00PM', '10:00PM', '11:00PM');   
    } else {
        $time = array('12:00AM', '1:00AM', '2:00AM', '3:00AM', '4:00AM', '5:00AM', '6:00AM', '7:00AM', '8:00AM', '9:00AM', '10:00AM', '11:00AM', '12:00PM', '1:00PM', '2:00PM', '3:00PM', '4:00PM', '5:00PM', '6:00PM', '7:00PM', '8:00PM', '9:00PM', '10:00PM', '11:00PM');
    }
    return $time;
}

function ifklicked_if_test() {
    $validator = new \EmailValidator\Validator();
    $test = $validator->isValid('abuse@klicked.com');
    if($test === false) {
        $output = 'Invalid.';
    } else {
        $output = 'Valid.';
    }
    
    return $output;
}
add_shortcode('iftest', 'ifklicked_if_test');

/**
Add Subscribe Page to Editor
**/
function ifklicked_add_templates($templates) {
    // Add the template
    $templates['templates/subscribe.php'] = 'Subscribe Template';
    
    // Return the templates
    return $templates;
}
add_filter('theme_page_templates', 'ifklicked_add_templates');

/**
Output the Subscribe Template
**/
// On desktop and mobile, if WP Touch Pro isn't enabled
function ifklicked_subscribe_template($page_template) {
    // Get subscribe page
    $created = get_option('ifklicked_subscribe_page');
    
    // Output subscribe page, if subscribe page doesn't exist
    if(!is_admin() && is_page_template('templates/subscribe.php') && $created == '1' || !is_admin() && is_page('subscribe') && $created == '1') {
        $page_template = IFKLICKED_BASE_PATH.'templates/subscribe.php';
    }
    
    // Return the templates
    return $page_template;
}
add_filter('page_template', 'ifklicked_subscribe_template');

// On mobile, if WP Touch Pro is enabled
function ifklicked_subscribe_template_wptouch() {
    // Variables
    $template = get_post_meta(get_the_ID(), '_mobile_page_template', true);
    
    // Check for subscribe.php
    if($template === 'subscribe.php') {
        return false;
    } else {
        return true;
    }
}
if(is_plugin_active('wptouch-pro/wptouch-pro.php')) {
    add_filter('wptouch_should_show_mobile_theme', 'ifklicked_subscribe_template_wptouch', 10, 2);
}

/**
Send Notification Email
**/
function ifklicked_email_notification($address, $schedule) {
    // Checks
    if(empty($address)) {
        // Don't take another step. DO NOTHING! Nothing at all, you hear! 
    } else {
        // Compose
        $to = $address;
        $subject = 'Error Sending Campaign on '.get_bloginfo('name').' at '.date('F j, Y, g:i a');
        $message = 'Hello! There was an issue sending the campaign from schedule '.$schedule.' on '.get_bloginfo('name').' ('.get_bloginfo('url').'). To view this issue, please go to '.get_bloginfo('url').'/wp-admin/admin.php?page=inbox-first-schedule. Thank you!';
        
        // Send
        wp_mail($to, $subject, $message);
    }  
}