<?php
/**
 * Awesome_Application_Form setup
 *
 * @package Awesome_Application_Form
 * @since  1.0.0
 */

namespace AwesomeApplicationForm;

use AwesomeApplicationForm\Admin\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'ApplicationForm' ) ) :

	/**
	 * Main ApplicationForm Class
	 *
	 * @class ApplicationForm
	 */
	final class ApplicationForm {


		/**
		 * Instance of this class.
		 *
		 * @var object
		 */
		protected static $instance = null;

		/**
		 * Instance of Install Class.
		 *
		 * @since 1.0.0
		 *
		 * @var use AwesomeApplicationForm\Install;
		 */
		public $install = null;

		/**
		 * Admin class instance
		 *
		 * @var \Admin
		 * @since 1.0.0
		 */
		public $admin = null;

		/**
		 * Shortcodes.
		 *
		 * @since 1.0.0
		 *
		 * @var AwesomeApplicationForm\Shortcodes;
		 */
		public $shortcodes = null;

		/**
		 * Ajax.
		 *
		 * @since 1.0.0
		 *
		 * @var AwesomeApplicationForm\Ajax;
		 */
		public $ajax = null;

		/**
		 * Plugin Version
		 *
		 * @var string
		 */
		const VERSION = AWESOME_APPLICATION_FORM_VERSION;

		/**
		 * Return an instance of this class
		 *
		 * @return object A single instance of this class.
		 */
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 */
		private function __construct() {
			require 'Functions/CoreFunctions.php';

			// Set up localisation.
			$this->load_plugin_textdomain();

			// Actions and Filters.
			add_filter( 'plugin_action_links_' . plugin_basename( AWESOME_APPLICATION_FORM_PLUGIN_FILE ), array( $this, 'plugin_action_links' ) );
			add_action( 'init', array( $this, 'includes' ) );

			register_activation_hook( __FILE__, array( 'Install', 'install' ) );

		}

		/**
		 * Includes.
		 */
		public function includes() {

			// Files to include.
			$this->install = new Install();
			$this->shortcodes = new Shortcodes();
			$this->ajax = new Ajax();

			// Class admin.
			if ( $this->is_admin() ) {
				// require file.
				$this->admin = new Admin();
			}

		}

		/**
		 * Check if is admin or not and load the correct class
		 *
		 * @return bool
		 * @since 1.0.0
		 */
		public function is_admin() {
			$check_ajax    = defined( 'DOING_AJAX' ) && DOING_AJAX;
			$check_context = isset( $_REQUEST['context'] ) && $_REQUEST['context'] == 'frontend';

			return is_admin() && ! ( $check_ajax && $check_context );
		}

		/**
		 * Display action links in the Plugins list table.
		 *
		 * @param array $actions Add plugin action link.
		 *
		 * @return array
		 */
		public function plugin_action_links( $actions ) {
			$new_actions = array(
				'settings' => '<a href="' . admin_url( 'admin.php?page=awesome-application-form' ) . '" title="' . esc_attr( __( 'View Awesome Application Form Settings', 'awesome-application-form' ) ) . '">' . __( 'Settings', 'awesome-application-form' ) . '</a>',
			);

			return array_merge( $new_actions, $actions );
		}

		/**
		 * Get the plugin url.
		 *
		 * @return string
		 */
		public function plugin_url() {
			return untrailingslashit( plugins_url( '/', __FILE__ ) );
		}

		/**
		 * Load Localisation files.
		 *
		 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
		 *
		 * Locales found in:
		 *      - WP_LANG_DIR/awesome-application-form/awesome-application-form-LOCALE.mo
		 *      - WP_LANG_DIR/plugins/awesome-application-form-LOCALE.mo
		 */
		public function load_plugin_textdomain() {
			$locale = is_admin() && function_exists( 'get_user_locale' ) ? get_user_locale() : get_locale();
			$locale = apply_filters( 'plugin_locale', $locale, 'awesome-application-form' );

			unload_textdomain( 'awesome-application-form' );
			load_textdomain( 'awesome-application-form', WP_LANG_DIR . '/awesome-application-form/awesome-application-form-' . $locale . '.mo' );
			load_plugin_textdomain( 'awesome-application-form', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
		}

	}
endif;

/**
 * Main instance of ApplicationForm.
 *
 * @since  1.0.0
 * @return ApplicationForm
 */
function ApplicationForm() {
	return ApplicationForm::get_instance();
}
