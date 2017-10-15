/* globals page, base_url, use_live_countdown_timer, list_sort_type, list_sort_order */
$(function(){
	'use strict';
	if(page !== 'dashboard_beta') { return false; }

	load();

	function load() {
		$.getJSON(base_url + 'api/internal/get_list/all', function(json) {
			handleInactive(json.has_inactive, json.inactive_titles);
		});
	}

	function handleInactive(has_inactive, inactive_titles) {
		//TODO: The <ul> list should be hidden by default, and only shown if a button is clicked?

		let $inactiveContainer     = $('#inactive-container'),
		    $inactiveListContainer = $inactiveContainer.find('> #inactive-list-container'),
		    $inactiveList          = $inactiveListContainer.find('> ul');

		if(has_inactive) {
			for (let url in inactive_titles) {
				if(inactive_titles.hasOwnProperty(url)) {
					let domain      = url.split('/')[2],
					    domainClass = domain.replace(/\./g, '-');
					$('<li/>').append(
						$('<i/>', {class: `sprite-site sprite-${domainClass}`, title: domain})).append(
						$('<a/>', {text: ' '+inactive_titles[url], href: url})
					).appendTo($inactiveList);
				}
			}

			$inactiveContainer.removeClass('hidden');
		} else {
			$inactiveContainer.addClass('hidden');

			$inactiveList.find('> li').empty();
		}

		$('#inactive-display').on('click', function() {
			$(this).hide();
			$inactiveListContainer.removeClass('hidden');
		});
	}
});
