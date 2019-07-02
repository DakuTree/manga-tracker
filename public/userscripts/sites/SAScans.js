(function(sites) {
	/**
	 * SAScans (myMangaReaderCMS)
	 * @type {SiteObject}
	 */
	sites['reader.sascans.com'] = {
		preInit : function(callback) {
			return; // Site disabled.

			this.setupMyMangaReaderCMS();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
