(function(sites) {
	/**
	 * Chibi Manga (myMangaReaderCMS)
	 * @type {SiteObject}
	 */
	sites['www.cmreader.info'] = {
		preInit : function(callback) {
			this.setupMyMangaReaderCMS();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
