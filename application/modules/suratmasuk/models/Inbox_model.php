<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Inbox_model extends CI_Model
{
	public function get_disposisisurat($cari = null,$limit,$start,$jabatan_id,$tahun)
	{
		if($cari){
			$cari="surat_masuk.dari LIKE '%$cari%' AND disposisi_suratmasuk.aparatur_id = '$jabatan_id' OR surat_masuk.hal LIKE '%$cari%' AND";
		}
		$query = $this->db->query("
			SELECT * FROM disposisi_suratmasuk
			JOIN surat_masuk ON surat_masuk.suratmasuk_id = disposisi_suratmasuk.suratmasuk_id
			WHERE $cari disposisi_suratmasuk.aparatur_id = '$jabatan_id' AND disposisi_suratmasuk.status !='Dikembalikan'
			AND LEFT(surat_masuk.diterima, 4) = '$tahun'
			GROUP BY surat_masuk.suratmasuk_id ORDER BY surat_masuk.tanggal DESC
			LIMIT $start, $limit
		");
		return $query;
	}

	public function get_surattnd($jabatan_id,$tahun,$opd_id){
		$query = $this->db->query("
			SELECT *,draft.tanggal as tglsurat, surat_undangan.hal as halundangan,surat_undangan.nomor as nomorundangan,
			surat_biasa.hal as halbiasa,surat_biasa.nomor as nomorbiasa,surat_perintahtugas.nomor as nomorperintahtugas, surat_keterangan.nomor as nomorketerangan, surat_perjalanan.nomor as nomorperjalanan,
			surat_edaran.nomor as nomoredaran, surat_edaran.tentang as haledaran,surat_pengantar.nomor as nomorpengantar, a.kode_pd as pdbiasa,b.kode_pd as pdundangan,c.kode_pd as pdperintahtugas, d.kode_pd as pdketerangan, e.kode_pd as pdperjalanan,
			f.kode_pd as pdedaran,g.kode_pd as pdizin,h.kode_pd as pdpanggilan,i.kode_pd as pdnotadinas,j.kode_pd as pdpengumuman,k.kode_pd as pdlaporan,l.kode_pd as pdrekomendasi,m.kode_pd as pdnotulen,surat_lainnya.nomor as nomorlainnya,surat_lainnya.perihal as hallainnya,p.kode_pd as pdlainnya,
			surat_lampiran.nomor as nomorlampiran, surat_lampiran.perihal as hallampiran,
			surat_instruksi.tentang as halinstruksi, surat_instruksi.nomor as nomorinstruksi, q.kode_pd as pdinstruksi,
			surat_notadinas.hal as halnotadinas, surat_notadinas.nomor as nomornotadinas,i.kode_pd as pdnotadinas,
			surat_melaksanakantugas.nomor as nomormelaksanakantugas, r.kode_pd as pdmelaksanakantugas,
			n.kode_pd as pdlampiran,o.kode_pd as pdpengantar FROM disposisi_suratkeluar
			LEFT JOIN draft ON draft.surat_id = disposisi_suratkeluar.surat_id
			LEFT JOIN surat_undangan ON surat_undangan.id=disposisi_suratkeluar.surat_id
			LEFT JOIN surat_perintahtugas ON surat_perintahtugas.id=disposisi_suratkeluar.surat_id
			LEFT JOIN surat_biasa ON surat_biasa.id=disposisi_suratkeluar.surat_id
			LEFT JOIN surat_keterangan ON surat_keterangan.id=disposisi_suratkeluar.surat_id
			LEFT JOIN surat_perjalanan ON surat_perjalanan.id=disposisi_suratkeluar.surat_id
			LEFT JOIN surat_edaran ON surat_edaran.id=disposisi_suratkeluar.surat_id
			LEFT JOIN surat_izin ON surat_izin.id=disposisi_suratkeluar.surat_id
			LEFT JOIN surat_panggilan ON surat_panggilan.id=disposisi_suratkeluar.surat_id
			LEFT JOIN surat_notadinas ON surat_notadinas.id=disposisi_suratkeluar.surat_id
			LEFT JOIN surat_pengumuman ON surat_pengumuman.id=disposisi_suratkeluar.surat_id
			LEFT JOIN surat_pengantar ON surat_pengantar.id=disposisi_suratkeluar.surat_id
			LEFT JOIN surat_laporan ON surat_laporan.id=disposisi_suratkeluar.surat_id
			LEFT JOIN surat_rekomendasi ON surat_rekomendasi.id=disposisi_suratkeluar.surat_id
			LEFT JOIN surat_notulen ON surat_notulen.id=disposisi_suratkeluar.surat_id
			LEFT JOIN surat_lampiran ON surat_lampiran.id=disposisi_suratkeluar.surat_id
			LEFT JOIN surat_lainnya ON surat_lainnya.id=disposisi_suratkeluar.surat_id
			LEFT JOIN surat_instruksi ON surat_instruksi.id=disposisi_suratkeluar.surat_id
			LEFT JOIN surat_melaksanakantugas ON surat_melaksanakantugas.id=disposisi_suratkeluar.surat_id
			LEFT JOIN aparatur ON aparatur.jabatan_id = disposisi_suratkeluar.users_id
			LEFT JOIN opd a ON a.opd_id=surat_biasa.opd_id
			LEFT JOIN opd b ON b.opd_id=surat_undangan.opd_id
			LEFT JOIN opd c ON c.opd_id=surat_perintahtugas.opd_id
			LEFT JOIN opd d ON d.opd_id=surat_keterangan.opd_id
			LEFT JOIN opd e ON e.opd_id=surat_perjalanan.opd_id
			LEFT JOIN opd f ON f.opd_id=surat_edaran.opd_id
			LEFT JOIN opd g ON g.opd_id=surat_izin.opd_id
			LEFT JOIN opd h ON h.opd_id=surat_panggilan.opd_id
			LEFT JOIN opd i ON i.opd_id=surat_notadinas.opd_id
			LEFT JOIN opd j ON j.opd_id=surat_pengumuman.opd_id
			LEFT JOIN opd k ON k.opd_id=surat_laporan.opd_id
			LEFT JOIN opd l ON l.opd_id=surat_rekomendasi.opd_id
			LEFT JOIN opd m ON m.opd_id=surat_notulen.opd_id
			LEFT JOIN opd n ON n.opd_id=surat_lampiran.opd_id
			LEFT JOIN opd o ON o.opd_id=surat_pengantar.opd_id
			LEFT JOIN opd p ON p.opd_id=surat_lainnya.opd_id
			LEFT JOIN opd q ON q.opd_id=surat_instruksi.opd_id
			LEFT JOIN opd r ON r.opd_id=surat_melaksanakantugas.opd_id
			WHERE disposisi_suratkeluar.users_id = '$jabatan_id'
			AND aparatur.opd_id = '$opd_id'
			AND LEFT(draft.tanggal, 4) = '$tahun'
			AND disposisi_suratkeluar.status = 'Selesai'
			ORDER BY draft.tanggal DESC, disposisi_suratkeluar.dsuratkeluar_id DESC
		");
		return $query;
	}

	public function get_surattembusan($jabatan_id,$tahun,$opd_id){
		$query = $this->db->query("
			SELECT *,draft.tanggal as tglsurat, surat_undangan.hal as halundangan,surat_undangan.nomor as nomorundangan,
			surat_biasa.hal as halbiasa,surat_biasa.nomor as nomorbiasa,surat_perintahtugas.nomor as nomorperintahtugas, surat_keterangan.nomor as nomorketerangan, surat_perjalanan.nomor as nomorperjalanan,
			surat_edaran.nomor as nomoredaran, surat_edaran.tentang as haledaran,surat_pengantar.nomor as nomorpengantar, a.kode_pd as pdbiasa,b.kode_pd as pdundangan,c.kode_pd as pdperintahtugas, d.kode_pd as pdketerangan, e.kode_pd as pdperjalanan, surat_lainnya.nomor as nomorsuratlainnya, surat_lainnya.perihal as halsuratlainnya,
			f.kode_pd as pdedaran,g.kode_pd as pdizin,h.kode_pd as pdpanggilan,i.kode_pd as pdnotadinas,j.kode_pd as pdpengumuman,k.kode_pd as pdlaporan,l.kode_pd as pdrekomendasi,m.kode_pd as pdnotulen,
			n.kode_pd as pdlampiran,o.kode_pd as pdpengantar FROM tembusan_surat
			LEFT JOIN draft ON draft.surat_id = tembusan_surat.surat_id
			LEFT JOIN surat_undangan ON surat_undangan.id=tembusan_surat.surat_id
			LEFT JOIN surat_perintahtugas ON surat_perintahtugas.id=tembusan_surat.surat_id
			LEFT JOIN surat_biasa ON surat_biasa.id=tembusan_surat.surat_id
			LEFT JOIN surat_keterangan ON surat_keterangan.id=tembusan_surat.surat_id
			LEFT JOIN surat_perjalanan ON surat_perjalanan.id=tembusan_surat.surat_id
			LEFT JOIN surat_edaran ON surat_edaran.id=tembusan_surat.surat_id
			LEFT JOIN surat_izin ON surat_izin.id=tembusan_surat.surat_id
			LEFT JOIN surat_panggilan ON surat_panggilan.id=tembusan_surat.surat_id
			LEFT JOIN surat_notadinas ON surat_notadinas.id=tembusan_surat.surat_id
			LEFT JOIN surat_pengumuman ON surat_pengumuman.id=tembusan_surat.surat_id
			LEFT JOIN surat_pengantar ON surat_pengantar.id=tembusan_surat.surat_id
			LEFT JOIN surat_laporan ON surat_laporan.id=tembusan_surat.surat_id
			LEFT JOIN surat_rekomendasi ON surat_rekomendasi.id=tembusan_surat.surat_id
			LEFT JOIN surat_notulen ON surat_notulen.id=tembusan_surat.surat_id
			LEFT JOIN surat_lampiran ON surat_lampiran.id=tembusan_surat.surat_id
			LEFT JOIN surat_lainnya ON surat_lainnya.id=tembusan_surat.surat_id
			LEFT JOIN aparatur ON aparatur.jabatan_id = tembusan_surat.users_id
			LEFT JOIN opd a ON a.opd_id=surat_biasa.opd_id
			LEFT JOIN opd b ON b.opd_id=surat_undangan.opd_id
			LEFT JOIN opd c ON c.opd_id=surat_perintahtugas.opd_id
			LEFT JOIN opd d ON d.opd_id=surat_keterangan.opd_id
			LEFT JOIN opd e ON e.opd_id=surat_perjalanan.opd_id
			LEFT JOIN opd f ON f.opd_id=surat_edaran.opd_id
			LEFT JOIN opd g ON g.opd_id=surat_izin.opd_id
			LEFT JOIN opd h ON h.opd_id=surat_panggilan.opd_id
			LEFT JOIN opd i ON i.opd_id=surat_notadinas.opd_id
			LEFT JOIN opd j ON j.opd_id=surat_pengumuman.opd_id
			LEFT JOIN opd k ON k.opd_id=surat_laporan.opd_id
			LEFT JOIN opd l ON l.opd_id=surat_rekomendasi.opd_id
			LEFT JOIN opd m ON m.opd_id=surat_notulen.opd_id
			LEFT JOIN opd n ON n.opd_id=surat_lampiran.opd_id
			LEFT JOIN opd o ON o.opd_id=surat_pengantar.opd_id
			LEFT JOIN opd p ON p.opd_id=surat_lainnya.opd_id
			WHERE tembusan_surat.users_id = '$jabatan_id'
			AND aparatur.opd_id = '$opd_id'
			AND LEFT(draft.tanggal, 4) = '$tahun'
			AND tembusan_surat.status = 'Selesai'
			ORDER BY draft.tanggal DESC, tembusan_surat.tembusansurat_id DESC
		");
		return $query;
	}

	public function get_disposisi($cari = null,$limit,$start,$jabatan_id,$tahun)
	{
		if($cari){
			$cari="and surat_masuk.dari LIKE '%$cari%' and disposisi_suratmasuk.aparatur_id = '$jabatan_id' and LEFT(surat_masuk.tanggal, 4) = '$tahun' or surat_masuk.hal LIKE '%$cari%' and disposisi_suratmasuk.aparatur_id = '$jabatan_id' and LEFT(surat_masuk.tanggal, 4) = '$tahun' or surat_masuk.nomor LIKE '%$cari%' and disposisi_suratmasuk.aparatur_id = '$jabatan_id' and LEFT(surat_masuk.tanggal, 4) = '$tahun'";
		}
		$query = $this->db->query("
			SELECT *,surat_masuk.tanggal as tglsurat, surat_masuk.lampiran_lain,surat_masuk.lampiran FROM disposisi_suratmasuk
            JOIN surat_masuk ON surat_masuk.suratmasuk_id = disposisi_suratmasuk.suratmasuk_id
            JOIN aparatur ON aparatur.jabatan_id = disposisi_suratmasuk.aparatur_id
            WHERE disposisi_suratmasuk.status = 'Belum Selesai' $cari
            AND disposisi_suratmasuk.aparatur_id = '$jabatan_id' AND LEFT(surat_masuk.diterima, 4) = '$tahun'
            GROUP BY disposisi_suratmasuk.suratmasuk_id
            ORDER BY surat_masuk.diterima DESC, LENGTH(surat_masuk.suratmasuk_id) DESC, disposisi_suratmasuk.dsuratmasuk_id DESC LIMIT $start, $limit
		");
		return $query;
	}
	public function countdsuratmasuk($jabatan_id,$tahun)
	{	
		$query = $this->db->query("
		SELECT *,surat_masuk.tanggal as tglsurat, surat_masuk.lampiran_lain,surat_masuk.lampiran FROM disposisi_suratmasuk
		JOIN surat_masuk ON surat_masuk.suratmasuk_id = disposisi_suratmasuk.suratmasuk_id
		JOIN aparatur ON aparatur.jabatan_id = disposisi_suratmasuk.aparatur_id
		WHERE disposisi_suratmasuk.status = 'Belum Selesai'
		AND disposisi_suratmasuk.aparatur_id = '$jabatan_id' AND LEFT(surat_masuk.diterima, 4) = '$tahun'
		GROUP BY disposisi_suratmasuk.suratmasuk_id
		ORDER BY surat_masuk.diterima DESC, LENGTH(surat_masuk.suratmasuk_id) DESC, disposisi_suratmasuk.dsuratmasuk_id DESC
		")->num_rows();
		return $query;
	}

	public function get_sudahdisposisi($cari = null,$limit,$start,$jabatan_id,$tahun)
	{
	       // Update @Mpik Egov 30/06/2022
    		if($cari){
    			$cari="surat_masuk.dari LIKE '%$cari%' and disposisi_suratmasuk.users_id='$jabatan_id' and LEFT(surat_masuk.tanggal, 4) = '$tahun' and disposisi_suratmasuk.status='Selesai Disposisi' or
    			surat_masuk.hal LIKE '%$cari%' and disposisi_suratmasuk.users_id='$jabatan_id' and LEFT(surat_masuk.tanggal, 4) = '$tahun' and disposisi_suratmasuk.status='Selesai Disposisi' or
    			surat_masuk.nomor LIKE '%$cari%' and LEFT(surat_masuk.diterima, 4) = '$tahun' and";
    		}
    		$query = $this->db->query("
    		SELECT * FROM disposisi_suratmasuk LEFT JOIN surat_masuk ON surat_masuk.suratmasuk_id=disposisi_suratmasuk.suratmasuk_id WHERE $cari disposisi_suratmasuk.users_id='$jabatan_id' and LEFT(surat_masuk.diterima, 4) = '$tahun' GROUP BY disposisi_suratmasuk.suratmasuk_id ORDER BY surat_masuk.diterima DESC, LENGTH(surat_masuk.suratmasuk_id) DESC, disposisi_suratmasuk.dsuratmasuk_id DESC LIMIT $start, $limit
    		");
		    return $query;
		     // Update @Mpik Egov 30/06/2022
	}

	public function countsudahdisposisi($cari = null,$jabatan_id,$tahun)
	{
		if($cari){
			$cari="surat_masuk.dari LIKE '%$cari%' or surat_masuk.hal LIKE '%$cari%' or surat_masuk.nomor LIKE '%$cari%' and";
		}
		$query = $this->db->query("
		SELECT surat_masuk.suratmasuk_id FROM disposisi_suratmasuk LEFT JOIN surat_masuk ON surat_masuk.suratmasuk_id=disposisi_suratmasuk.suratmasuk_id WHERE $cari disposisi_suratmasuk.users_id='$jabatan_id' and LEFT(surat_masuk.diterima, 4) = '$tahun' GROUP BY disposisi_suratmasuk.suratmasuk_id ORDER BY surat_masuk.diterima DESC, LENGTH(surat_masuk.suratmasuk_id) DESC, disposisi_suratmasuk.dsuratmasuk_id DESC
		")->num_rows();
		return $query;
	}
	// public function get_sudahdisposisiseknas($cari = null,$limit,$start,$jabatan_id,$tahun)
	// {
	// 	$query = $this->db->query("
	// 	SELECT * FROM disposisi_suratmasuk LEFT JOIN surat_masuk ON surat_masuk.suratmasuk_id=disposisi_suratmasuk.suratmasuk_id WHERE disposisi_suratmasuk.users_id='$jabatan_id' and disposisi_suratmasuk.status='Selesai Disposisi' and left(surat_masuk.tanggal, 4)='$tahun' GROUP BY disposisi_suratmasuk.suratmasuk_id ORDER BY surat_masuk.diterima DESC, LENGTH(surat_masuk.suratmasuk_id) DESC, disposisi_suratmasuk.dsuratmasuk_id DESC LIMIT $start,$limit
	// 	");
	// 	return $query;
	// }

	// public function countsudahdisposisiseknas($jabatan_id,$tahun)
	// {
	// 	$query = $this->db->query("
	// 	SELECT * FROM disposisi_suratmasuk LEFT JOIN surat_masuk ON surat_masuk.suratmasuk_id=disposisi_suratmasuk.suratmasuk_id WHERE disposisi_suratmasuk.users_id='$jabatan_id' GROUP BY disposisi_suratmasuk.suratmasuk_id ORDER BY surat_masuk.diterima DESC, LENGTH(surat_masuk.suratmasuk_id) DESC, disposisi_suratmasuk.dsuratmasuk_id DESC
	// 	")->num_rows();
	// 	return $query;
	// }

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
		LEFT JOIN levelbaru ON levelbaru.level_id=aparatur.level_id
		WHERE jabatan.atasan_id = '$jabatan_id'
		AND aparatur.nip != '-' AND aparatur.statusaparatur='Aktif'
		ORDER BY levelbaru.level_id ASC
		");
		return $query;
	}

	public function get_global($opd_id)
	{
		$query = $this->db->query("
		SELECT * FROM aparatur 
		LEFT JOIN users ON users.aparatur_id=aparatur.aparatur_id
		LEFT JOIN level ON level.level_id=users.level_id
		JOIN jabatan ON aparatur.jabatan_id = jabatan.jabatan_id 
		WHERE jabatan.opd_id ='$opd_id'
		AND aparatur.nip != '-' AND users.users_id !='NULL' AND aparatur.statusaparatur='Aktif'
		ORDER BY level.level_id ASC
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
		$insert = $this->db->insert($tabel, $data);
		return $insert;
	}
}