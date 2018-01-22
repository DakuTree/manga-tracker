(function(sites) {
	/**
	 * White Cloud Pavillion
	 * @type {SiteObject}
	 */
	sites['whitecloudpavilion.com'] = {
		preInit : function(callback) {
			this.myMangaReaderCMSBaseURL = this.https+'://whitecloudpavilion.com/manga/free';
			this.setupMyMangaReaderCMS();
			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
