<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Surat extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if (empty($this->session->userdata('login'))) {
			$this->session->set_flashdata('access', 'Anda harus login terlebih dahulu!');
			redirect(site_url());
		}
		$this->load->model('surat_model');
	}

	public function index()
	{
		$this->load->library("pagination");
		$opdid=$this->session->userdata('opd_id');

		// search
		if($this->input->post('submit')){
		$search['cari']=$this->input->post('cari');
		$this->session->userdata('dari',$search['cari']);
		}else{
			$search['cari']=$this->session->userdata("cari");
		}
		// config pagination
		$config['base_url']=base_url("suratmasuk/surat/index/");
		$config['total_rows']=$this->surat_model->countSuratMasuk();
		$data['total_rows']=$config['total_rows'];
		$config['per_page']=10;

		// initialize
		$this->pagination->initialize($config);

		// get data table !=0
		if($this->uri->segment(4) == null){
			$data['start']=0;
		}else{
			$data['start']=$this->uri->segment(4);
		}

		$data['content'] = 'surat_index';
		
		$tahun = $this->session->userdata('tahun');
		$opd_id = $this->session->userdata('opd_id');
		
		$jabatanid=$this->session->userdata('jabatan_id');
		if($jabatanid == '1286'){
			// $data['aparatur'] = $this->surat_model->get_global($opdid)->result();	
			$jabatan = $this->db->get_where('jabatan', array('jabatan_id' => $this->session->userdata('jabatan_id')))->row_array();
			$atasan=$jabatan['atasan_id'];
			$data['aparatur'] = $this->db->query("SELECT * FROM aparatur a LEFT JOIN jabatan b ON b.jabatan_id=a.jabatan_id WHERE a.jabatan_id='$atasan'")->result();
		}elseif($jabatanid == '600'){
			$data['kelurahan'] = $this->surat_model->get_boteng($jabatanid)->result();
			$data['aparatur'] = $this->surat_model->get_atasan($jabatanid)->result();
		}elseif($jabatanid == '611'){
			$data['kelurahan'] = $this->surat_model->get_bosel($jabatanid)->result();
			$data['aparatur'] = $this->surat_model->get_atasan($jabatanid)->result();
		}elseif($jabatanid == '622'){
			$data['kelurahan'] = $this->surat_model->get_bobar($jabatanid)->result();
			$data['aparatur'] = $this->surat_model->get_atasan($jabatanid)->result();
		}elseif($jabatanid == '633'){
			$data['kelurahan'] = $this->surat_model->get_bout($jabatanid)->result();
			$data['aparatur'] = $this->surat_model->get_atasan($jabatanid)->result();
		}elseif($jabatanid == '644'){
			$data['kelurahan'] = $this->surat_model->get_botim($jabatanid)->result();
			$data['aparatur'] = $this->surat_model->get_atasan($jabatanid)->result();
		}elseif($jabatanid == '655'){
			$data['kelurahan'] = $this->surat_model->get_tansar($jabatanid)->result();
			$data['aparatur'] = $this->surat_model->get_atasan($jabatanid)->result();
		}
		
		// Tampilan View Data untuk level 18 = Admin TU Sekretariat
		$levelid=$this->session->userdata('level');
		if($levelid == 18){
			$data['suratmasuk'] = $this->surat_model->getlevel18($config['per_page'],$data['start'],$levelid,$jabatanid,$tahun,$search['cari'])->result();
		}else{
			$data['suratmasuk'] = $this->surat_model->get($config['per_page'],$data['start'],$jabatanid,$tahun,$search['cari'])->result();
		}

		$this->load->view('template', $data);



	}

	public function add()
	{
		$data['content'] = 'surat_form';
		$data['kodesurat'] = $this->db->get('kode_surat')->result();

		$surat_id = $this->uri->segment(4);

		if (substr($surat_id, 0,2) == 'SB') {
			$data['surat'] = $this->db->get_where('surat_biasa', array('id' => $surat_id))->result();
			$data['jenissurat'] = 'Surat Biasa';
		}elseif (substr($surat_id, 0,2) == 'SE') {
			$data['surat'] = $this->db->get_where('surat_edaran', array('id' => $surat_id))->result();
			$data['jenissurat'] = 'Surat Edaran';
		}elseif (substr($surat_id, 0,2) == 'SU') {
			$data['surat'] = $this->db->get_where('surat_undangan', array('id' => $surat_id))->result();
			$data['jenissurat'] = 'Surat Undangan';
		}elseif (substr($surat_id, 0,5) == 'PNGMN') {
			$data['surat'] = $this->db->get_where('surat_pengumuman', array('id' => $surat_id))->result();
			$data['jenissurat'] = 'Surat Pengumuman';
		}elseif (substr($surat_id, 0,3) == 'LAP') {
			$data['surat'] = $this->db->get_where('surat_laporan', array('id' => $surat_id))->result();
			$data['jenissurat'] = 'Surat Laporan';
		}elseif (substr($surat_id, 0,3) == 'REK') {
			$data['surat'] = $this->db->get_where('surat_rekomendasi', array('id' => $surat_id))->result();
			$data['jenissurat'] = 'Surat Rekomendasi';
		}elseif (substr($surat_id, 0,3) == 'INT') {
			$data['surat'] = $this->db->get_where('surat_instruksi', array('id' => $surat_id))->result();
			$data['jenissurat'] = 'Surat Instruksi';
		}elseif (substr($surat_id, 0,3) == 'PNG') {
			$data['surat'] = $this->db->get_where('surat_pengantar', array('id' => $surat_id))->result();
			$data['jenissurat'] = 'Surat Pengantar';
		}elseif (substr($surat_id, 0,5) == 'NODIN') {
			$data['surat'] = $this->db->get_where('surat_notadinas', array('id' => $surat_id))->result();
			$data['jenissurat'] = 'Nota Dinas';
		}elseif (substr($surat_id, 0,2) == 'SK') {
			$data['surat'] = $this->db->get_where('surat_keterangan', array('id' => $surat_id))->result();
			$data['jenissurat'] = 'Surat Keterangan';
		}elseif (substr($surat_id, 0,3) == 'SPT') {
			$data['surat'] = $this->db->get_where('surat_perintahtugas', array('id' => $surat_id))->result();
			$data['jenissurat'] = 'Surat Perintah Tugas';
		}elseif (substr($surat_id, 0,2) == 'SP') {
			$data['surat'] = $this->db->get_where('surat_perintah', array('id' => $surat_id))->result();
			$data['jenissurat'] = 'Surat Perintah';
		}elseif (substr($surat_id, 0,3) == 'IZN') {
			$data['surat'] = $this->db->get_where('surat_izin', array('id' => $surat_id))->result();
			$data['jenissurat'] = 'Surat Izin';
		}elseif (substr($surat_id, 0,3) == 'PJL') {
			$data['surat'] = $this->db->get_where('surat_perjalanandinas', array('id' => $surat_id))->result();
			$data['jenissurat'] = 'Surat Perjalanan Dinas';
		}elseif (substr($surat_id, 0,3) == 'KSA') {
			$data['surat'] = $this->db->get_where('surat_kuasa', array('id' => $surat_id))->result();
			$data['jenissurat'] = 'Surat Kuasa';
		}elseif (substr($surat_id, 0,3) == 'MKT') {
			$data['surat'] = $this->db->get_where('surat_melaksanakantugas', array('id' => $surat_id))->result();
			$data['jenissurat'] = 'Surat Melaksanakan Tugas';
		}elseif (substr($surat_id, 0,3) == 'PGL') {
			$data['surat'] = $this->db->get_where('surat_panggilan', array('id' => $surat_id))->result();
			$data['jenissurat'] = 'Surat Panggilan';
		}elseif (substr($surat_id, 0,3) == 'NTL') {
			$data['surat'] = $this->db->get_where('surat_notulen', array('id' => $surat_id))->result();
			$data['jenissurat'] = 'Surat Notulen';
		}elseif (substr($surat_id, 0,3) == 'MMO') {
			$data['surat'] = $this->db->get_where('surat_memo', array('id' => $surat_id))->result();
			$data['jenissurat'] = 'Surat Memo';
		}elseif (substr($surat_id, 0,3) == 'LMP') {
			$data['surat'] = $this->db->get_where('surat_lampiran', array('id' => $surat_id))->result();
			$data['jenissurat'] = 'Surat Lampiran';
		}
		
		$this->load->view('template', $data);
	}

	public function insert()
	{
		$surat = $_FILES['lampiran']['name'];
		$file = $_FILES['lampiran_lain']['name'];
		$surat_id = $this->input->post('surat_id');
		$opdid=$this->session->userdata('opd_id');

		//untuk surat manual
		if (empty($surat_id)) {
			
			if (empty($file)) {
				$ambext = explode(".",$surat);
				$ekstensi = end($ambext);
				$nama_baru = date('YmdHis');
				$nama_file = $nama_baru.".".$ekstensi;	
				$config['upload_path'] = './assets/lampiransuratmasuk/';
				$config['allowed_types'] = 'pdf|jpg|jpeg|png';
				$config['max_size']=40000;
				$config['file_name'] = $nama_file;
				$this->upload->initialize($config);

				if(!$this->upload->do_upload('lampiran')){
					$this->session->set_flashdata('error','Upload Surat Gagal');
					redirect(site_url('suratmasuk/surat/add'));
				}else{
					$data = array(
						'dari' => htmlentities($this->input->post('dari')),
						'dibuat_id' => $this->session->userdata('jabatan_id'),
						'nomor' => htmlentities($this->input->post('nomor')),
						'tanggal' => htmlentities($this->input->post('tanggal')), 
						'lampiran' => $nama_file, 
						'hal' => htmlentities($this->input->post('hal')), 
						'diterima' => htmlentities($this->input->post('diterima')), 
						'penerima' => htmlentities($this->input->post('penerima')), 
						'opd_id' => $this->session->userdata('opd_id'),
						'indeks' => '', 
						'kodesurat_id' => htmlentities($this->input->post('kodesurat_id')), 
						'sifat' => htmlentities($this->input->post('sifat')), 
						'lampiran_lain' => '',
						'telp' => $this->input->post('telp'), 
						'isi' => $this->input->post('isi'),  
						'catatan' => $this->input->post('catatan'),  
					);
					// CEK SURAT MASUK SUDAH ADA ATAU BELUM
					$nomor=$this->input->post('nomor');
					$cekdoubleinput=$this->db->query("SELECT * FROM surat_masuk WHERE nomor='$nomor' AND opd_id='$opdid'")->num_rows();
					if($cekdoubleinput == 1){
						$this->session->set_flashdata('error', 'Surat Sudah Pernah Dimasukkan');
						redirect(site_url('suratmasuk/surat'));
					}else{
						$insert = $this->surat_model->insert_data('surat_masuk', $data);
						if ($insert) {
							$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
							redirect(site_url('suratmasuk/surat'));
						}else{
							$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
							redirect(site_url('suratmasuk/surat/add'));
						}
					}
					// CEK SURAT MASUK SUDAH ADA ATAU BELUM
				}
			}else{
				$ambext = explode(".",$surat);
				$ekstensi = end($ambext);
				$nama_baru = date('YmdHis');
				$nama_file_surat = $nama_baru.".".$ekstensi;	
				$config['upload_path'] = './assets/lampiransuratmasuk/';
				$config['allowed_types'] = 'pdf|jpg|jpeg|png';
				$config['max_size']=40000;
				$config['file_name'] = $nama_file_surat;
				$this->upload->initialize($config);

				if(!$this->upload->do_upload('lampiran')){
					$this->session->set_flashdata('error','Upload Surat Gagal');
					redirect(site_url('suratmasuk/surat/add'));
				}else{
					$ambext = explode(".",$file);
					$ekstensi = end($ambext);
					$nama_baru = date('YmdHis')+1;
					$nama_file = $nama_baru.".".$ekstensi;	
					$config['upload_path'] = './assets/lampiransuratmasuk/';
					$config['allowed_types'] = 'pdf|jpg|jpeg|png';
					$config['max_size']=40000;
					$config['file_name'] = $nama_file;
					$this->upload->initialize($config);

					if(!$this->upload->do_upload('lampiran_lain')){
						$this->session->set_flashdata('error','Upload Lampiran Gagal');
						redirect(site_url('suratmasuk/surat/add'));
					}else{
						$data = array(
							'dari' => htmlentities($this->input->post('dari')),
							'dibuat_id' => $this->session->userdata('jabatan_id'),
							'nomor' => htmlentities($this->input->post('nomor')),
							'tanggal' => htmlentities($this->input->post('tanggal')), 
							'lampiran' => $nama_file_surat, 
							'hal' => htmlentities($this->input->post('hal')), 
							'diterima' => htmlentities($this->input->post('diterima')), 
							'penerima' => htmlentities($this->input->post('penerima')), 
							'opd_id' => $this->session->userdata('opd_id'),
							'indeks' => '', 
							'kodesurat_id' => htmlentities($this->input->post('kodesurat_id')), 
							'sifat' => htmlentities($this->input->post('sifat')), 
							'lampiran_lain' => $nama_file,
							'telp' => $this->input->post('telp'), 
							'isi' => $this->input->post('isi'),  
							'catatan' => $this->input->post('catatan'),  
						);
						// CEK SURAT MASUK SUDAH ADA ATAU BELUM
							$nomor=$this->input->post('nomor');
							$cekdoubleinput=$this->db->query("SELECT * FROM surat_masuk WHERE nomor='$nomor' AND opd_id='$opdid'")->num_rows();
							if($cekdoubleinput == 1){
								$this->session->set_flashdata('error', 'Surat Sudah Pernah Dimasukkan');
								redirect(site_url('suratmasuk/surat'));
							}else{
								$insert = $this->surat_model->insert_data('surat_masuk', $data);
								if ($insert) {
									$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
									redirect(site_url('suratmasuk/surat'));
								}else{
									$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
									redirect(site_url('suratmasuk/surat/add'));
								}
							}
							// CEK SURAT MASUK SUDAH ADA ATAU BELUM
					}
				}
			}

		
		//untuk surat otomatis
		}else{
			
			if (empty($file)) {
				$data = array(
					'dari' => htmlentities($this->input->post('dari')),
					'dibuat_id' => $this->session->userdata('jabatan_id'),
					'nomor' => htmlentities($this->input->post('nomor')),
					'tanggal' => htmlentities($this->input->post('tanggal')), 
					'lampiran' => $this->input->post('lampiran'), 
					'hal' => htmlentities($this->input->post('hal')), 
					'diterima' => htmlentities($this->input->post('diterima')), 
					'penerima' => htmlentities($this->input->post('penerima')), 
					'opd_id' => $this->session->userdata('opd_id'),
					'indeks' => '', 
					'kodesurat_id' => htmlentities($this->input->post('kodesurat_id')), 
					'sifat' => htmlentities($this->input->post('sifat')), 
					'lampiran_lain' => '',
					'telp' => $this->input->post('telp'), 
					'isi' => $this->input->post('isi'),  
					'catatan' => $this->input->post('catatan'),  
				);
				// CEK SURAT MASUK SUDAH ADA ATAU BELUM
				$nomor=$this->input->post('nomor');
				$cekdoubleinput=$this->db->query("SELECT * FROM surat_masuk WHERE nomor='$nomor' AND opd_id='$opdid'")->num_rows();
				if($cekdoubleinput == 1){
					$this->session->set_flashdata('error', 'Surat Sudah Pernah Dimasukkan');
					redirect(site_url('suratmasuk/surat'));
				}else{
					$insert = $this->surat_model->insert_data('surat_masuk', $data);
					if ($insert) {
						$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
						redirect(site_url('suratmasuk/surat'));
					}else{
						$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
						redirect(site_url('suratmasuk/surat/add'));
					}
				}
				// CEK SURAT MASUK SUDAH ADA ATAU BELUM
			}else{
				$ambext = explode(".",$file);
				$ekstensi = end($ambext);
				$nama_baru = date('YmdHis')+1;
				$nama_file = $nama_baru.".".$ekstensi;	
				$config['upload_path'] = './assets/lampiransuratmasuk/';
				$config['allowed_types'] = 'pdf|jpg|jpeg|png';
				$config['max_size']=40000;
				$config['file_name'] = $nama_file;
				$this->upload->initialize($config);

				if(!$this->upload->do_upload('lampiran_lain')){
					$this->session->set_flashdata('error','Upload Lampiran Gagal');
					redirect(site_url('suratmasuk/surat/add'));
				}else{
					$data = array(
						'dari' => htmlentities($this->input->post('dari')),
						'dibuat_id' => $this->session->userdata('jabatan_id'),
						'nomor' => htmlentities($this->input->post('nomor')),
						'tanggal' => htmlentities($this->input->post('tanggal')), 
						'lampiran' => $this->input->post('lampiran'), 
						'hal' => htmlentities($this->input->post('hal')), 
						'diterima' => htmlentities($this->input->post('diterima')), 
						'penerima' => htmlentities($this->input->post('penerima')), 
						'opd_id' => $this->session->userdata('opd_id'),
						'indeks' => '', 
						'kodesurat_id' => htmlentities($this->input->post('kodesurat_id')), 
						'sifat' => htmlentities($this->input->post('sifat')), 
						'lampiran_lain' => $nama_file,
						'telp' => $this->input->post('telp'), 
						'isi' => $this->input->post('isi'),  
						'catatan' => $this->input->post('catatan'),  
					);
					// CEK SURAT MASUK SUDAH ADA ATAU BELUM
					$nomor=$this->input->post('nomor');
					$cekdoubleinput=$this->db->query("SELECT * FROM surat_masuk WHERE nomor='$nomor' AND opd_id='$opdid'")->num_rows();
					if($cekdoubleinput == 1){
						$this->session->set_flashdata('error', 'Surat Sudah Pernah Dimasukkan');
						redirect(site_url('suratmasuk/surat'));
					}else{
						$insert = $this->surat_model->insert_data('surat_masuk', $data);
						if ($insert) {
							$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
							redirect(site_url('suratmasuk/surat'));
						}else{
							$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
							redirect(site_url('suratmasuk/surat/add'));
						}
					}
					// CEK SURAT MASUK SUDAH ADA ATAU BELUM
				}
			}

		}

		
	}

	public function edit()
	{
		$data['content'] = 'surat_form';
		$data['kodesurat'] = $this->db->get('kode_surat')->result();
		$data['suratmasuk'] = $this->surat_model->edit_data($this->uri->segment(4))->result();
		
		
		$this->load->view('template', $data);
	}

	public function update()
	{
		$id = $this->input->post('suratmasuk_id');

		$surat = $_FILES['lampiran']['name'];
		$file = $_FILES['lampiran_lain']['name'];
		$getQuery = $this->db->query("
		SELECT * FROM surat_masuk
		LEFT JOIN opd ON opd.opd_id=surat_masuk.opd_id
		WHERE surat_masuk.suratmasuk_id='$id'
		")->result();
		foreach ($getQuery as $key => $h){
			$filelampiransurat= $h->lampiran;
			$filelampiran= $h->lampiran_lain;
		}

		//Jika mengedit semua file
		if(!empty($surat) AND !empty($file)){
			$ambext = explode(".",$surat);
			$ekstensi = end($ambext);
			$nama_baru = date('YmdHis');
			$nama_file_surat = $nama_baru.".".$ekstensi;	
			$config['upload_path'] = './assets/lampiransuratmasuk/';
			$config['allowed_types'] = 'pdf|jpg|jpeg|png';
			$config['max_size']=40000;
			$config['file_name'] = $nama_file_surat;
			$this->upload->initialize($config);

			if(!$this->upload->do_upload('lampiran')){
				$this->session->set_flashdata('error','Upload Surat Gagal');
				redirect(site_url('suratmasuk/surat/edit'.$id));
			}else{
				$ambext = explode(".",$file);
				$ekstensi = end($ambext);
				$nama_baru = date('YmdHis')+1;
				$nama_file = $nama_baru.".".$ekstensi;	
				$config['upload_path'] = './assets/lampiransuratmasuk/';
				$config['allowed_types'] = 'pdf|jpg|jpeg|png';
				$config['max_size']=40000;
				$config['file_name'] = $nama_file;
				$this->upload->initialize($config);

				if(!$this->upload->do_upload('lampiran_lain')){
					$this->session->set_flashdata('error','Upload Lampiran Gagal');
					redirect(site_url('suratmasuk/surat/edit'.$id));
				}else{
					$data = array(
						'dari' => htmlentities($this->input->post('dari')),
						'nomor' => htmlentities($this->input->post('nomor')),
						'tanggal' => htmlentities($this->input->post('tanggal')), 
						'lampiran' => $nama_file_surat, 
						'hal' => htmlentities($this->input->post('hal')), 
						'diterima' => htmlentities($this->input->post('diterima')), 
						'penerima' => htmlentities($this->input->post('penerima')), 
						'opd_id' => $this->session->userdata('opd_id'),
						'indeks' => '', 
						'kodesurat_id' => htmlentities($this->input->post('kodesurat_id')), 
						'sifat' => htmlentities($this->input->post('sifat')), 
						'lampiran_lain' => $nama_file,
						'telp' => $this->input->post('telp'), 
						'isi' => $this->input->post('isi'),  
						'catatan' => $this->input->post('catatan'),  
					);
					$where = array('suratmasuk_id' => $id);
					$update = $this->surat_model->update_data('surat_masuk', $data, $where);
					if ($update) {
						$this->session->set_flashdata('success', 'Surat Berhasil Diedit');
						redirect(site_url('suratmasuk/surat'));
					}else{
						$this->session->set_flashdata('error', 'Surat Gagal Diedit');
						redirect(site_url('suratmasuk/surat/edit'.$id));
					}
				}
			}
		// Jika upload Lampiran Surat
		}elseif(!empty($surat) AND empty($file)){
			$ambext = explode(".",$surat);
			$ekstensi = end($ambext);
			$nama_baru = date('YmdHis');
			$nama_file_surat = $nama_baru.".".$ekstensi;	
			$config['upload_path'] = './assets/lampiransuratmasuk/';
			$config['allowed_types'] = 'pdf|jpg|jpeg|png';
			$config['max_size']=40000;
			$config['file_name'] = $nama_file_surat;
			$this->upload->initialize($config);

			if(!$this->upload->do_upload('lampiran')){
				$this->session->set_flashdata('error','Upload Surat Gagal');
				redirect(site_url('suratmasuk/surat/edit/'.$id));
			}else{
					$data = array(
						'dari' => htmlentities($this->input->post('dari')),
						'nomor' => htmlentities($this->input->post('nomor')),
						'tanggal' => htmlentities($this->input->post('tanggal')), 
						'lampiran' => $nama_file_surat, 
						'hal' => htmlentities($this->input->post('hal')), 
						'diterima' => htmlentities($this->input->post('diterima')), 
						'penerima' => htmlentities($this->input->post('penerima')), 
						'opd_id' => $this->session->userdata('opd_id'),
						'indeks' => '', 
						'kodesurat_id' => htmlentities($this->input->post('kodesurat_id')), 
						'sifat' => htmlentities($this->input->post('sifat')), 
						'lampiran_lain' => $filelampiran,
						'telp' => $this->input->post('telp'), 
						'isi' => $this->input->post('isi'),  
						'catatan' => $this->input->post('catatan'),  
					);
					$where = array('suratmasuk_id' => $id);
					$update = $this->surat_model->update_data('surat_masuk', $data, $where);
					if ($update) {
						$this->session->set_flashdata('success', 'Surat Berhasil Diedit');
						redirect(site_url('suratmasuk/surat'));
					}else{
						$this->session->set_flashdata('error', 'Surat Gagal Diedit');
						redirect(site_url('suratmasuk/surat/edit/'.$id));
					}
			}

		}elseif(empty($surat) AND !empty($file)){
			$ambext = explode(".",$file);
			$ekstensi = end($ambext);
			$nama_baru = date('YmdHis')+1;
			$nama_file_surat = $nama_baru.".".$ekstensi;	
			$config['upload_path'] = './assets/lampiransuratmasuk/';
			$config['allowed_types'] = 'pdf|jpg|jpeg|png';
			$config['max_size']=40000;
			$config['file_name'] = $nama_file_surat;
			$this->upload->initialize($config);

			if(!$this->upload->do_upload('lampiran_lain')){
				$this->session->set_flashdata('error','Upload Surat Gagal');
				redirect(site_url('suratmasuk/surat/edit/'.$id));
			}else{
					$data = array(
						'dari' => htmlentities($this->input->post('dari')),
						'nomor' => htmlentities($this->input->post('nomor')),
						'tanggal' => htmlentities($this->input->post('tanggal')), 
						'lampiran' => $filelampiransurat, 
						'hal' => htmlentities($this->input->post('hal')), 
						'diterima' => htmlentities($this->input->post('diterima')), 
						'penerima' => htmlentities($this->input->post('penerima')), 
						'opd_id' => $this->session->userdata('opd_id'),
						'indeks' => '', 
						'kodesurat_id' => htmlentities($this->input->post('kodesurat_id')), 
						'sifat' => htmlentities($this->input->post('sifat')), 
						'lampiran_lain' => $nama_file_surat,
						'telp' => $this->input->post('telp'), 
						'isi' => $this->input->post('isi'),  
						'catatan' => $this->input->post('catatan'),  
					);
					$where = array('suratmasuk_id' => $id);
					$update = $this->surat_model->update_data('surat_masuk', $data, $where);
					if ($update) {
						$this->session->set_flashdata('success', 'Surat Berhasil Diedit');
						redirect(site_url('suratmasuk/surat'));
					}else{
						$this->session->set_flashdata('error', 'Surat Gagal Diedit');
						redirect(site_url('suratmasuk/surat/edit/'.$id));
					}
			}
		}elseif(!$this->upload->do_upload('lampiran') AND !$this->upload->do_upload('lampiran_lain')){
			$data = array(
				'dari' => htmlentities($this->input->post('dari')),
				'nomor' => htmlentities($this->input->post('nomor')),
				'tanggal' => htmlentities($this->input->post('tanggal')), 
				'lampiran' => $filelampiransurat, 
				'hal' => htmlentities($this->input->post('hal')), 
				'diterima' => htmlentities($this->input->post('diterima')), 
				'penerima' => htmlentities($this->input->post('penerima')), 
				'opd_id' => $this->session->userdata('opd_id'),
				'indeks' => '', 
				'kodesurat_id' => htmlentities($this->input->post('kodesurat_id')), 
				'sifat' => htmlentities($this->input->post('sifat')), 
				'lampiran_lain' => $filelampiran,
				'telp' => $this->input->post('telp'), 
				'isi' => $this->input->post('isi'),  
				'catatan' => $this->input->post('catatan'),  
			);
			$where = array('suratmasuk_id' => $id);
			$update = $this->surat_model->update_data('surat_masuk', $data, $where);
			if ($update) {
				$this->session->set_flashdata('success', 'Surat Berhasil Diedit');
				redirect(site_url('suratmasuk/surat'));
			}else{
				$this->session->set_flashdata('error', 'Surat Gagal Diedit');
				redirect(site_url('suratmasuk/surat/edit'.$id));
			}
		}
}

public function disposisi()
{
	if (isset($_POST['disposisi'])) {
		$suratmasukid=$this->input->post('suratmasuk_id');
		$aparatur_id = implode(',', $this->input->post('aparatur_id'));
		$explodeAparatur = explode(',', $aparatur_id);
		$dataAparatur = array();
		$index = 0;
		foreach ($explodeAparatur as $key => $h) {
			$index++;
		}
		$cekdisposisi=$this->db->query("select * from disposisi_suratmasuk a where a.suratmasuk_id='$suratmasukid' and a.aparatur_id='$h' and a.status !='Dikembalikan'")->num_rows();
		if($cekdisposisi==1){
			$this->session->set_flashdata('error','Tujuan Aparatur Yang Dipilih Sudah Dimasukkan');
			redirect(site_url('suratmasuk/surat'));
		}else{
		if (empty($this->input->post('harap'))) {
			$harap = '';
		}else{
			$harap = implode(',', $this->input->post('harap'));
		}
		$aparatur_id = implode(',', $this->input->post('aparatur_id'));
		$explodeAparatur = explode(',', $aparatur_id);
		$datenow=date("Y-m-d");
		$dataAparatur = array();
		$index = 0;
		foreach ($explodeAparatur as $key => $h) {
			array_push($dataAparatur, array(
				'suratmasuk_id' => $this->input->post('suratmasuk_id'), 
				'aparatur_id' => $h, 
				'users_id' => $this->input->post('users_id'), 
				'harap' => 	$harap, 
				'keterangan' => $this->input->post('keterangan'), 
				'tanggal' => $this->input->post('tanggal'),
				'tanggal_verifikasi' => htmlentities($datenow),
				));
			$index++;
		}
		$dispos = $this->surat_model->insert_aparatur('disposisi_suratmasuk', $dataAparatur);
		if ($dispos) {

			//update status
			$this->surat_model->update_data('disposisi_suratmasuk', array('Status' => 'Selesai Disposisi'), array('dsuratmasuk_id' => $this->input->post('dsuratmasuk_id')));

			$this->session->set_flashdata('success', 'Surat Berhasil Didisposisikan');
			redirect(site_url('suratmasuk/surat'));
		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Didisposisikannnn');
			redirect(site_url('suratmasuk/surat'));
		}
	}
	}else{

		$jabatan = $this->db->get_where('jabatan', array('jabatan_id' => $this->session->userdata('jabatan_id')))->row_array();
		if ($jabatan['atasan_id'] == 0) {
			$getBawahan = $this->db->get_where('jabatan', array('atasan_id' => $this->session->userdata('jabatan_id')))->row_array();
			$getAtasan = $this->db->get_where('aparatur', array('jabatan_id' => $getBawahan['jabatan_id']))->row_array();
		}else{
			$getAtasan = $this->db->get_where('aparatur', array('jabatan_id' => $jabatan['atasan_id']))->row_array();
		}
		$suratmasukid=$this->uri->segment(4);
		$aparaturid=$getAtasan['jabatan_id'];
		$cekdisposisi=$this->db->query("select * from disposisi_suratmasuk a where a.suratmasuk_id='$suratmasukid' and a.aparatur_id='$aparaturid' and a.status !='Selesai Disposisi'")->num_rows();
		if($cekdisposisi==0){
		$harap = implode(',', $this->input->post('harap'));
		$datenow=date("Y-m-d");
		$data = array(
			'suratmasuk_id' => $this->uri->segment(4), 
			'aparatur_id' => $getAtasan['jabatan_id'], 
			'users_id' => $this->session->userdata('jabatan_id'), 
			'harap' => 	'', 
			'keterangan' => '', 
			'tanggal' => htmlentities($datenow),
		);
		    $this->surat_model->update_data('disposisi_suratmasuk', array('Status' => 'Riwayat'), array('suratmasuk_id' => $this->uri->segment(4)));
			$dispos = $this->surat_model->insert_data('disposisi_suratmasuk', $data);
			if ($dispos) {
				$this->session->set_flashdata('success', 'Surat Berhasil Didisposisikan');
				redirect(site_url('suratmasuk/surat'));
			}else{
				$this->session->set_flashdata('error', 'Surat Gagal Didisposisikan');
				redirect(site_url('suratmasuk/surat'));
			}
		}else{
			$this->session->set_flashdata('error','Surat Sudah Didisposisikan');
			redirect(site_url('suratmasuk/surat'));
	}

	}
	
}
// public function disposisilagi()
// {
// 	if (isset($_POST['disposisi'])) {
// 		$suratmasukid=$this->input->post('suratmasuk_id');
// 		$cekdisposisi=$this->db->query("select * from disposisi_suratmasuk a where a.suratmasuk='$suratmasukid' and a.aparatur_id='$h'")->num_rows();
// 		if($cekdisposisi==1){
// 			$this->session->set_flashdata('error','Tujuan Aparatur Yang Dipilih Sudah Dimasukkan');
// 			redirect(site_url('suratmasuk/surat'));
// 		}else{
// 		if (empty($this->input->post('harap'))) {
// 			$harap = '';
// 		}else{
// 			$harap = implode(',', $this->input->post('harap'));
// 		}
// 		$aparatur_id = implode(',', $this->input->post('aparatur_id'));
// 		$explodeAparatur = explode(',', $aparatur_id);
// 		$datenow=date("Y-m-d");
// 		$dataAparatur = array();
// 		$index = 0;
// 		foreach ($explodeAparatur as $key => $h) {
// 			array_push($dataAparatur, array(
// 				'suratmasuk_id' => $this->input->post('suratmasuk_id'), 
// 				'aparatur_id' => $h, 
// 				'users_id' => $this->input->post('users_id'), 
// 				'harap' => 	$harap, 
// 				'keterangan' => $this->input->post('keterangan'), 
// 				'tanggal' => $this->input->post('tanggal'),
// 				'tanggal_verifikasi' => htmlentities($datenow),
// 				));
// 			$index++;
// 		}
// 		$dispos = $this->surat_model->insert_aparatur('disposisi_suratmasuk', $dataAparatur);
// 		if ($dispos) {

// 			//update status
// 			$this->surat_model->update_data('disposisi_suratmasuk', array('Status' => 'Selesai Disposisi'), array('dsuratmasuk_id' => $this->input->post('dsuratmasuk_id')));

// 			$this->session->set_flashdata('success', 'Surat Berhasil Didisposisikan');
// 			redirect(site_url('suratmasuk/surat'));
// 		}else{
// 			$this->session->set_flashdata('error', 'Surat Gagal Didisposisikannnn');
// 			redirect(site_url('suratmasuk/surat'));
// 		}
// 	}
	
// }
// }


	public function delete()
	{
		$where = array('suratmasuk_id' => $this->uri->segment(4));
		$delete = $this->surat_model->delete_data('surat_masuk', $where);
		if ($delete) {

			$this->surat_model->delete_data('disposisi_suratmasuk', $where);
			$this->session->set_flashdata('success', 'Surat Berhasil Dihapus');
			redirect(site_url('suratmasuk/surat'));
		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Dihapus');
			redirect(site_url('suratmasuk/surat'));
		}
	}

}