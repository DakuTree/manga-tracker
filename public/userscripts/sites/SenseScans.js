(function(sites) {
	/**
	 * Sense Scans (No subdomain)
	 * @type {SiteObject}
	 */
	sites['sensescans.com'] = {
		preInit : function() {
			//Auto-redirect to subdomain if using non-subdomain url.
			location.href = location.href.replace('sensescans.com/reader', 'reader.sensescans.com');
		}
	};

	/**
	 * Sense Scans (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['reader.sensescans.com'] = {
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
