(function(sites) {
	/**
	 * Yummy Gummy Scans (No subdomain)
	 * @type {SiteObject}
	 */
	sites['ygscans.com'] = {
		preInit : function() {
			//Auto-redirect to subdomain if using non-subdomain url.
			location.href = location.href.replace(/^https?:\/\/ygscans\.com\/reader/, 'http://reader.ygscans.com'); //NOTE: Subdomain doesn't have https support for some reason.
		}
	};

	/**
	 * Yummy Gummy Scans (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['reader.ygscans.com'] = {
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
