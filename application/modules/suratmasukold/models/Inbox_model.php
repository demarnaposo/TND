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
			surat_edaran.tentang as haledaran,surat_edaran.nomor as nomoredaran,
			surat_izin.tentang as halizin,surat_izin.nomor as nomorizin,
			surat_pengumuman.tentang as halpengumuman,surat_pengumuman.nomor as nomorpengumuman,
			surat_laporan.tentang as hallaporan,surat_laporan.nomor as nomorlaporan,
			surat_rekomendasi.tentang as halrekomendasi,surat_rekomendasi.nomor as nomorrekomendasi,
			surat_panggilan.hal as halpanggilan,surat_panggilan.nomor as nomorpanggilan,
			surat_notadinas.hal as halnotadinas,surat_notadinas.nomor as nomornotadinas,
			surat_biasa.hal as halbiasa,surat_biasa.nomor as nomorbiasa, surat_lampiran.perihal as hallampiran, surat_lampiran.nomor as nomorlampiran,surat_lampiran.kodesurat_id as kodesuratlampiran,
			surat_pengantar.nomor as nomorpengantar,surat_pengantar.kodesurat_id as kodesuratpengantar, surat_perintahtugas.nomor as nomorperintahtugas,
			surat_perintah.nomor as nomorperintah, surat_notulen.nomor as nomornotulen,surat_keterangan.nomor as nomorketerangan, surat_perjalanan.nomor as nomoperjalanan, surat_perjalanan.kegiatan as kegiatan_perjalanan,
			surat_kuasa.nomor as nomorkuasa, surat_melaksanakantugas.nomor as nomormelaksanakantugas
			FROM disposisi_suratkeluar
			LEFT JOIN draft ON draft.surat_id = disposisi_suratkeluar.surat_id
			LEFT JOIN surat_undangan ON surat_undangan.id=disposisi_suratkeluar.surat_id
			LEFT JOIN surat_edaran ON surat_edaran.id=disposisi_suratkeluar.surat_id
			LEFT JOIN surat_melaksanakantugas ON surat_melaksanakantugas.id=disposisi_suratkeluar.surat_id
			LEFT JOIN surat_kuasa ON surat_kuasa.id=disposisi_suratkeluar.surat_id
			LEFT JOIN surat_keterangan ON surat_keterangan.id=disposisi_suratkeluar.surat_id
			LEFT JOIN surat_notulen ON surat_notulen.id=disposisi_suratkeluar.surat_id
			LEFT JOIN surat_izin ON surat_izin.id=disposisi_suratkeluar.surat_id
			LEFT JOIN surat_notadinas ON surat_notadinas.id=disposisi_suratkeluar.surat_id
			LEFT JOIN surat_pengumuman ON surat_pengumuman.id=disposisi_suratkeluar.surat_id
			LEFT JOIN surat_perjalanan ON surat_perjalanan.id=disposisi_suratkeluar.surat_id
			LEFT JOIN surat_laporan ON surat_laporan.id=disposisi_suratkeluar.surat_id
			LEFT JOIN surat_biasa ON surat_biasa.id=disposisi_suratkeluar.surat_id
			LEFT JOIN surat_lampiran ON surat_lampiran.id=disposisi_suratkeluar.surat_id
			LEFT JOIN surat_pengantar ON surat_pengantar.id=disposisi_suratkeluar.surat_id
			LEFT JOIN surat_perintahtugas ON surat_perintahtugas.id=disposisi_suratkeluar.surat_id
			LEFT JOIN surat_panggilan ON surat_panggilan.id=disposisi_suratkeluar.surat_id
			LEFT JOIN surat_perintah ON surat_perintah.id=disposisi_suratkeluar.surat_id
			LEFT JOIN surat_rekomendasi ON surat_rekomendasi.id=disposisi_suratkeluar.surat_id
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

	public function get_sudahdisposisi($jabatan_id,$tahun)
	{
		$query = $this->db->query("
		SELECT * FROM disposisi_suratmasuk LEFT JOIN surat_masuk ON surat_masuk.suratmasuk_id=disposisi_suratmasuk.suratmasuk_id WHERE disposisi_suratmasuk.users_id='$jabatan_id' GROUP BY disposisi_suratmasuk.suratmasuk_id ORDER BY surat_masuk.diterima DESC, LENGTH(surat_masuk.suratmasuk_id) DESC, disposisi_suratmasuk.dsuratmasuk_id DESC
		");
		return $query;
	}
	public function get_sudahdisposisiseknas($jabatan_id,$tahun)
	{
		$query = $this->db->query("
		SELECT * FROM disposisi_suratmasuk LEFT JOIN surat_masuk ON surat_masuk.suratmasuk_id=disposisi_suratmasuk.suratmasuk_id WHERE disposisi_suratmasuk.users_id='$jabatan_id' GROUP BY disposisi_suratmasuk.suratmasuk_id ORDER BY surat_masuk.diterima DESC, LENGTH(surat_masuk.suratmasuk_id) DESC, disposisi_suratmasuk.dsuratmasuk_id DESC
		");
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