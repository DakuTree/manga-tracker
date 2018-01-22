(function(sites) {
	/**
	 * One Time Scans! (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['otscans.com'] = {
		preInit : function(callback) {
			this.foolSlideBaseURL = this.https+'://otscans.com/foolslide';
			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
