(function(sites) {
	/**
	 * PureMashiroScans (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['reader.puremashiro.moe'] = {
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
