<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Undangan extends CI_Controller {

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
		$kopId=$this->input->post('kop_id');
		//untuk menambahkan data eksternal
		if (isset($_POST['simpan'])) {
			
			$getEksID = $this->undangan_model->get_id('eksternal_keluar')->result();
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
				'tempat' => $this->input->post('tempat_eksternal'), 
			);

			//form validation eksternal surat undangan
			$this->form_validation->set_rules('nama','Nama Eksternal', 'required');
        	$this->form_validation->set_rules('email','Email Eksternal', 'required');
			$this->form_validation->set_rules('required', '%s masih kosong, silakan diisikan');

			if($this->form_validation->run()===FALSE) {

			$this->session->set_flashdata('error', 'Nama dan email eksternal tidak boleh kosong');
			
			redirect(site_url('suratkeluar/undangan/add'));
			
			}
			else {

			$insert = $this->undangan_model->insert_data('eksternal_keluar', $data);
	
			}

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
			
			$file = $_FILES['lampiran_lain']['name'];
			$p=array('<p>','</p>');

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
					'hal' => str_replace($p,"",$this->input->post('hal')), 
					'tembusan' => '', 
					'lampiran_lain' => '',
					'p1' => $this->input->post('p1'),
					'hari' => $this->input->post('hari'),
					'tgl_acara' => $this->input->post('tgl_acara'),
					'pukul' => $this->input->post('pukul'),
					'tempat' => $this->input->post('tempat'),
					'acara' => str_replace($p,"",$this->input->post('acara')),
					'p2' => $this->input->post('p2'),
					'catatan' => $this->input->post('catatan')
					
				);
				//form validation surat undangan [@dam|E-Gov 10.04.2022]
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
				        'field' => 'p1',  
				        'label' => 'Paragraf Pembuka',
				        'rules' => 'required'],
				        
				        [
				        'field' => 'hari',  
				        'label' => 'Hari',
				        'rules' => 'required'],
				        
				        [
				        'field' => 'tgl_acara',  
				        'label' => 'Tanggal Acara',
				        'rules' => 'required'],
				        
				        [
				        'field' => 'pukul',  
				        'label' => 'Pukul/Waktu',
				        'rules' => 'required'],
				        
				        [
				        'field' => 'tempat',  
				        'label' => 'Tempat Acara',
				        'rules' => 'required'],
				        
				        [
				        'field' => 'acara',  
				        'label' => 'Nama Acara',
				        'rules' => 'required'],
				        
				        [
				        'field' => 'p2',  
				        'label' => 'Paragraf Penutup',
				        'rules' => 'required']
				    ];
				$this->form_validation->set_rules($rules);
				$this->form_validation->set_message('required', '%s masih kosong, silakan diisi');				

				if($this->form_validation->run()===FALSE) {
				
				//set value
				$this->session->set_flashdata('value_kop_id', set_value('kop_id', kop_id));
				$this->session->set_flashdata('value_tujuan', set_value('', '*Silakan pilih kembali tujuan internal-eksternal Pemkot'));
				$this->session->set_flashdata('value_sifat', set_value('sifat', sifat));
				$this->session->set_flashdata('value_lampiran', set_value('lampiran', lampiran));
				$this->session->set_flashdata('value_hal', set_value('hal', hal));
				$this->session->set_flashdata('value_p1', set_value('p1', p1));
				$this->session->set_flashdata('value_hari', set_value('hari', hari));
				$this->session->set_flashdata('value_tgl_acara', set_value('tgl_acara', tgl_acara));
				$this->session->set_flashdata('value_pukul', set_value('pukul', pukul));
				$this->session->set_flashdata('value_tempat', set_value('tempat', tempat));
				$this->session->set_flashdata('value_acara', set_value('acara', acara));
				$this->session->set_flashdata('value_p2', set_value('p2', p2));
				$this->session->set_flashdata('value_catatan', set_value('catatan', catatan));
				
				
				//error
				$this->session->set_flashdata('kop_id', form_error('kop_id'));
				$this->session->set_flashdata('sifat', form_error('sifat'));
				$this->session->set_flashdata('lampiran', form_error('lampiran'));
				$this->session->set_flashdata('hal', form_error('hal'));
				$this->session->set_flashdata('p1', form_error('p1'));
				$this->session->set_flashdata('hari', form_error('hari'));
				$this->session->set_flashdata('tgl_acara', form_error('tgl_acara'));
				$this->session->set_flashdata('pukul', form_error('pukul'));
				$this->session->set_flashdata('tempat', form_error('tempat'));
				$this->session->set_flashdata('acara', form_error('acara'));
				$this->session->set_flashdata('p2', form_error('p2'));
				
				redirect(site_url('suratkeluar/undangan/add'));
				
				}
				else {

				$insert = $this->undangan_model->insert_data('surat_undangan', $data);


				if ($insert) {
					
					//pengiriman surat internal atau eksternal
					$jabatan_id = $this->input->post('jabatan_id');
					$eksternal_id = $this->input->post('eksternal_id');

					//proses input internal eksternal
					internal_eksternal('undangan',$surat_id,$jabatan_id,$eksternal_id);
					
					//pengiriman tembusan internal atau eksternal
		            $tembusan_id = $this->input->post('tembusan_id');
		            $tembusaneks_id = $this->input->post('tembusaneks_id');
			
	                //proses input tembusan internal atau eksternal
		            internal_eksternal_tembusan('undangan',$surat_id,$tembusan_id,$tembusaneks_id);

					$datadraft = array(
						'surat_id' => $surat_id,
						'kopId' => $kopId,
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
			}else{
	        	$ambext = explode(".",$file);
				$ekstensi = end($ambext);
				$date = date('his');
				$nama_baru = "Lampiran-Surat-Undangan-No-".$surat_id."-".$date;
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
						'hal' => str_replace($p,"",$this->input->post('hal')), 
						'tembusan' => '', 
						'lampiran_lain' => $nama_file, 
						'p1' => $this->input->post('p1'),
						'hari' => $this->input->post('hari'),
						'tgl_acara' => $this->input->post('tgl_acara'),
						'pukul' => $this->input->post('pukul'),
						'tempat' => $this->input->post('tempat'),
						'acara' => str_replace($p,"",$this->input->post('acara')),
						'p2' => $this->input->post('p2'),
						'catatan' => $this->input->post('catatan')
													
					);
					//form validation surat undangan [@dam|E-Gov 10.04.2022]
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
    				        'field' => 'p1',  
    				        'label' => 'Paragraf Pembuka',
    				        'rules' => 'required'],
    				        
    				        [
    				        'field' => 'hari',  
    				        'label' => 'Hari',
    				        'rules' => 'required'],
    				        
    				        [
    				        'field' => 'tgl_acara',  
    				        'label' => 'Tanggal Acara',
    				        'rules' => 'required'],
    				        
    				        [
    				        'field' => 'pukul',  
    				        'label' => 'Pukul/Waktu',
    				        'rules' => 'required'],
    				        
    				        [
    				        'field' => 'tempat',  
    				        'label' => 'Tempat Acara',
    				        'rules' => 'required'],
    				        
    				        [
    				        'field' => 'acara',  
    				        'label' => 'Nama Acara',
    				        'rules' => 'required'],
    				        
    				        [
    				        'field' => 'p2',  
    				        'label' => 'Paragraf Penutup',
    				        'rules' => 'required']
    				    ];
    				$this->form_validation->set_rules($rules);
    				$this->form_validation->set_message('required', '%s masih kosong, silakan diisi');				
    
    				if($this->form_validation->run()===FALSE) {
    				
    				//set value
    				$this->session->set_flashdata('value_kop_id', set_value('kop_id', kop_id));
    				$this->session->set_flashdata('value_tujuan', set_value('', '*Silakan pilih kembali tujuan internal-eksternal Pemkot'));
    				$this->session->set_flashdata('value_sifat', set_value('sifat', sifat));
    				$this->session->set_flashdata('value_lampiran', set_value('lampiran', lampiran));
    				$this->session->set_flashdata('value_hal', set_value('hal', hal));
    				$this->session->set_flashdata('value_p1', set_value('p1', p1));
    				$this->session->set_flashdata('value_hari', set_value('hari', hari));
    				$this->session->set_flashdata('value_tgl_acara', set_value('tgl_acara', tgl_acara));
    				$this->session->set_flashdata('value_pukul', set_value('pukul', pukul));
    				$this->session->set_flashdata('value_tempat', set_value('tempat', tempat));
    				$this->session->set_flashdata('value_acara', set_value('acara', acara));
    				$this->session->set_flashdata('value_p2', set_value('p2', p2));
					$this->session->set_flashdata('value_catatan', set_value('catatan', catatan));
    				$this->session->set_flashdata('value_lain', set_value('', '* Silakan upload kembali file lampiran'));
					
    				
    				//error
    				$this->session->set_flashdata('kop_id', form_error('kop_id'));
    				$this->session->set_flashdata('sifat', form_error('sifat'));
    				$this->session->set_flashdata('lampiran', form_error('lampiran'));
    				$this->session->set_flashdata('hal', form_error('hal'));
    				$this->session->set_flashdata('p1', form_error('p1'));
    				$this->session->set_flashdata('hari', form_error('hari'));
    				$this->session->set_flashdata('tgl_acara', form_error('tgl_acara'));
    				$this->session->set_flashdata('pukul', form_error('pukul'));
    				$this->session->set_flashdata('tempat', form_error('tempat'));
    				$this->session->set_flashdata('acara', form_error('acara'));
    				$this->session->set_flashdata('p2', form_error('p2'));
    				
    				redirect(site_url('suratkeluar/undangan/add'));
    				
    				}
    				else {

					$insert = $this->undangan_model->insert_data('surat_undangan', $data);

					if ($insert) {

						//pengiriman surat internal atau eksternal
						$jabatan_id = $this->input->post('jabatan_id');
						$eksternal_id = $this->input->post('eksternal_id');

						//proses input internal eksternal
						internal_eksternal('undangan',$surat_id,$jabatan_id,$eksternal_id);
						
						//pengiriman tembusan internal atau eksternal
			            $tembusan_id = $this->input->post('tembusan_id');
			            $tembusaneks_id = $this->input->post('tembusaneks_id');
				
		                //proses input tembusan internal atau eksternal
			            internal_eksternal_tembusan('undangan',$surat_id,$tembusan_id,$tembusaneks_id);

						$datadraft = array(
							'surat_id' => $surat_id, 
							'kopId' => $kopId, 
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
	}

	public function duplicate()
	{
		$data['content'] = 'undangan/undangan_form';
		$data['kop'] = $this->db->get('kop_surat')->result();
		$data['kodesurat'] = $this->db->get('kode_surat')->result();
		$data['undangan'] = $this->undangan_model->edit_data($this->uri->segment(4), $this->session->userdata('opd_id'))->result();
		
		$this->load->view('template', $data);
	}
	
	public function saveduplicate()
	{
	    $suratId=$this->uri->segment(4);
	    $kopId=$this->input->post('kop_id');
		//untuk menambahkan data eksternal
		if (isset($_POST['simpan'])) {
			
			$getEksID = $this->undangan_model->get_id('eksternal_keluar')->result();
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
				'tempat' => $this->input->post('tempat_eksternal'), 
			);

			//form validation eksternal surat undangan
			$this->form_validation->set_rules('nama','Nama Eksternal', 'required');
        	$this->form_validation->set_rules('email','Email Eksternal', 'required');
			$this->form_validation->set_rules('required', '%s masih kosong, silakan diisikan');

			if($this->form_validation->run()===FALSE) {

			$this->session->set_flashdata('error', 'Nama dan email eksternal tidak boleh kosong');
			
			redirect(site_url('suratkeluar/undangan/duplicate'));
			
			}
			else {

			$insert = $this->undangan_model->insert_data('eksternal_keluar', $data);
	
			}

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
			
			$file = $_FILES['lampiran_lain']['name'];
			$p=array('<p>','</p>');

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
					'hal' => str_replace($p,"",$this->input->post('hal')), 
					'tembusan' => '', 
					'lampiran_lain' => '',
					'p1' => $this->input->post('p1'),
					'hari' => $this->input->post('hari'),
					'tgl_acara' => $this->input->post('tgl_acara'),
					'pukul' => $this->input->post('pukul'),
					'tempat' => $this->input->post('tempat'),
					'acara' => str_replace($p,"",$this->input->post('acara')),
					'p2' => $this->input->post('p2'),
					'catatan' => $this->input->post('catatan'),
					
				);
				//form validation surat undangan
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
				        'field' => 'p1',  
				        'label' => 'Paragraf Pembuka',
				        'rules' => 'required'],
				        
				        [
				        'field' => 'hari',  
				        'label' => 'Hari',
				        'rules' => 'required'],
				        
				        [
				        'field' => 'tgl_acara',  
				        'label' => 'Tanggal Acara',
				        'rules' => 'required'],
				        
				        [
				        'field' => 'pukul',  
				        'label' => 'Pukul/Waktu',
				        'rules' => 'required'],
				        
				        [
				        'field' => 'tempat',  
				        'label' => 'Tempat Acara',
				        'rules' => 'required'],
				        
				        [
				        'field' => 'acara',  
				        'label' => 'Nama Acara',
				        'rules' => 'required'],
				        
				        [
				        'field' => 'p2',  
				        'label' => 'Paragraf Penutup',
				        'rules' => 'required']
				    ];
				$this->form_validation->set_rules($rules);
				$this->form_validation->set_message('required', '%s masih kosong, silakan diisi');				

				if($this->form_validation->run()===FALSE) {
				
				//set value
				$this->session->set_flashdata('value_kop_id', set_value('kop_id', kop_id));
				$this->session->set_flashdata('value_tujuan', set_value('', '*Silakan pilih kembali tujuan internal-eksternal Pemkot'));
				$this->session->set_flashdata('value_sifat', set_value('sifat', sifat));
				$this->session->set_flashdata('value_lampiran', set_value('lampiran', lampiran));
				$this->session->set_flashdata('value_hal', set_value('hal', hal));
				$this->session->set_flashdata('value_p1', set_value('p1', p1));
				$this->session->set_flashdata('value_hari', set_value('hari', hari));
				$this->session->set_flashdata('value_tgl_acara', set_value('tgl_acara', tgl_acara));
				$this->session->set_flashdata('value_pukul', set_value('pukul', pukul));
				$this->session->set_flashdata('value_tempat', set_value('tempat', tempat));
				$this->session->set_flashdata('value_acara', set_value('acara', acara));
				$this->session->set_flashdata('value_p2', set_value('p2', p2));
				$this->session->set_flashdata('value_catatan', set_value('catatan', catatan));
				
				//error
				$this->session->set_flashdata('kop_id', form_error('kop_id'));
				$this->session->set_flashdata('sifat', form_error('sifat'));
				$this->session->set_flashdata('lampiran', form_error('lampiran'));
				$this->session->set_flashdata('hal', form_error('hal'));
				$this->session->set_flashdata('p1', form_error('p1'));
				$this->session->set_flashdata('hari', form_error('hari'));
				$this->session->set_flashdata('tgl_acara', form_error('tgl_acara'));
				$this->session->set_flashdata('pukul', form_error('pukul'));
				$this->session->set_flashdata('tempat', form_error('tempat'));
				$this->session->set_flashdata('acara', form_error('acara'));
				$this->session->set_flashdata('p2', form_error('p2'));
				
				redirect(site_url('suratkeluar/undangan/add'));
				
				}
				else {

				$insert = $this->undangan_model->insert_data('surat_undangan', $data);


				if ($insert) {
					
					//pengiriman surat internal atau eksternal
					$jabatan_id = $this->input->post('jabatan_id');
					$eksternal_id = $this->input->post('eksternal_id');

					//proses input internal eksternal
					internal_eksternal('undangan',$surat_id,$jabatan_id,$eksternal_id);
					
					//pengiriman tembusan internal atau eksternal
		            $tembusan_id = $this->input->post('tembusan_id');
		            $tembusaneks_id = $this->input->post('tembusaneks_id');
			
	                //proses input tembusan internal atau eksternal
		            internal_eksternal_tembusan('undangan',$surat_id,$tembusan_id,$tembusaneks_id);

					$datadraft = array(
						'surat_id' => $surat_id,
						'kopId' => $kopId,
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
			}else{
	        	$ambext = explode(".",$file);
				$ekstensi = end($ambext);
				$date = date('his');
				$nama_baru = "Lampiran-Surat-Undangan-No-".$surat_id."-".$date;
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
						'hal' => str_replace($p,"",$this->input->post('hal')), 
						'tembusan' => '', 
						'lampiran_lain' => $nama_file, 
						'p1' => $this->input->post('p1'),
						'hari' => $this->input->post('hari'),
						'tgl_acara' => $this->input->post('tgl_acara'),
						'pukul' => $this->input->post('pukul'),
						'tempat' => $this->input->post('tempat'),
						'acara' => str_replace($p,"",$this->input->post('acara')),
						'p2' => $this->input->post('p2'),
						'catatan' => $this->input->post('catatan'),
												
					);
					//form validation surat undangan
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
    				        'field' => 'p1',  
    				        'label' => 'Paragraf Pembuka',
    				        'rules' => 'required'],
    				        
    				        [
    				        'field' => 'hari',  
    				        'label' => 'Hari',
    				        'rules' => 'required'],
    				        
    				        [
    				        'field' => 'tgl_acara',  
    				        'label' => 'Tanggal Acara',
    				        'rules' => 'required'],
    				        
    				        [
    				        'field' => 'pukul',  
    				        'label' => 'Pukul/Waktu',
    				        'rules' => 'required'],
    				        
    				        [
    				        'field' => 'tempat',  
    				        'label' => 'Tempat Acara',
    				        'rules' => 'required'],
    				        
    				        [
    				        'field' => 'acara',  
    				        'label' => 'Nama Acara',
    				        'rules' => 'required'],
    				        
    				        [
    				        'field' => 'p2',  
    				        'label' => 'Paragraf Penutup',
    				        'rules' => 'required']
    				    ];
    				$this->form_validation->set_rules($rules);
    				$this->form_validation->set_message('required', '%s masih kosong, silakan diisi');				
    
    				if($this->form_validation->run()===FALSE) {
    				
    				//set value
    				$this->session->set_flashdata('value_kop_id', set_value('kop_id', kop_id));
    				$this->session->set_flashdata('value_tujuan', set_value('', '*Silakan pilih kembali tujuan internal-eksternal Pemkot'));
    				$this->session->set_flashdata('value_sifat', set_value('sifat', sifat));
    				$this->session->set_flashdata('value_lampiran', set_value('lampiran', lampiran));
    				$this->session->set_flashdata('value_hal', set_value('hal', hal));
    				$this->session->set_flashdata('value_p1', set_value('p1', p1));
    				$this->session->set_flashdata('value_hari', set_value('hari', hari));
    				$this->session->set_flashdata('value_tgl_acara', set_value('tgl_acara', tgl_acara));
    				$this->session->set_flashdata('value_pukul', set_value('pukul', pukul));
    				$this->session->set_flashdata('value_tempat', set_value('tempat', tempat));
    				$this->session->set_flashdata('value_acara', set_value('acara', acara));
    				$this->session->set_flashdata('value_p2', set_value('p2', p2));
					$this->session->set_flashdata('value_catatan', set_value('catatan', catatan));
    				$this->session->set_flashdata('value_lain', set_value('', '* Silakan upload kembali file lampiran'));
    				
    				//error
    				$this->session->set_flashdata('kop_id', form_error('kop_id'));
    				$this->session->set_flashdata('sifat', form_error('sifat'));
    				$this->session->set_flashdata('lampiran', form_error('lampiran'));
    				$this->session->set_flashdata('hal', form_error('hal'));
    				$this->session->set_flashdata('p1', form_error('p1'));
    				$this->session->set_flashdata('hari', form_error('hari'));
    				$this->session->set_flashdata('tgl_acara', form_error('tgl_acara'));
    				$this->session->set_flashdata('pukul', form_error('pukul'));
    				$this->session->set_flashdata('tempat', form_error('tempat'));
    				$this->session->set_flashdata('acara', form_error('acara'));
    				$this->session->set_flashdata('p2', form_error('p2'));
    				
    				redirect(site_url('suratkeluar/undangan/add'));
    				
    				}
    				else {

					$insert = $this->undangan_model->insert_data('surat_undangan', $data);

					if ($insert) {

						//pengiriman surat internal atau eksternal
						$jabatan_id = $this->input->post('jabatan_id');
						$eksternal_id = $this->input->post('eksternal_id');

						//proses input internal eksternal
						internal_eksternal('undangan',$surat_id,$jabatan_id,$eksternal_id);
						
						//pengiriman tembusan internal atau eksternal
			            $tembusan_id = $this->input->post('tembusan_id');
			            $tembusaneks_id = $this->input->post('tembusaneks_id');
				
		                //proses input tembusan internal atau eksternal
			            internal_eksternal_tembusan('undangan',$surat_id,$tembusan_id,$tembusaneks_id);

						$datadraft = array(
							'surat_id' => $surat_id, 
							'kopId' => $kopId, 
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
			
			$getEksID = $this->undangan_model->get_id('eksternal_keluar')->result();
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
				'tempat' => $this->input->post('tempat_eksternal'), 
			);
			//form validation eksternal surat undangan
			$this->form_validation->set_rules('nama','Nama Eksternal', 'required');
        		$this->form_validation->set_rules('email','Email Eksternal', 'required');
			$this->form_validation->set_rules('required', '%s masih kosong, silakan diisikan');

			if($this->form_validation->run()===FALSE) {

			$this->session->set_flashdata('error', 'Nama dan email eksternal tidak boleh kosong');
			
			redirect(site_url('suratkeluar/undangan/add'));
			
			}
			else {

			$insert = $this->undangan_model->insert_data('eksternal_keluar', $data);
	
			}

			if ($insert) {
				$this->session->set_flashdata('success', 'Eksternal Berhasil Dibuat');
				redirect(site_url('suratkeluar/undangan/add'));
			}else{
				$this->session->set_flashdata('error', 'Eksternal Gagal Dibuat');
				redirect(site_url('suratkeluar/draft/add/'));
			}

		//Untuk mengedit surat
		}else{

			//pengiriman surat internal atau eksternal
			$jabatan_id = $this->input->post('jabatan_id');
			$eksternal_id = $this->input->post('eksternal_id');
			if (!empty($jabatan_id) OR !empty($eksternal_id)) {
				internal_eksternal('undangan',$id,$jabatan_id,$eksternal_id);
			}
			
			//pengiriman tembusan internal atau eksternal
		    $tembusan_id = $this->input->post('tembusan_id');
		    $tembusaneks_id = $this->input->post('tembusaneks_id');

	    	if (!empty($tembusan_id) OR !empty($tembusaneks_id)) {
	    		internal_eksternal_tembusan('undangan',$id,$tembusan_id,$tembusaneks_id);
	    	}

			$file = $_FILES['lampiran_lain']['name'];
			$p=array('<p>','</p>');

			if (empty($file)) {
			    
				$data = array(
					'kop_id' => htmlentities($this->input->post('kop_id')),
					'opd_id' => htmlentities($this->session->userdata('opd_id')),
					'kodesurat_id' => htmlentities($this->input->post('kodesurat_id')),
					'tanggal' => htmlentities($this->input->post('tanggal')),
					'sifat' => htmlentities($this->input->post('sifat')), 
					'lampiran' => htmlentities($this->input->post('lampiran')), 
					'hal' => str_replace($p,"",$this->input->post('hal')), 
					'tembusan' => '', 
					'p1' => $this->input->post('p1'),
					'hari' => $this->input->post('hari'),
					'tgl_acara' => $this->input->post('tgl_acara'),
					'pukul' => $this->input->post('pukul'),
					'tempat' => $this->input->post('tempat'),
					'acara' => str_replace($p,"",$this->input->post('acara')),
					'p2' => $this->input->post('p2'),		
					'catatan' => $this->input->post('catatan')
									
				);
				$where = array('id' => $id);
				$update = $this->undangan_model->update_data('surat_undangan', $data, $where);
				if ($update) {

					$datadraft = array(
						'tanggal' => htmlentities($this->input->post('tanggal')), 
						'kopId' => $this->input->post('kop_id'), 
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
				$query = $this->db->query("SELECT * FROM surat_undangan WHERE id='$id'")->row_array();
	        	$ambext = explode(".",$file);
				$ekstensi = end($ambext);
				$date = date('his');
				$nama_baru = "Lampiran-Surat-Undangan-No-".$id."-".$date;
				$nama_file = $nama_baru.".".$ekstensi;	
				$config['upload_path'] = './assets/lampiransurat/undangan/';
				$config['allowed_types'] = 'pdf';
				$config['file_name'] = $nama_file;
				$this->upload->initialize($config);

				if(!$this->upload->do_upload('lampiran_lain')){
					$this->session->set_flashdata('error','Upload File Gagal');
					redirect(site_url('suratkeluar/undangan/edit'));
				}else{
					@unlink('./assets/lampiransurat/undangan/'.$query['lampiran_lain']);
					$data = array(
						'kop_id' => htmlentities($this->input->post('kop_id')),
						'opd_id' => $this->session->userdata('opd_id'),
						'kodesurat_id' => htmlentities($this->input->post('kodesurat_id')),
						'tanggal' => htmlentities($this->input->post('tanggal')), 
						'sifat' => htmlentities($this->input->post('sifat')), 
						'lampiran' => htmlentities($this->input->post('lampiran')), 
						'hal' => str_replace($p,"",$this->input->post('hal')), 
						'tembusan' => '', 	
						'lampiran_lain' => $nama_file,
						'p1' => $this->input->post('p1'),
						'hari' => $this->input->post('hari'),
						'tgl_acara' => $this->input->post('tgl_acara'),
						'pukul' => $this->input->post('pukul'),
						'tempat' => $this->input->post('tempat'),
						'acara' => str_replace($p,"",$this->input->post('acara')),
						'p2' => $this->input->post('p2'),	
						'catatan' => $this->input->post('catatan')				
					);
					$where = array('id' => $id);
					$update = $this->undangan_model->update_data('surat_undangan', $data, $where);
					if ($update) {

						$datadraft = array( 
							'tanggal' => htmlentities($this->input->post('tanggal')), 
							'kopId' => $this->input->post('kop_id'), 
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
				}
			}

		}
	}

	public function delete()
	{
		$where = array('id' => $this->uri->segment(4));
		$id = $this->uri->segment(4);
		$query = $this->db->query("SELECT * FROM surat_undangan WHERE id='$id'")->row_array();
		@unlink('./assets/lampiransurat/undangan/'.$query['lampiran_lain']);
		$delete = $this->undangan_model->delete_data('surat_undangan', $where);
		if ($delete) {
			$whereDis = array('surat_id' => $this->uri->segment(4));
			$this->undangan_model->delete_data('draft', $whereDis);
			$this->undangan_model->delete_data('verifikasi', $whereDis);
			$this->undangan_model->delete_data('disposisi_suratkeluar', $whereDis);
			$this->undangan_model->delete_data('tembusan_surat', $whereDis);
			
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

	public function delete_tembusan()
	{
		$surat_id = $this->uri->segment(4);
		$id = $this->uri->segment(5);
		$where = array('tembusansurat_id' => $id);
		$delete = $this->undangan_model->delete_data('tembusan_surat', $where);
		if ($delete) {
			$this->session->set_flashdata('success', 'Data Tembusan Berhasil Dihapus');
			redirect(site_url('suratkeluar/undangan/edit/'.$surat_id));
		}else{
			$this->session->set_flashdata('error', 'Data Tembusan Gagal Dihapus');
			redirect(site_url('suratkeluar/undangan/edit/'.$surat_id));
		}
	}	

}