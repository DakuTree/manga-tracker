(function(sites) {
	/**
	 * SAScans (myMangaReaderCMS)
	 * @type {SiteObject}
	 */
	sites['reader.sascans.com'] = {
		preInit : function(callback) {
			this.setupMyMangaReaderCMS();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
