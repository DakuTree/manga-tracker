$(function(){
	"use strict";

	//Sort by unread, then alphabetically.
	$(".tablesorter").tablesorter({
		sortList: [[0,0], [1,0]]
	});

	$('.update-read').click(function(e) {
		var _this = this;
		var row            = $(this).closest('tr'),
		    chapter_id     = $(row).attr('data-id'),
		    current_chapter = $(row).find('.current'),
		    latest_chapter = $(row).find('.latest');

		$.post(base_url + 'ajax/update_tracker_inline', {id: chapter_id, chapter: latest_chapter.text()}, function (data) {
			$(_this).hide();
			$(current_chapter).attr('href', $(latest_chapter).attr('href')).text($(latest_chapter).text());
		});
	});
});
