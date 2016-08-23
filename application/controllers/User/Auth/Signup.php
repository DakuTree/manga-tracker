<?php defined('BASEPATH') or exit('No direct script access allowed');

class Signup extends No_Auth_Controller {
	public function __construct() {
		parent::__construct();

		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters(
			$this->config->item('error_start_delimiter', 'ion_auth'),
			$this->config->item('error_end_delimiter', 'ion_auth')
		);

		$this->load->model('Auth_Model', 'Auth');
	}

	//Signup is done in multiple parts.
	// 1. User visits user/signup, inputs email. Site checks if email is new, and if so, sends verify email to said address.
	//        Page now shows a continuation code box + a note saying you can also click link in email.
	// 2. User clicks link in email, is now taken to proper sign-up page. We now know the email is valid.
	//        Signup continues like normal.

	public function index($verificationCode = NULL) {
		$this->header_data['title'] = "Signup";
		$this->header_data['page']  = "signup";

		if(is_null($verificationCode)) {
			$this->_initial_signup();
		} else {
			$this->_continue_signup($verificationCode);
		}
	}

	//This initial signup occurs when the user is first shown the page.
	private function _initial_signup() {
		//user is provided with an email form
		//user enters email, submits, email is sent to verify it exists and to continue setup.

		$this->form_validation->set_rules('email', 'Email:', 'trim|required|valid_email|is_unique_email', array(
			'required'        => 'Email field is empty.',
			'valid_email'     => 'Email is an invalid format',
			'is_unique_email' => 'Email already exists.'
		));

		if ($isValid = $this->form_validation->run() === TRUE) {
			$email = $this->Auth->parse_email($this->input->post('email'));

			$this->body_data['email'] = $email;
			if($this->Auth->verification_start($email)) {
				$this->_render_page('User/Signup_Verification');
			} else {
				//TODO: We should have a better error return here.
				$isValid = FALSE;
			}
		}

		//login wasn't valid, failed, or this is a fresh login attempt
		if(!$isValid){
			//display the email validation form

			$this->global_data['notices'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->body_data['form_email'] = array(
					'name'        => 'email',
					'id'          => 'email',
					'type'        => 'email',

					'class'       => 'form-control input-lg',
					'tabindex'    => '1',

					'placeholder' => 'Email Address',
					'value'       => '',

					'required'    => ''
			);
			$this->body_data['form_submit'] = array(
					'name'     => 'submit',
					'type'     => 'submit',

					'class'    => 'btn btn-primary btn-block btn-lg',
					'tagindex' => '2',

					'value'    => 'Send verification email.'
			);

			$this->_render_page('User/Signup');
		}
	}

	//This continued signup occurs after the user clicks the verification link in their email.
	private function _continue_signup($verificationCode) {
		//check if validation is valid, if so return email, if not redirect to signup
		if(!($email = $this->Auth->verification_check($verificationCode))) redirect('user/signup');

		//validation is valid, proceed as normal
		$this->form_validation->set_rules('username',         'Username',           'required|min_length[4]|max_length[15]|valid_username|is_unique_username');
		$this->form_validation->set_rules('password',         'Password',           'required|valid_password');
		$this->form_validation->set_rules('password_confirm', 'Confirm Password',   'required|matches[password]');
		$this->form_validation->set_rules('terms',            'Terms & Conditions', 'required');
		//TODO: timezone
		//TODO: receive email

		if ($isValid = $this->form_validation->run() === TRUE) {
			$username = $this->input->post('username');
			$password = $this->input->post('password');

			$additional_data = array(
				'username' => $username // ion_auth is extremely retarded, and doesn't save username if email is being used as identity, we need to force it.
			); //TODO: For extra user info.

			if($this->ion_auth->register($username, $password, $email, $additional_data)) {
				//Signup succeeded.

				$this->Auth->verification_complete($email);

				redirect('/'); //TODO: We should redirect to an "after" signup page.
			} else { //@codeCoverageIgnore
				//Signup failed.

				$this->session->set_flashdata('notices', $this->ion_auth->errors());

				$isValid = FALSE;
			}
		}

		//signup wasn't valid, failed, or this is a fresh signup attempt
		if(!$isValid){
			//display the create user form

			$this->global_data['notices'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->body_data['verificationCode'] = $verificationCode;

			$this->body_data['form_username'] = array(
				'name'        => 'username',
				'id'          => 'username',
				'type'        => 'text',

				'class'       => 'form-control input-lg',
				'title'       => 'Username must be 4-15 characters, and can only contain a-z, A-Z, 0-9, _ & - characters.',
				'tabindex'    => '1',

				'placeholder' => 'Username (4-15 characters)',
				'value'       => $this->form_validation->set_value('username'),
				'pattern'     => '[a-zA-Z0-9_-]{4,15}',

				'required'    => ''
			);
			$this->body_data['form_email'] = array(
				'name'        => 'email',
				'id'          => 'email',
				'type'        => 'text',

				'class'       => 'form-control input-lg',
				'tabindex'    => '-1',

				'placeholder' => 'Email Address',
				'value'       => $email,
				'readonly'    => '1',

				'required'    => ''
			);
			$this->body_data['form_password'] = array(
				'name'         => 'password',
				'id'           => 'password',
				'type'         => 'password',

				'class'        => 'form-control input-lg',
				'title'        => 'Password must be between 6 & 64 characters.',
				'tabindex'     => '2',

				'placeholder'  => 'Password',
				'value'        => '',
				'pattern'      => '.{6,64}',
				'autocomplete' => 'off',

				'required'     => ''
			);
			$this->body_data['form_password_confirm'] = array(
				'name'        => 'password_confirm',
				'id'          => 'password_confirm',
				'type'        => 'password',

				'class'       => 'form-control input-lg',
				'title'       => 'This field must match the password field',
				'tabindex'    => '3',

				'placeholder' => 'Confirm Password',
				'value'       => '',

				'required'    => ''
			);
			$this->body_data['form_terms'] = array(
				'name'         => 'terms',
				'id'           => 'terms',
				'type'         => 'checkbox',

				'class'        => 'hidden',
				'tagindex'     => '4',
				'title'        => 'You must click to accept TOS.',

				'value'        => '1',
				'autocomplete' => 'off',

				'required'     => ''
			);
			$this->body_data['form_submit'] = array(
				'name'     => 'submit',
				'type'     => 'submit',

				'class'    => 'btn btn-primary btn-block btn-lg',
				'tagindex' => '5',

				'value'    => 'Register'
			);

			$this->_render_page('User/Signup_Continued');
		}
	}
}
