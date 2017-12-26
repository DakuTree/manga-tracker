<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

//http://english.stackexchange.com/a/141735
class Tracker_Portation_Model extends Tracker_Base_Model {
	public function __construct() {
		parent::__construct();
	}

	public function importFromJSON(string $json_string) : array {
		//We already know the this is a valid JSON string as it was validated by form_validator.
		$json = json_decode($json_string, TRUE);

		/*
		 * 0 = Success
		 * 1 = Invalid keys.
		 * 2 = Has failed rows
		 */
		$status = ['code' => 0, 'failed_rows' => []];

		$categories = array_keys($json);
		if(count($categories) === count(array_intersect(['reading', 'on-hold', 'plan-to-read', 'custom1', 'custom2', 'custom3'], $categories))) {
			$json_keys = array_keys(call_user_func_array('array_merge', call_user_func_array('array_merge', $json)));

			if(count($json_keys) === 3 && !array_diff(array('site', 'title_url', 'current_chapter'), $json_keys)) {
				foreach($categories as $category) {
					foreach($json[$category] as $row) {
						$success = $this->Tracker->list->update($this->User->id, $row['site'], $row['title_url'], $row['current_chapter']);
						if(!$success) {
							$status['code']          = 2;
							$status['failed_rows'][] = $row;
						}
					}
				}
			} else {
				$status['code'] = 1;
			}
		} else {
			$status['code'] = 1;
		}
		return $status;
	}

	public function export() {
		$query = $this->db
			->select('
			    tracker_chapters.current_chapter,
			    tracker_chapters.category,
			    tracker_chapters.mal_id,
			    tracker_chapters.tags,

			    tracker_titles.title_url,

			    tracker_sites.site
			')
			->from('tracker_chapters')
			->join('tracker_titles', 'tracker_chapters.title_id = tracker_titles.`id', 'left')
			->join('tracker_sites', 'tracker_sites.id = tracker_titles.site_id', 'left')
			->where('tracker_chapters.user_id', $this->User->id)
			->where('tracker_chapters.active', 'Y')
			->get();

		$arr = [];
		if($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$arr[$row->category][] = [
					'site'            => $row->site,
					'title_url'       => $row->title_url,
					'current_chapter' => $row->current_chapter,

					'tag_list'        => $row->tags,
					'mal_id'          => $row->mal_id
				];
			}

			return $arr;
		}
	}

	/**
	 * Formats a line (passed as a fields  array) as CSV and returns the CSV as a string.
	 * Adapted from http://us3.php.net/manual/en/function.fputcsv.php#87120
	 * SEE: http://stackoverflow.com/a/3933816/1168377
	 *
	 * @param array  $fields
	 * @param string $delimiter
	 * @param string $enclosure
	 * @param bool   $encloseAll
	 * @param bool   $nullToMysqlNull
	 *
	 * @return string
	 */
	public function arrayToCSV(array &$fields, $delimiter = ',', $enclosure = '"', $encloseAll = FALSE, $nullToMysqlNull = FALSE) : string {
		$delimiter_esc = preg_quote($delimiter, '/');
		$enclosure_esc = preg_quote($enclosure, '/');

		$output = array();
		foreach ($fields as $field) {
			if ($field === NULL && $nullToMysqlNull) {
				$output[] = 'NULL';
				continue;
			}

			// Enclose fields containing $delimiter, $enclosure or whitespace
			if ($encloseAll || preg_match("/(?:${delimiter_esc}|${enclosure_esc}|\s)/", $field)) {
				$output[] = $enclosure . str_replace($enclosure, $enclosure . $enclosure, $field) . $enclosure;
			} else {
				$output[] = $field;
			}
		}

		return implode($delimiter, $output);
	}
	public function arrayToCSVRecursive(array &$fields, string $headers = '', $delimiter = ',', $enclosure = '"', $encloseAll = FALSE, $nullToMysqlNull = FALSE) : string {
		$csvArr = [];
		if(!empty($headers)) {
			$csvArr[] = $headers;
		}

		foreach ($fields as $field) {
			$csvArr[] = $this->arrayToCSV($field, $delimiter, $enclosure,$encloseAll,$nullToMysqlNull);
		}

		return implode(PHP_EOL,$csvArr);
	}
}
