<?php defined('BASEPATH') OR exit('no direct script access allowed'); 

class Auth extends CI_Controller 
{
	/**
	 * Construct 
	 */
	public function __construct()
	{
		parent::__construct(); 

		$this->load->model('api_users');
	}

	protected function _validateLogin()
	{
		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() == FALSE) {
			return __return_json([
				'message' => $this->form_validation->error_array()
			], 403); 
		}
	}

	/**
	 * do Login
	 * 
	 * @return CodeIgniter\Output\JsonResponse
	 */
	public function login()
	{
		if (__isPostRequest()) {
			$this->_validateLogin(); 
			if ($this->form_validation->run()) {
				return $this->api_users->doLogin(); 
			}
		} else {
			__show_404(); 
		}
	}
}