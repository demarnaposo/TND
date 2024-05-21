<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		header("X-XSS-Protection: 1; mode=block");
	}

	public function index()
	{
		$this->load->view('login');
	}

	public function cek()
	{

		$this->form_validation->set_rules('username','Username','required');
		$this->form_validation->set_rules('password','Password','required');
		$this->form_validation->set_rules('kode','kode','callback_cek_aritmatika');

		if($this->form_validation->run() === TRUE){
		$username = htmlentities($this->input->post('username'));
		$pass = htmlentities($this->input->post('password'));
		$password = sha1($pass);
		$tahun = htmlentities($this->input->post('tahun'));
		$captcha=$this->input->post('kode');

		$data = array(
			'username' => $username, 
			'password' => $password,
		);
		$cek = $this->db->get_where('users', $data)->num_rows();
		if ($cek > 0) {
			$this->db->select('aparatur.nama, aparatur.nik,opd.nama_pd,users.username, opd.email,aparatur.jabatan_id,jabatan.nama_jabatan,users.foto, users.level_id,aparatur.opd_id,users.users_id,aparatur.statusaparatur,jabatan.jabatan');
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
				'status' => $status
				);
				if ($status == "Pensiun")
				{
				$this->session->set_flashdata('access', 'Status User Sudah Pensiun!');
				redirect(site_url());
				}
				elseif ($status == "Meninggal") 
				{
				$this->session->set_flashdata('access', 'Status User Sudah Meninggal!');
				redirect(site_url());
				}
				elseif ($status == "Tidak Aktif") 
				{
				$this->session->set_flashdata('access', 'Status User Sudah Tidak Aktif!');
				redirect(site_url());
				}
				elseif ($level == "1") 
				{
					$this->session->set_userdata($session);
					redirect('master/perangkatdaerah');
				}
				else 
				{
				$this->session->set_userdata($session);
				redirect('home/dashboard');
				}
		}else{
			$this->session->set_flashdata('access', 'Username dan Password Salah!');
			redirect(site_url());
		}
	}else{
		$this->index();
	}
		
	}

	public function cek_aritmatika($str){
		if ($str == "") :
            $this->session->set_flashdata('cek_aritmatika', 'Hasil Belum Diisi');
            return FALSE;
        else :
            if ($str == $this->session->userdata('hasil')) {
                return TRUE;
            } else {
                $this->session->set_flashdata('cek_aritmatika', 'Hasil Belum Benar');
                return FALSE;
            }
        endif;
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect(site_url());
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

	public function login()
	{
		$user = 'kominfo';
		$pwd = '78HGygyhbjUGTDRtrtFY54cG35vVdxd78CYV8vcdfghYChjTDChjutD65xH3gC8j';
		$header = $this->json_rpc_header($user, $pwd);
		$urlTo = "https://sso-bsw.kotabogor.go.id/oulsso/websvc.php";

		$url_do_login = base_url('Welcome/do_login');

		// var_dump($url_do_login);
		// die;	

		// $url_form_login = "https://layanan-bapenda.kotabogor.go.id/api/oneuserlogin/tes/form_login.php";
		$url_form_login = "";

		$aplikasi = 'TNDE';
		$data = 'data=
	   {
		 "jsonrpc": "2.0", 
		 "method": "get_sso_token",
		 "params": 
		 { 
		   "php_is_native":"1",
		   "url_do_login":"' . $url_do_login . '",
		   "url_form_login":"' . $url_form_login . '",
		   "nama_aplikasi":"' . $aplikasi . '"
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
		// return $res;
		$json = json_decode($res);
		$status = $json->status;
		$message = $json->result->message;
		$token = $json->result->token;
		// var_dump($status);
		// die;
		if ($status == 'sukses') {
			$token = $json->result->token;
			header("location: https://sso-bsw.kotabogor.go.id/oulsso/app/do_sso/" . $token);
		} else {
			redirect('https://sso-bsw.kotabogor.go.id/oulsso/app/login' . rawurlencode($message));
		}
	}

}