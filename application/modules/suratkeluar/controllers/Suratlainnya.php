<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Suratlainnya extends CI_Controller {

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
		$this->load->model('suratlainnya_model');
	} 

	public function index()
	{
		$data['content'] = 'suratlainnya/suratlainnya_index';
		
		$tahun = $this->session->userdata('tahun');
		$opd_id = $this->session->userdata('opd_id');
		$jabatan_id = $this->session->userdata('jabatan_id');

		$data['suratlainnya'] = $this->suratlainnya_model->get_data($tahun,$opd_id,$jabatan_id)->result();
		
		$this->load->view('template', $data);
	}

	public function add()
	{
		$data['content'] = 'suratlainnya/suratlainnya_form';
		$data['kop'] = $this->db->get('kop_surat')->result();
		$this->load->view('template', $data);
	}

	public function insert()
	{
		//untuk menambahkan data eksternal
		if (isset($_POST['simpan'])) {
			
			$getEksID = $this->suratlainnya_model->get_id('eksternal_keluar')->result();
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
			//form validation eksternal suratlainnya
			$this->form_validation->set_rules('nama','Nama Eksternal', 'required');
        	$this->form_validation->set_rules('email','Email Eksternal', 'required');
			$this->form_validation->set_rules('required', '%s masih kosong, silakan diisikan');

			if($this->form_validation->run()===FALSE) {

			$this->session->set_flashdata('error', 'Nama dan email eksternal tidak boleh kosong');
			
			redirect(site_url('suratkeluar/suratlainnya/add'));
			
			}
			else {

			$insert = $this->suratlainnya_model->insert_data('eksternal_keluar', $data);

			if ($insert) {
				$this->session->set_flashdata('success', 'Eksternal Berhasil Dibuat');
				redirect(site_url('suratkeluar/suratlainnya/add'));
			}else{
				$this->session->set_flashdata('error', 'Eksternal Gagal Dibuat');
				redirect(site_url('suratkeluar/draft/add/'));
			}

			}

		//Untuk menambahkan surat
		}else{

			$getID = $this->suratlainnya_model->get_id('surat_lainnya')->result();
			foreach ($getID as $key => $h) {
				$id = $h->id;
			}
			if (empty($id)) {
				$surat_id = 'SL-1';
			}else{
				$urut = substr($id, 3)+1;
				$surat_id = 'SL-'.$urut;
			}

			$file = $_FILES['lampiran_lain']['name'];

			if (empty($file)) {
				$data = array(
					'id' => $surat_id,
                    'opd_id' => $this->session->userdata('opd_id'),
					'kop_id' => htmlentities($this->input->post('kop_id')),
					'jenissurat' => htmlentities($this->input->post('jenissurat')),
					'perihal' => $this->input->post('perihal'),
					'tanggal' => $this->input->post('tanggal'),
					'lampiran' =>'', 
				);
				//form validation suratlainnya [@dam|E-Gov 17.04.2022]
				$this->load->library('form_validation');	
    				
    			$rules =
    			    [
    			        [
    			        'field' => 'kop_id',  
    			        'label' => 'Kop Surat',
    			        'rules' => 'required'],
    				        
    			        [
    			        'field' => 'jenissurat',  
    			        'label' => 'Jenis Surat',
    			        'rules' => 'required'],
    			        
    			        [
    				    'field' => 'lampiran_lain',  
    				    'label' => 'Lampiran',
    				    'rules' => 'required'],
    				        
    				    [
    				    'field' => 'perihal',  
    				    'label' => 'Perihal Surat',
    				    'rules' => 'required']
    				        
    				];
    			$this->form_validation->set_rules($rules);
    			$this->form_validation->set_message('required', '%s masih kosong, silakan diisi');				
    
				if($this->form_validation->run()===FALSE) {
    				
				//set value
				$this->session->set_flashdata('value_kop_id', set_value('kop_id', kop_id));
				$this->session->set_flashdata('value_tujuan', set_value('', '*Silakan pilih kembali tujuan internal-eksternal Pemkot'));
				$this->session->set_flashdata('value_jenissurat', set_value('jenissurat', jenissurat));
				$this->session->set_flashdata('value_perihal', set_value('perihal', perihal));
    				
				//error
				$this->session->set_flashdata('kop_id', form_error('kop_id'));
				$this->session->set_flashdata('jenissurat', form_error('jenissurat'));
				$this->session->set_flashdata('lampiran_lain', form_error('lampiran_lain'));
				$this->session->set_flashdata('perihal', form_error('perihal'));
				
				redirect(site_url('suratkeluar/suratlainnya/add'));
    				
				}
				else {

				$insert = $this->suratlainnya_model->insert_data('surat_lainnya', $data);

				if ($insert) {

					//pengiriman surat internal atau eksternal
					$jabatan_id = $this->input->post('jabatan_id');
					$eksternal_id = $this->input->post('eksternal_id');

					//proses input internal eksternal
					internal_eksternal('suratlainnya',$surat_id,$jabatan_id,$eksternal_id);
					
					//pengiriman tembusan internal atau eksternal
	            	$tembusan_id = $this->input->post('tembusan_id');
		            $tembusaneks_id = $this->input->post('tembusaneks_id');
			
		            //proses input tembusan internal atau eksternal
		            internal_eksternal_tembusan('suratlainnya',$surat_id,$tembusan_id,$tembusaneks_id);

					$datadraft = array(
						'surat_id' => $surat_id,
						'tanggal' => htmlentities($this->input->post('tanggal')), 
						'dibuat_id' => $this->session->userdata('jabatan_id'), 
						'kopId' => $this->input->post('kop_id'), 
						'penandatangan_id' => '',
						'verifikasi_id' => '', 
						'nama_surat' => $this->input->post('jenissurat'), 
					);
					$this->suratlainnya_model->insert_data('draft', $datadraft);

					$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
					redirect(site_url('suratkeluar/suratlainnya'));
				}else{
					$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
					redirect(site_url('suratkeluar/suratlainnya/add'));
				}
				}
			}else{
	        	$ambext = explode(".",$file);
				$ekstensi = end($ambext);
				$date = date('his');
			    $nama_baru = "SuratLainnya-Lampiran-No-".$surat_id."-".$date;
				$nama_file = $nama_baru.".".$ekstensi;	
				$config['upload_path'] = './assets/lampiransurat/suratlainnya/';
				//$config['allowed_types'] = 'docx';
				$config['allowed_types'] = 'pdf';
				$config['file_name'] = $nama_file;
				$this->upload->initialize($config);

				if(!$this->upload->do_upload('lampiran_lain')){
					$this->session->set_flashdata('error','Upload File Gagal');
					redirect(site_url('suratkeluar/suratlainnya/add'));
				}else{
					$data = array(
						            'id' => $surat_id,
                       				'opd_id' => $this->session->userdata('opd_id'),
                        			'jenissurat' => htmlentities($this->input->post('jenissurat')),
                        			'kop_id' => $this->input->post('kop_id'),
                        			'perihal' => $this->input->post('perihal'),
                        			'tanggal' => $this->input->post('tanggal'),
                        			'lampiran' =>$nama_file, 
					);
					//form validation suratlainnya [@dam|E-Gov 17.04.2022]
    				$this->load->library('form_validation');	
        				
        			$rules =
        			    [
        			        [
        			        'field' => 'kop_id',  
        			        'label' => 'Kop Surat',
        			        'rules' => 'required'],
        				        
        			        [	
        			        'field' => 'jenissurat',  
        			        'label' => 'Jenis Surat',
        			        'rules' => 'required'],
        				        
        				    [
        				    'field' => 'perihal',  
        				    'label' => 'Perihal Surat',
        				    'rules' => 'required']
        				        
        				];
        			$this->form_validation->set_rules($rules);
        			$this->form_validation->set_message('required', '%s masih kosong, silakan diisi');				
        
    				if($this->form_validation->run()===FALSE) {
        				
    				//set value
    				$this->session->set_flashdata('value_kop_id', set_value('kop_id', kop_id));
    				$this->session->set_flashdata('value_tujuan', set_value('', '*Silakan pilih kembali tujuan internal-eksternal Pemkot'));
    				$this->session->set_flashdata('value_jenissurat', set_value('jenissurat', jenissurat));
    				$this->session->set_flashdata('value_perihal', set_value('perihal', perihal));
    				$this->session->set_flashdata('lampiran_lain', set_value('', '* Silakan upload kembali file lampiran'));
        				
    				//error
    				$this->session->set_flashdata('kop_id', form_error('kop_id'));
    				$this->session->set_flashdata('jenissurat', form_error('jenissurat'));
    				$this->session->set_flashdata('perihal', form_error('perihal'));
    				
    				redirect(site_url('suratkeluar/suratlainnya/add'));
        				
    				}
    				else {

					$insert = $this->suratlainnya_model->insert_data('surat_lainnya', $data);
					if ($insert) {
						
						//pengiriman surat internal atau eksternal
						$jabatan_id = $this->input->post('jabatan_id');
						$eksternal_id = $this->input->post('eksternal_id');

						//proses input internal eksternal
						internal_eksternal('suratlainnya',$surat_id,$jabatan_id,$eksternal_id);
						
						//pengiriman tembusan internal atau eksternal
		            	$tembusan_id = $this->input->post('tembusan_id');
			            $tembusaneks_id = $this->input->post('tembusaneks_id');
				
			            //proses input tembusan internal atau eksternal
			            internal_eksternal_tembusan('suratlainnya',$surat_id,$tembusan_id,$tembusaneks_id);
						
						$datadraft = array(
							'surat_id' => $surat_id, 
							'tanggal' => htmlentities($this->input->post('tanggal')), 
							'kopId' => $this->input->post('kop_id'), 
							'dibuat_id' => $this->session->userdata('jabatan_id'), 
							'penandatangan_id' => '',
							'verifikasi_id' => '', 
							'nama_surat' =>$this->input->post('jenissurat'), 
						);
						$this->suratlainnya_model->insert_data('draft', $datadraft);

						$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
						redirect(site_url('suratkeluar/suratlainnya'));
					}else{
						$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
						redirect(site_url('suratkeluar/suratlainnya/add'));
					}
					}
				}
			}
 
		}
	}

	public function edit()
	{
		$data['content'] = 'suratlainnya/suratlainnya_form';
		$data['kop'] = $this->db->get('kop_surat')->result();
		$data['suratlainnya'] = $this->suratlainnya_model->edit_data($this->uri->segment(4), $this->session->userdata('opd_id'))->result();
		
		$this->load->view('template', $data);
	}

	public function update()
	{
		$id = $this->input->post('id');

		//Untuk menambahkan data eksternal 
		if (isset($_POST['simpan'])) {
			
			$getEksID = $this->suratlainnya_model->get_id('eksternal_keluar')->result();
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
			//form validation eksternal suratlainnya
			$this->form_validation->set_rules('nama','Nama Eksternal', 'required');
        	$this->form_validation->set_rules('email','Email Eksternal', 'required');
			$this->form_validation->set_rules('required', '%s masih kosong, silakan diisikan');

			if($this->form_validation->run()===FALSE) {

			$this->session->set_flashdata('error', 'Nama dan email eksternal tidak boleh kosong');
			
			redirect(site_url('suratkeluar/suratlainnya/add'));
			
			}
			else {

			$insert = $this->suratlainnya_model->insert_data('eksternal_keluar', $data);

			if ($insert) {
				$this->session->set_flashdata('success', 'Eksternal Berhasil Dibuat');
				redirect(site_url('suratkeluar/suratlainnya/add'));
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
				internal_eksternal('suratlainnya',$id,$jabatan_id,$eksternal_id);
			}
			
			//pengiriman tembusan internal atau eksternal
		    $tembusan_id = $this->input->post('tembusan_id');
		    $tembusaneks_id = $this->input->post('tembusaneks_id');

		    if (!empty($tembusan_id) OR !empty($tembusaneks_id)) {
		    	internal_eksternal_tembusan('suratlainnya',$id,$tembusan_id,$tembusaneks_id);
	    	}

			$file = $_FILES['suratlainnya_lain']['name'];

			if (empty($file)) {
				$getQuery = $this->suratlainnya_model->edit_data('surat_lainnya', array('id' => $id))->result();
				foreach ($getQuery as $key => $h) {
					$filelampiran = $h->lampiran;
				}
				$data = array(
                    'opd_id' => $this->session->userdata('opd_id'),
					'kop_id' => $this->input->post('kop_id'),
                    'jenissurat' => htmlentities($this->input->post('jenissurat')),
                    'perihal' => $this->input->post('perihal'),
                    'tanggal' => $this->input->post('tanggal'),
                    // 'lampiran' =>$filelampiran,  
				);
				$where = array('id' => $id);
				$update = $this->suratlainnya_model->update_data('surat_lainnya', $data, $where);
				if ($update) {

					$datadraft = array(
						'tanggal' => htmlentities($this->input->post('tanggal')), 
						'kopId' => $this->input->post('kop_id'), 
					);
					$wheredraft = array('surat_id' => $id);
					$this->suratlainnya_model->update_data('draft', $datadraft, $wheredraft);

					$this->session->set_flashdata('success', 'Surat Berhasil Diedit');

					$pembuatSurat = $this->db->get_where('draft', array('surat_id' => $id))->row_array();
					
					if ($pembuatSurat['dibuat_id'] == $this->session->userdata('jabatan_id')) {
						redirect(site_url('suratkeluar/suratlainnya'));
					}else{
						redirect(site_url('suratkeluar/draft'));
					}

				}else{
					$this->session->set_flashdata('error', 'Surat Gagal Diedit');
					redirect(site_url('suratkeluar/suratlainnya/edit/'.$id));
				}
			}else{
			    $query = $this->db->query("SELECT * FROM surat_lainnya WHERE id='$id'")->row_array();
	        	$ambext = explode(".",$file);
				$ekstensi = end($ambext);
				$date = date('his');
			    $nama_baru = "SuratLainnya-Lampiran-No-".$id."-".$date;
				$nama_file = $nama_baru.".".$ekstensi;	
				$config['upload_path'] = './assets/lampiransurat/suratlainnya/';
				//$config['allowed_types'] = 'docx';
				$config['allowed_types'] = 'pdf';
				$config['file_name'] = $nama_file;
				$this->upload->initialize($config);

				if(!$this->upload->do_upload('suratlainnya_lain')){
					$this->session->set_flashdata('error','Upload File Gagal');
					redirect(site_url('suratkeluar/suratlainnya/edit'));
				}else{
				    @unlink('./assets/lampiransurat/suratlainnya/'.$query['lampiran']);
					$data = array(
                        'opd_id' => $this->session->userdata('opd_id'),
						'jenissurat' => htmlentities($this->input->post('jenissurat')),
                        'perihal' => $this->input->post('perihal'),
                        'kop_id' => $this->input->post('kop_id'),
                        'tanggal' => $this->input->post('tanggal'), 
                        'lampiran' =>$nama_file,  	
					);
					$where = array('id' => $id);
					$update = $this->suratlainnya_model->update_data('surat_lainnya', $data, $where);
					if ($update) {

						$datadraft = array( 
							'tanggal' => htmlentities($this->input->post('tanggal')), 
							'kopId' => $this->input->post('kop_id'), 
						);
						$wheredraft = array('surat_id' => $id);
						$this->suratlainnya_model->update_data('draft', $datadraft, $wheredraft);

						$this->session->set_flashdata('success', 'Surat Berhasil Diedit');
						
						$pembuatSurat = $this->db->get_where('draft', array('surat_id' => $id))->row_array();
						
						if ($pembuatSurat['dibuat_id'] == $this->session->userdata('jabatan_id')) {
							redirect(site_url('suratkeluar/suratlainnya'));
						}else{
							redirect(site_url('suratkeluar/draft'));
						}
						
					}else{
						$this->session->set_flashdata('error', 'Surat Gagal Diedit');
						redirect(site_url('suratkeluar/suratlainnya/edit/'.$id));
					}
				}
			}

		}
	}

	public function delete()
	{
		$where = array('id' => $this->uri->segment(4));
		$id = $this->uri->segment(4);
		$query = $this->db->query("SELECT * FROM surat_lainnya WHERE id='$id'")->row_array();
		@unlink('./assets/lampiransurat/suratlainnya/'.$query['lampiran']);
		$delete = $this->suratlainnya_model->delete_data('surat_lainnya', $where);
		if ($delete) {
			$whereDis = array('surat_id' => $this->uri->segment(4));
			$this->suratlainnya_model->delete_data('draft', $whereDis);
			$this->suratlainnya_model->delete_data('verifikasi', $whereDis);
			$this->suratlainnya_model->delete_data('disposisi_suratkeluar', $whereDis);
			$this->suratlainnya_model->delete_data('tembusan_surat', $whereDis);
			
			$this->session->set_flashdata('success', 'Surat Berhasil Dihapus');
			redirect(site_url('suratkeluar/suratlainnya'));
		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Dihapus');
			redirect(site_url('suratkeluar/suratlainnya'));
		}
	}

	public function delete_kepada()
	{
		$surat_id = $this->uri->segment(4);
		$id = $this->uri->segment(5);
		$where = array('dsuratkeluar_id' => $id);
		$delete = $this->suratlainnya_model->delete_data('disposisi_suratkeluar', $where);
		if ($delete) {
			$this->session->set_flashdata('success', 'Data Berhasil Dihapus');
			redirect(site_url('suratkeluar/suratlainnya/edit/'.$surat_id));
		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Dihapus');
			redirect(site_url('suratkeluar/suratlainnya/edit/'.$surat_id));
		}
	}

	public function delete_tembusan()
	{
		$surat_id = $this->uri->segment(4);
		$id = $this->uri->segment(5);
		$where = array('tembusansurat_id' => $id);
		$delete = $this->suratlainnya_model->delete_data('tembusan_surat', $where);
		if ($delete) {
			$this->session->set_flashdata('success', 'Data Tembusan Berhasil Dihapus');
			redirect(site_url('suratkeluar/suratlainnya/edit/'.$surat_id));
		}else{
			$this->session->set_flashdata('error', 'Data Tembusan Gagal Dihapus');
			redirect(site_url('suratkeluar/suratlainnya/edit/'.$surat_id));
		}
	}

}