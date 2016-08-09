// ==UserScript==
// @name         Manga Tracker
// @namespace    https://github.com/DakuTree/userscripts
// @author       Daku (admin@codeanimu.net)
// @description  A cross-site manga tracker.
// @homepageURL  https://tracker.codeanimu.net
// @supportURL   https://github.com/DakuTree/manga-tracker/issues
// @include      /^https:\/\/(?:(?:dev|test)\.)?tracker\.codeanimu\.net\/user\/options.*$/
// @include      /^http:\/\/mangafox\.me\/manga\/.+\/(?:.*\/)?.*\/.*$/
// @include      /^http:\/\/(?:www\.)?mangahere\.co\/manga\/.+\/.*\/?.*\/.*$/
// @include      /^http:\/\/bato\.to\/reader.*$/
// @include      /^http:/\/dynasty-scans\.com\/chapters\/.+$/
// @include      /^http:\/\/www\.mangapanda\.com\/(?!(?:search|privacy|latest|alphabetical|popular|random)).+\/.+$/
// @include      /^https?:\/\/mangastream.com\/r\/.+\/.+\/[0-9]+(?:\/[0-9]+)?$/
// @include      /^http:\/\/www\.webtoons\.com\/(?:en|zh-hant|zh-hans|th|id)\/[a-z0-9A-Z-_]+\/[a-z0-9A-Z-_]+\/[a-z0-9A-Z-_]+\/viewer\?title_no=[0-9]+&episode_no=[0-9]+$/
// @include      /^http:\/\/kissmanga\.com\/Manga\/[a-zA-Z0-9-_]+\/[a-zA-Z0-9-_]+\?id=[0-9]+$/
// @updated      2016-XX-XX
// @version      0.9.0
// @require      http://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js
// @grant        GM_addStyle
// @grant        GM_getValue
// @grant        GM_setValue
// @run-at       document-start
// ==/UserScript==
/* jshint -W097, browser:true, devel:true, multistr:true */
/* global $:false, jQuery:false, GM_addStyle:false, GM_getValue, GM_setValue */
'use strict';

/* CORE TODO
Get a proper logo for the topbar (so we're not just using the AMR one anymore.
Setup events for topbar favourites, stop tracking. Unsure how exactly we should go about "stop tracking" though?
Get an actual working place to view your tracking stuff. Preferably similar to NovelUpdates.
*/

