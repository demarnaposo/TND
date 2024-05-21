<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengantar extends CI_Controller {
 
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
		$this->load->model('pengantar_model');
	}

	public function index()
	{
		$data['content'] = 'pengantar/pengantar_index';
		
		$tahun = $this->session->userdata('tahun');
		$opd_id = $this->session->userdata('opd_id');
		$jabatan_id = $this->session->userdata('jabatan_id');

		$data['pengantar'] = $this->pengantar_model->get_data($tahun,$opd_id,$jabatan_id)->result();		
		
		$this->load->view('template', $data);
	}

	public function add()
	{
		$data['content'] 	= 'pengantar/pengantar_form';
		$data['kop'] 		= $this->db->get('kop_surat')->result();
		$data['kodesurat'] 	= $this->db->get('kode_surat')->result();
		
		$this->load->view('template', $data);
	}

	public function insert()
	{
		$opdId	= $this->session->userdata('opd_id');
	    $kopId	= $this->input->post('kop_id');
		//untuk menambahkan data eksternal
		if (isset($_POST['simpan'])) {
			
			$getEksID = $this->pengantar_model->get_id('eksternal_keluar')->result();
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
				'opd_id' => $opdId, 
				'nama' => $this->input->post('nama'), 
				'email' => $this->input->post('email'),
				'alamat_eksternal' => $this->input->post('alamat_eksternal'),
				'tempat' => $this->input->post('tempat'), 
			);
			//form validation eksternal surat pengantar
			$this->form_validation->set_rules('nama','Nama Eksternal', 'required');
        	$this->form_validation->set_rules('email','Email Eksternal', 'required');
			$this->form_validation->set_rules('required', '%s masih kosong, silakan diisikan');

			if($this->form_validation->run()===FALSE) {

			$this->session->set_flashdata('error', 'Nama dan email eksternal tidak boleh kosong');
			
			redirect(site_url('suratkeluar/pengantar/add'));
			
			}
			else {

			$insert = $this->pengantar_model->insert_data('eksternal_keluar', $data);

			if ($insert) {
				$this->session->set_flashdata('success', 'Eksternal Berhasil Dibuat');
				redirect(site_url('suratkeluar/pengantar/add'));
			}else{
				$this->session->set_flashdata('error', 'Eksternal Gagal Dibuat');
				redirect(site_url('suratkeluar/draft/add/'));
			}
			
			}

		//Untuk menambahkan surat
		}else{
			
			$getID = $this->pengantar_model->get_id('surat_pengantar')->result();
			foreach ($getID as $key => $h) {
				$id = $h->id;
			}
			if (empty($id)) {
				$surat_id = 'PNG-1';
			}else{
				$urut = substr($id, 4)+1;
				$surat_id = 'PNG-'.$urut;
			}

			
			$file = $_FILES['lampiran_lain']['name'];
			
// 			// Tembusan
// 			$tinternal=$this->input->post('tembusan');
// 			$teksternal=$this->input->post('tembusaneks');
// 			$tembusan_int = implode(',', $this->input->post('tembusan'));
//     		$tembusan_eks = implode(',', $this->input->post('tembusaneks'));
//     		$tembusan_slug_int = str_replace(' ','-',$tembusan_int);
//     		$tembusan_slug_eks = str_replace(' ','-',$tembusan_eks);
//     		// if($this->input->post('tembusan')==''){
//     		// 	$tembusan = $tembusan_slug_eks;
//     		// }elseif($this->input->post('tembusaneks')==''){
//     		// 	$tembusan = $tembusan_slug_int;
//     		// }else{
//     		// 	$tembusan = $tembusan_slug_int.','.$tembusan_slug_eks;
//     		// }

// 			// Jika internal,eksternal tidak kosong
// 			if(!empty($tinternal) AND !empty($teksternal)){
// 				$tembusan=$tembusan_slug_int.','.$tembusan_slug_eks;
// 			// Jika internal tidak kosong tetapi eksternal kosong
// 			}else if(!empty($tinternal) AND empty($teksternal)){
// 				$tembusan=$tembusan_slug_int;
// 			// Jika eksternal tidak kosong tetapi internal kosong
// 			}else if(empty($tinternal) AND !empty($teksternal)){
// 				$tembusan=$tembusan_slug_eks;
// 			// Kosong
// 			}else if(empty($tinternal) AND empty($teksternal)){
// 				$tembusan='';
// 			}
			
			if (empty($file)) {				
				$data = array(
					'id' => $surat_id,
					'opd_id' => $this->session->userdata('opd_id'),
					'kodesurat_id' => $this->input->post('kodesurat_id'), 
					'nomor' => '',
					'tanggal' => htmlentities($this->input->post('tanggal')), 				
					'nip_penerima' => $this->input->post('nip_penerima'), 
					'nama_penerima' => $this->input->post('nama_penerima'), 
					'jabatan_penerima' => $this->input->post('jabatan_penerima'), 
					'pangkat_penerima' => $this->input->post('pangkat_penerima'), 
					'tgl_terima' => $this->input->post('tgl_terima'), 
					'tlpn' => $this->input->post('tlpn'), 
					'catatan' => $this->input->post('catatan'), 
					'tembusan' => '', 
					'lampiran_lain' => '',
				);
				$jenis = $this->input->post('jenis');
				$banyak = $this->input->post('banyak');
				$keterangan = $this->input->post('keterangan');
				for($i=0;$i < count($jenis);$i++){
					$in['surat_id'] = $surat_id;
					$in['jenis'] = $jenis[$i];
					$in['banyak'] = $banyak[$i];
					$in['keterangan'] = $keterangan[$i];
	
					$this->pengantar_model->insert_detail('sp_detail',$in);
				}
				//form validation surat pengantar [@dam|E-Gov 13.04.2022]
				$this->load->library('form_validation');	
				
				$rules =
				    [
				        [
				        'field' => 'nip_penerima',  
				        'label' => 'NIP Penerima',
				        'rules' => 'required'],
				        
				        [
				        'field' => 'nama_penerima',  
				        'label' => 'Nama Penerima',
				        'rules' => 'required'],
				        
				        [
				        'field' => 'jabatan_penerima',  
				        'label' => 'Jabatan Penerima',
				        'rules' => 'required'],
				        
				        [
				        'field' => 'pangkat_penerima',  
				        'label' => 'Pangkat Penerima',
				        'rules' => 'required'],
				        
				        [
				        'field' => 'tlpn',  
				        'label' => 'Nomor Telepon',
				        'rules' => 'required']
				        
				    ];
				$this->form_validation->set_rules($rules);
				$this->form_validation->set_message('required', '%s masih kosong, silakan diisi');				

				if($this->form_validation->run()===FALSE) {
				
				//set value
				$this->session->set_flashdata('value_tujuan', set_value('', '*Silakan pilih kembali tujuan internal-eksternal Pemkot'));
				$this->session->set_flashdata('value_nip_penerima', set_value('nip_penerima', nip_penerima)); 
				$this->session->set_flashdata('value_nama_penerima', set_value('nama_penerima', nama_penerima));
				$this->session->set_flashdata('value_jabatan_penerima', set_value('jabatan_penerima', jabatan_penerima));
				$this->session->set_flashdata('value_pangkat_penerima', set_value('pangkat_penerima', pangkat_penerima));
				$this->session->set_flashdata('value_tlpn', set_value('tlpn', tlpn));
				$this->session->set_flashdata('value_catatan', set_value('catatan', catatan));
				
				//error
				$this->session->set_flashdata('nip_penerima', form_error('nip_penerima'));
				$this->session->set_flashdata('nama_penerima', form_error('nama_penerima'));
				$this->session->set_flashdata('jabatan_penerima', form_error('jabatan_penerima'));
				$this->session->set_flashdata('pangkat_penerima', form_error('pangkat_penerima'));
				$this->session->set_flashdata('tlpn', form_error('tlpn'));
				
				redirect(site_url('suratkeluar/pengantar/add'));
				
				}
				else {

				$insert = $this->pengantar_model->insert_data('surat_pengantar', $data);
				if ($insert) {
	
					//pengiriman surat internal atau eksternal
					$jabatan_id = $this->input->post('jabatan_id');
					$eksternal_id = $this->input->post('eksternal_id');

					//proses input internal eksternal
					internal_eksternal('pengantar',$surat_id,$jabatan_id,$eksternal_id);
					
					//pengiriman tembusan internal atau eksternal
	            	$tembusan_id = $this->input->post('tembusan_id');
	            	$tembusaneks_id = $this->input->post('tembusaneks_id');
			
	            	//proses input tembusan internal atau eksternal
	            	internal_eksternal_tembusan('pengantar',$surat_id,$tembusan_id,$tembusaneks_id);

					$datadraft = array(
						'surat_id' => $surat_id,
						'kopId' => $kopId,
						'tanggal' => htmlentities($this->input->post('tanggal')), 
						'dibuat_id' => $this->session->userdata('jabatan_id'), 
						'penandatangan_id' => '',
						'verifikasi_id' => '', 
						'nama_surat' => 'Surat Pengantar', 
					);
					$this->pengantar_model->insert_data('draft', $datadraft);
	
					$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
					redirect(site_url('suratkeluar/pengantar'));
				}else{
					$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
					redirect(site_url('suratkeluar/pengantar/add'));
				}
				}
			}else {								
				$ambext = explode(".",$file);
				$ekstensi = end($ambext);
				$date = date('his');
				$nama_baru = "Lampiran-Surat-Pengantar-No-".$surat_id."-".$date;
				// $nama_baru = "Contoh";
				$nama_file = $nama_baru.".".$ekstensi;	
				$config['upload_path'] = './assets/lampiransurat/pengantar/';
				$config['allowed_types'] = 'pdf';
				$config['max_size']=40000;
				$config['file_name'] = $nama_file;
				$this->upload->initialize($config);

				if(!$this->upload->do_upload('lampiran_lain')){
					$this->session->set_flashdata('error','Upload File Gagal');
					redirect(site_url('suratkeluar/pengantar/add'));
				}else{
					$data = array(
						'id' => $surat_id,
						'opd_id' => $this->session->userdata('opd_id'),
						'kodesurat_id' => $this->input->post('kodesurat_id'), 
						'nomor' => '',
						'tanggal' => htmlentities($this->input->post('tanggal')), 				
						'nip_penerima' => $this->input->post('nip_penerima'), 
						'nama_penerima' => $this->input->post('nama_penerima'), 
						'jabatan_penerima' => $this->input->post('jabatan_penerima'), 
						'pangkat_penerima' => $this->input->post('pangkat_penerima'), 
						'tgl_terima' => $this->input->post('tgl_terima'), 
						'tlpn' => $this->input->post('tlpn'), 
						'catatan' => $this->input->post('catatan'), 
						'tembusan' => $tembusan, 
						'lampiran_lain' => $nama_file,
					);
					$jenis = $this->input->post('jenis');
					$banyak = $this->input->post('banyak');
					$keterangan = $this->input->post('keterangan');
					for($i=0;$i < count($jenis);$i++){
						$in['surat_id'] = $surat_id;
						$in['jenis'] = $jenis[$i];
						$in['banyak'] = $banyak[$i];
						$in['keterangan'] = $keterangan[$i];
		
						$this->pengantar_model->insert_detail('sp_detail',$in);
					}
					//form validation surat pengantar [@dam|E-Gov 13.04.2022]
					$this->load->library('form_validation');	
				
    				$rules =
    				    [
    				        [
    				        'field' => 'nip_penerima',  
    				        'label' => 'NIP Penerima',
    				        'rules' => 'required'],
    				        
    				        [
    				        'field' => 'nama_penerima',  
    				        'label' => 'Nama Penerima',
    				        'rules' => 'required'],
    				        
    				        [
    				        'field' => 'jabatan_penerima',  
    				        'label' => 'Jabatan Penerima',
    				        'rules' => 'required'],
    				        
    				        [
    				        'field' => 'pangkat_penerima',  
    				        'label' => 'Pangkat Penerima',
    				        'rules' => 'required'],
    				        
    				        [
    				        'field' => 'tlpn',  
    				        'label' => 'Nomor Telepon',
    				        'rules' => 'required']
    				        
    				    ];
    				$this->form_validation->set_rules($rules);
    				$this->form_validation->set_message('required', '%s masih kosong, silakan diisi');				
    
    				if($this->form_validation->run()===FALSE) {
    				
    				//set value
    				$this->session->set_flashdata('value_tujuan', set_value('', '*Silakan pilih kembali tujuan internal-eksternal Pemkot'));
    				$this->session->set_flashdata('value_nip_penerima', set_value('nip_penerima', nip_penerima)); 
    				$this->session->set_flashdata('value_nama_penerima', set_value('nama_penerima', nama_penerima));
    				$this->session->set_flashdata('value_jabatan_penerima', set_value('jabatan_penerima', jabatan_penerima));
    				$this->session->set_flashdata('value_pangkat_penerima', set_value('pangkat_penerima', pangkat_penerima));
    				$this->session->set_flashdata('value_tlpn', set_value('tlpn', tlpn));
    				$this->session->set_flashdata('value_catatan', set_value('catatan', catatan));
    				$this->session->set_flashdata('value_lain', set_value('', '* Silakan upload kembali file lampiran'));
    				
    				//error
    				$this->session->set_flashdata('nip_penerima', form_error('nip_penerima'));
    				$this->session->set_flashdata('nama_penerima', form_error('nama_penerima'));
    				$this->session->set_flashdata('jabatan_penerima', form_error('jabatan_penerima'));
    				$this->session->set_flashdata('pangkat_penerima', form_error('pangkat_penerima'));
    				$this->session->set_flashdata('tlpn', form_error('tlpn'));
    				
    				redirect(site_url('suratkeluar/pengantar/add'));
    				
    				}
    				else {

					$insert = $this->pengantar_model->insert_data('surat_pengantar', $data);
					if ($insert) {

						//pengiriman surat internal atau eksternal
						$jabatan_id = $this->input->post('jabatan_id');
						$eksternal_id = $this->input->post('eksternal_id');

						//proses input internal eksternal
						internal_eksternal('pengantar',$surat_id,$jabatan_id,$eksternal_id);
						
						//pengiriman tembusan internal atau eksternal
		            	$tembusan_id = $this->input->post('tembusan_id');
		            	$tembusaneks_id = $this->input->post('tembusaneks_id');
				
		            	//proses input tembusan internal atau eksternal
		            	internal_eksternal_tembusan('pengantar',$surat_id,$tembusan_id,$tembusaneks_id);
		
						$datadraft = array(
							'surat_id' => $surat_id,
							'kopId' => $kopId,
							'tanggal' => htmlentities($this->input->post('tanggal')), 
							'dibuat_id' => $this->session->userdata('jabatan_id'), 
							'penandatangan_id' => '',
							'verifikasi_id' => '', 
							'nama_surat' => 'Surat Pengantar', 
						);
						$this->pengantar_model->insert_data('draft', $datadraft);
		
						$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
						redirect(site_url('suratkeluar/pengantar'));
					}else{
						$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
						redirect(site_url('suratkeluar/pengantar/add'));
					}
					}
				}
			}
		}
	}

	public function insert_sp()
	{
		$jenis = $this->input->post('jenis');
		$banyak = $this->input->post('banyak');
		$keterangan = $this->input->post('keterangan');
		$surat_id = $this->input->post('surat_id');
			$in['surat_id'] = $surat_id;
			$in['jenis'] = $jenis;
			$in['banyak'] = $banyak;
			$in['keterangan'] = $keterangan;

		 	$insert = $this->pengantar_model->insert_detail('sp_detail',$in);
		
		if ($insert) {
			$this->session->set_flashdata('success', 'Data Berhasil Ditambahkan');
			redirect(site_url('suratkeluar/pengantar/edit/'.$surat_id));
		}else{
			$this->session->set_flashdata('error', 'Data Gagal Ditambahkan');
			redirect(site_url('suratkeluar/pengantar/edit/'.$surat_id));
		}
	}

	public function edit()
	{
		$data['content'] = 'pengantar/pengantar_form';
		$data['kop'] = $this->db->get('kop_surat')->result();
		$data['kodesurat'] = $this->db->get('kode_surat')->result();
		$id = $this->uri->segment(4);
		$data['sp_detail'] = $this->db->query("SELECT * FROM sp_detail WHERE surat_id = '$id'")->result();
		$data['pengantar'] = $this->pengantar_model->edit_data($this->uri->segment(4), $this->session->userdata('opd_id'))->result();
		
		$this->load->view('template', $data);
	}

	public function update()
	{
		$id 	= $this->input->post('id');
		$kopId	= $this->input->post('kop_id');
		
		//Untuk menambahkan data eksternal 
		if (isset($_POST['simpan'])) {
			
			$getEksID = $this->pengantar_model->get_id('eksternal_keluar')->result();
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
			//form validation eksternal surat pengantar
			$this->form_validation->set_rules('nama','Nama Eksternal', 'required');
        	$this->form_validation->set_rules('email','Email Eksternal', 'required');
			$this->form_validation->set_rules('required', '%s masih kosong, silakan diisikan');

			if($this->form_validation->run()===FALSE) {

			$this->session->set_flashdata('error', 'Nama dan email eksternal tidak boleh kosong');
			
			redirect(site_url('suratkeluar/pengantar/add'));
			
			}
			else {

			$insert = $this->pengantar_model->insert_data('eksternal_keluar', $data);

			if ($insert) {
				$this->session->set_flashdata('success', 'Eksternal Berhasil Dibuat');
				redirect(site_url('suratkeluar/pengantar/add'));
			}else{
				$this->session->set_flashdata('error', 'Eksternal Gagal Dibuat');
				redirect(site_url('suratkeluar/draft/add/'));
			}
			
			}

		}elseif (isset($_POST['simpan_tembusan'])) {
			$getTembusanID = $this->pengantar_model->get_id('tembusan_keluar')->result();
			foreach ($getTembusanID as $key => $tb) {
				$idTembusan = $tb->id;
			}
			if (empty($idTembusan)) {
				$tembusan_id = 'TEKS-1';
			}else{
				$urut = substr($idTembusan, 5)+1;
				$tembusan_id = 'TEKS-'.$urut;
			}
			$data = array(
				'opd_id' => $this->session->userdata('opd_id'), 
				'namatembusan' => $this->input->post('namatembusan'), 
				'emailtembusan' => $this->input->post('emailtembusan'), 
			);
			$insert = $this->pengantar_model->insert_data('tembusan_keluar', $data);

			if ($insert) {
				$this->session->set_flashdata('success', 'Tembusan Eksternal Berhasil Dibuat');
				redirect(site_url('suratkeluar/pengantar/add'));
			}else{
				$this->session->set_flashdata('error', 'Tembusan Eksternal Gagal Dibuat');
				redirect(site_url('suratkeluar/pengantar/add/'));
			}
		//Untuk mengedit surat
		}else{
// 			$tinternal=$this->input->post('tembusan');
// 			$teksternal=$this->input->post('tembusaneks');
// 			$tembusan_int = implode(',',$tinternal);
//     		$tembusan_eks = implode(',',$teksternal);
//     		$tembusan_slug_int = str_replace('','-',$tembusan_int);
//     		$tembusan_slug_eks = str_replace('','-',$tembusan_eks);
//     		$queryTembusan = $this->db->query("SELECT tembusan FROM surat_pengantar WHERE id = '$id'")->row_array();
//     		// if(empty($this->input->post('tembusan')) AND !empty($queryTembusan['tembusan'])){
//     		// 	$tembusan = $queryTembusan['tembusan'].','.$tembusan_slug_eks;
//     		// }elseif(empty($this->input->post('tembusaneks')) AND !empty($queryTembusan['tembusan'])){
//     		// 	$tembusan = $queryTembusan['tembusan'].','.$tembusan_slug_int;
//     		// }elseif(empty($queryTembusan['tembusan']) AND !empty($this->input->post('tembusan')) AND !empty($this->input->post('tembusaneks'))){
//     		//     $tembusan = $tembusan_slug_int.','.$tembusan_slug_eks;
//     		// }elseif(empty($queryTembusan['tembusan']) AND empty($this->input->post('tembusan'))){
//     		//     $tembusan = $tembusan_slug_eks;
//     		// }elseif(empty($queryTembusan['tembusan']) AND empty($this->input->post('tembusaneks'))){
//     		//     $tembusan = $tembusan_slug_int;
//     		// }else{
//     		// 	$tembusan = $queryTembusan['tembusan'].','.$tembusan_slug_int.','.$tembusan_slug_eks;
//     		// }
			
// 			// Jika internal,eksternal,query tidak kosong
// 			if(!empty($tinternal) AND !empty($teksternal) AND !empty($queryTembusan)){
// 				$tembusan=$queryTembusan['tembusan'].','.$tembusan_slug_int.','.$tembusan_slug_eks;
// 			// Jika internal, eksternal tidak kosong tetapi query kosong
// 			}else if(!empty($tinternal) AND !empty($teksternal) AND empty($queryTembusan)){
// 				$tembusan=$tembusan_slug_int.','.$tembusan_slug_eks;
// 			// Jika internal, query tidak kosong tetapi eksternal kosong
// 			}else if(!empty($tinternal) AND empty($teksternal) AND !empty($queryTembusan)){
// 				$tembusan=$queryTembusan['tembusan'].','.$tembusan_slug_int;
// 			// Jika eksternal, query tidak kosong tetapi internal kosong
// 			}else if(empty($tinternal) AND !empty($teksternal) AND !empty($queryTembusan)){
// 				$tembusan=$queryTembusan['tembusan'].','.$tembusan_slug_eks;
// 			// Jika internal tidak kosong tetapi eksternal,query kosong
// 			}else if(!empty($tinternal) AND empty($teksternal) AND empty($queryTembusan)){
// 				$tembusan=$tembusan_slug_int;
// 			// Jika eksternal tidak kosong tetapi internal,query kosong
// 			}else if(empty($tinternal) AND !empty($teksternal) AND empty($queryTembusan)){
// 				$tembusan=$tembusan_slug_eks;
// 			// Jika query tidak kosong tetapi internal,eksternal kosong
// 			}else if(empty($tinternal) AND empty($teksternal) AND !empty($queryTembusan)){
// 				$tembusan=$queryTembusan['tembusan'];
// 			// Jika kosong semua
// 			}else if(empty($tinternal) AND empty($teksternal) AND empty($queryTembusan)){
// 				$tembusan='';
// 			}
			$sp_detail_id = $this->input->post('sp_detail_id');
			
			//pengiriman surat internal atau eksternal
			$jabatan_id = $this->input->post('jabatan_id');
			$eksternal_id = $this->input->post('eksternal_id');
			if (!empty($jabatan_id) OR !empty($eksternal_id)) {
				internal_eksternal('pengantar',$id,$jabatan_id,$eksternal_id);
			}
			
			//pengiriman tembusan internal atau eksternal
	    	$tembusan_id = $this->input->post('tembusan_id');
	    	$tembusaneks_id = $this->input->post('tembusaneks_id');

	    	if (!empty($tembusan_id) OR !empty($tembusaneks_id)) {
	    		internal_eksternal_tembusan('pengantar',$id,$tembusan_id,$tembusaneks_id);
	    	}

			$file = $_FILES['lampiran_lain']['name'];
			
			if (empty($file)) {
				$jenis = $this->input->post('jenis');
				$banyak = $this->input->post('banyak');
				$keterangan = $this->input->post('keterangan');
				for($i=0;$i < count($sp_detail_id);$i++){
					$sp_id['sp_detail_id'] = $sp_detail_id[$i];
					$in['jenis'] = $jenis[$i];
					$in['banyak'] = $banyak[$i];
					$in['keterangan'] = $keterangan[$i];
					$this->pengantar_model->update_data('sp_detail',$in,$sp_id);
				}
	
				$data = array(
					'kodesurat_id' => $this->input->post('kodesurat_id'), 
					'nomor' => '',
					'tanggal' => htmlentities($this->input->post('tanggal')), 	
					'nip_penerima' => $this->input->post('nip_penerima'), 
					'nama_penerima' => $this->input->post('nama_penerima'), 
					'jabatan_penerima' => $this->input->post('jabatan_penerima'), 
					'pangkat_penerima' => $this->input->post('pangkat_penerima'), 			
					'tgl_terima' => $this->input->post('tgl_terima'), 
					'tlpn' => $this->input->post('tlpn'), 
					'catatan' => $this->input->post('catatan'), 
					'tembusan' => $tembusan, 
				);
				$where = array('id' => $id);
				$update = $this->pengantar_model->update_data('surat_pengantar', $data, $where);
				if ($update) {
	
					$datadraft = array( 
						'kopId' 	=> $kopId,
						'tanggal' 	=> $this->input->post('tanggal'),
					);
					$wheredraft = array('surat_id' => $id);
					$this->pengantar_model->update_data('draft', $datadraft, $wheredraft);
					
					$this->session->set_flashdata('success', 'Surat Berhasil Diedit');
	
					$pembuatSurat = $this->db->get_where('draft', array('surat_id' => $id))->row_array();
					
					if ($pembuatSurat['dibuat_id'] == $this->session->userdata('jabatan_id')) {
						redirect(site_url('suratkeluar/pengantar'));
					}else{
						redirect(site_url('suratkeluar/draft'));
					}
	
				}else{
					$this->session->set_flashdata('error', 'Surat Gagal Diedit');
					redirect(site_url('suratkeluar/pengantar/edit/'.$id));
				}
			}else{
				$query = $this->db->query("SELECT * FROM surat_pengantar WHERE id='$id'")->row_array();
				$ambext = explode(".",$file);
				$ekstensi = end($ambext);
				$date = date('his');
				$nama_baru = "Lampiran-Surat-Pengantar-No-".$id."-".$date;
				$nama_file = $nama_baru.".".$ekstensi;	
				$config['upload_path'] = './assets/lampiransurat/pengantar/';
				$config['allowed_types'] = 'pdf';
				$config['max_size']=40000;
				$config['file_name'] = $nama_file;
				$this->upload->initialize($config);

				if(!$this->upload->do_upload('lampiran_lain')){
					$this->session->set_flashdata('error','Upload File Gagal');
					redirect(site_url('suratkeluar/pengantar/add'));
				}else{
					@unlink('./assets/lampiransurat/pengantar/'.$query['lampiran_lain']);
					$jenis = $this->input->post('jenis');
					$banyak = $this->input->post('banyak');
					$keterangan = $this->input->post('keterangan');
					for($i=0;$i < count($sp_detail_id);$i++){
						$sp_id['sp_detail_id'] = $sp_detail_id[$i];
						$in['jenis'] = $jenis[$i];
						$in['banyak'] = $banyak[$i];
						$in['keterangan'] = $keterangan[$i];
						$this->pengantar_model->update_data('sp_detail',$in,$sp_id);
					}
		
					$data = array(
						'kodesurat_id' => $this->input->post('kodesurat_id'), 
						'nomor' => '',
						'tanggal' => htmlentities($this->input->post('tanggal')), 	
						'nip_penerima' => $this->input->post('nip_penerima'), 
						'nama_penerima' => $this->input->post('nama_penerima'), 
						'jabatan_penerima' => $this->input->post('jabatan_penerima'), 
						'pangkat_penerima' => $this->input->post('pangkat_penerima'), 			
						'tgl_terima' => $this->input->post('tgl_terima'), 
						'tlpn' => $this->input->post('tlpn'), 
						'catatan' => $this->input->post('catatan'), 
						'tembusan' => $tembusan, 
						'lampiran_lain' => $nama_file,
					);
					$where = array('id' => $id);
					$update = $this->pengantar_model->update_data('surat_pengantar', $data, $where);
					if ($update) {
		
						$datadraft = array( 
							'kopId' 	=> $kopId,
							'tanggal' 	=> $this->input->post('tanggal'),
						);
						$wheredraft = array('surat_id' => $id);
						$this->pengantar_model->update_data('draft', $datadraft, $wheredraft);
						
						$this->session->set_flashdata('success', 'Surat Berhasil Diedit');
		
						$pembuatSurat = $this->db->get_where('draft', array('surat_id' => $id))->row_array();
						
						if ($pembuatSurat['dibuat_id'] == $this->session->userdata('jabatan_id')) {
							redirect(site_url('suratkeluar/pengantar'));
						}else{
							redirect(site_url('suratkeluar/draft'));
						}
		
					}else{
						$this->session->set_flashdata('error', 'Surat Gagal Diedit');
						redirect(site_url('suratkeluar/pengantar/edit/'.$id));
					}
				}
			}

		}
	}

	public function delete()
	{
		$where = array('id' => $this->uri->segment(4));
		$sp_detail = array('surat_id' => $this->uri->segment(4));
		$deleteDetail = $this->pengantar_model->delete_data('sp_detail', $sp_detail);
		$id = $this->uri->segment(4);
		$query = $this->db->query("SELECT * FROM surat_pengantar WHERE id='$id'")->row_array();
		@unlink('./assets/lampiransurat/pengantar/'.$query['lampiran_lain']);
		$delete = $this->pengantar_model->delete_data('surat_pengantar', $where);
		if ($delete) {
			$whereDis = array('surat_id' => $this->uri->segment(4));
			$this->pengantar_model->delete_data('draft', $whereDis);
			$this->pengantar_model->delete_data('verifikasi', $whereDis);
			$this->pengantar_model->delete_data('disposisi_suratkeluar', $whereDis);
			$this->pengantar_model->delete_data('tembusan_surat', $whereDis);
			
			$this->session->set_flashdata('success', 'Surat Berhasil Dihapus');
			redirect(site_url('suratkeluar/pengantar'));
		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Dihapus');
			redirect(site_url('suratkeluar/pengantar'));
		}
	}

	public function delete_kepada()
	{
		$surat_id = $this->uri->segment(4);
		$id = $this->uri->segment(5);
		$where = array('dsuratkeluar_id' => $id);
		$delete = $this->pengantar_model->delete_data('disposisi_suratkeluar', $where);
		if ($delete) {
			$this->session->set_flashdata('success', 'Data Berhasil Dihapus');
			redirect(site_url('suratkeluar/pengantar/edit/'.$surat_id));
		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Dihapus');
			redirect(site_url('suratkeluar/pengantar/edit/'.$surat_id));
		}
	}

	public function delete_tembusan()
	{
		$surat_id = $this->uri->segment(4);
		$id = $this->uri->segment(5);
		$where = array('tembusansurat_id' => $id);
		$delete = $this->pengantar_model->delete_data('tembusan_surat', $where);
		if ($delete) {
			$this->session->set_flashdata('success', 'Data Tembusan Berhasil Dihapus');
			redirect(site_url('suratkeluar/pengantar/edit/'.$surat_id));
		}else{
			$this->session->set_flashdata('error', 'Data Tembusan Gagal Dihapus');
			redirect(site_url('suratkeluar/pengantar/edit/'.$surat_id));
		}
	}

	public function delete_data()
	{
		$surat_id = $this->uri->segment(4);
		$id = $this->uri->segment(5);
		$where = array('sp_detail_id' => $id);
		$delete = $this->pengantar_model->delete_data('sp_detail', $where);
		if ($delete) {
			$this->session->set_flashdata('success', 'Data Berhasil Dihapus');
			redirect(site_url('suratkeluar/pengantar/edit/'.$surat_id));
		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Dihapus');
			redirect(site_url('suratkeluar/pengantar/edit/'.$surat_id));
		}
	}

}