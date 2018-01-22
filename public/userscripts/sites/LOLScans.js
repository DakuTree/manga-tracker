(function(sites) {
	/**
	 * LOLScans
	 * @type {SiteObject}
	 */
	sites['forums.lolscans.com'] = {
		setObjVars : function() {
			let _this = this;

			this.title         = this.parameters.p;
			this.chapter       = this.parameters.c;

			this.title_url   = this.https + `://forums.lolscans.com/book/browseChapters.php?p=${this.title}&t=manga&pF=projectFolderName`;
			this.chapter_url = this.https + `://forums.lolscans.com/book/page2.php?c=${this.chapter}&p=${this.title}&t=manga&pF=projectFolderName`;

			let titleOption = $('#mySelectP').find('option:contains('+this.title.replace(/_/g, ' ')+')');
			if(titleOption.length) {
				//url - text
				/** @namespace unsafeWindow.projectFolders */
				this.chapterList = unsafeWindow.projectFolders[titleOption.val()].reduce(function(acc, chName, i) {
					let chUrl = `page2.php?c=${chName}&p=${_this.title}&t=manga&pF=projectFolderName`;
					acc[chUrl] = chName;

					return acc;
				}, {});
				this.chapterListCurrent = `page2.php?c=${this.chapter}&p=${this.title}&t=manga&pF=projectFolderName`;
			} else {
				alert('Something has went wrong when trying to generate the chapter list. Please submit a bug report.');
			}
			this.delay = 1000; //Added delay due to site using pretty size heavy scans which can slow the browser when you load all the pages at once...

			/** @namespace unsafeWindow.imgArray */
			this.viewerCustomImageList  = unsafeWindow.imgArray;
			this.page_count = this.viewerCustomImageList.length;
		},
		preSetupViewer : function(callback) {
			$('body > table:contains(Project):eq(0), body > button:eq(0)').remove();

			$('table[align=center]').replaceWith($('<div/>', {id: 'viewer'})); //Set base viewer div
			callback(true, true);
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
