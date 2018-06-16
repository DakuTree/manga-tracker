<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Forgot_Password extends No_Auth_Controller {
	public function __construct() {
		parent::__construct();

		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters(
			$this->config->item('error_start_delimiter', 'ion_auth'),
			$this->config->item('error_end_delimiter', 'ion_auth')
		);
	}

	public function index() : void {
		$this->header_data['title'] = 'Forgot Password';
		$this->header_data['page']  = 'forgot_password';

		//TODO (RESEARCH): Should we allow username here too?
		$this->form_validation->set_rules('email', 'Email',  'required|valid_email', array(
			'required'    => 'Please enter your email.',
			'valid_email' => 'The email your entered is invalid.'
		));

		if ($this->form_validation->run() === TRUE) {
			//form is valid

			$identity = $this->ion_auth->where('email', $this->input->post('email'))->users()->row();

			//To avoid people finding out if an email is associated with the site, a valid email (regardless if it's used) will always return a success page.
			if(is_object($identity)) {
				/*$forgotten = */$this->ion_auth->forgotten_password($identity->{'email'});
				//if(!$forgotten) { print $this->email->print_debugger();}
			}

			$this->_render_page('User/Forgot_Password_Success');
		} else {
			$this->body_data['form_email'] = array(
				'name'        => 'email',
				'id'          => 'email',
				'type'        => 'text',

				'class'       => 'form-control input-lg',
				'tabindex'    => '1',

				'placeholder' => 'Email Address',
				'value'       => $this->form_validation->set_value('email'),

				'required'    => ''
			);
			$this->body_data['form_submit'] = array(
				'name'     => 'submit',
				'type'     => 'submit',

				'class'    => 'btn btn-primary btn-block btn-lg',
				'tagindex' => '2',

				'value'    => 'Send Recovery Email'
			);

			$this->_render_page('User/Forgot_Password');
		}
	}

	// reset password - final step for forgotten password
	public function reset_password($code) : void {
		$this->header_data['title'] = 'Reset Password';
		$this->header_data['page'] = 'reset-password';

		$user = $this->ion_auth->forgotten_password_check($code);
		if ($user) {
			//code is valid, show reset form or process reset
			$min_password_length = $this->config->item('min_password_length', 'ion_auth');
			$max_password_length = $this->config->item('max_password_length', 'ion_auth');
			$this->form_validation->set_rules('new_password',         'Password',         'required|min_length['.$min_password_length.']|max_length['.$max_password_length.']');
			$this->form_validation->set_rules('new_password_confirm', 'Password Confirm', 'required|matches[new_password]');

			if ($this->form_validation->run() === TRUE) {
				//form is valid, process the password reset request

				$identity = $user->{'email'};
				$change   = $this->ion_auth->reset_password($identity, $this->input->post('new_password'));

				if ($change) {
					//password changed successfully, redirect to login
					redirect('user/login', 'refresh');
				} else { //@codeCoverageIgnore
					//password changed unsuccessfully, refresh page.
					redirect('user/reset_password/'.$code, 'refresh');
				}
			} else { //@codeCoverageIgnore
				//form is invalid OR first-time viewing page
				$this->body_data['reset_code']    = $code;
				$this->body_data['form_password'] = array(
					'name'         => 'new_password',
					'id'           => 'new_password',
					'type'         => 'password',

					'class'        => 'form-control input-lg',
					'title'        => 'Password must be between 6 & 64 characters.',
					'tabindex'     => '1',

					'placeholder'  => 'Password',
					'value'        => '',
					'pattern'      => '.{6,64}',
					'autocomplete' => 'off',

					'required'     => ''
				);
				$this->body_data['form_password_confirm'] = array(
					'name'         => 'new_password_confirm',
					'id'           => 'new_password_confirm',
					'type'         => 'password',

					'class'        => 'form-control input-lg',
					'tabindex'     => '2',

					'placeholder'  => 'Confirm Password',
					'value'        => '',
					'autocomplete' => 'off',

					'required'     => ''
				);
				$this->body_data['form_submit'] = array(
					'name' => 'submit',
					'type' => 'submit',

					'class' => 'btn btn-primary btn-block btn-lg',
					'tagindex' => '3',

					'value' => 'Reset Password'
				);

				// render
				$this->_render_page('User/Reset_Password');
			}
		} else {
			//code is invalid, send them back to forgot password page
			redirect('user/forgot_password');
		}
	}
}
