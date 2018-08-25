(function(sites) {
	/**
	 * MangaFox (New Domain 2.0)
	 * @type {SiteObject}
	 */
	sites['fanfox.net'] = {
		preInit : function(callback) {
			let ms = sites['mangafox.me'];
			this.setObjVars      = ms.setObjVars;
			this.stylize         = ms.stylize;
			this.preSetupTopBar  = ms.preSetupTopBar;
			this.postSetupTopBar = ms.postSetupTopBar;
			this.preSetupViewer  = ms.preSetupViewer;

			this.site = 'mangafox.me';

			callback();
		}
	};

	/**
	 * MangaFox (New Domain)
	 * @type {SiteObject}
	 */
	sites['mangafox.la'] = {
		preInit : function(callback) {
			let ms = sites['mangafox.me'];
			this.setObjVars      = ms.setObjVars;
			this.stylize         = ms.stylize;
			this.preSetupTopBar  = ms.preSetupTopBar;
			this.postSetupTopBar = ms.postSetupTopBar;
			this.preSetupViewer  = ms.preSetupViewer;

			this.site = 'mangafox.me';

			callback();
		}
	};

	/**
	 * MangaFox
	 * @type {SiteObject}
	 */
	sites['mangafox.me'] = {
		setObjVars : function () {
			this.segments    = window.location.pathname.split( '/' );

			this.title       = this.segments[2];
			this.chapter     = ((!!this.segments[4] && ! /\.html$/.test(this.segments[4])) ? this.segments[3]+'/'+this.segments[4] : this.segments[3]);

			this.page_count  = $('#top_bar').find('.prev_page + div').text().trim().replace(/^[\s\S]*of ([0-9]+)$/, '$1');

			this.title_url   = 'http://fanfox.net/manga/'+this.title+'/';
			this.chapter_url = '//fanfox.net/manga/'+this.title+'/'+this.chapter+'/';

			this.chapterListCurrent = this.chapter_url+'1.html';
			this.chapterList        = {}; //This is set via preSetupTopbar

			this.viewerTitle            = $('#series').find('> strong:last > a').text().slice(0, -6);
			this.viewerChapterURLFormat = this.chapter_url + '%pageN%'+'.html';
			this.viewerRegex            = /^[\s\S]*(<div class="read_img">[\s\S]*<\/div>)[\s\S]*<\/div>[\s\S]*<div id="shares"[\s\S]*$/;
			// this.viewerCustomImageList  = []; //This is (possibly) set below.

			this.searchURLFormat = 'http://fanfox.net/search.php?advopts=1&name={%SEARCH%}';

			this.currentPage = parseInt(this.segments.slice(-1)[0].replace(/^([0-9]+).*/, '$1'));

			this.delay = 1000;
		},
		stylize : function() {
			//This removes the old border/background. The viewer adds borders to the images now instead which looks better.
			$('#viewer').css({
				'background' : 'none',
				'border'     : '0'
			});

			let tool = $('#tool');
			//Remove page count from the header, since all pages are loaded at once now.
			tool.find('> #series > strong:eq(1)').remove();

			//Float title in the header to the right. This just looks nicer and is a bit easier to read.
			tool.find('> #series > strong:last').css('float', 'right');

			$('#left-skyscraper, #right-skyscraper').remove();
		},
		preSetupTopBar : function(callback) {
			let _this = this;

			//The inline chapter list is cached. This causes new chapters to not properly show on the list. (Why the cache isn't reset when a new chapter is added is beyond me)
			//Because of this, we can't use the inline chapter list as a source, and instead we need to check the manga page.
			//We can't CSRF to the subdomain for some reason, so we need to use a GM function here...
			GM.xmlHttpRequest({
				url     : _this.title_url.replace('fanfox.net', 'm.fanfox.net'),
				method  : 'GET',
				onload  : function(response) {
					let data = response.responseText;
					data = data.replace(/^[\S\s]*(<div id="chapters"\s*>[\S\s]*)<div id="discussion" >[\S\s]*$/, '$1'); //Only grab the chapter list

					let div = $('<div/>').append($(data));

					$('.chlist a', div).reverseObj().each(function() {
						let chapterTitle     = $('+ span.title', this).text().trim(),
						    url              = $(this).attr('href').replace(/^(.*\/)(?:[0-9]+\.html)?$/, '$1'); //Remove trailing page number

						url = url.replace('m.fanfox.net/manga/', 'fanfox.net/manga/');
						_this.chapterList[url+'1.html'] = url.replace(/^.*\/manga\/[^/]+\/(?:v(.*?)\/)?c(.*?)\/$/, 'Vol.$1 Ch.$2')
							.replace(/^Vol\. /, '') + (chapterTitle !== '' ? ': ' + chapterTitle : '');
					});

					callback();

				},
				onerror : function() {
					console.log('trackr - Unable to load mobile site, fallback to old loading method');
					$.ajax({
						url: _this.title_url,
						beforeSend: function(xhr) {
							xhr.setRequestHeader('Cache-Control', 'no-cache, no-store');
							xhr.setRequestHeader('Pragma', 'no-cache');
						},
						cache: false,
						success: function(response) {
							response = response.replace(/^[\S\s]*(<div id="chapters"\s*>[\S\s]*)<div id="discussion" >[\S\s]*$/, '$1'); //Only grab the chapter list
							let div = $('<div/>').append($(response));

							$('#chapters > .chlist > li > div > a + * > a', div).reverseObj().each(function() {
								let chapterTitle     = $('+ span.title', this).text().trim(),
								    url              = $(this).attr('href').replace(/^(.*\/)(?:[0-9]+\.html)?$/, '$1'); //Remove trailing page number

								_this.chapterList[url+'1.html'] = url.replace(/^.*\/manga\/[^/]+\/(?:v(.*?)\/)?c(.*?)\/$/, 'Vol.$1 Ch.$2')
									.replace(/^Vol\. /, '') + (chapterTitle !== '' ? ': ' + chapterTitle : '');
							});

							callback();
						},
						error: function(/*jqXHR, textStatus, errorThrown*/) {
							callback();
						}
					});
				}
			});
		},
		postSetupTopBar : function(callback) {
			$('#top_center_bar, #bottom_center_bar').remove();
			$('#tool').parent().find('> .gap').remove();
			$('#series').css('padding-top', '0');

			callback();
		},
		preSetupViewer : function(callback) {
			let _this = this;

			let newViewer = $('<div/>', {id: 'viewer'});


			$('#viewer').replaceWith(newViewer); //Set base viewer div

			//We can't CSRF to the subdomain for some reason, so we need to use a GM function here...
			GM.xmlHttpRequest({
				url     : 'http:'+_this.chapter_url.replace('fanfox.net/manga', 'm.fanfox.net/roll_manga'),
				method  : 'GET',
				onload  : function(response) {
					let data      = response.responseText,
					    imageList = [];

					if(data.indexOf('it’s licensed and not available.') === -1) {
						//Avoid attempting to load imageList if is licensed.
						imageList = $(data.replace(/^[\s\S]*(<div class="mangaread-main">[\s\S]*<\/div>)[\s\S]*<div class="mangaread-operate[\s\S]*$/, '$1')).find('img.reader-page');

						_this.viewerCustomImageList = imageList.map(function(i, e) {
							return $(e).attr('data-original');
						});

						if(_this.viewerCustomImageList.length) {
							//Sometimes the page count on the actual site isn't accurate, but the mobile sites is. Fix when possible.
							_this.page_count = _this.viewerCustomImageList.length;

							callback(false, true);
						} else {
							console.log('trackr - Mobile site returned no images? Falling back to old loading method');
							callback(false, false);
						}
					} else {
						console.log('trackr - Mobile site returned licensed. Falling back to old method.');
						callback(false, false);
					}
				},
				onerror : function() {
					console.log('trackr - Unable to load mobile site, fallback to old page loading method');
					callback(false, false);
				}
			});
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
