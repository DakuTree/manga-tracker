(function(sites) {
	/**
	 * MangaHere (Alt Domain)
	 * @type {SiteObject}
	 */
	sites['www.mangahere.cc'] = {
		preInit : function(callback) {
			let ms = sites['www.mangahere.co'];
			this.setObjVars      = ms.setObjVars;
			this.stylize         = ms.stylize;
			this.preSetupTopBar  = ms.preSetupTopBar;
			this.postSetupTopBar = ms.postSetupTopBar;
			this.preSetupViewer  = ms.preSetupViewer;

			this.site = 'www.mangahere.co';

			callback();
		}
	};

	/**
	 * MangaHere
	 * @type {SiteObject}
	 */
	sites['www.mangahere.co'] = {
		//MangaHere uses pretty much the same site format as MangaFox, with a few odd changes.
		setObjVars : function() {
			this.segments      = window.location.pathname.split( '/' );

			//FIXME: Is there a better way to do this? It just feels like an ugly way of setting vars.
			this.page_count    = $('.go_page:first > .right > select > option').length;
			this.title         = this.segments[2];
			this.chapter       = ((!!this.segments[4] && ! /\.html$/.test(this.segments[4])) ? this.segments[3]+'/'+this.segments[4] : this.segments[3]);

			this.title_url   = 'http://www.mangahere.cc/manga/'+this.title+'/';
			this.chapter_url = '//www.mangahere.cc/manga/'+this.title+'/'+this.chapter+'/';

			this.chapterListCurrent = this.chapter_url;
			// this.chapterList        = {}; //This is set via preSetupTopbar

			this.viewerTitle            = $('.readpage_top > .title > h2').text().slice(0, -6);
			this.viewerChapterURLFormat = this.chapter_url + '%pageN%'+'.html';
			this.viewerRegex            = /^[\s\S]*<section class="read_img" id="viewer">[\s\S]*(<img src[\s\S]*\/>)[\s\S]*<\/section>[\s\S]*<section class="readpage_footer[\s\S]*$/;

			this.currentPage = parseInt(this.segments.slice(-1)[0].replace(/^([0-9]+).*/, '$1'));
		},
		stylize : function() {
			GM_addStyle(`
				.read_img { min-height: 0; }
				.readpage_top {margin-bottom: 5px;}
				.readpage_top .title h1, .readpage_top .title h2 {font-size: 15px;}
			`);

			//Remove banners
			$('.readpage_top > div[class^=advimg], .readpage_footer > div[class^=banner-]').remove();

			//Remove Tsukkomi thing
			$('.readpage_footer > .tsuk-control, #tsuk_container').remove();

			//Remove social bar.
			$('.plus_report').remove();

			$('#viewer').css({
				'background' : 'none',
				'border'     : '0'
			});

			//Format the chapter header
			let title = $('.readpage_top > .title');
			title.html(function(i, html) { return html.replace('</span> / <h2', '</span><h2'); });
			title.find('> span[class^=color]').remove();
			title.find('h2').addClass('right');
		},
		preSetupTopBar : function(callback) {
			let _this = this;

			//Much like MangaFox, the inline chapter list is cached so we need to grab the proper list via AJAX.
			GM.xmlHttpRequest({
				url     : _this.title_url.replace('http://www.mangahere.cc', 'https://m.mangahere.co'),
				method  : 'GET',
				onload  : function(response) {
					let data = response.responseText;
					data = data.replace(/^[\S\s]*(<section class="main">[\S\s]*(?=<\/section>)<\/section>)[\S\s]*$/, '$1'); //Only grab the chapter list
					let div = $('<div/>').append($(data));

					$('.manga-chapters > ul > li > a', div).reverseObj().each(function() {
						let chapterTitle     = $(this).parent().clone().children().remove().end().text().trim(),
						    url              = $(this).attr('href').replace(/^(.*\/)(?:[0-9]+\.html)?$/, '$1'); //Remove trailing page number

						url = url.replace('m.mangahere.co/manga/', 'www.mangahere.cc/manga/');
						_this.chapterList[url] = url.replace(/^.*\/manga\/[^/]+\/(?:v(.*?)\/)?c(.*?)\/$/, 'Vol.$1 Ch.$2')
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
							response = response.replace(/^[\S\s]*(<section id="main" class="main clearfix">[\S\s]*(?=<\/section>)<\/section>)[\S\s]*$/, '$1'); //Only grab the chapter list
							let div = $('<div/>').append($(response).find('.detail_list > ul:first'));

							$('li > span.left > a', div).reverseObj().each(function() {
								let chapterTitle     = $(this).parent().clone().children().remove().end().text().trim(),
								    url              = $(this).attr('href').replace(/^(.*\/)(?:[0-9]+\.html)?$/, '$1'); //Remove trailing page number

								_this.chapterList[url] = url.replace(/^.*\/manga\/[^/]+\/(?:v(.*?)\/)?c(.*?)\/$/, 'Vol.$1 Ch.$2')
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
			$('.go_page:first').remove();

			callback();
		},
		preSetupViewer : function(callback) {
			let _this = this;

			let newViewer = $('<div/>', {id: 'viewer'});

			//Add a notice about adblock.
			newViewer.prepend(
				$('<p/>', {style: 'background: white; border: 2px solid black;', text: `
					MangaHere has moved to using an image host which is blacklisted by some AdBlockers.
					If you can't see any images, you will need to whitelist "mhcdn.secure.footprint.net".
				`})
			);

			$('#viewer').replaceWith(newViewer); //Set base viewer div

			//We can't CSRF to the subdomain for some reason, so we need to use a GM function here...
			GM.xmlHttpRequest({
				url     : 'https:'+_this.chapter_url.replace('www.mangahere.cc/manga', 'm.mangahere.co/roll_manga'),
				method  : 'GET',
				onload  : function(response) {
					let data = response.responseText,
					    imageList = $(data.replace(/^[\s\S]*(<div class="mangaread-main">[\s\S]*<\/div>)[\s\S]*<div class="mangaread-operate[\s\S]*$/, '$1')).find('img.lazy[data-original]');

					// console.log(imageList);
					_this.viewerCustomImageList = imageList.map(function(i, e) {
						//NOTE: This is a temp-fix for uMatrix blocking secure.footprint.net by default due to one of the default lists containing it.
						return $(e).attr('data-original')/*.replace('https://mhcdn.secure.footprint.net', 'http://c.mhcdn.net')*/;
					});

					if(_this.viewerCustomImageList.length) {
						//Sometimes the page count on the actual site isn't accurate, but the mobile sites is. Fix when possible.
						_this.page_count = _this.viewerCustomImageList.length;

						callback(false, true);
					} else {
						console.log('trackr - Mobile site returned no images? Falling back to old loading method');
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
