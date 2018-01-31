(function(sites) {
	/**
	 * Meraki Scans (GlossyBright)
	 * @type {SiteObject}
	 */
	sites['merakiscans.com'] = {
		preInit : function(callback) {
			this.setupGlossyBright();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
