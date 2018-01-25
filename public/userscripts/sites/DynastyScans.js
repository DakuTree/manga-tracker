/* global window.generateChapterList */
(function(sites) {	/**
	 * Dynasty Scans
	 * @type {SiteObject}
	 */
	sites['dynasty-scans.com'] = {
		setObjVars    : function() {
			let title_ele = $('#chapter-title').find('> b > a');

			this.is_one_shot = !title_ele.length;

			if(!this.is_one_shot) {
				this.title_url = title_ele.attr('href').replace(/.*\/(.*)$/, '$1');
				this.chapter_url = location.pathname.split(this.title_url + '_').pop(); //There is really no other valid way to get the chapter_url :|
			} else {
				this.title_url = location.pathname.substr(10);
				this.chapter_url = 'oneshot'; //This is labeled oneshot so it's properly handled in the backend.
			}

			this.title = this.title_url + ':--:' + (+this.is_one_shot);
			this.chapter = this.chapter_url;

			this.chapterListCurrent = location.pathname;
			this.chapterList = {}; //This is set in preSetupTopBar

			this.viewerTitle = $('#chapter-title > b > a, #chapter-title > b').get(0).innerText; //FIXME: This doesn't prepend series names (if exists)
			this.viewerCustomImageList = $('script:contains("/system/releases/")').html().match(/"(\/system[^"]+)"/g).map(function(e) {
				return e.replace(/^"|"$/g, '');
			});
			this.page_count = this.viewerCustomImageList.length;

			this.searchURLFormat = 'https://dynasty-scans.com/search?q={%SEARCH%}';

			if(location.hash) {
				this.currentPage = parseInt(location.hash.substring(1));
			}
		},
		stylize: function() {
			//These buttons aren't needed since we have our own viewer.
			$('#chapter-actions > div > .btn-group:last, #download_page').remove();
			$('#reader').addClass('noresize');

			//Topbar covers a bunch of nav buttons.
			GM_addStyle(`
				#content > .navbar > .navbar-inner { padding-top: 42px; }
			`);
		},
		preSetupTopBar: function(callback) {
			let _this = this;

			if(!_this.is_one_shot) {
				//Sadly, we don't have any form of inline chapterlist. We need to AJAX the title page for this one.
				$.ajax({
					url: 'https://dynasty-scans.com/series/' + _this.title_url,
					beforeSend: function(xhr) {
						xhr.setRequestHeader('Cache-Control', 'no-cache, no-store');
						xhr.setRequestHeader('Pragma', 'no-cache');
					},
					cache     : false,
					success   : function(response) {
						response = response.replace(/^[\S\s]*(<dl class="chapter-list">[\S\s]*<\/dl>)[\S\s]*$/, '$1');
						let div = $('<div/>').append($(response));

						_this.chapterList = window.generateChapterList($('.chapter-list > dd > a.name', div), 'href');

						callback();
					}
				});
			} else {
				_this.chapterList[location.pathname] = 'Oneshot';

				callback();
			}
		},
		preSetupViewer: function(callback) {
			$('#reader').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div

			callback(true, true);
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
