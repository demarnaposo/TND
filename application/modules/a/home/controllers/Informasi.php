<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Informasi extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if (empty($this->session->userdata('login'))) {
			$this->session->set_flashdata('access', 'Anda harus login terlebih dahulu!');
			redirect(site_url());
		}
	}

	public function index()
	{
		$data['content'] = 'informasi';
		$data['informasi'] = $this->db->get_where('informasi', array('status' => "Publish"))->result();
		$this->load->view('template', $data);
	}

}
