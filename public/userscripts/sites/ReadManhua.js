(function(sites) {
	/**
	 * ReadManhua (myMangaReaderCMS)
	 * @type {SiteObject}
	 */
	sites['readmanhua.net'] = {
		preInit : function(callback) {
			return; // Site disabled.

			this.setupMyMangaReaderCMS();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
