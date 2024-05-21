<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Perintah extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		if (empty($this->session->userdata('login'))) {
			$this->session->set_flashdata('access', 'Anda harus login terlebih dahulu!');
			redirect(site_url());
		} elseif ($this->session->userdata('level') == 4 || $this->session->userdata('level') == 2 || $this->session->userdata('level') == 3 || $this->session->userdata('level') == 18) {
			$this->session->set_flashdata('access', 'Session Tidak Sesuai, Silahkan Login Kembali');
			redirect(site_url());
		}
		$this->load->model('perintah_model');
	}

	public function index()
	{
		$data['content'] = 'perintah/perintah_index';

		$tahun = $this->session->userdata('tahun');
		$opd_id = $this->session->userdata('opd_id');
		$jabatan_id = $this->session->userdata('jabatan_id');
		$pegawai_id = $this->session->userdata('opd_id');
		$data['pegawai'] = $this->db->query('Select * from aparatur where opd_id = ' . $this->session->userdata('opd_id'))->result();

		$data['perintah'] = $this->perintah_model->get_data($tahun, $opd_id, $jabatan_id)->result();

		$this->load->view('template', $data);
	}

	public function add()
	{
		$string = '-';
		$data['content'] = 'perintah/perintah_form';
		$data['kop'] = $this->db->get('kop_surat')->result();
		$data['kodesurat'] = $this->db->get('kode_surat')->result();
		// $data['pegawai'] = $this->db->query('Select * from aparatur INNER JOIN users ON aparatur.aparatur_id = users.aparatur_id where opd_id = '.$this->session->userdata('opd_id').' ORDER BY level_id asc')->result();	
		$data['pegawai'] = $this->db->query("Select aparatur.nip,aparatur.nama,aparatur.aparatur_id,jabatan.nama_jabatan from aparatur LEFT JOIN users ON users.aparatur_id=aparatur.aparatur_id LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id WHERE nip !='$string' AND aparatur.opd_id = " . $this->session->userdata('opd_id') . " AND statusaparatur='Aktif' ORDER BY aparatur.level_id ASC")->result();

		$this->load->view('template', $data);
	}

	public function insert()
	{
		if (isset($_POST['simpan'])) {
			$getEksID = $this->perintah_model->get_ids('eksternal_keluar')->result();
			foreach ($getEksID as $key => $ek) {
				$idEks = $ek->id;
			}
			if (empty($idEks)) {
				$eksternalkeluar_id = 'EKS-1';
			} else {
				$urut = substr($idEks, 4) + 1;
				$eksternalkeluar_id = 'EKS-' . $urut;
			}

			$data = array(
				'id' => $eksternalkeluar_id,
				'opd_id' => $this->session->userdata('opd_id'),
				'nama' => $this->input->post('nama'),
				'email' => $this->input->post('email'),
				'alamat_eksternal' => $this->input->post('alamat_eksternal'),
				'tempat' => $this->input->post('tempat'),
			);
			//form validation eksternal surat biasa
			$this->form_validation->set_rules('nama', 'Nama Eksternal', 'required');
			$this->form_validation->set_rules('email', 'Email Eksternal', 'required');
			$this->form_validation->set_rules('required', '%s masih kosong, silakan diisikan');

			if ($this->form_validation->run() === FALSE) {

				$this->session->set_flashdata('error', 'Nama dan email eksternal tidak boleh kosong');

				redirect(site_url('suratkeluar/perintah/add'));
			} else {

				$insert = $this->perintah_model->insert_data('eksternal_keluar', $data);

				if ($insert) {
					$this->session->set_flashdata('success', 'Eksternal Berhasil Dibuat');
					redirect(site_url('suratkeluar/perintah/add'));
				} else {
					$this->session->set_flashdata('error', 'Eksternal Gagal Dibuat');
					redirect(site_url('suratkeluar/draft/add/'));
				}
			}

			//Untuk menambahkan surat
		} else {
			$kopId = $this->input->post('kop_id');
			$getID = $this->perintah_model->get_id()->result();
			foreach ($getID as $key => $h) {
				$id = $h->id;
			}
			if (empty($id)) {
				$surat_id = 'SP-1';
			} else {
				$urut = substr($id, 3) + 1;
				$surat_id = 'SP-' . $urut;
			}

			$file = $_FILES['lampiran_lain']['name'];
			$datapegawai = implode(",", $this->input->post('pegawai_id'));
			//$p=array("<p>","</p>");
			if (empty($file)) {
				$data = array(
					'id' => $surat_id,
					'opd_id' => $this->session->userdata('opd_id'),
					'kop_id' => $this->input->post('kop_id'),
					'kodesurat_id' => $this->input->post('kodesurat_id'),
					'nomor' => '',
					'dasar' => $this->input->post('dasar'),
					'untuk' => $this->input->post('untuk'),
					'catatan' => $this->input->post('catatan'),
					'tanggal' => $this->input->post('tanggal'),
					'pegawai_id' => $datapegawai,
					'kepada' => $this->input->post('kepada'),
					'lampiran_lain' => '',
				);
				//form validation surat perintah [@dam|E-Gov 21.04.2022]
				$this->load->library('form_validation');

				$rules =
					[
						[
							'field' => 'kop_id',
							'label' => 'Kop Surat',
							'rules' => 'required'
						],

						[
							'field' => 'kepada',
							'label' => 'Daftar Pegawai'
						],

						[
							'field' => 'untuk',
							'label' => 'Tujuan Sprint',
							'rules' => 'required'
						]

					];
				$this->form_validation->set_rules($rules);
				$this->form_validation->set_message('required', '%s masih kosong, silakan diisi');

				if ($this->form_validation->run() === FALSE) {

					//set value
					$this->session->set_flashdata('value_kop_id', set_value('kop_id', kop_id));
					$this->session->set_flashdata('value_tujuan', set_value('', '*Silakan pilih kembali tujuan internal-eksternal Pemkot'));
					$this->session->set_flashdata('value_pegawai', set_value('', '*Silakan pilih kembali daftar pegawai yang diperintahkan'));
					$this->session->set_flashdata('value_kepada', set_value('kepada', kepada));
					$this->session->set_flashdata('value_untuk', set_value('untuk', untuk));
					$this->session->set_flashdata('value_catatan', set_value('catatan', catatan));


					//error
					$this->session->set_flashdata('kop_id', form_error('kop_id'));
					$this->session->set_flashdata('kepada', form_error('kepada'));
					$this->session->set_flashdata('untuk', form_error('untuk'));

					redirect(site_url('suratkeluar/perintah/add'));
				} else {

					$insert = $this->perintah_model->insert_data('surat_perintah', $data);
					if ($insert) {

						//pengiriman surat internal atau eksternal
						$jabatan_id = $this->input->post('jabatan_id');
						$eksternal_id = $this->input->post('eksternal_id');

						//proses input internal atau eksternal
						internal_eksternal('perintah', $surat_id, $jabatan_id, $eksternal_id);

						//pengiriman tembusan internal atau eksternal
						$tembusan_id = $this->input->post('tembusan_id');
						$tembusaneks_id = $this->input->post('tembusaneks_id');

						//proses input tembusan internal atau eksternal
						internal_eksternal_tembusan('perintah', $surat_id, $tembusan_id, $tembusaneks_id);

						$datadraft = array(
							'surat_id' => $surat_id,
							'kopId' => $kopId,
							'tanggal' => $this->input->post('tanggal'),
							'dibuat_id' => $this->session->userdata('jabatan_id'),
							'penandatangan_id' => '',
							'verifikasi_id' => '',
							'nama_surat' => 'Surat Perintah',
						);
						$this->perintah_model->insert_data('draft', $datadraft);

						$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
						redirect(site_url('suratkeluar/perintah'));
					} else {
						$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
						redirect(site_url('suratkeluar/perintah/add'));
					}
				}
			} else {
				$ambext = explode(".", $file);
				$ekstensi = end($ambext);
				$date = date('his');
				$nama_baru = "Lampiran-Surat-Perintah-No-" . $surat_id . "-" . $date;
				$nama_file = $nama_baru . "." . $ekstensi;
				$config['upload_path'] = './assets/lampiransurat/perintah/';
				$config['allowed_types'] = 'pdf|doc|docx';
				$config['max_size'] = '100000';
				$config['file_name'] = $nama_file;
				$this->upload->initialize($config);

				if (!$this->upload->do_upload('lampiran_lain')) {
					$this->session->set_flashdata('error', 'Upload File Gagal');
					redirect(site_url('suratkeluar/perintah/add'));
				} else {
					$data = array(
						'id' => $surat_id,
						'opd_id' => $this->session->userdata('opd_id'),
						'kop_id' => $this->input->post('kop_id'),
						'kodesurat_id' => $this->input->post('kodesurat_id'),
						'nomor' => '',
						'dasar' => $this->input->post('dasar'),
						'untuk' => $this->input->post('untuk'),
						'catatan' => $this->input->post('catatan'),
						'tanggal' => $this->input->post('tanggal'),
						'pegawai_id' => $datapegawai,
						'kepada' => $this->input->post('kepada'),
						'lampiran_lain' => $nama_file,
					);
					//form validation surat perintah [@dam|E-Gov 21.04.2022]
					$this->load->library('form_validation');

					$rules =
						[
							[
								'field' => 'kop_id',
								'label' => 'Kop Surat',
								'rules' => 'required'
							],

							[
								'field' => 'kepada',
								'label' => 'Daftar Pegawai'
							],

							[
								'field' => 'untuk',
								'label' => 'Tujuan Sprint',
								'rules' => 'required'
							]

						];
					$this->form_validation->set_rules($rules);
					$this->form_validation->set_message('required', '%s masih kosong, silakan diisi');

					if ($this->form_validation->run() === FALSE) {

						//set value
						$this->session->set_flashdata('value_kop_id', set_value('kop_id', kop_id));
						$this->session->set_flashdata('value_tujuan', set_value('', '*Silakan pilih kembali tujuan internal-eksternal Pemkot'));
						$this->session->set_flashdata('value_pegawai', set_value('', '*Silakan pilih kembali daftar pegawai yang diperintahkan'));
						$this->session->set_flashdata('value_kepada', set_value('kepada', kepada));
						$this->session->set_flashdata('value_untuk', set_value('untuk', untuk));
						$this->session->set_flashdata('value_catatan', set_value('catatan', catatan));
						$this->session->set_flashdata('value_lain', set_value('', '* Silakan upload kembali file lampiran'));

						//error
						$this->session->set_flashdata('kop_id', form_error('kop_id'));
						$this->session->set_flashdata('kepada', form_error('kepada'));
						$this->session->set_flashdata('untuk', form_error('untuk'));

						redirect(site_url('suratkeluar/perintah/add'));
					} else {

						$insert = $this->perintah_model->insert_data('surat_perintah', $data);
						if ($insert) {

							//pengiriman surat internal atau eksternal
							$jabatan_id = $this->input->post('jabatan_id');
							$eksternal_id = $this->input->post('eksternal_id');

							//proses input internal atau eksternal
							internal_eksternal('perintah', $surat_id, $jabatan_id, $eksternal_id);

							//pengiriman tembusan internal atau eksternal
							$tembusan_id = $this->input->post('tembusan_id');
							$tembusaneks_id = $this->input->post('tembusaneks_id');

							//proses input tembusan internal atau eksternal
							internal_eksternal_tembusan('perintah', $surat_id, $tembusan_id, $tembusaneks_id);

							$datadraft = array(
								'surat_id' => $surat_id,
								'kopId' => $kopId,
								'tanggal' => $this->input->post('tanggal'),
								'dibuat_id' => $this->session->userdata('jabatan_id'),
								'penandatangan_id' => '',
								'verifikasi_id' => '',
								'nama_surat' => 'Surat Perintah',
							);
							$this->perintah_model->insert_data('draft', $datadraft);

							$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
							redirect(site_url('suratkeluar/perintah'));
						} else {
							$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
							redirect(site_url('suratkeluar/perintah/add'));
						}
					}
				}
			}
		}
	}

	public function edit()
	{
		$string = '-';
		$data['content'] = 'perintah/perintah_form';
		$data['kop'] = $this->db->get('kop_surat', 1, 0)->result();
		$data['kodesurat'] = $this->db->get('kode_surat')->result();
		// $data['pegawai'] = $this->db->query('Select * from aparatur INNER JOIN users ON aparatur.aparatur_id = users.aparatur_id where opd_id = '.$this->session->userdata('opd_id').' ORDER BY level_id asc')->result();
		$data['pegawai'] = $this->db->query("Select aparatur.nip,aparatur.nama,aparatur.aparatur_id,jabatan.nama_jabatan from aparatur LEFT JOIN users ON users.aparatur_id=aparatur.aparatur_id LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id WHERE nip !='$string' AND aparatur.opd_id = " . $this->session->userdata('opd_id') . " ORDER BY jabatan.jabatan_id ASC")->result();
		$data['perintah'] = $this->perintah_model->edit_data($this->uri->segment(4), $this->session->userdata('opd_id'))->result();

		$this->load->view('template', $data);
	}

	public function update()
	{
		$id = $this->input->post('id');
		$datapegawai = implode(",", $this->input->post('pegawai_id'));
		//$p=array("<p>","</p>");

		//pengiriman surat internal atau eksternal
		$jabatan_id = $this->input->post('jabatan_id');
		$eksternal_id = $this->input->post('eksternal_id');

		if (!empty($jabatan_id) or !empty($eksternal_id)) {
			internal_eksternal('perintah', $id, $jabatan_id, $eksternal_id);
		}

		//pengiriman tembusan internal atau eksternal
		$tembusan_id = $this->input->post('tembusan_id');
		$tembusaneks_id = $this->input->post('tembusaneks_id');

		if (!empty($tembusan_id) or !empty($tembusaneks_id)) {
			internal_eksternal_tembusan('perintah', $id, $tembusan_id, $tembusaneks_id);
		}

		$file = $_FILES['lampiran_lain']['name'];

		if (empty($file)) {
			$getQuery = $this->perintah_model->edit_data('surat_perintah', array('id' => $id))->result();
			foreach ($getQuery as $key => $h) {
				$fileLampiran = $h->lampiran_lain;
			}
			$data = array(
				'kop_id' => $this->input->post('kop_id'),
				'kodesurat_id' => $this->input->post('kodesurat_id'),
				'nomor' => '',
				'dasar' => $this->input->post('dasar'),
				'untuk' => $this->input->post('untuk'),
				'catatan' => $this->input->post('catatan'),
				'tanggal' => $this->input->post('tanggal'),
				'pegawai_id' => $datapegawai,
				'kepada' => $this->input->post('kepada'),
				'lampiran_lain' => $lampiran_lain,
			);
			$where = array('id' => $id);
			$update = $this->perintah_model->update_data('surat_perintah', $data, $where);
			if ($update) {

				$datadraft = array(
					'tanggal' => $this->input->post('tanggal'),
				);
				$wheredraft = array('surat_id' => $id);
				$this->perintah_model->update_data('draft', $datadraft, $wheredraft);

				$this->session->set_flashdata('success', 'Surat Berhasil Diedit');

				$pembuatSurat = $this->db->get_where('draft', array('surat_id' => $id))->row_array();

				if ($pembuatSurat['dibuat_id'] == $this->session->userdata('jabatan_id')) {
					redirect(site_url('suratkeluar/perintah'));
				} else {
					redirect(site_url('suratkeluar/draft'));
				}
			} else {
				$this->session->set_flashdata('error', 'Surat Gagal Diedit');
				redirect(site_url('suratkeluar/perintah/edit/' . $id));
			}
		} else {
			$query = $this->db->query("SELECT * FROM surat_perintah WHERE id='$id'")->row_array();
			$ambext = explode(".", $file);
			$ekstensi = end($ambext);
			$date = date('his');
			$nama_baru = "Lampiran-Surat-Perintah-No-" . $id . "-" . $date;
			$nama_file = $nama_baru . "." . $ekstensi;
			$config['upload_path'] = './assets/lampiransurat/perintah/';
			$config['allowed_types'] = 'pdf|doc|docx';
			$config['max_size'] = '100000';
			$config['file_name'] = $nama_file;
			$this->upload->initialize($config);

			if (!$this->upload->do_upload('lampiran_lain')) {
				$this->session->set_flashdata('error', 'Upload File Gagal');
				redirect(site_url('suratkeluar/perintah/edit'));
			} else {
				@unlink('./assets/lampiransurat/perintah/' . $query['lampiran_lain']);
				$data = array(
					'kop_id' => $this->input->post('kop_id'),
					'kodesurat_id' => $this->input->post('kodesurat_id'),
					'nomor' => '',
					'dasar' => $this->input->post('dasar'),
					'untuk' => $this->input->post('untuk'),
					'catatan' => $this->input->post('catatan'),
					'tanggal' => $this->input->post('tanggal'),
					'pegawai_id' => $datapegawai,
					'kepada' => $this->input->post('kepada'),
					'lampiran_lain' => $nama_file,
				);
				$where = array('id' => $id);
				$update = $this->perintah_model->update_data('surat_perintah', $data, $where);
				if ($update) {

					$datadraft = array(
						'tanggal' => $this->input->post('tanggal'),
					);
					$wheredraft = array('surat_id' => $id);
					$this->perintah_model->update_data('draft', $datadraft, $wheredraft);

					$this->session->set_flashdata('success', 'Surat Berhasil Diedit');

					$pembuatSurat = $this->db->get_where('draft', array('surat_id' => $id))->row_array();

					if ($pembuatSurat['dibuat_id'] == $this->session->userdata('jabatan_id')) {
						redirect(site_url('suratkeluar/perintah'));
					} else {
						redirect(site_url('suratkeluar/draft'));
					}
				} else {
					$this->session->set_flashdata('error', 'Surat Gagal Diedit');
					redirect(site_url('suratkeluar/perintah/edit/' . $id));
				}
			}
		}
	}

	public function delete()
	{
		$where = array('id' => $this->uri->segment(4));
		$delete = $this->perintah_model->delete_data('surat_perintah', $where);
		if ($delete) {
			$whereDis = array('surat_id' => $this->uri->segment(4));
			$this->perintah_model->delete_data('draft', $whereDis);
			$this->perintah_model->delete_data('verifikasi', $whereDis);
			$this->perintah_model->delete_data('disposisi_suratkeluar', $whereDis);
			$this->perintah_model->delete_data('tembusan_surat', $whereDis);

			$this->session->set_flashdata('success', 'Surat Berhasil Dihapus');
			redirect(site_url('suratkeluar/perintah'));
		} else {
			$this->session->set_flashdata('error', 'Surat Gagal Dihapus');
			redirect(site_url('suratkeluar/perintah'));
		}
	}

	public function delete_kepada()
	{
		$surat_id = $this->uri->segment(4);
		$id = $this->uri->segment(5);
		$where = array('dsuratkeluar_id' => $id);
		$delete = $this->perintah_model->delete_data('disposisi_suratkeluar', $where);
		if ($delete) {
			$this->session->set_flashdata('success', 'Data Berhasil Dihapus');
			redirect(site_url('suratkeluar/perintah/edit/' . $surat_id));
		} else {
			$this->session->set_flashdata('error', 'Surat Gagal Dihapus');
			redirect(site_url('suratkeluar/perintah/edit/' . $surat_id));
		}
	}

	public function delete_tembusan()
	{
		$surat_id = $this->uri->segment(4);
		$id = $this->uri->segment(5);
		$where = array('tembusansurat_id' => $id);
		$delete = $this->perintah_model->delete_data('tembusan_surat', $where);
		if ($delete) {
			$this->session->set_flashdata('success', 'Data Tembusan Berhasil Dihapus');
			redirect(site_url('suratkeluar/perintah/edit/' . $surat_id));
		} else {
			$this->session->set_flashdata('error', 'Data Tembusan Gagal Dihapus');
			redirect(site_url('suratkeluar/perintah/edit/' . $surat_id));
		}
	}
}