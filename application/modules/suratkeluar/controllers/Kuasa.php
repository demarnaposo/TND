<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kuasa extends CI_Controller {

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
		$this->load->model('kuasa_model');
	}

	public function index()
	{
		$data['content'] = 'kuasa/kuasa_index';

		$tahun = $this->session->userdata('tahun');
		$opd_id = $this->session->userdata('opd_id');
		$jabatan_id = $this->session->userdata('jabatan_id');

		$data['kuasa'] = $this->kuasa_model->get_data($tahun,$opd_id,$jabatan_id)->result();
		
		$this->load->view('template', $data);
	}

	public function add()
	{
		$data['content'] = 'kuasa/kuasa_form';
		$data['kodesurat'] = $this->db->get('kode_surat')->result();
		$data['kop'] = $this->db->get('kop_surat')->result();
		$data['pegawai'] = $this->db->query('select * from aparatur 
		left join levelbaru on levelbaru.level_id=aparatur.level_id 
		left join jabatan on jabatan.jabatan_id=aparatur.jabatan_id 
		where aparatur.opd_id = '.$this->session->userdata('opd_id').' and aparatur.level_id NOT IN (0,18,19) order by levelbaru.level_id ASC')->result();	
		
		$this->load->view('template', $data);
	}

	public function insert()
	{
		$kopId=$this->input->post('kop_id');
		$getID = $this->kuasa_model->get_id()->result();
		foreach ($getID as $key => $h) {
			$id = $h->id;
		}
		if (empty($id)) {
			$surat_id = 'KSA-1';
		}else{
			$urut = substr($id, 4)+1;
			$surat_id = 'KSA-'.$urut;
		}	
	
		$file = $_FILES['lampiran_lain']['name'];
			
		$p=array("<p>","</p>");
		if (empty($file)) {
			$data = array(
				'id' => $surat_id,
				'opd_id' => $this->session->userdata('opd_id'),
				'kop_id' => $this->input->post('kop_id'), 
				'kodesurat_id' => $this->input->post('kodesurat_id'), 
				'tanggal' => htmlentities($this->input->post('tanggal')),
				'nomor' =>'',  
				'pegawai_id' => $this->input->post('pegawai_id'),
				'untuk' => str_replace($p,'',$this->input->post('untuk')),
				'catatan' => str_replace($p,'',$this->input->post('catatan')),
				'lampiran_lain' => '',
			);
			//form validation surat kuasa [@dam|E-Gov 13.04.2022]
			$this->load->library('form_validation');	
				
			$rules =
			    [
			        [
			        'field' => 'kop_id',  
			        'label' => 'Kop Surat',
			        'rules' => 'required'],
				        
			        [
			        'field' => 'pegawai_id',  
			        'label' => 'Pegawai',
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
			$this->session->set_flashdata('value_pegawai_id', set_value('pegawai_id', pegawai_id));
			$this->session->set_flashdata('value_untuk', set_value('untuk', untuk));
			$this->session->set_flashdata('value_catatan', set_value('catatan', catatan));
				
			//error
			$this->session->set_flashdata('kop_id', form_error('kop_id'));
			$this->session->set_flashdata('pegawai_id', form_error('pegawai_id'));
			$this->session->set_flashdata('untuk', form_error('untuk'));
			
			redirect(site_url('suratkeluar/kuasa/add'));
			
			}
			else {
			
			$insert = $this->kuasa_model->insert_data('surat_kuasa', $data);			
			if ($insert) {

				//pengiriman surat internal atau eksternal
				$jabatan_id = $this->input->post('jabatan_id');
				$eksternal_id = $this->input->post('eksternal_id');

				//proses input internal eksternal
				internal_eksternal('kuasa',$surat_id,$jabatan_id,$eksternal_id);
					
				//pengiriman tembusan internal atau eksternal
				$tembusan_id = $this->input->post('tembusan_id');
				$tembusaneks_id = $this->input->post('tembusaneks_id');
			
				//proses input tembusan internal atau eksternal
				internal_eksternal_tembusan('kuasa',$surat_id,$tembusan_id,$tembusaneks_id);
	
				$datadraft = array(
					'surat_id' => $surat_id,
					'kopId' => $kopId,
					'tanggal' => htmlentities($this->input->post('tanggal')), 
					'dibuat_id' => $this->session->userdata('jabatan_id'), 
					'penandatangan_id' => '',
					'verifikasi_id' => '', 
					'nama_surat' => 'Surat Kuasa', 
				);
				$this->kuasa_model->insert_data('draft', $datadraft);
	
				$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
				redirect(site_url('suratkeluar/kuasa'));
			}else{
				$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
				redirect(site_url('suratkeluar/kuasa/add'));
			}
			}
		}else {
			$ambext = explode(".",$file);
			$ekstensi = end($ambext);
			$date = date('his');
			$nama_baru = "Lampiran-Surat-Kuasa-No-".$surat_id."-".$date;
			$nama_file = $nama_baru.".".$ekstensi;	
			$config['upload_path'] = './assets/lampiransurat/kuasa/';
			$config['allowed_types'] = 'pdf';
			$config['file_name'] = $nama_file;
			$this->upload->initialize($config);

			if(!$this->upload->do_upload('lampiran_lain')){
				$this->session->set_flashdata('error','Upload File Gagal');
				redirect(site_url('suratkeluar/kuasa/add'));
			}else{
				$data = array(
					'id' => $surat_id,
					'opd_id' => $this->session->userdata('opd_id'),
					'kop_id' => $this->input->post('kop_id'), 
					'kodesurat_id' => $this->input->post('kodesurat_id'), 
					'tanggal' => htmlentities($this->input->post('tanggal')),
					'nomor' =>'',  
					'pegawai_id' => $this->input->post('pegawai_id'),
					'untuk' => str_replace($p,'',$this->input->post('untuk')),
					'catatan' => $this->input->post('catatan'), 
					'lampiran_lain' => $nama_file,
				);
				//form validation surat kuasa [@dam|E-Gov 13.04.2022]
    			$this->load->library('form_validation');	
    				
    			$rules =
    			    [
    			        [
    			        'field' => 'kop_id',  
    			        'label' => 'Kop Surat',
    			        'rules' => 'required'],
    				        
    			        [
    			        'field' => 'pegawai_id',  
    			        'label' => 'Pegawai',
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
    			$this->session->set_flashdata('value_pegawai_id', set_value('pegawai_id', pegawai_id));
    			$this->session->set_flashdata('value_untuk', set_value('untuk', untuk));
    			$this->session->set_flashdata('value_catatan', set_value('catatan', catatan));
    			$this->session->set_flashdata('value_lain', set_value('', '* Silakan upload kembali file lampiran'));
    				
    			//error
    			$this->session->set_flashdata('kop_id', form_error('kop_id'));
    			$this->session->set_flashdata('pegawai_id', form_error('pegawai_id'));
    			$this->session->set_flashdata('untuk', form_error('untuk'));
    			
    			redirect(site_url('suratkeluar/kuasa/add'));
    			
    			}
    			else {

				$insert = $this->kuasa_model->insert_data('surat_kuasa', $data);
				if ($insert) {

					//pengiriman surat internal atau eksternal
    				$jabatan_id = $this->input->post('jabatan_id');
    				$eksternal_id = $this->input->post('eksternal_id');
    
    				//proses input internal eksternal
    				internal_eksternal('kuasa',$surat_id,$jabatan_id,$eksternal_id);
    					
    				//pengiriman tembusan internal atau eksternal
    	            $tembusan_id = $this->input->post('tembusan_id');
    		        $tembusaneks_id = $this->input->post('tembusaneks_id');
    			
    		        //proses input tembusan internal atau eksternal
    		        internal_eksternal_tembusan('kuasa',$surat_id,$tembusan_id,$tembusaneks_id);
		
					$datadraft = array(
						'surat_id' => $surat_id,
						'kopId' => $kopId,
						'tanggal' => htmlentities($this->input->post('tanggal')), 
						'dibuat_id' => $this->session->userdata('jabatan_id'), 
						'penandatangan_id' => '',
						'verifikasi_id' => '', 
						'nama_surat' => 'Surat Kuasa', 
					);
					$this->kuasa_model->insert_data('draft', $datadraft);
		
					$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
					redirect(site_url('suratkeluar/kuasa'));
				}else{
					$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
					redirect(site_url('suratkeluar/kuasa/add'));
				}
				}
			}
		}
	}

	public function edit()
	{
		$data['content'] = 'kuasa/kuasa_form';
		$data['kop'] = $this->db->get('kop_surat')->result();
		$data['kodesurat'] = $this->db->get('kode_surat')->result();
		$data['pegawai'] = $this->db->query('select * from aparatur 
		left join levelbaru on levelbaru.level_id=aparatur.level_id 
		left join jabatan on jabatan.jabatan_id=aparatur.jabatan_id 
		where aparatur.opd_id = '.$this->session->userdata('opd_id').' and aparatur.level_id NOT IN (0,18,19) order by levelbaru.level_id ASC')->result();	
		$data['kuasa'] = $this->kuasa_model->edit_data($this->uri->segment(4), $this->session->userdata('opd_id'))->result();
		
		$this->load->view('template', $data);
	}

	public function update()
	{
		$id = $this->input->post('id');

		//pengiriman surat internal atau eksternal
		$jabatan_id = $this->input->post('jabatan_id');
		$eksternal_id = $this->input->post('eksternal_id');
		
        	if (!empty($jabatan_id) OR !empty($eksternal_id)) {
			internal_eksternal('kuasa',$id,$jabatan_id,$eksternal_id);
		}
		
		//pengiriman tembusan internal atau eksternal
		$tembusan_id = $this->input->post('tembusan_id');
		$tembusaneks_id = $this->input->post('tembusaneks_id');

		if (!empty($tembusan_id) OR !empty($tembusaneks_id)) {
			internal_eksternal_tembusan('kuasa',$id,$tembusan_id,$tembusaneks_id);
		}

		$file = $_FILES['lampiran_lain']['name'];
			
		$p=array("<p>","</p>");
		if (empty($file)) {
			$data = array(
				'kop_id' => $this->input->post('kop_id'), 
				'kodesurat_id' => $this->input->post('kodesurat_id'), 
				'tanggal' => htmlentities($this->input->post('tanggal')),
				'nomor' =>'',  
				'pegawai_id' => $this->input->post('pegawai_id'),
				'untuk' => $this->input->post('untuk'),
				'catatan' => $this->input->post('catatan'),
			);
			$where = array('id' => $id);
			$update = $this->kuasa_model->update_data('surat_kuasa', $data, $where);
			if ($update) {
	
				$datadraft = array( 
					'tanggal' => $this->input->post('tanggal'),
					'kopId' => $this->input->post('kop_id'),
				);
				$wheredraft = array('surat_id' => $id);
				$this->kuasa_model->update_data('draft', $datadraft, $wheredraft);
				
				$this->session->set_flashdata('success', 'Surat Berhasil Diedit');
	
				$pembuatSurat = $this->db->get_where('draft', array('surat_id' => $id))->row_array();
	
				if ($pembuatSurat['dibuat_id'] == $this->session->userdata('jabatan_id')) {
					redirect(site_url('suratkeluar/kuasa'));
				}else{
					redirect(site_url('suratkeluar/draft'));
				}
	
			}else{
				$this->session->set_flashdata('error', 'Surat Gagal Diedit');
				redirect(site_url('suratkeluar/kuasa/edit/'.$id));
			}
		}else {
			$query = $this->db->query("SELECT * FROM surat_kuasa WHERE id='$id'")->row_array();
			$ambext = explode(".",$file);
			$ekstensi = end($ambext);
			$date = date('his');
			$nama_baru = "Lampiran-Surat-Kuasa-No-".$id."-".$date;
			$nama_file = $nama_baru.".".$ekstensi;	
			$config['upload_path'] = './assets/lampiransurat/kuasa/';
			$config['allowed_types'] = 'pdf';
			$config['file_name'] = $nama_file;
			$this->upload->initialize($config);

			if(!$this->upload->do_upload('lampiran_lain')){
				$this->session->set_flashdata('error','Upload File Gagal');
				redirect(site_url('suratkeluar/kuasa/add'));
			}else{
				@unlink('./assets/lampiransurat/kuasa/'.$query['lampiran_lain']);
				$data = array(
					'kop_id' => $this->input->post('kop_id'), 
					'kodesurat_id' => $this->input->post('kodesurat_id'), 
					'tanggal' => htmlentities($this->input->post('tanggal')),
					'nomor' =>'',  
					'pegawai_id' => $this->input->post('pegawai_id'),
					'untuk' => str_replace($p,'',$this->input->post('untuk')),
					'lampiran_lain' => $nama_file,
				);
				$where = array('id' => $id);
				$update = $this->kuasa_model->update_data('surat_kuasa', $data, $where);
				if ($update) {
		
					$datadraft = array( 
						'tanggal' => $this->input->post('tanggal'),
						'kopId' => $this->input->post('kop_id'),
					);
					$wheredraft = array('surat_id' => $id);
					$this->kuasa_model->update_data('draft', $datadraft, $wheredraft);
					
					$this->session->set_flashdata('success', 'Surat Berhasil Diedit');
		
					$pembuatSurat = $this->db->get_where('draft', array('surat_id' => $id))->row_array();
		
					if ($pembuatSurat['dibuat_id'] == $this->session->userdata('jabatan_id')) {
						redirect(site_url('suratkeluar/kuasa'));
					}else{
						redirect(site_url('suratkeluar/draft'));
					}
		
				}else{
					$this->session->set_flashdata('error', 'Surat Gagal Diedit');
					redirect(site_url('suratkeluar/kuasa/edit/'.$id));
				}
			}
		}
	}

	public function delete()
	{
		$where = array('id' => $this->uri->segment(4));
		$id = $this->uri->segment(4);
		$query = $this->db->query("SELECT * FROM surat_kuasa WHERE id='$id'")->row_array();
		@unlink('./assets/lampiransurat/kuasa/'.$query['lampiran_lain']);
		$delete = $this->kuasa_model->delete_data('surat_kuasa', $where);
		if ($delete) {
			$whereDis = array('surat_id' => $this->uri->segment(4));
			$this->kuasa_model->delete_data('draft', $whereDis);
			$this->kuasa_model->delete_data('verifikasi', $whereDis);
			$this->kuasa_model->delete_data('disposisi_suratkeluar', $whereDis);
			$this->kuasa_model->delete_data('tembusan_surat', $whereDis);
			
			$this->session->set_flashdata('success', 'Surat Berhasil Dihapus');
			redirect(site_url('suratkeluar/kuasa'));
		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Dihapus');
			redirect(site_url('suratkeluar/kuasa'));
		}
	}

	public function delete_kepada()
	{
		$surat_id = $this->uri->segment(4);
		$id = $this->uri->segment(5);
		$where = array('dsuratkeluar_id' => $id);
		$delete = $this->kuasa_model->delete_data('disposisi_suratkeluar', $where);
		if ($delete) {
			$this->session->set_flashdata('success', 'Data Berhasil Dihapus');
			redirect(site_url('suratkeluar/kuasa/edit/'.$surat_id));
		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Dihapus');
			redirect(site_url('suratkeluar/kuasa/edit/'.$surat_id));
		}
	}

	public function delete_tembusan()
	{
		$surat_id = $this->uri->segment(4);
		$id = $this->uri->segment(5);
		$where = array('tembusansurat_id' => $id);
		$delete = $this->kuasa_model->delete_data('tembusan_surat', $where);
		if ($delete) {
			$this->session->set_flashdata('success', 'Data Tembusan Berhasil Dihapus');
			redirect(site_url('suratkeluar/kuasa/edit/'.$surat_id));
		}else{
			$this->session->set_flashdata('error', 'Data Tembusan Gagal Dihapus');
			redirect(site_url('suratkeluar/kuasa/edit/'.$surat_id));
		}
	}

}