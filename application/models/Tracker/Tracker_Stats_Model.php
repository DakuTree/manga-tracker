<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class Tracker_Stats_Model extends Tracker_Base_Model {
	public function __construct() {
		parent::__construct();
	}



	public function get() : array {
		if(!($stats = $this->cache->get('site_stats'))) {
			$stats = array();

			//CHECK: Is it possible to merge some of these queries?
			$queryUsers = $this->db
				->select([
					'COUNT(*) AS total_users',
					'SUM(CASE WHEN api_key IS NOT NULL THEN 1 ELSE 0 END) AS validated_users',
					'SUM(CASE WHEN (api_key IS NOT NULL AND from_unixtime(last_login) > DATE_SUB(NOW(), INTERVAL 7 DAY)) THEN 1 ELSE 0 END) AS active_users'
				], FALSE)
				->from('auth_users')
				->get();
			$stats = array_merge($stats, $queryUsers->result_array()[0]);

			$queryCounts = $this->db
				->select([
					'tracker_titles.title',
					'COUNT(tracker_chapters.title_id) AS count'
				], FALSE)
				->from('tracker_chapters')
				->join('tracker_titles', 'tracker_titles.id = tracker_chapters.title_id', 'left')
				->group_by('tracker_chapters.title_id')
				->having('count > 1')
				->order_by('count DESC')
				->get();
			$stats['titles_tracked_more'] = count($queryCounts->result_array());
			$stats['top_title_name']  = $queryCounts->result_array()[0]['title'] ?? 'N/A';
			$stats['top_title_count'] = $queryCounts->result_array()[0]['count'] ?? 'N/A';

			$queryTitles = $this->db
				->select([
					'COUNT(DISTINCT tracker_titles.id) AS total_titles',
					'COUNT(DISTINCT tracker_titles.site_id) AS total_sites',
					'SUM(CASE WHEN from_unixtime(auth_users.last_login) > DATE_SUB(NOW(), INTERVAL 120 HOUR) IS NOT NULL THEN 0 ELSE 1 END) AS inactive_titles',
					'SUM(CASE WHEN (tracker_titles.last_updated > DATE_SUB(NOW(), INTERVAL 24 HOUR)) THEN 1 ELSE 0 END) AS updated_titles'
				], FALSE)
				->from('tracker_titles')
				->join('tracker_sites', 'tracker_sites.id = tracker_titles.site_id', 'left')
				->join('tracker_chapters', 'tracker_titles.id = tracker_chapters.title_id', 'left')
				->join('auth_users', 'tracker_chapters.user_id = auth_users.id', 'left')
				->get();
			$stats = array_merge($stats, $queryTitles->result_array()[0]);

			$querySites = $this->db
				->select([
					'tracker_sites.site',
					'COUNT(*) AS count'
				], FALSE)
				->from('tracker_titles')
				->join('tracker_sites', 'tracker_sites.id = tracker_titles.site_id', 'left')
				->group_by('tracker_titles.site_id')
				->order_by('count DESC')
				->limit(3)
				->get();
			$querySitesResult = $querySites->result_array();
			$stats['rank1_site']       = $querySitesResult[0]['site'];
			$stats['rank1_site_count'] = $querySitesResult[0]['count'];
			$stats['rank2_site']       = $querySitesResult[1]['site'];
			$stats['rank2_site_count'] = $querySitesResult[1]['count'];
			$stats['rank3_site']       = $querySitesResult[2]['site'];
			$stats['rank3_site_count'] = $querySitesResult[2]['count'];

			$queryTitlesU = $this->db
				->select([
					'COUNT(*) AS title_updated_count'
				], FALSE)
				->from('tracker_titles_history')
				->get();
			$stats = array_merge($stats, $queryTitlesU->result_array()[0]);

			$queryUsersU = $this->db
				->select([
					'COUNT(*) AS user_updated_count'
				], FALSE)
				->from('tracker_user_history')
				->get();
			$stats = array_merge($stats, $queryUsersU->result_array()[0]);

			$stats['live_time'] = timespan(/*2016-09-10T03:17:19*/ 1473477439, time(), 2);

			$this->cache->save('site_stats', $stats, 3600); //Cache for an hour
		}

		return $stats;
	}
}
