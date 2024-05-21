<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class Biasa_model extends CI_Model
{

	public function get_data($tahun,$opd_id,$jabatan_id)
	{
		$query = $this->db->query("
			SELECT * FROM draft
			LEFT JOIN surat_biasa
			ON draft.surat_id = surat_biasa.id
			LEFT JOIN opd
			ON opd.opd_id = surat_biasa.opd_id
			LEFT JOIN aparatur
			ON aparatur.jabatan_id = draft.penandatangan_id
			LEFT JOIN kode_surat
			ON kode_surat.kodesurat_id = surat_biasa.kodesurat_id
			WHERE surat_biasa.opd_id = '$opd_id'
			AND LEFT(surat_biasa.tanggal, 4) = '$tahun'
			AND draft.dibuat_id = '$jabatan_id'
			ORDER BY surat_biasa.tanggal DESC, LENGTH(surat_biasa.id) DESC, surat_biasa.id DESC
		");
		return $query;
	}

	public function get_id($table)
	{
		$query = $this->db->query("SELECT * FROM $table ORDER BY LENGTH(id) ASC, id ASC");
		return $query;
	}
	
	public function insert_data($tabel,$data)
	{
		$insert = $this->db->insert($tabel, $data);
		return $insert;
	}

	public function edit_data($id,$opd_id)
	{
		$edit = $this->db->query("
			SELECT * FROM draft
			LEFT JOIN surat_biasa
			ON draft.surat_id = surat_biasa.id
			LEFT JOIN opd
			ON opd.opd_id = surat_biasa.opd_id
			LEFT JOIN aparatur
			ON aparatur.jabatan_id = draft.penandatangan_id
			LEFT JOIN kode_surat
			ON kode_surat.kodesurat_id = surat_biasa.kodesurat_id
			WHERE surat_biasa.opd_id = '$opd_id'
			AND surat_biasa.id = '$id'
		");
		return $edit;
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
	
}