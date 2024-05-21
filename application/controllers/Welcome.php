<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	function __construct()
    	{
    		parent::__construct();
    		//load Helper for Form
    		$this->load->helper('url', 'form'); 
    		$this->load->library('form_validation');
    	}

	public function index()
		{
			$this->load->view('welcome');
		}

	public function upload() 
    	{
		//echo "oke"; 
        	$config['upload_path'] = './contoh/';
        	$config['allowed_types'] = 'pdf';
        	$config['max_size'] = 7000;
 		
		echo $this->input->post('file');
 
        	$this->load->library('upload', $config);
 
        	if (!$this->upload->do_upload('file')) 
        	{
            		//$error = array('error' => );
 
            		 var_dump($this->upload->display_errors());
        	} 
        	else 
        	{
            		echo "oke";
        	}
    	}

		function json_rpc_header($userid, $password)
		{
			date_default_timezone_set("UTC");
			$inttime = strval(time() - strtotime("1970-01-01 00:00:00"));
			$value = $userid . "&" . $inttime;
			$key = $password;
			$signature = hash_hmac("sha256", $value, $key, true);
			$signature64 = base64_encode($signature);
			$headers =
				[
					"userid:" . $userid,
					"signature:" . $signature64,
					"key:" . $inttime
				];
			return $headers;
		}
	
		public function do_login($sso_otp = null)
		{
			date_default_timezone_set("Asia/Bangkok");
			$this->load->library('session');
	
			$sso_otp = $_GET['otp_sso'];
			// var_dump($sso_otp);
			// die;
	
			$user = 'kominfo';
			$pwd = '78HGygyhbjUGTDRtrtFY54cG35vVdxd78CYV8vcdfghYChjTDChjutD65xH3gC8j';
			$header = $this->json_rpc_header($user, $pwd);
			$urlTo = "https://sso-bsw.kotabogor.go.id/oulsso/websvc.php";
	
			$aplikasi = 'e-SPPT';
			$data = 'data=
			{
			  "jsonrpc": "2.0", 
			  "method": "do_sso_login",
			  "params": 
			  { 
				"otp_sso":"' . $sso_otp . '",
				"nama_aplikasi":"e-SPPT"
			  } 
			} 
			';
	
			$c = curl_init();
			curl_setopt($c, CURLOPT_URL, $urlTo);
			curl_setopt($c, CURLOPT_POST, TRUE);
			curl_setopt($c, CURLOPT_HTTPHEADER, $header);
			curl_setopt($c, CURLOPT_POSTFIELDS, $data);
			curl_setopt($c, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($c, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
	
			$res = curl_exec($c);
			if (curl_errno($c)) {
				echo 'Curl error: ' . curl_error($c);
			}
			curl_close($c);
	
			// var_dump($res);
			// die;
			// return $res;
	
			$json = json_decode($res);
	// 		var_dump($json);
	// 		die;
			
			$status = $json->status;
			$message = $json->result->message;
			if ($status != 'sukses') //jika gagal;
			{
				exit;
				redirect(site_url() . 'app/logout');
			}
	
			// rama fungsi sso
			$data = array(
				'nip' => $json->result->nip,
			);
	
			$nik =$json->result->nik;
			$nip = $json->result->nip;
			
			// var_dump($nik);
			// die;
			$cek = $this->db->query("SELECT * FROM aparatur WHERE nip = '$nip'")->num_rows();
			$ceks = $this->db->query("SELECT * FROM aparatur WHERE nik = '$nik' ")->num_rows();
			// var_dump($cek, $ceks);
			// die;
			if ($cek > 0 || $ceks > 0) {
				
				if($cek > 0){
					$this->db->select('aparatur.nama, aparatur.nik,opd.nama_pd,users.username, opd.email,aparatur.jabatan_id,jabatan.nama_jabatan,users.foto, users.level_id,aparatur.opd_id,users.users_id,aparatur.statusaparatur,jabatan.jabatan');
					$this->db->from('users');
					$this->db->join('aparatur', 'aparatur.aparatur_id = users.aparatur_id', 'left');
					$this->db->join('opd', 'opd.opd_id = aparatur.opd_id', 'left');
					$this->db->join('jabatan', 'jabatan.jabatan_id = aparatur.jabatan_id', 'left');
					$this->db->where('aparatur.nip', $nip);
				}else{
					$this->db->select('aparatur.nama, aparatur.nik,opd.nama_pd,users.username, opd.email,aparatur.jabatan_id,jabatan.nama_jabatan,users.foto, users.level_id,aparatur.opd_id,users.users_id,aparatur.statusaparatur,jabatan.jabatan');
					$this->db->from('users');
					$this->db->join('aparatur', 'aparatur.aparatur_id = users.aparatur_id', 'left');
					$this->db->join('opd', 'opd.opd_id = aparatur.opd_id', 'left');
					$this->db->join('jabatan', 'jabatan.jabatan_id = aparatur.jabatan_id', 'left');
					$this->db->where('aparatur.nik', $nik);
				}
				
				
				$query =  $this->db->get()->result();
				
				// var_dump($query);
				// die;
				if(!empty($query)){
						foreach ($query as $key => $h) {
						$nama = $h->nama;
						$namapd = $h->nama_pd;
						$username = $h->username;
						$email = $h->email;
						$jabatan_id = $h->jabatan_id;
						$nama_jabatan = $h->nama_jabatan;
						$foto = $h->foto;
						$level = $h->level_id;
						$opd_id = $h->opd_id;
						$users_id = $h->users_id;
					}
					$session = array(
						'login' => 1,
						'tahun' => date('Y'),
						'nama' => $nama,
						'nama_pd' => $namapd,
						'username' => $username,
						'email' => $email,
						'jabatan_id' => $jabatan_id,
						'nama_jabatan' => $nama_jabatan,
						'foto' => $foto,
						'level' => $level,
						'opd_id' => $opd_id,
						'users_id' => $users_id,
					);
					$this->session->set_userdata($session);
		
					// session sso
					$sso_idusrsso = $json->result->idusrapisso;
					$sso_nik = $json->result->nik;
					$sso_npwp = $json->result->npwp;
					$sso_nib = $json->result->nib;
					$sso_nip = $json->result->nip;
					$sso_email = $json->result->email;
					$sso_no_hp = $json->result->nohp;
					$sso_no_wa = $json->result->nowa;
					$sso_nokk = $json->result->nokk;
					$sso_nama = $json->result->nama;
					$sso_nick = $json->result->nick;
					$sso_isbadan = $json->result->jwp;
					$sso_sumber = $json->result->sumber;
					$sso_tglreg = $json->result->tglreg;
					$sso_lastlogin = $json->result->lastlogin;
					$sso_byklogin = $json->result->byklogin;
					$sso_bykresetpwd = $json->result->bykresetpwd;
					$sso_last_update = $json->result->last_update;
					$sso_last_activity = $json->result->last_activity;
					$sso_ip = $json->result->ip;
		
					$this->session->set_userdata('esppt_ispetugas', 0);
					$this->session->set_userdata('esppt_iduser_sso', $sso_idusrsso);
					$this->session->set_userdata('esppt_nama', $sso_nama);
					$this->session->set_userdata('esppt_nick', $sso_nick);
					$this->session->set_userdata('esppt_nik', $sso_nik);
					$this->session->set_userdata('esppt_nib', $sso_nib);
					$this->session->set_userdata('esppt_nip', $sso_nip);
					$this->session->set_userdata('esppt_npwp', $sso_npwp);
					$this->session->set_userdata('esppt_isbadan', $sso_isbadan);
					$this->session->set_userdata('esppt_nohp', $sso_no_hp);
					$this->session->set_userdata('esppt_nowa', $sso_no_wa);
					$this->session->set_userdata('esppt_email', $sso_email);
					$this->session->set_userdata('esppt_tgljam_login', $sso_lastlogin);
					// end session sso
		
					redirect('home/dashboard');
				}else{
					$this->session->set_flashdata('access', 'Aparatur tidak terdaftar di Aplikasi TND!');
					redirect(site_url());
				}
				
			}else{
				$this->session->set_flashdata('access', 'Aparatur tidak terdaftar di Aplikasi TND!');
				redirect(site_url());
			}
	
			// end rama fungsi sso
	
			// //untuk autologin khusus android atau centang ingat saya
			// $user = '';
			// if (isset($_COOKIE['esppt_user'])) {
			// 	$user = $_COOKIE['esppt_user'];
			// }
			// if (isset($_COOKIE['esppt_pwd'])) {
			// 	$pwd = $_COOKIE['esppt_pwd'];
			// }
			// if (isset($_COOKIE['esppt_is_mobile'])) {
			// 	if ($_COOKIE['esppt_is_mobile'] == '1') {
			// 		$this->session->set_userdata('esppt_is_mobile', 1);
			// 	}
			// }
	
			// $idusrsso = $json->result->idusrapisso;
			// $nik = $json->result->nik;
			// $npwp = $json->result->npwp;
			// $nib = $json->result->nib;
			// $nip = $json->result->nip;
			// $email = $json->result->email;
			// $no_hp = $json->result->nohp;
			// $no_wa = $json->result->nowa;
			// $nokk = $json->result->nokk;
			// $nama = $json->result->nama;
			// $nick = $json->result->nick;
			// $isbadan = $json->result->jwp;
			// $sumber = $json->result->sumber;
			// $tglreg = $json->result->tglreg;
			// $lastlogin = $json->result->lastlogin;
			// $byklogin = $json->result->byklogin;
			// $bykresetpwd = $json->result->bykresetpwd;
			// $last_update = $json->result->last_update;
			// $last_activity = $json->result->last_activity;
			// $ip = $json->result->ip;
	
			// $ispns = $json->result->ispns;
			// $byk = $json->result->byklogin + 1;
			// $lastlogin = $json->result->lastlogin;
	
			// //cek filter user database lokal
			// $ada = 0;
			// $content = $this->esppt->db_getdata_user_sso($idusrsso);
			// foreach ($content->result_array() as $row) :
			// 	$idusr = $row['id'];
			// 	$ada = 1;
			// endforeach;
	
			// //jika belum terdaftar bisa kembali ke login/logout atau bisa form info blm terdaftar sekaligus bertanya apakah akan dijadikan user aplikasi atau langsung otomatis tambah user lokal
			// if ($ada == 0) {
	
			// 	//jika pilih user tidak terdaftar
			// 	//$message = 'user SSO tidak terdaftar sebagai user simpeg';
			// 	//redirect('https://layanan-bapenda.kotabogor.go.id/api/oneuserlogin/sso/app/login/'.rawurlencode($message));
	
			// 	//pilih otomatis tambah ke database user lokal
			// 	$this->esppt->db_add_user_lengkap($idusr, $nik, $npwp, $nib, $nip, $email, $no_hp, $no_wa, $nokk, $nama, $nick, $isbadan, $sumber, $tglreg, $lastlogin, $byklogin, $bykresetpwd, $last_update, $last_activity, $ip);
			// 	//cari id user lokal
			// 	$content = $this->esppt->db_getdata_user_sso($idusrsso);
			// 	foreach ($content->result_array() as $row) :
			// 		$idusr = $row['id'];
			// 		$ada = 1;
			// 	endforeach;
			// 	if ($ada == 0) {
			// 		$message = 'tambah data user lokal esppt error';
			// 		redirect('https://layanan-bapenda.kotabogor.go.id/api/oneuserlogin/sso/app/login/' . rawurlencode($message));
			// 	}
			// } else {
			// 	//update database user lokal
			// 	$this->esppt->db_update_user_lengkap($idusr, $nik, $npwp, $nib, $nip, $email, $no_hp, $no_wa, $nokk, $nama, $nick, $isbadan, $sumber, $tglreg, $lastlogin, $byklogin, $bykresetpwd, $last_update, $last_activity, $ip);
			// }
	
			// $is_mobile = $this->session->esppt_is_mobile;
			// if ($is_mobile == 1) //Jika Android
			// {
			// 	setcookie('esppt_user', $user, time() + (86400 * 30 * 12 * 5), "/"); // 86400 = 1 day
			// 	setcookie('esppt_pwd', $pwd, time() + (86400 * 30 * 12 * 5), "/"); // 86400 = 1 day
			// 	setcookie('esppt_is_mobile', '1', time() + (86400 * 30 * 12 * 5), "/"); // 86400 = 1 day
			// }
	
			// $this->load->library('session');
			// $this->session->set_userdata('esppt_ispetugas', 0);
			// $this->session->set_userdata('esppt_iduser', $idusr);
			// $this->session->set_userdata('esppt_iduser_sso', $idusrsso);
			// $this->session->set_userdata('esppt_nama', $nama);
			// $this->session->set_userdata('esppt_pwd', $pwd);
			// $this->session->set_userdata('esppt_nick', $nick);
			// $this->session->set_userdata('esppt_nik', $nik);
			// $this->session->set_userdata('esppt_nib', $nib);
			// $this->session->set_userdata('esppt_nip', $nip);
			// $this->session->set_userdata('esppt_npwp', $npwp);
			// $this->session->set_userdata('esppt_isbadan', $isbadan);
			// $this->session->set_userdata('esppt_nohp', $no_hp);
			// $this->session->set_userdata('esppt_nowa', $no_wa);
			// $this->session->set_userdata('esppt_email', $email);
			// $this->session->set_userdata('esppt_tgljam_login', $lastlogin);
			// $content = $this->esppt->db_update_login($idusr, $byk, $ip);
	
			// $tgljam = 'tanggal ' . date('d-m-Y') . ' jam ' . date('H:i:s');
			// $pesan = 'Yth. Bpk/Ibu ' . $nama . ', Anda telah melakukan login user ke Sistem e-SPPT Kota Bogor pada ' . $tgljam;
			// $ket = 'e-SPPT';
			// if (strlen($no_wa) > 0) {
			// 	$content = $this->esppt->db_kirim_wa($no_wa, $pesan, $idusr, $ket);
			// }
	
			// if (strlen($email) > 0) {
			// 	$pesan = '<!DOCTYPE html>';
			// 	$pesan = $pesan . '<html>';
			// 	$pesan = $pesan . '<head>';
			// 	$pesan = $pesan . '<meta name="viewport" content="width=device-width, initial-scale=1">';
			// 	$pesan = $pesan . '<style>';
			// 	$pesan = $pesan . '.container {';
			// 	$pesan = $pesan . '  position: absolute;';
			// 	$pesan = $pesan . '  text-align: left;';
			// 	$pesan = $pesan . '  color: black;';
			// 	$pesan = $pesan . '}';
			// 	$pesan = $pesan . '.top-left {';
			// 	$pesan = $pesan . '  position: absolute;';
			// 	$pesan = $pesan . '  top: 180px;';
			// 	$pesan = $pesan . '  left: 50px;';
			// 	$pesan = $pesan . '}';
			// 	$pesan = $pesan . '</style>';
			// 	$pesan = $pesan . '</head>';
			// 	$pesan = $pesan . '<body>';
			// 	$pesan = $pesan . '<div class="container">';
			// 	$pesan = $pesan . '  <img src="https://layanan-bapenda.kotabogor.go.id/e-sppt/vendor/images/background_surat1.jpg" alt="" style="width:600px">';
			// 	$pesan = $pesan . '  <div class="top-left">';
			// 	$pesan = $pesan . '<h4>Informasi Layanan Bapenda<br></h4>';
			// 	$pesan = $pesan . '<h5>' . 'Yth. Bpk/Ibu ' . $nama . ', Anda telah melakukan login user ke Sistem e-SPPT Kota Bogor pada ' . $tgljam . '<br></h5>';
			// 	$pesan = $pesan . '<br>';
			// 	$pesan = $pesan . '<h7>Konsultasi pajak dapat disampaikan melalui nomor Whatsapp : 08111002021<br></h7>';
			// 	$pesan = $pesan . '<br>';
			// 	$pesan = $pesan . '<h7>Pajak Anda Membangun Kota Bogor<br></h7>';
			// 	$pesan = $pesan . '<h7>--- Hatur Nuhun ---<br></h7>';
			// 	$pesan = $pesan . '  </div>';
			// 	$pesan = $pesan . '</div>';
			// 	$pesan = $pesan . '</body>';
			// 	$pesan = $pesan . '</html>';
			// 	$content = $this->esppt->db_kirim_email($email, 'Notifikasi Login User e-SPPT PBB P2 Kota Bogor', $nama, $pesan, $idusr, $ket);
			// }
	
			// $nm1 = explode(',', strtolower($nama));
			// redirect(site_url() . 'app/index/' . base32_encode('Wilujeng sumping ' . $nm1[0]));
		}
}
