(function(sites) {
	/**
	 * LHtranslation
	 * @type {SiteObject}
	 */
	sites['read.lhtranslation.com'] = {
		preInit : function(callback) {
			//Force webtoon mode.
			if(getCookie('read_type') === '1') {
				callback();
			} else {
				document.cookie = 'read_type=1; expires=Fri, 6 Sep 2069 00:00:00 UTC; path=/;';
				location.reload();
			}
		},
		setObjVars : function() {
			this.segments = this.segments[1].match(/^read-(.*?)-chapter-([0-9\.]+)(?:-page-[0-9]+)?\.html$/);
			this.title         = this.segments[1];
			this.chapter       = this.segments[2];

			this.title_url   = this.https + `://read.lhtranslation.com/manga-${this.title}.html`;
			this.chapter_url = this.https + `://read.lhtranslation.com/read-${this.title}-chapter-${this.chapter}.html`;

			this.chapterListCurrent = `read-${this.title}-chapter-${this.chapter}.html`;
			this.chapterList        = generateChapterList($('.chapter-before:eq(0) .select-chapter > select > option').reverseObj(), 'value');

			let imgList = $('img.chapter-img');
			this.viewerCustomImageList  = imgList.map(function(i, e) {
				return $(e).attr('src');
			});
			this.page_count = imgList.length;
		},
		preSetupViewer : function(callback) {
			$('.chapter-content').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div
			callback(false, true);
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
