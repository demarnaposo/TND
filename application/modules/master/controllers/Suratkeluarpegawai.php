<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class Suratkeluarpegawai extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if (empty($this->session->userdata('login'))) {
			$this->session->set_flashdata('access', 'Anda harus login terlebih dahulu!');
			redirect(site_url());
		}
		$this->load->model('Suratkeluarpegawai_model');
	}

	public function index()
	{
		$data['content'] = 'jabatan_index';

		if ($this->session->userdata('level') == 2) {
			$opd_id = $this->session->userdata('opd_id');
			$data['jabatan'] = $this->jabatan_model->get_adminskpd($opd_id)->result();
		}else{
			$data['jabatan'] = $this->jabatan_model->get()->result();
		}
		
		$this->load->view('template', $data);
	}

	public function add()
	{
		$data['content'] = 'jabatan_form';

		if ($this->session->userdata('level') == 2) {
			$opd_id = $this->session->userdata('opd_id');
			$data['opd'] = $this->jabatan_model->get_opd_adminskpd($opd_id)->result();
			$data['jabatan'] = $this->jabatan_model->get_adminskpd($opd_id)->result();
		}else{
			$data['opd'] = $this->jabatan_model->get_opd()->result();
			$data['jabatan'] = $this->jabatan_model->get()->result();
		}
		
		$this->load->view('template', $data);
	}

	public function insert()
	{
		$data = array(
			'jabatan_id' => htmlentities($this->input->post('jabatan_id')), 
			'opd_id' => htmlentities($this->input->post('opd_id')), 
			'nama_jabatan' => htmlentities($this->input->post('nama_jabatan')), 
			'atasan_id' => htmlentities($this->input->post('atasan_id'))
		);
		$insert = $this->jabatan_model->insert($data);
		if ($insert) {
			$this->session->set_flashdata('success', 'Jabatan Berhasil Dibuat');
			redirect(site_url('master/jabatan'));
		}else{
			$this->session->set_flashdata('error', 'Jabatan Gagal Dibuat');
			redirect(site_url('master/jabatan/add'));
		}
	}

	public function edit()
	{
		$data['content'] = 'jabatan_form';

		if ($this->session->userdata('level') == 2) {
			$opd_id = $this->session->userdata('opd_id');
			$data['opd'] = $this->jabatan_model->get_opd_adminskpd($opd_id)->result();
			$data['jabatanoption'] = $this->jabatan_model->get_adminskpd($opd_id)->result();
		}else{
			$data['opd'] = $this->jabatan_model->get_opd()->result();
			$data['jabatanoption'] = $this->jabatan_model->get()->result();
		}

		$where = array('jabatan_id' => $this->uri->segment(4));

		$data['jabatan'] = $this->jabatan_model->edit($where)->result();
		
		$this->load->view('template', $data);
	}

	public function update()
	{
		$where = array('jabatan_id' => htmlentities($this->input->post('jabatan_id')));

		$data = array(
			'jabatan_id' => htmlentities($this->input->post('jabatan_id')), 
			'opd_id' => htmlentities($this->input->post('opd_id')), 
			'nama_jabatan' => htmlentities($this->input->post('nama_jabatan')), 
			'atasan_id' => htmlentities($this->input->post('atasan_id'))
		);
		$update = $this->jabatan_model->update($data,$where);
		if ($update) {
			$this->session->set_flashdata('success', 'Jabatan Berhasil Diedit');
			redirect(site_url('master/jabatan'));
		}else{
			$this->session->set_flashdata('error', 'Jabatan Gagal Diedit');
			redirect(site_url('master/jabatan/edit/'.htmlentities($this->input->post('jabatan_id'))));
		}
	}

	public function delete($id)
	{
		$where = array('jabatan_id' => $this->uri->segment(4));
		$delete = $this->jabatan_model->delete($where);
		if ($delete) {
			$this->session->set_flashdata('success', 'Jabatan Berhasil Dihapus');
			redirect(site_url('master/jabatan'));
		}else{
			$this->session->set_flashdata('error', 'Jabatan Gagal Dihapus');
			redirect(site_url('master/jabatan'));
		}
	}

}