<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Signature extends CI_Controller
{
	/**
	 * Construct 
	 */
	public function __construct()
	{
		parent::__construct(); 

		$this->load->library('esign_api_gateway'); 
	}

	/**
	 * Do Signature Document 
	 * 
	 * @return json
	 */
	public function index()
	{
		$this->esign_api_gateway->execute(); 
	}
}