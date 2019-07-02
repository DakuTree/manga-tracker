(function(sites) {
	/**
	 * Riceballicious (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['riceballicious.info'] = {
		preInit : function(callback) {
			return; // Site disabled.

			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
