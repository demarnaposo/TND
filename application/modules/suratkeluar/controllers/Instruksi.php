<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Instruksi extends CI_Controller {

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
		$this->load->model('instruksi_model');
	}

	public function index()
	{
		$data['content'] = 'instruksi/instruksi_index';
		
		$tahun = $this->session->userdata('tahun');
		$opd_id = $this->session->userdata('opd_id');
		$jabatan_id = $this->session->userdata('jabatan_id');

		$data['instruksi'] = $this->instruksi_model->get_data($tahun,$opd_id,$jabatan_id)->result();
		
		$this->load->view('template', $data);
	}

	public function add()
	{
		$data['content'] = 'instruksi/instruksi_form';
		$data['kop'] = $this->db->get('kop_surat')->result();
		$data['kodesurat'] = $this->db->get('kode_surat')->result();
		
		$this->load->view('template', $data);
	}

	public function insert_isi()
	{
		$isi = $this->input->post('isi');
		$surat_id = $this->input->post('surat_id');
			$in['surat_id'] = $surat_id;
			$in['users_id'] = $this->session->userdata('opd_id');
			$in['isi'] = $isi;
			
		 	$insert = $this->instruksi_model->insert_data('isisurat',$in);
		
		if ($insert) {
			$this->session->set_flashdata('success', 'Data Berhasil Ditambahkan');
			redirect(site_url('suratkeluar/instruksi/edit/'.$surat_id));
		}else{
			$this->session->set_flashdata('error', 'Data Gagal Ditambahkan');
			redirect(site_url('suratkeluar/instruksi/edit/'.$surat_id));
		}
	}

	public function insert()
	{
		//untuk menambahkan data eksternal
		if (isset($_POST['simpan'])) {
			
			$getEksID = $this->instruksi_model->get_id('eksternal_keluar')->result();
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
			//form validation eksternal surat instruksi
			$this->form_validation->set_rules('nama','Nama Eksternal', 'required');
        	$this->form_validation->set_rules('email','Email Eksternal', 'required');
			$this->form_validation->set_rules('required', '%s masih kosong, silakan diisikan');

			if($this->form_validation->run()===FALSE) {

			$this->session->set_flashdata('error', 'Nama dan email eksternal tidak boleh kosong');
			
			redirect(site_url('suratkeluar/instruksi/add'));
			
			}
			else {

			$insert = $this->instruksi_model->insert_data('eksternal_keluar', $data);

			if ($insert) {		
				$this->session->set_flashdata('success', 'Eksternal Berhasil Dibuat');
				redirect(site_url('suratkeluar/instruksi/add'));
				
			}else{
				$this->session->set_flashdata('error', 'Eksternal Gagal Dibuat');
				redirect(site_url('suratkeluar/draft/add/'));
			}
			
			}


		//Untuk menambahkan surat
		}else{

			$getID = $this->instruksi_model->get_id('surat_instruksi')->result();
			foreach ($getID as $key => $h) {
				$id = $h->id;
			}
			if (empty($id)) {
				$surat_id = 'INT-1';
			}else{
				$urut = substr($id, 4)+1;
				$surat_id = 'INT-'.$urut;
			}

			$implodesurat=implode('|',$this->input->post('isi'));
			$explodesurat=explode('|',$implodesurat);
			$dataisisurat = array();
			$index = 0;
			foreach ($explodesurat as $key => $h) {
					array_push($dataisisurat, array(
						'surat_id' => $surat_id, 
						'users_id' => $this->session->userdata('opd_id'),
						'isi' => $h
						));
					$index++;
			}
			$this->db->insert_batch('isisurat', $dataisisurat);
			
			$file = $_FILES['lampiran_lain']['name'];

			if (empty($file)) {
				$data = array(
					'id' => $surat_id,
					'opd_id' => $this->session->userdata('opd_id'),
					'kodesurat_id' => htmlentities($this->input->post('kodesurat_id')), 
					'tanggal' => htmlentities($this->input->post('tanggal')), 
					'nomor' => '',
					'lampiran_lain' => htmlentities($this->input->post('lampiran')), 
					'tentang' => htmlentities($this->input->post('tentang')),
					'dalamrangka' => htmlentities($this->input->post('dalamrangka')), 
					'catatan' => $this->input->post('catatan') 
				);
				
				//form validation surat instruksi
				$this->load->library('form_validation');	
				
				$rules =
				    [
				        [
				        'field' => 'tentang',  
				        'label' => 'Tentang/Perihal',
				        'rules' => 'required'],
				        
				        [
				        'field' => 'dalamrangka',  
				        'label' => 'Dalam Rangka',
				        'rules' => 'required']
				        
				    ];
				$this->form_validation->set_rules($rules);
				$this->form_validation->set_message('required', '%s masih kosong, silakan diisi');				

				if($this->form_validation->run()===FALSE) {
				
				//set value
				$this->session->set_flashdata('value_tentang', set_value('tentang', tentang));
				$this->session->set_flashdata('value_tujuan', set_value('', '*Silakan pilih kembali tujuan internal-eksternal Pemkot'));
				$this->session->set_flashdata('value_dalamrangka', set_value('dalamrangka', dalamrangka));
				$this->session->set_flashdata('value_catatan', set_value('catatan', catatan));
				
				//error
				$this->session->set_flashdata('tentang', form_error('tentang'));
				$this->session->set_flashdata('dalamrangka', form_error('dalamrangka'));
				
				redirect(site_url('suratkeluar/instruksi/add'));
				
				}
				else {
				
				$insert = $this->instruksi_model->insert_data('surat_instruksi', $data);
				if ($insert) {

					//pengiriman surat internal atau eksternal
					$jabatan_id = $this->input->post('jabatan_id');
					$eksternal_id = $this->input->post('eksternal_id');

					//proses input internal eksternal
					internal_eksternal('instruksi',$surat_id,$jabatan_id,$eksternal_id);

					$datadraft = array(
						'surat_id' => $surat_id,
						'tanggal' => htmlentities($this->input->post('tanggal')), 
						'dibuat_id' => $this->session->userdata('jabatan_id'), 
						'kopId' => 1, 
						'penandatangan_id' => '',
						'verifikasi_id' => '', 
						'nama_surat' => 'Surat Instruksi', 
					);
					$this->instruksi_model->insert_data('draft', $datadraft);

					$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
					redirect(site_url('suratkeluar/instruksi'));
				}else{
					$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
					redirect(site_url('suratkeluar/instruksi/add'));
				}
				}
			}else{
	        	$ambext = explode(".",$file);
				$ekstensi = end($ambext);
				$date = date('his');
		    		$nama_baru = "Lampiran-Surat-Instruksi-No-".$surat_id."-".$date;
				$nama_file = $nama_baru.".".$ekstensi;	
				$config['upload_path'] = './assets/lampiransurat/instruksi/';
				$config['allowed_types'] = 'pdf';
				$config['file_name'] = $nama_file;
				$this->upload->initialize($config);

				if(!$this->upload->do_upload('lampiran_lain')){
					$this->session->set_flashdata('error','Upload File Gagal');
					redirect(site_url('suratkeluar/instruksi/add'));
				}else{
					$data = array(
						'id' => $surat_id,
						'opd_id' => $this->session->userdata('opd_id'),
						'kodesurat_id' => htmlentities($this->input->post('kodesurat_id')), 
						'tanggal' => htmlentities($this->input->post('tanggal')), 
						'nomor' => '',
						'sifat' => htmlentities($this->input->post('sifat')), 
						'lampiran' => htmlentities($this->input->post('lampiran')), 
						'tentang' => htmlentities($this->input->post('tentang')), 
						'dalamrangka' => htmlentities($this->input->post('dalamrangka')),
						'lampiran_lain' => $nama_file, 
						'isi' => $this->input->post('isi'), 
						'catatan' => $this->input->post('catatan'), 
					);
					
					//form validation surat instruksi
    				$this->load->library('form_validation');	
    				
    				$rules =
    				    [
    				        [
    				        'field' => 'tentang',  
    				        'label' => 'Tentang/Perihal',
    				        'rules' => 'required'],
    				        
    				        [
    				        'field' => 'dalamrangka',  
    				        'label' => 'Dalam Rangka',
    				        'rules' => 'required']
    				        
    				    ];
    				$this->form_validation->set_rules($rules);
    				$this->form_validation->set_message('required', '%s masih kosong, silakan diisi');				
    
    				if($this->form_validation->run()===FALSE) {
    				
    				//set value
    				$this->session->set_flashdata('value_tentang', set_value('tentang', tentang));
    				$this->session->set_flashdata('value_tujuan', set_value('', '*Silakan pilih kembali tujuan internal-eksternal Pemkot'));
    				$this->session->set_flashdata('value_dalamrangka', set_value('dalamrangka', dalamrangka));
					$this->session->set_flashdata('value_catatan', set_value('catatan', catatan));
    				$this->session->set_flashdata('value_lain', set_value('', '* Silakan upload kembali file lampiran'));
    				
    				//error
    				$this->session->set_flashdata('tentang', form_error('tentang'));
    				$this->session->set_flashdata('dalamrangka', form_error('dalamrangka'));
    				
    				redirect(site_url('suratkeluar/instruksi/add'));
    				
    				}
    				else {
					
					$insert = $this->instruksi_model->insert_data('surat_instruksi', $data);
					if ($insert) {
						
						//pengiriman surat internal atau eksternal
						$jabatan_id = $this->input->post('jabatan_id');
						$eksternal_id = $this->input->post('eksternal_id');

						//proses input internal eksternal
						internal_eksternal('instruksi',$surat_id,$jabatan_id,$eksternal_id);
						
						$datadraft = array(
							'surat_id' => $surat_id, 
							'tanggal' => htmlentities($this->input->post('tanggal')), 
							'dibuat_id' => $this->session->userdata('jabatan_id'), 
							'kopId' => 1, 
							'penandatangan_id' => '',
							'verifikasi_id' => '', 
							'nama_surat' => 'Surat Instruksi', 
						);
						$this->instruksi_model->insert_data('draft', $datadraft);

						$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
						redirect(site_url('suratkeluar/instruksi'));
					}else{
						$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
						redirect(site_url('suratkeluar/instruksi/add'));
					}	
    				}
				}
			}

		}
	}

	public function edit()
	{
		$data['content'] = 'instruksi/instruksi_form';
		$data['kop'] = $this->db->get('kop_surat')->result();
		$data['kodesurat'] = $this->db->get('kode_surat')->result();
		$id = $this->uri->segment(4);
		$data['isi_surat'] = $this->db->query("SELECT * FROM isisurat WHERE surat_id = '$id'")->result();
		$data['instruksi'] = $this->instruksi_model->edit_data($this->uri->segment(4), $this->session->userdata('opd_id'))->result();
		
		$this->load->view('template', $data);
	}

	public function update()
	{
		$id = $this->input->post('id');

		//Untuk menambahkan data eksternal 
		if (isset($_POST['simpan'])) {
			
			$getEksID = $this->instruksi_model->get_id('eksternal_keluar')->result();
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
			//form validation eksternal surat instruksi
			$this->form_validation->set_rules('nama','Nama Eksternal', 'required');
        		$this->form_validation->set_rules('email','Email Eksternal', 'required');
			$this->form_validation->set_rules('required', '%s masih kosong, silakan diisikan');

			if($this->form_validation->run()===FALSE) {

			$this->session->set_flashdata('error', 'Nama dan email eksternal tidak boleh kosong');
			
			redirect(site_url('suratkeluar/instruksi/add'));
			
			}
			else {

			$insert = $this->instruksi_model->insert_data('eksternal_keluar', $data);

			if ($insert) {		
				$this->session->set_flashdata('success', 'Eksternal Berhasil Dibuat');
				redirect(site_url('suratkeluar/instruksi/add'));
				
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
				internal_eksternal('instruksi',$id,$jabatan_id,$eksternal_id);
			}
			
			//update_batch pada table isi surat
			$idisi=$this->input->post('isi_id');
			$isisurat=$this->input->post('isi');
			$updatearray=array();
			for($x=0;$x < count($idisi);$x++){
				$isi_id['isi_id']=$idisi[$x];
				$in['isi']=$isisurat[$x];
				$this->instruksi_model->update_data('isisurat',$in,$isi_id);
			}
			//update_batch pada table isi surat

			$file = $_FILES['lampiran_lain']['name'];

			if (empty($file)) {
				$getQuery = $this->instruksi_model->edit_data('surat_instruksi', array('id' => $id))->result();
				foreach ($getQuery as $key => $h) {
					$fileLampiran = $h->lampiran_lain;
				}
				$data = array(
					'opd_id' => $this->session->userdata('opd_id'),
					'kodesurat_id' => htmlentities($this->input->post('kodesurat_id')), 
					'tanggal' => htmlentities($this->input->post('tanggal')), 
					'nomor' => '',
					'tentang' => htmlentities($this->input->post('tentang')), 
					'dalamrangka' => htmlentities($this->input->post('dalamrangka')),
					'catatan' => $this->session->userdata('catatan'),
					
				);
				$where = array('id' => $id);
				$update = $this->instruksi_model->update_data('surat_instruksi', $data, $where);
				if ($update) {

					$datadraft = array(
						'tanggal' => htmlentities($this->input->post('tanggal')), 
					);
					$wheredraft = array('surat_id' => $id);
					$this->instruksi_model->update_data('draft', $datadraft, $wheredraft);

					$this->session->set_flashdata('success', 'Surat Berhasil Diedit');
					
					$pembuatSurat = $this->db->get_where('draft', array('surat_id' => $id))->row_array();
					
					if ($pembuatSurat['dibuat_id'] == $this->session->userdata('jabatan_id')) {
						redirect(site_url('suratkeluar/instruksi'));
					}else{
						redirect(site_url('suratkeluar/draft'));
					}

				}else{
					$this->session->set_flashdata('error', 'Surat Gagal Diedit');
					redirect(site_url('suratkeluar/instruksi/edit/'.$id));
				}
			}else{
			    $query = $this->db->query("SELECT * FROM surat_instruksi WHERE id='$id'")->row_array();
	        	$ambext = explode(".",$file);
				$ekstensi = end($ambext);
				$date = date('his');
			    $nama_baru = "Lampiran-Surat-Instruksi-No-".$id."-".$date;
				$nama_file = $nama_baru.".".$ekstensi;	
				$config['upload_path'] = './assets/lampiransurat/instruksi/';
				$config['allowed_types'] = 'pdf';
				$config['file_name'] = $nama_file;
				$this->upload->initialize($config);

				if(!$this->upload->do_upload('lampiran_lain')){
					$this->session->set_flashdata('error','Upload File Gagal');
					redirect(site_url('suratkeluar/instruksi/edit'));
				}else{
				// 	$id = $this->input->post('id');
					@unlink('./assets/lampiransurat/instruksi/'.$query['lampiran_lain']);
					$data = array(
						'opd_id' => $this->session->userdata('opd_id'),
						'kodesurat_id' => htmlentities($this->input->post('kodesurat_id')),
						'tanggal' => htmlentities($this->input->post('tanggal')), 
						'tentang' => htmlentities($this->input->post('tentang')),
						'catatan' => $this->session->userdata('catatan'),
						'dalamrangka' => htmlentities($this->input->post('dalamrangka')), 
						'lampiran_lain' => $nama_file,	
					);
					
					$where = array('id' => $id);
					$update = $this->instruksi_model->update_data('surat_instruksi', $data, $where);
					if ($update) {
                        
						$datadraft = array( 
							'tanggal' => htmlentities($this->input->post('tanggal')), 
						);
						$wheredraft = array('surat_id' => $id);
						$this->instruksi_model->update_data('draft', $datadraft, $wheredraft);

						$this->session->set_flashdata('success', 'Surat Berhasil Diedit');
						
						$pembuatSurat = $this->db->get_where('draft', array('surat_id' => $id))->row_array();
						
						if ($pembuatSurat['dibuat_id'] == $this->session->userdata('jabatan_id')) {
							redirect(site_url('suratkeluar/instruksi'));
						}else{
							redirect(site_url('suratkeluar/draft'));
						}
						
					}else{
						$this->session->set_flashdata('error', 'Surat dengan File Lampiran Gagal Diedit');
						redirect(site_url('suratkeluar/instruksi/edit/'.$id));
					}
				}
			}

		}
	}

	public function delete()
	{
		$where = array('id' => $this->uri->segment(4));
		$surat_id = array('surat_id' => $this->uri->segment(4));
		$id = $this->uri->segment(4);
		$query = $this->db->query("SELECT * FROM surat_instruksi WHERE id='$id'")->row_array();
		@unlink('./assets/lampiransurat/instruksi/'.$query['lampiran_lain']);
		$deleteisi = $this->instruksi_model->delete_data('isisurat', $surat_id);
		$delete = $this->instruksi_model->delete_data('surat_instruksi', $where);
		if ($delete) {
			$whereDis = array('surat_id' => $this->uri->segment(4));
			$this->instruksi_model->delete_data('draft', $whereDis);
			$this->instruksi_model->delete_data('verifikasi', $whereDis);
			$this->instruksi_model->delete_data('disposisi_suratkeluar', $whereDis);
			
			$this->session->set_flashdata('success', 'Surat Berhasil Dihapus');
			redirect(site_url('suratkeluar/instruksi'));
		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Dihapus');
			redirect(site_url('suratkeluar/instruksi'));
		}
	}

	public function delete_kepada()
	{
		$surat_id = $this->uri->segment(4);
		$id = $this->uri->segment(5);
		$where = array('dsuratkeluar_id' => $id);
		$delete = $this->instruksi_model->delete_data('disposisi_suratkeluar', $where);
		if ($delete) {
			$this->session->set_flashdata('success', 'Data Berhasil Dihapus');
			redirect(site_url('suratkeluar/instruksi/edit/'.$surat_id));
		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Dihapus');
			redirect(site_url('suratkeluar/instruksi/edit/'.$surat_id));
		}
	}

	public function delete_data()
	{
		$surat_id = $this->uri->segment(4);
		$id = $this->uri->segment(5);
		$where = array('isi_id' => $id);
		$delete = $this->instruksi_model->delete_data('isisurat', $where);
		if ($delete) {
			$this->session->set_flashdata('success', 'Data Berhasil Dihapus');
			redirect(site_url('suratkeluar/instruksi/edit/'.$surat_id));
		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Dihapus');
			redirect(site_url('suratkeluar/instruksi/edit/'.$surat_id));
		}
	}

}