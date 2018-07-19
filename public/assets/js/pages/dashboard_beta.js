/* globals page, base_url, use_live_countdown_timer, list_sort_type, list_sort_order, site_aliases */
$(function(){
	'use strict';
	if(page !== 'dashboard_beta') { return false; }

	function _handleAjaxError(jqXHR, textStatus, errorThrown) {
		switch(jqXHR.status) {
			case 400:
				alert('ERROR: ' + errorThrown);
				break;
			case 401:
				alert('Session has expired, please re-log to continue.');
				location.refresh();
				break;
			case 429:
				alert('ERROR: Rate limit reached.');
				break;
			default:
				alert('ERROR: Something went wrong!\n'+errorThrown);
				break;
		}
	}

	let decodeEntities = (function() {
		// this prevents any overhead from creating the object each time
		let element = document.createElement('div');

		function decodeHTMLEntities (str) {
			if(str && typeof str === 'string') {
				// strip script/html tags
				str = str.replace(/<script[^>]*>([\S\s]*?)<\/script>/gmi, '');
				str = str.replace(/<\/?\w(?:[^"'>]|"[^"]*"|'[^']*')*>/gmi, '');
				element.innerHTML = str;
				str = element.textContent;
				element.textContent = '';
			}

			return str;
		}

		return decodeHTMLEntities;
	})();

	/**
	 * @class TrackrApp
	 */
	class TrackrApp {
		constructor() {
			let _class = this;

			this.isDashboard = !(/^\/list\//.test(location.pathname));

			this.refreshData();
			this.$tables = $('.tracker-table');

			this.enabledCategories = Object.keys(this.data.series).filter((n) => ['custom1', 'custom2', 'custom3'].includes(n));

			this.initialSortOrder = this.getListSort(list_sort_type, list_sort_order);
			this.tablesorterDefaults = {
				initialized: function(table) {
					//fix for being unable to sort title column by asc on a single click if using "Unread (Alphabetical)" sort
					//SEE: https://github.com/Mottie/tablesorter/issues/1445#issuecomment-321537911
					let sortVars = table.config.sortVars;
					sortVars.forEach(function(el) {
						// reset the internal counter
						el.count = -1;
					});

					$(table.config.headerList[4]).find('.fa-spin').remove();
				},

				//FIXME: This is kinda unneeded, and it does add a longer delay to the tablesorter load, but we need it for setting the header sort direction icons..
				sortList: _class.initialSortOrder,

				headers : {
					1 : { sortInitialOrder : 'asc'  },
					2 : { sortInitialOrder : 'desc', sorter: 'updated-at' },
					3 : { sortInitialOrder : 'desc', sorter: 'latest' }
				},

				textExtraction: {
					1: function (node) {
						// return only the text from the text node (ignores DIV contents)
						return $(node).find('.title').text();
					}
				},

				widgets: ['zebra', 'filter'],
				widgetOptions : {
					filter_external : '#search',
					filter_columnFilters: false,
					filter_saveFilters : false,
					filter_reset: '.reset',
					filter_searchFiltered: false //FIXME: This is a temp fix for #201. More info here: https://mottie.github.io/tablesorter/docs/#widget-filter-searchfiltered
				}
			};
		}

		refreshData() {
			//FIXME: This shouldn't be async: false?
			$.ajax(
				{
					url: base_url + 'api/internal/get_list/all',
					dataType: 'json',
					async: false,
					success: (json) => (this.data = json)
				}
			);
		}

		start() {
			this.setupNotices();
			this.handleInactive(this.data.extra_data.inactive_titles);

			this.setupNav();

			this.generateLists(this.data.series);
			this.startTablesorter();
			this.setupStickyListHeader();

			this.updateFavicon();
		}

		updateFavicon() {
			let unreadCount = $('table[data-list=reading]').attr('data-unread').toString();
			unreadCount = parseInt(unreadCount) > 99 ? '99+' : unreadCount;

			let $favicon = $('link[rel="shortcut icon"]');
			if(parseInt(unreadCount) !== 0) {
				let canvas  = $('<canvas/>', {id: 'faviconCanvas', style: '/*display: none*/'})[0];
				//Bug?: Unable to set this via jQuery for some reason..
				canvas.width  = 32;
				canvas.height = 32;

				let context = canvas.getContext('2d');

				let imageObj = new Image();
				imageObj.onload = function(){
					context.drawImage(imageObj, 0, 0, 32, 32);

					context.font      = 'Bold 17px Helvetica';
					context.textAlign = 'right';

					context.lineWidth   = 3;
					context.strokeStyle = 'white';
					context.strokeText(unreadCount, 32, 30);

					context.fillStyle = 'black';
					context.fillText(unreadCount, 32, 30);

					$favicon.attr('href', canvas.toDataURL());
				};
				imageObj.src = `${base_url}favicon.ico`;
			} else {
				$favicon.attr('href', `${base_url}favicon.ico`);
			}
		}
		updateUnread(table, row) {
			let unread_e     = row.find('> td:eq(0)'),
			    chapter_e    = row.find('> td:eq(2)'),
			    update_icons = row.find('.update-read, .ignore-latest');

			//Hide update icons
			update_icons.hide();

			//Update updated-at time for sorting purposes.
			chapter_e.attr('data-updated-at', (new Date()).toISOString().replace(/^([0-9]+-[0-9]+-[0-9]+)T([0-9]+:[0-9]+:[0-9]+)\.[0-9]+Z$/, '$1 $2'));
			table.trigger('updateCell', [chapter_e[0], false, null]);

			//Update unread status for sorting purposes.
			unread_e.find(' > span').text('1');
			table.trigger('updateCell', [unread_e[0], false, null]);

			//And show reset sort button to signify the current sort is no longer valid
			let th = $(table).find('thead > tr > th:eq(4) > .tablesorter-header-inner');
			if(!th.find('> .table-reset').length && ($(table).find('thead > tr > th:eq(0)').attr('aria-sort') !== 'none')) {
				th.empty().append($('<i/>', {class: 'fa fa-eraser table-reset', 'aria-hidden': 'true'}));
			}
			let totalUnread  = table.find('tr .update-read:not([style])').length;

			//Update header text
			let unreadText = (totalUnread > 0 ? ` (${totalUnread} unread)` : '');
			table.find('thead > tr > th:eq(1) > div').text('Series'+unreadText);

			//Update data attr
			table.attr('data-unread', totalUnread);

			//Update favicon
			if(table.attr('data-list') === 'reading') {
				this.updateFavicon();
			}
		}

		setupNotices() {
			//Click to hide notice
			$('#update-notice').on('closed.bs.alert', function() {
				$.post(base_url + 'ajax/hide_notice');
			});
		}

		handleInactive(inactive_titles) {
			//TODO: The <ul> list should be hidden by default, and only shown if a button is clicked?

			let $inactiveContainer     = $('#inactive-container'),
			    $inactiveListContainer = $inactiveContainer.find('> #inactive-list-container'),
			    $inactiveList          = $inactiveListContainer.find('> ul');

			if(Object.keys(inactive_titles).length) {
				for (let url in inactive_titles) {
					if(inactive_titles.hasOwnProperty(url)) {
						let domain      = url.split('/')[2],
						    domainClass = domain;
						if(site_aliases[domainClass]) {
							domainClass = site_aliases[domainClass];
						}
						domainClass = domainClass.replace(/\./g, '-');

						//FIXME: Don't append if already exists in list!
						$('<li/>').append(
							$('<i/>', {class: `sprite-site sprite-${domainClass}`, title: domain})).append(
							$('<a/>', {text: ' '+inactive_titles[url], href: url})
						).appendTo($inactiveList);
					}
				}

				$inactiveContainer.removeAttr('hidden');
			} else {
				$inactiveContainer.attr('hidden');

				$inactiveList.find('> li').empty();
			}

			$('#inactive-display').on('click', function() {
				$(this).hide();
				$inactiveListContainer.removeAttr('hidden');
			});
		}

		setupNav() {
			this.setupCategoryTabs();

			this.setupUpdateTimer();

			this.setupModifySelectedEvent();
			this.setupMoveToEvent();

			//NOTE: Search event is handled via Tablesorter.

			this.setupNavToggle();
			this.setupSorterEvent();
		}
		setupCategoryTabs() {
			let $categoryNav = $('#list-nav-category').find('> .navbar-nav'),
			    $moveSelect  = $('#move-input');

			for (let i = 0, len = this.enabledCategories.length; i < len; i++) {
				let categoryStub = this.enabledCategories[i],
				    categoryName = this.data.series[categoryStub].name;

				$categoryNav.append(
					$('<li/>').append(
						$('<a/>', {href: '#', 'data-list': categoryStub, text: categoryName})
					)
				);

				$moveSelect.append(
					$('<option/>', {value: categoryStub, text: categoryName})
				);
			}

			//Change list when clicking category tabs
			$categoryNav.find('> li > a').on('click', function(e) {
				e.preventDefault();

				//Change category active state
				$(this).closest('ul').find('> .active').removeClass('active');
				$(this).parent().addClass('active');

				$('.tracker-table:visible').hide();

				let datalist  = $(this).attr('data-list'),
				    $newTable = $(`.tracker-table[data-list="${datalist}"]`);

				$newTable.show();

				//Trigger update to generate even/odd rows. Tablesorter doesn't appear to auto-generate on hidden tables for some reason..
				if(!$newTable.has('.odd, .even').length) {
					$newTable.trigger('update', [true]);
				}

				//Scroll to top of page
				$('html, body').animate({ scrollTop: 0 }, 'slow');
			});
		}
		setupUpdateTimer() {
			if(typeof use_live_countdown_timer !== 'undefined' && use_live_countdown_timer && this.isDashboard) {
				let $timer = $('#update-timer'),
				    timer_arr = $timer.text().split(':'),
				    time_left = parseInt((timer_arr[0] * 60 * 60).toString(), 10) + parseInt((timer_arr[1] * 60).toString(), 10) + parseInt(timer_arr[2], 10);
				let timer = setInterval(() => {
					let hours   = parseInt((time_left / 60 / 60).toString(), 10).toString(),
					    minutes = parseInt((time_left / 60 % 60).toString(), 10).toString(),
					    seconds = parseInt((time_left % 60).toString(), 10).toString();

					if(hours.length === 1)   { hours   = '0' + hours;   }
					else if(hours.length === 0)   { hours   = '00';   }
					if(minutes.length === 1) { minutes = '0' + minutes; }
					if(seconds.length === 1) { seconds = '0' + seconds; }

					$timer.text(hours + ':' + minutes + ':' + seconds);

					if (--time_left < 0) {
						clearInterval(timer);

						//Wait one minute, then change favicon to alert user of update
						setTimeout(function(){
							//TODO: This "should" just be favicon.updated.ico, and we should handle any ENV stuff on the backend
							$('link[rel*="icon"]').attr('href', `${base_url}favicon.updated.ico`);

							//location.reload(); //TODO: We should have an option for this?
						}, 60000);
					}
				}, 1000);
			}
		}
		setupModifySelectedEvent() {
			let _class = this;

			$('#mass-action').find('> select').on('change', function() {
				let redirect = false;

				let $checked_rows = $('.tracker-table:visible').find('tr:has(td input[type=checkbox]:checked)'),
				    total_rows   = $checked_rows.length;
				if(total_rows > 0) {
					let row_ids = $($checked_rows).map(function() {
						return parseInt($(this).attr('data-id').toString());
					}).toArray();

					let postData = {
						'id[]' : row_ids
					};
					switch($(this).val()) {
						case 'delete':
							if(confirm(`Are you sure you want to delete the ${total_rows} selected row(s)?`)) {
								window.onbeforeunload = null;
								$.post(base_url + 'ajax/delete_inline', postData, () => {
									redirect = true;
									location.reload();
								}).fail((jqXHR, textStatus, errorThrown) => {
									_handleAjaxError(jqXHR, textStatus, errorThrown);
								});
							}

							break;

						case 'tag':
							if(confirm(`Are you sure you want to edit the tags of ${total_rows} selected row(s)?`)) {
								let tags = prompt('Tags: ');
								_class.validateTagList(tags, (tag_list_new) => {
									postData.tag_string = tag_list_new;

									window.onbeforeunload = null;
									$.post(base_url + 'ajax/mass_tag_update', postData, () => {
										redirect = true;
										location.reload(); //unlike a normal tag update, it's probably better to just force a reload here.
									}).fail((jqXHR, textStatus, errorThrown) => {
										_handleAjaxError(jqXHR, textStatus, errorThrown);
									});
								});
							}
							break;

						default:
							//do nothing
							break;
					}
				} else {
					alert('No selected series found.');
				}

				if($(this).val() !== 'n/a' && !redirect) { console.log('resetting value'); $(this).val('n/a'); } //Reset change if user hasn't followed through with mass action
			});
		}
		setupMoveToEvent() {
			$('#move-input').on('change', function() {
				let selected      = $(this).find(':selected'),
				    selected_name = selected.text();
				if(selected.is('[value]')) {
					let $checked_rows = $('.tracker-table:visible').find('tr:has(td input[type=checkbox]:checked)'),
					    total_rows   = $checked_rows.length;
					if($checked_rows.length > 0 && confirm(`Are you sure you want to move the ${total_rows} selected row(s) to the ${selected_name} category?`)) {
						let row_ids = $($checked_rows).map(function() {
							return parseInt($(this).attr('data-id').toString());
						}).toArray();

						window.onbeforeunload = null;
						$.post(base_url + 'ajax/set_category', {'id[]' : row_ids, category : selected.attr('value')}, () => {
							location.reload();
						}).fail((jqXHR, textStatus, errorThrown) => {
							_handleAjaxError(jqXHR, textStatus, errorThrown);
						});
					} else {
						$('#move-input').val('---');
					}
				}
			});
		}
		setupNavToggle() {
			$('#toggle-nav-options').on('click', function(e) {
				e.preventDefault();

				let $icon    = $(this).find('> i'),
				    $options = $('#nav-options');
				$icon.toggleClass('down');

				if($icon.hasClass('down')) {
					$options.hide().slideDown(500);
				} else {
					$options.show().slideUp(500);
				}
			});
		}
		setupSorterEvent() {
			let _class = this;

			//Setup sorter event
			$('.list_sort').on('change', function() {
				let $tables    = $('.tracker-table'),
				    type       = $('select[name=list_sort_type]').val(),
				    $order_ele = $('select[name=list_sort_order]'),
				    order      = $order_ele.val();

				if(type === 'n/a') { return; } //do nothing, if n/a

				if($(this).attr('name') === 'list_sort_type') {
					//Type has changed, so set order to default.
					switch(type) {
						case 'unread_latest':
							order = 'desc';
							break;

						case 'my_status':
							order = 'desc';
							break;

						case 'latest':
							order = 'desc';
							break;

						default:
							order = 'asc';
							break;
					}
					$order_ele.val(order); //thankfully .val doesn't re-trigger .change
				}

				$tables.trigger('sorton', [ _class.getListSort(type, order) ]);
			});

			// Make sure order inputs are updated if sort is changed elsewhere
			$('.tracker-table').on('sortEnd', function(/**e, table**/) {
				let $type_ele  = $('select[name=list_sort_type]'),
				    $order_ele = $('select[name=list_sort_order]'),
				    sortList = this.config.sortList,
				    sort = sortList.reduce(function(acc, cur/*, i*/) {
					    acc[cur[0]] = cur[1];
					    return acc;
				    }, {});

				let sortType  = 'n/a',
				    sortOrder = 'asc';
				switch(Object.keys(sort).join()) {
					case '0,1':
						if(sort[0] === 0) {
							sortType  = 'unread';
							sortOrder = (sort[1] === 0 ? 'asc' : 'desc');
						}
						break;

					case '0,3':
						if(sort[0] === 0) {
							sortType  = 'unread-latest';
							sortOrder = (sort[1] === 0 ? 'asc' : 'desc');
						}
						break;

					case '1':
						sortType = 'alphabetical';
						sortOrder = (sort[1] === 0 ? 'asc' : 'desc');
						break;

					case '2':
						sortType = 'my_status';
						sortOrder = (sort[2] === 0 ? 'asc' : 'desc');
						break;

					case '3':
						sortType = 'latest';
						sortOrder = (sort[3] === 0 ? 'asc' : 'desc');
						break;

					default:
						//we already default to n/a
						break;
				}

				$type_ele.val(sortType);
				$order_ele.val(sortOrder);
			});
		}

		generateLists(series) {
			// Generate Tables
			// FIXME: We should generate lists for activated categories, even if empty.
			for (let [seriesStub, seriesData] of Object.entries(series)) {
				let mangaList = seriesData.manga,
				    unreadCount = seriesData.unread_count;

				//region let table = ...;
				let $table = $('<table/>', {'class': 'tablesorter-bootstrap tracker-table', 'style': (seriesStub === 'reading') ? '' : 'display : none', 'data-list': seriesStub, 'data-unread': unreadCount}).append(
					$('<thead/>').append(
						$('<tr/>').append(
							$('<th/>', {class: 'header read'})).append(
							$('<th/>', {class: 'header read'}).append(
								$('<div/>', {class: 'tablesorter-header-inner', text: 'Series '+(unreadCount > 0 ? `(${unreadCount} unread)` : '')})
							)).append(
							$('<th/>', {class: 'header read'}).append(
								$('<div/>', {class: 'tablesorter-header-inner', text: 'My Status'})
							)).append(
							$('<th/>', {class: 'header read'}).append(
								$('<div/>', {class: 'tablesorter-header-inner', text: 'Latest Release'})
							)).append(
							$('<th/>', {'data-sorter': 'false'}).append(
								$('<i/>', {class: 'fa fa-spinner fa-spin'})
							)
						)
					)
				);
				//endregion

				//FIXME: Closure compiler toggled off.

				let $tbody = $('<tbody/>');

				mangaList.forEach(manga => { // jshint ignore:line
					let tr = generateRow(manga);
					$tbody.append(tr);
				});
				$table.append($tbody);

				$table.appendTo('#list-container');
			}
			this.$tables = $('.tracker-table'); //Reset cache.
			this.setupListEvents();

			/**
			 * @param {Object}      manga
			 * @param {String}      manga.id
			 * @param {Object}      manga.generated_current_data
			 * @param {Object}      manga.generated_current_data.url
			 * @param {Object}      manga.generated_current_data.number
			 * @param {Object}      manga.generated_latest_data
			 * @param {Object}      manga.generated_latest_data.url
			 * @param {Object}      manga.generated_latest_data.number
			 * @param {Object|null} manga.generated_ignore_data
			 * @param {Object|null} manga.generated_ignore_data.url
			 * @param {Object|null} manga.generated_ignore_data.number
			 * @param {String}      manga.full_title_url
			 * @param {Number}      manga.new_chapter_exists
			 * @param {String}      manga.tag_list
			 * @param {Boolean}     manga.has_tags
			 * @param {String}      manga.mal_id
			 * @param {String}      manga.mal_type
			 * @param {String}      manga.last_updated
			 * @param {Object}      manga.title_data
			 * @param {String}      manga.title_data.id
			 * @param {String}      manga.title_data.title
			 * @param {String}      manga.title_data.title_url
			 * @param {String}      manga.title_data.latest_chapter
			 * @param {String}      manga.title_data.current_chapter
			 * @param {String|null} manga.title_data.ignore_chapter
			 * @param {String}      manga.title_data.last_updated
			 * @param {String}      manga.title_data.time_class
			 * @param {Number}      manga.title_data.status
			 * @param {Number}      manga.title_data.failed_checks
			 * @param {Boolean}     manga.title_data.active
			 * @param {Object}      manga.site_data
			 * @param {String}      manga.site_data.id
			 * @param {String}      manga.site_data.site
			 * @param {String}      manga.site_data.status
			 * @param {String}      manga.mal_icon
			 */
			function generateRow(manga) {
				let $mal_node = null;
				if(manga.mal_id && manga.mal_type === 'chapter') {
					$mal_node = $('<span/>')
						.append(document.createTextNode('('))
						.append($('<small/>', {text: (manga.mal_id !== '0' ? manga.mal_id : 'none')}))
						.append(document.createTextNode(')'));
				}

				//region let tr = ...;
				let $tr = $('<tr/>', {'data-id': manga.id}).append(
					$('<td/>')
						.append($('<span/>', {hidden: true, text: manga.new_chapter_exists}))
						.append($('<input/>', {type: 'checkbox', name: 'check'}))
				).append(
					$('<td/>')
						.append(
							$('<div/>', {class: 'row-icons'})
								.append($('<i/>', {class: `sprite-time ${manga.title_data.time_class}`, title: manga.title_data.last_updated}))
								.append($('<i/>', {class: `sprite-site sprite-${manga.site_data.site.replace(/\./g, '-')}`, title: manga.site_data.site}))
								.append(manga.mal_icon)
						)
						.append($('<a/>', {href: manga.full_title_url, rel: 'nofollow', class: 'title', 'data-title': decodeEntities(manga.title_data.title_url), target: '_blank', text: decodeEntities(manga.title_data.title)}))

						.append($('<small/>', {class: 'toggle-info pull-right text-muted', text: 'More info'}))
						.append($('<div/>', {class: 'more-info'}).append(
							$('<small/>')
								.append($('<a/>', {href: `/history/${manga.title_data.id}`, text: 'History'}))
								.append(document.createTextNode(' | '))
								.append($('<a/>', {href: '#', class: 'set-mal-id', 'data-mal-id': manga.mal_id, 'data-mal-type': manga.mal_type, text: 'Set MAL ID'}))
								.append($mal_node)

								.append(document.createTextNode(' | Tags ('))
								.append($('<a/>', {href: '#', class: 'edit-tags small', text: 'Edit'}))
								.append(document.createTextNode('): '))
								.append(
									$('<span/>', {class: 'text-lowercase tag-list', })
										.append(manga.has_tags ? (manga.tag_list.split(',').map((e) => { return $('<i/>', {class: 'tag', text: e}).get(0); })) : document.createTextNode('none')))

								.append(
									$('<div/>', {class: 'input-group tag-edit', hidden: true})
										.append($('<input/>', {type: 'text', class: 'form-control', placeholder: 'tag1,tag2,tag3', maxlength: 255, pattern: '[a-z0-9-_,]{0,255}', value: manga.tag_list}))
										.append(
											$('<span/>', {class: 'input-group-btn'})
												.append($('<button/>', {class: 'btn btn-default', type: 'button', text: 'Save'}))))))
				).append(
					$('<td/>', {'data-updated-at': manga.last_updated})
						.append($('<a/>', {class: 'chp-release current', href: manga.generated_current_data.url, rel: 'nofollow', target: '_blank', text: decodeEntities(manga.generated_current_data.number)}))
						.append(manga.title_data.ignore_chapter ? $('<span/>', {class: 'hidden-chapter', title: 'The latest chapter was marked as ignored.', text: manga.generated_ignore_data.number}) : null)
				).append(
					$('<td/>')
						.append(
							manga.generated_latest_data.number !== 'No chapters found' ?
								$('<a/>', {class: 'chp-release latest', href: manga.generated_latest_data.url, rel: 'nofollow', 'data-chapter': manga.title_data.latest_chapter, target: '_blank', text: decodeEntities(manga.generated_latest_data.number)})
								:
								$('<i/>', {title: 'Title page still appears to exist, but chapters have been removed. This is usually due to DMCA.', text: 'No chapters found'})
						)
				).append(
					$('<td/>')
						.append(
							manga.site_data.status === 'disabled' ?
								$('<i/>', {class: 'fa fa-exclamation-triangle', 'aria-hidden': 'true', style: 'color: red', title: `Tracking has been disabled for this series as the site '${manga.site_data.site}' is disabled`})
								:
								null
						)
						.append(
							manga.new_chapter_exists === 0 ?
								$('<div/>', {class: 'row-icons'})
									.append(
										$('<span/>', {class: 'list-icon ignore-latest', title: 'Ignore latest chapter. Useful when latest chapter isn\'t actually the latest chapter.'})
											.append($('<i/>', {class:' fa fa-bell-slash', 'aria-hidden': 'true'}))
									)
									.append(
										$('<span/>', {class: 'list-icon update-read', title: 'I\'ve read the latest chapter!'})
											.append($('<i/>', {class: 'fa fa-refresh', 'aria-hidden': 'true'}))
									)
								:
								null
						)
				);
				//endregion
				if(manga.site_data.status === 'disabled') {
					$tr.addClass('bg-danger');
				}
				else if(manga.title_data.status === 255) {
					$tr.addClass('bg-danger');
					$tr.attr('title', 'This title is no longer being updated as it has been marked as deleted/ignored.');
				}
				else if(manga.title_data.failed_checks >= 5) {
					$tr.addClass('bg-danger');
					$tr.attr('title', 'The last 5+ updates for this title have failed, as such it may not be completely up to date.');
				}
				else if(manga.title_data.failed_checks > 0) {
					$tr.addClass('bg-warning');
					$tr.attr('title', 'The last update for this title failed, as such it may not be completely up to date.');
				}

				return $tr;
			}
		}
		setupListEvents() {
			let _class = this;
			//This makes it easier to press the row checkbox.
			this.$tables.find('> tbody > tr > td:nth-of-type(1)').on('click', function (e) {
				if(!$(e.target).is('input')) {
					let $checkbox = $(this).find('> input[type=checkbox]');
					$($checkbox).prop('checked', !$checkbox.prop('checked'));
				}
			});

			//Requires user confirm to change page if any boxes are checked
			$('input[name=check]').on('change', function() {
				if(window.onbeforeunload === null) {
					window.onbeforeunload = function (e) {
						e = e || window.event;

						let dialogText = 'You appear to have some checked titles.\nDo you still wish to continue?';
						// For IE and Firefox prior to version 4
						if (e) { e.returnValue = dialogText; }

						// For others, except Chrome (https://bugs.chromium.org/p/chromium/issues/detail?id=587940)
						return dialogText;
					};
				} else if($('.tracker-table:visible').find('tr:has(td input[type=checkbox]:checked)').length === 0) {
					window.onbeforeunload = null;
				}
			});

			//This shows/hides the row info row.
			$('.toggle-info').on('click', function(e) {
				e.preventDefault();

				$(this).find('+ .more-info').toggle();
				if($(this).text() === 'More info') {
					$(this).text('Hide info');
				} else {
					$(this).text('More info');

					//Hide input when hiding info
					$(this).closest('tr').find('.tag-edit').attr('hidden', true);
				}
			});

			//Set MAL ID
			$('.set-mal-id').on('click', function(e) {
				e.preventDefault();

				let _this          = this,
				    current_mal_id = $(this).attr('data-mal-id');

				//If trackr.moe already has it's own MAL id for the series, ask if the user wants to override it (if they haven't already).
				if($(this).attr('data-mal-type') === 'title' && $(this).attr('data-mal-id') && !confirm('A MAL ID already exists for this series on our backend.\n Are you sure you want to override it?')) { return; }

				let new_mal_id     = prompt('MAL ID:', current_mal_id);
				if(/^([0-9]+|none)?$/.test(new_mal_id)) {
					if(/^[0-9]+$/.test(new_mal_id)) { new_mal_id = parseInt(new_mal_id); } //Stops people submitting multiple 0s

					let tr         = $(this).closest('tr'),
					    td         = tr.find('td:eq(1)'),
					    table      = $(this).closest('table'),
					    id         = tr.attr('data-id'),
					    icon_link  = $(td).find('.sprite-myanimelist-net').parent(),
					    iconN_link = $(td).find('.sprite-myanimelist-net-none').parent(),
					    id_text    = $(this).find('+ span'),
					    deferred   = $.Deferred();

					if(new_mal_id !== '' && new_mal_id !== 'none' && new_mal_id !== 0) {
						set_mal_id(id, new_mal_id, () => {
							$(iconN_link).remove(); //Make sure to remove MAL none icon when changing ID
							if(icon_link.length) {
								//icon exists, just change link
								$(icon_link).attr('href', 'https://myanimelist.net/manga/'+new_mal_id);
								$(icon_link).find('.sprite-myanimelist-net').attr('title', new_mal_id);
							} else {
								$($('<a/>', {href: 'https://myanimelist.net/manga/'+new_mal_id, class: 'mal-link'}).append(
									$('<i/>', {class: 'sprite-site sprite-myanimelist-net', title: new_mal_id})
								)).prepend(' ').insertAfter(td.find('.sprite-site'));
							}

							set_id_text($(_this), id_text, new_mal_id);

							deferred.resolve();
						});
					} else {
						if(new_mal_id === 'none' || new_mal_id === 0) {
							set_mal_id(id, '0', () => {
								icon_link.remove();
								iconN_link.remove();

								$($('<a/>', {class: 'mal-link'}).append(
									$('<i/>', {class: 'sprite-site sprite-myanimelist-net-none', title: new_mal_id})
								)).prepend(' ').insertAfter(td.find('.sprite-site'));

								set_id_text($(_this), id_text, 'none');

								deferred.resolve();
							});
						} else {
							set_mal_id(id, null, () => {
								icon_link.remove();
								iconN_link.remove();
								id_text.remove();

								deferred.resolve();
							});
						}
					}

					deferred.done(() => {
						$(this).attr('data-mal-id', new_mal_id);
						table.trigger('updateCell', [td[0], false, null]);
					});
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
						).prepend(' (').append(')').insertAfter(_this);
					}
				}

				function set_mal_id(id, mal_id, successCallback) {
					successCallback = successCallback || function(){};

					let postData = {
						'id'     : id,
						'mal_id' : mal_id
					};
					$.post(base_url + 'ajax/set_mal_id', postData, () => {
						successCallback();
					}).fail((jqXHR, textStatus, errorThrown) => {
						_handleAjaxError(jqXHR, textStatus, errorThrown);
					});
				}
			});

			this.setupTagEditor();

			//Ignore latest chapter
			$('.ignore-latest').on('click', function() {
				let row             = $(this).closest('tr'),
				    table           = $(this).closest('table'),
				    chapter_id      = $(row).attr('data-id'),
				    current_chapter = $(row).find('.current'),
				    latest_chapter  = $(row).find('.latest');

				if(confirm('Ignore latest chapter?')) {
					let postData = {
						id      : chapter_id,
						chapter : latest_chapter.attr('data-chapter')
					};
					$.post(base_url + 'ajax/ignore_inline', postData, () => {
						$(current_chapter).parent().append(
							$('<span/>', {class: 'hidden-chapter', title: 'This latest chapter was marked as ignored.', text: $(latest_chapter).text()})
						);

						_class.updateUnread(table, row);
					}).fail((jqXHR, textStatus, errorThrown) => {
						_handleAjaxError(jqXHR, textStatus, errorThrown);
					});
				}
			});

			//Update latest chapter (via "I've read the latest chapter")
			$('.update-read').on('click', function(e, data) {
				let row             = $(this).closest('tr'),
				    table           = $(this).closest('table'),
				    chapter_id      = $(row).attr('data-id'),
				    current_chapter = $(row).find('.current'),
				    latest_chapter  = $(row).find('.latest');

				if (!(data && data.isUserscript)) {
					let postData = {
						id     : chapter_id,
						chapter: latest_chapter.attr('data-chapter')
					};
					$.post(base_url + 'ajax/update_inline', postData, () => {
						$(current_chapter)
							.attr('href', $(latest_chapter).attr('href'))
							.text($(latest_chapter).text());

						_class.updateUnread(table, row);
					}).fail((jqXHR, textStatus, errorThrown) => {
						_handleAjaxError(jqXHR, textStatus, errorThrown);
					});
				} else {
					console.log('Userscript is updating table...');

					//Userscript handles updating current_chapter url/text.

					if(data.isLatest) {
						_class.updateUnread(table, row);
					} else {
						let chapter_e = current_chapter.parent();

						//Update updated-at time for sorting purposes.
						chapter_e.attr('data-updated-at', (new Date()).toISOString().replace(/^([0-9]+-[0-9]+-[0-9]+)T([0-9]+:[0-9]+:[0-9]+)\.[0-9]+Z$/, '$1 $2'));
						table.trigger('updateCell', [chapter_e[0], false, null]);
					}
				}
			});
		}
		setupTagEditor() {
			let _class = this;
			//Toggle input on clicking "Edit"
			$('.edit-tags').on('click', function(e) {
				e.preventDefault();
				let editorEle = $(this).parent().find('.tag-edit');
				editorEle.attr('hidden', function(_, attr){ return !attr; });
				if(!editorEle[0].hasAttribute('hidden')) {
					//NOTE: setTimeout is required here due to a chrome bug.
					setTimeout(function(){
						let input = editorEle.find('> input');
						input.focus();

						//Resetting value to force pointer to end of line
						//SEE: https://stackoverflow.com/a/8631903
						let tmp_val = input.val();
						input.val('');
						input.val(tmp_val);
					}, 1);
				}
			});


			//Simulate "Save" click on enter press.
			$('.tag-edit input').on('keypress', function(e) {
				if(e.which === /* enter */ 13) {
					$(this).closest('.tag-edit').find('[type=button]').click();
				}
			});

			//Submit tags
			$('.tag-edit [type=button]').on('click', function() {
				let _this = this;
				//CHECK: We would use jQuery.validate here but I don't think it works without an actual form.
				let input    = $(this).closest('.tag-edit').find('input'),
				    tag_list = input.val().toString().trim().replace(/,,/g, ','),
				    id       = $(this).closest('tr').attr('data-id'),
				    table    = $(this).closest('table'),
				    td       = $(this).closest('td');

				//Validation
				_class.validateTagList(tag_list, (tag_list_new) => {
					let postData = {
						'id'         : id,
						'tag_string' : tag_list_new
					};
					$.post(base_url + 'ajax/tag_update', postData, () => {
						$(input).val(tag_list_new);

						let $tag_list = $(_this).closest('.more-info').find('.tag-list');
						if(!tag_list_new) {
							$tag_list.text('none');
						} else {
							let tagArr = tag_list_new.split(',').map((e/*, i*/) => {
								return $('<i/>', {class: 'tag', text: e});
							});
							$tag_list.html(tagArr);
						}

						table.trigger('updateCell', [td[0], false, null]);

						$(_this).closest('.tag-edit').attr('hidden', function(_, attr){ return !attr; });
					}).fail((jqXHR, textStatus, errorThrown) => {
						_handleAjaxError(jqXHR, textStatus, errorThrown);
					});
				});
			});
		}
		validateTagList(tag_list, callback) {
			if(!$.isArray(tag_list)) { tag_list = tag_list.trim().replace(/,,/g, ','); }

			if(/^[a-z0-9\-_,:]{0,255}$/.test(tag_list)) {
				let tag_array    = Array.from(new Set(tag_list.split(','))).filter(function(n){ return n !== ''; }),
				    tag_list_new = tag_array.join(',');
				if($.inArray('none', tag_array) === -1) {
					if((tag_list.match(/\bmal:(?:[0-9]+|none)\b/g) || []).length <= 1) {
						callback(tag_list_new);
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
		}

		setupStickyListHeader() {
			let $window    = $(window),
			    nav        = $('#list-nav'),
			    offset     = nav.offset().top - nav.find('ul').height()/* - 21*/,
			    list_table = $('table[data-list]');
			if(offset > 10) {
				//normal load
				$window.on('scroll', function() {
					//FIXME: Using .scroll for this seems really slow. Is there no pure CSS way of doing this?
					//FIXME: The width of the nav doesn't auto-adjust to change window width (since we're calcing it in JS)..
					handleScroll();
				});
				handleScroll(); //Make sure we also trigger on page load.
			} else {
				//page was loaded via less but less hasn't parsed yet.
				let existCondition = setInterval(function() {
					if($('style[id="less:less-main"]').length) {
						offset = nav.offset().top - nav.find('> ul').height() - 2; //reset offset

						$window.on('scroll', function() {
							handleScroll();
						});
						handleScroll(); //Make sure we also trigger on page load.

						clearInterval(existCondition);
					}
				}, 500);
			}

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
		}

		startTablesorter() {
			//region Setup sorting methods & metatags.
			$.tablesorter.addParser(
				{
					id: 'updated-at',

					is: function() {
						return false; // return false so this parser is not auto detected
					},

					format: function(s, table, cell/*, cellIndex*/) {
						return parseInt($(cell).attr('data-updated-at').replace(/[^0-9]+/g, '').toString());
					},

					type: 'numeric'
				}
			);

			$.tablesorter.addParser(
				{
					id: 'latest',

					is: function() {
						return false; // return false so this parser is not auto detected
					},

					format: function(s, table, cell/*, cellIndex*/) {
						return parseInt($(cell).closest('tr').find('td:eq(1) .sprite-time').attr('title').replace(/[^0-9]+/g, '').toString());
					},

					type: 'numeric'
				}
			);

			/**
			 * @return {boolean|null}
			 */
			$.tablesorter.filter.types.FindMalId = function( config, data ) {
				if(/^mal:(?:[0-9]+|any|none|notset|duplicates?)$/.test(data.iFilter)) {
					let searchID  = data.iFilter.match(/^mal:([0-9]+|any|none|notset|duplicates?)$/)[1].toLowerCase();
					if(searchID === 'duplicates') { searchID = 'duplicate'; }

					let status = false;
					switch(searchID) {
						case 'any':
							status = (data.$row.find('> td:eq(1) i.sprite-myanimelist-net').length > 0);
							break;

						case 'none':
							status = (data.$row.find('> td:eq(1) i.sprite-myanimelist-net-none').length > 0);
							break;

						case 'notset':
							status = (data.$row.find('> td:eq(1) i[class*="sprite-myanimelist-net"]').length === 0);
							break;

						case 'duplicate':
							if(data.$row.find('> td:eq(1) i.sprite-myanimelist-net').length > 0) {
								let malID      = data.$row.find('> td:eq(1) i.sprite-myanimelist-net').attr('title'),
								    $foundRows = data.$row.parent().find(`tr > td:nth-of-type(2) i.sprite-myanimelist-net[title=${malID}]`);

								status = ($foundRows.length > 1);
							}
							break;

						default:
							let currentID = data.$row.find('> td:eq(1) i.sprite-myanimelist-net').attr('title');
							status = (searchID === currentID);
							break;
					}

					return status;
				}
				return null;
			};

			/**
			 * @return {boolean|null}
			 */
			$.tablesorter.filter.types.FindSite = function( config, data ) {
				if(/^site:[\w-.]+$/.test(data.iFilter)) {
					let searchSite  = data.iFilter.match(/^site:([\w-.]+)$/i)[1].replace(/\./g, '-').toLowerCase(),
					    currentSite = data.$row.find('> td:eq(1) .sprite-site').attr('class').split(' ')[1].substr(7);

					return searchSite === currentSite;
				}
				return null;
			};

			/**
			 * @return {boolean|null}
			 */
			$.tablesorter.filter.types.FindTag = function(config, data) {
				if(/^tag:[a-z0-9\-_:,]{1,255}$/.test(data.iFilter)) {
					let searchTagList  = data.iFilter.match(/^tag:([a-z0-9\-_:,]{1,255})$/)[1],
					    searchTagArray = searchTagList.split(',');

					let rowTagArray = data.$row.find('td:eq(1) .tag-list .tag').map((i, e) => {
						return $(e).text();
					}).toArray();

					return searchTagArray.every(tag => ($.inArray(tag, rowTagArray) !== -1));
				}
				return null;
			};
			/**
			 * @return {boolean|null}
			 */
			$.tablesorter.filter.types.FindChecked = function( config, data ) {
				if(/^checked:(?:yes|no)$/.test(data.iFilter)) {
					let checked = data.iFilter.match(/^checked:(yes|no)$/)[1].toLowerCase();

					let status = data.$row.find('> td:eq(0) input').is(':checked');
					if(checked === 'no') { status = !status; }

					return status;
				}
				return null;
			};
			/**
			 * @return {boolean|null}
			 */
			$.tablesorter.filter.types.FindUnread = function( config, data ) {
				if(/^unread:(?:yes|no)$/.test(data.iFilter)) {
					let unread = data.iFilter.match(/^unread:(yes|no)$/)[1].toLowerCase();

					let status = data.$row.find('> td:eq(4)').children().length > 0;
					if(unread === 'no') { status = !status; }

					return status;
				}
				return null;
			};

			//The range filter uses "to" as a designator which can cause issues when searching. - SEE: #221
			//FIXME: We should try and presserve the original filter and just remove to "to" designator. Same goes to the "and" designator for
			delete $.tablesorter.filter.types.range;
			//endregion

			this.$tables.on('click', '.table-reset', function() { $(this).closest('table').trigger('resetToLoadState'); });

			this.refreshTablesorter();
		}
		refreshTablesorter() {
			let _class = this;

			this.$tables.trigger('destroy');

			this.$tables
				.tablesorter(this.tablesorterDefaults)
				.bind('sortEnd', function(e, table) {
					if(_class.initialSortOrder.sort().toString() === e.target.config.sortList.sort().toString()) {
						$(table).find('thead > tr > th:eq(4) > .tablesorter-header-inner').empty();
					} else {
						let th = $(table).find('thead > tr > th:eq(4) > .tablesorter-header-inner');
						if(!th.find('> .table-reset').length) {
							th.empty().append($('<i/>', {class: 'fa fa-eraser table-reset', 'aria-hidden': 'true'}));
						}
					}
				});
		}
		getListSort(type, order) {
			let sortArr = [];

			let sortOrder = (order === 'asc' ? 0 : 1);
			switch(type) {
				case 'unread':
					sortArr = [[/* unread */ 0, 0], [/* title*/ 1, sortOrder]];
					break;

				case 'unread_latest':
					sortArr = [[/* unread */ 0, 0], [/* title*/ 3, sortOrder]];
					break;

				case 'alphabetical':
					sortArr = [[/* title */ 1, sortOrder]];
					break;

				case 'my_status':
					sortArr = [[/* unread */ 2, sortOrder]];
					break;

				case 'latest':
					sortArr = [[/* unread */ 3, sortOrder]];
					break;

				default:
					break;
			}

			return sortArr;
		}
	}
	let App = new TrackrApp();
	App.start();
});
