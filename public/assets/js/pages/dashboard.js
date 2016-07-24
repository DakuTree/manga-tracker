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
	$('.tracker-table').find('> tbody > tr > td:nth-of-type(1)').click(function (e) {
		if(!$(e.target).is('input')) {
			var checkbox = $(this).find('> input[type=checkbox]');
			$(checkbox).prop("checked", !checkbox.prop("checked"));
		}
	});

	$('#delete_selected').click(function(e) {
		e.preventDefault();

		var checked_rows = $('.tracker-table:visible').find('tr:has(td input[type=checkbox]:checked)');
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

	/****** TAG EDITTING *******/
	//This isn't possible in pure CSS
	$('.more-info').click(function(e) {
		e.preventDefault();

		$(this).find('+ .tags').toggle();
		if($(this).text() == 'More info') {
			$(this).text('Hide info');
		} else {
			$(this).text('More info');
		}
	});

	$('.edit-tags').click(function(e) {
		e.preventDefault();
		$(this).parent().find('.tag-edit').toggleClass('hidden');
	});

	$('.tag-edit input').on('keypress', function () {
		if(event.which === 13) {
			$(this).closest('.tag-edit').find('[type=button]').click();
		}
	});
	$('.tag-edit [type=button]').click(function() {
		var _this = this;
		//CHECK: We would use jQuery.validate here but I don't think it works without an actual form.
		var input    = $(this).closest('.tag-edit').find('input'),
		    tag_list = input.val().trim().replace(/,,/g, ','),
		    id       = $(this).closest('tr').attr('data-id');

		//Validation
		if(/^[a-z0-9,\-_]{0,255}$/.test(tag_list)) {
			var tag_array = tag_list.split(',');
			if($.inArray('none', tag_array) === -1) {
				var data = new FormData();
				data.append('id', id);
				data.append('tag_string', tag_array.join(','));

				$.ajax({
					type: "POST",
					url: './ajax/tag/update',
					data: data,
					success: function () {
						$(_this).closest('.tags').find('.tag-list').text(tag_array.join(',') || 'none');
						$(_this).closest('.tag-edit').toggleClass('hidden');
					},
					error : function (xhr, ajaxOptions, thrownError) {

					},
					contentType: false,
					processData: false
				});
			} else {
				alert('"none" is a restricted tag.');
			}
		} else {
			//Tag list is invalid.
			alert('Tags can only contain: lowercase a-z, 0-9, - & _.');
		}
	});

	/***** CATEGORIES *****/
	$('#category-nav > .nav > li > a').click(function(e) {
		e.preventDefault();

		//Change category active state
		$(this).closest('ul').find('> .active').removeClass('active');
		$(this).parent().addClass('active');

		$('.tracker-table:visible').hide();
		$('.tracker-table[data-list="'+$(this).attr('data-list')+'"]').show();
	});
	$('#move-input').change(function() {
		var selected = $(this).find(':selected');
		if(selected.is('[value]')) {
			var checked_rows = $('.tracker-table:visible').find('tr:has(td input[type=checkbox]:checked)');
			if(checked_rows.length > 0) {
				var json = {
					id_list: [],
					category: selected.attr('value')
				};
				$(checked_rows).each(function() {
					json['id_list'].push($(this).attr('data-id'));
				});

				var data = new FormData();
				data.append('json', JSON.stringify(json));
				$.ajax({
					type: "POST",
					url: './ajax/set_category',
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
		}
	});

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
