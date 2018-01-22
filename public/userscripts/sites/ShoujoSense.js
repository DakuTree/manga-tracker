(function(sites) {
	/**
	 * ShoujoSense (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['reader.shoujosense.com'] = {
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
