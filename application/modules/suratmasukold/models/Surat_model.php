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
			$cari="and dibuat_id='$jabatanid' and dari LIKE '%$cari%' and dibuat_id='$jabatanid' or hal LIKE '%$cari%' and dibuat_id='$jabatanid' or nomor LIKE '%$cari%' and dibuat_id='$jabatanid'";
		}
			$query = $this->db->query("
				SELECT * FROM surat_masuk
				WHERE dibuat_id='$jabatanid'
				AND LEFT(diterima, 4) = '$tahun' $cari
				ORDER BY diterima DESC, suratmasuk_id DESC LIMIT $start, $limit
			");
		return $query;
	}

	// Untuk mengambil data dari level 18 = Admin TU Sekretariat
	public function getlevel18($limit,$start,$levelid,$jabatanid,$tahun,$cari=null)
	{
		if($cari){
			$cari="and a.dari LIKE '%$cari%' or a.hal LIKE '%$cari%' or a.nomor LIKE '%$cari%' and dibuat_id='$jabatanid'";
		}
			$query = $this->db->query("
				SELECT * FROM surat_masuk a
				LEFT JOIN aparatur b ON b.jabatan_id=a.dibuat_id
				LEFT JOIN users c ON c.aparatur_id=b.aparatur_id
				WHERE c.level_id=$levelid
				AND LEFT(a.diterima, 4) = '$tahun' $cari
				ORDER BY a.diterima DESC, a.suratmasuk_id DESC LIMIT $start, $limit
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

	public function countSuratMasuk($dari=null,$tanggal=null,$hal=null,$nomor=null){
		$jabatanid=$this->session->userdata('jabatan_id');
		$count=$this->db->query("SELECT a.suratmasuk_id FROM surat_masuk a WHERE a.dibuat_id='$jabatanid'")->num_rows();

		return $count;
	}

	public function edit_data($id)
	{
		$edit = $this->db->query("
		SELECT * FROM surat_masuk
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