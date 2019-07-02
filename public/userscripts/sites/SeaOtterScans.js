(function(sites) {
	/**
	 * SeaOtter Scans (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['reader.seaotterscans.com'] = {
		preInit : function(callback) {
			return; // Site disabled.

			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
