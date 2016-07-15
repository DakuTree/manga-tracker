<?php declare(strict_types=1); defined('BASEPATH') OR exit('No direct script access allowed');

class User_Model extends CI_Model {
	public $id;
	public $username;
	private $email;

	public function __construct() {
		parent::__construct();

		//CHECK: Should this be placed elsewhere?
		if($this->logged_in()) {
			if(!$this->session->userdata('username')) {
				$this->session->set_userdata('username', $this->ion_auth->user()->row()->username);
			}
			if(!$this->session->userdata('email')) {
				//CHECK: This seems like a bad idea?
				$this->session->set_userdata('email', $this->ion_auth->user()->row()->email);
			}
		}

		$this->id       = (int) $this->ion_auth->get_user_id();
		$this->username = $this->session->userdata('username');
		$this->email    = $this->session->userdata('email');
	}

	public function logged_in() : bool {
		return $this->ion_auth->logged_in();
	}

	public function login_redirect() {
		if(!strpos(current_url(), '/import_list') && !strpos(current_url(), '/export_list')) {
			$this->session->set_flashdata('referred_from', current_url());
		}
		//FIXME: We should handle the redirect here too, but it causes issues with tests
		//redirect('user/login');
	}

	public function username_exists(string $username) : bool {
		$this->load->database();

		$query = $this->db->select('*')
		                  ->from('auth_users')
		                  ->where('username', $username)
		                  ->get();

		return (bool) $query->num_rows();
	}

	/**
	 * @param $identity
	 *
	 * @return mixed
	 */
	public function find_email_from_identity(string $identity) {
		//login allows using email or username, but ion_auth doesn't support this
		//check if identity is email, and if not, try and find it
		//returns: email or FALSE
		//CHECK: How should we handle invalid emails being passed to this?
		$email = $identity;

		if(!strpos($identity, '@')) {
			//identity does not contain @, assume username
			$this->load->database();

			$query = $this->db->select('email')
			                  ->from('auth_users')
			                  ->where('username', $identity)
			                  ->get();

			if($query->num_rows() > 0) {
				//username exists, grab email
				$email = $query->row('email');
			}else{
				//username doesn't exist, return FALSE
				$email = FALSE;
			}
		}

		return $email;
	}

	public function get_user_by_username(string $username) {
		$user = NULL;

		$query = $this->db->select('*')
		                  ->from('auth_users')
		                  ->where('username', $username)
		                  ->get();

		if($query->num_rows() > 0) {
			$user = $query->row();
		}
		return $user;
	}

	public function get_gravatar_url($email = NULL, $size = NULL) : string {
		$email = $email ?? $this->email;
		//TODO: FIXME ON PROFILE PAGES
		return $this->gravatar->get($email, $size);
	}

	public function get_new_api_key() : string {
		$api_key = NULL;
		if($this->logged_in()) {
			$api_key = substr("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ", mt_rand(0, 51), 1) . substr(md5((string) time()), 1);

			$this->db->where('id', $this->id);
			$this->db->update('auth_users', ['api_key' => $api_key]);
		}

		return $api_key;
	}
	
	public function get_id_from_api_key(string $api_key) {
		$query = $this->db->select('id')
		                  ->from('auth_users')
		                  ->where('api_key', $api_key)
		                  ->get();

		if($query->num_rows() > 0) {
			$userID = $query->row('id');
		}

		return $userID ?? FALSE;
	}
}
