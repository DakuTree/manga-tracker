(function(sites) {
	/**
	 * ForgottenScans (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['reader.fos-scans.com'] = {
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
