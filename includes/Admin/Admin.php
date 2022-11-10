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

	}

	/**
	 *  Init the Awesome Application Form Settings page.
	 */
	public function awesome_application_form_list_page() {
		global $application_table_list;
		$application_table_list->display_page();
	}
}
