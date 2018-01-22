(function(sites) {
	/**
	 * Meraki Scans
	 * @type {SiteObject}
	 */
	sites['merakiscans.com'] = {
		setObjVars : function() {
			let _this = this;

			this.title       = this.segments[1];
			this.chapter     = this.segments[2];

			this.title_url   = 'http://merakiscans.com/'+this.title+'/';
			this.chapter_url = this.title_url+this.chapter+'/';

			/** @type {(jQuery)} */
			let pageNav = $('.wpm_nav');

			pageNav.find('select:first > option').each(function(i, e) {
				$(e).val(_this.title_url + $(e).val() + '/');
			});
			this.chapterList        = generateChapterList(pageNav.find('select:first > option').reverseObj(), 'value');
			this.chapterListCurrent = this.chapter_url;

			this.viewerCustomImageList = $('#longWrap').html().match(/(http:\/\/merakiscans\.com\/wp-content\/manga\/[^"]+)/g).filter(function(value, index, self) {
				return self.indexOf(value) === index;
			});
			this.page_count = this.viewerCustomImageList.length;

			this.viewerChapterName      = 'c'+this.chapter.split('/')[0];
			this.viewerTitle            = $('.ttl > a').text();

			this.searchURLFormat = 'http://merakiscans.com/manga-list/search/{%SEARCH%}';

			if(this.segments[3]) {
				this.currentPage = parseInt(this.segments[3]);
			}
		},
		postSetupTopBar : function(callback) {
			let image = $('#singleWrap');

			//Remove extra unneeded elements.
			image.prevAll().remove();
			image.nextAll().remove();

			callback();
		},
		preSetupViewer : function(callback) {
			$('.wpm_nav, .wpm_ifo_box').remove();
			$('#toHome, #toTop').remove();

			$('#singleWrap').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div
			callback(false, true);
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
