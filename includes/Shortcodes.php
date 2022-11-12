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
			'applicant_form'        => __CLASS__ . '::awesome_application_form',
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
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_style( "awesome-application-form-style", AWESOME_APPLICATION_FORM_ASSETS_URL . '/css/awesome-application-form.css', array(), AWESOME_APPLICATION_FORM_VERSION );
		wp_enqueue_script( "awesome-application-form-script", AWESOME_APPLICATION_FORM_ASSETS_URL . '/js/awesome-application-form' . $suffix . '.js', array(), AWESOME_APPLICATION_FORM_VERSION );

		wp_localize_script(
			"awesome-application-form-script",
			"awesome_application_form_script_params",
			array(
				'ajax_url'                              => admin_url( 'admin-ajax.php' ),
				'awesome_application_form_submit_nonce' => wp_create_nonce( 'awesome_application_form_submit_nonce' ),
				'cv_upload_nonce' => wp_create_nonce( 'cv_upload_nonce' ),
				'awesome_application_form_submit_button_text' => esc_html__( 'Submit', 'awesome-application-form'),
				'awesome_application_form_submitting_button_text' => esc_html__( 'Submitting ...', 'awesome-application-form'),
				'awesome_application_form_uploading_text' => esc_html__( 'Uploading ...', 'awesome-application-form')
				)
			);

		if ( is_user_logged_in() ) {
			include AWESOME_APPLICATION_FORM_TEMPLATE_PATH . '/awesome-application-form-page.php';
		}
	}

}
