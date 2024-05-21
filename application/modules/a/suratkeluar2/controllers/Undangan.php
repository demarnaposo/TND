<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Undangan extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if (empty($this->session->userdata('login'))) {
			$this->session->set_flashdata('access', 'Anda harus login terlebih dahulu!');
			redirect(site_url());
		}
		$this->load->model('undangan_model');
	}

	public function index()
	{
		$data['content'] = 'undangan/undangan_index';
		
		$tahun = $this->session->userdata('tahun');
		$opd_id = $this->session->userdata('opd_id');
		$jabatan_id = $this->session->userdata('jabatan_id');

		$data['undangan'] = $this->undangan_model->get_data($tahun,$opd_id,$jabatan_id)->result();
		
		$this->load->view('template', $data);
	}

	public function add()
	{
		$data['content'] = 'undangan/undangan_form';
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
			$insert = $this->undangan_model->insert_data('eksternal_keluar', $data);

			if ($insert) {
				$this->session->set_flashdata('success', 'Eksternal Berhasil Dibuat');
				redirect(site_url('suratkeluar/undangan/add'));
			}else{
				$this->session->set_flashdata('error', 'Eksternal Gagal Dibuat');
				redirect(site_url('suratkeluar/draft/add/'));
			}

		//Untuk menambahkan surat
		}else{

			$getID = $this->undangan_model->get_id('surat_undangan')->result();
			foreach ($getID as $key => $h) {
				$id = $h->id;
			}
			if (empty($id)) {
				$surat_id = 'SU-1';
			}else{
				$urut = substr($id, 3)+1;
				$surat_id = 'SU-'.$urut;
			}

			$jabatan_id = $this->input->post('jabatan_id');
			$eksternal_id = $this->input->post('eksternal_id');

			//pengiriman surat internal atau eksternal
			internal_eksternal('undangan',$surat_id,$jabatan_id,$eksternal_id);

			$file = $_FILES['lampiran_lain']['name'];

			if (empty($file)) {
				$data = array(
					'id' => $surat_id,
					'kop_id' => htmlentities($this->input->post('kop_id')), 
					'opd_id' => $this->session->userdata('opd_id'),
					'kodesurat_id' => htmlentities($this->input->post('kodesurat_id')), 
					'tanggal' => htmlentities($this->input->post('tanggal')), 
					'nomor' => '',
					'sifat' => htmlentities($this->input->post('sifat')), 
					'lampiran' => htmlentities($this->input->post('lampiran')), 
					'hal' => htmlentities($this->input->post('hal')), 
					'tembusan' => htmlentities($this->input->post('tembusan')), 
					'lampiran_lain' => '',
					'p1' => $this->input->post('p1'),
					'hari' => $this->input->post('hari'),
					'tgl_acara' => $this->input->post('tgl_acara'),
					'pukul' => $this->input->post('pukul'),
					'tempat' => $this->input->post('tempat'),
					'acara' => $this->input->post('acara'),
					'p2' => $this->input->post('p2'),
				);
				$insert = $this->undangan_model->insert_data('surat_undangan', $data);
				if ($insert) {

					$datadraft = array(
						'surat_id' => $surat_id,
						'tanggal' => htmlentities($this->input->post('tanggal')), 
						'dibuat_id' => $this->session->userdata('jabatan_id'), 
						'penandatangan_id' => '',
						'verifikasi_id' => '', 
						'nama_surat' => 'Surat Undangan', 
					);
					$this->undangan_model->insert_data('draft', $datadraft);

					$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
					redirect(site_url('suratkeluar/undangan'));
				}else{
					$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
					redirect(site_url('suratkeluar/undangan/add'));
				}
			}else{
	        	$ambext = explode(".",$file);
				$ekstensi = end($ambext);
				$nama_baru = date('YmdHis');
				$nama_file = $nama_baru.".".$ekstensi;	
				$config['upload_path'] = './assets/lampiransurat/undangan/';
				$config['allowed_types'] = 'pdf';
				$config['file_name'] = $nama_file;
				$this->upload->initialize($config);

				if(!$this->upload->do_upload('lampiran_lain')){
					$this->session->set_flashdata('error','Upload File Gagal');
					redirect(site_url('suratkeluar/undangan/add'));
				}else{
					$data = array(
						'id' => $surat_id,
						'kop_id' => htmlentities($this->input->post('kop_id')), 
						'opd_id' => $this->session->userdata('opd_id'),
						'kodesurat_id' => htmlentities($this->input->post('kodesurat_id')), 
						'tanggal' => htmlentities($this->input->post('tanggal')), 
						'nomor' => '',
						'sifat' => htmlentities($this->input->post('sifat')), 
						'lampiran' => htmlentities($this->input->post('lampiran')), 
						'hal' => htmlentities($this->input->post('hal')), 
						'tembusan' => htmlentities($this->input->post('tembusan')), 
						'lampiran_lain' => $nama_file, 
						'p1' => $this->input->post('p1'),
						'hari' => $this->input->post('hari'),
						'tgl_acara' => $this->input->post('tgl_acara'),
						'pukul' => $this->input->post('pukul'),
						'tempat' => $this->input->post('tempat'),
						'acara' => $this->input->post('acara'),
						'p2' => $this->input->post('p2'),
					);
					$insert = $this->undangan_model->insert_data('surat_undangan', $data);
					if ($insert) {

						$datadraft = array(
							'surat_id' => $surat_id, 
							'tanggal' => htmlentities($this->input->post('tanggal')), 
							'dibuat_id' => $this->session->userdata('jabatan_id'), 
							'penandatangan_id' => '',
							'verifikasi_id' => '', 
							'nama_surat' => 'Surat Undangan', 
						);
						$this->undangan_model->insert_data('draft', $datadraft);

						$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
						redirect(site_url('suratkeluar/undangan'));
					}else{
						$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
						redirect(site_url('suratkeluar/undangan/add'));
					}
				}
			}

		}
	}

	public function edit()
	{
		$data['content'] = 'undangan/undangan_form';
		$data['kop'] = $this->db->get('kop_surat')->result();
		$data['kodesurat'] = $this->db->get('kode_surat')->result();
		$data['undangan'] = $this->undangan_model->edit_data($this->uri->segment(4), $this->session->userdata('opd_id'))->result();
		
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
			$insert = $this->undangan_model->insert_data('eksternal_keluar', $data);

			if ($insert) {
				$this->session->set_flashdata('success', 'Eksternal Berhasil Dibuat');
				redirect(site_url('suratkeluar/undangan/edit/'.$id));
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
				internal_eksternal('undangan',$id,$jabatan_id,$eksternal_id);
			}

			$file = $_FILES['lampiran_lain']['name'];

			if (empty($file)) {
				$getQuery = $this->undangan_model->edit_data('surat_undangan', array('id' => $id))->result();
				foreach ($getQuery as $key => $h) {
					$fileLampiran = $h->lampiran_lain;
				}
				$data = array(
					'kop_id' => htmlentities($this->input->post('kop_id')),
					'opd_id' => htmlentities($this->session->userdata('opd_id')),
					'kodesurat_id' => htmlentities($this->input->post('kodesurat_id')),
					'tanggal' => htmlentities($this->input->post('tanggal')),
					'sifat' => htmlentities($this->input->post('sifat')), 
					'lampiran' => htmlentities($this->input->post('lampiran')), 
					'hal' => htmlentities($this->input->post('hal')), 
					'tembusan' => htmlentities($this->input->post('tembusan')), 
					'lampiran_lain' => $fileLampiran,
					'p1' => $this->input->post('p1'),
					'hari' => $this->input->post('hari'),
					'tgl_acara' => $this->input->post('tgl_acara'),
					'pukul' => $this->input->post('pukul'),
					'tempat' => $this->input->post('tempat'),
					'acara' => $this->input->post('acara'),
					'p2' => $this->input->post('p2'),							
				);
				$where = array('id' => $id);
				$update = $this->undangan_model->update_data('surat_undangan', $data, $where);
				if ($update) {

					$datadraft = array(
						'tanggal' => htmlentities($this->input->post('tanggal')), 
					);
					$wheredraft = array('surat_id' => $id);
					$this->undangan_model->update_data('draft', $datadraft, $wheredraft);

					$this->session->set_flashdata('success', 'Surat Berhasil Diedit');
					
					$pembuatSurat = $this->db->get_where('draft', array('surat_id' => $id))->row_array();
					
					if ($pembuatSurat['dibuat_id'] == $this->session->userdata('jabatan_id')) {
						redirect(site_url('suratkeluar/undangan'));
					}else{
						redirect(site_url('suratkeluar/draft'));
					}

				}else{
					$this->session->set_flashdata('error', 'Surat Gagal Diedit');
					redirect(site_url('suratkeluar/undangan/edit/'.$id));
				}
			}else{
	        	$ambext = explode(".",$file);
				$ekstensi = end($ambext);
				$nama_baru = date('YmdHis');
				$nama_file = $nama_baru.".".$ekstensi;	
				$config['upload_path'] = './assets/lampiransurat/undangan/';
				$config['allowed_types'] = 'pdf';
				$config['file_name'] = $nama_file;
				$this->upload->initialize($config);

				if(!$this->upload->do_upload('lampiran_lain')){
					$this->session->set_flashdata('error','Upload File Gagal');
					redirect(site_url('suratkeluar/undangan/edit'));
				}else{
					$data = array(
						'kop_id' => htmlentities($this->input->post('kop_id')),
						'opd_id' => $this->session->userdata('opd_id'),
						'kodesurat_id' => htmlentities($this->input->post('kodesurat_id')),
						'tanggal' => htmlentities($this->input->post('tanggal')), 
						'sifat' => htmlentities($this->input->post('sifat')), 
						'lampiran' => htmlentities($this->input->post('lampiran')), 
						'hal' => htmlentities($this->input->post('hal')), 
						'tembusan' => htmlentities($this->input->post('tembusan')), 
						'lampiran_lain' => $nama_file,
						'p1' => $this->input->post('p1'),
						'hari' => $this->input->post('hari'),
						'tgl_acara' => $this->input->post('tgl_acara'),
						'pukul' => $this->input->post('pukul'),
						'tempat' => $this->input->post('tempat'),
						'acara' => $this->input->post('acara'),
						'p2' => $this->input->post('p2'),					
					);
					$where = array('id' => $id);
					$update = $this->undangan_model->update_data('surat_undangan', $data, $where);
					if ($update) {

						$datadraft = array( 
							'tanggal' => htmlentities($this->input->post('tanggal')), 
						);
						$wheredraft = array('surat_id' => $id);
						$this->undangan_model->update_data('draft', $datadraft, $wheredraft);

						$this->session->set_flashdata('success', 'Surat Berhasil Diedit');
						
						if ($this->session->userdata('level') == 8) {
							redirect(site_url('suratkeluar/undangan'));
						}else{
							redirect(site_url('suratkeluar/draft'));
						}
						
					}else{
						$this->session->set_flashdata('error', 'Surat Gagal Diedit');
						redirect(site_url('suratkeluar/undangan/edit/'.$id));
					}
				}
			}

		}
	}

	public function delete()
	{
		$where = array('id' => $this->uri->segment(4));
		$delete = $this->undangan_model->delete_data('surat_undangan', $where);
		if ($delete) {
			$whereDis = array('surat_id' => $this->uri->segment(4));
			$this->undangan_model->delete_data('draft', $whereDis);
			$this->undangan_model->delete_data('verifikasi', $whereDis);
			$this->undangan_model->delete_data('disposisi_suratkeluar', $whereDis);
			
			$this->session->set_flashdata('success', 'Surat Berhasil Dihapus');
			redirect(site_url('suratkeluar/undangan'));
		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Dihapus');
			redirect(site_url('suratkeluar/undangan'));
		}
	}

	public function delete_kepada()
	{
		$surat_id = $this->uri->segment(4);
		$id = $this->uri->segment(5);
		$where = array('dsuratkeluar_id' => $id);
		$delete = $this->undangan_model->delete_data('disposisi_suratkeluar', $where);
		if ($delete) {
			$this->session->set_flashdata('success', 'Data Berhasil Dihapus');
			redirect(site_url('suratkeluar/undangan/edit/'.$surat_id));
		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Dihapus');
			redirect(site_url('suratkeluar/undangan/edit/'.$surat_id));
		}
	}	

}