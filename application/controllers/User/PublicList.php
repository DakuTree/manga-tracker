<?php defined('BASEPATH') or exit('No direct script access allowed');

class PublicList extends MY_Controller {
	public function __construct() {
		parent::__construct();


		$this->load->library('form_validation');
	}

	public function index(?string $username = NULL, string $type = 'html') : void {
		$show_404 = FALSE;

		$type = mb_strtolower($type);
		if(
			(!is_null($username) && $this->form_validation->valid_username($username) && ($user = $this->User->get_user_by_username($username)))
			&& in_array($type, ['html', 'json', 'csv'])
			&& (($this->User->id == $user->id) || ($this->User_Options->get('enable_public_list', $user->id) == 'enabled' ? TRUE : FALSE))//&& get option enabled
		) {
			$this->header_data['title'] = "{$username}'s list";
			$this->header_data['page']  = "dashboard";

			$trackerData = $this->Tracker->list->get($user->id);
			switch($type) {
				case 'csv':
					$csvArr = [];
					foreach ($trackerData['series'] as $category => $categoryData) {
						$categoryName = $categoryData['name'];

						foreach ($categoryData['manga'] as $manga) {
							$csvArr[] = [
								$categoryName,
								'=HYPERLINK("' . $manga['full_title_url'] . '","' . $manga['title_data']['title'] . '")',
								$manga['site_data']['site'],

								'=HYPERLINK("' . $manga['generated_current_data']['url'] . '","' . addslashes($manga['generated_current_data']['number']) . '")',
								'=HYPERLINK("' . $manga['generated_latest_data']['url'] . '","' . addslashes($manga['generated_latest_data']['number']) . '")',

								$manga['tag_list'],
								$manga['mal_id'],
								$manga['last_updated']
							];
						}
					}

					$this->output->set_content_type('text/csv', 'utf-8');
					$this->_render_content($this->Tracker->portation->arrayToCSVRecursive($csvArr, 'Category,Title,Site,My Status,Latest Chapter,Tag List,MAL ID, Last Updated', ',', '"', FALSE, TRUE), 'csv', TRUE, 'tracker-data');
					break;

				case 'html':
					$this->body_data['trackerData']  = $trackerData['series'];

					$this->header_data['show_header'] = FALSE;
					$this->footer_data['show_footer'] = FALSE;

					$this->body_data['category_custom_1']      = ($this->User_Options->get('category_custom_1', $user->id) == 'enabled' ? TRUE : FALSE);
					$this->body_data['category_custom_1_text'] = $this->User_Options->get('category_custom_1_text', $user->id);

					$this->body_data['category_custom_2']      = ($this->User_Options->get('category_custom_2', $user->id) == 'enabled' ? TRUE : FALSE);
					$this->body_data['category_custom_2_text'] = $this->User_Options->get('category_custom_2_text', $user->id);

					$this->body_data['category_custom_3']      = ($this->User_Options->get('category_custom_3', $user->id) == 'enabled' ? TRUE : FALSE);
					$this->body_data['category_custom_3_text'] = $this->User_Options->get('category_custom_3_text', $user->id);

					$this->_render_page('User/PublicList');
					break;

				case 'json':
					$trackerData = $this->_walk_recursive_remove($trackerData, function($v, $k) {
						return in_array($k, ['mal_icon']);
					});
					$this->_render_json($trackerData);
					break;

				default:
					//This will never happen.
					break;
			}

		} else {
			$show_404 = TRUE;
		}

		if($show_404) show_404();
	}

	private function _walk_recursive_remove (array $array, callable $callback) : array {
		foreach ($array as $k => $v) {
			if (is_array($v)) {
				$array[$k] = $this->_walk_recursive_remove($v, $callback);
			} else {
				if ($callback($v, $k)) {
					unset($array[$k]);
				}
			}
		}

		return $array;
	}
}
