/* globals page, base_url */
$(function() {
	'use strict';
	if(page !== 'signup') { return false; }

	//Validate signup. This will fallback to HTML5 validation if JS isn't enabled.
	//This should run on all signup forms (initial & continued)
	$('#page[data-page=signup]').find('form').validate({
		ignore: [], //Make sure terms input is validated
		onkeyup: false,

		rules: {
			username: {
				minlength: 4,
				maxlength: 15,

				pattern: /^[a-zA-Z0-9_-]{4,15}$/,

				remote: {
					//This has a ratelimit cap of 10, if it reaches this no JS validations will appear (and will intend fallback to CI)
					url: `${base_url}ajax/username_check`,
					type: 'post',
					data: {
						username: function () {
							return $('input[name="username"]').val();
						}
					}
				}
			}
		},
		messages: {
			username: {
				minlength : 'This username is too short (Minimum 4 characters).',
				maxlength : 'This username is too long (Maximum 15 characters).',
				pattern   : 'This username has invalid characters (Only allowed a-z, A-Z, 0-9, _ & - characters).',
				remote    : 'This username already taken.'
			}
		}
	});

	//This is only used for the terms checkbox button :|
	$('.button-checkbox').each(function () {
		// Settings
		let $widget = $(this),
		    $button = $widget.find('button'),
		    $checkbox = $widget.find('input:checkbox'),
		    color = $button.data('color'),
		    settings = {
			    on: {
				    icon: 'glyphicon glyphicon-check'
			    },
			    off: {
				    icon: 'glyphicon glyphicon-unchecked'
			    }
		    };

		// Event Handlers
		$button.on('click', function () {
			$checkbox.prop('checked', !$checkbox.is(':checked'));
			$checkbox.triggerHandler('change');
			updateDisplay();
		});
		$checkbox.on('change', function () {
			updateDisplay();
		});

		// Actions
		function updateDisplay() {
			let isChecked = $checkbox.is(':checked');

			// Set the button's state
			$button.data('state', (isChecked) ? 'on' : 'off');

			// Set the button's icon
			$button.find('.state-icon')
				.removeClass()
				.addClass('state-icon ' + settings[$button.data('state')].icon);

			// Update the button's color
			if (isChecked) {
				$button
					.removeClass('btn-default')
					.addClass('btn-' + color + ' active');
			} else {
				$button
					.removeClass('btn-' + color + ' active')
					.addClass('btn-default');
			}
		}

		// Initialization
		function init() {
			updateDisplay();

			// Inject the icon if applicable
			if ($button.find('.state-icon').length === 0) {
				$button.prepend('<i class="state-icon ' + settings[$button.data('state')].icon + '"></i>');
			}
		}
		init();
	});
});
