(function(sites) {
	/**
	 * RavensScans (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['ravens-scans.com'] = {
		preInit : function(callback) {
			return; // Site disabled.

			if(location.href.indexOf('/multi/') !== -1) {
				location.href = location.href.replace('/multi/', '/lector/').replace('.0', '');
			} else {
				this.foolSlideBaseURL = this.https+'://ravens-scans.com/lector';
				this.setupFoolSlide();
				callback();
			}
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
