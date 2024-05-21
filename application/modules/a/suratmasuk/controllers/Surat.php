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
		$data['content'] = 'surat_index';
		
		$tahun = $this->session->userdata('tahun');
		$opd_id = $this->session->userdata('opd_id');

		$data['suratmasuk'] = $this->surat_model->get($opd_id,$tahun)->result();
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
		}
		
		$this->load->view('template', $data);
	}

	public function insert()
	{
		$surat = $_FILES['lampiran']['name'];
		$file = $_FILES['lampiran_lain']['name'];
		$surat_id = $this->input->post('surat_id');

		//untuk surat manual
		if (empty($surat_id)) {
			
			if (empty($file)) {
				$ambext = explode(".",$surat);
				$ekstensi = end($ambext);
				$nama_baru = date('YmdHis');
				$nama_file = $nama_baru.".".$ekstensi;	
				$config['upload_path'] = './assets/surat/';
				$config['allowed_types'] = 'pdf';
				$config['file_name'] = $nama_file;
				$this->upload->initialize($config);

				if(!$this->upload->do_upload('lampiran')){
					$this->session->set_flashdata('error','Upload Surat Gagal');
					redirect(site_url('suratmasuk/surat/add'));
				}else{
					$data = array(
						'dari' => htmlentities($this->input->post('dari')),
						'nomor' => htmlentities($this->input->post('nomor')),
						'tanggal' => htmlentities($this->input->post('tanggal')), 
						'lampiran' => $nama_file, 
						'hal' => htmlentities($this->input->post('hal')), 
						'diterima' => htmlentities($this->input->post('diterima')), 
						'penerima' => htmlentities($this->input->post('penerima')), 
						'opd_id' => $this->session->userdata('opd_id'),
						'indeks' => htmlentities($this->input->post('indeks')), 
						'kodesurat_id' => htmlentities($this->input->post('kodesurat_id')), 
						'sifat' => htmlentities($this->input->post('sifat')), 
						'lampiran_lain' => '',
						'telp' => htmlentities($this->input->post('telp')), 
						'isi' => $this->input->post('isi'),  
						'catatan' => $this->input->post('catatan'),  
					);
					$insert = $this->surat_model->insert_data('surat_masuk', $data);
					if ($insert) {
						$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
						redirect(site_url('suratmasuk/surat'));
					}else{
						$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
						redirect(site_url('suratmasuk/surat/add'));
					}
				}
			}else{
				$ambext = explode(".",$surat);
				$ekstensi = end($ambext);
				$nama_baru = date('YmdHis');
				$nama_file_surat = $nama_baru.".".$ekstensi;	
				$config['upload_path'] = './assets/surat/';
				$config['allowed_types'] = 'pdf';
				$config['file_name'] = $nama_file_surat;
				$this->upload->initialize($config);

				if(!$this->upload->do_upload('lampiran')){
					$this->session->set_flashdata('error','Upload Surat Gagal');
					redirect(site_url('suratmasuk/surat/add'));
				}else{
					$ambext = explode(".",$file);
					$ekstensi = end($ambext);
					$nama_baru = date('YmdHis');
					$nama_file = $nama_baru.".".$ekstensi;	
					$config['upload_path'] = './assets/lampiransuratmasuk/';
					$config['allowed_types'] = 'pdf';
					$config['file_name'] = $nama_file;
					$this->upload->initialize($config);

					if(!$this->upload->do_upload('lampiran_lain')){
						$this->session->set_flashdata('error','Upload Lampiran Gagal');
						redirect(site_url('suratmasuk/surat/add'));
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
							'indeks' => htmlentities($this->input->post('indeks')), 
							'kodesurat_id' => htmlentities($this->input->post('kodesurat_id')), 
							'sifat' => htmlentities($this->input->post('sifat')), 
							'lampiran_lain' => $nama_file,
							'telp' => htmlentities($this->input->post('telp')), 
							'isi' => $this->input->post('isi'),  
							'catatan' => $this->input->post('catatan'),  
						);
						$insert = $this->surat_model->insert_data('surat_masuk', $data);
						if ($insert) {
							$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
							redirect(site_url('suratmasuk/surat'));
						}else{
							$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
							redirect(site_url('suratmasuk/surat/add'));
						}
					}
				}
			}

		
		//untuk surat otomatis
		}else{
			
			if (empty($file)) {
				$data = array(
					'dari' => htmlentities($this->input->post('dari')),
					'nomor' => htmlentities($this->input->post('nomor')),
					'tanggal' => htmlentities($this->input->post('tanggal')), 
					'lampiran' => $this->input->post('lampiran'), 
					'hal' => htmlentities($this->input->post('hal')), 
					'diterima' => htmlentities($this->input->post('diterima')), 
					'penerima' => htmlentities($this->input->post('penerima')), 
					'opd_id' => $this->session->userdata('opd_id'),
					'indeks' => htmlentities($this->input->post('indeks')), 
					'kodesurat_id' => htmlentities($this->input->post('kodesurat_id')), 
					'sifat' => htmlentities($this->input->post('sifat')), 
					'lampiran_lain' => '',
					'telp' => htmlentities($this->input->post('telp')), 
					'isi' => $this->input->post('isi'),  
					'catatan' => $this->input->post('catatan'),  
				);
				$insert = $this->surat_model->insert_data('surat_masuk', $data);
				if ($insert) {
					$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
					redirect(site_url('suratmasuk/surat'));
				}else{
					$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
					redirect(site_url('suratmasuk/surat/add'));
				}
			}else{
				$ambext = explode(".",$file);
				$ekstensi = end($ambext);
				$nama_baru = date('YmdHis');
				$nama_file = $nama_baru.".".$ekstensi;	
				$config['upload_path'] = './assets/lampiransuratmasuk/';
				$config['allowed_types'] = 'pdf';
				$config['file_name'] = $nama_file;
				$this->upload->initialize($config);

				if(!$this->upload->do_upload('lampiran_lain')){
					$this->session->set_flashdata('error','Upload Lampiran Gagal');
					redirect(site_url('suratmasuk/surat/add'));
				}else{
					$data = array(
						'dari' => htmlentities($this->input->post('dari')),
						'nomor' => htmlentities($this->input->post('nomor')),
						'tanggal' => htmlentities($this->input->post('tanggal')), 
						'lampiran' => $this->input->post('lampiran'), 
						'hal' => htmlentities($this->input->post('hal')), 
						'diterima' => htmlentities($this->input->post('diterima')), 
						'penerima' => htmlentities($this->input->post('penerima')), 
						'opd_id' => $this->session->userdata('opd_id'),
						'indeks' => htmlentities($this->input->post('indeks')), 
						'kodesurat_id' => htmlentities($this->input->post('kodesurat_id')), 
						'sifat' => htmlentities($this->input->post('sifat')), 
						'lampiran_lain' => $nama_file,
						'telp' => htmlentities($this->input->post('telp')), 
						'isi' => $this->input->post('isi'),  
						'catatan' => $this->input->post('catatan'),  
					);
					$insert = $this->surat_model->insert_data('surat_masuk', $data);
					if ($insert) {
						$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
						redirect(site_url('suratmasuk/surat'));
					}else{
						$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
						redirect(site_url('suratmasuk/surat/add'));
					}
				}
			}

		}

		
	}

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