<?php
/**
 * Plugin Name: Awesome Application Form
 * Plugin URI: https://wprdpress.org/plugins
 * Description: Awesome Application Form Plugin For WordPress.
 * Version: 1.0.0
 * Author: Prajjwal Poudel
 * Author URI: http://prajjwalpoudel.com.np
 * Text Domain: awesome-application-form
 * Domain Path: /languages/
 *
 * Copyright: © 2022 Prajjwal.
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package Awesome_Application_Form
 */

defined( 'ABSPATH' ) || exit;

if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
	require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

use AwesomeApplicationForm\ApplicationForm;

if ( ! defined( 'AWESOME_APPLICATION_FORM_VERSION' ) ) {
	define( 'AWESOME_APPLICATION_FORM_VERSION', '1.0.0' );
}

// Define AWESOME_APPLICATION_FORM_PLUGIN_FILE.
if ( ! defined( 'AWESOME_APPLICATION_FORM_PLUGIN_FILE' ) ) {
	define( 'AWESOME_APPLICATION_FORM_PLUGIN_FILE', __FILE__ );
}

// Define AWESOME_APPLICATION_FORM_DIR.
if ( ! defined( 'AWESOME_APPLICATION_FORM_DIR' ) ) {
	define( 'AWESOME_APPLICATION_FORM_DIR', plugin_dir_path( __FILE__ ) );
}

// Define AWESOME_APPLICATION_FORM_DS.
if ( ! defined( 'AWESOME_APPLICATION_FORM_DS' ) ) {
	define( 'AWESOME_APPLICATION_FORM_DS', DIRECTORY_SEPARATOR );
}

// Define AWESOME_APPLICATION_FORM_URL.
if ( ! defined( 'AWESOME_APPLICATION_FORM_URL' ) ) {
	define( 'AWESOME_APPLICATION_FORM_URL', plugin_dir_url( __FILE__ ) );
}

// Define AWESOME_APPLICATION_FORM_ASSETS_URL.
if ( ! defined( 'AWESOME_APPLICATION_FORM_ASSETS_URL' ) ) {
	define( 'AWESOME_APPLICATION_FORM_ASSETS_URL', AWESOME_APPLICATION_FORM_URL . 'assets' );
}

// Define AWESOME_APPLICATION_FORM_TEMPLATE_PATH.
if ( ! defined( ' AWESOME_APPLICATION_FORM_TEMPLATE_PATH' ) ) {
	define( 'AWESOME_APPLICATION_FORM_TEMPLATE_PATH', AWESOME_APPLICATION_FORM_DIR . 'templates' );
}

/**
 * Initialization of ApplicationForm instance.
 **/
function awesome_application_form() {
	return ApplicationForm::get_instance();
}

awesome_application_form();
