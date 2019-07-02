(function(sites) {
	/**
	 * AtelierDuNoir (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['atelierdunoir.org'] = {
		preInit : function(callback) {
			return; // Site disabled.

			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
