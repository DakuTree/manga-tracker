(function(sites) {
	/**
	 * Zero Scans
	 * @type {SiteObject}
	 */
	sites['zeroscans.com'] = {
		preInit : function(callback) {
			//Force webtoon mode.
			if(window.location.search === "?style=list") {
				callback();
			} else {
				window.location.href += '?style=list';
			}
		},
		setObjVars : function() {
			this.title         = this.segments[2];
			this.chapter       = this.segments[3];

			this.title_url   = this.https + `://zeroscans.com/manga/${this.title}/`;
			this.chapter_url = this.https + `://zeroscans.com/manga/${this.title}/${this.chapter}`;

			this.chapterListCurrent = this.chapter_url + '?style=list';
			this.chapterList        = window.generateChapterList($('.single-chapter-select > option'), 'data-redirect');
			let imgList = $('img.wp-manga-chapter-img');
			this.viewerCustomImageList  = imgList.map(function(i, e) {
				return $(e).attr('src');
			});

			this.page_count = imgList.length;
		},
		preSetupViewer : function(callback) {
			$('.read-container').replaceWith($('<div/>', {id: 'viewer', class:'read-container'})); //Set base viewer div
			callback(true, true);
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
