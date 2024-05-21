<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notadinas extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if (empty($this->session->userdata('login'))) {
			$this->session->set_flashdata('access', 'Anda harus login terlebih dahulu!');
			redirect(site_url());
		}elseif($this->session->userdata('level') == 4 || $this->session->userdata('level') == 2 || $this->session->userdata('level') == 3 || $this->session->userdata('level') == 18){
		    $this->session->set_flashdata('access', 'Session Tidak Sesuai, Silahkan Login Kembali');
			redirect(site_url());
		}
		$this->load->model('notadinas_model');
	}

	public function index()
	{
		$data['content'] = 'notadinas/notadinas_index';
		
		$tahun = $this->session->userdata('tahun');
		$opd_id = $this->session->userdata('opd_id');
		$jabatan_id = $this->session->userdata('jabatan_id');

		$data['notadinas'] = $this->notadinas_model->get_data($tahun,$opd_id,$jabatan_id)->result();
		
		$this->load->view('template', $data);
	}

	public function add() 
	{
		$data['content'] = 'notadinas/notadinas_form';
		$data['kop']=$this->db->get('kop_surat')->result();
		$data['kodesurat'] = $this->db->get('kode_surat')->result();
        // :Penghapusan field kepada internal dan eksternal
		$opdid=$this->session->userdata('opd_id');
		$data['jabatan'] = $this->db->query("SELECT a.nama,b.nama_jabatan, b.jabatan_id FROM aparatur a LEFT JOIN jabatan b ON b.jabatan_id=a.jabatan_id LEFT JOIN users c ON c.aparatur_id=a.aparatur_id LEFT JOIN level d ON d.level_id=c.level_id WHERE d.level_id IN (5,6,7,8,9,10,11,12,13,14,15,16,17,19,20,21,22,23) AND a.statusaparatur='Aktif' AND a.opd_id='$opdid' ORDER BY a.level_id ASC")->result();
        // Penghapusan field kepada internal dan eksternal

		$this->load->view('template', $data);
	}

	public function insert()
	{
		$kopId=$this->input->post('kop_id');
		//untuk menambahkan data eksternal
		if (isset($_POST['simpan'])) {
			
			$getEksID = $this->notadinas_model->get_id('eksternal_keluar')->result();
			foreach ($getEksID as $key => $ek) {
				$idEKs = $ek->id;
			}
			if (empty($idEKs)) {
				$eksternalkeluar_id = 'EKS-1';
			}else{
				$urut = substr($idEKs, 4)+1;
				$eksternalkeluar_id = 'EKS-'.$urut;
			}
			
			$data = array(
				'id' => $eksternalkeluar_id,
				'opd_id' => $this->session->userdata('opd_id'), 
				'nama' => $this->input->post('nama'), 
				'email' => $this->input->post('email'),
				'alamat_eksternal' => $this->input->post('alamat_eksternal'),
				'tempat' => $this->input->post('tempat'), 
			);
			//form validation eksternal nota dinas
			$this->form_validation->set_rules('nama','Nama Eksternal', 'required');
        	$this->form_validation->set_rules('email','Email Eksternal', 'required');
			$this->form_validation->set_rules('required', '%s masih kosong, silakan diisikan');

			if($this->form_validation->run()===FALSE) {

			$this->session->set_flashdata('error', 'Nama dan email eksternal tidak boleh kosong');
			
			redirect(site_url('suratkeluar/notadinas/add'));
			
			}
			else {

			$insert = $this->notadinas_model->insert_data('eksternal_keluar', $data);

			if ($insert) {
				$this->session->set_flashdata('success', 'Eksternal Berhasil Dibuat');
				redirect(site_url('suratkeluar/notadinas/add'));
			}else{
				$this->session->set_flashdata('error', 'Eksternal Gagal Dibuat');
				redirect(site_url('suratkeluar/draft/add/'));
			}

			}

		//Untuk menambahkan surat
		}else{
			
			$getID = $this->notadinas_model->get_id('surat_notadinas')->result();
			foreach ($getID as $key => $h) {
				$id = $h->id;
			}
			if (empty($id)) {
				$surat_id = 'NODIN-1';
			}else{
				$urut = substr($id, 6)+1;
				$surat_id = 'NODIN-'.$urut;
			}
			
			$file = $_FILES['lampiran_lain']['name'];

			if (empty($file)) {
				$data = array(
					'id' => $surat_id,
					'opd_id' => $this->session->userdata('opd_id'),
					'kop_id' => $this->input->post('kop_id'),
					'kodesurat_id' => $this->input->post('kodesurat_id'), 
					'tanggal' => htmlentities($this->input->post('tanggal')),
					'nomor' => '',
					'sifat' => $this->input->post('sifat'), 
					'lampiran' => $this->input->post('lampiran'), 
					'hal' => $this->input->post('hal'), 
					'tembusan' => '', 
					'lampiran_lain' => '',
					'isi' => $this->input->post('isi'),  
					'catatan' => $this->input->post('catatan'),  
				);
				//form validation notadinas [@dam|E-Gov 10.04.2022]
				$this->load->library('form_validation');	
				
				$rules =
				    [
				        [
				        'field' => 'kop_id',  
				        'label' => 'Kop Surat',
				        'rules' => 'required'],
				        
				        [
				        'field' => 'sifat',  
				        'label' => 'Sifat Surat',
				        'rules' => 'required'],
				        
				        [
				        'field' => 'lampiran',  
				        'label' => 'Lampiran Surat',
				        'rules' => 'required'],
				        
				        [
				        'field' => 'hal',  
				        'label' => 'Perihal',
				        'rules' => 'required'],
				        
				        [
				        'field' => 'isi',  
				        'label' => 'Isi Surat',
				        'rules' => 'required']
				    ];
				$this->form_validation->set_rules($rules);
				$this->form_validation->set_message('required', '%s masih kosong, silakan diisi');				

				if($this->form_validation->run()===FALSE) {
				
				//set value
				$this->session->set_flashdata('value_kop_id', set_value('kop_id', kop_id));
				$this->session->set_flashdata('value_tujuan', set_value('', '*Silakan pilih kembali tujuan internal-eksternal Pemkot'));
				$this->session->set_flashdata('value_jabatan_tujuan', set_value('', '*Silakan pilih kembali jabatan tujuan nota dinas'));
				$this->session->set_flashdata('value_sifat', set_value('sifat', sifat));
				$this->session->set_flashdata('value_lampiran', set_value('lampiran', lampiran));
				$this->session->set_flashdata('value_hal', set_value('hal', hal)); 
				$this->session->set_flashdata('value_isi', set_value('isi', isi));
				$this->session->set_flashdata('value_catatan', set_value('catatan', catatan));
				
				//error
				$this->session->set_flashdata('kop_id', form_error('kop_id'));
				$this->session->set_flashdata('sifat', form_error('sifat'));
				$this->session->set_flashdata('lampiran', form_error('lampiran'));
				$this->session->set_flashdata('hal', form_error('hal'));
				$this->session->set_flashdata('isi', form_error('isi'));
				
				redirect(site_url('suratkeluar/notadinas/add'));
				
				}
				else {
	
				$insert = $this->notadinas_model->insert_data('surat_notadinas', $data);
				if ($insert) {
					
					//pengiriman surat internal atau eksternal
					$jabatan_id = $this->input->post('jabatan_id');
					$eksternal_id = $this->input->post('eksternal_id');

					//proses input internal eksternal
					internal_eksternal('notadinas',$surat_id,$jabatan_id,$eksternal_id);
					
					//pengiriman tembusan internal atau eksternal
		            $tembusan_id = $this->input->post('tembusan_id');
		            $tembusaneks_id = $this->input->post('tembusaneks_id');
			
		            //proses input tembusan internal atau eksternal
		            internal_eksternal_tembusan('notadinas',$surat_id,$tembusan_id,$tembusaneks_id);

					$datadraft = array(
						'surat_id' => $surat_id,
						'kopId' => $kopId,
						'tanggal' => htmlentities($this->input->post('tanggal')), 
						'dibuat_id' => $this->session->userdata('jabatan_id'), 
						'penandatangan_id' => '',
						'verifikasi_id' => '', 
						'nama_surat' => 'Nota Dinas', 
					);
					$this->notadinas_model->insert_data('draft', $datadraft);

					$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
					redirect(site_url('suratkeluar/notadinas'));
				}else{
					$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
					redirect(site_url('suratkeluar/notadinas/add'));
				}
				}
			}else{
	        	$ambext = explode(".",$file);
				$ekstensi = end($ambext);
				$date = date('his');
				$nama_baru = "Lampiran-Surat-Notadinas-No-".$surat_id."-".$date;
				$nama_file = $nama_baru.".".$ekstensi;	
				$config['upload_path'] = './assets/lampiransurat/notadinas/';
				$config['allowed_types'] = 'pdf';
				$config['file_name'] = $nama_file;
				$this->upload->initialize($config);

				if(!$this->upload->do_upload('lampiran_lain')){
					$this->session->set_flashdata('error','Upload File Gagal');
					redirect(site_url('suratkeluar/notadinas/add'));
				}else{
					$data = array(
						'id' => $surat_id,
						'opd_id' => $this->session->userdata('opd_id'), 
						'kodesurat_id' => htmlentities($this->input->post('kodesurat_id')), 
						'kop_id' => $this->input->post('kop_id'),
						'tanggal' => htmlentities($this->input->post('tanggal')), 
						'nomor' => '',
						'sifat' => htmlentities($this->input->post('sifat')), 
						'lampiran' => htmlentities($this->input->post('lampiran')), 
						'hal' => htmlentities($this->input->post('hal')), 
						'tembusan' => '', 
						'lampiran_lain' => $nama_file, 
						'isi' => $this->input->post('isi'), 
						'catatan' => $this->input->post('catatan'), 
					);
					//form validation notadinas [@dam|E-Gov 10.04.2022]
					$this->load->library('form_validation');	
				
    				$rules =
    				    [
    				        [
    				        'field' => 'kop_id',  
    				        'label' => 'Kop Surat',
    				        'rules' => 'required'],
    				        
    				        [
    				        'field' => 'sifat',  
    				        'label' => 'Sifat Surat',
    				        'rules' => 'required'],
    				        
    				        [
    				        'field' => 'lampiran',  
    				        'label' => 'Lampiran Surat',
    				        'rules' => 'required'],
    				        
    				        [
    				        'field' => 'hal',  
    				        'label' => 'Perihal',
    				        'rules' => 'required'],
    				        
    				        [
    				        'field' => 'isi',  
    				        'label' => 'Isi Surat',
    				        'rules' => 'required']
    				    ];
    				$this->form_validation->set_rules($rules);
    				$this->form_validation->set_message('required', '%s masih kosong, silakan diisi');				
    
    				if($this->form_validation->run()===FALSE) {
    				
    				//set value
    				$this->session->set_flashdata('value_kop_id', set_value('kop_id', kop_id));
    				$this->session->set_flashdata('value_tujuan', set_value('', '*Silakan pilih kembali tujuan internal-eksternal Pemkot'));
    				$this->session->set_flashdata('value_jabatan_tujuan', set_value('', '*Silakan pilih kembali jabatan tujuan nota dinas'));
    				$this->session->set_flashdata('value_sifat', set_value('sifat', sifat));
    				$this->session->set_flashdata('value_lampiran', set_value('lampiran', lampiran));
    				$this->session->set_flashdata('value_hal', set_value('hal', hal)); 
    				$this->session->set_flashdata('value_isi', set_value('isi', isi));
    				$this->session->set_flashdata('value_catatan', set_value('catatan', catatan));
    				$this->session->set_flashdata('value_lain', set_value('', '* Silakan upload kembali file lampiran'));
    				
    				//error
    				$this->session->set_flashdata('kop_id', form_error('kop_id'));
    				$this->session->set_flashdata('sifat', form_error('sifat'));
    				$this->session->set_flashdata('lampiran', form_error('lampiran'));
    				$this->session->set_flashdata('hal', form_error('hal'));
    				$this->session->set_flashdata('isi', form_error('isi'));
    				
    				redirect(site_url('suratkeluar/notadinas/add'));
    				
    				}
    				else {

					$insert = $this->notadinas_model->insert_data('surat_notadinas', $data);
					if ($insert) {
						
						//pengiriman surat internal atau eksternal
						$jabatan_id = $this->input->post('jabatan_id');
						$eksternal_id = $this->input->post('eksternal_id');

						//proses input internal eksternal
						internal_eksternal('notadinas',$surat_id,$jabatan_id,$eksternal_id);
						
						//pengiriman tembusan internal atau eksternal
			            $tembusan_id = $this->input->post('tembusan_id');
			            $tembusaneks_id = $this->input->post('tembusaneks_id');
				
			            //proses input tembusan internal atau eksternal
			            internal_eksternal_tembusan('notadinas',$surat_id,$tembusan_id,$tembusaneks_id);

						$datadraft = array(
							'surat_id' => $surat_id, 
							'kopId' => $kopId, 
							'tanggal' => htmlentities($this->input->post('tanggal')), 
							'dibuat_id' => $this->session->userdata('jabatan_id'), 
							'penandatangan_id' => '',
							'verifikasi_id' => '', 
							'nama_surat' => 'Nota Dinas', 
						);
						$this->notadinas_model->insert_data('draft', $datadraft);

						$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
						redirect(site_url('suratkeluar/notadinas'));
					}else{
						$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
						redirect(site_url('suratkeluar/notadinas/add'));
					}
					}
				}
			}

		}
	}

	public function edit()
	{
		$data['content'] = 'notadinas/notadinas_form';
		$data['kodesurat'] = $this->db->get('kode_surat')->result();
		$data['kop'] = $this->db->get('kop_surat')->result();
        // Penghapusan field kepada internal dan eksternal
		$opdid=$this->session->userdata('opd_id');
		$data['jabatan'] = $this->db->query("SELECT a.nama,b.nama_jabatan, b.jabatan_id FROM aparatur a LEFT JOIN jabatan b ON b.jabatan_id=a.jabatan_id LEFT JOIN users c ON c.aparatur_id=a.aparatur_id LEFT JOIN level d ON d.level_id=c.level_id WHERE d.level_id IN (5,6,7,8,9,10,11,12,13,14,15,16,17,19,20,21,22,23) AND a.statusaparatur='Aktif' AND a.opd_id='$opdid' ORDER BY b.jabatan_id ASC")->result();
        // Penghapusan field kepada internal dan eksternal
		$data['notadinas'] = $this->notadinas_model->edit_data($this->uri->segment(4), $this->session->userdata('opd_id'))->result();
		
		$this->load->view('template', $data);
	}

	public function update()
	{
		$id = $this->input->post('id');
		
		//Untuk menambahkan data eksternal 
		if (isset($_POST['simpan'])) {
			
			$getEksID = $this->notadinas_model->get_id('eksternal_keluar')->result();
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
				'alamat_eksternal' => $this->input->post('alamat_eksternal'),
				'tempat' => $this->input->post('tempat'), 
			);
			//form validation eksternal nota dinas
			$this->form_validation->set_rules('nama','Nama Eksternal', 'required');
        		$this->form_validation->set_rules('email','Email Eksternal', 'required');
			$this->form_validation->set_rules('required', '%s masih kosong, silakan diisikan');

			if($this->form_validation->run()===FALSE) {

			$this->session->set_flashdata('error', 'Nama dan email eksternal tidak boleh kosong');
			
			redirect(site_url('suratkeluar/notadinas/add'));
			
			}
			else {

			$insert = $this->notadinas_model->insert_data('eksternal_keluar', $data);

			if ($insert) {
				$this->session->set_flashdata('success', 'Eksternal Berhasil Dibuat');
				redirect(site_url('suratkeluar/notadinas/add'));
			}else{
				$this->session->set_flashdata('error', 'Eksternal Gagal Dibuat');
				redirect(site_url('suratkeluar/draft/add/'));
			}

			}

		//Untuk mengedit surat
		}else{
			//pengiriman surat internal atau eksternal
			$jabatan_id = $this->input->post('jabatan_id');
			$eksternal_id = $this->input->post('eksternal_id');
			if (!empty($jabatan_id) OR !empty($eksternal_id)) {
				internal_eksternal('notadinas',$id,$jabatan_id,$eksternal_id);
			}
			
			//pengiriman tembusan internal atau eksternal
		    $tembusan_id = $this->input->post('tembusan_id');
	    	$tembusaneks_id = $this->input->post('tembusaneks_id');

		    if (!empty($tembusan_id) OR !empty($tembusaneks_id)) {
		    	internal_eksternal_tembusan('notadinas',$id,$tembusan_id,$tembusaneks_id);
	    	}

			$file = $_FILES['lampiran_lain']['name'];

			if (empty($file)) {
				$getQuery = $this->notadinas_model->edit_data($id, $this->session->userdata('opd_id'))->result();
				foreach ($getQuery as $key => $h) {
					$fileLampiran = $h->lampiran_lain;
				}
				$data = array(
					'opd_id' => htmlentities($this->session->userdata('opd_id')),
					'kop_id' => htmlentities($this->input->post('kop_id')),
					'kodesurat_id' => htmlentities($this->input->post('kodesurat_id')),
					'tanggal' => htmlentities($this->input->post('tanggal')),
					'sifat' => htmlentities($this->input->post('sifat')), 
					'lampiran' => htmlentities($this->input->post('lampiran')), 
					'hal' => htmlentities($this->input->post('hal')), 
					'tembusan' => '', 
					'lampiran_lain' => $fileLampiran,
					'isi' => $this->input->post('isi'), 
					'catatan' => $this->input->post('catatan'), 
				);
				$where = array('id' => $id);
				$update = $this->notadinas_model->update_data('surat_notadinas', $data, $where);
				if ($update) {

					$datadraft = array(
						'tanggal' => htmlentities($this->input->post('tanggal')), 
						'kopId' => $this->input->post('kop_id'),  
					);
					$wheredraft = array('surat_id' => $id);
					$this->notadinas_model->update_data('draft', $datadraft, $wheredraft);

					$this->session->set_flashdata('success', 'Surat Berhasil Diedit');
					
					$pembuatSurat = $this->db->get_where('draft', array('surat_id' => $id))->row_array();
					
					if ($pembuatSurat['dibuat_id'] == $this->session->userdata('jabatan_id')) {
						redirect(site_url('suratkeluar/notadinas'));
					}else{
						redirect(site_url('suratkeluar/draft'));
					}

				}else{
					$this->session->set_flashdata('error', 'Surat Gagal Diedit');
					redirect(site_url('suratkeluar/notadinas/edit/'.$id));
				}
			}else{
	        	$query = $this->db->query("SELECT * FROM surat_notadinas WHERE id='$id'")->row_array();
	        	$ambext = explode(".",$file);
				$ekstensi = end($ambext);
				$date = date('his');
				$nama_baru = "Lampiran-Surat-NotaDinas-No-".$id."-".$date;
				$nama_file = $nama_baru.".".$ekstensi;	
				$config['upload_path'] = './assets/lampiransurat/notadinas/';
				$config['allowed_types'] = 'pdf';
				$config['max_size']=20000;
				$config['file_name'] = $nama_file;
				$this->upload->initialize($config);

				if(!$this->upload->do_upload('lampiran_lain')){
					$this->session->set_flashdata('error','Upload File Gagal');
					$error = array('error' => $this->upload->display_errors());
					// var_dump($error);
					// die;
					redirect(site_url('suratkeluar/notadinas/edit'));
				}else{
					@unlink('./assets/lampiransurat/notadinas/'.$query['lampiran_lain']);
					$data = array(
						'opd_id' => $this->session->userdata('opd_id'),
						'kop_id' => htmlentities($this->input->post('kop_id')),
						'kodesurat_id' => htmlentities($this->input->post('kodesurat_id')),
						'tanggal' => htmlentities($this->input->post('tanggal')), 
						// 'kepada' =>$this->input->post('kepada'), 
						// 'dari' =>$this->input->post('dari'), 
						'sifat' => htmlentities($this->input->post('sifat')), 
						'lampiran' => htmlentities($this->input->post('lampiran')), 
						'hal' => htmlentities($this->input->post('hal')), 
						'tembusan' => '', 
						'lampiran_lain' => $nama_file,
						'isi' => $this->input->post('isi'), 	
						'catatan' => $this->input->post('catatan'), 	
					);
					$where = array('id' => $id);
					$update = $this->notadinas_model->update_data('surat_notadinas', $data, $where);
					if ($update) {

						$datadraft = array( 
							'tanggal' => htmlentities($this->input->post('tanggal')), 
							'kopId' => $this->input->post('kop_id'), 
						);
						$wheredraft = array('surat_id' => $id);
						$this->notadinas_model->update_data('draft', $datadraft, $wheredraft);

						$this->session->set_flashdata('success', 'Surat Berhasil Diedit');
						
						$pembuatSurat = $this->db->get_where('draft', array('surat_id' => $id))->row_array();
						
						if ($pembuatSurat['dibuat_id'] == $this->session->userdata('jabatan_id')) {
							redirect(site_url('suratkeluar/notadinas'));
						}else{
							redirect(site_url('suratkeluar/draft'));
						}
						
					}else{
						$this->session->set_flashdata('error', 'Surat Gagal Diedit');
						redirect(site_url('suratkeluar/notadinas/edit/'.$id));
					}
				}
			}

		}
	}

	public function delete()
	{
		$where = array('id' => $this->uri->segment(4));
		$id = $this->uri->segment(4);
		$query = $this->db->query("SELECT * FROM surat_notadinas WHERE id='$id'")->row_array();
		@unlink('./assets/lampiransurat/notadinas/'.$query['lampiran_lain']);
		$delete = $this->notadinas_model->delete_data('surat_notadinas', $where);
		if ($delete) {
			$whereDis = array('surat_id' => $this->uri->segment(4));
			$this->notadinas_model->delete_data('draft', $whereDis);
			$this->notadinas_model->delete_data('verifikasi', $whereDis);
			$this->notadinas_model->delete_data('disposisi_suratkeluar', $whereDis);
			$this->notadinas_model->delete_data('tembusan_surat', $whereDis);
			
			$this->session->set_flashdata('success', 'Surat Berhasil Dihapus');
			redirect(site_url('suratkeluar/notadinas'));
		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Dihapus');
			redirect(site_url('suratkeluar/notadinas'));
		}
	}

	public function delete_kepada()
	{
		$surat_id = $this->uri->segment(4);
		$id = $this->uri->segment(5);
		$where = array('dsuratkeluar_id' => $id);
		$delete = $this->notadinas_model->delete_data('disposisi_suratkeluar', $where);
		if ($delete) {
			$this->session->set_flashdata('success', 'Data Berhasil Dihapus');
			redirect(site_url('suratkeluar/notadinas/edit/'.$surat_id));
		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Dihapus');
			redirect(site_url('suratkeluar/notadinas/edit/'.$surat_id));
		}
	}

	public function delete_tembusan()
	{
		$surat_id = $this->uri->segment(4);
		$id = $this->uri->segment(5);
		$where = array('tembusansurat_id' => $id);
		$delete = $this->notadinas_model->delete_data('tembusan_surat', $where);
		if ($delete) {
			$this->session->set_flashdata('success', 'Data Tembusan Berhasil Dihapus');
			redirect(site_url('suratkeluar/notadinas/edit/'.$surat_id));
		}else{
			$this->session->set_flashdata('error', 'Data Tembusan Gagal Dihapus');
			redirect(site_url('suratkeluar/notadinas/edit/'.$surat_id));
		}
	}

}