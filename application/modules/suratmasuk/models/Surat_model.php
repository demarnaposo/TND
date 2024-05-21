<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class Surat_model extends CI_Model
{

    public function get($limit,$start,$jabatanid,$tahun,$cari=null)
	{
		if($cari){
			$cari="dari LIKE '%$cari%' and dibuat_id='$jabatanid' or hal LIKE '%$cari%' and dibuat_id='$jabatanid' or nomor LIKE '%$cari%' and dibuat_id='$jabatanid' and";
		}
			$query = $this->db->query("
				SELECT * FROM surat_masuk
				WHERE $cari dibuat_id='$jabatanid'
				AND LEFT(tanggal, 4) = '$tahun' 
				ORDER BY tanggal DESC, suratmasuk_id DESC LIMIT $start, $limit
			");
		return $query;
	}

	//Start get surat hibah
	public function get_surathibah($limit,$start,$jabatanid,$tahun,$cari=null)
	{
		if($cari){
			$cari="dari LIKE '%$cari%' and dibuat_id='$jabatanid' or hal LIKE '%$cari%' and dibuat_id='$jabatanid' or nomor LIKE '%$cari%' and dibuat_id='$jabatanid' and";
		}
			$query = $this->db->query("
				SELECT * FROM surat_masuk
				WHERE $cari penerima LIKE '%sahabat%'
				AND LEFT(tanggal, 4) = '$tahun' 
				ORDER BY tanggal DESC, suratmasuk_id DESC LIMIT $start, $limit
			");
		return $query;
	}
	//Start get surat hibah

	//Start count surat hibah
	public function countSuratHibah($cari=NULL){
	
	
		$tahun=$this->session->userdata('tahun');
		if($cari){
			$cari="dari LIKE '%$cari%' or hal LIKE '%$cari%' or nomor LIKE '%$cari%' and";
		}
			$count = $this->db->query("
				SELECT * FROM surat_masuk
				WHERE $cari penerima LIKE '%sahabat%' AND LEFT(surat_masuk.tanggal, 4) = '$tahun'
			")->num_rows();

		return $count;
	}
	//End count surat hibah

	// Untuk mengambil data dari level 18 = Admin TU Sekretariat
	public function getlevel18($limit,$start,$levelid,$jabatanid,$tahun,$cari=null)
	{
		if($cari){
			$cari="a.dari LIKE '%$cari%' and a.dibuat_id='$jabatanid' or a.hal LIKE '%$cari%' and a.dibuat_id='$jabatanid' or a.nomor LIKE '%$cari%' and a.dibuat_id='$jabatanid' and";
		}
			$query = $this->db->query("
				SELECT * FROM surat_masuk a
				LEFT JOIN aparatur b ON b.jabatan_id=a.dibuat_id
				LEFT JOIN users c ON c.aparatur_id=b.aparatur_id
				WHERE $cari c.level_id=$levelid and a.dibuat_id='$jabatanid'
				AND LEFT(a.tanggal, 4) = '$tahun'
				ORDER BY a.tanggal DESC, a.suratmasuk_id DESC LIMIT $start, $limit
			");
		return $query;
	}
	
	public function insert_data($tabel,$data)
	{
		$insert = $this->db->insert($tabel, $data);
		return $insert;
	}
	
	public function get_global($opd_id)
	{
		$query = $this->db->query("
		SELECT * FROM aparatur a 
		LEFT JOIN opd b ON b.opd_id=a.opd_id
		LEFT JOIN jabatan c ON c.jabatan_id=a.jabatan_id
		LEFT JOIN users d ON d.aparatur_id=a.aparatur_id
		WHERE d.level_id='4' ORDER BY b.opd_id ASC
		");
		return $query;
	}

	// Filter Option Kelurahan Sesuai Kecamatan
	public function get_bosel()
	{
			$query = $this->db->query("
			SELECT * FROM jabatan a LEFT JOIN opd b ON b.opd_id=a.opd_id WHERE a.atasan_id='612' and b.opd_id !=49
			");
		return $query;
	}
	public function get_bobar()
	{
			$query = $this->db->query("
			SELECT * FROM jabatan a LEFT JOIN opd b ON b.opd_id=a.opd_id WHERE a.atasan_id='622'
			");
		return $query;
	}
	public function get_boteng()
	{
			$query = $this->db->query("
			SELECT * FROM jabatan a LEFT JOIN opd b ON b.opd_id=a.opd_id WHERE a.atasan_id='601'
			");
		return $query;
	}
	public function get_botim()
	{
			$query = $this->db->query("
			SELECT * FROM jabatan a LEFT JOIN opd b ON b.opd_id=a.opd_id WHERE a.atasan_id='645'
			");
		return $query;
	}
	public function get_bout()
	{
			$query = $this->db->query("
			SELECT * FROM jabatan a LEFT JOIN opd b ON b.opd_id=a.opd_id WHERE a.atasan_id='634'
			");
		return $query;
	}
	public function get_tansar()
	{
			$query = $this->db->query("
			SELECT * FROM jabatan a LEFT JOIN opd b ON b.opd_id=a.opd_id WHERE a.atasan_id='656' and b.opd_id !=53
			");
		return $query;
	}

	public function get_aparaturglobal()
	{
		$query = $this->db->query("
		SELECT * FROM aparatur a 
		LEFT JOIN jabatan c ON c.jabatan_id=a.jabatan_id
		LEFT JOIN opd b ON b.opd_id=a.opd_id
		LEFT JOIN users d ON d.aparatur_id=a.aparatur_id
		WHERE d.level_id='4'
		ORDER By b.opd_id ASC
		");

// 		foreach($query as $key=>$ats){
// 			$jabatanid=$ats->jabatan_id;
// 			$queryatasan=$this->db->query("SELECT a.nama_jabatan FROM jabatan a
// 			WHERE a.atasan_id IN ($jabatanid)");
// 		}

		return $query;
	}

	public function get_atasan($jabatan_id)
	{
		// $query = $this->db->query("
		// 	SELECT * FROM aparatur 
		// 	JOIN jabatan ON aparatur.jabatan_id = jabatan.jabatan_id 
		// 	WHERE jabatan.atasan_id = '$jabatan_id' 
		// 	AND jabatan.opd_id = '$opd_id'
		// 	AND aparatur.nip != '-'
		// ");

		$query = $this->db->query("SELECT * FROM jabatan WHERE jabatan_id='$jabatan_id'");
		return $query;
	}

	public function countSuratMasuk($cari=NULL){
		$jabatanid=$this->session->userdata('jabatan_id');
		$tahun=$this->session->userdata('tahun');
		if($cari){
			$cari="dari LIKE '%$cari%' or hal LIKE '%$cari%' or nomor LIKE '%$cari%' and";
		}
			$count = $this->db->query("
				SELECT * FROM surat_masuk
				WHERE $cari dibuat_id='$jabatanid' AND LEFT(surat_masuk.tanggal, 4) = '$tahun'
			")->num_rows();

		return $count;
	}

	public function edit_data($id)
	{
		$edit = $this->db->query("
			SELECT * FROM surat_masuk
			LEFT JOIN retensi_arsip ON surat_masuk.suratmasuk_id = retensi_arsip.surat_id
			LEFT JOIN jra ON retensi_arsip.jra_id = jra.id
			WHERE surat_masuk.suratmasuk_id='$id'
		");
		return $edit;
	}
	public function update_data($tabel,$data,$where)
	{
		$update = $this->db->update($tabel,$data,$where);
		return $update;
	}

	public function insert_aparatur($tabel,$data)
	{
		$insert = $this->db->insert_batch($tabel, $data);
		return $insert;
	}	

	public function delete_data($tabel,$where)
	{
		$delete = $this->db->delete($tabel,$where);
		return $delete;
	}

}