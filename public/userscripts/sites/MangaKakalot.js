(function(sites) {
	/**
	 * MangaKakalot
	 * @type {SiteObject}
	 */
	sites['mangakakalot.com'] = {
		preInit : function(callback) {
			//Force all images on one page mode.
			if(getCookie('loadimg') === 'yes') {
				callback();
			} else {
				document.cookie = 'loadimg=yes; expires=Fri, 6 Sep 2069 00:00:00 UTC; path=/;';
				location.reload();
			}
		},
		setObjVars : function() {
			let _this = this;

			this.title       = this.segments[2];
			this.chapter     = this.segments[3].substr(8);

			this.title_url   = this.https+'://mangakakalot.com/manga/'+this.title;
			this.chapter_url = this.https+'://mangakakalot.com/chapter/'+this.title+'/chapter_'+this.chapter;

			let tempList = generateChapterList($('#c_chapter').find('> option').reverseObj(), 'value');
			this.chapterList = Object.keys(tempList).reduce(function(result, key) {
				result[`${_this.https}://mangakakalot.com/chapter/${_this.title}/chapter_${key}`] = tempList[key];
				return result;
			}, {});
			this.chapterListCurrent = this.chapter_url;

			this.viewerCustomImageList  = $('#vungdoc').find('img').map(function(i, e) {
				return $(e).attr('src');
			});
			this.page_count = this.viewerCustomImageList.length;
		},
		stylize : function() {
			$('.info-top-chapter, .option_wrap').remove();
		},
		preSetupViewer : function(callback) {
			$('#vungdoc').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div

			callback(true, true);
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
