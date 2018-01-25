(function(sites) {
	/**
	 * MangaPanda
	 * @type {SiteObject}
	 */
	sites['www.mangapanda.com'] = {
		preInit : function(callback) {
			//MangaPanda is tricky. For whatever stupid reason, it decided to not use a URL format which actually separates its manga URLs from every other page on the site.
			//I've went and already filtered a bunch of URLs out in the include regex, but since it may not match everything, we have to do an additional check here.
			if($('#topchapter, #chapterMenu, #bottomchapter').length === 3) {
				//MangaPanda is another site which uses the MangaFox layout. Is this just another thing like FoolSlide?

				callback();
			}
		},
		setObjVars : function() {
			this.page_count     = parseInt($('#topchapter').find('#selectpage select > option:last').text());
			this.title          = this.segments[1];
			this.chapter        = this.segments[2];

			this.chapterListCurrent = '/'+this.title+'/'+this.chapter;
			// this.chapterList = {}, //This is set via preSetupTopBar.

			this.title_url      = 'http://www.mangapanda.com/'+this.title+'/';
			this.chapter_url    = 'http://www.mangapanda.com/'+this.title+'/'+this.chapter+'/';

			// this.viewerChapterName      = '';
			this.viewerTitle            = $('#mangainfo').find('> div[style*=float] > h2').text().slice(0, -6);
			this.viewerChapterURLFormat = this.chapter_url + '%pageN%';
			this.viewerRegex            = /^[\s\S]+(<img id="img".+?(?=>)>)[\s\S]+$/;

			this.searchURLFormat = 'http://www.mangapanda.com/search/?w={%SEARCH%}';

			if(this.segments[3]) {
				this.currentPage = parseInt(this.segments[3]);
			}
		},
		stylize : function() {
			let mangaInfo = $('#mangainfo').find('> div');
			//Remove page count from the header, since all pages are loaded at once now.
			mangaInfo.find(':first .c1').remove();

			//Float title in the header to the right. This just looks nicer and is a bit easier to read.
			mangaInfo.find('+ div:not(.clear)').css('float', 'right');
		},
		preSetupTopBar : function(callback) {
			let _this = this;

			//MangaPanda is tricky here. The chapter list is loaded via AJAX, and not a <script> tag. As far as I can tell, we can't watch for this to load without watching the actual element.
			let attempts = 0;
			let checkExist = setInterval(function() {
				let option     = $('#topchapter').find('> #selectmanga > select > option');
				if(option.length) {
					clearInterval(checkExist);

					_this.chapterList = window.generateChapterList(option, 'value');
					callback();
				}

				if(attempts === 25) {
					alert('ERROR: Having issues loading the chapter list.\nTry reloading the page.');
					clearInterval(checkExist);
				}
				attempts++;
			}, 500);
		},
		postSetupTopBar : function(callback) {
			//Remove MangaFox's chapter navigation since we now have our own. Also remove leftover whitespace.
			$('#topchapter > #mangainfo ~ div, #bottomchapter > #mangainfo ~ div').remove();

			callback();
		},
		preSetupViewer : function(callback) {
			$('.episode-table').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div

			callback(true);
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
