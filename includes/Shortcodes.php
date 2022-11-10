<?php
/**
 *  Shortcodes.
 *
 * @class    Shortcodes
 * @version  1.0.0
 * @package  AwesomeApplicationForm/Classes
 */

namespace  AwesomeApplicationForm;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shortcodes Class
 */
class Shortcodes {

	/**
	 * Init Shortcodes.
	 */
	public function __construct() {
		$shortcodes = array(
			'application_form'        => __CLASS__ . '::awesome_application_form',
		);

		foreach ( $shortcodes as $shortcode => $function ) {
			add_shortcode( apply_filters( "{$shortcode}_shortcode_tag", $shortcode ), $function );
		}
	}

	/**
	 * Application Form shortcode.
	 *
	 * @param mixed $atts Attributes.
	 */
	public static function awesome_application_form( $atts ) {

		ob_start();
		self::render_application_form();
		return ob_get_clean();
	}

	/**
	 * Output for Application Form.
	 *
	 * @since 1.0.0
	 */
	public static function render_application_form() {

		wp_enqueue_style( "awesome-application-form-style", AWESOME_APPLICATION_FORM_ASSETS_URL . '/css/awesome-application-form.css', array(), AWESOME_APPLICATION_FORM_VERSION );

		if ( is_user_logged_in() ) {
			include AWESOME_APPLICATION_FORM_TEMPLATE_PATH . '/awesome-application-form-page.php';
		}
	}

}
