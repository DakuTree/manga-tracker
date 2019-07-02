(function(sites) {
	/**
	 * Hot Chocolate Scans (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['hotchocolatescans.com'] = {
		preInit : function(callback) {
			return; // Site disabled.

			this.foolSlideBaseURL = this.https+'://hotchocolatescans.com/fs';
			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
