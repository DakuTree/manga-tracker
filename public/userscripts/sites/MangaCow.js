(function(sites) {
	/**
	 * MangaCow (GlossyBright)
	 * @type {SiteObject}
	 */
	sites['mangacow.ws'] = {
		preInit : function(callback) {
			return; // Site disabled.

			this.setupGlossyBright();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
