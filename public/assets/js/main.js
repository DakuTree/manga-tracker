/*global $:false, jQuery:false, validate:false, base_url:false */
$(function(){
	"use strict";

	//Initialize bootstrap tooltip.js
	$('[data-toggle="tooltip"]').tooltip({html: true});

	//Initialize header update timer
	var timer_obj = $('#update-timer'),
		timer_arr = timer_obj.text().split(':'),
		time_left = parseInt(timer_arr[0] * 60 * 60, 10) + parseInt(timer_arr[1] * 60, 10) + parseInt(timer_arr[2], 10);
	var timer = setInterval(function() {
		var hours   = parseInt(time_left / 60 / 60, 10).toString(),
		    minutes = parseInt(time_left / 60 % 60, 10).toString(),
		    seconds = parseInt(time_left % 60, 10).toString();

		if(  hours.length == 1) hours   = '0'+hours;
		if(minutes.length == 1) minutes = '0'+minutes;
		if(seconds.length == 1) seconds = '0'+seconds;

		timer_obj.text(hours + ':' + minutes + ':' + seconds);

		if(--time_left < 0) {
			clearInterval(timer);
			//location.reload(); //?
		}
	}, 1000);
});
