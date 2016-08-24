$(function(){
	"use strict";
	if(page === 'dashboard') { return false; }

	//We sort by unread by using prepending 1 or 0 depending if it's unread or not to an invisible td
	$(".tablesorter").tablesorter(/*{
		sortList: [[0,0], [1,0]]
	}*/);

	//UX: This makes it easier to press the checkbox
	$('.tracker-table').find('> tbody > tr > td:nth-of-type(1)').click(function (e) {
		if(!$(e.target).is('input')) {
			var checkbox = $(this).find('> input[type=checkbox]');
			$(checkbox).prop("checked", !checkbox.prop("checked"));
		}
	});

	//Update latest chapter (via "I've read the latest chapter")
	$('.update-read').click(function() {
		var _this = this;
		var row             = $(this).closest('tr'),
		    chapter_id      = $(row).attr('data-id'),
		    current_chapter = $(row).find('.current'),
		    latest_chapter  = $(row).find('.latest');

		$.post(base_url + 'ajax/update_inline', {id: chapter_id, chapter: latest_chapter.attr('data-chapter')}, function () {
			$(_this).hide();
			$(current_chapter).attr('href', $(latest_chapter).attr('href')).text($(latest_chapter).text());
		}).fail(function(jqXHR, textStatus, errorThrown) {
			switch(jqXHR.status) {
				case 400:
					alert('ERROR: ' + errorThrown);
					break;
				case 429:
					alert('ERROR: Rate limit reached.');
					break;
				default:
					alert('ERROR: Something went wrong!\n'+errorThrown);
					break
			}
		});
	});

	//Delete selected series
	$('#delete_selected').click(function(e) {
		e.preventDefault();

		var checked_rows = $('.tracker-table:visible').find('tr:has(td input[type=checkbox]:checked)');
		if(checked_rows.length > 0) {
			var row_ids = $(checked_rows).map(function() {
				return parseInt($(this).attr('data-id'));
			}).toArray();

			$.post(base_url + 'ajax/delete_inline', {'id[]' : row_ids}, function () {
				location.reload();
			}).fail(function(jqXHR, textStatus, errorThrown) {
				switch(jqXHR.status) {
					case 400:
						alert('ERROR: ' + errorThrown);
						break;
					case 429:
						alert('ERROR: Rate limit reached.');
						break;
					default:
						alert('ERROR: Something went wrong!\n'+errorThrown);
						break
				}
			});
		}
	});

	//File import
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
							error : function(jqXHR, textStatus, errorThrown) {
								switch(jqXHR.status) {
									case 400:
										alert('ERROR: ' + errorThrown);
										break;
									case 429:
										alert('ERROR: Rate limit reached.');
										break;
									default:
										alert('ERROR: Something went wrong!\n'+errorThrown);
										break
								}
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
				$.post(base_url + 'ajax/tag_update', {id: id, tag_string: tag_array.join(',')}, function () {
					$(_this).closest('.tags').find('.tag-list').text(tag_array.join(',') || 'none');
					$(_this).closest('.tag-edit').toggleClass('hidden');
				}).fail(function(jqXHR, textStatus, errorThrown) {
					switch(jqXHR.status) {
						case 400:
							alert('ERROR: ' + errorThrown);
							break;
						case 429:
							alert('ERROR: Rate limit reached.');
							break;
						default:
							alert('ERROR: Something went wrong!\n'+errorThrown);
							break
					}
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
				var row_ids = $(checked_rows).map(function() {
					return parseInt($(this).attr('data-id'));
				}).toArray();

				$.post(base_url + 'ajax/set_category', {'id[]' : row_ids, category : selected.attr('value')}, function () {
					location.reload();
				}).fail(function(jqXHR, textStatus, errorThrown) {
					switch(jqXHR.status) {
						case 400:
							alert('ERROR: ' + errorThrown);
							break;
						case 429:
							alert('ERROR: Rate limit reached.');
							break;
						default:
							alert('ERROR: Something went wrong!\n'+errorThrown);
							break
					}
				});
			}
		}
	});


	//Initialize header update timer
	if(typeof use_live_countdown_timer !== 'undefined' && use_live_countdown_timer) {
		var timer_obj = $('#update-timer'),
		    timer_arr = timer_obj.text().split(':'),
		    time_left = parseInt(timer_arr[0] * 60 * 60, 10) + parseInt(timer_arr[1] * 60, 10) + parseInt(timer_arr[2], 10);
		var timer = setInterval(function () {
			var hours   = parseInt(time_left / 60 / 60, 10).toString(),
			    minutes = parseInt(time_left / 60 % 60, 10).toString(),
			    seconds = parseInt(time_left % 60, 10).toString();

			if (hours.length == 1) hours = '0' + hours;
			if (minutes.length == 1) minutes = '0' + minutes;
			if (seconds.length == 1) seconds = '0' + seconds;

			timer_obj.text(hours + ':' + minutes + ':' + seconds);

			if (--time_left < 0) {
				clearInterval(timer);
				//location.reload(); //?
			}
		}, 1000);
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
