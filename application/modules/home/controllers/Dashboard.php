<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if (empty($this->session->userdata('login'))) {
			$this->session->set_flashdata('access', 'Anda harus login terlebih dahulu!');
			redirect(site_url());
		}
		$this->load->model('dashboard_model');
	}

	public function index()
	{
		$data['content'] = 'dashboard';
		$data['messages'] = messages();

		$tahun = $this->session->userdata('tahun');
		$jabatan_id = $this->session->userdata('jabatan_id');
		$opd_id = $this->session->userdata('opd_id');

		$informasi = $this->dashboard_model->informasi()->row_array();

		if($this->session->userdata('level') == 1){

			$data['pengajuansurat'] = $this->dashboard_model->draft_administrator($tahun)->num_rows();
			$data['suratkeluar'] = $this->dashboard_model->suratkeluar_administrator($tahun)->num_rows();
			$data['suratmasuk'] = $this->dashboard_model->suratmasuk_administrator($tahun)->num_rows();
            // Update @Mpik Egov 21/07/2022	
            $data['listopd']=$this->db->query("SELECT * FROM opd LEFT JOIN urutan_opd ON urutan_opd.urutan_id=opd.urutan_id WHERE opd.statusopd='Aktif' AND opd.urutan_id !=0 GROUP BY opd.opd_id ORDER BY urutan_opd.urutan_id ASC")->result();
            $data['listopd1']=$this->db->query("SELECT * FROM opd LEFT JOIN urutan_opd ON urutan_opd.urutan_id=opd.urutan_id WHERE opd.statusopd='Aktif' AND opd.urutan_id !=0 GROUP BY opd.opd_id ORDER BY urutan_opd.urutan_id ASC")->result();
            $data['listopd2']=$this->db->query("SELECT * FROM opd LEFT JOIN urutan_opd ON urutan_opd.urutan_id=opd.urutan_id WHERE opd.statusopd='Aktif' AND opd.urutan_id !=0 GROUP BY opd.opd_id ORDER BY urutan_opd.urutan_id ASC")->result();
            // $data['jmlsuratmasuk']=$this->db->query("SELECT opd.kode_pd, SUM(YEAR(surat_masuk.tanggal)='$tahun') as jmlsuratmasuk FROM opd
            //                         LEFT JOIN surat_masuk ON surat_masuk.opd_id=opd.opd_id
            //                         LEFT JOIN urutan_opd ON urutan_opd.urutan_id=opd.urutan_id
            //                         WHERE opd.statusopd='Aktif' AND opd.urutan_id != 0
            //                         GROUP BY opd.opd_id
            //                         ORDER BY urutan_opd.urutan_id ASC")->result();
            // $data['jmlsuratkeluar']=$this->db->query("SELECT opd.opd_id, opd.kode_pd FROM opd 
            //                         LEFT JOIN urutan_opd ON urutan_opd.urutan_id=opd.urutan_id
            //                         WHERE opd.urutan_id !=0 AND opd.statusopd='Aktif'
            //                         ORDER BY urutan_opd.urutan_id ASC")->result();
			// 			
            // END
            // Update @Mpik Egov 21/07/2022	
		}elseif($this->session->userdata('level') == 4 || $this->session->userdata('level') == 18){

			$data['pengajuansurat'] = $this->dashboard_model->draft($jabatan_id,$tahun)->num_rows();
// 			$data['suratmasuk'] = $this->dashboard_model->suratmasuk_tu($tahun,$jabatan_id)->result();
			$data['didisposisikan'] = $this->dashboard_model->suratmasuk_tu_didisposisikan($opd_id,$tahun)->result();
			// $data['countsuratmasuk'] = $this->db->query("SELECT surat_id FROM disposisi_suratkeluar WHERE users_id = '$jabatan_id' AND status ='selesai'")->num_rows();
			$data['suratmasuk'] = $this->dashboard_model->suratmasuk_disposisi($jabatan_id,$tahun)->num_rows();
			$data['tanggal'] = $informasi['tanggal'];
			$data['deskripsi'] = $informasi['deskripsi'];
			$data['disposisi'] = $this->dashboard_model->disposisi_surat($tahun,$jabatan_id)->num_rows();
			$data['draft'] = $this->dashboard_model->draft($jabatan_id,$tahun)->num_rows();
			$data['tandatangan'] = $this->dashboard_model->tandatangan($jabatan_id)->num_rows();
			$data['surattnd'] = $this->db->query("SELECT * FROM surat_masuk WHERE lampiran REGEXP 's|n|r|l' AND opd_id ='$opd_id' AND LEFT(surat_masuk.tanggal, 4) = '$tahun'")->num_rows();
			$data['suratsudahtte'] = $this->db->query("SELECT * FROM penandatangan LEFT JOIN draft ON draft.surat_id=penandatangan.surat_id LEFT JOIN jabatan ON jabatan.jabatan_id=penandatangan.jabatan_id WHERE opd_id='$opd_id' AND penandatangan.status='Sudah Ditandatangani' AND LEFT(draft.tanggal,4)='$tahun'")->num_rows();
			$data['pengarsipan'] = $this->db->query("SELECT * FROM pengarsipan
                                    LEFT JOIN draft ON draft.surat_id=pengarsipan.surat_id
                                    LEFT JOIN jabatan ON jabatan.jabatan_id=draft.dibuat_id
                                    WHERE jabatan.opd_id='$opd_id' AND LEFT(draft.tanggal,4)='$tahun'")->num_rows();
            // Update @Mpik Egov 19 Juli 2022
            // Query jenis - jenis surat keluar
            $data['suratinstruksi'] = $this->db->query("SELECT * FROM surat_instruksi WHERE opd_id = '$opd_id' AND LEFT(tanggal,4)='$tahun'")->num_rows();
            $data['suratedaran'] = $this->db->query("SELECT * FROM surat_edaran WHERE opd_id = '$opd_id' AND LEFT(tanggal,4)='$tahun'")->num_rows();
            $data['suratbiasa'] = $this->db->query("SELECT * FROM surat_biasa WHERE opd_id = '$opd_id' AND LEFT(tanggal,4)='$tahun'")->num_rows();
            $data['suratundangan'] = $this->db->query("SELECT * FROM surat_undangan WHERE opd_id = '$opd_id' AND LEFT(tanggal,4)='$tahun'")->num_rows();
            $data['suratketerangan'] = $this->db->query("SELECT * FROM surat_keterangan WHERE opd_id = '$opd_id' AND LEFT(tanggal,4)='$tahun'")->num_rows();
            $data['suratperintah'] = $this->db->query("SELECT * FROM surat_perintah WHERE opd_id = '$opd_id' AND LEFT(tanggal,4)='$tahun'")->num_rows();
            $data['suratperintahtugas'] = $this->db->query("SELECT * FROM surat_perintahtugas WHERE opd_id = '$opd_id' AND LEFT(tanggal,4)='$tahun'")->num_rows();
            $data['suratnotadinas'] = $this->db->query("SELECT * FROM surat_notadinas WHERE opd_id = '$opd_id' AND LEFT(tanggal,4)='$tahun'")->num_rows();
            $data['suratlampiran'] = $this->db->query("SELECT * FROM surat_lampiran WHERE opd_id = '$opd_id' AND LEFT(tanggal,4)='$tahun'")->num_rows();
            $data['suratizin'] = $this->db->query("SELECT * FROM surat_izin WHERE opd_id = '$opd_id' AND LEFT(tanggal,4)='$tahun'")->num_rows();
            $data['suratperjalanandinas'] = $this->db->query("SELECT * FROM surat_perjalanan WHERE opd_id = '$opd_id' AND LEFT(tanggal,4)='$tahun'")->num_rows();
            $data['suratmelaksanakantugas'] = $this->db->query("SELECT * FROM surat_melaksanakantugas WHERE opd_id = '$opd_id' AND LEFT(tanggal,4)='$tahun'")->num_rows();
            $data['suratpanggilan'] = $this->db->query("SELECT * FROM surat_panggilan WHERE opd_id = '$opd_id' AND LEFT(tanggal,4)='$tahun'")->num_rows();
            $data['suratpengumuman'] = $this->db->query("SELECT * FROM surat_pengumuman WHERE opd_id = '$opd_id' AND LEFT(tanggal,4)='$tahun'")->num_rows();
            $data['suratlaporan'] = $this->db->query("SELECT * FROM surat_laporan WHERE opd_id = '$opd_id' AND LEFT(tanggal,4)='$tahun'")->num_rows();
            $data['suratrekomendasi'] = $this->db->query("SELECT * FROM surat_rekomendasi WHERE opd_id = '$opd_id' AND LEFT(tanggal,4)='$tahun'")->num_rows();
            $data['suratpengantar'] = $this->db->query("SELECT * FROM surat_pengantar WHERE opd_id = '$opd_id' AND LEFT(tanggal,4)='$tahun'")->num_rows();
            $data['suratkuasa'] = $this->db->query("SELECT * FROM surat_kuasa WHERE opd_id = '$opd_id' AND LEFT(tanggal,4)='$tahun'")->num_rows();
            $data['suratberitaacara'] = $this->db->query("SELECT * FROM surat_beritaacara WHERE opd_id = '$opd_id' AND LEFT(tanggal,4)='$tahun'")->num_rows();
            $data['suratnotulen'] = $this->db->query("SELECT * FROM surat_notulen WHERE opd_id = '$opd_id' AND LEFT(tanggal,4)='$tahun'")->num_rows();
            $data['suratmemo'] = $this->db->query("SELECT * FROM surat_memo WHERE opd_id = '$opd_id' AND LEFT(tanggal,4)='$tahun'")->num_rows();
            $data['suratkeputusan'] = $this->db->query("SELECT * FROM surat_keputusan WHERE opd_id = '$opd_id' AND LEFT(tanggal,4)='$tahun'")->num_rows();
            $data['suratlainnya'] = $this->db->query("SELECT * FROM surat_lainnya WHERE opd_id = '$opd_id' AND LEFT(tanggal,4)='$tahun'")->num_rows();
            // END 
            // Update @Mpik Egov 19 Juli 2022

		}else{
            
			$data['pengajuansurat'] = $this->dashboard_model->pengajuansurat($jabatan_id,$tahun)->num_rows();
			$data['suratmasuk'] = $this->dashboard_model->suratmasuk_disposisi($jabatan_id,$tahun)->num_rows();
			$data['draft'] = $this->dashboard_model->draft($jabatan_id,$tahun)->num_rows();
			$data['tandatangan'] = $this->dashboard_model->tandatangan($jabatan_id)->num_rows();
			$data['tanggal'] = $informasi['tanggal'];
			$data['deskripsi'] = $informasi['deskripsi'];

		}

		$this->load->view('template', $data);
	}

}
