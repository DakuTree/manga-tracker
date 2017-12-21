/* globals page, base_url, use_live_countdown_timer, list_sort_type, list_sort_order, site_aliases */
$(function(){
	'use strict';
	if(page !== 'dashboard_beta') { return false; }

	load();

	function load() {
		$.getJSON(base_url + 'api/internal/get_list/all', function(json) {
			/** @namespace json.extra_data */
			/** @namespace json.extra_data.inactive_titles */
			handleInactive(json.extra_data.inactive_titles);

			handleList(json.series);
		});
	}

	function handleInactive(inactive_titles) {
		//TODO: The <ul> list should be hidden by default, and only shown if a button is clicked?

		let $inactiveContainer     = $('#inactive-container'),
		    $inactiveListContainer = $inactiveContainer.find('> #inactive-list-container'),
		    $inactiveList          = $inactiveListContainer.find('> ul');

		if(Object.keys(inactive_titles).length) {
			for (let url in inactive_titles) {
				if(inactive_titles.hasOwnProperty(url)) {
					let domain      = url.split('/')[2],
					    domainClass = domain.replace(/\./g, '-');
					if(site_aliases[domainClass]) {
						domainClass = site_aliases[domainClass];
					}

					//FIXME: Don't append if already exists in list!
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

	function handleList(series) {
		let enabledCategories = Object.keys(series).filter((n) => ['custom1', 'custom2', 'custom3'].includes(n));

		let $nav = $('#list-nav-category').find('> .navbar-nav');
		for (let i = 0, len = enabledCategories.length; i < len; i++) {
			let category = enabledCategories[i];
			$nav.append(
				$('<li/>').append(
					$('<a/>', {href: '#', 'data-list': category, text: series[category].name})
				)
			);
		}


		console.log(series);
	}
});
