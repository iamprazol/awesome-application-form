<?php
/**
 * AwesomeApplicationForm CoreFunctions.
 *
 * General core functions available on both the front-end and admin.
 *
 * @author   WPMake
 * @category Core
 * @package  AwesomeApplicationForm/Handler
 * @version  1.0.0
 */

add_action( 'awesome_application_form_after_application_submission', 'awesome_application_form_send_application_received_mail', 10, 2 );

if ( ! function_exists( 'awesome_application_form_send_application_received_mail' ) ) {

	/**
	 * Send an email to the applicant.
	 *
	 * @param email $email Applicant's Email.
	 * @param string $fullname Applicant's Full Name.
	 */
	function awesome_application_form_send_application_received_mail( $email, $fullname ) {

		$data = array(
			'email' => $email,
			'fullname' => $fullname
		);

		$header  = "Reply-To: {{email}} \r\n";
		$header .= 'Content-Type: text/html; charset=UTF-8';
		$admin_email = get_option( 'admin_email' );
		$admin_email = explode( ',', $admin_email );
		$admin_email = array_map( 'trim', $admin_email );

		$mail_subject   = esc_html__( 'Thank You for the application', 'awesome-application-form' );
		$message      = apply_filters(
			'awesome_application_form_application_submission_email_message',
			sprintf(
				__(
					'Dear {{fullname}}, <br/>
					We have received your application successfully. We will take some time to review your application and will get back to you in less tha 3 weeks:<br/>
					Email: {{email}},
					Fullname: {{fullname}}<br/><br/>
					Thank You!'
				),
				'awesome-application-form'
			)
		);

		foreach( $data as $key => $value ) {
			$message = str_replace( '{{' . $key . '}}', $value, $message );
			$header = str_replace( '{{' . $key . '}}', $value, $header );
		}

		wp_mail( $email, $mail_subject, $message, $header );
	}
}

add_action( 'wp_dashboard_setup', 'aaf_add_dashboard_widget' );

if ( ! function_exists( 'aaf_add_dashboard_widget' ) ) {

	/**
	 * Register the applications dashboard widget.
	 *
	 */
	function aaf_add_dashboard_widget() {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		wp_add_dashboard_widget( 'awesome_application_form_latest_submissions', esc_html__( 'Latest Applications', 'awesome-application-form' ), 'aaf_application_widget' );
	}
}

if ( ! function_exists( "aaf_application_widget" ) ) {

	/**
	 * Content to the aaf_application_widget widget.
	 *
	 */
	function aaf_application_widget() {

		wp_enqueue_script( 'awesome-application-form-dashboard-widget-js' );
		wp_enqueue_style( 'awesome-application-form-dashboard-widget-style' );

		wp_localize_script(
			'awesome-application-form-dashboard-widget-js',
			'aaf_widget_params',
			array(
				'ajax_url'     => admin_url( 'admin-ajax.php' ),
				'loading'      => esc_html__( 'loading...', 'awesome-application-form' ),
				'widget_nonce' => wp_create_nonce( 'dashboard-widget' ),
			)
		);

		include AWESOME_APPLICATION_FORM_TEMPLATE_PATH . '/dashboard-widget.php';
	}
}
