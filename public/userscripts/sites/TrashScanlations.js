(function(sites) {
	/**
	 * Trash Scanlations
	 * @type {SiteObject}
	 */
	sites['trashscanlations.com'] = {
		preInit : function(callback) {
			return; // Site disabled.

			this.setupWPManga();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
