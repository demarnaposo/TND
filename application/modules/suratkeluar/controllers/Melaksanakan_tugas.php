<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Melaksanakan_tugas extends CI_Controller
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
		$this->load->model('melaksanakan_tugas_model');
	}

	public function index()
	{
		$data['content'] = 'melaksanakan_tugas/melaksanakan_tugas_index';

		$tahun = $this->session->userdata('tahun');
		$opd_id = $this->session->userdata('opd_id');
		$jabatan_id = $this->session->userdata('jabatan_id');
		$pegawai_id = $this->session->userdata('opd_id');
		$data['pegawai'] = $this->db->query('Select * from aparatur where opd_id = ' . $this->session->userdata('opd_id'))->result();

		$data['melaksanakan_tugas'] = $this->melaksanakan_tugas_model->get_data($tahun, $opd_id, $jabatan_id, $pegawai_id)->result();

		$this->load->view('template', $data);
	}

	public function add()
	{
		$data['content'] = 'melaksanakan_tugas/melaksanakan_tugas_form';
		$data['kop'] = $this->db->get('kop_surat')->result();
		$data['kodesurat'] = $this->db->get('kode_surat')->result();
		$data['pegawai'] = $this->db->query('Select * from aparatur left join levelbaru on levelbaru.level_id=aparatur.level_id where opd_id = ' . $this->session->userdata('opd_id') . ' and aparatur.level_id NOT IN (0,18,19) order by levelbaru.level_id ASC')->result();
		$this->load->view('template', $data);
	}

	public function insert()
	{
		if (isset($_POST['simpan'])) {
			$getEksID = $this->melaksanakan_tugas_model->get_ids('eksternal_keluar')->result();
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
			//form validation eksternal surat biasa
			$this->form_validation->set_rules('nama','Nama Eksternal', 'required');
        	$this->form_validation->set_rules('email','Email Eksternal', 'required');
			$this->form_validation->set_rules('required', '%s masih kosong, silakan diisikan');

			if($this->form_validation->run()===FALSE) {

			$this->session->set_flashdata('error', 'Nama dan email eksternal tidak boleh kosong');
			
			redirect(site_url('suratkeluar/melaksanakan_tugas/add'));
			
			}
			else {

			$insert = $this->melaksanakan_tugas_model->insert_data('eksternal_keluar', $data);

			if ($insert) {		
				$this->session->set_flashdata('success', 'Eksternal Berhasil Dibuat');
				redirect(site_url('suratkeluar/melaksanakan_tugas/add'));
				
			}else{
				$this->session->set_flashdata('error', 'Eksternal Gagal Dibuat');
				redirect(site_url('suratkeluar/draft/add/'));
			}
			
			}

		//Untuk menambahkan surat
		} else {
			$kopId = $this->input->post('kop_id');
			$getID = $this->melaksanakan_tugas_model->get_id()->result();
			foreach ($getID as $key => $h) {
				$id = $h->id;
			}
			if (empty($id)) {
				$surat_id = 'MKT-1';
			} else {
				$urut = substr($id, 4) + 1;
				$surat_id = 'MKT-' . $urut;
			}

			$data = array(
				'id' => $surat_id,
				'opd_id' => $this->session->userdata('opd_id'),
				'kop_id' => $this->input->post('kop_id'),
				'kodesurat_id' => $this->input->post('kodesurat_id'),
				'tanggal' => $this->input->post('tanggal'),
				'nomor' => '',
				'pegawai_id' => $this->input->post('pegawai_id'),
				'dasarsk' => $this->input->post('dasarsk'),
				'nomorsk' => $this->input->post('nomorsk'),
				'tmt' => $this->input->post('tmt'),
				'tugas' => $this->input->post('tugas'),
				'catatan' => $this->input->post('catatan'),
			);
			//form validation surat pernyataan melaksanakan tugas [@dam|E-Gov 11.04.2022]
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
						'field' => 'dasarsk',
						'label' => 'Dasar SK',
						'rules' => 'required'
					],

					[
						'field' => 'nomorsk',
						'label' => 'Nomor SK',
						'rules' => 'required'
					],

					[
						'field' => 'tmt',
						'label' => 'TMT',
						'rules' => 'required'
					],

					[
						'field' => 'tugas',
						'label' => 'Tugas',
						'rules' => 'required'
					]

				];
			$this->form_validation->set_rules($rules);
			$this->form_validation->set_message('required', '%s masih kosong, silakan diisi');

			if ($this->form_validation->run() === FALSE) {

				//set value
				$this->session->set_flashdata('value_kop_id', set_value('kop_id', kop_id));
				$this->session->set_flashdata('value_tujuan', set_value('', '*Silakan pilih kembali tujuan internal-eksternal Pemkot'));
				$this->session->set_flashdata('value_pegawai_id', set_value('pegawai_id', pegawai_id));
				$this->session->set_flashdata('value_dasarsk', set_value('dasarsk', dasarsk));
				$this->session->set_flashdata('value_nomorsk', set_value('nomorsk', nomorsk));
				$this->session->set_flashdata('value_tmt', set_value('tmt', tmt));
				$this->session->set_flashdata('value_tugas', set_value('tugas', tugas));
				$this->session->set_flashdata('value_catatan', set_value('catatan', catatan));

				//error
				$this->session->set_flashdata('kop_id', form_error('kop_id'));
				$this->session->set_flashdata('pegawai_id', form_error('pegawai_id'));
				$this->session->set_flashdata('dasarsk', form_error('dasarsk'));
				$this->session->set_flashdata('nomorsk', form_error('nomorsk'));
				$this->session->set_flashdata('tmt', form_error('tmt'));
				$this->session->set_flashdata('tugas', form_error('tugas'));

				redirect(site_url('suratkeluar/melaksanakan_tugas/add'));
			} else {

				$insert = $this->melaksanakan_tugas_model->insert_data('surat_melaksanakantugas', $data);
				if ($insert) {

					//pengiriman surat internal atau eksternal
					$jabatan_id = $this->input->post('jabatan_id');
					$eksternal_id = $this->input->post('eksternal_id');

					//proses input internal eksternal
					internal_eksternal('melaksanakan_tugas', $surat_id, $jabatan_id, $eksternal_id);

					//pengiriman tembusan internal atau eksternal
					$tembusan_id = $this->input->post('tembusan_id');
					$tembusaneks_id = $this->input->post('tembusaneks_id');

					//proses input tembusan internal atau eksternal
					internal_eksternal_tembusan('melaksanakan_tugas', $surat_id, $tembusan_id, $tembusaneks_id);

					$datadraft = array(
						'surat_id' => $surat_id,
						'kopId' => $kopId,
						'tanggal' => htmlentities($this->input->post('tanggal')),
						'dibuat_id' => $this->session->userdata('jabatan_id'),
						'penandatangan_id' => '',
						'verifikasi_id' => '',
						'nama_surat' => 'Surat Melaksanakan Tugas',
					);
					$this->melaksanakan_tugas_model->insert_data('draft', $datadraft);

					$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
					redirect(site_url('suratkeluar/melaksanakan_tugas'));
				} else {
					$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
					redirect(site_url('suratkeluar/melaksanakan_tugas/add'));
				}
			}
		}
	}

	public function edit()
	{
		$data['content'] = 'melaksanakan_tugas/melaksanakan_tugas_form';
		$data['kop'] = $this->db->get('kop_surat')->result();
		$data['kodesurat'] = $this->db->get('kode_surat')->result();
		$data['pegawai'] = $this->db->query('Select * from aparatur left join levelbaru on levelbaru.level_id=aparatur.level_id where opd_id = ' . $this->session->userdata('opd_id') . ' and aparatur.level_id NOT IN (0,18,19) order by levelbaru.level_id ASC')->result();

		$data['melaksanakan_tugas'] = $this->melaksanakan_tugas_model->edit_data($this->uri->segment(4), $this->session->userdata('opd_id'))->result();

		$this->load->view('template', $data);
	}

	public function update()
	{
		$id = $this->input->post('id');

		//pengiriman surat internal atau eksternal
		$jabatan_id = $this->input->post('jabatan_id');
		$eksternal_id = $this->input->post('eksternal_id');

		if (!empty($jabatan_id) or !empty($eksternal_id)) {
			internal_eksternal('melaksanakan_tugas', $id, $jabatan_id, $eksternal_id);
		}

		//pengiriman tembusan internal atau eksternal
		$tembusan_id = $this->input->post('tembusan_id');
		$tembusaneks_id = $this->input->post('tembusaneks_id');

		if (!empty($tembusan_id) or !empty($tembusaneks_id)) {
			internal_eksternal_tembusan('melaksanakan_tugas', $id, $tembusan_id, $tembusaneks_id);
		}

		$data = array(
			'kop_id' => $this->input->post('kop_id'),
			'kodesurat_id' => $this->input->post('kodesurat_id'),
			'tanggal' => htmlentities($this->input->post('tanggal')),
			'nomor' => '',
			'pegawai_id' => $this->input->post('pegawai_id'),
			'dasarsk' => $this->input->post('dasarsk'),
			'nomorsk' => $this->input->post('nomorsk'),
			'tmt' => $this->input->post('tmt'),
			'tugas' => $this->input->post('tugas'),
			'catatan' => $this->input->post('catatan'),
		);
		$where = array('id' => $id);
		$update = $this->melaksanakan_tugas_model->update_data('surat_melaksanakantugas', $data, $where);
		if ($update) {

			$datadraft = array(
				'tanggal' => $this->input->post('tanggal'),
				'kopId' => $this->input->post('kop_id'),
			);
			$wheredraft = array('surat_id' => $id);
			$this->melaksanakan_tugas_model->update_data('draft', $datadraft, $wheredraft);

			$this->session->set_flashdata('success', 'Surat Berhasil Diedit');

			$pembuatSurat = $this->db->get_where('draft', array('surat_id' => $id))->row_array();

			if ($pembuatSurat['dibuat_id'] == $this->session->userdata('jabatan_id')) {
				redirect(site_url('suratkeluar/melaksanakan_tugas'));
			} else {
				redirect(site_url('suratkeluar/draft'));
			}
		} else {
			$this->session->set_flashdata('error', 'Surat Gagal Diedit');
			redirect(site_url('suratkeluar/melaksanakan_tugas/edit/' . $id));
		}
	}

	public function delete()
	{
		$where = array('id' => $this->uri->segment(4));
		$delete = $this->melaksanakan_tugas_model->delete_data('surat_melaksanakantugas', $where);
		if ($delete) {
			$whereDis = array('surat_id' => $this->uri->segment(4));
			$this->melaksanakan_tugas_model->delete_data('draft', $whereDis);
			$this->melaksanakan_tugas_model->delete_data('verifikasi', $whereDis);
			$this->melaksanakan_tugas_model->delete_data('disposisi_suratkeluar', $whereDis);
			$this->melaksanakan_tugas_model->delete_data('tembusan_surat', $whereDis);

			$this->session->set_flashdata('success', 'Surat Berhasil Dihapus');
			redirect(site_url('suratkeluar/melaksanakan_tugas'));
		} else {
			$this->session->set_flashdata('error', 'Surat Gagal Dihapus');
			redirect(site_url('suratkeluar/melaksanakan_tugas'));
		}
	}

	public function delete_kepada()
	{
		$surat_id = $this->uri->segment(4);
		$id = $this->uri->segment(5);
		$where = array('dsuratkeluar_id' => $id);
		$delete = $this->melaksanakan_tugas_model->delete_data('disposisi_suratkeluar', $where);
		if ($delete) {
			$this->session->set_flashdata('success', 'Data Berhasil Dihapus');
			redirect(site_url('suratkeluar/melaksanakan_tugas/edit/' . $surat_id));
		} else {
			$this->session->set_flashdata('error', 'Surat Gagal Dihapus');
			redirect(site_url('suratkeluar/melaksanakan_tugas/edit/' . $surat_id));
		}
	}

	public function delete_tembusan()
	{
		$surat_id = $this->uri->segment(4);
		$id = $this->uri->segment(5);
		$where = array('tembusansurat_id' => $id);
		$delete = $this->melaksanakan_tugas_model->delete_data('tembusan_surat', $where);
		if ($delete) {
			$this->session->set_flashdata('success', 'Data Tembusan Berhasil Dihapus');
			redirect(site_url('suratkeluar/melaksanakan_tugas/edit/' . $surat_id));
		} else {
			$this->session->set_flashdata('error', 'Data Tembusan Gagal Dihapus');
			redirect(site_url('suratkeluar/melaksanakan_tugas/edit/' . $surat_id));
		}
	}
}
