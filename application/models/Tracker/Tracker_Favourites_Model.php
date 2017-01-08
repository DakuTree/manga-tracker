<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class Tracker_Favourites_Model extends Tracker_Base_Model {
	public function __construct() {
		parent::__construct();
	}

	public function get(int $page) : array {
		$rowsPerPage = 50;
		$query = $this->db
			->select('SQL_CALC_FOUND_ROWS
			          tt.title, tt.title_url,
			          ts.site, ts.site_class,
			          tf.chapter, tf.updated_at', FALSE)
			->from('tracker_favourites AS tf')
			->join('tracker_chapters AS tc', 'tf.chapter_id = tc.id', 'left')
			->join('tracker_titles AS tt',   'tc.title_id = tt.id',   'left')
			->join('tracker_sites AS ts',    'tt.site_id = ts.id',    'left')
			->where('tc.user_id', $this->User->id) //CHECK: Is this inefficient? Would it be better to have a user_id column in tracker_favourites?
			->order_by('tf.id DESC')
			->limit($rowsPerPage, ($rowsPerPage * ($page - 1)))
			->get();

		$favourites = ['rows' => [], 'totalPages' => 1];
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$arrRow = [];

				$arrRow['updated_at'] = $row->updated_at;
				$arrRow['title']      = $row->title;
				$arrRow['title_url']  = $this->Tracker->sites->{$row->site_class}->getFullTitleURL($row->title_url);

				$arrRow['site']        = $row->site;
				$arrRow['site_sprite'] = str_replace('.', '-', $row->site);

				$chapterData = $this->Tracker->sites->{$row->site_class}->getChapterData($row->title_url, $row->chapter);
				$arrRow['chapter'] = "<a href=\"{$chapterData['url']}\">{$chapterData['number']}</a>";
				$favourites['rows'][] = $arrRow;
			}
			$favourites['totalPages'] = ceil($this->db->query('SELECT FOUND_ROWS() count;')->row()->count / $rowsPerPage);
		}

		return $favourites;
	}

	public function set(string $site, string $title, string $chapter) : array {

		$success = array(
			'status' => 'Something went wrong',
			'bool'   => FALSE
		);
		if($siteData = $this->Tracker->title->getSiteDataFromURL($site)) {
			//Validate user input
			if(!$this->sites->{$siteData->site_class}->isValidTitleURL($title)) {
				//Error is already logged via isValidTitleURL
				$success['status'] = 'Title URL is not valid';
				return $success;
			}
			if(!$this->sites->{$siteData->site_class}->isValidChapter($chapter)) {
				//Error is already logged via isValidChapter
				$success['status'] = 'Chapter URL is not valid';
				return $success;
			}

			//NOTE: If the title doesn't exist it will be created. This maybe isn't perfect, but it works for now.
			$titleID = $this->Tracker->title->getID($title, (int) $siteData->id);
			if($titleID === 0) {
				//Something went wrong.
				log_message('error', "TitleID = 0 for {$title} @ {$siteData->id}");
				return $success;
			}

			////We need the series to be tracked
			//$idCQuery = $this->db->select('id')
			//                     ->where('user_id', $this->User->id)
			//                     ->where('title_id', $titleID)
			//                     ->get('tracker_chapters');
			//if($idCQuery->num_rows() > 0) {
				//Check if it is already favourited
				$idFQuery = $this->db->select('id')
				                     ->where('user_id', $this->User->id)
				                     ->where('title_id', $titleID)
				                     ->where('chapter', $chapter)
				                     ->get('tracker_favourites');
				if($idFQuery->num_rows() > 0) {
					//Chapter is already favourited, so remove it from DB
					$idFQueryRow = $idFQuery->row();

					$isSuccess = (bool) $this->db->where('id', $idFQueryRow->id)
					                             ->delete('tracker_favourites');

					if($isSuccess) {
						$success = array(
							'status' => 'Unfavourited',
							'bool'   => TRUE
						);
						//$this->History->userRemoveFavourite($chapter_id, $chapter);
					}
				} else {
					//Chapter is not favourited, so add to DB.
					$isSuccess = (bool) $this->db->insert('tracker_favourites', [
						'user_id'         => $this->User->id,
						'title_id'        => $titleID,
						'chapter'         => $chapter,
						'updated_at'      => date('Y-m-d H:i:s')
					]);

					if($isSuccess) {
						$success = array(
							'status' => 'Favourited',
							'bool'   => TRUE
						);
						//$this->History->userAddFavourite((int) $idCQueryRow->id, $chapter);
					}
				}
			//} else {
			//	$success['status'] = 'Series needs to be tracked before we can favourite chapters';
			//}
		}
		return $success;
	}
}
