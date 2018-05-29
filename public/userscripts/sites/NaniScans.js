(function(sites) {
	/**
	 * NaniScans (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['reader.naniscans.xyz'] = {
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
