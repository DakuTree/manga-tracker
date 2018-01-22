(function(sites) {
	/**
	 * CatScans (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['reader.thecatscans.com'] = {
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
