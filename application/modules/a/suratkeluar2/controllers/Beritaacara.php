<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Beritaacara extends CI_Controller {

	function __construct() 
	{
		parent::__construct();
		if (empty($this->session->userdata('login'))) {
			$this->session->set_flashdata('access', 'Anda harus login terlebih dahulu!');
			redirect(site_url());
		}
		$this->load->model('beritaacara_model');
	}

	public function index()
	{
		$data['content'] = 'beritaacara/beritaacara_index';
		
		$tahun = $this->session->userdata('tahun');
		$opd_id = $this->session->userdata('opd_id');
		$jabatan_id = $this->session->userdata('jabatan_id');
		$pegawai_id = $this->session->userdata('opd_id');
		$data['pegawai'] = $this->db->query('Select * from aparatur where opd_id = '.$this->session->userdata('opd_id'))->result();
		
		$data['beritaacara'] = $this->beritaacara_model->get_data($tahun,$opd_id,$jabatan_id)->result();
		
		$this->load->view('template', $data);
	}

	public function add()
	{
		$data['content'] = 'beritaacara/beritaacara_form';
		$data['kop'] = $this->db->get('kop_surat')->result();
		$data['kodesurat'] = $this->db->get('kode_surat')->result();
		$data['pegawai'] = $this->db->query('Select * from aparatur where opd_id = '.$this->session->userdata('opd_id'))->result();	
				
		$this->load->view('template', $data);
	}

	public function insert()
	{
		$getID = $this->beritaacara_model->get_id()->result();
		foreach ($getID as $key => $h) {
			$id = $h->id;
		}
		if (empty($id)) {
			$surat_id = 'BRA-1';
		}else{
			$urut = substr($id, 4)+1;
			$surat_id = 'BRA-'.$urut;
		}
		
		$data = array(
			
			'id' => $surat_id,
			'opd_id' => $this->session->userdata('opd_id'),
			'kop_id' => $this->input->post('kop_id'),  
			'kodesurat_id' => $this->input->post('kodesurat_id'),  
			'nomor' => '',  
			'isi' => $this->input->post('isi'),   
			'tanggal' => $this->input->post('tanggal'),
			'pihakpertama_id' =>$this->input->post('pihakpertama_id'),  
			'pihakkedua_id' =>$this->input->post('pihakkedua_id'),  
		);
		$insert = $this->beritaacara_model->insert_data('surat_beritaacara', $data);
		if ($insert) {

			$datadraft = array(
				'surat_id' => $surat_id,
				'tanggal' => $this->input->post('tanggal'), 
				'dibuat_id' => $this->session->userdata('jabatan_id'), 
				'penandatangan_id' => '',
				'verifikasi_id' => '', 
				'nama_surat' => 'Surat beritaacara', 
			);
			$this->beritaacara_model->insert_data('draft', $datadraft);

			$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
			redirect(site_url('suratkeluar/beritaacara'));
		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
			redirect(site_url('suratkeluar/beritaacara/add'));
		}
	}

	public function edit()
	{
		$data['content'] = 'beritaacara/beritaacara_form';
		$data['kop'] = $this->db->get('kop_surat')->result();
		$data['kodesurat'] = $this->db->get('kode_surat')->result();
		$data['pegawai'] = $this->db->query('Select * from aparatur where opd_id = '.$this->session->userdata('opd_id'))->result();	
		$data['beritaacara'] = $this->beritaacara_model->edit_data($this->uri->segment(4), $this->session->userdata('opd_id'))->result();
		
		$this->load->view('template', $data);
	}

	public function update()
	{
		$id = $this->input->post('id');
		$data = array( 
			'kop_id' => $this->input->post('kop_id'),  
			'kodesurat_id' => $this->input->post('kodesurat_id'),  
			'nomor' => '',  
			'isi' => $this->input->post('isi'),  
			'untuk' => $this->input->post('untuk'),  
			'tanggal' => $this->input->post('tanggal'),
			'pihakpertama_id' => $this->input->post('pihakpertama_id'),
			'pihakkedua_id' => $this->input->post('pihakkedua_id'),
		);
		$where = array('id' => $id);
		$update = $this->beritaacara_model->update_data('surat_beritaacara', $data, $where);
		if ($update) {

			$datadraft = array( 
				'tanggal' => $this->input->post('tanggal'),
			);
			$wheredraft = array('surat_id' => $id);
			$this->beritaacara_model->update_data('draft', $datadraft, $wheredraft);
			
			$this->session->set_flashdata('success', 'Surat Berhasil Diedit');

			$pembuatSurat = $this->db->get_where('draft', array('surat_id' => $id))->row_array();
			
			if ($pembuatSurat['dibuat_id'] == $this->session->userdata('jabatan_id')) {
				redirect(site_url('suratkeluar/beritaacara'));
			}else{
				redirect(site_url('suratkeluar/draft'));
			}

		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Diedit');
			redirect(site_url('suratkeluar/beritaacara/edit/'.$id));
		}
	}

	public function delete()
	{
		$where = array('id' => $this->uri->segment(4));
		$delete = $this->beritaacara_model->delete_data('surat_beritaacara', $where);
		if ($delete) {
			$whereDis = array('surat_id' => $this->uri->segment(4));
			$this->beritaacara_model->delete_data('draft', $whereDis);
			$this->beritaacara_model->delete_data('verifikasi', $whereDis);
			$this->beritaacara_model->delete_data('disposisi_suratkeluar', $whereDis);
			
			$this->session->set_flashdata('success', 'Surat Berhasil Dihapus');
			redirect(site_url('suratkeluar/beritaacara'));
		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Dihapus');
			redirect(site_url('suratkeluar/beritaacara'));
		}
	}

}