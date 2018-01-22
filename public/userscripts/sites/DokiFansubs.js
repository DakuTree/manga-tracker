(function(sites) {
	/**
	 * Doki Fansubs (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['kobato.hologfx.com'] = {
		preInit : function(callback) {
			this.foolSlideBaseURL = this.https+'://kobato.hologfx.com/reader';
			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
