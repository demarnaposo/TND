<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class Draft_model extends CI_Model
{
	public function get_verifikasi($tahun,$jabatan_id)
	{
		$query = $this->db->query("
			SELECT *
			FROM draft
			LEFT JOIN jabatan ON jabatan.jabatan_id = draft.dibuat_id
			WHERE LEFT(draft.tanggal, 4) = '$tahun'
			AND draft.verifikasi_id = '$jabatan_id'
			ORDER BY draft.id DESC
		");
		return $query;
	}

	public function insert_data($tabel,$data)
	{
		$insert = $this->db->insert($tabel, $data);
		return $insert;
	}

	public function edit_data($tabel,$where)
	{
		$edit = $this->db->get_where($tabel,$where);
		return $edit;
	}

	public function update_data($tabel,$data,$where)
	{
		$update = $this->db->update($tabel,$data,$where);
		return $update;
	}

	public function insert_disposisi($tabel,$data)
	{
		$insert = $this->db->insert_batch($tabel, $data);
		return $insert;
	}

	public function delete_data($tabel,$where)
	{
		$delete = $this->db->delete($tabel,$where);
		return $delete;
	}

	public function dari_untuk_aparatur($jabatan_id)
	{
		$this->db->from('aparatur');
		$this->db->join('jabatan', 'jabatan.jabatan_id = aparatur.jabatan_id');
		$this->db->where('aparatur.jabatan_id', $jabatan_id);
		return $this->db->get();
	}

	public function nomor_surat($table,$surat_id)
	{
		$query = $this->db->query("
				SELECT * FROM $table 
				LEFT JOIN kode_surat ON kode_surat.kodesurat_id = $table.kodesurat_id 
				WHERE $table.id = '$surat_id'
			");
		return $query;
	}

	public function penandatangan($opd_id,$jabatan_id)
	{
		if (empty($jabatan_id)) {
			$query = $this->db->query("
				SELECT * FROM aparatur
				LEFT JOIN jabatan ON jabatan.jabatan_id = aparatur.jabatan_id
				LEFT JOIN users ON users.aparatur_id = aparatur.aparatur_id
				WHERE aparatur.opd_id = '$opd_id' 
				AND users.level_id != 2
				AND users.level_id != 3
				AND users.level_id != 4
				AND users.level_id != 16
			");
		}else{
			$query = $this->db->query("
				SELECT * FROM aparatur
				LEFT JOIN jabatan ON jabatan.jabatan_id = aparatur.jabatan_id
				LEFT JOIN users ON users.aparatur_id = aparatur.aparatur_id
				WHERE aparatur.opd_id = '$opd_id' 
				AND aparatur.jabatan_id = '$jabatan_id'
				AND users.level_id != 2
				AND users.level_id != 3
				AND users.level_id != 4
				AND users.level_id != 16
			");
		}
		return $query;
	}

	public function get_tandatangan($jabatan_id)
	{
		$query = $this->db->query("
			SELECT * FROM penandatangan
			LEFT JOIN draft ON draft.surat_id = penandatangan.surat_id
			LEFT JOIN jabatan ON jabatan.jabatan_id = draft.dibuat_id 
			WHERE penandatangan.jabatan_id = $jabatan_id
			AND status = 'Belum Ditandatangani' 
			ORDER BY penandatangan.penandatangan_id DESC
		");
		return $query;
	}

	public function get_eksternal($surat_id)
	{
		$query = $this->db->query("
			SELECT * FROM disposisi_suratkeluar 
			JOIN eksternal_keluar ON users_id = id 
			WHERE surat_id = '$surat_id'
		");
		return $query;
	}
}