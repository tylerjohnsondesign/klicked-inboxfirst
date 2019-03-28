<?php
/**
Send
**/
class klickedIFSubscribe {
	private $ifklicked_subscribe_options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'ifklicked_subscribe_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'ifklicked_subscribe_page_init' ) );
	}

	public function ifklicked_subscribe_add_plugin_page() {
		add_submenu_page(
            'inbox-first',
            'Subscribe',
            'Subscribe',
            'publish_pages',
            'inbox-first-subscribe',
            array($this, 'ifklicked_subscribe_create_admin_page')
        );
	}

	public function ifklicked_subscribe_create_admin_page() {
		include (IFKLICKED_BASE_PATH.'admin/templates/template-subscribe.php');
	}

	public function ifklicked_subscribe_page_init() {
		register_setting(
			'ifklicked_subscribe_option_group', // option_group
			'ifklicked_subscribe_option_name', // option_name
			array( $this, 'ifklicked_subscribe_sanitize' ) // sanitize_callback
		);

		add_settings_section(
			'ifklicked_subscribe_setting_section', // id
			'', // title
			array( $this, 'ifklicked_subscribe_section_info' ), // callback
			'inbox-first-subscribe' // page
		);
        
        add_settings_field(
			'ifklicked_subscribe_logo', // id
			'Logo', // title
			array( $this, 'ifklicked_subscribe_logo_callback' ), // callback
			'inbox-first-subscribe', // page
			'ifklicked_subscribe_setting_section' // section
		);
        
        add_settings_field(
			'ifklicked_subscribe_list', // id
			'List', // title
			array( $this, 'ifklicked_subscribe_list_callback' ), // callback
			'inbox-first-subscribe', // page
			'ifklicked_subscribe_setting_section' // section
		);
        
        add_settings_field(
			'ifklicked_subscribe_primary', // id
			'Primary Color', // title
			array( $this, 'ifklicked_subscribe_primary_callback' ), // callback
			'inbox-first-subscribe', // page
			'ifklicked_subscribe_setting_section' // section
		);
        
        add_settings_field(
			'ifklicked_subscribe_background', // id
			'Background Color', // title
			array( $this, 'ifklicked_subscribe_background_callback' ), // callback
			'inbox-first-subscribe', // page
			'ifklicked_subscribe_setting_section' // section
		);
	}

	public function ifklicked_subscribe_sanitize($input) {
		$sanitary_values = array();
        if(isset($input['ifklicked_subscribe_logo'])) {
            $sanitary_values['ifklicked_subscribe_logo'] = sanitize_text_field($input['ifklicked_subscribe_logo']);
        }
        
        if(isset($input['ifklicked_subscribe_list'])) {
            $sanitary_values['ifklicked_subscribe_list'] = $input['ifklicked_subscribe_list'];
        }
        
        if(isset($input['ifklicked_subscribe_primary'])) {
            $sanitary_values['ifklicked_subscribe_primary'] = sanitize_text_field($input['ifklicked_subscribe_primary']);
        }
        
        if(isset($input['ifklicked_subscribe_background'])) {
            $sanitary_values['ifklicked_subscribe_background'] = sanitize_text_field($input['ifklicked_subscribe_background']);
        }
        
		return $sanitary_values;
	}

	public function ifklicked_subscribe_section_info() {
		// Nothing here.
	}
    
    public function ifklicked_subscribe_logo_callback() {
        printf(
			'<input class="regular-text" type="text" name="ifklicked_subscribe_option_name[ifklicked_subscribe_logo]" id="ifklicked_subscribe_logo" value="%s"><button class="button klicked-subscribe-logo-upload">Upload</button><div id="ifklicked_subscribe_logo_display"></div>',
			isset( $this->ifklicked_subscribe_options['ifklicked_subscribe_logo'] ) ? esc_attr( $this->ifklicked_subscribe_options['ifklicked_subscribe_logo']) : ''
		);
    }
    
    public function ifklicked_subscribe_list_callback() {
        // Variables
        $lists = ifklicked_get_lists(IFKLICKED_IBF_KEY);
        echo '<select name="ifklicked_subscribe_option_name[ifklicked_subscribe_list]" id="ifklicked_subscribe_list">';
        foreach($lists['data'] as $list) { ?>
			<?php $selected = (isset( $this->ifklicked_subscribe_options['ifklicked_subscribe_list'] ) && $this->ifklicked_subscribe_options['ifklicked_subscribe_list'] === 'list-'.$list['id']) ? 'selected' : '' ; ?>
			<option value="<?php echo 'list-'.$list['id']; ?>" <?php echo $selected; ?>><?php echo $list['name']; ?></option><?php
        }
        echo '</select>';
    }
    
    public function ifklicked_subscribe_primary_callback() {
        printf(
			'<input class="regular-text klicked-sub-colors" type="text" name="ifklicked_subscribe_option_name[ifklicked_subscribe_primary]" id="ifklicked_subscribe_primary" value="%s">',
			isset( $this->ifklicked_subscribe_options['ifklicked_subscribe_primary'] ) ? esc_attr( $this->ifklicked_subscribe_options['ifklicked_subscribe_primary']) : ''
		);
    }
    
    public function ifklicked_subscribe_background_callback() {
        printf(
			'<input class="regular-text klicked-sub-colors" type="text" name="ifklicked_subscribe_option_name[ifklicked_subscribe_background]" id="ifklicked_subscribe_background" value="%s">',
			isset( $this->ifklicked_subscribe_options['ifklicked_subscribe_background'] ) ? esc_attr( $this->ifklicked_subscribe_options['ifklicked_subscribe_background']) : ''
		);
    }
}