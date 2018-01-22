(function(sites) {
	/**
	 * ElPsyCongroo (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['elpsycongroo.tk'] = {
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
