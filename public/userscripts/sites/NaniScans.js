(function(sites) {
	/**
	 * NaniScans (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['reader.naniscans.xyz'] = {
		preInit : function(callback) {
			return; // Site disabled.

			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
