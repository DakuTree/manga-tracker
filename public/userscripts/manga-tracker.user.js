// ==UserScript==
// @name         Manga Tracker
// @namespace    https://github.com/DakuTree/manga-tracker
// @author       Daku (admin@codeanimu.net)
// @description  A cross-site manga tracker.
// @homepageURL  https://trackr.moe
// @supportURL   https://github.com/DakuTree/manga-tracker/issues
// @icon         https://trackr.moe/favicon.png
// @include      /^https:\/\/trackr\.moe(\/.*$|$)/
// @include      /^https?:\/\/fanfox\.net\/manga\/.+\/(?:.*\/)?.*\/.*$/
// @include      /^https?:\/\/(?:www\.)?mangahere\.c[o|c]\/manga\/.+\/.*\/?.*\/.*$/
// @include      /^https?:\/\/bato\.to\/reader.*$/
// @include      /^https:/\/dynasty-scans\.com\/chapters\/.+$/
// @include      /^https:\/\/www\.mangapanda\.com\/(?!(?:search|privacy|latest|alphabetical|popular|random)).+\/.+$/
// @include      /^https?:\/\/readms\.net\/r\/.+\/.+\/[0-9]+(?:\/[0-9]+)?(?:\?.+)?$/
// @include      /^https?:\/\/mangastream\.com\/r(ead)?\/.+\/.+\/[0-9]+(?:\/[0-9]+)?(?:\?.+)?$/
// @include      /^https?:\/\/www\.webtoons\.com\/(?:en|zh-hant|zh-hans|th|id)\/[a-z0-9A-Z-_]+\/[a-z0-9A-Z-_]+\/[a-z0-9A-Z-_]+\/viewer\?title_no=[0-9]+&episode_no=[0-9]+$/
// @include      /^http:\/\/kissmanga\.com\/Manga\/[a-zA-Z0-9-_]+\/[a-zA-Z0-9-_%]+\?id=[0-9]+$/
// @include      /^https?:\/\/reader\.kireicake\.com\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^https?:\/\/reader\.whiteoutscans\.com\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^https?:\/\/reader\.seaotterscans\.com\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^https?:\/\/(reader\.)?sensescans\.com\/(reader\/)?read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^https:\/\/helveticascans\.com\/r(?:eader)?\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^https:\/\/gameofscanlation\.moe\/projects\/[a-z0-9-]+\/[a-z0-9\.-]+\/.*$/
// @include      /^http:\/\/mangacow\.ws\/[a-zA-Z0-9_-]+\/[0-9\.]+\/([0-9]+\/)?$/
// @include      /^https:\/\/jaiminisbox\.com\/reader\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^https:\/\/kobato\.hologfx\.com\/reader\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^https?:\/\/merakiscans\.com\/[a-zA-Z0-9_-]+\/[0-9\.]+\/([0-9]+\/?)?$/
// @include      /^http:\/\/www\.demonicscans\.com\/FoOlSlide\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^https?:\/\/reader\.deathtollscans\.net\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^http:\/\/read\.egscans\.com\/[A-Za-z0-9\-_\!,]+\/?(?:Chapter_[0-9]+(?:_?extra)?(?:&display=(default|webtoon))?\/?)?$/
// @include      /^https:\/\/otscans\.com\/foolslide\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^https?:\/\/reader\.s2smanga\.com\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^https?:\/\/(?:www\.)?(?:readmanga\.today|readmng\.com)\/[^\/]+(\/.*)?$/
// @include      /^https?:\/\/manga\.fascans\.com\/[a-z]+\/[a-zA-Z0-9_-]+\/[0-9\.]+[\/]*[0-9]*$/
// @include      /^http?:\/\/mangaichiscans\.mokkori\.fr\/fs\/read\/.*?\/[a-z]+\/[0-9]+(\/.*)?$/
// @include      /^http:\/\/read\.lhtranslation\.com\/read-(.*?)-chapter-[0-9\.]+(?:-page-[0-9]+)?\.html$/
// @include      /^https?:\/\/(?:www\.)?whitecloudpavilion\.com\/manga\/free\/manga\/.*?\/[0-9\.]+(\/.*)?$/
// @include      /^http:\/\/www\.slide\.world-three\.org\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^http:\/\/hotchocolatescans\.com\/fs\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^https?:\/\/mangazuki\.co\/[a-z]+\/[a-zA-Z0-9_-]+\/[0-9\.]+[\/]*[0-9]*$/
// @include      /^https?:\/\/(reader\.)?ygscans\.com\/(reader\/)?read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^https?:\/\/reader\.championscans\.com\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^http:\/\/reader\.puremashiro\.moe\/read\/.*?\/[a-z\-]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^http:\/\/ravens-scans\.com\/(?:multi|lector)\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9\.]+(\/.*)?$/
// @include      /^https?:\/\/reader\.thecatscans\.com\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^https?:\/\/(?:www\.)?hatigarmscans\.net\/[a-z]+\/[a-zA-Z0-9_-]+\/[a-zA-Z0-9\._-]+[\/]*[0-9]*$/
// @include      /^https?:\/\/reader\.serenade\.moe\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^https?:\/\/forums\.lolscans\.com\/book\/page2\.php\?c=.*?&t=webcomic&pF=projectFolderName$/
// @include      /^https?:\/\/mangarock\.com($|\/.*?$)/
// @include      /^http:\/\/reader\.evilflowers\.com\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^http:\/\/shoujohearts\.com\/reader\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^http:\/\/www\.twistedhelscans\.com\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^https?:\/\/www\.cmreader\.info\/[a-z]+\/[a-zA-Z0-9_-]+\/[a-zA-Z0-9\.-]+[\/]*[0-9]*$/
// @include      /^https?:\/\/reader\.sascans\.com\/[a-z]+\/[a-zA-Z0-9_-]+\/[a-zA-Z0-9\.-]+[\/]*[0-9]*$/
// @include      /^https?:\/\/psychoplay\.co\/read\/[a-zA-Z0-9_-]+\/[0-9\.]+$/
// @include      /^https?:\/\/sodascans\.me\/read\/[a-zA-Z0-9_-]+\/[0-9\.]+$/
// @include      /^http:\/\/mangakakalot\.com\/chapter\/[a-zA-Z_\-0-9]+\/chapter_[0-9\.]+$/
// @include      /^http:\/\/reader\.dkthias\.com\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^http:\/\/reader\.fos-scans\.com\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^http:\/\/saikoscans\.ml\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^http:\/\/reader\.shoujosense\.com\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^http:\/\/mangatopia\.net\/slide\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^https?:\/\/reader\.vortex-scans\.com\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^http:\/\/dokusha\.info\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^https?:\/\/elpsykongroo\.pw\/r\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^http:\/\/bangaqua\.com\/reader\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^https?:\/\/damn-feels\.com\/reader\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^http:\/\/atelierdunoir\.org\/reader\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^http:\/\/reader\.holylolikingdom\.net\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^http:\/\/riceballicious\.info\/fs\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^https?:\/\/(?:www\.)?mangadex\.org\/chapter\/[0-9]+(?:\/[0-9]+)?$/
// @include      /^https?:\/\/reader\.tukimoop\.pw\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^http:\/\/reader\.roseliascans\.com\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^https?:\/\/taptaptaptaptap\.net\/fs\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^http:\/\/reader\.letitgo\.scans\.today\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^https:\/\/trashscanlations\.com\/series\/[a-zA-Z0-9_-]+\/(?:[0-9]+-[0-9]+\/)?(?:oneshot|(?:chapter-)?[0-9a-zA-Z\.\-_]+)\/(?:$|\?.*?)$/
// @include      /^https:\/\/zeroscans\.com\/manga\/[a-zA-Z0-9_-]+\/(?:[0-9]+-[0-9]+\/)?(?:oneshot|(?:chapter-)?[0-9a-zA-Z\.\-_]+)\/(?:$|\?.*?)$/
// @include      /^https?:\/\/reader\.naniscans\.xyz\/read\/.*?\/[a-z]+\/[0-9]+\/[0-9]+(\/.*)?$/
// @include      /^https:\/\/readmanhua\.net\/[a-z]+\/[a-zA-Z0-9_-]+\/[0-9\.]+[\/]*[0-9]*$/
// @include      /^https?:\/\/wowescans\.net\/[a-z]+\/[a-zA-Z0-9_-]+\/[0-9\.]+[\/]*[0-9]*$/
// @updated      2018-08-18
// @version      1.12.17
// @downloadURL  https://trackr.moe/userscripts/manga-tracker.user.js
// @updateURL    https://trackr.moe/userscripts/manga-tracker.meta.js
// @require      https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js
// @require      https://greasemonkey.github.io/gm4-polyfill/gm4-polyfill.js
// @require      https://cdn.rawgit.com/flaviusmatis/easyModal.js/48cdbdfe/jquery.easyModal.js
// @require      https://trackr.moe/userscripts/sites/_trackr.moe.11.js
// @require      https://trackr.moe/userscripts/sites/AtelierDuNoir.2.js
// @require      https://trackr.moe/userscripts/sites/Bangaqua.js
// @require      https://trackr.moe/userscripts/sites/Batoto.3.js
// @require      https://trackr.moe/userscripts/sites/CatScans.js
// @require      https://trackr.moe/userscripts/sites/ChampionScans.js
// @require      https://trackr.moe/userscripts/sites/ChibiManga.js
// @require      https://trackr.moe/userscripts/sites/DamnFeels.js
// @require      https://trackr.moe/userscripts/sites/DeathTollScans.js
// @require      https://trackr.moe/userscripts/sites/DemonicScans.js
// @require      https://trackr.moe/userscripts/sites/DKThiasScans.js
// @require      https://trackr.moe/userscripts/sites/DokiFansubs.js
// @require      https://trackr.moe/userscripts/sites/Dokusha.js
// @require      https://trackr.moe/userscripts/sites/DynastyScans.3.js
// @require      https://trackr.moe/userscripts/sites/EGScans.2.js
// @require      https://trackr.moe/userscripts/sites/ElPsyCongroo.3.js
// @require      https://trackr.moe/userscripts/sites/EvilFlowers.js
// @require      https://trackr.moe/userscripts/sites/FallenAngelsScans.js
// @require      https://trackr.moe/userscripts/sites/ForgottenScans.js
// @require      https://trackr.moe/userscripts/sites/GameOfScanlation.2.js
// @require      https://trackr.moe/userscripts/sites/HatigarmScans.2.js
// @require      https://trackr.moe/userscripts/sites/HelveticaScans.js
// @require      https://trackr.moe/userscripts/sites/HotChocolateScans.js
// @require      https://trackr.moe/userscripts/sites/JaiminisBox.js
// @require      https://trackr.moe/userscripts/sites/KireiCake.js
// @require      https://trackr.moe/userscripts/sites/KissManga.2.js
// @require      https://trackr.moe/userscripts/sites/LetItGoScans.1.js
// @require      https://trackr.moe/userscripts/sites/LHTranslation.2.js
// @require      https://trackr.moe/userscripts/sites/Lolitannia.js
// @require      https://trackr.moe/userscripts/sites/LOLScans.2.js
// @require      https://trackr.moe/userscripts/sites/MangaCow.3.js
// @require      https://trackr.moe/userscripts/sites/MangaDex.31.js
// @require      https://trackr.moe/userscripts/sites/MangaFox.3.js
// @require      https://trackr.moe/userscripts/sites/MangaHere.5.js
// @require      https://trackr.moe/userscripts/sites/MangaichiScans.js
// @require      https://trackr.moe/userscripts/sites/MangaKakalot.2.js
// @require      https://trackr.moe/userscripts/sites/MangaPanda.3.js
// @require      https://trackr.moe/userscripts/sites/MangaRock.5.js
// @require      https://trackr.moe/userscripts/sites/MangaStream.4.js
// @require      https://trackr.moe/userscripts/sites/MangaTopia.js
// @require      https://trackr.moe/userscripts/sites/Mangazuki.js
// @require      https://trackr.moe/userscripts/sites/MerakiScans.3.js
// @require      https://trackr.moe/userscripts/sites/NaniScans.1.js
// @require      https://trackr.moe/userscripts/sites/OneTimeScans.js
// @require      https://trackr.moe/userscripts/sites/PhoenixSerenade.js
// @require      https://trackr.moe/userscripts/sites/PsychoPlay.2.js
// @require      https://trackr.moe/userscripts/sites/PureMashiroScans.js
// @require      https://trackr.moe/userscripts/sites/RavensScans.js
// @require      https://trackr.moe/userscripts/sites/ReadMangaToday.2.js
// @require      https://trackr.moe/userscripts/sites/ReadManhua.1.js
// @require      https://trackr.moe/userscripts/sites/Riceballicious.js
// @require      https://trackr.moe/userscripts/sites/RoseliaScans.1.js
// @require      https://trackr.moe/userscripts/sites/S2Scans.js
// @require      https://trackr.moe/userscripts/sites/SAScans.1.js
// @require      https://trackr.moe/userscripts/sites/SaikoScans.js
// @require      https://trackr.moe/userscripts/sites/SeaOtterScans.js
// @require      https://trackr.moe/userscripts/sites/SenseScans.js
// @require      https://trackr.moe/userscripts/sites/ShoujoHearts.js
// @require      https://trackr.moe/userscripts/sites/ShoujoSense.js
// @require      https://trackr.moe/userscripts/sites/SodaScans.1.js
// @require      https://trackr.moe/userscripts/sites/TapTrans.1.js
// @require      https://trackr.moe/userscripts/sites/TrashScanlations.1.js
// @require      https://trackr.moe/userscripts/sites/TukiScans.1.js
// @require      https://trackr.moe/userscripts/sites/TwistedHelScans.js
// @require      https://trackr.moe/userscripts/sites/VortexScans.js
// @require      https://trackr.moe/userscripts/sites/WebToons.3.js
// @require      https://trackr.moe/userscripts/sites/WhiteCloudPavillion.2.js
// @require      https://trackr.moe/userscripts/sites/WhiteoutScans.js
// @require      https://trackr.moe/userscripts/sites/WorldThree.js
// @require      https://trackr.moe/userscripts/sites/WoweScans.1.js
// @require      https://trackr.moe/userscripts/sites/YummyGummyScans.js
// @require      https://trackr.moe/userscripts/sites/ZeroScans.1.js
// @resource     fontAwesome    https://use.fontawesome.com/9533173d07.css
// @resource     userscriptCSS  https://trackr.moe/userscripts/assets/main.11.css
// @resource     userscriptLESS https://trackr.moe/userscripts/assets/main.11.less
// @resource     reload         https://trackr.moe/userscripts/assets/reload.png
// @grant        GM_addStyle
// @grant        GM_getResourceURL
// @grant        GM.getResourceUrl
// @grant        GM_getValue
// @grant        GM.getValue
// @grant        GM_setValue
// @grant        GM.setValue
// @grant        GM_addValueChangeListener
// @grant        GM.addValueChangeListener
// @grant        GM_xmlhttpRequest
// @grant        GM.xmlHttpRequest
// @grant        unsafeWindow
// @noframes
// @connect      trackr.moe
// @connect      myanimelist.net
// @connect      m.mangafox.me
// @connect      m.mangafox.la
// @connect      m.fanfox.net
// @connect      mangahere.cc
// @run-at       document-start
// ==/UserScript==
/** jshint asi=false, bitwise=true, boss=false, browser=true, browserify=false, camelcase=false, couch=false, curly=true, debug=false, devel=true, dojo=false, elision=false, enforceall=false, eqeqeq=true, eqnull=false, es3=false, es5=false, esnext=false, esversion=6, evil=false, expr=false, forin=true, freeze=false, funcscope=false, futurehostile=false, gcl=true, globalstrict=false, immed=false, iterator=false, jasmine=false, jquery=true, lastsemic=false, latedef=false, laxbreak=false, laxcomma=false, loopfunc=false, maxerr=50, mocha=false, module=true, mootools=false, moz=false, multistr=false, newcap=false, noarg=true, nocomma=false, node=false, noempty=false, nomen=false, nonbsp=false, nonew=true, nonstandard=false, notypeof=false, noyield=false, onevar=false, passfail=false, phantom=false, plusplus=false, proto=false, prototypejs=false, qunit=false, quotmark=single, rhino=false, scripturl=false, shadow=false, shelljs=false, singleGroups=false, smarttabs=true, strict=true, sub=false, supernew=false, trailing=true, typed=false, undef=true, unused=true, validthis=false, varstmt=true, white=true, withstmt=false, worker=false, wsh=false, yui=false **/
/* global $, jQuery, GM, GM_addStyle, GM_getResourceUrl, GM_getValue, GM_setValue, GM.xmlHttpRequest, mal_sync, GM_addValueChangeListener, unsafeWindow */
'use strict';

