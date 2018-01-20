// ==UserScript==
// @name         Manga Tracker
// @namespace    https://github.com/DakuTree/userscripts
// @author       Daku (admin@codeanimu.net)
// @description  A cross-site manga tracker.
// @homepageURL  https://trackr.moe
// @supportURL   https://github.com/DakuTree/manga-tracker/issues
// @icon         https://trackr.moe/favicon.production.png
// @include      /^https:\/\/(?:(?:dev|test)\.)?trackr\.moe(\/.*$|$)/
// @include      /^https?:\/\/mangafox\.(?:me|la)\/manga\/.+\/(?:.*\/)?.*\/.*$/
// @include      /^https?:\/\/(?:www\.)?mangahere\.c[o|c]\/manga\/.+\/.*\/?.*\/.*$/
// @include      /^https?:\/\/bato\.to\/reader.*$/
// @include      /^https:/\/dynasty-scans\.com\/chapters\/.+$/
// @include      /^http:\/\/www\.mangapanda\.com\/(?!(?:search|privacy|latest|alphabetical|popular|random)).+\/.+$/
// @include      /^https?:\/\/readms\.net\/r\/.+\/.+\/[0-9]+(?:\/[0-9]+)?(?:\?.+)?$/
// @include      /^https?:\/\/mangastream\.com\/r(ead)?\/.+\/.+\/[0-9]+(?:\/[0-9]+)?(?:\?.+)?$/
// @include      /^http:\/\/www\.webtoons\.com\/(?:en|zh-hant|zh-hans|th|id)\/[a-z0-9A-Z-_]+\/[a-z0-9A-Z-_]+\/[a-z0-9A-Z-_]+\/viewer\?title_no=[0-9]+&episode_no=[0-9]+$/
// @include      /^http:\/\/kissmanga\.com\/Manga\/[a-zA-Z0-9-_]+\/[a-zA-Z0-9-_%]+\?id=[0-9]+$/
// @include      /^https?:\/\/reader\.kireicake\.com\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^https?:\/\/reader\.whiteoutscans\.com\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^https?:\/\/reader\.seaotterscans\.com\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^https?:\/\/(reader\.)?sensescans\.com\/(reader\/)?read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^https?:\/\/helveticascans\.com\/r(?:eader)?\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^https:\/\/gameofscanlation\.moe\/projects\/[a-z0-9-]+\/[a-z0-9\.-]+\/.*$/
// @include      /^http:\/\/mngcow\.co\/[a-zA-Z0-9_-]+\/[0-9\.]+\/([0-9]+\/)?$/
// @include      /^https:\/\/jaiminisbox\.com\/reader\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^https:\/\/kobato\.hologfx\.com\/reader\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^https?:\/\/merakiscans\.com\/[a-zA-Z0-9_-]+\/[0-9\.]+\/([0-9]+\/)?$/
// @include      /^http:\/\/www\.demonicscans\.com\/FoOlSlide\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^https?:\/\/reader\.deathtollscans\.net\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^http:\/\/read\.egscans\.com\/[A-Za-z0-9\-_\!,]+\/?(?:Chapter_[0-9]+(?:_extra)?(?:&display=(default|webtoon))?\/?)?$/
// @include      /^https:\/\/otscans\.com\/foolslide\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^https?:\/\/reader\.s2smanga\.com\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^https?:\/\/(?:www\.)?(?:readmanga\.today|readmng\.com)\/[^\/]+(\/.*)?$/
// @include      /^https?:\/\/manga\.fascans\.com\/[a-z]+\/[a-zA-Z0-9_-]+\/[0-9\.]+[\/]*[0-9]*$/
// @include      /^http?:\/\/mangaichiscans\.mokkori\.fr\/fs\/read\/.*?\/[a-z]+\/[0-9]+(\/.*)?$/
// @include      /^http:\/\/read\.lhtranslation\.com\/read-(.*?)-chapter-[0-9\.]+(?:-page-[0-9]+)?\.html$/
// @include      /^https?:\/\/whitecloudpavilion\.com\/manga\/free\/manga\/.*?\/[0-9\.]+(\/.*)?$/
// @include      /^http:\/\/www\.slide\.world-three\.org\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^http:\/\/hotchocolatescans\.com\/fs\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^https?:\/\/mangazuki\.co\/[a-z]+\/[a-zA-Z0-9_-]+\/[0-9\.]+[\/]*[0-9]*$/
// @include      /^https?:\/\/(reader\.)?ygscans\.com\/(reader\/)?read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^https?:\/\/reader\.championscans\.com\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^http:\/\/reader\.puremashiro\.moe\/read\/.*?\/[a-z\-]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^http:\/\/ravens-scans\.com\/(?:multi|lector)\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9\.]+(\/.*)?$/
// @include      /^https?:\/\/reader\.thecatscans\.com\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^http:\/\/hatigarmscans\.eu\/hs\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^https?:\/\/reader\.serenade\.moe\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^https?:\/\/forums\.lolscans\.com\/book\/page2\.php\?c=.*?&t=(manga|webcomic)&pF=projectFolderName$/
// @include      /^https?:\/\/mangarock\.com\/manga\/mrs-serie-[0-9]+\/chapter\/mrs-chapter-[0-9]+$/
// @include      /^http:\/\/reader\.evilflowers\.com\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^http:\/\/shoujohearts\.com\/reader\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^http:\/\/www\.twistedhelscans\.com\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^https?:\/\/www\.cmreader\.info\/[a-z]+\/[a-zA-Z0-9_-]+\/[a-zA-Z0-9\.-]+[\/]*[0-9]*$/
// @include      /^https?:\/\/psychoplay\.co\/read\/[a-zA-Z0-9_-]+\/[0-9\.]+$/
// @include      /^http:\/\/mangakakalot\.com\/chapter\/[a-zA-Z_\-0-9]+\/chapter_[0-9\.]+$/
// @include      /^http:\/\/reader\.dkthias\.com\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^http:\/\/reader\.fos-scans\.com\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^http:\/\/saikoscans\.ml\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^http:\/\/reader\.shoujosense\.com\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^http:\/\/mangatopia\.net\/slide\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^https?:\/\/reader\.vortex-scans\.com\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^http:\/\/dokusha\.info\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^https?:\/\/elpsycongroo\.tk\/r\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^http:\/\/bangaqua\.com\/reader\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^https?:\/\/damn-feels\.com\/reader\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^http:\/\/atelierdunoir\.org\/reader\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^http:\/\/reader\.holylolikingdom\.net\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^http:\/\/riceballicious\.info\/fs\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^https:\/\/mangadex\.com\/chapter\/[0-9]+(?:\/[0-9]+)?$/
// @updated      2018-01-20
// @version      1.8.55
// @downloadURL  https://trackr.moe/userscripts/manga-tracker.user.js
// @updateURL    https://trackr.moe/userscripts/manga-tracker.meta.js
// @require      https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js
// @resource     fontAwesome   https://use.fontawesome.com/9533173d07.css
// @resource     userscriptCSS https://trackr.moe/userscripts/assets/main.3.css
// @resource     reload        https://trackr.moe/userscripts/reload.png
// @grant        GM_addStyle
// @grant        GM_getResourceURL
// @grant        GM_getValue
// @grant        GM_setValue
// @grant        GM_addValueChangeListener
// @grant        GM_xmlhttpRequest
// @grant        unsafeWindow
// @noframes
// @connect      trackr.moe
// @connect      myanimelist.net
// @connect      m.mangafox.me
// @connect      m.mangafox.la
// @connect      m.mangahere.co
// @connect      m.mangahere.cc
// @run-at       document-start
// ==/UserScript==
/** jshint asi=false, bitwise=true, boss=false, browser=true, browserify=false, camelcase=false, couch=false, curly=true, debug=false, devel=true, dojo=false, elision=false, enforceall=false, eqeqeq=true, eqnull=false, es3=false, es5=false, esnext=false, esversion=6, evil=false, expr=false, forin=true, freeze=false, funcscope=false, futurehostile=false, gcl=true, globalstrict=false, immed=false, iterator=false, jasmine=false, jquery=true, lastsemic=false, latedef=false, laxbreak=false, laxcomma=false, loopfunc=false, maxerr=50, mocha=false, module=true, mootools=false, moz=false, multistr=false, newcap=false, noarg=true, nocomma=false, node=false, noempty=false, nomen=false, nonbsp=false, nonew=true, nonstandard=false, notypeof=false, noyield=false, onevar=false, passfail=false, phantom=false, plusplus=false, proto=false, prototypejs=false, qunit=false, quotmark=single, rhino=false, scripturl=false, shadow=false, shelljs=false, singleGroups=false, smarttabs=true, strict=true, sub=false, supernew=false, trailing=true, typed=false, undef=true, unused=true, validthis=false, varstmt=true, white=true, withstmt=false, worker=false, wsh=false, yui=false **/
/* global $, jQuery, GM_addStyle, GM_getResourceURL, GM_getValue, GM_setValue, GM_xmlhttpRequest, mal_sync, GM_addValueChangeListener, unsafeWindow */
'use strict';

jQuery.fn.reverseObj = function() {
	return $(this.get().reverse());
};

function getCookie(k){return(document.cookie.match(new RegExp('(^|; )'+k+'=([^;]*)'))||0)[2];}

function hasEmptyValues(o) {
	return Object.keys(o).some(function(x) {
		return o[x]===''||o[x]===null;  // or just "return o[x];" for falsy values
	});
}
function addStyleFromResource(resourceName) {
	//Userscript extensions don't seem to handle GM_getResourceURL the same, so we need to fix that.
	let GM_blob = GM_getResourceURL(resourceName);

	let cssEle = null;
	if(GM_blob.substr(0, 5) === 'blob:') {
		//ViolentMonkey
		//This is kind of a hack, but these blob: URLs don't work with css containing // includes, which means we need to convert it to base64 somehow.

		let xhr = new XMLHttpRequest();
			xhr.open('GET', GM_blob, true);
			xhr.responseType = 'arraybuffer';
			xhr.onload = function(e) {
			if (this.status === 200) {
				let uInt8Array   = new Uint8Array(this.response),
				    i            = uInt8Array.length,
				    binaryString = new Array(i);

				while (i--) {
					binaryString[i] = String.fromCharCode(uInt8Array[i]);
				}

				let data   = binaryString.join('');
				cssEle = $('<style/>', {type: 'text/css', text: data});
				$('head').append(cssEle);
			}
		};
		xhr.send();
	} else {
		//Other
		cssEle = $('<style/>', {type: 'text/css', text: atob(GM_blob.substr(21))});
		$('head').append(cssEle);
	}
}

/***********************************************************************************************************/

/**
 * Base container model for relevant functions and variables.
 * @namespace
 */
