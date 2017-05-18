$(function(){
	"use strict";
	if(page !== 'dashboard') { return false; }

	//We sort by unread by using prepending 1 or 0 depending if it's unread or not to an invisible td
	$(".tablesorter").tablesorter(/*{
		sortList: [[0,0], [1,0]]
	}*/);

	//UX: This makes it easier to press the checkbox
	$('.tracker-table').find('> tbody > tr > td:nth-of-type(1)').click(function (e) {
		if(!$(e.target).is('input')) {
			let checkbox = $(this).find('> input[type=checkbox]');
			$(checkbox).prop("checked", !checkbox.prop("checked"));
		}
	});

	//Update latest chapter (via "I've read the latest chapter")
	$('.update-read').click(function() {
		let _this = this;
		let row             = $(this).closest('tr'),
		    chapter_id      = $(row).attr('data-id'),
		    current_chapter = $(row).find('.current'),
		    latest_chapter  = $(row).find('.latest');

		$.post(base_url + 'ajax/update_inline', {id: chapter_id, chapter: latest_chapter.attr('data-chapter')}, function () {
			$(_this).parent().find('.update-read, .ignore-latest').hide();
			$(current_chapter).attr('href', $(latest_chapter).attr('href')).text($(latest_chapter).text());

			updateUnread();
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
					break;
			}
		});
	});

	//Ignore latest chapter
	$('.ignore-latest').click(function() {
		let _this = this;
		let row             = $(this).closest('tr'),
		    chapter_id      = $(row).attr('data-id'),
		    current_chapter = $(row).find('.current'),
		    latest_chapter  = $(row).find('.latest');

		if(confirm('Ignore latest chapter?')) {
			$.post(base_url + 'ajax/ignore_inline', {id: chapter_id, chapter: latest_chapter.attr('data-chapter')}, function () {
				$(_this).parent().find('.update-read, .ignore-latest').hide();
				$(current_chapter).parent().append(
					$('<span/>', {class: 'hidden-chapter', title: 'This latest chapter was marked as ignored.', text: $(latest_chapter).text()})
				);

				updateUnread();
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
						break;
				}
			});
		}
	});

	//Delete selected series
	$('#delete_selected').click(function(e) {
		e.preventDefault();

		let checked_rows = $('.tracker-table:visible').find('tr:has(td input[type=checkbox]:checked)');
		if(checked_rows.length > 0) {
			let row_ids = $(checked_rows).map(function() {
				return parseInt($(this).attr('data-id'));
			}).toArray();

			if(confirm('Are you sure you want to delete the selected rows?')) {
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
							break;
					}
				});
			}
		} else {
			alert('No selected series found.');
		}
	});

	//File import
	$('#file_import').change(function() {
		let files = this.files;
		if(files && files[0]) {
			let file = files[0];

			if(!file.name.match(/\.json$/)) {
				import_status('ERROR: Only .json is supported!');
			} else if(file.size > 2097152) {
				import_status('ERROR: File too large ( < 2MB)!');
			} else {
				let reader = new FileReader();
				reader.onload = function (e) {
					let json_string = e.target.result;
					if(!isJsonString(json_string)) {
						import_status('ERROR: File isn\'t valid JSON!');
					} else {
						let data = new FormData();
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
										break;
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

	/****** SET MAL ID ******/
	//FIXME: This entire thing is a mess.
	$('.set-mal-id').click(function(e) {
		e.preventDefault();

		let current_mal_id = $(this).data('mal-id');
		if($(this).data('mal-type') === 'title' && $(this).data('mal-id') && !confirm('A MAL ID already exists for this series on our backend.\n Are you sure you want to override it?')) return;

		let new_mal_id     = prompt("MAL ID:", current_mal_id);

		if(/^([0-9]+|none)?$/.test(new_mal_id)) {
			let tr        = $(this).closest('tr'),
			    td        = tr.find('td:eq(1)'),
			    id        = tr.attr('data-id'),
			    icon_link = $(td).find('.sprite-myanimelist-net').parent(),
			    id_text   = $(this).find('+ span'),
			    _this     = this;

			if(new_mal_id !== '' && new_mal_id !== 'none' && new_mal_id !== '0') {
				set_mal_id(id, new_mal_id, function (){
					if(icon_link.length) {
						//icon exists, just change link
						$(icon_link).attr('href', 'https://myanimelist.net/manga/'+new_mal_id);
					} else {
						$($('<a/>', {href: 'https://myanimelist.net/manga/'+new_mal_id}).append(
							$('<i/>', {class: 'sprite-site sprite-myanimelist-net', title: new_mal_id})
						)).prepend(' ').insertAfter(td.find('.sprite-site'));
					}

					set_id_text($(_this), id_text, new_mal_id);
				});
			} else {
				if(new_mal_id === 'none' || new_mal_id === '0') {
					set_mal_id(id, '0', function (){
						if(icon_link.length) {
							$(icon_link).remove();
						}
						$($('<a/>', {}).append(
							$('<i/>', {class: 'sprite-site sprite-myanimelist-net-none', title: new_mal_id})
						)).prepend(' ').insertAfter(td.find('.sprite-site'));

						set_id_text($(_this), id_text, 'none');
					});
				} else {
					set_mal_id(id, null, function () {
						icon_link.remove();
						id_text.remove();
					});
				}
			}

			$(this).data('mal-id', new_mal_id);
			//TODO: AJAX
		} else if (new_mal_id === null) {
			//input cancelled, do nothing
		} else {
			alert('MAL ID can only contain numbers.');
		}

		function set_id_text(_this, id_text, text) {
			text = (text !== '0' ? text : 'none');
			if(id_text.length) {
				id_text.find('small').text(text);
			} else {
				$('<span/>').append(
					$('<small/>', {text: text})
				).prepend(' (').append(')').insertAfter($(_this));
			}
		}

		function set_mal_id(id, mal_id, successCallback) {
			successCallback = successCallback || function(){};

			$.post(base_url + 'ajax/set_mal_id', {'id': id, mal_id: mal_id}, function () {
				successCallback();
			}).fail(function(jqXHR, textStatus, errorThrown) {
				switch(jqXHR.status) {
					case 400:
						alert('ERROR: ' + errorThrown);
						break;
					case 401:
						alert('Session has expired, please re-log to continue.');
						break;
					case 429:
						alert('ERROR: Rate limit reached.');
						break;
					default:
						alert('ERROR: Something went wrong!\n'+errorThrown);
						break;
				}
			});
		}
	});

	/****** TAG EDITING *******/
	//This isn't possible in pure CSS
	$('.more-info').click(function(e) {
		e.preventDefault();

		$(this).find('+ .tags').toggle();
		if($(this).text() === 'More info') {
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
		let _this = this;
		//CHECK: We would use jQuery.validate here but I don't think it works without an actual form.
		let input    = $(this).closest('.tag-edit').find('input'),
		    tag_list = input.val().trim().replace(/,,/g, ','),
		    id       = $(this).closest('tr').attr('data-id');

		//Validation
		if(/^[a-z0-9\-_,:]{0,255}$/.test(tag_list)) {
			let tag_array    = uniq(tag_list.split(',')).filter(function(n){ return n !== ''; }),
			    tag_list_new = tag_array.join(',');
			if($.inArray('none', tag_array) === -1) {
				if((tag_list.match(/\bmal:(?:[0-9]+|none)\b/g) || []).length <= 1) {
					$.post(base_url + 'ajax/tag_update', {id: id, tag_string: tag_list_new}, function () {
						$(input).val(tag_list_new);
						$(_this).closest('.tags').find('.tag-list').text(tag_list_new || 'none');
						$(_this).closest('.tag-edit').toggleClass('hidden');
					}).fail(function(jqXHR, textStatus, errorThrown) {
						switch(jqXHR.status) {
							case 400:
								alert('ERROR: ' + errorThrown);
								break;
							case 401:
								alert('Session has expired, please re-log to continue.');
								break;
							case 429:
								alert('ERROR: Rate limit reached.');
								break;
							default:
								alert('ERROR: Something went wrong!\n'+errorThrown);
								break;
						}
					});
				} else {
					alert('You can only use one MAL ID tag per series');
				}
			} else {
				alert('"none" is a restricted tag.');
			}
		} else {
			//Tag list is invalid.
			alert('Tags can only contain: lowercase a-z, 0-9, -, :, & _. They can also only have one MAL metatag.');
		}
	});

	/***** TAG SEARCH *****/
	//TODO: Improve this.
	$('#search').on('input', function(){
		let tag_search_string = $(this).val(),
		    tag_lists = $('.tag-list');

		let filtered_tag_lists = tag_lists.filter(function() {
			let safe_search_string = tag_search_string.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
			let regex = new RegExp(/*"\\b"+*/safe_search_string/*+"\\b"*/, 'i');

			return ! (regex.test($(this).text()) || regex.test($(this).closest('tr').find('> td:nth-of-type(2) > a').text()));
		});

		let unfiltered_tag_lists = $(tag_lists).not(filtered_tag_lists);

		filtered_tag_lists.closest('tr').addClass('hidden');
		unfiltered_tag_lists.closest('tr').removeClass('hidden');
	});

	/***** CATEGORIES *****/
	$('#category-nav').find('> .nav > li > a').click(function(e) {
		e.preventDefault();

		//Change category active state
		$(this).closest('ul').find('> .active').removeClass('active');
		$(this).parent().addClass('active');

		$('.tracker-table:visible').hide();
		$('.tracker-table[data-list="'+$(this).attr('data-list')+'"]').show();

		//Scroll to top of page
		$("html, body").animate({ scrollTop: 0 }, "slow");
	});
	$('#move-input').change(function() {
		let selected = $(this).find(':selected');
		if(selected.is('[value]')) {
			let checked_rows = $('.tracker-table:visible').find('tr:has(td input[type=checkbox]:checked)');
			if(checked_rows.length > 0) {
				let row_ids = $(checked_rows).map(function() {
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
							break;
					}
				});
			}
		}
	});

	//Initialize header update timer
	if(typeof use_live_countdown_timer !== 'undefined' && use_live_countdown_timer) {
		let timer_obj = $('#update-timer'),
		    timer_arr = timer_obj.text().split(':'),
		    time_left = parseInt(timer_arr[0] * 60 * 60, 10) + parseInt(timer_arr[1] * 60, 10) + parseInt(timer_arr[2], 10);
		let timer = setInterval(function () {
			let hours   = parseInt(time_left / 60 / 60, 10).toString(),
			    minutes = parseInt(time_left / 60 % 60, 10).toString(),
			    seconds = parseInt(time_left % 60, 10).toString();

			if(hours.length === 1)   hours   = '0' + hours;
			if(minutes.length === 1) minutes = '0' + minutes;
			if(seconds.length === 1) seconds = '0' + seconds;

			timer_obj.text(hours + ':' + minutes + ':' + seconds);

			if (--time_left < 0) {
				clearInterval(timer);

				//Wait one minute, then change favicon to alert user of update
				setTimeout(function(){
					//TODO: This "should" just be favicon.updated.ico, and we should handle any ENV stuff on the backend
					$("link[rel*='icon']").attr("href", base_url+"favicon.production.updated.ico");

					//location.reload(); //TODO: We should have an option for this?
				}, 60000);
			}
		}, 1000);
	}

	//Sticky List Header
	let $window = $(window),
	    offset  = $('#category-nav').offset().top - $('#category-nav').find('> ul').height() - 21,
	    nav     = $('#list-nav'),
	    list_table = $('table[data-list]');
	$window.scroll(function() {
		//FIXME: Using .scroll for this seems really slow. Is there no pure CSS way of doing this?
		//FIXME: The width of the nav doesn't auto-adjust to change window width (since we're calcing it in JS)..
		handleScroll();
	});
	handleScroll(); //Make sure we also trigger on page load.

	$('#update-notice').on('closed.bs.alert', function () {
		$.post(base_url + 'ajax/hide_notice');
	});

	function updateUnread() {
		let table       = $('table[data-list=reading]'),
		    totalUnread = table.find('tr .update-read:not([style])').length;

		//Update header text
		table.find('thead > tr > th:eq(1) > div').text('Series'+(totalUnread > 0 ? ' ('+totalUnread+' unread)' : ''));

		//Update data attr
		table.data('unread', totalUnread);

		//Update favicon
		setFavicon(totalUnread);
	}
	function setFavicon(text) {
		text = parseInt(text) > 50 ? '50+' : text;

		let favicon = $('link[rel="shortcut icon"]');
		if(parseInt(text) !== 0) {
			let canvas  = $('<canvas/>', {id: 'faviconCanvas', style: '/*display: none*/'})[0];
			//Bug?: Unable to set this via jQuery for some reason..
			canvas.width  = 32;
			canvas.height = 32;

			let context = canvas.getContext("2d");

			let imageObj = new Image();
			imageObj.onload = function(){
				context.drawImage(imageObj, 0, 0, 32, 32);

				context.font      = "Bold 17px Helvetica";
				context.textAlign = 'right';

				context.lineWidth   = 3;
				context.strokeStyle = 'white';
				context.strokeText(text, 32, 30);

				context.fillStyle = 'black';
				context.fillText(text, 32, 30);

				favicon.attr('href', canvas.toDataURL());
			};
			imageObj.src = favicon.attr('href');
		} else {
			favicon.attr('href', base_url+'favicon.ico');
		}
	}
	setFavicon($('table[data-list=reading]').data('unread'));

	function handleScroll() {
		if($window.scrollTop() >= offset) {
			list_table.css('margin-top', '97px');
			nav.addClass('fixed-header');
			nav.css('width', $('#list-nav').parent().width() + 'px');
		} else {
			list_table.css('margin-top', '5px');
			nav.removeClass('fixed-header');
			nav.css('width', 'initial');
		}
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

	/* http://stackoverflow.com/a/9229821/1168377 */
	function uniq(a) { return Array.from(new Set(a)); }
});
