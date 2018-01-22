(function(sites) {
	/**
	 * Riceballicious (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['riceballicious.info'] = {
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
