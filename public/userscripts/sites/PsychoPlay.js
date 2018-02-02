(function(sites) {
	/**
	 * PsychoPlay (Roku)
	 * @type {SiteObject}
	 */
	sites['psychoplay.co'] = {
		preInit : function(callback) {
			this.setupRoku();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
