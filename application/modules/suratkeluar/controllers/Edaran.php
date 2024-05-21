<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edaran extends CI_Controller {

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
		$this->load->model('edaran_model');
	} 

	public function index()
	{
		$data['content'] = 'edaran/edaran_index';
		
		$tahun = $this->session->userdata('tahun');
		$opd_id = $this->session->userdata('opd_id');
		$jabatan_id = $this->session->userdata('jabatan_id');

		$data['edaran'] = $this->edaran_model->get_data($tahun,$opd_id,$jabatan_id)->result();
		
		$this->load->view('template', $data);
	}

	public function add()
	{	
		$data['content'] = 'edaran/edaran_form';
		$data['kop'] = $this->db->get('kop_surat')->result();
		$data['kodesurat'] = $this->db->get('kode_surat')->result();

		$this->load->view('template', $data);
	}

	public function insert()
	{
		$kopId=$this->input->post('kop_id');
		//untuk menambahkan data eksternal
		if (isset($_POST['simpan'])) {
 
			$getEksID = $this->edaran_model->get_id('eksternal_keluar')->result();
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
			//form validation eksternal surat edaran
			$this->form_validation->set_rules('nama','Nama Eksternal', 'required');
        	$this->form_validation->set_rules('email','Email Eksternal', 'required');
			$this->form_validation->set_rules('required', '%s masih kosong, silakan diisikan');

			if($this->form_validation->run()===FALSE) {

			$this->session->set_flashdata('error', 'Nama dan email eksternal tidak boleh kosong');
			
			redirect(site_url('suratkeluar/edaran/add'));
			
			}
			else {

			$insert = $this->edaran_model->insert_data('eksternal_keluar', $data);

			if ($insert) {
				$this->session->set_flashdata('success', 'Eksternal Berhasil Dibuat');
				redirect(site_url('suratkeluar/edaran/add'));
			}else{
				$this->session->set_flashdata('error', 'Eksternal Gagal Dibuat');
				redirect(site_url('suratkeluar/draft/add/'));
			}
	
			}
		
			//untuk menambahkan tembusan eksternal 		
		}elseif (isset($_POST['simpan_tembusan'])) {
			$getTembusanID = $this->edaran_model->get_id('tembusan_keluar')->result();
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
						
			$insert = $this->edaran_model->insert_data('tembusan_keluar', $data);

			if ($insert) {
				$this->session->set_flashdata('success', 'Tembusan Eksternal Berhasil Dibuat');
				redirect(site_url('suratkeluar/edaran/add'));
			}else{
				$this->session->set_flashdata('error', 'Tembusan Eksternal Gagal Dibuat');
				redirect(site_url('suratkeluar/draft/add/'));
			}

		//Untuk menambahkan surat
		}else{

			$getID = $this->edaran_model->get_id('surat_edaran')->result();
			foreach ($getID as $key => $h) {
				$id = $h->id;
			}
			if (empty($id)) {
				$surat_id = 'SE-1';
			}else{
				$urut = substr($id, 3)+1;
				$surat_id = 'SE-'.$urut;
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

			// $tinternal=$this->input->post('tembusan');
			// $teksternal=$this->input->post('tembusaneks');
			// $tembusan_int = implode(',', $this->input->post('tembusan'));
    		// $tembusan_eks = implode(',', $this->input->post('tembusaneks'));
    		// $tembusan_slug_int = str_replace(' ','-',$tembusan_int);
    		// $tembusan_slug_eks = str_replace(' ','-',$tembusan_eks);
    		// // if($this->input->post('tembusan')==''){
    		// // 	$tembusan = $tembusan_slug_eks;
    		// // }elseif($this->input->post('tembusaneks')==''){
    		// // 	$tembusan = $tembusan_slug_int;
    		// // }else{
    		// // 	$tembusan = $tembusan_slug_int.','.$tembusan_slug_eks;
    		// // }

			// // Jika internal,eksternal tidak kosong
			// if(!empty($tinternal) AND !empty($teksternal)){
			// 	$tembusan=$tembusan_slug_int.','.$tembusan_slug_eks;
			// // Jika internal tidak kosong tetapi eksternal kosong
			// }else if(!empty($tinternal) AND empty($teksternal)){
			// 	$tembusan=$tembusan_slug_int;
			// // Jika eksternal tidak kosong tetapi internal kosong
			// }else if(empty($tinternal) AND !empty($teksternal)){
			// 	$tembusan=$tembusan_slug_eks;
			// // Kosong
			// }else if(empty($tinternal) AND empty($teksternal)){
			// 	$tembusan='';
			// }

			if (empty($file)) {
				$data = array(
					'id' => $surat_id,
					'kop_id' => $this->input->post('kop_id'), 
					'opd_id' => $this->session->userdata('opd_id'),
					'kodesurat_id' => $this->input->post('kodesurat_id'), 
					'tanggal' => $this->input->post('tanggal'), 
					'nomor' => '',
					'tentang' => $this->input->post('tentang'),  
					'tembusan' => '',
					'isi' => $this->input->post('isi'),
					'catatan' => $this->input->post('catatan'),   
					'lampiran_lain' => '',
				);
				//form validation surat edaran [@dam|E-Gov 10.04.2022]
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
				$this->session->set_flashdata('value_tentang', set_value('tentang', tentang)); 
				$this->session->set_flashdata('value_isi', set_value('isi', isi));
				$this->session->set_flashdata('value_catatan', set_value('catatan', catatan));
				
				//error
				$this->session->set_flashdata('kop_id', form_error('kop_id'));
				$this->session->set_flashdata('tentang', form_error('tentang'));
				$this->session->set_flashdata('isi', form_error('isi'));
				
				redirect(site_url('suratkeluar/edaran/add'));
				
				}
				else {

				$insert = $this->edaran_model->insert_data('surat_edaran', $data);

				if ($insert) {

					//pengiriman surat internal atau eksternal
					$jabatan_id = $this->input->post('jabatan_id');
					$eksternal_id = $this->input->post('eksternal_id');
			
					//proses input internal atau eksternal
					internal_eksternal('edaran',$surat_id,$jabatan_id,$eksternal_id);
					
					//pengiriman tembusan internal atau eksternal
					$tembusan_id = $this->input->post('tembusan_id');
					$tembusaneks_id = $this->input->post('tembusaneks_id');
			
					//proses input tembusan internal atau eksternal
					internal_eksternal_tembusan('edaran',$surat_id,$tembusan_id,$tembusaneks_id);

					$datadraft = array(
						'surat_id' => $surat_id,
						'kopId' => $kopId,
						'tanggal' => $this->input->post('tanggal'), 
						'dibuat_id' => $this->session->userdata('jabatan_id'), 
						'penandatangan_id' => '',
						'verifikasi_id' => '', 
						'nama_surat' => 'Surat Edaran', 
					);
					$this->edaran_model->insert_data('draft', $datadraft);

					$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
					redirect(site_url('suratkeluar/edaran'));
				}else{
					$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
					redirect(site_url('suratkeluar/edaran/add'));
				}

			    	}
			}else{
	        	$ambext = explode(".",$file);
				$ekstensi = end($ambext);
				$date = date('his');
			    $nama_baru = "Lampiran-Surat-Edaran-No-".$surat_id."-".$date;
				$nama_file = $nama_baru.".".$ekstensi;	
				$config['upload_path'] = './assets/lampiransurat/edaran/';
				$config['allowed_types'] = 'pdf';
				$config['max_size']=40000;
				$config['file_name'] = $nama_file;
				$this->upload->initialize($config);

				if(!$this->upload->do_upload('lampiran_lain')){
					$this->session->set_flashdata('error','Upload File Gagal');
					redirect(site_url('suratkeluar/edaran/add'));
				}else{
					$data = array(
						'id' => $surat_id, 
						'kop_id' => $this->input->post('kop_id'), 
						'opd_id' => $this->session->userdata('opd_id'),
						'kodesurat_id' => $this->input->post('kodesurat_id'), 
						'tanggal' => $this->input->post('tanggal'), 
						'nomor' => '',
						'tentang' => $this->input->post('tentang'), 
						'tembusan' => '',
						'isi' => $this->input->post('isi'),
						'catatan' => $this->input->post('catatan'),
						'lampiran_lain' => $nama_file, 
					);
					//form validation surat edaran [@dam|E-Gov 10.04.2022]
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
    				$this->session->set_flashdata('value_tentang', set_value('tentang', tentang)); 
    				$this->session->set_flashdata('value_isi', set_value('isi', isi));
					$this->session->set_flashdata('value_catatan', set_value('catatan', catatan));
    				$this->session->set_flashdata('value_lain', set_value('', '* Silakan upload kembali file lampiran'));
    				
    				//error
    				$this->session->set_flashdata('kop_id', form_error('kop_id'));
    				$this->session->set_flashdata('tentang', form_error('tentang'));
    				$this->session->set_flashdata('isi', form_error('isi'));
    				
    				redirect(site_url('suratkeluar/edaran/add'));
    				
    				}
    				else {

					$insert = $this->edaran_model->insert_data('surat_edaran', $data);
					if ($insert) {
						
						//pengiriman surat internal atau eksternal
						$jabatan_id = $this->input->post('jabatan_id');
						$eksternal_id = $this->input->post('eksternal_id');
				
						//proses input internal atau eksternal
						internal_eksternal('edaran',$surat_id,$jabatan_id,$eksternal_id);
						
						//pengiriman tembusan internal atau eksternal
						$tembusan_id = $this->input->post('tembusan_id');
						$tembusaneks_id = $this->input->post('tembusaneks_id');
				
						//proses input tembusan internal atau eksternal
						internal_eksternal_tembusan('edaran',$surat_id,$tembusan_id,$tembusaneks_id);

						$datadraft = array(
							'surat_id' => $surat_id, 
							'kopId' => $kopId, 
							'tanggal' => $this->input->post('tanggal'),
							'dibuat_id' => $this->session->userdata('jabatan_id'), 
							'penandatangan_id' => '',
							'verifikasi_id' => '', 
							'nama_surat' => 'Surat Edaran', 
						);
						$this->edaran_model->insert_data('draft', $datadraft);

						$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
						redirect(site_url('suratkeluar/edaran'));
					}else{
						$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
						redirect(site_url('suratkeluar/edaran/add'));
					}
					}
				}
			}

		}
	}

	public function edit()
	{
		$data['content'] = 'edaran/edaran_form';
		$data['kop'] = $this->db->get('kop_surat')->result();
		$data['kodesurat'] = $this->db->get('kode_surat')->result();
		$data['edaran'] = $this->edaran_model->edit_data($this->uri->segment(4))->result();
		
		$this->load->view('template', $data);
	}

	public function update()
	{
		$id = $this->input->post('id');

		//Untuk menambahkan data eksternal 
		if (isset($_POST['simpan'])) {

			$getEksID = $this->edaran_model->get_id('eksternal_keluar')->result();
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
			//form validation eksternal surat edaran
			$this->form_validation->set_rules('nama','Nama Eksternal', 'required');
        		$this->form_validation->set_rules('email','Email Eksternal', 'required');
			$this->form_validation->set_rules('required', '%s masih kosong, silakan diisikan');

			if($this->form_validation->run()===FALSE) {

			$this->session->set_flashdata('error', 'Nama dan email eksternal tidak boleh kosong');
			
			redirect(site_url('suratkeluar/edaran/add'));
			
			}
			else {

			$insert = $this->edaran_model->insert_data('eksternal_keluar', $data);

			if ($insert) {
				$this->session->set_flashdata('success', 'Eksternal Berhasil Dibuat');
				redirect(site_url('suratkeluar/edaran/add'));
			}else{
				$this->session->set_flashdata('error', 'Eksternal Gagal Dibuat');
				redirect(site_url('suratkeluar/draft/add/'));
			}
	
			}
		//untuk menambahkan tembusan eksternal 		
		}elseif (isset($_POST['simpan_tembusan'])) {
	
			$data = array(
				'opd_id' => $this->session->userdata('opd_id'), 
				'namatembusan' => $this->input->post('namatembusan'), 
				'emailtembusan' => $this->input->post('emailtembusan'), 
			);
			$insert = $this->edaran_model->insert_data('tembusan_keluar', $data);

			if ($insert) {
				$this->session->set_flashdata('success', 'Tembusan Eksternal Berhasil Dibuat');
				redirect(site_url('suratkeluar/edaran/add'));
			}else{
				$this->session->set_flashdata('error', 'Tembusan Eksternal Gagal Dibuat');
				redirect(site_url('suratkeluar/draft/add/'));
			}
			
		//Untuk Mengedit surat
		//Untuk mengedit surat
		}else{
			// $tinternal=$this->input->post('tembusan');
			// $teksternal=$this->input->post('tembusaneks');
			// $tembusan_int = implode(',',$tinternal);
    		// $tembusan_eks = implode(',',$teksternal);
    		// $tembusan_slug_int = str_replace('','-',$tembusan_int);
    		// $tembusan_slug_eks = str_replace('','-',$tembusan_eks);
    		// $queryTembusan = $this->db->query("SELECT tembusan FROM surat_edaran WHERE id = '$id'")->row_array();
    		// // if(empty($this->input->post('tembusan')) AND !empty($queryTembusan['tembusan'])){
    		// // 	$tembusan = $queryTembusan['tembusan'].','.$tembusan_slug_eks;
    		// // }elseif(empty($this->input->post('tembusaneks')) AND !empty($queryTembusan['tembusan'])){
    		// // 	$tembusan = $queryTembusan['tembusan'].','.$tembusan_slug_int;
    		// // }elseif(empty($queryTembusan['tembusan']) AND !empty($this->input->post('tembusan')) AND !empty($this->input->post('tembusaneks'))){
    		// //     $tembusan = $tembusan_slug_int.','.$tembusan_slug_eks;
    		// // }elseif(empty($queryTembusan['tembusan']) AND empty($this->input->post('tembusan'))){
    		// //     $tembusan = $tembusan_slug_eks;
    		// // }elseif(empty($queryTembusan['tembusan']) AND empty($this->input->post('tembusaneks'))){
    		// //     $tembusan = $tembusan_slug_int;
    		// // }else{
    		// // 	$tembusan = $queryTembusan['tembusan'].','.$tembusan_slug_int.','.$tembusan_slug_eks;
    		// // }
			
			// // Jika internal,eksternal,query tidak kosong
			// if(!empty($tinternal) AND !empty($teksternal) AND !empty($queryTembusan)){
			// 	$tembusan=$queryTembusan['tembusan'].','.$tembusan_slug_int.','.$tembusan_slug_eks;
			// // Jika internal, eksternal tidak kosong tetapi query kosong
			// }else if(!empty($tinternal) AND !empty($teksternal) AND empty($queryTembusan)){
			// 	$tembusan=$tembusan_slug_int.','.$tembusan_slug_eks;
			// // Jika internal, query tidak kosong tetapi eksternal kosong
			// }else if(!empty($tinternal) AND empty($teksternal) AND !empty($queryTembusan)){
			// 	$tembusan=$queryTembusan['tembusan'].','.$tembusan_slug_int;
			// // Jika eksternal, query tidak kosong tetapi internal kosong
			// }else if(empty($tinternal) AND !empty($teksternal) AND !empty($queryTembusan)){
			// 	$tembusan=$queryTembusan['tembusan'].','.$tembusan_slug_eks;
			// // Jika internal tidak kosong tetapi eksternal,query kosong
			// }else if(!empty($tinternal) AND empty($teksternal) AND empty($queryTembusan)){
			// 	$tembusan=$tembusan_slug_int;
			// // Jika eksternal tidak kosong tetapi internal,query kosong
			// }else if(empty($tinternal) AND !empty($teksternal) AND empty($queryTembusan)){
			// 	$tembusan=$tembusan_slug_eks;
			// // Jika query tidak kosong tetapi internal,eksternal kosong
			// }else if(empty($tinternal) AND empty($teksternal) AND !empty($queryTembusan)){
			// 	$tembusan=$queryTembusan['tembusan'];
			// // Jika kosong semua
			// }else if(empty($tinternal) AND empty($teksternal) AND empty($queryTembusan)){
			// 	$tembusan='';
			// }
			
			
			//pengiriman surat internal atau eksternal
			$jabatan_id = $this->input->post('jabatan_id');
			$eksternal_id = $this->input->post('eksternal_id');
			if (!empty($jabatan_id) OR !empty($eksternal_id)) {
				internal_eksternal('edaran',$id,$jabatan_id,$eksternal_id);
			}
			
			//pengiriman tembusan internal atau eksternal
			$tembusan_id = $this->input->post('tembusan_id');
			$tembusaneks_id = $this->input->post('tembusaneks_id');

			if (!empty($tembusan_id) OR !empty($tembusaneks_id)) {
				internal_eksternal_tembusan('edaran',$id,$tembusan_id,$tembusaneks_id);
			}

			$file = $_FILES['lampiran_lain']['name'];

			if (empty($file)) {
				$getQuery = $this->edaran_model->edit_data('surat_edaran', array('id' => $id))->result();
				foreach ($getQuery as $key => $h) {
					$fileLampiran = $h->lampiran_lain;
				}
				$data = array(
					'opd_id' => $this->session->userdata('opd_id'),
					'kop_id' => $this->input->post('kop_id'), 
					'kodesurat_id' => $this->input->post('kodesurat_id'), 
					'tanggal' => $this->input->post('tanggal'),
					'tentang' => $this->input->post('tentang'),
					'tembusan' => $tembusan,
					'isi' => $this->input->post('isi'), 
					'catatan' => $this->input->post('catatan'), 
					'lampiran_lain' => $fileLampiran,
				);
				$where = array('id' => $id);
				$update = $this->edaran_model->update_data('surat_edaran', $data, $where);
				if ($update) {

					$datadraft = array( 
						'tanggal' => $this->input->post('tanggal'),
						'kopId' => $this->input->post('kop_id'),
					);
					$wheredraft = array('surat_id' => $id);
					$this->edaran_model->update_data('draft', $datadraft, $wheredraft);
					
					$this->session->set_flashdata('success', 'Surat Berhasil Diedit');
					
					$pembuatSurat = $this->db->get_where('draft', array('surat_id' => $id))->row_array();

					if ($pembuatSurat['dibuat_id'] == $this->session->userdata('jabatan_id')) {
						redirect(site_url('suratkeluar/edaran'));
					}else{
						redirect(site_url('suratkeluar/draft'));
					}

				}else{
					$this->session->set_flashdata('error', 'Surat Gagal Diedit');
					redirect(site_url('suratkeluar/edaran/edit/'.$id));
				}
			}else{
			    $query = $this->db->query("SELECT * FROM surat_edaran WHERE id='$id'")->row_array();
	        	$ambext = explode(".",$file);
				$ekstensi = end($ambext);
				$date = date('his');
			    $nama_baru = "Lampiran-Surat-Edaran-No-".$id."-".$date;
				$nama_file = $nama_baru.".".$ekstensi;	
				$config['upload_path'] = './assets/lampiransurat/edaran/';
				$config['allowed_types'] = 'pdf';
				$config['max_size']=40000;
				$config['file_name'] = $nama_file;
				$this->upload->initialize($config);

				if(!$this->upload->do_upload('lampiran_lain')){
					$this->session->set_flashdata('error','Upload File Gagal');
					redirect(site_url('suratkeluar/edaran/edit'));
				}else{
				    @unlink('./assets/lampiransurat/edaran/'.$query['lampiran_lain']);
					$data = array(
						'kop_id' => $this->input->post('kop_id'), 
						'opd_id' => $this->session->userdata('opd_id'),
						'kodesurat_id' => $this->input->post('kodesurat_id'), 
						'tanggal' => $this->input->post('tanggal'),
						'tentang' => $this->input->post('tentang'),
						'tembusan' => $tembusan,
						'isi' => $this->input->post('isi'), 
						'catatan' => $this->input->post('catatan'), 
						'lampiran_lain' => $nama_file, 	
					);
					$where = array('id' => $id);
					$update = $this->edaran_model->update_data('surat_edaran', $data, $where);
					if ($update) {

						$datadraft = array( 
							'tanggal' => $this->input->post('tanggal'),
							'kopId' => $this->input->post('kop_id'),
						);
						$wheredraft = array('surat_id' => $id);
						$this->edaran_model->update_data('draft', $datadraft, $wheredraft);

						$this->session->set_flashdata('success', 'Surat Berhasil Diedit');
						
						$pembuatSurat = $this->db->get_where('draft', array('surat_id' => $id))->row_array();

						if ($pembuatSurat['dibuat_id'] == $this->session->userdata('jabatan_id')) {
							redirect(site_url('suratkeluar/edaran'));
						}else{
							redirect(site_url('suratkeluar/draft'));
						}

					}else{
						$this->session->set_flashdata('error', 'Surat Gagal Diedit');
						redirect(site_url('suratkeluar/edaran/edit/'.$id));
					}
				}
			}

		}
	}

	public function delete()
	{
		$where = array('id' => $this->uri->segment(4));
		$id = $this->uri->segment(4);
		$query = $this->db->query("SELECT * FROM surat_edaran WHERE id='$id'")->row_array();
		@unlink('./assets/lampiransurat/edaran/'.$query['lampiran_lain']);
		$delete = $this->edaran_model->delete_data('surat_edaran', $where);
		if ($delete) {
			$whereDis = array('surat_id' => $this->uri->segment(4));
			$this->edaran_model->delete_data('draft', $whereDis);
			$this->edaran_model->delete_data('verifikasi', $whereDis);
			$this->edaran_model->delete_data('disposisi_suratkeluar', $whereDis);
			$this->edaran_model->delete_data('tembusan_surat', $whereDis);
			
			$this->session->set_flashdata('success', 'Surat Berhasil Dihapus');
			redirect(site_url('suratkeluar/edaran'));
		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Dihapus');
			redirect(site_url('suratkeluar/edaran'));
		}
	}

	public function delete_kepada()
	{
		$surat_id = $this->uri->segment(4);
		$id = $this->uri->segment(5);
		$where = array('dsuratkeluar_id' => $id);
		$delete = $this->edaran_model->delete_data('disposisi_suratkeluar', $where);
		if ($delete) {
			$this->session->set_flashdata('success', 'Data Berhasil Dihapus');
			redirect(site_url('suratkeluar/edaran/edit/'.$surat_id));
		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Dihapus');
			redirect(site_url('suratkeluar/edaran/edit/'.$surat_id));
		}
	}

	public function delete_tembusan()
	{
		$surat_id = $this->uri->segment(4);
		$id = $this->uri->segment(5);
		$where = array('tembusansurat_id' => $id);
		$delete = $this->edaran_model->delete_data('tembusan_surat', $where);
		if ($delete) {
			$this->session->set_flashdata('success', 'Data Tembusan Berhasil Dihapus');
			redirect(site_url('suratkeluar/edaran/edit/'.$surat_id));
		}else{
			$this->session->set_flashdata('error', 'Data Tembusan Gagal Dihapus');
			redirect(site_url('suratkeluar/edaran/edit/'.$surat_id));
		}
	}

}