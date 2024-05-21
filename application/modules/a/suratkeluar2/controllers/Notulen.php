<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notulen extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if (empty($this->session->userdata('login'))) {
			$this->session->set_flashdata('access', 'Anda harus login terlebih dahulu!');
			redirect(site_url());
		}
		$this->load->model('notulen_model');
	}

	public function index()
	{
		$data['content'] = 'notulen/notulen_index';
		
		$tahun = $this->session->userdata('tahun');
		$opd_id = $this->session->userdata('opd_id');
		$jabatan_id = $this->session->userdata('jabatan_id');

		$data['notulen'] = $this->notulen_model->get_data($tahun,$opd_id,$jabatan_id)->result();
		
		$this->load->view('template', $data);
	}

	public function add()
	{
		$data['content'] = 'notulen/notulen_form';
		$data['kodesurat'] = $this->db->get('kode_surat')->result();
		
		$this->load->view('template', $data);
	}

	public function insert()
	{
		$getID = $this->notulen_model->get_id()->result();
		foreach ($getID as $key => $h) {
			$id = $h->id;
		}
		if (empty($id)) {
			$surat_id = 'NTL-1';
		}else{
			$urut = substr($id, 4)+1;
			$surat_id = 'NTL-'.$urut;
		}

		$data = array(
			'id' => $surat_id,
			'opd_id' => $this->session->userdata('opd_id'),
			'kodesurat_id' => $this->input->post('kodesurat_id'), 
			'tanggal' => htmlentities($this->input->post('tanggal')),
			'isi' => $this->input->post('isi'),
		);
		$insert = $this->notulen_model->insert_data('surat_notulen', $data);
		if ($insert) {

			$datadraft = array(
				'surat_id' => $surat_id,
				'tanggal' => htmlentities($this->input->post('tanggal')), 
				'dibuat_id' => $this->session->userdata('jabatan_id'), 
				'penandatangan_id' => '',
				'verifikasi_id' => '', 
				'nama_surat' => 'Surat Notulen', 
			);
			$this->notulen_model->insert_data('draft', $datadraft);

			$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
			redirect(site_url('suratkeluar/notulen'));
		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
			redirect(site_url('suratkeluar/notulen/add'));
		}
	}

	public function edit()
	{
		$data['content'] = 'notulen/notulen_form';
		$data['kodesurat'] = $this->db->get('kode_surat')->result();
		$data['notulen'] = $this->notulen_model->edit_data($this->uri->segment(4), $this->session->userdata('opd_id'))->result();
		
		$this->load->view('template', $data);
	}

	public function update()
	{
		$id = $this->input->post('id');
		$data = array(
			'kodesurat_id' => $this->input->post('kodesurat_id'), 
			'tanggal' => $this->input->post('tanggal'), 
			'isi' => $this->input->post('isi'),
		);
		$where = array('id' => $id);
		$update = $this->notulen_model->update_data('surat_notulen', $data, $where);
		if ($update) {

			$datadraft = array( 
				'tanggal' => $this->input->post('tanggal'),
			);
			$wheredraft = array('surat_id' => $id);
			$this->notulen_model->update_data('draft', $datadraft, $wheredraft);
			
			$this->session->set_flashdata('success', 'Surat Berhasil Diedit');

			$pembuatSurat = $this->db->get_where('draft', array('surat_id' => $id))->row_array();

			if ($pembuatSurat['dibuat_id'] == $this->session->userdata('jabatan_id')) {
				redirect(site_url('suratkeluar/notulen'));
			}else{
				redirect(site_url('suratkeluar/draft'));
			}

		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Diedit');
			redirect(site_url('suratkeluar/notulen/edit/'.$id));
		}
	}

	public function delete()
	{
		$where = array('id' => $this->uri->segment(4));
		$delete = $this->notulen_model->delete_data('surat_notulen', $where);
		if ($delete) {
			$whereDis = array('surat_id' => $this->uri->segment(4));
			$this->notulen_model->delete_data('draft', $whereDis);
			$this->notulen_model->delete_data('verifikasi', $whereDis);
			
			$this->session->set_flashdata('success', 'Surat Berhasil Dihapus');
			redirect(site_url('suratkeluar/notulen'));
		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Dihapus');
			redirect(site_url('suratkeluar/notulen'));
		}
	}

}