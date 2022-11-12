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

		// Set screens.
		add_filter( 'set-screen-option', array( $this, 'set_screen_option' ), 10, 3 );
	}

	/**
	 * Initialize hooks.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function init_hooks() {

		$suffix    = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		add_action( 'admin_menu', array( $this, 'awesome_application_form_menu' ), 68 );

		wp_register_style( 'awesome-application-form-dashboard-widget-style', AWESOME_APPLICATION_FORM_ASSETS_URL . '/css/dashboard.css', array(), AWESOME_APPLICATION_FORM_VERSION );
		wp_register_script( 'awesome-application-form-dashboard-widget-js',AWESOME_APPLICATION_FORM_ASSETS_URL . '/js/dashboard-widget' . $suffix . '.js', 'jquery', AWESOME_APPLICATION_FORM_VERSION, false );

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
				'awesome_application_form_list_page',
			), '', 56
		);

		add_action( 'load-' . $template_page, array( $this, 'template_page_init' ) );

	}

	/**
	 * Loads screen options into memory.
	 */
	public function template_page_init() {
		global $application_table_list;

		$application_table_list = new ListTable();
		$application_table_list->process_actions();

		// Add screen option.
		add_screen_option(
			'per_page',
			array(
				'default' => 20,
				'option'  => 'applications_per_page',
			)
		);

		do_action( 'template_page_init' );

	}

	/**
	 *  Init the Awesome Application Form Settings page.
	 */
	public function awesome_application_form_list_page() {
		global $application_table_list;
		$application_table_list->display_page();
	}

	/**
	 * Validate screen options on update.
	 *
	 * @param mixed $status Status.
	 * @param mixed $option Option.
	 * @param mixed $value Value.
	 */
	public function set_screen_option( $status, $option, $value ) {
		if ( in_array( $option, array( 'applications_per_page' ), true ) ) {
			return $value;
		}

		return $status;
	}
}
