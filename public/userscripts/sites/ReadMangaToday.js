(function(sites) {
	/**
	 * ReadMangaToday (Old Domain)
	 * @type {SiteObject}
	 */
	sites['www.readmanga.today'] = {
		preInit : function() {
			//Auto-redirect to new domain
			location.href = location.href.replace(/^https?:\/\/www\.readmanga\.today/, 'https://www.readmng.com');
		}
	};

	/**
	 * ReadMangaToday (No www)
	 * @type {SiteObject}
	 */
	sites['readmng.com'] = {
		preInit : function() {
			//Auto-redirect to www (Preferably we'd use non-www, however most of the site links use www.
			location.href = location.href.replace(/^https?:\/\/readmng\.com/, 'https://www.readmng.com');
		}
	};

	/**
	 * ReadMangaToday
	 * @type {SiteObject}
	 */
	sites['www.readmng.com'] = {
		setObjVars : function() {
			this.site = 'www.readmanga.today';

			this.segments      = window.location.pathname.replace(/^(.*\/)(?:[0-9]+\.html)?$/, '$1').split( '/' );

			//FIXME: Is there a better way to do this? It just feels like an ugly way of setting vars.
			this.page_count    = $('.list-switcher-2 > li > select[name=category_type]').get(0).length;
			this.title         = this.segments[1];
			this.chapter       = this.segments[2];

			this.title_url   = this.https + '://www.readmng.com/'+this.title+'/';
			this.chapter_url = this.title_url + this.chapter+'/';

			//Might be easier to keep chapter_url different.
			this.chapterListCurrent = this.chapter_url.slice(0,-1);
			this.chapterList        = generateChapterList($('.jump-menu[name=chapter_list] > option:gt(0)').reverseObj(), 'value');

			//this.viewerTitle            = $('.readpage_top > .title > h2').text().slice(0, -6);
			this.viewerChapterURLFormat = this.chapter_url + '%pageN%';
			this.viewerRegex            = /^[\s\S]*<div class="content-list col-md-12 page_chapter">[\s\S]*(<img[\s\S][^>]+>)[\s\S]*<!--col-md-12-->[\s\S]*$/;

			if(this.segments[3]) {
				this.currentPage = parseInt(this.segments[3]);
			}
		},
		stylize : function() {

		},
		preSetupViewer : function(callback) {
			$('.content').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div
			callback(true);
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
