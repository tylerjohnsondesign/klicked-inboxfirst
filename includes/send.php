<?php
/**
Send Schedule Campaign
**/
function ifklicked_schedule_send_campaign($schedule) {
    // Variables
    $opts       = get_option('ifklicked_schedule_option_name');
    $list       = str_replace('list-', '', $opts['list_schedule_'.$schedule]);
    $url        = IFKLICKED_IBF_URL.'/v2/mailing_lists/'.$list.'/campaigns';
    $template   = $opts['ifklicked_template_schedule_'.$schedule];
    $html       = get_permalink(str_replace('id-', '', $opts['template_schedule_'.$schedule]));
    $settime    = $opts['time_schedule_'.$schedule];
    $fromemail  = $opts['from_email_schedule_'.$schedule];
    $fromname   = $opts['from_name_schedule_'.$schedule];
    $subject    = $opts['ifklicked_subject_schedule_'.$schedule];
    $segment    = str_replace('segment-', '', $opts['segmentation_schedule_'.$schedule]);
    $key        = $opts['ifklicked_apikey_schedule_'.$schedule];
    $notify     = $opts['ifklicked_notify_schedule_'.$schedule];
    
    // Checks
        // API Key Check
        if(empty($key)) {
            $key = IFKLICKED_IBF_KEY;
        } else {
            $key = base64_decode($key);
        }
        
        // Subject check
        if($subject === 'default') {
            $subject = 'Recent Articles from '.get_bloginfo('name').' on '.date('F j, Y').' at '.date('h:i A');
        } else {
            $htmltitle  = file_get_contents($html);
            $title      = preg_match('/<title[^>]*>(.*?)<\/title>/ims', $htmltitle, $matches) ? $matches[1] : null;
            $subject    = $title;
        }
    
        // Time check
        date_default_timezone_set(get_option('timezone_string'));
    
        $date = date('Y-m-d');
        $hour = str_replace(array(':00AM', ':00PM'), '', $settime);
        $meri = str_replace(array($hour, ':00'), '', $settime);
        if(date('i') > 54) {
            $minute = '05';
            if($hour === '12') {
                $hour = '1';
            } else {
                $hour = $hour++;
            }
        } else {
            $minute = date('i') + 5;
        }
        $time = $hour.':'.$minute.$meri;
        $sendtime = $date.' '.$time.' '.date('T');
    
    // If we have a template, go ahead and compose
    if(!empty($template) && !empty($segment)) {
        // Compose
        $compose = array(
            'name' => 'Auto Send from '.get_bloginfo('name').' on '.date('F j, Y').' at '.date('h:i:s A'),
            'mailing_list_id' => $list,
            'segmentation_criteria_id' => $segment,
            'contents' => array(
                array(
                    'name'  => 'Auto Send from '.get_bloginfo('name').' on '.date('F j, Y').' at '.date('h:i:s A'),
                    'format'    => 'html',
                    'subject'   => $subject,
                    'html'  => file_get_contents($html),
                    'text'  => strip_tags(file_get_contents($html), '<a>'),
                ),
            ),
            'dispatch_attributes'   => array(
                'state'                 => 'scheduled',
                'from_email'            => $fromemail,
                'from_name'             => $fromname,
                'speed'                 => 0,
                'virtual_mta_id'        => 0,
                'bounce_email_id'       => '1@1',
                'url_domain_id'         => 1,
                'begins_at'             => $sendtime,
                'track_opens'           => true,
                'track_links'           => '1',
            ),
        );
        
        // Campaign
        $campaign = array(
            'source_template_id' => $template,
            'duplicate_segmentation_criteria' => true,
            'duplicate_dispatch' => true,
            'campaign' => $compose,
        );
        
        // Start cURL
        $ch     = curl_init($url);
        $json   = json_encode($campaign);
        
        // Set cURL Options
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $key);
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
                $return_value = 'Message sent.';
            } else {
                $return_value = 'Error: Unknown status. Please contact the developer.';
            }
        } else {
            $return_value = 'Error: Unknown response from Inbox First.';
        }

        // Check message to be stored
        if($return_value === 'Message sent.') {
            $return_value = '<span class="success-sent">Campaign successfully sent '.date('F j, Y').' at '.date('h:i A').'</span>';
        } else {
            // Save error message.
            $return_value = '<span class="error-sent">'.$return_value.'</span>';
            // Send notification email.
            if(!empty($notify)) {
                ifklicked_email_notification($notify, $schedule);
            }
        }

        // Echo message
        if($schedule == 'one') {
            update_option('ifklicked_email_schedule_one_message', $return_value, true);
        } elseif($schedule == 'two') {
            update_option('ifklicked_email_schedule_two_message', $return_value, true);
        } elseif($schedule == 'three') {
            update_option('ifklicked_email_schedule_three_message', $return_value, true);
        } elseif($schedule == 'four') {
            update_option('ifklicked_email_schedule_four_message', $return_value, true);
        } else {
            // Don't update anything.
        }
    }
}

