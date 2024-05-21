<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class Surat_model extends CI_Model
{

	public function get($opd_id,$tahun)
	{
		$query = $this->db->query("
			SELECT * FROM surat_masuk
			WHERE opd_id = '$opd_id'
			AND LEFT(diterima, 4) = '$tahun'
			ORDER BY diterima DESC, suratmasuk_id DESC
		");
		return $query;
	}

	public function insert_data($tabel,$data)
	{
		$insert = $this->db->insert($tabel, $data);
		return $insert;
	}

	public function delete_data($tabel,$where)
	{
		$delete = $this->db->delete($tabel,$where);
		return $delete;
	}

}