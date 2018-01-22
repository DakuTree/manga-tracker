(function(sites) {
	/**
	 * Lolitannia (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['reader.holylolikingdom.net'] = {
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
