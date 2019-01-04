(function(sites) {
	/**
	 * Meraki Scans (GlossyBright Variant)
	 * @type {SiteObject}
	 */

	 //NOTE: This appears to use a variant (or updated) variant of the GlossyBright WP theme.
	sites['merakiscans.com'] = {
		setObjVars : function() {
			let _this = this;


			this.title       = this.segments[1];
			this.chapter     = this.segments[2];

			this.title_url   = `${this.baseURL}/${this.title}/`;
			this.chapter_url = this.title_url +  this.chapter + '/';

			let chapterSelect = $('#chapter_select > option').reverseObj();
			chapterSelect.each(function(i, e) {
				$(e).val(_this.title_url + $(e).val() + '/');
			});
			this.chapterList        = window.generateChapterList(chapterSelect.reverseObj(), 'value');
			this.chapterListCurrent = this.chapter_url;

			this.viewerCustomImageList = JSON.parse($('script:contains("var images = ")').html().match(/images = (\[.*?\])/)[1]).map(img => {
				return `${this.baseURL}/manga/${this.title}/${this.chapter}/${img}`
			});
			this.page_count = this.viewerCustomImageList.length;

			this.searchURLFormat = `${this.baseURL}/manga-list/search/{%SEARCH%}`;

			if(this.segments[3]) {
				this.currentPage = parseInt(this.segments[3]);
			}
		},

		stylize : function() {
			GM_addStyle(".read_img > img { display: inline-block; }");
		},

		preSetupViewer : function(callback) {
			$('#adbar, #controlbox').hide();

			$('#content').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div
			callback(true, true);
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
