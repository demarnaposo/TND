<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profil extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if (empty($this->session->userdata('login'))) {
			$this->session->set_flashdata('access', 'Anda harus login terlebih dahulu!');
			redirect(site_url());
		}
	}

	public function index()
	{
		$data['content'] = 'profil_index';

		$this->db->from('users');
		$this->db->join('aparatur', 'users.aparatur_id = aparatur.aparatur_id', 'left');
		$this->db->join('jabatan', 'jabatan.jabatan_id = aparatur.jabatan_id', 'left');
		$this->db->where('users.users_id', $this->session->userdata('users_id'));
		$data['profil'] = $this->db->get()->result();

		$this->load->view('template', $data);
	}

	public function edit()
	{
		$data['content'] = 'profil_form';

		$data['profil'] = $this->db->get_where('users', array('users_id' => $this->uri->segment(4)))->result();

		$this->load->view('template', $data);
	}

	public function update()
	{
		$where = array('users_id' => htmlentities($this->input->post('users_id')));

		$foto = $_FILES['foto']['name'];
		$password = htmlentities($this->input->post('password'));

		if (empty($foto)) {

			if (empty($password)) {
				$id = htmlentities($this->input->post('users_id'));
				$getQuery = $this->db->get_where('users',array('users_id' => $id))->result();
				foreach ($getQuery as $key => $h) {
					$fotoLama = $h->foto;
				}
				
				$data = array(
					'username' => htmlentities($this->input->post('username')),
					'email' => htmlentities($this->input->post('email')), 
					'telp' => htmlentities($this->input->post('telp')), 
					'foto' => $fotoLama
				);
			}else{
				$id = htmlentities($this->input->post('users_id'));
				$getQuery = $this->db->get_where('users',array('users_id' => $id))->result();
				foreach ($getQuery as $key => $h) {
					$fotoLama = $h->foto;
				}
				
				$data = array(
					'username' => htmlentities($this->input->post('username')),
					'password' => sha1($password), 
					'email' => htmlentities($this->input->post('email')), 
					'telp' => htmlentities($this->input->post('telp')), 
					'foto' => $fotoLama
				);
			}

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
				redirect(site_url('home/profil/edit/'.htmlentities($this->input->post('users_id'))));
			}else{

				if (empty($password)) {
					$data = array(
						'username' => htmlentities($this->input->post('username')),
						'email' => htmlentities($this->input->post('email')), 
						'telp' => htmlentities($this->input->post('telp')), 
						'foto' => $nama_file
					);
				}else{
					$data = array(
						'username' => htmlentities($this->input->post('username')),
						'password' => sha1($password), 
						'email' => htmlentities($this->input->post('email')), 
						'telp' => htmlentities($this->input->post('telp')), 
						'foto' => $nama_file
					);
				}

			}
		
		}
		$update = $this->db->update('users',$data,$where);
		if ($update) {
			$this->session->set_flashdata('success', 'Profil Berhasil Diedit');
			redirect(site_url('home/profil'));
		}else{
			$this->session->set_flashdata('error', 'Profil Gagal Diedit');
			redirect(site_url('home/profil/edit/'.htmlentities($this->input->post('users_id'))));
		}
	}

}