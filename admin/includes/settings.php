<?php
/**
Main Settings
**/
class klickedIFSettings {
	private $ifklicked_main_options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'ifklicked_main_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'ifklicked_main_page_init' ) );
	}

	public function ifklicked_main_add_plugin_page() {
		add_menu_page(
			'Inbox First', // page_title
			'Inbox First', // menu_title
			'manage_options', // capability
			'inbox-first', // menu_slug
			array( $this, 'ifklicked_main_create_admin_page' ), // function
			'dashicons-email', // icon_url
			25 // position
		);
	}

	public function ifklicked_main_create_admin_page() {
		include (IFKLICKED_BASE_PATH.'admin/templates/template-settings.php');
	}

	public function ifklicked_main_page_init() {
		register_setting(
			'ifklicked_main_option_group', // option_group
			'ifklicked_main_option_name', // option_name
			array( $this, 'ifklicked_main_sanitize' ) // sanitize_callback
		);

		add_settings_section(
			'ifklicked_main_setting_section', // id
			'', // title
			array( $this, 'ifklicked_main_section_info' ), // callback
			'inbox-first-main' // page
		);

		add_settings_field(
			'ifklicked_api_key', // id
			'API Key', // title
			array( $this, 'ifklicked_api_key_callback' ), // callback
			'inbox-first-main', // page
			'ifklicked_main_setting_section' // section
		);

	}

	public function ifklicked_main_sanitize($input) {
		$sanitary_values = array();
		if ( isset( $input['ifklicked_api_key'] ) ) {
			$sanitary_values['ifklicked_api_key'] = sanitize_text_field( $input['ifklicked_api_key'] );
		}

		return $sanitary_values;
	}

	public function ifklicked_main_section_info() {
		// Nothing.
	}

	public function ifklicked_api_key_callback() {
		printf(
			'<input class="regular-text" type="text" name="ifklicked_main_option_name[ifklicked_api_key]" id="ifklicked_api_key" value="%s">',
			isset( $this->ifklicked_main_options['ifklicked_api_key'] ) ? esc_attr( $this->ifklicked_main_options['ifklicked_api_key']) : ''
		);
	}

}