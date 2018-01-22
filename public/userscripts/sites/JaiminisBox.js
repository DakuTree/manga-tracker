(function(sites) {
	/**
	 * Jaimini's Box (FoolSlide)
	 * @type {SiteObject}
	 */
	sites['jaiminisbox.com'] = {
		preInit : function(callback) {
			this.foolSlideBaseURL = this.https+'://jaiminisbox.com/reader';

			//Jaimini's Box seems to be yet another weird FoolSlide fork.
			this.viewerCustomImageList = unsafeWindow.pages.map(function(e) {
				return e.url.replace(/https:\/\/images[0-9]+-focus-opensocial\.googleusercontent\.com\/gadgets\/proxy\?container=focus\&refresh=604800\&url=/, '');
			});
			this.setupFoolSlide();

			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
