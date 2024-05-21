<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class Draft_model extends CI_Model
{
	public function get_verifikasi($tahun,$jabatan_id)
	{
	   // Upadate @Mpik Egov 9/06/2022 11:00
	   // Update 23/02/2024
	   // Penambahan Join surat keluar dan penambahan kop untuk surat pengantar
		$query = $this->db->query("
			SELECT d.verifikasi_id,d.dibuat_id, d.surat_id,
			d.nama_surat,
			d.tanggal,
			d.kopId,
			jabatan.nama_jabatan,
			a.hal as halundangan,a.kop_id as kopundangan, a.nomor as nomorundangan, kopundangan.nama as namakopundangan,
			surat_edaran.tentang as haledaran,surat_edaran.kop_id as kopedaran,surat_edaran.nomor as nomoredaran, kopedaran.nama as namakopedaran,
			surat_biasa.hal as halbiasa, surat_biasa.kop_id as kopbiasa,surat_biasa.nomor as nomorbiasa,kopbiasa.nama as namakopbiasa, kopbiasa.nama as namakopbiasa,
			surat_izin.tentang as halizin, surat_izin.kop_id as kopizin,surat_izin.nomor as nomorizin,kopizin.nama as namakopizin,
			surat_panggilan.hal as halpanggilan, surat_panggilan.kop_id as koppanggilan,surat_panggilan.nomor as nomorpanggilan,koppanggilan.nama as namakoppanggilan,
			surat_pengantar.nomor as nomorpengantar, d.kopId as koppengantar, koppengantar.nama as namakoppengantar,
			surat_lampiran.perihal as hallampiran,surat_lampiran.nomor as nomorlampiran,
			surat_notadinas.hal as halnotadinas, surat_notadinas.kop_id as kopnodin,surat_notadinas.nomor as nomornotadinas,kopnotadinas.nama as namakopnotadinas,
			surat_pengumuman.tentang as halpengumuman, surat_pengumuman.kop_id as koppengumuman,surat_pengumuman.nomor as nomorpengumuman,koppengumuman.nama as namakoppengumuman,
			surat_laporan.tentang as hallaporan, surat_laporan.kop_id as koplaporan,surat_laporan.nomor as nomorlaporan,koplaporan.nama as namakoplaporan,
			surat_rekomendasi.tentang as halrekomendasi, surat_rekomendasi.kop_id as koprekomendasi,surat_rekomendasi.nomor as nomorrekomendasi,koprekomendasi.nama as namakoprekomendasi,
			surat_keterangan.kop_id as kopketerangan,surat_keterangan.nomor as nomorketerangan,surat_keterangan.maksud as halketerangan,kopketerangan.nama as namakopketerangan,
			surat_perintahtugas.kop_id as kopperintahtugas,surat_perintahtugas.nomor as nomorperintahtugas,kopperintahtugas.nama as namakopperintahtugas,
			surat_melaksanakantugas.kop_id as kopmelaksanakantugas,surat_melaksanakantugas.nomor as nomormelaksanakantugas,kopmelaksanakantugas.nama as namakopmelaksanakantugas,
			surat_kuasa.kop_id as kopkuasa,surat_kuasa.nomor as nomorkuasa,kopkuasa.nama as namakopkuasa,
			surat_beritaacara.nomor as nomorberitaacara,kopberitaacara.nama as namakopberitaacara,surat_beritaacara.kop_id as kopberitaacara,
			surat_memo.kop_id as kopmemo,surat_memo.nomor as nomormemo,kopmemo.nama as namakopmemo,
			surat_keputusan.kop_id as kopkeputusan,surat_keputusan.tentang as halkeputusan,surat_keputusan.nomor as nomorkeputusan,kopkeputusan.nama as namakopkeputusan,
			surat_lainnya.perihal as halsuratlainnya,surat_lainnya.nomor as nomorlainnya, koplainnya.nama as namakoplainnya, surat_lainnya.kop_id as koplainnya,
			surat_notulen.acara as halnotulen, surat_notulen.nomor as nomornotulen,
			surat_instruksi.nomor as nomorinstruksi,surat_instruksi.tentang as halinstruksi,
			surat_perjalanan.nomor as nomorperjalanan,
			surat_wilayah.nomor as nomorwilayah,surat_wilayah.hal as halwilayah,
			aparatur.nama
			FROM draft d
			LEFT JOIN jabatan ON jabatan.jabatan_id = d.dibuat_id
			LEFT JOIN aparatur ON jabatan.jabatan_id = aparatur.jabatan_id
			LEFT JOIN surat_undangan a ON a.id=d.surat_id
			LEFT JOIN surat_edaran ON surat_edaran.id=d.surat_id
			LEFT JOIN surat_biasa ON surat_biasa.id=d.surat_id
			LEFT JOIN surat_izin ON surat_izin.id=d.surat_id
			LEFT JOIN surat_panggilan ON surat_panggilan.id=d.surat_id
			LEFT JOIN surat_notadinas ON surat_notadinas.id=d.surat_id
			LEFT JOIN surat_pengumuman ON surat_pengumuman.id=d.surat_id
			LEFT JOIN surat_laporan ON surat_laporan.id=d.surat_id
			LEFT JOIN surat_rekomendasi ON surat_rekomendasi.id=d.surat_id
			LEFT JOIN surat_notulen ON surat_notulen.id=d.surat_id
			LEFT JOIN surat_lampiran ON surat_lampiran.id=d.surat_id
			LEFT JOIN surat_keterangan ON surat_keterangan.id=d.surat_id
			LEFT JOIN surat_perintahtugas ON surat_perintahtugas.id=d.surat_id
			LEFT JOIN surat_melaksanakantugas ON surat_melaksanakantugas.id=d.surat_id
			LEFT JOIN surat_kuasa ON surat_kuasa.id=d.surat_id
			LEFT JOIN surat_beritaacara ON surat_beritaacara.id=d.surat_id
			LEFT JOIN surat_memo ON surat_memo.id=d.surat_id
			LEFT JOIN surat_keputusan ON surat_keputusan.id=d.surat_id
			LEFT JOIN surat_lainnya ON surat_lainnya.id=d.surat_id
			LEFT JOIN surat_pengantar ON surat_pengantar.id=d.surat_id
			LEFT JOIN surat_perjalanan ON surat_perjalanan.id=d.surat_id
			LEFT JOIN surat_instruksi ON surat_instruksi.id=d.surat_id
			LEFT JOIN surat_wilayah ON surat_wilayah.id=d.surat_id
			LEFT JOIN kop_surat kopundangan ON kopundangan.kop_id=a.kop_id
			LEFT JOIN kop_surat kopbiasa ON kopbiasa.kop_id=surat_biasa.kop_id
			LEFT JOIN kop_surat kopedaran ON kopedaran.kop_id=surat_edaran.kop_id
			LEFT JOIN kop_surat kopizin ON kopizin.kop_id=surat_izin.kop_id
			LEFT JOIN kop_surat koppanggilan ON koppanggilan.kop_id=surat_panggilan.kop_id
			LEFT JOIN kop_surat koppengantar ON koppengantar.kop_id=d.kopId
			LEFT JOIN kop_surat kopnotadinas ON kopnotadinas.kop_id=surat_notadinas.kop_id
			LEFT JOIN kop_surat koppengumuman ON koppengumuman.kop_id=surat_pengumuman.kop_id
			LEFT JOIN kop_surat koplaporan ON koplaporan.kop_id=surat_laporan.kop_id
			LEFT JOIN kop_surat koprekomendasi ON koprekomendasi.kop_id=surat_rekomendasi.kop_id
			LEFT JOIN kop_surat kopketerangan ON kopketerangan.kop_id=surat_keterangan.kop_id
			LEFT JOIN kop_surat kopperintahtugas ON kopperintahtugas.kop_id=surat_perintahtugas.kop_id
			LEFT JOIN kop_surat kopmelaksanakantugas ON kopmelaksanakantugas.kop_id=surat_melaksanakantugas.kop_id
			LEFT JOIN kop_surat kopkuasa ON kopkuasa.kop_id=surat_kuasa.kop_id
			LEFT JOIN kop_surat kopberitaacara ON kopberitaacara.kop_id=surat_beritaacara.kop_id
			LEFT JOIN kop_surat kopmemo ON kopmemo.kop_id=surat_memo.kop_id
			LEFT JOIN kop_surat kopkeputusan ON kopkeputusan.kop_id=surat_keputusan.kop_id
			LEFT JOIN kop_surat koplainnya ON koplainnya.kop_id=surat_lainnya.kop_id
			WHERE LEFT(d.tanggal, 4) = '$tahun'
			AND d.verifikasi_id = '$jabatan_id'
			AND aparatur.statusaparatur = 'Aktif'
			ORDER BY tanggal DESC
			");
		return $query;
	}

	public function get_verifikasi_tu($tahun,$jabatan_id)
	{
	   // Upadate @Dam Egov 02/10/2023
	   // Penambahan Join surat keluar
		$query = $this->db->query("
			SELECT d.verifikasi_id,d.dibuat_id, d.surat_id,
			d.nama_surat,
			d.tanggal,
			d.kopId,
			jabatan.nama_jabatan,
			a.hal as halundangan,a.kop_id as kopundangan, a.nomor as nomorundangan, kopundangan.nama as namakopundangan,
			surat_edaran.tentang as haledaran,surat_edaran.kop_id as kopedaran,surat_edaran.nomor as nomoredaran, kopedaran.nama as namakopedaran,
			surat_biasa.hal as halbiasa, surat_biasa.kop_id as kopbiasa,surat_biasa.nomor as nomorbiasa,kopbiasa.nama as namakopbiasa, kopbiasa.nama as namakopbiasa,
			surat_izin.tentang as halizin, surat_izin.kop_id as kopizin,surat_izin.nomor as nomorizin,kopizin.nama as namakopizin,
			surat_panggilan.hal as halpanggilan, surat_panggilan.kop_id as koppanggilan,surat_panggilan.nomor as nomorpanggilan,koppanggilan.nama as namakoppanggilan,
			surat_lampiran.perihal as hallampiran,surat_lampiran.nomor as nomorlampiran,
			surat_notadinas.hal as halnotadinas, surat_notadinas.kop_id as kopnodin,surat_notadinas.nomor as nomornotadinas,kopnotadinas.nama as namakopnotadinas,
			surat_pengumuman.tentang as halpengumuman, surat_pengumuman.kop_id as koppengumuman,surat_pengumuman.nomor as nomorpengumuman,koppengumuman.nama as namakoppengumuman,
			surat_laporan.tentang as hallaporan, surat_laporan.kop_id as koplaporan,surat_laporan.nomor as nomorlaporan,koplaporan.nama as namakoplaporan,
			surat_rekomendasi.tentang as halrekomendasi, surat_rekomendasi.kop_id as koprekomendasi,surat_rekomendasi.nomor as nomorrekomendasi,koprekomendasi.nama as namakoprekomendasi,
			surat_keterangan.kop_id as kopketerangan,surat_keterangan.nomor as nomorketerangan,surat_keterangan.maksud as halketerangan,kopketerangan.nama as namakopketerangan,
			surat_perintahtugas.kop_id as kopperintahtugas,surat_perintahtugas.nomor as nomorperintahtugas,kopperintahtugas.nama as namakopperintahtugas,
			surat_melaksanakantugas.kop_id as kopmelaksanakantugas,surat_melaksanakantugas.nomor as nomormelaksanakantugas,kopmelaksanakantugas.nama as namakopmelaksanakantugas,
			surat_kuasa.kop_id as kopkuasa,surat_kuasa.nomor as nomorkuasa,kopkuasa.nama as namakopkuasa,
			surat_beritaacara.nomor as nomorberitaacara,kopberitaacara.nama as namakopberitaacara,surat_beritaacara.kop_id as kopberitaacara,
			surat_memo.kop_id as kopmemo,surat_memo.nomor as nomormemo,kopmemo.nama as namakopmemo,
			surat_keputusan.kop_id as kopkeputusan,surat_keputusan.tentang as halkeputusan,surat_keputusan.nomor as nomorkeputusan,kopkeputusan.nama as namakopkeputusan,
			surat_lainnya.perihal as halsuratlainnya,surat_lainnya.nomor as nomorlainnya, koplainnya.nama as namakoplainnya, surat_lainnya.kop_id as koplainnya,
			surat_notulen.acara as halnotulen, surat_notulen.nomor as nomornotulen,
			surat_pengantar.nomor as nomorpengantar,
			surat_instruksi.nomor as nomorinstruksi,surat_instruksi.tentang as halinstruksi,
			surat_perjalanan.nomor as nomorperjalanan,
			surat_wilayah.nomor as nomorwilayah,surat_wilayah.hal as halwilayah,
			aparatur.nama
			FROM draft d
			LEFT JOIN jabatan ON jabatan.jabatan_id = d.dibuat_id
			LEFT JOIN aparatur ON jabatan.jabatan_id = aparatur.jabatan_id
			LEFT JOIN surat_undangan a ON a.id=d.surat_id
			LEFT JOIN surat_edaran ON surat_edaran.id=d.surat_id
			LEFT JOIN surat_biasa ON surat_biasa.id=d.surat_id
			LEFT JOIN surat_izin ON surat_izin.id=d.surat_id
			LEFT JOIN surat_panggilan ON surat_panggilan.id=d.surat_id
			LEFT JOIN surat_notadinas ON surat_notadinas.id=d.surat_id
			LEFT JOIN surat_pengumuman ON surat_pengumuman.id=d.surat_id
			LEFT JOIN surat_laporan ON surat_laporan.id=d.surat_id
			LEFT JOIN surat_rekomendasi ON surat_rekomendasi.id=d.surat_id
			LEFT JOIN surat_notulen ON surat_notulen.id=d.surat_id
			LEFT JOIN surat_lampiran ON surat_lampiran.id=d.surat_id
			LEFT JOIN surat_keterangan ON surat_keterangan.id=d.surat_id
			LEFT JOIN surat_perintahtugas ON surat_perintahtugas.id=d.surat_id
			LEFT JOIN surat_melaksanakantugas ON surat_melaksanakantugas.id=d.surat_id
			LEFT JOIN surat_kuasa ON surat_kuasa.id=d.surat_id
			LEFT JOIN surat_beritaacara ON surat_beritaacara.id=d.surat_id
			LEFT JOIN surat_memo ON surat_memo.id=d.surat_id
			LEFT JOIN surat_keputusan ON surat_keputusan.id=d.surat_id
			LEFT JOIN surat_lainnya ON surat_lainnya.id=d.surat_id
			LEFT JOIN surat_pengantar ON surat_pengantar.id=d.surat_id
			LEFT JOIN surat_perjalanan ON surat_perjalanan.id=d.surat_id
			LEFT JOIN surat_instruksi ON surat_instruksi.id=d.surat_id
			LEFT JOIN surat_wilayah ON surat_wilayah.id=d.surat_id
			LEFT JOIN kop_surat kopundangan ON kopundangan.kop_id=a.kop_id
			LEFT JOIN kop_surat kopbiasa ON kopbiasa.kop_id=surat_biasa.kop_id
			LEFT JOIN kop_surat kopedaran ON kopedaran.kop_id=surat_edaran.kop_id
			LEFT JOIN kop_surat kopizin ON kopizin.kop_id=surat_izin.kop_id
			LEFT JOIN kop_surat koppanggilan ON koppanggilan.kop_id=surat_panggilan.kop_id
			LEFT JOIN kop_surat kopnotadinas ON kopnotadinas.kop_id=surat_notadinas.kop_id
			LEFT JOIN kop_surat koppengumuman ON koppengumuman.kop_id=surat_pengumuman.kop_id
			LEFT JOIN kop_surat koplaporan ON koplaporan.kop_id=surat_laporan.kop_id
			LEFT JOIN kop_surat koprekomendasi ON koprekomendasi.kop_id=surat_rekomendasi.kop_id
			LEFT JOIN kop_surat kopketerangan ON kopketerangan.kop_id=surat_keterangan.kop_id
			LEFT JOIN kop_surat kopperintahtugas ON kopperintahtugas.kop_id=surat_perintahtugas.kop_id
			LEFT JOIN kop_surat kopmelaksanakantugas ON kopmelaksanakantugas.kop_id=surat_melaksanakantugas.kop_id
			LEFT JOIN kop_surat kopkuasa ON kopkuasa.kop_id=surat_kuasa.kop_id
			LEFT JOIN kop_surat kopberitaacara ON kopberitaacara.kop_id=surat_beritaacara.kop_id
			LEFT JOIN kop_surat kopmemo ON kopmemo.kop_id=surat_memo.kop_id
			LEFT JOIN kop_surat kopkeputusan ON kopkeputusan.kop_id=surat_keputusan.kop_id
			LEFT JOIN kop_surat koplainnya ON koplainnya.kop_id=surat_lainnya.kop_id
			WHERE LEFT(d.tanggal, 4) = '$tahun'
			AND d.verifikasi_id = '$jabatan_id'
			AND d.penandatangan_id != '0'
			ORDER BY tanggal DESC
			LIMIT 200
			");
		return $query;
	}

	public function get_draftpenomoran($tahun,$jabatan_id)
	{
	   // Upadate @Dam Egov 02/10/2023
	   // Penambahan Join surat keluar
		$query = $this->db->query("
			SELECT d.verifikasi_id,d.dibuat_id, d.surat_id,
			d.nama_surat,
			d.tanggal,
			d.kopId,
			jabatan.nama_jabatan,
			a.hal as halundangan,a.kop_id as kopundangan, a.nomor as nomorundangan, kopundangan.nama as namakopundangan,
			surat_edaran.tentang as haledaran,surat_edaran.kop_id as kopedaran,surat_edaran.nomor as nomoredaran, kopedaran.nama as namakopedaran,
			surat_biasa.hal as halbiasa, surat_biasa.kop_id as kopbiasa,surat_biasa.nomor as nomorbiasa,kopbiasa.nama as namakopbiasa, kopbiasa.nama as namakopbiasa,
			surat_izin.tentang as halizin, surat_izin.kop_id as kopizin,surat_izin.nomor as nomorizin,kopizin.nama as namakopizin,
			surat_panggilan.hal as halpanggilan, surat_panggilan.kop_id as koppanggilan,surat_panggilan.nomor as nomorpanggilan,koppanggilan.nama as namakoppanggilan,
			surat_lampiran.perihal as hallampiran,surat_lampiran.nomor as nomorlampiran,
			surat_notadinas.hal as halnotadinas, surat_notadinas.kop_id as kopnodin,surat_notadinas.nomor as nomornotadinas,kopnotadinas.nama as namakopnotadinas,
			surat_pengumuman.tentang as halpengumuman, surat_pengumuman.kop_id as koppengumuman,surat_pengumuman.nomor as nomorpengumuman,koppengumuman.nama as namakoppengumuman,
			surat_laporan.tentang as hallaporan, surat_laporan.kop_id as koplaporan,surat_laporan.nomor as nomorlaporan,koplaporan.nama as namakoplaporan,
			surat_rekomendasi.tentang as halrekomendasi, surat_rekomendasi.kop_id as koprekomendasi,surat_rekomendasi.nomor as nomorrekomendasi,koprekomendasi.nama as namakoprekomendasi,
			surat_keterangan.kop_id as kopketerangan,surat_keterangan.nomor as nomorketerangan,surat_keterangan.maksud as halketerangan,kopketerangan.nama as namakopketerangan,
			surat_perintahtugas.kop_id as kopperintahtugas,surat_perintahtugas.nomor as nomorperintahtugas,kopperintahtugas.nama as namakopperintahtugas,
			surat_melaksanakantugas.kop_id as kopmelaksanakantugas,surat_melaksanakantugas.nomor as nomormelaksanakantugas,kopmelaksanakantugas.nama as namakopmelaksanakantugas,
			surat_kuasa.kop_id as kopkuasa,surat_kuasa.nomor as nomorkuasa,kopkuasa.nama as namakopkuasa,
			surat_beritaacara.nomor as nomorberitaacara,kopberitaacara.nama as namakopberitaacara,surat_beritaacara.kop_id as kopberitaacara,
			surat_memo.kop_id as kopmemo,surat_memo.nomor as nomormemo,kopmemo.nama as namakopmemo,
			surat_keputusan.kop_id as kopkeputusan,surat_keputusan.tentang as halkeputusan,surat_keputusan.nomor as nomorkeputusan,kopkeputusan.nama as namakopkeputusan,
			surat_lainnya.perihal as halsuratlainnya,surat_lainnya.nomor as nomorlainnya, koplainnya.nama as namakoplainnya, surat_lainnya.kop_id as koplainnya,
			surat_notulen.acara as halnotulen, surat_notulen.nomor as nomornotulen,
			surat_pengantar.nomor as nomorpengantar,
			surat_instruksi.nomor as nomorinstruksi,surat_instruksi.tentang as halinstruksi,
			surat_perjalanan.nomor as nomorperjalanan,
			surat_wilayah.nomor as nomorwilayah,surat_wilayah.hal as halwilayah,
			aparatur.nama
			FROM draft d
			LEFT JOIN jabatan ON jabatan.jabatan_id = d.dibuat_id
			LEFT JOIN aparatur ON jabatan.jabatan_id = aparatur.jabatan_id
			LEFT JOIN surat_undangan a ON a.id=d.surat_id
			LEFT JOIN surat_edaran ON surat_edaran.id=d.surat_id
			LEFT JOIN surat_biasa ON surat_biasa.id=d.surat_id
			LEFT JOIN surat_izin ON surat_izin.id=d.surat_id
			LEFT JOIN surat_panggilan ON surat_panggilan.id=d.surat_id
			LEFT JOIN surat_notadinas ON surat_notadinas.id=d.surat_id
			LEFT JOIN surat_pengumuman ON surat_pengumuman.id=d.surat_id
			LEFT JOIN surat_laporan ON surat_laporan.id=d.surat_id
			LEFT JOIN surat_rekomendasi ON surat_rekomendasi.id=d.surat_id
			LEFT JOIN surat_notulen ON surat_notulen.id=d.surat_id
			LEFT JOIN surat_lampiran ON surat_lampiran.id=d.surat_id
			LEFT JOIN surat_keterangan ON surat_keterangan.id=d.surat_id
			LEFT JOIN surat_perintahtugas ON surat_perintahtugas.id=d.surat_id
			LEFT JOIN surat_melaksanakantugas ON surat_melaksanakantugas.id=d.surat_id
			LEFT JOIN surat_kuasa ON surat_kuasa.id=d.surat_id
			LEFT JOIN surat_beritaacara ON surat_beritaacara.id=d.surat_id
			LEFT JOIN surat_memo ON surat_memo.id=d.surat_id
			LEFT JOIN surat_keputusan ON surat_keputusan.id=d.surat_id
			LEFT JOIN surat_lainnya ON surat_lainnya.id=d.surat_id
			LEFT JOIN surat_pengantar ON surat_pengantar.id=d.surat_id
			LEFT JOIN surat_perjalanan ON surat_perjalanan.id=d.surat_id
			LEFT JOIN surat_instruksi ON surat_instruksi.id=d.surat_id
			LEFT JOIN surat_wilayah ON surat_wilayah.id=d.surat_id
			LEFT JOIN kop_surat kopundangan ON kopundangan.kop_id=a.kop_id
			LEFT JOIN kop_surat kopbiasa ON kopbiasa.kop_id=surat_biasa.kop_id
			LEFT JOIN kop_surat kopedaran ON kopedaran.kop_id=surat_edaran.kop_id
			LEFT JOIN kop_surat kopizin ON kopizin.kop_id=surat_izin.kop_id
			LEFT JOIN kop_surat koppanggilan ON koppanggilan.kop_id=surat_panggilan.kop_id
			LEFT JOIN kop_surat kopnotadinas ON kopnotadinas.kop_id=surat_notadinas.kop_id
			LEFT JOIN kop_surat koppengumuman ON koppengumuman.kop_id=surat_pengumuman.kop_id
			LEFT JOIN kop_surat koplaporan ON koplaporan.kop_id=surat_laporan.kop_id
			LEFT JOIN kop_surat koprekomendasi ON koprekomendasi.kop_id=surat_rekomendasi.kop_id
			LEFT JOIN kop_surat kopketerangan ON kopketerangan.kop_id=surat_keterangan.kop_id
			LEFT JOIN kop_surat kopperintahtugas ON kopperintahtugas.kop_id=surat_perintahtugas.kop_id
			LEFT JOIN kop_surat kopmelaksanakantugas ON kopmelaksanakantugas.kop_id=surat_melaksanakantugas.kop_id
			LEFT JOIN kop_surat kopkuasa ON kopkuasa.kop_id=surat_kuasa.kop_id
			LEFT JOIN kop_surat kopberitaacara ON kopberitaacara.kop_id=surat_beritaacara.kop_id
			LEFT JOIN kop_surat kopmemo ON kopmemo.kop_id=surat_memo.kop_id
			LEFT JOIN kop_surat kopkeputusan ON kopkeputusan.kop_id=surat_keputusan.kop_id
			LEFT JOIN kop_surat koplainnya ON koplainnya.kop_id=surat_lainnya.kop_id
			WHERE LEFT(d.tanggal, 4) = '$tahun'
			AND d.verifikasi_id = '$jabatan_id'
			AND d.penandatangan_id = '0'
			ORDER BY tanggal DESC
			");
		return $query;
	}
    // Upadate @Mpik Egov 9/06/2022 11:00
	  // Penambahan Join surat keluar : Penambahan LEFT JOIN Kop Surat
	public function get_riwayatterusan($tahun)
	{
	    $aparatur=substr($this->session->userdata('nama'), 0,10);
		$query = $this->db->query("
			SELECT a.surat_id, c.nama_jabatan,b.tanggal,b.nama_surat,b.surat_id,surat_undangan.hal as halundangan,surat_undangan.kop_id as kopundangan, surat_undangan.nomor as nomorundangan,
			surat_edaran.tentang as haledaran,surat_edaran.kop_id as kopedaran, surat_edaran.nomor as nomoredaran,
			surat_biasa.hal as halbiasa, surat_biasa.kop_id as kopbiasa, surat_biasa.nomor as nomorbiasa,
			surat_izin.tentang as halizin, surat_izin.kop_id as kopizin,surat_izin.nomor as nomorizin,
			surat_panggilan.hal as halpanggilan, surat_panggilan.kop_id as koppanggilan, surat_panggilan.nomor as nomorpanggilan,
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
			surat_keputusan.kop_id as kopkeputusan,surat_keputusan.tentang as halkeputusan, surat_keputusan.nomor as nomorkeputusan,
			surat_lainnya.perihal as halsuratlainnya,surat_lainnya.nomor as nomorsuratlainnya,
			surat_notulen.acara as halnotulen,surat_notulen.nomor as nomornotulen,
			aparatur.nama
			FROM verifikasi a
			LEFT JOIN draft b ON b.surat_id=a.surat_id
			LEFT JOIN jabatan c ON c.jabatan_id = b.dibuat_id
			LEFT JOIN aparatur ON c.jabatan_id = aparatur.jabatan_id
			LEFT JOIN surat_undangan ON surat_undangan.id=b.surat_id
			LEFT JOIN surat_edaran ON surat_edaran.id=b.surat_id
			LEFT JOIN surat_biasa ON surat_biasa.id=b.surat_id
			LEFT JOIN surat_izin ON surat_izin.id=b.surat_id
			LEFT JOIN surat_panggilan ON surat_panggilan.id=b.surat_id
			LEFT JOIN surat_notadinas ON surat_notadinas.id=b.surat_id
			LEFT JOIN surat_pengumuman ON surat_pengumuman.id=b.surat_id
			LEFT JOIN surat_laporan ON surat_laporan.id=b.surat_id
			LEFT JOIN surat_rekomendasi ON surat_rekomendasi.id=b.surat_id
			LEFT JOIN surat_notulen ON surat_notulen.id=b.surat_id
			LEFT JOIN surat_lampiran ON surat_lampiran.id=b.surat_id
			LEFT JOIN surat_keterangan ON surat_keterangan.id=b.surat_id
			LEFT JOIN surat_perintahtugas ON surat_perintahtugas.id=b.surat_id
			LEFT JOIN surat_melaksanakantugas ON surat_melaksanakantugas.id=b.surat_id
			LEFT JOIN surat_kuasa ON surat_kuasa.id=b.surat_id
			LEFT JOIN surat_beritaacara ON surat_beritaacara.id=b.surat_id
			LEFT JOIN surat_memo ON surat_memo.id=b.surat_id
			LEFT JOIN surat_keputusan ON surat_keputusan.id=b.surat_id
			LEFT JOIN surat_lainnya ON surat_lainnya.id=b.surat_id
			WHERE a.dari LIKE '%$aparatur%' and LEFT(b.tanggal, 4) = '$tahun' and aparatur.statusaparatur = 'Aktif'
			ORDER BY b.id DESC
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
		$this->db->get_where($tabel,$where);
		$edit =$this->db->get_where($tabel,$where);
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
	    $where=array('aparatur');
		$this->db->from('aparatur');
		$this->db->join('jabatan', 'jabatan.jabatan_id = aparatur.jabatan_id');
		$this->db->where('aparatur.jabatan_id', $jabatan_id);
		$this->db->where('aparatur.statusaparatur', 'Aktif');
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
		    if($this->session->userdata('jabatan_id') == 1473){
		        $query = $this->db->query("
    			SELECT * FROM aparatur
    			LEFT JOIN jabatan ON jabatan.jabatan_id = aparatur.jabatan_id
    			LEFT JOIN users ON users.aparatur_id = aparatur.aparatur_id
    			WHERE users.level_id IN (5,6,7,9,10,11,22,23,24,25,26,27,19) AND aparatur.opd_id IN (4,3,2) AND aparatur.statusaparatur='Aktif' ORDER BY jabatan.jabatan_id ASC
    			");
		    }else{
    			$query = $this->db->query("
    			SELECT * FROM aparatur
    			LEFT JOIN jabatan ON jabatan.jabatan_id = aparatur.jabatan_id
    			LEFT JOIN users ON users.aparatur_id = aparatur.aparatur_id
    			WHERE users.level_id IN (5,6,7,9,19,10,11,22,23,24,25,26,27) AND aparatur.opd_id = '$opd_id' AND aparatur.statusaparatur='Aktif' ORDER BY aparatur.level_id ASC
    			");
		    }
		}else{
			$query = $this->db->query("
			SELECT * FROM aparatur
			LEFT JOIN jabatan ON jabatan.jabatan_id = aparatur.jabatan_id
			LEFT JOIN users ON users.aparatur_id = aparatur.aparatur_id
			WHERE users.level_id IN (5,6,7,8,9,10,11,12,17,19,22,23,24,25,26,27) AND aparatur.jabatan_id = '$jabatan_id' AND aparatur.statusaparatur='Aktif' ORDER BY jabatan.jabatan_id ASC
			");
		}
		return $query;
	}

	public function get_tandatangan($jabatan_id)
	{
		$query = $this->db->query("
			SELECT *, draft.tanggal FROM penandatangan
			LEFT JOIN draft ON draft.surat_id = penandatangan.surat_id
			LEFT JOIN surat_lampiran ON surat_lampiran.id=draft.surat_id
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

	public function getlampirandetails($surat_id){
		
		$query = $this->db->query("
			Select 
			su.id,su.nomor AS nomorsurat,
			su.jenissurat, su.tanggal,
			ptd.nama AS namapejabat,
			(
				select jbt.jabatan
				from jabatan jbt
				where jbt.jabatan_id = ptd.jabatan_id
			) AS namajabatan,
			(
				select pangkat from aparatur
				where aparatur.jabatan_id = ptd.jabatan_id
			) as pangkat,
			su.lampiran_lain
			from surat_lampiran su, penandatangan ptd
			where su.id = ptd.surat_id 
			AND su.id = '$surat_id'
		");

		return $query;
	}
}
