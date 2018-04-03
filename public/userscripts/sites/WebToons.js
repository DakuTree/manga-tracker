(function(sites) {
	/**
	 * Webtoons
	 * @type {SiteObject}
	 */
	sites['www.webtoons.com'] = {
		setObjVars : function() {
			let title_id     = window.location.search.match(/title_no=([0-9]+)/)[1],
			    chapter_id   = window.location.search.match(/episode_no=([0-9]+)/)[1];
			this.title       = title_id   + ':--:' + this.segments[1] + ':--:' + this.segments[3] + ':--:' + this.segments[2];
			this.chapter     = chapter_id + ':--:' + this.segments[4];
			this.chapterNumber = this.segments[4];

			this.title_url   = 'https://www.webtoons.com/'+this.segments[1]+'/'+this.segments[2]+'/'+this.segments[3]+'/list?title_no='+title_id;
			this.chapter_url = 'https://www.webtoons.com/'+this.segments[1]+'/'+this.segments[2]+'/'+this.segments[3]+'/'+this.segments[4]+'/viewer?title_no='+title_id+'&episode_no='+chapter_id;

			this.chapterList        = window.generateChapterList($('.episode_lst > .episode_cont > ul > li a'), 'href');
			this.chapterListCurrent = this.chapter_url;

			this.viewerTitle = $('.subj').text();

			this.searchURLFormat = 'https://www.webtoons.com/search?keyword={%SEARCH%}';
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
