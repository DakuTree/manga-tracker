(function(sites) {
	/**
	 * VortexScans (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['reader.vortex-scans.com'] = {
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
