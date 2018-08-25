/* global GM, GM_config, GM_addValueChangeListener, main_site, userscriptVersion, versionCompare, mal_sync, unsafeWindow */
//NOTE: mal_sync is set on the page

(function(sites) {
	sites['trackr.moe'] = {
		init : function() {
			let _this = this;

			switch(location.pathname) {
				case '/user/dashboard': {
					//Dashboard / Front Page
					if($('#page[data-page=dashboard]').length) {
						//TODO: Is there a better way to do this?
						$('.update-read').on('click', function() {
							let row             = $(this).closest('tr'),
							    latest_chapter  = $(row).find('.latest');

							//get mal_sync option
							//NOTE: This variable is set on the page, not through the userscript.
							switch (mal_sync) {
								case 'disabled': {
									//do nothing
									break;
								}

								case 'csrf': {
									let mal_arr = $(row).find('.sprite-myanimelist-net');

									if(mal_arr.length > 0) {
										let mal_id = parseInt(mal_arr.attr('title'));
										_this.syncMALCSRF(mal_id, latest_chapter.text());
									}

									break;
								}

								case 'api': {
									//TODO: Not implemented yet.
									break;
								}

								default: {
									break;
								}
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

								let row = unsafeWindow.$(`i[title="${site}"]`) //Find everything using site
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
										unsafeWindow.updateChapter(update_ele, true, true);
									} else {
										//Chapter isn't latest.
										$(current_chapter).text(chapterNumber);
										unsafeWindow.updateChapter(update_ele, false, true);
									}
								}
							});
						}
					}
					break;
				}

				case '/user/options': {
					let userscriptDiv = $('#options-userscript');

					// Add open userscript options link.
					let openLink = $('<a/>', {href: '#', text: 'Open Userscript Options'}).on('click', function(e) {
						e.preventDefault();

						GM_config.open();
					});
					openLink.appendTo(userscriptDiv);

					/* ALL OLD CODE */
					let check         = $('#userscript-check'),
					    latestVersion = check.attr('data-version');

					check
						.html('')
						.append($('<span/>', {text: 'Userscript is enabled!'}))
						.removeClass('alert-danger')
						.addClass('alert-success');

					if(userscriptVersion === undefined) {
						let versionWarning = $('<div/>', {class: 'alert alert-danger text-center'});
						versionWarning.html(`Your userscript extension appears to be having issues loading required data. Try using another extension such as: TamperMonkey (Chrome) or ViolentMonkey (FireFox).`);

						$(versionWarning).insertAfter(check);

					}
					else if(versionCompare(latestVersion, userscriptVersion) === 1) {
						let versionWarning = $('<div/>', {class: 'alert alert-danger text-center'});
						versionWarning.html(`Userscript version is behind the version reported by the server.<br/>${userscriptVersion} < ${latestVersion}<br/>Click <a href='https://trackr.moe/userscripts/manga-tracker.user.js'>here</a> to manually update to the latest version.`);

						$(versionWarning).insertAfter(check);
					}

					if(location.hostname !== 'manga-tracker.localhost') {
						$('#api-key').text(GM_config.get('apiKey') || 'not set');
					} else {
						$('#api-key').text(GM_config.get('apiKeyDev')|| 'not set');
					}
					let apiDiv = $('#api-key-div');
					apiDiv.on('click', '#generate-api-key', function() {
						GM.xmlHttpRequest({
							url     : main_site + '/ajax/get_apikey',
							method  : 'GET',
							withCredentials : true,
							onload  : function(e) {
								if(e.status === 200) {
									let data = e.responseText,
									    json = JSON.parse(data);

									if(json['api-key']) {
										setApiKey(json['api-key']);
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
										setApiKey(json['api-key']);
									} else if(json['api-key'] === null) {
										alert('API Key hasn\'t been set before. Use generate API key instead.');
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
			}

			function setApiKey(apiKey) {
				$('#api-key').text(apiKey);

				if(location.hostname !== 'manga-tracker.localhost') {
					GM_config.set('apiKeyProd', apiKey);
				} else {
					GM_config.set('apiKeyDev', apiKey);
				}
				GM_config.save();
			}
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
