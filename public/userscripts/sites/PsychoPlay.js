(function(sites) {
	/**
	 * PsychoPlay
	 * @type {SiteObject}
	 */
	sites['psychoplay.co'] = {
		setObjVars : function() {
			this.title       = this.segments[2];
			this.chapter     = this.segments[3];

			this.title_url   = this.https+'://psychoplay.co/series/'+this.title;
			this.chapter_url = this.https+'://psychoplay.co/read/'+this.title+'/'+this.chapter;

			this.chapterListCurrent = this.chapter_url;

			this.viewerChapterName      = 'c'+this.chapter;
			this.viewerTitle            = $.trim(($('.content-wrapper > div:eq(1) > div > h1 > a').text()));
			this.viewerCustomImageList  = $('.content-wrapper').find('img').map(function(i, e) {
				return $(e).attr('src');
			});
			this.page_count = this.viewerCustomImageList.length;
		},
		preSetupTopBar : function(callback) {
			let _this = this;

			//We need to use AJAX as the chapter pages don't provide a full chapter list.
			$.ajax({
				url: _this.title_url,
				beforeSend: function(xhr) {
					xhr.setRequestHeader('Cache-Control', 'no-cache, no-store');
					xhr.setRequestHeader('Pragma', 'no-cache');
				},
				cache: false,
				success: function(response) {
					let $container = $(response).wrap('<div />').parent();
					$container.find('.text-muted, .media-left, .media-right').remove();
					_this.chapterList = generateChapterList($('.media-list > li > a', $container).reverseObj(), 'href');

					callback();
				}
			});
		},
		preSetupViewer : function(callback) {
			$('.page-content').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div

			callback(false, true);
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
