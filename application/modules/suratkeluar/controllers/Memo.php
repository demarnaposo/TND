<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Memo extends CI_Controller {

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
		$this->load->model('memo_model');
	}

	public function index()
	{
		$data['content'] = 'memo/memo_index';
		
		$tahun = $this->session->userdata('tahun');
		$opd_id = $this->session->userdata('opd_id');
		$jabatan_id = $this->session->userdata('jabatan_id');

		$data['memo'] = $this->memo_model->get_data($tahun,$opd_id,$jabatan_id)->result();
		
		$this->load->view('template', $data);
	}

	public function add()
	{
		$data['content'] = 'memo/memo_form';
		$data['kop'] = $this->db->get('kop_surat')->result();
		$data['jabatan'] = $this->db->get('jabatan')->result();
		$data['kodesurat'] = $this->db->get('kode_surat')->result();
		
		$this->load->view('template', $data);
	}

	public function insert()
	{
		$kopId=$this->input->post('kop_id');
		//untuk menambahkan data eksternal
		if (isset($_POST['simpan'])) {
			
			$getEksID = $this->memo_model->get_id('eksternal_keluar')->result();
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
			);
			$insert = $this->memo_model->insert_data('eksternal_keluar', $data);

			if ($insert) {
				$this->session->set_flashdata('success', 'Eksternal Berhasil Dibuat');
				redirect(site_url('suratkeluar/memo/add'));
			}else{
				$this->session->set_flashdata('error', 'Eksternal Gagal Dibuat');
				redirect(site_url('suratkeluar/draft/add/'));
			}

		//Untuk menambahkan surat
		}else{

			$getID = $this->memo_model->get_id('surat_memo')->result();
			foreach ($getID as $key => $h) {
				$id = $h->id;
			}
			if (empty($id)) {
				$surat_id = 'MMO-1';
			}else{
				$urut = substr($id, 4)+1;
				$surat_id = 'MMO-'.$urut;
			}
			
			$data = array(
				'id' => $surat_id,
				'opd_id' => $this->session->userdata('opd_id'),
				'kop_id' => $this->input->post('kop_id'), 
				'kodesurat_id' => $this->input->post('kodesurat_id'), 
				'tanggal' => htmlentities($this->input->post('tanggal')),
				'kepada_id' => $this->input->post('kepada_id'),
				'dari_id' => $this->input->post('dari_id'),
				'isi' => $this->input->post('isi'),
				'catatan' => $this->input->post('catatan'),
			);
			//form validation memo [@dam|E-Gov 17.04.2022]
			$this->load->library('form_validation');	
				
			$rules =
			    [
			        [
			        'field' => 'kop_id',  
			        'label' => 'Kop Surat',
			        'rules' => 'required'],
				        
			        [
			        'field' => 'kepada_id',  
			        'label' => 'Kepada/Tujuan Memo',
			        'rules' => 'required'],
				        
			        [
			        'field' => 'dari_id',  
			        'label' => 'Dari/Asal Memo',
			        'rules' => 'required'],
				        
			        [
			        'field' => 'isi',  
			        'label' => 'Isi Surat',
			        'rules' => 'required']
			    ];
			$this->form_validation->set_rules($rules);
			$this->form_validation->set_message('required', '%s masih kosong, silakan diisi');				

			if($this->form_validation->run()===FALSE) {
				
			//set value
			$this->session->set_flashdata('value_kop_id', set_value('kop_id', kop_id));
			$this->session->set_flashdata('value_tujuan', set_value('', '*Silakan pilih kembali tujuan internal-eksternal Pemkot'));
			$this->session->set_flashdata('value_kepada_id', set_value('kepada_id', kepada_id));
			$this->session->set_flashdata('value_dari_id', set_value('dari_id', dari_id)); 
			$this->session->set_flashdata('value_isi', set_value('isi', isi));
			$this->session->set_flashdata('value_catatan', set_value('catatan', catatan));
			
			//error
			$this->session->set_flashdata('kop_id', form_error('kop_id'));
			$this->session->set_flashdata('kepada_id', form_error('kepada_id'));
			$this->session->set_flashdata('dari_id', form_error('dari_id'));
			$this->session->set_flashdata('isi', form_error('isi'));
			
			redirect(site_url('suratkeluar/memo/add'));
			
			}
			else {

			$insert = $this->memo_model->insert_data('surat_memo', $data);
			if ($insert) {

				//pengiriman surat internal atau eksternal
    			$jabatan_id = $this->input->post('jabatan_id');
                $eksternal_id = $this->input->post('eksternal_id');
    			
    			//proses input internal atau eksternal
    			internal_eksternal('memo',$surat_id,$jabatan_id,$eksternal_id);
    			
    			//pengiriman tembusan internal atau eksternal
    	    	$tembusan_id = $this->input->post('tembusan_id');
    	    	$tembusaneks_id = $this->input->post('tembusaneks_id');
    			
    	    	//proses input tembusan internal atau eksternal
    	    	internal_eksternal_tembusan('memo',$surat_id,$tembusan_id,$tembusaneks_id);

				$datadraft = array(
					'surat_id' => $surat_id,
					'kopId' => $kopId,
					'tanggal' => htmlentities($this->input->post('tanggal')), 
					'dibuat_id' => $this->session->userdata('jabatan_id'), 
					'penandatangan_id' => '',
					'verifikasi_id' => '', 
					'nama_surat' => 'Surat Memo', 
				);
				$this->memo_model->insert_data('draft', $datadraft);

				$this->session->set_flashdata('success', 'Surat Berhasil Dibuat');
				redirect(site_url('suratkeluar/memo'));
			}else{
				$this->session->set_flashdata('error', 'Surat Gagal Dibuat');
				redirect(site_url('suratkeluar/memo/add'));
			}
			}
		}
	}

	public function edit()
	{
		$data['content'] = 'memo/memo_form';
		$data['kop'] = $this->db->get('kop_surat')->result();
		$data['kodesurat'] = $this->db->get('kode_surat')->result();
		$data['jabatan'] = $this->db->get('jabatan')->result();
		$data['memo'] = $this->memo_model->edit_data($this->uri->segment(4), $this->session->userdata('opd_id'))->result();
		
		$this->load->view('template', $data);
	}

	public function update()
	{
		$id = $this->input->post('id');

		//Untuk menambahkan data eksternal 
		if (isset($_POST['simpan'])) {
			
			$getEksID = $this->memo_model->get_id('eksternal_keluar')->result();
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
			);
			$insert = $this->memo_model->insert_data('eksternal_keluar', $data);

			if ($insert) {
				$this->session->set_flashdata('success', 'Eksternal Berhasil Dibuat');
				redirect(site_url('suratkeluar/memo/edit/'.$id));
			}else{
				$this->session->set_flashdata('error', 'Eksternal Gagal Dibuat');
				redirect(site_url('suratkeluar/draft/edit/'.$id));
			}

		//Untuk mengedit surat
		}else{

			//pengiriman surat internal atau eksternal
			$jabatan_id = $this->input->post('jabatan_id');
			$eksternal_id = $this->input->post('eksternal_id');
		
        	if (!empty($jabatan_id) OR !empty($eksternal_id)) {
			internal_eksternal('memo',$id,$jabatan_id,$eksternal_id);
			}

			//pengiriman tembusan internal atau eksternal
		    $tembusan_id = $this->input->post('tembusan_id');
	    	$tembusaneks_id = $this->input->post('tembusaneks_id');

	    	if (!empty($tembusan_id) OR !empty($tembusaneks_id)) {
	    		internal_eksternal_tembusan('memo',$id,$tembusan_id,$tembusaneks_id);
	    	}

			$data = array(
				'kodesurat_id' => $this->input->post('kodesurat_id'), 
				'kop_id' => $this->input->post('kop_id'), 
				'tanggal' => $this->input->post('tanggal'),
				'kepada_id' => $this->input->post('kepada_id'),
				'dari_id' => $this->input->post('dari_id'),
				'isi' => $this->input->post('isi'),
				'catatan' => $this->input->post('catatan'),
			);
			$where = array('id' => $id);
			$update = $this->memo_model->update_data('surat_memo', $data, $where);
			if ($update) {

				$datadraft = array( 
					'tanggal' => $this->input->post('tanggal'),
				);
				$wheredraft = array('surat_id' => $id);
				$this->memo_model->update_data('draft', $datadraft, $wheredraft);
				
				$this->session->set_flashdata('success', 'Surat Berhasil Diedit');

				$pembuatSurat = $this->db->get_where('draft', array('surat_id' => $id))->row_array();

				if ($pembuatSurat['dibuat_id'] == $this->session->userdata('jabatan_id')) {
					redirect(site_url('suratkeluar/memo'));
				}else{
					redirect(site_url('suratkeluar/draft'));
				}

			}else{
				$this->session->set_flashdata('error', 'Surat Gagal Diedit');
				redirect(site_url('suratkeluar/memo/edit/'.$id));
			}

		}
	}

	public function delete()
	{
		$where = array('id' => $this->uri->segment(4));
		$delete = $this->memo_model->delete_data('surat_memo', $where);
		if ($delete) {
			$whereDis = array('surat_id' => $this->uri->segment(4));
			$this->memo_model->delete_data('draft', $whereDis);
			$this->memo_model->delete_data('verifikasi', $whereDis);
			$this->memo_model->delete_data('disposisi_suratkeluar', $whereDis);
			$this->memo_model->delete_data('tembusan_surat', $whereDis);
			
			$this->session->set_flashdata('success', 'Surat Berhasil Dihapus');
			redirect(site_url('suratkeluar/memo'));
		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Dihapus');
			redirect(site_url('suratkeluar/memo'));
		}
	}

	public function delete_kepada()
	{
		$surat_id = $this->uri->segment(4);
		$id = $this->uri->segment(5);
		$where = array('dsuratkeluar_id' => $id);
		$delete = $this->memo_model->delete_data('disposisi_suratkeluar', $where);
		if ($delete) {
			$this->session->set_flashdata('success', 'Data Berhasil Dihapus');
			redirect(site_url('suratkeluar/memo/edit/'.$surat_id));
		}else{
			$this->session->set_flashdata('error', 'Surat Gagal Dihapus');
			redirect(site_url('suratkeluar/memo/edit/'.$surat_id));
		}
	}

	public function delete_tembusan()
	{
		$surat_id = $this->uri->segment(4);
		$id = $this->uri->segment(5);
		$where = array('tembusansurat_id' => $id);
		$delete = $this->memo_model->delete_data('tembusan_surat', $where);
		if ($delete) {
			$this->session->set_flashdata('success', 'Data Tembusan Berhasil Dihapus');
			redirect(site_url('suratkeluar/memo/edit/'.$surat_id));
		}else{
			$this->session->set_flashdata('error', 'Data Tembusan Gagal Dihapus');
			redirect(site_url('suratkeluar/memo/edit/'.$surat_id));
		}
	}

}