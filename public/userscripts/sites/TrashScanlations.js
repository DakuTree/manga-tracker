(function(sites) {
	/**
	 * Trash Scanlations
	 * @type {SiteObject}
	 */
	sites['trashscanlations.com'] = {
		preInit : function(callback) {
			this.setupWPManga();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
