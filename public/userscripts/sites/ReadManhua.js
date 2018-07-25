(function(sites) {
	/**
	 * ReadManhua (myMangaReaderCMS)
	 * @type {SiteObject}
	 */
	sites['readmanhua.net'] = {
		preInit : function(callback) {
			this.setupMyMangaReaderCMS();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
