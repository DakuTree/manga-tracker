(function(sites) {
	/**
	 * Mangazuki (myMangaReaderCMS)
	 * @type {SiteObject}
	 */
	sites['mangazuki.co'] = {
		preInit : function(callback) {
			this.setupMyMangaReaderCMS();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
