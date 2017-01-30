$(function () {
	"use strict";
	if(page !== 'options') { return false; }

	//Disallow disabling category if category has series
	$('input[type=checkbox][name^=category_custom]').change(function () {
		if($(this).data('has-series') === '1') {
			//FIXME: Using alerts is kinda ugh.
			alert('Unable to disable category while it still contains series.');
			$(this).prop('checked', !$(this).prop('checked'));
		}
	});

	//Enable category when text box is clicked
	$('input[type=text][name^=category_custom]').click(function () {
		$(this).parent().find('[type=checkbox]').attr('checked', true);
	});
});
