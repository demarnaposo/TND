<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class panggilan extends CI_Controller {

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
		$data['jabatan'] = $this->db->query('select * from aparatur 
		left join levelbaru on levelbaru.level_id=aparatur.level_id 
		left join jabatan on jabatan.jabatan_id=aparatur.jabatan_id 
		where aparatur.opd_id = '.$this->session->userdata('opd_id').' and aparatur.level_id NOT IN (0,18,19) order by levelbaru.level_id ASC')->result();	
		
		$this->load->view('template', $data);
	}

	public function insert()
	{
		$kopId=$this->input->post('kop_id');
		//untuk menambahkan data eksternal
		if (isset($_POST['simpan'])) {
			
			$getEksID = $this->panggilan_model->get_id('eksternal_keluar')->result();
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
			//form validation eksternal surat panggilan
			$this->form_validation->set_rules('nama','Nama Eksternal', 'required');
        	$this->form_validation->set_rules('email','Email Eksternal', 'required');
			$this->form_validation->set_rules('required', '%s masih kosong, silakan diisikan');

			if($this->form_validation->run()===FALSE) {

			$this->session->set_flashdata('error', 'Nama dan email eksternal tidak boleh kosong');
			
			redirect(site_url('suratkeluar/panggilan/add'));
			
			}
			else {

			$insert = $this->panggilan_model->insert_data('eksternal_keluar', $data);

			if ($insert) {
				$this->session->set_flashdata('success', 'Eksternal Berhasil Dibuat');
				redirect(site_url('suratkeluar/panggilan/add'));
			}else{
				$this->session->set_flashdata('error', 'Eksternal Gagal Dibuat');
				redirect(site_url('suratkeluar/draft/add/'));
			}

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
					'lampiran_lain' => '',
					'kantor' => $this->input->post('kantor'),
					'tgl_acara' => htmlentities($this->input->post('tgl_acara')), 
					'pukul' => $this->input->post('pukul'),
					'tempat' => $this->input->post('tempat'),
					'menghadapkepada_id' => $this->input->post('menghadapkepada_id'),
					'alamat' => $this->input->post('alamat'),
					'untuk' => $this->input->post('untuk'),
					'catatan' => $this->input->post('catatan'),
				);
				//form validation surat panggilan [@dam|E-Gov 12.04.2022]
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
				        'field' => 'kantor',  
				        'label' => 'Kantor',
				        'rules' => 'required'],
				        
				        [
				        'field' => 'pukul',  
				        'label' => 'Pukul/Waktu',
				        'rules' => 'required'],
				        
				        [
				        'field' => 'tempat',  
				        'label' => 'Tempat',
				        'rules' => 'required'],
				        
				        [
				        'field' => 'menghadapkepada_id',  
				        'label' => 'Menghadap Kepada',
				        'rules' => 'required'],
				        
				        [
				        'field' => 'alamat',  
				        'label' => 'Alamat',
				        'rules' => 'required'],
				        
				        [
				        'field' => 'untuk',  
				        'label' => 'Untuk',
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
				$this->session->set_flashdata('value_kantor', set_value('kantor', kantor));
				$this->session->set_flashdata('value_pukul', set_value('pukul', pukul));
				$this->session->set_flashdata('value_tempat', set_value('tempat', tempat));
				$this->session->set_flashdata('value_menghadapkepada_id', set_value('menghadapkepada_id', menghadapkepada_id));
				$this->session->set_flashdata('value_alamat', set_value('alamat', alamat));
				$this->session->set_flashdata('value_untuk', set_value('untuk', untuk));
				$this->session->set_flashdata('value_catatan', set_value('catatan', catatan));
				
				//error
				$this->session->set_flashdata('kop_id', form_error('kop_id'));
				$this->session->set_flashdata('sifat', form_error('sifat'));
				$this->session->set_flashdata('lampiran', form_error('lampiran'));
				$this->session->set_flashdata('hal', form_error('hal'));
				$this->session->set_flashdata('kantor', form_error('kantor'));
				$this->session->set_flashdata('pukul', form_error('pukul'));
				$this->session->set_flashdata('tempat', form_error('tempat'));
				$this->session->set_flashdata('menghadapkepada_id', form_error('menghadapkepada_id'));
				$this->session->set_flashdata('alamat', form_error('alamat'));
				$this->session->set_flashdata('untuk', form_error('untuk'));
				
				redirect(site_url('suratkeluar/panggilan/add'));
				
				}
				else {

				$insert = $this->panggilan_model->insert_data('surat_panggilan', $data);
				if ($insert) {
		
					//pengiriman surat internal atau eksternal
					$jabatan_id = $this->input->post('jabatan_id');
					$eksternal_id = $this->input->post('eksternal_id');

					//proses input internal eksternal
					internal_eksternal('panggilan',$surat_id,$jabatan_id,$eksternal_id);
					
					//pengiriman tembusan internal atau eksternal
	            	$tembusan_id = $this->input->post('tembusan_id');
	            	$tembusaneks_id = $this->input->post('tembusaneks_id');
			
	            	//proses input tembusan internal atau eksternal
	            	internal_eksternal_tembusan('panggilan',$surat_id,$tembusan_id,$tembusaneks_id);

					$datadraft = array(
						'surat_id' => $surat_id,
						'kopId' => $kopId,
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
						'tanggal' => htmlentities($this->input->post('tanggal')), 
						'nomor' => '',
						'sifat' => htmlentities($this->input->post('sifat')), 
						'lampiran' => htmlentities($this->input->post('lampiran')), 
						'hal' => htmlentities($this->input->post('hal')),  
						'lampiran_lain' => '',
						'kantor' => $this->input->post('kantor'),
						'tgl_acara' => htmlentities($this->input->post('tgl_acara')), 
						'pukul' => $this->input->post('pukul'),
						'tempat' => $this->input->post('tempat'),
						'menghadapkepada_id' => $this->input->post('menghadapkepada_id'),
						'alamat' => $this->input->post('alamat'),
						'untuk' => $this->input->post('untuk'),
						'catatan' => $this->input->post('catatan'),
					);
					//form validation surat panggilan [@dam|E-Gov 12.04.2022]
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
    				        'field' => 'kantor',  
    				        'label' => 'Kantor',
    				        'rules' => 'required'],
    				        
    				        [
    				        'field' => 'pukul',  
    				        'label' => 'Pukul/Waktu',
    				        'rules' => 'required'],
    				        
    				        [
    				        'field' => 'tempat',  
    				        'label' => 'Tempat',
    				        'rules' => 'required'],
    				        
    				        [
    				        'field' => 'menghadapkepada_id',  
    				        'label' => 'Menghadap Kepada',
    				        'rules' => 'required'],
    				        
    				        [
    				        'field' => 'alamat',  
    				        'label' => 'Alamat',
    				        'rules' => 'required'],
    				        
    				        [
    				        'field' => 'untuk',  
    				        'label' => 'Untuk',
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
    				$this->session->set_flashdata('value_kantor', set_value('kantor', kantor));
    				$this->session->set_flashdata('value_pukul', set_value('pukul', pukul));
    				$this->session->set_flashdata('value_tempat', set_value('tempat', tempat));
    				$this->session->set_flashdata('value_menghadapkepada_id', set_value('menghadapkepada_id', menghadapkepada_id));
    				$this->session->set_flashdata('value_alamat', set_value('alamat', alamat));
    				$this->session->set_flashdata('value_untuk', set_value('untuk', untuk));
    				$this->session->set_flashdata('value_catatan', set_value('catatan', catatan));
    				$this->session->set_flashdata('value_lain', set_value('', '* Silakan upload kembali file lampiran'));
    				
    				//error
    				$this->session->set_flashdata('kop_id', form_error('kop_id'));
    				$this->session->set_flashdata('sifat', form_error('sifat'));
    				$this->session->set_flashdata('lampiran', form_error('lampiran'));
    				$this->session->set_flashdata('hal', form_error('hal'));
    				$this->session->set_flashdata('kantor', form_error('kantor'));
    				$this->session->set_flashdata('pukul', form_error('pukul'));
    				$this->session->set_flashdata('tempat', form_error('tempat'));
    				$this->session->set_flashdata('menghadapkepada_id', form_error('menghadapkepada_id'));
    				$this->session->set_flashdata('alamat', form_error('alamat'));
    				$this->session->set_flashdata('untuk', form_error('untuk'));
    				
    				redirect(site_url('suratkeluar/panggilan/add'));
    				
    				}
    				else {
						
					$insert = $this->panggilan_model->insert_data('surat_panggilan', $data);
					if ($insert) {

						//pengiriman surat internal atau eksternal
						$jabatan_id = $this->input->post('jabatan_id');
						$eksternal_id = $this->input->post('eksternal_id');

						//proses input internal eksternal
						internal_eksternal('panggilan',$surat_id,$jabatan_id,$eksternal_id);
						
						//pengiriman tembusan internal atau eksternal
		            	$tembusan_id = $this->input->post('tembusan_id');
		            	$tembusaneks_id = $this->input->post('tembusaneks_id');
				
		            	//proses input tembusan internal atau eksternal
		            	internal_eksternal_tembusan('panggilan',$surat_id,$tembusan_id,$tembusaneks_id);

						$datadraft = array(
							'surat_id' => $surat_id, 
							'kopId' => $kopId, 
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
	}

	public function edit()
	{
		$data['content'] = 'panggilan/panggilan_form';
		$data['kop'] = $this->db->get('kop_surat')->result();
		$data['kodesurat'] = $this->db->get('kode_surat')->result();
		$data['panggilan'] = $this->panggilan_model->edit_data($this->uri->segment(4), $this->session->userdata('opd_id'))->result();
		$data['jabatan'] = $this->db->query('select * from aparatur 
		left join levelbaru on levelbaru.level_id=aparatur.level_id 
		left join jabatan on jabatan.jabatan_id=aparatur.jabatan_id 
		where aparatur.opd_id = '.$this->session->userdata('opd_id').' and aparatur.level_id NOT IN (0,18,19) order by levelbaru.level_id ASC')->result();	
		
		$this->load->view('template', $data);
	}

	public function update()
	{
		$id = $this->input->post('id');

		//Untuk menambahkan data eksternal 
		if (isset($_POST['simpan'])) {
			
			$getEksID = $this->panggilan_model->get_id('eksternal_keluar')->result();
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
			//form validation eksternal surat panggilan
			$this->form_validation->set_rules('nama','Nama Eksternal', 'required');
        	$this->form_validation->set_rules('email','Email Eksternal', 'required');
			$this->form_validation->set_rules('required', '%s masih kosong, silakan diisikan');

			if($this->form_validation->run()===FALSE) {

			$this->session->set_flashdata('error', 'Nama dan email eksternal tidak boleh kosong');
			
			redirect(site_url('suratkeluar/panggilan/add'));
			
			}
			else {

			$insert = $this->panggilan_model->insert_data('eksternal_keluar', $data);

			if ($insert) {
				$this->session->set_flashdata('success', 'Eksternal Berhasil Dibuat');
				redirect(site_url('suratkeluar/panggilan/add'));
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
				internal_eksternal('panggilan',$id,$jabatan_id,$eksternal_id);
			}
			
			//pengiriman tembusan internal atau eksternal
	    	$tembusan_id = $this->input->post('tembusan_id');
	    	$tembusaneks_id = $this->input->post('tembusaneks_id');

	    	if (!empty($tembusan_id) OR !empty($tembusaneks_id)) {
	    		internal_eksternal_tembusan('panggilan',$id,$tembusan_id,$tembusaneks_id);
	    	}

			$file = $_FILES['lampiran_lain']['name'];

			if (empty($file)) {
				$getQuery = $this->panggilan_model->edit_data('surat_panggilan', array('id' => $id))->result();
				foreach ($getQuery as $key => $h) {
					$fileLampiran = $h->lampiran_lain;
				}
				$data = array(
					'kop_id' => htmlentities($this->input->post('kop_id')), 
					'kodesurat_id' => htmlentities($this->input->post('kodesurat_id')), 
					'tanggal' => htmlentities($this->input->post('tanggal')), 
					'nomor' => '',
					'sifat' => htmlentities($this->input->post('sifat')), 
					'lampiran' => htmlentities($this->input->post('lampiran')), 
					'hal' => htmlentities($this->input->post('hal')),  
					'lampiran_lain' => '',
					'kantor' => $this->input->post('kantor'),
					'tgl_acara' => htmlentities($this->input->post('tgl_acara')), 
					'pukul' => $this->input->post('pukul'),
					'tempat' => $this->input->post('tempat'),
					'menghadapkepada_id' => $this->input->post('menghadapkepada_id'),
					'alamat' => $this->input->post('alamat'),
					'untuk' => $this->input->post('untuk'),					
					'catatan' => $this->input->post('catatan'),					
				);
				$where = array('id' => $id);
				$update = $this->panggilan_model->update_data('surat_panggilan', $data, $where);
				if ($update) {

					$datadraft = array(
						'tanggal' => htmlentities($this->input->post('tanggal')), 
						'kopId' => $this->input->post('kop_id'), 
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
						'tgl' => $this->input->post('tgl'),
						'pukul' => $this->input->post('pukul'),
						'tempat' => $this->input->post('tempat'),
						'menghadapkepada' => $this->input->post('menghadapkepada'),
						'alamat' => $this->input->post('alamat'),
						'untuk' => $this->input->post('untuk'),	
						'catatan' => $this->input->post('catatan'),	
					);
					$where = array('id' => $id);
					$update = $this->panggilan_model->update_data('surat_panggilan', $data, $where);
					if ($update) {

						$datadraft = array( 
							'tanggal' => htmlentities($this->input->post('tanggal')), 
							'kopId' => $this->input->post('kop_id'), 
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
			$this->panggilan_model->delete_data('tembusan_surat', $whereDis);
			
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

	public function delete_tembusan()
	{
		$surat_id = $this->uri->segment(4);
		$id = $this->uri->segment(5);
		$where = array('tembusansurat_id' => $id);
		$delete = $this->panggilan_model->delete_data('tembusan_surat', $where);
		if ($delete) {
			$this->session->set_flashdata('success', 'Data Tembusan Berhasil Dihapus');
			redirect(site_url('suratkeluar/panggilan/edit/'.$surat_id));
		}else{
			$this->session->set_flashdata('error', 'Data Tembusan Gagal Dihapus');
			redirect(site_url('suratkeluar/panggilan/edit/'.$surat_id));
		}
	}	

}