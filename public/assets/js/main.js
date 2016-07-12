/*global $:false, jQuery:false, validate:false, base_url:false */
$(function(){
	"use strict";

	//Initialize bootstrap tooltip.js
	$('[data-toggle="tooltip"]').tooltip({html: true});

	$(window).on("load", function() {
		setTimeout(function() {
			if($('#loading-userscript').length) {
				$('#loading-userscript').replaceWith('USERSCRIPT NOT FOUND');
			}
		}, 5000);
	});
});
