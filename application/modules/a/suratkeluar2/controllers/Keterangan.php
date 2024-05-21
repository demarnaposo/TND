<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Keterangan extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if (empty($this->session->userdata('login'))) {
			$this->session->set_flashdata('access', 'Anda harus login terlebih dahulu!');
			redirect(site_url());
		}
		$this->load->model('keterangan_model');
	}

	public function index()
	{
		$data['content'] = 'keterangan/keterangan_index';
		
		$tahun = $this->session->userdata('tahun');
		$opd_id = $this->session->userdata('opd_id');
		$jabatan_id = $this->session->userdata('jabatan_id');
		$pegawai_id = $this->session->userdata('opd_id');
		$data['pegawai'] = $this->db->query('Select * from aparatur where opd_id = '.$this->session->userdata('opd_id'))->result();	
		
		$data['keterangan'] = $this->keterangan_model->get_data($tahun,$opd_id,$jabatan_id,$pegawai_id)->result();
		
		$this->load->view('template', $data);
	}

	public function add()
	{
		$data['content'] = 'keterangan/keterangan_form';
		$data['kop'] = $this->db->get('kop_surat')->result();
		$data['kodesurat'] = $this->db->get('kode_surat')->result();
		$data['pegawai'] = $this->db->query('Select * from aparatur where opd_id = '.$this->session->userdata('opd_id'))->result();	
		
		
		$this->load->view('template', $data);
	}

	public function insert()
	{
		$getID = $this->keterangan_model->get_id()->result();
		foreach ($getID as $key => $h) {
			$id = $h->id;
		}
		if (empty($id)) {
			$surat_id = 'SK-1';
		}else{
			$urut = substr($id, 3)+1;
			$surat_id = 'SK-'.$urut;
		}

		$data = array(
			'id' => $surat_id,
			'kop_id' => $this->input->post('kop_id'),  
			'kodesurat_id' => $this->input->post('kodesurat_id'),  
			'opd_id' => $this->session->userdata('opd_id'),
			'nomor' => '',  
			'pegawai_id' => $this->input->post('pegawai_id'), 			
			'maksud' => $this->input->post('maksud'),  
			'tanggal' => $this->input->post('tanggal'),
		);
		$insert = $this->keterangan_model->insert_data('surat_keterangan', $data);
		if ($insert) {

			$datadraft = array(
				'surat_id' => $surat_id,
				'tanggal' => $this->input->post('tanggal'), 
				'dibuat_id' => $this->session->userdata('jabatan_id'), 
				'penandatangan_id' => '',
				'verifikasi_id' => '', 
				'nama_surat' => 'Surat Keterangan', 
			);
			$this->keterangan_model->insert_data('draft', $datadraft);

			$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
			redirect(site_url('suratkeluar/keterangan'));
		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
			redirect(site_url('suratkeluar/keterangan/add'));
		}
	}

	public function edit()
	{
		$data['content'] = 'keterangan/keterangan_form';
		$data['kop'] = $this->db->get('kop_surat')->result();
		$data['kodesurat'] = $this->db->get('kode_surat')->result();
		$data['pegawai'] = $this->db->query('Select * from aparatur where opd_id = '.$this->session->userdata('opd_id'))->result();	
		
		$data['keterangan'] = $this->keterangan_model->edit_data($this->uri->segment(4), $this->session->userdata('opd_id'))->result();
		
		$this->load->view('template', $data);
	}

	public function update()
	{
		$id = $this->input->post('id');
		$data = array( 
			'kop_id' => $this->input->post('kop_id'),  
			'kodesurat_id' => $this->input->post('kodesurat_id'),  
			'pegawai_id' => $this->input->post('pegawai_id'), 			
			'maksud' => $this->input->post('maksud'), 			
			'tanggal' => $this->input->post('tanggal'),
		);
		$where = array('id' => $id);
		$update = $this->keterangan_model->update_data('surat_keterangan', $data, $where);
		if ($update) {

			$datadraft = array( 
				'tanggal' => $this->input->post('tanggal'),
			);
			$wheredraft = array('surat_id' => $id);
			$this->keterangan_model->update_data('draft', $datadraft, $wheredraft);
			
			$this->session->set_flashdata('success', 'Surat Berhasil Diedit');

			$pembuatSurat = $this->db->get_where('draft', array('surat_id' => $id))->row_array();
			
			if ($pembuatSurat['dibuat_id'] == $this->session->userdata('jabatan_id')) {
				redirect(site_url('suratkeluar/keterangan'));
			}else{
				redirect(site_url('suratkeluar/draft'));
			}

		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Diedit');
			redirect(site_url('suratkeluar/keterangan/edit/'.$id));
		}
	}

	public function delete()
	{
		$where = array('id' => $this->uri->segment(4));
		$delete = $this->keterangan_model->delete_data('surat_keterangan', $where);
		if ($delete) {
			$whereDis = array('surat_id' => $this->uri->segment(4));
			$this->keterangan_model->delete_data('draft', $whereDis);
			$this->keterangan_model->delete_data('verifikasi', $whereDis);
			$this->keterangan_model->delete_data('disposisi_suratkeluar', $whereDis);
			
			$this->session->set_flashdata('success', 'Surat Berhasil Dihapus');
			redirect(site_url('suratkeluar/keterangan'));
		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Dihapus');
			redirect(site_url('suratkeluar/keterangan'));
		}
	}

}