<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Export extends CI_Controller
{	
	
	public function biasa()
	{
		$id = $this->uri->segment(3);
		
		$qfilename = $this->db->get_where('surat_biasa', array('id' => $id))->row_array();
		$filename = 'Surat Biasa - '.tanggal($qfilename['tanggal']).' ('.$qfilename['id'].')';
		//$filename =$qfilename['id'];
		$qr = $qfilename['id'];

		$this->load->library('pdf');

	//	$query = $this->db->query("SELECT * FROM draft LEFT JOIN surat_biasa ON surat_biasa.id = draft.surat_id 		LEFT JOIN opd ON opd.opd_id = surat_biasa.opd_id LEFT JOIN penandatangan ON penandatangan.penandatangan_id = draft.penandatangan_id LEFT JOIN aparatur ON aparatur.jabatan_id = penandatangan.jabatan_id WHERE surat_biasa.id = '$id' ");
		
		$query = $this->db->query("SELECT a.id, a.opd_id, a.kop_id, a.kodesurat_id, a.tanggal, a.nomor, a.sifat, a.lampiran, a.hal, a.tembusan, a.lampiran_lain, a.catatan, a.isi, b.opd_id,b.opd_induk, b.nama_pd, b.kode_pd, b.alamat, b.telp, b.faksimile, b.email, b.alamat_website, e.surat_id, e.jabatan_id, e.nama AS namapejabat, e.status, f.jabatan_id, f.nama_jabatan AS namajabatanpejabat, f.jabatan AS jabatanpejabat, g.nip, g.pangkat FROM surat_biasa a LEFT JOIN opd b ON a.opd_id = b.opd_id LEFT JOIN penandatangan e ON a.id = e.surat_id LEFT JOIN kop_surat h ON h.kop_id=a.kop_id LEFT JOIN jabatan f ON e.jabatan_id = f.jabatan_id LEFT JOIN aparatur g ON e.jabatan_id = g.jabatan_id WHERE a.id = '$id' LIMIT 1");	
        // Update @Mpik Egov 29/07/2022
		$uri = $this->db->query("SELECT * FROM penandatangan 
		LEFT JOIN aparatur ON aparatur.jabatan_id=penandatangan.jabatan_id
		LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
		WHERE penandatangan.surat_id='$id' AND aparatur.statusaparatur='Aktif'")->row_array(); // Update @Mpik Egov 25/07/2022
        // $uri = $this->db->query("SELECT * FROM penandatangan WHERE surat_id = '$id'")->row_array();
		// Update @Mpik Egov 29/07/2022
		$status = $uri['status'];
        
		$kopsurat = $query->row_array();
			$content = $this->load->view('exportsurat/biasa_pdf', array (
				'biasa' 	=> $query->result(),
				'tte' => $uri // Update @Mpik Egov 29/07/2022
			), TRUE);
			$content2 = $this->load->view('exportsurat/lampiran_pdf', array(
				'lampiran' 	=> $query->result()
			), TRUE);
		
		$this->pdf->create($content, $filename, $qr, $content2, $status);
	}

	public function edaran()
	{
		$id = $this->uri->segment(3);
		
		$qfilename = $this->db->get_where('surat_edaran', array('id' => $id))->row_array();
		$filename = 'Surat Edaran - '.tanggal($qfilename['tanggal']).' ('.$qfilename['id'].')';
		$qr = $qfilename['id'];
		
		$this->load->library('pdf');

	//	$query = $this->db->query("	SELECT * FROM draft	LEFT JOIN surat_edaran ON surat_edaran.id = draft.surat_id 			LEFT JOIN opd ON opd.opd_id = surat_edaran.opd_id LEFT JOIN penandatangan ON penandatangan.penandatangan_id = draft.penandatangan_id LEFT JOIN aparatur ON aparatur.jabatan_id = penandatangan.jabatan_id WHERE surat_edaran.id = '$id' ");

	$query = $this->db->query("SELECT 
		a.id, a.opd_id, a.kop_id, a.kodesurat_id, a.tanggal, a.nomor, a.tentang, a.tembusan, a.isi, a.catatan, a.lampiran_lain, b.opd_id,b.opd_induk, b.nama_pd, b.kode_pd, b.alamat, b.telp, b.faksimile, b.email, b.alamat_website, e.surat_id, e.jabatan_id, e.nama AS namapejabat, e.status, f.jabatan_id, f.nama_jabatan AS namajabatanpejabat, f.jabatan AS jabatanpejabat, g.nip, g.pangkat FROM surat_edaran a 
		LEFT JOIN opd b ON a.opd_id = b.opd_id 
		LEFT JOIN penandatangan e ON a.id = e.surat_id 
		LEFT JOIN jabatan f ON e.jabatan_id = f.jabatan_id 
		LEFT JOIN aparatur g ON e.jabatan_id = g.jabatan_id 
		WHERE a.id = '$id' LIMIT 1 ");	
        // Update @Mpik Egov 29/07/2022
		$uri = $this->db->query("SELECT * FROM penandatangan 
		LEFT JOIN aparatur ON aparatur.jabatan_id=penandatangan.jabatan_id
		LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
		WHERE penandatangan.surat_id='$id' AND aparatur.statusaparatur='Aktif'")->row_array(); // Update @Mpik Egov 25/07/2022
        // $uri = $this->db->query("SELECT * FROM penandatangan WHERE surat_id = '$id'")->row_array();
		// Update @Mpik Egov 29/07/2022
		$status = $uri['status'];

		$kopsurat = $query->row_array();
			$content = $this->load->view('exportsurat/edaran_pdf', array (
				'edaran' 	=> $query->result(),
				'tte' => $uri
			), TRUE);
			$content2 = $this->load->view('exportsurat/lampiran_pdf', array(
				'lampiran' 	=> $query->result()
			), TRUE);
		
		$this->pdf->create($content, $filename, $qr, $content2, $status);
	}

	public function undangan()
	{
		$id = $this->uri->segment(3);
		
		$qfilename = $this->db->get_where('surat_undangan', array('id' => $id))->row_array();
		$filename = 'Surat Undangan - '.tanggal($qfilename['tanggal']).' ('.$qfilename['id'].')';
		$qr = $qfilename['id'];
		
		$this->load->library('pdf');

//		$query = $this->db->query("SELECT * FROM draft 	LEFT JOIN surat_undangan ON surat_undangan.id = draft.surat_id 	LEFT JOIN opd ON opd.opd_id = surat_undangan.opd_id LEFT JOIN penandatangan ON penandatangan.penandatangan_id = draft.penandatangan_id LEFT JOIN aparatur ON aparatur.jabatan_id = penandatangan.jabatan_id WHERE surat_undangan.id = '$id' ");

	$query = $this->db->query("SELECT 
		a.id, a.opd_id, a.kop_id, a.kodesurat_id, a.tanggal, a.nomor, a.sifat, a.lampiran, a.hal,a.hari, a.tembusan, a.lampiran_lain, a.p1, a.tgl_acara, a.pukul, a.tempat, a.acara, a.p2, a.catatan,		
		b.opd_id, b.nama_pd,b.opd_induk, b.kode_pd, b.alamat, b.telp, b.faksimile, b.email, b.alamat_website, e.surat_id, e.jabatan_id, e.nama AS namapejabat, e.status, f.jabatan_id, f.nama_jabatan AS namajabatanpejabat, f.jabatan AS jabatanpejabat, g.nip, g.pangkat FROM surat_undangan a 
		LEFT JOIN opd b ON a.opd_id = b.opd_id 
		LEFT JOIN penandatangan e ON a.id = e.surat_id 
		LEFT JOIN jabatan f ON e.jabatan_id = f.jabatan_id 
		LEFT JOIN aparatur g ON e.jabatan_id = g.jabatan_id 
		WHERE a.id = '$id' LIMIT 1");	
		// Update @Mpik Egov 29/07/2022
		$uri = $this->db->query("SELECT * FROM penandatangan 
		LEFT JOIN aparatur ON aparatur.jabatan_id=penandatangan.jabatan_id
		LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
		WHERE penandatangan.surat_id='$id' AND aparatur.statusaparatur='Aktif'")->row_array(); // Update @Mpik Egov 25/07/2022
        // $uri = $this->db->query("SELECT * FROM penandatangan WHERE surat_id = '$id'")->row_array();
		// Update @Mpik Egov 29/07/2022
		$status = $uri['status'];

		$kopsurat = $query->row_array();
			$content = $this->load->view('exportsurat/undangan_pdf', array (
				'undangan' 	=> $query->result(),
				'tte' => $uri // Update @Mpik Egov 29/07/2022
			), TRUE);
			$content2 = $this->load->view('exportsurat/lampiran_pdf', array (
				'lampiran' 	=> $query->result()
			), TRUE);
		
		$this->pdf->create($content, $filename, $qr, $content2, $status);
	}

	public function pengumuman()
	{
		$id = $this->uri->segment(3);
		
		$qfilename = $this->db->get_where('surat_pengumuman', array('id' => $id))->row_array();
		$filename = 'Pengumuman - '.tanggal($qfilename['tanggal']).' ('.$qfilename['id'].')';
		$qr = $qfilename['id'];
		
		$this->load->library('pdf');

		$query = $this->db->query("
		SELECT a.id,a.nomor,a.tentang,a.isi,a.catatan,a.tanggal,a.kop_id,
		b.alamat,b.telp,b.faksimile,b.email,b.alamat_website,b.nama_pd,b.opd_induk,
		c.nama,c.jabatan,c.`status`,
		d.jabatan as jabatanpejabat,d.jabatan,
		e.nip,e.nama as namapejabat,e.pangkat FROM surat_pengumuman a
		LEFT JOIN opd b ON a.opd_id=b.opd_id
		LEFT JOIN penandatangan c ON c.surat_id=a.id
		LEFT JOIN jabatan d ON d.jabatan_id=c.jabatan_id
		LEFT JOIN aparatur e ON e.jabatan_id=c.jabatan_id 
		WHERE a.id = '$id' LIMIT 1
		");
		$uri = $this->db->query("SELECT * FROM penandatangan LEFT JOIN aparatur ON aparatur.jabatan_id=penandatangan.jabatan_id WHERE penandatangan.surat_id='$id' AND aparatur.statusaparatur='Aktif'")->row_array(); // Update @Mpik Egov 25/07/2022
        // $uri = $this->db->query("SELECT * FROM penandatangan WHERE surat_id = '$id'")->row_array();

		$status = $uri['status'];
		
		$kopsurat = $query->row_array();
			$content = $this->load->view('exportsurat/pengumuman_pdf', array (
				'pengumuman' 	=> $query->result(),
				'tte' => $uri
			), TRUE);
			$content2 = $this->load->view('exportsurat/lampiran_pdf', array(
				'lampiran' 	=> $query->result()
			), TRUE);
		
		$this->pdf->create($content, $filename, $qr, $content2, $status);
	}

	public function laporan()
	{
		$id = $this->uri->segment(3);
		
		$qfilename = $this->db->get_where('surat_laporan', array('id' => $id))->row_array();
		$filename = 'Laporan - '.tanggal($qfilename['tanggal']).' ('.$qfilename['id'].')';
		$qr = $qfilename['id'];
		
		$this->load->library('pdf');

		$query = $this->db->query("
		SELECT a.id,a.kop_id,a.nomor,a.tentang,a.latarbelakang,a.tanggal,a.landasanhukum,a.maksud,a.kegiatan,a.hasil,a.kesimpulan,a.penutup,a.catatan,
		b.alamat,b.telp,b.faksimile,b.email,b.alamat_website,b.nama_pd,b.opd_induk,
		c.nama,c.jabatan,c.`status`,
		d.nama_jabatan,d.jabatan,
		e.nip,e.nama,e.pangkat
		FROM surat_laporan a
		LEFT JOIN opd b ON a.opd_id=b.opd_id
		LEFT JOIN penandatangan c ON c.surat_id=a.id
		LEFT JOIN jabatan d ON d.jabatan_id=c.jabatan_id
		LEFT JOIN aparatur e ON e.jabatan_id=c.jabatan_id
		WHERE a.id = '$id'
		LIMIT 1
		");
		
		// Update @Mpik Egov 29/07/2022
		$uri = $this->db->query("SELECT * FROM penandatangan 
		LEFT JOIN aparatur ON aparatur.jabatan_id=penandatangan.jabatan_id
		LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
		WHERE penandatangan.surat_id='$id' AND aparatur.statusaparatur='Aktif'")->row_array(); // Update @Mpik Egov 25/07/2022
        // $uri = $this->db->query("SELECT * FROM penandatangan WHERE surat_id = '$id'")->row_array();
		// Update @Mpik Egov 29/07/2022
		$status = $uri['status'];
		$kopsurat = $query->row_array();
			$content = $this->load->view('exportsurat/laporan_pdf', array (
				'laporan' 	=> $query->result(),
				'tte' => $uri // Update @Mpik Egov 29/07/2022
			), TRUE);
			$content2 = $this->load->view('exportsurat/lampiran_pdf', array(
				'lampiran' 	=> $query->result(),
				'tte' => $uri
		    ), TRUE);
		
		
		$this->pdf->create($content, $filename, $qr, $content2, $status);
	}

	public function rekomendasi()
	{
		$id = $this->uri->segment(3);
		
		$qfilename = $this->db->get_where('surat_rekomendasi', array('id' => $id))->row_array();
		$filename = 'Surat Rekomendasi - '.tanggal($qfilename['tanggal']).' ('.$qfilename['id'].')';
		$qr = $qfilename['id'];
		
		$this->load->library('pdf');

		$query = $this->db->query("
			SELECT *,jabatan.jabatan as jabatanpejabat,aparatur.nama as namapejabat, aparatur.pangkat FROM draft
			LEFT JOIN surat_rekomendasi ON surat_rekomendasi.id = draft.surat_id
			LEFT JOIN opd ON opd.opd_id = surat_rekomendasi.opd_id
			LEFT JOIN penandatangan ON penandatangan.penandatangan_id = draft.penandatangan_id
			LEFT JOIN aparatur ON aparatur.jabatan_id = penandatangan.jabatan_id
			LEFT JOIN jabatan ON jabatan.jabatan_id=penandatangan.jabatan_id
			WHERE surat_rekomendasi.id = '$id'
		");
		// Update @Mpik Egov 29/07/2022
		$uri = $this->db->query("SELECT * FROM penandatangan 
		LEFT JOIN aparatur ON aparatur.jabatan_id=penandatangan.jabatan_id
		LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
		WHERE penandatangan.surat_id='$id' AND aparatur.statusaparatur='Aktif'")->row_array(); // Update @Mpik Egov 25/07/2022
        // $uri = $this->db->query("SELECT * FROM penandatangan WHERE surat_id = '$id'")->row_array();
		// Update @Mpik Egov 29/07/2022
		$status = $uri['status'];
		
		$kopsurat = $query->row_array();
			$content = $this->load->view('exportsurat/rekomendasi_pdf', array (
				'rekomendasi' 	=> $query->result(),
				'tte' => $uri // Update @Mpik Egov 29/07/2022
			), TRUE);
			$content2 = $this->load->view('exportsurat/lampiran_pdf', array(
				'lampiran' 	=> $query->result()
			), TRUE);
		
		$this->pdf->create($content, $filename, $qr, $content2, $status);
	}


	public function instruksi()
	{
		$id = $this->uri->segment(3);
		
		$qfilename = $this->db->get_where('surat_instruksi', array('id' => $id))->row_array();
		$filename = 'Surat Instruksi - '.tanggal($qfilename['tanggal']).' ('.$qfilename['id'].')';
		$qr = $qfilename['id'];
		
		$this->load->library('pdf');
		
		$isiquery = $this->db->query("
			SELECT * FROM isisurat a
            left join surat_instruksi b on b.id=a.surat_id
            left join opd c on c.opd_id=a.users_id
            where b.id='$id'
		"); 

		$query = $this->db->query("
		SELECT *,aparatur.nama as namapejabat,jabatan.jabatan as jabatanpejabat, aparatur.pangkat, penandatangan.status FROM draft
		LEFT JOIN surat_instruksi ON surat_instruksi.id = draft.surat_id
		LEFT JOIN opd ON opd.opd_id = surat_instruksi.opd_id
		LEFT JOIN penandatangan ON penandatangan.penandatangan_id = draft.penandatangan_id
		LEFT JOIN aparatur ON aparatur.jabatan_id = penandatangan.jabatan_id
		LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
		WHERE surat_instruksi.id = '$id'
		"); 
		// Update @Mpik Egov 29/07/2022
		$uri = $this->db->query("SELECT * FROM penandatangan 
		LEFT JOIN aparatur ON aparatur.jabatan_id=penandatangan.jabatan_id
		LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
		WHERE penandatangan.surat_id='$id' AND aparatur.statusaparatur='Aktif'")->row_array(); // Update @Mpik Egov 25/07/2022
        // $uri = $this->db->query("SELECT * FROM penandatangan WHERE surat_id = '$id'")->row_array();
		// Update @Mpik Egov 29/07/2022
		$status = $uri['status'];
		
		$content = $this->load->view('exportsurat/instruksiwalikota_pdf', array (
			'instruksi' 	=> $query->result(),
			'isisurat' 	    => $isiquery->result(),
			'tte' => $uri // Update @Mpik Egov 29/07/2022
		), TRUE);
		$content2 = $this->load->view('exportsurat/lampiran_pdf', array(
			'lampiran' 	=> $query->result()
		), TRUE);
		
		$this->pdf->create($content, $filename, $qr, $content2, $status);
	}

	public function pengantar()
	{
		$id = $this->uri->segment(3);
		
		$qfilename = $this->db->get_where('surat_pengantar', array('id' => $id))->row_array();
		$filename = 'Surat Pengantar - '.tanggal($qfilename['tanggal']).' ('.$qfilename['id'].')';
		$qr = $qfilename['id'];
		
		$this->load->library('pdf');

		$query = $this->db->query("
		SELECT *,penandatangan.nama as namapejabat,jabatan.jabatan as jabatanpejabat  FROM draft
			LEFT JOIN surat_pengantar ON surat_pengantar.id = draft.surat_id
			LEFT JOIN opd ON opd.opd_id = surat_pengantar.opd_id
			LEFT JOIN penandatangan ON penandatangan.penandatangan_id = draft.penandatangan_id
			LEFT JOIN aparatur ON aparatur.jabatan_id = penandatangan.jabatan_id
			LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
			WHERE surat_pengantar.id = '$id'
			LIMIT 1
		");
		$detail = $this->db->query("SELECT * FROM sp_detail WHERE surat_id = '$id'");
		// Update @Mpik Egov 29/07/2022
		$uri = $this->db->query("SELECT * FROM penandatangan 
		LEFT JOIN aparatur ON aparatur.jabatan_id=penandatangan.jabatan_id
		LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
		WHERE penandatangan.surat_id='$id' AND aparatur.statusaparatur='Aktif'")->row_array(); // Update @Mpik Egov 25/07/2022
        // $uri = $this->db->query("SELECT * FROM penandatangan WHERE surat_id = '$id'")->row_array();
		// Update @Mpik Egov 29/07/2022
		$status = $uri['status'];
		
		$content = $this->load->view('exportsurat/pengantar_pdf', array (
			'pengantar' 	=> $query->result(),
			'sp_detail'		=> $detail->result(),
			'tte' => $uri // Update @Mpik Egov 29/07/2022
		), TRUE);
		$content2 = $this->load->view('exportsurat/lampiran_pdf', array(
			'lampiran' 	=> $query->result()
		), TRUE);
		
		$this->pdf->create($content, $filename, $qr, $content2, $status);
	}

	public function notadinas()
	{
		$id = $this->uri->segment(3);
		
		$qfilename = $this->db->get_where('surat_notadinas', array('id' => $id))->row_array();
		$filename = 'Nota Dinas - '.tanggal($qfilename['tanggal']).' ('.$qfilename['id'].')';
		$qr = $qfilename['id'];
		
		$this->load->library('pdf'); 
        // [UPDATE] Fikri Egov 26012022 16:12Pm Pengkondisian Jika Surat Nota Dinas, Maka Penandatanganan Si Pembuatnya
		$query = $this->db->query("
		SELECT a.id, a.tanggal,a.kepada_id,a.nomor,a.sifat,a.lampiran,a.hal,a.tembusan,a.isi,a.catatan,a.kop_id,
		b.alamat,b.telp,b.email,b.nama_pd,b.alamat_website,b.opd_induk,
		c.nama_jabatan as jabatanaparatur, c.jabatan AS jabatanpejabat,
		d.surat_id,d.jabatan_id,d.nama,d.jabatan,d.`status`,h.jabatan as pembuatsurat,
		f.nip, f.pangkat as pangkatpejabat,f.nama as namapejabat, g.nama_jabatan as kepadajabatan
		FROM surat_notadinas a
		LEFT JOIN opd b ON b.opd_id=a.opd_id
        LEFT JOIN draft j ON j.surat_id=a.id
        LEFT JOIN jabatan g ON g.jabatan_id=a.kepada_id
        LEFT JOIN jabatan h ON h.jabatan_id=j.dibuat_id
		LEFT JOIN penandatangan d ON d.surat_id=a.id
		LEFT JOIN jabatan c ON c.jabatan_id=d.jabatan_id
		LEFT JOIN aparatur f ON f.jabatan_id=d.jabatan_id
		WHERE a.id='$id' LIMIT 1
		");
		// [UPDATE] Fikri Egov 26012022 16:12Pm Pengkondisian Jika Surat Nota Dinas, Maka Penandatanganan Si Pembuatnya
		// Update @Mpik Egov 29/07/2022
		$uri = $this->db->query("SELECT * FROM penandatangan 
		LEFT JOIN aparatur ON aparatur.jabatan_id=penandatangan.jabatan_id
		LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
		WHERE penandatangan.surat_id='$id' AND aparatur.statusaparatur='Aktif'")->row_array(); // Update @Mpik Egov 25/07/2022
        // $uri = $this->db->query("SELECT * FROM penandatangan WHERE surat_id = '$id'")->row_array();
		// Update @Mpik Egov 29/07/2022
		$status = $uri['status'];
		$kopsurat = $query->row_array();
			$content = $this->load->view('exportsurat/notadinas_pdf', array (
				'notadinas' 	=> $query->result(),
				'tte' => $uri
			), TRUE);
			$content2 = $this->load->view('exportsurat/lampiran_pdf', array(
    			'lampiran' 	=> $query->result()
    		), TRUE);
		
		$this->pdf->create($content, $filename, $qr, $content2, $status);
	}

	public function keterangan()
	{
		$id = $this->uri->segment(3);
		
		$qfilename = $this->db->get_where('surat_keterangan', array('id' => $id))->row_array();
		$filename = 'Surat Keterangan - '.tanggal($qfilename['tanggal']).' ('.$qfilename['id'].')';
		$qr = $qfilename['id'];
		
		$this->load->library('pdf');

//		$query = $this->db->query("SELECT a.id, a.surat_id, a.tanggal, a.dibuat_id, a.penandatangan_id, a.verifikasi_id, a. nama_surat, b.id, b.opd_id, b.kop_id, b.kodesurat_id, b.nomor, b.pegawai_id, b.maksud, b.penutup, b.tanggal, c.opd_id, c.nama_pd, c.kode_pd, c.alamat, c.telp, c.email, c.alamat_website, d.penandatangan_id, d.surat_id, d.jabatan_id, d.nama AS namapejabat, d.jabatan AS jabatanpejabat, d.status, e.aparatur_id, e.jabatan_id, e.opd_id, e.nip AS nippejabat, e.nama AS namapejabat, e.eselon, e.pangkat AS pangkatpejabat, e.golongan, f.aparatur_id, f.jabatan_id, f.opd_id, f.nip AS nippegawai, f.nama AS namapegawai, f.eselon, f.pangkat AS pangkatpegawai, f.golongan AS golonganpegawai, g.jabatan_id, g.opd_id, g.nama_jabatan AS jabatanpegawai, g.jabatan FROM draft a LEFT JOIN surat_keterangan b ON b.id = a.surat_id LEFT JOIN opd c ON c.opd_id = b.opd_id LEFT JOIN penandatangan d ON d.penandatangan_id = a.penandatangan_id	LEFT JOIN aparatur e ON e.jabatan_id = d.jabatan_id LEFT JOIN aparatur f ON b.pegawai_id  = f.aparatur_id LEFT JOIN jabatan g ON f.jabatan_id  = g.jabatan_id	WHERE b.id = '$id' ");

		$query = $this->db->query("SELECT a.id, a.opd_id, a.kop_id, a.kodesurat_id, a.nomor, a.pegawai_id, a.maksud, a.penutup, a.catatan, a.tanggal, b.alamat_website, b.opd_id, b.nama_pd, b.kode_pd, b.alamat, b.telp, b.faksimile, b.email, b.alamat_website, c.aparatur_id, c.jabatan_id, c.nip AS nippegawai, c.nama AS namapegawai, c.pangkat AS pangkatpegawai, c.golongan AS golonganpegawai, d.jabatan_id, d.nama_jabatan AS jabatanpegawai, d.jabatan, e.surat_id, e.jabatan_id, e.nama AS namapejabat, e.jabatan AS jabatanpejabat, e.status, f.jabatan_id, f.nama_jabatan AS namajabatanpejabat, f.jabatan AS jabatanpejabat, g.nip AS nippejabat, g.pangkat AS pangkatpejabat FROM surat_keterangan a LEFT JOIN opd b ON a.opd_id = b.opd_id LEFT JOIN aparatur c ON a.pegawai_id = c.aparatur_id LEFT JOIN jabatan d ON c.jabatan_id = d.jabatan_id LEFT JOIN penandatangan e ON a.id = e.surat_id LEFT JOIN jabatan f ON e.jabatan_id = f.jabatan_id LEFT JOIN aparatur g ON e.jabatan_id = g.jabatan_id WHERE a.id = '$id' ");	
		// Update @Mpik Egov 29/07/2022
		$uri = $this->db->query("SELECT * FROM penandatangan 
		LEFT JOIN aparatur ON aparatur.jabatan_id=penandatangan.jabatan_id
		LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
		WHERE penandatangan.surat_id='$id' AND aparatur.statusaparatur='Aktif'")->row_array(); // Update @Mpik Egov 25/07/2022
        // $uri = $this->db->query("SELECT * FROM penandatangan WHERE surat_id = '$id'")->row_array();
		// Update @Mpik Egov 29/07/2022
		$status = $uri['status'];
		
		$kopsurat = $query->row_array();
			$content = $this->load->view('exportsurat/keterangan_pdf', array (
				'keterangan'	=> $query->result(),
				'tte' => $uri
			), TRUE);
			$content2 = $this->load->view('exportsurat/lampiran_pdf', array(
    			'lampiran' 	=> $query->result()
    		), TRUE);
		$this->pdf->create($content, $filename, $qr, $content2, $status);
	}

	public function perintahtugas()
	{
		$id = $this->uri->segment(3);
		
		$qfilename = $this->db->get_where('surat_perintahtugas', array('id' => $id))->row_array();
		$filename = 'Surat Perintah Tugas - '.tanggal($qfilename['tanggal']).' ('.$qfilename['id'].')';
		$qr = $qfilename['id'];
		
		$this->load->library('pdf');

		$query = $this->db->query("SELECT 
		a.id,a.opd_id, a.kop_id, a.kodesurat_id,a.kepada, a.nomor, a.dasar, a.untuk, a.catatan, a.tanggal, a.pegawai_id, a.tembusan, 	
		b.opd_id, b.nama_pd,b.opd_induk, b.kode_pd, b.alamat, b.telp, b.faksimile, b.email, b.alamat_website, 
		c.aparatur_id, c.jabatan_id, c.nip AS nippegawai, c.nama AS namapegawai, c.pangkat AS pangkatpegawai, c.golongan AS golonganpegawai, 
		d.jabatan_id, d.nama_jabatan AS jabatanpegawai, d.jabatan, 
		e.surat_id, e.jabatan_id, e.nama AS namapejabat, e.jabatan AS jabatanpejabat, e.status, 
		f.jabatan_id, f.nama_jabatan AS namajabatanpejabat, f.jabatan AS jabatanpejabat, 
		g.nip AS nippejabat, g.pangkat AS pangkatpejabat 
		FROM surat_perintahtugas a LEFT JOIN opd b ON a.opd_id = b.opd_id LEFT JOIN aparatur c ON a.pegawai_id = c.aparatur_id LEFT JOIN jabatan d ON c.jabatan_id = d.jabatan_id LEFT JOIN penandatangan e ON a.id = e.surat_id LEFT JOIN jabatan f ON e.jabatan_id = f.jabatan_id LEFT JOIN aparatur g ON e.jabatan_id = g.jabatan_id WHERE a.id = '$id' LIMIT 1
		");
		// Update @Mpik Egov 29/07/2022
		$uri = $this->db->query("SELECT * FROM penandatangan 
		LEFT JOIN aparatur ON aparatur.jabatan_id=penandatangan.jabatan_id
		LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
		WHERE penandatangan.surat_id='$id' AND aparatur.statusaparatur='Aktif'")->row_array(); // Update @Mpik Egov 25/07/2022
        // $uri = $this->db->query("SELECT * FROM penandatangan WHERE surat_id = '$id'")->row_array();
		// Update @Mpik Egov 29/07/2022
		$status = $uri['status'];
		
		$kopsurat = $query->row_array();
			$content = $this->load->view('exportsurat/perintahtugas_pdf', array (
				'perintahtugas'	=> $query->result(),
				'tte' => $uri // Update @Mpik Egov 29/07/2022
			), TRUE);
			$content2 = $this->load->view('exportsurat/lampiranperintahtugas_pdf', array(
    			'lampiran' 	=> $query->result()
    		), TRUE);
		ob_end_clean();
		$this->pdf->create($content, $filename, $qr, $content2, $status);
	}

	public function perintah()
	{
		$id = $this->uri->segment(3);
		
		$qfilename = $this->db->get_where('surat_perintah', array('id' => $id))->row_array();
		$filename = 'Surat Perintah - '.tanggal($qfilename['tanggal']).' ('.$qfilename['id'].')';
		$qr = $qfilename['id'];
		
		$this->load->library('pdf');

		$query = $this->db->query("SELECT 
		a.id, a.kepada,a.opd_id, a.kop_id, a.kodesurat_id, a.nomor, a.dasar, a.untuk, a.catatan, a.tanggal, a.pegawai_id, a.tembusan, 	
		b.opd_id, b.nama_pd, b.kode_pd, b.alamat, b.telp, b.faksimile, b.email, b.alamat_website, 
		c.aparatur_id, c.jabatan_id, c.nip AS nippegawai, c.nama AS namapegawai, c.pangkat AS pangkatpegawai, c.golongan AS golonganpegawai, 
		d.jabatan_id, d.nama_jabatan AS jabatanpegawai, d.jabatan, 
		e.surat_id, e.jabatan_id, e.nama AS namapejabat, e.jabatan AS jabatanpejabat, e.status, 
		f.jabatan_id, f.nama_jabatan AS namajabatanpejabat, f.jabatan AS jabatanpejabat, 
		g.nip AS nippejabat, g.pangkat AS pangkatpejabat 
		FROM surat_perintah a LEFT JOIN opd b ON a.opd_id = b.opd_id LEFT JOIN aparatur c ON a.pegawai_id = c.aparatur_id LEFT JOIN jabatan d ON c.jabatan_id = d.jabatan_id LEFT JOIN penandatangan e ON a.id = e.surat_id LEFT JOIN jabatan f ON e.jabatan_id = f.jabatan_id LEFT JOIN aparatur g ON e.jabatan_id = g.jabatan_id WHERE a.id = '$id' ");
		// Update @Mpik Egov 29/07/2022
		$uri = $this->db->query("SELECT * FROM penandatangan 
		LEFT JOIN aparatur ON aparatur.jabatan_id=penandatangan.jabatan_id
		LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
		WHERE penandatangan.surat_id='$id' AND aparatur.statusaparatur='Aktif'")->row_array(); // Update @Mpik Egov 25/07/2022
        // $uri = $this->db->query("SELECT * FROM penandatangan WHERE surat_id = '$id'")->row_array();
		// Update @Mpik Egov 29/07/2022
		$status = $uri['status'];
		
		$kopsurat = $query->row_array();
			$content = $this->load->view('exportsurat/perintah_pdf', array (
				'perintah'	=> $query->result(),
				'tte' => $uri // Update @Mpik Egov 29/07/2022
			), TRUE);
			$content2 = $this->load->view('exportsurat/lampiran_pdf', array(
    			'lampiran' 	=> $query->result()
    		), TRUE);
		
		$this->pdf->create($content, $filename, $qr, $content2, $status);
	}
	
	public function beritaacara()
	{
		$id = $this->uri->segment(3);
		
		$qfilename = $this->db->get_where('surat_beritaacara', array('id' => $id))->row_array();
		$filename = 'Surat Berita Acara - '.tanggal($qfilename['tanggal']).' ('.$qfilename['id'].')';
		$qr = $qfilename['id'];
		
		$this->load->library('pdf');

//		$query = $this->db->query("	SELECT surat_perintah.kop_id,draft.surat_id,surat_perintah.id,opd.opd_id,surat_perintah.opd_id,penandatangan.penandatangan_id,draft.penandatangan_id,aparatur.jabatan_id,penandatangan.jabatan_id,jabatan.jabatan_id,penandatangan.jabatan_id,opd.nama_pd,opd.alamat,opd.telp,opd.alamat_website,opd.email,			surat_perintah.nomor,surat_perintah.nama_pejabat,surat_perintah.jabatan AS jabatan_perintah,		surat_perintah.isi,draft.verifikasi_id,surat_perintah.tanggal,			penandatangan.jabatan,penandatangan.nama,aparatur.pangkat,aparatur.nip,surat_perintah.tembusan			FROM draft			LEFT JOIN surat_perintah ON surat_perintah.id = draft.surat_id			LEFT JOIN opd ON opd.opd_id = surat_perintah.opd_id			LEFT JOIN penandatangan ON penandatangan.penandatangan_id = draft.penandatangan_id			LEFT JOIN aparatur ON aparatur.jabatan_id = penandatangan.jabatan_id			LEFT JOIN jabatan ON jabatan.jabatan_id = penandatangan.jabatan_id			WHERE surat_perintah.id = '$id'		");
		
		$query = $this->db->query("
		SELECT 
		a.id, a.opd_id, a.kop_id, a.kodesurat_id, a.nomor, a.isi, a.catatan, a.tanggal,
		b.opd_id, b.nama_pd, b.kode_pd, b.alamat, b.telp, b.faksimile, b.email, b.alamat_website, 
		c.aparatur_id, c.jabatan_id, c.nip AS nip1, c.nama AS nama1, c.pangkat AS pangkat1, c.golongan AS golonganpegawai, 
		d.jabatan_id, d.nama_jabatan AS jabatanpegawai1, d.jabatan, i.nama_jabatan AS jabatanpegawai2,
		e.surat_id, e.jabatan_id, e.nama AS namapejabat, e.jabatan AS jabatanpejabat, e.status, 
		f.jabatan_id, f.nama_jabatan AS namajabatanpejabat, f.jabatan AS jabatanpejabat, 
		g.nip AS nippejabat, g.pangkat AS pangkatpejabat, h.nip as nip2, h.nama as nama2, h.pangkat as pangkat2,i.nama_jabatan as jabatanpegawai2
		FROM surat_beritaacara a
		LEFT JOIN opd b ON a.opd_id = b.opd_id
		LEFT JOIN aparatur c ON a.pihakpertama_id = c.aparatur_id
		LEFT JOIN aparatur h ON a.pihakkedua_id = h.aparatur_id
		LEFT JOIN jabatan d ON c.jabatan_id = d.jabatan_id
		LEFT JOIN jabatan i ON h.jabatan_id = i.jabatan_id
		LEFT JOIN penandatangan e ON a.id = e.surat_id
		LEFT JOIN jabatan f ON e.jabatan_id = f.jabatan_id
		LEFT JOIN aparatur g ON e.jabatan_id = g.jabatan_id
		WHERE a.id='$id'");
		// Update @Mpik Egov 29/07/2022
		$uri = $this->db->query("SELECT * FROM penandatangan 
		LEFT JOIN aparatur ON aparatur.jabatan_id=penandatangan.jabatan_id
		LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
		WHERE penandatangan.surat_id='$id' AND aparatur.statusaparatur='Aktif'")->row_array(); // Update @Mpik Egov 25/07/2022
        // $uri = $this->db->query("SELECT * FROM penandatangan WHERE surat_id = '$id'")->row_array();
		// Update @Mpik Egov 29/07/2022
		$status = $uri['status'];
		
		
		$kopsurat = $query->row_array();
			$content = $this->load->view('exportsurat/beritaacara_pdf', array (
				'beritaacara'	=> $query->result(),
				'tte' => $uri // Update @Mpik Egov 29/07/2022
			), TRUE);
			$content2 = $this->load->view('exportsurat/lampiran_pdf', array(
    			'lampiran' 	=> $query->result()
    		), TRUE);
		
		$this->pdf->create($content, $filename, $qr, $content2, $status);
	}


		public function izin()
	{
		$id = $this->uri->segment(3);
		
		$qfilename = $this->db->get_where('surat_izin', array('id' => $id))->row_array();
		$filename = 'Surat Izin - '.tanggal($qfilename['tanggal']).' ('.$qfilename['id'].')';
		$qr = $qfilename['id'];
		
		$this->load->library('pdf');

		$query = $this->db->query("
		SELECT 
		a.id, a.opd_id, a.kop_id, a.kodesurat_id, a.nomor, a.dasar, a.untuk, a.catatan, a.tanggal, a.pegawai_id, a.tentang,	
		b.opd_id, b.nama_pd, b.kode_pd, b.alamat, b.telp, b.faksimile, b.email, b.alamat_website, 
		c.aparatur_id, c.jabatan_id, c.nip AS nippegawai, c.nama AS namapegawai, c.pangkat AS pangkatpegawai, c.golongan AS golonganpegawai, 
		d.jabatan_id, d.nama_jabatan AS jabatanpegawai, d.jabatan, 
		e.surat_id, e.jabatan_id, e.nama AS namapejabat, e.jabatan AS jabatanpejabat, e.status, 
		f.jabatan_id, f.nama_jabatan AS namajabatanpejabat, f.jabatan AS jabatanpejabat, 
		g.nip AS nippejabat, g.pangkat AS pangkatpejabat, h.nama_pd as namapd
		FROM surat_izin a
		LEFT JOIN opd b ON a.opd_id = b.opd_id
		LEFT JOIN aparatur c ON a.pegawai_id = c.aparatur_id
		LEFT JOIN jabatan d ON c.jabatan_id = d.jabatan_id
		LEFT JOIN opd h ON c.opd_id = h.opd_id
		LEFT JOIN penandatangan e ON a.id = e.surat_id
		LEFT JOIN jabatan f ON e.jabatan_id = f.jabatan_id
		LEFT JOIN aparatur g ON e.jabatan_id = g.jabatan_id
		WHERE a.id = '$id'
		");
		// Update @Mpik Egov 29/07/2022
		$uri = $this->db->query("SELECT * FROM penandatangan 
		LEFT JOIN aparatur ON aparatur.jabatan_id=penandatangan.jabatan_id
		LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
		WHERE penandatangan.surat_id='$id' AND aparatur.statusaparatur='Aktif'")->row_array(); // Update @Mpik Egov 25/07/2022
        // $uri = $this->db->query("SELECT * FROM penandatangan WHERE surat_id = '$id'")->row_array();
		// Update @Mpik Egov 29/07/2022
		$status = $uri['status'];
		
		$kopsurat = $query->row_array();
			$content = $this->load->view('exportsurat/izin_pdf', array (
				'izin'	=> $query->result(),
				'tte' => $uri
			), TRUE);
			$content2 = $this->load->view('exportsurat/lampiran_pdf', array(
    			'lampiran' 	=> $query->result()
    		), TRUE);
		
		$this->pdf->create($content, $filename, $qr, $content2, $status);
	}

	public function perjalanan()
	{
		$id = $this->uri->segment(3);
		
		$qfilename = $this->db->get_where('surat_perjalanan', array('id' => $id))->row_array();
		$filename = 'Surat Perjalanan Dinas - '.tanggal($qfilename['tanggal']).' ('.$qfilename['id'].')';
		$qr = $qfilename['id'];
		
		$this->load->library('pdf');

		$query = $this->db->query("
		SELECT a.id,a.tanggal,a.penggunaanggaran,a.pengikut_id,a.nomor,a.tingkat_biaya,a.maksud_perjalanan,a.alat_angkutan,a.tmpt_berangkat,a.tmpt_tujuan,a.lama_perjalanan,a.tgl_berangkat,a.tgl_pulang,a.keterangan,a.catatan,
		b.alamat,b.telp,b.faksimile,b.email,b.nama_pd,b.alamat_website,
		c.nama AS namapelaksana,c.nip AS nippelaksana,c.pangkat AS pangkatpelaksana,c.golongan,g.nama_jabatan AS jabatanpelaksana,
		h.nama AS namapengikut,
		i.nama_pd as namaperangkat,i.email as akun,
		d.surat_id,d.jabatan_id,d.nama as namapejabat,d.jabatan,d.`status`,
		f.nip, f.pangkat, e.jabatan as jabatanpejabat, e.nama_jabatan as kuasaanggaran
		FROM surat_perjalanan a
		LEFT JOIN aparatur c ON c.nip=a.pegawai_id
		LEFT JOIN jabatan g ON g.jabatan_id=c.jabatan_id
		LEFT JOIN aparatur h ON h.aparatur_id=a.pengikut_id
		LEFT JOIN opd i ON i.opd_id=a.perangkatdaerah_id
		LEFT JOIN opd b ON b.opd_id=a.opd_id
		LEFT JOIN penandatangan d ON d.surat_id=a.id
		LEFT JOIN jabatan e ON e.jabatan_id=d.jabatan_id
		LEFT JOIN aparatur f ON f.jabatan_id=d.jabatan_id
		WHERE a.id = '$id'
		");
		// Update @Mpik Egov 29/07/2022
		$uri = $this->db->query("SELECT * FROM penandatangan 
		LEFT JOIN aparatur ON aparatur.jabatan_id=penandatangan.jabatan_id
		LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
		WHERE penandatangan.surat_id='$id' AND aparatur.statusaparatur='Aktif'")->row_array(); // Update @Mpik Egov 25/07/2022
        // $uri = $this->db->query("SELECT * FROM penandatangan WHERE surat_id = '$id'")->row_array();
		// Update @Mpik Egov 29/07/2022
		$status = $uri['status'];
		
		$content = $this->load->view('exportsurat/perjalanan_pdf', array (
			'perjalanan'	=> $query->result(),
			'tte' => $uri // Update @Mpik Egov 29/07/2022
		), TRUE);
		$content2 = $this->load->view('exportsurat/lampiranperjalanan_pdf', array(
			'lampiran' 	=> $query->result()
		), TRUE);
		
		$this->pdf->create($content, $filename, $qr, $content2, $status);
	}

	public function kuasa()
	{
		$id = $this->uri->segment(3);
		
		$qfilename = $this->db->get_where('surat_kuasa', array('id' => $id))->row_array();
		$filename = 'Surat Kuasa - '.tanggal($qfilename['tanggal']).' ('.$qfilename['id'].')';
		$qr = $qfilename['id'];
		
		$this->load->library('pdf');

		$query = $this->db->query("SELECT 
		a.id,a.tanggal,a.nomor,a.untuk,a.catatan,a.kop_id,a.pegawai_id,
        c.nama,c.nip,
		b.opd_id, b.nama_pd, b.kode_pd, b.alamat, b.telp, b.faksimile, b.email, b.alamat_website, 
		e.surat_id, e.jabatan_id, e.nama AS namapejabat, e.jabatan AS jabatanpejabat, e.status, 
		f.jabatan_id, f.nama_jabatan AS namajabatanpejabat, f.jabatan AS jabatanpejabat, 
		g.nip AS nippejabat, g.pangkat AS pangkatpejabat
		FROM surat_kuasa a
        LEFT JOIN aparatur c ON c.nip=a.pegawai_id
		LEFT JOIN opd b ON a.opd_id = b.opd_id
		LEFT JOIN penandatangan e ON a.id = e.surat_id
		LEFT JOIN jabatan f ON e.jabatan_id = f.jabatan_id
		LEFT JOIN aparatur g ON e.jabatan_id = g.jabatan_id
		WHERE a.id='$id'
		");
		// Update @Mpik Egov 29/07/2022
		$uri = $this->db->query("SELECT * FROM penandatangan 
		LEFT JOIN aparatur ON aparatur.jabatan_id=penandatangan.jabatan_id
		LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
		WHERE penandatangan.surat_id='$id' AND aparatur.statusaparatur='Aktif'")->row_array(); // Update @Mpik Egov 25/07/2022
        // $uri = $this->db->query("SELECT * FROM penandatangan WHERE surat_id = '$id'")->row_array();
		// Update @Mpik Egov 29/07/2022
		$status = $uri['status'];
		
		$kopsurat = $query->row_array();
			$content = $this->load->view('exportsurat/kuasa_pdf', array (
				'kuasa'	=> $query->result(),
				'tte' => $uri // Update @Mpik Egov 29/07/2022
			), TRUE);
			$content2 = $this->load->view('exportsurat/lampiran_pdf', array(
    			'lampiran' 	=> $query->result()
    		), TRUE);
		
		$this->pdf->create($content, $filename, $qr, $content2, $status);
	}

	public function melaksanakan_tugas()
	{
		$id = $this->uri->segment(3);
		
		$qfilename = $this->db->get_where('surat_melaksanakantugas', array('id' => $id))->row_array();
		$filename = 'Surat Melaksanakan Tugas - '.tanggal($qfilename['tanggal']).' ('.$qfilename['id'].')';
		$qr = $qfilename['id'];
		
		$this->load->library('pdf');

		$query = $this->db->query("
		SELECT a.id, a.opd_id, a.kop_id, a.kodesurat_id,  a.tanggal, a.nomor, a.pegawai_id, a.dasarsk, a.nomorsk, a.tmt, a.tugas, a.catatan,	
		b.opd_id, b.nama_pd, b.kode_pd, b.alamat, b.telp, b.faksimile, b.email, b.alamat_website, c.aparatur_id, c.jabatan_id, c.nip AS nippegawai, c.nama AS namapegawai, c.pangkat AS pangkatpegawai, c.golongan AS golonganpegawai, d.jabatan_id, d.nama_jabatan AS jabatanpegawai, d.jabatan, e.surat_id, e.jabatan_id, e.nama AS namapejabat, e.jabatan AS jabatanpejabat, e.status, f.jabatan_id, f.nama_jabatan AS namajabatanpejabat, f.jabatan AS jabatanpejabat, g.nip AS nippejabat, g.pangkat AS pangkatpejabat 
		FROM surat_melaksanakantugas a LEFT JOIN opd b ON a.opd_id = b.opd_id LEFT JOIN aparatur c ON a.pegawai_id = c.aparatur_id LEFT JOIN jabatan d ON c.jabatan_id = d.jabatan_id LEFT JOIN penandatangan e ON a.id = e.surat_id LEFT JOIN jabatan f ON e.jabatan_id = f.jabatan_id LEFT JOIN aparatur g ON e.jabatan_id = g.jabatan_id WHERE a.id = '$id' LIMIT 1");	
		// Update @Mpik Egov 29/07/2022
		$uri = $this->db->query("SELECT * FROM penandatangan 
		LEFT JOIN aparatur ON aparatur.jabatan_id=penandatangan.jabatan_id
		LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
		WHERE penandatangan.surat_id='$id' AND aparatur.statusaparatur='Aktif'")->row_array(); // Update @Mpik Egov 25/07/2022
        // $uri = $this->db->query("SELECT * FROM penandatangan WHERE surat_id = '$id'")->row_array();
		// Update @Mpik Egov 29/07/2022
		$status = $uri['status'];
		
		$kopsurat = $query->row_array();
			$content = $this->load->view('exportsurat/melaksanakantugas_pdf', array (
				'melaksanakantugas'	=> $query->result(),
				'tte' => $uri
			), TRUE);
			$content2 = $this->load->view('exportsurat/lampiran_pdf', array(
    			'lampiran' 	=> $query->result()
    		), TRUE);
		
		$this->pdf->create($content, $filename, $qr, $content2, $status);
	}

	public function panggilan()
	{
		$id = $this->uri->segment(3);
		
		$qfilename = $this->db->get_where('surat_panggilan', array('id' => $id))->row_array();
		$filename = 'Surat Panggilan - '.tanggal($qfilename['tanggal']).' ('.$qfilename['id'].')';
		$qr = $qfilename['id'];
		
		$this->load->library('pdf');

		$query = $this->db->query("
		SELECT a.id,a.tanggal,a.nomor,a.sifat,a.lampiran,a.hal,a.kantor,a.hari,a.tgl_acara,a.pukul,a.tempat,a.alamat,a.untuk,a.catatan,a.kop_id,
		b.nama_jabatan as namajabatana,c.nama_jabatan as namajabatanb,
		d.nama_pd,d.alamat,d.telp,d.faksimile,d.email,d.alamat_website,d.opd_induk,
		e.nama,e.jabatan,e.status,f.jabatan AS jabatanpejabat,
		g.nip,g.nama as namapejabat,g.pangkat
		FROM surat_panggilan a
		LEFT JOIN jabatan b ON b.jabatan_id=a.kepada_id
		LEFT JOIN jabatan c ON c.jabatan_id=a.menghadapkepada_id
		LEFT JOIN opd d ON d.opd_id=a.opd_id
		LEFT JOIN penandatangan e ON e.surat_id=a.id
		LEFT JOIN jabatan f ON f.jabatan_id=e.jabatan_id
		LEFT JOIN aparatur g ON g.jabatan_id=e.jabatan_id
		WHERE a.id = '$id' LIMIT 1
		");
		$uri = $this->db->query("SELECT * FROM penandatangan LEFT JOIN aparatur ON aparatur.jabatan_id=penandatangan.jabatan_id WHERE penandatangan.surat_id='$id' AND aparatur.statusaparatur='Aktif'")->row_array(); // Update @Mpik Egov 25/07/2022
        // $uri = $this->db->query("SELECT * FROM penandatangan WHERE surat_id = '$id'")->row_array();
		// Update @Mpik Egov 29/07/2022
		$status = $uri['status'];
		
		$kopsurat = $query->row_array();
			$content = $this->load->view('exportsurat/panggilan_pdf', array (
				'panggilan'	=> $query->result(),
				'tte' => $uri
			), TRUE);
			$content2 = $this->load->view('exportsurat/lampiran_pdf', array(
    			'lampiran' 	=> $query->result()
    		), TRUE);
		
		$this->pdf->create($content, $filename, $qr, $content2, $status);
	}

	public function notulen()
	{
		$id = $this->uri->segment(3);
		
		$qfilename = $this->db->get_where('surat_notulen', array('id' => $id))->row_array();
		$filename = 'Notulen - '.tanggal($qfilename['tanggal']).' ('.$qfilename['id'].')';
		$qr = $qfilename['id'];
		
		$this->load->library('pdf');

		$query = $this->db->query("SELECT 
		a.id, a.opd_id, a.kodesurat_id,a.nomor,a.rapat,a.tanggal,a.waktu_pgl,a.wakturapat,a.acara,a.peserta_id,a.kegiatan_rapat,a.pembukaan,a.pembahasan,a.peraturan,a.catatan,
		b.opd_id, b.nama_pd, b.kode_pd, b.alamat, b.telp, b.faksimile, b.email, b.alamat_website, 
		c.aparatur_id, c.jabatan_id, c.nip AS nipketua, c.nama AS namaketua, c.pangkat AS pangkatketua, c.golongan AS golonganketua, 
		i.aparatur_id, i.jabatan_id, i.nip AS nipsekertaris, i.nama AS namasekertaris, i.pangkat AS pangkatsekertaris, i.golongan AS golongansekertaris, 
		h.aparatur_id, h.jabatan_id, h.nip AS nippencatat, h.nama AS namapencatat, h.pangkat AS pangkatpencatat, h.golongan AS golonganpencatat, 
		d.jabatan_id, d.nama_jabatan AS jabatanpegawai, d.jabatan, 
		e.surat_id, e.jabatan_id, e.nama AS namapejabat, e.jabatan AS jabatanpejabat, e.status, 
		f.jabatan_id, f.nama_jabatan AS namajabatanpejabat, f.jabatan AS jabatanpejabat, 
		g.nip AS nippejabat, g.pangkat AS pangkatpejabat
		FROM surat_notulen a
		LEFT JOIN opd b ON a.opd_id = b.opd_id
		LEFT JOIN aparatur c ON a.ketua_id = c.aparatur_id
		LEFT JOIN aparatur i ON a.sekertaris_id = i.aparatur_id
		LEFT JOIN aparatur h ON a.pencatat_id = h.aparatur_id
		LEFT JOIN jabatan d ON c.jabatan_id = d.jabatan_id
		LEFT JOIN penandatangan e ON a.id = e.surat_id
		LEFT JOIN jabatan f ON e.jabatan_id = f.jabatan_id
		LEFT JOIN aparatur g ON e.jabatan_id = g.jabatan_id
		WHERE a.id = '$id'
		");
		// Update @Mpik Egov 29/07/2022
		$uri = $this->db->query("SELECT * FROM penandatangan 
		LEFT JOIN aparatur ON aparatur.jabatan_id=penandatangan.jabatan_id
		LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
		WHERE penandatangan.surat_id='$id' AND aparatur.statusaparatur='Aktif'")->row_array(); // Update @Mpik Egov 25/07/2022
        // $uri = $this->db->query("SELECT * FROM penandatangan WHERE surat_id = '$id'")->row_array();
		// Update @Mpik Egov 29/07/2022
		$status = $uri['status'];
		
		$content = $this->load->view('exportsurat/notulen_pdf', array (
			'notulen'	=> $query->result(),
			'tte' => $uri // Update @Mpik Egov 29/07/2022
		), TRUE);
		$content2 = $this->load->view('exportsurat/lampiran_pdf', array(
			'lampiran' 	=> $query->result()
		), TRUE);
		
		$this->pdf->create($content, $filename, $qr, $content2, $status);
	}

	public function memo()
	{
		$id = $this->uri->segment(3);
		
		$qfilename = $this->db->get_where('surat_memo', array('id' => $id))->row_array();
		$filename = 'Memo - '.tanggal($qfilename['tanggal']).' ('.$qfilename['id'].')';
		$qr = $qfilename['id'];
		
		$this->load->library('pdf');

		$query = $this->db->query("
		SELECT a.id,a.nomor,a.tanggal,a.isi,a.catatan,a.kop_id,c.nama_jabatan AS namakepada,g.nama_jabatan AS namadari,
		b.alamat,b.telp,b.faksimile,b.email,b.nama_pd,b.alamat_website,
		d.surat_id,d.jabatan_id,d.nama,d.jabatan,d.`status`,
		f.nip, f.pangkat
		FROM surat_memo a
		LEFT JOIN jabatan c ON c.jabatan_id=a.kepada_id 
		LEFT JOIN jabatan g ON g.jabatan_id=a.dari_id
		LEFT JOIN opd b ON b.opd_id=a.opd_id
		LEFT JOIN penandatangan d ON d.surat_id=a.id
		LEFT JOIN jabatan e ON e.jabatan_id=d.jabatan_id
		LEFT JOIN aparatur f ON f.jabatan_id=d.jabatan_id
		WHERE a.id = '$id'
		");
		// Update @Mpik Egov 29/07/2022
		$uri = $this->db->query("SELECT * FROM penandatangan 
		LEFT JOIN aparatur ON aparatur.jabatan_id=penandatangan.jabatan_id
		LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
		WHERE penandatangan.surat_id='$id' AND aparatur.statusaparatur='Aktif'")->row_array(); // Update @Mpik Egov 25/07/2022
        // $uri = $this->db->query("SELECT * FROM penandatangan WHERE surat_id = '$id'")->row_array();
		// Update @Mpik Egov 29/07/2022
		$status = $uri['status'];
		
		$kopsurat = $query->row_array();
			$content = $this->load->view('exportsurat/memo_pdf', array (
				'memo'	=> $query->result(),
				'tte' => $uri // Update @Mpik Egov 29/07/2022
			), TRUE);
			$content2 = $this->load->view('exportsurat/lampiran_pdf', array(
    			'lampiran' 	=> $query->result()
    		), TRUE);
		
		$this->pdf->create($content, $filename, $qr, $content2, $status);
	}

// ============================================================================================================================
// ============================================================================================================================

								// EXPORT UNTUK LEMBAR DISPOSISI

// ============================================================================================================================
// ============================================================================================================================


	public function lembar_disposisi()
	{
		$id = $this->uri->segment(3);

		$this->load->library('pdf');

		$query = $this->db->query("
			SELECT *, surat_masuk.suratmasuk_id FROM surat_masuk
			LEFT JOIN disposisi_suratmasuk ON disposisi_suratmasuk.suratmasuk_id = surat_masuk.suratmasuk_id
			LEFT JOIN kode_surat ON kode_surat.kodesurat_id = surat_masuk.kodesurat_id
			LEFT JOIN opd ON opd.opd_id = surat_masuk.opd_id
			WHERE surat_masuk.suratmasuk_id = '$id'
			LIMIT 1
		");

		$file_name = "Lembar Disposisi Surat - ".tanggal($query->row_array()['diterima']).' ('.$query->row_array()['suratmasuk_id'].')';
		
		$content = $this->load->view('exportsurat/lembardisposisi_pdf', array (
			'disposisi' 	=> $query->result()
		), TRUE);
		
		$this->pdf->create($content, $file_name,$qr=null,$content2=null,$status=null);
	}

	public function lembar_pengembalian()
	{
		$id = $this->uri->segment(3);

		$this->load->library('pdf');

		$query = $this->db->query("
			SELECT * FROM surat_masuk
			LEFT JOIN kode_surat ON kode_surat.kodesurat_id = surat_masuk.kodesurat_id
			LEFT JOIN opd ON opd.opd_id = surat_masuk.opd_id
			WHERE surat_masuk.suratmasuk_id = '$id'
		");

		$file_name = "Lembar Disposisi Surat - ".tanggal($query->row_array()['diterima']).' ('.$query->row_array()['suratmasuk_id'].')';
		
		$content = $this->load->view('exportsurat/lembarpengembalian_pdf', array (
			'disposisi' 	=> $query->result()
		), TRUE);
		
		$this->pdf->create($content, $file_name,$qr=null,$content2=null,$status=null);
	}


	// ============================================================================================================================
// ============================================================================================================================

								// EXPORT UNTUK TANDA TERIMA

// ============================================================================================================================
// ============================================================================================================================


public function tandaterima()
{
	$id = $this->uri->segment(3);

	$this->load->library('pdf');

	$query = $this->db->query("
		SELECT * FROM surat_masuk
		LEFT JOIN kode_surat ON kode_surat.kodesurat_id = surat_masuk.kodesurat_id
		LEFT JOIN opd ON opd.opd_id = surat_masuk.opd_id
		WHERE surat_masuk.suratmasuk_id = '$id'
	");

	$filename = "Tanda Terima Surat - ".tanggal($query->row_array()['diterima']).' ('.$query->row_array()['suratmasuk_id'].')';
	
	$content = $this->load->view('exportsurat/tandaterima_pdf', array (
		'tandaterima' 	=> $query->result()
	), TRUE);
	
	$this->pdf->createlandscape($content, $filename,$qr);
}

public function lampiran(){
	$id = $this->uri->segment(3);

	$this->load->library('pdf');

	$file_name = $this->pdf->convertlampiran($id);
	
	
	redirect('assets/lampiransurat/lampiran/'.$file_name.'.pdf');
}

public function templatette()
{

	$this->load->library('pdf');
	$opdid=$this->session->userdata('opd_id');

	if($opdid == 4){
		$query = $this->db->query("
		SELECT d.nama_jabatan, d.jabatan, a.nama, a.pangkat,a.nip FROM aparatur a
		LEFT JOIN jabatan d ON d.jabatan_id=a.jabatan_id
		LEFT JOIN users b ON b.aparatur_id=a.aparatur_id
		LEFT JOIN level c ON c.level_id=b.level_id
		WHERE a.opd_id=4 AND b.level_id=10
		");
	}else{
		$query = $this->db->query("
		SELECT d.nama_jabatan, d.jabatan, a.nama, a.pangkat,a.nip FROM aparatur a
		LEFT JOIN jabatan d ON d.jabatan_id=a.jabatan_id
		LEFT JOIN users b ON b.aparatur_id=a.aparatur_id
		LEFT JOIN level c ON c.level_id=b.level_id
		WHERE a.opd_id='$opdid' AND b.level_id IN (5,6,22,24,26) AND statusaparatur = 'Aktif'
		");

		$qWalkot = $this->db->query("
		SELECT d.nama_jabatan, d.jabatan, a.nama, a.pangkat,a.nip FROM aparatur a 
		LEFT JOIN jabatan d ON d.jabatan_id=a.jabatan_id 
		LEFT JOIN users b ON b.aparatur_id=a.aparatur_id 
		LEFT JOIN level c ON c.level_id=b.level_id WHERE d.jabatan_id IN (1,2,5,16291) AND statusaparatur = 'Aktif'
		");
	}
	

	$filename = "TTE";
	
	$content = $this->load->view('exportsurat/templatette_pdf', array (
		'tte' 		=> $query->result(),
		'spesimen'	=> $qWalkot->result()
	), TRUE);
	
	$this->pdf->createlandscape($content, $filename,$qr);
}

}
