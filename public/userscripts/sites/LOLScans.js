(function(sites) {
	/**
	 * LOLScans
	 * @type {SiteObject}
	 */
	sites['forums.lolscans.com'] = {
		preInit : function() {
			return; // Site disabled.
		},
		setObjVars : function() {
			let _this = this;

			this.title         = this.parameters.p;
			this.chapter       = this.parameters.c;

			this.title_url   = this.https + `://forums.lolscans.com/book/browseChapters.php?p=${this.title}&t=webcomic&pF=projectFolderName`;
			this.chapter_url = this.https + `://forums.lolscans.com/book/page2.php?c=${this.chapter}&p=${this.title}&t=webcomic&pF=projectFolderName`;

			let titleOption = $('#mySelectP').find('option:contains('+this.title.replace(/_/g, ' ')+')');
			if(titleOption.length) {
				//url - text
				/** @namespace unsafeWindow.projectFolders */
				this.chapterList = unsafeWindow.projectFolders[titleOption.val()].reduce(function(acc, chName, i) {
					let chUrl = `page2.php?c=${chName}&p=${_this.title}&t=webcomic&pF=projectFolderName`;
					acc[chUrl] = chName;

					return acc;
				}, {});
				this.chapterListCurrent = `page2.php?c=${this.chapter}&p=${this.title}&t=webcomic&pF=projectFolderName`;
			} else {
				alert('Something has went wrong when trying to generate the chapter list. Please submit a bug report.');
			}
		}
	};
})(window.trackerSites = (window.trackerSites || {}));