const userscriptDebug   = false; //TODO: Move to a userscript option.
const userscriptVersion = GM.info.script.version;
const userscriptIsDev   = GM.info.script.resources.find(function(r) { return r.name === 'userscriptCSS'; }).url.includes('manga-tracker.localhost'); // manga-tracker.localhost is the default docker hostname

// Testing grounds for sites! Use this to test new sites, as well updates for existing sites. This will overwrite required files.
(function(sites) {
	//sites['example.com'] = {};
})(window.trackerSites = (window.trackerSites || {}));


/* * * * * * * * * * Site Functions * * * * * * * * * */
function main() {
	if(!$.isEmptyObject(config) || hostname === 'trackr.moe') {
		//Config exists OR site is trackr.moe.
		if(main_site === 'http://manga-tracker.localhost:20180' && hostname !== 'trackr.moe') { config['api-key'] = config['api-key-dev']; } //Use dev API-key if using dev site
		if(!config.options) { config.options = {}; } //We can't use the 'in' operator on this if options doesn't exist.

		//NOTE: Although we load the userscript at document-start, we can't actually start poking the DOM of "most" sites until it's actually ready.
		if(window.sites[hostname]) {
			if(hostname === 'trackr.moe') {
				//trackr.moe needs to utilize these to avoid duplicating code
				unsafeWindow.userscriptVersion = userscriptVersion;
				unsafeWindow.versionCompare    = versionCompare;
			}
			$(function () {
				window.sites[hostname].init();
			});
		} else {
			console.error(`Hostname doesn't exist in sites object? | '${hostname}'`);
		}
	} else {
		alert('Tracker isn\'t setup! Go to trackr.moe/user/options to set things up.');
	}
}

