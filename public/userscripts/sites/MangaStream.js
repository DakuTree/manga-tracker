(function(sites) {
	/**
	 * MangaStream (Alt Domain)
	 * @type {SiteObject}
	 */
	sites['readms.net'] = {
		preInit : function(callback) {
			let ms = sites['mangastream.com'];
			this.setObjVars      = ms.setObjVars;
			this.stylize         = ms.stylize;
			this.preSetupTopBar  = ms.preSetupTopBar;
			this.postSetupTopBar = ms.postSetupTopBar;
			this.preSetupViewer  = ms.preSetupViewer;

			this.site = 'mangastream.com';

			callback();
		}
	};

	/**
	 * MangaStream
	 * @type {SiteObject}
	 */
	sites['mangastream.com'] = {
		setObjVars : function() {
			this.page_count  = parseInt($('.controls ul:last > li:last').text().replace(/[^0-9]/g, ''));
			this.title       = this.segments[2];
			this.chapter     = this.segments[3]+'/'+this.segments[4];

			this.title_url   = location.origin+'/manga/'+this.title;
			this.chapter_url = location.origin+'/r/'+this.title+'/'+this.chapter;

			this.chapterNumber = 'c'+this.chapter.split('/')[0];

			// this.chapterList     = {}; //This is set via preSetupTopBar.
			this.chapterListCurrent = '/r/'+this.title+'/'+this.chapter+'/1'; //FIXME: MS only seems to use http urls, even if you are on https

			this.viewerChapterName      = this.chapterNumber;
			this.viewerTitle            = $('.btn-reader-chapter > a > span:first').text();
			this.viewerChapterURLFormat = this.chapter_url + '/' + '%pageN%';
			this.viewerRegex            = /^[\S\s]*(<div class="page">[\S\s]*?(?=<\/div>)<\/div>)[\S\s]*$/;

			if(this.segments[5]) {
				this.currentPage = parseInt(this.segments[5].replace(/^([0-9]+).*$/, '$1'));
			}
		},
		stylize : function() {
			GM_addStyle(`
				.page { margin-right: 0 !important; }
				#reader-nav { margin-bottom: 0; }
			`);

			$('.page-wrap > #reader-sky').remove(); //Ad block
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
					let table = $(response.replace(/^[\S\s]*(<table[\S\s]*<\/table>)[\S\s]*$/, '$1').replace(/\?t=[0-9]+&(amp;)?f=[0-9]+&(amp;)?e=[0-9]+/g, ''));

					_this.chapterList = window.generateChapterList($('tr:not(:first) a', table).reverseObj(), 'href');

					callback();
				}
			});
		},
		postSetupTopBar : function(callback) {
			$('.subnav').remove(); //Remove topbar, since we have our own

			callback();
		},
		preSetupViewer : function(callback) {
			$('.page').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div

			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
