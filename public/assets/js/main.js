$(function(){
	'use strict';

	//Initialize bootstrap tooltip.js
	$('[data-toggle="tooltip"]').tooltip({html: true});

	//Initialize tablesorter
	//NOTE/TODO(?) (for dashboard): We prepend 0 or 1 to the first column which allows us organize by unread, however at the moment we're doing that on the backend instead.
	//                              This has a speed cost, but it's a better user-experience. Can we make tablesorter better / as fast?
	$('.tablesorter').tablesorter(/*{
		sortList: [[0,0], [1,0]]
	}*/);

	$.fn.bootstrapShow = function() {
		this.addClass('show');
		this.removeClass('hidden');
		return this;
	};
	$.fn.bootstrapHide = function() {
		this.addClass('hidden');
		this.removeClass('show');
		return this;
	};
});
