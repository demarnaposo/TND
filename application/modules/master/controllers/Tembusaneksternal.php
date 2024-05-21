<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class Tembusaneksternal extends CI_Controller {

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
		$data['content'] = 'tembusan_eksternal_index';

		$opd_id = $this->session->userdata('opd_id');
		$data['tembusan_eksternal'] = $this->eksternal_model->get_tembusan($opd_id)->result();
		
		$this->load->view('template', $data);
	}

	public function add()
	{
		$data['content'] = 'tembusan_eksternal_form';
		$this->load->view('template', $data);
	}

	public function insert()
	{
		$getEksID = $this->eksternal_model->get_id('tembusan_keluar')->result();
		foreach ($getEksID as $key => $ek) {
			$idEKs = $ek->id;
		}
		if (empty($idEKs)) {
			$tembusankeluar_id = 'EKS-1';
		}else{
			$urut = substr($idEKs, 4)+1;
			$tembusankeluar_id = 'EKS-'.$urut;
		}

		$data = array(
			'id' => $tembusankeluar_id, 
			'opd_id' => $this->session->userdata('opd_id'), 
			'nama_tembusan' => htmlentities($this->input->post('nama_tembusan')), 
			'email_tembusan' => htmlentities($this->input->post('email_tembusan')),			
		);
		$insert = $this->eksternal_model->insert_tembusan($data);
		if ($insert) {
			$this->session->set_flashdata('success', 'Tembusan Eksternal Berhasil Dibuat');
			redirect(site_url('master/tembusaneksternal'));
		}else{
			$this->session->set_flashdata('error', 'Tembusan Eksternal Gagal Dibuat');
			redirect(site_url('master/tembusaneksternal/add'));
		}
	}

	public function edit()
	{
		$data['content'] = 'tembusan_eksternal_form';
		$where = array('id' => $this->uri->segment(4));
		$data['tembusan_eksternal'] = $this->eksternal_model->edit_tembusan($where)->result();
		
		$this->load->view('template', $data);
	}

	public function update()
	{
		$where = array('id' => htmlentities($this->input->post('id')));
		$data = array(
			'opd_id' => $this->session->userdata('opd_id'), 
			'nama_tembusan' => htmlentities($this->input->post('nama_tembusan')), 
			'email_tembusan' => htmlentities($this->input->post('email_tembusan'))
			
		);
		$update = $this->eksternal_model->update_tembusan($data,$where);
		if ($update) {
			$this->session->set_flashdata('success', 'Tembusan Eksternal Berhasil Diedit');
			redirect(site_url('master/tembusaneksternal'));
		}else{
			$this->session->set_flashdata('error', 'Tembusan Eksternal Gagal Diedit');
			redirect(site_url('master/tembusaneksternal/edit/'.htmlentities($this->input->post('id'))));
		}
	}

	public function delete($id)
	{
		$where = array('id' => $this->uri->segment(4));
		$delete = $this->eksternal_model->delete_tembusan($where);
		if ($delete) {
			$this->session->set_flashdata('success', 'Tembusan Eksternal Berhasil Dihapus');
			redirect(site_url('master/tembusaneksternal'));
		}else{
			$this->session->set_flashdata('error', 'Tembusan Eksternal Gagal Dihapus');
			redirect(site_url('master/tembusaneksternal'));
		}
	}

}