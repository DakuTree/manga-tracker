$(function(){
	//Validate signup. This will fallback to HTML5 validation if JS isn't enabled.
	//This should run on all signup forms (initial & continued)
	$('#page[data-page=signup] form').validate({
		ignore: [], //Make sure terms input is validated
		onkeyup: false,

		rules: {
			username: {
				minlength: 4,
				maxlength: 15,

				pattern: /^[a-zA-Z0-9_-]{4,15}$/,

				remote: {
					//This has a ratelimit cap of 10, if it reaches this no JS validations will appear (and will intend fallback to CI)
					url: base_url+"ajax/username_check",
					type: "post",
					data: {
						username: function () {
							return $("input[name='username']").val();
						}
					}
				}
			}
		},
		messages: {
			username: {
				minlength: "This username is too short (Minimum 4 characters).",
				maxlength: "This username is too long (Maximum 15 characters).",
				pattern: "This username has invalid characters (Only allowed a-z, A-Z, 0-9, _ & - characters).",
				remote: "This username already taken."
			}
		}
	});
});
