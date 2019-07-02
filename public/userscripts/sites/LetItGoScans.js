(function(sites) {
	/**
	 * LetItGoScans (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['reader.letitgo.scans.today'] = {
		preInit : function(callback) {
			return; // Site disabled.

			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
