(function(sites) {
	/**
	 * MangaCow (GlossyBright)
	 * @type {SiteObject}
	 */
	sites['mngcow.co'] = {
		preInit : function(callback) {
			this.setupGlossyBright();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
