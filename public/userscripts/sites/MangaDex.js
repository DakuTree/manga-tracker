/* global unsafeWindow */
(function(sites) {
	/**
	 * MangaDex (www)
	 * @type {SiteObject}
	 */
	sites['www.mangadex.org'] = {
		preInit : function() {
			//Auto-redirect to non-www version.
			location.href = location.href.replace('www.mangadex.org', 'mangadex.org');
		}
	};

	/**
	 * MangaDex
	 * @type {SiteObject}
	 */
	sites['mangadex.org'] = {
		preInit : function(callback) {
			let _this = this;

			this.site = 'mangadex.org';

			//NOTE: We need to wait for the page to load as if we call this before the initial load it will make duplicate requests to the API.
			let pageLoad = $.Deferred();
			let checkSelector = setInterval(function () {
				if($('.reader-images > *').length) {
					pageLoad.resolve();
					clearInterval(checkSelector);
				} else {
					console.log('trackr - Waiting for initial page load...');
				}
			}, 1000);
			pageLoad.done(() => {
				/* FIXME: JS API no longer exists for whatever reason, so we need to do this the hard way.
				   The problem with the hard way is it requires two duplicated requests that aren't cached. Great.

				//Page is now loaded, now use the site JS API to grab the data ourselves.
				//NOTE: This does not send duplicate API requests as it uses the cache from the page load request.
				let chapterID = $('head').attr('data-chapter-id');
				unsafeWindow.API.Chapter.create({id: chapterID}).then(data => {
					this.preInitData = data;
					callback();
				});
				*/

				let preInitData = {};

				//GM_xmlHttpRequest function may be better here.
				$.getJSON(`${_this.https}://mangadex.org/api/chapter/${_this.segments[2]}?`, function(chapterData) {
					preInitData._data = chapterData;
					$.getJSON(`${_this.https}://mangadex.org/api/manga/${chapterData.manga_id}?`, function(titleData) {
						preInitData.manga = {
							_data       : titleData.manga,
							chapters    : titleData.chapter,
							chapterList : []
						};

						Object.keys(titleData.chapter).forEach(function (key) {
							let chData = titleData.chapter[key];
							if(chData.lang_code === chapterData.lang_code) {
								chData.id = key;
								preInitData.manga.chapterList.push(chData);
							}
						});

						_this.preInitData = preInitData;
						callback();
					});
				});
			});
		},

		setObjVars : function() {
			let _this = this;
			/* This contains all chapter & manga data:
			       - manga
			         - chapterList (This is all chapter data according to language settings)
			         - chapters (This is all chapter data, regardless of language settings (Unused)
			         - _data (Manga Data)
			       - _data (Current Chapter Data)*/
			let apiData        = this.preInitData,
			    chapterData    = apiData._data,
			    titleData      = apiData.manga;

			let titleID      = chapterData.manga_id;
			this.title       = titleID + ':--:' + chapterData.lang_name;

			let chapter      = chapterData.id;
			this.chapterNumber = `v${chapterData.volume}/c${chapterData.chapter}`.replace(/^v\//, '').replace(/^c$/, 'cOneshot');
			this.chapter     = chapterData.id + ':--:' + this.chapterNumber;

			this.title_url   = `${_this.https}://mangadex.org/manga/${titleID}`;
			this.chapter_url = `${_this.https}://mangadex.org/chapter/${chapter}`;

			let tempList = {};
			titleData.chapterList.forEach((chData) => {
				let chapterNumber = `v${chData.volume}/c${chData.chapter}`.replace(/^v\//, '');
				tempList[`${_this.https}://mangadex.org/chapter/${chData.id}`] = chapterNumber + (chData.title !== '' ? ' - '+chData.title : '');
			});
			this.chapterList = tempList;
			this.chapterListCurrent = this.chapter_url;

			//TODO: Handle Webtoons
			this.viewerCustomImageList = chapterData.page_array.map((filename) => {
				return `${chapterData.server}${chapterData.hash}/${filename}`;
			});
			this.page_count             = this.viewerCustomImageList.length;
			if(this.segments[3]) {
				this.currentPage = parseInt(this.segments[3]);
			}

			this.viewerChapterName      = this.chapterNumber + (chapterData.title !== '' ? ' - '+chapterData.title : '');
			this.viewerTitle            = titleData._data.title;
		},
		stylize : function() {
			$('.reader-page-bar').remove();
		},
		preSetupViewer : function(callback) {
			$('.reader-images').replaceWith($('<div/>', {id: 'viewer'}));

			callback(false, true);
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
