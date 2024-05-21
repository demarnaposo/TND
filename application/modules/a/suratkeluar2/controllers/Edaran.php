<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edaran extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if (empty($this->session->userdata('login'))) {
			$this->session->set_flashdata('access', 'Anda harus login terlebih dahulu!');
			redirect(site_url());
		}
		$this->load->model('edaran_model');
	}

	public function index()
	{
		$data['content'] = 'edaran/edaran_index';
		
		$tahun = $this->session->userdata('tahun');
		$opd_id = $this->session->userdata('opd_id');
		$jabatan_id = $this->session->userdata('jabatan_id');

		$data['edaran'] = $this->edaran_model->get_data($tahun,$opd_id,$jabatan_id)->result();
		
		$this->load->view('template', $data);
	}

	public function add()
	{	
		$data['content'] = 'edaran/edaran_form';
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
				$urut = substr($idEks, 4)+1;
				$eksternalkeluar_id = 'EKS-'.$urut;
			}
			$data = array(
				'id' => $eksternalkeluar_id,
				'opd_id' => $this->session->userdata('opd_id'), 
				'nama' => $this->input->post('nama'), 
				'email' => $this->input->post('email'), 
			);
			$insert = $this->edaran_model->insert_data('eksternal_keluar', $data);

			if ($insert) {
				$this->session->set_flashdata('success', 'Eksternal Berhasil Dibuat');
				redirect(site_url('suratkeluar/edaran/add'));
			}else{
				$this->session->set_flashdata('error', 'Eksternal Gagal Dibuat');
				redirect(site_url('suratkeluar/draft/add/'));
			}

		//Untuk menambahkan surat
		}else{

			$getID = $this->edaran_model->get_id('surat_edaran')->result();
			foreach ($getID as $key => $h) {
				$id = $h->id;
			}
			if (empty($id)) {
				$surat_id = 'SE-1';
			}else{
				$urut = substr($id, 3)+1;
				$surat_id = 'SE-'.$urut;
			}

			$jabatan_id = $this->input->post('jabatan_id');
			$eksternal_id = $this->input->post('eksternal_id');
			
			// $OpdImplode = implode(',', $jabatan_id);
			// $EksternalImplode = implode(',', $eksternal_id);
			
			// $OpdExplode = explode(',', $OpdImplode);
			// $EksternalExplode = explode(',', $EksternalImplode);
			
			// foreach ($OpdExplode as $key => $o) {
			// 	$jabatan = $this->db->get_where('jabatan', array('jabatan_id' => $o))->row_array();
			// 	$opd = $this->db->get_where('opd', array('opd_id' => $jabatan['opd_id']))->row_array();
			// 	foreach ($EksternalExplode as $key => $e) {
			// 		$eksternal = $this->db->get_where('eksternal_keluar', array('id' => $e))->row_array();
			// 		echo $opd['nama_pd'].'<br>'.$eksternal['nama'].'<br>';
			// 	}
			// }
			
			//pengiriman surat internal atau eksternal
			internal_eksternal('edaran',$surat_id,$jabatan_id,$eksternal_id);

			$file = $_FILES['lampiran_lain']['name'];

			if (empty($file)) {
				$data = array(
					'id' => $surat_id,
					'kop_id' => $this->input->post('kop_id'), 
					'opd_id' => $this->session->userdata('opd_id'),
					'kodesurat_id' => $this->input->post('kodesurat_id'), 
					'tanggal' => $this->input->post('tanggal'), 
					'nomor' => '',
					'tentang' => $this->input->post('tentang'),  
					'isi' => $this->input->post('isi'),  
					'lampiran_lain' => '',
				);
				$insert = $this->edaran_model->insert_data('surat_edaran', $data);
				if ($insert) {

					$datadraft = array(
						'surat_id' => $surat_id,
						'tanggal' => $this->input->post('tanggal'), 
						'dibuat_id' => $this->session->userdata('jabatan_id'), 
						'penandatangan_id' => '',
						'verifikasi_id' => '', 
						'nama_surat' => 'Surat Edaran', 
					);
					$this->edaran_model->insert_data('draft', $datadraft);

					$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
					redirect(site_url('suratkeluar/edaran'));
				}else{
					$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
					redirect(site_url('suratkeluar/edaran/add'));
				}
			}else{
	        	$ambext = explode(".",$file);
				$ekstensi = end($ambext);
				$nama_baru = date('YmdHis');
				$nama_file = $nama_baru.".".$ekstensi;	
				$config['upload_path'] = './assets/lampiransurat/edaran/';
				$config['allowed_types'] = 'pdf';
				$config['file_name'] = $nama_file;
				$this->upload->initialize($config);

				if(!$this->upload->do_upload('lampiran_lain')){
					$this->session->set_flashdata('error','Upload File Gagal');
					redirect(site_url('suratkeluar/edaran/add'));
				}else{
					$data = array(
						'id' => $surat_id, 
						'kop_id' => $this->input->post('kop_id'), 
						'opd_id' => $this->session->userdata('opd_id'),
						'kodesurat_id' => $this->input->post('kodesurat_id'), 
						'tanggal' => $this->input->post('tanggal'), 
						'nomor' => '',
						'tentang' => $this->input->post('tentang'), 
						'isi' => $this->input->post('isi'),
						'lampiran_lain' => $nama_file, 
					);
					$insert = $this->edaran_model->insert_data('surat_edaran', $data);
					if ($insert) {

						$datadraft = array(
							'surat_id' => $surat_id, 
							'tanggal' => $this->input->post('tanggal'),
							'dibuat_id' => $this->session->userdata('jabatan_id'), 
							'penandatangan_id' => '',
							'verifikasi_id' => '', 
							'nama_surat' => 'Surat Edaran', 
						);
						$this->edaran_model->insert_data('draft', $datadraft);

						$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
						redirect(site_url('suratkeluar/edaran'));
					}else{
						$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
						redirect(site_url('suratkeluar/edaran/add'));
					}
				}
			}

		}
	}

	public function edit()
	{
		$data['content'] = 'edaran/edaran_form';
		$data['kop'] = $this->db->get('kop_surat')->result();
		$data['kodesurat'] = $this->db->get('kode_surat')->result();
		$data['edaran'] = $this->edaran_model->edit_data($this->uri->segment(4))->result();
		
		$this->load->view('template', $data);
	}

	public function update()
	{
		$id = $this->input->post('id');

		//Untuk menambahkan data eksternal 
		if (isset($_POST['simpan'])) {

			$getEksID = $this->edaran_model->get_id('eksternal_keluar')->result();
			foreach ($getEksID as $key => $ek) {
				$idEks = $ek->id;
			}
			if (empty($idEks)) {
				$eksternalkeluar_id = 'EKS-1';
			}else{
				$urut = substr($idEks, 4)+1;
				$eksternalkeluar_id = 'EKS-'.$urut;
			}
			
			$data = array(
				'id' => $eksternalkeluar_id,
				'opd_id' => $this->session->userdata('opd_id'), 
				'nama' => $this->input->post('nama'), 
				'email' => $this->input->post('email'), 
			);
			$insert = $this->edaran_model->insert_data('eksternal_keluar', $data);

			if ($insert) {
				$this->session->set_flashdata('success', 'Eksternal Berhasil Dibuat');
				redirect(site_url('suratkeluar/edaran/edit/'.$id));
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
				internal_eksternal('edaran',$id,$jabatan_id,$eksternal_id);
			}

			$file = $_FILES['lampiran_lain']['name'];

			if (empty($file)) {
				$getQuery = $this->edaran_model->edit_data('surat_edaran', array('id' => $id))->result();
				foreach ($getQuery as $key => $h) {
					$fileLampiran = $h->lampiran_lain;
				}
				$data = array(
					'opd_id' => $this->session->userdata('opd_id'),
					'kop_id' => $this->input->post('kop_id'), 
					'kodesurat_id' => $this->input->post('kodesurat_id'), 
					'tanggal' => $this->input->post('tanggal'),
					'tentang' => $this->input->post('tentang'),
					'isi' => $this->input->post('isi'), 
					'lampiran_lain' => $fileLampiran,
				);
				$where = array('id' => $id);
				$update = $this->edaran_model->update_data('surat_edaran', $data, $where);
				if ($update) {

					$datadraft = array( 
						'tanggal' => $this->input->post('tanggal'),
					);
					$wheredraft = array('surat_id' => $id);
					$this->edaran_model->update_data('draft', $datadraft, $wheredraft);
					
					$this->session->set_flashdata('success', 'Surat Berhasil Diedit');
					
					$pembuatSurat = $this->db->get_where('draft', array('surat_id' => $id))->row_array();

					if ($pembuatSurat['dibuat_id'] == $this->session->userdata('jabatan_id')) {
						redirect(site_url('suratkeluar/edaran'));
					}else{
						redirect(site_url('suratkeluar/draft'));
					}

				}else{
					$this->session->set_flashdata('error', 'Surat Gagal Diedit');
					redirect(site_url('suratkeluar/edaran/edit/'.$id));
				}
			}else{
	        	$ambext = explode(".",$file);
				$ekstensi = end($ambext);
				$nama_baru = date('YmdHis');
				$nama_file = $nama_baru.".".$ekstensi;	
				$config['upload_path'] = './assets/lampiransurat/edaran/';
				$config['allowed_types'] = 'pdf';
				$config['file_name'] = $nama_file;
				$this->upload->initialize($config);

				if(!$this->upload->do_upload('lampiran_lain')){
					$this->session->set_flashdata('error','Upload File Gagal');
					redirect(site_url('suratkeluar/edaran/edit'));
				}else{
					$data = array(
						'kop_id' => $this->input->post('kop_id'), 
						'opd_id' => $this->session->userdata('opd_id'),
						'kodesurat_id' => $this->input->post('kodesurat_id'), 
						'tanggal' => $this->input->post('tanggal'),
						'tentang' => $this->input->post('tentang'),
						'isi' => $this->input->post('isi'), 
						'lampiran_lain' => $nama_file, 	
					);
					$where = array('id' => $id);
					$update = $this->edaran_model->update_data('surat_edaran', $data, $where);
					if ($update) {

						$datadraft = array( 
							'tanggal' => $this->input->post('tanggal'),
						);
						$wheredraft = array('surat_id' => $id);
						$this->edaran_model->update_data('draft', $datadraft, $wheredraft);

						$this->session->set_flashdata('success', 'Surat Berhasil Diedit');
						
						$pembuatSurat = $this->db->get_where('draft', array('surat_id' => $id))->row_array();

						if ($pembuatSurat['dibuat_id'] == $this->session->userdata('jabatan_id')) {
							redirect(site_url('suratkeluar/edaran'));
						}else{
							redirect(site_url('suratkeluar/draft'));
						}

					}else{
						$this->session->set_flashdata('error', 'Surat Gagal Diedit');
						redirect(site_url('suratkeluar/edaran/edit/'.$id));
					}
				}
			}

		}
	}

	public function delete()
	{
		$where = array('id' => $this->uri->segment(4));
		$delete = $this->edaran_model->delete_data('surat_edaran', $where);
		if ($delete) {
			$whereDis = array('surat_id' => $this->uri->segment(4));
			$this->edaran_model->delete_data('draft', $whereDis);
			$this->edaran_model->delete_data('verifikasi', $whereDis);
			$this->edaran_model->delete_data('disposisi_suratkeluar', $whereDis);
			
			$this->session->set_flashdata('success', 'Surat Berhasil Dihapus');
			redirect(site_url('suratkeluar/edaran'));
		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Dihapus');
			redirect(site_url('suratkeluar/edaran'));
		}
	}

	public function delete_kepada()
	{
		$surat_id = $this->uri->segment(4);
		$id = $this->uri->segment(5);
		$where = array('dsuratkeluar_id' => $id);
		$delete = $this->edaran_model->delete_data('disposisi_suratkeluar', $where);
		if ($delete) {
			$this->session->set_flashdata('success', 'Data Berhasil Dihapus');
			redirect(site_url('suratkeluar/edaran/edit/'.$surat_id));
		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Dihapus');
			redirect(site_url('suratkeluar/edaran/edit/'.$surat_id));
		}
	}

}