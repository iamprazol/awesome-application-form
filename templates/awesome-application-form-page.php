<?php
/**
 * Awesome Application Form Layout
 *
 * @package AwesomeApplicationForm/Templates
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>

<div class="awesome_application_form_wrap" style="background: #cccccc">
	<form action="/" id="awesome-application-form" method="POST">

		<input type="hidden" name="aaf-nonce" value="<?php esc_html( wp_create_nonce( 'aaf-nonce' ) ); ?>" >
		<div class="aaf-form-row">
			<div class="aaf-input-row">
				<label for="first_name" class="aff-label"><?php esc_html_e( 'First Name', 'awesome-application-form' ); ?></label>
				<input type="text" class="input-text aaf-frontend-field" id="first_name" name="first_name">
			</div>

			<div class="aaf-input-row">
				<label for="last_name"><?php esc_html_e( 'Last Name', 'awesome-application-form' ); ?></label>
				<input type="text" class="input-text aaf-frontend-field" id="last_name" name="last_name">
			</div>
		</div>

		<div class="aaf-form-row">
			<div class="aaf-input-row">
				<label for="user_email"><?php esc_html_e( 'Email', 'awesome-application-form' ); ?></label>
				<input type="email" class="input-text aaf-frontend-field" id="user_email" name="user_email">
			</div>
			<div class="aaf-input-row">
				<label for="user_address"><?php esc_html_e( 'Present Address', 'awesome-application-form' ); ?></label>
				<input type="text" class="input-text aaf-frontend-field" id="user_address" name="user_address">
			</div>
		</div>

		<div class="aaf-form-row">
			<div class="aaf-input-row">
				<label for="user_phone"><?php esc_html_e( 'Mobile No', 'awesome-application-form' ); ?></label>
				<input type="tel" class="input-text aaf-frontend-field" id="user_phone" name="user_phone" />
			</div>
		</div>

		<div class="aaf-form-row">
			<div class="aaf-input-row">
				<label for="post_name" class="col-md-3"><?php esc_html_e( 'Post Name', 'awesome-application-form' ); ?> (1 - 5)</label>
				<input type="text" class="input-text aaf-frontend-field" id="post_name" name="post_name">
			</div>
		</div>

		<div class="aaf-form-row">
			<button type="submit" class="btn btn-primary" name="aaf-submit" id="aaf-submit-btn"><?php esc_html_e( 'Submit', 'awesome-application-form' ); ?></button>
		</div>
	</form>
</div>
