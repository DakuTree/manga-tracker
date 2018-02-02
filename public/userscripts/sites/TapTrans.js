(function(sites) {
	/**
	 * TapTrans (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['taptaptaptaptap.net'] = {
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
