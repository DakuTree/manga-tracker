(function(sites) {
	/**
	 * Dokusha (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['dokusha.info'] = {
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
