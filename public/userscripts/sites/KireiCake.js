(function(sites) {
	/**
	 * KireiCake (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['reader.kireicake.com'] = {
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
