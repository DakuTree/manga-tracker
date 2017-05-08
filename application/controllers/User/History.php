<?php defined('BASEPATH') or exit('No direct script access allowed');

class History extends Auth_Controller {
	public function __construct() {
		parent::__construct();
	}

	public function index(int $page = 1) {
		if($page === 0) redirect('user/history/1');

		$this->header_data['title'] = "History";
		$this->header_data['page']  = "history";

		$historyData = $this->History->userGetHistory($page);
		$this->body_data['historyData'] = $historyData['rows'];
		$this->body_data['currentPage'] = (int) $page;
		$this->body_data['totalPages']  = $historyData['totalPages'];

		if($page > $this->body_data['totalPages'] && $page > 1) redirect('user/history/1');

		$this->_render_page('User/History');
	}

	public function export(string $type) : void {
		$historyData = $this->History->userGetHistoryAll();

		switch($type) {
			case 'json':
				$this->_render_json($historyData, TRUE, 'tracker-history');
				break;

			case 'csv':
				$this->output->set_content_type('text/csv', 'utf-8');
				$this->_render_content($this->arrayToCSVRecursive($historyData), 'csv',TRUE, 'tracker-history');
				break;

			default:
				//404
				break;
		}
	}

	/**
	 * Formats a line (passed as a fields  array) as CSV and returns the CSV as a string.
	 * Adapted from http://us3.php.net/manual/en/function.fputcsv.php#87120
	 * SEE: http://stackoverflow.com/a/3933816/1168377
	 */
	private function arrayToCSV(array &$fields, $delimiter = ',', $enclosure = '"', $encloseAll = FALSE, $nullToMysqlNull = FALSE) {
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
	private function arrayToCSVRecursive(array &$fields, $delimiter = ',', $enclosure = '"', $encloseAll = FALSE, $nullToMysqlNull = FALSE) {
		$csvArr = [
			'Date/Time,Title,URL,Site,Status'
		];
		foreach ($fields as $field) {
			$csvArr[] = $this->arrayToCSV($field, $delimiter, $enclosure,$encloseAll,$nullToMysqlNull);
		}

		return implode(PHP_EOL,$csvArr);
	}
}
