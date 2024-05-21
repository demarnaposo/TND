<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inbox extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if (empty($this->session->userdata('login'))) {
			$this->session->set_flashdata('access', 'Anda harus login terlebih dahulu!');
			redirect(site_url());
		}
		$this->load->model('inbox_model');
	}

	public function index()
	{	
		$level = $this->session->userdata('level');
		$tahun = $this->session->userdata('tahun');
		$opd_id = $this->session->userdata('opd_id');
		$jabatan_id = $this->session->userdata('jabatan_id');

		if ($level == 4) {
			$data['content'] = 'inbox_tu';
			$data['inbox'] = $this->inbox_model->get_disposisisurat($jabatan_id,$opd_id,$tahun)->result();
		}else{
			$data['content'] = 'inbox_aparatur';
			$data['inbox'] = $this->inbox_model->get_disposisi($jabatan_id,$tahun)->result();
			$data['aparatur'] = $this->inbox_model->get_bawahan($jabatan_id,$opd_id)->result();
		}
		
		$this->load->view('template', $data);
	}

	public function disposisi()
	{
		if (isset($_POST['disposisi'])) {

			if (empty($this->input->post('harap'))) {
				$harap = '';
			}else{
				$harap = implode(',', $this->input->post('harap'));
			}

			$aparatur_id = implode(',', $this->input->post('aparatur_id'));
			$explodeAparatur = explode(',', $aparatur_id);
			$dataAparatur = array();
			$index = 0;
			foreach ($explodeAparatur as $key => $h) {
				array_push($dataAparatur, array(
					'suratmasuk_id' => $this->input->post('suratmasuk_id'), 
					'aparatur_id' => $h, 
					'users_id' => $this->input->post('users_id'), 
					'harap' => 	$harap, 
					'keterangan' => $this->input->post('keterangan'), 
					));
				$index++;
			}

			$dispos = $this->inbox_model->insert_aparatur('disposisi_suratmasuk', $dataAparatur);
			if ($dispos) {

				//update status
				$this->inbox_model->update_data('disposisi_suratmasuk', array('Status' => 'Selesai Disposisi'), array('dsuratmasuk_id' => $this->input->post('dsuratmasuk_id')));

				$this->session->set_flashdata('success', 'Surat Berhasil Didisposisikan');
				redirect(site_url('suratmasuk/inbox'));
			}else{
				$this->session->set_flashdata('error', 'Surat Gagal Didisposisikan');
				redirect(site_url('suratmasuk/inbox'));
			}

		}elseif (isset($_POST['selesai'])){

			$data = array('status' => 'Selesai');
			$where = array('dsuratmasuk_id' => $this->input->post('dsuratmasuk_id'));
			$update = $this->inbox_model->update_data('disposisi_suratmasuk', $data, $where);

			if ($update) {
				$this->session->set_flashdata('success', 'Surat Berhasil Diselesaikan');
				redirect(site_url('suratmasuk/inbox'));
			}else{
				$this->session->set_flashdata('error', 'Surat Gagal Diselesaikan');
				redirect(site_url('suratmasuk/inbox'));
			}

		}else{

			$jabatan = $this->db->get_where('jabatan', array('jabatan_id' => $this->session->userdata('jabatan_id')))->row_array();
			if ($jabatan['atasan_id'] == 0) {
				$getBawahan = $this->db->get_where('jabatan', array('atasan_id' => $this->session->userdata('jabatan_id')))->row_array();
				$getAtasan = $this->db->get_where('aparatur', array('jabatan_id' => $getBawahan['jabatan_id']))->row_array();
			}else{
				$getAtasan = $this->db->get_where('aparatur', array('jabatan_id' => $jabatan['atasan_id']))->row_array();
			}
			$harap = implode(',', $this->input->post('harap'));
			$data = array(
				'suratmasuk_id' => $this->uri->segment(4), 
				'aparatur_id' => $getAtasan['jabatan_id'], 
				'users_id' => $this->session->userdata('jabatan_id'), 
				'harap' => 	'', 
				'keterangan' => '', 
			);

			$dispos = $this->inbox_model->insert_data('disposisi_suratmasuk', $data);
			if ($dispos) {
				$this->session->set_flashdata('success', 'Surat Berhasil Didisposisikan');
				redirect(site_url('suratmasuk/surat'));
			}else{
				$this->session->set_flashdata('error', 'Surat Gagal Didisposisikan');
				redirect(site_url('suratmasuk/surat'));
			}

		}
	}

	public function selesai()
	{
		
		$level = $this->session->userdata('level');

		if ($level == 4) {
			$data['content'] = 'selesai_tu';
			$data['selesai'] = $this->inbox_model->get_selesai_tu($this->session->userdata('tahun'),$this->session->userdata('opd_id'))->result();
		}else{
			$data['content'] = 'selesai_aparatur';
			$data['selesai'] = $this->inbox_model->get_selesai_aparatur()->result();
			$data['tahun'] = $this->session->userdata('tahun');
			$data['opd_id'] = $this->session->userdata('opd_id');
			$data['jabatan_id'] = $this->session->userdata('jabatan_id');
		}

		$this->load->view('template', $data);
	}

}