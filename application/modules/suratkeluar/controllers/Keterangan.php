<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Keterangan extends CI_Controller
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
		$this->load->model('keterangan_model');
	}

	public function index()
	{
		$data['content'] = 'keterangan/keterangan_index';

		$tahun = $this->session->userdata('tahun');
		$opd_id = $this->session->userdata('opd_id');
		$jabatan_id = $this->session->userdata('jabatan_id');
		$pegawai_id = $this->session->userdata('opd_id');
		$data['pegawai'] = $this->db->query('Select * from aparatur where opd_id = ' . $this->session->userdata('opd_id'))->result();

		$data['keterangan'] = $this->keterangan_model->get_data($tahun, $opd_id, $jabatan_id, $pegawai_id)->result();

		$this->load->view('template', $data);
	}

	public function add()
	{
		$data['content'] = 'keterangan/keterangan_form';
		$data['kop'] = $this->db->get('kop_surat')->result();
		$data['kodesurat'] = $this->db->get('kode_surat')->result();
		$data['pegawai'] = $this->db->query('Select * from aparatur left join levelbaru on levelbaru.level_id=aparatur.level_id where opd_id = ' . $this->session->userdata('opd_id') . ' and aparatur.level_id NOT IN (0,18,19) order by levelbaru.level_id ASC')->result();


		$this->load->view('template', $data);
	}

	public function insert()
	{
		//untuk menambahkan data eksternal
		if (isset($_POST['simpan'])) {
			$getEksID = $this->keterangan_model->get_ids('eksternal_keluar')->result();
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

				redirect(site_url('suratkeluar/biasa/add'));
			} else {

				$insert = $this->keterangan_model->insert_data('eksternal_keluar', $data);

				if ($insert) {
					$this->session->set_flashdata('success', 'Eksternal Berhasil Dibuat');
					redirect(site_url('suratkeluar/biasa/add'));
				} else {
					$this->session->set_flashdata('error', 'Eksternal Gagal Dibuat');
					redirect(site_url('suratkeluar/draft/add/'));
				}
			}
		} else {

			$kopId = $this->input->post('kop_id');
			$getID = $this->keterangan_model->get_id()->result();
			foreach ($getID as $key => $h) {
				$id = $h->id;
			}
			if (empty($id)) {
				$surat_id = 'SK-1';
			} else {
				$urut = substr($id, 3) + 1;
				$surat_id = 'SK-' . $urut;
			}

			$file = $_FILES['lampiran_lain']['name'];
			if (empty($file)) {
				$data = array(
					'id' => $surat_id,
					'kop_id' => $this->input->post('kop_id'),
					'kodesurat_id' => $this->input->post('kodesurat_id'),
					'opd_id' => $this->session->userdata('opd_id'),
					'nomor' => '',
					'pegawai_id' => $this->input->post('pegawai_id'),
					'maksud' => $this->input->post('maksud'),
					'tanggal' => $this->input->post('tanggal'),
					'lampiran_lain' => '',
					'penutup' => $this->input->post('penutup'),
					'catatan' => $this->input->post('catatan'),
				);
				//form validation surat keterangan [@dam|E-Gov 11.04.2022]
				$this->load->library('form_validation');

				$rules =
					[
						[
							'field' => 'kop_id',
							'label' => 'Kop Surat',
							'rules' => 'required'
						],

						[
							'field' => 'pegawai_id',
							'label' => 'Pegawai',
							'rules' => 'required'
						],

						[
							'field' => 'maksud',
							'label' => 'Maksud',
							'rules' => 'required'
						],

						[
							'field' => 'penutup',
							'label' => 'Parapraf Penutup',
							'rules' => 'required'
						]

					];
				$this->form_validation->set_rules($rules);
				$this->form_validation->set_message('required', '%s masih kosong, silakan diisi');

				if ($this->form_validation->run() === FALSE) {

					//set value
					$this->session->set_flashdata('value_kop_id', set_value('kop_id', kop_id));
					$this->session->set_flashdata('value_tujuan', set_value('', '*Silakan pilih kembali tujuan internal-eksternal Pemkot'));
					$this->session->set_flashdata('value_pegawai', set_value('pegawai_id', pegawai_id));
					$this->session->set_flashdata('value_maksud', set_value('maksud', maksud));
					$this->session->set_flashdata('value_penutup', set_value('penutup', penutup));
					$this->session->set_flashdata('value_catatan', set_value('catatan', catatan));

					//error
					$this->session->set_flashdata('kop_id', form_error('kop_id'));
					$this->session->set_flashdata('pegawai_id', form_error('pegawai_id'));
					$this->session->set_flashdata('maksud', form_error('maksud'));
					$this->session->set_flashdata('penutup', form_error('penutup'));

					redirect(site_url('suratkeluar/keterangan/add'));
				} else {

					$insert = $this->keterangan_model->insert_data('surat_keterangan', $data);
					if ($insert) {

						//pengiriman surat internal atau eksternal
						$jabatan_id = $this->input->post('jabatan_id');
						$eksternal_id = $this->input->post('eksternal_id');

						//proses input internal eksternal
						internal_eksternal('keterangan', $surat_id, $jabatan_id, $eksternal_id);

						//pengiriman tembusan internal atau eksternal
						$tembusan_id = $this->input->post('tembusan_id');
						$tembusaneks_id = $this->input->post('tembusaneks_id');

						//proses input tembusan internal atau eksternal
						internal_eksternal_tembusan('keterangan', $surat_id, $tembusan_id, $tembusaneks_id);

						$datadraft = array(
							'surat_id' => $surat_id,
							'kopId' => $kopId,
							'tanggal' => $this->input->post('tanggal'),
							'dibuat_id' => $this->session->userdata('jabatan_id'),
							'penandatangan_id' => '',
							'verifikasi_id' => '',
							'nama_surat' => 'Surat Keterangan',
						);
						$this->keterangan_model->insert_data('draft', $datadraft);

						$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
						redirect(site_url('suratkeluar/keterangan'));
					} else {
						$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
						redirect(site_url('suratkeluar/keterangan/add'));
					}
				}
			} else {
				$ambext = explode(".", $file);
				$ekstensi = end($ambext);
				$date = date('his');
				$nama_baru = "Lampiran-Surat-Keterangan-No-" . $surat_id . "-" . $date;
				$nama_file = $nama_baru . "." . $ekstensi;
				$config['upload_path'] = './assets/lampiransurat/keterangan/';
				$config['allowed_types'] = 'pdf';
				$config['file_name'] = $nama_file;
				$this->upload->initialize($config);

				if (!$this->upload->do_upload('lampiran_lain')) {
					$this->session->set_flashdata('error', 'Upload File Gagal');
					redirect(site_url('suratkeluar/keterangan/add'));
				} else {
					$data = array(
						'id' => $surat_id,
						'kop_id' => $this->input->post('kop_id'),
						'kodesurat_id' => $this->input->post('kodesurat_id'),
						'opd_id' => $this->session->userdata('opd_id'),
						'nomor' => '',
						'pegawai_id' => $this->input->post('pegawai_id'),
						'maksud' => $this->input->post('maksud'),
						'tanggal' => $this->input->post('tanggal'),
						'lampiran_lain' => $nama_file,
						'penutup' => $this->input->post('penutup'),
						'catatan' => $this->input->post('catatan'),
					);
					//form validation surat keterangan [@dam|E-Gov 11.04.2022]
					$this->load->library('form_validation');

					$rules =
						[
							[
								'field' => 'kop_id',
								'label' => 'Kop Surat',
								'rules' => 'required'
							],

							[
								'field' => 'pegawai_id',
								'label' => 'Pegawai',
								'rules' => 'required'
							],

							[
								'field' => 'maksud',
								'label' => 'Maksud',
								'rules' => 'required'
							],

							[
								'field' => 'penutup',
								'label' => 'Parapraf Penutup',
								'rules' => 'required'
							]

						];
					$this->form_validation->set_rules($rules);
					$this->form_validation->set_message('required', '%s masih kosong, silakan diisi');

					if ($this->form_validation->run() === FALSE) {

						//set value
						$this->session->set_flashdata('value_kop_id', set_value('kop_id', kop_id));
						$this->session->set_flashdata('value_tujuan', set_value('', '*Silakan pilih kembali tujuan internal-eksternal Pemkot'));
						$this->session->set_flashdata('value_pegawai', set_value('pegawai_id', pegawai_id));
						$this->session->set_flashdata('value_maksud', set_value('maksud', maksud));
						$this->session->set_flashdata('value_penutup', set_value('penutup', penutup));
						$this->session->set_flashdata('value_catatan', set_value('catatan', catatan));
						$this->session->set_flashdata('value_lain', set_value('', '* Silakan upload kembali file lampiran'));

						//error
						$this->session->set_flashdata('kop_id', form_error('kop_id'));
						$this->session->set_flashdata('pegawai_id', form_error('pegawai_id'));
						$this->session->set_flashdata('maksud', form_error('maksud'));
						$this->session->set_flashdata('penutup', form_error('penutup'));

						redirect(site_url('suratkeluar/keterangan/add'));
					} else {

						$insert = $this->keterangan_model->insert_data('surat_keterangan', $data);
						if ($insert) {

							$datadraft = array(
								'surat_id' => $surat_id,
								'kopId' => $kopId,
								'tanggal' => $this->input->post('tanggal'),
								'dibuat_id' => $this->session->userdata('jabatan_id'),
								'penandatangan_id' => '',
								'verifikasi_id' => '',
								'nama_surat' => 'Surat Keterangan',
							);
							$this->keterangan_model->insert_data('draft', $datadraft);

							$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
							redirect(site_url('suratkeluar/keterangan'));
						} else {
							$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
							redirect(site_url('suratkeluar/keterangan/add'));
						}
					}
				}
			}
		}
	}

	public function edit()
	{
		$data['content'] = 'keterangan/keterangan_form';
		$data['kop'] = $this->db->get('kop_surat')->result();
		$data['kodesurat'] = $this->db->get('kode_surat')->result();
		$data['pegawai'] = $this->db->query('Select * from aparatur left join levelbaru on levelbaru.level_id=aparatur.level_id where opd_id = ' . $this->session->userdata('opd_id') . ' and aparatur.level_id NOT IN (0,18,19) order by levelbaru.level_id ASC')->result();

		$data['keterangan'] = $this->keterangan_model->edit_data($this->uri->segment(4), $this->session->userdata('opd_id'))->result();

		$this->load->view('template', $data);
	}

	public function update()
	{
		$id = $this->input->post('id');

		//pengiriman surat internal atau eksternal
		$jabatan_id = $this->input->post('jabatan_id');
		$eksternal_id = $this->input->post('eksternal_id');

		if (!empty($jabatan_id) or !empty($eksternal_id)) {
			internal_eksternal('keterangan', $id, $jabatan_id, $eksternal_id);
		}

		//pengiriman tembusan internal atau eksternal
		$tembusan_id = $this->input->post('tembusan_id');
		$tembusaneks_id = $this->input->post('tembusaneks_id');

		if (!empty($tembusan_id) or !empty($tembusaneks_id)) {
			internal_eksternal_tembusan('keterangan', $id, $tembusan_id, $tembusaneks_id);
		}

		$file = $_FILES['lampiran_lain']['name'];

		if (empty($file)) {
			$data = array(
				'kop_id' => $this->input->post('kop_id'),
				'kodesurat_id' => $this->input->post('kodesurat_id'),
				'pegawai_id' => $this->input->post('pegawai_id'),
				'maksud' => $this->input->post('maksud'),
				'tanggal' => $this->input->post('tanggal'),
				'penutup' => $this->input->post('penutup'),
				'catatan' => $this->input->post('catatan'),
			);
			$where = array('id' => $id);
			$update = $this->keterangan_model->update_data('surat_keterangan', $data, $where);
			if ($update) {

				$datadraft = array(
					'tanggal' => $this->input->post('tanggal'),
					'kopId' => $this->input->post('kop_id'),
				);
				$wheredraft = array('surat_id' => $id);
				$this->keterangan_model->update_data('draft', $datadraft, $wheredraft);

				$this->session->set_flashdata('success', 'Surat Berhasil Diedit');

				$pembuatSurat = $this->db->get_where('draft', array('surat_id' => $id))->row_array();

				if ($pembuatSurat['dibuat_id'] == $this->session->userdata('jabatan_id')) {
					redirect(site_url('suratkeluar/keterangan'));
				} else {
					redirect(site_url('suratkeluar/draft'));
				}
			} else {
				$this->session->set_flashdata('error', 'Surat Gagal Diedit');
				redirect(site_url('suratkeluar/keterangan/edit/' . $id));
			}
		} else {
			$query = $this->db->query("SELECT * FROM surat_keterangan WHERE id='$id'")->row_array();
			$ambext = explode(".", $file);
			$ekstensi = end($ambext);
			$date = date('his');
			$nama_baru = "Lampiran-Surat-Keterangan-No-" . $id . "-" . $date;
			$nama_file = $nama_baru . "." . $ekstensi;
			$config['upload_path'] = './assets/lampiransurat/keterangan/';
			$config['allowed_types'] = 'pdf';
			$config['file_name'] = $nama_file;
			$this->upload->initialize($config);

			if (!$this->upload->do_upload('lampiran_lain')) {
				$this->session->set_flashdata('error', 'Upload File Gagal');
				redirect(site_url('suratkeluar/keterangan/add'));
			} else {
				@unlink('./assets/lampiransurat/keterangan/' . $query['lampiran_lain']);
				$data = array(
					'kop_id' => $this->input->post('kop_id'),
					'kodesurat_id' => $this->input->post('kodesurat_id'),
					'pegawai_id' => $this->input->post('pegawai_id'),
					'maksud' => $this->input->post('maksud'),
					'tanggal' => $this->input->post('tanggal'),
					'lampiran_lain' => $nama_file,
					'penutup' => $this->input->post('penutup'),
					'catatan' => $this->input->post('catatan'),
				);
				$where = array('id' => $id);
				$update = $this->keterangan_model->update_data('surat_keterangan', $data, $where);
				if ($update) {

					$datadraft = array(
						'tanggal' => $this->input->post('tanggal'),
						'kopId' => $this->input->post('kop_id'),
					);
					$wheredraft = array('surat_id' => $id);
					$this->keterangan_model->update_data('draft', $datadraft, $wheredraft);

					$this->session->set_flashdata('success', 'Surat Berhasil Diedit');

					$pembuatSurat = $this->db->get_where('draft', array('surat_id' => $id))->row_array();

					if ($pembuatSurat['dibuat_id'] == $this->session->userdata('jabatan_id')) {
						redirect(site_url('suratkeluar/keterangan'));
					} else {
						redirect(site_url('suratkeluar/draft'));
					}
				} else {
					$this->session->set_flashdata('error', 'Surat Gagal Diedit');
					redirect(site_url('suratkeluar/keterangan/edit/' . $id));
				}
			}
		}
	}

	public function delete()
	{
		$where = array('id' => $this->uri->segment(4));
		$id = $this->uri->segment(4);
		$query = $this->db->query("SELECT * FROM surat_keterangan WHERE id='$id'")->row_array();
		@unlink('./assets/lampiransurat/keterangan/' . $query['lampiran_lain']);
		$delete = $this->keterangan_model->delete_data('surat_keterangan', $where);
		if ($delete) {
			$whereDis = array('surat_id' => $this->uri->segment(4));
			$this->keterangan_model->delete_data('draft', $whereDis);
			$this->keterangan_model->delete_data('verifikasi', $whereDis);
			$this->keterangan_model->delete_data('disposisi_suratkeluar', $whereDis);
			$this->keterangan_model->delete_data('tembusan_surat', $whereDis);

			$this->session->set_flashdata('success', 'Surat Berhasil Dihapus');
			redirect(site_url('suratkeluar/keterangan'));
		} else {
			$this->session->set_flashdata('error', 'Surat Gagal Dihapus');
			redirect(site_url('suratkeluar/keterangan'));
		}
	}

	public function delete_kepada()
	{
		$surat_id = $this->uri->segment(4);
		$id = $this->uri->segment(5);
		$where = array('dsuratkeluar_id' => $id);
		$delete = $this->keterangan_model->delete_data('disposisi_suratkeluar', $where);
		if ($delete) {
			$this->session->set_flashdata('success', 'Data Berhasil Dihapus');
			redirect(site_url('suratkeluar/keterangan/edit/' . $surat_id));
		} else {
			$this->session->set_flashdata('error', 'Surat Gagal Dihapus');
			redirect(site_url('suratkeluar/keterangan/edit/' . $surat_id));
		}
	}

	public function delete_tembusan()
	{
		$surat_id = $this->uri->segment(4);
		$id = $this->uri->segment(5);
		$where = array('tembusansurat_id' => $id);
		$delete = $this->keterangan_model->delete_data('tembusan_surat', $where);
		if ($delete) {
			$this->session->set_flashdata('success', 'Data Tembusan Berhasil Dihapus');
			redirect(site_url('suratkeluar/keterangan/edit/' . $surat_id));
		} else {
			$this->session->set_flashdata('error', 'Data Tembusan Gagal Dihapus');
			redirect(site_url('suratkeluar/keterangan/edit/' . $surat_id));
		}
	}
}
