(function(sites) {
	/**
	 * Helvetica Scans (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['helveticascans.com'] = {
		preInit : function(callback) {
			if(location.pathname.substr(0, 7) === 'reader') {
				//If old URL, redirect to new one.
				location.pathname = location.pathname.replace(/^\/reader/, '/r');
			} else {
				this.foolSlideBaseURL = this.https+'://helveticascans.com/r';
				this.setupFoolSlide();
				callback();
			}
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
