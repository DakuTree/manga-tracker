(function(sites) {
	/**
	 * Game of Scanlation
	 * @type {SiteObject}
	 */
	sites['gameofscanlation.moe'] = {
		setObjVars : function() {
			let _this = this;
			//GoS is a bit weird. The title URL has two variations, one with the ID and one without.
			//The ID one works only on the title page, and the no ID one works on the chapter page.
			this.title       = $('#readerHeader').find('> .thelefted a:last').attr('href').split('/')[1];
			this.chapter     = this.segments[3];

			if(this.title.indexOf('.') !== -1) {
				this.title_url   = 'https://gameofscanlation.moe/forums/'+this.title+'/';
			} else {
				this.title_url   = 'https://gameofscanlation.moe/projects/'+this.title+'/';
			}
			this.title_url   = 'https://gameofscanlation.moe/forums/'+this.title+'/';
			this.chapter_url = 'https://gameofscanlation.moe/projects/'+this.title.replace(/\.[0-9]+$/, '')+'/'+this.chapter+'/';

			let tempList = generateChapterList($('select[name=chapter_list] > option'), 'data-chapterurl');
			this.chapterList = Object.keys(tempList).reduce(function(result, key) {
				let segments = key.split('/');
				result[`projects/${_this.title.replace(/\.[0-9]+$/, '')}/${segments[2]}/`] = tempList[key];
				return result;
			}, {});

			this.chapterListCurrent = this.chapter_url.substr(29);
		},
		postSetupTopBar : function(callback) {
			$('.samBannerUnit').remove(); //Remove huge header banner.
			$('.AdBlockOn').remove(); //Remove huge header banner.

			callback();
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
