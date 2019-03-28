<?php
/**
Schedule
**/
class klickedIFSchedule {
	private $ifklicked_schedule_options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'ifklicked_schedule_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'ifklicked_schedule_page_init' ) );
	}

	public function ifklicked_schedule_add_plugin_page() {
        add_submenu_page(
            'inbox-first',
            'Schedule',
            'Schedule',
            'publish_pages',
            'inbox-first-schedule',
            array($this, 'ifklicked_schedule_create_admin_page')
        );
	}

	public function ifklicked_schedule_create_admin_page() {
        include (IFKLICKED_BASE_PATH.'admin/templates/template-schedule.php');
	}

	public function ifklicked_schedule_page_init() {
		register_setting(
			'ifklicked_schedule_option_group', // option_group
			'ifklicked_schedule_option_name', // option_name
			array( $this, 'ifklicked_schedule_sanitize' ) // sanitize_callback
		);
        
        // Schedules
        $schedules = array('one', 'two', 'three', 'four');
        foreach($schedules as $schedule) {
            // Sections
            add_settings_section(
                'ifklicked_schedule_'.$schedule.'_section', // id
                '<h2 id="schedule-'.($schedule).'" class="schedule-sections">Schedule '.ucfirst($schedule).get_option('ifklicked_email_schedule_'.$schedule.'_message').'</h2>',
                array($this, 'ifklicked_schedule_'.$schedule.'_section_info'), // callback
                'inbox-first-schedule' // page
            );
            
            // Enable
            add_settings_field(
                'enable_schedule_'.$schedule,
                'Enable Schedule',
                array($this, 'enable_schedule_'.$schedule.'_callback'),
                'inbox-first-schedule',
                'ifklicked_schedule_'.$schedule.'_section'
            );
            
            // List
            add_settings_field(
                'list_schedule_'.$schedule,
                'List',
                array($this, 'list_schedule_'.$schedule.'_callback'),
                'inbox-first-schedule',
                'ifklicked_schedule_'.$schedule.'_section'
            );
            
            // Segmentation
            add_settings_field(
                'segmentation_schedule_'.$schedule,
                'Segment',
                array($this, 'segmentation_schedule_'.$schedule.'_callback'),
                'inbox-first-schedule',
                'ifklicked_schedule_'.$schedule.'_section'
            );
            
            // Campaign Template
            add_settings_field(
                'ifklicked_template_schedule_'.$schedule,
                'Campaign Template ID',
                array($this, 'ifklicked_template_schedule_'.$schedule.'_callback'),
                'inbox-first-schedule',
                'ifklicked_schedule_'.$schedule.'_section'
            );
            
            // Email Template
            add_settings_field(
                'template_schedule_'.$schedule,
                'Email Template',
                array($this, 'template_schedule_'.$schedule.'_callback'),
                'inbox-first-schedule',
                'ifklicked_schedule_'.$schedule.'_section'
            );
            
            // Days
            $days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
            foreach($days as $day) {
                add_settings_field(
                    'cron_schedule_'.$schedule.'_'.$day,
                    $day,
                    array($this, 'cron_schedule_'.$schedule.'_'.$day.'_callback'),
                    'inbox-first-schedule',
                    'ifklicked_schedule_'.$schedule.'_section'
                );
            }
            
            // Time
            add_settings_field(
                'time_schedule_'.$schedule,
                'Time',
                array($this, 'time_schedule_'.$schedule.'_callback'),
                'inbox-first-schedule',
                'ifklicked_schedule_'.$schedule.'_section'
            );
            
            // From Email
            add_settings_field(
                'from_email_schedule_'.$schedule,
                'From Email',
                array($this, 'from_email_schedule_'.$schedule.'_callback'),
                'inbox-first-schedule',
                'ifklicked_schedule_'.$schedule.'_section'
            );
            
            // From Name
            add_settings_field(
                'from_name_schedule_'.$schedule,
                'From Name',
                array($this, 'from_name_schedule_'.$schedule.'_callback'),
                'inbox-first-schedule',
                'ifklicked_schedule_'.$schedule.'_section'
            );
            
            // Subject
            add_settings_field(
                'ifklicked_subject_schedule_'.$schedule,
                'Subject',
                array($this, 'ifklicked_subject_schedule_'.$schedule.'_callback'),
                'inbox-first-schedule',
                'ifklicked_schedule_'.$schedule.'_section'
            );
            
            // Override API Key
            add_settings_field(
                'ifklicked_apikey_schedule_'.$schedule,
                'Override API Key',
                array($this, 'ifklicked_apikey_schedule_'.$schedule.'_callback'),
                'inbox-first-schedule',
                'ifklicked_schedule_'.$schedule.'_section'
            );
            
            // Notification Email
            add_settings_field(
                'ifklicked_notify_schedule_'.$schedule,
                'Notification Email',
                array($this, 'ifklicked_notify_schedule_'.$schedule.'_callback'),
                'inbox-first-schedule',
                'ifklicked_schedule_'.$schedule.'_section'
            );
        }
	}

	public function ifklicked_schedule_sanitize($input) {
		$sanitary_values = array();
        
        // Schedules
        $schedules = array('one', 'two', 'three', 'four');
        foreach($schedules as $schedule) {
            if(isset($input['enable_schedule_'.$schedule])) {
                $sanitary_values['enable_schedule_'.$schedule] = $input['enable_schedule_'.$schedule];
            }
            
            if(isset($input['list_schedule_'.$schedule])) {
                $sanitary_values['list_schedule_'.$schedule] = $input['list_schedule_'.$schedule];
            }
            
            if(isset($input['segmentation_schedule_'.$schedule])) {
                $sanitary_values['segmentation_schedule_'.$schedule] = $input['segmentation_schedule_'.$schedule];
            }
            
            if(isset($input['ifklicked_template_schedule_'.$schedule])) {
                $sanitary_values['ifklicked_template_schedule_'.$schedule] = sanitize_text_field($input['ifklicked_template_schedule_'.$schedule]);
            }
            
            if(isset($input['template_schedule_'.$schedule])) {
                $sanitary_values['template_schedule_'.$schedule] = $input['template_schedule_'.$schedule];
            }
            
            $days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
            foreach($days as $day) {
                if(isset($input['cron_schedule_'.$schedule.'_'.$day])) {
                    $sanitary_values['cron_schedule_'.$schedule.'_'.$day] = $input['cron_schedule_'.$schedule.'_'.$day];
                }
            }
            
            if(isset($input['time_schedule_'.$schedule])) {
                $sanitary_values['time_schedule_'.$schedule] = $input['time_schedule_'.$schedule];
            }
            
            if(isset($input['from_email_schedule_'.$schedule])) {
                $sanitary_values['from_email_schedule_'.$schedule] = $input['from_email_schedule_'.$schedule];
            }
            
            if(isset($input['from_name_schedule_'.$schedule])) {
                $sanitary_values['from_name_schedule_'.$schedule] = $input['from_name_schedule_'.$schedule];
            }
            
            if(isset($input['ifklicked_subject_schedule_'.$schedule])) {
                $sanitary_values['ifklicked_subject_schedule_'.$schedule] = $input['ifklicked_subject_schedule_'.$schedule];
            }
            
            if(isset($input['ifklicked_apikey_schedule_'.$schedule])) {
                $sanitary_values['ifklicked_apikey_schedule_'.$schedule] = sanitize_text_field($input['ifklicked_apikey_schedule_'.$schedule]);
            }
            
            if(isset($input['ifklicked_notify_schedule_'.$schedule])) {
                $sanitary_values['ifklicked_notify_schedule_'.$schedule] = sanitize_text_field($input['ifklicked_notify_schedule_'.$schedule]);
            }
        }

		return $sanitary_values;
	}

    public function ifklicked_schedule_one_section_info() {
        // Nothing.
    }
    
    public function ifklicked_schedule_two_section_info() {
        // Nothing.
    }
    
    public function ifklicked_schedule_three_section_info() {
        // Nothing.
    }

    public function ifklicked_schedule_four_section_info() {
        // Nothing.
    }
    
    // Callbacks   
    public function enable_schedule_one_callback() {
        printf(
			'<input type="checkbox" name="ifklicked_schedule_option_name[enable_schedule_one]" id="enable_schedule_one" class="enable-check" value="enable_schedule_one" %s>',
			( isset( $this->ifklicked_schedule_options['enable_schedule_one'] ) && $this->ifklicked_schedule_options['enable_schedule_one'] === 'enable_schedule_one' ) ? 'checked' : ''
		);
    }
    
    public function enable_schedule_two_callback() {
        printf(
			'<input type="checkbox" name="ifklicked_schedule_option_name[enable_schedule_two]" id="enable_schedule_two" class="enable-check" value="enable_schedule_two" %s>',
			( isset( $this->ifklicked_schedule_options['enable_schedule_two'] ) && $this->ifklicked_schedule_options['enable_schedule_two'] === 'enable_schedule_two' ) ? 'checked' : ''
		);
    }
    
    public function enable_schedule_three_callback() {
        printf(
			'<input type="checkbox" name="ifklicked_schedule_option_name[enable_schedule_three]" id="enable_schedule_three" class="enable-check" value="enable_schedule_three" %s>',
			( isset( $this->ifklicked_schedule_options['enable_schedule_three'] ) && $this->ifklicked_schedule_options['enable_schedule_three'] === 'enable_schedule_three' ) ? 'checked' : ''
		);
    }
    
    public function enable_schedule_four_callback() {
        printf(
			'<input type="checkbox" name="ifklicked_schedule_option_name[enable_schedule_four]" id="enable_schedule_four" class="enable-check" value="enable_schedule_four" %s>',
			( isset( $this->ifklicked_schedule_options['enable_schedule_four'] ) && $this->ifklicked_schedule_options['enable_schedule_four'] === 'enable_schedule_four' ) ? 'checked' : ''
		);
    }
    
    public function list_schedule_one_callback() { 
        // Variables
        if(empty($this->ifklicked_schedule_options['ifklicked_apikey_schedule_one'])) {
            $key = IFKLICKED_IBF_KEY;
        } else {
            $key = base64_decode($this->ifklicked_schedule_options['ifklicked_apikey_schedule_one']);
        }
        
        $lists = ifklicked_get_lists($key);
        if(!empty($lists)) {
            echo '<select name="ifklicked_schedule_option_name[list_schedule_one]" id="list_schedule_one">';
            foreach($lists['data'] as $list) { ?>
                <?php $selected = (isset( $this->ifklicked_schedule_options['list_schedule_one'] ) && $this->ifklicked_schedule_options['list_schedule_one'] === 'list-'.$list['id']) ? 'selected' : '' ; ?>
                <option value="<?php echo 'list-'.$list['id']; ?>" <?php echo $selected; ?>><?php echo $list['name']; ?></option><?php
            }
            echo '</select>';
        }
    }
    
    public function list_schedule_two_callback() { 
        // Variables
        if(empty($this->ifklicked_schedule_options['ifklicked_apikey_schedule_two'])) {
            $key = IFKLICKED_IBF_KEY;
        } else {
            $key = base64_decode($this->ifklicked_schedule_options['ifklicked_apikey_schedule_two']);
        }
        
        $lists = ifklicked_get_lists($key);
        if(!empty($lists)) {
            echo '<select name="ifklicked_schedule_option_name[list_schedule_two]" id="list_schedule_two">';
            foreach($lists['data'] as $list) { ?>
                <?php $selected = (isset( $this->ifklicked_schedule_options['list_schedule_two'] ) && $this->ifklicked_schedule_options['list_schedule_two'] === 'list-'.$list['id']) ? 'selected' : '' ; ?>
                <option value="<?php echo 'list-'.$list['id']; ?>" <?php echo $selected; ?>><?php echo $list['name']; ?></option><?php
            }
            echo '</select>';
        }
    }
    
    public function list_schedule_three_callback() { 
        // Variables
        if(empty($this->ifklicked_schedule_options['ifklicked_apikey_schedule_three'])) {
            $key = IFKLICKED_IBF_KEY;
        } else {
            $key = base64_decode($this->ifklicked_schedule_options['ifklicked_apikey_schedule_three']);
        }
        
        $lists = ifklicked_get_lists($key);
        if(!empty($lists)) {
            echo '<select name="ifklicked_schedule_option_name[list_schedule_three]" id="list_schedule_three">';
            foreach($lists['data'] as $list) { ?>
                <?php $selected = (isset( $this->ifklicked_schedule_options['list_schedule_three'] ) && $this->ifklicked_schedule_options['list_schedule_three'] === 'list-'.$list['id']) ? 'selected' : '' ; ?>
                <option value="<?php echo 'list-'.$list['id']; ?>" <?php echo $selected; ?>><?php echo $list['name']; ?></option><?php
            }
            echo '</select>';
        }
    }
    
    public function list_schedule_four_callback() { 
        // Variables
        if(empty($this->ifklicked_schedule_options['ifklicked_apikey_schedule_four'])) {
            $key = IFKLICKED_IBF_KEY;
        } else {
            $key = base64_decode($this->ifklicked_schedule_options['ifklicked_apikey_schedule_four']);
        }
        
        $lists = ifklicked_get_lists($key);
        if(!empty($lists)) {
            echo '<select name="ifklicked_schedule_option_name[list_schedule_four]" id="list_schedule_four">';
            foreach($lists['data'] as $list) { ?>
                <?php $selected = (isset( $this->ifklicked_schedule_options['list_schedule_four'] ) && $this->ifklicked_schedule_options['list_schedule_four'] === 'list-'.$list['id']) ? 'selected' : '' ; ?>
                <option value="<?php echo 'list-'.$list['id']; ?>" <?php echo $selected; ?>><?php echo $list['name']; ?></option><?php
            }
            echo '</select>';
        }
    }
    
    public function segmentation_schedule_one_callback() { 
        // Variables
        if(empty($this->ifklicked_schedule_options['ifklicked_apikey_schedule_one'])) {
            $key = IFKLICKED_IBF_KEY;
        } else {
            $key = base64_decode($this->ifklicked_schedule_options['ifklicked_apikey_schedule_one']);
        }

        $segments = ifklicked_get_segments($key);
        if(!empty($segments)) {
            echo '<select name="ifklicked_schedule_option_name[segmentation_schedule_one]" id="segmentation_schedule_one">';
            foreach($segments['data'] as $segment) { ?>
                <?php $selected = (isset( $this->ifklicked_schedule_options['segmentation_schedule_one'] ) && $this->ifklicked_schedule_options['segmentation_schedule_one'] === 'segment-'.$segment['id']) ? 'selected' : '' ; ?>
                <option value="<?php echo 'segment-'.$segment['id']; ?>" <?php echo $selected; ?>><?php echo $segment['name']; ?></option><?php
            }
            echo '</select>';
        }
    }

    public function segmentation_schedule_two_callback() { 
        // Variables
        if(empty($this->ifklicked_schedule_options['ifklicked_apikey_schedule_two'])) {
            $key = IFKLICKED_IBF_KEY;
        } else {
            $key = base64_decode($this->ifklicked_schedule_options['ifklicked_apikey_schedule_two']);
        }

        $segments = ifklicked_get_segments($key);
        if(!empty($segments)) {
            echo '<select name="ifklicked_schedule_option_name[segmentation_schedule_two]" id="segmentation_schedule_two">';
            foreach($segments['data'] as $segment) { ?>
                <?php $selected = (isset( $this->ifklicked_schedule_options['segmentation_schedule_two'] ) && $this->ifklicked_schedule_options['segmentation_schedule_two'] === 'segment-'.$segment['id']) ? 'selected' : '' ; ?>
                <option value="<?php echo 'segment-'.$segment['id']; ?>" <?php echo $selected; ?>><?php echo $segment['name']; ?></option><?php
            }
            echo '</select>';
        }
    }

    public function segmentation_schedule_three_callback() { 
        // Variables
        if(empty($this->ifklicked_schedule_options['ifklicked_apikey_schedule_three'])) {
            $key = IFKLICKED_IBF_KEY;
        } else {
            $key = base64_decode($this->ifklicked_schedule_options['ifklicked_apikey_schedule_three']);
        }

        $segments = ifklicked_get_segments($key);
        if(!empty($segments)) {
            echo '<select name="ifklicked_schedule_option_name[segmentation_schedule_three]" id="segmentation_schedule_three">';
            foreach($segments['data'] as $segment) { ?>
                <?php $selected = (isset( $this->ifklicked_schedule_options['segmentation_schedule_three'] ) && $this->ifklicked_schedule_options['segmentation_schedule_three'] === 'segment-'.$segment['id']) ? 'selected' : '' ; ?>
                <option value="<?php echo 'segment-'.$segment['id']; ?>" <?php echo $selected; ?>><?php echo $segment['name']; ?></option><?php
            }
            echo '</select>';
        }
    }
    
    public function segmentation_schedule_four_callback() { 
        // Variables
        if(empty($this->ifklicked_schedule_options['ifklicked_apikey_schedule_four'])) {
            $key = IFKLICKED_IBF_KEY;
        } else {
            $key = base64_decode($this->ifklicked_schedule_options['ifklicked_apikey_schedule_four']);
        }

        $segments = ifklicked_get_segments($key);
        if(!empty($segments)) {
            echo '<select name="ifklicked_schedule_option_name[segmentation_schedule_four]" id="segmentation_schedule_four">';
            foreach($segments['data'] as $segment) { ?>
                <?php $selected = (isset( $this->ifklicked_schedule_options['segmentation_schedule_four'] ) && $this->ifklicked_schedule_options['segmentation_schedule_four'] === 'segment-'.$segment['id']) ? 'selected' : '' ; ?>
                <option value="<?php echo 'segment-'.$segment['id']; ?>" <?php echo $selected; ?>><?php echo $segment['name']; ?></option><?php
            }
            echo '</select>';
        }
    }
    
    public function ifklicked_template_schedule_one_callback() {
        printf(
			'<input class="regular-text" type="text" name="ifklicked_schedule_option_name[ifklicked_template_schedule_one]" id="ifklicked_template_schedule_one" value="%s">',
			isset( $this->ifklicked_schedule_options['ifklicked_template_schedule_one'] ) ? esc_attr( $this->ifklicked_schedule_options['ifklicked_template_schedule_one']) : ''
		);
    }
    
    public function ifklicked_template_schedule_two_callback() {
        printf(
			'<input class="regular-text" type="text" name="ifklicked_schedule_option_name[ifklicked_template_schedule_two]" id="ifklicked_template_schedule_two" value="%s">',
			isset( $this->ifklicked_schedule_options['ifklicked_template_schedule_two'] ) ? esc_attr( $this->ifklicked_schedule_options['ifklicked_template_schedule_two']) : ''
		);
    }
    
    public function ifklicked_template_schedule_three_callback() {
        printf(
			'<input class="regular-text" type="text" name="ifklicked_schedule_option_name[ifklicked_template_schedule_three]" id="ifklicked_template_schedule_three" value="%s">',
			isset( $this->ifklicked_schedule_options['ifklicked_template_schedule_three'] ) ? esc_attr( $this->ifklicked_schedule_options['ifklicked_template_schedule_three']) : ''
		);
    }
    
    public function ifklicked_template_schedule_four_callback() {
        printf(
			'<input class="regular-text" type="text" name="ifklicked_schedule_option_name[ifklicked_template_schedule_four]" id="ifklicked_template_schedule_four" value="%s">',
			isset( $this->ifklicked_schedule_options['ifklicked_template_schedule_four'] ) ? esc_attr( $this->ifklicked_schedule_options['ifklicked_template_schedule_four']) : ''
		);
    }
    
    public function template_schedule_one_callback() {
        echo '<select name="ifklicked_schedule_option_name[template_schedule_one]" id="template_schedule_one" class="template-preview">';
        $templates = ifklicked_get_templates();
        foreach($templates as $template) {
			$selected = (isset( $this->ifklicked_schedule_options['template_schedule_one'] ) && $this->ifklicked_schedule_options['template_schedule_one'] === 'id-'.$template['id']) ? 'selected' : '' ; ?>
			<option value="<?php echo 'id-'.$template['id']; ?>" data-url="<?php echo get_permalink($template['id']); ?>" <?php echo $selected; ?>><?php echo $template['title']; ?></option>
        <?php }
		echo '</select><a href="" class="klicked-preview klicked-preview-one" target="_blank">Preview</a>';
    }
    
    public function template_schedule_two_callback() {
        echo '<select name="ifklicked_schedule_option_name[template_schedule_two]" id="template_schedule_two" class="template-preview">';
        $templates = ifklicked_get_templates();
        foreach($templates as $template) {
			$selected = (isset( $this->ifklicked_schedule_options['template_schedule_two'] ) && $this->ifklicked_schedule_options['template_schedule_two'] === 'id-'.$template['id']) ? 'selected' : '' ; ?>
			<option value="<?php echo 'id-'.$template['id']; ?>" data-url="<?php echo get_permalink($template['id']); ?>" <?php echo $selected; ?>><?php echo $template['title']; ?></option>
        <?php }
		echo '</select><a href="" class="klicked-preview klicked-preview-one" target="_blank">Preview</a>';
    }
    
    public function template_schedule_three_callback() {
        echo '<select name="ifklicked_schedule_option_name[template_schedule_three]" id="template_schedule_three" class="template-preview">';
        $templates = ifklicked_get_templates();
        foreach($templates as $template) {
			$selected = (isset( $this->ifklicked_schedule_options['template_schedule_three'] ) && $this->ifklicked_schedule_options['template_schedule_three'] === 'id-'.$template['id']) ? 'selected' : '' ; ?>
			<option value="<?php echo 'id-'.$template['id']; ?>" data-url="<?php echo get_permalink($template['id']); ?>" <?php echo $selected; ?>><?php echo $template['title']; ?></option>
        <?php }
		echo '</select><a href="" class="klicked-preview klicked-preview-one" target="_blank">Preview</a>';
    }
    
    public function template_schedule_four_callback() {
        echo '<select name="ifklicked_schedule_option_name[template_schedule_four]" id="template_schedule_four" class="template-preview">';
        $templates = ifklicked_get_templates();
        foreach($templates as $template) {
			$selected = (isset( $this->ifklicked_schedule_options['template_schedule_four'] ) && $this->ifklicked_schedule_options['template_schedule_four'] === 'id-'.$template['id']) ? 'selected' : '' ; ?>
			<option value="<?php echo 'id-'.$template['id']; ?>" data-url="<?php echo get_permalink($template['id']); ?>" <?php echo $selected; ?>><?php echo $template['title']; ?></option>
        <?php }
		echo '</select><a href="" class="klicked-preview klicked-preview-one" target="_blank">Preview</a>';
    }
    
    public function cron_schedule_one_Sunday_callback() {
        printf(
			'<input type="checkbox" name="ifklicked_schedule_option_name[cron_schedule_one_Sunday]" id="cron_schedule_one_Sunday" value="cron_schedule_one_Sunday" %s>',
			( isset( $this->ifklicked_schedule_options['cron_schedule_one_Sunday'] ) && $this->ifklicked_schedule_options['cron_schedule_one_Sunday'] === 'cron_schedule_one_Sunday' ) ? 'checked' : ''
		);
    }
    
    public function cron_schedule_one_Monday_callback() {
        printf(
			'<input type="checkbox" name="ifklicked_schedule_option_name[cron_schedule_one_Monday]" id="cron_schedule_one_Monday" value="cron_schedule_one_Monday" %s>',
			( isset( $this->ifklicked_schedule_options['cron_schedule_one_Monday'] ) && $this->ifklicked_schedule_options['cron_schedule_one_Monday'] === 'cron_schedule_one_Monday' ) ? 'checked' : ''
		);
    }
    
    public function cron_schedule_one_Tuesday_callback() {
        printf(
			'<input type="checkbox" name="ifklicked_schedule_option_name[cron_schedule_one_Tuesday]" id="cron_schedule_one_Tuesday" value="cron_schedule_one_Tuesday" %s>',
			( isset( $this->ifklicked_schedule_options['cron_schedule_one_Tuesday'] ) && $this->ifklicked_schedule_options['cron_schedule_one_Tuesday'] === 'cron_schedule_one_Tuesday' ) ? 'checked' : ''
		);
    }
    
    public function cron_schedule_one_Wednesday_callback() {
        printf(
			'<input type="checkbox" name="ifklicked_schedule_option_name[cron_schedule_one_Wednesday]" id="cron_schedule_one_Wednesday" value="cron_schedule_one_Wednesday" %s>',
			( isset( $this->ifklicked_schedule_options['cron_schedule_one_Wednesday'] ) && $this->ifklicked_schedule_options['cron_schedule_one_Wednesday'] === 'cron_schedule_one_Wednesday' ) ? 'checked' : ''
		);
    }
    
    public function cron_schedule_one_Thursday_callback() {
        printf(
			'<input type="checkbox" name="ifklicked_schedule_option_name[cron_schedule_one_Thursday]" id="cron_schedule_one_Thursday" value="cron_schedule_one_Thursday" %s>',
			( isset( $this->ifklicked_schedule_options['cron_schedule_one_Thursday'] ) && $this->ifklicked_schedule_options['cron_schedule_one_Thursday'] === 'cron_schedule_one_Thursday' ) ? 'checked' : ''
		);
    }
    
    public function cron_schedule_one_Friday_callback() {
        printf(
			'<input type="checkbox" name="ifklicked_schedule_option_name[cron_schedule_one_Friday]" id="cron_schedule_one_Friday" value="cron_schedule_one_Friday" %s>',
			( isset( $this->ifklicked_schedule_options['cron_schedule_one_Friday'] ) && $this->ifklicked_schedule_options['cron_schedule_one_Friday'] === 'cron_schedule_one_Friday' ) ? 'checked' : ''
		);
    }
    
    public function cron_schedule_one_Saturday_callback() {
        printf(
			'<input type="checkbox" name="ifklicked_schedule_option_name[cron_schedule_one_Saturday]" id="cron_schedule_one_Saturday" value="cron_schedule_one_Saturday" %s>',
			( isset( $this->ifklicked_schedule_options['cron_schedule_one_Saturday'] ) && $this->ifklicked_schedule_options['cron_schedule_one_Saturday'] === 'cron_schedule_one_Saturday' ) ? 'checked' : ''
		);
    }
    
    public function cron_schedule_two_Sunday_callback() {
        printf(
			'<input type="checkbox" name="ifklicked_schedule_option_name[cron_schedule_two_Sunday]" id="cron_schedule_two_Sunday" value="cron_schedule_two_Sunday" %s>',
			( isset( $this->ifklicked_schedule_options['cron_schedule_two_Sunday'] ) && $this->ifklicked_schedule_options['cron_schedule_two_Sunday'] === 'cron_schedule_two_Sunday' ) ? 'checked' : ''
		);
    }
    
    public function cron_schedule_two_Monday_callback() {
        printf(
			'<input type="checkbox" name="ifklicked_schedule_option_name[cron_schedule_two_Monday]" id="cron_schedule_two_Monday" value="cron_schedule_two_Monday" %s>',
			( isset( $this->ifklicked_schedule_options['cron_schedule_two_Monday'] ) && $this->ifklicked_schedule_options['cron_schedule_two_Monday'] === 'cron_schedule_two_Monday' ) ? 'checked' : ''
		);
    }
    
    public function cron_schedule_two_Tuesday_callback() {
        printf(
			'<input type="checkbox" name="ifklicked_schedule_option_name[cron_schedule_two_Tuesday]" id="cron_schedule_two_Tuesday" value="cron_schedule_two_Tuesday" %s>',
			( isset( $this->ifklicked_schedule_options['cron_schedule_two_Tuesday'] ) && $this->ifklicked_schedule_options['cron_schedule_two_Tuesday'] === 'cron_schedule_two_Tuesday' ) ? 'checked' : ''
		);
    }
    
    public function cron_schedule_two_Wednesday_callback() {
        printf(
			'<input type="checkbox" name="ifklicked_schedule_option_name[cron_schedule_two_Wednesday]" id="cron_schedule_two_Wednesday" value="cron_schedule_two_Wednesday" %s>',
			( isset( $this->ifklicked_schedule_options['cron_schedule_two_Wednesday'] ) && $this->ifklicked_schedule_options['cron_schedule_two_Wednesday'] === 'cron_schedule_two_Wednesday' ) ? 'checked' : ''
		);
    }
    
    public function cron_schedule_two_Thursday_callback() {
        printf(
			'<input type="checkbox" name="ifklicked_schedule_option_name[cron_schedule_two_Thursday]" id="cron_schedule_two_Thursday" value="cron_schedule_two_Thursday" %s>',
			( isset( $this->ifklicked_schedule_options['cron_schedule_two_Thursday'] ) && $this->ifklicked_schedule_options['cron_schedule_two_Thursday'] === 'cron_schedule_two_Thursday' ) ? 'checked' : ''
		);
    }
    
    public function cron_schedule_two_Friday_callback() {
        printf(
			'<input type="checkbox" name="ifklicked_schedule_option_name[cron_schedule_two_Friday]" id="cron_schedule_two_Friday" value="cron_schedule_two_Friday" %s>',
			( isset( $this->ifklicked_schedule_options['cron_schedule_two_Friday'] ) && $this->ifklicked_schedule_options['cron_schedule_two_Friday'] === 'cron_schedule_two_Friday' ) ? 'checked' : ''
		);
    }
    
    public function cron_schedule_two_Saturday_callback() {
        printf(
			'<input type="checkbox" name="ifklicked_schedule_option_name[cron_schedule_two_Saturday]" id="cron_schedule_two_Saturday" value="cron_schedule_two_Saturday" %s>',
			( isset( $this->ifklicked_schedule_options['cron_schedule_two_Saturday'] ) && $this->ifklicked_schedule_options['cron_schedule_two_Saturday'] === 'cron_schedule_two_Saturday' ) ? 'checked' : ''
		);
    }
    
    public function cron_schedule_three_Sunday_callback() {
        printf(
			'<input type="checkbox" name="ifklicked_schedule_option_name[cron_schedule_three_Sunday]" id="cron_schedule_three_Sunday" value="cron_schedule_three_Sunday" %s>',
			( isset( $this->ifklicked_schedule_options['cron_schedule_three_Sunday'] ) && $this->ifklicked_schedule_options['cron_schedule_three_Sunday'] === 'cron_schedule_three_Sunday' ) ? 'checked' : ''
		);
    }
    
    public function cron_schedule_three_Monday_callback() {
        printf(
			'<input type="checkbox" name="ifklicked_schedule_option_name[cron_schedule_three_Monday]" id="cron_schedule_three_Monday" value="cron_schedule_three_Monday" %s>',
			( isset( $this->ifklicked_schedule_options['cron_schedule_three_Monday'] ) && $this->ifklicked_schedule_options['cron_schedule_three_Monday'] === 'cron_schedule_three_Monday' ) ? 'checked' : ''
		);
    }
    
    public function cron_schedule_three_Tuesday_callback() {
        printf(
			'<input type="checkbox" name="ifklicked_schedule_option_name[cron_schedule_three_Tuesday]" id="cron_schedule_three_Tuesday" value="cron_schedule_three_Tuesday" %s>',
			( isset( $this->ifklicked_schedule_options['cron_schedule_three_Tuesday'] ) && $this->ifklicked_schedule_options['cron_schedule_three_Tuesday'] === 'cron_schedule_three_Tuesday' ) ? 'checked' : ''
		);
    }
    
    public function cron_schedule_three_Wednesday_callback() {
        printf(
			'<input type="checkbox" name="ifklicked_schedule_option_name[cron_schedule_three_Wednesday]" id="cron_schedule_three_Wednesday" value="cron_schedule_three_Wednesday" %s>',
			( isset( $this->ifklicked_schedule_options['cron_schedule_three_Wednesday'] ) && $this->ifklicked_schedule_options['cron_schedule_three_Wednesday'] === 'cron_schedule_three_Wednesday' ) ? 'checked' : ''
		);
    }
    
    public function cron_schedule_three_Thursday_callback() {
        printf(
			'<input type="checkbox" name="ifklicked_schedule_option_name[cron_schedule_three_Thursday]" id="cron_schedule_three_Thursday" value="cron_schedule_three_Thursday" %s>',
			( isset( $this->ifklicked_schedule_options['cron_schedule_three_Thursday'] ) && $this->ifklicked_schedule_options['cron_schedule_three_Thursday'] === 'cron_schedule_three_Thursday' ) ? 'checked' : ''
		);
    }
    
    public function cron_schedule_three_Friday_callback() {
        printf(
			'<input type="checkbox" name="ifklicked_schedule_option_name[cron_schedule_three_Friday]" id="cron_schedule_three_Friday" value="cron_schedule_three_Friday" %s>',
			( isset( $this->ifklicked_schedule_options['cron_schedule_three_Friday'] ) && $this->ifklicked_schedule_options['cron_schedule_three_Friday'] === 'cron_schedule_three_Friday' ) ? 'checked' : ''
		);
    }
    
    public function cron_schedule_three_Saturday_callback() {
        printf(
			'<input type="checkbox" name="ifklicked_schedule_option_name[cron_schedule_three_Saturday]" id="cron_schedule_three_Saturday" value="cron_schedule_three_Saturday" %s>',
			( isset( $this->ifklicked_schedule_options['cron_schedule_three_Saturday'] ) && $this->ifklicked_schedule_options['cron_schedule_three_Saturday'] === 'cron_schedule_three_Saturday' ) ? 'checked' : ''
		);
    }
    
    public function cron_schedule_four_Sunday_callback() {
        printf(
			'<input type="checkbox" name="ifklicked_schedule_option_name[cron_schedule_four_Sunday]" id="cron_schedule_four_Sunday" value="cron_schedule_four_Sunday" %s>',
			( isset( $this->ifklicked_schedule_options['cron_schedule_four_Sunday'] ) && $this->ifklicked_schedule_options['cron_schedule_four_Sunday'] === 'cron_schedule_four_Sunday' ) ? 'checked' : ''
		);
    }
    
    public function cron_schedule_four_Monday_callback() {
        printf(
			'<input type="checkbox" name="ifklicked_schedule_option_name[cron_schedule_four_Monday]" id="cron_schedule_four_Monday" value="cron_schedule_four_Monday" %s>',
			( isset( $this->ifklicked_schedule_options['cron_schedule_four_Monday'] ) && $this->ifklicked_schedule_options['cron_schedule_four_Monday'] === 'cron_schedule_four_Monday' ) ? 'checked' : ''
		);
    }
    
    public function cron_schedule_four_Tuesday_callback() {
        printf(
			'<input type="checkbox" name="ifklicked_schedule_option_name[cron_schedule_four_Tuesday]" id="cron_schedule_four_Tuesday" value="cron_schedule_four_Tuesday" %s>',
			( isset( $this->ifklicked_schedule_options['cron_schedule_four_Tuesday'] ) && $this->ifklicked_schedule_options['cron_schedule_four_Tuesday'] === 'cron_schedule_four_Tuesday' ) ? 'checked' : ''
		);
    }
    
    public function cron_schedule_four_Wednesday_callback() {
        printf(
			'<input type="checkbox" name="ifklicked_schedule_option_name[cron_schedule_four_Wednesday]" id="cron_schedule_four_Wednesday" value="cron_schedule_four_Wednesday" %s>',
			( isset( $this->ifklicked_schedule_options['cron_schedule_four_Wednesday'] ) && $this->ifklicked_schedule_options['cron_schedule_four_Wednesday'] === 'cron_schedule_four_Wednesday' ) ? 'checked' : ''
		);
    }
    
    public function cron_schedule_four_Thursday_callback() {
        printf(
			'<input type="checkbox" name="ifklicked_schedule_option_name[cron_schedule_four_Thursday]" id="cron_schedule_four_Thursday" value="cron_schedule_four_Thursday" %s>',
			( isset( $this->ifklicked_schedule_options['cron_schedule_four_Thursday'] ) && $this->ifklicked_schedule_options['cron_schedule_four_Thursday'] === 'cron_schedule_four_Thursday' ) ? 'checked' : ''
		);
    }
    
    public function cron_schedule_four_Friday_callback() {
        printf(
			'<input type="checkbox" name="ifklicked_schedule_option_name[cron_schedule_four_Friday]" id="cron_schedule_four_Friday" value="cron_schedule_four_Friday" %s>',
			( isset( $this->ifklicked_schedule_options['cron_schedule_four_Friday'] ) && $this->ifklicked_schedule_options['cron_schedule_four_Friday'] === 'cron_schedule_four_Friday' ) ? 'checked' : ''
		);
    }
    
    public function cron_schedule_four_Saturday_callback() {
        printf(
			'<input type="checkbox" name="ifklicked_schedule_option_name[cron_schedule_four_Saturday]" id="cron_schedule_four_Saturday" value="cron_schedule_four_Saturday" %s>',
			( isset( $this->ifklicked_schedule_options['cron_schedule_four_Saturday'] ) && $this->ifklicked_schedule_options['cron_schedule_four_Saturday'] === 'cron_schedule_four_Saturday' ) ? 'checked' : ''
		);
    }
    
    public function time_schedule_one_callback() {
        $times = ifklicked_get_times('false');
        echo '<select name="ifklicked_schedule_option_name[time_schedule_one]" id="time_schedule_one">';
        foreach($times as $time) {
            $selected = (isset( $this->ifklicked_schedule_options['time_schedule_one'] ) && $this->ifklicked_schedule_options['time_schedule_one'] === $time) ? 'selected' : '' ; ?>
			<option value="<?php echo $time; ?>" <?php echo $selected; ?>><?php echo $time; ?></option>
        <?php }
        echo '</select>';
    }
    
    public function time_schedule_two_callback() {
        $times = ifklicked_get_times('false');
        echo '<select name="ifklicked_schedule_option_name[time_schedule_two]" id="time_schedule_two">';
        foreach($times as $time) {
            $selected = (isset( $this->ifklicked_schedule_options['time_schedule_two'] ) && $this->ifklicked_schedule_options['time_schedule_two'] === $time) ? 'selected' : '' ; ?>
			<option value="<?php echo $time; ?>" <?php echo $selected; ?>><?php echo $time; ?></option>
        <?php }
        echo '</select>';
    }
    
    public function time_schedule_three_callback() {
        $times = ifklicked_get_times('false');
        echo '<select name="ifklicked_schedule_option_name[time_schedule_three]" id="time_schedule_three">';
        foreach($times as $time) {
            $selected = (isset( $this->ifklicked_schedule_options['time_schedule_three'] ) && $this->ifklicked_schedule_options['time_schedule_three'] === $time) ? 'selected' : '' ; ?>
			<option value="<?php echo $time; ?>" <?php echo $selected; ?>><?php echo $time; ?></option>
        <?php }
        echo '</select>';
    }
    
    public function time_schedule_four_callback() {
        $times = ifklicked_get_times('false');
        echo '<select name="ifklicked_schedule_option_name[time_schedule_four]" id="time_schedule_four">';
        foreach($times as $time) {
            $selected = (isset( $this->ifklicked_schedule_options['time_schedule_four'] ) && $this->ifklicked_schedule_options['time_schedule_four'] === $time) ? 'selected' : '' ; ?>
			<option value="<?php echo $time; ?>" <?php echo $selected; ?>><?php echo $time; ?></option>
        <?php }
        echo '</select>';
    }
    
    public function from_email_schedule_one_callback() {
        printf(
			'<input class="regular-text" type="text" name="ifklicked_schedule_option_name[from_email_schedule_one]" id="from_email_schedule_one" value="%s">',
			isset( $this->ifklicked_schedule_options['from_email_schedule_one'] ) ? esc_attr( $this->ifklicked_schedule_options['from_email_schedule_one']) : ''
		);
    }
    
    public function from_email_schedule_two_callback() {
        printf(
			'<input class="regular-text" type="text" name="ifklicked_schedule_option_name[from_email_schedule_two]" id="from_email_schedule_two" value="%s">',
			isset( $this->ifklicked_schedule_options['from_email_schedule_two'] ) ? esc_attr( $this->ifklicked_schedule_options['from_email_schedule_two']) : ''
		);
    }
    
    public function from_email_schedule_three_callback() {
        printf(
			'<input class="regular-text" type="text" name="ifklicked_schedule_option_name[from_email_schedule_three]" id="from_email_schedule_three" value="%s">',
			isset( $this->ifklicked_schedule_options['from_email_schedule_three'] ) ? esc_attr( $this->ifklicked_schedule_options['from_email_schedule_three']) : ''
		);
    }
    
    public function from_email_schedule_four_callback() {
        printf(
			'<input class="regular-text" type="text" name="ifklicked_schedule_option_name[from_email_schedule_four]" id="from_email_schedule_four" value="%s">',
			isset( $this->ifklicked_schedule_options['from_email_schedule_four'] ) ? esc_attr( $this->ifklicked_schedule_options['from_email_schedule_four']) : ''
		);
    }
    
    public function from_name_schedule_one_callback() {
        printf(
			'<input class="regular-text" type="text" name="ifklicked_schedule_option_name[from_name_schedule_one]" id="from_name_schedule_one" value="%s">',
			isset( $this->ifklicked_schedule_options['from_name_schedule_one'] ) ? esc_attr( $this->ifklicked_schedule_options['from_name_schedule_one']) : ''
		);
    }
    
    public function from_name_schedule_two_callback() {
        printf(
			'<input class="regular-text" type="text" name="ifklicked_schedule_option_name[from_name_schedule_two]" id="from_name_schedule_two" value="%s">',
			isset( $this->ifklicked_schedule_options['from_name_schedule_two'] ) ? esc_attr( $this->ifklicked_schedule_options['from_name_schedule_two']) : ''
		);
    }
    
    public function from_name_schedule_three_callback() {
        printf(
			'<input class="regular-text" type="text" name="ifklicked_schedule_option_name[from_name_schedule_three]" id="from_name_schedule_three" value="%s">',
			isset( $this->ifklicked_schedule_options['from_name_schedule_three'] ) ? esc_attr( $this->ifklicked_schedule_options['from_name_schedule_three']) : ''
		);
    }
    
    public function from_name_schedule_four_callback() {
        printf(
			'<input class="regular-text" type="text" name="ifklicked_schedule_option_name[from_name_schedule_four]" id="from_name_schedule_four" value="%s">',
			isset( $this->ifklicked_schedule_options['from_name_schedule_four'] ) ? esc_attr( $this->ifklicked_schedule_options['from_name_schedule_four']) : ''
		);
    }
    
    public function ifklicked_subject_schedule_one_callback() { ?>
        <select name="ifklicked_schedule_option_name[ifklicked_subject_schedule_one]" id="ifklicked_subject_schedule_one" class="schedule-subject">
			<?php $selected = (isset( $this->ifklicked_schedule_options['ifklicked_subject_schedule_one'] ) && $this->ifklicked_schedule_options['ifklicked_subject_schedule_one'] === 'default') ? 'selected' : '' ; ?>
			<option value="default" <?php echo $selected; ?>>Default (<?php echo 'From '.get_bloginfo('name').' on '.date('F j, Y').' at '.date('h:i A'); ?>)</option>
            <?php $selected = (isset( $this->ifklicked_schedule_options['ifklicked_subject_schedule_one'] ) && $this->ifklicked_schedule_options['ifklicked_subject_schedule_one'] === 'page-title') ? 'selected' : '' ; ?>
			<option value="page-title" <?php echo $selected; ?>>Page Title</option>
		</select> <?php
    }
    
    public function ifklicked_subject_schedule_two_callback() { ?>
        <select name="ifklicked_schedule_option_name[ifklicked_subject_schedule_two]" id="ifklicked_subject_schedule_two" class="schedule-subject">
			<?php $selected = (isset( $this->ifklicked_schedule_options['ifklicked_subject_schedule_two'] ) && $this->ifklicked_schedule_options['ifklicked_subject_schedule_two'] === 'default') ? 'selected' : '' ; ?>
			<option value="default" <?php echo $selected; ?>>Default (<?php echo 'From '.get_bloginfo('name').' on '.date('F j, Y').' at '.date('h:i A'); ?>)</option>
            <?php $selected = (isset( $this->ifklicked_schedule_options['ifklicked_subject_schedule_two'] ) && $this->ifklicked_schedule_options['ifklicked_subject_schedule_two'] === 'page-title') ? 'selected' : '' ; ?>
			<option value="page-title" <?php echo $selected; ?>>Page Title</option>
		</select> <?php
    }
    
    public function ifklicked_subject_schedule_three_callback() { ?>
        <select name="ifklicked_schedule_option_name[ifklicked_subject_schedule_three]" id="ifklicked_subject_schedule_three" class="schedule-subject">
			<?php $selected = (isset( $this->ifklicked_schedule_options['ifklicked_subject_schedule_three'] ) && $this->ifklicked_schedule_options['ifklicked_subject_schedule_three'] === 'default') ? 'selected' : '' ; ?>
			<option value="default" <?php echo $selected; ?>>Default (<?php echo 'From '.get_bloginfo('name').' on '.date('F j, Y').' at '.date('h:i A'); ?>)</option>
            <?php $selected = (isset( $this->ifklicked_schedule_options['ifklicked_subject_schedule_three'] ) && $this->ifklicked_schedule_options['ifklicked_subject_schedule_three'] === 'page-title') ? 'selected' : '' ; ?>
			<option value="page-title" <?php echo $selected; ?>>Page Title</option>
		</select> <?php
    }
    
    public function ifklicked_subject_schedule_four_callback() { ?>
        <select name="ifklicked_schedule_option_name[ifklicked_subject_schedule_four]" id="ifklicked_subject_schedule_four" class="schedule-subject">
			<?php $selected = (isset( $this->ifklicked_schedule_options['ifklicked_subject_schedule_four'] ) && $this->ifklicked_schedule_options['ifklicked_subject_schedule_four'] === 'default') ? 'selected' : '' ; ?>
			<option value="default" <?php echo $selected; ?>>Default (<?php echo 'From '.get_bloginfo('name').' on '.date('F j, Y').' at '.date('h:i A'); ?>)</option>
            <?php $selected = (isset( $this->ifklicked_schedule_options['ifklicked_subject_schedule_four'] ) && $this->ifklicked_schedule_options['ifklicked_subject_schedule_four'] === 'page-title') ? 'selected' : '' ; ?>
			<option value="page-title" <?php echo $selected; ?>>Page Title</option>
		</select> <?php
    }
    
    public function ifklicked_apikey_schedule_one_callback() {
        printf(
			'<input class="regular-text" type="text" name="ifklicked_schedule_option_name[ifklicked_apikey_schedule_one]" id="ifklicked_apikey_schedule_one" value="%s">',
			isset( $this->ifklicked_schedule_options['ifklicked_apikey_schedule_one'] ) ? esc_attr( $this->ifklicked_schedule_options['ifklicked_apikey_schedule_one']) : ''
		);
    }
    
    public function ifklicked_apikey_schedule_two_callback() {
        printf(
			'<input class="regular-text" type="text" name="ifklicked_schedule_option_name[ifklicked_apikey_schedule_two]" id="ifklicked_apikey_schedule_two" value="%s">',
			isset( $this->ifklicked_schedule_options['ifklicked_apikey_schedule_two'] ) ? esc_attr( $this->ifklicked_schedule_options['ifklicked_apikey_schedule_two']) : ''
		);
    }
    
    public function ifklicked_apikey_schedule_three_callback() {
        printf(
			'<input class="regular-text" type="text" name="ifklicked_schedule_option_name[ifklicked_apikey_schedule_three]" id="ifklicked_apikey_schedule_three" value="%s">',
			isset( $this->ifklicked_schedule_options['ifklicked_apikey_schedule_three'] ) ? esc_attr( $this->ifklicked_schedule_options['ifklicked_apikey_schedule_three']) : ''
		);
    }
    
    public function ifklicked_apikey_schedule_four_callback() {
        printf(
			'<input class="regular-text" type="text" name="ifklicked_schedule_option_name[ifklicked_apikey_schedule_four]" id="ifklicked_apikey_schedule_four" value="%s">',
			isset( $this->ifklicked_schedule_options['ifklicked_apikey_schedule_four'] ) ? esc_attr( $this->ifklicked_schedule_options['ifklicked_apikey_schedule_four']) : ''
		);
    }
    
    // Notification Email
    public function ifklicked_notify_schedule_one_callback() {
        printf(
			'<input class="regular-text" type="text" name="ifklicked_schedule_option_name[ifklicked_notify_schedule_one]" id="ifklicked_notify_schedule_one" value="%s">',
			isset( $this->ifklicked_schedule_options['ifklicked_notify_schedule_one'] ) ? esc_attr( $this->ifklicked_schedule_options['ifklicked_notify_schedule_one']) : ''
		);
    }
    
    public function ifklicked_notify_schedule_two_callback() {
        printf(
			'<input class="regular-text" type="text" name="ifklicked_schedule_option_name[ifklicked_notify_schedule_two]" id="ifklicked_notify_schedule_two" value="%s">',
			isset( $this->ifklicked_schedule_options['ifklicked_notify_schedule_two'] ) ? esc_attr( $this->ifklicked_schedule_options['ifklicked_notify_schedule_two']) : ''
		);
    }
    
    public function ifklicked_notify_schedule_three_callback() {
        printf(
			'<input class="regular-text" type="text" name="ifklicked_schedule_option_name[ifklicked_notify_schedule_three]" id="ifklicked_notify_schedule_three" value="%s">',
			isset( $this->ifklicked_schedule_options['ifklicked_notify_schedule_three'] ) ? esc_attr( $this->ifklicked_schedule_options['ifklicked_notify_schedule_three']) : ''
		);
    }
    
    public function ifklicked_notify_schedule_four_callback() {
        printf(
			'<input class="regular-text" type="text" name="ifklicked_schedule_option_name[ifklicked_notify_schedule_four]" id="ifklicked_notify_schedule_four" value="%s">',
			isset( $this->ifklicked_schedule_options['ifklicked_notify_schedule_four'] ) ? esc_attr( $this->ifklicked_schedule_options['ifklicked_notify_schedule_four']) : ''
		);
    }
}