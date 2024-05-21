<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class Users extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if (empty($this->session->userdata('login'))) {
			$this->session->set_flashdata('access', 'Anda harus login terlebih dahulu!');
			redirect(site_url());
		}
		$this->load->model('users_model');
	}

	public function index()
	{
		$data['content'] = 'users_index';

		if ($this->session->userdata('level') == 2) {
			$opd_id = $this->session->userdata('opd_id');
			$data['users'] = $this->users_model->get_adminskpd($opd_id)->result();
		}else{
			$data['users'] = $this->users_model->get()->result();
			$data['admin'] = $this->users_model->get_admin()->result();
		}
		
		$this->load->view('template', $data);
	}

	public function add()
	{
		$data['content'] = 'users_form';

		if ($this->session->userdata('level') == 2) {
			$opd_id = $this->session->userdata('opd_id');
			$data['level'] = $this->users_model->get_level_adminskpd()->result();
			$data['aparatur'] = $this->users_model->get_aparatur_adminskpd($opd_id)->result();
		}else{
			$data['level'] = $this->users_model->get_level()->result();
			$data['aparatur'] = $this->users_model->get_aparatur()->result();
		}
		
		$this->load->view('template', $data);
	}

	public function insert()
	{
		$foto = $_FILES['foto']['name'];

		//get username and password
		$getUsername = $this->users_model->get_username(htmlentities($this->input->post('aparatur_id')))->row_array();

		//check jabatan yang sama
		$cekJabatan = $this->users_model->cek_jabatan($getUsername['jabatan_id'])->num_rows();
		if ($cekJabatan == 1) {
			$this->session->set_flashdata('error', 'Aparatur tersebut sudah dibuat User dengan Jabatan yang sama');
			redirect(site_url('master/users/add'));
		}else{

			//check nip for 2 jabatan
			$cekNip = $this->users_model->cek_nip($getUsername['nip'])->num_rows();
			if ($cekNip == 1) {
				$nip = $getUsername['nip'].'-1';
			}else{
				$nip = $getUsername['nip'];
			}

			if (empty($foto)) {
				$data = array(
					// 'users_id' => htmlentities($this->input->post('aparatur_id')), 
					'level_id' => htmlentities($this->input->post('level_id')), 
					'aparatur_id' => htmlentities($this->input->post('aparatur_id')),
					'username' => $nip,
					'password' => sha1($nip), 
					'email' => htmlentities($this->input->post('email')), 
					'telp' => htmlentities($this->input->post('telp')), 
					'foto' => ''
				);
			}else{
				
				$ambext = explode(".",$foto);
				$ekstensi = end($ambext);
				$nama_baru = date('YmdHis');
				$nama_file = $nama_baru.".".$ekstensi;	
				$config['upload_path'] = './assets/imagesusers/';
				$config['allowed_types'] = 'jpg|jpeg|png';
				$config['file_name'] = $nama_file;
				$this->upload->initialize($config);

				if(!$this->upload->do_upload('foto')){
					$this->session->set_flashdata('error','Upload File Gagal');
					redirect(site_url('master/users/add'));
				}else{
					$data = array(
						// 'users_id' => htmlentities($this->input->post('aparatur_id')),
						'level_id' => htmlentities($this->input->post('level_id')), 
						'aparatur_id' => htmlentities($this->input->post('aparatur_id')), 
						'username' => $nip,
						'password' => sha1($nip), 
						'email' => htmlentities($this->input->post('email')), 
						'telp' => htmlentities($this->input->post('telp')), 
						'foto' => $nama_file
					);
				}
			}

			$insert = $this->users_model->insert($data);
			if ($insert) {
				$this->session->set_flashdata('success', 'User Berhasil Dibuat');
				redirect(site_url('master/users'));
			}else{
				$this->session->set_flashdata('error', 'User Gagal Dibuat');
				redirect(site_url('master/users/add'));
			}
		}
	}

	public function edit()
	{
		$data['content'] = 'users_form';

		if ($this->session->userdata('level') == 2) {
			$opd_id = $this->session->userdata('opd_id');
			$data['level'] = $this->users_model->get_level_adminskpd($opd_id)->result();
			$data['aparatur'] = $this->users_model->get_aparatur_adminskpd($opd_id)->result();
		}else{
			$data['level'] = $this->users_model->get_level()->result();
			$data['aparatur'] = $this->users_model->get_aparatur()->result();
		}

		$where = array('users_id' => $this->uri->segment(4));

		$data['users'] = $this->users_model->edit($where)->result();
		
		$this->load->view('template', $data);
	}

	public function update()
	{
		$where = array('users_id' => htmlentities($this->input->post('users_id')));

		$foto = $_FILES['foto']['name'];
		//get username and password
		$getUsername = $this->users_model->get_username(htmlentities($this->input->post('aparatur_id')))->row_array();

		//check nip for 2 jabatan
		$cekNip = $this->users_model->cek_nip($getUsername['nip'])->num_rows();

		if (empty($foto)) {

			$id = htmlentities($this->input->post('users_id'));
			$where = array('users_id' => $id);
			$getQuery = $this->users_model->edit($where)->result();
			foreach ($getQuery as $key => $h) {
				$fotoLama = $h->foto;
			}
			
			$data = array(
				'users_id' => $this->input->post('users_id'),
				'level_id' => htmlentities($this->input->post('level_id')), 
				'aparatur_id' => htmlentities($this->input->post('aparatur_id')), 
				'username' => $getUsername['nip'],
				'password' => sha1($getUsername['nip']), 
				'email' => htmlentities($this->input->post('email')), 
				'telp' => htmlentities($this->input->post('telp')), 
				'foto' => $fotoLama
			);

		}else{

			$ambext = explode(".",$foto);
			$ekstensi = end($ambext);
			$nama_baru = date('YmdHis');
			$nama_file = $nama_baru.".".$ekstensi;	
			$config['upload_path'] = './assets/imagesusers/';
			$config['allowed_types'] = 'jpg|jpeg|png';
			$config['file_name'] = $nama_file;
			$this->upload->initialize($config);

			if(!$this->upload->do_upload('foto')){
				$this->session->set_flashdata('error','Upload File Gagal');
				redirect(site_url('master/users/edit/'.htmlentities($this->input->post('users_id'))));
			}else{
				$data = array(
					'users_id' => $this->input->post('users_id'),
					'level_id' => htmlentities($this->input->post('level_id')), 
					'aparatur_id' => htmlentities($this->input->post('aparatur_id')), 
					'username' => $getUsername['nip'],
					'password' => sha1($getUsername['nip']), 
					'email' => htmlentities($this->input->post('email')), 
					'telp' => htmlentities($this->input->post('telp')), 
					'foto' => $nama_file
				);
			}
		}

		
		$update = $this->users_model->update($data,$where);
		if ($update) {
			$this->session->set_flashdata('success', 'User Berhasil Diedit');
			redirect(site_url('master/users'));
		}else{
			$this->session->set_flashdata('error', 'User Gagal Diedit');
			redirect(site_url('master/users/edit/'.htmlentities($this->input->post('users_id'))));
		}
	}

	public function delete($id)
	{
		$where = array('users_id' => $this->uri->segment(4));
		$delete = $this->users_model->delete($where);
		if ($delete) {
			$this->session->set_flashdata('success', 'User Berhasil Dihapus');
			redirect(site_url('master/users'));
		}else{
			$this->session->set_flashdata('error', 'User Gagal Dihapus');
			redirect(site_url('master/users'));
		}
	}

