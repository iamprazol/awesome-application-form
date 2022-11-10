<?php
/**
 * AwesomeApplicationForm Admin.
 *
 * @class    Admin
 * @version  1.0.0
 * @package  AwesomeApplicationForm/Admin
 */

namespace AwesomeApplicationForm\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin Class
 */
class Admin {

	/**
	 * Hook in tabs.
	 */
	public function __construct() {

		$this->init_hooks();
	}

	/**
	 * Initialize hooks.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function init_hooks() {
		add_action( 'admin_menu', array( $this, 'awesome_application_form_menu' ), 68 );
	}

	/**
	 * Add  menu item.
	 */
	public function awesome_application_form_menu() {
		$template_page = add_menu_page(
			__( 'Awesome Applications', 'awesome-application-form' ),
			__( 'Awesome Applications', 'awesome-application-form' ),
			'manage_options',
			'awesome-application-form',
			array(
				$this,
				'awesome_application_form_settings_page',
			), '', 56
		);

		add_action( 'load-' . $template_page, array( $this, 'template_page_init' ) );

	}

	/**
	 * Loads screen options into memory.
	 */
	public function template_page_init() {
		// Table display code here.
	}

	/**
	 *  Init the Awesome Application Form Settings page.
	 */
	public function awesome_application_form_settings_page() {
		ob_start();
		echo '<h1>Awesome Application Form Settings</h1>';
		return ob_get_clean();
	}
}