/**
 * Base container model for relevant functions and variables.
 * @namespace
 */
const base_site = {
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
	 * Called after setupViewer. This is only called if single page loader is enabled.
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
			if(!userscriptIsDev) {
				addStyleFromResource('userscriptCSS');
			} else {
				addStyleFromResource('userscriptLESS', true).then(() => {
					$('head').append($('<script/>', {src: '//cdnjs.cloudflare.com/ajax/libs/less.js/3.7.0/less.min.js'}));
				});
			}

			let previous = (Object.keys(_this.chapterList).indexOf(_this.chapterListCurrent) > 0 ? $('<a/>', {class: 'buttonTracker', id: 'trackr-previous', href: Object.keys(_this.chapterList)[Object.keys(_this.chapterList).indexOf(_this.chapterListCurrent) - 1], text: 'Previous'}) : '');
			let next     = (Object.keys(_this.chapterList).indexOf(_this.chapterListCurrent) < (Object.keys(_this.chapterList).length - 1) ? $('<a/>', {class: 'buttonTracker', id: 'trackr-next', href: Object.keys(_this.chapterList)[Object.keys(_this.chapterList).indexOf(_this.chapterListCurrent) + 1], text: 'Next'}) : '');
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
						$('<i/>', {id: 'toggleWebtoon', class: 'fa fa-file-image-o', 'aria-hidden': 'true', title: 'Toggle Webtoon Mode'})
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
			$(topbar).on('click', '#toggleWebtoon', function(e) {
				e.preventDefault();

				$('#viewer').toggleClass('webtoon');
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

						GM.xmlHttpRequest({
							url     : main_site + '/ajax/userscript/update',
							method  : 'POST',
							data    : $.param(params),
							headers: {
								'Content-Type'         : 'application/x-www-form-urlencoded',
								'X-Userscript-Version' : userscriptVersion,
								'Referer'              : location.href
							},
							onload  : function(e) {
								_this.attemptingTrack = false;

								handleUserscriptUpdate(e.responseHeaders);

								if(e.status === 200) {
									let data = e.responseText,
									    json = JSON.parse(data);

									/** @param {{mal_sync:string, mal_id:int, chapter:string}} json **/

									GM.setValue('lastUpdatedSeries', JSON.stringify(Object.assign(params, json, {url: location.href, chapterNumber: (_this.chapterNumber !== '' ? _this.chapterNumber : _this.chapter)})));

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
		GM.xmlHttpRequest({
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
			if(chapterN < 2500) { //Hopefully this will never need to be changed again...
				let status = $('#TrackerStatus');

				GM.xmlHttpRequest({
					method: 'POST',
					url: 'https://myanimelist.net/ownlist/manga/edit.json',
					data: JSON.stringify(json),
					onload: function(response) {
						if(response.responseText !== '{"errors":[{"message":"failed to edit"}]}') {
							status.html(`Updated & <a href="https://myanimelist.net/manga/${malIDI}" class="mal-link">MAL Synced</a> (c${chapterN})`);
						} else {
							status.text('Updated (MAL missing from list, attempting to add...)');
							GM.xmlHttpRequest({
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

		//FIXME: VIEWER: Is it possible to set the size of the image element before it is loaded (to avoid pop-in)?
		//FIXME: Somehow handle the viewer header code here?

		this.preSetupViewer(function(useCustomHeader, useCustomImageList) {
			useCustomHeader    = (typeof useCustomHeader !== 'undefined' ? useCustomHeader : false);
			useCustomImageList = (typeof useCustomImageList !== 'undefined' ? useCustomImageList : false);

			let viewer = $('#viewer');
			if(_this.isWebtoon) {
				viewer.addClass('webtoon');
			}

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
			let urls = [];
			if(useCustomImageList) {
				urls = _this.viewerCustomImageList; // .slice(0) ?
			}

			for(let pageN=1; pageN<=_this.page_count; pageN++) {
				$('<div/>', {id: 'trackr-page-'+pageN, class: 'read_img'}) // this should probably be in presetupviewer in its own loop
					.append($('<div/>', {class: 'pageNumber'}).append($('<div/>', {class: 'number', text: `${pageN} / ${_this.page_count}`}))) // add page number
					.insertAfter(viewer.find('> .read_img:last'));
				if(!useCustomImageList) { // move this out of the loop once the previous line is in presetupviewer
					pagePromises.push(new Promise((resolve, reject) => { // jshint ignore:line
						let pageDelay = _this.delay + (_this.delay !== 0 ? (pageN * _this.delay) : 0);
						setTimeout(collectImagesURLs, pageDelay, urls, pageN, resolve, reject);
					}));
				}
			}

			Promise.all(pagePromises).then(() => {
				console.log('trackr - All promises resolved.');
				_this.imageLoader = _this.setupViewerContainer(urls, +!useCustomImageList);
				for (let k = 0; k < 3; k++) { // setup 3 pipelines (this will make the browser load 3 images in parallel but in order).. the number 3 should probably be smthing users can specify in their options page
					_this.imageLoader.next();
				}

				//Auto-track chapter if enabled.
				/** @namespace config.auto_track */
				if(config.options.auto_track) {
					console.log('trackr - Auto-tracking chapter');
					_this.trackChapter();
				}

				//Setup zoom event
				if(viewer.length) {
					const images = $('#viewer').find('img');
					const firstImage = images.get(0);
					const next = document.getElementById('trackr-next');
					const prev = document.getElementById('trackr-previous');
					const handleClick = function(action) {
						const newZoom = firstImage.clientWidth;

						switch(action.key) {
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

							case 'ArrowLeft':
								prev && prev.click();
								break;

							case 'ArrowRight':
								next && next.click();
								break;

							default:
								//do nothing
								break;
						}
					};
					$(document).keydown(handleClick);
				}

				_this.postSetupViewer();
			});

			//Setup favourite page event.
			$(viewer).on('click', '.favouriteChapterPage', function(e) {
				e.preventDefault();

				let page = parseInt($(this).attr('data-page'));

				_this.favouriteChapter(page);
			});

			function collectImagesURLs(urls, pageN, promiseResolve, promiseReject) {
				let url = _this.viewerChapterURLFormat.replace('%pageN%', pageN.toString());

				//FIXME: (TEMP HACK) Due to MH being weird with https redirects, we need to do this.
				//       When I get the time we should move this to the parent object so we can override it.
				if(url.includes('mangahere.cc', 0)) {
					url = url.replace('/1.html', '/');
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

							const original_image = $(data).find('img:first').addBack('img:first');
							urls[pageN] = $(original_image).data('trackr-src');
						}
						promiseResolve();
					},
					error: function () {
						promiseResolve(); // we probably should use promiseReject() here
					}
				});
			}
		});
	},

	/**
	 * A generator function that sets up the correct container for the next image and loads it.
	 *
	 * @function
	 * @alias sites.*.setupViewerContainer
	 * @name base_site.setupViewerContainer
	 *
	 * @param {Array} imgURLs
	 * @param {int}	offset
	 *
	 * @final
	 */
	setupViewerContainer : function* (imgURLs, offset = 0) {
		const _this = this;
		let pageN = 0;
		while(pageN < _this.page_count + offset) {
			const imgURL = imgURLs[pageN + offset];
			pageN += 1;
			const cb = _this.imageLoader.next.bind(_this.imageLoader);

			if (imgURL === undefined) {
				this.setupViewerContainerError(imgURL, pageN, false, cb);
			}
			else {
				this.setupViewerContainerSuccess(imgURL, pageN, cb);
			}
			yield;
		}
	},


	/**
	 * Used to setup the page container used by the viewer.
	 *
	 * @function
	 * @alias sites.*.setupViewerContainerSuccess
	 * @name base_site.setupViewerContainerSuccess
	 *
	 * @param {string} imgURL
	 * @param {int}	pageN
	 *
	 * @final
	 */
	setupViewerContainerSuccess : function(imgURL, pageN, cb = () => {}) { // cb =
		let _this = this;
		// cb = cb || $.noop;

		let image_container = $('<div/>', {id: `trackr-page-${pageN}`, class: 'read_img'}).append(
			//We want to completely recreate the image element to remove all additional attributes
			$('<img/>', {src: imgURL})
				.on('load', function() {
					_this.updatePagesLoaded(true);
					cb();
				})
				.on('error', function() {
					_this.setupViewerContainerError(imgURL, pageN, true);
				})
		);
		if(_this.page_count !== 1) {
			image_container.append(
				//Add page number
				$('<div/>', {class: 'pageNumber'}).append(
					$('<div/>', {class: 'number', text: `${pageN} / ${_this.page_count} `}).append(
						$('<i/>', {class: 'fa fa-star favouriteChapterPage', 'aria-hidden': 'true', 'data-page': pageN, title: 'Click to favourite this page (Requires series to be tracked first!)'})
					)
				)
			);
		}

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
	setupViewerContainerError : async function(pageURL, pageN, imgLoadFailed, cb = () => {}) { // jshint ignore:line
		let _this = this;
		_this.updatePagesLoaded(false);

		let reloadUrl = await GM.getResourceUrl('reload');

		console.error('setupViewerContainerError called');
		let image_container = $('<div/>', {class: 'read_img', id: 'trackr-page-'+pageN}).append(
			$('<img/>', {style: 'cursor: pointer', src: reloadUrl}).click(function() {
				if(!imgLoadFailed) {
					//Page load failed
					$.ajax({
						url    : pageURL,
						type   : 'GET',
						page   : pageN,
						// async: useASync,
						success: function (data) {
							let original_image = $(data.replace(_this.viewerRegex, '$1')).find('img:first').addBack('img:first');
							_this.setupViewerContainerSuccess($(original_image).attr('src'), this.page);
						},
						error: function () {
							alert('Failed to load image again. Something may be wrong with the site.');
							_this.setupViewerContainerError(pageURL, this.page, false);
						}
					});
				} else {
					//Image load failed
					_this.setupViewerContainerSuccess(`${pageURL}?` + new Date().getTime(), pageN);
				}
			})
		).append(
			//Add page number
			$('<div/>', {class: 'pageNumber'}).append(
				$('<div/>', {class: 'number', text: `${pageN} / ${_this.page_count}`}))
		);

		//Replace the placeholder image_container with the real one
		$('#trackr-page-'+pageN).replaceWith(image_container);

		cb();
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
	favouriteChapter : function(page = null) {
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
			if(page) {
				params.manga.page = page;
			}

			GM.xmlHttpRequest({
				url     : main_site + '/ajax/userscript/favourite',
				method  : 'POST',
				data    : $.param(params),
				headers: {
					'Content-Type'         : 'application/x-www-form-urlencoded',
					'X-Userscript-Version' : userscriptVersion,
					'Referer'              : location.href
				},
				onload  : function(e) {
					handleUserscriptUpdate(e.responseHeaders);

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
			this.chapterList        = generateChapterList($('.topbar_left > div:has([class^=dropdown]):eq(1) > ul > li > a').reverseObj(), 'href');
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

			this.isWebtoon = ($('#page').find('a img').length > 1);
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
			if(this.segments[2]) {
				this.currentPage = parseInt(this.segments[2]);
			}
		};

		this.preSetupViewer = function(callback) {
			$('.viewer-cnt').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div
			callback(true, true);
		};
		this.postSetupViewer = function(/*topbar*/) {
			let viewer = $('.viewer-cnt');

			//Remove extra unneeded elements.
			viewer.prevAll().remove();
			viewer.nextAll().remove();
		}
	},

	/**
	 * Used to setup sites that using the GlossyBright WordPress theme.
	 *
	 * @function
	 * @alias sites.*.setupGlossyBright
	 * @name base_site.setupGlossyBright
	 *
	 * @final
	 */
	setupGlossyBright : function() {
		this.setObjVars = function() {
			let _this = this;

			this.title       = this.segments[1];
			this.chapter     = this.segments[2];

			this.title_url   = `${this.baseURL}/${this.title}/`;
			this.chapter_url = this.title_url +  this.chapter + '/';

			let chapterSelect = $('.cbo_wpm_chp:first > option');
			chapterSelect.each(function(i, e) {
				$(e).val(_this.title_url + $(e).val() + '/');
			});
			this.chapterList        = window.generateChapterList(chapterSelect.reverseObj(), 'value');
			this.chapterListCurrent = this.chapter_url;

			this.viewerCustomImageList = $('script:contains("/wp-content/manga/"), #longWrap').last().html().match(/(https?:\/\/.*?\/wp-content\/manga\/[^"]+)/g).filter(function(value, index, self) {
				return self.indexOf(value) === index;
			});
			this.page_count = this.viewerCustomImageList.length;

			this.searchURLFormat = `${this.baseURL}/manga-list/search/{%SEARCH%}`;

			if(this.segments[3]) {
				this.currentPage = parseInt(this.segments[3]);
			}
		};

		this.preSetupViewer = function(callback) {
			$('.wpm_nav, .wpm_ifo_box').remove();
			$('#toHome, #toTop').remove();

			$('#singleWrap').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div
			callback(true, true);
		};
	},

	/**
	 * Used to setup sites that using Roku - https://github.com/OneAutumnLeaf/Roku
	 *
	 * @function
	 * @alias sites.*.setupRoku
	 * @name base_site.setupRoku
	 *
	 * @final
	 */
	setupRoku : function() {
		this.setObjVars = function() {
			this.title       = this.segments[2];
			this.chapter     = this.segments[3];

			this.title_url   = `${this.baseURL}/series/${this.title}`;
			this.chapter_url = `${this.baseURL}/read/${this.title}/${this.chapter}`;

			this.chapterListCurrent = this.chapter_url;

			this.viewerChapterName      = `c${this.chapter}`;
			this.viewerTitle            = $.trim(($('.content-wrapper > div:eq(1) > div > h1 > a').text()));
			this.viewerCustomImageList  = $('.content-wrapper').find('img').map(function(i, e) {
				return $(e).attr('src');
			});
			this.page_count = this.viewerCustomImageList.length;

			//CHECK: Does Roku support page URLs?
		};
		this.preSetupTopBar = function(callback) {
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
					_this.chapterList = window.generateChapterList($('.media-list > li > a', $container).reverseObj(), 'href');

					callback();
				}
			});
		};
		this.preSetupViewer = function(callback) {
			$('.page-content').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div

			callback(false, true);
		};
	},

	/**
	 * Used to setup sites that using WP Manga - http://xhanch.com/wp-manga/
	 *
	 * @function
	 * @alias sites.*.setupWPManga
	 * @name base_site.setupWPManga
	 *
	 * @final
	 */
	setupWPManga : function() {
		this.setObjVars = function() {
			//NOTE: We can't override preInit here, so we need to put this here
			//Force webtoon mode.
			if(window.location.search !== "?style=list") {
				window.location.href += '?style=list';
			}

			let type           = this.segments[1];
			this.title         = this.segments[2];
			if(this.segments.length === 5) {
				this.chapter = this.segments[3];
			} else {
				this.chapter = `${this.segments[3]}/${this.segments[4]}`;
			}

			this.title_url   = `${this.baseURL}/${type}/${this.title}`;
			this.chapter_url = `${this.baseURL}/${type}/${this.title}/${this.chapter}`;

			this.chapterListCurrent = this.chapter_url + '?style=list';
			this.chapterList        = window.generateChapterList($('.single-chapter-select > option'), 'data-redirect');
			let imgList = $('img.wp-manga-chapter-img');
			this.viewerCustomImageList  = imgList.map(function(i, e) {
				return $(e).attr('src');
			});

			this.page_count = imgList.length;
		};
		this.preSetupViewer = function(callback) {
			$('.read-container').replaceWith($('<div/>', {id: 'viewer', class:'read-container'})); //Set base viewer div
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
	site    : location.hostname,

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

	/**
	 * Marks if the viewer is a webtoon and handles it appropriately.
	 * @type {Boolean}
	 */
	isWebtoon : false,

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

	/**
	 * Generator object that loads the image
	 * @type {Generator}
	 */
	imageLoader: null,

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

	baseURL : location.origin,

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
window.generateChapterList = function(target, attrURL) {
	let chapterList = {};
	if(target instanceof jQuery) {
		$(target).each(function() {
			chapterList[$(this).attr(attrURL)] = $(this).text().trim();
		});
	} else {
		//TODO: Throw error
	}
	return chapterList;
};

function initializeSites() {
	let siteKeys = Object.keys(window.trackerSites);
	for (let i = 0, l = siteKeys.length; i < l; i++) {
		let domain = siteKeys[i],
		    siteC  = window.trackerSites[domain];
		if(!window.sites[domain]) { window.sites[domain] = extendSite(siteC); } //Don't add if in testing area.
	}
}

/* * * * * * * * * * General Functions * * * * * * * * * */

async function addStyleFromResource(resourceName, isLess = false) {
	//Userscript extensions don't seem to handle GM_getResourceUrl the same, so we need to fix that.
	let GMblob = await GM.getResourceUrl(resourceName);

	let cssEle = null;
	if(GMblob.substr(0, 5) === 'blob:') {
		//ViolentMonkey
		//This is kind of a hack, but these blob: URLs don't work with css containing // includes, which means we need to convert it to base64 somehow.

		let xhr = new XMLHttpRequest();
		xhr.open('GET', GMblob, true);
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
				if(isLess) cssEle.attr('type', 'text/less');
				$('head').append(cssEle);
			}
		};
		xhr.send();
	} else {
		//Other
		cssEle = $('<style/>', {type: 'text/css', text: atob(GMblob.replace(/^data:.*?;base64,/, ''))});
		if(isLess) cssEle.attr('type', 'text/less');
		$('head').append(cssEle);
	}
}

window.getCookie = function(k){return(document.cookie.match(new RegExp('(^|; )'+k+'=([^;]*)'))||0)[2];};

function hasEmptyValues(o) {
	return Object.keys(o).some(function(x) {
		return o[x]===''||o[x]===null;  // or just "return o[x];" for falsy values
	});
}

jQuery.fn.reverseObj = function() {
	return $(this.get().reverse());
};

// https://gist.github.com/mmazer/5404301
function parseResponseHeaders(headerStr) {
	let headers = {};
	if (!headerStr) return headers;

	let headerPairs = headerStr.split('\u000d\u000a');
	for (let i = 0, len = headerPairs.length; i < len; i++) {
		let headerPair = headerPairs[i],
		    index = headerPair.indexOf('\u003a\u0020');
		if (index > 0) {
			let key = headerPair.substring(0, index),
			    val = headerPair.substring(index + 2);
			headers[key] = val;
		}
	}
	return headers;
}

function handleUserscriptUpdate(headers) {
	let updateAvailable = parseInt(parseResponseHeaders(headers)['x-userscript-update-available']);
	if(updateAvailable) {
		if($('#modal-userscript').length > 0) {
			$('#modal-userscript').trigger('openModal');
		} else {
			let style = $('<style/>', {
				type: 'text/css', text: `
					#modal-userscript {
						background: #FF5722;
						text-align: center;
						width: 600px;
						padding: 5px 0;
						color: #FFF;
						text-shadow: 0 1px 0 rgba(0,0,0,0.25);
						box-shadow: 1px 1px 3px rgba(0,0,0,0.5);
						font-weight: 600;
					}
				`
			});
			$('head').append(style);

			let modal = $('<div/>', {id: 'modal-userscript', style: 'display: none'});
			modal.html(`Userscript version is behind the version reported by the server.<br/>Click <a href='https://trackr.moe/userscripts/manga-tracker.user.js'>here</a> to manually update to the latest version.`);
			modal.appendTo('body');

			$('#modal-userscript').easyModal(
				{
					autoOpen      : true,
					overlayOpacity: 0.3,
					overlayColor  : "#333"
				}
			);
		}
	}
}

// https://stackoverflow.com/a/6832721
function versionCompare(v1, v2, options) {
	var lexicographical = options && options.lexicographical,
	    zeroExtend = options && options.zeroExtend,
	    v1parts = v1.split('.'),
	    v2parts = v2.split('.');

	function isValidPart(x) {
		return (lexicographical ? /^\d+[A-Za-z]*$/ : /^\d+$/).test(x);
	}

	if (!v1parts.every(isValidPart) || !v2parts.every(isValidPart)) {
		return NaN;
	}

	if (zeroExtend) {
		while (v1parts.length < v2parts.length) v1parts.push("0");
		while (v2parts.length < v1parts.length) v2parts.push("0");
	}

	if (!lexicographical) {
		v1parts = v1parts.map(Number);
		v2parts = v2parts.map(Number);
	}

	for (let i = 0; i < v1parts.length; ++i) {
		if (v2parts.length === i) {
			return 1;
		}

		if (v1parts[i] === v2parts[i]) {
			continue;
		}
		else if (v1parts[i] > v2parts[i]) {
			return 1;
		}
		else {
			return -1;
		}
	}

	if (v1parts.length !== v2parts.length) {
		return -1;
	}

	return 0;
}

/* * * * * * * * * * Main Script * * * * * * * * * */
/* jshint ignore:start*/
(async function() {
	//FIXME: ViolentMonkey is weird with @require scripts and needs us to use window to allow global variables...
	//       We should really look into tweaking/rewriting this stuff..
	window.main_site = 'https://trackr.moe';
	window.hostname  = location.hostname;
	let pConfig = await GM.getValue('config');
	window.config    = JSON.parse(pConfig || '{}');
	if(userscriptDebug) { console.log(window.config); }

	window.sites = {};
	initializeSites();

	main();
})();
/* jshint ignore:end*/
