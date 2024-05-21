<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if (empty($this->session->userdata('login'))) {
			$this->session->set_flashdata('access', 'Anda harus login terlebih dahulu!');
			redirect(site_url());
		}
		$this->load->model('dashboard_model');
	}

	public function index()
	{
		$data['content'] = 'dashboard';
		$data['messages'] = messages();

		$tahun = $this->session->userdata('tahun');
		$jabatan_id = $this->session->userdata('jabatan_id');
		$opd_id = $this->session->userdata('opd_id');

		$informasi = $this->dashboard_model->informasi()->row_array();

		if($this->session->userdata('level') == 1){

			$data['pengajuansurat'] = $this->dashboard_model->draft_administrator($tahun)->num_rows();
			$data['suratkeluar'] = $this->dashboard_model->suratkeluar_administrator($tahun)->num_rows();
			$data['suratmasuk'] = $this->dashboard_model->suratmasuk_administrator($tahun)->num_rows();

		}elseif($this->session->userdata('level') == 4){

			$data['pengajuansurat'] = $this->dashboard_model->draft($jabatan_id,$tahun)->num_rows();
			$data['suratmasuk'] = $this->dashboard_model->suratmasuk_tu($tahun,$jabatan_id)->result();
			$data['didisposisikan'] = $this->dashboard_model->suratmasuk_tu_didisposisikan($opd_id,$tahun)->result();
			$data['tanggal'] = $informasi['tanggal'];
			$data['deskripsi'] = $informasi['deskripsi'];
			$data['disposisi'] = $this->dashboard_model->disposisi_surat($tahun,$opd_id)->num_rows();

		}else{

			$data['pengajuansurat'] = $this->dashboard_model->pengajuansurat($jabatan_id,$tahun)->num_rows();
			$data['suratmasuk'] = $this->dashboard_model->suratmasuk_disposisi($jabatan_id)->num_rows();
			$data['draft'] = $this->dashboard_model->draft($jabatan_id,$tahun)->num_rows();
			$data['tandatangan'] = $this->dashboard_model->tandatangan($jabatan_id)->num_rows();
			$data['tanggal'] = $informasi['tanggal'];
			$data['deskripsi'] = $informasi['deskripsi'];

		}

		$this->load->view('template', $data);
	}

}
