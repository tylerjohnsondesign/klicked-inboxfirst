<?php
/**
Form Settings
**/
class klickedIFForms {
	private $ifklicked_forms_options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'ifklicked_forms_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'ifklicked_forms_page_init' ) );
	}

	public function ifklicked_forms_add_plugin_page() {
		add_submenu_page(
            'inbox-first',
            'Forms',
            'Forms',
            'publish_pages',
            'inbox-first-forms',
            array($this, 'ifklicked_forms_create_admin_page')
        );
	}

	public function ifklicked_forms_create_admin_page() {
		include (IFKLICKED_BASE_PATH.'admin/templates/template-forms.php');
	}

	public function ifklicked_forms_page_init() {
	}
}