var mtBase64       = 'data:image/png;base64,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';
var bookmarkBase64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAACaElEQVQ4jX2SvU+TURTGf/d96QtYChT5CB8BU40I0SA4oA6Kootx5i8wxkaKKIszEzFRAaFGXVwciHEwaIwhDjL4FXFwESQBJQw0BQq0lL5t33scWjQgcJMnJzn3OU+e556LiLAXxh419o72+7p2u99zOHjD8G0sPEssTvWHB/0U7cQx2OM0NLXezisqyN1fdaDUV9/QtRNnV4Fgl1lz/OSFDuIhSEVpOXXp6sA15d7Oy9neGOpULsPIqWhqbukrLikoIhYCLCqr62oaG4/0BgPGHUdLODAsGkCJCK/7qrqrag+cLfQU1nqKi8vdnkKv2+12I2nAykDlImKxvhaNxtaWI9FIOLS2Gp5TIsL4gG/0TFvbZSyBdAJEwHFAVCalmBngAuUCrTI1Gs9EmPgx25mKx/PbTre2m1YCkmsgKdBp0A44AhpwsmKmBzuxj7Fvn14oEQHgnl/VHav2Pjnf7LtoGnEQnXEgm8M6CyFpW/J2cn5keiUW+CsAcNevalqqi56eO1rZnhHgn4DWoAWdEnkzFXo+GYn7e4KyvGWNPQ9lPrJuf8BOg+1A0slU2wFbZ+GoSCI51hOU5R3XWJZv1ZPK2gVAgc4+qgOGhlLL1bDrPygxzYPYacAEBxZjGxsmGN68/FzEgXQKr2E2bvK3RBjsVKVlOTm1aDfLq6b9bmbp/cjMgn9kNtQ9Phf5srpupaGAcsM8dP+6cv3nwFJU2ElDffwdnvi+svR4XeTlrWEJDQaU8TW68upnbLWjxeO9UijK7VKUAKEtWxgKqBJDOGEL0zeH5df2eIMBZWjNYUtRrRWfOx9I7A+InWebTg8pngAAAABJRU5ErkJggg==';
var trackBase64    = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAAXNSR0IArs4c6QAAAAZiS0dEAP8A/wD/oL2nkwAAAAlwSFlzAAALEwAACxMBAJqcGAAAAAd0SU1FB9sFBAwYLqR4MpkAAAIhSURBVDjLzZLPaxNREMe/u91sSkISBTGxJcaA4EFbETy2FAQNmFxavXmQIJV4EIIpniTdg5coAb0IkUBzkiT4BxRECoVaFFtKwORiiLgRIfTH1jS7b9++3fUQs1hqvHjxC8OXBzOfN8wM8I/ifn/k83khFApdNQw6wwMR0zRBGWuKovtNMplcBWAPBVSr1TlVVfOh4MkzkfExNA5EaIQiMqJAltv29t7elsvlupdKpd4fAVQqFcnj8WRnpqe4nuDH4rsf6GgcdlQTIzbDo4tA0Gjj7coqNS0rmclkXg0AfLFYvOn1erOJRILzBY7h8fo+bpzzYT68C3+tDJ0aeLBOcSocxezcrGhZVkmSpGkHQAjJxmIxDgA+dXR4RkWcYNuAvAX+42t4PyzB4F1Y/qwh4PMiHo+7er3e8wFAoJQySikEQYBtA/5REc+WKnhyNoCXt27/SlsGGoDWAI4DuKvrl54OOuB5PlMul03GGC4E3djtqohevwMAMBUFRqcDXZZBWi2o9Tp6tRr219acIfLpdHpFUZT5QqHAvn5p4f6kGzuqCQCwKIWl67AIgaVpfSfkz2vM5XJThJAX4fGxidORKM5/a0OXZafYZgwWIbANAwe1Gi5vbHBHDgkAJ0mLV7rd7rWFicmHpNVyfrYZc0C9en0owNH3UslW63Wnbdsw+m6a0JpNB8D/7c5txvphGH03TdiMHcoRhhUfbG4emvb/q59HqiRjuY+xSgAAAABJRU5ErkJggg==';
var currentBase64  = 'data:image/gif;base64,R0lGODlhEAAQAHcAACH/C05FVFNDQVBFMi4wAwEAAAAh+QQJCgBEACwAAAAAEAAQAIeyPgbynlb+znrybh7+umb+9q66WiLyikL+qkr2upb+ljbqRgL2zrr+6tr+wm7qVg7+9ur2pnrufi7uZhr+nk7+smb2kk66Uhr+4pr6jjr+rk7mTgb62qb+ynb++vLufk7+okK2Rg7+1n76tob6ypb+8ub+wn7mXiL2fibqZib2mlL+okrudjr+unb+hir+qk72wqb+lkbmSgL+1rr67uL+wnbqWhb+9vLygjL+jjL+rlbqUgr+3sb++vb+oka2RhL+1oruai72mloAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAIyQCJCCRywcCFgxdCDFxIxEABDBiAiOjg4AfDhgUEaEBQ44UGDQQsDnRYA0SLCj1McADhQ4fICwUc5FBQgoQHmytWKHih0ACQDC5c1LxB4kaCGS5W9BSQoUGGEgmMGuWBIobFCxUk3IhQgsaNBjcYzBhA4aqKBw14QLjBlu2MByos/sCxI2rbtjB2BLhABECQDQ943GX7YcIIkQBsLDjhtW0CwwYWAjghw0aQy0FOjIjMEECKFCwOWBCy+aJAACFC/PhxQaTp1wsDAgA7';

$.fn.reverseObj = [].reverse;
function escapeHTML(html) { return $('<div/>').text(html).html(); }
function getCookie(k){return(document.cookie.match('(^|; )'+k+'=([^;]*)')||0)[2];}

/***********************************************************************************************************/