/**
Send Schedule Campaign
**/
function ifklicked_send_campaign() {
    // Variables
    $list       = str_replace('list-', '', $_POST['list']);
    $url        = IFKLICKED_IBF_URL.'/v2/mailing_lists/'.$list.'/campaigns';
    $template   = $_POST['temp'];
    $html       = get_permalink(str_replace('id-', '', $_POST['url']));
    $date       = $_POST['date'];
    $settime    = $_POST['time'];
    $fromemail  = $_POST['email'];
    $fromname   = $_POST['name'];
    $subject    = $_POST['sub'];
    $segment    = str_replace('segment-', '', $_POST['seg']);
    
    // Checks
        // Subject check
        if($subject === 'default') {
            $subject = 'From '.get_bloginfo('name').' on '.date('F j, Y').' at '.date('h:i A');
        } elseif($subject === 'custom') {
            $subject = $_POST['cus'];
        } else {
            $htmltitle = file_get_contents($html);
            $title      = preg_match('/<title[^>]*>(.*?)<\/title>/ims', $htmltitle, $matches) ? $matches[1] : null;
            $subject    = $title;
        }
    
        // Time check
        date_default_timezone_set(get_option('timezone_string'));
    
        if($settime === 'Now') {
            $hour = date('h');
            $meri = date('A');
            if(date('i') > 54) {
                $minute = '05';
                if($hour === '12') {
                    $hour = '1';
                    if($meri === 'PM') {
                        $meri = 'AM';
                    } else {
                        $meri = 'PM';
                    }
                } else {
                    $hour = $hour++;
                }
            } else {
                $minute = date('i') + 5;
            }
        } else {
            $hour = str_replace(array(':00AM', ':00PM'), '', $settime);
            $meri = str_replace(array($hour, ':00'), '', $settime);
            $minute = '00';
        }
        $time = $hour.':'.$minute.$meri;
        $sendtime = $date.' '.$time.' '.date('T');
    
    // If we have a template and we're doing AJAX, go ahead and compose
    if(!empty($template) && !empty($segment) && defined('DOING_AJAX') && DOING_AJAX) {
        
        // Compose
        $compose = array(
            'name' => 'Auto Send from '.get_bloginfo('name').' on '.date('F j, Y').' at '.date('h:i:s A'),
            'mailing_list_id' => $list,
            'segmentation_criteria_id' => $segment,
            'contents' => array(
                array(
                    'name' => 'Auto Send from '.get_bloginfo('name').' on '.date('F j, Y').' at '.date('h:i:s A'),
                    'format'  => 'html',
                    'subject' => $subject,
                    'html' => file_get_contents($html),
                    'text' => strip_tags(file_get_contents($html), '<a>'),
                ),
            ),
            'dispatch_attributes'   => array(
                'state'                 => 'scheduled',
                'from_email'            => $fromemail,
                'from_name'             => $fromname,
                'speed'                 => 0,
                'virtual_mta_id'        => 0,
                'bounce_email_id'       => '1@1',
                'url_domain_id'         => 1,
                'begins_at'             => $sendtime,
                'track_opens'           => true,
                'track_links'           => '1',
            ),
        );
        
        // Campaign
        $campaign = array(
            'source_template_id' => $template,
            'duplicate_segmentation_criteria' => true,
            'duplicate_dispatch' => true,
            'campaign' => $compose,
        );
        
        // Start cURL
        $ch     = curl_init($url);
        $json   = json_encode($campaign);
        
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
                $return_value = 'Message sent.';
            } else {
                $return_value = 'Error: Unknown status. Please contact the developer.';
            }
        } else {
            $return_value = 'Error: Unknown response from Inbox First.';
        }

        // Check message to be stored
        if($return_value === 'Message sent.') {
            if($_POST['time'] === 'Now') {
                $return_value = '<span class="success">Campaign successfully sent.</span>';   
            } else {
                $return_value = '<span class="success">Campaign successfully scheduled for '.$sendtime.'.</span>';
            }
        } else {
            $return_value = '<span class="error">'.$return_value.'</span>';
        }

        echo $return_value;
    }
    
    // Kill it with fire
    die();
}
add_action('wp_ajax_nopriv_ifklicked_send_campaign', 'ifklicked_send_campaign');
add_action('wp_ajax_ifklicked_send_campaign', 'ifklicked_send_campaign');