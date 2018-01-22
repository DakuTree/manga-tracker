(function(sites) {
	/**
	 * World Three (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['www.slide.world-three.org'] = {
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
