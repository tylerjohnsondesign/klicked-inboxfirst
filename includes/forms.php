<?php
/**
Subscribe Form Output
**/
function ifklicked_subscribe_form($atts) {
    // Get attributes
    extract(shortcode_atts(array(
        'list' => '',
        'submit' => '',
    ), $atts));
    
    // Check variables
    if(empty($list)) {
        $list = false;
    } else {
        $list = str_replace('list-', '', $list);
    }
    if(empty($submit)) {
        $submit = 'Subscribe';
    } else {
        $submit = $submit;
    }
    
    // Random number
    $rand = rand(0,100);
    
    // Output
    if($list === false) {
        if(is_user_logged_in()) {
            $output = '<div class="ifklicked-subscribe"><p>NOTE: Please supply a list ID. Message only shows for logged in users.</p></div>';
        } else {
            $output = '';
        }
    } else {
        $output = '<div class="ifklicked-subscribe"><input id="form-'.$rand.'" type="text" placeholder="Email Address" class="ifklicked-subscribe-email" data-id="'.$list.'" /><button class="ifklicked-subscribe-btn" data-input="form-'.$rand.'">'.$submit.'</button></div><span class="ifklicked-loading-form-'.$rand.'" style="display: none"><svg width="28px" height="28px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" class="lds-dual-ring" style="animation-play-state: running; animation-delay: 0s; background: none;"><circle cx="50" cy="50" ng-attr-r="{{config.radius}}" ng-attr-stroke-width="{{config.width}}" ng-attr-stroke="{{config.stroke}}" ng-attr-stroke-dasharray="{{config.dasharray}}" fill="none" stroke-linecap="round" r="40" stroke-width="15" stroke="#bebebe" stroke-dasharray="62.83185307179586 62.83185307179586" transform="rotate(189.733 50 50)" style="animation-play-state: running; animation-delay: 0s;"><animateTransform attributeName="transform" type="rotate" calcMode="linear" values="0 50 50;360 50 50" keyTimes="0;1" dur="1.2s" begin="0s" repeatCount="indefinite" style="animation-play-state: running; animation-delay: 0s;"></animateTransform></circle></svg></span><p class="ifklicked-message-form-'.$rand.'" style="display: none"></p>';
    }
    
    // Output forms
    return $output;
}
add_shortcode('ifform', 'ifklicked_subscribe_form');

/**
Inbox First Email Import
**/
function ifklicked_subscribe_import() {  
    // See if we're doing AJAX
    if(defined('DOING_AJAX') && DOING_AJAX) {
        // Variables
        $email  = $_POST['email'];
        $list   = $_POST['list'];
        $url    = IFKLICKED_IBF_URL.'/v2/mailing_lists/'.$list.'/subscribers';
        
        // Validate
        if(!empty($email)) {
            $validator = new \EmailValidator\Validator();
            $test = $validator->isValid($email);
        } else {
            $return_value = '<p class="error">Please enter an email address.</p>';
        }
        
        // If email is invalid
        if($test === false || $test === null) {
            $return_value = '<p class="error">Please enter a valid email address.</p><style>.if-signup-sidebar .ifklicked-subscribe {display: block!important;margin: 0 auto 14px;}</style>';
        }
        
        // If email is valid
        if($test === true && !empty($list)) {
            // Set time
            date_default_timezone_set(get_option('timezone_string'));
            $time = date('c');
            
            // Create subscriber variable
            $subscriber = array(
                'subscriber' => array(
                    'email' => $email,
                    'email_format' => 'html',
                    'status' => 'active',
                    'mailing_list_id' => $list,
                    'subscribe_ip' => null,
                    'subscribe_time' => $time,
                ),
            );
            
            // Start cURL
            $ch     = curl_init($url);
            $json   = json_encode($subscriber);
            
            // Set cURL Options
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, IFKLICKED_IBF_KEY);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: '.strlen($json),
            ));
            
            // Execute the command, retrieve HTTP errors.
            $response_raw = curl_exec($ch);
            $error_test   = curl_error($ch);
            $err          = curl_errno($ch);

            // Don't leave the connection hanging.
            curl_close($ch);

            // First, check if there was an HTTP error.
            if ($err != 0) {
                $rv = "ERROR: cURL - $err $error_test\n";
                return $rv;
            }

            // Decode InboxFirst's response JSON.
            $result = json_decode($response_raw);
            $ifreturned = json_decode($response_raw, true);

            // Check responses
            if(isset($result->success)) {
                if($result->success == false) {
                    $return_value = 'Error:';

                    if(isset($result->error_message)) {
                        $return_value .= ' '.$result->error_message.'.';
                    }
                } elseif($result->success == true) {
                    $return_value = 'Subscribed.';
                } else {
                    $return_value = 'Error: Unknown status. Please contact the developer.';
                }
            } else {
                $return_value = 'Error: Unknown response from Inbox First.';
            }

            // Check message to be stored
            if($return_value === 'Subscribed.') {
                $return_value = 'Thank you for subscribing!';
            } elseif($return_value === 'Error: Email ^"Email" is the primary key for this mailing list, and this value is already taken by a different subscriber in the list., Email address ^"Email" is the primary key for this mailing list, and this value is already taken by a different subscriber in the list..') {
                $return_value = '<p class="error">Sorry, but you are already subscribed.</p>';
            } else {
                $return_value = '<p class="error">Sorry, there was an error with that email address, perhaps because it\'s already signed up to receive the newsletter. Please try again with a different email address.</p><style>.if-signup-sidebar .ifklicked-subscribe {display: block!important;margin: 0 auto 14px;}</style>';
            }
        }
        
        // Return response
        echo $return_value;
    }
    
    die; // Execute order 66.
}
add_action('wp_ajax_nopriv_ifklicked_subscribe_import', 'ifklicked_subscribe_import');
add_action('wp_ajax_ifklicked_subscribe_import', 'ifklicked_subscribe_import');
