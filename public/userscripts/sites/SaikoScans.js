(function(sites) {
	/**
	 * SaikoScans (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['saikoscans.ml'] = {
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
