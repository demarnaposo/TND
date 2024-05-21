<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class Inbox_model extends CI_Model
{
	public function get_disposisisurat($jabatan_id,$tahun)
	{
		// $query = $this->db->query("
		// 	SELECT * FROM disposisi_suratkeluar
		// 	LEFT JOIN draft ON draft.surat_id = disposisi_suratkeluar.surat_id
		// 	LEFT JOIN aparatur ON aparatur.jabatan_id = disposisi_suratkeluar.users_id
		// 	WHERE disposisi_suratkeluar.users_id = '$jabatan_id'
		// 	AND aparatur.opd_id = '$opd_id'
		// 	AND LEFT(draft.tanggal, 4) = '$tahun'
		// 	AND disposisi_suratkeluar.status = 'Selesai'
		// 	ORDER BY draft.tanggal DESC, disposisi_suratkeluar.dsuratkeluar_id DESC
		// ");

		$query = $this->db->query("
			SELECT disposisi_suratmasuk.suratmasuk_id FROM disposisi_suratmasuk
			JOIN surat_masuk ON surat_masuk.suratmasuk_id = disposisi_suratmasuk.suratmasuk_id
			WHERE disposisi_suratmasuk.aparatur_id = '$jabatan_id' AND disposisi_suratmasuk.status='Belum Selesai'
			AND LEFT(surat_masuk.diterima, 4) = '$tahun'
		");
		return $query;
	}

	public function get_surattnd($jabatan_id,$tahun,$opd_id){
		$query = $this->db->query("
			SELECT *,draft.tanggal as tglsurat, surat_undangan.hal as halundangan,surat_undangan.nomor as nomorundangan,
			surat_biasa.hal as halbiasa,surat_biasa.nomor as nomorbiasa FROM disposisi_suratkeluar
			LEFT JOIN draft ON draft.surat_id = disposisi_suratkeluar.surat_id
			LEFT JOIN surat_undangan ON surat_undangan.id=disposisi_suratkeluar.surat_id
			LEFT JOIN surat_biasa ON surat_biasa.id=disposisi_suratkeluar.surat_id
			LEFT JOIN aparatur ON aparatur.jabatan_id = disposisi_suratkeluar.users_id
			WHERE disposisi_suratkeluar.users_id = '$jabatan_id'
			AND aparatur.opd_id = '$opd_id'
			AND LEFT(draft.tanggal, 4) = '$tahun'
			AND disposisi_suratkeluar.status = 'Selesai'
			ORDER BY draft.tanggal DESC, disposisi_suratkeluar.dsuratkeluar_id DESC
		");
		return $query;
	}

	public function get_disposisi($cari = null,$limit,$start,$jabatan_id,$tahun)
	{
		if($cari){
			$cari="and surat_masuk.dari LIKE '%$cari%' or surat_masuk.hal LIKE '%$cari%' or surat_masuk.nomor LIKE '%$cari%'";
		}
		$query = $this->db->query("
			SELECT *,surat_masuk.tanggal as tglsurat, surat_masuk.lampiran_lain,surat_masuk.lampiran FROM disposisi_suratmasuk
            JOIN surat_masuk ON surat_masuk.suratmasuk_id = disposisi_suratmasuk.suratmasuk_id
            JOIN aparatur ON aparatur.jabatan_id = disposisi_suratmasuk.aparatur_id
            WHERE disposisi_suratmasuk.status = 'Belum Selesai' $cari
            AND disposisi_suratmasuk.aparatur_id = '$jabatan_id'
            GROUP BY disposisi_suratmasuk.suratmasuk_id
            ORDER BY surat_masuk.diterima DESC, LENGTH(surat_masuk.suratmasuk_id) DESC, disposisi_suratmasuk.dsuratmasuk_id DESC LIMIT $start, $limit
		");
		return $query;
	}
	public function countdsuratmasuk($jabatan_id,$tahun)
	{	
		$query = $this->db->query("
			SELECT *,surat_masuk.tanggal as tglsurat, surat_masuk.lampiran_lain,surat_masuk.lampiran FROM disposisi_suratmasuk
            JOIN surat_masuk ON surat_masuk.suratmasuk_id = disposisi_suratmasuk.suratmasuk_id
            JOIN aparatur ON aparatur.jabatan_id = disposisi_suratmasuk.aparatur_id
            WHERE disposisi_suratmasuk.status = 'Belum Selesai'
            AND disposisi_suratmasuk.aparatur_id = '$jabatan_id'
            GROUP BY disposisi_suratmasuk.suratmasuk_id
            ORDER BY surat_masuk.diterima DESC, LENGTH(surat_masuk.suratmasuk_id) DESC, disposisi_suratmasuk.dsuratmasuk_id DESC
		")->num_rows();
		return $query;
	}

	public function get_sudahdisposisi($cari = null,$limit,$start,$jabatan_id,$tahun)
	{
		if($cari){
			$cari="surat_masuk.dari LIKE '%$cari%' or surat_masuk.hal LIKE '%$cari%' or surat_masuk.nomor LIKE '%$cari%' and";
		}
		$query = $this->db->query("
		SELECT * FROM disposisi_suratmasuk LEFT JOIN surat_masuk ON surat_masuk.suratmasuk_id=disposisi_suratmasuk.suratmasuk_id WHERE $cari disposisi_suratmasuk.users_id='$jabatan_id' GROUP BY disposisi_suratmasuk.suratmasuk_id ORDER BY surat_masuk.diterima DESC, LENGTH(surat_masuk.suratmasuk_id) DESC, disposisi_suratmasuk.dsuratmasuk_id DESC LIMIT $start, $limit
		");
		return $query;
	}

	public function countsudahdisposisi($cari = null,$jabatan_id,$tahun)
	{
		if($cari){
			$cari="surat_masuk.dari LIKE '%$cari%' or surat_masuk.hal LIKE '%$cari%' or surat_masuk.nomor LIKE '%$cari%' and";
		}
		$query = $this->db->query("
		SELECT surat_masuk.suratmasuk_id FROM disposisi_suratmasuk LEFT JOIN surat_masuk ON surat_masuk.suratmasuk_id=disposisi_suratmasuk.suratmasuk_id WHERE $cari disposisi_suratmasuk.users_id='$jabatan_id' GROUP BY disposisi_suratmasuk.suratmasuk_id ORDER BY surat_masuk.diterima DESC, LENGTH(surat_masuk.suratmasuk_id) DESC, disposisi_suratmasuk.dsuratmasuk_id DESC
		")->num_rows();
		return $query;
	}
	public function get_sudahdisposisiseknas($cari = null,$limit,$start,$jabatan_id,$tahun)
	{
		$query = $this->db->query("
		SELECT * FROM disposisi_suratmasuk LEFT JOIN surat_masuk ON surat_masuk.suratmasuk_id=disposisi_suratmasuk.suratmasuk_id WHERE disposisi_suratmasuk.users_id='$jabatan_id' GROUP BY disposisi_suratmasuk.suratmasuk_id ORDER BY surat_masuk.diterima DESC, LENGTH(surat_masuk.suratmasuk_id) DESC, disposisi_suratmasuk.dsuratmasuk_id DESC LIMIT $start, $limit
		");
		return $query;
	}

	public function countsudahdisposisiseknas($jabatan_id,$tahun)
	{
		$query = $this->db->query("
		SELECT * FROM disposisi_suratmasuk LEFT JOIN surat_masuk ON surat_masuk.suratmasuk_id=disposisi_suratmasuk.suratmasuk_id WHERE disposisi_suratmasuk.users_id='$jabatan_id' GROUP BY disposisi_suratmasuk.suratmasuk_id ORDER BY surat_masuk.diterima DESC, LENGTH(surat_masuk.suratmasuk_id) DESC, disposisi_suratmasuk.dsuratmasuk_id DESC
		")->num_rows();
		return $query;
	}

	public function get_selesai_tu($tahun,$jabatanid)
	{
		$query = $this->db->query("
		SELECT * FROM surat_masuk a
		LEFT JOIN disposisi_suratmasuk b ON b.suratmasuk_id=a.suratmasuk_id
		LEFT JOIN aparatur c ON c.jabatan_id=b.users_id
		WHERE b.`status`='Selesai' AND LEFT(a.diterima,4)='$tahun' AND a.dibuat_id='$jabatanid'
		GROUP BY b.suratmasuk_id
		ORDER BY a.diterima desc, LENGTH(b.dsuratmasuk_id) DESC
		");
		return $query;
	}

	public function get_selesai_aparatur()
	{
		$query = $this->db->query("
			SELECT * FROM disposisi_suratmasuk WHERE status = 'Selesai'
			Order By tanggal desc
		");

		return $query;
	}

	public function get_bawahan($jabatan_id,$opd_id)
	{
		// $query = $this->db->query("
		// 	SELECT * FROM aparatur 
		// 	JOIN jabatan ON aparatur.jabatan_id = jabatan.jabatan_id 
		// 	WHERE jabatan.atasan_id = '$jabatan_id' 
		// 	AND jabatan.opd_id = '$opd_id'
		// 	AND aparatur.nip != '-'
		// ");

		$query = $this->db->query("
			SELECT * FROM aparatur 
			JOIN jabatan ON aparatur.jabatan_id = jabatan.jabatan_id
			LEFT JOIN users ON users.aparatur_id=aparatur.aparatur_id
			LEFT JOIN level ON level.level_id=users.level_id
			WHERE jabatan.atasan_id = '$jabatan_id'
			AND jabatan.opd_id = '$opd_id'
			AND aparatur.nip != '-'
			ORDER BY level.level_id ASC
		");
		return $query;
	}

	public function get_global($opd_id)
	{
		$query = $this->db->query("
			SELECT * FROM aparatur 
			JOIN jabatan ON aparatur.jabatan_id = jabatan.jabatan_id 
			WHERE jabatan.opd_id = '$opd_id'
			AND aparatur.nip != '-'
		");
		return $query;
	}
	public function get_aparaturglobal()
	{
		$query = $this->db->query("
		SELECT *
		FROM aparatur a
		LEFT JOIN jabatan c ON c.jabatan_id=a.jabatan_id
		LEFT JOIN opd b ON b.opd_id=a.opd_id
		LEFT JOIN users d ON d.aparatur_id=a.aparatur_id
		WHERE d.level_id='4'
		");

		// foreach($query as $key=>$ats){
		// 	$jabatanid=$ats->jabatan_id;
		// 	$queryatasan=$this->db->query("SELECT a.nama_jabatan FROM jabatan a
		// 	WHERE a.atasan_id IN ($jabatanid)");
		// }

		return $query;
	}

	public function insert_data($tabel,$data)
	{
		$insert = $this->db->insert($tabel, $data);
		return $insert;
	}

	public function update_data($tabel,$data,$where)
	{
		$update = $this->db->update($tabel,$data,$where);
		return $update;
	}

	public function delete_data($tabel,$where)
	{
		$delete = $this->db->delete($tabel,$where);
		return $delete;
	}

	public function insert_aparatur($tabel,$data)
	{
		$insert = $this->db->insert_batch($tabel, $data);
		return $insert;
	}
}