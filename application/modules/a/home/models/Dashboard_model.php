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
		$suratkeluar = $this->db->query("
			SELECT * FROM disposisi_suratkeluar
			LEFT JOIN draft ON draft.surat_id = disposisi_suratkeluar.surat_id
			LEFT JOIN aparatur ON aparatur.jabatan_id = disposisi_suratkeluar.users_id
			WHERE disposisi_suratkeluar.users_id = '$jabatan_id'
			AND LEFT(draft.tanggal, 4) = '$tahun'
			AND disposisi_suratkeluar.status = 'Selesai'
		");
		return $suratkeluar;
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

	public function suratmasuk_disposisi($jabatan_id)
	{
		return $this->db->get_where('disposisi_suratmasuk', array('status' => 'Belum Selesai', 'aparatur_id' => $jabatan_id));
		
	}

	public function disposisi_surat($tahun,$opd_id)
	{
		$query = $this->db->query("
			SELECT * FROM disposisi_suratmasuk
			JOIN surat_masuk ON surat_masuk.suratmasuk_id = disposisi_suratmasuk.suratmasuk_id 
			LEFT JOIN aparatur ON aparatur.jabatan_id = disposisi_suratmasuk.users_id
			WHERE LEFT(diterima, 4) = '$tahun' AND aparatur.opd_id = '$opd_id' AND status = 'Selesai'
			GROUP BY disposisi_suratmasuk.suratmasuk_id ORDER BY dsuratmasuk_id DESC
		");
		return $query;
	}

// =================================================================================================================================
// =================================================================================================================================
// =================================================================================================================================

	public function informasi()
	{
		return $this->db->get_where('informasi', array('status' => 'Publish'));
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