<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Disposisi extends CI_Controller {

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
		$data['content'] = 'disposisi';

		$tahun = $this->session->userdata('tahun');
		$opd_id = $this->session->userdata('opd_id');
		
		$query = $this->db->query("
			SELECT * FROM disposisi_suratmasuk
			JOIN surat_masuk ON surat_masuk.suratmasuk_id = disposisi_suratmasuk.suratmasuk_id 
			LEFT JOIN aparatur ON aparatur.jabatan_id = disposisi_suratmasuk.users_id
			WHERE LEFT(diterima, 4) = '$tahun' AND aparatur.opd_id = '$opd_id' AND status = 'Selesai'
			GROUP BY disposisi_suratmasuk.suratmasuk_id ORDER BY dsuratmasuk_id DESC
		");

		$data['disposisi'] = $query->result();

		$this->load->view('template', $data);
	}

}
