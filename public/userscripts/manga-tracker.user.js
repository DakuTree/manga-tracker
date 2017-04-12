// ==UserScript==
// @name         Manga Tracker
// @namespace    https://github.com/DakuTree/userscripts
// @author       Daku (admin@codeanimu.net)
// @description  A cross-site manga tracker.
// @homepageURL  https://trackr.moe
// @supportURL   https://github.com/DakuTree/manga-tracker/issues
// @icon         https://trackr.moe/favicon.production.png
// @include      /^https:\/\/(?:(?:dev|test)\.)?trackr\.moe(\/.*$|$)/
// @include      /^http:\/\/mangafox\.me\/manga\/.+\/(?:.*\/)?.*\/.*$/
// @include      /^http:\/\/(?:www\.)?mangahere\.co\/manga\/.+\/.*\/?.*\/.*$/
// @include      /^https?:\/\/bato\.to\/reader.*$/
// @include      /^https:/\/dynasty-scans\.com\/chapters\/.+$/
// @include      /^http:\/\/www\.mangapanda\.com\/(?!(?:search|privacy|latest|alphabetical|popular|random)).+\/.+$/
// @include      /^https?:\/\/readms\.net\/r\/.+\/.+\/[0-9]+(?:\/[0-9]+)?(?:\?.+)?$/
// @include      /^http:\/\/www\.webtoons\.com\/(?:en|zh-hant|zh-hans|th|id)\/[a-z0-9A-Z-_]+\/[a-z0-9A-Z-_]+\/[a-z0-9A-Z-_]+\/viewer\?title_no=[0-9]+&episode_no=[0-9]+$/
// @include      /^http:\/\/kissmanga\.com\/Manga\/[a-zA-Z0-9-_]+\/[a-zA-Z0-9-_%]+\?id=[0-9]+$/
// @include      /^https?:\/\/reader\.kireicake\.com\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^https?:\/\/reader\.seaotterscans\.com\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^https?:\/\/reader\.sensescans\.com\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^https?:\/\/helveticascans\.com\/r(?:eader)?\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^https:\/\/gameofscanlation\.moe\/projects\/[a-z0-9-]+\/[a-z0-9\.-]+\/.*$/
// @include      /^http:\/\/mngcow\.co\/[a-zA-Z0-9_]+\/[0-9]+\/([0-9]+\/)?$/
// @include      /^https:\/\/jaiminisbox\.com\/reader\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^https:\/\/kobato\.hologfx\.com\/reader\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^http:\/\/www\.demonicscans\.com\/FoOlSlide\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^https?:\/\/reader\.deathtollscans\.net\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^http:\/\/read\.egscans\.com\/[A-Za-z0-9\-_\!,]+(?:\/Chapter_[0-9]+(?:_extra)?\/?)?$/
// @updated      2017-04-12
// @version      1.5.2
// @downloadURL  https://trackr.moe/userscripts/manga-tracker.user.js
// @updateURL    https://trackr.moe/userscripts/manga-tracker.meta.js
// @require      https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js
// @resource     fontAwesome https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css
// @resource     reload https://trackr.moe/userscripts/reload.png
// @grant        GM_addStyle
// @grant        GM_getResourceText
// @grant        GM_getResourceURL
// @grant        GM_getValue
// @grant        GM_setValue
// @grant        GM_xmlhttpRequest
// @connect      myanimelist.net
// @run-at       document-start
// ==/UserScript==
/* jshint -W097, browser:true, devel:true, multistr:true, esnext:true */
/* global $:false, jQuery:false, GM_addStyle:false, GM_getResourceText:false, GM_getResourceURL:false, GM_getValue, GM_setValue, GM_xmlhttpRequest, mal_sync */
'use strict';

