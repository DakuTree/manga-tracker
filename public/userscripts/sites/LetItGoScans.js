(function(sites) {
	/**
	 * LetItGoScans (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['reader.letitgo.scans.today'] = {
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
