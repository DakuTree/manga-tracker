(function(sites) {
	/**
	 * Bangaqua (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['bangaqua.com'] = {
		preInit : function(callback) {
			return; // Site disabled.

			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
