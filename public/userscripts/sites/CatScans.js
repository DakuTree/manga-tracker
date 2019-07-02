(function(sites) {
	/**
	 * CatScans (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['reader.thecatscans.com'] = {
		preInit : function(callback) {
			return; // Site disabled.

			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
