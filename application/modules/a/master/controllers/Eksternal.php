<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class Eksternal extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if (empty($this->session->userdata('login'))) {
			$this->session->set_flashdata('access', 'Anda harus login terlebih dahulu!');
			redirect(site_url());
		}
		$this->load->model('eksternal_model');
	}

	public function index()
	{
		$data['content'] = 'eksternal_index';

		$opd_id = $this->session->userdata('opd_id');
		$data['eksternal'] = $this->eksternal_model->get($opd_id)->result();
		
		$this->load->view('template', $data);
	}

	public function add()
	{
		$data['content'] = 'eksternal_form';
		$this->load->view('template', $data);
	}

	public function insert()
	{
		$getEksID = $this->edaran_model->get_id('eksternal_keluar')->result();
		foreach ($getEksID as $key => $ek) {
			$idEKs = $ek->id;
		}
		if (empty($idEKs)) {
			$eksternalkeluar_id = 'EKS-1';
		}else{
			$urut = substr($id, 4)+1;
			$eksternalkeluar_id = 'EKS-'.$urut;
		}

		$data = array(
			'id' => $eksternalkeluar_id, 
			'opd_id' => $this->session->userdata('opd_id'), 
			'nama' => htmlentities($this->input->post('nama')), 
			'email' => htmlentities($this->input->post('email'))
		);
		$insert = $this->eksternal_model->insert($data);
		if ($insert) {
			$this->session->set_flashdata('success', 'Perangkat Eksternal Berhasil Dibuat');
			redirect(site_url('master/eksternal'));
		}else{
			$this->session->set_flashdata('error', 'Perangkat Eksternal Gagal Dibuat');
			redirect(site_url('master/eksternal/add'));
		}
	}

	public function edit()
	{
		$data['content'] = 'eksternal_form';
		$where = array('id' => $this->uri->segment(4));
		$data['eksternal'] = $this->eksternal_model->edit($where)->result();
		
		$this->load->view('template', $data);
	}

	public function update()
	{
		$where = array('id' => htmlentities($this->input->post('id')));
		$data = array(
			'opd_id' => $this->session->userdata('opd_id'), 
			'nama' => htmlentities($this->input->post('nama')), 
			'email' => htmlentities($this->input->post('email'))
		);
		$update = $this->eksternal_model->update($data,$where);
		if ($update) {
			$this->session->set_flashdata('success', 'Perangkat Eksternal Berhasil Diedit');
			redirect(site_url('master/eksternal'));
		}else{
			$this->session->set_flashdata('error', 'Perangkat Eksternal Gagal Diedit');
			redirect(site_url('master/eksternal/edit/'.htmlentities($this->input->post('id'))));
		}
	}

	public function delete($id)
	{
		$where = array('id' => $this->uri->segment(4));
		$delete = $this->eksternal_model->delete($where);
		if ($delete) {
			$this->session->set_flashdata('success', 'Perangkat Eksternal Berhasil Dihapus');
			redirect(site_url('master/eksternal'));
		}else{
			$this->session->set_flashdata('error', 'Perangkat Eksternal Gagal Dihapus');
			redirect(site_url('master/eksternal'));
		}
	}

}