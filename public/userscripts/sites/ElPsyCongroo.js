(function(sites) {
	/**
	 * ElPsyCongroo (FoolSlide) (New Domain)
	 * @type {SiteObject}
	 */
	sites['elpsykongroo.pw'] = {
		preInit : function(callback) {
			this.site = 'elpsycongroo.tk';
			this.setupFoolSlide();
			callback();
		}
	};
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
