(function(sites) {
	/**
	 * Soda Scans (Roku)
	 * @type {SiteObject}
	 */
	sites['sodascans.me'] = {
		preInit : function(callback) {
			this.setupRoku();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
