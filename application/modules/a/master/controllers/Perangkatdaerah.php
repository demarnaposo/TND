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
		$data['perangkatdaerah'] = $this->perangkatdaerah_model->get()->result();

		$this->load->view('template', $data);
	}

	public function add()
	{
		$data['content'] = 'perangkatdaerah_form';
		$this->load->view('template', $data);
	}

	public function insert()
	{
		$data = array(
			'nama_pd' => htmlentities($this->input->post('nama_pd')), 
			'kode_pd' => htmlentities($this->input->post('kode_pd')), 
			'alamat' => htmlentities($this->input->post('alamat')), 
			'telp' => htmlentities($this->input->post('telp')), 
			'email' => htmlentities($this->input->post('email')), 
			'alamat_website' => htmlentities($this->input->post('alamat_website'))
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

		$this->load->view('template', $data);
	}

	public function update()
	{
		$where = array('opd_id' => htmlentities($this->input->post('opd_id')));

		$data = array(
			'nama_pd' => htmlentities($this->input->post('nama_pd')), 
			'kode_pd' => htmlentities($this->input->post('kode_pd')), 
			'alamat' => htmlentities($this->input->post('alamat')), 
			'telp' => htmlentities($this->input->post('telp')), 
			'email' => htmlentities($this->input->post('email')), 
			'alamat_website' => htmlentities($this->input->post('alamat_website'))
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