<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class PermissionUser extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if (empty($this->session->userdata('login'))) {
			$this->session->set_flashdata('access', 'Anda harus login terlebih dahulu!');
			redirect(site_url());
		}
		$this->load->model('permissionuser_model');
	}

	public function index()
	{
		$data['content'] = 'permissionuser_index';
		$data['permissionuser'] = $this->permissionuser_model->get()->result();
		
		$this->load->view('template', $data);
	}

	public function add()
	{
		$data['content'] = 'permissionuser_form';
		$data['users'] = $this->db->query("SELECT * FROM users WHERE level_id=2")->result();
		$this->load->view('template', $data);
	}

	public function insert()
	{
		$data = array(
			'users_id' => $this->input->post('users_id'), 
			'status' => $this->input->post('status')
		);
		$insert = $this->permissionuser_model->insert($data);
		if ($insert) {
			$this->session->set_flashdata('success', 'Permission Berhasil Dibuat');
			redirect(site_url('master/PermissionUser'));
		}else{
			$this->session->set_flashdata('error', 'Permission Gagal Dibuat');
			redirect(site_url('master/PermissionUser/add'));
		}
	}

	public function edit()
	{
		$data['content'] = 'permissionuser_form';
		$where = array('id' => $this->uri->segment(4));

		$data['permissionuser'] = $this->permissionuser_model->edit($where)->result();
		$data['users'] = $this->db->query("SELECT * FROM users WHERE level_id=2")->result();
		
		$this->load->view('template', $data);
	}

	public function update()
	{
		$where = array('id' =>$this->input->post('id'));
		$data = array(
			'users_id' => $this->input->post('users_id'), 
			'status' => $this->input->post('status')
		);
		$update = $this->permissionuser_model->update($data,$where);
		if ($update) {
			$this->session->set_flashdata('success', 'Level Berhasil Diedit');
			redirect(site_url('master/PermissionUser'));
		}else{
			$this->session->set_flashdata('error', 'Permission Gagal Diedit');
			redirect(site_url('master/PermissionUser/edit/'.$this->input->post('id')));
		}
	}

	public function delete($id)
	{
		$where = array('id' => $this->uri->segment(4));
		$delete = $this->permissionuser_model->delete($where);
		if ($delete) {
			$this->session->set_flashdata('success', 'Permission Berhasil Dihapus');
			redirect(site_url('master/PermissionUser'));
		}else{
			$this->session->set_flashdata('error', 'Permission Gagal Dihapus');
			redirect(site_url('master/PermissionUser'));
		}
	}

}