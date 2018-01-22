(function(sites) {
	/**
	 * Demonic Scans (FoolSlide) - Disabled
	 * @type {SiteObject}
	 */
	sites['www.demonicscans.com'] = {
		preInit : function(callback) {
			this.foolSlideBaseURL = this.https+'://www.demonicscans.com/FoOlSlide';
			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
