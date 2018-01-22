(function(sites) {
	/**
	 * HatigarmScans (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['hatigarmscans.eu'] = {
		preInit : function(callback) {
			this.foolSlideBaseURL = this.https+'://hatigarmscans.eu/hs';
			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
