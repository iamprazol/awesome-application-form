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
