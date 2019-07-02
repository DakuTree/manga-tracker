(function(sites) {
	/**
	 * SaikoScans (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['saikoscans.ml'] = {
		preInit : function(callback) {
			return; // Site disabled.

			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
