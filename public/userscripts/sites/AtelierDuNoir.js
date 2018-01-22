(function(sites) {
	/**
	 * AtelierDuNoir (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['atelierdunoir.org'] = {
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
