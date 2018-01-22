(function(sites) {
	/**
	 * Batoto
	 * @type {SiteObject}
	 */
	sites['bato.to'] = {
		preInit : function(callback) {
			//Bato.to loads the image page AFTER page load via AJAX. We need to wait for this to load.
			let dfd = $.Deferred();
			let checkSelector = setInterval(function () {
				if ($('#reader').text() !== 'Loading...') {
					//AJAX has loaded, resolve deferred.
					dfd.resolve();
					clearInterval(checkSelector);
				} else {
					console.log('trackr - Waiting for initial page load...');
				}
			}, 1000);
			dfd.done(() => {
				callback();
			});
		},
		setObjVars : function() {
			let chapterNParts   = $('select[name=chapter_select]:first > option:selected').text().trim().match(/^(?:Vol\.(\S+) )?(?:Ch.([^\s:]+)(?:\s?-\s?([0-9]+))?):?.*/);
			let reader          = $('#reader');

			this.page_count     = $('#page_select:first').find('> option').length;
			let web_toon_check  = $('a[href$=_1_t]');
			this.isWebtoon    = ($(web_toon_check).length ? ($(web_toon_check).text() === 'Want to see this chapter per page instead?' ? 1 : 2) : 0); //0 = no, 1 = yes & long strip, 2 = yes & chapter per page

			this.chapter_hash   = location.hash.substr(1).split('_')[0];
			this.chapterNumber = (chapterNParts[1] ? 'v'+chapterNParts[1]+'/' : '') + 'c'+chapterNParts[2] + (chapterNParts[3] ? '-'+chapterNParts[3] : '');

			this.title_url      = reader.find('a[href*="/comic/"]:first').attr('href');
			this.manga_language = $('select[name=group_select]:first > option:selected').text().trim().replace(/.* - ([\S]+)$/, '$1');

			this.title          = this.title_url.replace(/.*r([0-9]+)$/, '$1') + ':--:' + this.manga_language;
			this.chapter        = this.chapter_hash + ':--:' + this.chapterNumber;

			this.chapter_url    = this.https+'://bato.to/reader#'+this.chapter_hash;

			let chapterListOptions  = $('select[name=chapter_select]:first > option');
			this.chapterListCurrent = this.chapter_url;
			if(this.https === 'https') {
				chapterListOptions.each(function(i, e) {
					let value = $(e).val();
					$(e).val(value.replace(/^http/, 'https'));
				});
			}
			this.chapterList            = generateChapterList(chapterListOptions.reverseObj(), 'value');

			this.viewerChapterName      = this.chapterNumber;
			this.viewerTitle            = document.title.replace(/ - (?:vol|ch) [0-9]+.*/, '').replace(/&#(\d{1,4});/, function(fullStr, code) { return String.fromCharCode(code); });
			this.viewerChapterURLFormat = this.https+'://bato.to/areader?id='+this.chapter_hash+'&p=' + '%pageN%';
			this.viewerRegex            = /^[\s\S]+(<img id="comic_page".+?(?=>)>)[\s\S]+$/;

			this.searchURLFormat = this.https+'://bato.to/search?name={%SEARCH%}';

			if(location.hash.split('_').length > 1) {
				this.currentPage = parseInt(location.hash.split('_')[1]);
			}
		},
		preSetupViewer : function(callback) {
			let reader = $('#reader');

			reader.replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div

			if(this.isWebtoon !== 1) {
				console.log('trackr - bato.to chapter is not webtoon');
				callback();
			} else {
				console.log('trackr - bato.to chapter is webtoon');

				//Bato.to has an option for webtoons to show all chapters on a single page (with a single ajax), we need to do stuff differently if this happens.
				this.viewerCustomImageList = reader.find('#read_settings + div + div img').map(function(i, e) {
					return $(e).attr('src');
				});
				this.page_count = this.viewerCustomImageList.length;

				callback(false, true);
			}
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
