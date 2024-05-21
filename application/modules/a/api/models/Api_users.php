<?php defined('BASEPATH') OR exit('no direct script access allowed'); 

class Api_users extends CI_Model
{
	/**
	 * Construct 
	 */
	public function __construct()
	{
		parent::__construct(); 

		$this->load->library('token');
	}

	/**
	 * doLogin 
	 * 
	 * @return object 
	 */
	public function doLogin()
	{
		$loginQuery = $this->db; 
		$loginQuery = $loginQuery->select('*'); 
		$loginQuery = $loginQuery->from('users'); 
		$loginQuery = $loginQuery->where('username', $this->input->post('username')); 
		$loginQuery = $loginQuery->where('password', sha1($this->input->post('password'))); 
		$loginQuery = $loginQuery->get(); 

		if ($loginQuery->num_rows()) {
			$userData = $loginQuery->row(); 
			$token = $this->token->encode($userData, $this->config->item('encryption_key')); 
			__return_json([
				'status' => 'success', 
				'token' => $token
			]); 
		} else {
			__return_json([
				'status' => 'error',
				'message' => 'Username atau password yang anda masukan salah'
			], 400); 
		}
	}
}