(function(sites) {
	/**
	 * MangaTopia (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['mangatopia.net'] = {
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
