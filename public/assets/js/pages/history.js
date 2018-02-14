/* globals page, currentPagination, totalPagination, titleID */
$(function(){
	'use strict';
	if(page !== 'history') { return false; }

	$('.pagination').pagination({
		currentPage    : currentPagination,

		pages          : totalPagination,
		displayedPages : 10,

		hrefTextPrefix : (/user\/history/.test(location.pathname) ? '/user/history/' : `/history/${titleID}/`),

		selectOnClick  : false,

		cssStyle       : '',

		prevText       : '&laquo;',
		nextText       : '&raquo;',

		onInit         : function() {
			$('.pagination li').addClass('page-item'); //This is removed by the plugin
			$('.pagination .current').addClass('page-link');
		}
	});
});
