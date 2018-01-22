(function(sites) {
	/**
	 * Champion Scans (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['reader.championscans.com'] = {
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
