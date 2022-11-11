/* global  awesome_application_form_script_params  */
jQuery(document).ready(function ($) {
	// Submit application when form is submitted.
	$("#awesome-application-form").submit((e) => {
		e.preventDefault();
		e.stopPropagation();

		$("#alerts-box").html("");
		$(".aaf-error").remove();

		var formData = $("#awesome-application-form").serialize();

		$.ajax({
			url: awesome_application_form_script_params.ajax_url,
			data: {
				action: "awesome_application_form_submit_form",
				security:
					awesome_application_form_script_params.awesome_application_form_submit_nonce,
				formData: formData,
			},
			type: "POST",
			beforeSend: function () {
				$("#aaf-submit-btn")
					.text(
						awesome_application_form_script_params.awesome_application_form_submitting_button_text
					)
					.prop("disabled", true);
			},
			success: function (response) {
				if (response.success) {
					$("#alerts-box").html(
						$("#alerts-box").html() +
							"<div class='aaf-success'>" +
							response.data.message +
							"</div>"
					);

					$("#awesome-application-form").each(function () {
						this.reset();
					});
				} else {
					if (response.data.field_error) {
						Object.keys(response.data.field_error).map(
							(field_key) => {
								$("#" + field_key.split("_error")[0])
									.closest(".aaf-input-row")
									.append(
										'<label class="aaf-error" for="' +
											field_key.split("_error")[0] +
											'">' +
											response.data.field_error[
												field_key
											] +
											"</label>"
									);
							}
						);
					} else {
						$("#alerts-box").html(
							$("#alerts-box").html() +
								"<div class='aaf-error'>" +
								response.data.message +
								"</div>"
						);
					}
				}

				$("#aaf-submit-btn")
					.text(
						awesome_application_form_script_params.awesome_application_form_submit_button_text
					)
					.prop("disabled", false);

				$(window).scrollTop(
					$(document)
						.find(".awesome_application_form_wrap")
						.find(".aaf-success, .aaf-error")
						.offset().top
				);
			},
		});
	});
});
