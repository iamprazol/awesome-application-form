<?php
/**
 * Dashboard widget for user activity.
 *
 * @package AwesomeApplicationForm/Templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
	<div class="aaf-dashboard-widget-statictics">
		<ul>
			<li>
				<div class="aaf-widget-title">
					<?php esc_html_e( 'Name', 'awesome-application-form' ); ?>
				</div>
				<div class="aaf-applicants-name">
				</div>
			</li>

			<li>
				<div class="aaf-widget-title">
					<?php esc_html_e( 'Email', 'awesome-application-form' ); ?>
				</div>
				<div class="aaf-applicants-email">
				</div>
			</li>

			<li>
				<div class="aaf-widget-title">
					<?php esc_html_e( 'Post Name', 'awesome-application-form' ); ?>
				</div>
				<div class="aaf-applicants-post-name">
				</div>
			</li>
		</ul>
	</div>
