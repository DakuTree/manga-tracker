(function(sites) {
	/**
	 * TukiScans (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['reader.tukimoop.pw'] = {
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
