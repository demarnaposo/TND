<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Coba extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();

    $this->load->library('token');
  }
  public function wa()
  {
    $beritaacara = $this->db->query("SELECT jabatan.nama_jabatan, aparatur.nama, aparatur.nohp, count(*) as jumlah FROM `penandatangan` INNER join jabatan on penandatangan.jabatan_id=jabatan.jabatan_id INNER join aparatur on aparatur.jabatan_id=jabatan.jabatan_id WHERE status='Belum Ditandatangani' and aparatur.nohp!= 'NULL' GROUP BY jabatan.jabatan_id")->result();
    foreach ($beritaacara as $key => $h) {
      // echo $h->nama_jabatan;

      $msg = "Ada Surat yang Harus di TTD Diaplikasi TND, dengan detail sbb :
	    Nama : $h->nama
	    Jabatan : $h->nama_jabatan
        Surat Yang Perlu di TTD : $h->jumlah Surat
        ------------------------------------
        silahkan klik link berikut atau copy paste link dan buka di browser anda : 
        https://tnd.kotabogor.go.id/
        ";

      // $pegawai = pegawai::findOrFail($bukutamu->pegawai_id);
      $token = 'oLqCnFErqHJBbIVdDg5bf5yX9vxikD6vxPimIm2iOEiHFo3iEH';
      $phone = str_replace("+", "", $h->nohp);
      $message = $msg;
      //$url = 'http://ruangwa.id/v2/api/send-message.php';
      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://app.ruangwa.id/api/send_message',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => 'token=' . $token . '&number=' . $phone . '&message=' . $message . '',
        CURLOPT_HTTPHEADER => array(
          'Accept-Charset: application/json',
          'Content-Type: application/x-www-form-urlencoded',
          'Cookie: ci_session=03342e70e2f58252245284e37670a2ab9fc05384'
        ),
      ));
      $response = curl_exec($curl);
      curl_close($curl);
    }
  }

  public function ssh_test()
  {
    //  $this->db->query("select * from ");
    $msg = "Ada Surat yang Harus di TTD Diaplikasi TND, dengan detail sbb :
        Surat Yang Perlu di TTD : 1 Surat
        ------------------------------------
        silahkan klik link berikut atau copy paste link dan buka di browser anda : 
        https://tnd.kotabogor.go.id/
        ";

    // $pegawai = pegawai::findOrFail($bukutamu->pegawai_id);
    $token = 'oLqCnFErqHJBbIVdDg5bf5yX9vxikD6vxPimIm2iOEiHFo3iEH';
    $phone = str_replace("+", "", '628176772212');
    $message = $msg;
    //$url = 'http://ruangwa.id/v2/api/send-message.php';
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://app.ruangwa.id/api/send_message',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => 'token=' . $token . '&number=' . $phone . '&message=' . $message . '',
      CURLOPT_HTTPHEADER => array(
        'Accept-Charset: application/json',
        'Content-Type: application/x-www-form-urlencoded',
        'Cookie: ci_session=03342e70e2f58252245284e37670a2ab9fc05384'
      ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
  }

  public function asinan_test()
  {
    $newFileName  = $this->input->get('surat_id') . '.pdf';
    $surt         = $this->input->get('surat_id');

    $curl = curl_init();

    curl_setopt_array($curl, array(
      //  CURLOPT_URL => 'https://api.lite.smartgov.teknolab.id/integration/client/ticket/document-signed',
      CURLOPT_URL => 'https://ticket-api.citigov.id/integration/client/ticket/document-signed',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => array('symetric_key' => 'AHm0GDp3vL2vytYkEzmgyL3ZPD6shUD0', 'surat_id' => $surt, 'dokumen_url[0]' => 'https://tnd.kotabogor.go.id/uploads/SIGNED/' . $newFileName),

    ));

    $response = curl_exec($curl);

    var_dump($response);die;

    curl_close($curl);
  }
}
