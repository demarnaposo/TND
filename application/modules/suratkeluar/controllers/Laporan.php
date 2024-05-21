<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {

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
		$this->load->model('laporan_model');
	}

	public function index()
	{
		$data['content'] = 'laporan/laporan_index';
		
		$tahun = $this->session->userdata('tahun');
		$opd_id = $this->session->userdata('opd_id');
		$jabatan_id = $this->session->userdata('jabatan_id');

		$data['laporan'] = $this->laporan_model->get_data($tahun,$opd_id,$jabatan_id)->result();
		
		$this->load->view('template', $data);
	}

	public function add()
	{
		$data['content'] = 'laporan/laporan_form';
		$data['kodesurat'] = $this->db->get('kode_surat')->result();
		$data['kop'] = $this->db->get('kop_surat')->result();
		
		$this->load->view('template', $data);
	}

	public function insert()
	{
		$kopId=$this->input->post('kop_id');
		//untuk menambahkan data eksternal
		if (isset($_POST['simpan'])) {
 
			$getEksID = $this->laporan_model->get_id('eksternal_keluar')->result();
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
			$insert = $this->laporan_model->insert_data('eksternal_keluar', $data);

			if ($insert) {
				$this->session->set_flashdata('success', 'Eksternal Berhasil Dibuat');
				redirect(site_url('suratkeluar/laporan/add'));
			}else{
				$this->session->set_flashdata('error', 'Eksternal Gagal Dibuat');
				redirect(site_url('suratkeluar/draft/add/'));
			}

		//Untuk menambahkan surat
		}else{

			$getID = $this->laporan_model->get_id('surat_laporan')->result();
			foreach ($getID as $key => $h) {
				$id = $h->id;
			}
			if (empty($id)) {
				$surat_id = 'LAP-1';
			}else{
				$urut = substr($id, 4)+1;
				$surat_id = 'LAP-'.$urut;
			}

			// $jabatan_id = $this->input->post('jabatan_id');
			// $eksternal_id = $this->input->post('eksternal_id');
			
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
					'opd_id' => $this->session->userdata('opd_id'),
					'kop_id' => $this->input->post('kop_id'),
					'kodesurat_id' => $this->input->post('kodesurat_id'), 
					'nomor' => '',
					'lampiran_lain' => '',
					'tanggal' => htmlentities($this->input->post('tanggal')),
					'tentang' => htmlentities($this->input->post('tentang')),
					'latarbelakang' => $this->input->post('latarbelakang'),
					'landasanhukum' => $this->input->post('landasanhukum'),
					'maksud' => $this->input->post('maksud'),
					'kegiatan' => $this->input->post('kegiatan'),
					'hasil' => $this->input->post('hasil'),
					'kesimpulan' => $this->input->post('kesimpulan'),
					'penutup' => $this->input->post('penutup'),
					'catatan' => $this->input->post('catatan'),
				);
				//form validation laporan [@dam|E-Gov 12.04.2022]
				$this->load->library('form_validation');	
				
				$rules =
				    [
				        [
				        'field' => 'kop_id',  
				        'label' => 'Kop Surat',
				        'rules' => 'required'],
				        
				        [
				        'field' => 'tentang',  
				        'label' => 'Tentang/Perihal',
				        'rules' => 'required'],
				        
				        [
				        'field' => 'latarbelakang',  
				        'label' => 'Umum/Latar Belakang',
				        'rules' => 'required'],
				        
				        [
				        'field' => 'landasanhukum',  
				        'label' => 'Landasan Hukum',
				        'rules' => 'required'],
				        
				        [
				        'field' => 'maksud',  
				        'label' => 'Maksud dan Tujuan',
				        'rules' => 'required'],
				        
				        [
				        'field' => 'kegiatan',  
				        'label' => 'Kegiatan yang Dilaksanakan',
				        'rules' => 'required'],
				        
				        [
				        'field' => 'hasil',  
				        'label' => 'Hasil yang Dicapai',
				        'rules' => 'required'],
				        
				        [
				        'field' => 'kesimpulan',  
				        'label' => 'Kesimpulan dan Saran',
				        'rules' => 'required'],
				        
				        [
				        'field' => 'penutup',  
				        'label' => 'Penutup',
				        'rules' => 'required']
				        
				    ];
				$this->form_validation->set_rules($rules);
				$this->form_validation->set_message('required', '%s masih kosong, silakan diisi');				

				if($this->form_validation->run()===FALSE) {
				
				//set value
				$this->session->set_flashdata('value_kop_id', set_value('kop_id', kop_id));
				$this->session->set_flashdata('value_tujuan', set_value('', '*Silakan pilih kembali tujuan internal-eksternal Pemkot'));
				$this->session->set_flashdata('value_tentang', set_value('tentang', tentang));
				$this->session->set_flashdata('value_latarbelakang', set_value('latarbelakang', latarbelakang));
				$this->session->set_flashdata('value_landasanhukum', set_value('landasanhukum', landasanhukum)); 
				$this->session->set_flashdata('value_maksud', set_value('maksud', maksud));
				$this->session->set_flashdata('value_kegiatan', set_value('kegiatan', kegiatan));
				$this->session->set_flashdata('value_hasil', set_value('hasil', hasil));
				$this->session->set_flashdata('value_kesimpulan', set_value('kesimpulan', kesimpulan));
				$this->session->set_flashdata('value_penutup', set_value('penutup', penutup));
				$this->session->set_flashdata('value_catatan', set_value('catatan', catatan));
				
				//error
				$this->session->set_flashdata('kop_id', form_error('kop_id'));
				$this->session->set_flashdata('tentang', form_error('tentang'));
				$this->session->set_flashdata('latarbelakang', form_error('latarbelakang'));
				$this->session->set_flashdata('landasanhukum', form_error('landasanhukum'));
				$this->session->set_flashdata('maksud', form_error('maksud'));
				$this->session->set_flashdata('kegiatan', form_error('kegiatan'));
				$this->session->set_flashdata('hasil', form_error('hasil'));
				$this->session->set_flashdata('kesimpulan', form_error('kesimpulan'));
				$this->session->set_flashdata('penutup', form_error('penutup'));
				
				redirect(site_url('suratkeluar/laporan/add'));
				
				}
				else {

				$insert = $this->laporan_model->insert_data('surat_laporan', $data);
				if ($insert) {

					//pengiriman surat internal atau eksternal
					$jabatan_id = $this->input->post('jabatan_id');
					$eksternal_id = $this->input->post('eksternal_id');

					//proses input internal eksternal
					internal_eksternal('laporan',$surat_id,$jabatan_id,$eksternal_id);
						
					//pengiriman tembusan internal atau eksternal
		            $tembusan_id = $this->input->post('tembusan_id');
			        $tembusaneks_id = $this->input->post('tembusaneks_id');
				
			        //proses input tembusan internal atau eksternal
			        internal_eksternal_tembusan('laporan',$surat_id,$tembusan_id,$tembusaneks_id);

					$datadraft = array(
						'surat_id' => $surat_id,
						'kopId' => $kopId,
						'tanggal' => $this->input->post('tanggal'), 
						'dibuat_id' => $this->session->userdata('jabatan_id'), 
						'penandatangan_id' => '',
						'verifikasi_id' => '', 
						'nama_surat' => 'Surat Laporan', 
					);
					$this->laporan_model->insert_data('draft', $datadraft);

					$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
					redirect(site_url('suratkeluar/laporan'));
				}else{
					$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
					redirect(site_url('suratkeluar/laporan/add'));
				}
				}
			}else{
	        	$ambext = explode(".",$file);
				$ekstensi = end($ambext);
				$date = date('his');
			    $nama_baru = "Lampiran-Surat-Laporan-No-".$surat_id."-".$date;
				$nama_file = $nama_baru.".".$ekstensi;
				$config['upload_path'] = './assets/lampiransurat/laporan/';
				$config['allowed_types'] = 'pdf';
				$config['file_name'] = $nama_file;

				$this->upload->initialize($config);

				if(!$this->upload->do_upload('lampiran_lain')){
					$this->session->set_flashdata('error','Upload File Gagal');
					redirect(site_url('suratkeluar/laporan/add'));
				}else{
					$data = array(
						'id' => $surat_id,
						'opd_id' => $this->session->userdata('opd_id'),
						'kop_id' => $this->input->post('kop_id'),
						'kodesurat_id' => $this->input->post('kodesurat_id'), 
						'nomor' => '',
						'lampiran_lain' => $nama_file,
						'tanggal' => htmlentities($this->input->post('tanggal')),
						'tentang' => htmlentities($this->input->post('tentang')),
						'latarbelakang' => $this->input->post('latarbelakang'),
						'landasanhukum' => $this->input->post('landasanhukum'),
						'maksud' => $this->input->post('maksud'),
						'kegiatan' => $this->input->post('kegiatan'),
						'hasil' => $this->input->post('hasil'),
						'kesimpulan' => $this->input->post('kesimpulan'),
						'penutup' => $this->input->post('penutup'),
						'catatan' => $this->input->post('catatan'),
					);
					//form validation laporan [@dam|E-Gov 12.04.2022]
    				$this->load->library('form_validation');	
    				
    				$rules =
    				    [
    				        [
    				        'field' => 'kop_id',  
    				        'label' => 'Kop Surat',
    				        'rules' => 'required'],
    				        
    				        [
    				        'field' => 'tentang',  
    				        'label' => 'Tentang/Perihal',
    				        'rules' => 'required'],
    				        
    				        [
    				        'field' => 'latarbelakang',  
    				        'label' => 'Umum/Latar Belakang',
    				        'rules' => 'required'],
    				        
    				        [
    				        'field' => 'landasanhukum',  
    				        'label' => 'Landasan Hukum',
    				        'rules' => 'required'],
    				        
    				        [
    				        'field' => 'maksud',  
    				        'label' => 'Maksud dan Tujuan',
    				        'rules' => 'required'],
    				        
    				        [
    				        'field' => 'kegiatan',  
    				        'label' => 'Kegiatan yang Dilaksanakan',
    				        'rules' => 'required'],
    				        
    				        [
    				        'field' => 'hasil',  
    				        'label' => 'Hasil yang Dicapai',
    				        'rules' => 'required'],
    				        
    				        [
    				        'field' => 'kesimpulan',  
    				        'label' => 'Kesimpulan dan Saran',
    				        'rules' => 'required'],
    				        
    				        [
    				        'field' => 'penutup',  
    				        'label' => 'Penutup',
    				        'rules' => 'required']
    				        
    				    ];
    				$this->form_validation->set_rules($rules);
    				$this->form_validation->set_message('required', '%s masih kosong, silakan diisi');				
    
    				if($this->form_validation->run()===FALSE) {
    				
    				//set value
    				$this->session->set_flashdata('value_kop_id', set_value('kop_id', kop_id));
    				$this->session->set_flashdata('value_tujuan', set_value('', '*Silakan pilih kembali tujuan internal-eksternal Pemkot'));
    				$this->session->set_flashdata('value_tentang', set_value('tentang', tentang));
    				$this->session->set_flashdata('value_latarbelakang', set_value('latarbelakang', latarbelakang));
    				$this->session->set_flashdata('value_landasanhukum', set_value('landasanhukum', landasanhukum)); 
    				$this->session->set_flashdata('value_maksud', set_value('maksud', maksud));
    				$this->session->set_flashdata('value_kegiatan', set_value('kegiatan', kegiatan));
    				$this->session->set_flashdata('value_hasil', set_value('hasil', hasil));
    				$this->session->set_flashdata('value_kesimpulan', set_value('kesimpulan', kesimpulan));
    				$this->session->set_flashdata('value_penutup', set_value('penutup', penutup));
    				$this->session->set_flashdata('value_catatan', set_value('catatan', catatan));
    				$this->session->set_flashdata('value_lain', set_value('', '* Silakan upload kembali file lampiran'));
    				
    				//error
    				$this->session->set_flashdata('kop_id', form_error('kop_id'));
    				$this->session->set_flashdata('tentang', form_error('tentang'));
    				$this->session->set_flashdata('latarbelakang', form_error('latarbelakang'));
    				$this->session->set_flashdata('landasanhukum', form_error('landasanhukum'));
    				$this->session->set_flashdata('maksud', form_error('maksud'));
    				$this->session->set_flashdata('kegiatan', form_error('kegiatan'));
    				$this->session->set_flashdata('hasil', form_error('hasil'));
    				$this->session->set_flashdata('kesimpulan', form_error('kesimpulan'));
    				$this->session->set_flashdata('penutup', form_error('penutup'));
    				
    				redirect(site_url('suratkeluar/laporan/add'));
    				
    				}
    				else {

					$insert = $this->laporan_model->insert_data('surat_laporan', $data);
					if ($insert) {

						//pengiriman surat internal atau eksternal
						$jabatan_id = $this->input->post('jabatan_id');
						$eksternal_id = $this->input->post('eksternal_id');

						//proses input internal eksternal
						internal_eksternal('laporan',$surat_id,$jabatan_id,$eksternal_id);
						
						//pengiriman tembusan internal atau eksternal
		            	$tembusan_id = $this->input->post('tembusan_id');
			            $tembusaneks_id = $this->input->post('tembusaneks_id');
				
			            //proses input tembusan internal atau eksternal
			            internal_eksternal_tembusan('laporan',$surat_id,$tembusan_id,$tembusaneks_id);

						$datadraft = array(
							'surat_id' => $surat_id, 
							'kopId' => $kopId, 
							'tanggal' => $this->input->post('tanggal'),
							'dibuat_id' => $this->session->userdata('jabatan_id'), 
							'penandatangan_id' => '',
							'verifikasi_id' => '', 
							'nama_surat' => 'Surat Laporan', 
						);
						$this->laporan_model->insert_data('draft', $datadraft);

						$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
						redirect(site_url('suratkeluar/laporan'));
					}else{
						$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
						redirect(site_url('suratkeluar/laporan/add'));
					}
					}
				}
			}

		}
	}
	public function edit()
	{
		$data['content'] = 'laporan/laporan_form';
		$data['kodesurat'] = $this->db->get('kode_surat')->result();
		$data['kop'] = $this->db->get('kop_surat')->result();
		$data['laporan'] = $this->laporan_model->edit_data($this->uri->segment(4), $this->session->userdata('opd_id'))->result();
		
		$this->load->view('template', $data);
	}

	public function update()
	{
		$id = $this->input->post('id');
		//pengiriman surat internal atau eksternal
		$jabatan_id = $this->input->post('jabatan_id');
		$eksternal_id = $this->input->post('eksternal_id');
		
        	if (!empty($jabatan_id) OR !empty($eksternal_id)) {
			internal_eksternal('laporan',$id,$jabatan_id,$eksternal_id);
		}
		
		//pengiriman tembusan internal atau eksternal
		$tembusan_id = $this->input->post('tembusan_id');
		$tembusaneks_id = $this->input->post('tembusaneks_id');

		if (!empty($tembusan_id) OR !empty($tembusaneks_id)) {
			internal_eksternal_tembusan('laporan',$id,$tembusan_id,$tembusaneks_id);
		}
		$file = $_FILES['lampiran_lain']['name'];
        
        if (empty($file)) {
			$getQuery = $this->laporan_model->edit_data('surat_laporan', array('id' => $id))->result();
			foreach ($getQuery as $key => $h) {
				$fileLampiran = $h->lampiran_lain;
			}
			$data = array(
			'kop_id' => $this->input->post('kop_id'),
			'kodesurat_id' => $this->input->post('kodesurat_id'), 
			'nomor' => '',
			'lampiran_lain' => '',
			'tanggal' => htmlentities($this->input->post('tanggal')),
			'tentang' => htmlentities($this->input->post('tentang')),
			'latarbelakang' => $this->input->post('latarbelakang'),
			'landasanhukum' => $this->input->post('landasanhukum'),
			'maksud' => $this->input->post('maksud'),
			'kegiatan' => $this->input->post('kegiatan'),
			'hasil' => $this->input->post('hasil'),
			'kesimpulan' => $this->input->post('kesimpulan'),
			'penutup' => $this->input->post('penutup'),
			'catatan' => $this->input->post('catatan'),
    		);
    		$where = array('id' => $id);
    		$update = $this->laporan_model->update_data('surat_laporan', $data, $where);
    		if ($update) {
    
    			$datadraft = array( 
    				'tanggal' => $this->input->post('tanggal'),
    				'kopId' => $this->input->post('kop_id'),
    			);
    			$wheredraft = array('surat_id' => $id);
    			$this->laporan_model->update_data('draft', $datadraft, $wheredraft);
    			
    			$this->session->set_flashdata('success', 'Surat Berhasil Diedit');
    
    			$pembuatSurat = $this->db->get_where('draft', array('surat_id' => $id))->row_array();
    			
    			if ($pembuatSurat['dibuat_id'] == $this->session->userdata('jabatan_id')) {
    				redirect(site_url('suratkeluar/laporan'));
    			}else{
    				redirect(site_url('suratkeluar/draft'));
    			}
    
    		}else{
    			$this->session->set_flashdata('error', 'Surat Gagal Diedit');
    			redirect(site_url('suratkeluar/laporan/edit/'.$id));
    		}
        }else{
            $query = $this->db->query("SELECT * FROM surat_laporan WHERE id='$id'")->row_array();
        	$ambext = explode(".",$file);
			$ekstensi = end($ambext);
			$date = date('his');
		    $nama_baru = "Lampiran-Surat-Laporan-No-".$id."-".$date;
			$nama_file = $nama_baru.".".$ekstensi;	
			$config['upload_path'] = './assets/lampiransurat/laporan/';
			$config['allowed_types'] = 'pdf';
			$config['file_name'] = $nama_file;
			$this->upload->initialize($config);

			if(!$this->upload->do_upload('lampiran_lain')){
				$this->session->set_flashdata('error','Upload File Gagal');
				redirect(site_url('suratkeluar/laporan/edit/'.$id));
			}else{
			    @unlink('./assets/lampiransurat/laporan/'.$query['lampiran_lain']);
			    $data = array(
    			'kop_id' => $this->input->post('kop_id'),
    			'kodesurat_id' => $this->input->post('kodesurat_id'), 
    			'nomor' => '',
    			'lampiran_lain' => $nama_file,
    			'tanggal' => htmlentities($this->input->post('tanggal')),
    			'tentang' => htmlentities($this->input->post('tentang')),
    			'latarbelakang' => $this->input->post('latarbelakang'),
    			'landasanhukum' => $this->input->post('landasanhukum'),
    			'maksud' => $this->input->post('maksud'),
    			'kegiatan' => $this->input->post('kegiatan'),
    			'hasil' => $this->input->post('hasil'),
    			'kesimpulan' => $this->input->post('kesimpulan'),
    			'penutup' => $this->input->post('penutup'),
    			'catatan' => $this->input->post('catatan'),
        		);
        		$where = array('id' => $id);
        		$update = $this->laporan_model->update_data('surat_laporan', $data, $where);
        		if ($update) {
        
        			$datadraft = array( 
        				'tanggal' => $this->input->post('tanggal'),
        				'kopId' => $this->input->post('kop_id'),
        			);
        			$wheredraft = array('surat_id' => $id);
        			$this->laporan_model->update_data('draft', $datadraft, $wheredraft);
        			
        			$this->session->set_flashdata('success', 'Surat Berhasil Diedit');
        
        			$pembuatSurat = $this->db->get_where('draft', array('surat_id' => $id))->row_array();
        			
        			if ($pembuatSurat['dibuat_id'] == $this->session->userdata('jabatan_id')) {
        				redirect(site_url('suratkeluar/laporan'));
        			}else{
        				redirect(site_url('suratkeluar/draft'));
        			}
        
        		}else{
        			$this->session->set_flashdata('error', 'Surat Gagal Diedit');
        			redirect(site_url('suratkeluar/laporan/edit/'.$id));
        		}
			}
        }
	}

	public function delete()
	{
		$where = array('id' => $this->uri->segment(4));
		$delete = $this->laporan_model->delete_data('surat_laporan', $where);
		if ($delete) {
			$whereDis = array('surat_id' => $this->uri->segment(4));
			$this->laporan_model->delete_data('draft', $whereDis);
			$this->laporan_model->delete_data('verifikasi', $whereDis);
			$this->laporan_model->delete_data('disposisi_suratkeluar', $whereDis);
			$this->laporan_model->delete_data('tembusan_surat', $whereDis);
			
			$this->session->set_flashdata('success', 'Surat Berhasil Dihapus');
			redirect(site_url('suratkeluar/laporan'));
		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Dihapus');
			redirect(site_url('suratkeluar/laporan'));
		}
	}

	public function delete_kepada()
	{
		$surat_id = $this->uri->segment(4);
		$id = $this->uri->segment(5);
		$where = array('dsuratkeluar_id' => $id);
		$delete = $this->laporan_model->delete_data('disposisi_suratkeluar', $where);
		if ($delete) {
			$this->session->set_flashdata('success', 'Data Berhasil Dihapus');
			redirect(site_url('suratkeluar/laporan/edit/'.$surat_id));
		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Dihapus');
			redirect(site_url('suratkeluar/laporan/edit/'.$surat_id));
		}
	}

	public function delete_tembusan()
	{
		$surat_id = $this->uri->segment(4);
		$id = $this->uri->segment(5);
		$where = array('tembusansurat_id' => $id);
		$delete = $this->laporan_model->delete_data('tembusan_surat', $where);
		if ($delete) {
			$this->session->set_flashdata('success', 'Data Tembusan Berhasil Dihapus');
			redirect(site_url('suratkeluar/laporan/edit/'.$surat_id));
		}else{
			$this->session->set_flashdata('error', 'Data Tembusan Gagal Dihapus');
			redirect(site_url('suratkeluar/laporan/edit/'.$surat_id));
		}
	}

}