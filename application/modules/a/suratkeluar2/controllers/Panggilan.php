<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class panggilan extends CI_Controller {

	function __construct() 
	{
		parent::__construct();
		if (empty($this->session->userdata('login'))) {
			$this->session->set_flashdata('access', 'Anda harus login terlebih dahulu!');
			redirect(site_url());
		}
		$this->load->model('panggilan_model');
	} 

	public function index()
	{
		$data['content'] = 'panggilan/panggilan_index';
		
		$tahun = $this->session->userdata('tahun');
		$opd_id = $this->session->userdata('opd_id');
		$jabatan_id = $this->session->userdata('jabatan_id');

		$data['panggilan'] = $this->panggilan_model->get_data($tahun,$opd_id,$jabatan_id)->result();
		
		$this->load->view('template', $data);
	}

	public function add()
	{
		$data['content'] = 'panggilan/panggilan_form';
		$data['kop'] = $this->db->get('kop_surat')->result();
		$data['kodesurat'] = $this->db->get('kode_surat')->result();
		$data['jabatan'] = $this->db->get('jabatan')->result();
		
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
			$insert = $this->panggilan_model->insert_data('eksternal_keluar', $data);

			if ($insert) {
				$this->session->set_flashdata('success', 'Eksternal Berhasil Dibuat');
				redirect(site_url('suratkeluar/panggilan/add'));
			}else{
				$this->session->set_flashdata('error', 'Eksternal Gagal Dibuat');
				redirect(site_url('suratkeluar/draft/add/'));
			}

		//Untuk menambahkan surat
		}else{

			$getID = $this->panggilan_model->get_id('surat_panggilan')->result();
			foreach ($getID as $key => $h) {
				$id = $h->id;
			}
			if (empty($id)) {
				$surat_id = 'PGL-1';
			}else{
				$urut = substr($id, 4)+1;
				$surat_id = 'PGL-'.$urut;
			}

			$file = $_FILES['lampiran_lain']['name'];

			if (empty($file)) {
				$data = array(
					'id' => $surat_id,
					'kop_id' => htmlentities($this->input->post('kop_id')), 
					'opd_id' => $this->session->userdata('opd_id'),
					'kodesurat_id' => htmlentities($this->input->post('kodesurat_id')), 
					'kepada_id' => $this->input->post('kepada_id'), 
					'tanggal' => htmlentities($this->input->post('tanggal')), 
					'nomor' => '',
					'sifat' => htmlentities($this->input->post('sifat')), 
					'lampiran' => htmlentities($this->input->post('lampiran')), 
					'hal' => htmlentities($this->input->post('hal')),  
					'lampiran_lain' => '',
					'kantor' => $this->input->post('kantor'),
					'hari' => $this->input->post('hari'),
					'tgl_acara' => htmlentities($this->input->post('tgl_acara')), 
					'pukul' => $this->input->post('pukul'),
					'tempat' => $this->input->post('tempat'),
					'menghadapkepada_id' => $this->input->post('menghadapkepada_id'),
					'alamat' => $this->input->post('alamat'),
					'untuk' => $this->input->post('untuk'),
				);
				$insert = $this->panggilan_model->insert_data('surat_panggilan', $data);
				if ($insert) {

					$datadraft = array(
						'surat_id' => $surat_id,
						'tanggal' => htmlentities($this->input->post('tanggal')), 
						'dibuat_id' => $this->session->userdata('jabatan_id'), 
						'penandatangan_id' => '',
						'verifikasi_id' => '', 
						'nama_surat' => 'Surat panggilan', 
					);
					$this->panggilan_model->insert_data('draft', $datadraft);

					$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
					redirect(site_url('suratkeluar/panggilan'));
				}else{
					$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
					redirect(site_url('suratkeluar/panggilan/add'));
				}
			}else{
	        	$ambext = explode(".",$file);
				$ekstensi = end($ambext);
				$nama_baru = date('YmdHis');
				$nama_file = $nama_baru.".".$ekstensi;	
				$config['upload_path'] = './assets/lampiransurat/panggilan/';
				$config['allowed_types'] = 'pdf';
				$config['file_name'] = $nama_file;
				$this->upload->initialize($config);

				if(!$this->upload->do_upload('lampiran_lain')){
					$this->session->set_flashdata('error','Upload File Gagal');
					redirect(site_url('suratkeluar/panggilan/add'));
				}else{
					$data = array(
						'id' => $surat_id,
						'kop_id' => htmlentities($this->input->post('kop_id')), 
						'opd_id' => $this->session->userdata('opd_id'),
						'kodesurat_id' => htmlentities($this->input->post('kodesurat_id')), 
						'kepada_id' => $this->input->post('kepada_id'), 
						'tanggal' => htmlentities($this->input->post('tanggal')), 
						'nomor' => '',
						'sifat' => htmlentities($this->input->post('sifat')), 
						'lampiran' => htmlentities($this->input->post('lampiran')), 
						'hal' => htmlentities($this->input->post('hal')),  
						'lampiran_lain' => '',
						'kantor' => $this->input->post('kantor'),
						'hari' => $this->input->post('hari'),
						'tgl_acara' => htmlentities($this->input->post('tgl_acara')), 
						'pukul' => $this->input->post('pukul'),
						'tempat' => $this->input->post('tempat'),
						'menghadapkepada_id' => $this->input->post('menghadapkepada_id'),
						'alamat' => $this->input->post('alamat'),
						'untuk' => $this->input->post('untuk'),
					);
					$insert = $this->panggilan_model->insert_data('surat_panggilan', $data);
					if ($insert) {

						$datadraft = array(
							'surat_id' => $surat_id, 
							'tanggal' => htmlentities($this->input->post('tanggal')), 
							'dibuat_id' => $this->session->userdata('jabatan_id'), 
							'penandatangan_id' => '',
							'verifikasi_id' => '', 
							'nama_surat' => 'Surat panggilan', 
						);
						$this->panggilan_model->insert_data('draft', $datadraft);

						$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
						redirect(site_url('suratkeluar/panggilan'));
					}else{
						$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
						redirect(site_url('suratkeluar/panggilan/add'));
					}
				}
			}

		}
	}

	public function edit()
	{
		$data['content'] = 'panggilan/panggilan_form';
		$data['kop'] = $this->db->get('kop_surat')->result();
		$data['kodesurat'] = $this->db->get('kode_surat')->result();
		$data['panggilan'] = $this->panggilan_model->edit_data($this->uri->segment(4), $this->session->userdata('opd_id'))->result();
		
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
			$insert = $this->panggilan_model->insert_data('eksternal_keluar', $data);

			if ($insert) {
				$this->session->set_flashdata('success', 'Eksternal Berhasil Dibuat');
				redirect(site_url('suratkeluar/panggilan/edit/'.$id));
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
				internal_eksternal('panggilan',$id,$jabatan_id,$eksternal_id);
			}

			$file = $_FILES['lampiran_lain']['name'];

			if (empty($file)) {
				$getQuery = $this->panggilan_model->edit_data('surat_panggilan', array('id' => $id))->result();
				foreach ($getQuery as $key => $h) {
					$fileLampiran = $h->lampiran_lain;
				}
				$data = array(
					'kop_id' => htmlentities($this->input->post('kop_id')), 
					'opd_id' => $this->session->userdata('opd_id'),
					'kodesurat_id' => htmlentities($this->input->post('kodesurat_id')), 
					'tanggal' => htmlentities($this->input->post('tanggal')), 
					'nomor' => '',
					'sifat' => htmlentities($this->input->post('sifat')), 
					'lampiran' => htmlentities($this->input->post('lampiran')), 
					'hal' => htmlentities($this->input->post('hal')),  
					'lampiran_lain' => '',
					'hari' => $this->input->post('hari'),
					'tgl' => $this->input->post('tgl'),
					'pukul' => $this->input->post('pukul'),
					'tempat' => $this->input->post('tempat'),
					'menghadapkepada' => $this->input->post('menghadapkepada'),
					'alamat' => $this->input->post('alamat'),
					'untuk' => $this->input->post('untuk'),						
				);
				$where = array('id' => $id);
				$update = $this->panggilan_model->update_data('surat_panggilan', $data, $where);
				if ($update) {

					$datadraft = array(
						'tanggal' => htmlentities($this->input->post('tanggal')), 
					);
					$wheredraft = array('surat_id' => $id);
					$this->panggilan_model->update_data('draft', $datadraft, $wheredraft);

					$this->session->set_flashdata('success', 'Surat Berhasil Diedit');
					
					$pembuatSurat = $this->db->get_where('draft', array('surat_id' => $id))->row_array();
					
					if ($pembuatSurat['dibuat_id'] == $this->session->userdata('jabatan_id')) {
						redirect(site_url('suratkeluar/panggilan'));
					}else{
						redirect(site_url('suratkeluar/draft'));
					}

				}else{
					$this->session->set_flashdata('error', 'Surat Gagal Diedit');
					redirect(site_url('suratkeluar/panggilan/edit/'.$id));
				}
			}else{
	        	$ambext = explode(".",$file);
				$ekstensi = end($ambext);
				$nama_baru = date('YmdHis');
				$nama_file = $nama_baru.".".$ekstensi;	
				$config['upload_path'] = './assets/lampiransurat/panggilan/';
				$config['allowed_types'] = 'pdf';
				$config['file_name'] = $nama_file;
				$this->upload->initialize($config);

				if(!$this->upload->do_upload('lampiran_lain')){
					$this->session->set_flashdata('error','Upload File Gagal');
					redirect(site_url('suratkeluar/panggilan/edit'));
				}else{
					$data = array(
						'kop_id' => htmlentities($this->input->post('kop_id')), 
						'opd_id' => $this->session->userdata('opd_id'),
						'kodesurat_id' => htmlentities($this->input->post('kodesurat_id')), 
						'tanggal' => htmlentities($this->input->post('tanggal')), 
						'nomor' => '',
						'sifat' => htmlentities($this->input->post('sifat')), 
						'lampiran' => htmlentities($this->input->post('lampiran')), 
						'hal' => htmlentities($this->input->post('hal')),  
						'lampiran_lain' => '',
						'hari' => $this->input->post('hari'),
						'tgl' => $this->input->post('tgl'),
						'pukul' => $this->input->post('pukul'),
						'tempat' => $this->input->post('tempat'),
						'menghadapkepada' => $this->input->post('menghadapkepada'),
						'alamat' => $this->input->post('alamat'),
						'untuk' => $this->input->post('untuk'),	
					);
					$where = array('id' => $id);
					$update = $this->panggilan_model->update_data('surat_panggilan', $data, $where);
					if ($update) {

						$datadraft = array( 
							'tanggal' => htmlentities($this->input->post('tanggal')), 
						);
						$wheredraft = array('surat_id' => $id);
						$this->panggilan_model->update_data('draft', $datadraft, $wheredraft);

						$this->session->set_flashdata('success', 'Surat Berhasil Diedit');
						
						if ($this->session->userdata('level') == 8) {
							redirect(site_url('suratkeluar/panggilan'));
						}else{
							redirect(site_url('suratkeluar/draft'));
						}
						
					}else{
						$this->session->set_flashdata('error', 'Surat Gagal Diedit');
						redirect(site_url('suratkeluar/panggilan/edit/'.$id));
					}
				}
			}

		}
	}

	public function delete()
	{
		$where = array('id' => $this->uri->segment(4));
		$delete = $this->panggilan_model->delete_data('surat_panggilan', $where);
		if ($delete) {
			$whereDis = array('surat_id' => $this->uri->segment(4));
			$this->panggilan_model->delete_data('draft', $whereDis);
			$this->panggilan_model->delete_data('verifikasi', $whereDis);
			$this->panggilan_model->delete_data('disposisi_suratkeluar', $whereDis);
			
			$this->session->set_flashdata('success', 'Surat Berhasil Dihapus');
			redirect(site_url('suratkeluar/panggilan'));
		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Dihapus');
			redirect(site_url('suratkeluar/panggilan'));
		}
	}

	public function delete_kepada()
	{
		$surat_id = $this->uri->segment(4);
		$id = $this->uri->segment(5);
		$where = array('dsuratkeluar_id' => $id);
		$delete = $this->panggilan_model->delete_data('disposisi_suratkeluar', $where);
		if ($delete) {
			$this->session->set_flashdata('success', 'Data Berhasil Dihapus');
			redirect(site_url('suratkeluar/panggilan/edit/'.$surat_id));
		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Dihapus');
			redirect(site_url('suratkeluar/panggilan/edit/'.$surat_id));
		}
	}	

}