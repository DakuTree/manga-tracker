(function(sites) {
	/**
	 * Death Toll Scans (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['reader.deathtollscans.net'] = {
		preInit : function(callback) {
			this.setupFoolSlide();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
