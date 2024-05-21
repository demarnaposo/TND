<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inbox extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if (empty($this->session->userdata('login'))) {
			$this->session->set_flashdata('access', 'Anda harus login terlebih dahulu!');
			redirect(site_url());
		}
		$this->load->model('inbox_model');
		$this->load->library("pagination");
	}

	public function index()
	{
		$level = $this->session->userdata('level');
		$tahun = $this->session->userdata('tahun');
		$opd_id = $this->session->userdata('opd_id');
		$jabatan_id = $this->session->userdata('jabatan_id');
		$namajabatan= $this->session->userdata('nama_jabatan');
		$users_id=$this->session->userdata('users_id');
		
		// search
		if($this->input->post('submit')){
			$search['cari']=$this->input->post('cari');
			$this->session->userdata('cari',$search['cari']);
			}else{
				$search['cari']=$this->session->userdata("cari");
			}
		
		// config pagination
		$config['base_url']=base_url("suratmasuk1/inbox/index/");
		$config['total_rows']=$this->inbox_model->countdsuratmasuk($jabatan_id,$tahun);
		$data['total_rows']=$config['total_rows'];
		$config['per_page']=10;

		// initialize
		$this->pagination->initialize($config);

		// get data table !=0
		if($this->uri->segment(4) == null){
			$data['start']=0;
		}else{
			$data['start']=$this->uri->segment(4);
		}

		if ($level == 4 || $level == 18) {
			$data['content'] = 'inbox_tu';
			$data['inbox'] = $this->inbox_model->get_disposisisurat($jabatan_id,$tahun)->result();
		}else{
			$data['content'] = 'inbox_aparatur';
			$data['inbox'] = $this->inbox_model->get_disposisi($search['cari'], $config['per_page'],$data['start'],$jabatan_id,$tahun)->result();
			// Select Option Untuk Kepala Dinas,Kepala Camat, Kepala Lurah
			if(substr($namajabatan,0,13) == 'Kepala Bagian'){
				$data['aparatur'] = $this->inbox_model->get_bawahan($jabatan_id,$opd_id)->result();
			// Select Option Untuk SEKDA
			}elseif(substr($namajabatan,0,12) == 'Kepala Dinas' || substr($namajabatan,0,12) == 'Kepala Badan' || substr($namajabatan,0,5) == 'Lurah' || substr($namajabatan,0,5) == 'Camat'){
				$data['aparatur'] = $this->inbox_model->get_global($opd_id)->result();
			// }elseif($users_id == '1414' || $users_id == '2131'){
			// 	$data['aparatur'] = $this->inbox_model->get_aparaturglobal()->result(); 
			// Select Option Untuk SEKDA
			}else{
				$data['opd'] = $this->db->query("SELECT *,jabatan.atasan_id FROM opd JOIN aparatur ON opd.opd_id = aparatur.opd_id JOIN jabatan ON aparatur.jabatan_id = jabatan.jabatan_id JOIN users ON aparatur.aparatur_id = users.aparatur_id WHERE users.level_id = 4 OR users.level_id = 18 AND opd.opd_id != 4 ORDER BY nama_pd ASC")->result();
				$data['aparatur'] = $this->inbox_model->get_bawahan($jabatan_id,$opd_id)->result();
			}
		}
		
		$this->load->view('template', $data);
	}

	public function surattnd(){
		$tahun = $this->session->userdata('tahun');
		$opd_id = $this->session->userdata('opd_id');
		$jabatan_id = $this->session->userdata('jabatan_id');

		$data['content'] = 'surattnd_tu';
		$data['surattnd'] = $this->inbox_model->get_surattnd($jabatan_id,$tahun,$opd_id)->result();

		$this->load->view('template', $data);
	}
	
	public function sudahdisposisi()
	{	
		$tahun = $this->session->userdata('tahun');
		$jabatan_id = $this->session->userdata('jabatan_id');
		// search
		if($this->input->post('submit')){
			$search['cari']=$this->input->post('cari');
			$this->session->userdata('cari',$search['cari']);
			}else{
				$search['cari']=$this->session->userdata("cari");
			}
		
		// config pagination
		$config['base_url']=base_url("suratmasuk1/inbox/sudahdisposisi/");
		$config['total_rows']=$this->inbox_model->countsudahdisposisi($search['cari'],$jabatan_id,$tahun);
		$data['total_rows']=$config['total_rows'];
		$config['per_page']=10;

		// initialize
		$this->pagination->initialize($config);

		// get data table !=0
		if($this->uri->segment(4) == null){
			$data['start']=0;
		}else{
			$data['start']=$this->uri->segment(4);
		}

		$level = $this->session->userdata('level');
		$tahun = $this->session->userdata('tahun');
		$opd_id = $this->session->userdata('opd_id');
		$jabatan_id = $this->session->userdata('jabatan_id');

		$data['content'] = 'sudah_disposisi';
		if($level == 6){
			$data['inbox'] = $this->inbox_model->get_sudahdisposisiseknas($search['cari'], $config['per_page'],$data['start'],$jabatan_id,$tahun)->result();
		}else{
			$data['inbox'] = $this->inbox_model->get_sudahdisposisi($search['cari'], $config['per_page'],$data['start'],$jabatan_id,$tahun)->result();
		}

		$this->load->view('template', $data);
	}

	public function disposisi()
	{
		$opd = $this->session->userdata('opd_id');
		$level = $this->session->userdata('level');
		if (isset($_POST['disposisi'])) {

			if (empty($this->input->post('harap'))) {
				$harap = '';
			}else{
				$harap = implode(',', $this->input->post('harap'));
			}

			$aparatur_id = implode(',', $this->input->post('aparatur_id'));
			$explodeAparatur = explode(',', $aparatur_id);
			$datenow=date("Y-m-d");
			$dataAparatur = array();
			$index = 0;
			foreach ($explodeAparatur as $key => $h) {
				array_push($dataAparatur, array(
					'suratmasuk_id' => $this->input->post('suratmasuk_id'), 
					'aparatur_id' => $h, 
					'users_id' => $this->input->post('users_id'), 
					'harap' => 	$harap, 
					'keterangan' => $this->input->post('keterangan'), 
					'tanggal' => $this->input->post('tanggal'),
					'tanggal_verifikasi' => htmlentities($datenow),
					));
				$index++;
			}

			if ($level == 5) {
				$aparatur = $this->db->query("SELECT * FROM users a LEFT JOIN aparatur b ON b.aparatur_id=a.aparatur_id WHERE b.jabatan_id IN ($aparatur_id) ORDER BY a.level_id ASC")->result_array();
			
				if ($aparatur[0]['level_id'] != 6) {
			
					$sekdis = $this->db->query("SELECT * FROM aparatur 
						JOIN opd ON aparatur.opd_id=opd.opd_id 
						JOIN users ON aparatur.aparatur_id=users.aparatur_id 
						JOIN level ON users.level_id=level.level_id
						WHERE opd.opd_id='$opd' AND level.level_id = 6")->row_array();
						$dataAtasan = array(
							'suratmasuk_id' => $this->input->post('suratmasuk_id'), 
							'aparatur_id' => $sekdis['jabatan_id'], 
							'users_id' => $this->input->post('users_id'), 
							'harap' => 	$harap, 	
							'keterangan' => $this->input->post('keterangan'), 
							'tanggal' => htmlentities($datenow),
							'tanggal_verifikasi' => htmlentities($datenow),
							'status' => 'Selesai Disposisi',
						);	
						$this->inbox_model->insert_data('disposisi_suratmasuk', $dataAtasan);			
				}else{
					$aparatur_id = implode(',', $this->input->post('aparatur_id'));
					$explodeAparatur = explode(',', $aparatur_id);
					$datenow=date("Y-m-d");
					$dataAparatur = array();
					$index = 0;
					foreach ($explodeAparatur as $key => $h) {
						array_push($dataAparatur, array(
							'suratmasuk_id' => $this->input->post('suratmasuk_id'), 
							'aparatur_id' => $h, 
							'users_id' => $this->input->post('users_id'), 
							'harap' => 	$harap, 
							'keterangan' => $this->input->post('keterangan'), 
							'tanggal' => $this->input->post('tanggal'),
							'tanggal_verifikasi' => htmlentities($datenow),
							));
						$index++;
					}
					
				}
			}
			$suratmasukid=$this->input->post('suratmasuk_id');
			$aparaturid=$this->session->userdata('jabatan_id');
			$cekdisposisi=$this->db->query("select * from disposisi_suratmasuk a where a.suratmasuk_id='$suratmasukid' and a.users_id='$aparaturid' and a.status ='Belum Selesai'")->num_rows();
			if($cekdisposisi==0){
				$dispos = $this->inbox_model->insert_aparatur('disposisi_suratmasuk', $dataAparatur);
				if ($dispos) {

					//update status
					$this->inbox_model->update_data('disposisi_suratmasuk', array('status' => 'Selesai Disposisi'), array('dsuratmasuk_id' => $this->input->post('dsuratmasuk_id')));

					$this->session->set_flashdata('success', 'Surat Berhasil Didisposisikan');
					redirect(site_url('suratmasuk1/inbox/detaildata/'.$suratmasukid));
				}else{
					$this->session->set_flashdata('error', 'Surat Gagal Didisposisikan');
					redirect(site_url('suratmasuk1/inbox/detaildata/'.$suratmasukid));
				}
			}else{
				$this->session->set_flashdata('error','Surat Sudah Pernah Dididsposisikan');
				redirect(site_url('suratmasuk1/inbox/detaildata/'.$suratmasukid));
			}

		}elseif (isset($_POST['selesai'])){
            $tanggal=date('Y-m-d');
			$data = array(
			    'catatan'=> $this->input->post('catatan'),
			    'tanggal' => $tanggal,
			    'status' => 'Selesai'
			    );
			$where = array('dsuratmasuk_id' => $this->input->post('dsuratmasuk_id'));
			$update = $this->inbox_model->update_data('disposisi_suratmasuk', $data, $where);

			if ($update) {
				$this->session->set_flashdata('success', 'Surat Berhasil Diselesaikan');
				redirect(site_url('suratmasuk1/inbox/detaildata/'.$suratmasukid));
			}else{
				$this->session->set_flashdata('error', 'Surat Gagal Diselesaikan');
				redirect(site_url('suratmasuk1/inbox/detaildata/'.$suratmasukid));
			}
		}elseif (isset($_POST['kembalikan'])){
			$jabatanid=$this->session->userdata('jabatan_id');
			$suratmasukid=$this->input->post('suratmasuk_id');
			$kembalikan=$this->db->get_where('surat_masuk',array('suratmasuk_id'=>$this->input->post('suratmasuk_id')))->row_array();
			$statusdikembalikan='Dikembalikan';
			$datenow=date("Y-m-d");
			$data=array(
				'suratmasuk_id'=>$suratmasukid,
				'aparatur_id'=>$kembalikan['dibuat_id'],
				'users_id'=>$jabatanid,
				'harap'=>'',
				'keterangan'=>$this->input->post('keterangan'),
				'tanggal' => htmlentities($datenow),
				'tanggal_verifikasi' => htmlentities($datenow),
				'status'=>$statusdikembalikan,
			);
			$where=array('suratmasuk_id' => $suratmasukid,'aparatur_id' =>$jabatanid);
			$kembali=$this->inbox_model->update_data('disposisi_suratmasuk',$data,$where);
			if ($kembali) {
				$this->session->set_flashdata('success', 'Surat Berhasil Dikembalikan');
				redirect(site_url('suratmasuk1/inbox/detaildata/'.$suratmasukid));
			}else{
				$this->session->set_flashdata('error', 'Surat Gagal Dikembalikan');
				redirect(site_url('suratmasuk1/inbox/detaildata/'.$suratmasukid));
			}
		}else{

			$jabatan = $this->db->get_where('jabatan', array('jabatan_id' => $this->session->userdata('jabatan_id')))->row_array();
			if ($jabatan['atasan_id'] == 0) {
				$getBawahan = $this->db->get_where('jabatan', array('atasan_id' => $this->session->userdata('jabatan_id')))->row_array();
				$getAtasan = $this->db->get_where('aparatur', array('jabatan_id' => $getBawahan['jabatan_id']))->row_array();
			}else{
				$getAtasan = $this->db->get_where('aparatur', array('jabatan_id' => $jabatan['atasan_id']))->row_array();
			}
			$suratmasukid=$this->input->post('suratmasuk_id');
			$aparaturid=$this->session->userdata('jabatan_id');
			$cekdisposisi=$this->db->query("select * from disposisi_suratmasuk a where a.suratmasuk_id='$suratmasukid' and a.users_id='$aparaturid' and a.status ='Belum Selesai'")->num_rows();
			if($cekdisposisi==0){
				$harap = implode(',', $this->input->post('harap'));
				$datenow=date("Y-m-d");
				$data = array(
					'suratmasuk_id' => $this->uri->segment(4), 
					'aparatur_id' => $getAtasan['jabatan_id'], 
					'users_id' => $this->session->userdata('jabatan_id'), 
					'harap' => 	'', 
					'keterangan' => '', 
					'tanggal' => htmlentities($datenow),
					'tanggal_verifikasi' => htmlentities($datenow),
				);
				
				$dispos = $this->inbox_model->insert_data('disposisi_suratmasuk', $data);
				if ($dispos) {
					$this->inbox_model->update_data('disposisi_suratmasuk', array('status' => 'Selesai Disposisi'), array('dsuratmasuk_id' => $this->input->post('dsuratmasuk_id')));
					$this->session->set_flashdata('success', 'Surat Berhasil Didisposisikan');
					redirect(site_url('suratmasuk1/inbox/detaildata/'.$suratmasukid));
				}else{
					$this->session->set_flashdata('error', 'Surat Gagal Didisposisikan');
					redirect(site_url('suratmasuk1/inbox/detaildata/'.$suratmasukid));
				}
			}else{
				$this->session->set_flashdata('error','Surat Sudah Pernah Dididsposisikan');
				redirect(site_url('suratmasuk1/inbox/detaildata/'.$suratmasukid));
			}
		}
	}

    public function detaildata(){
		$level = $this->session->userdata('level');
		$tahun = $this->session->userdata('tahun');
		$opd_id = $this->session->userdata('opd_id');
		$jabatan_id = $this->session->userdata('jabatan_id');
		$namajabatan= $this->session->userdata('nama_jabatan');
		$users_id=$this->session->userdata('users_id');

		if ($level == 4 || $level == 18) {
			$data['content'] = 'inbox_tu';
			$data['inbox'] = $this->inbox_model->get_disposisisurat($jabatan_id,$tahun)->result();
		}else{
			$data['content'] = 'detaildata_aparatur';
			$data['cekdisposisi']=$this->db->query("SELECT *,surat_masuk.tanggal as tglsurat, surat_masuk.lampiran_lain,surat_masuk.lampiran FROM disposisi_suratmasuk
			JOIN surat_masuk ON surat_masuk.suratmasuk_id = disposisi_suratmasuk.suratmasuk_id
			JOIN aparatur ON aparatur.jabatan_id = disposisi_suratmasuk.aparatur_id
			WHERE disposisi_suratmasuk.status = 'Belum Selesai'
			AND disposisi_suratmasuk.aparatur_id ='$jabatan_id'
			GROUP BY disposisi_suratmasuk.suratmasuk_id
			ORDER BY surat_masuk.diterima DESC, LENGTH(surat_masuk.suratmasuk_id) DESC, disposisi_suratmasuk.dsuratmasuk_id DESC")->result();
			// Select Option Untuk Kepala Dinas,Kepala Camat, Kepala Lurah
			if(substr($namajabatan,0,13) == 'Kepala Bagian'){
				$data['aparatur'] = $this->inbox_model->get_bawahan($jabatan_id,$opd_id)->result();
			// Select Option Untuk SEKDA
			}elseif(substr($namajabatan,0,12) == 'Kepala Dinas' || substr($namajabatan,0,12) == 'Kepala Badan' || substr($namajabatan,0,5) == 'Lurah' || substr($namajabatan,0,5) == 'Camat'){
				$data['aparatur'] = $this->inbox_model->get_global($opd_id)->result();
			// }elseif($users_id == '1414' || $users_id == '2131'){
			// 	$data['aparatur'] = $this->inbox_model->get_aparaturglobal()->result(); 
			// Select Option Untuk SEKDA
			}else{
				$data['opd'] = $this->db->query("SELECT *,jabatan.atasan_id FROM opd JOIN aparatur ON opd.opd_id = aparatur.opd_id JOIN jabatan ON aparatur.jabatan_id = jabatan.jabatan_id JOIN users ON aparatur.aparatur_id = users.aparatur_id WHERE users.level_id = 4 OR users.level_id = 18 AND opd.opd_id != 4 ORDER BY nama_pd ASC")->result();
				$data['aparatur'] = $this->inbox_model->get_bawahan($jabatan_id,$opd_id)->result();
			}
		}
		// $data['opd'] = $this->db->query("SELECT *,jabatan.atasan_id FROM opd JOIN aparatur ON opd.opd_id = aparatur.opd_id JOIN jabatan ON aparatur.jabatan_id = jabatan.jabatan_id JOIN users ON aparatur.aparatur_id = users.aparatur_id WHERE users.level_id = 4 OR users.level_id = 18 AND opd.opd_id != 4 ORDER BY nama_pd ASC")->result();
		// $data['aparatur'] = $this->inbox_model->get_bawahan($jabatan_id)->result();
        // $data['content'] = 'detaildata_aparatur';
        $this->load->view('template', $data);
    }

    public function detaildatariwayat(){
		$data['opd'] = $this->db->query("SELECT *,jabatan.atasan_id FROM opd JOIN aparatur ON opd.opd_id = aparatur.opd_id JOIN jabatan ON aparatur.jabatan_id = jabatan.jabatan_id JOIN users ON aparatur.aparatur_id = users.aparatur_id WHERE users.level_id = 4 OR users.level_id = 18 AND opd.opd_id != 4 ORDER BY nama_pd ASC")->result();
		$data['aparatur'] = $this->inbox_model->get_bawahan($jabatan_id)->result();
        $data['content'] = 'detaildata_riwayat';
        $this->load->view('template', $data);
    }
    
	public function selesai()
	{
		
		$level = $this->session->userdata('level');

		if ($level == 4) {
			$data['content'] = 'selesai_tu';
			$data['selesai'] = $this->inbox_model->get_selesai_tu($this->session->userdata('tahun'),$this->session->userdata('jabatan_id'))->result();
		}else{
			$data['content'] = 'selesai_aparatur';
			$data['selesai'] = $this->inbox_model->get_selesai_aparatur()->result();
			$data['tahun'] = $this->session->userdata('tahun');
			$data['opd_id'] = $this->session->userdata('opd_id');
			$data['jabatanid'] = $this->session->userdata('jabatan_id');
		}

		$this->load->view('template', $data);
	}

}