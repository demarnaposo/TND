<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notulen extends CI_Controller {

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
		$this->load->model('notulen_model');
	}

	public function index()
	{
		$data['content'] = 'notulen/notulen_index';
		
		$tahun = $this->session->userdata('tahun');
		$opd_id = $this->session->userdata('opd_id');
		$jabatan_id = $this->session->userdata('jabatan_id');

		$data['notulen'] = $this->notulen_model->get_data($tahun,$opd_id,$jabatan_id)->result();
		
		$this->load->view('template', $data);
	}

	public function add()
	{
		$data['content'] = 'notulen/notulen_form';
		$data['kodesurat'] = $this->db->get('kode_surat')->result();
		$data['pegawai'] = $this->db->query('select * from aparatur 
		left join levelbaru on levelbaru.level_id=aparatur.level_id 
		left join jabatan on jabatan.jabatan_id=aparatur.jabatan_id 
		where aparatur.opd_id = '.$this->session->userdata('opd_id').' and aparatur.level_id NOT IN (0,18,19) order by levelbaru.level_id ASC')->result();			
		$this->load->view('template', $data);
	}

	public function insert()
	{
		$getID = $this->notulen_model->get_id()->result();

		// Update @MpikEgov 14 Januari 2023
	    $opdId=$this->session->userdata('opd_id');
	    if($opdId == 4){
	        $kopId=4;
	    }elseif($opdId == 2){
	        $kopId=1;
	    }elseif($opdId == 3){
	        $kopId=3;
	    }else{
	        $kopId=2;
	    }
	   // END Update 14 Januari 2023

		foreach ($getID as $key => $h) {
			$id = $h->id;
		}
		if (empty($id)) {
			$surat_id = 'NTL-1';
		}else{
			$urut = substr($id, 4)+1;
			$surat_id = 'NTL-'.$urut;
		}

		$datapeserta = implode(",", $this->input->post('peserta_id'));

		$file = $_FILES['lampiran_lain']['name'];

		if (empty($file)) {
			$data = array(
				'id' => $surat_id,
				'opd_id' => $this->session->userdata('opd_id'),
				'kodesurat_id' => $this->input->post('kodesurat_id'), 
				'tanggal' => htmlentities($this->input->post('tanggal')),
				'rapat' => $this->input->post('rapat'),
				'nomor' => '',
				'waktu_pgl' => htmlentities($this->input->post('waktu_pgl')),
				'wakturapat' => htmlentities($this->input->post('wakturapat')),
				'acara' => $this->input->post('acara'),
				'ketua_id' => $this->input->post('ketua_id'),
				'sekertaris_id' => $this->input->post('sekertaris_id'),
				'pencatat_id' => $this->input->post('pencatat_id'),
				'peserta_id' => $datapeserta,
				'kegiatan_rapat' => $this->input->post('kegiatan_rapat'),
				'pembukaan' => $this->input->post('pembukaan'),
				'pembahasan' => $this->input->post('pembahasan'),
				'peraturan' => $this->input->post('peraturan'),
				'catatan' => $this->input->post('catatan'),
				'lampiran_lain' => '',
			);
			//form validation notulen [@dam|E-Gov 17.04.2022]
			$this->load->library('form_validation');	
				
			$rules =
			    [
			        [
			        'field' => 'rapat',  
			        'label' => 'Sidang/Rapat',
			        'rules' => 'required'],
				        
			        [
			        'field' => 'acara',  
			        'label' => 'Nama Acara',
			        'rules' => 'required'],
				        
			        [
			        'field' => 'ketua_id',  
			        'label' => 'Ketua Sidang',
			        'rules' => 'required'],
				        
			        [
			        'field' => 'sekertaris_id',  
			        'label' => 'Sekretaris Sidang',
			        'rules' => 'required'],
			        
			        [
			        'field' => 'pencatat_id',  
			        'label' => 'Pencatat',
			        'rules' => 'required'],
			        
			        [
			        'field' => 'kegiatan_rapat',  
			        'label' => 'Kegiatan Rapat',
			        'rules' => 'required'],
			        
			        [
			        'field' => 'pembukaan',  
			        'label' => 'Pembukaan',
			        'rules' => 'required'],
			        
			        [
			        'field' => 'pembahasan',  
			        'label' => 'Pembahasan',
			        'rules' => 'required'],
				        
			        [
			        'field' => 'peraturan',  
			        'label' => 'Peraturan',
			        'rules' => 'required']
			        
			    ];
			$this->form_validation->set_rules($rules);
			$this->form_validation->set_message('required', '%s masih kosong, silakan diisi');				

			if($this->form_validation->run()===FALSE) {
				
			//set value
			$this->session->set_flashdata('value_rapat', set_value('rapat', rapat));
			$this->session->set_flashdata('value_tujuan', set_value('', '*Silakan pilih kembali tujuan internal-eksternal Pemkot'));
			$this->session->set_flashdata('value_acara', set_value('acara', acara));
			$this->session->set_flashdata('value_ketua_id', set_value('ketua_id', ketua_id));
			$this->session->set_flashdata('value_sekertaris_id', set_value('sekertaris_id', sekertaris_id)); 
			$this->session->set_flashdata('value_pencatat_id', set_value('pencatat_id', pencatat_id));
			$this->session->set_flashdata('value_peserta', set_value('', '*Silakan pilih kembali peserta sidang rapat'));
			$this->session->set_flashdata('value_kegiatan_rapat', set_value('kegiatan_rapat', kegiatan_rapat));
			$this->session->set_flashdata('value_pembukaan', set_value('pembukaan', pembukaan));
			$this->session->set_flashdata('value_pembahasan', set_value('pembahasan', pembahasan));
			$this->session->set_flashdata('value_peraturan', set_value('peraturan', peraturan));
			$this->session->set_flashdata('value_catatan', set_value('catatan', catatan));
			
			//error
			$this->session->set_flashdata('rapat', form_error('rapat'));
			$this->session->set_flashdata('acara', form_error('acara'));
			$this->session->set_flashdata('ketua_id', form_error('ketua_id'));
			$this->session->set_flashdata('sekertaris_id', form_error('sekertaris_id'));
			$this->session->set_flashdata('pencatat_id', form_error('pencatat_id'));
			$this->session->set_flashdata('kegiatan_rapat', form_error('kegiatan_rapat'));
			$this->session->set_flashdata('pembukaan', form_error('pembukaan'));
			$this->session->set_flashdata('pembahasan', form_error('pembahasan'));
			$this->session->set_flashdata('peraturan', form_error('peraturan'));
				
			redirect(site_url('suratkeluar/notulen/add'));
				
			}
			else {

			$insert = $this->notulen_model->insert_data('surat_notulen', $data);
			if ($insert) {

				//pengiriman surat internal atau eksternal
        		$jabatan_id = $this->input->post('jabatan_id');
                $eksternal_id = $this->input->post('eksternal_id');
        			
        		//proses input internal atau eksternal
        		internal_eksternal('notulen',$surat_id,$jabatan_id,$eksternal_id);
        			
        		//pengiriman tembusan internal atau eksternal
        		$tembusan_id = $this->input->post('tembusan_id');
        		$tembusaneks_id = $this->input->post('tembusaneks_id');
        			
        		//proses input tembusan internal atau eksternal
        		internal_eksternal_tembusan('notulen',$surat_id,$tembusan_id,$tembusaneks_id);
	
				$datadraft = array(
					'surat_id' => $surat_id,
					'kopId' => $kopId,
					'tanggal' => htmlentities($this->input->post('tanggal')), 
					'dibuat_id' => $this->session->userdata('jabatan_id'), 
					'penandatangan_id' => '',
					'verifikasi_id' => '', 
					'nama_surat' => 'Surat Notulen', 
				);
				$this->notulen_model->insert_data('draft', $datadraft);
	
				$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
				redirect(site_url('suratkeluar/notulen'));
			}else{
				$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
				redirect(site_url('suratkeluar/notulen/add'));
			}
			}
		}else {
			$ambext = explode(".",$file);
			$ekstensi = end($ambext);
			$date = date('his');
			$nama_baru = "Lampiran-Surat-Notulen-No-".$surat_id."-".$date;
			$nama_file = $nama_baru.".".$ekstensi;	
			$config['upload_path'] = './assets/lampiransurat/notulen/';
			$config['allowed_types'] = 'pdf';
			$config['file_name'] = $nama_file;
			$this->upload->initialize($config);

			if(!$this->upload->do_upload('lampiran_lain')){
				$this->session->set_flashdata('error','Upload File Gagal');
				redirect(site_url('suratkeluar/notulen/add'));
			}else{
				$data = array(
					'id' => $surat_id,
					'opd_id' => $this->session->userdata('opd_id'),
					'kodesurat_id' => $this->input->post('kodesurat_id'), 
					'tanggal' => htmlentities($this->input->post('tanggal')),
					'rapat' => $this->input->post('rapat'),
					'nomor' => '',
					'waktu_pgl' => htmlentities($this->input->post('waktu_pgl')),
					'wakturapat' => htmlentities($this->input->post('wakturapat')),
					'acara' => $this->input->post('acara'),
					'ketua_id' => $this->input->post('ketua_id'),
					'sekertaris_id' => $this->input->post('sekertaris_id'),
					'pencatat_id' => $this->input->post('pencatat_id'),
					'peserta_id' => $datapeserta,
					'kegiatan_rapat' => $this->input->post('kegiatan_rapat'),
					'pembukaan' => $this->input->post('pembukaan'),
					'pembahasan' => $this->input->post('pembahasan'),
					'peraturan' => $this->input->post('peraturan'),
					'catatan' => $this->input->post('catatan'),
					'lampiran_lain' => $nama_file,
				);
				//form validation notulen [@dam|E-Gov 17.04.2022]
    			$this->load->library('form_validation');	
    				
    			$rules =
    			    [
    			        [
    			        'field' => 'rapat',  
    			        'label' => 'Sidang/Rapat',
    			        'rules' => 'required'],
    				        
    			        [
    			        'field' => 'acara',  
    			        'label' => 'Nama Acara',
    			        'rules' => 'required'],
    				        
    			        [
    			        'field' => 'ketua_id',  
    			        'label' => 'Ketua Sidang',
    			        'rules' => 'required'],
    				        
    			        [
    			        'field' => 'sekertaris_id',  
    			        'label' => 'Sekretaris Sidang',
    			        'rules' => 'required'],
    			        
    			        [
    			        'field' => 'pencatat_id',  
    			        'label' => 'Pencatat',
    			        'rules' => 'required'],
    			        
    			        [
    			        'field' => 'kegiatan_rapat',  
    			        'label' => 'Kegiatan Rapat',
    			        'rules' => 'required'],
    			        
    			        [
    			        'field' => 'pembukaan',  
    			        'label' => 'Pembukaan',
    			        'rules' => 'required'],
    			        
    			        [
    			        'field' => 'pembahasan',  
    			        'label' => 'Pembahasan',
    			        'rules' => 'required'],
    				        
    			        [
    			        'field' => 'peraturan',  
    			        'label' => 'Peraturan',
    			        'rules' => 'required']
    			        
    			    ];
    			$this->form_validation->set_rules($rules);
    			$this->form_validation->set_message('required', '%s masih kosong, silakan diisi');				
    
    			if($this->form_validation->run()===FALSE) {
    				
    			//set value
    			$this->session->set_flashdata('value_rapat', set_value('rapat', rapat));
    			$this->session->set_flashdata('value_tujuan', set_value('', '*Silakan pilih kembali tujuan internal-eksternal Pemkot'));
    			$this->session->set_flashdata('value_acara', set_value('acara', acara));
    			$this->session->set_flashdata('value_ketua_id', set_value('ketua_id', ketua_id));
    			$this->session->set_flashdata('value_sekertaris_id', set_value('sekertaris_id', sekertaris_id)); 
    			$this->session->set_flashdata('value_pencatat_id', set_value('pencatat_id', pencatat_id));
    			$this->session->set_flashdata('value_peserta', set_value('', '*Silakan pilih kembali peserta sidang rapat'));
    			$this->session->set_flashdata('value_kegiatan_rapat', set_value('kegiatan_rapat', kegiatan_rapat));
    			$this->session->set_flashdata('value_pembukaan', set_value('pembukaan', pembukaan));
    			$this->session->set_flashdata('value_pembahasan', set_value('pembahasan', pembahasan));
    			$this->session->set_flashdata('value_peraturan', set_value('peraturan', peraturan));
    			$this->session->set_flashdata('value_catatan', set_value('catatan', catatan));
    			$this->session->set_flashdata('value_lain', set_value('', '* Silakan upload kembali file lampiran'));
    			
    			//error
    			$this->session->set_flashdata('rapat', form_error('rapat'));
    			$this->session->set_flashdata('acara', form_error('acara'));
    			$this->session->set_flashdata('ketua_id', form_error('ketua_id'));
    			$this->session->set_flashdata('sekertaris_id', form_error('sekertaris_id'));
    			$this->session->set_flashdata('pencatat_id', form_error('pencatat_id'));
    			$this->session->set_flashdata('kegiatan_rapat', form_error('kegiatan_rapat'));
    			$this->session->set_flashdata('pembukaan', form_error('pembukaan'));
    			$this->session->set_flashdata('pembahasan', form_error('pembahasan'));
    			$this->session->set_flashdata('peraturan', form_error('peraturan'));
    				
    			redirect(site_url('suratkeluar/notulen/add'));
    				
    			}
    			else {

				$insert = $this->notulen_model->insert_data('surat_notulen', $data);
				if ($insert) {

					//pengiriman surat internal atau eksternal
            		$jabatan_id = $this->input->post('jabatan_id');
                    $eksternal_id = $this->input->post('eksternal_id');
            			
            		//proses input internal atau eksternal
            		internal_eksternal('notulen',$surat_id,$jabatan_id,$eksternal_id);
            			
            		//pengiriman tembusan internal atau eksternal
            		$tembusan_id = $this->input->post('tembusan_id');
            		$tembusaneks_id = $this->input->post('tembusaneks_id');
            			
            		//proses input tembusan internal atau eksternal
            		internal_eksternal_tembusan('notulen',$surat_id,$tembusan_id,$tembusaneks_id);
		
					$datadraft = array(
						'surat_id' => $surat_id,
						'kopId' => $kopId,
						'tanggal' => htmlentities($this->input->post('tanggal')), 
						'dibuat_id' => $this->session->userdata('jabatan_id'), 
						'penandatangan_id' => '',
						'verifikasi_id' => '', 
						'nama_surat' => 'Surat Notulen', 
					);
					$this->notulen_model->insert_data('draft', $datadraft);
		
					$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
					redirect(site_url('suratkeluar/notulen'));
				}else{
					$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
					redirect(site_url('suratkeluar/notulen/add'));
				}
				}
			}
		}
	}

	public function edit()
	{
		$data['content'] = 'notulen/notulen_form';
		$data['kodesurat'] = $this->db->get('kode_surat')->result();
		$data['pegawai'] = $this->db->query('select * from aparatur 
		left join levelbaru on levelbaru.level_id=aparatur.level_id 
		left join jabatan on jabatan.jabatan_id=aparatur.jabatan_id 
		where aparatur.opd_id = '.$this->session->userdata('opd_id').' and aparatur.level_id NOT IN (0,18,19) order by levelbaru.level_id ASC')->result();	
		$data['notulen'] = $this->notulen_model->edit_data($this->uri->segment(4), $this->session->userdata('opd_id'))->result();
		
		$this->load->view('template', $data);
	}

	public function update()
	{
		$id = $this->input->post('id');
		$datapeserta = implode(",", $this->input->post('peserta_id'));

		//pengiriman surat internal atau eksternal
		$jabatan_id = $this->input->post('jabatan_id');
		$eksternal_id = $this->input->post('eksternal_id');
		
        	if (!empty($jabatan_id) OR !empty($eksternal_id)) {
			internal_eksternal('notulen',$id,$jabatan_id,$eksternal_id);
		}
		
		//pengiriman tembusan internal atau eksternal
		$tembusan_id = $this->input->post('tembusan_id');
		$tembusaneks_id = $this->input->post('tembusaneks_id');

			if (!empty($tembusan_id) OR !empty($tembusaneks_id)) {
			internal_eksternal_tembusan('notulen',$id,$tembusan_id,$tembusaneks_id);
		}

		$file = $_FILES['lampiran_lain']['name'];

		if (empty($file)) {
			$data = array(
				'kodesurat_id' => $this->input->post('kodesurat_id'), 
				'tanggal' => htmlentities($this->input->post('tanggal')),
				'rapat' => $this->input->post('rapat'),
				'nomor' => '',
				'waktu_pgl' => htmlentities($this->input->post('waktu_pgl')),
				'wakturapat' => htmlentities($this->input->post('wakturapat')),
				'acara' => $this->input->post('acara'),
				'ketua_id' => $this->input->post('ketua_id'),
				'sekertaris_id' => $this->input->post('sekertaris_id'),
				'pencatat_id' => $this->input->post('pencatat_id'),
				'peserta_id' => $datapeserta,
				'kegiatan_rapat' => $this->input->post('kegiatan_rapat'),
				'pembukaan' => $this->input->post('pembukaan'),
				'pembahasan' => $this->input->post('pembahasan'),
				'peraturan' => $this->input->post('peraturan'),
				'catatan' => $this->input->post('catatan'),
			);
			$where = array('id' => $id);
			$update = $this->notulen_model->update_data('surat_notulen', $data, $where);
			if ($update) {
	
				$datadraft = array( 
					'tanggal' => $this->input->post('tanggal'),
				);
				$wheredraft = array('surat_id' => $id);
				$this->notulen_model->update_data('draft', $datadraft, $wheredraft);
				
				$this->session->set_flashdata('success', 'Surat Berhasil Diedit');
	
				$pembuatSurat = $this->db->get_where('draft', array('surat_id' => $id))->row_array();
	
				if ($pembuatSurat['dibuat_id'] == $this->session->userdata('jabatan_id')) {
					redirect(site_url('suratkeluar/notulen'));
				}else{
					redirect(site_url('suratkeluar/draft'));
				}
	
			}else{
				$this->session->set_flashdata('error', 'Surat Gagal Diedit');
				redirect(site_url('suratkeluar/notulen/edit/'.$id));
			}
		}else {
			$query = $this->db->query("SELECT * FROM surat_notulen WHERE id='$id'")->row_array();
			$ambext = explode(".",$file);
			$ekstensi = end($ambext);
			$date = date('his');
			$nama_baru = "Lampiran-Surat-Notulen-No-".$id."-".$date;
			$nama_file = $nama_baru.".".$ekstensi;	
			$config['upload_path'] = './assets/lampiransurat/notulen/';
			$config['allowed_types'] = 'pdf';
			$config['file_name'] = $nama_file;
			$this->upload->initialize($config);

			if(!$this->upload->do_upload('lampiran_lain')){
				$this->session->set_flashdata('error','Upload File Gagal');
				redirect(site_url('suratkeluar/notulen/add'));
			}else{
				@unlink('./assets/lampiransurat/notulen/'.$query['lampiran_lain']);
				$data = array(
					'kodesurat_id' => $this->input->post('kodesurat_id'), 
					'tanggal' => htmlentities($this->input->post('tanggal')),
					'rapat' => $this->input->post('rapat'),
					'nomor' => '',
					'waktu_pgl' => htmlentities($this->input->post('waktu_pgl')),
					'wakturapat' => htmlentities($this->input->post('wakturapat')),
					'acara' => $this->input->post('acara'),
					'ketua_id' => $this->input->post('ketua_id'),
					'sekertaris_id' => $this->input->post('sekertaris_id'),
					'pencatat_id' => $this->input->post('pencatat_id'),
					'peserta_id' => $datapeserta,
					'kegiatan_rapat' => $this->input->post('kegiatan_rapat'),
					'pembukaan' => $this->input->post('pembukaan'),
					'pembahasan' => $this->input->post('pembahasan'),
					'peraturan' => $this->input->post('peraturan'),
					'catatan' => $this->input->post('catatan'),
					'lampiran_lain' => $nama_file,
				);
				$where = array('id' => $id);
				$update = $this->notulen_model->update_data('surat_notulen', $data, $where);
				if ($update) {
		
					$datadraft = array( 
						'tanggal' => $this->input->post('tanggal'),
					);
					$wheredraft = array('surat_id' => $id);
					$this->notulen_model->update_data('draft', $datadraft, $wheredraft);
					
					$this->session->set_flashdata('success', 'Surat Berhasil Diedit');
		
					$pembuatSurat = $this->db->get_where('draft', array('surat_id' => $id))->row_array();
		
					if ($pembuatSurat['dibuat_id'] == $this->session->userdata('jabatan_id')) {
						redirect(site_url('suratkeluar/notulen'));
					}else{
						redirect(site_url('suratkeluar/draft'));
					}
		
				}else{
					$this->session->set_flashdata('error', 'Surat Gagal Diedit');
					redirect(site_url('suratkeluar/notulen/edit/'.$id));
				}
			}
		}
	}

	public function delete()
	{
		$where = array('id' => $this->uri->segment(4));
		$id = $this->uri->segment(4);
		$query = $this->db->query("SELECT * FROM surat_notulen WHERE id='$id'")->row_array();
		@unlink('./assets/lampiransurat/notulen/'.$query['lampiran_lain']);
		$delete = $this->notulen_model->delete_data('surat_notulen', $where);
		if ($delete) {
			$whereDis = array('surat_id' => $this->uri->segment(4));
			$this->notulen_model->delete_data('draft', $whereDis);
			$this->notulen_model->delete_data('verifikasi', $whereDis);
			$this->notulen_model->delete_data('disposisi_suratkeluar', $whereDis);
			$this->notulen_model->delete_data('tembusan_surat', $whereDis);
			
			$this->session->set_flashdata('success', 'Surat Berhasil Dihapus');
			redirect(site_url('suratkeluar/notulen'));
		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Dihapus');
			redirect(site_url('suratkeluar/notulen'));
		}
	}

	public function delete_kepada()
	{
		$surat_id = $this->uri->segment(4);
		$id = $this->uri->segment(5);
		$where = array('dsuratkeluar_id' => $id);
		$delete = $this->notulen_model->delete_data('disposisi_suratkeluar', $where);
		if ($delete) {
			$this->session->set_flashdata('success', 'Data Berhasil Dihapus');
			redirect(site_url('suratkeluar/notulen/edit/'.$surat_id));
		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Dihapus');
			redirect(site_url('suratkeluar/notulen/edit/'.$surat_id));
		}
	}

	public function delete_tembusan()
	{
		$surat_id = $this->uri->segment(4);
		$id = $this->uri->segment(5);
		$where = array('tembusansurat_id' => $id);
		$delete = $this->notulen_model->delete_data('tembusan_surat', $where);
		if ($delete) {
			$this->session->set_flashdata('success', 'Data Tembusan Berhasil Dihapus');
			redirect(site_url('suratkeluar/notulen/edit/'.$surat_id));
		}else{
			$this->session->set_flashdata('error', 'Data Tembusan Gagal Dihapus');
			redirect(site_url('suratkeluar/notulen/edit/'.$surat_id));
		}
	}

}