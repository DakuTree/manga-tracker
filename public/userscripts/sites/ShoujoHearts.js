(function(sites) {
	/**
	 * ShoujoHearts (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['shoujohearts.com'] = {
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
