<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class Suratkeluar extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if (empty($this->session->userdata('login'))) {
			$this->session->set_flashdata('access', 'Anda harus login terlebih dahulu!');
			redirect(site_url());
		}
		$this->load->model('arsip_model');
	}

	public function index()
	{
		$data['content'] = 'suratkeluar_index';
		
		$tahun = $this->session->userdata('tahun');
		$opd_id = $this->session->userdata('opd_id');

		$data['suratkeluar'] = $this->arsip_model->get($opd_id,$tahun)->result();
		
		$this->load->view('template', $data);
	}

}