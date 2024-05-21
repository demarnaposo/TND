<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class Edaran_model extends CI_Model
{
	public function get_data($tahun,$opd_id,$jabatan_id)
	{
		$query = $this->db->query("
			SELECT * FROM draft
			LEFT JOIN surat_edaran
			ON draft.surat_id = surat_edaran.id
			LEFT JOIN opd
			ON opd.opd_id = surat_edaran.opd_id
			LEFT JOIN aparatur
			ON aparatur.jabatan_id = draft.penandatangan_id
			WHERE surat_edaran.opd_id = '$opd_id'
			AND LEFT(surat_edaran.tanggal, 4) = '$tahun'
			AND draft.dibuat_id = '$jabatan_id'
			ORDER BY surat_edaran.tanggal DESC, LENGTH(surat_edaran.id) DESC, surat_edaran.id DESC
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

	public function edit_data($id)
	{
		$edit = $this->db->query("
			SELECT * FROM draft
			LEFT JOIN surat_edaran
			ON draft.surat_id = surat_edaran.id
			LEFT JOIN opd
			ON opd.opd_id = surat_edaran.opd_id
			LEFT JOIN aparatur
			ON aparatur.jabatan_id = draft.penandatangan_id
			WHERE surat_edaran.id = '$id'
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