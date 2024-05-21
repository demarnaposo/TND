<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class Inbox_model extends CI_Model
{
	public function get_disposisisurat($jabatan_id,$opd_id,$tahun)
	{
		$query = $this->db->query("
			SELECT * FROM disposisi_suratkeluar
			LEFT JOIN draft ON draft.surat_id = disposisi_suratkeluar.surat_id
			LEFT JOIN aparatur ON aparatur.jabatan_id = disposisi_suratkeluar.users_id
			WHERE disposisi_suratkeluar.users_id = '$jabatan_id'
			AND aparatur.opd_id = '$opd_id'
			AND LEFT(draft.tanggal, 4) = '$tahun'
			AND disposisi_suratkeluar.status = 'Selesai'
			ORDER BY draft.tanggal DESC, disposisi_suratkeluar.dsuratkeluar_id DESC
		");
		return $query;
	}

	public function get_disposisi($jabatan_id,$tahun)
	{
		$query = $this->db->query("
			SELECT disposisi_suratmasuk.suratmasuk_id FROM disposisi_suratmasuk
			JOIN surat_masuk ON surat_masuk.suratmasuk_id = disposisi_suratmasuk.suratmasuk_id
			WHERE disposisi_suratmasuk.aparatur_id = '$jabatan_id'
			AND LEFT(surat_masuk.diterima, 4) = '$tahun'
		");
		return $query;
	}

	public function get_selesai_tu($tahun,$opd_id)
	{
		$query = $this->db->query("
			SELECT * FROM disposisi_suratmasuk
			JOIN surat_masuk ON surat_masuk.suratmasuk_id = disposisi_suratmasuk.suratmasuk_id
			JOIN aparatur ON aparatur.jabatan_id = disposisi_suratmasuk.users_id
			WHERE disposisi_suratmasuk.status = 'Selesai'
			AND LEFT(surat_masuk.diterima, 4) = '$tahun'
			AND aparatur.opd_id = '$opd_id'
			GROUP BY disposisi_suratmasuk.suratmasuk_id
			ORDER BY LENGTH(disposisi_suratmasuk.dsuratmasuk_id) DESC
		");
		return $query;
	}

	public function get_selesai_aparatur()
	{
		$query = $this->db->query("
			SELECT * FROM disposisi_suratmasuk WHERE status = 'Selesai'
		");

		return $query;
	}

	public function get_bawahan($jabatan_id,$opd_id)
	{
		$query = $this->db->query("
			SELECT * FROM aparatur 
			JOIN jabatan ON aparatur.jabatan_id = jabatan.jabatan_id 
			WHERE jabatan.atasan_id = '$jabatan_id' 
			AND jabatan.opd_id = '$opd_id'
			AND aparatur.nip != '-'
		");
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