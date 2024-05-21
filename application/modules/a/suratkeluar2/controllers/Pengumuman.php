<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengumuman extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if (empty($this->session->userdata('login'))) {
			$this->session->set_flashdata('access', 'Anda harus login terlebih dahulu!');
			redirect(site_url());
		}
		$this->load->model('pengumuman_model');
	}

	public function index()
	{
		$data['content'] = 'pengumuman/pengumuman_index';
		
		$tahun = $this->session->userdata('tahun');
		$opd_id = $this->session->userdata('opd_id');
		$jabatan_id = $this->session->userdata('jabatan_id');

		$data['pengumuman'] = $this->pengumuman_model->get_data($tahun,$opd_id,$jabatan_id)->result();
		
		$this->load->view('template', $data);
	}

	public function add()
	{
		$data['content'] = 'pengumuman/pengumuman_form';
		$data['kop'] = $this->db->get('kop_surat')->result();
		$data['kodesurat'] = $this->db->get('kode_surat')->result();
		
		$this->load->view('template', $data);
	}

	public function insert()
	{
		$getID = $this->pengumuman_model->get_id()->result();
		foreach ($getID as $key => $h) {
			$id = $h->id;
		}
		if (empty($id)) {
			$surat_id = 'PNGMN-1';
		}else{
			$urut = substr($id, 6)+1;
			$surat_id = 'PNGMN-'.$urut;
		}

		$data = array(
			'id' => $surat_id,
			'opd_id' => $this->session->userdata('opd_id'),
			'kop_id' => $this->input->post('kop_id'), 
			'kodesurat_id' => $this->input->post('kodesurat_id'), 
			'tanggal' => htmlentities($this->input->post('tanggal')), 
			'nomor' => '',
			'tentang' => htmlentities($this->input->post('tentang')),  
			'isi' => $this->input->post('isi'),
		);
		$insert = $this->pengumuman_model->insert_data('surat_pengumuman', $data);
		if ($insert) {

			$datadraft = array(
				'surat_id' => $surat_id,
				'tanggal' => htmlentities($this->input->post('tanggal')), 
				'dibuat_id' => $this->session->userdata('jabatan_id'), 
				'penandatangan_id' => '',
				'verifikasi_id' => '', 
				'nama_surat' => 'Surat Pengumuman', 
			);
			$this->pengumuman_model->insert_data('draft', $datadraft);

			$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
			redirect(site_url('suratkeluar/pengumuman'));
		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
			redirect(site_url('suratkeluar/pengumuman/add'));
		}
	}

	public function edit()
	{
		$data['content'] = 'pengumuman/pengumuman_form';
		$data['kop'] = $this->db->get('kop_surat')->result();
		$data['kodesurat'] = $this->db->get('kode_surat')->result();
		$data['pengumuman'] = $this->pengumuman_model->edit_data($this->uri->segment(4), $this->session->userdata('opd_id'))->result();
		
		$this->load->view('template', $data);
	}

	public function update()
	{
		$id = $this->input->post('id');
		$data = array(
			'opd_id' => $this->session->userdata('opd_id'),
			'kodesurat_id' => $this->input->post('kodesurat_id'), 
			'kop_id' => $this->input->post('kop_id'), 
			'tanggal' => $this->input->post('tanggal'),  
			'tentang' => $this->input->post('tentang'),
			'isi' => $this->input->post('isi'),
		);
		$where = array('id' => $id);
		$update = $this->pengumuman_model->update_data('surat_pengumuman', $data, $where);
		if ($update) {

			$datadraft = array( 
				'tanggal' => $this->input->post('tanggal'),
			);
			$wheredraft = array('surat_id' => $id);
			$this->pengumuman_model->update_data('draft', $datadraft, $wheredraft);
			
			$this->session->set_flashdata('success', 'Surat Berhasil Diedit');

			$pembuatSurat = $this->db->get_where('draft', array('surat_id' => $id))->row_array();
			
			if ($pembuatSurat['dibuat_id'] == $this->session->userdata('jabatan_id')) {
				redirect(site_url('suratkeluar/pengumuman'));
			}else{
				redirect(site_url('suratkeluar/draft'));
			}

		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Diedit');
			redirect(site_url('suratkeluar/pengumuman/edit/'.$id));
		}
	}

	public function delete()
	{
		$where = array('id' => $this->uri->segment(4));
		$delete = $this->pengumuman_model->delete_data('surat_pengumuman', $where);
		if ($delete) {
			$whereDis = array('surat_id' => $this->uri->segment(4));
			$this->pengumuman_model->delete_data('draft', $whereDis);
			$this->pengumuman_model->delete_data('verifikasi', $whereDis);
			$this->pengumuman_model->delete_data('disposisi_suratkeluar', $whereDis);
			
			$this->session->set_flashdata('success', 'Surat Berhasil Dihapus');
			redirect(site_url('suratkeluar/pengumuman'));
		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Dihapus');
			redirect(site_url('suratkeluar/pengumuman'));
		}
	}

}