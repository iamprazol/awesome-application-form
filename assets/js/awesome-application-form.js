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

	// Handle CV upload.
	$(document).ready(function () {
		$(document).on("change", "#aaf-user-file", function () {
			var formData = new FormData();

			if (this.files && this.files[0]) {
				formData.append("file", this.files[0]);
			}

			formData.append(
				"nonce",
				awesome_application_form_script_params.cv_upload_nonce
			);
			formData.append(
				"action",
				"awesome_application_form_attachment_upload"
			);

			$(".aaf-file-uploaded").html(
				"<div class='file-info-section aaf-file-details'><div class='file-info aaf-file-thumb'><p class='file-name'>" +
					awesome_application_form_script_params.awesome_application_form_uploading_text +
					"</p></div></div>"
			);

			$.ajax({
				type: "POST",
				url: awesome_application_form_script_params.ajax_url,
				data: formData,
				processData: false,
				contentType: false,
				success: function (response) {
					if (response.success) {
						$(".aaf-file-uploaded").html("");

						var attachment_id = response.data.attachment_id,
							url = response.data.attachment_url,
							filename = url.split("/").pop();

						result =
							'<div class="file-info-section aaf-file-details">';
						result += '<div class="file-image">';
						result +=
							'<img width="30" height="30" src="/wp-content/plugins/awesome-application-form/assets/img/default.svg" />';
						result += "</div>";
						result += '<div class="file-info aaf-file-thumb">';
						result +=
							'<a href="' +
							url +
							'" target="_blank" class="aaf-attachment-link">';
						result += '<p class="file-name">' + filename + "</p>";
						result += "</a>";
						result += "</div>";
						result +=
							'<a class="aaf-remove" data-attachment-id=" ' +
							attachment_id +
							' "><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg></a></div>';

						$("#aaf-file-input").val(attachment_id);
						$(".aaf-file-uploaded").append(result);
					}
				},
			});
		});
	});
});