// ==============================================================================================================================

	public function adminadd()
	{
		$data['content'] = 'users_form';

		$data['level'] = $this->users_model->get_level_admin()->result();
		$data['aparatur'] = $this->users_model->get_aparaturadmin()->result();
		
		$this->load->view('template', $data);
	}

	public function admininsert()
	{
		$foto = $_FILES['foto']['name'];
		$cekID = $this->db->get_where('users', array('users_id' => htmlentities($this->input->post('aparatur_id'))))->num_rows();
		if ($cekID > 0) {
			$this->session->set_flashdata('error','Tidak boleh memilih aparatur admin yang sudah dibuat Usernya');
			redirect(site_url('master/users/adminadd'));
		}

		if (empty($foto)) {
			$data = array(
				// 'users_id' => htmlentities($this->input->post('aparatur_id')),
				'level_id' => htmlentities($this->input->post('level_id')), 
				'aparatur_id' => htmlentities($this->input->post('aparatur_id')),
				'username' => htmlentities($this->input->post('username')),
				'password' => htmlentities(sha1($this->input->post('password'))), 
				'email' => htmlentities($this->input->post('email')), 
				'telp' => htmlentities($this->input->post('telp')), 
				'foto' => ''
			);
		}else{
			
			$ambext = explode(".",$foto);
			$ekstensi = end($ambext);
			$nama_baru = date('YmdHis');
			$nama_file = $nama_baru.".".$ekstensi;	
			$config['upload_path'] = './assets/imagesusers/';
			$config['allowed_types'] = 'jpg|jpeg|png';
			$config['file_name'] = $nama_file;
			$this->upload->initialize($config);

			if(!$this->upload->do_upload('foto')){
				$this->session->set_flashdata('error','Upload File Gagal');
				redirect(site_url('master/users/adminadd'));
			}else{
				$data = array(
					// 'users_id' => htmlentities($this->input->post('aparatur_id')),
					'level_id' => htmlentities($this->input->post('level_id')), 
					'aparatur_id' => htmlentities($this->input->post('aparatur_id')), 
					'username' => htmlentities($this->input->post('username')),
					'password' => htmlentities(sha1($this->input->post('password'))), 
					'email' => htmlentities($this->input->post('email')), 
					'telp' => htmlentities($this->input->post('telp')), 
					'foto' => $nama_file
				);
			}
		}

		$insert = $this->users_model->insert($data);
		if ($insert) {
			$this->session->set_flashdata('success', 'User Admin Berhasil Dibuat');
			redirect(site_url('master/users'));
		}else{
			$this->session->set_flashdata('error', 'User Admin Gagal Dibuat');
			redirect(site_url('master/users/add'));
		}
	}

	public function adminedit()
	{
		$data['content'] = 'users_form';

		$data['level'] = $this->users_model->get_level_admin()->result();
		$data['aparatur'] = $this->users_model->get_aparaturadmin()->result();

		$where = array('users_id' => $this->uri->segment(4));

		$data['users'] = $this->users_model->edit($where)->result();
		
		$this->load->view('template', $data);
	}

	public function adminupdate()
	{
		$where = array('users_id' => $this->input->post('users_id'));

		$foto = $_FILES['foto']['name'];

		if (empty($foto)) {

			$id = $this->input->post('users_id');
			$where = array('users_id' => $id);
			$getQuery = $this->users_model->edit($where)->result();
			foreach ($getQuery as $key => $h) {
				$fotoLama = $h->foto;
			}
			
			$data = array(
				'users_id' => htmlentities($this->input->post('users_id')),
				'level_id' => htmlentities($this->input->post('level_id')), 
				'aparatur_id' => htmlentities($this->input->post('aparatur_id')), 
				'username' => htmlentities($this->input->post('username')), 
				'password' => htmlentities(sha1($this->input->post('password'))), 
				'email' => htmlentities($this->input->post('email')), 
				'telp' => htmlentities($this->input->post('telp')), 
				'foto' => $fotoLama
			);

		}else{

			$ambext = explode(".",$foto);
			$ekstensi = end($ambext);
			$nama_baru = date('YmdHis');
			$nama_file = $nama_baru.".".$ekstensi;	
			$config['upload_path'] = './assets/imagesusers/';
			$config['allowed_types'] = 'jpg|jpeg|png';
			$config['file_name'] = $nama_file;
			$this->upload->initialize($config);

			if(!$this->upload->do_upload('foto')){
				$this->session->set_flashdata('error','Upload File Gagal');
				redirect(site_url('master/users/adminedit/'.$this->input->post('users_id')));
			}else{
				$data = array(
					'users_id' => htmlentities($this->input->post('users_id')),
					'level_id' => htmlentities($this->input->post('level_id')), 
					'aparatur_id' => htmlentities($this->input->post('aparatur_id')), 
					'username' => htmlentities($this->input->post('username')), 
					'password' => htmlentities(sha1($this->input->post('password'))), 
					'email' => htmlentities($this->input->post('email')), 
					'telp' => htmlentities($this->input->post('telp')), 
					'foto' => $nama_file
				);
			}
		}

		
		$update = $this->users_model->update($data,$where);
		if ($update) {
			$this->session->set_flashdata('success', 'User Admin Berhasil Diedit');
			redirect(site_url('master/users'));
		}else{
			$this->session->set_flashdata('error', 'User Admin Gagal Diedit');
			redirect(site_url('master/users/edit/'.$this->input->post('users_id')));
		}
	}

	public function admindelete($id)
	{
		$where = array('users_id' => $this->uri->segment(4));
		$delete = $this->users_model->delete($where);
		if ($delete) {
			$this->session->set_flashdata('success', 'User Admin Berhasil Dihapus');
			redirect(site_url('master/users'));
		}else{
			$this->session->set_flashdata('error', 'User Admin Gagal Dihapus');
			redirect(site_url('master/users'));
		}
	}

}