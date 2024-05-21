<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class perjalanan extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if (empty($this->session->userdata('login'))) {
			$this->session->set_flashdata('access', 'Anda harus login terlebih dahulu!');
			redirect(site_url());
		}
		$this->load->model('perjalanan_model');
	}

	public function index()
	{
		$data['content'] = 'perjalanan/perjalanan_index';
		
		$tahun = $this->session->userdata('tahun');
		$opd_id = $this->session->userdata('opd_id');
		$jabatan_id = $this->session->userdata('jabatan_id');

		$data['perjalanan'] = $this->perjalanan_model->get_data($tahun,$opd_id,$jabatan_id)->result();
		
		$this->load->view('template', $data);
	}

	public function add()
	{
		$data['content'] = 'perjalanan/perjalanan_form';
        $data['kodesurat'] = $this->db->get('kode_surat')->result();
		$data['aparatur'] = $this->db->get_where('aparatur', array('opd_id' => $this->session->userdata('opd_id'),'nip !=' => '-'))->result();
		
		$this->load->view('template', $data);
	}

	public function insert()
	{
		$getID = $this->perjalanan_model->get_id()->result();
		foreach ($getID as $key => $h) {
			$id = $h->id;
		}
		if (empty($id)) {
			$surat_id = 'PJL-1';
		}else{
			$urut = substr($id, 4)+1;
			$surat_id = 'PJL-'.$urut;
		}

		$data = array(
			'id' => $surat_id,
			'opd_id' => $this->session->userdata('opd_id'),
			'kodesurat_id' => $this->input->post('kodesurat_id'), 
			'nomor' => '',
			'tanggal' => htmlentities($this->input->post('tanggal')),
			'nip_id' => $this->input->post('nip_id'),
			'tingkat_biaya' => $this->input->post('tingkat_biaya'),
			'maksud_perjalanan' => $this->input->post('maksud_perjalanan'),
			'alat_angkutan' => $this->input->post('alat_angkutan'),
			'tmpt_berangkat' => $this->input->post('tmpt_berangkat'),
			'tmpt_tujuan' => $this->input->post('tmpt_tujuan'),
			'lama_perjalanan' => $this->input->post('lama_perjalanan'),
			'tgl_berangkat' => $this->input->post('tgl_berangkat'),
			'tgl_pulang' => $this->input->post('tgl_pulang'),
			'kegiatan' => $this->input->post('kegiatan'),
			'keterangan' => $this->input->post('keterangan'),
			'akun' => $this->input->post('akun'),
		);
		$insert = $this->perjalanan_model->insert_data('surat_perjalanan', $data);
		if ($insert) {

			$datadraft = array(
				'surat_id' => $surat_id,
				'tanggal' => htmlentities($this->input->post('tanggal')), 
				'dibuat_id' => $this->session->userdata('jabatan_id'), 
				'penandatangan_id' => '',
				'verifikasi_id' => '', 
				'nama_surat' => 'Surat Perjalanan Dinas', 
			);
			$this->perjalanan_model->insert_data('draft', $datadraft);

			$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
			redirect(site_url('suratkeluar/perjalanan'));
		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
			redirect(site_url('suratkeluar/perjalanan/add'));
		}
	}

	public function edit()
	{
		$data['content'] = 'perjalanan/perjalanan_form';
		$data['kodesurat'] = $this->db->get('kode_surat')->result();
		$data['aparatur'] = $this->db->get_where('aparatur', array('opd_id' => $this->session->userdata('opd_id'),'nip !=' => '-'))->result();
		$data['perjalanan'] = $this->perjalanan_model->edit_data($this->uri->segment(4), $this->session->userdata('opd_id'))->result();
		
		$this->load->view('template', $data);
	}

	public function update()
	{
		$id = $this->input->post('id');
		$data = array(
			'kodesurat_id' => $this->input->post('kodesurat_id'), 
			'tanggal' => htmlentities($this->input->post('tanggal')),
			'nip_id' => $this->input->post('nip_id'),
			'tingkat_biaya' => $this->input->post('tingkat_biaya'),
			'maksud_perjalanan' => $this->input->post('maksud_perjalanan'),
			'alat_angkutan' => $this->input->post('alat_angkutan'),
			'tmpt_berangkat' => $this->input->post('tmpt_berangkat'),
			'tmpt_tujuan' => $this->input->post('tmpt_tujuan'),
			'lama_perjalanan' => $this->input->post('lama_perjalanan'),
			'tgl_berangkat' => $this->input->post('tgl_berangkat'),
			'tgl_pulang' => $this->input->post('tgl_pulang'),
			'kegiatan' => $this->input->post('kegiatan'),
			'keterangan' => $this->input->post('keterangan'),
			'akun' => $this->input->post('akun'),
		);
		$where = array('id' => $id);
		$update = $this->perjalanan_model->update_data('surat_perjalanan', $data, $where);
		if ($update) {

			$datadraft = array( 
				'tanggal' => $this->input->post('tanggal'),
			);
			$wheredraft = array('surat_id' => $id);
			$this->perjalanan_model->update_data('draft', $datadraft, $wheredraft);
			
			$this->session->set_flashdata('success', 'Surat Berhasil Diedit');
			
			$pembuatSurat = $this->db->get_where('draft', array('surat_id' => $id))->row_array();

			if ($pembuatSurat['dibuat_id'] == $this->session->userdata('jabatan_id')) {
				redirect(site_url('suratkeluar/perjalanan'));
			}else{
				redirect(site_url('suratkeluar/draft'));
			}

		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Diedit');
			redirect(site_url('suratkeluar/perjalanan/edit/'.$id));
		}
	}

	public function delete()
	{
		$where = array('id' => $this->uri->segment(4));
		$delete = $this->perjalanan_model->delete_data('surat_perjalanan', $where);
		if ($delete) {
			$whereDis = array('surat_id' => $this->uri->segment(4));
			$this->perjalanan_model->delete_data('draft', $whereDis);
			$this->perjalanan_model->delete_data('verifikasi', $whereDis);
			
			$this->session->set_flashdata('success', 'Surat Berhasil Dihapus');
			redirect(site_url('suratkeluar/perjalanan'));
		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Dihapus');
			redirect(site_url('suratkeluar/perjalanan'));
		}
	}

}