(function(sites) {
	/**
	 * PsychoPlay (Roku)
	 * @type {SiteObject}
	 */
	sites['psychoplay.co'] = {
		preInit : function(callback) {
			return; // Site disabled.

			this.setupRoku();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
