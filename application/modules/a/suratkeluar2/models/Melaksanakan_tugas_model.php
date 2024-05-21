<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class Melaksanakan_tugas_model extends CI_Model
{
	public function get_data($tahun,$opd_id,$jabatan_id,$pegawai_id)
	{
		$query = $this->db->query("
			SELECT x.id, x.surat_id, x.tanggal, x.dibuat_id, x.penandatangan_id, x.verifikasi_id, x.nama_surat,	a.id, a.opd_id, a.kop_id, a.kodesurat_id, a.tanggal, a.nomor, a.pegawai_id, a.dasarsk, a.nomorsk, a.tmt, a.tugas, 			
			b.opd_id, b.nama_pd, b.kode_pd, b.alamat, b.telp, b.email, b.alamat_website, c.aparatur_id, c.jabatan_id, c.nip AS nippegawai, c.nama AS namapegawai, c.pangkat AS pangkatpegawai, c.golongan AS golonganpegawai, d.jabatan_id, d.nama_jabatan AS jabatanpegawai, d.jabatan, e.surat_id, e.jabatan_id, e.nama AS namapejabat, e.status, f.jabatan_id, f.nama_jabatan AS namajabatanpejabat, f.jabatan AS jabatanpejabat, g.nip AS nippejabat, g.pangkat AS pangkatpejabat 			
			FROM draft x 
			LEFT JOIN surat_melaksanakantugas a ON x.surat_id = a.id 
			LEFT JOIN opd b ON a.opd_id = b.opd_id	
			LEFT JOIN aparatur c ON a.pegawai_id = c.aparatur_id 
			LEFT JOIN jabatan d ON c.jabatan_id = d.jabatan_id 
			LEFT JOIN penandatangan e ON a.id = e.surat_id 
			LEFT JOIN jabatan f ON e.jabatan_id = f.jabatan_id 
			LEFT JOIN aparatur g ON e.jabatan_id = g.jabatan_id
			WHERE a.opd_id = '$opd_id'
			AND LEFT(a.tanggal, 4) = '$tahun'
			AND x.dibuat_id = '$jabatan_id'			
			ORDER BY a.tanggal DESC, LENGTH(a.id) DESC, a.id DESC
		");
		return $query;
	}

	public function get_id()
	{
		$query = $this->db->query("SELECT * FROM surat_melaksanakantugas ORDER BY LENGTH(id) ASC, id ASC");
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
			LEFT JOIN surat_melaksanakantugas
			ON draft.surat_id = surat_melaksanakantugas.id
			LEFT JOIN opd
			ON opd.opd_id = surat_melaksanakantugas.opd_id
			LEFT JOIN aparatur
			ON aparatur.jabatan_id = draft.penandatangan_id
			WHERE surat_melaksanakantugas.opd_id = '$opd_id' AND surat_melaksanakantugas.id = '$id'
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