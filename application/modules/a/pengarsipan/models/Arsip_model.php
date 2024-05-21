<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class Arsip_model extends CI_Model
{
	public function get($opd_id,$tahun)
	{
		$get = $this->db->query("
			SELECT * FROM pengarsipan
			JOIN draft
			ON draft.surat_id = pengarsipan.surat_id
			LEFT JOIN aparatur
			ON aparatur.jabatan_id = draft.dibuat_id
			WHERE 
			aparatur.opd_id = '$opd_id'
			AND LEFT(draft.tanggal, 4) = '$tahun'
			ORDER BY arsip_id DESC
			");
		return $get;
	}
}