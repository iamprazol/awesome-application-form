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

<div class="awesome_application_form_wrap">
	<div id="alerts-box"></div>

	<form id="awesome-application-form" method="post">
		<div class="aaf-form-row">
			<div class="aaf-input-row">
				<label for="first_name" class="aaf-label"><?php esc_html_e( 'First Name', 'awesome-application-form' ); ?><abbr class="required" title="required">*</abbr></label>
				<input type="text" class="input-text aaf-frontend-field" id="first_name" name="first_name">
			</div>

			<div class="aaf-input-row">
				<label for="last_name" class="aaf-label"><?php esc_html_e( 'Last Name', 'awesome-application-form' ); ?><abbr class="required" title="required">*</abbr></label>
				<input type="text" class="input-text aaf-frontend-field" id="last_name" name="last_name">
			</div>
		</div>

		<div class="aaf-form-row">
			<div class="aaf-input-row">
				<label for="user_email" class="aaf-label"><?php esc_html_e( 'Email', 'awesome-application-form' ); ?><abbr class="required" title="required">*</abbr></label>
				<input type="email" class="input-text aaf-frontend-field" id="user_email" name="user_email">
			</div>
			<div class="aaf-input-row">
				<label for="user_address" class="aaf-label"><?php esc_html_e( 'Present Address', 'awesome-application-form' ); ?><abbr class="required" title="required">*</abbr></label>
				<input type="text" class="input-text aaf-frontend-field" id="user_address" name="user_address">
			</div>
		</div>

		<div class="aaf-form-row">
			<div class="aaf-input-row">
				<label for="user_phone" class="aaf-label"><?php esc_html_e( 'Mobile No', 'awesome-application-form' ); ?><abbr class="required" title="required">*</abbr></label>
				<input type="tel" class="input-text aaf-frontend-field" id="user_phone" name="user_phone" />
			</div>
		</div>

		<div class="aaf-form-row">
			<div class="aaf-input-row">
				<label for="post_name" class="aaf-label"><?php esc_html_e( 'Post Name', 'awesome-application-form' ); ?><abbr class="required" title="required">*</abbr></label>
				<input type="text" class="input-text aaf-frontend-field" id="post_name" name="post_name">
			</div>
		</div>

		<div class="aaf-form-row">
			<div class="aaf-input-row aaf-file-uploader">
				<label for="aaf-user-file" class="aaf-label"><?php esc_html_e( 'Upload CV', 'awesome-application-form' ); ?><abbr class="required" title="required">*</abbr></label>
				<input type="file" name="aaf-user-file" id="aaf-user-file" class="aaf-user-file-upload-input" >
				<div class="aaf-file-uploaded"></div>
				<input type="hidden" name="aaf-file-input" id="aaf-file-input"/>
			</div>
		</div>

		<div class="aaf-form-row">
			<button type="submit" class="btn btn-primary" name="aaf-submit" id="aaf-submit-btn"><?php esc_html_e( 'Submit', 'awesome-application-form' ); ?></button>
		</div>
	</form>
</div>
