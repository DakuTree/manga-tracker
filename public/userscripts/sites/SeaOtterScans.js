(function(sites) {
	/**
	 * SeaOtter Scans (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['reader.seaotterscans.com'] = {
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
