(function(sites) {
	/**
	 * ElPsyCongroo (FoolSlide) (Alt Domain)
	 * @type {SiteObject}
	 */
	sites['elpsycongroo.pw'] = {
		preInit : function() {
			location.href = location.href.replace('elpsycongroo.pw', 'elpsycongroo.tk');
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
