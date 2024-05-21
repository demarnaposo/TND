<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Android extends CI_Controller {	
	
	public function tahun()
	{
	    $tahun = date('Y');
	    $tahunAwal = date('Y') - 2; 
	    $arr = array();
	    for ($thn=$tahunAwal; $thn <= $tahun; $thn++) {
	        $arr[] = array("tahun" => $thn);
	    }
	    
	    $response = array(
			    'response_code' => 404,
                'message' => 'Data tahun berhasil diambil',
                'data' => $arr
			);
	    header('Content-type: application/json');
	    echo json_encode($response);
	    
	}
	
	public function login()
	{
	    $username = htmlentities($this->input->post('username'));
	    $pass = htmlentities($this->input->post('password'));
	    $password = sha1($pass);
	    $tahun = htmlentities($this->input->post('tahun'));
	    
	    $data = array(
			'username' => $username, 
			'password' => $password,
		);
		
		$cek = $this->db->get_where('users', $data)->num_rows();
		
		if ($cek > 0) {
			$this->db->select('aparatur.nama, aparatur.nik,opd.nama_pd,users.username, opd.email,aparatur.jabatan_id,jabatan.nama_jabatan,users.foto, users.level_id,aparatur.opd_id,users.users_id,aparatur.statusaparatur,jabatan.jabatan,opd.urutan_id');
			$this->db->from('users');
			$this->db->join('aparatur', 'aparatur.aparatur_id = users.aparatur_id', 'left');
			$this->db->join('opd', 'opd.opd_id = aparatur.opd_id', 'left');
			$this->db->join('jabatan', 'jabatan.jabatan_id = aparatur.jabatan_id', 'left');
			$this->db->where($data);
			$query =  $this->db->get()->result();
    			foreach ($query as $key => $h) {
    				$nama = $h->nama;
    				$nik = $h->nik;
    				$namapd = $h->nama_pd;
    				$username = $h->username;
    				$email = $h->email;
    				$jabatan_id = $h->jabatan_id;
    				$jabatan = $h->jabatan;
    				$nama_jabatan = $h->nama_jabatan;
    				$foto = $h->foto;
    				$level = $h->level_id;
    				$opd_id = $h->opd_id;
    				$users_id = $h->users_id;
    				$status = $h->statusaparatur;
    				$urutanopd = $h->urutan_id;
    			}
			$session = array(
				'login' => 1,
				'tahun' => $tahun,
				'nama' => $nama,
				'nik' => $nik,
				'nama_pd' => $namapd,
				'username' => $username,
				'email' => $email,
				'jabatan_id' => $jabatan_id,
				'nama_jabatan' => $nama_jabatan,
				'jabatan' => $jabatan,
				'foto' => $foto, 
				'level' => $level,
				'opd_id' => $opd_id,
				'users_id' => $users_id,
				'status' => $status,
				'urutan_id' => $urutanopd
				);
			if ($status == "Pensiun"){
				$response = array(
			        'response_code' => 404,
                    'message' => 'Status User Sudah Tidak Aktif',
			    );
			    header('Content-type: application/json');
			    echo json_encode($response);
			}elseif ($status == "Meninggal") {
				$response = array(
			        'response_code' => 404,
                    'message' => 'Status User Sudah Meninggal',
			    );
			    header('Content-type: application/json');
			    echo json_encode($response);
			}elseif ($status == "Tidak Aktif") {
				$response = array(
			        'response_code' => 404,
                    'message' => 'Status User Sudah Tidak Aktif',
			    );
			    header('Content-type: application/json');
			    echo json_encode($response);
			}else{
			    $response = array(
			        'response_code' => 200,
                    'message' => 'Login Berhasil',
                    'data' => $session
			    );
			    header('Content-type: application/json');
			    echo json_encode($response);
			}
		}else{
		    $response = array(
			    'response_code' => 404,
                'message' => 'Status User Sudah Tidak Aktif',
			);
			header('Content-type: application/json');
			echo json_encode($response);
		}
	    
	}
	
	public function saveToken()
	{
	    $users_id = $this->input->post('users_id');
	    $token = $this->input->post('token');
	    
	    if($users_id && $token){
	        $check = array(
    			'users_id' => $users_id
    		);
    		
	        $data = array(
    			'users_id' => $users_id,
    			'token' => $token
    		);
    	    
    	    $cek = $this->db->get_where('token_android', $check)->num_rows();
    	    
    	    if($cek > 0){
    	        $response = array(
    			    'response_code' => 500,
                    'message' => 'token login masih aktif'
    			);
    			
    			header('Content-type: application/json');
    	        echo json_encode($response);
    	    }else{
    	        $insert = $this->db->insert('token_android', $data);
        	    if($insert){
        	         $response = array(
        			    'response_code' => 200,
                        'message' => 'data berhasil dinputkan'
        			);
        			
        			header('Content-type: application/json');
        	        echo json_encode($response);
        	    }else{
        	        $response = array(
        			    'response_code' => 500,
                        'message' => 'data gagal dinputkan'
        			);
        			
        			header('Content-type: application/json');
        	        echo json_encode($response);
        	    }
    	    }
	    }else{
	    
    	    $response = array(
                'response_code' => 500,
                'message' => 'Something wrong!!'
        	);
        	
        	header('Content-type: application/json');
        	echo json_encode($response);
    	
	    }
	    
	}
	
	public function deleteToken()
	{
	    $users_id = $this->input->post('users_id');
	    
	    $where = array('users_id' => $users_id);
		$delete = $this->db->delete('token_android',$where);
		if ($delete) {
			$response = array(
			    'response_code' => 200,
                'message' => 'Token berhasil dihapus'
			);
    	    header('Content-type: application/json');
    	    echo json_encode($response);
		}else{
			$response = array(
			    'response_code' => 500,
                'message' => 'Token gagal dihapus'
			);
    	    header('Content-type: application/json');
    	    echo json_encode($response);
		}
	}
	
	public function jumlahSurat()
	{
	   // $level = $this->input->post('level');
		// 	    if($level == 1){
		// 	        $data['suratkeluar'] = $this->dashboard_model->suratkeluar_administrator($tahun)->num_rows();
		// 			$data['suratmasuk'] = $this->dashboard_model->suratmasuk_administrator($tahun)->num_rows();
		// 	    }elseif($level == 4 || $level == 18){
		// 	    }else{
		// 	    }

        $tahun = $this->input->post('tahun');
		$jabatan_id = $this->input->post('jabatan_id');
        
        $query=$this->db->query("SELECT *,surat_masuk.tanggal as tglsurat FROM disposisi_suratmasuk
		JOIN surat_masuk ON surat_masuk.suratmasuk_id = disposisi_suratmasuk.suratmasuk_id
		JOIN aparatur ON aparatur.jabatan_id = disposisi_suratmasuk.aparatur_id
		WHERE disposisi_suratmasuk.status = 'Belum Selesai'
		AND disposisi_suratmasuk.aparatur_id ='$jabatan_id' AND left(surat_masuk.tanggal,4)='$tahun'
		GROUP BY disposisi_suratmasuk.suratmasuk_id
		ORDER BY surat_masuk.diterima DESC, LENGTH(surat_masuk.suratmasuk_id) DESC, disposisi_suratmasuk.dsuratmasuk_id DESC");
        
        $data['pengajuansurat'] = $this->db->query("SELECT * FROM draft WHERE dibuat_id = '$jabatan_id' AND LEFT(tanggal, 4) = '$tahun'")->num_rows();
		$data['suratmasuk'] = $query->num_rows();
		$data['draft'] = $this->db->query("SELECT * FROM draft WHERE verifikasi_id = '$jabatan_id' AND LEFT(tanggal, 4) = '$tahun'")->num_rows();
		$data['tandatangan'] = $this->db->get_where('penandatangan', array('jabatan_id' => $jabatan_id, 'status' => 'Belum Ditandatangani'))->num_rows();


		$response = array(
			    'response_code' => 200,
                'message' => 'success',
                'items' => $data
			);
		header('Content-type: application/json');
		echo json_encode($response);
    
	}
	
	public function suratMasukInbox()
	{
	    $tahun = $this->input->post('tahun');
		$jabatan_id = $this->input->post('jabatan_id');
		
		$query = $this->db->query("
			SELECT *,surat_masuk.tanggal as tglsurat, surat_masuk.lampiran_lain,surat_masuk.lampiran FROM disposisi_suratmasuk
            JOIN surat_masuk ON surat_masuk.suratmasuk_id = disposisi_suratmasuk.suratmasuk_id
            JOIN aparatur ON aparatur.jabatan_id = disposisi_suratmasuk.aparatur_id
            WHERE disposisi_suratmasuk.status = 'Belum Selesai'
            AND disposisi_suratmasuk.aparatur_id = '$jabatan_id' AND LEFT(surat_masuk.diterima, 4) = '$tahun'
            GROUP BY disposisi_suratmasuk.suratmasuk_id
            ORDER BY surat_masuk.diterima DESC, LENGTH(surat_masuk.suratmasuk_id) DESC, disposisi_suratmasuk.dsuratmasuk_id DESC
		");
		
		$data = $query->result();
		
		$response = array(
			    'response_code' => 200,
                'message' => 'success',
                'items' => $data,
			);
		header('Content-type: application/json');
		echo json_encode($response);
	}
	
	public function suratMasukInboxDetailRiwayatDisposisi()
	{
	    $jabatan_id = $this->input->post('jabatan_id');
		$suratmasukid = $this->input->post('suratmasuk_id');
		
        // cek apakah surat sudah selesai
        $cekselesai = $this->db->order_by('dsuratmasuk_id', 'DESC')->get_where('disposisi_suratmasuk', array('suratmasuk_id' => $suratmasukid, 'status' => 'Selesai'))->num_rows();
		
		if($cekselesai == 0){
            $qdisposisi = $this->db->limit(1)->order_by('dsuratmasuk_id', 'DESC')->get_where('disposisi_suratmasuk', array('suratmasuk_id' => $suratmasukid));
            $cekAtasanJabatan = $this->db->get_where('jabatan', array('atasan_id' => $qdisposisi->row_array()['users_id']));
            
            if (empty($cekAtasanJabatan->num_rows())) {
                $statusTU = $this->db->get_where('jabatan', array('jabatan_id' => $qdisposisi->row_array()['users_id']))->row_array();
                $atasan_id = $statusTU['atasan_id'];
                $beradaTU = $this->db->query("SELECT * FROM disposisi_suratmasuk 
                                            JOIN aparatur ON disposisi_suratmasuk.aparatur_id = aparatur.jabatan_id
                                            LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
                                            WHERE disposisi_suratmasuk.suratmasuk_id = '$suratmasukid'
                                            AND disposisi_suratmasuk.status='Belum Selesai'
                                        ")->result();
                                        
                $text = "";
                foreach($beradaTU as $key =>$tu){
                    $text .= "<b>".$tu->nama_jabatan.", </b>";
                }
                
            }else{
                $berada = $this->db->query("SELECT * FROM disposisi_suratmasuk 
                                            LEFT JOIN jabatan ON jabatan.jabatan_id=disposisi_suratmasuk.aparatur_id
                                            WHERE disposisi_suratmasuk.suratmasuk_id = '$suratmasukid'
                                            AND disposisi_suratmasuk.status='Belum Selesai'
                                        ")->result();
                
                $numItems = count($berada);
                $i = 0;
                $text = "";                     
                foreach ($berada as $key => $b) {
                    if(++$i === $numItems) {
                        $text .= "- <font style='font-size:14px; color:#598eff;'><b>".$b->nama_jabatan."</b></font>";
                    }else{
                        $text .= "- <font style='font-size:14px; color:#598eff;'><b>".$b->nama_jabatan."</b></font> <br>";
                    }
                    
                }
            }
		} else{
            $text = "<h4><span class='badge badge-pill badge-success'>Surat Sudah Diselesaikan</span></h4>";
		}
		
		
		$response = array(
			'response_code' => 200,
            'message' => 'success',
            'text' => $text
		);
		header('Content-type: application/json');
		echo json_encode($response);
		
	}
	
	public function loopInboxDetailRiwayatDisposisi()
	{
	    $suratmasukid = $this->input->post('suratmasuk_id');
	    
	    $qketdis = $this->db->query("SELECT jabatan.nama_jabatan,disposisi_suratmasuk.tanggal,disposisi_suratmasuk.keterangan,disposisi_suratmasuk.users_id,disposisi_suratmasuk.status, disposisi_suratmasuk.waktudisposisi FROM disposisi_suratmasuk
                                 LEFT JOIN aparatur ON aparatur.jabatan_id = disposisi_suratmasuk.aparatur_id
                                 LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
                                 LEFT JOIN users ON users.aparatur_id = aparatur.aparatur_id
                                 WHERE disposisi_suratmasuk.suratmasuk_id = '$suratmasukid' AND disposisi_suratmasuk.status !='Riwayat' AND users.level_id != 4 AND users.level_id != 18 GROUP BY disposisi_suratmasuk.aparatur_id ORDER BY disposisi_suratmasuk.dsuratmasuk_id ASC
                                ")->result();
        
        $data = [];
        foreach ($qketdis as $key => $kd) {
            $data [] = array(
                "status" => $kd->status,
                "nama_jabatan" => $kd->nama_jabatan,
                "tanggal" => $kd->tanggal,
                "waktu" => date("h:ia", strtotime($kd->waktudisposisi))
            );
        }
        
        $response = array(
			'response_code' => 200,
            'message' => 'success',
            'item' => $data
		);
		header('Content-type: application/json');
		echo json_encode($response);
	}
	
	public function suratMasukInboxFile()
	{
	    $jabatan_id = $this->input->post('jabatan_id');
		$suratmasukid = $this->input->post('suratmasuk_id');
	    
	    $cd = $this->db->query("SELECT *,surat_masuk.tanggal as tglsurat FROM disposisi_suratmasuk
			JOIN surat_masuk ON surat_masuk.suratmasuk_id = disposisi_suratmasuk.suratmasuk_id
			JOIN aparatur ON aparatur.jabatan_id = disposisi_suratmasuk.aparatur_id
			WHERE disposisi_suratmasuk.aparatur_id ='$jabatan_id' AND disposisi_suratmasuk.suratmasuk_id='$suratmasukid' AND disposisi_suratmasuk.status != 'Riwayat'
			ORDER BY surat_masuk.diterima DESC, LENGTH(surat_masuk.suratmasuk_id) DESC, disposisi_suratmasuk.dsuratmasuk_id DESC")->row();
			
            // lihat surat yang ada lampirannya
            if(!empty($cd->lampiran_lain)){
                if(substr($cd->lampiran, 0,2) == 'SB'){ 
                                $urlFileSuratMasuk = site_url('uploads/SIGNED/'.$cd->lampiran);
                                 $urlFileLampiranSuratMasuk = site_url('assets/lampiransurat/biasa/'.$cd->lampiran_lain);
                            }elseif(substr($cd->lampiran, 0,2) == 'SE'){ 
                                $urlFileSuratMasuk = site_url('uploads/SIGNED/'.$cd->lampiran);
                                 $urlFileLampiranSuratMasuk = site_url('assets/lampiransurat/edaran/'.$cd->lampiran_lain);
                            }elseif(substr($cd->lampiran, 0,2) == 'SU'){ 
                                $urlFileSuratMasuk = site_url('uploads/SIGNED/'.$cd->lampiran);
                                 $urlFileLampiranSuratMasuk = site_url('assets/lampiransurat/undangan/'.$cd->lampiran_lain);
                            }elseif(substr($cd->lampiran, 0,5) == 'PNGMN'){ 
                                $urlFileSuratMasuk = site_url('uploads/SIGNED/'.$cd->lampiran);
                                 $urlFileLampiranSuratMasuk = site_url('assets/lampiransurat/pengumuman/'.$cd->lampiran_lain);
                            }elseif(substr($cd->lampiran, 0,3) == 'LAP'){
                                $urlFileSuratMasuk = site_url('uploads/SIGNED/'.$cd->lampiran);
                                 $urlFileLampiranSuratMasuk = site_url('assets/lampiransurat/laporan/'.$cd->lampiran_lain);
                            }elseif(substr($cd->lampiran, 0,3) == 'REK'){ 
                                $urlFileSuratMasuk = site_url('uploads/SIGNED/'.$cd->lampiran);
                                 $urlFileLampiranSuratMasuk = site_url('assets/lampiransurat/rekomendasi/'.$cd->lampiran_lain);
                            }elseif(substr($cd->lampiran, 0,3) == 'INT'){ 
                                $urlFileSuratMasuk = site_url('uploads/SIGNED/'.$cd->lampiran);
                                 $urlFileLampiranSuratMasuk = site_url('assets/lampiransurat/instruksi/'.$cd->lampiran_lain);
                            }elseif(substr($cd->lampiran, 0,3) == 'PNG'){
                                $urlFileSuratMasuk = site_url('uploads/SIGNED/'.$cd->lampiran);
                                 $urlFileLampiranSuratMasuk = site_url('assets/lampiransurat/pengantar/'.$cd->lampiran_lain);
                            }elseif(substr($cd->lampiran, 0,5) == 'NODIN'){
                                $urlFileSuratMasuk = site_url('uploads/SIGNED/'.$cd->lampiran);
                                 $urlFileLampiranSuratMasuk = site_url('assets/lampiransurat/notadinas/'.$cd->lampiran_lain);
                            }elseif(substr($cd->lampiran, 0,2) == 'SK'){
                                $urlFileSuratMasuk = site_url('uploads/SIGNED/'.$cd->lampiran);
                                 $urlFileLampiranSuratMasuk = site_url('assets/lampiransurat/keterangan/'.$cd->lampiran_lain);
                            }elseif(substr($cd->lampiran, 0,3) == 'SPT'){
                                $urlFileSuratMasuk = site_url('uploads/SIGNED/'.$cd->lampiran);
                                 $urlFileLampiranSuratMasuk = site_url('assets/lampiransurat/perintahtugas/'.$cd->lampiran_lain);
                            }elseif(substr($cd->lampiran, 0,2) == 'SP'){
                                $urlFileSuratMasuk = site_url('uploads/SIGNED/'.$cd->lampiran);
                                 $urlFileLampiranSuratMasuk = site_url('assets/lampiransurat/perintah/'.$cd->lampiran_lain);
                            }elseif(substr($cd->lampiran, 0,3) == 'IZN'){
                                $urlFileSuratMasuk = site_url('uploads/SIGNED/'.$cd->lampiran);
                                 $urlFileLampiranSuratMasuk = site_url('assets/lampiransurat/izin/'.$cd->lampiran_lain);
                            }elseif(substr($cd->lampiran, 0,3) == 'PJL'){
                                $urlFileSuratMasuk = site_url('uploads/SIGNED/'.$cd->lampiran);
                                 $urlFileLampiranSuratMasuk = site_url('assets/lampiransurat/perjalanan/'.$cd->lampiran_lain);
                            }elseif(substr($cd->lampiran, 0,3) == 'KSA'){
                                $urlFileSuratMasuk = site_url('uploads/SIGNED/'.$cd->lampiran);
                                 $urlFileLampiranSuratMasuk = site_url('assets/lampiransurat/kuasa/'.$cd->lampiran_lain);
                            }elseif(substr($cd->lampiran, 0,3) == 'MKT'){
                                $urlFileSuratMasuk = site_url('uploads/SIGNED/'.$cd->lampiran);
                                 $urlFileLampiranSuratMasuk = site_url('assets/lampiransurat/melaksanakantugas/'.$cd->lampiran_lain);
                            }elseif(substr($cd->lampiran, 0,3) == 'PGL'){
                                $urlFileSuratMasuk = site_url('uploads/SIGNED/'.$cd->lampiran);
                                 $urlFileLampiranSuratMasuk = site_url('assets/lampiransurat/panggilan/'.$cd->lampiran_lain);
                            }elseif(substr($cd->lampiran, 0,3) == 'NTL'){
                                $urlFileSuratMasuk = site_url('uploads/SIGNED/'.$cd->lampiran);
                                 $urlFileLampiranSuratMasuk = site_url('assets/lampiransurat/notulen/'.$cd->lampiran_lain);
                            }elseif(substr($cd->lampiran, 0,3) == 'MMO'){
                                $urlFileSuratMasuk = site_url('uploads/SIGNED/'.$cd->lampiran);
                                 $urlFileLampiranSuratMasuk = site_url('assets/lampiransurat/memo/'.$cd->lampiran_lain);
                            }elseif(substr($cd->lampiran, 0,3) == 'LMP'){
                                $urlFileSuratMasuk = site_url('uploads/SIGNED/'.$cd->lampiran);
                                 $urlFileLampiranSuratMasuk = site_url('assets/lampiransurat/lampiran/'.$cd->lampiran_lain);
                            }else{
                                $urlFileSuratMasuk = site_url('assets/lampiransuratmasuk/'.$cd->lampiran);
                                 $urlFileLampiranSuratMasuk = site_url('assets/lampiransuratmasuk/'.$cd->lampiran_lain);
                            }
            }else {
                // lihat surat saja
	            // <!-- Fikri Egov 26022022 - 9:31 Kondisi Preview Surat dari Surat TND -->
                    if(substr($cd->lampiran, 0,2) == 'SB'){
                        $urlFileSuratMasuk = site_url('uploads/SIGNED/'.$cd->lampiran);
                    }elseif(substr($cd->lampiran, 0,2) == 'SE'){
                        $urlFileSuratMasuk = site_url('uploads/SIGNED/'.$cd->lampiran);
                    }elseif(substr($cd->lampiran, 0,2) == 'SU'){
                        $urlFileSuratMasuk = site_url('uploads/SIGNED/'.$cd->lampiran);
                    }elseif(substr($cd->lampiran, 0,5) == 'PNGMN'){
                        $urlFileSuratMasuk = site_url('uploads/SIGNED/'.$cd->lampiran);
                    }elseif(substr($cd->lampiran, 0,3) == 'LAP'){
                        $urlFileSuratMasuk = site_url('uploads/SIGNED/'.$cd->lampiran);
                    }elseif(substr($cd->lampiran, 0,3) == 'REK'){
                        $urlFileSuratMasuk = site_url('uploads/SIGNED/'.$cd->lampiran);
                    }elseif(substr($cd->lampiran, 0,3) == 'INT'){
                        $urlFileSuratMasuk = site_url('uploads/SIGNED/'.$cd->lampiran);
                    }elseif(substr($cd->lampiran, 0,3) == 'PNG'){
                        $urlFileSuratMasuk = site_url('uploads/SIGNED/'.$cd->lampiran);
                    }elseif(substr($cd->lampiran, 0,5) == 'NODIN'){
                        $urlFileSuratMasuk = site_url('uploads/SIGNED/'.$cd->lampiran);
                    }elseif(substr($cd->lampiran, 0,2) == 'SK'){
                        $urlFileSuratMasuk = site_url('uploads/SIGNED/'.$cd->lampiran);
                    }elseif(substr($cd->lampiran, 0,3) == 'SPT'){
                        $urlFileSuratMasuk = site_url('uploads/SIGNED/'.$cd->lampiran);
                    }elseif(substr($cd->lampiran, 0,2) == 'SP'){
                        $urlFileSuratMasuk = site_url('uploads/SIGNED/'.$cd->lampiran);
                    }elseif(substr($cd->lampiran, 0,3) == 'IZN'){
                        $urlFileSuratMasuk = site_url('uploads/SIGNED/'.$cd->lampiran);
                    }elseif(substr($cd->lampiran, 0,3) == 'PJL'){
                        $urlFileSuratMasuk = site_url('uploads/SIGNED/'.$cd->lampiran);
                    }elseif(substr($cd->lampiran, 0,3) == 'KSA'){ 
                        $urlFileSuratMasuk = site_url('uploads/SIGNED/'.$cd->lampiran);
                    }elseif(substr($cd->lampiran, 0,3) == 'MKT'){ 
                        $urlFileSuratMasuk = site_url('uploads/SIGNED/'.$cd->lampiran);
                    }elseif(substr($cd->lampiran, 0,3) == 'PGL'){  
                        $urlFileSuratMasuk = site_url('uploads/SIGNED/'.$cd->lampiran);
                    }elseif(substr($cd->lampiran, 0,3) == 'NTL'){  
                        $urlFileSuratMasuk = site_url('uploads/SIGNED/'.$cd->lampiran);
                    }elseif(substr($cd->lampiran, 0,3) == 'MMO'){ 
                        $urlFileSuratMasuk = site_url('uploads/SIGNED/'.$cd->lampiran);
                    }elseif(substr($cd->lampiran, 0,3) == 'LMP'){ 
                        $urlFileSuratMasuk = site_url('uploads/SIGNED/'.$cd->lampiran);
                    }elseif(substr($cd->lampiran, 0,2) == 'SL'){ 
                        $urlFileSuratMasuk = site_url('uploads/SIGNED/'.$cd->lampiran);
                    }else{
                        $urlFileSuratMasuk = site_url('assets/lampiransuratmasuk/'.$cd->lampiran);
                    }
                     $urlFileLampiranSuratMasuk = null;
            // end lihat surat
            }   
			
            
        // lembardisposisi
        $pengembalian = $this->db->query("SELECT * FROM disposisi_suratmasuk a
                    WHERE a.aparatur_id = '$jabatan_id'
                    AND a.suratmasuk_id = '$suratmasukid' AND a.status='Dikembalikan'
                ")->num_rows();
                
        if($pengembalian != 0){
            $lembarDisposisi = site_url('export/lembar_disposisi/'.$suratmasukid);
            $lembarPengembalian = site_url('export/lembar_pengembalian/'.$suratmasukid);
        }else{
            $lembarDisposisi = site_url('export/lembar_disposisi/'.$suratmasukid);
            $lembarPengembalian = null;
        }
        //end lembar disposisi 
            
        $response = array(
			'response_code' => 200,
            'message' => 'success',
            'item' => $cd,
            'urlFileSuratMasuk' => $urlFileSuratMasuk,
            'urlFileLampiranSuratMasuk' => $urlFileLampiranSuratMasuk,
            'lembarDisposisi' => array(
                "urlLembarDisposisi" => $lembarDisposisi,
                "urlLembarPengembalian" => $lembarPengembalian,
            ),
		);
		header('Content-type: application/json');
		echo json_encode($response);
	}
	
	public function disposisi()
	{
	    $opd = $this->input->post('opd_id');
		$level = $this->input->post('level');
		$suratmasukid = $this->input->post('suratmasuk_id'); // Update @Mpik Egov 30/06/2022
		$jabatanid = $this->input->post('jabatan_id'); // Update @Mpik Egov 30/06/2022
		$datenow = date("Y-m-d");
		if (isset($_POST['disposisi'])) {
		    
			// if (empty($this->input->post('harap'))) {
			// 	$harap = '';
			// }else{
			// 	$harap = implode(',', $this->input->post('harap'));
			// }
            
			$aparatur_id = $this->input->post('aparatur_id');
			$explodeAparatur = explode(',', $aparatur_id);
			$datenow=date("Y-m-d");
			$dataAparatur = array();
			$index = 0;
			foreach ($explodeAparatur as $key => $h) {
				array_push($dataAparatur, array(
					'suratmasuk_id' => $this->input->post('suratmasuk_id'), 
					'aparatur_id' => $h, 
					'users_id' => $this->input->post('users_id'), 
					'harap' => 	$this->input->post('harap'), 
					'keterangan' => $this->input->post('keterangan'), 
					'waktudisposisi' => date("h:i:sa"),
					'tanggal' => date('Y-m-d'),
					'tanggal_verifikasi' => htmlentities($datenow),
					));
				$index++;
			}
        	
           
			if ($level == 5) {
			    $dat = explode(',', $aparatur_id);
			    $in = implode(',', $dat);
				$aparatur = $this->db->query("SELECT *, a.level_id as levelidd  FROM users a LEFT JOIN aparatur b ON b.aparatur_id=a.aparatur_id WHERE b.jabatan_id IN ($in) ORDER BY a.level_id ASC")->result_array();
			
				if ($aparatur[0]['levelidd'] != 6) {
                    $suratmasuk_id=$this->input->post('suratmasuk_id');
					$sekdis = $this->db->query("SELECT * FROM aparatur 
						JOIN opd ON aparatur.opd_id=opd.opd_id 
						JOIN users ON aparatur.aparatur_id=users.aparatur_id 
						JOIN level ON users.level_id=level.level_id
						WHERE opd.opd_id='$opd' AND level.level_id = 6")->row_array();
                    $cekdisposisisekdis=$this->db->query("SELECT * FROM disposisi_suratmasuk a WHERE a.suratmasuk_id='$suratmasuk_id' AND a.aparatur_id='".$sekdis['jabatan_id']."'")->num_rows();
					if($cekdisposisisekdis == 0){
                    $dataAtasan = array(
							'suratmasuk_id' => $suratmasuk_id, 
							'aparatur_id' => $sekdis['jabatan_id'], 
							'users_id' => $this->input->post('users_id'),
							'nama_pengirim' => $this->input->post('nama_pengirim'), 
				// 			'harap' => 	$this->input->post('harap'), 	
				// 			'keterangan' => $this->input->post('keterangan'), 
							'tanggal' => htmlentities($datenow),
							'waktudisposisi' => date("h:i:sa"),
							'tanggal_verifikasi' => htmlentities($datenow),
						);	
						$insert = $this->db->insert('disposisi_suratmasuk', $dataAtasan);
						if($insert){
						    $where = $this->input->post('dsuratmasuk_id');
						    $data=array('harap' => $this->input->post('harap'), 'keterangan' => $this->input->post('keterangan'));
						    
						    $this->db->update('disposisi_suratmasuk',$data,$where);
						}
                    }else{
                        $response = array(
        			        'response_code' => 200,
                            'message' => 'Gagal disposisi. Surat Sudah Pernah di Disposisi'
        			    );
        			    
        			    echo json_encode($response);
        			    //die;
                    }
				}else{
					$aparatur_id = $this->input->post('aparatur_id');
					$explodeAparatur = explode(',', $aparatur_id);
					$datenow=date("Y-m-d");
					$dataAparatur = array();
					$index = 0;
					foreach ($explodeAparatur as $key => $h) {
						array_push($dataAparatur, array(
							'suratmasuk_id' => $this->input->post('suratmasuk_id'), 
							'aparatur_id' => $h, 
							'users_id' => $this->input->post('users_id'), 
				// 			'harap' => 	$this->input->post('harap'), 
							'waktudisposisi' => date("h:i:sa"),
				// 			'keterangan' => $this->input->post('keterangan'), 
							'tanggal' => date('Y-m-d'),
							'tanggal_verifikasi' => htmlentities($datenow),
							));
						$index++;
					}
					
				}
				
			}
			$suratmasukid=$this->input->post('suratmasuk_id');

			$aparatur = explode(',',$this->input->post('aparatur_id'));
			$counting=count($aparatur);
 			// var_dump($this->input->post("suratmasuk_id"));
 			// die;
			for($i=0;$i < $counting; $i++){
			    
			    // send notification (Rama)
    			  $datatest = $this->sendNotif($aparatur[$i], $suratmasukid);
    			 // var_dump($datatest);
    			 // die; 
    			 // end send notification
			    
				$aptid=$aparatur[$i];
				$datenow=date("Y-m-d");
				$cekdisposisi=$this->db->query("select * from disposisi_suratmasuk a where a.suratmasuk_id='$suratmasukid' and a.aparatur_id='$aptid'")->num_rows();
			    if($cekdisposisi == 0){
				// PEMANGGILAN POST UNTUK SAVE DATA
				$in = array();
			    $in['suratmasuk_id'] = $this->input->post("suratmasuk_id");
			    $in['aparatur_id']=$aptid;
			    $in['users_id']=$this->input->post('users_id');
			 //   $in['harap']=$this->input->post('harap');
			 //   $in['keterangan']=$this->input->post('keterangan');
			    $in['waktudisposisi']=date("h:i:sa");
			    $in['tanggal']=date('Y-m-d');
			    $in['tanggal_verifikasi']= htmlentities($datenow);
			    //END
				$dispos = $this->db->insert('disposisi_suratmasuk', $in);
    			    if ($dispos) {
        				//update status
        				$this->db->update('disposisi_suratmasuk', array('status' => 'Selesai Disposisi','harap' => $this->input->post('harap'),'keterangan' => $this->input->post('keterangan')), array('dsuratmasuk_id' => $this->input->post('dsuratmasuk_id')));
        				//$this->inbox_model->update_data('disposisi_suratmasuk', array('status' => 'Selesai Disposisi','harap' => $this->input->post('harap'),'keterangan' => $this->input->post('keterangan')), array('dsuratmasuk_id' => $this->input->post('dsuratmasuk_id')));
        
        				$message = 'Surat Berhasil Didisposisikan';
    				
    				}else{
    					$message = 'Surat Gagal Didisposisikan';
    				}
    			}else{
    			    $message = 'Gagal disposisi. Surat Sudah Pernah di Disposisi';
    			}
			
			}
			
			$response = array(
			    'response_code' => 200,
                'message' => $message
			);
			header('Content-type: application/json');
        	echo json_encode($response);
		}elseif (isset($_POST['selesai'])){
            $tanggal=date('Y-m-d');
			$data = array(
			    'catatan'=> $this->input->post('catatan'),
			    'tanggal' => $tanggal,
			    'status' => 'Selesai'
			    );
			$where = array('dsuratmasuk_id' => $this->input->post('dsuratmasuk_id'));
			$update = $this->db->update('disposisi_suratmasuk',$data,$where);

		    if ($update) {
			    $response = array(
			        'response_code' => 200,
                    'message' => 'Surat Berhasil Diselesaikan'
			    );
			    header('Content-type: application/json');
			    echo json_encode($response);
			}else{
			    $response = array(
			        'response_code' => 200,
                    'message' => 'Surat Gagal Diselesaikan'
			    );
			    header('Content-type: application/json');
			    echo json_encode($response);
			}
		}elseif (isset($_POST['kembalikan'])){
			$kembalikan = $this->db->get_where('surat_masuk',array('suratmasuk_id'=>$this->input->post('suratmasuk_id')))->row_array();
			$statusdikembalikan = 'Dikembalikan';
			$data = array(
				'suratmasuk_id'=>$suratmasukid,
				'aparatur_id'=>$kembalikan['dibuat_id'],
				'users_id'=>$jabatanid,
				'harap'=>'',
				'waktudisposisi' => date("h:i:sa"),
				'tanggal' => htmlentities($datenow),
				'tanggal_verifikasi' => htmlentities($datenow),
				'status'=>$statusdikembalikan,
			);
			
			$where = array('suratmasuk_id' => $suratmasukid,'aparatur_id' =>$jabatanid);
			$kembali = $this->db->update('disposisi_suratmasuk',$data,$where);
			
			if ($kembali) {
			    $response = array(
			        'response_code' => 200,
                    'message' => 'Surat Berhasil Dikembalikan'
			    );
			    header('Content-type: application/json');
			    echo json_encode($response);
			}else{
			    $response = array(
			        'response_code' => 200,
                    'message' => 'Surat Gagal Dikembalikan'
			    );
			    header('Content-type: application/json');
			    echo json_encode($response);
			}
		}elseif (isset($_POST['selesaidisposisi'])){
			$data = array(
			    'suratmasuk_id' => $suratmasukid,
			    'aparatur_id' => $jabatanid,
			    'users_id' => $jabatanid,
			    'harap'=>'',
			    'waktudisposisi' => date("h:i:sa"),
			    'tanggal' => $datenow,
			    'tanggal_verifikasi' => htmlentities($datenow),
			    'status' => 'Selesai Disposisi'
			    );
			    
		    $insert = $this->db->insert('disposisi_suratmasuk', $data);

			if ($insert) {
			    $data=array('status' => 'Selesai Disposisi');
			    $where = array('dsuratmasuk_id' => $this->input->post('dsuratmasuk_id'));
		    	$update = $this->db->update('disposisi_suratmasuk',$data,$where);
		    	
		    	$response = array(
    			    'response_code' => 200,
                    'message' => "Surat Berhasil Diselesaikan"
    			);
    			header('Content-type: application/json');
            	echo json_encode($response);
			}else{
			    $response = array(
    			    'response_code' => 200,
                    'message' => "Surat Gagal Diselesaikan"
    			);
    			header('Content-type: application/json');
            	echo json_encode($response);
			}
        // END
        // Update @Mpik Egov 30/06/2022
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
					'waktudisposisi' => date("h:i:sa"),
					'tanggal' => htmlentities($datenow),
					'tanggal_verifikasi' => htmlentities($datenow),
				);
				
				$dispos = $this->inbox_model->insert_data('disposisi_suratmasuk', $data);
				if ($dispos) {
					$this->inbox_model->update_data('disposisi_suratmasuk', array('status' => 'Selesai Disposisi'), array('dsuratmasuk_id' => $this->input->post('dsuratmasuk_id')));
					$this->session->set_flashdata('success', 'Surat Berhasil Didisposisikan');
					redirect(site_url('suratmasuk/inbox/sudahdisposisi'));
				}else{
					$this->session->set_flashdata('error', 'Surat Gagal Didisposisikan');
					redirect(site_url('suratmasuk/inbox/detaildata/'.$suratmasukid));
				}
			}else{
				$this->session->set_flashdata('error','Surat Sudah Pernah Dididsposisikan');
				redirect(site_url('suratmasuk/inbox/detaildata/'.$suratmasukid));
			}
		}
	    
	}
	
	public function sendNotif($jabatanId, $suratmasukid)
	{
	    $ch = curl_init();
	   // get aparatur id
	    $jabatan = $this->db->query("SELECT * FROM aparatur where jabatan_id='$jabatanId' and statusaparatur='Aktif'")->row();
	    $aparaturId = $jabatan->aparatur_id;
	    
	   // get users id
	    $user = $this->db->query("SELECT * FROM users WHERE aparatur_id='$aparaturId'")->row();
	    $usersId = $user->users_id;
	    
	   // get token android
	    $andro = $this->db->query("SELECT * FROM token_android where users_id='$usersId'");
	    
	   // return $jabatanId;
	    if($andro->num_rows() > 0){
	        $token = $andro->row()->token;
	        //send api
    	    $url = "https://fcm.googleapis.com/fcm/send";
    	    $header = array(
    	        'Authorization: key=AAAAqsibmuk:APA91bEQafgWfJx8q5buWFhOmdBUzI03julNvnPYZ-Xfzm3o3Tta8pH7tL-fnWrZmrfkUD4wesiBrIVMmH6QGzWCJVRQyT52rMFR-GJqXELh_pGwq0uxduaRFEHZmDhZH_wMrJlwXDys',
    	        'Content-Type: application/json'
    	        );
    	        
    	    $data = '{
    		 "to": "'. $token .'",
    		 "notification": 
    		 { 
    		   "body":"silahkan cek pada aplikasi",
    		   "title":"Ada surat masuk!!"
    		 }  
    	   } 
    	   ';
    	   
    	   //return $data;
    	    
    	    curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_MAXREDIRS,10);
            curl_setopt($ch, CURLOPT_TIMEOUT,0);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
            curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
            curl_setopt($ch, CURLOPT_HEADER,false);
            curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
            
            $output=curl_exec($ch);

            curl_close($ch);
    
            return $output;
    	    //end send api
    	    }else{
        	    return;
        	}
	}
	
	public function aparatur()
	{
	    $opd_id = $this->input->post('opd_id');
		$jabatan_id = $this->input->post('jabatan_id');
		$namajabatan= $this->input->post('nama_jabatan');
	    
	    if(substr($namajabatan,0,13) == 'Kepala Bagian'){
	        	$aparatur = $this->db->query("
        			SELECT aparatur.jabatan_id, nama, nama_jabatan FROM aparatur 
        			JOIN jabatan ON aparatur.jabatan_id = jabatan.jabatan_id
        			LEFT JOIN users ON users.aparatur_id=aparatur.aparatur_id
        			LEFT JOIN level ON level.level_id=users.level_id
        			WHERE jabatan.atasan_id = '$jabatan_id'
        			AND jabatan.opd_id = '$opd_id'
        			AND aparatur.nip != '-' AND aparatur.statusaparatur='Aktif'
        			ORDER BY level.level_id ASC
        		")->result();
			// Select Option Untuk SEKDA
		}elseif(substr($namajabatan,0,12) == 'Kepala Dinas' || substr($namajabatan,0,12) == 'Kepala Badan' || substr($namajabatan,0,5) == 'Lurah' || substr($namajabatan,0,5) == 'Camat'){
			$aparatur = $query = $this->db->query("
                    		SELECT aparatur.jabatan_id, nama, nama_jabatan FROM aparatur 
                    		LEFT JOIN users ON users.aparatur_id=aparatur.aparatur_id
                    		LEFT JOIN level ON level.level_id=users.level_id
                    		JOIN jabatan ON aparatur.jabatan_id = jabatan.jabatan_id 
                    		WHERE jabatan.opd_id ='$opd_id'
                    		AND aparatur.nip != '-' AND users.users_id !='NULL' AND aparatur.statusaparatur='Aktif'
                    		ORDER BY level.level_id ASC
                		")->result();
			// Select Option Untuk SEKDA
		}else{
			$aparatur = $this->db->query("
                			SELECT aparatur.jabatan_id, nama, nama_jabatan FROM aparatur 
                			JOIN jabatan ON aparatur.jabatan_id = jabatan.jabatan_id
                			LEFT JOIN users ON users.aparatur_id=aparatur.aparatur_id
                			LEFT JOIN level ON level.level_id=users.level_id
                			WHERE jabatan.atasan_id = '$jabatan_id'
                			AND jabatan.opd_id = '$opd_id'
                			AND aparatur.nip != '-' AND aparatur.statusaparatur='Aktif'
                			ORDER BY level.level_id ASC
                		")->result();
		}
		
		// return $response;
        
        $denganHormat = array("Saya Hadir", "Hadiri/Wakili", "Pertimbangkan", "Pedomani", "Sarankan", "Koordinasikan", "Tindak Lanjuti", "Selesaikan", "Siapkan", "Untuk Diketahui", 
        "Untuk Diproses", "Menghadap Saya", "Laporakan Hasilnya", "Monitor Pelaksanaannya", "Sampaikan Kepada ybs", "Agendakan", "Lain - Lain");
		
		$response = array(
			'response_code' => 200,
            'message' => 'success',
            'aparatur' => $aparatur,
            'listOpt' => $denganHormat
		);
		header('Content-type: application/json');
		echo json_encode($response);
	}
	
}