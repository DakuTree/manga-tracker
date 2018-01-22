(function(sites) {
	/**
	 * Whiteout Scans (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['reader.whiteoutscans.com'] = {
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
