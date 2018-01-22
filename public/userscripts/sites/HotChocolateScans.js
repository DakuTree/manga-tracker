(function(sites) {
	/**
	 * Hot Chocolate Scans (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['hotchocolatescans.com'] = {
		preInit : function(callback) {
			this.foolSlideBaseURL = this.https+'://hotchocolatescans.com/fs';
			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
