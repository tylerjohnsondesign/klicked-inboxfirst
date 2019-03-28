<?php
/**
Send
**/
class klickedIFSend {
	private $ifklicked_send_options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'ifklicked_send_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'ifklicked_send_page_init' ) );
	}

	public function ifklicked_send_add_plugin_page() {
		add_submenu_page(
            'inbox-first',
            'Send',
            'Send',
            'publish_pages',
            'inbox-first-send',
            array($this, 'ifklicked_send_create_admin_page')
        );
	}

	public function ifklicked_send_create_admin_page() {
		include (IFKLICKED_BASE_PATH.'admin/templates/template-send.php');
	}

	public function ifklicked_send_page_init() {
		register_setting(
			'ifklicked_send_option_group', // option_group
			'ifklicked_send_option_name', // option_name
			array( $this, 'ifklicked_send_sanitize' ) // sanitize_callback
		);

		add_settings_section(
			'ifklicked_send_setting_section', // id
			'', // title
			array( $this, 'ifklicked_send_section_info' ), // callback
			'inbox-first-send' // page
		);
        
        add_settings_field(
			'ifklicked_send_list', // id
			'List', // title
			array( $this, 'ifklicked_send_list_callback' ), // callback
			'inbox-first-send', // page
			'ifklicked_send_setting_section' // section
		);
        
        add_settings_field(
			'ifklicked_send_segmentation', // id
			'Segment', // title
			array( $this, 'ifklicked_send_segmentation_callback' ), // callback
			'inbox-first-send', // page
			'ifklicked_send_setting_section' // section
		);
        
        add_settings_field(
			'ifklicked_send_campaign_template', // id
			'Campaign Template ID', // title
			array( $this, 'ifklicked_send_campaign_template_callback' ), // callback
			'inbox-first-send', // page
			'ifklicked_send_setting_section' // section
		);
        
        add_settings_field(
			'ifklicked_send_email_template', // id
			'Email Template', // title
			array( $this, 'ifklicked_send_email_template_callback' ), // callback
			'inbox-first-send', // page
			'ifklicked_send_setting_section' // section
		);
        
        add_settings_field(
			'ifklicked_send_date', // id
			'Date', // title
			array( $this, 'ifklicked_send_date_callback' ), // callback
			'inbox-first-send', // page
			'ifklicked_send_setting_section' // section
		);
        
        add_settings_field(
			'ifklicked_send_time', // id
			'Time', // title
			array( $this, 'ifklicked_send_time_callback' ), // callback
			'inbox-first-send', // page
			'ifklicked_send_setting_section' // section
		);
        
        add_settings_field(
			'ifklicked_from_email', // id
			'From Email', // title
			array( $this, 'ifklicked_from_email_callback' ), // callback
			'inbox-first-send', // page
			'ifklicked_send_setting_section' // section
		);
        
        add_settings_field(
			'ifklicked_from_name', // id
			'From Name', // title
			array( $this, 'ifklicked_from_name_callback' ), // callback
			'inbox-first-send', // page
			'ifklicked_send_setting_section' // section
		);
        
        add_settings_field(
			'ifklicked_send_subject', // id
			'Subject', // title
			array( $this, 'ifklicked_send_subject_callback' ), // callback
			'inbox-first-send', // page
			'ifklicked_send_setting_section' // section
		);
        
        add_settings_field(
			'ifklicked_send_custom_subject', // id
			'<span class="custom-subject" style="display: none">Custom Subject</span>', // title
			array( $this, 'ifklicked_send_custom_subject_callback' ), // callback
			'inbox-first-send', // page
			'ifklicked_send_setting_section' // section
		);
	}

	public function ifklicked_send_sanitize($input) {
		$sanitary_values = array();
        if(isset($input['ifklicked_send_list'])) {
            $sanitary_values['ifklicked_send_list'] = $input['ifklicked_send_list'];
        }
        
        if(isset($input['ifklicked_send_segmentation'])) {
            $sanitary_values['ifklicked_send_segmentation'] = $input['ifklicked_send_segmentation'];
        }
         
        if(isset($input['ifklicked_send_campaign_template'])) {
            $sanitary_values['ifklicked_send_campaign_template'] = sanitize_text_field($input['ifklicked_send_campaign_template']);
        }
        
        if(isset($input['ifklicked_send_email_template'])) {
            $sanitary_values['ifklicked_send_email_template'] = $input['ifklicked_send_email_template'];
        }
        
        if(isset($input['ifklicked_send_date'])) {
            $sanitary_values['ifklicked_send_date'] = sanitize_text_field($input['ifklicked_send_date']);
        }
        
        if(isset($input['ifklicked_send_time'])) {
            $sanitary_values['ifklicked_send_time'] = $input['ifklicked_send_time'];
        }
        
        if(isset($input['ifklicked_from_email'])) {
            $sanitary_values['ifklicked_from_email'] = sanitize_text_field($input['ifklicked_from_email']);
        }
        
        if(isset($input['ifklicked_from_name'])) {
            $sanitary_values['ifklicked_from_name'] = sanitize_text_field($input['ifklicked_from_name']);
        }
        
        if(isset($input['ifklicked_send_subject'])) {
            $sanitary_values['ifklicked_send_subject'] = $input['ifklicked_send_subject'];
        }
        
        if(isset($input['ifklicked_send_custom_subject'])) {
            $sanitary_values['ifklicked_send_custom_subject'] = sanitize_text_field($input['ifklicked_send_custom_subject']);
        }
        
		return $sanitary_values;
	}

	public function ifklicked_send_section_info() {
		// Nothing here.
	}
    
    public function ifklicked_send_list_callback() {
        // Variables
        $lists = ifklicked_get_lists(IFKLICKED_IBF_KEY);
        echo '<select name="ifklicked_send_option_name[ifklicked_send_list]" id="ifklicked_send_list">';
        foreach($lists['data'] as $list) { ?>
			<?php $selected = (isset( $this->ifklicked_send_options['ifklicked_send_list'] ) && $this->ifklicked_send_options['ifklicked_send_list'] === 'list-'.$list['id']) ? 'selected' : '' ; ?>
			<option value="<?php echo 'list-'.$list['id']; ?>" <?php echo $selected; ?>><?php echo $list['name']; ?></option><?php
        }
        echo '</select>';
    }
    
    public function ifklicked_send_segmentation_callback() {
        // Variables
        $segments = ifklicked_get_segments(IFKLICKED_IBF_KEY);
        echo '<select name="ifklicked_send_option_name[ifklicked_send_segmentation]" id="ifklicked_send_segmentation">';
        foreach($segments['data'] as $segment) { ?>
			<?php $selected = (isset( $this->ifklicked_send_options['ifklicked_send_segmentation'] ) && $this->ifklicked_send_options['ifklicked_send_segmentation'] === 'segment-'.$segment['id']) ? 'selected' : '' ; ?>
			<option value="<?php echo 'segment-'.$segment['id']; ?>" <?php echo $selected; ?>><?php echo $segment['name']; ?></option><?php
        }
        echo '</select>';
    }
    
    public function ifklicked_send_campaign_template_callback() {
        printf(
			'<input class="regular-text" type="text" name="ifklicked_send_option_name[ifklicked_send_campaign_template]" id="ifklicked_send_campaign_template" value="%s"><span class="note">To get your campaign template ID, go <a href="https://if.inboxfirst.com/ga/templates/" target="_blank">here</a>.</span>',
			isset( $this->ifklicked_send_options['ifklicked_send_campaign_template'] ) ? esc_attr( $this->ifklicked_send_options['ifklicked_send_campaign_template']) : ''
		);
    }
    
    public function ifklicked_send_email_template_callback() {
        // Variables
        $templates = ifklicked_get_templates();
        echo '<select name="ifklicked_send_option_name[ifklicked_send_email_template]" id="ifklicked_send_email_template">';
        foreach($templates as $template) { ?>
            <?php $selected = (isset($this->ifklicked_send_options['ifklicked_send_email_template']) && $this->ifklicked_send_options['ifklicked_send_email_template'] === 'id-'.$template['id']) ? 'selected' : ''; ?>
            <option value="<?php echo 'id-'.$template['id']; ?>" data-url="<?php echo get_permalink($template['id']); ?>" <?php echo $selected; ?>><?php echo $template['title']; ?></option>
        <?php }
        echo '</select><a href="" class="klicked-preview klicked-preview-one" target="_blank">Preview</a>';
    }
    
    public function ifklicked_send_date_callback() {
        printf(
			'<input class="regular-text" type="text" name="ifklicked_send_option_name[ifklicked_send_date]" id="ifklicked_send_date" value="%s">',
			isset( $this->ifklicked_send_options['ifklicked_send_date'] ) ? esc_attr( $this->ifklicked_send_options['ifklicked_send_date']) : ''
		);
    }
    
    public function ifklicked_send_time_callback() {
        $times = ifklicked_get_times('true');
        date_default_timezone_set(get_option('timezone_string'));
        echo '<select name="ifklicked_send_option_name[ifklicked_send_time]" id="ifklicked_send_time">';
        foreach($times as $time) {
            $selected = (isset( $this->ifklicked_send_options['ifklicked_send_time'] ) && $this->ifklicked_send_options['ifklicked_send_time'] === $time) ? 'selected' : '' ; ?>
			<option value="<?php echo $time; ?>" <?php echo $selected; ?>><?php echo $time; ?></option>
        <?php }
        echo '</select><span class="note">If sending today, please select either <strong>Now</strong> or a time after '.date('h:00A').'.</span>';
    }
    
    public function ifklicked_from_email_callback() {
        printf(
			'<input class="regular-text" type="text" name="ifklicked_send_option_name[ifklicked_from_email]" id="ifklicked_from_email" value="%s"><span class="note">Please use a valid sending email address.</span>',
			isset( $this->ifklicked_send_options['ifklicked_from_email'] ) ? esc_attr( $this->ifklicked_send_options['ifklicked_from_email']) : ''
		);
    }
    
    public function ifklicked_from_name_callback() {
        printf(
			'<input class="regular-text" type="text" name="ifklicked_send_option_name[ifklicked_from_name]" id="ifklicked_from_name" value="%s"><span class="note">Please use a valid sending name.</span>',
			isset( $this->ifklicked_send_options['ifklicked_from_name'] ) ? esc_attr( $this->ifklicked_send_options['ifklicked_from_name']) : ''
		);
    }
    
    public function ifklicked_send_subject_callback() { ?>
        <select name="ifklicked_send_option_name[ifklicked_send_subject]" id="ifklicked_send_subject">
			<?php $selected = (isset( $this->ifklicked_send_options['ifklicked_send_subject'] ) && $this->ifklicked_send_options['ifklicked_send_subject'] === 'default') ? 'selected' : '' ; ?>
			<option value="default" <?php echo $selected; ?>>Default (<?php echo 'From '.get_bloginfo('name').' on '.date('F j, Y').' at '.date('h:i A'); ?>)</option>
            <?php $selected = (isset( $this->ifklicked_send_options['ifklicked_send_subject'] ) && $this->ifklicked_send_options['ifklicked_send_subject'] === 'page-title') ? 'selected' : '' ; ?>
			<option value="page-title" <?php echo $selected; ?>>Page Title</option>
            <?php $selected = (isset( $this->ifklicked_send_options['ifklicked_send_subject'] ) && $this->ifklicked_send_options['ifklicked_send_subject'] === 'custom') ? 'selected' : '' ; ?>
			<option value="custom" <?php echo $selected; ?>>Custom</option>
		</select> <?php
    }
    
    public function ifklicked_send_custom_subject_callback() {
        printf(
			'<input class="regular-text" type="text" name="ifklicked_send_option_name[ifklicked_send_custom_subject]" id="ifklicked_send_custom_subject" value="%s">',
			isset( $this->ifklicked_send_options['ifklicked_send_custom_subject'] ) ? esc_attr( $this->ifklicked_send_options['ifklicked_send_custom_subject']) : ''
		);
    }
}