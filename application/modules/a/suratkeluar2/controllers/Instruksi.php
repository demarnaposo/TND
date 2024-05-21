<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Instruksi extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if (empty($this->session->userdata('login'))) {
			$this->session->set_flashdata('access', 'Anda harus login terlebih dahulu!');
			redirect(site_url());
		}
		$this->load->model('instruksi_model');
	}

	public function index()
	{
		$data['content'] = 'instruksi/instruksi_index';
		
		$tahun = $this->session->userdata('tahun');
		$opd_id = $this->session->userdata('opd_id');
		$jabatan_id = $this->session->userdata('jabatan_id');

		$data['instruksi'] = $this->instruksi_model->get_data($tahun,$opd_id,$jabatan_id)->result();
		
		$this->load->view('template', $data);
	}

	public function add()
	{
		$data['content'] = 'instruksi/instruksi_form';
		$data['kop'] = $this->db->get('kop_surat')->result();
		$data['kodesurat'] = $this->db->get('kode_surat')->result();
		
		$this->load->view('template', $data);
	}

	public function insert()
	{
		//untuk menambahkan data eksternal
		if (isset($_POST['simpan'])) {
			
			$getEksID = $this->edaran_model->get_id('eksternal_keluar')->result();
			foreach ($getEksID as $key => $ek) {
				$idEks = $ek->id;
			}
			if (empty($idEks)) {
				$eksternalkeluar_id = 'EKS-1';
			}else{
				$urut = substr($id, 4)+1;
				$eksternalkeluar_id = 'EKS-'.$urut;
			}
			
			$data = array(
				'id' => $eksternalkeluar_id,
				'opd_id' => $this->session->userdata('opd_id'), 
				'nama' => $this->input->post('nama'), 
				'email' => $this->input->post('email'), 
			);
			$insert = $this->instruksi_model->insert_data('eksternal_keluar', $data);

			if ($insert) {
				$this->session->set_flashdata('success', 'Eksternal Berhasil Dibuat');
				redirect(site_url('suratkeluar/instruksi/add'));
			}else{
				$this->session->set_flashdata('error', 'Eksternal Gagal Dibuat');
				redirect(site_url('suratkeluar/draft/add/'));
			}

		//Untuk menambahkan surat
		}else{

			$getID = $this->instruksi_model->get_id('surat_instruksi')->result();
			foreach ($getID as $key => $h) {
				$id = $h->id;
			}
			if (empty($id)) {
				$surat_id = 'INT-1';
			}else{
				$urut = substr($id, 4)+1;
				$surat_id = 'INT-'.$urut;
			}

			//pengiriman surat internal atau eksternal
			$jabatan_id = $this->input->post('jabatan_id');
			$eksternal_id = $this->input->post('eksternal_id');
			internal_eksternal('instruksi',$surat_id,$jabatan_id,$eksternal_id);

			$file = $_FILES['lampiran_lain']['name'];

			if (empty($file)) {
				$data = array(
					'id' => $surat_id,
					'opd_id' => $this->session->userdata('opd_id'),
					'kodesurat_id' => htmlentities($this->input->post('kodesurat_id')), 
					'tanggal' => htmlentities($this->input->post('tanggal')), 
					'nomor' => '',
					'sifat' => htmlentities($this->input->post('sifat')), 
					'lampiran' => htmlentities($this->input->post('lampiran')), 
					'tentang' => htmlentities($this->input->post('tentang')), 
					'lampiran_lain' => '',
					'isi' => $this->input->post('isi'),  
				);
				$insert = $this->instruksi_model->insert_data('surat_instruksi', $data);
				if ($insert) {

					$datadraft = array(
						'surat_id' => $surat_id,
						'tanggal' => htmlentities($this->input->post('tanggal')), 
						'dibuat_id' => $this->session->userdata('jabatan_id'), 
						'penandatangan_id' => '',
						'verifikasi_id' => '', 
						'nama_surat' => 'Surat Instruksi', 
					);
					$this->instruksi_model->insert_data('draft', $datadraft);

					$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
					redirect(site_url('suratkeluar/instruksi'));
				}else{
					$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
					redirect(site_url('suratkeluar/instruksi/add'));
				}
			}else{
	        	$ambext = explode(".",$file);
				$ekstensi = end($ambext);
				$nama_baru = date('YmdHis');
				$nama_file = $nama_baru.".".$ekstensi;	
				$config['upload_path'] = './assets/lampiransurat/instruksi/';
				$config['allowed_types'] = 'pdf';
				$config['file_name'] = $nama_file;
				$this->upload->initialize($config);

				if(!$this->upload->do_upload('lampiran_lain')){
					$this->session->set_flashdata('error','Upload File Gagal');
					redirect(site_url('suratkeluar/instruksi/add'));
				}else{
					$data = array(
						'id' => $surat_id,
						'opd_id' => $this->session->userdata('opd_id'),
						'kodesurat_id' => htmlentities($this->input->post('kodesurat_id')), 
						'tanggal' => htmlentities($this->input->post('tanggal')), 
						'nomor' => '',
						'sifat' => htmlentities($this->input->post('sifat')), 
						'lampiran' => htmlentities($this->input->post('lampiran')), 
						'tentang' => htmlentities($this->input->post('tentang')), 
						'lampiran_lain' => $nama_file, 
						'isi' => $this->input->post('isi'), 
					);
					$insert = $this->instruksi_model->insert_data('surat_instruksi', $data);
					if ($insert) {

						$datadraft = array(
							'surat_id' => $surat_id, 
							'tanggal' => htmlentities($this->input->post('tanggal')), 
							'dibuat_id' => $this->session->userdata('jabatan_id'), 
							'penandatangan_id' => '',
							'verifikasi_id' => '', 
							'nama_surat' => 'Surat Instruksi', 
						);
						$this->instruksi_model->insert_data('draft', $datadraft);

						$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
						redirect(site_url('suratkeluar/instruksi'));
					}else{
						$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
						redirect(site_url('suratkeluar/instruksi/add'));
					}
				}
			}

		}
	}

	public function edit()
	{
		$data['content'] = 'instruksi/instruksi_form';
		$data['kop'] = $this->db->get('kop_surat')->result();
		$data['kodesurat'] = $this->db->get('kode_surat')->result();
		$data['instruksi'] = $this->instruksi_model->edit_data($this->uri->segment(4), $this->session->userdata('opd_id'))->result();
		
		$this->load->view('template', $data);
	}

	public function update()
	{
		$id = $this->input->post('id');

		//Untuk menambahkan data eksternal 
		if (isset($_POST['simpan'])) {
			
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
				'nama' => $this->input->post('nama'), 
				'email' => $this->input->post('email'), 
			);
			$insert = $this->instruksi_model->insert_data('eksternal_keluar', $data);

			if ($insert) {
				$this->session->set_flashdata('success', 'Eksternal Berhasil Dibuat');
				redirect(site_url('suratkeluar/instruksi/edit/'.$id));
			}else{
				$this->session->set_flashdata('error', 'Eksternal Gagal Dibuat');
				redirect(site_url('suratkeluar/draft/edit/'.$id));
			}

		//Untuk mengedit surat
		}else{

			//pengiriman surat internal atau eksternal
			$jabatan_id = $this->input->post('jabatan_id');
			$eksternal_id = $this->input->post('eksternal_id');
			if (!empty($jabatan_id) OR !empty($eksternal_id)) {
				internal_eksternal('instruksi',$id,$jabatan_id,$eksternal_id);
			}

			$file = $_FILES['lampiran_lain']['name'];

			if (empty($file)) {
				$getQuery = $this->instruksi_model->edit_data('surat_instruksi', array('id' => $id))->result();
				foreach ($getQuery as $key => $h) {
					$fileLampiran = $h->lampiran_lain;
				}
				$data = array(
					'opd_id' => $this->session->userdata('opd_id'),
					'kodesurat_id' => htmlentities($this->input->post('kodesurat_id')), 
					'tanggal' => htmlentities($this->input->post('tanggal')), 
					'nomor' => '',
					'sifat' => htmlentities($this->input->post('sifat')), 
					'lampiran' => htmlentities($this->input->post('lampiran')), 
					'tentang' => htmlentities($this->input->post('tentang')), 
					'lampiran_lain' => '',
					'isi' => $this->input->post('isi'),   
				);
				$where = array('id' => $id);
				$update = $this->instruksi_model->update_data('surat_instruksi', $data, $where);
				if ($update) {

					$datadraft = array(
						'tanggal' => htmlentities($this->input->post('tanggal')), 
					);
					$wheredraft = array('surat_id' => $id);
					$this->instruksi_model->update_data('draft', $datadraft, $wheredraft);

					$this->session->set_flashdata('success', 'Surat Berhasil Diedit');
					
					$pembuatSurat = $this->db->get_where('draft', array('surat_id' => $id))->row_array();
					
					if ($pembuatSurat['dibuat_id'] == $this->session->userdata('jabatan_id')) {
						redirect(site_url('suratkeluar/instruksi'));
					}else{
						redirect(site_url('suratkeluar/draft'));
					}

				}else{
					$this->session->set_flashdata('error', 'Surat Gagal Diedit');
					redirect(site_url('suratkeluar/instruksi/edit/'.$id));
				}
			}else{
	        	$ambext = explode(".",$file);
				$ekstensi = end($ambext);
				$nama_baru = date('YmdHis');
				$nama_file = $nama_baru.".".$ekstensi;	
				$config['upload_path'] = './assets/lampiransurat/instruksi/';
				$config['allowed_types'] = 'pdf';
				$config['file_name'] = $nama_file;
				$this->upload->initialize($config);

				if(!$this->upload->do_upload('lampiran_lain')){
					$this->session->set_flashdata('error','Upload File Gagal');
					redirect(site_url('suratkeluar/instruksi/edit'));
				}else{
					$id = $this->input->post('id');
					$data = array(
						'opd_id' => $this->session->userdata('opd_id'),
						'kodesurat_id' => htmlentities($this->input->post('kodesurat_id')),
						'tanggal' => htmlentities($this->input->post('tanggal')), 
						'sifat' => htmlentities($this->input->post('sifat')), 
						'lampiran' => htmlentities($this->input->post('lampiran')), 
						'tentang' => htmlentities($this->input->post('tentanga')), 
						'lampiran_lain' => $nama_file,
						'isi' => $this->input->post('isi'), 	
					);
					$where = array('id' => $id);
					$update = $this->instruksi_model->update_data('surat_instruksi', $data, $where);
					if ($update) {

						$datadraft = array( 
							'tanggal' => htmlentities($this->input->post('tanggal')), 
						);
						$wheredraft = array('surat_id' => $id);
						$this->instruksi_model->update_data('draft', $datadraft, $wheredraft);

						$this->session->set_flashdata('success', 'Surat Berhasil Diedit');
						
						$pembuatSurat = $this->db->get_where('draft', array('surat_id' => $id))->row_array();
						
						if ($pembuatSurat['dibuat_id'] == $this->session->userdata('jabatan_id')) {
							redirect(site_url('suratkeluar/instruksi'));
						}else{
							redirect(site_url('suratkeluar/draft'));
						}
						
					}else{
						$this->session->set_flashdata('error', 'Surat Gagal Diedit');
						redirect(site_url('suratkeluar/instruksi/edit/'.$id));
					}
				}
			}

		}
	}

	public function delete()
	{
		$where = array('id' => $this->uri->segment(4));
		$delete = $this->instruksi_model->delete_data('surat_instruksi', $where);
		if ($delete) {
			$whereDis = array('surat_id' => $this->uri->segment(4));
			$this->instruksi_model->delete_data('draft', $whereDis);
			$this->instruksi_model->delete_data('verifikasi', $whereDis);
			$this->instruksi_model->delete_data('disposisi_suratkeluar', $whereDis);
			
			$this->session->set_flashdata('success', 'Surat Berhasil Dihapus');
			redirect(site_url('suratkeluar/instruksi'));
		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Dihapus');
			redirect(site_url('suratkeluar/instruksi'));
		}
	}

	public function delete_kepada()
	{
		$surat_id = $this->uri->segment(4);
		$id = $this->uri->segment(5);
		$where = array('dsuratkeluar_id' => $id);
		$delete = $this->instruksi_model->delete_data('disposisi_suratkeluar', $where);
		if ($delete) {
			$this->session->set_flashdata('success', 'Data Berhasil Dihapus');
			redirect(site_url('suratkeluar/instruksi/edit/'.$surat_id));
		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Dihapus');
			redirect(site_url('suratkeluar/instruksi/edit/'.$surat_id));
		}
	}

}