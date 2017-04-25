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
});
