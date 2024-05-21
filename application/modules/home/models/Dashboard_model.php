<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */
class Dashboard_model extends CI_Model
{
	public function draft($jabatan_id,$tahun)
	{ 
		return $this->db->query("SELECT * FROM draft WHERE verifikasi_id = '$jabatan_id' AND LEFT(tanggal, 4) = '$tahun'"); 
	}

	public function pengajuansurat($jabatan_id,$tahun)
	{
		return $this->db->query("SELECT * FROM draft WHERE dibuat_id = '$jabatan_id' AND LEFT(tanggal, 4) = '$tahun'");
		
	}

	public function tandatangan($jabatan_id)
	{
		return $this->db->get_where('penandatangan', array('jabatan_id' => $jabatan_id, 'status' => 'Belum Ditandatangani'));
	}

// =================================================================================================================================
// =================================================================================================================================
// =================================================================================================================================

	public function suratmasuk_tu($tahun,$jabatan_id)
	{
		// $suratkeluar = $this->db->get_where('disposisi_suratkeluar', array('users_id' => $jabatan_id, 'status' => 'Selesai'))->num_rows();
		$suratmasuk_tu = $this->db->query("
			SELECT * FROM disposisi_suratmasuk
			JOIN surat_masuk ON surat_masuk.suratmasuk_id = disposisi_suratmasuk.suratmasuk_id
			JOIN aparatur ON aparatur.jabatan_id = disposisi_suratmasuk.aparatur_id
			WHERE disposisi_suratmasuk.aparatur_id ='$jabatan_id'
			AND status = 'Belum Selesai'
			GROUP BY disposisi_suratmasuk.suratmasuk_id
			ORDER BY surat_masuk.diterima DESC, LENGTH(surat_masuk.suratmasuk_id) DESC, disposisi_suratmasuk.dsuratmasuk_id DESC							
			
		");
		return $suratmasuk_tu;
	}

	public function suratmasuk_tu_didisposisikan($opd_id,$tahun)
	{
		$didisposisikan = $this->db->query("
			SELECT * FROM surat_masuk
			WHERE opd_id = '$opd_id'
			AND LEFT(diterima, 4) = '$tahun'
			ORDER BY diterima DESC, suratmasuk_id DESC
		");
		return $didisposisikan;
	}

	public function suratmasuk_disposisi($jabatan_id,$tahun)
	{
		$query=$this->db->query("SELECT *,surat_masuk.tanggal as tglsurat, surat_masuk.lampiran_lain,surat_masuk.lampiran FROM disposisi_suratmasuk
		JOIN surat_masuk ON surat_masuk.suratmasuk_id = disposisi_suratmasuk.suratmasuk_id
		JOIN aparatur ON aparatur.jabatan_id = disposisi_suratmasuk.aparatur_id
		WHERE disposisi_suratmasuk.status = 'Belum Selesai'
		AND disposisi_suratmasuk.aparatur_id = '$jabatan_id' AND LEFT(surat_masuk.diterima, 4) = '$tahun'
		GROUP BY disposisi_suratmasuk.suratmasuk_id
		ORDER BY surat_masuk.diterima DESC, LENGTH(surat_masuk.suratmasuk_id) DESC, disposisi_suratmasuk.dsuratmasuk_id DESC");
		return $query;		
	}

	public function disposisi_surat($tahun,$jabatan_id)
	{
		$query = $this->db->query("
			SELECT * FROM disposisi_suratmasuk
			JOIN surat_masuk ON surat_masuk.suratmasuk_id = disposisi_suratmasuk.suratmasuk_id 
			LEFT JOIN aparatur ON aparatur.jabatan_id = disposisi_suratmasuk.users_id
			WHERE LEFT(diterima, 4) = '$tahun' AND surat_masuk.dibuat_id = '$jabatan_id' AND status = 'Selesai'
			GROUP BY disposisi_suratmasuk.suratmasuk_id ORDER BY dsuratmasuk_id DESC
		");
		return $query;
	}

// =================================================================================================================================
// =================================================================================================================================
// =================================================================================================================================

	public function informasi()
	{
		return $this->db->order_by('tanggal', 'DESC')->get_where('informasi', array('status' => 'Publish'));
	}

// =================================================================================================================================
// =================================================================================================================================
// =================================================================================================================================

	public function draft_administrator($tahun)
	{ 
		return $this->db->query("SELECT * FROM draft WHERE LEFT(tanggal, 4) = '$tahun'"); 
	}

	public function suratkeluar_administrator($tahun)
	{ 
		return $this->db->query("SELECT * FROM draft WHERE LEFT(tanggal, 4) = '$tahun'"); 
	}

	public function suratmasuk_administrator($tahun)
	{ 
		return $this->db->query("SELECT * FROM draft WHERE LEFT(tanggal, 4) = '$tahun'"); 
	}

}