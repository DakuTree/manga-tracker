$(function(){
	"use strict";

	$('input[type=checkbox][name^=category_custom]').change(function(e) {

		if($(this).data('has-series') == '1') {
			//FIXME: Using alerts is kinda ugh.
			alert('Unable to disable category while it still contains series.');
			$(this).prop('checked', !$(this).prop('checked'));
		}
	});

	$('input[type=text][name^=category_custom]').click(function() {
		$(this).parent().find('[type=checkbox]').attr('checked', true);
	});
});
