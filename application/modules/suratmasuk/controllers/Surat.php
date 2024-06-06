<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Surat extends CI_Controller
{

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
		$opdid = $this->session->userdata('opd_id');

		// search
		if ($this->input->get('submit')) {
			$search['cari'] = $this->input->get('cari');
			$this->session->userdata('cari', $search['cari']);
		} else {
			$search['cari'] = $this->session->userdata("cari");
		}
		// config pagination
		$config['base_url'] = base_url("suratmasuk/surat/index/");
		$config['total_rows'] = $this->surat_model->countSuratMasuk($search['cari']);
		$data['total_rows'] = $config['total_rows'];
		if (!empty($search['cari'])) {
			$config['per_page'] = 50;
		} else {
			$config['per_page'] = 10;
		}
		// initialize
		$this->pagination->initialize($config);

		// get data table !=0
		if ($this->uri->segment(4) == null) {
			$data['start'] = 0;
		} else {
			$data['start'] = $this->uri->segment(4);
		}

		$data['content'] = 'surat_index';

		$tahun = $this->session->userdata('tahun');
		$opd_id = $this->session->userdata('opd_id');

		$jabatanid = $this->session->userdata('jabatan_id');
		if ($jabatanid == '1286') {
			// $data['aparatur'] = $this->surat_model->get_global($opdid)->result();	
			$jabatan = $this->db->get_where('jabatan', array('jabatan_id' => $this->session->userdata('jabatan_id')))->row_array();
			$atasan = $jabatan['atasan_id'];
			$data['aparatur'] = $this->db->query("SELECT * FROM aparatur a LEFT JOIN jabatan b ON b.jabatan_id=a.jabatan_id WHERE a.jabatan_id='$atasan'")->result();
		} elseif ($jabatanid == '600') {
			$data['kelurahan'] = $this->surat_model->get_boteng($jabatanid)->result();
			$data['aparatur'] = $this->surat_model->get_atasan($jabatanid)->result();
		} elseif ($jabatanid == '611') {
			$data['kelurahan'] = $this->surat_model->get_bosel($jabatanid)->result();
			$data['aparatur'] = $this->surat_model->get_atasan($jabatanid)->result();
		} elseif ($jabatanid == '622') {
			$data['kelurahan'] = $this->surat_model->get_bobar($jabatanid)->result();
			$data['aparatur'] = $this->surat_model->get_atasan($jabatanid)->result();
		} elseif ($jabatanid == '633') {
			$data['kelurahan'] = $this->surat_model->get_bout($jabatanid)->result();
			$data['aparatur'] = $this->surat_model->get_atasan($jabatanid)->result();
		} elseif ($jabatanid == '644') {
			$data['kelurahan'] = $this->surat_model->get_botim($jabatanid)->result();
			$data['aparatur'] = $this->surat_model->get_atasan($jabatanid)->result();
		} elseif ($jabatanid == '655') {
			$data['kelurahan'] = $this->surat_model->get_tansar($jabatanid)->result();
			$data['aparatur'] = $this->surat_model->get_atasan($jabatanid)->result();
		}

		// Tampilan View Data untuk level 18 = Admin TU Sekretariat
		$levelid = $this->session->userdata('level');
		if ($levelid == 18) {
			$data['suratmasuk'] = $this->surat_model->getlevel18($config['per_page'], $data['start'], $levelid, $jabatanid, $tahun, $search['cari'])->result();
		} else {
			$data['suratmasuk'] = $this->surat_model->get($config['per_page'], $data['start'], $jabatanid, $tahun, $search['cari'])->result();
		}

		$this->load->view('template', $data);
	}

	//Start Menu Surat Masuk Hibah Editor : Muhamad Idham (18 Februari 2022)
	public function hibah()
	{
		$this->load->library("pagination");
		$opdid = $this->session->userdata('opd_id');

		// search
		if ($this->input->get('submit')) {
			$search['cari'] = $this->input->get('cari');
			$this->session->userdata('cari', $search['cari']);
		} else {
			$search['cari'] = $this->session->userdata("cari");
		}
		// config pagination
		$config['base_url'] = base_url("suratmasuk/surat/hibah/");
		$config['total_rows'] = $this->surat_model->countSuratHibah($search['cari']);
		$data['total_rows'] = $config['total_rows'];
		if (!empty($search['cari'])) {
			$config['per_page'] = 50;
		} else {
			$config['per_page'] = 10;
		}
		// initialize
		$this->pagination->initialize($config);

		// get data table !=0
		if ($this->uri->segment(4) == null) {
			$data['start'] = 0;
		} else {
			$data['start'] = $this->uri->segment(4);
		}

		$data['content'] = 'surat_hibah';

		$tahun = $this->session->userdata('tahun');
		$opd_id = $this->session->userdata('opd_id');

		$jabatanid = $this->session->userdata('jabatan_id');

		// Tampilan View Data untuk level 18 = Admin TU Sekretariat
		$levelid = $this->session->userdata('level');
		// 		if($levelid == 18){
		// 			$data['surathibah'] = $this->surat_model->getlevel18($config['per_page'],$data['start'],$levelid,$jabatanid,$tahun,$search['cari'])->result();
		// 		}else{
		$data['surathibah'] = $this->surat_model->get_surathibah($config['per_page'], $data['start'], $jabatanid, $tahun, $search['cari'])->result();
		// 		}

		$this->load->view('template', $data);
	}

	public function add()
	{
		$data['content'] 	= 'surat_form';
		$data['kodesurat'] 	= $this->db->get('kode_surat')->result();
		$data['jra'] 		= $this->db->from('jra')->order_by('series', 'asc')->get()->result();

		$surat_id = $this->uri->segment(4);

		if (substr($surat_id, 0, 2) == 'SB') {
			$data['surat'] = $this->db->get_where('surat_biasa', array('id' => $surat_id))->result();
			$data['jenissurat'] = 'Surat Biasa';
		} elseif (substr($surat_id, 0, 2) == 'SE') {
			$data['surat'] = $this->db->get_where('surat_edaran', array('id' => $surat_id))->result();
			$data['jenissurat'] = 'Surat Edaran';
		} elseif (substr($surat_id, 0, 2) == 'SU') {
			$data['surat'] = $this->db->get_where('surat_undangan', array('id' => $surat_id))->result();
			$data['jenissurat'] = 'Surat Undangan';
		} elseif (substr($surat_id, 0, 5) == 'PNGMN') {
			$data['surat'] = $this->db->get_where('surat_pengumuman', array('id' => $surat_id))->result();
			$data['jenissurat'] = 'Surat Pengumuman';
		} elseif (substr($surat_id, 0, 3) == 'LAP') {
			$data['surat'] = $this->db->get_where('surat_laporan', array('id' => $surat_id))->result();
			$data['jenissurat'] = 'Surat Laporan';
		} elseif (substr($surat_id, 0, 3) == 'REK') {
			$data['surat'] = $this->db->get_where('surat_rekomendasi', array('id' => $surat_id))->result();
			$data['jenissurat'] = 'Surat Rekomendasi';
		} elseif (substr($surat_id, 0, 3) == 'INT') {
			$data['surat'] = $this->db->get_where('surat_instruksi', array('id' => $surat_id))->result();
			$data['jenissurat'] = 'Surat Instruksi';
		} elseif (substr($surat_id, 0, 3) == 'PNG') {
			$data['surat'] = $this->db->get_where('surat_pengantar', array('id' => $surat_id))->result();
			$data['jenissurat'] = 'Surat Pengantar';
		} elseif (substr($surat_id, 0, 5) == 'NODIN') {
			$data['surat'] = $this->db->get_where('surat_notadinas', array('id' => $surat_id))->result();
			$data['jenissurat'] = 'Nota Dinas';
		} elseif (substr($surat_id, 0, 2) == 'SK') {
			$data['surat'] = $this->db->get_where('surat_keterangan', array('id' => $surat_id))->result();
			$data['jenissurat'] = 'Surat Keterangan';
		} elseif (substr($surat_id, 0, 3) == 'SPT') {
			$data['surat'] = $this->db->get_where('surat_perintahtugas', array('id' => $surat_id))->result();
			$data['jenissurat'] = 'Surat Perintah Tugas';
		} elseif (substr($surat_id, 0, 2) == 'SP') {
			$data['surat'] = $this->db->get_where('surat_perintah', array('id' => $surat_id))->result();
			$data['jenissurat'] = 'Surat Perintah';
		} elseif (substr($surat_id, 0, 3) == 'IZN') {
			$data['surat'] = $this->db->get_where('surat_izin', array('id' => $surat_id))->result();
			$data['jenissurat'] = 'Surat Izin';
		} elseif (substr($surat_id, 0, 3) == 'PJL') {
			$data['surat'] = $this->db->get_where('surat_perjalanandinas', array('id' => $surat_id))->result();
			$data['jenissurat'] = 'Surat Perjalanan Dinas';
		} elseif (substr($surat_id, 0, 3) == 'KSA') {
			$data['surat'] = $this->db->get_where('surat_kuasa', array('id' => $surat_id))->result();
			$data['jenissurat'] = 'Surat Kuasa';
		} elseif (substr($surat_id, 0, 3) == 'MKT') {
			$data['surat'] = $this->db->get_where('surat_melaksanakantugas', array('id' => $surat_id))->result();
			$data['jenissurat'] = 'Surat Melaksanakan Tugas';
		} elseif (substr($surat_id, 0, 3) == 'PGL') {
			$data['surat'] = $this->db->get_where('surat_panggilan', array('id' => $surat_id))->result();
			$data['jenissurat'] = 'Surat Panggilan';
		} elseif (substr($surat_id, 0, 3) == 'NTL') {
			$data['surat'] = $this->db->get_where('surat_notulen', array('id' => $surat_id))->result();
			$data['jenissurat'] = 'Surat Notulen';
		} elseif (substr($surat_id, 0, 3) == 'MMO') {
			$data['surat'] = $this->db->get_where('surat_memo', array('id' => $surat_id))->result();
			$data['jenissurat'] = 'Surat Memo';
		} elseif (substr($surat_id, 0, 3) == 'LMP') { //Penambahan Definisi Variabel Surat Lampiran oleh : @Muhamad Idham (14 Februari 2022)
			$data['surat'] = $this->db->get_where('surat_lampiran', array('id' => $surat_id))->result();
			$data['jenissurat'] = 'Surat Lampiran';
		} elseif (substr($surat_id, 0, 2) == 'SL') { //Penambahan Definisi Variabel Surat Lampiran oleh : @Muhamad Idham (14 Februari 2022)
			$data['surat'] = $this->db->get_where('surat_lainnya', array('id' => $surat_id))->result();
			$data['jenissurat'] = 'Surat Lainnya';
		}

		$this->load->view('template', $data);
	}

	public function insert()
	{
		$surat = $_FILES['lampiran']['name'];
		$file = $_FILES['lampiran_lain']['name'];
		$surat_id = $this->input->post('surat_id');
		$opdid = $this->session->userdata('opd_id');
		$tahun = $this->session->userdata('tahun');
		$jabatan = $this->session->userdata('jabatan_id');

		//Update @Mpik Egov 13/06/2022 09:10
		// Penambahan Generete Nomor Surat Masuk
		$ceknomor = $this->input->post('nomor');
		if (empty($ceknomor)) {
			$nomor = date("Ymdhis");
		} else {
			$nomor = $this->input->post('nomor');
		}
		// End
		//Update @Mpik Egov 13/06/2022 09:10

		//untuk surat manual
		if (empty($surat_id)) {

			if (empty($file)) {
				$ambext = explode(".", $surat);
				$ekstensi = end($ambext);
				$nama_baru = 'SuratMasuk' . date('YmdHis'); // @Mpik Egov 06 Juni 2023
				$nama_file = $nama_baru . "." . $ekstensi;
				// $config['upload_path'] = './assets/lampiransuratmasuk/';
				$config['upload_path'] = './assets/lampiransuratmasuk/';
				$config['allowed_types'] = 'pdf|jpg|jpeg|png';
				$config['max_size'] = 40000;
				$config['file_name'] = $nama_file;
				$this->upload->initialize($config);

				if ($this->upload->do_upload('lampiran')) {
					$data = array(
						'dari' => htmlentities($this->input->post('dari')),
						'dibuat_id' => $this->session->userdata('jabatan_id'),
						'nomor' => $nomor, //Update @Mpik Egov 13/06/2022 09:10 || Penambahan Generete Nomor Surat Masuk
						// 'nomor' => htmlentities($this->input->post('nomor')),
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
					$nomor = $this->input->post('nomor');
					$jabatanid 	= $this->session->userdata('jabatan_id');

					if ($opdid == 4) {
						$cekdoubleinput = $this->db->query("SELECT * FROM surat_masuk WHERE nomor='$nomor' AND dibuat_id='$jabatanid' and left(tanggal,4)='$tahun'")->num_rows();
					} else {
						$cekdoubleinput = $this->db->query("SELECT * FROM surat_masuk WHERE nomor='$nomor' AND opd_id='$opdid' and left(tanggal,4)='$tahun'")->num_rows();
					}

					if ($cekdoubleinput == 1) {
						$this->session->set_flashdata('error', 'Surat Sudah Pernah Dimasukkan');
						redirect(site_url('suratmasuk/surat'));
					} else {
						$insert = $this->surat_model->insert_data('surat_masuk', $data);
						if ($insert) {

							/* START:Input Retensi Arsip Surat Masuk [@Dam-Egov 11/01/2024] */
							$nomor = $data['nomor'];
							$surat = $this->db->query("SELECT suratmasuk_id, diterima, FROM surat_masuk WHERE nomor='$nomor' AND opd_id='$opdid' AND dibuat_id='$jabatan'")->row();

							$dataRetensi	= array(
								'surat_id' 			=> $surat->suratmasuk_id,
								'jenis_surat' 		=> 'Surat Masuk',
								'jra_id'			=> htmlentities($this->input->post('jra_id'))
							);

							$this->surat_model->insert_data('retensi_arsip', $dataRetensi);
							/* END:Input Retensi Arsip Surat Masuk [@Dam-Egov 11/01/2024] */

							$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
							redirect(site_url('suratmasuk/surat'));
						} else {
							$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
							redirect(site_url('suratmasuk/surat/add'));
						}
					}
					// CEK SURAT MASUK SUDAH ADA ATAU BELUM
				} else {
					// $this->session->set_flashdata('error', 'Upload Surat Gagal');
					$error = array('error' => $this->upload->display_errors());
					$this->session->set_flashdata('error', $error['error']);
					redirect(site_url('suratmasuk/surat/add'));
				}
			} else {
				$ambext = explode(".", $surat);
				$ekstensi = end($ambext);
				$nama_baru = 'SuratMasuk' . date('YmdHis'); // @MpikEgov 6 Juni 2023
				$nama_file_surat = $nama_baru . "." . $ekstensi;
				// $config['upload_path'] = './assets/lampiransuratmasuk/';
				$config['upload_path'] = './assets/lampiransuratmasuk/';
				$config['allowed_types'] = 'pdf|jpg|jpeg|png';
				$config['max_size'] = 40000;
				$config['file_name'] = $nama_file_surat;
				$this->upload->initialize($config);

				if (!$this->upload->do_upload('lampiran')) {
					$this->session->set_flashdata('error', 'Upload Surat Gagal');
					redirect(site_url('suratmasuk/surat/add'));
				} else {
					$ambext = explode(".", $file);
					$ekstensi = end($ambext);
					$nmr = date('YmdHis') + 1;
					$nama_baru = 'SuratMasuk' . $nmr; // @MpikEgov 6 Juni 2023
					$nama_file = $nama_baru . "." . $ekstensi;
					// $config['upload_path'] = './assets/lampiransuratmasuk/';
					$config['upload_path'] = './assets/lampiransuratmasuk/';
					$config['allowed_types'] = 'pdf|jpg|jpeg|png';
					$config['max_size'] = 40000;
					$config['file_name'] = $nama_file;
					$this->upload->initialize($config);

					if (!$this->upload->do_upload('lampiran_lain')) {
						$this->session->set_flashdata('error', 'Upload Lampiran Gagal');
						redirect(site_url('suratmasuk/surat/add'));
					} else {
						$data = array(
							'dari' => htmlentities($this->input->post('dari')),
							'dibuat_id' => $this->session->userdata('jabatan_id'),
							'nomor' => $nomor, //Update @Mpik Egov 13/06/2022 09:10 || Penambahan Generete Nomor Surat Masuk
							// 'nomor' => htmlentities($this->input->post('nomor')),
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
						$nomor 		= $this->input->post('nomor');
						$jabatanid 	= $this->session->userdata('jabatan_id');

						if ($opdid == 4) {
							$cekdoubleinput = $this->db->query("SELECT * FROM surat_masuk WHERE nomor='$nomor' AND dibuat_id='$jabatanid' and left(tanggal,4)='$tahun'")->num_rows();
						} else {
							$cekdoubleinput = $this->db->query("SELECT * FROM surat_masuk WHERE nomor='$nomor' AND opd_id='$opdid' and left(tanggal,4)='$tahun'")->num_rows();
						}
						
						if ($cekdoubleinput == 1) {
							$this->session->set_flashdata('error', 'Surat Sudah Pernah Dimasukkan');
							redirect(site_url('suratmasuk/surat'));
						} else {
							$insert = $this->surat_model->insert_data('surat_masuk', $data);
							if ($insert) {

								/* START:Input Retensi Arsip Surat Masuk [@Dam-Egov 11/01/2024] */
								$nomor = $data['nomor'];
								$surat_id = $this->db->query("SELECT suratmasuk_id, diterima FROM surat_masuk WHERE nomor='$nomor' AND opd_id='$opdid' AND dibuat_id='$jabatan'")->row();

								$dataRetensi	= array(
									'surat_id' 			=> $surat_id->suratmasuk_id,
									'jenis_surat' 		=> 'Surat Masuk',
									'jra_id'			=> htmlentities($this->input->post('jra_id'))
								);

								$this->surat_model->insert_data('retensi_arsip', $dataRetensi);
								/* END:Input Retensi Arsip Surat Masuk [@Dam-Egov 11/01/2024] */

								$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
								redirect(site_url('suratmasuk/surat'));
							} else {
								$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
								redirect(site_url('suratmasuk/surat/add'));
							}
						}
						// CEK SURAT MASUK SUDAH ADA ATAU BELUM
					}
				}
			}


			//untuk surat otomatis
		} else {
			// Penambahan kondisi lihat lampiran : Fikri Egov 2022
			// Cek Query Lampiran Lain

			if (substr($surat_id, 0, 2) == 'SB') {
				$ceklampiran = $this->db->query("SELECT a.lampiran_lain FROM surat_biasa a WHERE a.id='$surat_id'")->row_array();
			} elseif (substr($surat_id, 0, 2) == 'SE') {
				$ceklampiran = $this->db->query("SELECT a.lampiran_lain FROM surat_edaran a WHERE a.id='$surat_id'")->row_array();
			} elseif (substr($surat_id, 0, 2) == 'SU') {
				$ceklampiran = $this->db->query("SELECT a.lampiran_lain FROM surat_undangan a WHERE a.id='$surat_id'")->row_array();
			} elseif (substr($surat_id, 0, 5) == 'PNGMN') {
				$ceklampiran = $this->db->query("SELECT a.lampiran_lain FROM surat_pengumuman a WHERE a.id='$surat_id'")->row_array();
			} elseif (substr($surat_id, 0, 3) == 'LAP') {
				$ceklampiran = $this->db->query("SELECT a.lampiran_lain FROM surat_laporan a WHERE a.id='$surat_id'")->row_array();
			} elseif (substr($surat_id, 0, 3) == 'REK') {
				$ceklampiran = $this->db->query("SELECT a.lampiran_lain FROM surat_rekomendasi a WHERE a.id='$surat_id'")->row_array();
			} elseif (substr($surat_id, 0, 3) == 'INT') {
				$ceklampiran = $this->db->query("SELECT a.lampiran_lain FROM surat_instruksi a WHERE a.id='$surat_id'")->row_array();
			} elseif (substr($surat_id, 0, 3) == 'PNG') {
				$ceklampiran = $this->db->query("SELECT a.lampiran_lain FROM surat_pengantar a WHERE a.id='$surat_id'")->row_array();
			} elseif (substr($surat_id, 0, 5) == 'NODIN') {
				$ceklampiran = $this->db->query("SELECT a.lampiran_lain FROM surat_notadinas a WHERE a.id='$surat_id'")->row_array();
			} elseif (substr($surat_id, 0, 2) == 'SK') {
				$ceklampiran = $this->db->query("SELECT a.lampiran_lain FROM surat_keterangan a WHERE a.id='$surat_id'")->row_array();
			} elseif (substr($surat_id, 0, 3) == 'SPT') {
				$ceklampiran = $this->db->query("SELECT a.lampiran_lain FROM surat_perintahtugas a WHERE a.id='$surat_id'")->row_array();
			} elseif (substr($surat_id, 0, 2) == 'SP') {
				$ceklampiran = $this->db->query("SELECT a.lampiran_lain FROM surat_perintah a WHERE a.id='$surat_id'")->row_array();
			} elseif (substr($surat_id, 0, 3) == 'IZN') {
				$ceklampiran = $this->db->query("SELECT a.lampiran_lain FROM surat_izin a WHERE a.id='$surat_id'")->row_array();
			} elseif (substr($surat_id, 0, 3) == 'PJL') {
				$ceklampiran = $this->db->query("SELECT a.lampiran_lain FROM surat_perjalanan a WHERE a.id='$surat_id'")->row_array();
			} elseif (substr($surat_id, 0, 3) == 'KSA') {
				$ceklampiran = $this->db->query("SELECT a.lampiran_lain FROM surat_kuasa a WHERE a.id='$surat_id'")->row_array();
			} elseif (substr($surat_id, 0, 3) == 'MKT') {
				$ceklampiran = $this->db->query("SELECT a.lampiran_lain FROM surat_melaksanakantugas a WHERE a.id='$surat_id'")->row_array();
			} elseif (substr($surat_id, 0, 3) == 'PGL') {
				$ceklampiran = $this->db->query("SELECT a.lampiran_lain FROM surat_panggilan a WHERE a.id='$surat_id'")->row_array();
			} elseif (substr($surat_id, 0, 3) == 'NTL') {
				$ceklampiran = $this->db->query("SELECT a.lampiran_lain FROM surat_notulen a WHERE a.id='$surat_id'")->row_array();
			} elseif (substr($surat_id, 0, 3) == 'MMO') {
				$ceklampiran = $this->db->query("SELECT a.lampiran_lain FROM surat_memo a WHERE a.id='$surat_id'")->row_array();
			} elseif (substr($surat_id, 0, 3) == 'LMP') { //Penambahan Definisi Variabel Surat Lampiran oleh : @Muhamad Idham (14 Februari 2022)
				$ceklampiran = $this->db->query("SELECT a.lampiran_lain FROM surat_lampiran a WHERE a.id='$surat_id'")->row_array();
			}
			if ($ceklampiran['lampiran_lain'] == NULL) {
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
				$nomor = $this->input->post('nomor');
				$jabatanid 	= $this->session->userdata('jabatan_id');

				if ($opdid == 4) {
					$cekdoubleinput = $this->db->query("SELECT * FROM surat_masuk WHERE nomor='$nomor' AND dibuat_id='$jabatanid' and left(tanggal,4)='$tahun'")->num_rows();
				} else {
					$cekdoubleinput = $this->db->query("SELECT * FROM surat_masuk WHERE nomor='$nomor' AND opd_id='$opdid' and left(tanggal,4)='$tahun'")->num_rows();
				}

				if ($cekdoubleinput == 1) {
					$this->session->set_flashdata('error', 'Surat Sudah Pernah Dimasukkan');
					redirect(site_url('suratmasuk/surat'));
				} else {
					$insert = $this->surat_model->insert_data('surat_masuk', $data);
					if ($insert) {

						/* START:Input Retensi Arsip Surat Masuk [@Dam-Egov 11/01/2024] */
						$nomor = $data['nomor'];
						$surat = $this->db->query("SELECT suratmasuk_id, diterima FROM surat_masuk WHERE nomor='$nomor' AND opd_id='$opdid' AND dibuat_id='$jabatan'")->row();

						$dataRetensi	= array(
							'surat_id' 			=> $surat->suratmasuk_id,
							'jenis_surat' 		=> 'Surat Masuk',
							'jra_id'			=> htmlentities($this->input->post('jra_id'))
						);

						$this->surat_model->insert_data('retensi_arsip', $dataRetensi);
						/* END:Input Retensi Arsip Surat Masuk [@Dam-Egov 11/01/2024] */

						$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
						redirect(site_url('suratmasuk/surat'));
					} else {
						$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
						redirect(site_url('suratmasuk/surat/add'));
					}
				}
			} else {
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
					'lampiran_lain' => $this->input->post('lampiran_lain'),
					'telp' => $this->input->post('telp'),
					'isi' => $this->input->post('isi'),
					'catatan' => $this->input->post('catatan'),
				);
				// CEK SURAT MASUK SUDAH ADA ATAU BELUM
				$nomor = $this->input->post('nomor');
				$jabatanid 	= $this->session->userdata('jabatan_id');

				if ($opdid == 4) {
					$cekdoubleinput = $this->db->query("SELECT * FROM surat_masuk WHERE nomor='$nomor' AND dibuat_id='$jabatanid' and left(tanggal,4)='$tahun'")->num_rows();
				} else {
					$cekdoubleinput = $this->db->query("SELECT * FROM surat_masuk WHERE nomor='$nomor' AND opd_id='$opdid' and left(tanggal,4)='$tahun'")->num_rows();
				}
				
				if ($cekdoubleinput == 1) {
					$this->session->set_flashdata('error', 'Surat Sudah Pernah Dimasukkan');
					redirect(site_url('suratmasuk/surat'));
				} else {
					$insert = $this->surat_model->insert_data('surat_masuk', $data);
					if ($insert) {

						/* START:Input Retensi Arsip Surat Masuk [@Dam-Egov 11/01/2024] */
						$nomor = $data['nomor'];
						$surat = $this->db->query("SELECT suratmasuk_id, diterima FROM surat_masuk WHERE nomor='$nomor' AND opd_id='$opdid' AND dibuat_id='$jabatan'")->row();

						$dataRetensi	= array(
							'surat_id' 			=> $surat->suratmasuk_id,
							'jenis_surat' 		=> 'Surat Masuk',
							'jra_id'			=> htmlentities($this->input->post('jra_id'))
						);

						$this->surat_model->insert_data('retensi_arsip', $dataRetensi);
						/* END:Input Retensi Arsip Surat Masuk [@Dam-Egov 11/01/2024] */

						$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
						redirect(site_url('suratmasuk/surat'));
					} else {
						$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
						redirect(site_url('suratmasuk/surat/add'));
					}
				}
				// CEK SURAT MASUK SUDAH ADA ATAU BELUM
				// 			}else{
				// $ambext = explode(".",$file);
				// $ekstensi = end($ambext);
				// $nama_baru = date('YmdHis')+1;
				// $nama_file = $nama_baru.".".$ekstensi;	
				// $config['upload_path'] = './assets/lampiransuratmasuk/';
				// $config['allowed_types'] = 'pdf|jpg|jpeg|png';
				// $config['max_size']=40000;
				// $config['file_name'] = $nama_file;
				// $this->upload->initialize($config);

				// if(!$this->upload->do_upload('lampiran_lain')){
				// 	$this->session->set_flashdata('error','Upload Lampiran Gagal');
				// 	redirect(site_url('suratmasuk/surat/add'));
				// }else{
				// 	$data = array(
				// 		'dari' => htmlentities($this->input->post('dari')),
				// 		'dibuat_id' => $this->session->userdata('jabatan_id'),
				// 		'nomor' => htmlentities($this->input->post('nomor')),
				// 		'tanggal' => htmlentities($this->input->post('tanggal')), 
				// 		'lampiran' => $this->input->post('lampiran'), 
				// 		'hal' => htmlentities($this->input->post('hal')), 
				// 		'diterima' => htmlentities($this->input->post('diterima')), 
				// 		'penerima' => htmlentities($this->input->post('penerima')), 
				// 		'opd_id' => $this->session->userdata('opd_id'),
				// 		'indeks' => '', 
				// 		'kodesurat_id' => htmlentities($this->input->post('kodesurat_id')), 
				// 		'sifat' => htmlentities($this->input->post('sifat')), 
				// 		'lampiran_lain' => $nama_file,
				// 		'telp' => $this->input->post('telp'), 
				// 		'isi' => $this->input->post('isi'),  
				// 		'catatan' => $this->input->post('catatan'),  
				// 	);
				// 	// CEK SURAT MASUK SUDAH ADA ATAU BELUM
				// 	$nomor=$this->input->post('nomor');
				// 	$cekdoubleinput=$this->db->query("SELECT * FROM surat_masuk WHERE nomor='$nomor' AND opd_id='$opdid'")->num_rows();
				// 	if($cekdoubleinput == 1){
				// 		$this->session->set_flashdata('error', 'Surat Sudah Pernah Dimasukkan');
				// 		redirect(site_url('suratmasuk/surat'));
				// 	}else{
				// 		$insert = $this->surat_model->insert_data('surat_masuk', $data);
				// 		if ($insert) {
				// 			$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
				// 			redirect(site_url('suratmasuk/surat'));
				// 		}else{
				// 			$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
				// 			redirect(site_url('suratmasuk/surat/add'));
				// 		}
				// 	}
				// 	// CEK SURAT MASUK SUDAH ADA ATAU BELUM
				// }
			}
		}
	}

	public function edit()
	{
		$data['content'] 	= 'surat_form';
		$data['kodesurat'] 	= $this->db->get('kode_surat')->result();
		$data['jra'] 		= $this->db->from('jra')->order_by('series', 'asc')->get()->result();
		$data['suratmasuk'] = $this->surat_model->edit_data($this->uri->segment(4))->result();

		$surat_id			= $this->uri->segment(4);

		$this->load->view('template', $data);
	}

	public function update()
	{
		$id 		= $this->input->post('suratmasuk_id');
		$opdid 		= $this->session->userdata('opd_id');
		$jabatan 	= $this->session->userdata('jabatan_id');

		$surat 		= $_FILES['lampiran']['name'];
		$file 		= $_FILES['lampiran_lain']['name'];
		$getQuery 	= $this->db->query("
		SELECT * FROM surat_masuk
		LEFT JOIN opd ON opd.opd_id=surat_masuk.opd_id
		WHERE surat_masuk.suratmasuk_id='$id'
		")->result();
		foreach ($getQuery as $key => $h) {
			$filelampiransurat = $h->lampiran;
			$filelampiran = $h->lampiran_lain;
		}

		//Jika mengedit semua file
		if (!empty($surat) and !empty($file)) {
			$ambext = explode(".", $surat);
			$ekstensi = end($ambext);
			$nama_baru = 'SuratMasuk' . date('YmdHis'); // @MpikEgov 6 Juni 2023
			$nama_file_surat = $nama_baru . "." . $ekstensi;
			$config['upload_path'] = './assets/lampiransuratmasuk/';
			$config['allowed_types'] = 'pdf|jpg|jpeg|png';
			$config['max_size'] = 40000;
			$config['file_name'] = $nama_file_surat;
			$this->upload->initialize($config);

			if (!$this->upload->do_upload('lampiran')) {
				$this->session->set_flashdata('error', 'Upload Surat Gagal');
				redirect(site_url('suratmasuk/surat/edit' . $id));
			} else {
				$ambext = explode(".", $file);
				$ekstensi = end($ambext);
				$nama_baru = 'SuratMasuk' . date('YmdHis') + 1; // @MpikEgov 6 Juni 2023
				$nama_file = $nama_baru . "." . $ekstensi;
				$config['upload_path'] = './assets/lampiransuratmasuk/';
				$config['allowed_types'] = 'pdf|jpg|jpeg|png';
				$config['max_size'] = 40000;
				$config['file_name'] = $nama_file;
				$this->upload->initialize($config);

				if (!$this->upload->do_upload('lampiran_lain')) {
					$this->session->set_flashdata('error', 'Upload Lampiran Gagal');
					redirect(site_url('suratmasuk/surat/edit' . $id));
				} else {
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

						/* START:Input Retensi Arsip Surat Masuk [@Dam-Egov 11/01/2024] */
						$nomor = $data['nomor'];
						$surat = $this->db->query("SELECT suratmasuk_id, diterima FROM surat_masuk WHERE nomor='$nomor' AND opd_id='$opdid' AND dibuat_id='$jabatan'")->row();

						$dataRetensi	= array(
							'surat_id' 			=> $surat->suratmasuk_id,
							'jenis_surat' 		=> 'Surat Masuk',
							'jra_id'			=> htmlentities($this->input->post('jra_id'))
						);

						$cekRetensi = $this->db->query("SELECT * FROM retensi_arsip WHERE surat_id='$id'")->num_rows();

						if ($cekRetensi > 0) {
							$whereId = array('surat_id' => $id);
							$this->surat_model->update_data('retensi_arsip', $dataRetensi, $whereId);
						} else {
							$this->surat_model->insert_data('retensi_arsip', $dataRetensi);
						}

						/* END:Input Retensi Arsip Surat Masuk [@Dam-Egov 11/01/2024] */

						$this->session->set_flashdata('success', 'Surat Berhasil Diedit');
						redirect(site_url('suratmasuk/surat'));
					} else {
						$this->session->set_flashdata('error', 'Surat Gagal Diedit');
						redirect(site_url('suratmasuk/surat/edit' . $id));
					}
				}
			}
			// Jika upload Lampiran Surat
		} elseif (!empty($surat) and empty($file)) {
			$ambext = explode(".", $surat);
			$ekstensi = end($ambext);
			$nama_baru = 'SuratMasuk' . date('YmdHis'); // @MpikEgov 6 Juni 2023
			$nama_file_surat = $nama_baru . "." . $ekstensi;
			$config['upload_path'] = './assets/lampiransuratmasuk/';
			$config['allowed_types'] = 'pdf|jpg|jpeg|png';
			$config['max_size'] = 40000;
			$config['file_name'] = $nama_file_surat;
			$this->upload->initialize($config);

			if (!$this->upload->do_upload('lampiran')) {
				$this->session->set_flashdata('error', 'Upload Surat Gagal');
				redirect(site_url('suratmasuk/surat/edit/' . $id));
			} else {
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

					/* START:Input Retensi Arsip Surat Masuk [@Dam-Egov 11/01/2024] */
					$nomor = $data['nomor'];
					$surat = $this->db->query("SELECT suratmasuk_id, diterima FROM surat_masuk WHERE nomor='$nomor' AND opd_id='$opdid' AND dibuat_id='$jabatan'")->row();

					$dataRetensi	= array(
						'surat_id' 			=> $surat->suratmasuk_id,
						'jenis_surat' 		=> 'Surat Masuk',
						'jra_id'			=> htmlentities($this->input->post('jra_id'))
					);

					$cekRetensi = $this->db->query("SELECT * FROM retensi_arsip WHERE surat_id='$id'")->num_rows();

					if ($cekRetensi > 0) {
						$whereId = array('surat_id' => $id);
						$this->surat_model->update_data('retensi_arsip', $dataRetensi, $whereId);
					} else {
						$this->surat_model->insert_data('retensi_arsip', $dataRetensi);
					}
					/* END:Input Retensi Arsip Surat Masuk [@Dam-Egov 11/01/2024] */

					$this->session->set_flashdata('success', 'Surat Berhasil Diedit');
					redirect(site_url('suratmasuk/surat'));
				} else {
					$this->session->set_flashdata('error', 'Surat Gagal Diedit');
					redirect(site_url('suratmasuk/surat/edit/' . $id));
				}
			}
		} elseif (empty($surat) and !empty($file)) {
			$ambext = explode(".", $file);
			$ekstensi = end($ambext);
			$nama_baru = 'SuratMasuk' . date('YmdHis') + 1; // @MpikEgov 6 Juni 2023
			$nama_file_surat = $nama_baru . "." . $ekstensi;
			$config['upload_path'] = './assets/lampiransuratmasuk/';
			$config['allowed_types'] = 'pdf|jpg|jpeg|png';
			$config['max_size'] = 40000;
			$config['file_name'] = $nama_file_surat;
			$this->upload->initialize($config);

			if (!$this->upload->do_upload('lampiran_lain')) {
				$this->session->set_flashdata('error', 'Upload Surat Gagal');
				redirect(site_url('suratmasuk/surat/edit/' . $id));
			} else {
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

					/* START:Input Retensi Arsip Surat Masuk [@Dam-Egov 11/01/2024] */
					$nomor = $data['nomor'];
					$surat_id = $this->db->query("SELECT suratmasuk_id, diterima FROM surat_masuk WHERE nomor='$nomor' AND opd_id='$opdid' AND dibuat_id='$jabatan'")->row();

					$dataRetensi	= array(
						'surat_id' 			=> $surat_id->suratmasuk_id,
						'jenis_surat' 		=> 'Surat Masuk',
						'jra_id'			=> htmlentities($this->input->post('jra_id'))
					);

					$cekRetensi = $this->db->query("SELECT * FROM retensi_arsip WHERE surat_id='$id'")->num_rows();

					if ($cekRetensi > 0) {
						$whereId = array('surat_id' => $id);
						$this->surat_model->update_data('retensi_arsip', $dataRetensi, $whereId);
					} else {
						$this->surat_model->insert_data('retensi_arsip', $dataRetensi);
					}
					/* END:Input Retensi Arsip Surat Masuk [@Dam-Egov 11/01/2024] */

					$this->session->set_flashdata('success', 'Surat Berhasil Diedit');
					redirect(site_url('suratmasuk/surat'));
				} else {
					$this->session->set_flashdata('error', 'Surat Gagal Diedit');
					redirect(site_url('suratmasuk/surat/edit/' . $id));
				}
			}
		} elseif (!$this->upload->do_upload('lampiran') and !$this->upload->do_upload('lampiran_lain')) {
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

				/* START:Input Retensi Arsip Surat Masuk [@Dam-Egov 11/01/2024] */
				$nomor = $data['nomor'];
				$surat_id = $this->db->query("SELECT suratmasuk_id, diterima FROM surat_masuk WHERE nomor='$nomor' AND opd_id='$opdid' AND dibuat_id='$jabatan'")->row();

				$dataRetensi	= array(
					'surat_id' 			=> $surat_id->suratmasuk_id,
					'jenis_surat' 		=> 'Surat Masuk',
					'jra_id'			=> htmlentities($this->input->post('jra_id'))
				);

				$cekRetensi = $this->db->query("SELECT * FROM retensi_arsip WHERE surat_id='$id'")->num_rows();

				if ($cekRetensi > 0) {
					$whereId = array('surat_id' => $id);
					$this->surat_model->update_data('retensi_arsip', $dataRetensi, $whereId);
				} else {
					$this->surat_model->insert_data('retensi_arsip', $dataRetensi);
				}
				/* END:Input Retensi Arsip Surat Masuk [@Dam-Egov 11/01/2024] */

				$this->session->set_flashdata('success', 'Surat Berhasil Diedit');
				redirect(site_url('suratmasuk/surat'));
			} else {
				$this->session->set_flashdata('error', 'Surat Gagal Diedit');
				redirect(site_url('suratmasuk/surat/edit' . $id));
			}
		}
	}

	public function disposisi()
	{
		// Update @Mpik Egov 31 Mei 2023
		$namaadmintu = $this->session->userdata('nama');
		$admintuId = $this->session->userdata('jabatan_id');
		$qadmintuId = $this->db->query("SELECT * FROM jabatan WHERE jabatan_id='$admintuId'")->row_array();
		$atasanId = $qadmintuId['atasan_id'];
		$qatasanId = $this->db->query("SELECT * FROM aparatur WHERE jabatan_id='$atasanId'")->row_array();
		// END @Mpik Egov

		if (isset($_POST['disposisi'])) {
			$suratmasukid = $this->input->post('suratmasuk_id');
			$aparatur_id = implode(',', $this->input->post('aparatur_id'));
			$explodeAparatur = explode(',', $aparatur_id);
			$dataAparatur = array();
			$index = 0;
			foreach ($explodeAparatur as $key => $h) {
				$index++;
			}
			$cekdisposisi = $this->db->query("select * from disposisi_suratmasuk a where a.suratmasuk_id='$suratmasukid' and a.aparatur_id='$h' and a.status !='Dikembalikan'")->num_rows();
			if ($cekdisposisi == 1) {
				$this->session->set_flashdata('error', 'Tujuan Aparatur Yang Dipilih Sudah Dimasukkan');
				redirect(site_url('suratmasuk/surat/detaildata/' . $suratmasukid));
			} else {
				if (empty($this->input->post('harap'))) {
					$harap = '';
				} else {
					$harap = implode(',', $this->input->post('harap'));
				}
				$aparatur_id = implode(',', $this->input->post('aparatur_id'));
				$explodeAparatur = explode(',', $aparatur_id);
				$datenow = date("Y-m-d");
				$dataAparatur = array();
				$index = 0;
				foreach ($explodeAparatur as $key => $h) {
					$namaaparatur = $this->db->query("SELECT nama FROM aparatur WHERE jabatan_id='$h'")->row_array(); // Update @Mpik Egov 30 Mei 2023

					array_push($dataAparatur, array(
						'suratmasuk_id' => $this->input->post('suratmasuk_id'),
						'aparatur_id' => $h,
						'users_id' => $this->input->post('users_id'),
						'harap' => 	$harap,
						'keterangan' => '',
						'nama_pengirim' => $namaadmintu, // @Mpik Egov 31 Mei 2023
						'nama_penerima' => $namaaparatur['nama'], // @Mpik Egov 31 Mei 2023
						'waktudisposisi' => date("h:i:sa"),
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
					redirect(site_url('suratmasuk/surat/detaildata/' . $suratmasukid));
				} else {
					$this->session->set_flashdata('error', 'Surat Gagal Didisposisikan');
					redirect(site_url('suratmasuk/surat/detaildata/' . $suratmasukid));
				}
			}
		} else {

			$jabatan = $this->db->get_where('jabatan', array('jabatan_id' => $this->session->userdata('jabatan_id')))->row_array();
			if ($jabatan['atasan_id'] == 0) {
				$getBawahan = $this->db->get_where('jabatan', array('atasan_id' => $this->session->userdata('jabatan_id')))->row_array();
				$getAtasan = $this->db->get_where('aparatur', array('jabatan_id' => $getBawahan['jabatan_id']))->row_array();
			} else {
				$getAtasan = $this->db->get_where('aparatur', array('jabatan_id' => $jabatan['atasan_id']))->row_array();
			}
			$suratmasukid = $this->uri->segment(4);
			$aparaturid = $getAtasan['jabatan_id'];
			$cekdisposisi = $this->db->query("select * from disposisi_suratmasuk a where a.suratmasuk_id='$suratmasukid' and a.aparatur_id='$aparaturid' and a.status !='Selesai Disposisi'")->num_rows();
			if ($cekdisposisi == 0) {
				$datenow = date("Y-m-d");
				$data = array(
					'suratmasuk_id' => $this->uri->segment(4),
					'aparatur_id' => $getAtasan['jabatan_id'],
					'users_id' => $this->session->userdata('jabatan_id'),
					'harap' => 	'',
					'keterangan' => '',
					'nama_pengirim' => $namaadmintu, // @Mpik Egov 31 Mei 2023
					'nama_penerima' => $qatasanId['nama'], // @Mpik Egov 31 Mei 2023
					'waktudisposisi' => date("h:i:sa"),
					'tanggal' => htmlentities($datenow),
					'waktudisposisi' => date("h:i:sa"),
				);
				$this->surat_model->update_data('disposisi_suratmasuk', array('Status' => 'Riwayat'), array('suratmasuk_id' => $this->uri->segment(4)));
				$dispos = $this->surat_model->insert_data('disposisi_suratmasuk', $data);
				if ($dispos) {
					$this->session->set_flashdata('success', 'Surat Berhasil Didisposisikan');
					redirect(site_url('suratmasuk/surat/detaildata/' . $suratmasukid));
				} else {
					$this->session->set_flashdata('error', 'Surat Gagal Didisposisikan');
					redirect(site_url('suratmasuk/surat/detaildata/' . $suratmasukid));
				}
			} else {
				$this->session->set_flashdata('error', 'Surat Sudah Didisposisikan');
				redirect(site_url('suratmasuk/surat/detaildata/' . $suratmasukid));
			}
		}
	}

	public function detaildata()
	{
		$data['content'] = 'detaildata';
		$this->load->view('template', $data);
	}

	public function delete()
	{
		$where = array('suratmasuk_id' => $this->uri->segment(4));
		// @MpikEgov 6 Juni 2023 
		$suratid = $this->uri->segment(4);
		$query = $this->db->query("SELECT lampiran, lampiran_lain FROM surat_masuk WHERE suratmasuk_id='$suratid'")->row_array();
		// hapus file
		unlink(FCPATH . "assets/lampiransuratmasuk/" . $query['lampiran']);
		// END @MpikEgov 6 Juni 2023
		$delete = $this->surat_model->delete_data('surat_masuk', $where);
		if ($delete) {

			$this->surat_model->delete_data('disposisi_suratmasuk', $where);
			$this->surat_model->delete_data('retensi_arsip', array('surat_id' => $this->uri->segment(4)));
			$this->session->set_flashdata('success', 'Surat Berhasil Dihapus');
			redirect(site_url('suratmasuk/surat'));
		} else {
			$this->session->set_flashdata('error', 'Surat Gagal Dihapus');
			redirect(site_url('suratmasuk/surat'));
		}
	}
}
