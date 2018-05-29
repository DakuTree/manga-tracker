(function(sites) {
	/**
	 * HatigarmScans (www)
	 * @type {SiteObject}
	 */
	sites['www.hatigarmscans.net'] = {
		preInit : function() {
			//Auto-redirect to non-www version.
			location.href = location.href.replace('www.hatigarmscans.net', 'hatigarmscans.net');
		}
	};

	/**
	 * HatigarmScans (myMangaReaderCMS)
	 * @type {SiteObject}
	 */
	sites['hatigarmscans.net'] = {
		preInit : function(callback) {
			this.setupMyMangaReaderCMS();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
