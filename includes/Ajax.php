<?php
/**
 * AwesomeApplicationForm AJAX
 *
 * AJAX Event Handler
 *
 * @class    AJAX
 * @version  1.0.0
 * @package  AwesomeApplicationForm/Ajax
 * @category Class
 */

namespace  AwesomeApplicationForm;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * AJAX Class
 */
class AJAX {

	/**
	 * Hook in tabs.
	 */
	public function __construct() {
		self::add_ajax_events();
	}

	/**
	 * Hook in methods - uses WordPress ajax handlers (admin-ajax)
	 */
	public static function add_ajax_events() {

		add_action( 'wp_ajax_awesome_application_form_submit_form', array( __CLASS__, 'submit_form' ) );
		add_action('wp_ajax_nopriv_awesome_application_form_submit_form', array( __CLASS__, 'submit_form' ) );
	}

	/**
	 * Handle form submit.
	 */
	public static function submit_form() {
		global $wpdb;

		if ( ! check_ajax_referer( 'awesome_application_form_submit_nonce', 'security' ) ) {
			wp_send_json_error(
				array(
					'message' => esc_html__( 'Nonce error please reload.', 'awesome-application-form' ),
				)
			);
		}

		parse_str( $_POST['formData'], $form_data );
		$error_message = array();
		$applicant_data = array();

		if ( isset( $form_data['first_name'] ) && '' !== $form_data['first_name'] ) {
			$applicant_data['first_name'] = sanitize_text_field( $form_data['first_name'] );
		} else {
			$error_message['first_name_error'] = esc_html__( 'First Name field is required', 'awesome-application-form' );
		}

		if ( isset( $form_data['last_name'] ) && '' !== $form_data['last_name'] ) {
			$applicant_data['last_name'] = sanitize_text_field( $form_data['last_name'] );
		} else {
			$error_message['last_name_error'] = esc_html__( 'Last Name field is required', 'awesome-application-form' );
		}

		if ( isset( $form_data['user_email'] ) && '' !== $form_data['user_email'] ) {
			$applicant_data['email'] = sanitize_text_field( $form_data['user_email'] );
		} else {
			$error_message['user_email_error'] = esc_html__( 'Email field is required', 'awesome-application-form' );
		}

		if ( isset( $form_data['user_address'] ) && '' !== $form_data['user_address'] ) {
			$applicant_data['address'] = sanitize_text_field( $form_data['user_address'] );
		} else {
			$error_message['user_address_error'] = esc_html__( 'Present Address Field is required', 'awesome-application-form' );
		}

		if ( isset( $form_data['user_phone'] ) && '' !== $form_data['user_phone'] ) {
			$applicant_data['phone'] = sanitize_text_field( $form_data['user_phone'] );
		} else {
			$error_message['user_phone_error'] = esc_html__( 'Mobile No. Field is required', 'awesome-application-form' );
		}

		if ( isset( $form_data['post_name'] ) && '' !== $form_data['post_name'] ) {
			$applicant_data['post_name'] = sanitize_text_field( $form_data['post_name'] );
		} else {
			$error_message['post_name_error'] = esc_html__( 'Post Name Field is required', 'awesome-application-form' );
		}

		if ( ! empty( $error_message ) ) {
			wp_send_json_error( array( 'field_error' => $error_message ) );
		}

		$query_success = $wpdb->insert( 'wp_applicant_submissions', $applicant_data );

		if ( $query_success ) {
			wp_send_json_success(
				array(
					'message' => esc_html__( 'Application Submitted Successfully', 'awesome-application-form' )
				)
			);
		} else {
			wp_send_json_error(
				array(
					'message' => esc_html__( 'Application cannot be submitted at this moment. Please try again some time later', 'awesome-application-form' )
				)
			);
		}
	}

}
