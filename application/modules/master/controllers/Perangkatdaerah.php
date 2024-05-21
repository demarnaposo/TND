<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class Perangkatdaerah extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if (!$this->session->userdata('login')) {
			$this->session->set_flashdata('access', 'Anda harus login terlebih dahulu!');
		}
		$this->load->model('perangkatdaerah_model');
	}

	public function index()
	{
		$data['content'] = 'perangkatdaerah_index';
 		
		if($this->session->userdata('level') == 2) 
		{
		$opd_id = $this->session->userdata('opd_id');
		$data['perangkatdaerah'] = $this->perangkatdaerah_model->get_adminskpd($opd_id)->result();

		}
		else 
		{
		$data['perangkatdaerah'] = $this->perangkatdaerah_model->get()->result();

		}
		$this->load->view('template', $data);
	}

	public function add()
	{
		$data['content'] = 'perangkatdaerah_form';
		// Update @Mpik Egov 8 Agustus 2022
		$data['opd'] = $this->db->query("SELECT * FROM opd LEFT JOIN urutan_opd ON urutan_opd.urutan_id=opd.urutan_id WHERE opd.statusopd='Aktif' AND opd.urutan_id !=0 ORDER BY urutan_opd.urutan_id ASC")->result();
		$data['urutanopd'] = $this->db->query("SELECT * FROM urutan_opd")->result();
		// END 8 Agustus 2022
		$this->load->view('template', $data);
	}

	public function insert()
	{
		$data = array(
			'nama_pd' => htmlentities($this->input->post('nama_pd')), 
			'kode_pd' => htmlentities($this->input->post('kode_pd')), 
			'nomenklatur_pd' => htmlentities($this->input->post('nomenklatur_pd')), 
			'alamat' => htmlentities($this->input->post('alamat')), 
			'telp' => htmlentities($this->input->post('telp')), 
			'faksimile' => htmlentities($this->input->post('faksimile')), 
			'email' => htmlentities($this->input->post('email')), 
			'opd_induk' => htmlentities($this->input->post('opd_induk')), 
			'urutan_id' => htmlentities($this->input->post('urutan_id')), 
			'alamat_website' => htmlentities($this->input->post('alamat_website')),
			'statusopd' => htmlentities($this->input->post('statusopd'))
		);
		$insert = $this->perangkatdaerah_model->insert($data);
		if ($insert) {
			$this->session->set_flashdata('success', 'Perangkat Daerah Berhasil Dibuat');
			redirect('master/perangkatdaerah');
		}else{
			$this->session->set_flashdata('error', 'Perangkat Daerah Gagal Dibuat');
			redirect('master/perangkatdaerah');
		}
	}

	public function edit()
	{
		$data['content'] = 'perangkatdaerah_form';
		$where = array('opd_id' => $this->uri->segment(4));
		$data['perangkatdaerah'] = $this->perangkatdaerah_model->edit($where)->result();
		// Update @Mpik Egov 8 Agustus 2022
		$data['opd'] = $this->perangkatdaerah_model->get()->result();
		$data['urutanopd'] = $this->db->query("SELECT * FROM urutan_opd")->result();
		// END 8 Agustus 2022

		$this->load->view('template', $data);
	}

	public function update()
	{
		$where = array('opd_id' => htmlentities($this->input->post('opd_id')));

		$data = array(
			'nama_pd' => htmlentities($this->input->post('nama_pd')), 
			'kode_pd' => htmlentities($this->input->post('kode_pd')), 
			'nomenklatur_pd' => htmlentities($this->input->post('nomenklatur_pd')), 
			'alamat' => htmlentities($this->input->post('alamat')), 
			'telp' => htmlentities($this->input->post('telp')), 
			'faksimile' => htmlentities($this->input->post('faksimile')), 
			'email' => htmlentities($this->input->post('email')), 
			'opd_induk' => htmlentities($this->input->post('opd_induk')), 
			'urutan_id' => htmlentities($this->input->post('urutan_id')), 
			'alamat_website' => htmlentities($this->input->post('alamat_website')),
			'statusopd' => htmlentities($this->input->post('statusopd'))
		);
		$update = $this->perangkatdaerah_model->update($data,$where);
		if ($update) {
			$this->session->set_flashdata('success', 'Perangkat Daerah Berhasil Diedit');
			redirect('master/perangkatdaerah');
		}else{
			$this->session->set_flashdata('error', 'Perangkat Daerah Gagal Diedit');
			redirect('master/perangkatdaerah');
		}
	}

	public function delete()
	{
		$where = array('opd_id' => $this->uri->segment(4));
		$delete = $this->perangkatdaerah_model->delete($where);
		if ($delete) {
			$this->session->set_flashdata('success', 'Perangkat Daerah Berhasil Dihapus');
			redirect(site_url('master/perangkatdaerah'));
		}else{
			$this->session->set_flashdata('error', 'Perangkat Daerah Gagal Dihapus');
			redirect(site_url('master/perangkatdaerah'));
		}
	}
}