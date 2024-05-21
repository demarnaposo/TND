<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Perintah extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if (empty($this->session->userdata('login'))) {
			$this->session->set_flashdata('access', 'Anda harus login terlebih dahulu!');
			redirect(site_url());
		}
		$this->load->model('perintah_model');
	}

	public function index()
	{
		$data['content'] = 'perintah/perintah_index';
		
		$tahun = $this->session->userdata('tahun');
		$opd_id = $this->session->userdata('opd_id');
		$jabatan_id = $this->session->userdata('jabatan_id');

		$data['perintah'] = $this->perintah_model->get_data($tahun,$opd_id,$jabatan_id)->result();
		
		$this->load->view('template', $data);
	}

	public function add()
	{
		$data['content'] = 'perintah/perintah_form';
		$data['kop'] = $this->db->get('kop_surat')->result();
		$data['kodesurat'] = $this->db->get('kode_surat')->result();
		
		$this->load->view('template', $data);
	}

	public function insert()
	{
		$getID = $this->perintah_model->get_id()->result();
		foreach ($getID as $key => $h) {
			$id = $h->id;
		}
		if (empty($id)) {
			$surat_id = 'SP-1';
		}else{
			$urut = substr($id, 3)+1;
			$surat_id = 'SP-'.$urut;
		}

		$data = array(
			'id' => $surat_id,
			'opd_id' => $this->session->userdata('opd_id'),
			'kop_id' => $this->input->post('kop_id'),  
			'kodesurat_id' => $this->input->post('kodesurat_id'),  
			'nomor' => '',  
			'nama_pejabat' => $this->input->post('nama_pejabat'),  
			'jabatan' => $this->input->post('jabatan'),  
			'isi' => $this->input->post('isi'),  
			'tanggal' => $this->input->post('tanggal'),
			'tembusan' => $this->input->post('tembusan'),
		);
		$insert = $this->perintah_model->insert_data('surat_perintah', $data);
		if ($insert) {

			$datadraft = array(
				'surat_id' => $surat_id,
				'tanggal' => $this->input->post('tanggal'), 
				'dibuat_id' => $this->session->userdata('jabatan_id'), 
				'penandatangan_id' => '',
				'verifikasi_id' => '', 
				'nama_surat' => 'Surat Perintah', 
			);
			$this->perintah_model->insert_data('draft', $datadraft);

			$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
			redirect(site_url('suratkeluar/perintah'));
		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
			redirect(site_url('suratkeluar/perintah/add'));
		}
	}

	public function edit()
	{
		$data['content'] = 'perintah/perintah_form';
		$data['kop'] = $this->db->get('kop_surat')->result();
		$data['kodesurat'] = $this->db->get('kode_surat')->result();
		$data['perintah'] = $this->perintah_model->edit_data($this->uri->segment(4), $this->session->userdata('opd_id'))->result();
		
		$this->load->view('template', $data);
	}

	public function update()
	{
		$id = $this->input->post('id');
		$data = array( 
			'kop_id' => $this->input->post('kop_id'),  
			'kodesurat_id' => $this->input->post('kodesurat_id'),  
			'nama_pejabat' => $this->input->post('nama_pejabat'),  
			'jabatan' => $this->input->post('jabatan'),  
			'isi' => $this->input->post('isi'),
			'tanggal' => $this->input->post('tanggal'),
			'tembusan' => $this->input->post('tembusan'),
		);
		$where = array('id' => $id);
		$update = $this->perintah_model->update_data('surat_perintah', $data, $where);
		if ($update) {

			$datadraft = array( 
				'tanggal' => $this->input->post('tanggal'),
			);
			$wheredraft = array('surat_id' => $id);
			$this->perintah_model->update_data('draft', $datadraft, $wheredraft);
			
			$this->session->set_flashdata('success', 'Surat Berhasil Diedit');

			$pembuatSurat = $this->db->get_where('draft', array('surat_id' => $id))->row_array();
			
			if ($pembuatSurat['dibuat_id'] == $this->session->userdata('jabatan_id')) {
				redirect(site_url('suratkeluar/perintah'));
			}else{
				redirect(site_url('suratkeluar/draft'));
			}

		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Diedit');
			redirect(site_url('suratkeluar/perintah/edit/'.$id));
		}
	}

	public function delete()
	{
		$where = array('id' => $this->uri->segment(4));
		$delete = $this->perintah_model->delete_data('surat_perintah', $where);
		if ($delete) {
			$whereDis = array('surat_id' => $this->uri->segment(4));
			$this->perintah_model->delete_data('draft', $whereDis);
			$this->perintah_model->delete_data('verifikasi', $whereDis);
			$this->perintah_model->delete_data('disposisi_suratkeluar', $whereDis);
			
			$this->session->set_flashdata('success', 'Surat Berhasil Dihapus');
			redirect(site_url('suratkeluar/perintah'));
		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Dihapus');
			redirect(site_url('suratkeluar/perintah'));
		}
	}

}