GM_addStyle(GM_getResourceText("fontAwesome").replace(/\.\.\//g, 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/'));

/* CORE TODO
Setup events for topbar favourites, stop tracking. Unsure how exactly we should go about "stop tracking" though?
Move all CSS to external site. Should allow faster loading.
*/

$.fn.reverseObj = function() {
	return $(this.get().reverse());
};
function getCookie(k){return(document.cookie.match(new RegExp('(^|; )'+k+'=([^;]*)'))||0)[2];}

/***********************************************************************************************************/

let base_site = {
	init : function() {
		let _this = this;

		this.preInit(function() {
			_this.setObjVars();
			_this.page_count = parseInt(_this.page_count); //FIXME: Is there a better place to put this?

			_this.stylize();

			_this.setupTopBar();

			/** @namespace config.options.disable_viewer */
			if(config.options.disable_viewer) return;
			_this.setupViewer();
		});
	},
	preInit : function(callback) { callback(); }, //callback must always be called

	//Functions
	setObjVars      : function() {},
	stylize         : function() {},
	preSetupTopBar  : function(callback) { callback(); }, //callback must always be called
	postSetupTopBar : function(topbar) {},
	preSetupViewer  : function(callback) { callback(); }, //callback must always be called
	postSetupViewer : function(topbar) {},


	//Fixed Functions
	setupTopBar : function() {
		let _this = this;

		this.preSetupTopBar(function() {
			GM_addStyle(`
				#TrackerBar {
					position: fixed    !important;
					top:      0        !important;
					z-index:  10000000 !important;

					height: 0; /*Allows everything outside the topbar to be clicked properly*/
					width:  100% !important;

					opacity: .9 !important;
					padding:  0 !important;
					margin:   0 !important;

					font:       14px 'Open Sans', Arial, Helvetica, sans-serif !important;
					color:      black  !important;
					text-align: center !important;
				}
				#TrackerBar > * {
					display: inline-block;

					opacity: 1               !important;
					padding: 0 15px 2px 15px !important;
					margin:  0               !important;

					background-color: #FFF !important;

					border:        1px solid #CCC !important;
					border-top:    0              !important;
					border-radius: 0 0 6px 6px    !important;
				}

				#TrackerBarLayout {
					padding: 0 !important;
					margin:  0 !important;
				}
				a.buttonTracker {
					display: inline-block;
					cursor: pointer;

					margin:    5px;
					padding:   2px;
					min-width: 100px;

					background: linear-gradient(0, #EEE, #FFF);

					border:        1px solid rgb(221, 221, 221);
					border-radius: 5px;

					font-size:       13px;
					font-weight:     initial;
					color:           black;
					text-align:      center;
					text-decoration: none;

					transition: all 0.4s ease-in-out;
				}
				a.buttonTracker:hover {
					background: linear-gradient(0, rgb(255, 255, 255), rgb(238, 238, 238));

					border-color: #3278BE;

					color:           #003C82 !important;
					text-decoration: none    !important;
				}
				a.buttonTracker:active {
					background: #4195DD; /* For browsers that do not support gradients */
					background: linear-gradient(90deg, #003C82, #4195DD);
				}

				#TrackerBar *         { vertical-align: middle  !important; }
				#TrackerBar a         {
					vertical-align: initial !important;
					color:          black   !important;
				}
				#TrackerBar a:visited { color:black !important; }

				#TrackerBarIn .fa {
					margin: auto 5px !important;

					font-size: 16px;

					cursor: pointer !important;
				}
				#TrackerBarIn select {
					/* A lot of sites tend to overwrite the base <select> styles, so we need to revert */
					margin: 0 !important;

					background-color: initial;

					border: 1px solid rgb(221, 221, 221);

					font:  inherit;
					color: initial;
				}
				#TrackerBarIn select { margin: 0 !important; }
			`);
			let previous = (Object.keys(_this.chapterList).indexOf(_this.chapterListCurrent) > 0 ? $('<a/>', {class: 'buttonTracker', href: Object.keys(_this.chapterList)[Object.keys(_this.chapterList).indexOf(_this.chapterListCurrent) - 1], text: 'Previous'}) : "");
			let next     = (Object.keys(_this.chapterList).indexOf(_this.chapterListCurrent) < (Object.keys(_this.chapterList).length - 1) ? $('<a/>', {class: 'buttonTracker', href: Object.keys(_this.chapterList)[Object.keys(_this.chapterList).indexOf(_this.chapterListCurrent) + 1], text: 'Next'}) : "");
			let options  = $.map(_this.chapterList, function(k, v) {let o = $('<option/>', {value: v, text: k}); if(_this.chapterListCurrent === v) {o.attr('selected', '1');} return o.get();});

			let topbar = $('<div/>', {id: 'TrackerBar'}).append(
				$('<div/>', {id: 'TrackerBarIn'}).append(
					$('<a/>', {href: main_site, target: '_blank'}).append(
						$('<i/>', {class: 'fa fa-home', 'aria-hidden': 'true'}))).append(
					$('<div/>', {id: 'TrackerBarLayout', style: 'display: inline-block'}).append(
						previous
					).append(
						$('<select/>', {style: 'float: none; max-width: 500px', title: _this.viewerTitle}).append(
							options
						)
					).append(
						next
					).append(
						// $('<img/>', {class: 'trackStop', src: trackBase64, title: 'Stop following updates for this manga'})).append(
						$('<i/>', {id: 'report-bug', class: 'fa fa-bug', 'aria-hidden': 'true', title: 'Report a Bug'})
					).append(
						$('<i/>', {id: 'favouriteChapter', class: 'fa fa-star', 'aria-hidden': 'true', title: 'Click to favourite this chapter (Requires series to be tracked first!)'})
					).append(
						$('<i/>', {id: 'trackCurrentChapter',  class: 'fa fa-book', 'aria-hidden': 'true', style: 'color: maroon', title: 'Mark this chapter as latest chapter read'})
					).append(
						$('<span/>', {id: 'TrackerStatus'})
					)
				)
			).append(
				$('<br/>')
			).append(
				(_this.page_count ? $('<div/>', {id: 'TrackerBarPages', text: 'Pages loaded: '+_this.pagesLoaded+'/'+_this.page_count}) : '')
			);

			$(topbar).appendTo('body');

			//Setup select chapter change event
			$(topbar).on('change', 'select', function() {
				console.log(this.value);
				location.href = this.value;
				if(this.value.indexOf('#') !== -1) {
					window.location.reload();
				}
			});

			//Setup prev/next events
			$(topbar).on('click', 'a.buttonTracker', function(e) {
				e.preventDefault();

				location.href = $(this).attr('href');
				if($(this).attr('href').indexOf('#') !== -1) {
					window.location.reload();
				}
			});
			//Setup tracking event.
			$(topbar).on('click', '#trackCurrentChapter', function(e) {
				e.preventDefault();

				_this.trackChapter(true);
				// $(this).css('color', '#00b232');
			});
			//Setup bug report event.
			$(topbar).on('click', '#report-bug', function(e) {
				e.preventDefault();

				_this.reportBug();
			});
			//Setup favourite event.
			$(topbar).on('click', '#favouriteChapter', function(e) {
				e.preventDefault();

				_this.favouriteChapter();
			});
			//Setup reload page failed pages event.
			$(topbar).on('click', '#reloadPages', function(e) {
				e.preventDefault();

				_this.reloadPages();
			});

			_this.postSetupTopBar(topbar);
		});
	},
	trackChapter : function(askForConfirmation) {
		let _this = this;
		askForConfirmation = (typeof askForConfirmation !== 'undefined' ? askForConfirmation : false);

		if(config['api-key']) {
			if(this.attemptingTrack === false) {
				this.attemptingTrack = true;

				let params = {
					'api-key' : config['api-key'],
					'manga'   : {
						'site'    : this.site,

						//Both title and chapter can contain anything, as parsing is done on the backend.
						'title'   : this.title,
						'chapter' : this.chapter
					}
				};
				//TODO: Check if everything is set, and not null.

				if(!askForConfirmation || askForConfirmation && confirm("This action will reset your reading state for this manga and this chapter will be considered as the latest you have read.\nDo you confirm this action?")) {
					$.post(main_site + '/ajax/userscript/update', params, function (json) {
						//TODO: We should really output this somewhere other than the topbar..
						$('#TrackerStatus').text('Updated');

						switch(json['mal_sync']) {
							case 'disabled':
								//do nothing
								break;

							case 'csrf':
								if(json['mal_id']) _this.syncMALCSRF(json['mal_id'], json['chapter']);
								break;

							case 'api':
								//TODO: Not implemented yet.
								break;
							
							default:
								break;
						}
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
					}).always(function() {
						_this.attemptingTrack = false;
					});
				}
			} else {
				alert('Tracker is already attempting to track..');
			}
		} else {
			alert('Tracker isn\'t setup! Go to trackr.moe/user/options to set things up.');
		}
	},
	syncMALCSRF : function(malID, chapter) {
		let _this = this;
		GM_xmlhttpRequest({
			method: "GET",
			url: "https://myanimelist.net/panel.php?go=export",
			onload: function(response) {
				if(/https:\/\/myanimelist.net\/logout.php/.exec(response.responseText)) {
					//user is logged in, export manga then sync
					let csrfToken = /<meta name='csrf_token' content='([A-Za-z0-9]+)'>/.exec(response.responseText)[1];

					_this.syncMALCSRF_continued(malID, chapter, csrfToken);
				} else {
					//user is not logged in, throw error
					alert("Unable to sync, are you logged in on MAL?");
				}
			}
		});
	},
	syncMALCSRF_continued : function(malID, chapter, csrfToken) {
		let chapterArr = chapter.match(/^(?:v[0-9]+\/)?c([0-9]+)(?:\.[0-9]+)?$/) || [];

		if(chapterArr.length > 0) {
			let json = {
				"manga_id"          : parseInt(malID),
				"status"            : 1, //force reading list
				"num_read_chapters" : parseInt(chapterArr[1]),
				"csrf_token"        : csrfToken
			};
			GM_xmlhttpRequest({
				method: "POST",
				url: 'https://myanimelist.net/ownlist/manga/edit.json',
				data: JSON.stringify(json),
				onload: function() {
					$('#TrackerStatus').text('Updated & MAL Synced (c'+parseInt(chapterArr[1])+')');
				},
				onerror: function() {
					$('#TrackerStatus').text('Updated (MAL Sync failed)');
				}
				//TODO: On success/failure show UX
			});
		} else {
			$('#TrackerStatus').text('Updated (Unable to MAL Sync due to chapter format)');
		}
	},

	setupViewer : function() {
		let _this = this;

		//FIXME: VIEWER: Is it possible to make sure the pages load in order without using async: false?
		//FIXME: VIEWER: Is it possible to set the size of the image element before it is loaded (to avoid pop-in)?
		//FIXME: Somehow handle the viewer header code here?

		this.preSetupViewer(function(useCustomHeader, useCustomImageList) {
			useCustomHeader    = (typeof useCustomHeader !== 'undefined' ? useCustomHeader : false);
			useCustomImageList = (typeof useCustomImageList !== 'undefined' ? useCustomImageList : false);

			GM_addStyle(`
				#viewer                 { width: auto; max-width: 95%; margin: 0 auto !important; text-align: center; background: inherit; border: inherit; }
				#viewer > .read_img     { background: none; }
				#viewer > .read_img img { width: auto; max-width: 95%; border: 5px solid #a9a9a9; min-height: 300px;}
				.pageNumber             { border-image-source: initial; border-image-slice: initial; border-image-width: initial; border-image-outset: initial; border-image-repeat: initial; border-collapse: collapse; background-color: black; color: white; /*height: 18px; */font-size: 12px; font-family: Verdana; font-weight: bold; position: relative; bottom: 11px; width: 50px; text-align: center; opacity: 0.75; border-width: 2px; border-style: solid; border-color: white; border-radius: 16px !important; margin: 0px auto !important; padding: 0px !important; border-spacing: 0px !important;}
				.pageNumber .number     { border-collapse: collapse; text-align: center; display: table-cell; width: 50px; height: 18px; vertical-align: middle; border-spacing: 0px !important; padding: 0px !important; margin: 0px !important; }
				#viewer_header          { font-weight: bolder; text-align: center; }
			`);

			//Setup viewer header if enabled
			if(!useCustomHeader) {
				$('#viewer').append(
					$('<div/>', {id: 'viewer_header'}).append(
						$('<a/>', {href: _this.chapter_url, text: _this.viewerChapterName})).append(
						'  ----  ').append(
						$('<a/>', {href: _this.title_url, text: _this.viewerTitle})
					)
				);
			}

			//Generate the viewer using a loop & AJAX.
			for(let pageN=1; pageN<=_this.page_count; pageN++) {
				if(pageN === 1) {
					$('<div/>', {id: 'page-'+pageN, class: 'read_img'}).appendTo($('#viewer'));

					$('#TrackerBarPages')
						.hide('slow', function() {
							//This saves the display css.
							$('#TrackerBarPages').show('slow');
						});
				} else {
					$('<div/>', {id: 'page-'+pageN, class: 'read_img'}).insertAfter($('#viewer').find('> .read_img:last'));
				}

				if(!useCustomImageList) {
					setTimeout(function() {
						let url = _this.viewerChapterURLFormat.replace('%pageN%', pageN.toString());
						$.ajax({
							url    : url,
							type   : 'GET',
							page   : pageN,
							// async: useASync,
							success: function (data) {
								let original_image = $(data.replace(_this.viewerRegex, '$1')).find('img:first').addBack('img:first');

								_this.setupViewerContainer($(original_image).attr('src'), this.page);
							},
							error: function () {
								_this.setupViewerContainerError(this.page, url);
							}
						});
					}, _this.delay + (_this.delay !== 0 ? (pageN * _this.delay) : 0));
				} else {
					_this.setupViewerContainer(_this.viewerCustomImageList[pageN-1], pageN);
				}
			}

			//Auto-track chapter if enabled.
			$(window).on("load", function() {
				/** @namespace config.auto_track */
				if(config.options.auto_track) {
					_this.trackChapter();
				}
			});

			_this.postSetupViewer();
		});
	},
	setupViewerContainer : function(imgURL, pageN) {
		let _this = this;

		let image_container = $('<div/>', {class: 'read_img'}).append(
			//We want to completely recreate the image element to remove all additional attributes
			$('<img/>', {src: imgURL})
				.on('load', function() {
					_this.updatePagesLoaded(true);
				})
		).append(
			//Add page number
			$('<div/>', {class: 'pageNumber'}).append(
				$('<div/>', {class: 'number', text: pageN}))
		);

		//Replace the placeholder image_container with the real one
		$('#page-'+pageN).replaceWith(image_container);
	},
	setupViewerContainerError : function(pageN, url) {
		let _this = this;
		_this.updatePagesLoaded(false);

		let image_container = $('<div/>', {class: 'read_img', id: 'page-'+pageN}).append(
			$('<img/>', {style: 'cursor: pointer', src: GM_getResourceURL('reload')}).click(function() {
				$.ajax({
					url    : url,
					type   : 'GET',
					page   : pageN,
					// async: useASync,
					success: function (data) {
						let original_image = $(data.replace(_this.viewerRegex, '$1')).find('img:first').addBack('img:first');
						_this.setupViewerContainer($(original_image).attr('src'), this.page);
					},
					error: function () {
						alert('Failed to load image again. Something may be wrong with the site.');
						_this.setupViewerContainerError(this.page, url);
					}
				});
			})
		).append(
			//Add page number
			$('<div/>', {class: 'pageNumber'}).append(
				$('<div/>', {class: 'number', text: pageN}))
		);

		//Replace the placeholder image_container with the real one
		$('#page-'+pageN).replaceWith(image_container);
	},
	reloadPages : function() {
		let _this = this;

		$('#TrackerBarPages').html('Attempting to load pages...');
		//FIXME: This is a really lazy way of doing this...
		$('.read_img[id] img').each(function(i, v) {
			setTimeout(function() {
				$(v).click();
			}, _this.delay + (_this.delay !== 0 ? (i * _this.delay) : 0));
		})
	},
	updatePagesLoaded : function(loaded) {
		this.pagesLoadedAttempts += 1;
		if(loaded) {
			this.pagesLoaded += 1;
			$('#TrackerBarPages').text('Pages loaded: '+this.pagesLoaded+'/'+this.page_count);
		}

		if(this.pagesLoadedAttempts >= this.page_count) {
			//This is last page to load, check if everything loaded correctly.
			if(this.pagesLoaded >= this.page_count) {
				//Everything was loaded correctly, hide the page count div.
				setTimeout(function() {
					$('#TrackerBarPages').html('&nbsp;').hide("slow");
				}, 1500);
			} else {
				$('#TrackerBarPages')
					.html('') //remove everything from existing container
					.append($('<span/>', {text: 'ERROR: '+(this.page_count - this.pagesLoaded)+' pages failed to load | '}))
					.append($('<a/>', {href: '#', id: 'reloadPages'}).append(
						$('<i/>', {class: 'fa fa-refresh', 'aria-hidden': 'true'})
					));
			}
			console.log('higher than pc: '+this.pagesLoadedAttempts);
		} else {
			console.log('lower than pc: '+this.pagesLoadedAttempts);
		}
	},

	reportBug : function() {
		let bugText = prompt("Describe the bug.");
		if(bugText) {
			if(bugText !== '') {
				let params = {
					'api-key' : config['api-key'],
					'bug'     : {
						url  : location.href,
						text : 'APIKEY:'+config['api-key']+' ||| '+bugText
					}
				};

				$.post(main_site + '/ajax/userscript/report_bug', params, function () {
					alert('Bug successfully submitted');
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
			} else {
				alert('Bug text cannot be blank.');
			}
		}
	},

	favouriteChapter : function() {
		if(config['api-key']) {
			let params = {
				'api-key' : config['api-key'],
				'manga'   : {
					'site'    : this.site,

					//Both title and chapter can contain anything, as parsing is done on the backend.
					'title'   : this.title,
					'chapter' : this.chapter
				}
			};

			$.post(main_site + '/ajax/userscript/favourite', params, function (data, textStatus, jqXHR) {
				//TODO: We should really output this somewhere other than the topbar..
				$('#TrackerStatus').text(jqXHR.statusText);
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
		} else {
			alert('Tracker isn\'t setup! Go to trackr.moe/user/options to set things up.');
		}
	},

	/** Variables **/
	segments : window.location.pathname.split('/'),
	https    : location.protocol.slice(0, -1),

	//Used for tracking.
	site    : location.hostname.replace(/^(?:dev|test)\./, ''),
	title   : '',
	chapter : '',

	page_count : 0,

	//Used by everything for easy access
	chapter_url : '',
	title_url   : '',

	//Used for topbar.
	chapterListCurrent : '',
	chapterList        : {},

	//Used for custom viewer header (if requested)
	viewerChapterName      : '',
	viewerTitle            : '',
	viewerChapterURLFormat : '%pageN%', //%pageN% is replaced by the page number on load.
	//Used for viewer AJAX (if used)
	viewerRegex            : /^$/, // First img tag MUST be the chapter page
	viewerCustomImageList  : [], //This is is only used if useCustomImageList is true

	//Delay each page load by x ms when not using custom image list
	delay: 0,

	attemptingTrack     : false, //This is only changed by trackChapter
	pagesLoaded         : 0,
	pagesLoadedAttempts : 0
};
function extendSite(o) { return Object.assign({}, base_site, o); }
function generateChapterList(target, attrURL) {
	let chapterList = {};
	if(target instanceof jQuery) {
		$(target).each(function() {
			chapterList[$(this).attr(attrURL)] = $(this).text().trim();
		});
	} else {
		//TODO: Throw error
	}
	return chapterList;
}

let sites = {
	'mangafox.me' : extendSite({
		setObjVars : function () {
			this.segments    = window.location.pathname.replace(/^(.*\/)(?:[0-9]+\.html)?$/, '$1').split( '/' );

			this.title       = this.segments[2];
			this.chapter     = (!!this.segments[4] ? this.segments[3]+'/'+this.segments[4] : this.segments[3]);

			this.page_count  = $('#top_bar').find('.prev_page + div').text().trim().replace(/^[\s\S]*of ([0-9]+)$/, '$1');

			this.title_url   = 'http://mangafox.me/manga/'+this.title+'/';
			this.chapter_url = 'http://mangafox.me/manga/'+this.title+'/'+this.chapter+'/';

			this.chapterListCurrent = this.chapter_url;
			this.chapterList        = {}; //This is set via preSetupTopbar

			this.viewerTitle            = $('#series').find('> strong:last > a').text().slice(0, -6);
			this.viewerChapterURLFormat = this.chapter_url + '%pageN%'+'.html';
			this.viewerRegex            = /^[\s\S]*(<div class="read_img">[\s\S]*<\/div>)[\s\S]*<div id="MarketGid[\s\S]*$/;

			this.delay = 750;
		},
		stylize : function() {
			//This removes the old border/background. The viewer adds borders to the images now instead which looks better.
			$('#viewer').css({
				'background' : 'none',
				'border'     : '0'
			});

			let tool = $('#tool');
			//Remove page count from the header, since all pages are loaded at once now.
			tool.find('> #series > strong:eq(1)').remove();

			//Float title in the header to the right. This just looks nicer and is a bit easier to read.
			tool.find('> #series > strong:last').css('float', 'right');
		},
		preSetupTopBar : function(callback) {
			let _this = this;

			//The inline chapter list is cached. This causes new chapters to not properly show on the list. (Why the cache isn't reset when a new chapter is added is beyond me)
			//Because of this, we can't use the inline chapter list as a source, and instead we need to check the manga page.
			$.ajax({
				url: _this.title_url,
				beforeSend: function(xhr) {
					xhr.setRequestHeader("Cache-Control", "no-cache, no-store");
					xhr.setRequestHeader("Pragma", "no-cache");
				},
				cache: false,
				success: function(response) {
					response = response.replace(/^[\S\s]*(<div id="chapters"\s*>[\S\s]*)<div id="discussion" >[\S\s]*$/, '$1'); //Only grab the chapter list
					let div = $('<div/>').append($(response));

					$("#chapters > .chlist > li > div > a + * > a", div).reverseObj().each(function() {
						let chapterTitle     = $('+ span.title', this).text().trim(),
						    url              = $(this).attr('href').replace(/^(.*\/)(?:[0-9]+\.html)?$/, '$1'); //Remove trailing page number

						_this.chapterList[url] = url.replace(/^.*\/manga\/[^/]+\/(?:v(.*?)\/)?c(.*?)\/$/, 'Vol.$1 Ch.$2')
						                            .replace(/^Vol\. /, '') + (chapterTitle !== '' ? ': ' + chapterTitle : '');
					});

					callback();
				}
			});
		},
		postSetupTopBar : function() {
			$('#top_center_bar, #bottom_center_bar').remove();
			$('#tool').parent().find('> .gap').remove();
			$('#series').css('padding-top', '0');
		},
		preSetupViewer : function(callback) {
			$('#viewer').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div

			callback(false, false);
		}
	}),

	'www.mangahere.co' : extendSite({
		//MangaHere uses pretty much the same site format as MangaFox, with a few odd changes.
		setObjVars : function() {
			this.segments      = window.location.pathname.replace(/^(.*\/)(?:[0-9]+\.html)?$/, '$1').split( '/' );

			//FIXME: Is there a better way to do this? It just feels like an ugly way of setting vars.
			this.page_count    = $('.go_page:first > .right > select > option').length;
			this.title         = this.segments[2];
			this.chapter       = (!!this.segments[4] ? this.segments[3]+'/'+this.segments[4] : this.segments[3]);

			this.title_url   = 'http://www.mangahere.co/manga/'+this.title+'/';
			this.chapter_url = 'http://www.mangahere.co/manga/'+this.title+'/'+this.chapter+'/';

			this.chapterListCurrent = this.chapter_url;
			// this.chapterList        = {}; //This is set via preSetupTopbar

			this.viewerTitle            = $('.readpage_top > .title > h2').text().slice(0, -6);
			this.viewerChapterURLFormat = this.chapter_url + '%pageN%'+'.html';
			this.viewerRegex            = /^[\s\S]*<section class="read_img" id="viewer">[\s\S]*(<img src[\s\S]*\/>)[\s\S]*<\/section>[\s\S]*<section class="readpage_footer[\s\S]*$/;
		},
		stylize : function() {
			GM_addStyle(`
				.read_img { min-height: 0; }
				.readpage_top {margin-bottom: 5px;}
				.readpage_top .title h1, .readpage_top .title h2 {font-size: 15px;}
			`);

			//Remove banners
			$('.readpage_top > div[class^=advimg], .readpage_footer > div[class^=banner-]').remove();

			//Remove Tsukkomi thing
			$('.readpage_footer > .tsuk-control, #tsuk_container').remove();

			//Remove social bar.
			$('.plus_report').remove();

			$('#viewer').css({
				'background' : 'none',
				'border'     : '0'
			});

			//Format the chapter header
			let title = $('.readpage_top > .title');
			title.html(function(i, html) { return html.replace('</span> / <h2', '</span><h2'); });
			title.find('> span[class^=color]').remove();
			title.find('h2').addClass('right');
		},
		preSetupTopBar : function(callback) {
			let _this = this;

			//Much like MangaFox, the inline chapter list is cached so we need to grab the proper list via AJAX.
			$.ajax({
				url: _this.title_url,
				beforeSend: function(xhr) {
					xhr.setRequestHeader("Cache-Control", "no-cache, no-store");
					xhr.setRequestHeader("Pragma", "no-cache");
				},
				cache: false,
				success: function(response) {
					response = response.replace(/^[\S\s]*(<section id="main" class="main clearfix">[\S\s]*(?=<\/section>)<\/section>)[\S\s]*$/, '$1'); //Only grab the chapter list
					let div = $('<div/>').append($(response).find('.detail_list > ul:first'));

					$('li > span.left > a', div).reverseObj().each(function() {
						let chapterTitle     = $(this).parent().clone().children().remove().end().text().trim(),
						    url              = $(this).attr('href').replace(/^(.*\/)(?:[0-9]+\.html)?$/, '$1'); //Remove trailing page number

						_this.chapterList[url] = url.replace(/^.*\/manga\/[^/]+\/(?:v(.*?)\/)?c(.*?)\/$/, 'Vol.$1 Ch.$2')
						                            .replace(/^Vol\. /, '') + (chapterTitle !== '' ? ': ' + chapterTitle : '');
					});

					callback();
				}
			});
		},
		postSetupTopBar : function() {
			$('.go_page:first').remove();
		},
		preSetupViewer : function(callback) {
			$('#viewer').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div

			callback(true);
		}
	}),

	'bato.to' : extendSite({
		preInit : function(callback) {
			//Bato.to loads the image page AFTER page load via AJAX. We need to wait for this to load.
			let dfd = $.Deferred();
			let checkSelector = setInterval(function () {
				if ($('#reader').text() !== 'Loading...') {
					//AJAX has loaded, resolve deferred.
					dfd.resolve();
					clearInterval(checkSelector);
				} else {
					console.log("forever loading");
				}
			}, 1000);
			dfd.done(function () {
				callback();
			});
		},
		setObjVars : function() {
			let chapterNParts   = $('select[name=chapter_select]:first > option:selected').text().trim().match(/^(?:Vol\.(\S+) )?(?:Ch.([^\s:]+)(?:\s?-\s?([0-9]+))?):?.*/);
			let reader          = $('#reader');

			this.page_count     = $('#page_select:first').find('> option').length;
			let web_toon_check  = $('a[href$=_1_t]');
			this.is_web_toon    = ($(web_toon_check).length ? ($(web_toon_check).text() === 'Want to see this chapter per page instead?' ? 1 : 2) : 0); //0 = no, 1 = yes & long strip, 2 = yes & chapter per page

			this.chapter_hash   = location.hash.substr(1).split('_')[0];
			this.chapter_number = (chapterNParts[1] ? 'v'+chapterNParts[1]+'/' : '') + 'c'+chapterNParts[2] + (chapterNParts[3] ? '-'+chapterNParts[3] : '');

			this.title_url      = reader.find('a[href*="/comic/"]:first').attr('href');
			this.manga_language = $('select[name=group_select]:first > option:selected').text().trim().replace(/.* - ([\S]+)$/, '$1');

			this.title          = this.title_url.replace(/.*r([0-9]+)$/, '$1') + ':--:' + this.manga_language;
			this.chapter        = this.chapter_hash + ':--:' + this.chapter_number;
			this.chapter_url    = this.https+'://bato.to/reader#'+this.chapter_hash;

			let chapterListOptions  = $('select[name=chapter_select]:first > option');
			this.chapterListCurrent = this.chapter_url;
			if(this.https === 'https') {
				chapterListOptions.each(function(i, e) {
					$(e).val($(e).val().replace(/^http/, 'https'));
				});
			}
			this.chapterList            = generateChapterList(chapterListOptions.reverseObj(), 'value');

			this.viewerChapterName      = this.chapter_number;
			this.viewerTitle            = document.title.replace(/ - (?:vol|ch) [0-9]+.*/, '').replace(/&#(\d{1,4});/, function(fullStr, code) { return String.fromCharCode(code); });
			this.viewerChapterURLFormat = this.https+'://bato.to/areader?id='+this.chapter_hash+'&p=' + '%pageN%';
			this.viewerRegex            = /^[\s\S]+(<img id="comic_page".+?(?=>)>)[\s\S]+$/;
			this.viewerCustomImageList  = reader.find('#read_settings + div + div img').map(function(i, e) {
				return $(e).attr('src');
			});
		},
		stylize : function() {
			//Nothing?
		},
		preSetupViewer : function(callback) {
			let reader = $('#reader');

			this.viewerCustomImageList = reader.find('#read_settings + div + div img').map(function(i, e) {
				return $(e).attr('src');
			});

			reader.replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div

			if(this.is_web_toon !== 1) {
				callback();
			} else {
				//Bato.to has an option for webtoons to show all chapters on a single page (with a single ajax), we need to do stuff differently if this happens.
				this.page_count = this.viewerCustomImageList.length;
				callback(false, true);
			}
		}
	}),

	'dynasty-scans.com' : extendSite({
		setObjVars : function() {
			let title_ele = $('#chapter-title').find('> b > a');

			this.is_one_shot = !title_ele.length;

			if(!this.is_one_shot) {
				this.title_url   = title_ele.attr('href').replace(/.*\/(.*)$/, '$1');
				this.chapter_url = location.pathname.split(this.title_url + '_').pop(); //There is really no other valid way to get the chapter_url :|
			} else {
				this.title_url   = location.pathname.substr(10);
				this.chapter_url = 'oneshot'; //This is labeled oneshot so it's properly handled in the backend.
			}

			this.title   = this.title_url + ':--:' + (+this.is_one_shot);
			this.chapter = this.chapter_url;

			this.chapterListCurrent = location.pathname;
			this.chapterList = {}; //This is set in preSetupTopBar

			this.viewerTitle = $('#chapter-title > b > a, #chapter-title > b').get(0).innerText; //FIXME: This doesn't prepend series names (if exists)
			this.viewerCustomImageList = $('script:contains("/system/releases/")').html().match(/"(\/system[^"]+)"/g).map(function(e) {
				return e.replace(/^"|"$/g, '');
			});
			this.page_count = this.viewerCustomImageList.length;
		},
		stylize : function() {
			//These buttons aren't needed since we have our own viewer.
			$('#chapter-actions > div > .btn-group:last, #download_page').remove();
			$('#reader').addClass('noresize');

			//Topbar covers a bunch of nav buttons.
			GM_addStyle(`
				#content > .navbar > .navbar-inner { padding-top: 42px; }
			`);
		},
		preSetupTopBar : function(callback) {
			let _this = this;

			if(!_this.is_one_shot) {
				//Sadly, we don't have any form of inline chapterlist. We need to AJAX the title page for this one.
				$.ajax({
					url: 'https://dynasty-scans.com/series/'+_this.title_url,
					beforeSend: function(xhr) {
						xhr.setRequestHeader("Cache-Control", "no-cache, no-store");
						xhr.setRequestHeader("Pragma", "no-cache");
					},
					cache: false,
					success: function(response) {
						response = response.replace(/^[\S\s]*(<dl class="chapter-list">[\S\s]*<\/dl>)[\S\s]*$/, '$1');
						let div = $('<div/>').append($(response));

						_this.chapterList = generateChapterList($(".chapter-list > dd > a.name", div), 'href');

						callback();
					}
				});
			} else {
				_this.chapterList[location.pathname] = 'Oneshot';

				callback();
			}
		},
		preSetupViewer : function(callback) {
			$('#reader').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div

			callback(true, true);
		}
	}),

	'www.mangapanda.com' : extendSite({
		preInit : function(callback) {
			//MangaPanda is tricky. For whatever stupid reason, it decided to not use a URL format which actually seperates its manga URLs from every other page on the site.
			//I've went and already filtered a bunch of URLs out in the include regex, but since it may not match everything, we have to do an additional check here.
			if($('#topchapter, #chapterMenu, #bottomchapter').length === 3) {
				//MangaPanda is another site which uses the MangaFox layout. Is this just another thing like FoolSlide?

				callback();
			}
		},
		setObjVars : function() {
			this.page_count     = parseInt($('#topchapter').find('#selectpage select > option:last').text());
			this.title          = this.segments[1];
			this.chapter        = this.segments[2];

			this.chapterListCurrent = '/'+this.title+'/'+this.chapter;
			// this.chapterList = {}, //This is set via preSetupTopBar.

			this.title_url      = 'http://www.mangapanda.com/'+this.title+'/';
			this.chapter_url    = 'http://www.mangapanda.com/'+this.title+'/'+this.chapter+'/';

			// this.viewerChapterName      = '';
			this.viewerTitle            = $('#mangainfo').find('> div[style*=float] > h2').text().slice(0, -6);
			this.viewerChapterURLFormat = this.chapter_url + '%pageN%';
			this.viewerRegex            = /^[\s\S]+(<img id="img".+?(?=>)>)[\s\S]+$/;
		},
		stylize : function() {
			let mangaInfo = $('#mangainfo').find('> div');
			//Remove page count from the header, since all pages are loaded at once now.
			mangaInfo.find(':first .c1').remove();

			//Float title in the header to the right. This just looks nicer and is a bit easier to read.
			mangaInfo.find('+ div:not(.clear)').css('float', 'right');
		},
		preSetupTopBar : function(callback) {
			let _this = this;

			//MangaPanda is tricky here. The chapter list is loaded via AJAX, and not a <script> tag. As far as I can tell, we can't watch for this to load without watching the actual element.
			let attempts = 0;
			let checkExist = setInterval(function() {
				let option     = $('#topchapter').find('> #selectmanga > select > option');
				if(option.length) {
					clearInterval(checkExist);

					_this.chapterList = generateChapterList(option, 'value');
					callback();
				}

				if(attempts === 25) {
					alert('ERROR: Having issues loading the chapter list.\nTry reloading the page.');
					clearInterval(checkExist);
				}
				attempts++;
			}, 500);
		},
		postSetupTopBar : function() {
			//Remove MangaFox's chapter navigation since we now have our own. Also remove leftover whitespace.
			$('#topchapter > #mangainfo ~ div, #bottomchapter > #mangainfo ~ div').remove();
		},
		preSetupViewer : function(callback) {
			$('.episode-table').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div

			callback(true);
		}
	}),

	'readms.net' : extendSite({ //MangaStream
		setObjVars : function() {
			this.page_count  = parseInt($('.controls ul:last > li:last').text().replace(/[^0-9]/g, ''));
			this.title       = this.segments[2];
			this.chapter     = this.segments[3]+'/'+this.segments[4];

			this.title_url   = this.https+'://readms.net/manga/'+this.title;
			this.chapter_url =  this.https+'://readms.net/r/'+this.title+'/'+this.chapter;

			// this.chapterList     = {}; //This is set via preSetupTopBar.
			this.chapterListCurrent = (this.chapter_url+'/1').replace('https://', 'http://'); //FIXME: MS only seems to use http urls, even if you are on https

			this.viewerChapterName      = 'c'+this.chapter.split('/')[0];
			this.viewerTitle            = $('.btn-reader-chapter > a > span:first').text();
			this.viewerChapterURLFormat = this.chapter_url + '/' + '%pageN%';
			this.viewerRegex            = /^[\s\S]+(<div class="page">.+(?:.+)?(?=<\/div>)<\/div>)[\s\S]+$/;
		},
		stylize : function() {
			GM_addStyle(`
				.page { margin-right: 0 !important; }
				#reader-nav { margin-bottom: 0; }
			`);

			$('.page-wrap > #reader-sky').remove(); //Ad block
		},
		preSetupTopBar : function(callback) {
			let _this = this;

			$.ajax({
				url: _this.title_url,
				beforeSend: function(xhr) {
					xhr.setRequestHeader("Cache-Control", "no-cache, no-store");
					xhr.setRequestHeader("Pragma", "no-cache");
				},
				cache: false,
				success: function(response) {
					let table = $(response.replace(/^[\S\s]*(<table[\S\s]*<\/table>)[\S\s]*$/, '$1').replace(/\?t=[0-9]+&(amp;)?f=[0-9]+&(amp;)?e=[0-9]+/g, ''));

					_this.chapterList = generateChapterList($('tr:not(:first) a', table).reverseObj(), 'href');

					callback();
				}
			});
		},
		postSetupTopBar : function() {
			$('.subnav').remove(); //Remove topbar, since we have our own
		},
		preSetupViewer : function(callback) {
			$('.page').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div

			callback();
		}
	}),

	'www.webtoons.com' : extendSite({
		setObjVars : function() {
			let title_id     = window.location.search.match(/title_no=([0-9]+)/)[1],
			    chapter_id   = window.location.search.match(/episode_no=([0-9]+)/)[1];
			this.title       = title_id   + ':--:' + this.segments[1] + ':--:' + this.segments[3] + ':--:' + this.segments[2];
			this.chapter     = chapter_id + ':--:' + this.segments[4];

			this.title_url   = 'http://www.webtoons.com/'+this.segments[1]+'/'+this.segments[2]+'/'+this.segments[3]+'/list?title_no='+title_id;
			this.chapter_url = 'http://www.webtoons.com/'+this.segments[1]+'/'+this.segments[2]+'/'+this.segments[3]+'/'+this.segments[4]+'/viewer?title_no='+title_id+'&episode_no='+chapter_id;

			this.chapterList        = generateChapterList($('.episode_lst > .episode_cont > ul > li a'), 'href');
			this.chapterListCurrent = this.chapter_url;

			this.viewerTitle = $('.subj').text();
		}
	}),

	'kissmanga.com' : extendSite({
		preInit : function(callback) {
			//Kissmanga has bot protection, sometimes we need to wait for the site to load.
			if($('.cf-browser-verification').length === 0) {
				//Kissmanga has a built-in method to show all pages on the same page. Check if the cookie is correct, otherwise change and refresh.
				if(getCookie('vns_readType1') !== '1') {
					callback();
				} else {
					document.cookie = 'vns_readType1=0; expires=Fri, 6 Sep 2069 00:00:00 UTC; path=/;';
					location.reload();
				}
			}
		},
		setObjVars : function() {
			let chapter_id   = document.location.search.match(/id=([0-9]+)/)[1];

			this.title       = this.segments[2];
			this.chapter     = this.segments[3] + ':--:' + chapter_id;

			this.title_url   = 'http://kissmanga.com/Manga/'+this.title;
			this.chapter_url = this.title_url+'/'+this.segments[3]+'?id='+chapter_id;

			this.chapterList        = generateChapterList($('.selectChapter:first > option'), 'value');
			this.chapterListCurrent = decodeURI(this.segments[3])+'?id='+chapter_id;


			this.viewerChapterName     = $('.selectChapter:first > option:selected').text().trim();
			this.viewerTitle           = $('title').text().trim().split("\n")[1];
			this.viewerCustomImageList = $('#headnav').find('+ div + script').html().match(/"(http:\/\/[^"]+)"/g).map(function(e) {
				return e.replace(/^"|"$/g, '');
			});
			this.page_count = this.viewerCustomImageList.length;
		},
		postSetupTopBar : function() {
			let image = $('#divImage');

			//Remove extra unneeded elements.
			image.prevAll().remove();
			image.nextAll().remove();
		},
		preSetupViewer : function(callback) {
			$('#divImage').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div

			this.page_count = this.viewerCustomImageList.length;
			callback(false, true);
		},

		//FIXME: KissManga banned us. SEE: https://github.com/DakuTree/manga-tracker/issues/64
		trackChapter : function() {
			alert("KissManga decided to IP ban our server, which means tracking is no longer possible.\nThis may be fixed at a later date, sorry for the inconvenience.");
		}
	}),

	'reader.kireicake.com' : extendSite({
		setObjVars : function() {
			this.title       = this.segments[2];
			this.chapter     = this.segments[3] + '/' + this.segments[4] + '/' + this.segments[5] + (this.segments[6] && this.segments[6] !== 'page' ? '/' + this.segments[6] : '');

			this.title_url   = 'https://reader.kireicake.com/series/'+this.title;
			this.chapter_url = 'https://reader.kireicake.com/read/'+this.title+'/'+this.chapter;

			this.chapterList        = generateChapterList($('.topbar_left > .tbtitle:eq(2) > ul > li > a').reverseObj(), 'href');
			this.chapterListCurrent = this.chapter_url+'/';

			// this.viewerChapterName     = $('.selectChapter:first > option:selected').text().trim();
			this.viewerTitle           = $('.topbar_left > .dropdown_parent > .text a').text();
			this.viewerCustomImageList = $('#content').find('> script:first').html().match(/(https:\\\/\\\/[^"]+)/g).filter(function(value, index, self) {
				return self.indexOf(value) === index;
			}).map(function(e) {
				return e.replace(/\\/g, '');
			});
			this.page_count = this.viewerCustomImageList.length;
		},
		postSetupTopBar : function() {
			$('.topbar_left > .tbtitle:eq(2)').remove();
			$('.topbar_right').remove();
		},
		preSetupViewer : function(callback) {
			$('#page').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div
			callback(true, true);
		}
	}),

	'gameofscanlation.moe' : extendSite({
		setObjVars : function() {
			//GoS is a bit weird. The title URL has two variations, one with the ID and one without.
			//The ID one works only on the title page, and the no ID one works on the chapter page.
			this.title       = $('#readerHeader').find('> .thelefted a:last').attr('href').split('/')[1];
			this.chapter     = this.segments[3];

			if(this.title.indexOf('.') !== -1) {
				this.title_url   = 'https://gameofscanlation.moe/forums/'+this.title+'/';
			} else {
				this.title_url   = 'https://gameofscanlation.moe/projects/'+this.title+'/';
			}
			this.title_url   = 'https://gameofscanlation.moe/forums/'+this.title+'/';
			this.chapter_url = 'https://gameofscanlation.moe/projects/'+this.title.replace(/\.[0-9]+$/, '')+'/'+this.chapter+'/';

			this.chapterList        = generateChapterList($('select[name=chapter_list] > option'), 'data-chapterurl');
			this.chapterListCurrent = this.chapter_url.substr(29);
		},
		postSetupTopBar : function() {
			$('.samBannerUnit').remove(); //Remove huge header banner.
		}
	}),

	'mngcow.co' : extendSite({
		setObjVars : function() {
			let _this = this;

			this.title       = this.segments[1];
			this.chapter     = this.segments[2];

			this.title_url   = 'http://mngcow.co/'+this.title+'/';
			this.chapter_url = this.title_url+this.chapter+'/';

			let pageNav = $('#pageNav');
			pageNav.find('select:first > option').each(function(i, e) {
				$(e).val(_this.title_url + $(e).val() + '/');
			});
			this.chapterList        = generateChapterList(pageNav.find('select:first > option').reverseObj(), 'value');
			this.chapterListCurrent = this.chapter_url;

			// this.viewerChapterName     = $('.selectChapter:first > option:selected').text().trim();
			// this.viewerTitle           = $('.topbar_left > .dropdown_parent > .text a').text();
			this.viewerCustomImageList = $('script:contains("/wp-content/manga/")').html().match(/(http:\/\/mngcow\.co\/wp-content\/manga\/[^"]+)/g).filter(function(value, index, self) {
				return self.indexOf(value) === index;
			});
			this.page_count = this.viewerCustomImageList.length;
		},
		preSetupViewer : function(callback) {
			$('#longWrap').remove();
			$('.nav_typ, .nav_pag').remove();

			$('#singleWrap').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div
			callback(true, true);
		}
	}),

	'read.egscans.com' : extendSite({
		setObjVars : function() {
			let _this = this;

			this.title       = this.segments[1];
			this.chapter     = this.segments[2] || 'Chapter_001';

			this.title_url   = 'http://read.egscans.com/'+this.title+'/';
			this.chapter_url = this.title_url+this.chapter+'/';

			let option = $('select[name=chapter] > option');
			option.each(function(i, e) {
				$(e).val(_this.title_url + $(e).val() + '/');
			});
			this.chapterList        = generateChapterList(option, 'value');
			this.chapterListCurrent = this.chapter_url;

			$('script:contains("img_url.push(")').html().match(/url\.push\('(.*?')/g).filter(function(value, index, self) {
				return self.indexOf(value) === index;
			}).map(function(v) { return v.substr(10, v.length-11)
			});

			this.page_count = this.viewerCustomImageList.length;
		},
	}),

	'reader.seaotterscans.com' : extendSite({
		setObjVars : function() {
			this.title       = this.segments[2];
			this.chapter     = this.segments[3] + '/' + this.segments[4] + '/' + this.segments[5] + (this.segments[6] && this.segments[6] !== 'page' ? '/' + this.segments[6] : '');

			this.title_url   = this.https+'://reader.seaotterscans.com/series/'+this.title;
			this.chapter_url = this.https+'://reader.seaotterscans.com/read/'+this.title+'/'+this.chapter;

			this.chapterList        = generateChapterList($('.topbar_left > .tbtitle:eq(2) > ul > li > a').reverseObj(), 'href');
			//FIXME: The chapterList isn't properly ordered for series that have chapters in and outside volumes. - https://reader.seaotterscans.com/series/sss/
			this.chapterListCurrent = this.chapter_url+'/';

			// this.viewerChapterName     = $('.selectChapter:first > option:selected').text().trim();
			this.viewerTitle           = $('.topbar_left > .dropdown_parent > .text a').text();
			this.viewerCustomImageList = $('#content').find('> script:first').html().match(/(https?:\\\/\\\/[^"]+)/g).filter(function(value, index, self) {
				return self.indexOf(value) === index;
			}).map(function(e) {
				return e.replace(/\\/g, '');
			});
			this.page_count = this.viewerCustomImageList.length;
		},
		postSetupTopBar : function() {
			$('.topbar_left > .tbtitle:eq(2)').remove();
			$('.topbar_right').remove();
		},
		preSetupViewer : function(callback) {
			$('#page').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div
			callback(true, true);
		}
	}),

	'helveticascans.com' : extendSite({
		preInit : function(callback) {
			if(location.pathname.substr(0, 7) === 'reader') {
				//If old URL, redirect to new one.
				location.pathname = location.pathname.replace(/^\/reader/, '/r')
			} else {
				callback();
			}
		},
		setObjVars : function() {
			this.title       = this.segments[3];
			this.chapter     = this.segments[4] + '/' + this.segments[5] + '/' + this.segments[6] + (this.segments[7] && this.segments[7] !== 'page' ? '/' + this.segments[7] : '');

			this.title_url   = this.https+'://helveticascans.com/r/series/'+this.title;
			this.chapter_url = this.https+'://helveticascans.com/r/read/'+this.title+'/'+this.chapter;

			this.chapterList        = generateChapterList($('.topbar_left > .tbtitle:eq(2) > ul > li > a').reverseObj(), 'href');
			//FIXME: The chapterList isn't properly ordered for series that have chapters in and outside volumes. - https://reader.seaotterscans.com/series/sss/
			this.chapterListCurrent = this.chapter_url+'/';

			// this.viewerChapterName     = $('.selectChapter:first > option:selected').text().trim();
			this.viewerTitle           = $('.topbar_left > .dropdown_parent > .text a').text();
			this.viewerCustomImageList = $('#content').find('> script:first').html().match(/(https?:\\\/\\\/[^"]+)/g).filter(function(value, index, self) {
				return self.indexOf(value) === index;
			}).map(function(e) {
				return e.replace(/\\/g, '');
			});
			this.page_count = this.viewerCustomImageList.length;
		},
		postSetupTopBar : function() {
			$('.topbar_left > .tbtitle:eq(2)').remove();
			$('.topbar_right').remove();
			$('.reader_top_panel').remove();
		},
		preSetupViewer : function(callback) {
			$('#page').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div
			callback(true, true);
		}
	}),

	'reader.sensescans.com' : extendSite({
		setObjVars : function() {
			this.title       = this.segments[2];
			this.chapter     = this.segments[3] + '/' + this.segments[4] + '/' + this.segments[5] + (this.segments[6] && this.segments[6] !== 'page' ? '/' + this.segments[6] : '');

			this.title_url   = this.https+'://reader.sensescans.com/series/'+this.title;
			this.chapter_url = this.https+'://reader.sensescans.com/read/'+this.title+'/'+this.chapter;

			this.chapterList        = generateChapterList($('.topbar_left > .tbtitle:eq(2) > ul > li > a').reverseObj(), 'href');
			//FIXME: The chapterList isn't properly ordered for series that have chapters in and outside volumes. - https://reader.seaotterscans.com/series/sss/
			this.chapterListCurrent = this.chapter_url+'/';

			// this.viewerChapterName     = $('.selectChapter:first > option:selected').text().trim();
			this.viewerTitle           = $('.topbar_left > .dropdown_parent > .text a').text();
			this.viewerCustomImageList = $('#content').find('> script:first').html().match(/(https?:\\\/\\\/[^"]+)/g).filter(function(value, index, self) {
				return self.indexOf(value) === index;
			}).map(function(e) {
				return e.replace(/\\/g, '');
			});
			this.page_count = this.viewerCustomImageList.length;
		},
		postSetupTopBar : function() {
			$('.topbar_left > .tbtitle:eq(2)').remove();
			$('.topbar_right').remove();
		},
		preSetupViewer : function(callback) {
			$('#page').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div
			callback(true, true);
		}
	}),

	'jaiminisbox.com' : extendSite({
		setObjVars : function() {
			this.title       = this.segments[3];
			this.chapter     = this.segments[4] + '/' + this.segments[5] + '/' + this.segments[6] + (this.segments[7] && this.segments[7] !== 'page' ? '/' + this.segments[7] : '');

			this.title_url   = this.https+'://jaiminisbox.com/reader/series/'+this.title;
			this.chapter_url = this.https+'://jaiminisbox.com/reader/read/'+this.title+'/'+this.chapter;

			this.chapterList        = generateChapterList($('.topbar_left > .tbtitle:eq(2) > ul > li > a').reverseObj(), 'href');
			//FIXME: The chapterList isn't properly ordered for series that have chapters in and outside volumes. - https://reader.seaotterscans.com/series/sss/
			console.log(this.chapter_url+'/');
			this.chapterListCurrent = this.chapter_url+'/';

			// this.viewerChapterName     = $('.selectChapter:first > option:selected').text().trim();
			this.viewerTitle           = $('.topbar_left > .dropdown_parent > .text a').text();
			this.viewerCustomImageList = $('#content').find('> script:first').html().match(/(https?:\\\/\\\/[^"]+)/g).filter(function(value, index, self) {
				return self.indexOf(value) === index;
			}).map(function(e) {
				return e.replace(/\\/g, '');
			});
			this.page_count = this.viewerCustomImageList.length;
		   },
		   postSetupTopBar : function() {
			$('.topbar_left > .tbtitle:eq(2)').remove();
			$('.topbar_right').remove();
		},
		preSetupViewer : function(callback) {
			$('#page').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div
			callback(true, true);
		}
	}),

	//Doki Fansubs
	'kobato.hologfx.com' : extendSite({
		setObjVars : function() {
			this.title       = this.segments[3];
			this.chapter     = this.segments[4] + '/' + this.segments[5] + '/' + this.segments[6] + (this.segments[7] && this.segments[7] !== 'page' ? '/' + this.segments[7] : '');

			this.title_url   = this.https+'://kobato.hologfx.com/reader/series/'+this.title;
			this.chapter_url = this.https+'://kobato.hologfx.com/reader/read/'+this.title+'/'+this.chapter;

			this.chapterList        = generateChapterList($('.topbar_left > .tbtitle:eq(2) > ul > li > a').reverseObj(), 'href');
			//FIXME: The chapterList isn't properly ordered for series that have chapters in and outside volumes. - https://reader.seaotterscans.com/series/sss/
			this.chapterListCurrent = this.chapter_url+'/';

			// this.viewerChapterName     = $('.selectChapter:first > option:selected').text().trim();
			this.viewerTitle           = $('.topbar_left > .dropdown_parent > .text a').text();
			this.viewerCustomImageList = $('#content').find('> script:first').html().match(/(https?:\\\/\\\/[^"]+)/g).filter(function(value, index, self) {
				return self.indexOf(value) === index;
			}).map(function(e) {
				return e.replace(/\\/g, '');
			});
			this.page_count = this.viewerCustomImageList.length;
		},
		postSetupTopBar : function() {
			$('.topbar_left > .tbtitle:eq(2)').remove();
			$('.topbar_right').remove();
		},
		preSetupViewer : function(callback) {
			$('#page').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div
			callback(true, true);
		}
	}),

	'www.demonicscans.com' : extendSite({
		setObjVars : function() {
			this.title       = this.segments[3];
			this.chapter     = this.segments[4] + '/' + this.segments[5] + '/' + this.segments[6] + (this.segments[7] && this.segments[7] !== 'page' ? '/' + this.segments[7] : '');

			this.title_url   = this.https+'://www.demonicscans.com/FoOlSlide/series/'+this.title;
			this.chapter_url = this.https+'://www.demonicscans.com/FoOlSlide/read/'+this.title+'/'+this.chapter;

			this.chapterList        = generateChapterList($('.topbar_left > .tbtitle:eq(2) > ul > li > a').reverseObj(), 'href');
			//FIXME: The chapterList isn't properly ordered for series that have chapters in and outside volumes. - https://reader.seaotterscans.com/series/sss/
			this.chapterListCurrent = this.chapter_url+'/';

			// this.viewerChapterName     = $('.selectChapter:first > option:selected').text().trim();
			this.viewerTitle           = $('.topbar_left > .dropdown_parent > .text a').text();
			this.viewerCustomImageList = $('#content').find('> script:first').html().match(/(https?:\\\/\\\/[^"]+)/g).filter(function(value, index, self) {
				return self.indexOf(value) === index;
			}).map(function(e) {
				return e.replace(/\\/g, '');
			});
			this.page_count = this.viewerCustomImageList.length;
		},
		postSetupTopBar : function() {
			$('.topbar_left > .tbtitle:eq(2)').remove();
			$('.topbar_right').remove();
		},
		preSetupViewer : function(callback) {
			$('#page').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div
			callback(true, true);
		}
	}),


	'reader.deathtollscans.net' : extendSite({
		setObjVars : function() {
			this.title       = this.segments[2];
			this.chapter     = this.segments[3] + '/' + this.segments[4] + '/' + this.segments[5] + (this.segments[6] && this.segments[6] !== 'page' ? '/' + this.segments[6] : '');

			this.title_url   = this.https+'://reader.deathtollscans.net/series/'+this.title;
			this.chapter_url = this.https+'://reader.deathtollscans.net/read/'+this.title+'/'+this.chapter;

			this.chapterList        = generateChapterList($('.topbar_left > .tbtitle:eq(2) > ul > li > a').reverseObj(), 'href');
			//FIXME: The chapterList isn't properly ordered for series that have chapters in and outside volumes. - https://reader.seaotterscans.com/series/sss/
			this.chapterListCurrent = this.chapter_url+'/';

			// this.viewerChapterName     = $('.selectChapter:first > option:selected').text().trim();
			this.viewerTitle           = $('.topbar_left > .dropdown_parent > .text a').text();
			this.viewerCustomImageList = $('#content').find('> script:first').html().match(/(https?:\\\/\\\/[^"]+)/g).filter(function(value, index, self) {
				return self.indexOf(value) === index;
			}).map(function(e) {
				return e.replace(/\\/g, '');
			});
			this.page_count = this.viewerCustomImageList.length;
		},
		postSetupTopBar : function() {
			$('.topbar_left > .tbtitle:eq(2)').remove();
			$('.topbar_right').remove();
		},
		preSetupViewer : function(callback) {
			$('#page').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div
			callback(true, true);
		}
	}),

	//Tracking site
	//FIXME: We <probably> shouldn't have this here, but whatever.
	'trackr.moe' : extendSite({
		init : function() {
			let _this = this;

			switch(location.pathname) {
				case '/':
					//Dashboard / Front Page
					if($('#page[data-page=dashboard]').length) {
						//TODO: Is there a better way to do this?
						$('.update-read').click(function() {
							let row             = $(this).closest('tr'),
							    chapter_id      = $(row).attr('data-id'),
							    current_chapter = $(row).find('.current'),
							    latest_chapter  = $(row).find('.latest');

							//get mal_sync option
							switch(mal_sync) {
								case 'disabled':
									//do nothing
									break;

								case 'csrf':
									let tag_list   = $(row).find('.tag-list').text();
									let mal_id_arr = tag_list.match(/^(?:.*?,)?(mal:[0-9]+)(?:,.*?)?$/) || [];

									if(mal_id_arr.length > 0) {
										let mal_id = parseInt(mal_id_arr[1].split(':')[1]);
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
					}
					break;

				case '/user/options':
					/* TODO:
					 Stop generating HTML here, move entirely to PHP, but disable any user input unless enabled via userscript.
					 If userscript IS loaded, then insert data.
					 Seperate API key from general options. Always set API config when generate is clicked.
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

							GM_setValue('config', JSON.stringify(config));
							$('#form-feedback').text('Settings saved.').show().delay(4000).fadeOut(1000);
						} else {
							$('#form-feedback').text('API Key needs to be generated before options can be set.').show().delay(4000).fadeOut(1000);
						}

						e.preventDefault();
					});

					$('#api-key').text(config['api-key'] || "not set");
					$('#api-key-div').on('click', '#generate-api-key', function() {
						$.getJSON(main_site + '/ajax/get_apikey', function(json) {
							if(json['api-key']) {
								$('#api-key').text(json['api-key']);

								if(location.hostname === 'dev.trackr.moe') {
									config['api-key-dev'] = json['api-key'];
								} else {
									config['api-key']     = json['api-key'];
								}
								GM_setValue('config', JSON.stringify(config));
							} else {
								alert('ERROR: Something went wrong!\nJSON missing API key?');
							}
						}).fail(function(jqXHR, textStatus, errorThrown) {
							switch(jqXHR.status) {
								case 400:
									alert('ERROR: Not logged in?');
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

					break;
			}
		},
		enableForm : function(form) {
			$('#userscript-check').remove();
			$(form).find('fieldset').removeAttr('disabled');
			$(form).find('input[type=submit]').removeAttr('onclick');
		}
	})
};

/********************** SCRIPT *********************/
const main_site = 'https://trackr.moe';
let   config    = JSON.parse(GM_getValue('config') || '{}');
console.log(config); //This is useful for debugging.

const hostname = location.hostname.replace(/^(?:dev)\./, '');
if(!$.isEmptyObject(config) || hostname === 'trackr.moe') {
	//Config exists OR site is trackr.moe.
	if(main_site === 'https://dev.trackr.moe' && hostname !== 'trackr.moe') config['api-key'] = config['api-key-dev']; //Use dev API-key if using dev site
	if(!config.options) config.options = {}; //We can't use the 'in' operator on this if options doesn't exist.

	//NOTE: Although we load the userscript at document-start, we can't actually start poking the DOM of "most" sites until it's actually ready.
	if(sites[hostname]) {
		$(function () {
			sites[hostname].init();
		});
	}
} else {
	alert('Tracker isn\'t setup! Go to trackr.moe/user/options to set things up.');
}
