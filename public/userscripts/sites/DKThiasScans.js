(function(sites) {
	/**
	 * DKThiasScans (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['reader.dkthias.com'] = {
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
