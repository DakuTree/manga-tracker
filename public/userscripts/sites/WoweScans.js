(function(sites) {
	/**
	 * Wowe Scans (myMangaReaderCMS)
	 * @type {SiteObject}
	 */
	sites['wowescans.net'] = {
		preInit : function(callback) {
			this.setupMyMangaReaderCMS();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