var base_site = {
	init : function() {
		var _this = this;

		this.preInit(function() {
			_this.setObjVars();

			_this.stylize();

			_this.setupTopBar();

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
		var _this = this;

		this.preSetupTopBar(function() {
			GM_addStyle("\
				#TrackerBar { height: 0; position: fixed !important; z-index: 10000000 !important; top: 0 !important; width: 100% !important; /*text-align:center!important; height:30px!important;*/ opacity: .9 !important; -webkit-transition: all .4s ease-in-out !important; padding: 0 !important; margin: 0 !important; 	color: black; }\
				#TrackerBar:hover { opacity: 1 !important; }\
				#TrackerBarIn { padding: 2px 15px !important; margin: 0 !important; border-bottom-left-radius: 6px 6px !important; border-bottom-right-radius: 6px 6px !important; border: 1px solid #CCC !important; border-top: 0 !important; opacity: 1 !important; background-color: #fff !important; /*display:inline-block!important;*/ padding-left: 15px !important; padding-right: 15px !important; }\
				#TrackerBarIn img,.TrackerBarLayout img { vertical-align: middle !important; margin-left: 5px !important; margin-right: 5px !important; cursor: pointer !important; }\
				#TrackerBarIn div { padding: 0 !important; margin: 0 !important; }\
				#TrackerBarIn .buttonTracker,.TrackerBarLayout .buttonTracker { vertical-align: middle !important; }\
				#TrackerBarIn select,.TrackerBarLayout select { vertical-align: middle !important; color: initial; background-color: initial; border: 1px solid black; }\
				#TrackerBarIn a,.TrackerBarLayout a { vertical-align: middle !important; }\
				#TrackerBarIn select { margin: 0 !important; }\
				#TrackerBarInLtl { padding: 0 !important; margin: 0 !important; opacity: .7 !important; }\
				#TrackerBarInLtl:hover { opacity: 1 !important; }\
				a.buttonTracker { display: inline-block; min-width: 100px; border-image-source: initial; border-image-slice: initial; border-image-width: initial; border-image-outset: initial; border-image-repeat: initial; text-align: center; cursor: pointer; font-size: 10pt; color: rgb(0, 0, 0); text-decoration: none; padding: 2px; border-width: 1px; border-style: solid; border-color: rgb(221, 221, 221); background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(rgb(255, 255, 255)), to(rgb(238, 238, 238))); border-radius: 5px; transition: all 0.4s ease-in-out; margin: 5px; }\
				a.buttonTracker:hover { color:#003C82!important; border-color:#3278BE; text-decoration:none!important; background:-webkit-gradient(linear, 0% 0%, 0% 100%, from(#EEE), to(#FFFFFF)); }\
				a.buttonTracker:active { background:#4195DD; background:-webkit-gradient(linear, 0% 0%, 0% 100%, from(#003C82), to(#4195DD)); background:-moz-linear-gradient(0% 90% 90deg, #4195DD, #003C82); }\
			");

			var topbar = $('<div/>', {id: 'TrackerBar', style: 'text-align: center'}).append(
				$('<div/>', {id: 'TrackerBarIn', style: 'display: inline-block'}).append(
					$('<a/>', {href: main_site, target: '_blank'}).append(
						$('<img/>', {src: mtBase64, width: '20px'}))).append(
					$('<div/>', {class: 'TrackerBarLayout', style: 'display: inline-block'}).append(
						(Object.keys(_this.chapterList).indexOf(_this.chapterListCurrent) > 0 ? $('<a/>', {class: 'buttonTracker', href: Object.keys(_this.chapterList)[Object.keys(_this.chapterList).indexOf(_this.chapterListCurrent) - 1], text: 'Previous'}) : "")).append(
						$('<select/>', {style: 'float: none; max-width: 943px'}).append(
							$.map(_this.chapterList, function(k, v) {var o = $('<option/>', {value: v, text: k}); if(_this.chapterListCurrent == v) {o.attr('selected', '1');} return o.get();}))).append(
						(Object.keys(_this.chapterList).indexOf(_this.chapterListCurrent) < (Object.keys(_this.chapterList).length - 1) ? $('<a/>', {class: 'buttonTracker', href: Object.keys(_this.chapterList)[Object.keys(_this.chapterList).indexOf(_this.chapterListCurrent) + 1], text: 'Next'}) : "")).append(
						// $('<img/>', {class: 'bookAMR', src: bookmarkBase64, title: 'Click here to bookmark this chapter'})).append(
						// $('<img/>', {class: 'trackStop', src: trackBase64, title: 'Stop following updates for this manga'})).append(
						$('<img/>', {id: 'trackCurrentChapter', src: currentBase64, title: 'Mark this chapter as latest chapter read'}))).append(
					/*$('<div/>', {style: 'display: inline-block'}).append(
						$('<img/>', {src: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQBAMAAADt3eJSAAAAA3NCSVQICAjb4U/gAAAAGFBMVEW/v7/////V1dXu7u7Gxsbc3NzMzMzl5eW5mFoUAAAACXBIWXMAAAsSAAALEgHS3X78AAAAH3RFWHRTb2Z0d2FyZQBNYWNyb21lZGlhIEZpcmV3b3JrcyA4tWjSeAAAABZ0RVh0Q3JlYXRpb24gVGltZQAwNi8xNi8wNoxlAQMAAABwSURBVAiZLY4xCoAwFEMDle5pEVerF2hFdBX1At6gqOgsgue3X3zDJ0N+EpBN1DUJmvHuS5fEEUg7E2ZjonPwQYRVqPix4jRIuAfKDkAWPBQ9vnMyBxY+Yo5azOm9nVgoCSwuCeT+V9Dou49S+s94AbiAEUwMbfYNAAAAAElFTkSuQmCC', title: 'Hide AMR Toolbar', width: '16px'})))).append(
					$('<div/>', {id: 'TrackerBarInLtl', style: 'display: none'}).append(
						$('<img/>', {src: '#', style: 'margin-top: -10px; margin-left: -10px; cursor: pointer;', title: 'Display AMR Toolbar', width: '40px'})*/
					)
			);

			$(topbar).appendTo('body');

			//Setup select chapter change event
			$(topbar).on('change', 'select', function(e) {
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
			});

			_this.postSetupTopBar(topbar);
		});
	},
	trackChapter : function(askForConfirmation) {
		askForConfirmation = (typeof askForConfirmation !== 'undefined' ? askForConfirmation : false);

		if(config['api-key']) {
			var json = {
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
				//TODO: Add some basic checking for success here.
				$.post(main_site + '/ajax/userscript/update', json);
			}
		} else {
			alert('API Key isn\'t set.'); //TODO: This should give the user more info on how to fix.
		}
	},
	setupViewer : function() {
		var _this = this;

		//FIXME: VIEWER: Is it possible to make sure the pages load in order without using async: false?
		//FIXME: VIEWER: Is it possible to set the size of the image element before it is loaded (to avoid pop-in)?
		//FIXME: Somehow handle the viewer header code here?

		this.preSetupViewer(function(useCustomHeader, useCustomImageList) {
			useCustomHeader    = (typeof useCustomHeader !== 'undefined' ? useCustomHeader : false);
			useCustomImageList = (typeof useCustomImageList !== 'undefined' ? useCustomImageList : false);

			GM_addStyle('\
				#viewer                  { width: auto; max-width: 95%; margin: 0 auto !important; text-align: center; background: inherit; border: inherit; }\
				#viewer > .read_img      { background: none; }\
				#viewer > .read_img  img { width: auto; max-width: 95%; border: 5px solid #a9a9a9; /*background: #FFF repeat-y; background: url("http://mangafox.me/media/loading.gif") no-repeat center;*/ min-height: 300px;}\
				.pageNumber              { border-image-source: initial; border-image-slice: initial; border-image-width: initial; border-image-outset: initial; border-image-repeat: initial; border-collapse: collapse; background-color: black; color: white; /*height: 18px; */font-size: 12px; font-family: Verdana; font-weight: bold; position: relative; bottom: 11px; width: 50px; text-align: center; opacity: 0.75; border-width: 2px; border-style: solid; border-color: white; border-radius: 16px !important; margin: 0px auto !important; padding: 0px !important; border-spacing: 0px !important;}\
				.pageNumber .number      { border-collapse: collapse; text-align: center; display: table-cell; width: 50px; height: 18px; vertical-align: middle; border-spacing: 0px !important; padding: 0px !important; margin: 0px !important;\
				#viewer_header { font-weight: bolder; text-align: center; }\
			');

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
			for(var pageN=1; pageN<=_this.page_count; pageN++) {
				if(pageN == 1) {
					$('<div/>', {id: 'page-'+pageN, class: 'read_img'}).appendTo($('#viewer'));
				} else {
					$('<div/>', {id: 'page-'+pageN, class: 'read_img'}).insertAfter($('#viewer > .read_img:last'));
				}

				if(!useCustomImageList) {
					$.ajax({
						url: _this.viewerChapterURLFormat.replace('%pageN%', pageN),
						type: 'GET',
						page: pageN,
						//async: false,
						success: function(data) {
							var original_image  = $(data.replace(_this.viewerRegex, '$1')).find('img:first').addBack('img:first');
							var image_container = $('<div/>', {class: 'read_img'}).append(
								//We want to completely recreate the image element to remove all additional attributes
								$('<img/>', {src: $(original_image).attr('src')})).append(
								//Add page number
								$('<div/>', {class: 'pageNumber'}).append(
									$('<div/>', {class: 'number', text: this.page}))
							);

							//Replace the placeholder image_container with the real one
							$('#page-'+this.page).replaceWith(image_container);
						}
					});
				} else {
					//FIXME: We should probably split this and the above into a seperate function to avoid code duplication...
					var image_container = $('<div/>', {class: 'read_img'}).append(
						//We want to completely recreate the image element to remove all additional attributes
						$('<img/>', {src: _this.viewerCustomImageList[pageN-1]})).append(
						//Add page number
						$('<div/>', {class: 'pageNumber'}).append(
							$('<div/>', {class: 'number', text: pageN}))
					);

					//Replace the placeholder image_container with the real one
					$('#page-'+pageN).replaceWith(image_container);
				}
			}

			//Auto-track chapter if enabled.
			$(window).on("load", function() {
				if(config.auto_track && config.auto_track == 'on') {
					_this.trackChapter();
				}
			});

			_this.postSetupViewer();
		});
	},


	/** Variables **/
	//Used for tracking.
	site    : location.hostname.replace(/^(?:dev|test)\./, ''),
	title   : '',
	chapter : '',

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
	viewerCustomImageList  : [] //This is is only used if useCustomImageList is true
};
function extendSite(o) { return Object.assign({}, base_site, o); }
function generateChapterList(target, attrURL) {
	var chapterList = {};
	if(target instanceof jQuery) {
		$(target).each(function() {
			chapterList[$(this).attr(attrURL)] = $(this).text().trim();
		});
	} else {
		//TODO: Throw error
	}
	return chapterList;
}

var sites = {
	'mangafox.me' : extendSite({
		setObjVars : function () {
			var segments     = window.location.pathname.replace(/^(.*\/)(?:[0-9]+\.html)?$/, '$1').split( '/' );

			this.title       = segments[2];
			this.chapter     = (!!segments[4] ? segments[3]+'/'+segments[4] : segments[3]);

			this.page_count  = $('#top_bar .prev_page + div').text().trim().replace(/^[\s\S]*of ([0-9]+)$/, '$1');

			this.title_url   = 'http://mangafox.me/manga/'+this.title+'/';
			this.chapter_url = 'http://mangafox.me/manga/'+this.title+'/'+this.chapter+'/';

			this.chapterListCurrent = this.chapter_url;
			this.chapterList        = {}; //This is set via preSetupTopbar

			this.viewerChapterURLFormat = this.chapter_url + '%pageN%'+'.html';
			this.viewerRegex            = /^[\s\S]*(<div class="read_img">[\s\S]*<\/div>)[\s\S]*<div id="MarketGid[\s\S]*$/;
		},
		stylize : function() {
			//This removes the old border/background. The viewer adds borders to the images now instead which looks better.
			$('#viewer').css({
				'background' : 'none',
				'border'     : '0'
			});

			//Remove page count from the header, since all pages are loaded at once now.
			$('#tool > #series > strong:eq(1)').remove();

			//Float title in the header to the right. This just looks nicer and is a bit easier to read.
			$('#tool > #series > strong:last').css('float', 'right');
		},
		preSetupTopBar : function(callback) {
			var _this = this;

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
					var div = $('<div/>').append($(response));

					$("#chapters > .chlist > li > div > a + * > a", div).reverseObj().each(function() {
						var chapterTitle     = $('+ span.title', this).text().trim();
						var url              = $(this).attr('href').replace(/^(.*\/)(?:[0-9]+\.html)?$/, '$1'); //Remove trailing page number
						var realChapterTitle = url.replace(/^.*\/manga\/[^/]+\/(?:v(.*?)\/)?c(.*?)\/$/, 'Vol.$1 Ch.$2').replace(/^Vol\. /, '') + (chapterTitle !=='' ? ': '+chapterTitle : '');

						_this.chapterList[url] = realChapterTitle;
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

			callback();
		}
	}),

	'www.mangahere.co' : extendSite({
		//MangaHere uses pretty much the same site format as MangaFox, with a few odd changes.
		setObjVars : function() {
			var segments       = window.location.pathname.replace(/^(.*\/)(?:[0-9]+\.html)?$/, '$1').split( '/' );

			//FIXME: Is there a better way to do this? It just feels like an ugly way of setting vars.
			this.page_count    = $('.go_page:first > .right > select > option').length;
			this.title         = segments[2];
			this.chapter       = (!!segments[4] ? segments[3]+'/'+segments[4] : segments[3]);

			this.title_url   = 'http://www.mangahere.co/manga/'+this.title+'/';
			this.chapter_url = 'http://www.mangahere.co/manga/'+this.title+'/'+this.chapter+'/';

			this.chapterListCurrent = this.chapter_url;
			// this.chapterList        = {}; //This is set via preSetupTopbar

			this.viewerChapterURLFormat = this.chapter_url + '%pageN%'+'.html';
			this.viewerRegex            = /^[\s\S]*<section class="read_img" id="viewer">[\s\S]*(<img src[\s\S]*\/>)[\s\S]*<\/section>[\s\S]*<section class="readpage_footer[\s\S]*$/;
		},
		stylize : function() {
			GM_addStyle("\
				.read_img { min-height: 0; }\
				.readpage_top {margin-bottom: 5px;}\
				.readpage_top .title h1, .readpage_top .title h2 {font-size: 15px;}");

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
			$('.readpage_top > .title').html(function(i, html) { return html.replace('</span> / <h2', '</span><h2'); });
			$('.readpage_top > .title > span[class^=color]').remove();
			$('.readpage_top > .title h2').addClass('right');
		},
		preSetupTopBar : function(callback) {
			var _this = this;

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
					var div = $('<div/>').append($(response).find('.detail_list > ul:first'));

					$('li > span.left > a', div).reverseObj().each(function() {
						var chapterTitle     = $(this).parent().clone().children().remove().end().text().trim();

						var url              = $(this).attr('href').replace(/^(.*\/)(?:[0-9]+\.html)?$/, '$1'); //Remove trailing page number
						var realChapterTitle = url.replace(/^.*\/manga\/[^/]+\/(?:v(.*?)\/)?c(.*?)\/$/, 'Vol.$1 Ch.$2').replace(/^Vol\. /, '') + (chapterTitle !=='' ? ': '+chapterTitle : '');

						_this.chapterList[url] = realChapterTitle;
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
			var _this = this;

			//Bato.to loads the image page AFTER page load via AJAX. We need to wait for this to load.
			var dfd = $.Deferred();
			var checkSelector = setInterval(function () {
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
			var chapterNParts   = $('select[name=chapter_select]:first > option:selected').text().trim().match(/^(?:Vol\.(\S+) )?(?:Ch.([^\s:]+)):?.*/);

			this.page_count     = $('#page_select:first > option').length;
			this.is_web_toon    = ($('a[href$=_1_t]').length ? ($('a[href$=_1_t]').text() == 'Want to see this chapter per page instead?' ? 1 : 2) : 0); //0 = no, 1 = yes & long strip, 2 = yes & chapter per page

			this.chapter_hash   = location.hash.substr(1).split('_')[0];
			this.chapter_number = (chapterNParts[1] ? 'v'+chapterNParts[1]+'/' : '') + 'c'+chapterNParts[2];

			this.title_url      = $('#reader a[href*="/comic/"]:first').attr('href');
			this.manga_language = $('select[name=group_select]:first > option:selected').text().trim().replace(/.* - ([\S]+)$/, '$1');

			this.title          = this.title_url    + ':--:' + this.manga_language;
			this.chapter        = this.chapter_hash + ':--:' + this.chapter_number;
			this.chapter_url    = 'http://bato.to/reader#'+this.chapter_hash;

			this.chapterListCurrent = this.chapter_url;
			this.chapterList        = generateChapterList($('select[name=chapter_select]:first > option').reverseObj(), 'value');

			this.viewerChapterName      = this.chapter_number;
			this.viewerTitle            = document.title.replace(/ - (?:vol|ch) [0-9]+.*/, '');
			this.viewerChapterURLFormat = 'http://bato.to/areader?id='+this.chapter_hash+'&p=' + '%pageN%';
			this.viewerRegex            = /^[\s\S]+(<img id="comic_page".+?(?=>)>)[\s\S]+$/;
			this.viewerCustomImageList  = $('#reader').find('#read_settings + div + div img').map(function(i, e) {
				return $(e).attr('src');
			});
		},
		stylize : function() {
			//Nothing?
		},
		preSetupViewer : function(callback) {
			this.viewerCustomImageList = $('#reader').find('#read_settings + div + div img').map(function(i, e) {
				return $(e).attr('src');
			});

			$('#reader').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div

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
			this.is_one_shot = !$('#chapter-title > b > a').length;

			if(!this.is_one_shot) {
				this.title_url   = $('#chapter-title > b > a').attr('href').replace(/.*\/(.*)$/, '$1');
				this.chapter_url = location.pathname.split(this.title_url + '_').pop(); //There is really no other valid way to get the chapter_url :|
			} else {
				this.title_url   = location.pathname.substr(10);
				this.chapter_url = 'oneshot'; //This is labeled oneshot so it's properly handled in the backend.
			}

			this.title   = this.title_url + ':--:' + (+this.is_one_shot);
			this.chapter = this.chapter_url;

			this.chapterListCurrent = location.pathname;
			this.chapterList = {}; //This is set in preSetupTopBar

			this.viewerCustomImageList = $('script:contains("/system/releases/")').html().match(/"(\/system[^"]+)"/g).map(function(e, i) {
				return e.replace(/^"|"$/g, '');
			});
			this.page_count = this.viewerCustomImageList.length;
		},
		stylize : function() {
			//These buttons aren't needed since we have our own viewer.
			$('#chapter-actions > div > .btn-group:last, #download_page').remove();
			$('#reader').addClass('noresize');

			//Topbar covers a bunch of nav buttons.
			GM_addStyle("\
				#content > .navbar > .navbar-inner { padding-top: 42px; }");
		},
		preSetupTopBar : function(callback) {
			var _this = this;

			if(!_this.is_one_shot) {
				//Sadly, we don't have any form of inline chapterlist. We need to AJAX the title page for this one.
				$.ajax({
					url: 'http://dynasty-scans.com/series/'+_this.title_url,
					beforeSend: function(xhr) {
						xhr.setRequestHeader("Cache-Control", "no-cache, no-store");
						xhr.setRequestHeader("Pragma", "no-cache");
					},
					cache: false,
					success: function(response) {
						response = response.replace(/^[\S\s]*(<dl class="chapter-list">[\S\s]*<\/dl>)[\S\s]*$/, '$1');
						var div = $('<div/>').append($(response));

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
			var segments        = window.location.pathname.split( '/' );

			this.page_count     = parseInt($('#topchapter #selectpage select > option:last').text());
			this.title          = segments[1];
			this.chapter        = segments[2];

			this.chapterListCurrent = '/'+this.title+'/'+this.chapter;
			// this.chapterList = {}, //This is set via preSetupTopBar.

			this.title_url      = 'http://www.mangapanda.com/'+this.title+'/';
			this.chapter_url    = 'http://www.mangapanda.com/'+this.title+'/'+this.chapter+'/';

			// this.viewerChapterName      = '';
			// this.viewerTitle            = '';
			this.viewerChapterURLFormat = this.chapter_url + '%pageN%';
			this.viewerRegex            = /^[\s\S]+(<img id="img".+?(?=>)>)[\s\S]+$/;
		},
		stylize : function() {
			//Remove page count from the header, since all pages are loaded at once now.
			$('#mangainfo > div:first .c1').remove();

			//Float title in the header to the right. This just looks nicer and is a bit easier to read.
			$('#mangainfo > div + div:not(.clear)').css('float', 'right');
		},
		preSetupTopBar : function(callback) {
			var _this = this;

			//MangaPanda is tricky here. The chapter list is loaded via AJAX, and not a <script> tag. As far as I can tell, we can't watch for this to load without watching the actual element.
			//TODO: This should auto-fail after x amount of tries.
			var checkExist = setInterval(function() {
				if($('#topchapter > #selectmanga > select > option').length) {
					clearInterval(checkExist);

					_this.chapterList = generateChapterList($('#topchapter > #selectmanga > select > option'), 'value');
					callback();
				}
			}, 500);
		},
		postSetupTopBar : function(topbar) {
			//Remove MangaFox's chapter navigation since we now have our own. Also remove leftover whitespace.
			$('#topchapter > #mangainfo ~ div, #bottomchapter > #mangainfo ~ div').remove();
		},
		preSetupViewer : function(callback) {
			var _this = this;

			$('.episode-table').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div

			callback(true);
		}
	}),

	'mangastream.com' : extendSite({
		setObjVars : function() {
			var segments     = window.location.pathname.split( '/' );

			this.https       = location.protocol.slice(0, -1);

			this.page_count  = parseInt($('.controls ul:last > li:last').text().replace(/[^0-9]/g, ''));
			this.title       = segments[2];
			this.chapter     = segments[3]+'/'+segments[4];

			this.title_url   = this.https+'://mangastream.com/manga/'+this.title;
			this.chapter_url = this.https+'://mangastream.com/r/'+this.title+'/'+this.chapter;

			// this.chapterList     = {}; //This is set via preSetupTopBar.
			this.chapterListCurrent = this.chapter_url+'/1';

			this.viewerChapterName      = 'c'+this.chapter.split('/')[0];
			this.viewerTitle            = $('.btn-reader-chapter > a > span:first').text();
			this.viewerChapterURLFormat = this.chapter_url + '/' + '%pageN%';
			this.viewerRegex            = /^[\s\S]+(<div class="page">.+(?:.+)?(?=<\/div>)<\/div>)[\s\S]+$/;
		},
		stylize : function() {
			GM_addStyle("\
				.page { margin-right: 0 !important; }\
				#reader-nav { margin-bottom: 0; }");

			$('.page-wrap > #reader-sky').remove(); //Ad block
		},
		preSetupTopBar : function(callback) {
			var _this = this;

			$.ajax({
				url: _this.title_url,
				beforeSend: function(xhr) {
					xhr.setRequestHeader("Cache-Control", "no-cache, no-store");
					xhr.setRequestHeader("Pragma", "no-cache");
				},
				cache: false,
				success: function(response) {
					var table = $(response.replace(/^[\S\s]*(<table[\S\s]*<\/table>)[\S\s]*$/, '$1'));

					_this.chapterList = generateChapterList($('tr:not(:first) a', table).reverseObj(), 'href');

					callback();
				}
			});
		},
		postSetupTopBar : function() {
			$('.subnav').remove(); //Remove topbar, since we have our own
		},
		preSetupViewer : function(callback) {
			var _this = this;

			$('.page').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div

			callback();
		}
	}),

	'www.webtoons.com' : extendSite({
		setObjVars : function() {
			var segments     = window.location.pathname.split( '/' );

			var title_id     = window.location.search.match(/title_no=([0-9]+)/)[1];
			var chapter_id   = window.location.search.match(/episode_no=([0-9]+)/)[1];
			this.title       = title_id   + ':--:' + segments[1] + ':--:' + segments[3] + ':--:' + segments[2];
			this.chapter     = chapter_id + ':--:' + segments[4];

			this.title_url   = 'http://www.webtoons.com/'+segments[1]+'/'+segments[2]+'/'+segments[3]+'/list?title_no='+title_id;
			this.chapter_url = 'http://www.webtoons.com/'+segments[1]+'/'+segments[2]+'/'+segments[3]+'/'+segments[4]+'/viewer?title_no='+title_id+'&episode_no='+chapter_id;

			this.chapterList        = generateChapterList($('.episode_lst > .episode_cont > ul > li a'), 'href');
			this.chapterListCurrent = this.chapter_url;
		}
	}),

	'kissmanga.com' : extendSite({
		preInit : function(callback) {
			if(dev === true) {
				//NOTE: At the moment, KissManga support has been put on hold. We can't get the backend to work without somehow parsing the entire page (including JS).

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
			}
		},
		setObjVars : function() {
			var segments     = window.location.pathname.split( '/' );

			var chapter_id   = document.location.search.match(/id=([0-9]+)/)[1];

			this.title       = segments[2];
			this.chapter     = segments[3] + ':--:' + chapter_id;

			this.title_url   = 'http://kissmanga.com/Manga/'+this.title;
			this.chapter_url = this.title_url+'/'+segments[3]+'?id='+chapter_id;

			this.chapterList        = generateChapterList($('.selectChapter:first > option'), 'value');
			this.chapterListCurrent = segments[3]+'?id='+chapter_id;


			this.viewerChapterName     = $('.selectChapter:first > option:selected').text().trim();
			this.viewerTitle           = $('title').text().trim().split("\n")[1];
			this.viewerCustomImageList = $('#headnav + div + script').html().match(/"(http:\/\/[^"]+)"/g).map(function(e, i) {
				return e.replace(/^"|"$/g, '');
			});
			this.page_count = this.viewerCustomImageList.length;
		},
		postSetupTopBar : function() {
			//Remove extra unneeded elements.
			$('#divImage').prevAll().remove();
			$('#divImage').nextAll().remove();
		},
		preSetupViewer : function(callback) {
			$('#divImage').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div

			this.page_count = this.viewerCustomImageList.length;
			callback(false, true);
		}
	}),

	//Tracking site
	//FIXME: We <probably> shouldn't have this here, but whatever.
	'tracker.codeanimu.net' : {
		init : function() {
			/* TODO:
			Stop generating HTML here, move entirely to PHP, but disable any user input unless enabled via userscript.
			If userscript IS loaded, then insert data.
			Seperate API key from general options. Always set API config when generate is clicked.
			*/

			//Enable the form
			$('#userscript-check').remove();
			$('#userscript-form fieldset').removeAttr('disabled');
			$('#userscript-form input[type=submit]').removeAttr('onclick');

			//CHECK: Is there a better way to mass-set form values from an object/array?
			$('#userscript-form input#auto_track').attr('checked', !!config.auto_track);

			$('#userscript-form').submit(function(e) {
				var data = $(this).serializeArray().reduce(function(m,o){ m[o.name] = o.value; return m;}, {});
				if(config['api-key']) {
					data['api-key'] = config['api-key'];
					// data['init'] = false;

					GM_setValue('config', JSON.stringify(data));
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

						config['api-key'] = json['api-key'];
						GM_setValue('config', JSON.stringify(config));
					} else {
						//TODO: Handle errors here?
					}
				});
			});

			if(config.init === true) {
				//TODO: Point user to generating API key.
			}
		}
	}
};

/********************** SCRIPT *********************/
var main_site = 'https://dev.tracker.codeanimu.net';
var dev = false;

var config = JSON.parse(GM_getValue('config') || '{"init": true}');
console.log(config); //TODO: Disable on production

if(!$.isEmptyObject(config)) {
	//Config is loaded, do stuff.
	var hostname = location.hostname.replace(/^(?:dev|test)\./, '');
	if(hostname == 'tracker.codeanimu.net') {
		sites[hostname].init();
	} else if(sites[hostname]) {
		$(function() {
			sites[hostname].init();
		});
	}
} else {
	alert('Tracker isn\'t setup! Go to tracker.moe/user/options to set things up.');
}
