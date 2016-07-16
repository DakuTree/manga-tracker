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

	//UX: This makes it easier to press the checkbox
	$('#tracker-table').find('> tbody > tr > td:nth-of-type(1)').click(function (e) {
		if(!$(e.target).is('input')) {
			var checkbox = $(this).find('> input[type=checkbox]');
			$(checkbox).prop("checked", !checkbox.prop("checked"));
		}
	});

	$('#delete_selected').click(function(e) {
		e.preventDefault();

		var checked_rows = $('#tracker-table').find('tr:has(td input[type=checkbox]:checked)');
		if(checked_rows.length > 0) {
			var row_ids = [];
			$(checked_rows).each(function() {
				row_ids.push($(this).attr('data-id'));
			});

			var data = new FormData();
			data.append('json', JSON.stringify(row_ids));
			$.ajax({
				type: "POST",
				url: './ajax/delete_inline',
				data: data,
				success: function () {
					location.reload();
				},
				error : function (xhr, ajaxOptions, thrownError) {
					//TODO: We should probably do something here..
				},
				contentType: false,
				processData: false
			});
		}
	});

	$('#file_import').change(function() {
		var files = this.files;
		if(files && files[0]) {
			var file = files[0];

			if(!file.name.match(/\.json$/)) {
				import_status('ERROR: Only .json is supported!');
			} else if(file.size > 2097152) {
				import_status('ERROR: File too large ( < 2MB)!');
			} else {
				var reader = new FileReader();
				reader.onload = function (e) {
					var json_string = e.target.result;
					if(!isJsonString(json_string)) {
						import_status('ERROR: File isn\'t valid JSON!')
					} else {
						var data = new FormData();
						data.append('json', json_string);

						$.ajax({
							type: "POST",
							url: './import_list',
							data: data,
							success: function () {
								import_status('Upload was a success! Reloading page.', true);
								setTimeout(function () {
									location.reload();
								}, 2500);
							},
							error : function (xhr, ajaxOptions, thrownError) {
								import_status('ERROR: Upload failed! - ' + xhr.status);
							},
							contentType: false,
							processData: false
						});
					}
				};
				reader.readAsText(file);
			}
		}
	});

	function import_status(text, success) {
		success = typeof success !== 'undefined' ? success : false;
		$('#import-status')
			.text(text)
			.show().delay(4000).fadeOut(1000)
			.attr('style', (success ? 'color: rgb(0, 230 ,0)' : 'color: rgb(230, 0 ,0)'));
	}

	/* http://stackoverflow.com/a/3710226/1168377 */
	function isJsonString(str) {
		try {
			JSON.parse(str);
		} catch (e) {
			return false;
		}
		return true;
	}
});