let base_site = {
	/**
	 * This is the first thing that runs, and also calls also all relevant functions.
	 * This should never be overridden (with the exception of trackr.moe). Use other methods instead!
	 *
	 * @function
	 * @name base_site.init
	 * @alias sites.*.init
	 */
	init : function() {
		let _this = this;

		addStyleFromResource('fontAwesome');

		this.preInit(function() {
			_this.setObjVars();
			_this.page_count = parseInt(_this.page_count); //FIXME: Is there a better place to put this?

			_this.stylize();

			_this.setupTopBar(function() {
				//We should only load the viewer if we've been successful with loading the topbar.

				/** @namespace config.options.disable_viewer */
				if(config.options.disable_viewer) { return; }
				_this.setupViewer();
			});
		});
	},

	/**
	 * This is called AFTER init, but before we do everything else.
	 * It is often used to redirect to new domain URLs, or do additional waiting/checks.
	 *
	 * @function
	 * @name base_site.preInit
	 * @alias sites.*.preInit
	 *
	 * @param {function} callback
	 */
	preInit : function(callback) { callback(); }, //callback must always be called

	/**
	 * Used to set variables used by various other functions.
	 *
	 * @function
	 * @name  base_site.setObjVars
	 * @alias sites.*.setObjVars
	 *
	 * @abstract
	 */
	setObjVars : function() {},

	/**
	 * Used to do add/remove additional styles on the page.
	 * This is usually just removing ads and other various banners.
	 * preSetupTopBar/preSetupViewer handle removing the default site viewer.
	 *
	 * @function
	 * @name  base_site.stylize
	 * @alias sites.*.stylize
	 *
	 * @abstract
	 */
	stylize : function() {},

	/**
	 * Used to do things prior to adding our own topbar.
	 * This is usually getting data for our topbar (either via current, or via AJAX).
	 *
	 * @function
	 * @name  base_site.preSetupTopBar
	 * @alias sites.*.preSetupTopBar
	 *
	 * @param {function} callback
	 */
	preSetupTopBar  : function(callback) { callback(); }, //callback must always be called

	/**
	 * @callback postSetupTopBarCallback
	 * @param {bool} [useCustomHeader]
	 * @param {bool} [useCustomImageList]
	 */

	/**
	 * Used to do things after setting up the topbar. Usually used to remove old topbars if they exist.
	 *
	 * @function
	 * @name  base_site.postSetupTopBar
	 * @alias sites.*.postSetupTopBar
	 *
	 * @param {postSetupTopBarCallback} callback
	 */
	postSetupTopBar : function(callback) { callback(); }, //callback must always be called

	/**
	 * @callback preSetupViewerCallback
	 * @param {bool} [useCustomHeader]
	 * @param {bool} [useCustomImageList]
	 */

	/**
	 * Used to remove the old viewer, get pages (if we haven't already), and get ready to setup our own viewer.
	 *
	 * @function
	 * @name  base_site.preSetupViewer
	 * @alias sites.*.preSetupViewer
	 *
	 * @param {preSetupViewerCallback} callback
	 */
	preSetupViewer  : function(callback) { callback(); }, //callback must always be called

	/**
	 * This is currently just a stub and isn't used yet!
	 *
	 * @function
	 * @todo Add definition for postSetupViewer
	 * @alias sites.*.postSetupViewer
	 * @name  base_site.postSetupViewer
	 *
	 * @param {jQuery=} topbar
	 */
	postSetupViewer : function(topbar) {}, // jshint ignore:line

	//Fixed Functions

	/**
	 * Used to setup the topbar. This calls preSetupTopbar > this > postSetupBoar.
	 * This uses these variables: chapterList, chapterListCurrent, viewerTitle, searchURLFormat, page_count, pagesLoaded (this is changed by calling updatePagesLoaded)
	 * * chapterList is a key/value array (URL:CHAPTERNAME) & chapterListCurrent is a URL for the current chapter (which is formatted to work with chapterList). Both of these are used to generate
	 * * viewerTitle contains the title of the series. This shows on hover of the chapter list.
	 * * page_count contains the total number of pages. When using the default AJAX method this is used to make sure we check all the pages correctly.
	 * * (optional) searchURLFormat is a URL used for searching (Using {%SEARCH%} for search input). Will only show search icon if set.
	 *
	 * @function
	 * @alias sites.*.setupTopBar
	 * @name base_site.setupTopBar
	 *
	 * @abstract
	 * @final
	 */
	setupTopBar : function(callback) {
		let _this = this;

		this.preSetupTopBar(function() {
			addStyleFromResource('userscriptCSS');

			let previous = (Object.keys(_this.chapterList).indexOf(_this.chapterListCurrent) > 0 ? $('<a/>', {class: 'buttonTracker', href: Object.keys(_this.chapterList)[Object.keys(_this.chapterList).indexOf(_this.chapterListCurrent) - 1], text: 'Previous'}) : '');
			let next     = (Object.keys(_this.chapterList).indexOf(_this.chapterListCurrent) < (Object.keys(_this.chapterList).length - 1) ? $('<a/>', {class: 'buttonTracker', href: Object.keys(_this.chapterList)[Object.keys(_this.chapterList).indexOf(_this.chapterListCurrent) + 1], text: 'Next'}) : '');
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
						$('<a/>', {href: main_site + '/report_issue?url='+encodeURIComponent(location.href), target: '_blank'}).append(
							$('<i/>', {id: 'report-issue', class: 'fa fa-bug', 'aria-hidden': 'true', title: 'Report an Issue'}))
					).append(
						_this.searchURLFormat !== '' ? $('<i/>', {id: 'trackerSearch', class: 'fa fa-search', 'aria-hidden': 'true', title: 'Search'}) : ''
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
				(_this.page_count ? $('<div/>', {id: 'TrackerBarPages', text: `Pages loaded: ${_this.pagesLoaded}/${_this.page_count}`, style: 'display: none'}) : '')
			);

			$(topbar).appendTo('body');

			//Setup select chapter change event
			$(topbar).on('change', 'select', function() {
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
			//Setup search.
			$(topbar).on('click', '#trackerSearch', function(e) {
				e.preventDefault();

				_this.search();
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

			_this.postSetupTopBar(callback);
		});
	},

	/**
	 * Used to track the current chapter.
	 * This uses these variables: site, title, chapter.
	 *
	 * @function
	 * @name base_site.trackChapter
	 * @alias sites.*.trackChapter
	 *
	 * @param {bool} askForConfirmation This is only false if "Auto track series on page load" is enabled on page load.
	 *
	 * @final
	 */
	trackChapter : function(askForConfirmation) {
		let _this = this;
		askForConfirmation = (typeof askForConfirmation !== 'undefined' ? askForConfirmation : false);

		if(config['api-key']) {
			if(this.attemptingTrack === false) {
				if(!askForConfirmation || askForConfirmation && confirm('This action will reset your reading state for this manga and this chapter will be considered as the latest you have read.\nDo you confirm this action?')) {
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

					if(!hasEmptyValues(params.manga)) {
						let status = $('#TrackerStatus');

						GM_xmlhttpRequest({
							url      :main_site + '/ajax/userscript/update',
							method  : 'POST',
							data    : $.param(params),
							headers: {
								"Content-Type": "application/x-www-form-urlencoded"
							},
							onload  : function(e) {
								_this.attemptingTrack = false;

								if(e.status === 200) {
									let data = e.responseText,
									    json = JSON.parse(data);

									/** @param {{mal_sync:string, mal_id:int, chapter:string}} json **/

									GM_setValue('lastUpdatedSeries', JSON.stringify(Object.assign(params, json, {url: location.href, chapterNumber: (_this.chapterNumber !== '' ? _this.chapterNumber : _this.chapter)})));

									//TODO: We should really output this somewhere other than the topbar..
									status.text('Attempting update...');

									switch(json.mal_sync) {
										case 'disabled':
											status.text('Updated');
											break;

										case 'csrf':
											if(json.mal_id) {
												if(json.mal_id !== 'none') {
													status.text('Updated (Found MAL ID, attempting update...)');
													_this.syncMALCSRF(json.mal_id, json.chapter);
												} else {
													status.text('Updated (Not on MAL)');
												}
											} else {
												status.text('Updated (No MAL ID set)');
											}

											break;

										case 'api':
											//TODO: Not implemented yet.
											break;

										default:
											break;
									}
								} else {
									switch(e.status) {
										case 400:
											alert('ERROR: ' + e.statusText);
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
										alert('ERROR: ' + e.statusText);
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
					} else {
						alert('Something went wrong when attempting to track');
						//TODO: Throw bug report
					}
				}
			} else {
				alert('Tracker is already attempting to track..');
			}
		} else {
			alert('Tracker isn\'t setup! Go to trackr.moe/user/options to set things up.');
		}
	},

	/**
	 * Used to update MAL via CSRF. Only runs if the MAL CSRF option is selected.
	 * This grabs the CSRF token required to update MAL. If successful it calls syncMALCSRF_continued
	 *
	 * @function
	 * @alias sites.*.syncMALCSRF
	 * @name base_site.syncMALCSRF
	 *
	 * @param {int}    malID
	 * @param {string} chapter
	 *
	 * @final
	 */
	syncMALCSRF : function(malID, chapter) {
		let _this = this;
		GM_xmlhttpRequest({
			method: 'GET',
			url: 'https://myanimelist.net/panel.php?go=export',
			onload: function(response) {
				if(/https:\/\/myanimelist.net\/logout.php/.exec(response.responseText)) {
					//user is logged in, export manga then sync
					let csrfToken = /<meta name='csrf_token' content='([A-Za-z0-9]+)'>/.exec(response.responseText)[1];

					_this.syncMALCSRF_continued(malID, chapter, csrfToken);
				} else {
					//user is not logged in, throw error
					$('#TrackerStatus').text('Updated (MAL Sync failed, are you logged in?)');
				}
			}
		});
	},

	/**
	 * Used to update MAL. Is called from syncMALCSRF after successfully grabbing CSRF token.
	 *
	 * @function
	 * @alias sites.*.syncMALCSRF_continued
	 * @name base_site.syncMALCSRF_continued
	 *
	 * @param {int}    malID
	 * @param {string} chapter
	 * @param {string} csrfToken
	 *
	 * @final
	 */
	syncMALCSRF_continued : function(malID, chapter, csrfToken) {
		let chapterArr = chapter.match(/^(?:(?:v(?:[0-9]+|TBD|TBA|NA|LMT))\/)?c([0-9]+)(?:\.[0-9]+)?$/) || [];

		if(chapterArr.length > 0) {
			let malIDI = parseInt(malID),
			    chapterN = parseInt(chapterArr[1]);

			let json = {
				'manga_id'          : malIDI,
				'status'            : 1, //force reading list
				'num_read_chapters' : chapterN,
				'csrf_token'        : csrfToken
			};
			if(chapterN < 1000) {
				let status = $('#TrackerStatus');

				GM_xmlhttpRequest({
					method: 'POST',
					url: 'https://myanimelist.net/ownlist/manga/edit.json',
					data: JSON.stringify(json),
					onload: function(response) {
						if(response.responseText !== '{"errors":[{"message":"failed to edit"}]}') {
							status.html(`Updated & <a href="https://myanimelist.net/manga/${malIDI}" class="mal-link">MAL Synced</a> (c${chapterN})`);
						} else {
							status.text('Updated (MAL missing from list, attempting to add...)');
							GM_xmlhttpRequest({
								method: 'POST',
								url: 'https://myanimelist.net/ownlist/manga/add.json',
								data: JSON.stringify(json),
								onload: function(response) {
									if(response.responseText !== '{"errors":[{"message":"The manga is already in your list."}]}') {
										status.html(`Updated & <a href="https://myanimelist.net/manga/${malIDI}" class="mal-link">MAL Synced</a> (c${chapterN})`);
									} else {
										status.text('Updated (Adding to MAL failed?)');
									}
								},
								onerror: function() {
									status.text('Updated (MAL Sync failed)');
								}
							});
						}
					},
					onerror: function() {
						status.text('Updated (MAL Sync failed)');
					}
				});
			} else {
				$('#TrackerStatus').text('Updated (Unable to MAL Sync due to chapter format)');
			}
		} else {
			$('#TrackerStatus').text('Updated (Unable to MAL Sync due to chapter format)');
		}
	},

	/**
	 * Used to setup the viewer.
	 * Calls preSetupViewer > setupViewer > postSetupViewer.
	 *
	 * @function
	 * @alias sites.*.setupViewer
	 * @name base_site.setupViewer
	 *
	 * @final
	 */
	setupViewer : function() {
		let _this = this;

		//FIXME: VIEWER: Is it possible to make sure the pages load in order without using async: false?
		//FIXME: VIEWER: Is it possible to set the size of the image element before it is loaded (to avoid pop-in)?
		//FIXME: Somehow handle the viewer header code here?

		this.preSetupViewer(function(useCustomHeader, useCustomImageList) {
			useCustomHeader    = (typeof useCustomHeader !== 'undefined' ? useCustomHeader : false);
			useCustomImageList = (typeof useCustomImageList !== 'undefined' ? useCustomImageList : false);

			let viewer = $('#viewer');

			//Setup viewer header if enabled
			if(!useCustomHeader) {
				viewer.append(
					$('<div/>', {id: 'viewer_header'}).append(
						$('<a/>', {href: _this.chapter_url, text: _this.viewerChapterName})).append(
						'  ----  ').append(
						$('<a/>', {href: _this.title_url, text: _this.viewerTitle})
					)
				);
			}

			let TrackerBarPages = $('#TrackerBarPages');
			//Add page load counter IF it hasn't already been added (due to page_count being set lately) and if using our viewer
			if(!TrackerBarPages.length && _this.page_count) {
				TrackerBarPages = $('<div/>', {id: 'TrackerBarPages', text: `Pages loaded: ${_this.pagesLoaded}/${_this.page_count}`, style: 'display: none'}).appendTo('#TrackerBar');
			}
			TrackerBarPages.show('slow');

			//Generate the viewer using a loop & AJAX.
			$('<div/>', {class: 'read_img', style: 'display: none'}).appendTo(viewer.get()); //Add a dummy element

			let pagePromises = [];
			for(let pageN=1; pageN<=_this.page_count; pageN++) {
				pagePromises.push(new Promise((resolve, reject) => { // jshint ignore:line
					$('<div/>', {id: 'trackr-page-'+pageN, class: 'read_img'}).insertAfter(viewer.find('> .read_img:last'));

					if(!useCustomImageList) {
						let pageDelay = _this.delay + (_this.delay !== 0 ? (pageN * _this.delay) : 0);
						setTimeout(addToContainer, pageDelay, pageN, resolve, reject);
					} else {
						//Although we don't actually need a delay here, it would probably be good not to load every single page at once if possible
						let pageDelay = 100 + (pageN * 100);
						setTimeout(addToContainerCustom, pageDelay, pageN, resolve, reject);
					}
				}));
			}
			Promise.all(pagePromises).then(() => {
				console.log('trackr - All promises resolved.');

				//Auto-track chapter if enabled.
				/** @namespace config.auto_track */
				if(config.options.auto_track) {
					console.log('trackr - Auto-tracking chapter');
					_this.trackChapter();
				}

				//Setup zoom event
				if(viewer.length) {
					let changeZoom = function(action) {
						let images = $('#viewer').find('img'),
						    newZoom = images.get(0).clientWidth;

						switch(action) {
							case '+':
								//increase zoom
								images.css({'width': newZoom + 50});

								break;

							case '-':
								//decrease zoom
								images.css({'width': newZoom - 50});
								break;

							case '=':
								//reset
								images.css({'width': 'auto'});
								break;

							default:
								//do nothing
								break;
						}
					};
					$(document).keydown(function(event){
						changeZoom(event.key);
					});
				}

				_this.postSetupViewer();
			});

			function addToContainer(pageN, promiseResolve, promiseReject) {
				let url = _this.viewerChapterURLFormat.replace('%pageN%', pageN.toString());

				//FIXME: (TEMP HACK) Due to MH being weird with https redirects, we need to do this.
				//       When I get the time we should move this to the parent object so we can override it.
				if(url.includes('mangahere.cc', 0)) {
					url = url.replace('1.html', '');
				}

				$.ajax({
					url    : url,
					type   : 'GET',
					page   : pageN,
					// async: useASync,
					success: function (data) {
						if(data.length > 0) {
							data = data.replace(_this.viewerRegex, '$1');
							data = data.replace(' src=', ' data-trackr-src='); //This prevents jQuery from preloading images, which can cause issues.

							let original_image = $(data).find('img:first').addBack('img:first');
							_this.setupViewerContainer($(original_image).attr('data-trackr-src'), this.page);
						} else {
							_this.setupViewerContainerError(url, this.page, false);
						}
						promiseResolve();
					},
					error: function () {
						_this.setupViewerContainerError(url, this.page, false);
						promiseResolve(); // we probably should use promiseReject() here
					}
				});
			}
			function addToContainerCustom(pageN, promiseResolve, promiseReject) {
				_this.setupViewerContainer(_this.viewerCustomImageList[pageN-1], pageN);
				promiseResolve();
			}
		});
	},

	/**
	 * Used to setup the page container used by the viewer.
	 *
	 * @function
	 * @alias sites.*.setupViewerContainer
	 * @name base_site.setupViewerContainer
	 *
	 * @param {string} imgURL
	 * @param {int}    pageN
	 *
	 * @final
	 */
	setupViewerContainer : function(imgURL, pageN) {
		let _this = this;

		let image_container = $('<div/>', {id: `trackr-page-${pageN}`, class: 'read_img'}).append(
			//We want to completely recreate the image element to remove all additional attributes
			$('<img/>', {src: imgURL})
				.on('load', function() {
					_this.updatePagesLoaded(true);
				})
				.on('error', function() {
					_this.setupViewerContainerError(imgURL, pageN, true);
				})
		).append(
			//Add page number
			$('<div/>', {class: 'pageNumber'}).append(
				$('<div/>', {class: 'number', text: `${pageN} / ${_this.page_count}`}))
		);

		//Replace the placeholder image_container with the real one
		$(`#trackr-page-${pageN}`).replaceWith(image_container);
	},

	/**
	 * Used to setup the page container for errored pages.
	 *
	 * @function
	 * @alias sites.*.setupViewerContainerError
	 * @name base_site.setupViewerContainerError
	 *
	 * @param {string}  pageURL
	 * @param {int}     pageN
	 * @param {boolean} imgLoadFailed
	 *
	 * @final
	 */
	setupViewerContainerError : function(pageURL, pageN, imgLoadFailed) {
		let _this = this;
		_this.updatePagesLoaded(false);

		console.error('setupViewerContainerError called');
		let image_container = $('<div/>', {class: 'read_img', id: 'trackr-page-'+pageN}).append(
			$('<img/>', {style: 'cursor: pointer', src: GM_getResourceURL('reload')}).click(function() {
				if(!imgLoadFailed) {
					//Page load failed
					$.ajax({
						url    : pageURL,
						type   : 'GET',
						page   : pageN,
						// async: useASync,
						success: function (data) {
							let original_image = $(data.replace(_this.viewerRegex, '$1')).find('img:first').addBack('img:first');
							_this.setupViewerContainer($(original_image).attr('src'), this.page);
						},
						error: function () {
							alert('Failed to load image again. Something may be wrong with the site.');
							_this.setupViewerContainerError(pageURL, this.page, false);
						}
					});
				} else {
					//Image load failed
					_this.setupViewerContainer(`${pageURL}?` + new Date().getTime(), pageN);
				}
			})
		).append(
			//Add page number
			$('<div/>', {class: 'pageNumber'}).append(
				$('<div/>', {class: 'number', text: `${pageN} / ${_this.page_count}`}))
		);

		//Replace the placeholder image_container with the real one
		$('#trackr-page-'+pageN).replaceWith(image_container);
	},

	/**
	 * Used to update the page load counter.
	 *
	 * @function
	 * @alias sites.*.updatePagesLoaded
	 * @name base_site.updatePagesLoaded
	 *
	 * @param {boolean} loaded
	 *
	 * @final
	 */
	updatePagesLoaded : function(loaded) {
		let _this = this;
		this.pagesLoadedAttempts += 1;

		let ele = $('#TrackerBarPages');

		if(loaded) {
			this.pagesLoaded += 1;
			ele.text(`Pages loaded: ${this.pagesLoaded}/${this.page_count}`);
		}

		if(this.pagesLoadedAttempts >= this.page_count) {
			//This is last page to load, check if everything loaded correctly.
			if(this.pagesLoaded >= this.page_count) {
				//Everything was loaded correctly, hide the page count div.
				//FIXME: This doesn't always hide correctly?

				setTimeout(function() {
					ele.html('&nbsp;').hide('slow');
				}, 1500);

				//Auto-scroll to page if URL is a specific page URL
				//FIXME: Is there a better place to put this?
				if(_this.currentPage > 0) {
					_this.gotoPage(_this.currentPage);
				}
			} else {
				ele
					.html('') //remove everything from existing container
					.append($('<span/>', {text: 'ERROR: '+(this.page_count - this.pagesLoaded)+' pages failed to load | '}))
					.append($('<a/>', {href: '#', id: 'reloadPages'}).append(
						$('<i/>', {class: 'fa fa-refresh', 'aria-hidden': 'true'})
					));
			}

			console.log('trackr - higher than pc: '+this.pagesLoadedAttempts);
		} else {
			console.log('trackr - lower than pc: '+this.pagesLoadedAttempts);
		}
	},

	/**
	 * Used to reload all errored pages.
	 *
	 * @function
	 * @alias sites.*.reloadPages
	 * @name base_site.reloadPages
	 *
	 * @final
	 */
	reloadPages : function() {
		let _this = this;

		$('#TrackerBarPages').html('Attempting to load pages...');
		//FIXME: This is a really lazy way of doing this...
		$('.read_img[id] img').each(function(i, v) {
			setTimeout(function() {
				$(v).click();
			}, _this.delay + (_this.delay !== 0 ? (i * _this.delay) : 0));
		});
	},

	/**
	 * Used to search the current site. Requires searchURLFormat to be set to show.
	 *
	 * @function
	 * @alias sites.*.search
	 * @name base_site.search
	 *
	 * @final
	 */
	search : function() {
		let original_search_string = prompt('Search: ');

		if(original_search_string !== null) {
			let encoded_search_string  = encodeURIComponent(original_search_string);

			location.href = this.searchURLFormat.replace('{%SEARCH%}', encoded_search_string);
		}
	},

	/**
	 * Used to favourite the current chapter.
	 *
	 * @function
	 * @alias sites.*.favouriteChapter
	 * @name base_site.favouriteChapter
	 *
	 * @final
	 */
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

			GM_xmlhttpRequest({
				url     : main_site + '/ajax/userscript/favourite',
				method  : 'POST',
				data    : $.param(params),
				headers: {
					"Content-Type": "application/x-www-form-urlencoded"
				},
				onload  : function(e) {
					if(e.status === 200) {
						$('#TrackerStatus').text(e.statusText);
					} else {
						switch(e.status) {
							case 400:
								alert('ERROR: ' + e.statusText);
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
							alert('ERROR: ' + e.statusText);
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
		} else {
			alert('Tracker isn\'t setup! Go to trackr.moe/user/options to set things up.');
		}
	},

	/**
	 * Used to scroll to selected page.
	 *
	 * @function
	 * @alias sites.*.gotoPage
	 * @name base_site.gotoPage
	 *
	 * @final
	 */
	gotoPage : function(pageN) {
		console.log(`trackr - Scrolling to page "${pageN}"`);
		if(pageN > 1) {
			let page_ele = $(`#trackr-page-${pageN}`);
			if(page_ele.length) {
				$('html, body').animate({
					scrollTop: page_ele.offset().top
				}, 2000);
			}
		}
	},

	/**
	 * Used to setup (most) sites that use FoolSlide.
	 * Will most likely not work on sites that use very old versions of FoolSlide.
	 *
	 * @function
	 * @alias sites.*.setupFoolSlide
	 * @name base_site.setupFoolSlide
	 *
	 * @final
	 */
	setupFoolSlide : function() {
		this.segments = this.segments.slice(this.segments.indexOf('read') + 1);

		this.setObjVars = function() {
			this.title   = this.segments[0];
			this.chapter = this.segments[1] + '/' + this.segments[2] + '/' + this.segments[3] + (this.segments[4] && this.segments[4] !== 'page' ? '/' + this.segments[4] : '');

			let chapterArr     = this.chapter.split('/');
			this.chapterNumber = (chapterArr[1] !== '0' ? `v${chapterArr[1]}/` : '') + `c${chapterArr[2]}` + (chapterArr[3] ? `.${chapterArr[3]}` : '');

			//($chapter_parts[1] !== '0' ? "v{$chapter_parts[1]}/" : '') . "c{$chapter_parts[2]}" . (isset($chapter_parts[3]) ? ".{$chapter_parts[3]}" : '')/*)*/
			this.title_url   = this.foolSlideBaseURL+'/'+this.title;
			this.chapter_url = this.foolSlideBaseURL+'/read/'+this.title+'/'+this.chapter;

			//FIXME: The chapterList isn't properly ordered for series that have chapters in and outside volumes.
			//       SEE: - https://reader.seaotterscans.com/series/sss/
			this.chapterList        = generateChapterList($('.topbar_left > .tbtitle:eq(2) > ul > li > a').reverseObj(), 'href');
			this.chapterListCurrent = this.chapter_url+'/';

			this.viewerTitle = $('.topbar_left > .dropdown_parent > .text a').text();

			if(this.viewerCustomImageList.length === 0) {
				//FoolSlide has the list of images stored in an html tag we can use instead of having to AJAX each page.
				this.viewerCustomImageList = ($('#content, .isreaderc').find('> script:first, + script').first().html().match(/"url"\s*:\s*"(https?:\\\/\\\/[^"]+)"/g) || []).filter(function(value, index, self) {
					return self.indexOf(value) === index;
				}).map(function(e) {
					let val = e.replace(/"url"\s*:\s*"(https?:\\\/\\\/[^"]+)"/, '$1');
					return JSON.parse('"' + val.replace(/"/g, '\\"') + '"');
				});
			}
			this.page_count = this.viewerCustomImageList.length;

			let sPage = this.segments.indexOf('page');
			if((sPage !== -1) && this.segments[sPage + 1]) {
				this.currentPage = parseInt(this.segments[sPage + 1]);
			}
		};

		this.postSetupTopBar = function(callback) {
			$('.topbar_left > .tbtitle:eq(2)').remove();
			$('.topbar_right').remove();
			$('#bottombar').remove();

			callback();
		};

		this.preSetupViewer = function(callback) {
			$('#page').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div
			callback(true, true);
		};
	},

	/**
	 * Used to setup (most) sites that use My Manga Reader CMS.
	 *
	 * @function
	 * @alias sites.*.setupMyMangaReaderCMS
	 * @name base_site.setupMyMangaReaderCMS
	 *
	 * @final
	 */
	setupMyMangaReaderCMS : function() {
		this.segments = this.segments.slice(this.segments.lastIndexOf('manga') + 1);

		this.setObjVars = function() {
			this.title         = this.segments[0];
			this.chapter       = this.segments[1];

			this.title_url   = this.myMangaReaderCMSBaseURL+'/manga/'+this.title+'/';
			this.chapter_url = this.title_url + this.chapter;

			this.chapterList        = generateChapterList($('#chapter-list').find('> ul > li > a').reverseObj(), 'href');
			this.chapterListCurrent = this.chapter_url;

			this.viewerTitle            = $('ul[class="nav navbar-nav"] > li:first > a').text().slice(0,-6);
			this.viewerCustomImageList = $('#all').find('> img').map(function(i, e) {
				return $(e).attr('data-src');
			});
			this.page_count = this.viewerCustomImageList.length;
		};

		this.postSetupTopBar = function(callback) {
			let viewer = $('.viewer-cnt');

			//Remove extra unneeded elements.
			viewer.prevAll().remove();
			viewer.nextAll().remove();

			callback();
		};

		this.preSetupViewer = function(callback) {
			$('.viewer-cnt').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div
			callback(true, true);
		};
	},

	//Variables

	/**
	 * Array of strings based on URL, separated by '/' limiter.
	 * @type {Array}
	 */
	segments : window.location.pathname.split('/'),

	/**
	 * Object of URL parameters.
	 * @type {Object}
	 */
	parameters : window.location.search.substring(1) ? JSON.parse('{"' + window.location.search.substring(1).replace(/&/g, '","').replace(/=/g,'":"') + '"}', function(key, value) { return key === "" ?  value : decodeURIComponent(value); }) : {},

	/**
	 * String containing protocol
	 * @type {string}
	 */
	https    : location.protocol.slice(0, -1),

	//Used for tracking.

	/**
	 * Name of site.
	 * @type {string}
	 */
	site    : location.hostname.replace(/^(?:dev|test)\./, ''),

	/**
	 * Title of chapter
	 * @type {String}
	 */
	title   : '',

	/**
	 * Chapter
	 * @type {String}
	 */
	chapter : '',

	/**
	 * Chapter number (Used when updating updating trackr.moe table on another window)
	 * @type {String}
	 */
	chapterNumber : '',

	//Used by everything for easy access

	/**
	 * URL of chapter
	 * @type {String}
	 */
	chapter_url : '',

	/**
	 * URL of title
	 * @type {String}
	 */
	title_url   : '',

	//Used for topbar.

	/**
	 * Current chapter in chapterList
	 * @type {String}
	 */
	chapterListCurrent : '',

	/**
	 * Container for list of chapters
	 * @type {Object}
	 */
	chapterList        : {},

	/**
	 * Initialization of number of pages
	 * @type {Number}
	 */
	page_count : 0,

	//Used for custom viewer header (if requested)

	/**
	 * Name of chapter for viewer.
	 * @type {String}
	 */
	viewerChapterName      : '',

	/**
	 * Title for viewer
	 * @type {String}
	 */
	viewerTitle            : '',

	/**
	 * Stores URL format for chapters.
	 * %pageN% is replaced by the page number on load.
	 * @type {String}
	 */
	viewerChapterURLFormat : '%pageN%',

	//Used for viewer AJAX (if used)

	/**
	 * Regex used to find tag
	 * First img tag MUST be the chapter page.
	 * @type {RegExp}
	 */
	viewerRegex            : /^$/,

	/**
	 * Image list that contains the list of images.
	 * This is is only used if useCustomImageList is true.
	 * @type {Array}
	 */
	viewerCustomImageList  : [],

	/**
	 * Delay each page load by x ms when not using custom image list
	 * @type {Number}
	 */
	delay: 0,

	//Used for search.

	/**
	 * URL string that allows for searches
	 * {%SEARCH%} is replaced with search string.
	 * @type {String}
	 */
	searchURLFormat : '',

	//Misc

	/**
	 * Checks if tracking is being attempted.
	 * This is only changed by trackChapter
	 * @type {Boolean}
	 */
	attemptingTrack     : false,

	/**
	 * Number of pages loaded.
	 * @type {Number}
	 */
	pagesLoaded         : 0,

	/**
	 * Number of times attempted to load the page.
	 * @type {Number}
	 */
	pagesLoadedAttempts : 0,

	/**
	 * Current page. Used to allow auto-scrolling to pages when directly linked to them.
	 * @type {Number}
	 */
	currentPage: 0,

	/**
	 * URL pointing to base FoolSlide location. Used by setupFoolSlide.
	 * Most of the time this is just location.origin, but sometimes it's also location.origin/foolslide and so on.
	 * @type {String}
	 */
	foolSlideBaseURL : /\/read\//.test(location.pathname) ? location.href.replace(/^(.*?)\/read\/.*$/, '$1') : location.origin,

	/**
	 * URL pointing to base myMangaReaderCMS location. Used by myMangaReaderCMS.
	 * @type {String}
	 */
	myMangaReaderCMSBaseURL : /\/manga\//.test(location.pathname) ? location.href.replace(/^(.*?)\/manga\/.*$/, '$1') : location.origin
};

/**
 * @typedef {Object} SiteObject Object containing all necessary variables for site.
 */

/**
 * [extendSite description]
 * @param  {SiteObject} o Object containing all necessary variables for site.
 * @return {Object}   Returns base_site extension of intended site.
 */
function extendSite(o) { return Object.assign({}, base_site, o); }

/**
 * Generates list of chapters
 * @param  {jQuery} target  Target jQuery object containing list of chapters.
 * @param  {string} attrURL The inner tag containing each chapter URL.
 * @return {Object}         Contains URL and names.
 */
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

/**
 * List of Sites
 * @namespace
 */
let sites = {
	/**
	 * MangaFox (Alt Domain)
	 * @type {SiteObject}
	 */
	'mangafox.la' : extendSite({
		preInit : function(callback) {
			let ms = sites['mangafox.me'];
			this.setObjVars      = ms.setObjVars;
			this.stylize         = ms.stylize;
			this.preSetupTopBar  = ms.preSetupTopBar;
			this.postSetupTopBar = ms.postSetupTopBar;
			this.preSetupViewer  = ms.preSetupViewer;
			this.setupViewerContainer = ms.setupViewerContainer;

			this.site = 'mangafox.me';

			callback();
		}
	}),
	/**
	 * MangaFox
	 * @type {SiteObject}
	 */
	'mangafox.me' : extendSite({
		setObjVars : function () {
			this.segments    = window.location.pathname.split( '/' );

			this.title       = this.segments[2];
			this.chapter     = ((!!this.segments[4] && ! /\.html$/.test(this.segments[4])) ? this.segments[3]+'/'+this.segments[4] : this.segments[3]);

			this.page_count  = $('#top_bar').find('.prev_page + div').text().trim().replace(/^[\s\S]*of ([0-9]+)$/, '$1');

			this.title_url   = 'http://mangafox.la/manga/'+this.title+'/';
			this.chapter_url = '//mangafox.la/manga/'+this.title+'/'+this.chapter+'/';

			this.chapterListCurrent = this.chapter_url+'1.html';
			this.chapterList        = {}; //This is set via preSetupTopbar

			this.viewerTitle            = $('#series').find('> strong:last > a').text().slice(0, -6);
			this.viewerChapterURLFormat = this.chapter_url + '%pageN%'+'.html';
			this.viewerRegex            = /^[\s\S]*(<div class="read_img">[\s\S]*<\/div>)[\s\S]*<\/div>[\s\S]*<div id="shares"[\s\S]*$/;
			// this.viewerCustomImageList  = []; //This is (possibly) set below.

			this.searchURLFormat = 'http://mangafox.la/search.php?advopts=1&name={%SEARCH%}';

			this.currentPage = parseInt(this.segments.slice(-1)[0].replace(/^([0-9]+).*/, '$1'));

			this.delay = 1000;
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

			$('#left-skyscraper, #right-skyscraper').remove();
		},
		preSetupTopBar : function(callback) {
			let _this = this;

			//The inline chapter list is cached. This causes new chapters to not properly show on the list. (Why the cache isn't reset when a new chapter is added is beyond me)
			//Because of this, we can't use the inline chapter list as a source, and instead we need to check the manga page.
			//We can't CSRF to the subdomain for some reason, so we need to use a GM function here...
			GM_xmlhttpRequest({
				url     : _this.title_url.replace('mangafox.la', 'm.mangafox.la'),
				method  : 'GET',
				onload  : function(response) {
					let data = response.responseText;
					data = data.replace(/^[\S\s]*(<div id="chapters"\s*>[\S\s]*)<div id="discussion" >[\S\s]*$/, '$1'); //Only grab the chapter list

					let div = $('<div/>').append($(data));

					$('.chlist a', div).reverseObj().each(function() {
						let chapterTitle     = $('+ span.title', this).text().trim(),
						    url              = $(this).attr('href').replace(/^(.*\/)(?:[0-9]+\.html)?$/, '$1'); //Remove trailing page number

						url = url.replace('m.mangafox.la/manga/', 'mangafox.la/manga/');
						_this.chapterList[url+'1.html'] = url.replace(/^.*\/manga\/[^/]+\/(?:v(.*?)\/)?c(.*?)\/$/, 'Vol.$1 Ch.$2')
							.replace(/^Vol\. /, '') + (chapterTitle !== '' ? ': ' + chapterTitle : '');
					});

					callback();

				},
				onerror : function() {
					console.log('trackr - Unable to load mobile site, fallback to old loading method');
					$.ajax({
						url: _this.title_url,
						beforeSend: function(xhr) {
							xhr.setRequestHeader('Cache-Control', 'no-cache, no-store');
							xhr.setRequestHeader('Pragma', 'no-cache');
						},
						cache: false,
						success: function(response) {
							response = response.replace(/^[\S\s]*(<div id="chapters"\s*>[\S\s]*)<div id="discussion" >[\S\s]*$/, '$1'); //Only grab the chapter list
							let div = $('<div/>').append($(response));

							$('#chapters > .chlist > li > div > a + * > a', div).reverseObj().each(function() {
								let chapterTitle     = $('+ span.title', this).text().trim(),
								    url              = $(this).attr('href').replace(/^(.*\/)(?:[0-9]+\.html)?$/, '$1'); //Remove trailing page number

								_this.chapterList[url+'1.html'] = url.replace(/^.*\/manga\/[^/]+\/(?:v(.*?)\/)?c(.*?)\/$/, 'Vol.$1 Ch.$2')
									.replace(/^Vol\. /, '') + (chapterTitle !== '' ? ': ' + chapterTitle : '');
							});

							callback();
						},
						error: function(jqXHR, textStatus, errorThrown) {
							callback();
						}
					});
				}
			});
		},
		postSetupTopBar : function(callback) {
			$('#top_center_bar, #bottom_center_bar').remove();
			$('#tool').parent().find('> .gap').remove();
			$('#series').css('padding-top', '0');

			callback();
		},
		preSetupViewer : function(callback) {
			let _this = this;

			let newViewer = $('<div/>', {id: 'viewer'});

			//Add a notice about adblock.
			newViewer.prepend(
				$('<p/>', {style: 'background: white; border: 2px solid black;', text: `
					MangaFox has moved to using an image host which is blacklisted by some AdBlockers.
					If you can't see any images, you will need to whitelist "lmfcdn.secure.footprint.net.
					In addition to this, if the loaded image is a MangaFox logo, then the issue is on MangaFox's end and not a bug on our end.
					".
				`})
			);

			$('#viewer').replaceWith(newViewer); //Set base viewer div

			//We can't CSRF to the subdomain for some reason, so we need to use a GM function here...
			GM_xmlhttpRequest({
				url     : 'http:'+_this.chapter_url.replace('mangafox.la/manga', 'm.mangafox.la/roll_manga'),
				method  : 'GET',
				onload  : function(response) {
					let data      = response.responseText,
					    imageList = [];

					if(data.indexOf('it’s licensed and not available.') === -1) {
						//Avoid attempting to load imageList if is licensed.
						imageList = $(data.replace(/^[\s\S]*(<div class="mangaread-main">[\s\S]*<\/div>)[\s\S]*<div class="mangaread-operate[\s\S]*$/, '$1')).find('img.reader-page');

						_this.viewerCustomImageList = imageList.map(function(i, e) {
							//NOTE: This is a temp-fix for uMatrix blocking secure.footprint.net by default due to one of the default lists containing it.
							return $(e).attr('data-original').replace('https://lmfcdn.secure.footprint.net', 'http://l.mfcdn.net');
						});

						if(_this.viewerCustomImageList.length) {
							//Sometimes the page count on the actual site isn't accurate, but the mobile sites is. Fix when possible.
							_this.page_count = _this.viewerCustomImageList.length;

							callback(false, true);
						} else {
							console.log('trackr - Mobile site returned no images? Falling back to old loading method');
							callback(false, false);
						}
					} else {
						console.log('trackr - Mobile site returned licensed. Falling back to old method.');
						callback(false, false);
					}
				},
				onerror : function() {
					console.log('trackr - Unable to load mobile site, fallback to old page loading method');
					callback(false, false);
				}
			});
		},
		setupViewerContainer : function(imgURL, pageN) {
			let _this = this;

			imgURL = imgURL.replace('https://lmfcdn.secure.footprint.net', 'http://l.mfcdn.net');

			let image_container = $('<div/>', {id: `trackr-page-${pageN}`, class: 'read_img'}).append(
				//We want to completely recreate the image element to remove all additional attributes
				$('<img/>', {src: imgURL})
					.on('load', function() {
						_this.updatePagesLoaded(true);
					})
					.on('error', function() {
						_this.setupViewerContainerError(imgURL, pageN, true);
					})
			).append(
				//Add page number
				$('<div/>', {class: 'pageNumber'}).append(
					$('<div/>', {class: 'number', text: `${pageN} / ${_this.page_count}`}))
			);

			//Replace the placeholder image_container with the real one
			$(`#trackr-page-${pageN}`).replaceWith(image_container);
		}
	}),

	/**
	 * MangaHere (Alt Domain)
	 * @type {SiteObject}
	 */
	'www.mangahere.cc' : extendSite({
		preInit : function(callback) {
			let ms = sites['www.mangahere.co'];
			this.setObjVars      = ms.setObjVars;
			this.stylize         = ms.stylize;
			this.preSetupTopBar  = ms.preSetupTopBar;
			this.postSetupTopBar = ms.postSetupTopBar;
			this.preSetupViewer  = ms.preSetupViewer;
			this.setupViewerContainer = ms.setupViewerContainer;

			this.site = 'www.mangahere.co';

			callback();
		}
	}),

	/**
	 * MangaHere
	 * @type {SiteObject}
	 */
	'www.mangahere.co' : extendSite({
		//MangaHere uses pretty much the same site format as MangaFox, with a few odd changes.
		setObjVars : function() {
			this.segments      = window.location.pathname.split( '/' );

			//FIXME: Is there a better way to do this? It just feels like an ugly way of setting vars.
			this.page_count    = $('.go_page:first > .right > select > option').length;
			this.title         = this.segments[2];
			this.chapter       = ((!!this.segments[4] && ! /\.html$/.test(this.segments[4])) ? this.segments[3]+'/'+this.segments[4] : this.segments[3]);

			this.title_url   = 'http://www.mangahere.cc/manga/'+this.title+'/';
			this.chapter_url = '//www.mangahere.cc/manga/'+this.title+'/'+this.chapter+'/';

			this.chapterListCurrent = this.chapter_url;
			// this.chapterList        = {}; //This is set via preSetupTopbar

			this.viewerTitle            = $('.readpage_top > .title > h2').text().slice(0, -6);
			this.viewerChapterURLFormat = this.chapter_url + '%pageN%'+'.html';
			this.viewerRegex            = /^[\s\S]*<section class="read_img" id="viewer">[\s\S]*(<img src[\s\S]*\/>)[\s\S]*<\/section>[\s\S]*<section class="readpage_footer[\s\S]*$/;

			this.currentPage = parseInt(this.segments.slice(-1)[0].replace(/^([0-9]+).*/, '$1'));
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
			GM_xmlhttpRequest({
				url     : _this.title_url.replace('http://www.mangahere.cc', 'https://m.mangahere.co'),
				method  : 'GET',
				onload  : function(response) {
					let data = response.responseText;
					data = data.replace(/^[\S\s]*(<section class="main">[\S\s]*(?=<\/section>)<\/section>)[\S\s]*$/, '$1'); //Only grab the chapter list
					let div = $('<div/>').append($(data));

					$('.manga-chapters > ul > li > a', div).reverseObj().each(function() {
						let chapterTitle     = $(this).parent().clone().children().remove().end().text().trim(),
						    url              = $(this).attr('href').replace(/^(.*\/)(?:[0-9]+\.html)?$/, '$1'); //Remove trailing page number

						url = url.replace('m.mangahere.co/manga/', 'www.mangahere.cc/manga/');
						_this.chapterList[url] = url.replace(/^.*\/manga\/[^/]+\/(?:v(.*?)\/)?c(.*?)\/$/, 'Vol.$1 Ch.$2')
							.replace(/^Vol\. /, '') + (chapterTitle !== '' ? ': ' + chapterTitle : '');
					});

					callback();
				},
				onerror : function() {
					console.log('trackr - Unable to load mobile site, fallback to old loading method');
					$.ajax({
						url: _this.title_url,
						beforeSend: function(xhr) {
							xhr.setRequestHeader('Cache-Control', 'no-cache, no-store');
							xhr.setRequestHeader('Pragma', 'no-cache');
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
						},
						error: function(/*jqXHR, textStatus, errorThrown*/) {
							callback();
						}
					});
				}
			});
		},
		postSetupTopBar : function(callback) {
			$('.go_page:first').remove();

			callback();
		},
		preSetupViewer : function(callback) {
			let _this = this;

			let newViewer = $('<div/>', {id: 'viewer'});

			//Add a notice about adblock.
			newViewer.prepend(
				$('<p/>', {style: 'background: white; border: 2px solid black;', text: `
					MangaHere has moved to using an image host which is blacklisted by some AdBlockers.
					If you can't see any images, you will need to whitelist "mhcdn.secure.footprint.net".
				`})
			);

			$('#viewer').replaceWith(newViewer); //Set base viewer div

			//We can't CSRF to the subdomain for some reason, so we need to use a GM function here...
			GM_xmlhttpRequest({
				url     : 'https:'+_this.chapter_url.replace('www.mangahere.cc/manga', 'm.mangahere.co/roll_manga'),
				method  : 'GET',
				onload  : function(response) {
					let data = response.responseText,
					    imageList = $(data.replace(/^[\s\S]*(<div class="mangaread-main">[\s\S]*<\/div>)[\s\S]*<div class="mangaread-operate[\s\S]*$/, '$1')).find('img.lazy[data-original]');

					// console.log(imageList);
					_this.viewerCustomImageList = imageList.map(function(i, e) {
						//NOTE: This is a temp-fix for uMatrix blocking secure.footprint.net by default due to one of the default lists containing it.
						return $(e).attr('data-original')/*.replace('https://mhcdn.secure.footprint.net', 'http://c.mhcdn.net')*/;
					});

					if(_this.viewerCustomImageList.length) {
						//Sometimes the page count on the actual site isn't accurate, but the mobile sites is. Fix when possible.
						_this.page_count = _this.viewerCustomImageList.length;

						callback(false, true);
					} else {
						console.log('trackr - Mobile site returned no images? Falling back to old loading method');
						callback(false, false);
					}

				},
				onerror : function() {
					console.log('trackr - Unable to load mobile site, fallback to old page loading method');
					callback(false, false);
				}
			});
		}
	}),

	/**
	 * Batoto
	 * @type {SiteObject}
	 */
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
					console.log('trackr - Waiting for initial page load...');
				}
			}, 1000);
			dfd.done(() => {
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
			this.chapterNumber = (chapterNParts[1] ? 'v'+chapterNParts[1]+'/' : '') + 'c'+chapterNParts[2] + (chapterNParts[3] ? '-'+chapterNParts[3] : '');

			this.title_url      = reader.find('a[href*="/comic/"]:first').attr('href');
			this.manga_language = $('select[name=group_select]:first > option:selected').text().trim().replace(/.* - ([\S]+)$/, '$1');

			this.title          = this.title_url.replace(/.*r([0-9]+)$/, '$1') + ':--:' + this.manga_language;
			this.chapter        = this.chapter_hash + ':--:' + this.chapterNumber;

			this.chapter_url    = this.https+'://bato.to/reader#'+this.chapter_hash;

			let chapterListOptions  = $('select[name=chapter_select]:first > option');
			this.chapterListCurrent = this.chapter_url;
			if(this.https === 'https') {
				chapterListOptions.each(function(i, e) {
					let value = $(e).val();
					$(e).val(value.replace(/^http/, 'https'));
				});
			}
			this.chapterList            = generateChapterList(chapterListOptions.reverseObj(), 'value');

			this.viewerChapterName      = this.chapterNumber;
			this.viewerTitle            = document.title.replace(/ - (?:vol|ch) [0-9]+.*/, '').replace(/&#(\d{1,4});/, function(fullStr, code) { return String.fromCharCode(code); });
			this.viewerChapterURLFormat = this.https+'://bato.to/areader?id='+this.chapter_hash+'&p=' + '%pageN%';
			this.viewerRegex            = /^[\s\S]+(<img id="comic_page".+?(?=>)>)[\s\S]+$/;

			this.searchURLFormat = this.https+'://bato.to/search?name={%SEARCH%}';

			if(location.hash.split('_').length > 1) {
				this.currentPage = parseInt(location.hash.split('_')[1]);
			}
		},
		preSetupViewer : function(callback) {
			let reader = $('#reader');

			reader.replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div

			if(this.is_web_toon !== 1) {
				console.log('trackr - bato.to chapter is not webtoon');
				callback();
			} else {
				console.log('trackr - bato.to chapter is webtoon');

				//Bato.to has an option for webtoons to show all chapters on a single page (with a single ajax), we need to do stuff differently if this happens.
				this.viewerCustomImageList = reader.find('#read_settings + div + div img').map(function(i, e) {
					return $(e).attr('src');
				});
				this.page_count = this.viewerCustomImageList.length;

				callback(false, true);
			}
		}
	}),

	/**
	 * Dynasty Scans
	 * @type {SiteObject}
	 */
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

			this.searchURLFormat = 'https://dynasty-scans.com/search?q={%SEARCH%}';

			if(location.hash) {
				this.currentPage = parseInt(location.hash.substring(1));
			}
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
						xhr.setRequestHeader('Cache-Control', 'no-cache, no-store');
						xhr.setRequestHeader('Pragma', 'no-cache');
					},
					cache: false,
					success: function(response) {
						response = response.replace(/^[\S\s]*(<dl class="chapter-list">[\S\s]*<\/dl>)[\S\s]*$/, '$1');
						let div = $('<div/>').append($(response));

						_this.chapterList = generateChapterList($('.chapter-list > dd > a.name', div), 'href');

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

	/**
	 * MangaPanda
	 * @type {SiteObject}
	 */
	'www.mangapanda.com' : extendSite({
		preInit : function(callback) {
			//MangaPanda is tricky. For whatever stupid reason, it decided to not use a URL format which actually separates its manga URLs from every other page on the site.
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

			this.searchURLFormat = 'http://www.mangapanda.com/search/?w={%SEARCH%}';

			if(this.segments[3]) {
				this.currentPage = parseInt(this.segments[3]);
			}
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
		postSetupTopBar : function(callback) {
			//Remove MangaFox's chapter navigation since we now have our own. Also remove leftover whitespace.
			$('#topchapter > #mangainfo ~ div, #bottomchapter > #mangainfo ~ div').remove();

			callback();
		},
		preSetupViewer : function(callback) {
			$('.episode-table').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div

			callback(true);
		}
	}),


	/**
	 * MangaStream (Alt Domain)
	 * @type {SiteObject}
	 */
	'readms.net' : extendSite({
		preInit : function(callback) {
			let ms = sites['mangastream.com'];
			this.setObjVars      = ms.setObjVars;
			this.stylize         = ms.stylize;
			this.preSetupTopBar  = ms.preSetupTopBar;
			this.postSetupTopBar = ms.postSetupTopBar;
			this.preSetupViewer  = ms.preSetupViewer;

			this.site = 'mangastream.com';

			callback();
		}
	}),

	/**
	 * MangaStream
	 * @type {SiteObject}
	 */
	'mangastream.com' : extendSite({
		setObjVars : function() {
			this.page_count  = parseInt($('.controls ul:last > li:last').text().replace(/[^0-9]/g, ''));
			this.title       = this.segments[2];
			this.chapter     = this.segments[3]+'/'+this.segments[4];

			this.title_url   = location.origin+'/manga/'+this.title;
			this.chapter_url = location.origin+'/r/'+this.title+'/'+this.chapter;

			// this.chapterList     = {}; //This is set via preSetupTopBar.
			this.chapterListCurrent = '/r/'+this.title+'/'+this.chapter+'/1'; //FIXME: MS only seems to use http urls, even if you are on https

			this.viewerChapterName      = 'c'+this.chapter.split('/')[0];
			this.viewerTitle            = $('.btn-reader-chapter > a > span:first').text();
			this.viewerChapterURLFormat = this.chapter_url + '/' + '%pageN%';
			this.viewerRegex            = /^[\S\s]*(<div class="page">[\S\s]*?(?=<\/div>)<\/div>)[\S\s]*$/;

			if(this.segments[5]) {
				this.currentPage = parseInt(this.segments[5].replace(/^([0-9]+).*$/, '$1'));
			}
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

			//We need to use AJAX as the chapter pages don't provide a full chapter list.
			$.ajax({
				url: _this.title_url,
				beforeSend: function(xhr) {
					xhr.setRequestHeader('Cache-Control', 'no-cache, no-store');
					xhr.setRequestHeader('Pragma', 'no-cache');
				},
				cache: false,
				success: function(response) {
					let table = $(response.replace(/^[\S\s]*(<table[\S\s]*<\/table>)[\S\s]*$/, '$1').replace(/\?t=[0-9]+&(amp;)?f=[0-9]+&(amp;)?e=[0-9]+/g, ''));

					_this.chapterList = generateChapterList($('tr:not(:first) a', table).reverseObj(), 'href');

					callback();
				}
			});
		},
		postSetupTopBar : function(callback) {
			$('.subnav').remove(); //Remove topbar, since we have our own

			callback();
		},
		preSetupViewer : function(callback) {
			$('.page').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div

			callback();
		}
	}),

	/**
	 * Webtoons
	 * @type {SiteObject}
	 */
	'www.webtoons.com' : extendSite({
		setObjVars : function() {
			let title_id     = window.location.search.match(/title_no=([0-9]+)/)[1],
			    chapter_id   = window.location.search.match(/episode_no=([0-9]+)/)[1];
			this.title       = title_id   + ':--:' + this.segments[1] + ':--:' + this.segments[3] + ':--:' + this.segments[2];
			this.chapter     = chapter_id + ':--:' + this.segments[4];
			this.chapterNumber = this.segments[4];

			this.title_url   = 'http://www.webtoons.com/'+this.segments[1]+'/'+this.segments[2]+'/'+this.segments[3]+'/list?title_no='+title_id;
			this.chapter_url = 'http://www.webtoons.com/'+this.segments[1]+'/'+this.segments[2]+'/'+this.segments[3]+'/'+this.segments[4]+'/viewer?title_no='+title_id+'&episode_no='+chapter_id;

			this.chapterList        = generateChapterList($('.episode_lst > .episode_cont > ul > li a'), 'href');
			this.chapterListCurrent = this.chapter_url;

			this.viewerTitle = $('.subj').text();

			this.searchURLFormat = 'http://www.webtoons.com/search?keyword={%SEARCH%}';
		}
	}),

	/**
	 * KissManga - Tracking Disabled
	 * @type {SiteObject}
	 */
	'kissmanga.com' : extendSite({
		preInit : function(callback) {
			return;

			//Kissmanga has bot protection, sometimes we need to wait for the site to load.
			if($('.cf-browser-verification').length === 0) {
				//Kissmanga has a built-in method to show all pages on the same page. Check if the cookie is correct, otherwise change and refresh.
				if(getCookie('vns_readType1') !== '0') {
					callback();
				} else {
					document.cookie = 'vns_readType1=1; expires=Fri, 6 Sep 2069 00:00:00 UTC; path=/;';
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
			this.viewerTitle           = $('title').text().trim().split('\n')[1];
			this.viewerCustomImageList = $('#divImage').find('img').map(function(i, e) {
				return $(e).attr('src');
			});
			this.page_count = this.viewerCustomImageList.length;
		},
		postSetupTopBar : function(callback) {
			let image = $('#divImage');

			//Remove extra unneeded elements.
			image.prevAll().remove();
			image.nextAll().remove();

			callback();
		},
		preSetupViewer : function(callback) {
			$('#divImage').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div

			callback(false, true);
		},

		//FIXME: KissManga banned us. SEE: https://github.com/DakuTree/manga-tracker/issues/64
		trackChapter : function(askForConfirmation) {
			if(askForConfirmation === true) {
				//Only show on alert when manually updating.
				alert('KissManga decided to IP ban our server, which means tracking is no longer possible.\nThis may be fixed at a later date, sorry for the inconvenience.');
			}
		}
	}),

	/**
	 * KireiCake Scans (FoolSlide)
	 * @type {SiteObject}
	 */
	'reader.kireicake.com' : extendSite({
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	}),

	/**
	 * Whiteout Scans (FoolSlide)
	 * @type {SiteObject}
	 */
	'reader.whiteoutscans.com' : extendSite({
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	}),

	/**
	 * Game of Scanlation
	 * @type {SiteObject}
	 */
	'gameofscanlation.moe' : extendSite({
		setObjVars : function() {
			let _this = this;
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

			let tempList = generateChapterList($('select[name=chapter_list] > option'), 'data-chapterurl');
			this.chapterList = Object.keys(tempList).reduce(function(result, key) {
				let segments = key.split('/');
				result[`projects/${_this.title.replace(/\.[0-9]+$/, '')}/${segments[2]}/`] = tempList[key];
				return result;
			}, {});

			this.chapterListCurrent = this.chapter_url.substr(29);
		},
		postSetupTopBar : function(callback) {
			$('.samBannerUnit').remove(); //Remove huge header banner.
			$('.AdBlockOn').remove(); //Remove huge header banner.

			callback();
		}
	}),

	/**
	 * MangaCow
	 * @type {SiteObject}
	 */
	'mngcow.co' : extendSite({
		setObjVars : function() {
			let _this = this;

			this.title       = this.segments[1];
			this.chapter     = this.segments[2];

			this.title_url   = 'http://mngcow.co/'+this.title+'/';
			this.chapter_url = this.title_url+this.chapter+'/';

			/** @type {(jQuery)} */
			let pageNav = $('#pageNav');

			pageNav.find('select:first > option').each(function(i, e) {
				$(e).val(_this.title_url + $(e).val() + '/');
			});
			this.chapterList        = generateChapterList(pageNav.find('select:first > option').reverseObj(), 'value');
			this.chapterListCurrent = this.chapter_url;

			this.viewerCustomImageList = $('script:contains("/wp-content/manga/")').html().match(/(http:\/\/mngcow\.co\/wp-content\/manga\/[^"]+)/g).filter(function(value, index, self) {
				return self.indexOf(value) === index;
			});
			this.page_count = this.viewerCustomImageList.length;

			this.searchURLFormat = 'http://mngcow.co/manga-list/search/{%SEARCH%}';

			if(this.segments[3]) {
				this.currentPage = parseInt(this.segments[3]);
			}
		},
		preSetupViewer : function(callback) {
			$('.wpm_nav, .wpm_ifo_box').remove();
			$('#toHome, #toTop').remove();

			$('#singleWrap').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div
			callback(true, true);
		}
	}),

	/**
	 * EG Scans
	 * @type {SiteObject}
	 */
	'read.egscans.com' : extendSite({
		preInit : function(callback) {
			if(location.pathname.indexOf('&') !== -1) {
				//EGScans seems to generate different HTML when it has parameters, let's just redirect to normal version to make things easier.
				location.pathname = location.pathname.replace(/&.*$/, '');
			} else {
				callback();
			}
		},
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

			this.viewerCustomImageList = $('script:contains("img_url.push(")').html().match(/url\.push\('(.*?')/g).filter(function(value, index, self) {
				return self.indexOf(value) === index;
			}).map(function(v) {
				return v.substr(10, v.length-11);
			});

			this.page_count = this.viewerCustomImageList.length;
		},
		preSetupViewer : function(callback) {
			$('.pager').remove();

			$('#image_frame').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div
			callback(false, true);
		}
	}),

	/**
	 * SeaOtter Scans (FoolSlide)
	 * @type {SiteObject}
	 */
	'reader.seaotterscans.com' : extendSite({
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	}),

	/**
	 * Helvetica Scans (FoolSlide)
	 * @type {SiteObject}
	 */
	'helveticascans.com' : extendSite({
		preInit : function(callback) {
			if(location.pathname.substr(0, 7) === 'reader') {
				//If old URL, redirect to new one.
				location.pathname = location.pathname.replace(/^\/reader/, '/r');
			} else {
				this.foolSlideBaseURL = this.https+'://helveticascans.com/r';
				this.setupFoolSlide();
				callback();
			}
		}
	}),

	/**
	 * Sense Scans (No subdomain)
	 * @type {SiteObject}
	 */
	'sensescans.com' : extendSite({
		preInit : function() {
			//Auto-redirect to subdomain if using non-subdomain url.
			location.href = location.href.replace('sensescans.com/reader', 'reader.sensescans.com');
		}
	}),

	/**
	 * Sense Scans (FoolSlide)
	 * @type {SiteObject}
	 */
	'reader.sensescans.com' : extendSite({
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	}),

	/**
	 * Jaimini's Box (FoolSlide)
	 * @type {SiteObject}
	 */
	'jaiminisbox.com' : extendSite({
		preInit : function(callback) {
			this.foolSlideBaseURL = this.https+'://jaiminisbox.com/reader';

			//Jaimini's Box seems to be yet another weird FoolSlide fork.
			this.viewerCustomImageList = unsafeWindow.pages.map(function(e) {
				return e.url.replace(/https:\/\/images[0-9]+-focus-opensocial\.googleusercontent\.com\/gadgets\/proxy\?container=focus\&refresh=604800\&url=/, '');
			});
			this.setupFoolSlide();

			callback();
		}
	}),

	/**
	 * Doki Fansubs (FoolSlide)
	 * @type {SiteObject}
	 */
	'kobato.hologfx.com' : extendSite({
		preInit : function(callback) {
			this.foolSlideBaseURL = this.https+'://kobato.hologfx.com/reader';
			this.setupFoolSlide();
			callback();
		}
	}),

	/**
	 * Demonic Scans (FoolSlide) - Disabled
	 * @type {SiteObject}
	 */
	'www.demonicscans.com' : extendSite({
		preInit : function(callback) {
			this.foolSlideBaseURL = this.https+'://www.demonicscans.com/FoOlSlide';
			this.setupFoolSlide();
			callback();
		}
	}),

	/**
	 * Death Toll Scans (FoolSlide)
	 * @type {SiteObject}
	 */
	'reader.deathtollscans.net' : extendSite({
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	}),

	/**
	 * One Time Scans! (FoolSlide)
	 * @type {SiteObject}
	 */
	'otscans.com' : extendSite({
		preInit : function(callback) {
			this.foolSlideBaseURL = this.https+'://otscans.com/foolslide';
			this.setupFoolSlide();
			callback();
		}
	}),

	/**
	 * S2 Scans (FoolSlide)
	 * @type {SiteObject}
	 */
	'reader.s2smanga.com' : extendSite({
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	}),

	/**
	 * ReadMangaToday (Old Domain)
	 * @type {SiteObject}
	 */
	'www.readmanga.today' : extendSite({
		preInit : function() {
			//Auto-redirect to new domain
			location.href = location.href.replace(/^https?:\/\/www\.readmanga\.today/, 'https://www.readmng.com');
		}
	}),

	/**
	 * ReadMangaToday (No www)
	 * @type {SiteObject}
	 */
	'readmng.com' : extendSite({
		preInit : function() {
			//Auto-redirect to www (Preferably we'd use non-www, however most of the site links use www.
			location.href = location.href.replace(/^https?:\/\/readmng\.com/, 'https://www.readmng.com');
		}
	}),

	/**
	 * ReadMangaToday
	 * @type {SiteObject}
	 */
	'www.readmng.com' : extendSite({
		setObjVars : function() {
			this.site = 'www.readmanga.today';

			this.segments      = window.location.pathname.replace(/^(.*\/)(?:[0-9]+\.html)?$/, '$1').split( '/' );

			//FIXME: Is there a better way to do this? It just feels like an ugly way of setting vars.
			this.page_count    = $('.list-switcher-2 > li > select[name=category_type]').get(0).length;
			this.title         = this.segments[1];
			this.chapter       = this.segments[2];

			this.title_url   = this.https + '://www.readmng.com/'+this.title+'/';
			this.chapter_url = this.title_url + this.chapter+'/';

			//Might be easier to keep chapter_url different.
			this.chapterListCurrent = this.chapter_url.slice(0,-1);
			this.chapterList        = generateChapterList($('.jump-menu[name=chapter_list] > option:gt(0)').reverseObj(), 'value');

			//this.viewerTitle            = $('.readpage_top > .title > h2').text().slice(0, -6);
			this.viewerChapterURLFormat = this.chapter_url + '%pageN%';
			this.viewerRegex            = /^[\s\S]*<div class="content-list col-md-12 page_chapter">[\s\S]*(<img[\s\S][^>]+>)[\s\S]*<!--col-md-12-->[\s\S]*$/;

			if(this.segments[3]) {
				this.currentPage = parseInt(this.segments[3]);
			}
		},
		stylize : function() {

		},
		preSetupViewer : function(callback) {
			$('.content').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div
			callback(true);
		}
	}),

	/**
	 * Meraki Scans
	 * @type {SiteObject}
	 */
	'merakiscans.com' : extendSite({
		setObjVars : function() {
			let _this = this;

			this.title       = this.segments[1];
			this.chapter     = this.segments[2];

			this.title_url   = 'http://merakiscans.com/'+this.title+'/';
			this.chapter_url = this.title_url+this.chapter+'/';

			/** @type {(jQuery)} */
			let pageNav = $('.wpm_nav');

			pageNav.find('select:first > option').each(function(i, e) {
				$(e).val(_this.title_url + $(e).val() + '/');
			});
			this.chapterList        = generateChapterList(pageNav.find('select:first > option').reverseObj(), 'value');
			this.chapterListCurrent = this.chapter_url;

			this.viewerCustomImageList = $('#longWrap').html().match(/(http:\/\/merakiscans\.com\/wp-content\/manga\/[^"]+)/g).filter(function(value, index, self) {
				return self.indexOf(value) === index;
			});
			this.page_count = this.viewerCustomImageList.length;

			this.viewerChapterName      = 'c'+this.chapter.split('/')[0];
			this.viewerTitle            = $('.ttl > a').text();

			this.searchURLFormat = 'http://merakiscans.com/manga-list/search/{%SEARCH%}';

			if(this.segments[3]) {
				this.currentPage = parseInt(this.segments[3]);
			}
		},
		postSetupTopBar : function(callback) {
			let image = $('#singleWrap');

			//Remove extra unneeded elements.
			image.prevAll().remove();
			image.nextAll().remove();

			callback();
		},
		preSetupViewer : function(callback) {
			$('.wpm_nav, .wpm_ifo_box').remove();
			$('#toHome, #toTop').remove();

			$('#singleWrap').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div
			callback(false, true);
		}
	}),

	/**
	 * Fallen Angels Scans (myMangaReaderCMS)
	 * @type {SiteObject}
	 */
	'manga.fascans.com' : extendSite({
		preInit : function(callback) {
			this.setupMyMangaReaderCMS();
			callback();
		}
	}),

	/**
	 * Mangaichi Scanlation Division (FoolSlide)
	 * @type {SiteObject}
	 */
	'mangaichiscans.mokkori.fr' : extendSite({
		preInit : function(callback) {
			this.foolSlideBaseURL = this.https+'://mangaichiscans.mokkori.fr/fs';
			this.setupFoolSlide();
			callback();
		}
	}),

	/**
	 * LHtranslation
	 * @type {SiteObject}
	 */
	'read.lhtranslation.com' : extendSite({
		preInit : function(callback) {
			//Force webtoon mode.
			if(getCookie('read_type') === '1') {
				callback();
			} else {
				document.cookie = 'read_type=1; expires=Fri, 6 Sep 2069 00:00:00 UTC; path=/;';
				location.reload();
			}
		},
		setObjVars : function() {
			this.segments = this.segments[1].match(/^read-(.*?)-chapter-([0-9\.]+)(?:-page-[0-9]+)?\.html$/);
			this.title         = this.segments[1];
			this.chapter       = this.segments[2];

			this.title_url   = this.https + `://read.lhtranslation.com/manga-${this.title}.html`;
			this.chapter_url = this.https + `://read.lhtranslation.com/read-${this.title}-chapter-${this.chapter}.html`;

			this.chapterListCurrent = `read-${this.title}-chapter-${this.chapter}.html`;
			this.chapterList        = generateChapterList($('.chapter-before:eq(0) .select-chapter > select > option').reverseObj(), 'value');

			let imgList = $('img.chapter-img');
			this.viewerCustomImageList  = imgList.map(function(i, e) {
				return $(e).attr('src');
			});
			this.page_count = imgList.length;
		},
		preSetupViewer : function(callback) {
			$('.chapter-content').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div
			callback(false, true);
		}
	}),

	/**
	 * White Cloud Pavillion
	 * @type {SiteObject}
	 */
	'whitecloudpavilion.com' : extendSite({
		preInit : function(callback) {
			this.myMangaReaderCMSBaseURL = this.https+'://whitecloudpavilion.com/manga/free';
			this.setupMyMangaReaderCMS();
			callback();
		}
	}),

	/**
	 * World Three (FoolSlide)
	 * @type {SiteObject}
	 */
	'www.slide.world-three.org' : extendSite({
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	}),

	/**
	 * Hot Chocolate Scans (FoolSlide)
	 * @type {SiteObject}
	 */
	'hotchocolatescans.com' : extendSite({
		preInit : function(callback) {
			this.foolSlideBaseURL = this.https+'://hotchocolatescans.com/fs';
			this.setupFoolSlide();
			callback();
		}
	}),

	/**
	 * Mangazuki (myMangaReaderCMS)
	 * @type {SiteObject}
	 */
	'mangazuki.co' : extendSite({
		preInit : function(callback) {
			this.setupMyMangaReaderCMS();
			callback();
		}
	}),

	/**
	 * Yummy Gummy Scans (No subdomain)
	 * @type {SiteObject}
	 */
	'ygscans.com' : extendSite({
		preInit : function() {
			//Auto-redirect to subdomain if using non-subdomain url.
			location.href = location.href.replace(/^https?:\/\/ygscans\.com\/reader/, 'http://reader.ygscans.com'); //NOTE: Subdomain doesn't have https support for some reason.
		}
	}),

	/**
	 * Yummy Gummy Scans (FoolSlide)
	 * @type {SiteObject}
	 */
	'reader.ygscans.com' : extendSite({
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	}),

	/**
	 * Champion Scans (FoolSlide)
	 * @type {SiteObject}
	 */
	'reader.championscans.com' : extendSite({
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	}),

	/**
	 * PureMashiroScans (FoolSlide)
	 * @type {SiteObject}
	 */
	'reader.puremashiro.moe' : extendSite({
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	}),

	/**
	 * RavensScans (FoolSlide)
	 * @type {SiteObject}
	 */
	'ravens-scans.com' : extendSite({
		preInit : function(callback) {
			if(location.href.indexOf('/multi/') !== -1) {
				location.href = location.href.replace('/multi/', '/lector/').replace('.0', '');
			} else {
				this.foolSlideBaseURL = this.https+'://ravens-scans.com/lector';
				this.setupFoolSlide();
				callback();
			}
		}
	}),

	/**
	 * CatScans (FoolSlide)
	 * @type {SiteObject}
	 */
	'reader.thecatscans.com' : extendSite({
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	}),

	/**
	 * HatigarmScans (FoolSlide)
	 * @type {SiteObject}
	 */
	'hatigarmscans.eu' : extendSite({
		preInit : function(callback) {
			this.foolSlideBaseURL = this.https+'://hatigarmscans.eu/hs';
			this.setupFoolSlide();
			callback();
		}
	}),

	/**
	 * PhoenixSerenade (FoolSlide)
	 * @type {SiteObject}
	 */
	'reader.serenade.moe' : extendSite({
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	}),

	/**
	 * LOLScans
	 * @type {SiteObject}
	 */
	'forums.lolscans.com' : extendSite({
		setObjVars : function() {
			let _this = this;

			this.title         = this.parameters.p;
			this.chapter       = this.parameters.c;

			this.title_url   = this.https + `://forums.lolscans.com/book/browseChapters.php?p=${this.title}&t=manga&pF=projectFolderName`;
			this.chapter_url = this.https + `://forums.lolscans.com/book/page2.php?c=${this.chapter}&p=${this.title}&t=manga&pF=projectFolderName`;

			let titleOption = $('#mySelectP').find('option:contains('+this.title.replace(/_/g, ' ')+')');
			if(titleOption.length) {
				//url - text
				/** @namespace unsafeWindow.projectFolders */
				this.chapterList = unsafeWindow.projectFolders[titleOption.val()].reduce(function(acc, chName, i) {
					let chUrl = `page2.php?c=${chName}&p=${_this.title}&t=manga&pF=projectFolderName`;
					acc[chUrl] = chName;

					return acc;
				}, {});
				this.chapterListCurrent = `page2.php?c=${this.chapter}&p=${this.title}&t=manga&pF=projectFolderName`;
			} else {
				alert('Something has went wrong when trying to generate the chapter list. Please submit a bug report.');
			}
			this.delay = 1000; //Added delay due to site using pretty size heavy scans which can slow the browser when you load all the pages at once...

			/** @namespace unsafeWindow.imgArray */
			this.viewerCustomImageList  = unsafeWindow.imgArray;
			this.page_count = this.viewerCustomImageList.length;
		},
		preSetupViewer : function(callback) {
			$('body > table:contains(Project):eq(0), body > button:eq(0)').remove();

			$('table[align=center]').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div
			callback(true, true);
		}
	}),

	/**
	 * MangaRock
	 * @type {SiteObject}
	 */
	'mangarock.com' : extendSite({
		preInit : function(callback) {
			let dfd = $.Deferred();
			let checkSelector = setInterval(function () {
				if ($('._3Oahl').text() !== '') {
					//AJAX has loaded, resolve deferred.
					dfd.resolve();
					clearInterval(checkSelector);
				} else {
					console.log('trackr - Waiting for initial page load...');
				}
			}, 1000);
			dfd.done(() => {
				callback();
			});
		},
		setObjVars : function() {
			let _this = this;

			//this.page_count    = $('.list-switcher-2 > li > select[name=category_type]').get(0).length;
			this.title         = this.segments[2].substr(10);
			let chapterID      = this.segments[4].substr(12);
			this.chapter       = chapterID + ':--:' + $('._3Oahl.ll5Bk > select > option:selected').text().replace(/^(.*?):.*?$/, '$1').replace(/Chapter /g, 'c').replace(/Vol\.([0-9]+) /, 'v$1/');

			this.title_url   = `${this.https}://mangarock.com/manga/mrs-serie-${this.title}`;
			this.chapter_url = `${this.title_url}/chapter/mrs-chapter-${chapterID}`;

			let tempList = generateChapterList($('._3Oahl.ll5Bk > select:first > option'), 'value');
			this.chapterList = Object.keys(tempList).reduce(function(result, key) {
				result[`${_this.title_url}/chapter/${key}`] = tempList[key];
				return result;
			}, {});
			this.chapterListCurrent = this.chapter_url;
		},
		stylize : function() {
			//MangaRock uses AJAX when changing chapters,
			$('#app-layout-container').on('click', '._1le-p.hBsaA.lAuaX', function(e) {
				e.preventDefault();

				$('#TrackerBarLayout').find('> a:contains("Previous")').click();
			}).on('click', '._1le-p._1b_9G.lAuaX', function(e) {
				e.preventDefault();
				$('#TrackerBarLayout').find('> a:contains("Next")').click();
			});
		}
	}),

	/**
	 * EvilFlowers (FoolSlide)
	 * @type {SiteObject}
	 */
	'reader.evilflowers.com' : extendSite({
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	}),

	/**
	 * ShoujoHearts (FoolSlide)
	 * @type {SiteObject}
	 */
	'shoujohearts.com' : extendSite({
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	}),

	/**
	 * TwistedHelScans (FoolSlide)
	 * @type {SiteObject}
	 */
	'www.twistedhelscans.com' : extendSite({
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	}),

	/**
	 * Chibi Manga (myMangaReaderCMS)
	 * @type {SiteObject}
	 */
	'www.cmreader.info' : extendSite({
		preInit : function(callback) {
			this.setupMyMangaReaderCMS();
			callback();
		}
	}),

	/**
	 * PsychoPlay
	 * @type {SiteObject}
	 */
	'psychoplay.co' : extendSite({
		setObjVars : function() {
			this.title       = this.segments[2];
			this.chapter     = this.segments[3];

			this.title_url   = this.https+'://psychoplay.co/series/'+this.title;
			this.chapter_url = this.https+'://psychoplay.co/read/'+this.title+'/'+this.chapter;

			this.chapterListCurrent = this.chapter_url;

			this.viewerChapterName      = 'c'+this.chapter;
			this.viewerTitle            = $.trim(($('.content-wrapper > div:eq(1) > div > h1 > a').text()));
			this.viewerCustomImageList  = $('.content-wrapper').find('img').map(function(i, e) {
				return $(e).attr('src');
			});
			this.page_count = this.viewerCustomImageList.length;
		},
		preSetupTopBar : function(callback) {
			let _this = this;

			//We need to use AJAX as the chapter pages don't provide a full chapter list.
			$.ajax({
				url: _this.title_url,
				beforeSend: function(xhr) {
					xhr.setRequestHeader('Cache-Control', 'no-cache, no-store');
					xhr.setRequestHeader('Pragma', 'no-cache');
				},
				cache: false,
				success: function(response) {
					let $container = $(response).wrap('<div />').parent();
					$container.find('.text-muted, .media-left, .media-right').remove();
					_this.chapterList = generateChapterList($('.media-list > li > a', $container).reverseObj(), 'href');

					callback();
				}
			});
		},
		preSetupViewer : function(callback) {
			$('.page-content').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div

			callback(false, true);
		}
	}),

	/**
	 * MangaKakalot
	 * @type {SiteObject}
	 */
	'mangakakalot.com' : extendSite({
		preInit : function(callback) {
			//Force all images on one page mode.
			if(getCookie('loadimg') === 'yes') {
				callback();
			} else {
				document.cookie = 'loadimg=yes; expires=Fri, 6 Sep 2069 00:00:00 UTC; path=/;';
				location.reload();
			}
		},
		setObjVars : function() {
			let _this = this;

			this.title       = this.segments[2];
			this.chapter     = this.segments[3].substr(8);

			this.title_url   = this.https+'://mangakakalot.com/manga/'+this.title;
			this.chapter_url = this.https+'://mangakakalot.com/chapter/'+this.title+'/chapter_'+this.chapter;

			let tempList = generateChapterList($('#c_chapter').find('> option').reverseObj(), 'value');
			this.chapterList = Object.keys(tempList).reduce(function(result, key) {
				result[`${_this.https}://mangakakalot.com/chapter/${_this.title}/chapter_${key}`] = tempList[key];
				return result;
			}, {});
			this.chapterListCurrent = this.chapter_url;

			this.viewerCustomImageList  = $('#vungdoc').find('img').map(function(i, e) {
				return $(e).attr('src');
			});
			this.page_count = this.viewerCustomImageList.length;
		},
		stylize : function() {
			$('.info-top-chapter, .option_wrap').remove();
		},
		preSetupViewer : function(callback) {
			$('#vungdoc').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div

			callback(true, true);
		}
	}),

	/**
	 * DKThiasScans (FoolSlide)
	 * @type {SiteObject}
	 */
	'reader.dkthias.com' : extendSite({
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	}),

	/**
	 * ForgottenScans (FoolSlide)
	 * @type {SiteObject}
	 */
	'reader.fos-scans.com' : extendSite({
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	}),

	/**
	 * SaikoScans (FoolSlide)
	 * @type {SiteObject}
	 */
	'saikoscans.ml' : extendSite({
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	}),

	/**
	 * ShoujoSense (FoolSlide)
	 * @type {SiteObject}
	 */
	'reader.shoujosense.com' : extendSite({
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	}),

	/**
	 * MangaTopia (FoolSlide)
	 * @type {SiteObject}
	 */
	'mangatopia.net' : extendSite({
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	}),

	/**
	 * VortexScans (FoolSlide)
	 * @type {SiteObject}
	 */
	'reader.vortex-scans.com' : extendSite({
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	}),

	/**
	 * Dokusha (FoolSlide)
	 * @type {SiteObject}
	 */
	'dokusha.info' : extendSite({
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	}),

	/**
	 * ElPsyCongroo (FoolSlide)
	 * @type {SiteObject}
	 */
	'elpsycongroo.tk' : extendSite({
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	}),

	/**
	 * Bangaqua (FoolSlide)
	 * @type {SiteObject}
	 */
	'bangaqua.com' : extendSite({
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	}),

	/**
	 * DamnFeels (FoolSlide)
	 * @type {SiteObject}
	 */
	'damn-feels.com' : extendSite({
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	}),

	/**
	 * AtelierDuNoir (FoolSlide)
	 * @type {SiteObject}
	 */
	'atelierdunoir.org' : extendSite({
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	}),

	/**
	 * Lolitannia (FoolSlide)
	 * @type {SiteObject}
	 */
	'reader.holylolikingdom.net' : extendSite({
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	}),

	/**
	 * Riceballicious (FoolSlide)
	 * @type {SiteObject}
	 */
	'riceballicious.info' : extendSite({
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	}),

	/**
	 * MangaDex
	 * @type {SiteObject}
	 */
	'mangadex.com' : extendSite({
		setObjVars : function() {
			let _this = this;

			this.title       = $('span[title="Title"] + a').attr('href').replace(/.*?\/([0-9]+)$/, '$1');
			let chapter      = this.segments[2];
			this.chapter     = chapter + ':--:' + $('#jump_chapter > option:selected').text().replace(/^(?:Vol(?:ume|\.) ([0-9\.]+)?.*?)?Ch(?:apter|\.) ([0-9\.v]+)[\s\S]*$/, 'v$1/c$2').replace(/^v\//, '');

			this.title_url   = `${this.https}://mangadex.com/manga/${this.title}`;
			this.chapter_url = `${this.https}://mangadex.com/chapter/${chapter}`;

			let tempList     = generateChapterList($('#jump_chapter').find('> option'), 'value');
			this.chapterList = Object.keys(tempList).reduce(function(result, key) {
				result[`${_this.https}://mangadex.com/chapter/${key}`] = tempList[key];
				return result;
			}, {});
			this.chapterListCurrent = this.chapter_url;

			let pageSegments = $('#current_page').attr('src').split('/'),
			    imageHash    = pageSegments[2],
				imageExt     = pageSegments[3].split('.').pop();
			this.viewerCustomImageList = $('#jump_page').find('> option').map(function(i, e) {
				let pageN = $(e).attr('value');
				return `${_this.https}://mangadex.com/data/${imageHash}/${pageN}.${imageExt}`;
			});
			this.page_count = this.viewerCustomImageList.length;
		},
		stylize : function() {
			$('.info-top-chapter, .option_wrap').remove();
		},
		preSetupViewer : function(callback) {
			//FIX: I'd like to replace 'content instead, but I kinda like the header here...
			$('#current_page').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div

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

						GM_addValueChangeListener('lastUpdatedSeries', function(name, old_value, new_value/*, remote*/) {
							//TODO: Move as much of this as possible to using the actual site functions.

							let data    = JSON.parse(new_value),
							    site    = data.manga.site,
							    title   = data.manga.title,
							    chapter = data.manga.chapter,
							    chapterNumber = data.chapterNumber,
							    url     = data.url;

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

							GM_setValue('config', JSON.stringify(config));
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
						GM_xmlhttpRequest({
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
										GM_setValue('config', JSON.stringify(config));
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
						GM_xmlhttpRequest({
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
											GM_setValue('config', JSON.stringify(config));
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
	})
};

/********************** SCRIPT *********************/
const main_site = 'https://trackr.moe';
let   config    = JSON.parse(GM_getValue('config') || '{}');
console.log(config); //This is useful for debugging.

const hostname = location.hostname.replace(/^(?:dev)\./, '');
if(!$.isEmptyObject(config) || hostname === 'trackr.moe') {
	//Config exists OR site is trackr.moe.
	if(main_site === 'https://dev.trackr.moe' && hostname !== 'trackr.moe') { config['api-key'] = config['api-key-dev']; } //Use dev API-key if using dev site
	if(!config.options) { config.options = {}; } //We can't use the 'in' operator on this if options doesn't exist.

	//NOTE: Although we load the userscript at document-start, we can't actually start poking the DOM of "most" sites until it's actually ready.
	if(sites[hostname]) {
		$(function () {
			sites[hostname].init();
		});
	} else {
		console.error(`Hostname doesn't exist in sites object? | '${hostname}'`);
	}
} else {
	alert('Tracker isn\'t setup! Go to trackr.moe/user/options to set things up.');
}
