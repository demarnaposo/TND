<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class Kodesurat extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if (empty($this->session->userdata('login'))) {
			$this->session->set_flashdata('access', 'Anda harus login terlebih dahulu!');
			redirect(site_url());
		}
		$this->load->model('kodesurat_model');
	}

	public function index()
	{
		$data['content'] = 'kodesurat_index';
		$data['kodesurat'] = $this->kodesurat_model->get()->result();
		
		$this->load->view('template', $data);
	}

	public function add()
	{
		$data['content'] = 'kodesurat_form';
		$this->load->view('template', $data);
	}

	public function insert()
	{
		$data = array(
			'kodesurat_id' => htmlentities($this->input->post('kodesurat_id')), 
			'kode' => htmlentities($this->input->post('kode')), 
			'tentang' => htmlentities($this->input->post('tentang'))
		);
		$insert = $this->kodesurat_model->insert($data);
		if ($insert) {
			$this->session->set_flashdata('success', 'Kode Surat Berhasil Dibuat');
			redirect(site_url('master/kodesurat'));
		}else{
			$this->session->set_flashdata('error', 'Kode Surat Gagal Dibuat');
			redirect(site_url('master/kodesurat/add'));
		}
	}

	public function edit()
	{
		$data['content'] = 'kodesurat_form';
		$where = array('kodesurat_id' => $this->uri->segment(4));
		$data['kodesurat'] = $this->kodesurat_model->edit($where)->result();
		
		$this->load->view('template', $data);
	}

	public function update()
	{
		$where = array('kodesurat_id' => htmlentities($this->input->post('kodesurat_id')));
		$data = array(
			'kodesurat_id' => htmlentities($this->input->post('kodesurat_id')), 
			'kode' => htmlentities($this->input->post('kode')), 
			'tentang' => htmlentities($this->input->post('tentang'))
		);
		$update = $this->kodesurat_model->update($data,$where);
		if ($update) {
			$this->session->set_flashdata('success', 'Kode Surat Berhasil Diedit');
			redirect(site_url('master/kodesurat'));
		}else{
			$this->session->set_flashdata('error', 'Kode Surat Gagal Diedit');
			redirect(site_url('master/kodesurat/edit/'.htmlentities($this->input->post('kodesurat_id'))));
		}
	}

	public function delete($id)
	{
		$where = array('kodesurat_id' => $this->uri->segment(4));
		$delete = $this->kodesurat_model->delete($where);
		if ($delete) {
			$this->session->set_flashdata('success', 'Kode Surat Berhasil Dihapus');
			redirect(site_url('master/kodesurat'));
		}else{
			$this->session->set_flashdata('error', 'Kode Surat Gagal Dihapus');
			redirect(site_url('master/kodesurat'));
		}
	}

}