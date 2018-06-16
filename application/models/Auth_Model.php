<?php declare(strict_types=1); defined('BASEPATH') or exit('No direct script access allowed');

class Auth_Model extends CI_Model {
	public function __construct() {
		parent::__construct();

		$this->load->database();

		$this->load->library('email');
	}

	/**
	 * @param string $email
	 * @return bool
	 */
	public function verificationStart(string $email) : bool {
		//user is trying to create an account, send them an email verification email
		//at this point we know the email is valid and currently not used
		//we need to add row to database, as well as send the user an email

		$verificationCode = sha1(md5(microtime()));

		$success = FALSE;
		try {
			//add verification code to database
			if($this->db->select('*')->where('email', $email)->get('auth_signup_verification')->num_rows() > 0) {
				//email exists in verification DB, do a simple update.
				if(!$this->db->update('auth_signup_verification', array(
						'verification_code'      => $verificationCode,
						'verification_code_time' => time()
				), array('email' => $email))
				) {
					throw new Exception('Unable to insert email into database.');
				}
			} else {
				if(!$this->db->insert('auth_signup_verification', array(
						'email'                  => $email,
						'verification_code'      => $verificationCode,
						'verification_code_time' => time()
				))
				) {
					throw new Exception('Unable to insert email into database.');
				}
			}
			//send email to user to verify signup
			$message = $this->load->view($this->config->item('email_templates', 'ion_auth').$this->config->item('email_activate', 'ion_auth'), array(
					'email'             => $email,
					'verification_code' => $verificationCode,
					'verification_url'  => base_url("user/signup/{$verificationCode}")
			), TRUE);

			//TODO: Make an easy email helper
			$this->email->from($this->config->item('admin_email', 'ion_auth'), $this->config->item('site_title', 'ion_auth'));
			$this->email->to($email);
			$this->email->subject($this->config->item('site_title', 'ion_auth').' - Email Verification');
			$this->email->message($message);
			if(!$this->email->send()) {
				throw new Exception('Unable to send email to address provided.');
			}

			$success = TRUE;
		} catch (Exception $e) {
			//echo 'Caught exception: ',  $e->getMessage(), "\n";

			//revert verification
			$this->db->delete('auth_signup_verification', array('email' => $email));
		}

		return $success;
	}

	/**
	 * @param string $verificationCode
	 * @return mixed
	 */
	public function verificationCheck(string $verificationCode) {
		//user is trying to validate their email for signup, check if verification code is still valid/exists
		$query = $this->db->select('email, verification_code_time')
		                  ->from('auth_signup_verification')
		                  ->where(array('verification_code' => $verificationCode))
		                  ->get();

		$return = FALSE;
		if($query->num_rows() > 0) {
			$result = $query->row();

			if((time() - $result->verification_code_time) > 46400000) {
				//expired, past the 24hr mark

				$this->session->set_flashdata('errors', 'Verification code expired. Please re-submit signup.');
				$this->db->delete('auth_signup_verification')
				         ->where(array('verification_code' => $verificationCode));
			} else {
				//not expired, verification is valid, return email
				$return =  $result->email;
			}
		}
		return $return;
	}

	/**
	 * @param string $email
	 * @return bool
	 */
	public function verificationComplete(string $email) : bool {
		//user has completed signup, remove verification from DB
		return $this->db->delete('auth_signup_verification', array('email' => $email));
	}


	/**
	 * @param $identity
	 *
	 * @return string|null
	 */
	public function getEmailFromIdentity(string $identity) : ?string {
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
				$email = NULL;
			}
		}

		return $email;
	}

	//NOTE: This assumes we know the email is valid.
	public function parseEmail(string $email) : string {
		$email_parts = explode('@', $email);
		return $email_parts[0].'@'.strtolower($email_parts[1]); //Only the first half of the email can be case sensitive
	}
}
