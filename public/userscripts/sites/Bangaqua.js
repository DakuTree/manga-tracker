(function(sites) {
	/**
	 * Bangaqua (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['bangaqua.com'] = {
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
