<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Draft extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if (empty($this->session->userdata('login'))) {
			$this->session->set_flashdata('access', 'Anda harus login terlebih dahulu!');
			redirect(site_url());
		}
		$this->load->model('draft_model');
	}

	public function index()
	{
		$data['content'] = 'draft_index';
		
		$tahun = $this->session->userdata('tahun');
		$jabatan_id = $this->session->userdata('jabatan_id');

		$data['draft'] = $this->draft_model->get_verifikasi($tahun,$jabatan_id)->result();
		
		$this->load->view('template', $data);
	}

	public function verify()
	{
		if (isset($_POST['verifikasi'])) {
			$getJabatan = $this->db->get_where('jabatan', array('jabatan_id' => $this->input->post('jabatan_id')))->row_array();
			$dariAparatur = $this->draft_model->dari_untuk_aparatur($this->input->post('jabatan_id'))->row_array();
			$untukAparatur = $this->draft_model->dari_untuk_aparatur($getJabatan['atasan_id'])->row_array();
			$data = array(
				'dari' => $dariAparatur['nama'].' - '.$dariAparatur['nama_jabatan'], 
				'untuk' => $untukAparatur['nama'].' - '.$untukAparatur['nama_jabatan'], 
				'surat_id' => htmlentities($this->input->post('surat_id')), 
				'keterangan' => htmlentities($this->input->post('keterangan')), 
			);
			$verify = $this->draft_model->insert_data('verifikasi', $data);
			if ($verify) {
				$verifikasi = array('verifikasi_id' => $getJabatan['atasan_id']);
				$where = array('surat_id' => htmlentities($this->input->post('surat_id')));
				$this->draft_model->update_data('draft', $verifikasi, $where);

				$this->session->set_flashdata('success', 'Surat Berhasil Diteruskan');
					
				if ($this->input->post('uri_segment') == 'draft') {
					redirect(site_url('suratkeluar/draft'));
				}else{
					if (substr($this->input->post('surat_id'), 0,2) == 'SB') {
						redirect(site_url('suratkeluar/biasa'));
					}elseif (substr($this->input->post('surat_id'), 0,2) == 'SE') {
						redirect(site_url('suratkeluar/edaran'));
					}elseif (substr($this->input->post('surat_id'), 0,2) == 'SU') {
						redirect(site_url('suratkeluar/undangan'));
					}elseif (substr($this->input->post('surat_id'), 0,5) == 'PNGMN') {
						redirect(site_url('suratkeluar/pengumuman'));
					}elseif (substr($this->input->post('surat_id'), 0,3) == 'LAP') {
						redirect(site_url('suratkeluar/laporan'));
					}elseif (substr($this->input->post('surat_id'), 0,3) == 'REK') {
						redirect(site_url('suratkeluar/rekomendasi'));
					}elseif (substr($this->input->post('surat_id'), 0,3) == 'INT') {
						redirect(site_url('suratkeluar/instruksi'));
					}elseif (substr($this->input->post('surat_id'), 0,3) == 'PNG') {
						redirect(site_url('suratkeluar/pengantar'));
					}elseif (substr($this->input->post('surat_id'), 0,5) == 'NODIN') {
						redirect(site_url('suratkeluar/notadinas'));
					}elseif (substr($this->input->post('surat_id'), 0,2) == 'SK') {
						redirect(site_url('suratkeluar/keterangan'));
					}elseif (substr($this->input->post('surat_id'), 0,3) == 'SPT') {
						redirect(site_url('suratkeluar/perintahtugas'));
					}elseif (substr($this->input->post('surat_id'), 0,2) == 'SP') {
						redirect(site_url('suratkeluar/perintah'));
					}elseif (substr($this->input->post('surat_id'), 0,3) == 'IZN') {
						redirect(site_url('suratkeluar/izin'));
					}elseif (substr($this->input->post('surat_id'), 0,3) == 'PJL') {
						redirect(site_url('suratkeluar/perjalanan'));
					}elseif (substr($this->input->post('surat_id'), 0,3) == 'KSA') {
						redirect(site_url('suratkeluar/kuasa'));
					}elseif (substr($this->input->post('surat_id'), 0,3) == 'MKT') {
						redirect(site_url('suratkeluar/melaksanakan_tugas'));
					}elseif (substr($this->input->post('surat_id'), 0,3) == 'PGL') {
						redirect(site_url('suratkeluar/panggilan'));
					}elseif (substr($this->input->post('surat_id'), 0,3) == 'NTL') {
						redirect(site_url('suratkeluar/notulen'));
					}elseif (substr($this->input->post('surat_id'), 0,3) == 'MMO') {
						redirect(site_url('suratkeluar/memo'));
					}
				}
				
			}else{
				$this->session->set_flashdata('error', 'Surat Gagal Diteruskan');
				redirect(site_url('suratkeluar/draft'));
			}
		}elseif (isset($_POST['kembalikan'])) {
			$kembalikanDari = $this->db->limit(1)->order_by('verifikasi_id', 'ASC')->get_where('verifikasi', array('surat_id' => $this->input->post('surat_id')))->row_array();
			$kembalikanUntuk = $this->db->limit(1)->order_by('verifikasi_id', 'DESC')->get_where('verifikasi', array('surat_id' => $this->input->post('surat_id')))->row_array();
			$verifikasi = $this->db->get_where('jabatan', array('atasan_id' => $this->session->userdata('jabatan_id')))->row_array();
			$data = array(
				'untuk' => $kembalikanDari['dari'], 
				'dari' => $kembalikanUntuk['untuk'], 
				'surat_id' => htmlentities($this->input->post('surat_id')), 
				'keterangan' => htmlentities($this->input->post('keterangan')), 
			);
			$verify = $this->draft_model->insert_data('verifikasi', $data);
			if ($verify) {
				$kembalikan = $this->db->get_where('draft', array('surat_id' => $this->input->post('surat_id')))->row_array();
				$verifikasi = array('verifikasi_id' => $kembalikan['dibuat_id']);
				$where = array('surat_id' => htmlentities($this->input->post('surat_id')));
				$this->draft_model->update_data('draft', $verifikasi, $where);

				$this->session->set_flashdata('success', 'Surat Berhasil Dikembalikan');
				redirect(site_url('suratkeluar/draft'));
			}else{
				$this->session->set_flashdata('error', 'Surat Gagal Dikembalikan');
				redirect(site_url('suratkeluar/draft'));
			}
		}elseif (isset($_POST['selesai'])){
			$opd_id = $this->session->userdata('opd_id');
			$jabatan_id = $this->session->userdata('jabatan_id');
			
			$getTU = getTU($opd_id);
			$verifikasi = array('verifikasi_id' => $getTU['jabatan_id']);
			$where = array('surat_id' => htmlentities($this->input->post('surat_id')));
			$selesai = $this->draft_model->update_data('draft', $verifikasi, $where);

			if ($selesai) {

				$dari = $this->draft_model->dari_untuk_aparatur($jabatan_id)->row_array();
				$data = array(
					'untuk' => $getTU['nama'].' - '.$getTU['nama_jabatan'], 
					'dari' => $dari['nama'].' - '.$dari['nama_jabatan'], 
					'surat_id' => htmlentities($this->input->post('surat_id')), 
					'keterangan' => 'Surat telah diselesaikan', 
				);
				$this->draft_model->insert_data('verifikasi', $data);

				$this->session->set_flashdata('success', 'Surat Berhasil Diselesaikan');
				redirect(site_url('suratkeluar/draft'));
			}else{
				$this->session->set_flashdata('error', 'Surat Gagal Diselesaikan');
				redirect(site_url('suratkeluar/draft'));
			}
		}
	}
	
	public function disposisi()
	{
		$surat_id = $this->input->post('surat_id');
		if (isset($_POST['simpan'])) {

			$where = array('id' => $surat_id);

			if (substr($surat_id, 0,2) == 'SB') {

				if (!empty($this->input->post('no_urut'))) {
					$kodeSurat = $this->draft_model->nomor_surat('surat_biasa',$surat_id)->row_array();
					$whereNomor = $kodeSurat['kode'].'/'.htmlentities($this->input->post('no_urut')).'-'.htmlentities($this->input->post('kode_bidang'));
					$nomor = array('nomor' => $whereNomor);					
				}else{
					$nomor = array('nomor' => $this->input->post('nomor'));
				}
				$update = $this->draft_model->update_data('surat_biasa', $nomor, $where);
				
			}elseif (substr($surat_id, 0,2) == 'SE') {

				if (!empty($this->input->post('no_urut'))) {
					$kodeSurat = $this->draft_model->nomor_surat('surat_edaran',$surat_id)->row_array();
					$whereNomor = $kodeSurat['kode'].'/'.htmlentities($this->input->post('no_urut')).'-'.htmlentities($this->input->post('kode_bidang'));
					$nomor = array('nomor' => $whereNomor);					
				}else{
					$nomor = array('nomor' => $this->input->post('nomor'));
				}
				$update = $this->draft_model->update_data('surat_edaran', $nomor, $where);
			
			}elseif (substr($this->input->post('surat_id'), 0,2) == 'SU') {

				if (!empty($this->input->post('no_urut'))) {
					$kodeSurat = $this->draft_model->nomor_surat('surat_undangan',$surat_id)->row_array();
					$whereNomor = $kodeSurat['kode'].'/'.htmlentities($this->input->post('no_urut')).'-'.htmlentities($this->input->post('kode_bidang'));
					$nomor = array('nomor' => $whereNomor);					
				}else{
					$nomor = array('nomor' => $this->input->post('nomor'));
				}
				$update = $this->draft_model->update_data('surat_undangan', $nomor, $where);

			}elseif (substr($this->input->post('surat_id'), 0,3) == 'LAP') {

				if (!empty($this->input->post('no_urut'))) {
					$kodeSurat = $this->draft_model->nomor_surat('surat_laporan',$surat_id)->row_array();
					$whereNomor = $kodeSurat['kode'].'/'.htmlentities($this->input->post('no_urut')).'-'.htmlentities($this->input->post('kode_bidang'));
					$nomor = array('nomor' => $whereNomor);					
				}else{
					$nomor = array('nomor' => $this->input->post('nomor'));
				}
				$update = $this->draft_model->update_data('surat_laporan', $nomor, $where);

			}elseif (substr($this->input->post('surat_id'), 0,5) == 'PNGMN') {

				if (!empty($this->input->post('no_urut'))) {
					$kodeSurat = $this->draft_model->nomor_surat('surat_pengumuman',$surat_id)->row_array();
					$whereNomor = $kodeSurat['kode'].'/'.htmlentities($this->input->post('no_urut')).'-'.htmlentities($this->input->post('kode_bidang'));
					$nomor = array('nomor' => $whereNomor);					
				}else{
					$nomor = array('nomor' => $this->input->post('nomor'));
				}
				$update = $this->draft_model->update_data('surat_pengumuman', $nomor, $where);

			}elseif (substr($this->input->post('surat_id'), 0,3) == 'REK') {

				if (!empty($this->input->post('no_urut'))) {
					$kodeSurat = $this->draft_model->nomor_surat('surat_rekomendasi',$surat_id)->row_array();
					$whereNomor = $kodeSurat['kode'].'/'.htmlentities($this->input->post('no_urut')).'-'.htmlentities($this->input->post('kode_bidang'));
					$nomor = array('nomor' => $whereNomor);					
				}else{
					$nomor = array('nomor' => $this->input->post('nomor'));
				}
				$update = $this->draft_model->update_data('surat_rekomendasi', $nomor, $where);

			}elseif (substr($this->input->post('surat_id'), 0,3) == 'INT') {

				if (!empty($this->input->post('no_urut'))) {
					$kodeSurat = $this->draft_model->nomor_surat('surat_instruksi',$surat_id)->row_array();
					$whereNomor = $kodeSurat['kode'].'/'.htmlentities($this->input->post('no_urut')).'-'.htmlentities($this->input->post('kode_bidang'));
					$nomor = array('nomor' => $whereNomor);					
				}else{
					$nomor = array('nomor' => $this->input->post('nomor'));
				}
				$update = $this->draft_model->update_data('surat_instruksi', $nomor, $where);

			}elseif (substr($this->input->post('surat_id'), 0,3) == 'PNG') {
				
				if (!empty($this->input->post('no_urut'))) {
					$kodeSurat = $this->draft_model->nomor_surat('surat_pengantar',$surat_id)->row_array();
					$whereNomor = $kodeSurat['kode'].'/'.htmlentities($this->input->post('no_urut')).'-'.htmlentities($this->input->post('kode_bidang'));
					$nomor = array('nomor' => $whereNomor);					
				}else{
					$nomor = array('nomor' => $this->input->post('nomor'));
				}
				$update = $this->draft_model->update_data('surat_pengantar', $nomor, $where);
			
			}elseif (substr($this->input->post('surat_id'), 0,5) == 'NODIN') {
			
				if (!empty($this->input->post('no_urut'))) {
					$kodeSurat = $this->draft_model->nomor_surat('surat_notadinas',$surat_id)->row_array();
					$whereNomor = $kodeSurat['kode'].'/'.htmlentities($this->input->post('no_urut')).'-'.htmlentities($this->input->post('kode_bidang'));
					$nomor = array('nomor' => $whereNomor);					
				}else{
					$nomor = array('nomor' => $this->input->post('nomor'));
				}
				$update = $this->draft_model->update_data('surat_notadinas', $nomor, $where);
			
			}elseif (substr($this->input->post('surat_id'), 0,2) == 'SK') {
			
				if (!empty($this->input->post('no_urut'))) {
					$kodeSurat = $this->draft_model->nomor_surat('surat_keterangan',$surat_id)->row_array();
					$whereNomor = $kodeSurat['kode'].'/'.htmlentities($this->input->post('no_urut')).'-'.htmlentities($this->input->post('kode_bidang'));
					$nomor = array('nomor' => $whereNomor);					
				}else{
					$nomor = array('nomor' => $this->input->post('nomor'));
				}
				$update = $this->draft_model->update_data('surat_keterangan', $nomor, $where);
			
			}elseif (substr($this->input->post('surat_id'), 0,3) == 'SPT') {
			
				if (!empty($this->input->post('no_urut'))) {
					$kodeSurat = $this->draft_model->nomor_surat('surat_perintahtugas',$surat_id)->row_array();
					$whereNomor = $kodeSurat['kode'].'/'.htmlentities($this->input->post('no_urut')).'-'.htmlentities($this->input->post('kode_bidang'));
					$nomor = array('nomor' => $whereNomor);					
				}else{
					$nomor = array('nomor' => $this->input->post('nomor'));
				}
				$update = $this->draft_model->update_data('surat_perintahtugas', $nomor, $where);
			
			}elseif (substr($this->input->post('surat_id'), 0,2) == 'SP') {
			
				if (!empty($this->input->post('no_urut'))) {
					$kodeSurat = $this->draft_model->nomor_surat('surat_perintah',$surat_id)->row_array();
					$whereNomor = $kodeSurat['kode'].'/'.htmlentities($this->input->post('no_urut')).'-'.htmlentities($this->input->post('kode_bidang'));
					$nomor = array('nomor' => $whereNomor);					
				}else{
					$nomor = array('nomor' => $this->input->post('nomor'));
				}
				$update = $this->draft_model->update_data('surat_perintah', $nomor, $where);
			
			}elseif (substr($this->input->post('surat_id'), 0,3) == 'IZN') {
			
				if (!empty($this->input->post('no_urut'))) {
					$kodeSurat = $this->draft_model->nomor_surat('surat_izin',$surat_id)->row_array();
					$whereNomor = $kodeSurat['kode'].'/'.htmlentities($this->input->post('no_urut')).'-'.htmlentities($this->input->post('kode_bidang'));
					$nomor = array('nomor' => $whereNomor);					
				}else{
					$nomor = array('nomor' => $this->input->post('nomor'));
				}
				$update = $this->draft_model->update_data('surat_izin', $nomor, $where);
			
			}elseif (substr($this->input->post('surat_id'), 0,3) == 'PJL') {
			
				if (!empty($this->input->post('no_urut'))) {
					$kodeSurat = $this->draft_model->nomor_surat('surat_perjalanan',$surat_id)->row_array();
					$whereNomor = $kodeSurat['kode'].'/'.htmlentities($this->input->post('no_urut')).'-'.htmlentities($this->input->post('kode_bidang'));
					$nomor = array('nomor' => $whereNomor);					
				}else{
					$nomor = array('nomor' => $this->input->post('nomor'));
				}
				$update = $this->draft_model->update_data('surat_perjalanan', $nomor, $where);
			
			}elseif (substr($this->input->post('surat_id'), 0,3) == 'KSA') {
			
				if (!empty($this->input->post('no_urut'))) {
					$kodeSurat = $this->draft_model->nomor_surat('surat_kuasa',$surat_id)->row_array();
					$whereNomor = $kodeSurat['kode'].'/'.htmlentities($this->input->post('no_urut')).'-'.htmlentities($this->input->post('kode_bidang'));
					$nomor = array('nomor' => $whereNomor);					
				}else{
					$nomor = array('nomor' => $this->input->post('nomor'));
				}
				$update = $this->draft_model->update_data('surat_kuasa', $nomor, $where);
			
			}elseif (substr($this->input->post('surat_id'), 0,3) == 'MKT') {
			
				if (!empty($this->input->post('no_urut'))) {
					$kodeSurat = $this->draft_model->nomor_surat('surat_melaksanakantugas',$surat_id)->row_array();
					$whereNomor = $kodeSurat['kode'].'/'.htmlentities($this->input->post('no_urut')).'-'.htmlentities($this->input->post('kode_bidang'));
					$nomor = array('nomor' => $whereNomor);					
				}else{
					$nomor = array('nomor' => $this->input->post('nomor'));
				}
				$update = $this->draft_model->update_data('surat_melaksanakantugas', $nomor, $where);
			
			}elseif (substr($this->input->post('surat_id'), 0,3) == 'PGL') {
			
				if (!empty($this->input->post('no_urut'))) {
					$kodeSurat = $this->draft_model->nomor_surat('surat_panggilan',$surat_id)->row_array();
					$whereNomor = $kodeSurat['kode'].'/'.htmlentities($this->input->post('no_urut')).'-'.htmlentities($this->input->post('kode_bidang'));
					$nomor = array('nomor' => $whereNomor);					
				}else{
					$nomor = array('nomor' => $this->input->post('nomor'));
				}
				$update = $this->draft_model->update_data('surat_panggilan', $nomor, $where);
			
			}elseif (substr($this->input->post('surat_id'), 0,3) == 'NTL') {
			
				if (!empty($this->input->post('no_urut'))) {
					$kodeSurat = $this->draft_model->nomor_surat('surat_notulen',$surat_id)->row_array();
					$whereNomor = $kodeSurat['kode'].'/'.htmlentities($this->input->post('no_urut')).'-'.htmlentities($this->input->post('kode_bidang'));
					$nomor = array('nomor' => $whereNomor);					
				}else{
					$nomor = array('nomor' => $this->input->post('nomor'));
				}
				$update = $this->draft_model->update_data('surat_notulen', $nomor, $where);
			
			}elseif (substr($this->input->post('surat_id'), 0,3) == 'MMO') {
			
				if (!empty($this->input->post('no_urut'))) {
					$kodeSurat = $this->draft_model->nomor_surat('surat_memo',$surat_id)->row_array();
					$whereNomor = $kodeSurat['kode'].'/'.htmlentities($this->input->post('no_urut')).'-'.htmlentities($this->input->post('kode_bidang'));
					$nomor = array('nomor' => $whereNomor);					
				}else{
					$nomor = array('nomor' => $this->input->post('nomor'));
				}
				$update = $this->draft_model->update_data('surat_memo', $nomor, $where);
			
			}

			if ($update) {

				//id penandatangan
				$getID = $this->db->get('penandatangan')->result();
				foreach ($getID as $key => $h) {
					$id = $h->penandatangan_id;
				}
				if (empty($id)) {
					$penandatangan_id = '1';
				}else{
					$urut = $id+1;
					$penandatangan_id = $urut;
				}

				//get nama dan jabatan penandatangan
				$getnamajabatan = $this->draft_model->penandatangan($this->session->userdata('opd_id'),$this->input->post('jabatan_id'))->row_array();

				$dataTtd = array(
					'penandatangan_id' => $penandatangan_id,
					'surat_id' => $surat_id,
					'jabatan_id' => $this->input->post('jabatan_id'),
					'nama' => $getnamajabatan['nama'],
					'jabatan' => $getnamajabatan['nama_jabatan'],
				);
				// echo print_r($dataTtd);
				$this->draft_model->insert_data('penandatangan', $dataTtd);

				$penandatangan = array('penandatangan_id' => $penandatangan_id);
				$where = array('surat_id' => $surat_id);
				$this->draft_model->update_data('draft', $penandatangan, $where);

				$this->session->set_flashdata('success', 'Penomoran Surat Berhasil');
				redirect(site_url('suratkeluar/draft/disposisi/'.$surat_id));
			}else{
				$this->session->set_flashdata('error', 'Penomoran Surat Gagal');
				redirect(site_url('suratkeluar/draft/disposisi/'.$surat_id));
			}
				
		}elseif(isset($_POST['arsipkan'])){
			$data = array(
				'surat_id' => htmlentities($this->input->post('surat_id')), 
				'no_rak' => htmlentities($this->input->post('no_rak')), 
				'no_sampul' => htmlentities($this->input->post('no_sampul')), 
				'no_book' => htmlentities($this->input->post('no_book')),
			);
			$pengarsipan = $this->draft_model->insert_data('pengarsipan', $data);
			if ($pengarsipan) {
				$verifikasi = array('verifikasi_id' => '-1');
				$where = array('surat_id' => $surat_id);
				$this->draft_model->update_data('draft', $verifikasi, $where);

				$this->session->set_flashdata('success', 'Surat Berhasil Diarsipkan');
				redirect(site_url('suratkeluar/draft'));
			}else{
				$this->session->set_flashdata('error', 'Surat Gagal Diarsipkan');
				redirect(site_url('suratkeluar/draft'));
			}
		}else{
			$surat_id = $this->uri->segment(4);
			
			$data['content'] = 'draft_form';

			$data['penandatangan'] = $this->draft_model->penandatangan($this->session->userdata('opd_id'),'')->result();

			if (substr($surat_id, 0,2) == 'SB') {
				$qnomor = $this->draft_model->edit_data('surat_biasa',array('id' => $surat_id))->result();
			}elseif (substr($surat_id, 0,2) == 'SE') {
				$qnomor = $this->draft_model->edit_data('surat_edaran',array('id' => $surat_id))->result();
			}elseif (substr($surat_id, 0,2) == 'SU') {
				$qnomor = $this->draft_model->edit_data('surat_undangan',array('id' => $surat_id))->result();
			}elseif (substr($surat_id, 0,5) == 'PNGMN') {
				$qnomor = $this->draft_model->edit_data('surat_pengumuman',array('id' => $surat_id))->result();
			}elseif (substr($surat_id, 0,3) == 'LAP') {
				$qnomor = $this->draft_model->edit_data('surat_laporan',array('id' => $surat_id))->result();;
			}elseif (substr($surat_id, 0,3) == 'REK') {
				$qnomor = $this->draft_model->edit_data('surat_rekomendasi',array('id' => $surat_id))->result();
			}elseif (substr($surat_id, 0,3) == 'INT') {
				$qnomor = $this->draft_model->edit_data('surat_instruksi',array('id' => $surat_id))->result();
			}elseif (substr($surat_id, 0,3) == 'PNG') {
				$qnomor = $this->draft_model->edit_data('surat_pengantar',array('id' => $surat_id))->result();
			}elseif (substr($surat_id, 0,5) == 'NODIN') {
				$qnomor = $this->draft_model->edit_data('surat_notadinas',array('id' => $surat_id))->result();
			}elseif (substr($surat_id, 0,2) == 'SK') {
				$qnomor = $this->draft_model->edit_data('surat_keterangan',array('id' => $surat_id))->result();
			}elseif (substr($surat_id, 0,3) == 'SPT') {
				$qnomor = $this->draft_model->edit_data('surat_perintahtugas',array('id' => $surat_id))->result();
			}elseif (substr($surat_id, 0,2) == 'SP') {
				$qnomor = $this->draft_model->edit_data('surat_perintah',array('id' => $surat_id))->result();
			}elseif (substr($surat_id, 0,3) == 'IZN') {
				$qnomor = $this->draft_model->edit_data('surat_izin',array('id' => $surat_id))->result();
			}elseif (substr($surat_id, 0,3) == 'PJL') {
				$qnomor = $this->draft_model->edit_data('surat_perjalanan',array('id' => $surat_id))->result();
			}elseif (substr($surat_id, 0,3) == 'KSA') {
				$qnomor = $this->draft_model->edit_data('surat_kuasa',array('id' => $surat_id))->result();
			}elseif (substr($surat_id, 0,3) == 'MKT') {
				$qnomor = $this->draft_model->edit_data('surat_melaksanakantugas',array('id' => $surat_id))->result();
			}elseif (substr($surat_id, 0,3) == 'PGL') {
				$qnomor = $this->draft_model->edit_data('surat_panggilan',array('id' => $surat_id))->result();
			}elseif (substr($surat_id, 0,3) == 'NTL') {
				$qnomor = $this->draft_model->edit_data('surat_notulen',array('id' => $surat_id))->result();
			}elseif (substr($surat_id, 0,3) == 'MMO') {
				$qnomor = $this->draft_model->edit_data('surat_memo',array('id' => $surat_id))->result();
			}
			
			if (empty($qnomor)) {
				$data['nomor'] = '';
			}else{
				foreach ($qnomor as $key => $h) {
					$data['nomor'] = $h->nomor;
				}
			}

			$qttd = $this->draft_model->edit_data('penandatangan',array('surat_id' => $surat_id))->result();
			foreach ($qttd as $key => $h) {
				$data['ttd'] = $h->nama.' - '.$h->jabatan;
				$data['status'] = $h->status;
			}
			
			$this->load->view('template', $data);
		}

	}

	public function signature()
	{
		$data['content'] = 'signature_index';
		
		$tahun = $this->session->userdata('tahun');
		$jabatan_id = $this->session->userdata('jabatan_id');

		$data['tandatangan'] = $this->draft_model->get_tandatangan($jabatan_id)->result();
		
		$this->load->view('template', $data);
	}

	public function signer()
	{
		$surat_id = htmlentities($this->input->post('surat_id'));
		$tandatangan = array('status' => 'Sudah Ditandatangani');
		$where = array('surat_id' => $surat_id);
		$signer = $this->draft_model->update_data('penandatangan', $tandatangan, $where);
		if ($signer) {
			$surat = $this->db->get_where('draft', $where)->row_array();
			$filesurat = $surat['nama_surat'].' - '.tanggal($surat['tanggal']).' ('.$surat['surat_id'].').pdf';
			kirim_email_eksternal($surat_id,$filesurat);
		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Ditandatangan');
			redirect(site_url('suratkeluar/draft/signature'));
		}
	}

}