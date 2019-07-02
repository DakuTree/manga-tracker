(function(sites) {
	/**
	 * PureMashiroScans (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['reader.puremashiro.moe'] = {
		preInit : function(callback) {
			return; // Site disabled.

			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
