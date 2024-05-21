<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lampiran extends CI_Controller {

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
		$this->load->model('lampiran_model');
	} 

	public function index()
	{
		$data['content'] = 'lampiran/lampiran_index';
		
		$tahun = $this->session->userdata('tahun');
		$opd_id = $this->session->userdata('opd_id');
		$jabatan_id = $this->session->userdata('jabatan_id');

		$data['lampiran'] = $this->lampiran_model->get_data($tahun,$opd_id,$jabatan_id)->result();
		
		$this->load->view('template', $data);
	}

	public function add()
	{
		$data['content'] = 'lampiran/lampiran_form';
		
		$this->load->view('template', $data);
	}

	public function insert()
	{
		//untuk menambahkan data eksternal
		if (isset($_POST['simpan'])) {
			
			$getEksID = $this->lampiran_model->get_id('eksternal_keluar')->result();
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
				'alamat_eksternal' => $this->input->post('alamat_eksternal'),
				'tempat' => $this->input->post('tempat'), 
			);
			//form validation eksternal lampiran
			$this->form_validation->set_rules('nama','Nama Eksternal', 'required');
        	$this->form_validation->set_rules('email','Email Eksternal', 'required');
			$this->form_validation->set_rules('required', '%s masih kosong, silakan diisikan');

			if($this->form_validation->run()===FALSE) {

			$this->session->set_flashdata('error', 'Nama dan email eksternal tidak boleh kosong');
			
			redirect(site_url('suratkeluar/lampiran/add'));
			
			}
			else {

			$insert = $this->lampiran_model->insert_data('eksternal_keluar', $data);

			if ($insert) {
				$this->session->set_flashdata('success', 'Eksternal Berhasil Dibuat');
				redirect(site_url('suratkeluar/lampiran/add'));
			}else{
				$this->session->set_flashdata('error', 'Eksternal Gagal Dibuat');
				redirect(site_url('suratkeluar/draft/add/'));
			}

			}

		//Untuk menambahkan surat
		}else{

			$getID = $this->lampiran_model->get_id('surat_lampiran')->result();
			foreach ($getID as $key => $h) {
				$id = $h->id;
			}
			if (empty($id)) {
				$surat_id = 'LMP-1';
			}else{
				$urut = substr($id, 4)+1;
				$surat_id = 'LMP-'.$urut;
			}

			$file = $_FILES['lampiran_lain']['name'];

			if (empty($file)) {
				$data = array(
					'id' => $surat_id,
                    'opd_id' => $this->session->userdata('opd_id'),
					'jenissurat' => htmlentities($this->input->post('jenissurat')),
					'perihal' => $this->input->post('perihal'),
					'tanggal' => $this->input->post('tanggal'),
					'keterangan' => htmlentities($this->input->post('keterangan')), 
					'lampiran_lain' =>'', 
				);
				//form validation lampiran [@dam|E-Gov 11.04.2022]
				$this->load->library('form_validation');	
				
				$rules =
				    [
				        [
				        'field' => 'jenissurat',  
				        'label' => 'Jenis Surat',
				        'rules' => 'required'],
				        
				        [
				        'field' => 'perihal',  
				        'label' => 'Perihal',
				        'rules' => 'required'],
				        
				        [
    				    'field' => 'lampiran_lain',  
    				    'label' => 'Lampiran',
    				    'rules' => 'required'],
				        
				        [
				        'field' => 'keterangan',  
				        'label' => 'Keterangan',
				        'rules' => 'required']
				    ];
				$this->form_validation->set_rules($rules);
				$this->form_validation->set_message('required', '%s masih kosong, silakan diisi');				

				if($this->form_validation->run()===FALSE) {
				
				//set value
				$this->session->set_flashdata('value_jenis_surat', set_value('jenissurat', jenissurat));
				$this->session->set_flashdata('value_tujuan', set_value('', '*Silakan pilih kembali tujuan internal-eksternal Pemkot'));
				$this->session->set_flashdata('value_perihal', set_value('perihal', perihal));
				$this->session->set_flashdata('value_keterangan', set_value('keterangan', keterangan));
				
				//error
				$this->session->set_flashdata('jenissurat', form_error('jenissurat'));
				$this->session->set_flashdata('perihal', form_error('perihal'));
				$this->session->set_flashdata('lampiran_lain', form_error('lampiran_lain'));
				$this->session->set_flashdata('keterangan', form_error('keterangan'));
				
				redirect(site_url('suratkeluar/lampiran/add'));
				
				}
				else {

				$insert = $this->lampiran_model->insert_data('surat_lampiran', $data);

				if ($insert) {

					//pengiriman surat internal atau eksternal
					$jabatan_id = $this->input->post('jabatan_id');
					$eksternal_id = $this->input->post('eksternal_id');

					//proses input internal eksternal
					internal_eksternal('lampiran',$surat_id,$jabatan_id,$eksternal_id);
					
					//pengiriman tembusan internal atau eksternal
	            	$tembusan_id = $this->input->post('tembusan_id');
		            $tembusaneks_id = $this->input->post('tembusaneks_id');
			
		            //proses input tembusan internal atau eksternal
		            internal_eksternal_tembusan('lampiran',$surat_id,$tembusan_id,$tembusaneks_id);

					$datadraft = array(
						'surat_id' => $surat_id,
						'tanggal' => htmlentities($this->input->post('tanggal')), 
						'dibuat_id' => $this->session->userdata('jabatan_id'), 
						'penandatangan_id' => '',
						'verifikasi_id' => '', 
						'nama_surat' => 'Surat lampiran', 
					);
					$this->lampiran_model->insert_data('draft', $datadraft);

					$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
					redirect(site_url('suratkeluar/lampiran'));
				}else{
					$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
					redirect(site_url('suratkeluar/lampiran/add'));
				}
				}
			}else{
	        	$ambext = explode(".",$file);
				$ekstensi = end($ambext);
				$date = date('his');
			    $nama_baru = "Lampiran-Surat-Lampiran-No-".$surat_id."-".$date;
				$nama_file = $nama_baru.".".$ekstensi;	
				$config['upload_path'] = './assets/lampiransurat/lampiran/';
				//$config['allowed_types'] = 'docx';
				$config['allowed_types'] = 'docx|doc';
				$config['file_name'] = $nama_file;
				$this->upload->initialize($config);

				if(!$this->upload->do_upload('lampiran_lain')){
					$this->session->set_flashdata('error','Upload File Gagal');
					redirect(site_url('suratkeluar/lampiran/add'));
				}else{
					$data = array(
						'id' => $surat_id,
                       				'opd_id' => $this->session->userdata('opd_id'),
                        			'jenissurat' => htmlentities($this->input->post('jenissurat')),
                        			'perihal' => $this->input->post('perihal'),
                        			'tanggal' => $this->input->post('tanggal'),
                        			'keterangan' => htmlentities($this->input->post('keterangan')), 
                        			'lampiran_lain' =>$nama_file, 
					);
					//form validation lampiran [@dam|E-Gov 11.04.2022]
					$this->load->library('form_validation');	
				
    				$rules =
    				    [
    				        [
    				        'field' => 'jenissurat',  
    				        'label' => 'Jenis Surat',
    				        'rules' => 'required'],
    				        
    				        [
    				        'field' => 'perihal',  
    				        'label' => 'Perihal',
    				        'rules' => 'required'],
    				        
    				        [
    				        'field' => 'keterangan',  
    				        'label' => 'Keterangan',
    				        'rules' => 'required']
    				    ];
    				$this->form_validation->set_rules($rules);
    				$this->form_validation->set_message('required', '%s masih kosong, silakan diisi');				
    
    				if($this->form_validation->run()===FALSE) {
    				
    				//set value
    				$this->session->set_flashdata('value_jenis_surat', set_value('jenissurat', jenissurat));
    				$this->session->set_flashdata('value_tujuan', set_value('', '*Silakan pilih kembali tujuan internal-eksternal Pemkot'));
    				$this->session->set_flashdata('value_perihal', set_value('perihal', perihal));
    				$this->session->set_flashdata('value_keterangan', set_value('keterangan', keterangan));
    				$this->session->set_flashdata('lampiran_lain', set_value('', '* Silakan upload kembali file lampiran'));
    				
    				//error
    				$this->session->set_flashdata('jenissurat', form_error('jenissurat'));
    				$this->session->set_flashdata('perihal', form_error('perihal'));
    				$this->session->set_flashdata('keterangan', form_error('keterangan'));
    				
    				redirect(site_url('suratkeluar/lampiran/add'));
    				
    				}
    				else {

					$insert = $this->lampiran_model->insert_data('surat_lampiran', $data);
					if ($insert) {
						
						//pengiriman surat internal atau eksternal
						$jabatan_id = $this->input->post('jabatan_id');
						$eksternal_id = $this->input->post('eksternal_id');

						//proses input internal eksternal
						internal_eksternal('lampiran',$surat_id,$jabatan_id,$eksternal_id);
						
						//pengiriman tembusan internal atau eksternal
		            	$tembusan_id = $this->input->post('tembusan_id');
			            $tembusaneks_id = $this->input->post('tembusaneks_id');
				
			            //proses input tembusan internal atau eksternal
			            internal_eksternal_tembusan('lampiran',$surat_id,$tembusan_id,$tembusaneks_id);
						
						$datadraft = array(
							'surat_id' => $surat_id, 
							'tanggal' => htmlentities($this->input->post('tanggal')), 
							'dibuat_id' => $this->session->userdata('jabatan_id'), 
							'penandatangan_id' => '',
							'verifikasi_id' => '', 
							'nama_surat' => 'Surat lampiran', 
						);
						$this->lampiran_model->insert_data('draft', $datadraft);

						$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
						redirect(site_url('suratkeluar/lampiran'));
					}else{
						$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
						redirect(site_url('suratkeluar/lampiran/add'));
					}
					}
				}
			}
 
		}
	}

	public function edit()
	{
		$data['content'] = 'lampiran/lampiran_form';
		$data['lampiran'] = $this->lampiran_model->edit_data($this->uri->segment(4), $this->session->userdata('opd_id'))->result();
		
		$this->load->view('template', $data);
	}

	public function update()
	{
		$id = $this->input->post('id');

		//Untuk menambahkan data eksternal 
		if (isset($_POST['simpan'])) {
			
			$getEksID = $this->lampiran_model->get_id('eksternal_keluar')->result();
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
				'alamat_eksternal' => $this->input->post('alamat_eksternal'),
				'tempat' => $this->input->post('tempat'), 
			);
			//form validation eksternal lampiran
			$this->form_validation->set_rules('nama','Nama Eksternal', 'required');
        	$this->form_validation->set_rules('email','Email Eksternal', 'required');
			$this->form_validation->set_rules('required', '%s masih kosong, silakan diisikan');

			if($this->form_validation->run()===FALSE) {

			$this->session->set_flashdata('error', 'Nama dan email eksternal tidak boleh kosong');
			
			redirect(site_url('suratkeluar/lampiran/add'));
			
			}
			else {

			$insert = $this->lampiran_model->insert_data('eksternal_keluar', $data);

			if ($insert) {
				$this->session->set_flashdata('success', 'Eksternal Berhasil Dibuat');
				redirect(site_url('suratkeluar/lampiran/add'));
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
				internal_eksternal('lampiran',$id,$jabatan_id,$eksternal_id);
			}
			
			//pengiriman tembusan internal atau eksternal
		    $tembusan_id = $this->input->post('tembusan_id');
		    $tembusaneks_id = $this->input->post('tembusaneks_id');

		    if (!empty($tembusan_id) OR !empty($tembusaneks_id)) {
		    	internal_eksternal_tembusan('lampiran',$id,$tembusan_id,$tembusaneks_id);
	    	}

			$file = $_FILES['lampiran_lain']['name'];

			if (empty($file)) {
				$getQuery = $this->lampiran_model->edit_data('surat_lampiran', array('id' => $id))->result();
				foreach ($getQuery as $key => $h) {
					$fileLampiran = $h->lampiran_lain;
				}
				$data = array(
                    'opd_id' => $this->session->userdata('opd_id'),
                    'jenissurat' => htmlentities($this->input->post('jenissurat')),
                    'perihal' => $this->input->post('perihal'),
                    'tanggal' => $this->input->post('tanggal'),
                    'keterangan' => htmlentities($this->input->post('keterangan')), 
                    // 'lampiran_lain' =>$fileLampiran,  
				);
				$where = array('id' => $id);
				$update = $this->lampiran_model->update_data('surat_lampiran', $data, $where);
				if ($update) {

					$datadraft = array(
						'tanggal' => htmlentities($this->input->post('tanggal')), 
					);
					$wheredraft = array('surat_id' => $id);
					$this->lampiran_model->update_data('draft', $datadraft, $wheredraft);

					$this->session->set_flashdata('success', 'Surat Berhasil Diedit');

					$pembuatSurat = $this->db->get_where('draft', array('surat_id' => $id))->row_array();
					
					if ($pembuatSurat['dibuat_id'] == $this->session->userdata('jabatan_id')) {
						redirect(site_url('suratkeluar/lampiran'));
					}else{
						redirect(site_url('suratkeluar/draft'));
					}

				}else{
					$this->session->set_flashdata('error', 'Surat Gagal Diedit');
					redirect(site_url('suratkeluar/lampiran/edit/'.$id));
				}
			}else{
			    $query = $this->db->query("SELECT * FROM surat_lampiran WHERE id='$id'")->row_array();
	        	$ambext = explode(".",$file);
				$ekstensi = end($ambext);
				$date = date('his');
			    $nama_baru = "Lampiran-Surat-Lampiran-No-".$id."-".$date;
				$nama_file = $nama_baru.".".$ekstensi;	
				$config['upload_path'] = './assets/lampiransurat/lampiran/';
				$config['allowed_types'] = 'docx';
				$config['file_name'] = $nama_file;
				$this->upload->initialize($config);

				if(!$this->upload->do_upload('lampiran_lain')){
					$this->session->set_flashdata('error','Upload File Gagal');
					redirect(site_url('suratkeluar/lampiran/edit'));
				}else{
				    @unlink('./assets/lampiransurat/lampiran/'.$query['lampiran_lain']);
					$data = array(
                        'opd_id' => $this->session->userdata('opd_id'),
						'jenissurat' => htmlentities($this->input->post('jenissurat')),
                        'perihal' => $this->input->post('perihal'),
                        'tanggal' => $this->input->post('tanggal'),
                        'keterangan' => htmlentities($this->input->post('keterangan')), 
                        'lampiran_lain' =>$nama_file,  	
					);
					$where = array('id' => $id);
					$update = $this->lampiran_model->update_data('surat_lampiran', $data, $where);
					if ($update) {

						$datadraft = array( 
							'tanggal' => htmlentities($this->input->post('tanggal')), 
						);
						$wheredraft = array('surat_id' => $id);
						$this->lampiran_model->update_data('draft', $datadraft, $wheredraft);

						$this->session->set_flashdata('success', 'Surat Berhasil Diedit');
						
						$pembuatSurat = $this->db->get_where('draft', array('surat_id' => $id))->row_array();
						
						if ($pembuatSurat['dibuat_id'] == $this->session->userdata('jabatan_id')) {
							redirect(site_url('suratkeluar/lampiran'));
						}else{
							redirect(site_url('suratkeluar/draft'));
						}
						
					}else{
						$this->session->set_flashdata('error', 'Surat Gagal Diedit');
						redirect(site_url('suratkeluar/lampiran/edit/'.$id));
					}
				}
			}

		}
	}

	public function delete()
	{
		$where = array('id' => $this->uri->segment(4));
		$id = $this->uri->segment(4);
		$query = $this->db->query("SELECT * FROM surat_lampiran WHERE id='$id'")->row_array();
		@unlink('./assets/lampiransurat/lampiran/'.$query['lampiran_lain']);
		$delete = $this->lampiran_model->delete_data('surat_lampiran', $where);
		if ($delete) {
			$whereDis = array('surat_id' => $this->uri->segment(4));
			$this->lampiran_model->delete_data('draft', $whereDis);
			$this->lampiran_model->delete_data('verifikasi', $whereDis);
			$this->lampiran_model->delete_data('disposisi_suratkeluar', $whereDis);
			$this->lampiran_model->delete_data('tembusan_surat', $whereDis);
			
			$this->session->set_flashdata('success', 'Surat Berhasil Dihapus');
			redirect(site_url('suratkeluar/lampiran'));
		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Dihapus');
			redirect(site_url('suratkeluar/lampiran'));
		}
	}

	public function delete_kepada()
	{
		$surat_id = $this->uri->segment(4);
		$id = $this->uri->segment(5);
		$where = array('dsuratkeluar_id' => $id);
		$delete = $this->lampiran_model->delete_data('disposisi_suratkeluar', $where);
		if ($delete) {
			$this->session->set_flashdata('success', 'Data Berhasil Dihapus');
			redirect(site_url('suratkeluar/lampiran/edit/'.$surat_id));
		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Dihapus');
			redirect(site_url('suratkeluar/lampiran/edit/'.$surat_id));
		}
	}

	public function delete_tembusan()
	{
		$surat_id = $this->uri->segment(4);
		$id = $this->uri->segment(5);
		$where = array('tembusansurat_id' => $id);
		$delete = $this->lampiran_model->delete_data('tembusan_surat', $where);
		if ($delete) {
			$this->session->set_flashdata('success', 'Data Tembusan Berhasil Dihapus');
			redirect(site_url('suratkeluar/lampiran/edit/'.$surat_id));
		}else{
			$this->session->set_flashdata('error', 'Data Tembusan Gagal Dihapus');
			redirect(site_url('suratkeluar/lampiran/edit/'.$surat_id));
		}
	}

}