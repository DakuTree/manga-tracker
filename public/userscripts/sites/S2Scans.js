(function(sites) {
	/**
	 * S2 Scans (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['reader.s2smanga.com'] = {
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
