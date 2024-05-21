<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class Aparatur extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if (empty($this->session->userdata('login'))) {
			$this->session->set_flashdata('access', 'Anda harus login terlebih dahulu!');
			redirect(site_url());
		}
		$this->load->model('aparatur_model');
	}

	public function index()
	{
		$data['content'] = 'aparatur_index';
		$usersid=$this->session->userdata('users_id');
			$data['permission'] = $this->db->query("SELECT * FROM permission_user WHERE users_id='$usersid'");

		if ($this->session->userdata('level') == 2) {
			$opd_id = $this->session->userdata('opd_id');

			// if($this->session->userdata('jabatan_id') == 3){
			// 	$data['aparatur'] = $this->aparatur_model->get_adminskpd1($opd_id)->result();
			// }else{
				$data['aparatur'] = $this->aparatur_model->get_adminskpd($opd_id)->result();
			// }

			//$data['aparatur'] = $this->aparatur_model->get_adminskpd($opd_id)->result();
			$data['pindahaparatur'] = $this->aparatur_model->get_pindahaparatur($opd_id)->result();
			$data['pindahopd'] = $this->aparatur_model->get_pindahopd()->result();
		}else{
			$data['aparatur'] = $this->aparatur_model->get()->result();
			$data['aparaturadmin'] = $this->aparatur_model->get_admin()->result();
		}
		
		$this->load->view('template', $data);
	}
	
	public function pindah()
	{
		$data['content'] = 'aparatur_form';

		if ($this->session->userdata('level') == 2) {
			$opd_id = $this->session->userdata('opd_id');
			$data['pindahopd'] = $this->aparatur_model->get_pindahopd()->result();
			$data['pindahjabatan'] = $this->aparatur_model->get_pindahjabatan($opd_id)->result();
		}
		
		$this->load->view('template', $data);
	}
	
	public function pindahaparatur(){
		$data=array(
			'opd_id'=>$this->session->userdata('opd_id'),
			'jabatan_id'=>$this->input->post('jabatan_id')
		);
		// Cek Value
		// ob_start();
		// var_dump($data);
		// $result = ob_get_contents();
		$where = array('aparatur_id' => htmlentities($this->input->post('aparatur_id')));
		$update = $this->aparatur_model->update($data,$where);
		if ($update) {
			$this->session->set_flashdata('success', 'Aparatur Berhasil Dipindahkan');
			redirect(site_url('master/aparatur'));
		}else{
			$this->session->set_flashdata('error', 'Aparatur Gagal Dipindahkan');
			redirect(site_url('master/aparatur/index'));
		}
	}
	
	public function get_pindahaparatur()
	{
	    $opd_id = $this->input->post('opd_id', TRUE);
	    $data = $this->aparatur_model->get_pindahaparatur($opd_id)->result();
	    
	    echo json_encode($data);
	}

	public function add()
	{
		$data['content'] = 'aparatur_form';

		if ($this->session->userdata('level') == 2) {
			$opd_id = $this->session->userdata('opd_id');
			$data['jabatan'] = $this->aparatur_model->get_jabatan_adminskpd($opd_id)->result();
			$data['level'] = $this->db->query("SELECT * FROM levelbaru WHERE level_id NOT IN (1,2,17) ORDER BY level_id ASC")->result();
		}else{
			$data['jabatan'] = $this->aparatur_model->get_jabatan()->result();
			$data['level'] = $this->db->query("SELECT * FROM levelbaru WHERE level_id !=17 ORDER BY level_id ASC")->result();
		}
		
		$this->load->view('template', $data);
	}

	public function insert()
	{
		$getOpd = $this->aparatur_model->get_opd(htmlentities($this->input->post('jabatan_id')))->row_array();
		$data = array(
			'jabatan_id' => htmlentities($this->input->post('jabatan_id')), 
			'opd_id' => $getOpd['opd_id'], 
			'nip' => htmlentities($this->input->post('nip')), 
			'nik' => htmlentities($this->input->post('nik')), 
			'nama' => htmlentities($this->input->post('nama')), 
			'eselon' => htmlentities($this->input->post('eselon')), 
			'pangkat' => htmlentities($this->input->post('pangkat')), 
			// <!-- Penambahan field no telp : Fikri Egov 12 April 2022-->
			'nohp' => htmlentities($this->input->post('nohp')),
			'golongan' => htmlentities($this->input->post('golongan')),
			'statusaparatur' => htmlentities($this->input->post('statusaparatur')),
			'level_id' => $this->input->post('level_id')
		);
		$insert = $this->aparatur_model->insert($data);
		if ($insert) {
			$this->session->set_flashdata('success', 'Aparatur Berhasil Dibuat');
			redirect(site_url('master/aparatur'));
		}else{
			$this->session->set_flashdata('error', 'Aparatur Gagal Dibuat');
			redirect(site_url('master/aparatur/add'));
		}
	}

	public function edit()
	{
		$data['content'] = 'aparatur_form';

		if ($this->session->userdata('level') == 2) {
			$opd_id = $this->session->userdata('opd_id');
			$data['jabatan'] = $this->aparatur_model->get_jabatan_adminskpd($opd_id)->result();
			$data['level'] = $this->db->query("SELECT * FROM levelbaru WHERE level_id NOT IN (1,2,17) ORDER BY level_id ASC")->result();
		}else{
			$data['jabatan'] = $this->aparatur_model->get_jabatan()->result();
			$data['level'] = $this->db->query("SELECT * FROM levelbaru WHERE level_id !=17 ORDER BY level_id ASC")->result();
		}

		$where = array('aparatur_id' => $this->uri->segment(4));

		$data['aparatur'] = $this->aparatur_model->edit($where)->result();
		
		$this->load->view('template', $data);
	}

	public function update()
	{
		$where = array('aparatur_id' => htmlentities($this->input->post('aparatur_id')));
		$getOpd = $this->aparatur_model->get_opd(htmlentities($this->input->post('jabatan_id')))->row_array();
		$data = array(
			'jabatan_id' => htmlentities($this->input->post('jabatan_id')), 
			'opd_id' => $getOpd['opd_id'], 
			'nip' => htmlentities($this->input->post('nip')), 
			'nik' => htmlentities($this->input->post('nik')), 
			'nama' => htmlentities($this->input->post('nama')), 
			'eselon' => htmlentities($this->input->post('eselon')), 
			'pangkat' => htmlentities($this->input->post('pangkat')), 
			// 			<!-- Penambahan field no telp : Fikri Egov 12 April 2022-->
			'nohp' => htmlentities($this->input->post('nohp')),
			'golongan' => htmlentities($this->input->post('golongan')),
			'statusaparatur' => htmlentities($this->input->post('statusaparatur')),
			'level_id' => $this->input->post('level_id')
		);
		$update = $this->aparatur_model->update($data,$where);
		if ($update) {
			$this->session->set_flashdata('success', 'Aparatur Berhasil Diedit');
			redirect(site_url('master/aparatur'));
		}else{
			$this->session->set_flashdata('error', 'Aparatur Gagal Diedit');
			redirect(site_url('master/aparatur/edit/'.htmlentities($this->input->post('aparatur_id'))));
		}
	}

	public function delete($id)
	{
		$where = array('aparatur_id' => $this->uri->segment(4));
		$delete = $this->aparatur_model->delete($where);
		if ($delete) {
			$this->aparatur_model->delete_data('users', $where);
			$this->session->set_flashdata('success', 'Aparatur Berhasil Dihapus');
			redirect(site_url('master/aparatur'));
		}else{
			$this->session->set_flashdata('error', 'Aparatur Gagal Dihapus');
			redirect(site_url('master/aparatur'));
		}
	}

