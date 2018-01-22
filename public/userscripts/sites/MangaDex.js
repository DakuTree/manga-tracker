(function(sites) {
	/**
	 * MangaDex
	 * @type {SiteObject}
	 */
	sites['mangadex.com'] = {
		setObjVars : function() {
			let _this = this;

			this.title       = $('span[title="Title"] + a').attr('href').replace(/.*?\/([0-9]+)$/, '$1');
			let chapter      = this.segments[2];
			this.chapter     = chapter + ':--:' + $('#jump_chapter > option:selected').text().replace(/^(?:Vol(?:ume|\.) ([0-9\.]+)?.*?)?Ch(?:apter|\.) ([0-9\.v]+)[\s\S]*$/, 'v$1/c$2').replace(/^v\//, '');

			this.title_url   = `${this.https}://mangadex.com/manga/${this.title}`;
			this.chapter_url = `${this.https}://mangadex.com/chapter/${chapter}`;

			let tempList     = generateChapterList($('#jump_chapter').find('> option'), 'value');
			this.chapterList = Object.keys(tempList).reduce(function(result, key) {
				result[`${_this.https}://mangadex.com/chapter/${key}`] = tempList[key];
				return result;
			}, {});
			this.chapterListCurrent = this.chapter_url;

			let pageSegments = $('#current_page').attr('src').split('/'),
			    imageHash    = pageSegments[2],
				imageExt     = pageSegments[3].split('.').pop();
			this.viewerCustomImageList = $('#jump_page').find('> option').map(function(i, e) {
				let pageN = $(e).attr('value');
				return `${_this.https}://mangadex.com/data/${imageHash}/${pageN}.${imageExt}`;
			});
			this.page_count = this.viewerCustomImageList.length;
		},
		stylize : function() {
			$('.info-top-chapter, .option_wrap').remove();
		},
		preSetupViewer : function(callback) {
			//FIX: I'd like to replace 'content instead, but I kinda like the header here...
			$('#current_page').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div

			callback(true, true);
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
