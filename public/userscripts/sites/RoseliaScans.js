(function(sites) {
	/**
	 * RoseliaScans (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['reader.roseliascans.com'] = {
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
