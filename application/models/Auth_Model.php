<?php declare(strict_types=1); defined('BASEPATH') or exit('No direct script access allowed');

class Auth_Model extends CI_Model {
	public function __construct() {
		parent::__construct();

		$this->load->database();

		$this->load->library('email');
	}

	/** SIGNUP verification **/
	/**
	 * @param string $email
	 * @return bool
	 */
	public function verification_start(string $email) : bool {
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
				};
			} else {
				if(!$this->db->insert('auth_signup_verification', array(
						'email'                  => $email,
						'verification_code'      => $verificationCode,
						'verification_code_time' => time()
				))
				) {
					throw new Exception('Unable to insert email into database.');
				};
			}
			//send email to user to verify signup
			$message = $this->load->view($this->config->item('email_templates', 'ion_auth').$this->config->item('email_activate', 'ion_auth'), array(
					'email'             => $email,
					'verification_code' => $verificationCode,
					'verification_url'  => base_url("user/signup/{$verificationCode}")
			), TRUE);

			//TODO: Make an easy email helper
			$this->email->from('admin@codeanimu.net', 'Manga Tracker');
			$this->email->to($email);
			$this->email->subject('Manga Tracker - Email Verification');
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
	public function verification_check(string $verificationCode) {
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

				//TODO: Remove from DB, send user error that verification expired.
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
	public function verification_complete(string $email) : bool {
		//user has completed signup, remove verification from DB
		return $this->db->delete('auth_signup_verification', array('email' => $email));
	}

	//forgotten_password_time' => time()
//$activation_code       = sha1(md5(microtime()));
}
