<?php
/**
 * Awesome_Application_Form Install
 *
 * @package Awesome_Application_Form/Install
 * @since  1.0.0
 */

namespace AwesomeApplicationForm;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Install' ) ) :

	/**
	 * Install Class
	 *
	 * @class Install
	 */
	class Install {

		/**
		 * Initial actions to run when install class is run.
		 *
		 * @since 1.0.0
		 */
		public static function init() {

			add_action( 'admin_init', array( __CLASS__, 'install' ) );
		}

		/**
		 * Install actions when plugin is activated.
		 *
		 * This function is hooked into admin_init to affect admin only.
		 */
		public static function install() {

			if ( ! is_blog_installed() ) {
				return;
			}

			// Check if already installed.
			if ( get_option( 'aaf_installed', false ) ) {
				return;
			}

			// Check if already in the process of installing.
			if ( 'yes' === get_transient( 'aaf_installing' ) ) {
				return;
			}

			// If we made it till here nothing is running yet, lets set the transient now.
			set_transient( 'aaf_installing', 'yes', MINUTE_IN_SECONDS * 10 );

			! defined( 'AAF_INSTALLING' ) && define( 'AAF_INSTALLING', true );

			self::create_tables();

			delete_transient( 'aaf_installing' );
			update_option( 'aaf_installed', true );
		}

		/**
		 * Create table which the plugin will need to function;
		 */
		private static function create_tables() {
			global $wpdb;

			$wpdb->hide_errors();

			$collate = '';

			if ( $wpdb->has_cap( 'collation' ) ) {
				$collate = $wpdb->get_charset_collate();
			}

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';

			$sql = "
					CREATE TABLE IF NOT EXISTS {$wpdb->prefix}applicant_submissions (
						ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
						first_name varchar(255) NOT NULL,
						last_name varchar(255) NOT NULL,
						address varchar(255) NOT NULL,
						email varchar(100) NOT NULL,
						phone varchar(15) NOT NULL,
						post_name varchar(255) NOT NULL,
						cv varchar(100) NOT NULL,
						PRIMARY KEY (ID)
					) $collate;
							";

							dbDelta( $sql );
							error_log( print_r( dbDelta( $sql ), true ) );
		}
	}
endif;

Install::init();