// ============================================================================================================================

	public function addadmin()
	{
		$data['content'] = 'aparatur_form';

		$data['jabatan'] = $this->aparatur_model->get_jabatanadmin()->result();
		
		$this->load->view('template', $data);
	}

	public function insertadmin()
	{
		$getOpd = $this->aparatur_model->get_opd(htmlentities($this->input->post('jabatan_id')))->row_array();
		$data = array(
			'jabatan_id' => htmlentities($this->input->post('jabatan_id')), 
			'opd_id' => $getOpd['opd_id'], 
			'nip' => '-', 
			'nama' => htmlentities($this->input->post('nama')), 
			// 			<!-- Penambahan field no telp : Fikri Egov 12 April 2022-->
			'nohp' => htmlentities($this->input->post('nohp')),
			'eselon' => '-', 
			'pangkat' => '-', 
			'golongan' => '-',
			'statusaparatur' => htmlentities($this->input->post('statusaparatur'))
		);
		$insert = $this->aparatur_model->insert($data);
		if ($insert) {
			$this->session->set_flashdata('success', 'Aparatur Admin Berhasil Dibuat');
			redirect(site_url('master/aparatur'));
		}else{
			$this->session->set_flashdata('error', 'Aparatur Admin Gagal Dibuat');
			redirect(site_url('master/aparatur/add'));
		}
	}

	public function editadmin()
	{
		$data['content'] = 'aparatur_form';

		$data['jabatan'] = $this->aparatur_model->get_jabatanadmin()->result();

		$where = array('aparatur_id' => $this->uri->segment(4));

		$data['aparatur'] = $this->aparatur_model->edit($where)->result();
		
		$this->load->view('template', $data);
	}

	public function updateadmin()
	{
		$where = array('aparatur_id' => htmlentities($this->input->post('aparatur_id')));
		$getOpd = $this->aparatur_model->get_opd(htmlentities($this->input->post('jabatan_id')))->row_array();

		$data = array(
			'jabatan_id' => htmlentities($this->input->post('jabatan_id')), 
			'opd_id' => $getOpd['opd_id'], 
			'nip' => '-', 
			'nama' => htmlentities($this->input->post('nama')), 
			// 			<!-- Penambahan field no telp : Fikri Egov 12 April 2022-->
			'nohp' => htmlentities($this->input->post('nohp')),
			'eselon' => '-', 
			'pangkat' => '-', 
			'golongan' => '-',
			'statusaparatur' => htmlentities($this->input->post('statusaparatur'))
		);
		$update = $this->aparatur_model->update($data,$where);
		if ($update) {
			$this->session->set_flashdata('success', 'Aparatur Admin Berhasil Diedit');
			redirect(site_url('master/aparatur'));
		}else{
			$this->session->set_flashdata('error', 'Aparatur Admin Gagal Diedit');
			redirect(site_url('master/aparatur/edit/'.htmlentities($this->input->post('aparatur_id'))));
		}
	}

	public function deleteadmin($id)
	{
		$where = array('aparatur_id' => $this->uri->segment(4));
		$delete = $this->aparatur_model->delete($where);
		if ($delete) {
			$this->session->set_flashdata('success', 'Aparatur Admin Berhasil Dihapus');
			redirect(site_url('master/aparatur'));
		}else{
			$this->session->set_flashdata('error', 'Aparatur Admin Gagal Dihapus');
			redirect(site_url('master/aparatur'));
		}
	}

}