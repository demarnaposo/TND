	<?php
	defined('BASEPATH') or exit('No direct script access allowed');

	class Draft extends CI_Controller
	{

		function __construct()
		{
			parent::__construct();
			if (empty($this->session->userdata('login'))) {
				$this->session->set_flashdata('access', 'Anda harus login terlebih dahulu!');
				redirect(site_url());
			}
			$this->load->model('draft_model');

			$this->load->library('esign_api_gateway_dev');
		}

		public function index()
		{
			$data['content'] = 'draft_index';

			$tahun = $this->session->userdata('tahun');
			$jabatan_id = $this->session->userdata('jabatan_id');

			$level_id = $this->session->userdata('level');

			if($level_id == '4' || $level_id == '18') {

			$data['draft'] = $this->draft_model->get_verifikasi_tu($tahun, $jabatan_id)->result();

			}
			else {

			$data['draft'] = $this->draft_model->get_verifikasi($tahun, $jabatan_id)->result();
			}

			$this->load->view('template', $data);
		}

 		public function penomoran()
		{
			$data['content'] = 'draft_penomoran';

			$tahun = $this->session->userdata('tahun');
			$jabatan_id = $this->session->userdata('jabatan_id');

			$data['draft'] = $this->draft_model->get_draftpenomoran($tahun, $jabatan_id)->result();

			$this->load->view('template', $data);
		}

		public function riwayatterusan()
		{
			$data['content'] = 'riwayatterusan_index';

			$tahun = $this->session->userdata('tahun');

			$data['riwayat'] = $this->draft_model->get_riwayatterusan($tahun)->result();

			$this->load->view('template', $data);
		}

		public function ttd_selesai()
		{
			$data['content'] = 'selesai_index';

			$tahun = $this->session->userdata('tahun');
			$jabatan_id = $this->session->userdata('jabatan_id');
			$opd_id = $this->session->userdata('opd_id');

			$data['selesai'] = $this->db->query("SELECT * FROM draft INNER join penandatangan on draft.penandatangan_id=penandatangan.penandatangan_id INNER join jabatan on jabatan.jabatan_id=draft.dibuat_id where status =  'Sudah Ditandatangani' and opd_id ='$opd_id' ORDER BY draft.id DESC")->result();

			$this->load->view('template', $data);
		}

		public function verify()
		{
			if (isset($_POST['verifikasi'])) {
				$getJabatan = $this->db->get_where('jabatan', array('jabatan_id' => $this->input->post('jabatan_id')))->row_array();
				$dariAparatur = $this->draft_model->dari_untuk_aparatur($this->input->post('jabatan_id'))->row_array();
				$untukAparatur = $this->draft_model->dari_untuk_aparatur($getJabatan['atasan_id'])->row_array();
				$datenow = date("Y-m-d");
				$status = $untukAparatur['statusaparatur'];
				$data = array(
					'dari' => $dariAparatur['nama'] . ' - ' . $dariAparatur['nama_jabatan'],
					'untuk' => $untukAparatur['nama'] . ' - ' . $untukAparatur['nama_jabatan'],
					'surat_id' => htmlentities($this->input->post('surat_id')),
					'keterangan' => $this->input->post('keterangan'),
					'tanggal' => htmlentities($datenow),
				);
				if ($status != "Aktif") {
					// $this->session->set_flashdata('error', 'Surat Tidak Bisa Diteruskan, Atasan Sudah ' . $status);
					$this->session->set_flashdata('error', 'Surat Tidak Bisa Diteruskan, Atasan Sudah Tidak Aktif'); // Update @Mpik Egov 23/06/2022
					redirect(site_url('suratkeluar/draft'));
				} elseif ($status == "Aktif") {
					$verify = $this->draft_model->insert_data('verifikasi', $data);
				}
				if ($verify) {
					$verifikasi = array('verifikasi_id' => $getJabatan['atasan_id']);
					$where = array('surat_id' => htmlentities($this->input->post('surat_id')));
					$this->draft_model->update_data('draft', $verifikasi, $where);

					$this->session->set_flashdata('success', 'Surat Berhasil Diteruskan');

					if ($this->input->post('uri_segment') == 'draft') {
						redirect(site_url('suratkeluar/draft'));
					} else {
						if (substr($this->input->post('surat_id'), 0, 2) == 'SB') {
							redirect(site_url('suratkeluar/biasa'));
						} elseif (substr($this->input->post('surat_id'), 0, 2) == 'SE') {
							redirect(site_url('suratkeluar/edaran'));
						} elseif (substr($this->input->post('surat_id'), 0, 2) == 'SU') {
							redirect(site_url('suratkeluar/undangan'));
						} elseif (substr($this->input->post('surat_id'), 0, 5) == 'PNGMN') {
							redirect(site_url('suratkeluar/pengumuman'));
						} elseif (substr($this->input->post('surat_id'), 0, 3) == 'LAP') {
							redirect(site_url('suratkeluar/laporan'));
						} elseif (substr($this->input->post('surat_id'), 0, 3) == 'REK') {
							redirect(site_url('suratkeluar/rekomendasi'));
						} elseif (substr($this->input->post('surat_id'), 0, 3) == 'INT') {
							redirect(site_url('suratkeluar/instruksi'));
						} elseif (substr($this->input->post('surat_id'), 0, 3) == 'PNG') {
							redirect(site_url('suratkeluar/pengantar'));
						} elseif (substr($this->input->post('surat_id'), 0, 5) == 'NODIN') {
							redirect(site_url('suratkeluar/notadinas'));
						} elseif (substr($this->input->post('surat_id'), 0, 2) == 'SK') {
							redirect(site_url('suratkeluar/keterangan'));
						} elseif (substr($this->input->post('surat_id'), 0, 3) == 'SPT') {
							redirect(site_url('suratkeluar/perintahtugas'));
						} elseif (substr($this->input->post('surat_id'), 0, 2) == 'SP') {
							redirect(site_url('suratkeluar/perintah'));
						} elseif (substr($this->input->post('surat_id'), 0, 3) == 'IZN') {
							redirect(site_url('suratkeluar/izin'));
						} elseif (substr($this->input->post('surat_id'), 0, 3) == 'PJL') {
							redirect(site_url('suratkeluar/perjalanan'));
						} elseif (substr($this->input->post('surat_id'), 0, 3) == 'KSA') {
							redirect(site_url('suratkeluar/kuasa'));
						} elseif (substr($this->input->post('surat_id'), 0, 3) == 'MKT') {
							redirect(site_url('suratkeluar/melaksanakan_tugas'));
						} elseif (substr($this->input->post('surat_id'), 0, 3) == 'PGL') {
							redirect(site_url('suratkeluar/panggilan'));
						} elseif (substr($this->input->post('surat_id'), 0, 3) == 'NTL') {
							redirect(site_url('suratkeluar/notulen'));
						} elseif (substr($this->input->post('surat_id'), 0, 3) == 'MMO') {
							redirect(site_url('suratkeluar/memo'));
						} elseif (substr($this->input->post('surat_id'), 0, 3) == 'LMP') {
							redirect(site_url('suratkeluar/lampiran'));
						} elseif (substr($this->input->post('surat_id'), 0, 2) == 'SL') {
							redirect(site_url('suratkeluar/suratlainnya'));
						}
					}
				} else {
					$this->session->set_flashdata('error', 'Surat Gagal Diteruskan');
					redirect(site_url('suratkeluar/draft'));
				}
			} elseif (isset($_POST['kembalikan'])) {
				$kembalikanDari = $this->db->limit(1)->order_by('verifikasi_id', 'ASC')->get_where('verifikasi', array('surat_id' => $this->input->post('surat_id')))->row_array();
				$kembalikanUntuk = $this->db->limit(1)->order_by('verifikasi_id', 'DESC')->get_where('verifikasi', array('surat_id' => $this->input->post('surat_id')))->row_array();
				$verifikasi = $this->db->get_where('jabatan', array('atasan_id' => $this->session->userdata('jabatan_id')))->row_array();
				$data = array(
					'untuk' => $kembalikanDari['dari'],
					'dari' => $kembalikanUntuk['untuk'],
					'surat_id' => htmlentities($this->input->post('surat_id')),
					'keterangan' => $this->input->post('keterangan'),
				);
				$verify = $this->draft_model->insert_data('verifikasi', $data);
				if ($verify) {
					$kembalikan = $this->db->get_where('draft', array('surat_id' => $this->input->post('surat_id')))->row_array();
					$verifikasi = array('verifikasi_id' => $kembalikan['dibuat_id']);
					$where = array('surat_id' => htmlentities($this->input->post('surat_id')));
					$this->draft_model->update_data('draft', $verifikasi, $where);

					$this->session->set_flashdata('success', 'Surat Berhasil Dikembalikan');
					redirect(site_url('suratkeluar/draft'));
				} else {
					$this->session->set_flashdata('error', 'Surat Gagal Dikembalikan');
					redirect(site_url('suratkeluar/draft'));
				}
			} elseif (isset($_POST['selesai'])) {
				$opd_id = $this->session->userdata('opd_id');
				$jabatan_id = $this->session->userdata('jabatan_id');

				$getTU = getTU($opd_id);
				$verifikasi = array('verifikasi_id' => $getTU['jabatan_id']);
				$where = array('surat_id' => htmlentities($this->input->post('surat_id')));
				$selesai = $this->draft_model->update_data('draft', $verifikasi, $where);

				// $surla = 	$this->input->post('surat_id');
				//$key = $this->db->query("SELECT lampiran from surat_lainnya where id = '$surla'")->row_array();
				//   $lampiranName = $key['lampiran'];
				//if($key['lampiran'] != NULL){


				//    $this->load->library('pdf2'); 
				//         $this->pdf2->editpdf($this->input->post('surat_id'), $lampiranName);
				//	redirect(site_url('suratkeluar/suratlainnya'));
				//}

				$suratid = htmlentities($this->input->post('surat_id'));
				$data = $this->db->query("SELECT * from surat_lainnya where id = '$suratid'")->row_array();
							
				if($data['lampiran'] != NULL){ //
					$sourcefile = $_SERVER['DOCUMENT_ROOT'].'/assets/lampiransurat/suratlainnya/'.$data['lampiran'];

					$this->load->library('pdf');

					$this->pdf->addqrcode($sourcefile, $data['lampiran'], $data['id']);

				}

				if ($selesai) {

					$dari = $this->draft_model->dari_untuk_aparatur($jabatan_id)->row_array();
					$data = array(
						'untuk' => $getTU['nama'] . ' - ' . $getTU['nama_jabatan'],
						'dari' => $dari['nama'] . ' - ' . $dari['nama_jabatan'],
						'surat_id' => htmlentities($this->input->post('surat_id')),
						'keterangan' => 'Surat telah diselesaikan',
					);
					$this->draft_model->insert_data('verifikasi', $data);

					// iki 

					$data1 = array(
						'ket_selesai' => $dari['nama'] . ' - ' . $dari['nama_jabatan'],
						'surat_id' => htmlentities($this->input->post('surat_id')),
					);
					$this->draft_model->insert_data('selesai', $data1);


					$this->session->set_flashdata('success', 'Surat Berhasil Diselesaikan');
					$referred_from = $this->session->userdata('referred_from');
					redirect($_SERVER['HTTP_REFERER']);
					// redirect($referred_from, 'refresh');
					// redirect((site_url('suratkeluar/draft')));
				} else {
					$this->session->set_flashdata('error', 'Surat Gagal Diselesaikan');
					$referred_from = $this->session->userdata('referred_from');
					redirect($_SERVER['HTTP_REFERER']);
					// redirect($referred_from, 'refresh');
					// redirect(site_url('suratkeluar/draft'));
				}
			}
		}

		public function disposisi()
		{
			$surat_id = $this->input->post('surat_id');
			$data['jra'] = $this->db->from('jra')->order_by('series', 'asc')->get()->result();
			if (isset($_POST['simpan'])) {

				$where = array('id' => $surat_id);

				/** Start:Penandatangan @DamEgov 06/10/2023 */
				//id penandatangan
				$getID = $this->db->get('penandatangan')->result();
				foreach ($getID as $key => $h) {
					$id = $h->penandatangan_id;
				}
				if (empty($id)) {
					$penandatangan_id = '1';
				} else {
					$urut = $id + 1;
					$penandatangan_id = $urut;
				}

				//get nama dan jabatan penandatangan
				$getnamajabatan = $this->draft_model->penandatangan('', $this->input->post('jabatan_id'))->row_array();

				$dataTtd = array(
					'penandatangan_id' => $penandatangan_id,
					'surat_id' => $surat_id,
					'jabatan_id' => $this->input->post('jabatan_id'),
					'nama' => $getnamajabatan['nama'],
					'jabatan' => $getnamajabatan['nama_jabatan'],

				);
				// echo print_r($dataTtd);
				$this->draft_model->insert_data('penandatangan', $dataTtd);

				$getpenandatangan = $this->db->query("SELECT * FROM penandatangan where surat_id='$surat_id'")->row_array();

				if(!empty($getpenandatangan['penandatangan_id'])) {
					$penandatangan = array('penandatangan_id' => $getpenandatangan['penandatangan_id']);
					$whereIdsurat  = array('surat_id' => $surat_id);

					$this->draft_model->update_data('draft', $penandatangan, $whereIdsurat);
				}
				else {
					//id penandatangan
					$getID = $this->db->get('penandatangan')->result();
					foreach ($getID as $key => $h) {
						$id = $h->penandatangan_id;
					}
					if (empty($id)) {
						$penandatangan_id = '1';
					} else {
						$urut = $id + 1;
						$penandatangan_id = $urut;
					}

					//get nama dan jabatan penandatangan
					$getnamajabatan = $this->draft_model->penandatangan('', $this->input->post('jabatan_id'))->row_array();

					$dataTtd = array(
						'penandatangan_id' 	=> $penandatangan_id,
						'surat_id' 			=> $surat_id,
						'jabatan_id' 		=> $this->input->post('jabatan_id'),
						'nama' 				=> $getnamajabatan['nama'],
						'jabatan' 			=> $getnamajabatan['nama_jabatan'],

					);
					$this->draft_model->insert_data('penandatangan', $dataTtd);

					$getpenandatangan = $this->db->query("SELECT * FROM penandatangan where surat_id='$surat_id'")->row_array();

					$penandatangan = array('penandatangan_id' => $getpenandatangan['penandatangan_id']);
					$whereIdsurat  = array('surat_id' => $surat_id);

					$this->draft_model->update_data('draft', $penandatangan, $whereIdsurat);
				}
				/** End:Penandatangan @DamEgov 06/10/2023 */				

				/** Start:Penomoran  */
				if (substr($surat_id, 0, 2) == 'SB') {

					// Untuk menambah kode surat 

					$kodesurat = array('kodesurat_id' => $this->input->post('kodesurat_id'));
					$this->draft_model->update_data('surat_biasa', $kodesurat, $where);

					// Untuk menambah nomor urut surat dan kode bidang

					if (!empty($this->input->post('no_urut'))) {
						$kodeSurat = $this->draft_model->nomor_surat('surat_biasa', $surat_id)->row_array();
						$whereNomor = $kodeSurat['kode'] . '/' . htmlentities($this->input->post('no_urut')) . '-' . htmlentities($this->input->post('kode_bidang'));
						$nomor = array('nomor' => $whereNomor);
					} else {
						$nomor = array('nomor' => $this->input->post('nomor'));
					}
					$update = $this->draft_model->update_data('surat_biasa', $nomor, $where);
				} elseif (substr($surat_id, 0, 2) == 'SE') {

					// Untuk menambah kode surat 

					$kodesurat = array('kodesurat_id' => $this->input->post('kodesurat_id'));
					$this->draft_model->update_data('surat_edaran', $kodesurat, $where);

					// Untuk menambah nomor urut surat dan kode bidang

					if (!empty($this->input->post('no_urut'))) {
						$kodeSurat = $this->draft_model->nomor_surat('surat_edaran', $surat_id)->row_array();
						$whereNomor = $kodeSurat['kode'] . '/' . htmlentities($this->input->post('no_urut')) . '-' . htmlentities($this->input->post('kode_bidang'));
						$nomor = array('nomor' => $whereNomor);
					} else {
						$nomor = array('nomor' => $this->input->post('nomor'));
					}
					$update = $this->draft_model->update_data('surat_edaran', $nomor, $where);
				} elseif (substr($this->input->post('surat_id'), 0, 2) == 'SU') {

					// Untuk menambah kode surat 

					$kodesurat = array('kodesurat_id' => $this->input->post('kodesurat_id'));
					$this->draft_model->update_data('surat_undangan', $kodesurat, $where);

					// Untuk menambah nomor urut surat dan kode bidang

					if (!empty($this->input->post('no_urut'))) {
						$kodeSurat = $this->draft_model->nomor_surat('surat_undangan', $surat_id)->row_array();
						$whereNomor = $kodeSurat['kode'] . '/' . htmlentities($this->input->post('no_urut')) . '-' . htmlentities($this->input->post('kode_bidang'));
						$nomor = array('nomor' => $whereNomor);
					} else {
						$nomor = array('nomor' => $this->input->post('nomor'));
					}
					$update = $this->draft_model->update_data('surat_undangan', $nomor, $where);
				} elseif (substr($this->input->post('surat_id'), 0, 3) == 'LAP') {

					// Untuk menambah kode surat 

					$kodesurat = array('kodesurat_id' => $this->input->post('kodesurat_id'));
					$this->draft_model->update_data('surat_laporan', $kodesurat, $where);

					// Untuk menambah nomor urut surat dan kode bidang

					if (!empty($this->input->post('no_urut'))) {
						$kodeSurat = $this->draft_model->nomor_surat('surat_laporan', $surat_id)->row_array();
						$whereNomor = $kodeSurat['kode'] . '/' . htmlentities($this->input->post('no_urut')) . '-' . htmlentities($this->input->post('kode_bidang'));
						$nomor = array('nomor' => $whereNomor);
					} else {
						$nomor = array('nomor' => $this->input->post('nomor'));
					}
					$update = $this->draft_model->update_data('surat_laporan', $nomor, $where);
				} elseif (substr($this->input->post('surat_id'), 0, 5) == 'PNGMN') {

					// Untuk menambah kode surat 

					$kodesurat = array('kodesurat_id' => $this->input->post('kodesurat_id'));
					$this->draft_model->update_data('surat_pengumuman', $kodesurat, $where);

					// Untuk menambah nomor urut surat dan kode bidang

					if (!empty($this->input->post('no_urut'))) {
						$kodeSurat = $this->draft_model->nomor_surat('surat_pengumuman', $surat_id)->row_array();
						$whereNomor = $kodeSurat['kode'] . '/' . htmlentities($this->input->post('no_urut')) . '-' . htmlentities($this->input->post('kode_bidang'));
						$nomor = array('nomor' => $whereNomor);
					} else {
						$nomor = array('nomor' => $this->input->post('nomor'));
					}
					$update = $this->draft_model->update_data('surat_pengumuman', $nomor, $where);
				} elseif (substr($this->input->post('surat_id'), 0, 3) == 'REK') {

					// Untuk menambah kode surat 

					$kodesurat = array('kodesurat_id' => $this->input->post('kodesurat_id'));
					$this->draft_model->update_data('surat_rekomendasi', $kodesurat, $where);

					// Untuk menambah nomor urut surat dan kode bidang

					if (!empty($this->input->post('no_urut'))) {
						$kodeSurat = $this->draft_model->nomor_surat('surat_rekomendasi', $surat_id)->row_array();
						$whereNomor = $kodeSurat['kode'] . '/' . htmlentities($this->input->post('no_urut')) . '-' . htmlentities($this->input->post('kode_bidang'));
						$nomor = array('nomor' => $whereNomor);
					} else {
						$nomor = array('nomor' => $this->input->post('nomor'));
					}
					$update = $this->draft_model->update_data('surat_rekomendasi', $nomor, $where);
				} elseif (substr($this->input->post('surat_id'), 0, 3) == 'INT') {

					// Untuk menambah kode surat 

					$kodesurat = array('kodesurat_id' => $this->input->post('kodesurat_id'));
					$this->draft_model->update_data('surat_instruksi', $kodesurat, $where);

					// Untuk menambah nomor urut surat dan kode bidang

					if (!empty($this->input->post('no_urut'))) {
						$kodeSurat = $this->draft_model->nomor_surat('surat_instruksi', $surat_id)->row_array();
						$whereNomor = $kodeSurat['kode'] . '/' . htmlentities($this->input->post('no_urut')) . '-' . htmlentities($this->input->post('kode_bidang'));
						$nomor = array('nomor' => $whereNomor);
					} else {
						$nomor = array('nomor' => $this->input->post('nomor'));
					}
					$update = $this->draft_model->update_data('surat_instruksi', $nomor, $where);
				} elseif (substr($this->input->post('surat_id'), 0, 3) == 'PNG') {

					// Untuk menambah kode surat 

					$kodesurat = array('kodesurat_id' => $this->input->post('kodesurat_id'));
					$this->draft_model->update_data('surat_pengantar', $kodesurat, $where);

					// Untuk menambah nomor urut surat dan kode bidang

					if (!empty($this->input->post('no_urut'))) {
						$kodeSurat = $this->draft_model->nomor_surat('surat_pengantar', $surat_id)->row_array();
						$whereNomor = $kodeSurat['kode'] . '/' . htmlentities($this->input->post('no_urut')) . '-' . htmlentities($this->input->post('kode_bidang'));
						$nomor = array('nomor' => $whereNomor);
					} else {
						$nomor = array('nomor' => $this->input->post('nomor'));
					}
					$update = $this->draft_model->update_data('surat_pengantar', $nomor, $where);
				} elseif (substr($this->input->post('surat_id'), 0, 5) == 'NODIN') {

					// Untuk menambah kode surat 

					$kodesurat = array('kodesurat_id' => $this->input->post('kodesurat_id'));
					$this->draft_model->update_data('surat_notadinas', $kodesurat, $where);

					// Untuk menambah nomor urut surat dan kode bidang

					if (!empty($this->input->post('no_urut'))) {
						$kodeSurat = $this->draft_model->nomor_surat('surat_notadinas', $surat_id)->row_array();
						$whereNomor = $kodeSurat['kode'] . '/' . htmlentities($this->input->post('no_urut')) . '-' . htmlentities($this->input->post('kode_bidang'));
						$nomor = array('nomor' => $whereNomor);
					} else {
						$nomor = array('nomor' => $this->input->post('nomor'));
					}
					$update = $this->draft_model->update_data('surat_notadinas', $nomor, $where);
				} elseif (substr($this->input->post('surat_id'), 0, 2) == 'SK') {

					// Untuk menambah kode surat 

					$kodesurat = array('kodesurat_id' => $this->input->post('kodesurat_id'));
					$this->draft_model->update_data('surat_keterangan', $kodesurat, $where);

					// Untuk menambah nomor urut surat dan kode bidang

					if (!empty($this->input->post('no_urut'))) {
						$kodeSurat = $this->draft_model->nomor_surat('surat_keterangan', $surat_id)->row_array();
						$whereNomor = $kodeSurat['kode'] . '/' . htmlentities($this->input->post('no_urut')) . '-' . htmlentities($this->input->post('kode_bidang'));
						$nomor = array('nomor' => $whereNomor);
					} else {
						$nomor = array('nomor' => $this->input->post('nomor'));
					}
					$update = $this->draft_model->update_data('surat_keterangan', $nomor, $where);
				} elseif (substr($this->input->post('surat_id'), 0, 3) == 'SPT') {

					// Untuk menambah kode surat 

					$kodesurat = array('kodesurat_id' => $this->input->post('kodesurat_id'));
					$this->draft_model->update_data('surat_perintahtugas', $kodesurat, $where);

					// Untuk menambah nomor urut surat dan kode bidang

					if (!empty($this->input->post('no_urut'))) {
						$kodeSurat = $this->draft_model->nomor_surat('surat_perintahtugas', $surat_id)->row_array();
						$whereNomor = $kodeSurat['kode'] . '/Sprint ' . htmlentities($this->input->post('no_urut')) . '-' . htmlentities($this->input->post('kode_bidang'));
						$nomor = array('nomor' => $whereNomor);
					} else {
						$nomor = array('nomor' => $this->input->post('nomor'));
					}
					$update = $this->draft_model->update_data('surat_perintahtugas', $nomor, $where);
				} elseif (substr($this->input->post('surat_id'), 0, 2) == 'SP') {

					// Untuk menambah kode surat 

					$kodesurat = array('kodesurat_id' => $this->input->post('kodesurat_id'));
					$this->draft_model->update_data('surat_perintah', $kodesurat, $where);

					// Untuk menambah nomor urut surat dan kode bidang

					if (!empty($this->input->post('no_urut'))) {
						$kodeSurat = $this->draft_model->nomor_surat('surat_perintah', $surat_id)->row_array();
						$whereNomor = $kodeSurat['kode'] . '/Sprint ' . htmlentities($this->input->post('no_urut')) . '-' . htmlentities($this->input->post('kode_bidang'));
						$nomor = array('nomor' => $whereNomor);
					} else {
						$nomor = array('nomor' => $this->input->post('nomor'));
					}
					$update = $this->draft_model->update_data('surat_perintah', $nomor, $where);
				} elseif (substr($this->input->post('surat_id'), 0, 3) == 'IZN') {

					// Untuk menambah kode surat 

					$kodesurat = array('kodesurat_id' => $this->input->post('kodesurat_id'));
					$this->draft_model->update_data('surat_izin', $kodesurat, $where);

					// Untuk menambah nomor urut surat dan kode bidang

					if (!empty($this->input->post('no_urut'))) {
						$kodeSurat = $this->draft_model->nomor_surat('surat_izin', $surat_id)->row_array();
						$whereNomor = $kodeSurat['kode'] . '/' . htmlentities($this->input->post('no_urut')) . '-' . htmlentities($this->input->post('kode_bidang'));
						$nomor = array('nomor' => $whereNomor);
					} else {
						$nomor = array('nomor' => $this->input->post('nomor'));
					}
					$update = $this->draft_model->update_data('surat_izin', $nomor, $where);
				} elseif (substr($this->input->post('surat_id'), 0, 3) == 'PJL') {

					// Untuk menambah kode surat 

					$kodesurat = array('kodesurat_id' => $this->input->post('kodesurat_id'));
					$this->draft_model->update_data('surat_perjalanan', $kodesurat, $where);

					// Untuk menambah nomor urut surat dan kode bidang

					if (!empty($this->input->post('no_urut'))) {
						$kodeSurat = $this->draft_model->nomor_surat('surat_perjalanan', $surat_id)->row_array();
						$whereNomor = $kodeSurat['kode'] . '/' . htmlentities($this->input->post('no_urut')) . '-' . htmlentities($this->input->post('kode_bidang'));
						$nomor = array('nomor' => $whereNomor);
					} else {
						$nomor = array('nomor' => $this->input->post('nomor'));
					}
					$update = $this->draft_model->update_data('surat_perjalanan', $nomor, $where);
				} elseif (substr($this->input->post('surat_id'), 0, 3) == 'KSA') {

					// Untuk menambah kode surat 

					$kodesurat = array('kodesurat_id' => $this->input->post('kodesurat_id'));
					$this->draft_model->update_data('surat_kuasa', $kodesurat, $where);

					// Untuk menambah nomor urut surat dan kode bidang

					if (!empty($this->input->post('no_urut'))) {
						$kodeSurat = $this->draft_model->nomor_surat('surat_kuasa', $surat_id)->row_array();
						$whereNomor = $kodeSurat['kode'] . '/' . htmlentities($this->input->post('no_urut')) . '-' . htmlentities($this->input->post('kode_bidang'));
						$nomor = array('nomor' => $whereNomor);
					} else {
						$nomor = array('nomor' => $this->input->post('nomor'));
					}
					$update = $this->draft_model->update_data('surat_kuasa', $nomor, $where);
				} elseif (substr($this->input->post('surat_id'), 0, 3) == 'MKT') {

					// Untuk menambah kode surat 

					$kodesurat = array('kodesurat_id' => $this->input->post('kodesurat_id'));
					$this->draft_model->update_data('surat_melaksanakantugas', $kodesurat, $where);

					// Untuk menambah nomor urut surat dan kode bidang

					if (!empty($this->input->post('no_urut'))) {
						$kodeSurat = $this->draft_model->nomor_surat('surat_melaksanakantugas', $surat_id)->row_array();
						$whereNomor = $kodeSurat['kode'] . '/' . htmlentities($this->input->post('no_urut')) . '-' . htmlentities($this->input->post('kode_bidang'));
						$nomor = array('nomor' => $whereNomor);
					} else {
						$nomor = array('nomor' => $this->input->post('nomor'));
					}
					$update = $this->draft_model->update_data('surat_melaksanakantugas', $nomor, $where);
				} elseif (substr($this->input->post('surat_id'), 0, 3) == 'PGL') {

					// Untuk menambah kode surat 

					$kodesurat = array('kodesurat_id' => $this->input->post('kodesurat_id'));
					$this->draft_model->update_data('surat_panggilan', $kodesurat, $where);

					// Untuk menambah nomor urut surat dan kode bidang

					if (!empty($this->input->post('no_urut'))) {
						$kodeSurat = $this->draft_model->nomor_surat('surat_panggilan', $surat_id)->row_array();
						$whereNomor = $kodeSurat['kode'] . '/' . htmlentities($this->input->post('no_urut')) . '-' . htmlentities($this->input->post('kode_bidang'));
						$nomor = array('nomor' => $whereNomor);
					} else {
						$nomor = array('nomor' => $this->input->post('nomor'));
					}
					$update = $this->draft_model->update_data('surat_panggilan', $nomor, $where);
				} elseif (substr($this->input->post('surat_id'), 0, 3) == 'NTL') {

					// Untuk menambah kode surat 

					$kodesurat = array('kodesurat_id' => $this->input->post('kodesurat_id'));
					$this->draft_model->update_data('surat_notulen', $kodesurat, $where);

					// Untuk menambah nomor urut surat dan kode bidang

					if (!empty($this->input->post('no_urut'))) {
						$kodeSurat = $this->draft_model->nomor_surat('surat_notulen', $surat_id)->row_array();
						$whereNomor = $kodeSurat['kode'] . '/' . htmlentities($this->input->post('no_urut')) . '-' . htmlentities($this->input->post('kode_bidang'));
						$nomor = array('nomor' => $whereNomor);
					} else {
						$nomor = array('nomor' => $this->input->post('nomor'));
					}
					$update = $this->draft_model->update_data('surat_notulen', $nomor, $where);
				} elseif (substr($this->input->post('surat_id'), 0, 3) == 'MMO') {

					// Untuk menambah kode surat 

					$kodesurat = array('kodesurat_id' => $this->input->post('kodesurat_id'));
					$this->draft_model->update_data('surat_memo', $kodesurat, $where);

					// Untuk menambah nomor urut surat dan kode bidang

					if (!empty($this->input->post('no_urut'))) {
						$kodeSurat = $this->draft_model->nomor_surat('surat_memo', $surat_id)->row_array();
						$whereNomor = $kodeSurat['kode'] . '/' . htmlentities($this->input->post('no_urut')) . '-' . htmlentities($this->input->post('kode_bidang'));
						$nomor = array('nomor' => $whereNomor);
					} else {
						$nomor = array('nomor' => $this->input->post('nomor'));
					}
					$update = $this->draft_model->update_data('surat_memo', $nomor, $where);
				} elseif (substr($this->input->post('surat_id'), 0, 3) == 'LMP') {

					// Untuk menambah kode surat 

					$kodesurat = array('kodesurat_id' => $this->input->post('kodesurat_id'));
					$this->draft_model->update_data('surat_lampiran', $kodesurat, $where);

					// Untuk menambah nomor urut surat dan kode bidang

					if (!empty($this->input->post('no_urut'))) {
						$kodeSurat = $this->draft_model->nomor_surat('surat_lampiran', $surat_id)->row_array();
						$whereNomor = $kodeSurat['kode'] . '/' . htmlentities($this->input->post('no_urut')) . '-' . htmlentities($this->input->post('kode_bidang'));
						$nomor = array('nomor' => $whereNomor);
					} else {
						$nomor = array('nomor' => $this->input->post('nomor'));
					}
					$update = $this->draft_model->update_data('surat_lampiran', $nomor, $where);
				} elseif (substr($this->input->post('surat_id'), 0, 2) == 'SL') {

					// Untuk menambah kode surat 

					$kodesurat = array('kodesurat_id' => $this->input->post('kodesurat_id'));
					$this->draft_model->update_data('surat_lainnya', $kodesurat, $where);

					// Untuk menambah nomor urut surat dan kode bidang

					if (!empty($this->input->post('no_urut'))) {
						$kodeSurat = $this->draft_model->nomor_surat('surat_lainnya', $surat_id)->row_array();
						$whereNomor = $kodeSurat['kode'] . '/' . htmlentities($this->input->post('no_urut')) . '-' . htmlentities($this->input->post('kode_bidang'));
						$nomor = array('nomor' => $whereNomor);
					} else {
						$nomor = array('nomor' => $this->input->post('nomor'));
					}
					$update = $this->draft_model->update_data('surat_lainnya', $nomor, $where);
				}
				/** End:Penomoran */

				if ($update) {

					log_message('debug', "SURAT ID :" . substr($this->input->post('surat_id'), 0, 3));

					if (substr($this->input->post('surat_id'), 0, 3) == 'LMP') {
						log_message('debug', "IN DOCPROCESSOR");
						$this->load->library('docprocessor');

						$details = $this->draft_model->getlampirandetails($this->input->post('surat_id'))->row_array();

						$this->docprocessor->fillTemplate($details);
					}

					/* START:Input Retensi Arsip Surat Keluar [@Dam-Egov 12/01/2024] */
					$surat = $this->db->query("SELECT surat_id, tanggal FROM draft WHERE surat_id='$surat_id'")->row();

					$retensiAktif	= $this->input->post('retensi_aktif');
					$retensiInaktif	= $this->input->post('retensi_inaktif');

					$addAktif		= ' + ' . $retensiAktif . ' years';
					$addInaktif		= ' + ' . $retensiInaktif . ' years';
					$tahunAktif		= date('Y-m-d', strtotime($surat->tanggal . $addAktif));
					$tahunInaktif	= date('Y-m-d', strtotime($tahunAktif . $addInaktif));

					
					$dataRetensi	= array(
						'surat_id' 			=> $surat->surat_id,
						'jenis_surat' 		=> 'Surat Keluar',
						'jra_id'			=> htmlentities($this->input->post('jra_id'))
					);

					$where = array('surat_id' => $surat_id);
					$this->draft_model->insert_data('retensi_arsip', $dataRetensi);
					/* END:Input Retensi Arsip Surat Keluar [@Dam-Egov 12/01/2024] */

					$this->session->set_flashdata('success', 'Penomoran Surat Berhasil');
					redirect(site_url('suratkeluar/draft/disposisi/' . $surat_id));
				} else {
					$this->session->set_flashdata('error', 'Penomoran Surat Gagal Atau Sudah Dinomori');
					redirect(site_url('suratkeluar/draft/disposisi/' . $surat_id));
				}
			} elseif (isset($_POST['ubah'])) {

				$where = array('id' => $surat_id);

				if (substr($surat_id, 0, 2) == 'SB') {

					// Untuk mengubah kode surat 

					$kodesurat = array('kodesurat_id' => $this->input->post('kodesurat_id'));
					$this->draft_model->update_data('surat_biasa', $kodesurat, $where);

					// Untuk mengubah nomor urut surat dan kode bidang

					if (!empty($this->input->post('no_urut'))) {
						$kodeSurat = $this->draft_model->nomor_surat('surat_biasa', $surat_id)->row_array();
						$whereNomor = $kodeSurat['kode'] . '/' . htmlentities($this->input->post('no_urut')) . '-' . htmlentities($this->input->post('kode_bidang'));
						$nomor = array('nomor' => $whereNomor);
					} else {
						$nomor = array('nomor' => $this->input->post('nomor'));
					}
					$update = $this->draft_model->update_data('surat_biasa', $nomor, $where);
				} elseif (substr($surat_id, 0, 2) == 'SE') {


					// Untuk mengubah kode surat 

					$kodesurat = array('kodesurat_id' => $this->input->post('kodesurat_id'));
					$this->draft_model->update_data('surat_edaran', $kodesurat, $where);

					// Untuk mengubah nomor urut surat dan kode bidang

					if (!empty($this->input->post('no_urut'))) {
						$kodeSurat = $this->draft_model->nomor_surat('surat_edaran', $surat_id)->row_array();
						$whereNomor = $kodeSurat['kode'] . '/' . htmlentities($this->input->post('no_urut')) . '-' . htmlentities($this->input->post('kode_bidang'));
						$nomor = array('nomor' => $whereNomor);
					} else {
						$nomor = array('nomor' => $this->input->post('nomor'));
					}
					$update = $this->draft_model->update_data('surat_edaran', $nomor, $where);
				} elseif (substr($this->input->post('surat_id'), 0, 2) == 'SU') {

					// Untuk mengubah kode surat 

					$kodesurat = array('kodesurat_id' => $this->input->post('kodesurat_id'));
					$this->draft_model->update_data('surat_undangan', $kodesurat, $where);

					// Untuk mengubah nomor urut surat dan kode bidang

					if (!empty($this->input->post('no_urut'))) {
						$kodeSurat = $this->draft_model->nomor_surat('surat_undangan', $surat_id)->row_array();
						$whereNomor = $kodeSurat['kode'] . '/' . htmlentities($this->input->post('no_urut')) . '-' . htmlentities($this->input->post('kode_bidang'));
						$nomor = array('nomor' => $whereNomor);
					} else {
						$nomor = array('nomor' => $this->input->post('nomor'));
					}
					$update = $this->draft_model->update_data('surat_undangan', $nomor, $where);
				} elseif (substr($this->input->post('surat_id'), 0, 3) == 'LAP') {

					// Untuk mengubah kode surat 

					$kodesurat = array('kodesurat_id' => $this->input->post('kodesurat_id'));
					$this->draft_model->update_data('surat_laporan', $kodesurat, $where);

					// Untuk mengubah nomor urut surat dan kode bidang

					if (!empty($this->input->post('no_urut'))) {
						$kodeSurat = $this->draft_model->nomor_surat('surat_laporan', $surat_id)->row_array();
						$whereNomor = $kodeSurat['kode'] . '/' . htmlentities($this->input->post('no_urut')) . '-' . htmlentities($this->input->post('kode_bidang'));
						$nomor = array('nomor' => $whereNomor);
					} else {
						$nomor = array('nomor' => $this->input->post('nomor'));
					}
					$update = $this->draft_model->update_data('surat_laporan', $nomor, $where);
				} elseif (substr($this->input->post('surat_id'), 0, 5) == 'PNGMN') {

					// Untuk mengubah kode surat 

					$kodesurat = array('kodesurat_id' => $this->input->post('kodesurat_id'));
					$this->draft_model->update_data('surat_pengumuman', $kodesurat, $where);

					// Untuk mengubah nomor urut surat dan kode bidang

					if (!empty($this->input->post('no_urut'))) {
						$kodeSurat = $this->draft_model->nomor_surat('surat_pengumuman', $surat_id)->row_array();
						$whereNomor = $kodeSurat['kode'] . '/' . htmlentities($this->input->post('no_urut')) . '-' . htmlentities($this->input->post('kode_bidang'));
						$nomor = array('nomor' => $whereNomor);
					} else {
						$nomor = array('nomor' => $this->input->post('nomor'));
					}
					$update = $this->draft_model->update_data('surat_pengumuman', $nomor, $where);
				} elseif (substr($this->input->post('surat_id'), 0, 3) == 'REK') {

					// Untuk mengubah kode surat 

					$kodesurat = array('kodesurat_id' => $this->input->post('kodesurat_id'));
					$this->draft_model->update_data('surat_rekomendasi', $kodesurat, $where);

					// Untuk mengubah nomor urut surat dan kode bidang

					if (!empty($this->input->post('no_urut'))) {
						$kodeSurat = $this->draft_model->nomor_surat('surat_rekomendasi', $surat_id)->row_array();
						$whereNomor = $kodeSurat['kode'] . '/' . htmlentities($this->input->post('no_urut')) . '-' . htmlentities($this->input->post('kode_bidang'));
						$nomor = array('nomor' => $whereNomor);
					} else {
						$nomor = array('nomor' => $this->input->post('nomor'));
					}
					$update = $this->draft_model->update_data('surat_rekomendasi', $nomor, $where);
				} elseif (substr($this->input->post('surat_id'), 0, 3) == 'INT') {

					// Untuk mengubah kode surat 

					$kodesurat = array('kodesurat_id' => $this->input->post('kodesurat_id'));
					$this->draft_model->update_data('surat_instruksi', $kodesurat, $where);

					// Untuk mengubah nomor urut surat dan kode bidang

					if (!empty($this->input->post('no_urut'))) {
						$kodeSurat = $this->draft_model->nomor_surat('surat_instruksi', $surat_id)->row_array();
						$whereNomor = $kodeSurat['kode'] . '/' . htmlentities($this->input->post('no_urut')) . '-' . htmlentities($this->input->post('kode_bidang'));
						$nomor = array('nomor' => $whereNomor);
					} else {
						$nomor = array('nomor' => $this->input->post('nomor'));
					}
					$update = $this->draft_model->update_data('surat_instruksi', $nomor, $where);
				} elseif (substr($this->input->post('surat_id'), 0, 3) == 'PNG') {

					// Untuk mengubah kode surat 

					$kodesurat = array('kodesurat_id' => $this->input->post('kodesurat_id'));
					$this->draft_model->update_data('surat_pengantar', $kodesurat, $where);

					// Untuk mengubah nomor urut surat dan kode bidang

					if (!empty($this->input->post('no_urut'))) {
						$kodeSurat = $this->draft_model->nomor_surat('surat_pengantar', $surat_id)->row_array();
						$whereNomor = $kodeSurat['kode'] . '/' . htmlentities($this->input->post('no_urut')) . '-' . htmlentities($this->input->post('kode_bidang'));
						$nomor = array('nomor' => $whereNomor);
					} else {
						$nomor = array('nomor' => $this->input->post('nomor'));
					}
					$update = $this->draft_model->update_data('surat_pengantar', $nomor, $where);
				} elseif (substr($this->input->post('surat_id'), 0, 5) == 'NODIN') {

					// Untuk mengubah kode surat 

					$kodesurat = array('kodesurat_id' => $this->input->post('kodesurat_id'));
					$this->draft_model->update_data('surat_notadinas', $kodesurat, $where);

					// Untuk mengubah nomor urut surat dan kode bidang

					if (!empty($this->input->post('no_urut'))) {
						$kodeSurat = $this->draft_model->nomor_surat('surat_notadinas', $surat_id)->row_array();
						$whereNomor = $kodeSurat['kode'] . '/' . htmlentities($this->input->post('no_urut')) . '-' . htmlentities($this->input->post('kode_bidang'));
						$nomor = array('nomor' => $whereNomor);
					} else {
						$nomor = array('nomor' => $this->input->post('nomor'));
					}
					$update = $this->draft_model->update_data('surat_notadinas', $nomor, $where);
				} elseif (substr($this->input->post('surat_id'), 0, 2) == 'SK') {

					// Untuk mengubah kode surat 

					$kodesurat = array('kodesurat_id' => $this->input->post('kodesurat_id'));
					$this->draft_model->update_data('surat_keterangan', $kodesurat, $where);

					// Untuk mengubah nomor urut surat dan kode bidang

					if (!empty($this->input->post('no_urut'))) {
						$kodeSurat = $this->draft_model->nomor_surat('surat_keterangan', $surat_id)->row_array();
						$whereNomor = $kodeSurat['kode'] . '/' . htmlentities($this->input->post('no_urut')) . '-' . htmlentities($this->input->post('kode_bidang'));
						$nomor = array('nomor' => $whereNomor);
					} else {
						$nomor = array('nomor' => $this->input->post('nomor'));
					}
					$update = $this->draft_model->update_data('surat_keterangan', $nomor, $where);
				} elseif (substr($this->input->post('surat_id'), 0, 3) == 'SPT') {

					// Untuk mengubah kode surat 

					$kodesurat = array('kodesurat_id' => $this->input->post('kodesurat_id'));
					$this->draft_model->update_data('surat_perintahtugas', $kodesurat, $where);

					// Untuk mengubah nomor urut surat dan kode bidang

					if (!empty($this->input->post('no_urut'))) {
						$kodeSurat = $this->draft_model->nomor_surat('surat_perintahtugas', $surat_id)->row_array();
						$whereNomor = $kodeSurat['kode'] . '/Sprint ' . htmlentities($this->input->post('no_urut')) . '-' . htmlentities($this->input->post('kode_bidang'));
						$nomor = array('nomor' => $whereNomor);
					} else {
						$nomor = array('nomor' => $this->input->post('nomor'));
					}
					$update = $this->draft_model->update_data('surat_perintahtugas', $nomor, $where);
				} elseif (substr($this->input->post('surat_id'), 0, 2) == 'SP') {

					// Untuk mengubah kode surat 

					$kodesurat = array('kodesurat_id' => $this->input->post('kodesurat_id'));
					$this->draft_model->update_data('surat_perintah', $kodesurat, $where);

					// Untuk mengubah nomor urut surat dan kode bidang

					if (!empty($this->input->post('no_urut'))) {
						$kodeSurat = $this->draft_model->nomor_surat('surat_perintah', $surat_id)->row_array();
						$whereNomor = $kodeSurat['kode'] . '/sprint ' . htmlentities($this->input->post('no_urut')) . '-' . htmlentities($this->input->post('kode_bidang'));
						$nomor = array('nomor' => $whereNomor);
					} else {
						$nomor = array('nomor' => $this->input->post('nomor'));
					}
					$update = $this->draft_model->update_data('surat_perintah', $nomor, $where);
				} elseif (substr($this->input->post('surat_id'), 0, 3) == 'IZN') {

					// Untuk mengubah kode surat 

					$kodesurat = array('kodesurat_id' => $this->input->post('kodesurat_id'));
					$this->draft_model->update_data('surat_izin', $kodesurat, $where);

					// Untuk mengubah nomor urut surat dan kode bidang

					if (!empty($this->input->post('no_urut'))) {
						$kodeSurat = $this->draft_model->nomor_surat('surat_izin', $surat_id)->row_array();
						$whereNomor = $kodeSurat['kode'] . '/' . htmlentities($this->input->post('no_urut')) . '-' . htmlentities($this->input->post('kode_bidang'));
						$nomor = array('nomor' => $whereNomor);
					} else {
						$nomor = array('nomor' => $this->input->post('nomor'));
					}
					$update = $this->draft_model->update_data('surat_izin', $nomor, $where);
				} elseif (substr($this->input->post('surat_id'), 0, 3) == 'PJL') {

					// Untuk mengubah kode surat 

					$kodesurat = array('kodesurat_id' => $this->input->post('kodesurat_id'));
					$this->draft_model->update_data('surat_perjalanan', $kodesurat, $where);

					// Untuk mengubah nomor urut surat dan kode bidang

					if (!empty($this->input->post('no_urut'))) {
						$kodeSurat = $this->draft_model->nomor_surat('surat_perjalanan', $surat_id)->row_array();
						$whereNomor = $kodeSurat['kode'] . '/' . htmlentities($this->input->post('no_urut')) . '-' . htmlentities($this->input->post('kode_bidang'));
						$nomor = array('nomor' => $whereNomor);
					} else {
						$nomor = array('nomor' => $this->input->post('nomor'));
					}
					$update = $this->draft_model->update_data('surat_perjalanan', $nomor, $where);
				} elseif (substr($this->input->post('surat_id'), 0, 3) == 'KSA') {

					// Untuk mengubah kode surat 

					$kodesurat = array('kodesurat_id' => $this->input->post('kodesurat_id'));
					$this->draft_model->update_data('surat_kuasa', $kodesurat, $where);

					// Untuk mengubah nomor urut surat dan kode bidang

					if (!empty($this->input->post('no_urut'))) {
						$kodeSurat = $this->draft_model->nomor_surat('surat_kuasa', $surat_id)->row_array();
						$whereNomor = $kodeSurat['kode'] . '/' . htmlentities($this->input->post('no_urut')) . '-' . htmlentities($this->input->post('kode_bidang'));
						$nomor = array('nomor' => $whereNomor);
					} else {
						$nomor = array('nomor' => $this->input->post('nomor'));
					}
					$update = $this->draft_model->update_data('surat_kuasa', $nomor, $where);
				} elseif (substr($this->input->post('surat_id'), 0, 3) == 'MKT') {

					// Untuk mengubah kode surat 

					$kodesurat = array('kodesurat_id' => $this->input->post('kodesurat_id'));
					$this->draft_model->update_data('surat_melaksanakantugas', $kodesurat, $where);

					// Untuk mengubah nomor urut surat dan kode bidang

					if (!empty($this->input->post('no_urut'))) {
						$kodeSurat = $this->draft_model->nomor_surat('surat_melaksanakantugas', $surat_id)->row_array();
						$whereNomor = $kodeSurat['kode'] . '/' . htmlentities($this->input->post('no_urut')) . '-' . htmlentities($this->input->post('kode_bidang'));
						$nomor = array('nomor' => $whereNomor);
					} else {
						$nomor = array('nomor' => $this->input->post('nomor'));
					}
					$update = $this->draft_model->update_data('surat_melaksanakantugas', $nomor, $where);
				} elseif (substr($this->input->post('surat_id'), 0, 3) == 'PGL') {

					// Untuk mengubah kode surat 

					$kodesurat = array('kodesurat_id' => $this->input->post('kodesurat_id'));
					$this->draft_model->update_data('surat_panggilan', $kodesurat, $where);

					// Untuk mengubah nomor urut surat dan kode bidang

					if (!empty($this->input->post('no_urut'))) {
						$kodeSurat = $this->draft_model->nomor_surat('surat_panggilan', $surat_id)->row_array();
						$whereNomor = $kodeSurat['kode'] . '/' . htmlentities($this->input->post('no_urut')) . '-' . htmlentities($this->input->post('kode_bidang'));
						$nomor = array('nomor' => $whereNomor);
					} else {
						$nomor = array('nomor' => $this->input->post('nomor'));
					}
					$update = $this->draft_model->update_data('surat_panggilan', $nomor, $where);
				} elseif (substr($this->input->post('surat_id'), 0, 3) == 'NTL') {

					// Untuk mengubah kode surat 

					$kodesurat = array('kodesurat_id' => $this->input->post('kodesurat_id'));
					$this->draft_model->update_data('surat_notulen', $kodesurat, $where);

					// Untuk mengubah nomor urut surat dan kode bidang

					if (!empty($this->input->post('no_urut'))) {
						$kodeSurat = $this->draft_model->nomor_surat('surat_notulen', $surat_id)->row_array();
						$whereNomor = $kodeSurat['kode'] . '/' . htmlentities($this->input->post('no_urut')) . '-' . htmlentities($this->input->post('kode_bidang'));
						$nomor = array('nomor' => $whereNomor);
					} else {
						$nomor = array('nomor' => $this->input->post('nomor'));
					}
					$update = $this->draft_model->update_data('surat_notulen', $nomor, $where);
				} elseif (substr($this->input->post('surat_id'), 0, 3) == 'MMO') {

					// Untuk mengubah kode surat 

					$kodesurat = array('kodesurat_id' => $this->input->post('kodesurat_id'));
					$this->draft_model->update_data('surat_memo', $kodesurat, $where);

					// Untuk mengubah nomor urut surat dan kode bidang

					if (!empty($this->input->post('no_urut'))) {
						$kodeSurat = $this->draft_model->nomor_surat('surat_memo', $surat_id)->row_array();
						$whereNomor = $kodeSurat['kode'] . '/' . htmlentities($this->input->post('no_urut')) . '-' . htmlentities($this->input->post('kode_bidang'));
						$nomor = array('nomor' => $whereNomor);
					} else {
						$nomor = array('nomor' => $this->input->post('nomor'));
					}
					$update = $this->draft_model->update_data('surat_memo', $nomor, $where);
				} elseif (substr($this->input->post('surat_id'), 0, 3) == 'LMP') {

					// Untuk mengubah kode surat 

					$kodesurat = array('kodesurat_id' => $this->input->post('kodesurat_id'));
					$this->draft_model->update_data('surat_lampiran', $kodesurat, $where);

					// Untuk mengubah nomor urut surat dan kode bidang

					if (!empty($this->input->post('no_urut'))) {
						$kodeSurat = $this->draft_model->nomor_surat('surat_lampiran', $surat_id)->row_array();
						$whereNomor = $kodeSurat['kode'] . '/' . htmlentities($this->input->post('no_urut')) . '-' . htmlentities($this->input->post('kode_bidang'));
						$nomor = array('nomor' => $whereNomor);
					} else {
						$nomor = array('nomor' => $this->input->post('nomor'));
					}
					$update = $this->draft_model->update_data('surat_lampiran', $nomor, $where);
				} elseif (substr($this->input->post('surat_id'), 0, 2) == 'SL') {

					// Untuk mengubah kode surat 

					$kodesurat = array('kodesurat_id' => $this->input->post('kodesurat_id'));
					$this->draft_model->update_data('surat_lainnya', $kodesurat, $where);

					// Untuk mengubah nomor urut surat dan kode bidang

					if (!empty($this->input->post('no_urut'))) {
						$kodeSurat = $this->draft_model->nomor_surat('surat_lainnya', $surat_id)->row_array();
						$whereNomor = $kodeSurat['kode'] . '/' . htmlentities($this->input->post('no_urut')) . '-' . htmlentities($this->input->post('kode_bidang'));
						$nomor = array('nomor' => $whereNomor);
					} else {
						$nomor = array('nomor' => $this->input->post('nomor'));
					}
					$update = $this->draft_model->update_data('surat_lainnya', $nomor, $where);
				}
				if ($update) {
					$penandatangan_id = $this->input->post('penandatangan_id');
					$penandatangan = array('penandatangan_id' => $penandatangan_id);
					//get nama dan jabatan penandatangan
					$getnamajabatan = $this->draft_model->penandatangan($this->session->userdata('opd_id'), $this->input->post('jabatan_id'))->row_array();

					$dataTtd = array(
						'surat_id' => $surat_id,
						'jabatan_id' => $this->input->post('jabatan_id'),
						'nama' => $getnamajabatan['nama'],
						'jabatan' => $getnamajabatan['nama_jabatan'],
					);
					// echo print_r($dataTtd);
					$this->draft_model->update_data('penandatangan', $dataTtd, $penandatangan);

					$where = array('surat_id' => $surat_id);
					$this->draft_model->update_data('draft', $penandatangan, $where);
					/* START:Input Retensi Arsip Surat Keluar [@Dam-Egov 12/01/2024] */
					$surat = $this->db->query("SELECT surat_id, tanggal FROM draft WHERE surat_id='$surat_id'")->row();

					$retensiAktif	= $this->input->post('retensi_aktif');
					$retensiInaktif	= $this->input->post('retensi_inaktif');

					$addAktif		= ' + ' . $retensiAktif . ' years';
					$addInaktif		= ' + ' . $retensiInaktif . ' years';
					$tahunAktif		= date('Y-m-d', strtotime($surat->tanggal . $addAktif));
					$tahunInaktif	= date('Y-m-d', strtotime($tahunAktif . $addInaktif));

					$dataRetensi	= array(
						'surat_id' 			=> $surat->surat_id,
						'jenis_surat' 		=> 'Surat Keluar',
						'jra_id'			=> htmlentities($this->input->post('jra_id'))
					);

					$whereId = array('surat_id' => $surat_id);

					$cekRetensi = $this->db->query("SELECT * FROM retensi_arsip WHERE surat_id='$surat->surat_id'")->num_rows();

						if ($cekRetensi > 0) {
							$whereId = array('surat_id' => $surat->surat_id);
							$this->draft_model->update_data('retensi_arsip', $dataRetensi, $whereId);
						} else {
							$this->draft_model->insert_data('retensi_arsip', $dataRetensi);
						}
					
					$this->draft_model->update_data('retensi_arsip', $dataRetensi, $whereId);
					/* END:Input Retensi Arsip Surat Keluar [@Dam-Egov 12/01/2024] */
					
					$this->session->set_flashdata('success', 'Edit Penomoran Surat Berhasil');
					redirect(site_url('suratkeluar/draft/disposisi/' . $surat_id));
				} else {
					$this->session->set_flashdata('error', 'Edit Penomoran Surat Gagal');
					redirect(site_url('suratkeluar/draft/disposisi/' . $surat_id));
				}
			} elseif (isset($_POST['arsipkan'])) {
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
				} else {
					$this->session->set_flashdata('error', 'Surat Gagal Diarsipkan');
					redirect(site_url('suratkeluar/draft'));
				}
			} else {
				$surat_id = $this->uri->segment(4);

				// ======== START [UPDATE] FIKRI EGOV 24 FEB 2022 ============================================= [UPDATE] FIKRI EGOV 24 FEB 2022 ========================================== [UPDATE] FIKRI EGOV 24 FEB 2022 ========================================================================================			
				$data['content'] = 'draft_form';
				// 	$data['penandatangan'] = $this->draft_model->penandatangan($this->session->userdata('opd_id'),'')->result();
				$data['kodesurat'] = $this->db->get('kode_surat')->result();
				$data['series']    = $this->db->from('jra')->order_by('series', 'asc')->get()->result();

				if (substr($surat_id, 0, 2) == 'SB') {
					$qnomor = $this->draft_model->edit_data('surat_biasa', array('id' => $surat_id))->result();
					$kopsurat = $this->db->query("SELECT kop_id FROM surat_biasa WHERE id='$surat_id'")->row_array();
					if ($kopsurat['kop_id'] == 1 || $kopsurat['kop_id'] == 3) {
						// $data['penandatangan'] = $this->db->query("SELECT a.nama,a.jabatan_id, b.nama_jabatan FROM aparatur a LEFT JOIN jabatan b ON b.jabatan_id=a.jabatan_id WHERE a.jabatan_id IN ('1','2')")->result();
						$data['penandatangan'] = $this->db->query("SELECT a.nama,a.jabatan_id, b.nama_jabatan FROM aparatur a LEFT JOIN jabatan b ON b.jabatan_id=a.jabatan_id WHERE a.level_id IN ('1','2') AND statusaparatur='Aktif'")->result();
					} else {
						$data['penandatangan'] = $this->draft_model->penandatangan($this->session->userdata('opd_id'), '')->result();
					}
				} elseif (substr($surat_id, 0, 2) == 'SE') {
					$qnomor = $this->draft_model->edit_data('surat_edaran', array('id' => $surat_id))->result();
					$kopsurat = $this->db->query("SELECT kop_id FROM surat_edaran WHERE id='$surat_id'")->row_array();
					if ($kopsurat['kop_id'] == 1 || $kopsurat['kop_id'] == 3) {
						// $data['penandatangan'] = $this->db->query("SELECT a.nama,a.jabatan_id, b.nama_jabatan FROM aparatur a LEFT JOIN jabatan b ON b.jabatan_id=a.jabatan_id WHERE a.jabatan_id IN ('1','2')")->result();
						$data['penandatangan'] = $this->db->query("SELECT a.nama,a.jabatan_id, b.nama_jabatan FROM aparatur a LEFT JOIN jabatan b ON b.jabatan_id=a.jabatan_id WHERE a.level_id IN ('1','2') AND statusaparatur='Aktif'")->result();
					} else {
						$data['penandatangan'] = $this->draft_model->penandatangan($this->session->userdata('opd_id'), '')->result();
					}
				} elseif (substr($surat_id, 0, 2) == 'SU') {
					$qnomor = $this->draft_model->edit_data('surat_undangan', array('id' => $surat_id))->result();
					$kopsurat = $this->db->query("SELECT kop_id FROM surat_undangan WHERE id='$surat_id'")->row_array();
					if ($kopsurat['kop_id'] == 1 || $kopsurat['kop_id'] == 3) {
						// $data['penandatangan'] = $this->db->query("SELECT a.nama,a.jabatan_id, b.nama_jabatan FROM aparatur a LEFT JOIN jabatan b ON b.jabatan_id=a.jabatan_id WHERE a.jabatan_id IN ('1','2') AND statusaparatur='Aktif'")->result();
						$data['penandatangan'] = $this->db->query("SELECT a.nama,a.jabatan_id, b.nama_jabatan FROM aparatur a LEFT JOIN jabatan b ON b.jabatan_id=a.jabatan_id WHERE a.level_id IN ('1','2') AND statusaparatur='Aktif'")->result();
					} else {
						$data['penandatangan'] = $this->draft_model->penandatangan($this->session->userdata('opd_id'), '')->result();
					}
				} elseif (substr($surat_id, 0, 5) == 'PNGMN') {
					$qnomor = $this->draft_model->edit_data('surat_pengumuman', array('id' => $surat_id))->result();
					$kopsurat = $this->db->query("SELECT kop_id FROM surat_pengumuman WHERE id='$surat_id'")->row_array();
					if ($kopsurat['kop_id'] == 1 || $kopsurat['kop_id'] == 3) {
						// $data['penandatangan'] = $this->db->query("SELECT a.nama,a.jabatan_id, b.nama_jabatan FROM aparatur a LEFT JOIN jabatan b ON b.jabatan_id=a.jabatan_id WHERE a.jabatan_id IN ('1','2') AND statusaparatur='Aktif'")->result();
						$data['penandatangan'] = $this->db->query("SELECT a.nama,a.jabatan_id, b.nama_jabatan FROM aparatur a LEFT JOIN jabatan b ON b.jabatan_id=a.jabatan_id WHERE a.level_id IN ('1','2') AND statusaparatur='Aktif'")->result();
					} else {
						$data['penandatangan'] = $this->draft_model->penandatangan($this->session->userdata('opd_id'), '')->result();
					}
				} elseif (substr($surat_id, 0, 3) == 'LAP') {
					$qnomor = $this->draft_model->edit_data('surat_laporan', array('id' => $surat_id))->result();
					$kopsurat = $this->db->query("SELECT kop_id FROM surat_laporan WHERE id='$surat_id'")->row_array();
					if ($kopsurat['kop_id'] == 1 || $kopsurat['kop_id'] == 3) {
						$data['penandatangan'] = $this->db->query("SELECT a.nama,a.jabatan_id, b.nama_jabatan FROM aparatur a LEFT JOIN jabatan b ON b.jabatan_id=a.jabatan_id WHERE a.level_id IN ('1','2') AND statusaparatur='Aktif'")->result();
						// $data['penandatangan'] = $this->db->query("SELECT a.nama,a.jabatan_id, b.nama_jabatan FROM aparatur a LEFT JOIN jabatan b ON b.jabatan_id=a.jabatan_id WHERE a.jabatan_id IN ('1','2') AND statusaparatur='Aktif'")->result();
					} else {
						$data['penandatangan'] = $this->draft_model->penandatangan($this->session->userdata('opd_id'), '')->result();
					}
				} elseif (substr($surat_id, 0, 3) == 'REK') {
					$qnomor = $this->draft_model->edit_data('surat_rekomendasi', array('id' => $surat_id))->result();
					$kopsurat = $this->db->query("SELECT kop_id FROM surat_rekomendasi WHERE id='$surat_id'")->row_array();
					if ($kopsurat['kop_id'] == 1 || $kopsurat['kop_id'] == 3) {
						$data['penandatangan'] = $this->db->query("SELECT a.nama,a.jabatan_id, b.nama_jabatan FROM aparatur a LEFT JOIN jabatan b ON b.jabatan_id=a.jabatan_id WHERE a.level_id IN ('1','2') AND statusaparatur='Aktif'")->result();
						// $data['penandatangan'] = $this->db->query("SELECT a.nama,a.jabatan_id, b.nama_jabatan FROM aparatur a LEFT JOIN jabatan b ON b.jabatan_id=a.jabatan_id WHERE a.jabatan_id IN ('1','2') AND statusaparatur='Aktif'")->result();
					} else {
						$data['penandatangan'] = $this->draft_model->penandatangan($this->session->userdata('opd_id'), '')->result();
					}
				} elseif (substr($surat_id, 0, 3) == 'INT') {
					$qnomor = $this->draft_model->edit_data('surat_instruksi', array('id' => $surat_id))->result();
					$data['penandatangan'] = $this->draft_model->penandatangan($this->session->userdata('opd_id'), '')->result();
				} elseif (substr($surat_id, 0, 3) == 'PNG') {
					$qnomor = $this->draft_model->edit_data('surat_pengantar', array('id' => $surat_id))->result();
					$data['penandatangan'] = $this->draft_model->penandatangan($this->session->userdata('opd_id'), '')->result();
				} elseif (substr($surat_id, 0, 5) == 'NODIN') {
					$qnomor = $this->draft_model->edit_data('surat_notadinas', array('id' => $surat_id))->result();
					$kopsurat = $this->db->query("SELECT kop_id FROM surat_notadinas WHERE id='$surat_id'")->row_array();
					if ($kopsurat['kop_id'] == 1 || $kopsurat['kop_id'] == 3) {
						$data['penandatangan'] = $this->db->query("SELECT a.nama,a.jabatan_id, b.nama_jabatan FROM aparatur a LEFT JOIN jabatan b ON b.jabatan_id=a.jabatan_id WHERE a.level_id IN ('1','2') AND statusaparatur='Aktif'")->result();
						// $data['penandatangan'] = $this->db->query("SELECT a.nama,a.jabatan_id, b.nama_jabatan FROM aparatur a LEFT JOIN jabatan b ON b.jabatan_id=a.jabatan_id WHERE a.jabatan_id IN ('1','2') AND statusaparatur='Aktif'")->result();
					} else {
						$opdid = $this->session->userdata('opd_id');
						// $data['ttdnodin'] = $this->db->query("SELECT a.nama, a.jabatan_id, b.nama_jabatan FROM aparatur a LEFT JOIN jabatan b ON b.jabatan_id=a.jabatan_id LEFT JOIN users c ON c.aparatur_id=a.aparatur_id WHERE c.level_id=5 AND a.opd_id='$opdid' AND a.statusaparatur='Aktif'")->row_array();
						// Update @Mpik Egov 4 August 2022 : Penambahan Query Where In untuk pengkondisian kolom select penandatangan
						// $data['ttdnodin'] = $this->db->query("SELECT a.nama, a.jabatan_id, b.nama_jabatan, b.jabatan FROM aparatur a
						// LEFT JOIN jabatan b ON b.jabatan_id=a.jabatan_id
						// LEFT JOIN users c ON c.aparatur_id=a.aparatur_id
						// WHERE c.level_id IN (5,6,9,10,19) AND a.opd_id='$opdid' AND a.statusaparatur='Aktif'")->row_array();
						$data['ttdnodin'] = $this->db->query("SELECT a.nama, a.jabatan_id, b.nama_jabatan, b.jabatan FROM aparatur a
						LEFT JOIN jabatan b ON b.jabatan_id=a.jabatan_id
						LEFT JOIN users c ON c.aparatur_id=a.aparatur_id
						WHERE c.level_id IN (5,6,9,10,19) AND a.opd_id='$opdid' AND a.statusaparatur='Aktif'")->result();
					}
				} elseif (substr($surat_id, 0, 2) == 'SK') {
					$qnomor = $this->draft_model->edit_data('surat_keterangan', array('id' => $surat_id))->result();
					$kopsurat = $this->db->query("SELECT kop_id FROM surat_keterangan WHERE id='$surat_id'")->row_array();
					if ($kopsurat['kop_id'] == 1 || $kopsurat['kop_id'] == 3) {
						$data['penandatangan'] = $this->db->query("SELECT a.nama,a.jabatan_id, b.nama_jabatan FROM aparatur a LEFT JOIN jabatan b ON b.jabatan_id=a.jabatan_id WHERE a.level_id IN ('1','2') AND statusaparatur='Aktif'")->result();
						// $data['penandatangan'] = $this->db->query("SELECT a.nama,a.jabatan_id, b.nama_jabatan FROM aparatur a LEFT JOIN jabatan b ON b.jabatan_id=a.jabatan_id WHERE a.jabatan_id IN ('1','2') AND statusaparatur='Aktif'")->result();
					} else {
						$data['penandatangan'] = $this->draft_model->penandatangan($this->session->userdata('opd_id'), '')->result();
					}
				} elseif (substr($surat_id, 0, 3) == 'SPT') {
					$qnomor = $this->draft_model->edit_data('surat_perintahtugas', array('id' => $surat_id))->result();
					$kopsurat = $this->db->query("SELECT kop_id FROM surat_perintahtugas WHERE id='$surat_id'")->row_array();
					if ($kopsurat['kop_id'] == 1 || $kopsurat['kop_id'] == 3) {
						// $data['penandatangan'] = $this->db->query("SELECT a.nama,a.jabatan_id, b.nama_jabatan FROM aparatur a LEFT JOIN jabatan b ON b.jabatan_id=a.jabatan_id WHERE a.level_id IN ('1','2') AND statusaparatur='Aktif'")->result();
						$data['penandatangan'] = $this->db->query("SELECT a.nama,a.jabatan_id, b.nama_jabatan FROM aparatur a LEFT JOIN jabatan b ON b.jabatan_id=a.jabatan_id WHERE a.level_id IN ('1','2') AND statusaparatur='Aktif'")->result();
					} else {
						$data['penandatangan'] = $this->draft_model->penandatangan($this->session->userdata('opd_id'), '')->result();
					}
				} elseif (substr($surat_id, 0, 2) == 'SP') {
					$qnomor = $this->draft_model->edit_data('surat_perintah', array('id' => $surat_id))->result();
					$data['penandatangan'] = $this->draft_model->penandatangan($this->session->userdata('opd_id'), '')->result();
				} elseif (substr($surat_id, 0, 3) == 'IZN') {
					$qnomor = $this->draft_model->edit_data('surat_izin', array('id' => $surat_id))->result();
					$kopsurat = $this->db->query("SELECT kop_id FROM surat_izin WHERE id='$surat_id'")->row_array();
					if ($kopsurat['kop_id'] == 1 || $kopsurat['kop_id'] == 3) {
						$data['penandatangan'] = $this->db->query("SELECT a.nama,a.jabatan_id, b.nama_jabatan FROM aparatur a LEFT JOIN jabatan b ON b.jabatan_id=a.jabatan_id WHERE a.level_id IN ('1','2') AND statusaparatur='Aktif'")->result();
						// $data['penandatangan'] = $this->db->query("SELECT a.nama,a.jabatan_id, b.nama_jabatan FROM aparatur a LEFT JOIN jabatan b ON b.jabatan_id=a.jabatan_id WHERE a.jabatan_id IN ('1','2') AND statusaparatur='Aktif'")->result();
					} else {
						$data['penandatangan'] = $this->draft_model->penandatangan($this->session->userdata('opd_id'), '')->result();
					}
				} elseif (substr($surat_id, 0, 3) == 'PJL') {
					$qnomor = $this->draft_model->edit_data('surat_perjalanan', array('id' => $surat_id))->result();

					$data['penandatangan'] = $this->draft_model->penandatangan($this->session->userdata('opd_id'), '')->result();
				} elseif (substr($surat_id, 0, 3) == 'KSA') {
					$qnomor = $this->draft_model->edit_data('surat_kuasa', array('id' => $surat_id))->result();
					$kopsurat = $this->db->query("SELECT kop_id FROM surat_kuasa WHERE id='$surat_id'")->row_array();
					if ($kopsurat['kop_id'] == 1 || $kopsurat['kop_id'] == 3) {
						$data['penandatangan'] = $this->db->query("SELECT a.nama,a.jabatan_id, b.nama_jabatan FROM aparatur a LEFT JOIN jabatan b ON b.jabatan_id=a.jabatan_id WHERE a.level_id IN ('1','2') AND statusaparatur='Aktif'")->result();
						// $data['penandatangan'] = $this->db->query("SELECT a.nama,a.jabatan_id, b.nama_jabatan FROM aparatur a LEFT JOIN jabatan b ON b.jabatan_id=a.jabatan_id WHERE a.jabatan_id IN ('1','2') AND statusaparatur='Aktif'")->result();
					} else {
						$data['penandatangan'] = $this->draft_model->penandatangan($this->session->userdata('opd_id'), '')->result();
					}
				} elseif (substr($surat_id, 0, 3) == 'MKT') {
					$qnomor = $this->draft_model->edit_data('surat_melaksanakantugas', array('id' => $surat_id))->result();
					$kopsurat = $this->db->query("SELECT kop_id FROM surat_melaksanakantugas WHERE id='$surat_id'")->row_array();
					if ($kopsurat['kop_id'] == 1 || $kopsurat['kop_id'] == 3) {
						$data['penandatangan'] = $this->db->query("SELECT a.nama,a.jabatan_id, b.nama_jabatan FROM aparatur a LEFT JOIN jabatan b ON b.jabatan_id=a.jabatan_id WHERE a.level_id IN ('1','2') AND statusaparatur='Aktif'")->result();
						// $data['penandatangan'] = $this->db->query("SELECT a.nama,a.jabatan_id, b.nama_jabatan FROM aparatur a LEFT JOIN jabatan b ON b.jabatan_id=a.jabatan_id WHERE a.jabatan_id IN ('1','2') AND statusaparatur='Aktif'")->result();
					} else {
						$data['penandatangan'] = $this->draft_model->penandatangan($this->session->userdata('opd_id'), '')->result();
					}
				} elseif (substr($surat_id, 0, 3) == 'PGL') {
					$qnomor = $this->draft_model->edit_data('surat_panggilan', array('id' => $surat_id))->result();
					$kopsurat = $this->db->query("SELECT kop_id FROM surat_panggilan WHERE id='$surat_id'")->row_array();
					if ($kopsurat['kop_id'] == 1 || $kopsurat['kop_id'] == 3) {
						$data['penandatangan'] = $this->db->query("SELECT a.nama,a.jabatan_id, b.nama_jabatan FROM aparatur a LEFT JOIN jabatan b ON b.jabatan_id=a.jabatan_id WHERE a.level_id IN ('1','2') AND statusaparatur='Aktif'")->result();
						// $data['penandatangan'] = $this->db->query("SELECT a.nama,a.jabatan_id, b.nama_jabatan FROM aparatur a LEFT JOIN jabatan b ON b.jabatan_id=a.jabatan_id WHERE a.jabatan_id IN ('1','2') AND statusaparatur='Aktif'")->result();
					} else {
						$data['penandatangan'] = $this->draft_model->penandatangan($this->session->userdata('opd_id'), '')->result();
					}
				} elseif (substr($surat_id, 0, 3) == 'NTL') {
					$qnomor = $this->draft_model->edit_data('surat_notulen', array('id' => $surat_id))->result();

					$data['penandatangan'] = $this->draft_model->penandatangan($this->session->userdata('opd_id'), '')->result();
				} elseif (substr($surat_id, 0, 3) == 'MMO') {
					$qnomor = $this->draft_model->edit_data('surat_memo', array('id' => $surat_id))->result();
					$kopsurat = $this->db->query("SELECT kop_id FROM surat_memo WHERE id='$surat_id'")->row_array();
					if ($kopsurat['kop_id'] == 1 || $kopsurat['kop_id'] == 3) {
						$data['penandatangan'] = $this->db->query("SELECT a.nama,a.jabatan_id, b.nama_jabatan FROM aparatur a LEFT JOIN jabatan b ON b.jabatan_id=a.jabatan_id WHERE a.level_id IN ('1','2') AND statusaparatur='Aktif'")->result();
						// $data['penandatangan'] = $this->db->query("SELECT a.nama,a.jabatan_id, b.nama_jabatan FROM aparatur a LEFT JOIN jabatan b ON b.jabatan_id=a.jabatan_id WHERE a.jabatan_id IN ('1','2') AND statusaparatur='Aktif'")->result();
					} else {
						$data['penandatangan'] = $this->draft_model->penandatangan($this->session->userdata('opd_id'), '')->result();
					}
				} elseif (substr($surat_id, 0, 3) == 'LMP') {
					$qnomor = $this->draft_model->edit_data('surat_lampiran', array('id' => $surat_id))->result();
					$data['penandatangan'] = $this->draft_model->penandatangan($this->session->userdata('opd_id'), '')->result();
				} elseif (substr($surat_id, 0, 2) == 'SL') {
					$qnomor = $this->draft_model->edit_data('surat_lainnya', array('id' => $surat_id))->result();
					$data['penandatangan'] = $this->draft_model->penandatangan($this->session->userdata('opd_id'), '')->result();
				}

				// ======== START [UPDATE] FIKRI EGOV 24 FEB 2022 ============================================= [UPDATE] FIKRI EGOV 24 FEB 2022 ========================================== [UPDATE] FIKRI EGOV 24 FEB 2022 ========================================================================================			


				if (empty($qnomor)) {
					$data['nomor'] = '';
				} else {
					foreach ($qnomor as $key => $h) {
						$data['nomor'] = $h->nomor;
					}
				}

				$qttd = $this->draft_model->edit_data('penandatangan', array('surat_id' => $surat_id))->result();
				foreach ($qttd as $key => $h) {
					$data['ttd'] = $h->nama . ' - ' . $h->jabatan;
					$data['status'] = $h->status;
				}

				$this->load->view('template', $data);
			}
		}

		public function edit()
		{
			$surat_id = $this->uri->segment(4);

			$data['content'] 		= 'draft_form';

			//pengkondisian ttd untuk surat nota dinas [@dam|E-Gov 20042022] 
			// Penambahan Retensi Arsip Surat Keluar [@Dam-Egov 12/01/2024]
			$opdid 					= $this->session->userdata('opd_id');
			$data['ttdnodin'] 		= $this->db->query("SELECT a.nama, a.jabatan_id, b.nama_jabatan FROM aparatur a LEFT JOIN jabatan b ON b.jabatan_id=a.jabatan_id LEFT JOIN users c ON c.aparatur_id=a.aparatur_id WHERE c.level_id=5 AND a.opd_id='$opdid'")->row_array();

			$data['penandatangan'] 	= $this->draft_model->penandatangan($this->session->userdata('opd_id'), '')->result();
			$data['tandatangan'] 	= $this->db->query("SELECT * FROM draft 
									LEFT JOIN penandatangan ON penandatangan.surat_id = draft.surat_id	
									LEFT JOIN retensi_arsip ON draft.surat_id = retensi_arsip.surat_id
									WHERE draft.surat_id = '$surat_id';
									")->result();
			$data['kodesurat'] 		= $this->db->get('kode_surat')->result();

			$data['series'] 		= $this->db->from('jra')->order_by('series', 'asc')->get()->result();
			$data['jra']			= $this->db->query("SELECT * FROM retensi_arsip 
									LEFT JOIN jra ON jra.id = retensi_arsip.jra_id
									WHERE surat_id ='$surat_id'")->row();

			if (substr($surat_id, 0, 2) == 'SB') {
				$qnomor = $this->draft_model->edit_data('surat_biasa', array('id' => $surat_id))->result();
			} elseif (substr($surat_id, 0, 2) == 'SE') {
				$qnomor = $this->draft_model->edit_data('surat_edaran', array('id' => $surat_id))->result();
			} elseif (substr($surat_id, 0, 2) == 'SU') {
				$qnomor = $this->draft_model->edit_data('surat_undangan', array('id' => $surat_id))->result();
			} elseif (substr($surat_id, 0, 5) == 'PNGMN') {
				$qnomor = $this->draft_model->edit_data('surat_pengumuman', array('id' => $surat_id))->result();
			} elseif (substr($surat_id, 0, 3) == 'LAP') {
				$qnomor = $this->draft_model->edit_data('surat_laporan', array('id' => $surat_id))->result();;
			} elseif (substr($surat_id, 0, 3) == 'REK') {
				$qnomor = $this->draft_model->edit_data('surat_rekomendasi', array('id' => $surat_id))->result();
			} elseif (substr($surat_id, 0, 3) == 'INT') {
				$qnomor = $this->draft_model->edit_data('surat_instruksi', array('id' => $surat_id))->result();
			} elseif (substr($surat_id, 0, 3) == 'PNG') {
				$qnomor = $this->draft_model->edit_data('surat_pengantar', array('id' => $surat_id))->result();
			} elseif (substr($surat_id, 0, 5) == 'NODIN') {
				$qnomor = $this->draft_model->edit_data('surat_notadinas', array('id' => $surat_id))->result();
			} elseif (substr($surat_id, 0, 2) == 'SK') {
				$qnomor = $this->draft_model->edit_data('surat_keterangan', array('id' => $surat_id))->result();
			} elseif (substr($surat_id, 0, 3) == 'SPT') {
				$qnomor = $this->draft_model->edit_data('surat_perintahtugas', array('id' => $surat_id))->result();
			} elseif (substr($surat_id, 0, 2) == 'SP') {
				$qnomor = $this->draft_model->edit_data('surat_perintah', array('id' => $surat_id))->result();
			} elseif (substr($surat_id, 0, 3) == 'IZN') {
				$qnomor = $this->draft_model->edit_data('surat_izin', array('id' => $surat_id))->result();
			} elseif (substr($surat_id, 0, 3) == 'PJL') {
				$qnomor = $this->draft_model->edit_data('surat_perjalanan', array('id' => $surat_id))->result();
			} elseif (substr($surat_id, 0, 3) == 'KSA') {
				$qnomor = $this->draft_model->edit_data('surat_kuasa', array('id' => $surat_id))->result();
			} elseif (substr($surat_id, 0, 3) == 'MKT') {
				$qnomor = $this->draft_model->edit_data('surat_melaksanakantugas', array('id' => $surat_id))->result();
			} elseif (substr($surat_id, 0, 3) == 'PGL') {
				$qnomor = $this->draft_model->edit_data('surat_panggilan', array('id' => $surat_id))->result();
			} elseif (substr($surat_id, 0, 3) == 'NTL') {
				$qnomor = $this->draft_model->edit_data('surat_notulen', array('id' => $surat_id))->result();
			} elseif (substr($surat_id, 0, 3) == 'MMO') {
				$qnomor = $this->draft_model->edit_data('surat_memo', array('id' => $surat_id))->result();
			} elseif (substr($surat_id, 0, 3) == 'LMP') {
				$qnomor = $this->draft_model->edit_data('surat_lampiran', array('id' => $surat_id))->result();
			} elseif (substr($surat_id, 0, 2) == 'SL') {
				$qnomor = $this->draft_model->edit_data('surat_lainnya', array('id' => $surat_id))->result();
			}

			foreach ($qnomor as $key => $h) {
				$data['nomor'] = $h->nomor;
				$data['kodesurat_id'] = $h->kodesurat_id;
			}

			$qttd = $this->draft_model->edit_data('penandatangan', array('surat_id' => $surat_id))->result();
			foreach ($qttd as $key => $h) {
				$data['ttd'] = $h->jabatan;
				$data['status'] = $h->status;
			}

			$this->load->view('template', $data);
		}

		public function signature()
		{
			$data['content'] = 'signature_index';

			$tahun = $this->session->userdata('tahun');
			$jabatan_id = $this->session->userdata('jabatan_id');

			$data['tandatangan'] = $this->draft_model->get_tandatangan($jabatan_id)->result();

			$this->load->view('template', $data);
		}
		public function signature_wilayah()
		{
			$data['content'] = 'signature_index1';

			$tahun = $this->session->userdata('tahun');
			$jabatan_id = $this->session->userdata('jabatan_id');

			$data['tandatangan'] = $this->draft_model->get_tandatangan('229')->result();

			$this->load->view('template', $data);
		}

		public function signer()
		{

			//Perubahan Status Disposisi Surat Keluar Menjadi Selesai
			$surat_id1 = htmlentities($this->input->post('surat_id'));
			$StatusSurat = array('status' => 'Selesai');
			$whereid = array('surat_id' => $surat_id1);
			$updatestatus = $this->draft_model->update_data('disposisi_suratkeluar', $StatusSurat, $whereid);
			//Selesai

			$surat_id = htmlentities($this->input->post('surat_id'));
			$tandatangan = array('status' => 'Sudah Ditandatangani');
			$where = array('surat_id' => $surat_id);
			$signer = $this->draft_model->update_data('penandatangan', $tandatangan, $where);
			if ($signer) {
				$surat = $this->db->get_where('draft', $where)->row_array();
				$filesurat = $surat['nama_surat'] . ' - ' . tanggal($surat['tanggal']) . ' (' . $surat['surat_id'] . ').pdf';
				// kirim_email_eksternal($surat_id,$filesurat);
				redirect(site_url('suratkeluar/draft/signature'));
			} else {
				$this->session->set_flashdata('error', 'Surat Gagal Ditandatangan');
				redirect(site_url('suratkeluar/draft/signature'));
			}
		}

		public function tteNewPage()
		{
			$data['penandatangan_id'] = $this->input->post('penandatangan_id');
			$data['surat_id'] = $this->input->post('surat_id');
			$data['linksurat'] = $this->input->post('linksurat');

			$data['content'] = 'tte_index';

			$tahun = $this->session->userdata('tahun');
			$jabatan_id = $this->session->userdata('jabatan_id');

			$this->load->view('template', $data);
		}

		// public function cobaTTE()
		// {
		// 	// $homepage = file_get_contents(
		// 	// 	"https://api-tnd.kotabogor.go.id/assets/lampiransurat/Suratprodukhukum/Suratprodukhukum-Lampiran-No-SPH-6-042155.pdf"
		// 	// );
		// 	// header("Content-Type: application/pdf");
		// 	// echo $homepage;

		// 	$streamContext = stream_context_create([
		// 		'ssl' => [
		// 			'verify_peer' => false,
		// 			'verify_peer_name' => false
		// 		]
		// 	]);

		// 	$imageUrl = "https://api-tnd.kotabogor.go.id/assets/lampiransurat/Suratprodukhukum/Suratprodukhukum-Lampiran-No-SPH-6-042155.pdf";
		// 	$newFileName = "TesFile-baru" . '.pdf';
		// 	@$rawImage = file_get_contents($imageUrl, false, $streamContext);
		// 	if ($rawImage) {
		// 		file_put_contents("./uploads/backup/" . $newFileName, $rawImage);
		// 		// if (file_exists("./uploads/backup/$newFileName")) {
		// 		// 	unlink("./uploads/backup/$newFileName");
		// 		// 	file_put_contents("./uploads/backup/" . $newFileName, $rawImage);
		// 		// } else {
		// 		// 	file_put_contents("./uploads/backup/" . $newFileName, $rawImage);
		// 		// }
		// 		echo "berhasil";
		// 	} else {
		// 		echo "gagal";
		// 	}
		// }

		public function tteSigniature()
		{
			$this->esign_api_gateway_dev->execute();
		}

		public function simpanFile()
		{
			// echo "hai";
			// die;
			// $pdf_path = "https://tnd.kotabogor.go.id/export/biasa/SB-13224";

			// $arrContextOptions = array(
			// 	"ssl" => array(
			// 		"verify_peer" => false,
			// 		"verify_peer_name" => false,
			// 	),
			// );

			// $binnaryContent = file_get_contents($pdfUrl, false, stream_context_create($arrContextOptions));


			// $newFileName = $this->input->get('surat_id') . '.pdf';
			// $isUnique = false;
			// while (!$isUnique) {
			// 	if (file_exists("./uploads/PDF/$newFileName")) {
			// 		$newFileName = $this->input->get('surat_id') . '.pdf';
			// 	} else {
			// 		$isUnique = true;
			// 	}
			// }

			// file_put_contents("./uploads/PDF/$newFileName", $binnaryContent);

			// return 'true';

			$newFileName = $this->input->get('surat_id') . '.pdf';
			if ($this->input->get('surat_id') && $this->input->get('link')) {
				$imageUrl = $this->input->get('link');
				@$rawImage = file_get_contents($imageUrl);
				if ($rawImage) {
					if (file_exists("./uploads/PDF/$newFileName")) {
						unlink("./uploads/PDF/$newFileName");
						file_put_contents("./uploads/PDF/" . $newFileName, $rawImage);
					} else {
						file_put_contents("./uploads/PDF/" . $newFileName, $rawImage);
					}
					echo 'File Saved';
					return;
				} else {
					$error = error_get_last();
					echo "HTTP request failed. Error was: " . $error['message'];
					return;
				}
			}
			echo "surat_id dan link url harus diisi";
			// echo "hai";
		}
	}
