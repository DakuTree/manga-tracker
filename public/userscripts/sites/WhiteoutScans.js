(function(sites) {
	/**
	 * Whiteout Scans (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['reader.whiteoutscans.com'] = {
		preInit : function(callback) {
			return; // Site disabled.

			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
