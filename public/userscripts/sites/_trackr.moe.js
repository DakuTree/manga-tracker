(function(sites) {
	//TODO: Plan is to move this to actual inline userscript options at some point.
	sites['trackr.moe'] = {
		init : function() {
			let _this = this;

			switch(location.pathname) {
				case '/':
					//Dashboard / Front Page
					if($('#page[data-page=dashboard]').length) {
						//TODO: Is there a better way to do this?
						$('.update-read').click(function() {
							let row             = $(this).closest('tr'),
							    latest_chapter  = $(row).find('.latest');

							//get mal_sync option
							//NOTE: This variable is set on the page, not through the userscript.
							switch(mal_sync) {
								case 'disabled':
									//do nothing
									break;

								case 'csrf':
									let mal_arr   = $(row).find('.sprite-myanimelist-net');

									if(mal_arr.length > 0) {
										let mal_id = parseInt(mal_arr.attr('title'));
										_this.syncMALCSRF(mal_id, latest_chapter.text());
									}

									break;

								case 'api':
									//TODO: Not implemented yet.
									break;

								default:
									break;
							}
						});

						//NOTE: GM_addValueChangeListener is TamperMonkey only, but come to others eventually: https://github.com/greasemonkey/greasemonkey/issues/2646
						if(typeof GM_addValueChangeListener !== 'undefined') {
							GM_addValueChangeListener('lastUpdatedSeries', function(name, old_value, new_value/*, remote*/) {
								//TODO: Move as much of this as possible to using the actual site functions.

								let data          = JSON.parse(new_value),
								    site          = data.manga.site,
								    title         = data.manga.title,
								    chapter       = data.manga.chapter,
								    chapterNumber = data.chapterNumber,
								    url           = data.url;

								let row = $(`i[title="${site}"]`) //Find everything using site
									.closest('tr')
									.find(`[data-title="${title}"]`) //Find title
									.closest('tr');
								if(row.length) {
									let current_chapter = $(row).find('.current'),
									    latest_chapter  = $(row).find('.latest'),
									    update_ele      = unsafeWindow.$(row).find('.update-read');

									$(current_chapter)
										.attr('href', url);
									if(chapter.toString() === latest_chapter.attr('data-chapter').toString()) {
										$(current_chapter).text(latest_chapter.text()); //This uses formatted chapter when possible
										update_ele.trigger('click', {isUserscript: true, isLatest: true});
									} else {
										//Chapter isn't latest.
										$(current_chapter).text(chapterNumber);
										update_ele.trigger('click', {isUserscript: true, isLatest: false});
									}
								}
							});
						}
					}
					break;

				case '/user/options':
					/* TODO:
					   Stop generating HTML here, move entirely to PHP, but disable any user input unless enabled via userscript.
					   If userscript IS loaded, then insert data.
					   Separate API key from general options. Always set API config when generate is clicked.
					*/

					let form = $('#userscript-form');

					this.enableForm(form); //Enable the form

					//CHECK: Is there a better way to mass-set form values from an object/array?
					$(form).find('input[name=auto_track]').attr(    'checked', ('auto_track' in config.options));
					$(form).find('input[name=disable_viewer]').attr('checked', ('disable_viewer' in config.options));

					$(form).submit(function(e) {
						let data = {};
						data.options = $(this).serializeArray().reduce(function(m,o){
							m[o.name] = (typeof o.value !== 'undefined' ? true : o.value);
							return m;
						}, {});
						/** @namespace data.csrf_token */
						delete data.csrf_token;
						if(config['api-key']) {
							config = $.extend(config, data);

							GM.setValue('config', JSON.stringify(config));
							$('#form-feedback').text('Settings saved.').show().delay(4000).fadeOut(1000);
						} else {
							$('#form-feedback').text('API Key needs to be generated before options can be set.').show().delay(4000).fadeOut(1000);
						}

						e.preventDefault();
					});

					if(location.hostname === 'dev.trackr.moe') {
						$('#api-key').text(config['api-key-dev'] || 'not set');
					} else {
						$('#api-key').text(config['api-key'] || 'not set');
					}
					let apiDiv = $('#api-key-div');
					apiDiv.on('click', '#generate-api-key', function() {
						GM.xmlHttpRequest({
							url     : main_site + '/ajax/get_apikey',
							method  : 'GET',
							onload  : function(e) {
								if(e.status === 200) {
									let data = e.responseText,
									    json = JSON.parse(data);

									if(json['api-key']) {
										$('#api-key').text(json['api-key']);

										if(location.hostname === 'dev.trackr.moe') {
											config['api-key-dev'] = json['api-key'];
										} else {
											config['api-key']     = json['api-key'];
										}
										GM.setValue('config', JSON.stringify(config));
									} else {
										alert('ERROR: Something went wrong!\nJSON missing API key?');
									}
								} else {
									switch(e.status) {
										case 400:
											alert('ERROR: Not logged in?');
											break;
										case 429:
											alert('ERROR: Rate limit reached.');
											break;
										default:
											alert('ERROR: Something went wrong!\n'+e.statusText);
											break;
									}
								}
							},
							onerror : function(e) {
								switch(e.status) {
									case 400:
										alert('ERROR: Not logged in?');
										break;
									case 429:
										alert('ERROR: Rate limit reached.');
										break;
									default:
										alert('ERROR: Something went wrong!\n'+e.statusText);
										break;
								}
							}
						});
					});
					apiDiv.on('click', '#restore-api-key', function() {
						GM.xmlHttpRequest({
							url     : main_site + '/ajax/get_apikey/restore',
							method  : 'GET',
							onload  : function(e) {
								if(e.status === 200) {
									let data = e.responseText,
									    json = JSON.parse(data);

									if(json['api-key']) {
										if(json['api-key'] !== '') {
											$('#api-key').text(json['api-key']);

											if(location.hostname === 'dev.trackr.moe') {
												config['api-key-dev'] = json['api-key'];
											} else {
												config['api-key'] = json['api-key'];
											}
											GM.setValue('config', JSON.stringify(config));
										} else {
											alert('API Key hasn\'t been set before. Use generate API key instead.')
										}
									} else {
										alert('ERROR: Something went wrong!\nJSON missing API key?');
									}
								} else {
									switch(e.status) {
										case 400:
											alert('ERROR: Not logged in?');
											break;
										case 429:
											alert('ERROR: Rate limit reached.');
											break;
										default:
											alert('ERROR: Something went wrong!\n'+e.statusText);
											break;
									}
								}
							},
							onerror : function(e) {
								switch(e.status) {
									case 400:
										alert('ERROR: Not logged in?');
										break;
									case 429:
										alert('ERROR: Rate limit reached.');
										break;
									default:
										alert('ERROR: Something went wrong!\n'+e.statusText);
										break;
								}
							}
						});
					});

					break;
			}
		},
		enableForm : function(form) {
			$('#userscript-check')
				.text('Userscript is enabled!')
				.removeClass('alert-danger')
				.addClass('alert-success');
			$(form).find('fieldset').removeAttr('disabled');
			$(form).find('input[type=submit]').removeAttr('onclick');
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
