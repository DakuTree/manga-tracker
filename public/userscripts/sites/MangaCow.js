(function(sites) {
	/**
	 * MangaCow
	 * @type {SiteObject}
	 */
	sites['mngcow.co'] = {
		setObjVars : function() {
			let _this = this;

			this.title       = this.segments[1];
			this.chapter     = this.segments[2];

			this.title_url   = 'http://mngcow.co/'+this.title+'/';
			this.chapter_url = this.title_url+this.chapter+'/';

			/** @type {(jQuery)} */
			let pageNav = $('#pageNav');

			pageNav.find('select:first > option').each(function(i, e) {
				$(e).val(_this.title_url + $(e).val() + '/');
			});
			this.chapterList        = window.generateChapterList(pageNav.find('select:first > option').reverseObj(), 'value');
			this.chapterListCurrent = this.chapter_url;

			this.viewerCustomImageList = $('script:contains("/wp-content/manga/")').html().match(/(http:\/\/mngcow\.co\/wp-content\/manga\/[^"]+)/g).filter(function(value, index, self) {
				return self.indexOf(value) === index;
			});
			this.page_count = this.viewerCustomImageList.length;

			this.searchURLFormat = 'http://mngcow.co/manga-list/search/{%SEARCH%}';

			if(this.segments[3]) {
				this.currentPage = parseInt(this.segments[3]);
			}
		},
		preSetupViewer : function(callback) {
			$('.wpm_nav, .wpm_ifo_box').remove();
			$('#toHome, #toTop').remove();

			$('#singleWrap').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div
			callback(true, true);
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
