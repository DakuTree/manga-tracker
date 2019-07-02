(function(sites) {
	/**
	 * MangaTopia (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['mangatopia.net'] = {
		preInit : function(callback) {
			return; // Site disabled.

			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
