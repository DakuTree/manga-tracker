(function(sites) {
	/**
	 * Fallen Angels Scans (myMangaReaderCMS)
	 * @type {SiteObject}
	 */
	sites['manga.fascans.com'] = {
		preInit : function(callback) {
			this.setupMyMangaReaderCMS();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
