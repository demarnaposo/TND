<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class Arsip_model extends CI_Model
{
	public function getsuratkeluar($opd_id,$tahun) // Update @Mpik Egov 10/06/2022 10/45
	
	{
		// Update @Mpik Egov 10/06/2022 10/45
		$get = $this->db->query("
		SELECT pengarsipan.surat_id,draft.tanggal,draft.nama_surat,
		pengarsipan.no_rak,pengarsipan.no_sampul,pengarsipan.no_book,
		penandatangan.status,
		a.hal as halundangan,a.kop_id as kopundangan, a.nomor as nomorundangan,
		surat_edaran.tentang as haledaran,surat_edaran.kop_id as kopedaran,surat_edaran.nomor as nomoredaran,
		surat_biasa.hal as halbiasa, surat_biasa.kop_id as kopbiasa,surat_biasa.nomor as nomorbiasa,
		surat_izin.tentang as halizin, surat_izin.kop_id as kopizin,surat_izin.nomor as nomorizin,
		surat_panggilan.hal as halpanggilan, surat_panggilan.kop_id as koppanggilan,surat_panggilan.nomor as nomorpanggilan,
		surat_lampiran.perihal as hallampiran,surat_lampiran.nomor as nomorlampiran,
		surat_notadinas.hal as halnotadinas, surat_notadinas.kop_id as kopnodin,surat_notadinas.nomor as nomornotadinas,
		surat_pengumuman.tentang as halpengumuman, surat_pengumuman.kop_id as koppengumuman,surat_pengumuman.nomor as nomorpengumuman,
		surat_laporan.tentang as hallaporan, surat_laporan.kop_id as koplaporan,surat_laporan.nomor as nomorlaporan,
		surat_rekomendasi.tentang as halrekomendasi, surat_rekomendasi.kop_id as koprekomendasi,surat_rekomendasi.nomor as nomorrekomendasi,
		surat_keterangan.kop_id as kopketerangan,surat_keterangan.nomor as nomorketerangan,surat_keterangan.maksud as halketerangan,
		surat_perintahtugas.kop_id as kopperintahtugas,surat_perintahtugas.nomor as nomorperintahtugas,
		surat_melaksanakantugas.kop_id as kopmelaksanakantugas,surat_melaksanakantugas.nomor as nomormelaksanakantugas,
		surat_kuasa.kop_id as kopkuasa,surat_kuasa.nomor as nomorkuasa,
		surat_beritaacara.kop_id as kopberitaacara,surat_beritaacara.nomor as nomorberitaacara,
		surat_memo.kop_id as kopmemo,surat_memo.nomor as nomormemo,
		surat_keputusan.kop_id as kopkeputusan,surat_keputusan.tentang as halkeputusan,surat_keputusan.nomor as nomorkeputusan,
		surat_lainnya.perihal as halsuratlainnya,surat_lainnya.nomor as nomorlainnya,
		surat_notulen.acara as halnotulen, surat_notulen.nomor as nomornotulen,
		surat_pengantar.nomor as nomorpengantar FROM pengarsipan
		LEFT JOIN draft ON draft.surat_id=pengarsipan.surat_id
		LEFT JOIN jabatan ON jabatan.jabatan_id=draft.dibuat_id
		LEFT JOIN penandatangan ON penandatangan.surat_id=pengarsipan.surat_id
		LEFT JOIN surat_undangan a ON a.id=pengarsipan.surat_id
		LEFT JOIN surat_edaran ON surat_edaran.id=pengarsipan.surat_id
		LEFT JOIN surat_biasa ON surat_biasa.id=pengarsipan.surat_id
		LEFT JOIN surat_izin ON surat_izin.id=pengarsipan.surat_id
		LEFT JOIN surat_panggilan ON surat_panggilan.id=pengarsipan.surat_id
		LEFT JOIN surat_notadinas ON surat_notadinas.id=pengarsipan.surat_id
		LEFT JOIN surat_pengumuman ON surat_pengumuman.id=pengarsipan.surat_id
		LEFT JOIN surat_laporan ON surat_laporan.id=pengarsipan.surat_id
		LEFT JOIN surat_rekomendasi ON surat_rekomendasi.id=pengarsipan.surat_id
		LEFT JOIN surat_notulen ON surat_notulen.id=pengarsipan.surat_id
		LEFT JOIN surat_lampiran ON surat_lampiran.id=pengarsipan.surat_id
		LEFT JOIN surat_keterangan ON surat_keterangan.id=pengarsipan.surat_id
		LEFT JOIN surat_perintahtugas ON surat_perintahtugas.id=pengarsipan.surat_id
		LEFT JOIN surat_melaksanakantugas ON surat_melaksanakantugas.id=pengarsipan.surat_id
		LEFT JOIN surat_kuasa ON surat_kuasa.id=pengarsipan.surat_id
		LEFT JOIN surat_beritaacara ON surat_beritaacara.id=pengarsipan.surat_id
		LEFT JOIN surat_memo ON surat_memo.id=pengarsipan.surat_id
		LEFT JOIN surat_keputusan ON surat_keputusan.id=pengarsipan.surat_id
		LEFT JOIN surat_lainnya ON surat_lainnya.id=pengarsipan.surat_id
		LEFT JOIN surat_pengantar ON surat_pengantar.id=pengarsipan.surat_id
		WHERE LEFT(draft.tanggal, 4) = '$tahun' AND jabatan.opd_id=$opd_id
		ORDER BY arsip_id DESC
		");
	return $get;
	// End
	// Update @Mpik Egov 10/06/2022 10/45
	}
}