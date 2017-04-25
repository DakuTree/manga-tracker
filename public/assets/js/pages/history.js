/*global currentPagination:false, totalPagination:false */
$(function(){
	"use strict";
	if(page !== 'history') { return false; }

	$('#history-pagination').pagination({
		currentPage    : currentPagination,

		pages          : totalPagination,
		displayedPages : 10,

		hrefTextPrefix : '/user/history/',

		selectOnClick  : false,

		prevText       : '&laquo;',
		nextText       : '&raquo;'
	});

	if(typeof titleID === 'undefined') { const titleID = 0; } //handle possible error if titleID isn't set
	$('#title-history-pagination').pagination({
		currentPage    : currentPagination,

		pages          : 5,
		displayedPages : 10,

		hrefTextPrefix : '/history/'+titleID+'/',

		selectOnClick  : false,

		prevText       : '&laquo;',
		nextText       : '&raquo;'
	});
});
