(function(sites) {
	/**
	 * DamnFeels (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['damn-feels.com'] = {
		preInit : function(callback) {
			return; // Site disabled.

			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
