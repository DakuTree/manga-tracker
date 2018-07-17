(function(sites) {
	/**
	 * Zero Scans
	 * @type {SiteObject}
	 */
	sites['zeroscans.com'] = {
		preInit : function(callback) {
			this.setupWPManga();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
