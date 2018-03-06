(function(sites) {
	/**
	 * MangaRock
	 * @type {SiteObject}
	 */
	sites['mangarock.com'] = {
		preInit : function(callback) {
			let dfd = $.Deferred();
			let checkSelector = setInterval(function () {
				if ($('._3Oahl').text() !== '') {
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
			let _this = this;

			//this.page_count    = $('.list-switcher-2 > li > select[name=category_type]').get(0).length;
			this.title         = this.segments[2].substr(10);
			let chapterID      = this.segments[4].substr(12);
			this.chapter       = chapterID + ':--:' + $('._3Oahl.ll5Bk > select > option:selected').text().replace(/^(.*?):.*?$/, '$1').replace(/Chapter /g, 'c').replace(/Vol\.([0-9]+) /, 'v$1/').trim();

			this.title_url   = `${this.https}://mangarock.com/manga/mrs-serie-${this.title}`;
			this.chapter_url = `${this.title_url}/chapter/mrs-chapter-${chapterID}`;

			let tempList = window.generateChapterList($('._3Oahl.ll5Bk > select:first > option'), 'value');
			this.chapterList = Object.keys(tempList).reduce(function(result, key) {
				result[`${_this.title_url}/chapter/${key}`] = tempList[key];
				return result;
			}, {});
			this.chapterListCurrent = this.chapter_url;
		},
		stylize : function() {
			//MangaRock uses AJAX when changing chapters,
			$('#app-layout-container').on('click', '._1le-p.hBsaA.lAuaX', function(e) {
				e.preventDefault();

				$('#TrackerBarLayout').find('> a:contains("Previous")').click();
			}).on('click', '._1le-p._1b_9G.lAuaX', function(e) {
				e.preventDefault();
				$('#TrackerBarLayout').find('> a:contains("Next")').click();
			});
		}
	};

})(window.trackerSites = (window.trackerSites || {}));
