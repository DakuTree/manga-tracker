(function(sites) {
	/**
	 * EvilFlowers (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['reader.evilflowers.com'] = {
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
