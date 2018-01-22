(function(sites) {
	/**
	 * PhoenixSerenade (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['reader.serenade.moe'] = {
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
