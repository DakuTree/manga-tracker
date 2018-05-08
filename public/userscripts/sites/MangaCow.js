(function(sites) {
	/**
	 * MangaCow (GlossyBright)
	 * @type {SiteObject}
	 */
	sites['mangacow.ws'] = {
		preInit : function(callback) {
			this.setupGlossyBright();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
