(function(sites) {
	/**
	 * KissManga - Tracking Disabled
	 * @type {SiteObject}
	 */
	sites['kissmanga.com'] = {
		preInit : function(callback) {
			return;

			//Kissmanga has bot protection, sometimes we need to wait for the site to load.
			if($('.cf-browser-verification').length === 0) {
				//Kissmanga has a built-in method to show all pages on the same page. Check if the cookie is correct, otherwise change and refresh.
				if(window.getCookie('vns_readType1') !== '0') {
					callback();
				} else {
					document.cookie = 'vns_readType1=1; expires=Fri, 6 Sep 2069 00:00:00 UTC; path=/;';
					location.reload();
				}
			}
		},
		setObjVars : function() {
			let chapter_id   = document.location.search.match(/id=([0-9]+)/)[1];

			this.title       = this.segments[2];
			this.chapter     = this.segments[3] + ':--:' + chapter_id;

			this.title_url   = 'http://kissmanga.com/Manga/'+this.title;
			this.chapter_url = this.title_url+'/'+this.segments[3]+'?id='+chapter_id;

			this.chapterList        = window.generateChapterList($('.selectChapter:first > option'), 'value');
			this.chapterListCurrent = decodeURI(this.segments[3])+'?id='+chapter_id;

			this.viewerChapterName     = $('.selectChapter:first > option:selected').text().trim();
			this.viewerTitle           = $('title').text().trim().split('\n')[1];
			this.viewerCustomImageList = $('#divImage').find('img').map(function(i, e) {
				return $(e).attr('src');
			});
			this.page_count = this.viewerCustomImageList.length;
		},
		postSetupTopBar : function(callback) {
			let image = $('#divImage');

			//Remove extra unneeded elements.
			image.prevAll().remove();
			image.nextAll().remove();

			callback();
		},
		preSetupViewer : function(callback) {
			$('#divImage').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div

			callback(false, true);
		},

		//FIXME: KissManga banned us. SEE: https://github.com/DakuTree/manga-tracker/issues/64
		trackChapter : function(askForConfirmation) {
			if(askForConfirmation === true) {
				//Only show on alert when manually updating.
				alert('KissManga decided to IP ban our server, which means tracking is no longer possible.\nThis may be fixed at a later date, sorry for the inconvenience.');
			}
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
