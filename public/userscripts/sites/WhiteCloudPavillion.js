(function(sites) {
	/**
	 * ReadMangaToday (www)
	 * @type {SiteObject}
	 */
	sites['www.whitecloudpavilion.com'] = {
		preInit : function() {
			//Auto-redirect to non-www.
			location.href = location.href.replace('www.whitecloudpavilion.com', 'whitecloudpavilion.com');
		}
	};

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
