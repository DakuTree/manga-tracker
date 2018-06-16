/* globals page */
$(function() {
	'use strict';
	if(page !== 'index') { return false; }

	$('.example-image').hover(function() {
		$(this).attr('src', (i, val) => {
			return val.replace(/\.png$/, '.gif');
		});
	}, function() {
		$(this).attr('src', (i, val) => {
			return val.replace(/\.gif$/, '.png');
		});
	});
});
