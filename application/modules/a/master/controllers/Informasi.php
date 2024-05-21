<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class Informasi extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if (empty($this->session->userdata('login'))) {
			$this->session->set_flashdata('access', 'Anda harus login terlebih dahulu!');
			redirect(site_url());
		}
		$this->load->model('informasi_model');
	}

	public function index()
	{
		$data['content'] = 'informasi_index';
		$data['informasi'] = $this->informasi_model->get()->result();
		
		$this->load->view('template', $data);
	}

	public function add()
	{
		$data['content'] = 'informasi_form';	
		$this->load->view('template', $data);
	}

	public function insert()
	{
		$file = $_FILES['filename']['name'];

		if (empty($this->input->post('status'))) {
			$status = "Tidak Publish";
		}else{
			$status = "Publish";
		}

		if (empty($file)) {
			$data = array( 
				'deskripsi' => $this->input->post('deskripsi'), 
				'status' => $status,
				'file' => ''
			);
		}else{
			$ambext = explode(".",$file);
			$ekstensi = end($ambext);
			$nama_baru = date('YmdHis');
			$nama_file = $nama_baru.".".$ekstensi;	
			$config['upload_path'] = './assets/fileinformasi/';
			$config['allowed_types'] = 'pdf';
			$config['file_name'] = $nama_file;
			$this->upload->initialize($config);
			if(!$this->upload->do_upload('filename')){
				$this->session->set_flashdata('error','Upload File Gagal');
				redirect(site_url('master/informasi/add'));
			}else{
				$data = array( 
					'deskripsi' => $this->input->post('deskripsi'), 
					'status' => $status,
					'file' => $nama_file
				);
			}
		}

		$insert = $this->informasi_model->insert($data);
		if ($insert) {
			$this->session->set_flashdata('success', 'Informasi Berhasil Dibuat');
			redirect(site_url('master/informasi'));
		}else{
			$this->session->set_flashdata('error', 'Informasi Gagal Dibuat');
			redirect(site_url('master/informasi/add'));
		}
	}

	public function edit()
	{
		$data['content'] = 'informasi_form';
		$where = array('id' => $this->uri->segment(4));

		$data['informasi'] = $this->informasi_model->edit($where)->result();
		
		$this->load->view('template', $data);
	}

	public function update()
	{
		$file = $_FILES['filename']['name'];

		if (empty($this->input->post('status'))) {
			$status = "Tidak Publish";
		}else{
			$status = "Publish";
		}

		$where = array('id' => htmlentities($this->input->post('id')));
		
		if (empty($file)) {
			
			$getQuery = $this->informasi_model->edit($where)->result();
			foreach ($getQuery as $key => $h) {
				$fileLama = $h->file;
			}

			$data = array( 
				'deskripsi' => $this->input->post('deskripsi'), 
				'status' => $status,
				'file' => $fileLama
			);
		}else{
			$ambext = explode(".",$file);
			$ekstensi = end($ambext);
			$nama_baru = date('YmdHis');
			$nama_file = $nama_baru.".".$ekstensi;	
			$config['upload_path'] = './assets/fileinformasi/';
			$config['allowed_types'] = 'pdf';
			$config['file_name'] = $nama_file;
			$this->upload->initialize($config);
			if(!$this->upload->do_upload('filename')){
				$this->session->set_flashdata('error','Upload File Gagal');
				redirect(site_url('master/informasi_model/add'));
			}else{
				$data = array( 
					'deskripsi' => $this->input->post('deskripsi'), 
					'status' => $status,
					'file' => $nama_file
				);
			}
		}
		
		$update = $this->informasi_model->update($data,$where);
		if ($update) {
			$this->session->set_flashdata('success', 'Informasi Berhasil Diedit');
			redirect(site_url('master/informasi'));
		}else{
			$this->session->set_flashdata('error', 'Informasi Gagal Diedit');
			redirect(site_url('master/informasi/edit/'.htmlentities($this->input->post('id'))));
		}
	}

	public function delete($id)
	{
		$where = array('id' => $this->uri->segment(4));
		$delete = $this->informasi_model->delete($where);
		if ($delete) {
			$this->session->set_flashdata('success', 'Informasi Berhasil Dihapus');
			redirect(site_url('master/informasi'));
		}else{
			$this->session->set_flashdata('error', 'Informasi Gagal Dihapus');
			redirect(site_url('master/informasi'));
		}
	}

}