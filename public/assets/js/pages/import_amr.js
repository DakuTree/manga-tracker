/* globals page */
$(function () {
	'use strict';
	if (page !== 'import_amr') { return false; }

	const valid_sites = [
		'Manga-Fox',
		// 'Batoto',
		'Manga Here',
		'MangaStream',
		'Manga Panda',
		'Kirei Cake',
		'Dynasty Scans',
		'Manga Reader',
		'Easy Going',
		'EvilFlowers',
		'S2Scans',
		'Vortex Scans'
	];

	$('#amr_import').change(function () {
		let files = this.files;
		if (files && files[0]) {
			let file = files[0];
			if (!file.name.match(/\.(json|txt)$/)) {
				alert('ERROR: Only .json/txt is supported!');
			} else if (file.size > 2097152) {
				alert('ERROR: File too large ( < 2MB)!');
			} else {
				let reader = new FileReader();
				reader.onload = function (e) {
					let json_string = e.target.result;
					if (!isJsonString(json_string)) {
						alert('ERROR: File isn\'t valid JSON!');
					}
					else {
						/**
						 * @param {{mangas:string, mirror:string}} base_json
						 */
						let base_json = JSON.parse(json_string);

						if (!base_json.mangas) {
							alert('JSON file is missing "mangas" object. Was this exported from AMR?');
						} else {
							let mangas = JSON.parse(base_json.mangas);

							let siteList = {};
							let success = mangas.every((manga) => {
								if (!('mirror' in manga || 'name' in manga || 'url' in manga || 'lastChapterReadURL' in manga)) {
									return false;
								} else {
									if (!siteList[manga.mirror]) { siteList[manga.mirror] = []; }
									siteList[manga.mirror].push(manga);
									return true;
								}
							});
							if (!success) {
								alert('JSON has invalid keys?');
							} else {
								for (let site in siteList) {
									if (siteList.hasOwnProperty(site)) {
										let titleList = siteList[site];
										let id = valid_sites.includes(site) ? '#amr_good' : '#amr_bad';
										if(id === '#amr_bad' && $('#amr_bad').is(':empty')) {
											$('<h3/>', {text: 'Incompatible Sites'}).appendTo('#amr_bad');
										}
										$('<h4/>', {text: (site !== 'Batoto' ? site : 'Batoto (Replaced with MangaDex Links)') + ' (' + titleList.length + ')'}).appendTo(id);

										let tbody = $('<tbody/>', {'aria-live': 'polite', 'aria-relevant': 'all'});
										for (let titleN in titleList) {
											if (titleList.hasOwnProperty(titleN)) {
												let title = titleList[titleN];
												/**
												 * @param {{url:string, name:string, lastChapterReadURL:string, lastChapterReadName:string}} title
												 */
												if(site === 'Batoto') {
													let batotoID = (title.url.match(/([0-9]+)/) || []).pop();
													title.url = `https://mangadex.org/manga/${batotoID}`;
												}
												let tr = $('<tr/>', {role: 'row'}).append(
													$('<td/>', {style: 'width: 50%'}).append(
														$('<a/>', {
															href: title.url,
															text: title.name
														})
													)).append(
													$('<td/>', {style: 'width: 50%'}).append(
														$('<a/>', {
															href: title.lastChapterReadURL,
															text: title.lastChapterReadName
														})
													)
												);
												tbody.append(tr);
											}
										}

										$('<table/>', {
											class: 'tablesorter tablesorter-bootstrap table-striped',
											role : 'grid'
										}).append(
											$('<thead/>').append(
												$('<tr/>').append(
													$('<th/>', {text: 'Title'})
												).append(
													$('<th/>', {text: 'Current Chapter'})
												)
											)
										).append(
											tbody
										).appendTo(id);
									}
								}
							}
						}
					}
				};
				reader.readAsText(file);
			}
		}
	});

	/* http://stackoverflow.com/a/3710226/1168377 */
	function isJsonString(str) {
		try {
			JSON.parse(str);
		} catch (e) {
			return false;
		}
		return true;
	}
});
