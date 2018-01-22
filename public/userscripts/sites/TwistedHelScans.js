(function(sites) {
	/**
	 * TwistedHelScans (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['www.twistedhelscans.com'] = {
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
