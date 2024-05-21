<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class perjalanan extends CI_Controller {

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
		$this->load->model('perjalanan_model');
	}

	public function index()
	{
		$data['content'] = 'perjalanan/perjalanan_index';
		
		$tahun = $this->session->userdata('tahun');
		$opd_id = $this->session->userdata('opd_id');
		$jabatan_id = $this->session->userdata('jabatan_id');

		$data['perjalanan'] = $this->perjalanan_model->get_data($tahun,$opd_id,$jabatan_id)->result();
		
		$this->load->view('template', $data);
	} 

	public function add()
	{
		$data['content'] = 'perjalanan/perjalanan_form';
        	$data['kodesurat'] = $this->db->get('kode_surat')->result();
			$data['pegawai'] = $this->db->query('Select * from aparatur left join levelbaru on levelbaru.level_id=aparatur.level_id where opd_id = '.$this->session->userdata('opd_id').' and aparatur.level_id NOT IN (0,18,19) order by levelbaru.level_id ASC')->result();	
        	$data['opd'] = $this->db->query("SELECT * FROM opd LEFT JOIN urutan_opd ON urutan_opd.urutan_id=opd.urutan_id WHERE opd.statusopd='Aktif' AND opd.urutan_id !=0 ORDER BY urutan_opd.urutan_id ASC")->result();
		$data['aparatur'] = $this->db->get_where('aparatur', array('opd_id' => $this->session->userdata('opd_id'),'nip !=' => '-'))->result();
		
		$this->load->view('template', $data);
	}

	public function insert()
	{
		$getID = $this->perjalanan_model->get_id()->result();

		// Update @MpikEgov 14 Januari 2023
		$opdId=$this->session->userdata('opd_id');
		if($opdId == 4){
			$kopId=4;
		}elseif($opdId == 2){
			$kopId=1;
		}elseif($opdId == 3){
			$kopId=3;
		}else{
			$kopId=2;
		}
		// END Update 14 Januari 2023
		foreach ($getID as $key => $h) {
			$id = $h->id;
		}
		if (empty($id)) {
			$surat_id = 'PJL-1';
		}else{
			$urut = substr($id, 4)+1;
			$surat_id = 'PJL-'.$urut;
		}

		$datapengikut=implode(",",$this->input->post('pengikut_id'));
		$data = array(
			'id' => $surat_id,
			'opd_id' => $this->session->userdata('opd_id'),
			'kodesurat_id' => htmlentities($this->input->post('kodesurat_id')), 
			'nomor' => '',
			'tanggal' => htmlentities($this->input->post('tanggal')),
			'pegawai_id' => $this->input->post('pegawai_id'), 
			'tingkat_biaya' => $this->input->post('tingkat_biaya'),
			'maksud_perjalanan' => $this->input->post('maksud_perjalanan'),
			'alat_angkutan' => $this->input->post('alat_angkutan'),
			'tmpt_berangkat' => $this->input->post('tmpt_berangkat'),
			'tmpt_tujuan' => $this->input->post('tmpt_tujuan'),
			'lama_perjalanan' => $this->input->post('lama_perjalanan'),
			'tgl_berangkat' => htmlentities($this->input->post('tgl_berangkat')),
			'tgl_pulang' => htmlentities($this->input->post('tgl_pulang')),
			'pengikut_id' => $datapengikut,
			'perangkatdaerah_id' => $this->input->post('perangkatdaerah_id'),
			'keterangan' => $this->input->post('keterangan'),
			'catatan' => $this->input->post('catatan')
		);
		//form validation surat perjalanan dinas [@dam|E-Gov 11.04.2022]
		$this->load->library('form_validation');	
				
		$rules =
		    [
		        [
		        'field' => 'pegawai_id',  
		        'label' => 'Pegawai Pelaksana',
		        'rules' => 'required'],
			        
		        [
		        'field' => 'tingkat_biaya',  
		        'label' => 'Tingkat Biaya',
		        'rules' => 'required'],
				        
		        [
		        'field' => 'maksud_perjalanan',  
		        'label' => 'Maksud Perjalanan',
		        'rules' => 'required'],
			        
		        [
		        'field' => 'alat_angkutan',  
		        'label' => 'Alat Angkutan',
		        'rules' => 'required'],
				        
		        [
		        'field' => 'tmpt_berangkat',  
		        'label' => 'Tempat Berangkat',
		        'rules' => 'required'],
		        
		        [
		        'field' => 'tmpt_tujuan',  
		        'label' => 'Tempat Tujuan',
		        'rules' => 'required'],
		        
		        [
		        'field' => 'lama_perjalanan',  
		        'label' => 'Lama Perjalanan',
		        'rules' => 'required'],
		        
		        [
		        'field' => 'perangkatdaerah_id',  
		        'label' => 'Perangkat Daerah',
		        'rules' => 'required'],
		        
		        [
		        'field' => 'keterangan',  
		        'label' => 'Keterangan Lain-Lain',
		        'rules' => 'required']
		        
		    ];
		$this->form_validation->set_rules($rules);
		$this->form_validation->set_message('required', '%s masih kosong, silakan diisi');				
		if($this->form_validation->run()===FALSE) {
				
		//set value
		$this->session->set_flashdata('value_pegawai_id', set_value('pegawai_id', pegawai_id));
		$this->session->set_flashdata('value_tujuan', set_value('', '*Silakan pilih kembali tujuan internal-eksternal Pemkot'));
		$this->session->set_flashdata('value_tingkat_biaya', set_value('tingkat_biaya', tingkat_biaya));
		$this->session->set_flashdata('value_maksud_perjalanan', set_value('maksud_perjalanan', maksud_perjalanan));
		$this->session->set_flashdata('value_alat_angkutan', set_value('alat_angkutan', alat_angkutan)); 
		$this->session->set_flashdata('value_tmpt_berangkat', set_value('tmpt_berangkat', tmpt_berangkat));
		$this->session->set_flashdata('value_tmpt_tujuan', set_value('tmpt_tujuan', tmpt_tujuan));
		$this->session->set_flashdata('value_lama_perjalanan', set_value('lama_perjalanan', lama_perjalanan));
		$this->session->set_flashdata('value_pengikut', set_value('', '*Silakan pilih kembali jika ada pegawai pengikut'));
		$this->session->set_flashdata('value_perangkatdaerah_id', set_value('perangkatdaerah_id', perangkatdaerah_id));
		$this->session->set_flashdata('value_keterangan', set_value('keterangan', keterangan));
		$this->session->set_flashdata('value_catatan', set_value('catatan', catatan));
		
		//error
		$this->session->set_flashdata('pegawai_id', form_error('pegawai_id'));
		$this->session->set_flashdata('tingkat_biaya', form_error('tingkat_biaya'));
		$this->session->set_flashdata('maksud_perjalanan', form_error('maksud_perjalanan'));
		$this->session->set_flashdata('alat_angkutan', form_error('alat_angkutan'));
		$this->session->set_flashdata('tmpt_berangkat', form_error('tmpt_berangkat'));
		$this->session->set_flashdata('tmpt_tujuan', form_error('tmpt_tujuan'));
		$this->session->set_flashdata('lama_perjalanan', form_error('lama_perjalanan'));
		$this->session->set_flashdata('perangkatdaerah_id', form_error('perangkatdaerah_id'));
		$this->session->set_flashdata('keterangan', form_error('keterangan'));
			
		redirect(site_url('suratkeluar/perjalanan/add'));
			
		}
		else {		
		$insert = $this->perjalanan_model->insert_data('surat_perjalanan', $data);
		if ($insert) {

			//pengiriman surat internal atau eksternal
			$jabatan_id = $this->input->post('jabatan_id');
			$eksternal_id = $this->input->post('eksternal_id');

			//proses input internal eksternal
			internal_eksternal('perjalanan',$surat_id,$jabatan_id,$eksternal_id);
					
			//pengiriman tembusan internal atau eksternal
	        $tembusan_id = $this->input->post('tembusan_id');
		    $tembusaneks_id = $this->input->post('tembusaneks_id');
			
		    //proses input tembusan internal atau eksternal
		    internal_eksternal_tembusan('perjalanan',$surat_id,$tembusan_id,$tembusaneks_id);

			$datadraft = array(
				'surat_id' => $surat_id,
				'kopId' => $kopId,
				'tanggal' => htmlentities($this->input->post('tanggal')), 
				'dibuat_id' => $this->session->userdata('jabatan_id'), 
				'penandatangan_id' => '',
				'verifikasi_id' => '', 
				'nama_surat' => 'Surat Perjalanan Dinas', 
			);
			$this->perjalanan_model->insert_data('draft', $datadraft);

			$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
			redirect(site_url('suratkeluar/perjalanan'));
		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
			redirect(site_url('suratkeluar/perjalanan/add'));
		}
		}
	}

	public function edit()
	{
		$string='-';
		$data['content'] = 'perjalanan/perjalanan_form';
		$data['kodesurat'] = $this->db->get('kode_surat')->result();
		$data['opd'] = $this->db->query("SELECT * FROM opd LEFT JOIN urutan_opd ON urutan_opd.urutan_id=opd.urutan_id WHERE opd.statusopd='Aktif' AND opd.urutan_id !=0 ORDER BY urutan_opd.urutan_id ASC")->result();
		$data['aparatur'] = $this->db->get('aparatur')->result();
		$data['pegawai'] = $this->db->query('Select * from aparatur left join levelbaru on levelbaru.level_id=aparatur.level_id where opd_id = '.$this->session->userdata('opd_id').' and aparatur.level_id NOT IN (0,18,19) order by levelbaru.level_id ASC')->result();	
		$data['perjalanan'] = $this->perjalanan_model->edit_data($this->uri->segment(4), $this->session->userdata('opd_id'))->result();
		
		$this->load->view('template', $data);
	}

	public function update()
	{
		$id = $this->input->post('id');
		$datapengikut=implode(",",$this->input->post('pengikut_id'));

		//pengiriman surat internal atau eksternal
		$jabatan_id = $this->input->post('jabatan_id');
		$eksternal_id = $this->input->post('eksternal_id');
		
        	if (!empty($jabatan_id) OR !empty($eksternal_id)) {
			internal_eksternal('perjalanan',$id,$jabatan_id,$eksternal_id);
		}
		
		//pengiriman tembusan internal atau eksternal
		$tembusan_id = $this->input->post('tembusan_id');
		$tembusaneks_id = $this->input->post('tembusaneks_id');

			if (!empty($tembusan_id) OR !empty($tembusaneks_id)) {
			internal_eksternal_tembusan('perjalanan',$id,$tembusan_id,$tembusaneks_id);
		}

		$data = array(
			'kodesurat_id' => $this->input->post('kodesurat_id'), 
			'tanggal' => htmlentities($this->input->post('tanggal')),
			'pegawai_id' => $this->input->post('pegawai_id'),
			'tingkat_biaya' => $this->input->post('tingkat_biaya'),
			'maksud_perjalanan' => $this->input->post('maksud_perjalanan'),
			'alat_angkutan' => $this->input->post('alat_angkutan'),
			'tmpt_berangkat' => $this->input->post('tmpt_berangkat'),
			'tmpt_tujuan' => $this->input->post('tmpt_tujuan'),
			'lama_perjalanan' => $this->input->post('lama_perjalanan'),
			'tgl_berangkat' => $this->input->post('tgl_berangkat'),
			'tgl_pulang' => $this->input->post('tgl_pulang'),
			'pengikut_id' => $datapengikut,
			'perangkatdaerah_id' => $this->input->post('perangkatdaerah_id'),
			'keterangan' => $this->input->post('keterangan'),
			'catatan' => $this->input->post('catatan'),
		);
		$where = array('id' => $id);
		$update = $this->perjalanan_model->update_data('surat_perjalanan', $data, $where);
		if ($update) {

			$datadraft = array( 
				'tanggal' => $this->input->post('tanggal'),
			);
			$wheredraft = array('surat_id' => $id);
			$this->perjalanan_model->update_data('draft', $datadraft, $wheredraft);
			
			$this->session->set_flashdata('success', 'Surat Berhasil Diedit');
			
			$pembuatSurat = $this->db->get_where('draft', array('surat_id' => $id))->row_array();

			if ($pembuatSurat['dibuat_id'] == $this->session->userdata('jabatan_id')) {
				redirect(site_url('suratkeluar/perjalanan'));
			}else{
				redirect(site_url('suratkeluar/draft'));
			}

		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Diedit');
			redirect(site_url('suratkeluar/perjalanan/edit/'.$id));
		}
	}

	public function delete()
	{
		$where = array('id' => $this->uri->segment(4));
		$delete = $this->perjalanan_model->delete_data('surat_perjalanan', $where);
		if ($delete) {
			$whereDis = array('surat_id' => $this->uri->segment(4));
			$this->perjalanan_model->delete_data('draft', $whereDis);
			$this->perjalanan_model->delete_data('verifikasi', $whereDis);
			$this->perjalanan_model->delete_data('disposisi_suratkeluar', $whereDis);
			$this->perjalanan_model->delete_data('tembusan_surat', $whereDis);
			
			$this->session->set_flashdata('success', 'Surat Berhasil Dihapus');
			redirect(site_url('suratkeluar/perjalanan'));
		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Dihapus');
			redirect(site_url('suratkeluar/perjalanan'));
		}
	}

	public function delete_kepada()
	{
		$surat_id = $this->uri->segment(4);
		$id = $this->uri->segment(5);
		$where = array('dsuratkeluar_id' => $id);
		$delete = $this->perjalanan_model->delete_data('disposisi_suratkeluar', $where);
		if ($delete) {
			$this->session->set_flashdata('success', 'Data Berhasil Dihapus');
			redirect(site_url('suratkeluar/perjalanan/edit/'.$surat_id));
		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Dihapus');
			redirect(site_url('suratkeluar/perjalanan/edit/'.$surat_id));
		}
	}

	public function delete_tembusan()
	{
		$surat_id = $this->uri->segment(4);
		$id = $this->uri->segment(5);
		$where = array('tembusansurat_id' => $id);
		$delete = $this->perjalanan_model->delete_data('tembusan_surat', $where);
		if ($delete) {
			$this->session->set_flashdata('success', 'Data Tembusan Berhasil Dihapus');
			redirect(site_url('suratkeluar/perjalanan/edit/'.$surat_id));
		}else{
			$this->session->set_flashdata('error', 'Data Tembusan Gagal Dihapus');
			redirect(site_url('suratkeluar/perjalanan/edit/'.$surat_id));
		}
	}
}