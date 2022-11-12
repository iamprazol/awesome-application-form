jQuery(function ($) {
	$(document.body).ready(function (e) {
		$(
			".aaf-applicants-name, .aaf-applicants-email, .aaf-applicants-post-name"
		)
			.html("")
			.html("<i>" + aaf_widget_params.loading + "</i>");

		var data = {
			action: "awesome_application_form_dashboard_widget",
			security: aaf_widget_params.widget_nonce,
		};

		$.post(aaf_widget_params.ajax_url, data, function (response) {
			var applications = response.data.applications,
				applicants_name = "",
				applicants_email = "",
				applicants_post_name = "";

			$.each(applications, function (field_key, field_value) {
				applicants_name +=
					"<i>" +
					field_value.first_name +
					" " +
					field_value.last_name +
					"</i>";
				applicants_email += "<i>" + field_value.email + "</i>";
				applicants_post_name += "<i>" + field_value.post_name + "</i>";
			});
			$(".aaf-applicants-name").html(applicants_name);
			$(".aaf-applicants-email").html(applicants_email);
			$(".aaf-applicants-post-name").html(applicants_post_name);
		}).fail(function (xhr) {
			window.console.log(xhr.responseText);
		});
	});
});
