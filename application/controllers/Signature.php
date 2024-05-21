<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Signature extends CI_Controller
{
	/**
	 * Construct 
	 */
	public function __construct()
	{
		parent::__construct(); 

		$this->load->library('esign_api_gateway'); 
	}

	/**
	 * Do Signature Document 
	 * 
	 * @return json
	 */
	public function index()
	{
		$this->esign_api_gateway->execute(); 
		
		
	}

	public function coba_send()
	{
		$con = ssh2_connect('172.16.2.172','8022');
		ssh2_auth_password($con, 'kominfo', 'k0m1nf0#');
		$sftp = ssh2_sftp($con);
		$local_file = 'https://tnd.kotabogor.go.id/assets/lampiransuratmasuk/20211112131233.pdf';
		$remote_file = '/var/www/html/kominfo';
		if(ssh2_scp_send($con, $local_file, $remote_file, 777)){
			echo "berhasil";
		}else{
			echo "gagal";exit;
		}
	}


	public function ttd(){
	$penandatangan_id = $this->input->post('penandatangan_id');
		$surat_id = $this->input->post('surat_id');
		$username = $this->session->userdata('username');
		$passpress = $this->input->post('passphrase');

		if ($penandatangan_id && $surat_id && $username && $passpress) {

			$this->db->where('penandatangan_id', $penandatangan_id);
			$this->db->update('penandatangan', [
				'status' => 'Sudah Ditandatangani'
			]);

			$data = $this->db->insert('penandatangan_history', [
				'penandatangan_id' => $penandatangan_id, 
				'surat_id' => $surat_id,
				'username' => $username,
				'passphrase' => $passpress,
				'created_at' => date('Y-m-d H:i:s'),
			]); 
		} else {
			return $this->output
				->set_status_header(400)
				->set_content_type('application/json')
				->set_output(json_encode(array('error' => 'Mohon maaf terjadi kesalahan dalam sistem')));
			// ->set_output(json_encode(array('error' => array( 'p_id' => $penandatangan_id, 's_id' => $surat_id, 'u_name' => $username, 'passpres' => $passpress ))));
		}
	}
}