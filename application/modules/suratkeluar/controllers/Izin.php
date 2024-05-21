<?php
defined('BASEPATH') or exit('No direct script access allowed');

class izin extends CI_Controller
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
		$this->load->model('izin_model');
	}

	public function index()
	{
		$data['content'] = 'izin/izin_index';

		$tahun = $this->session->userdata('tahun');
		$opd_id = $this->session->userdata('opd_id');
		$pegawai_id = $this->session->userdata('opd_id');
		$jabatan_id = $this->session->userdata('jabatan_id');

		$data['pegawai'] = $this->db->query('Select * from aparatur where opd_id = ' . $this->session->userdata('opd_id'))->result();
		$data['izin'] = $this->izin_model->get_data($tahun, $opd_id, $jabatan_id)->result();


		$this->load->view('template', $data);
	}

	public function add()
	{
		$data['content'] = 'izin/izin_form';
		$data['kodesurat'] = $this->db->get('kode_surat')->result();
		$data['kop'] = $this->db->get('kop_surat')->result();
		$data['aparatur'] = $this->db->get_where('aparatur', array('opd_id' => $this->session->userdata('opd_id'), 'nip !=' => '-'))->result();
		$data['pegawai'] = $this->db->query('Select * from aparatur left join levelbaru on levelbaru.level_id=aparatur.level_id where opd_id = ' . $this->session->userdata('opd_id') . ' and aparatur.level_id NOT IN (0,18,19) order by levelbaru.level_id ASC')->result();


		$this->load->view('template', $data);
	}

	public function insert()
	{

		if (isset($_POST['simpan'])) {
			$getEksID = $this->izin_model->get_ids('eksternal_keluar')->result();
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

				redirect(site_url('suratkeluar/izin/add'));
			} else {

				$insert = $this->izin_model->insert_data('eksternal_keluar', $data);

				if ($insert) {
					$this->session->set_flashdata('success', 'Eksternal Berhasil Dibuat');
					redirect(site_url('suratkeluar/izin/add'));
				} else {
					$this->session->set_flashdata('error', 'Eksternal Gagal Dibuat');
					redirect(site_url('suratkeluar/draft/add/'));
				}
			}

			//Untuk menambahkan surat
		} else {

			$kopId = $this->input->post('kop_id');
			$getID = $this->izin_model->get_id()->result();
			foreach ($getID as $key => $h) {
				$id = $h->id;
			}
			if (empty($id)) {
				$surat_id = 'IZN-1';
			} else {
				$urut = substr($id, 4) + 1;
				$surat_id = 'IZN-' . $urut;
			}

			//$p=array("<p>","</p>");		

			$data = array(
				'id' => $surat_id,
				'opd_id' => $this->session->userdata('opd_id'),
				'kop_id' => $this->input->post('kop_id'),
				'kodesurat_id' => $this->input->post('kodesurat_id'),
				'tanggal' => $this->input->post('tanggal'),
				'nomor' => '',
				'tentang' => $this->input->post('tentang'),
				'pegawai_id' => $this->input->post('pegawai_id'),
				'untuk' => str_replace($p, "", $this->input->post('untuk')),
				'dasar' => str_replace($p, "", $this->input->post('dasar')),
				'catatan' => $this->input->post('catatan')
			);
			//form validation surat izin [@dam|E-Gov 11.04.2022]
			$this->load->library('form_validation');

			$rules =
				[
					[
						'field' => 'kop_id',
						'label' => 'Kop Surat',
						'rules' => 'required'
					],

					[
						'field' => 'tentang',
						'label' => 'Tentang/Perihal',
						'rules' => 'required'
					],

					[
						'field' => 'dasar',
						'label' => 'Dasar',
						'rules' => 'required'
					],

					[
						'field' => 'pegawai_id',
						'label' => 'Daftar Pegawai',
						'rules' => 'required'
					],

					[
						'field' => 'untuk',
						'label' => 'Untuk',
						'rules' => 'required'
					]
				];
			$this->form_validation->set_rules($rules);
			$this->form_validation->set_message('required', '%s masih kosong, silakan diisi');

			if ($this->form_validation->run() === FALSE) {

				//set value
				$this->session->set_flashdata('value_kop_id', set_value('kop_id', kop_id));
				$this->session->set_flashdata('value_tujuan', set_value('', '*Silakan pilih kembali tujuan internal-eksternal Pemkot'));
				$this->session->set_flashdata('value_tentang', set_value('tentang', tentang));
				$this->session->set_flashdata('value_dasar', set_value('dasar', dasar));
				$this->session->set_flashdata('value_pegawai_id', set_value('pegawai_id', pegawai_id));
				$this->session->set_flashdata('value_untuk', set_value('untuk', untuk));
				$this->session->set_flashdata('value_catatan', set_value('catatan', catatan));


				//error
				$this->session->set_flashdata('kop_id', form_error('kop_id'));
				$this->session->set_flashdata('tentang', form_error('tentang'));
				$this->session->set_flashdata('dasar', form_error('dasar'));
				$this->session->set_flashdata('pegawai_id', form_error('pegawai_id'));
				$this->session->set_flashdata('untuk', form_error('untuk'));

				redirect(site_url('suratkeluar/izin/add'));
			} else {

				$insert = $this->izin_model->insert_data('surat_izin', $data);

				if ($insert) {

					//pengiriman surat internal atau eksternal
					$jabatan_id = $this->input->post('jabatan_id');
					$eksternal_id = $this->input->post('eksternal_id');

					//proses input internal eksternal
					internal_eksternal('izin', $surat_id, $jabatan_id, $eksternal_id);

					//pengiriman tembusan internal atau eksternal
					$tembusan_id = $this->input->post('tembusan_id');
					$tembusaneks_id = $this->input->post('tembusaneks_id');

					//proses input tembusan internal atau eksternal
					internal_eksternal_tembusan('izin', $surat_id, $tembusan_id, $tembusaneks_id);

					$datadraft = array(
						'surat_id' => $surat_id,
						'kopId' => $kopId,
						'tanggal' => htmlentities($this->input->post('tanggal')),
						'dibuat_id' => $this->session->userdata('jabatan_id'),
						'penandatangan_id' => '',
						'verifikasi_id' => '',
						'nama_surat' => 'Surat Izin',
					);
					$this->izin_model->insert_data('draft', $datadraft);

					$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
					redirect(site_url('suratkeluar/izin'));
				} else {
					$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
					redirect(site_url('suratkeluar/izin/add'));
				}
			}
		}
	}

	public function edit()
	{
		$data['content'] = 'izin/izin_form';
		$data['kodesurat'] = $this->db->get('kode_surat')->result();
		$data['kop'] = $this->db->get('kop_surat')->result();
		$data['aparatur'] = $this->db->get_where('aparatur', array('opd_id' => $this->session->userdata('opd_id'), 'nip !=' => '-'))->result();
		$data['pegawai'] = $this->db->query('Select * from aparatur left join levelbaru on levelbaru.level_id=aparatur.level_id where opd_id = ' . $this->session->userdata('opd_id') . ' and aparatur.level_id NOT IN (0,18,19) order by levelbaru.level_id ASC')->result();
		$data['izin'] = $this->izin_model->edit_data($this->uri->segment(4), $this->session->userdata('opd_id'))->result();

		$this->load->view('template', $data);
	}

	public function update()
	{
		$id = $this->input->post('id');
		$p = array("<p>", "</p>");

		//pengiriman surat internal atau eksternal
		$jabatan_id = $this->input->post('jabatan_id');
		$eksternal_id = $this->input->post('eksternal_id');

		if (!empty($jabatan_id) or !empty($eksternal_id)) {
			internal_eksternal('izin', $id, $jabatan_id, $eksternal_id);
		}

		//pengiriman tembusan internal atau eksternal
		$tembusan_id = $this->input->post('tembusan_id');
		$tembusaneks_id = $this->input->post('tembusaneks_id');

		if (!empty($tembusan_id) or !empty($tembusaneks_id)) {
			internal_eksternal_tembusan('izin', $id, $tembusan_id, $tembusaneks_id);
		}

		$data = array(
			'id' => $id,
			'opd_id' => $this->session->userdata('opd_id'),
			'kop_id' => $this->input->post('kop_id'),
			'kodesurat_id' => $this->input->post('kodesurat_id'),
			'tanggal' => $this->input->post('tanggal'),
			'nomor' => '',
			'tentang' => $this->input->post('tentang'),
			'pegawai_id' => $this->input->post('pegawai_id'),
			'untuk' => str_replace($p, "", $this->input->post('untuk')),
			'dasar' => str_replace($p, "", $this->input->post('dasar')),
			'catatan' => $this->input->post('catatan')

		);
		$where = array('id' => $id);
		$update = $this->izin_model->update_data('surat_izin', $data, $where);
		if ($update) {

			$datadraft = array(
				'tanggal' => $this->input->post('tanggal'),
				'kopId' => $this->input->post('kop_id'),
			);
			$wheredraft = array('surat_id' => $id);
			$this->izin_model->update_data('draft', $datadraft, $wheredraft);

			$this->session->set_flashdata('success', 'Surat Berhasil Diedit');

			$pembuatSurat = $this->db->get_where('draft', array('surat_id' => $id))->row_array();

			if ($pembuatSurat['dibuat_id'] == $this->session->userdata('jabatan_id')) {
				redirect(site_url('suratkeluar/izin'));
			} else {
				redirect(site_url('suratkeluar/draft'));
			}
		} else {
			$this->session->set_flashdata('error', 'Surat Gagal Diedit');
			redirect(site_url('suratkeluar/izin/edit/' . $id));
		}
	}

	public function delete()
	{
		$where = array('id' => $this->uri->segment(4));
		$delete = $this->izin_model->delete_data('surat_izin', $where);
		if ($delete) {
			$whereDis = array('surat_id' => $this->uri->segment(4));
			$this->izin_model->delete_data('draft', $whereDis);
			$this->izin_model->delete_data('verifikasi', $whereDis);
			$this->izin_model->delete_data('disposisi_suratkeluar', $whereDis);
			$this->izin_model->delete_data('tembusan_surat', $whereDis);

			$this->session->set_flashdata('success', 'Surat Berhasil Dihapus');
			redirect(site_url('suratkeluar/izin'));
		} else {
			$this->session->set_flashdata('error', 'Surat Gagal Dihapus');
			redirect(site_url('suratkeluar/izin'));
		}
	}

	public function delete_kepada()
	{
		$surat_id = $this->uri->segment(4);
		$id = $this->uri->segment(5);
		$where = array('dsuratkeluar_id' => $id);
		$delete = $this->izin_model->delete_data('disposisi_suratkeluar', $where);
		if ($delete) {
			$this->session->set_flashdata('success', 'Data Berhasil Dihapus');
			redirect(site_url('suratkeluar/izin/edit/' . $surat_id));
		} else {
			$this->session->set_flashdata('error', 'Surat Gagal Dihapus');
			redirect(site_url('suratkeluar/izin/edit/' . $surat_id));
		}
	}

	public function delete_tembusan()
	{
		$surat_id = $this->uri->segment(4);
		$id = $this->uri->segment(5);
		$where = array('tembusansurat_id' => $id);
		$delete = $this->izin_model->delete_data('tembusan_surat', $where);
		if ($delete) {
			$this->session->set_flashdata('success', 'Data Tembusan Berhasil Dihapus');
			redirect(site_url('suratkeluar/izin/edit/' . $surat_id));
		} else {
			$this->session->set_flashdata('error', 'Data Tembusan Gagal Dihapus');
			redirect(site_url('suratkeluar/izin/edit/' . $surat_id));
		}
	}
}
