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

		add_action( 'wp_ajax_awesome_application_form_attachment_upload', array( __CLASS__, 'attachment_upload' ) );
		add_action('wp_ajax_nopriv_awesome_application_form_attachment_upload', array( __CLASS__, 'attachment_upload' ) );
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

		if ( isset( $form_data['aaf-file-input'] ) && '' !== $form_data['aaf-file-input'] ) {
			$applicant_data['cv'] = sanitize_text_field( $form_data['aaf-file-input'] );
		} else {
			$error_message['aaf-file-input_error'] = esc_html__( 'Upload CV Field is required', 'awesome-application-form' );
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

	/**
	 * File upload function
	 *
	 * @var array $_POST
	 */
	public static function attachment_upload() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['nonce'] ) ), 'cv_upload_nonce' ) ) { //phpcs:ignore;
			wp_send_json_error(
				array(
					'status'  => false,
					'message' => esc_html__( 'Nonce validation failed', "awesome-application-form" ),
				)
			);
		}

		$upload = isset( $_FILES['file'] ) ? $_FILES['file'] : array();

		$max_size = wp_max_upload_size();

		if ( ! isset( $upload['size'] ) || ( isset( $upload['size'] ) && $upload['size'] < 1 ) ) {

			wp_send_json_error(
				array(
					/* translators: %s - Max Size */
					'message' => sprintf( __( 'Please upload a file with size less than %s', 'awesome-application-form' ), size_format( $max_size ) ),
				)
			);
		}

		$upload_dir  = wp_upload_dir();
		$upload_path = apply_filters( 'awesome_application_form_cv_upload_url', $upload_dir['basedir'] . '/awesome_application_form/cv' ); /*Get path of upload dir of WordPress*/

		// Checks if the upload directory exists and create one if not.
		if ( ! file_exists( $upload_path ) ) {
			wp_mkdir_p( $upload_path );
		}

		if ( ! is_writable( $upload_path ) ) {  /*Check if upload dir is writable*/

			wp_send_json_error(
				array(
					'message' => __( 'Upload path permission deny.', 'awesome-application-form' ),
				)
			);

		}

		$upload_path = $upload_path . '/';
		$file_ext  = strtolower( pathinfo( $upload['name'], PATHINFO_EXTENSION ) );

		$file_name = wp_unique_filename( $upload_path, $upload['name'] );

		$file_path = $upload_path . sanitize_file_name( $file_name );

		if ( move_uploaded_file( $upload['tmp_name'], $file_path ) ) {

			$attachment_id = wp_insert_attachment(
				array(
					'guid'           => $file_path,
					'post_mime_type' => $file_ext,
					'post_title'     => preg_replace( '/\.[^.]+$/', '', sanitize_file_name( $file_name ) ),
					'post_content'   => '',
					'post_status'    => 'inherit',
				),
				$file_path
			);

			if ( is_wp_error( $attachment_id ) ) {

				wp_send_json_error(
					array(

						'message' => $attachment_id->get_error_message(),
					)
				);
			}

			// Generate and save the attachment metas into the database.
			wp_update_attachment_metadata( $attachment_id, wp_generate_attachment_metadata( $attachment_id, $file_path ) );

			$url = wp_get_attachment_url( $attachment_id );

			if ( empty( $url ) ) {
				$url = home_url() . '/wp-includes/images/media/text.png';
			}

			wp_send_json_success(
				array(
					'attachment_id'       => $attachment_id,
					'attachment_url' => $url,
				)
			);
		}

		wp_send_json_error(
			array(
				'message' => esc_html__( "File cannot be uploaded at the moment", "awesome-application-form"),
			)
		);
	}

}
