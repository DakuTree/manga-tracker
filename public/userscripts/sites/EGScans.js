(function(sites) {
	/**
	 * EG Scans
	 * @type {SiteObject}
	 */
	sites['read.egscans.com'] = {
		preInit : function(callback) {
			if(location.pathname.indexOf('&') !== -1) {
				//EGScans seems to generate different HTML when it has parameters, let's just redirect to normal version to make things easier.
				location.pathname = location.pathname.replace(/&.*$/, '');
			} else {
				callback();
			}
		},
		setObjVars : function() {
			let _this = this;

			this.title       = this.segments[1];
			this.chapter     = this.segments[2] || 'Chapter_001';

			this.title_url   = 'http://read.egscans.com/'+this.title+'/';
			this.chapter_url = this.title_url+this.chapter+'/';

			let option = $('select[name=chapter] > option');
			option.each(function(i, e) {
				$(e).val(_this.title_url + $(e).val() + '/');
			});
			this.chapterList        = window.generateChapterList(option, 'value');
			this.chapterListCurrent = this.chapter_url;

			this.viewerCustomImageList = $('script:contains("img_url.push(")').html().match(/url\.push\('(.*?')/g).filter(function(value, index, self) {
				return self.indexOf(value) === index;
			}).map(function(v) {
				return v.substr(10, v.length-11);
			});

			this.page_count = this.viewerCustomImageList.length;
		},
		preSetupViewer : function(callback) {
			$('.pager').remove();

			$('#image_frame').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div
			callback(false, true);
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
