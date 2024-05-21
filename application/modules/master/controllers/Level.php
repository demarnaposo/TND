<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class Level extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if (empty($this->session->userdata('login'))) {
			$this->session->set_flashdata('access', 'Anda harus login terlebih dahulu!');
			redirect(site_url());
		}
		$this->load->model('level_model');
	}

	public function index()
	{
		$data['content'] = 'level_index';
		$data['level'] = $this->level_model->get()->result();
		
		$this->load->view('template', $data);
	}

	public function add()
	{
		$data['content'] = 'level_form';
		$this->load->view('template', $data);
	}

	public function insert()
	{
		$data = array(
			'level_id' => htmlentities($this->input->post('level_id')), 
			'level' => htmlentities($this->input->post('level'))
		);
		$insert = $this->level_model->insert($data);
		if ($insert) {
			$this->session->set_flashdata('success', 'Level Berhasil Dibuat');
			redirect(site_url('master/level'));
		}else{
			$this->session->set_flashdata('error', 'Level Gagal Dibuat');
			redirect(site_url('master/level/add'));
		}
	}

	public function edit()
	{
		$data['content'] = 'level_form';
		$where = array('level_id' => $this->uri->segment(4));

		$data['level'] = $this->level_model->edit($where)->result();
		
		$this->load->view('template', $data);
	}

	public function update()
	{
		$where = array('level_id' => htmlentities($this->input->post('level_id')));
		$data = array(
			'level_id' => htmlentities($this->input->post('level_id')), 
			'level' => htmlentities($this->input->post('level'))
		);
		$update = $this->level_model->update($data,$where);
		if ($update) {
			$this->session->set_flashdata('success', 'Level Berhasil Diedit');
			redirect(site_url('master/level'));
		}else{
			$this->session->set_flashdata('error', 'Level Gagal Diedit');
			redirect(site_url('master/level/edit/'.htmlentities($this->input->post('level_id'))));
		}
	}

	public function delete($id)
	{
		$where = array('level_id' => $this->uri->segment(4));
		$delete = $this->level_model->delete($where);
		if ($delete) {
			$this->session->set_flashdata('success', 'Level Berhasil Dihapus');
			redirect(site_url('master/level'));
		}else{
			$this->session->set_flashdata('error', 'Level Gagal Dihapus');
			redirect(site_url('master/level'));
		}
	}

}