<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

include 'partials/request_helper.php'; 

function tanggal($tanggal)
{
	$bulan = array(
		'1' => 'Januari', 
		'Februari', 
		'Maret', 
		'April', 
		'Mei', 
		'Juni', 
		'Juli', 
		'Agustus', 
		'September', 
		'Oktober', 
		'November', 
		'Desember', 
	);

	$pecahkan = explode('-', $tanggal);
	return $pecahkan[2].' '.$bulan[(int)$pecahkan[1]].' '.$pecahkan[0];
}

function hari($hari){
    $daftar_hari = array(
    	'Sunday' => 'Minggu',
    	'Monday' => 'Senin',
    	'Tuesday' => 'Selasa',
    	'Wednesday' => 'Rabu',
    	'Thursday' => 'Kamis',
    	'Friday' => 'Jumat',
    	'Saturday' => 'Sabtu'
   	);
   	$namahari = date('l',strtotime(date($hari)));
   	
   	return $daftar_hari[$namahari];
}

function hijriah($tanggal)
{
	$array_bulan = array("Muharram", "Safar", "Rabiul Awwal", "Rabiul Akhir",
						 "Jumadil Awwal","Jumadil Akhir", "Rajab", "Sya'ban", 
						 "Ramadhan","Syawwal", "Zulqaidah", "Zulhijjah");
					 
	$date = (int)(substr($tanggal,8,2));
	$month = (int)(substr($tanggal,5,2));
	$year = (int)(substr($tanggal,0,4));

	if (($year > 1582)||(($year == "1582") && ($month > 10))||(($year == "1582") && ($month=="10")&&($date >14))){
		$jd = (int)((1461 * ($year + 4800 + (int)(($month - 14) / 12))) / 4) +
		(int)((367 * ($month - 2 - 12 * ((int)(($month - 14) / 12)))) / 12) -
		(int)((3 * ((int)(($year + 4900 + (int)(($month - 14) / 12)) / 100))) / 4)+
		$date - 32075; 
	}else{
		$jd = 367 * $year - (int)((7 * ($year + 5001 + (int)(($month - 9) / 7))) / 4) +
		(int)((275 * $month) / 9) + $date + 1729777;
	}

	$wd = $jd % 7;
	$l = $jd - 1948440 + 10633;
	$n = (int) (($l - 1) / 10631);
	$l = $l - 10631 * $n + 354;
	$z = ((int)((10985 - $l) / 5316)) * ((int)((50 * $l) / 17719)) + ((int)($l / 5670)) * ((int)((43 * $l) / 15238));
	$l = $l - ((int)((30 - $z) / 15)) * ((int)((17719 * $z) / 50)) - ((int)($z / 16)) * ((int)((15238 * $z) / 43)) + 29;
	$m = (int)((24 * $l) / 709);
	$d = $l - (int)((709 * $m) / 24);
	$y = 30 * $n + $z - 30;

	$g = $m - 1;
	$final = "$d $array_bulan[$g] $y H";

	return $final;
}

function export_data($filename){
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=$filename");
	header("Pragma: no-cache");
	header("Expires: 0");
}

function getTU($opd_id)
{
	$ci =& get_instance();


	if ($opd_id == '4') {

	$query = $ci->db->query("
		SELECT * FROM aparatur 
		JOIN users ON aparatur.aparatur_id = users.aparatur_id 
		JOIN jabatan ON jabatan.jabatan_id = aparatur.jabatan_id 
		WHERE aparatur.aparatur_id = 1545 
		");
	}elseif($opd_id == '2'){
		$query = $ci->db->query("
		SELECT * FROM aparatur 
		JOIN users ON aparatur.aparatur_id = users.aparatur_id 
		JOIN jabatan ON jabatan.jabatan_id = aparatur.jabatan_id 
		WHERE aparatur.aparatur_id = 1545 
		");
	} else {

	$query = $ci->db->query("
		SELECT * FROM aparatur 
		JOIN users ON aparatur.aparatur_id = users.aparatur_id 
		JOIN jabatan ON jabatan.jabatan_id = aparatur.jabatan_id 
		WHERE aparatur.opd_id = '$opd_id' AND users.level_id = 4
		");

	}

	return $query->row_array();
}

function sendOpd()
{
	$ci =& get_instance();
	$sendOpd = "
		SELECT *,jabatan.atasan_id FROM opd
		JOIN aparatur ON opd.opd_id = aparatur.opd_id
		JOIN jabatan ON aparatur.jabatan_id = jabatan.jabatan_id
		JOIN users ON aparatur.aparatur_id = users.aparatur_id
		JOIN urutan_opd ON urutan_opd.urutan_id=opd.urutan_id
		WHERE users.level_id = 4 OR users.level_id = 18 AND opd.urutan_id!=0 AND opd.statusopd='Aktif'
		ORDER BY urutan_opd.urutan_id ASC
	";
	return $ci->db->query($sendOpd)->result();
}

function sendEksternal($opdid)
{
	$ci =& get_instance();
	// return $ci->db->get('eksternal_keluar')->result();
	$sendEks="SELECT * FROM eksternal_keluar WHERE opd_id='$opdid'";
	return $ci->db->query($sendEks)->result();
}

function sendTembusanInt()
{
	$ci =& get_instance();
	$tembusan = "
		SELECT *,jabatan.atasan_id FROM opd
		JOIN aparatur ON opd.opd_id = aparatur.opd_id
		JOIN jabatan ON aparatur.jabatan_id = jabatan.jabatan_id
		JOIN users ON aparatur.aparatur_id = users.aparatur_id
		WHERE users.level_id = 4 OR users.level_id = 18
		ORDER BY opd.opd_id ASC
		";
	return $ci->db->query($tembusan)->result();
}

function get_tembusan($surat,$tahun,$opd_id,$jabatan_id,$tembusan,$id)
	{
		$ci =& get_instance();
		
		$query = $ci->db->query("
				SELECT * FROM draft
				LEFT JOIN $surat
				ON draft.surat_id = $surat.id
				LEFT JOIN opd
				ON opd.opd_id = $surat.opd_id
				LEFT JOIN aparatur
				ON aparatur.jabatan_id = draft.penandatangan_id
				WHERE $surat.opd_id = '$opd_id'
				AND LEFT($surat.tanggal, 4) = '$tahun'
				AND draft.dibuat_id = '$jabatan_id'
				AND $surat.id IN ($id)
			");
		return $query;
	}

function sendTembusanEks($opdid)
{
	$ci =& get_instance();
	// return $ci->db->get('eksternal_keluar')->result();
	$sendTembusanEks="SELECT * FROM tembusan_keluar WHERE opd_id='$opdid'";
	return $ci->db->query($sendTembusanEks)->result();
}

function messages()
{
	$ci =& get_instance();
	
	$jabatan_id = $ci->session->userdata('jabatan_id');
	$tahun = $ci->session->userdata('tahun');
	
	$draft = $ci->db->query("
		SELECT *
		FROM draft
		LEFT JOIN aparatur ON aparatur.jabatan_id = draft.dibuat_id
		WHERE LEFT(draft.tanggal, 4) = '$tahun'
		AND draft.verifikasi_id = '$jabatan_id'
		ORDER BY draft.id DESC
	")->num_rows();
	$ttd = $ci->db->get_where('penandatangan', array('jabatan_id' => $jabatan_id, 'status' => 'Belum Ditandatangani'))->num_rows();
	// $suratmasuk = $ci->db->get_where('disposisi_suratmasuk', array('status' => 'Belum Selesai', 'aparatur_id' => $jabatan_id))->num_rows();;
	$suratmasuk = $ci->db->query("SELECT *,surat_masuk.tanggal as tglsurat FROM disposisi_suratmasuk JOIN surat_masuk ON surat_masuk.suratmasuk_id = disposisi_suratmasuk.suratmasuk_id JOIN aparatur ON aparatur.jabatan_id = disposisi_suratmasuk.aparatur_id WHERE disposisi_suratmasuk.status = 'Belum Selesai' AND disposisi_suratmasuk.aparatur_id ='$jabatan_id' and left(surat_masuk.tanggal,4)='$tahun' GROUP BY disposisi_suratmasuk.suratmasuk_id ORDER BY surat_masuk.diterima DESC, LENGTH(surat_masuk.suratmasuk_id) DESC, disposisi_suratmasuk.dsuratmasuk_id DESC")->num_rows();

	$messages = $draft+$ttd+$suratmasuk;

	return $messages;
}

function messages_draft()
{
	$ci =& get_instance();
	
	$jabatan_id = $ci->session->userdata('jabatan_id');
	$tahun = $ci->session->userdata('tahun');
	
	$draft = $ci->db->query("
		SELECT *
		FROM draft
		LEFT JOIN aparatur ON aparatur.jabatan_id = draft.dibuat_id
		WHERE LEFT(draft.tanggal, 4) = '$tahun'
		AND draft.verifikasi_id = '$jabatan_id'
		ORDER BY draft.id DESC
	")->num_rows();

	return $draft;
}

function messages_ttd()
{
	$ci =& get_instance();
	
	$jabatan_id = $ci->session->userdata('jabatan_id');
	$ttd = $ci->db->get_where('penandatangan', array('jabatan_id' => $jabatan_id, 'status' => 'Belum Ditandatangani'))->num_rows();

	return $ttd;
}

function messages_suratmasuk()
{
	$ci =& get_instance();
	
	$jabatan_id = $ci->session->userdata('jabatan_id');
	$tahun = $ci->session->userdata('tahun');
	// $suratmasuk = $ci->db->get_where('disposisi_suratmasuk', array('status' => 'Belum Selesai', 'aparatur_id' => $jabatan_id))->num_rows();
	$suratmasuk = $ci->db->query("SELECT *,surat_masuk.tanggal as tglsurat FROM disposisi_suratmasuk JOIN surat_masuk ON surat_masuk.suratmasuk_id = disposisi_suratmasuk.suratmasuk_id JOIN aparatur ON aparatur.jabatan_id = disposisi_suratmasuk.aparatur_id WHERE disposisi_suratmasuk.status = 'Belum Selesai' AND disposisi_suratmasuk.aparatur_id ='$jabatan_id' and left(surat_masuk.tanggal,4)='$tahun' GROUP BY disposisi_suratmasuk.suratmasuk_id ORDER BY surat_masuk.diterima DESC, LENGTH(surat_masuk.suratmasuk_id) DESC, disposisi_suratmasuk.dsuratmasuk_id DESC")->num_rows();

	return $suratmasuk;
}

function internal_eksternal($jenis_surat,$surat_id,$jabatan_id,$eksternal_id)
{
	$ci =& get_instance();

	if ($jabatan_id != "" AND $eksternal_id != "") {
		//internal
		$jabatan_id = implode(',', $jabatan_id);
		$explodeOpd = explode(',', $jabatan_id);
		$dataOpd = array();
		$index = 0;
		foreach ($explodeOpd as $key => $h) {
			$cekOpd = $ci->db->query("SELECT * FROM disposisi_suratkeluar WHERE users_id = '$h' AND surat_id = '$surat_id'")->num_rows();
			if ($cekOpd > 0) {
				$ci->session->set_flashdata('error', 'Terdapat OPD yang sudah dipilih');
				redirect(site_url('suratkeluar/'.$jenis_surat.'/edit/'.$surat_id));
			}else{
				array_push($dataOpd, array(
					'surat_id' => $surat_id, 
					'users_id' => $h,
					));
				$index++;
			}
		}
		$disposisiOpd = $ci->db->insert_batch('disposisi_suratkeluar', $dataOpd);
		//eksternal
		$eksternal_id = implode(',', $eksternal_id);
		$explodeEksternal = explode(',', $eksternal_id);
		$dataEksternal = array();
		$index = 0;
		foreach ($explodeEksternal as $key => $h) {
			$cekOpd = $ci->db->query("SELECT * FROM disposisi_suratkeluar WHERE users_id = '$h' AND surat_id = '$surat_id'")->num_rows();
			if ($cekOpd > 0) {
				$ci->session->set_flashdata('error', 'Terdapat Eksternal yang sudah dipilih');
				redirect(site_url('suratkeluar/'.$jenis_surat.'/edit/'.$surat_id));
			}else{
				array_push($dataEksternal, array(
					'surat_id' => $surat_id, 
					'users_id' => $h,
					));
				$index++;
			}
		}
		$disposisiEksternal = $ci->db->insert_batch('disposisi_suratkeluar', $dataEksternal);
	}elseif ($jabatan_id != "") {
		//internal
		$jabatan_id = implode(',', $jabatan_id);
		$explodeOpd = explode(',', $jabatan_id);
		$dataOpd = array();
		$index = 0;
		foreach ($explodeOpd as $key => $h) {
			$cekOpd = $ci->db->query("SELECT * FROM disposisi_suratkeluar WHERE users_id = '$h' AND surat_id = '$surat_id'")->num_rows();
			if ($cekOpd > 0) {
				$ci->session->set_flashdata('error', 'Terdapat OPD yang sudah dipilih');
				redirect(site_url('suratkeluar/'.$jenis_surat.'/edit/'.$surat_id));
			}else{
				array_push($dataOpd, array(
					'surat_id' => $surat_id, 
					'users_id' => $h,
					));
				$index++;
			}
		}
		$disposisiOpd = $ci->db->insert_batch('disposisi_suratkeluar', $dataOpd);
	}elseif($eksternal_id != "") {
		//eksternal
		$eksternal_id = implode(',', $eksternal_id);
		$explodeEksternal = explode(',', $eksternal_id);
		$dataEksternal = array();
		$index = 0;
		foreach ($explodeEksternal as $key => $h) {
			$cekOpd = $ci->db->query("SELECT * FROM disposisi_suratkeluar WHERE users_id = '$h' AND surat_id = '$surat_id'")->num_rows();
			if ($cekOpd > 0) {
				$ci->session->set_flashdata('error', 'Terdapat Eksternal yang sudah dipilih');
				redirect(site_url('suratkeluar/'.$jenis_surat.'/edit/'.$surat_id));
			}else{
				array_push($dataEksternal, array(
					'surat_id' => $surat_id, 
					'users_id' => $h,
					));
				$index++;
			}
		}
		$disposisiEksternal = $ci->db->insert_batch('disposisi_suratkeluar', $dataEksternal);
	}else{
		$ci->session->set_flashdata('error', 'Tujuan surat tidak boleh kosong');
		if ($ci->uri->segment(3) == 'insert') {
			redirect(site_url('suratkeluar/'.$jenis_surat.'/add'));
		}else{
			redirect(site_url('suratkeluar/'.$jenis_surat.'/edit/'.$surat_id));
		}
	}
}

function internal_eksternal_tembusan($jenis_surat,$surat_id,$tembusan_id,$tembusaneks_id)
{
	$ci =& get_instance();

	if (!empty($tembusan_id) AND !empty($tembusaneks_id)) {
		//internal
		$tembusan_id = implode(',', $tembusan_id);
		$explodeOpd = explode(',', $tembusan_id);
		$dataOpd = array();
		$index = 0;
		foreach ($explodeOpd as $key => $h) {
			$cekOpd = $ci->db->query("SELECT * FROM tembusan_surat WHERE users_id = '$h' AND surat_id = '$surat_id'")->num_rows();
			if ($cekOpd > 0) {
				$ci->session->set_flashdata('error', 'Terdapat Tembusan yang sudah dipilih');
				redirect(site_url('suratkeluar/'.$jenis_surat.'/edit/'.$surat_id));
			}else{
				array_push($dataOpd, array(
					'surat_id' => $surat_id, 
					'users_id' => $h,
					));
				$index++;
			}
		}
		$disposisiOpd = $ci->db->insert_batch('tembusan_surat', $dataOpd);
		//eksternal
		$tembusaneks_id = implode(',', $tembusaneks_id);
		$explodeEksternal = explode(',', $tembusaneks_id);
		$dataEksternal = array();
		$index = 0;
		foreach ($explodeEksternal as $key => $h) {
			$cekOpd = $ci->db->query("SELECT * FROM tembusan_surat WHERE users_id = '$h' AND surat_id = '$surat_id'")->num_rows();
			if ($cekOpd > 0) {
				$ci->session->set_flashdata('error', 'Terdapat Tembusan Eksternal yang sudah dipilih');
				redirect(site_url('suratkeluar/'.$jenis_surat.'/edit/'.$surat_id));
			}else{
				array_push($dataEksternal, array(
					'surat_id' => $surat_id, 
					'users_id' => $h,
					));
				$index++;
			}
		}
		$disposisiEksternal = $ci->db->insert_batch('tembusan_surat', $dataEksternal);
	}elseif (!empty($tembusan_id)) {
		//internal
		$tembusan_id = implode(',', $tembusan_id);
		$explodeOpd = explode(',', $tembusan_id);
		$dataOpd = array();
		$index = 0;
		foreach ($explodeOpd as $key => $h) {
			$cekOpd = $ci->db->query("SELECT * FROM tembusan_surat WHERE users_id = '$h' AND surat_id = '$surat_id'")->num_rows();
			if ($cekOpd > 0) {
				$ci->session->set_flashdata('error', 'Terdapat Tembusan yang sudah dipilih');
				redirect(site_url('suratkeluar/'.$jenis_surat.'/edit/'.$surat_id));
			}else{
				array_push($dataOpd, array(
					'surat_id' => $surat_id, 
					'users_id' => $h,
					));
				$index++;
			}
		}
		$disposisiOpd = $ci->db->insert_batch('tembusan_surat', $dataOpd);
	}elseif(!empty($tembusaneks_id)) {
		//eksternal
		$tembusaneks_id = implode(',', $tembusaneks_id);
		$explodeEksternal = explode(',', $tembusaneks_id);
		$dataEksternal = array();
		$index = 0;
		foreach ($explodeEksternal as $key => $h) {
			$cekOpd = $ci->db->query("SELECT * FROM tembusan_surat WHERE users_id = '$h' AND surat_id = '$surat_id'")->num_rows();
			if ($cekOpd > 0) {
				$ci->session->set_flashdata('error', 'Terdapat Tembusan Eksternal yang sudah dipilih');
				redirect(site_url('suratkeluar/'.$jenis_surat.'/edit/'.$surat_id));
			}else{
				array_push($dataEksternal, array(
					'surat_id' => $surat_id, 
					'users_id' => $h,
					));
				$index++;
			}
		}
		$disposisiEksternal = $ci->db->insert_batch('tembusan_surat', $dataEksternal);
	}
}

function listOpd($id)
{
	$ci =& get_instance();
	$query = $ci->db->query("
		SELECT * FROM disposisi_suratkeluar
		JOIN aparatur ON aparatur.jabatan_id = disposisi_suratkeluar.users_id
		JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
		JOIN users ON users.aparatur_id = aparatur.aparatur_id
		JOIN opd ON opd.opd_id = aparatur.opd_id
		WHERE disposisi_suratkeluar.surat_id = '$id'
		ORDER BY nama_pd ASC
	");
	return $query->result();
}

function listEksternal($id)
{
	$ci =& get_instance();
	$query = $ci->db->query("
		SELECT * FROM disposisi_suratkeluar
		JOIN eksternal_keluar ON eksternal_keluar.id = disposisi_suratkeluar.users_id
		WHERE disposisi_suratkeluar.surat_id = '$id'
	");
	return $query->result();
}

function listTembusan($id)
{
	$ci =& get_instance();
	$query = $ci->db->query("
		SELECT * FROM tembusan_surat
		JOIN aparatur ON aparatur.jabatan_id = tembusan_surat.users_id
		JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
		JOIN users ON users.aparatur_id = aparatur.aparatur_id
		JOIN opd ON opd.opd_id = aparatur.opd_id
		WHERE tembusan_surat.surat_id = '$id'
		ORDER BY opd.opd_id ASC
	");
	return $query->result();
}

function listEksternalTembusan($id)
{
	$ci =& get_instance();
	$query = $ci->db->query("
		SELECT * FROM tembusan_surat
		JOIN tembusan_keluar ON tembusan_keluar.id = tembusan_surat.users_id
		WHERE tembusan_surat.surat_id = '$id'
	");
	return $query->result();
}

// Update @Mpik Egov 20/06/2022 : Penambahan method lihat lampiran surat keluar
function lihatlampiransuratkeluar($surat_id)
{
	$ci =& get_instance();
	if (substr($surat_id, 0, 2) == 'SU') {
        $qundangan = $ci->db->query("SELECT lampiran_lain FROM surat_undangan WHERE id='$surat_id'")->result();
        foreach ($qundangan as $key => $qu) {
            if (empty($qu->lampiran_lain)) {
                echo '';
            } else {
                echo "| <a href=" . site_url('assets/lampiransurat/undangan/' . $qu->lampiran_lain) . " title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-file-pdf-o fa-stack-2x'></i></span></a>";
            }
        }
    } elseif (substr($surat_id, 0, 2) == 'SB') {
        $qbiasa = $ci->db->query("SELECT lampiran_lain FROM surat_biasa WHERE id='$surat_id'")->result();
        foreach ($qbiasa as $key => $qb) {
            if (empty($qb->lampiran_lain)) {
                echo '';
            } else {
                echo "| <a href=" . site_url('assets/lampiransurat/biasa/' . $qb->lampiran_lain) . " title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-file-pdf-o fa-stack-2x'></i></span></a>";
            }
        }
    } elseif (substr($surat_id, 0, 2) == 'SE') {
        $qedaran = $ci->db->query("SELECT lampiran_lain FROM surat_edaran WHERE id='$surat_id'")->result();
        foreach ($qedaran as $key => $qe) {
            if (empty($qe->lampiran_lain)) {
                echo '';
            } else {
                echo "| <a href=" . site_url('assets/lampiransurat/edaran/' . $qe->lampiran_lain) . " title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-file-pdf-o fa-stack-2x'></i></span></a>";
            }
        }
    } elseif (substr($surat_id, 0, 5) == 'PNGMN') {
        $qpengumuman = $ci->db->query("SELECT lampiran_lain FROM surat_edaran WHERE id='$surat_id'")->result();
        foreach ($qpengumuman as $key => $qpngmn) {
            if (empty($qpngmn->lampiran_lain)) {
                echo '';
            } else {
                echo "| <a href=" . site_url('assets/lampiransurat/edaran/' . $qpngmn->lampiran_lain) . " title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-file-pdf-o fa-stack-2x'></i></span></a>";
            }
        }
    } elseif (substr($surat_id, 0, 5) == 'PNGMN') {
        $qpengumuman = $ci->db->query("SELECT lampiran_lain FROM surat_pengumuman WHERE id='$surat_id'")->result();
        foreach ($qpengumuman as $key => $qpngmn) {
            if (empty($qpngmn->lampiran_lain)) {
                echo '';
            } else {
                echo "| <a href=" . site_url('assets/lampiransurat/pengumuman/' . $qpngmn->lampiran_lain) . " title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-file-pdf-o fa-stack-2x'></i></span></a>";
            }
        }
    } elseif (substr($surat_id, 0, 3) == 'LAP') {
        $qlaporan = $ci->db->query("SELECT lampiran_lain FROM surat_laporan WHERE id='$surat_id'")->result();
        foreach ($qlaporan as $key => $qlap) {
            if (empty($qlap->lampiran_lain)) {
                echo '';
            } else {
                echo "| <a href=" . site_url('assets/lampiransurat/pengumuman/' . $qlap->lampiran_lain) . " title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-file-pdf-o fa-stack-2x'></i></span></a>";
            }
        }
    } elseif (substr($surat_id, 0, 3) == 'REK') {
        $qrekomendasi = $ci->db->query("SELECT lampiran_lain FROM surat_rekomendasi WHERE id='$surat_id'")->result();
        foreach ($qrekomendasi as $key => $qrek) {
            if (empty($qrek->lampiran_lain)) {
                echo '';
            } else {
                echo "| <a href=" . site_url('assets/lampiransurat/rekomendasi/' . $qrek->lampiran_lain) . " title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-file-pdf-o fa-stack-2x'></i></span></a>";
            }
        }
    } elseif (substr($surat_id, 0, 3) == 'INT') {
        $qinstruksi = $ci->db->query("SELECT lampiran_lain FROM surat_instruksi WHERE id='$surat_id'")->result();
        foreach ($qinstruksi as $key => $qint) {
            if (empty($qint->lampiran_lain)) {
                echo '';
            } else {
                echo "| <a href=" . site_url('assets/lampiransurat/instruksi/' . $qrek->lampiran_lain) . " title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-file-pdf-o fa-stack-2x'></i></span></a>";
            }
        }
    } elseif (substr($surat_id, 0, 3) == 'PNG') {
        $qpengantar = $ci->db->query("SELECT lampiran_lain FROM surat_pengantar WHERE id='$surat_id'")->result();
        foreach ($qpengantar as $key => $qpng) {
            if (empty($qpng->lampiran_lain)) {
                echo '';
            } else {
                echo "| <a href=" . site_url('assets/lampiransurat/pengantar/' . $qpng->lampiran_lain) . " title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-file-pdf-o fa-stack-2x'></i></span></a>";
            }
        }
    } elseif (substr($surat_id, 0, 5) == 'NODIN') {
        $qnotadinas = $ci->db->query("SELECT lampiran_lain FROM surat_notadinas WHERE id='$surat_id'")->result();
        foreach ($qnotadinas as $key => $qnodin) {
            if (empty($qnodin->lampiran_lain)) {
                echo '';
            } else {
                echo "| <a href=" . site_url('assets/lampiransurat/notadinas/' . $qnodin->lampiran_lain) . " title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-file-pdf-o fa-stack-2x'></i></span></a>";
            }
        }
    } elseif (substr($surat_id, 0, 2) == 'SK') {
        $qketerangan = $ci->db->query("SELECT lampiran_lain FROM surat_keterangan WHERE id='$surat_id'")->result();
        foreach ($qketerangan as $key => $qsk) {
            if (empty($qsk->lampiran_lain)) {
                echo '';
            } else {
                echo "| <a href=" . site_url('assets/lampiransurat/keterangan/' . $qsk->lampiran_lain) . " title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-file-pdf-o fa-stack-2x'></i></span></a>";
            }
        }
    } elseif (substr($surat_id, 0, 3) == 'SPT') {
        $qperintahtugas = $ci->db->query("SELECT lampiran_lain FROM surat_perintahtugas WHERE id='$surat_id'")->result();
        foreach ($qperintahtugas as $key => $qspt) {
            if (empty($qspt->lampiran_lain)) {
                echo '';
            } else {
                echo "| <a href=" . site_url('assets/lampiransurat/perintahtugas/' . $qspt->lampiran_lain) . " title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-file-pdf-o fa-stack-2x'></i></span></a>";
            }
        }
    } elseif (substr($surat_id, 0, 2) == 'SP') {
        $qperintah = $ci->db->query("SELECT lampiran_lain FROM surat_perintah WHERE id='$surat_id'")->result();
        foreach ($qperintah as $key => $qsp) {
            if (empty($qsp->lampiran_lain)) {
                echo '';
            } else {
                echo "| <a href=" . site_url('assets/lampiransurat/perintah/' . $qsp->lampiran_lain) . " title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-file-pdf-o fa-stack-2x'></i></span></a>";
            }
        }
    } elseif (substr($surat_id, 0, 3) == 'IZN') {
        $qizin = $ci->db->query("SELECT lampiran_lain FROM surat_izin WHERE id='$surat_id'")->result();
        foreach ($qizin as $key => $qizn) {
            if (empty($qizn->lampiran_lain)) {
                echo '';
            } else {
                echo "| <a href=" . site_url('assets/lampiransurat/izin/' . $qizn->lampiran_lain) . " title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-file-pdf-o fa-stack-2x'></i></span></a>";
            }
        }
    } elseif (substr($surat_id, 0, 3) == 'KSA') {
        $qkuasa = $ci->db->query("SELECT lampiran_lain FROM surat_kuasa WHERE id='$surat_id'")->result();
        foreach ($qkuasa as $key => $qksa) {
            if (empty($qlsa->lampiran_lain)) {
                echo '';
            } else {
                echo "| <a href=" . site_url('assets/lampiransurat/kuasa/' . $qksa->lampiran_lain) . " title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-file-pdf-o fa-stack-2x'></i></span></a>";
            }
        }
    } elseif (substr($surat_id, 0, 3) == 'MKT') {
        $qmelaksanakantugas = $ci->db->query("SELECT lampiran_lain FROM surat_melaksanakantugas WHERE id='$surat_id'")->result();
        foreach ($qmelaksanakantugas as $key => $qmkt) {
            if (empty($qmkt->lampiran_lain)) {
                echo '';
            } else {
                echo "| <a href=" . site_url('assets/lampiransurat/melaksanakantugas/' . $qmkt->lampiran_lain) . " title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-file-pdf-o fa-stack-2x'></i></span></a>";
            }
        }
    } elseif (substr($surat_id, 0, 3) == 'PGL') {
        $qpanggilan = $ci->db->query("SELECT lampiran_lain FROM surat_panggilan WHERE id='$surat_id'")->result();
        foreach ($qpanggilan as $key => $qpgl) {
            if (empty($qpgl->lampiran_lain)) {
                echo '';
            } else {
                echo "| <a href=" . site_url('assets/lampiransurat/panggilan/' . $qpgl->lampiran_lain) . " title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-file-pdf-o fa-stack-2x'></i></span></a>";
            }
        }
    } elseif (substr($surat_id, 0, 3) == 'NTL') {
        $qnotulen = $ci->db->query("SELECT lampiran_lain FROM surat_notulen WHERE id='$surat_id'")->result();
        foreach ($qnotulen as $key => $qntl) {
            if (empty($qntl->lampiran_lain)) {
                echo '';
            } else {
                echo "| <a href=" . site_url('assets/lampiransurat/notulen/' . $qntl->lampiran_lain) . " title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-file-pdf-o fa-stack-2x'></i></span></a>";
            }
        }
    } elseif (substr($surat_id, 0, 3) == 'MMO') {
        $qmemo = $ci->db->query("SELECT lampiran_lain FROM surat_memo WHERE id='$surat_id'")->result();
        foreach ($qmemo as $key => $qmmo) {
            if (empty($qmmo->lampiran_lain)) {
                echo '';
            } else {
                echo "| <a href=" . site_url('assets/lampiransurat/memo/' . $qmmo->lampiran_lain) . " title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-file-pdf-o fa-stack-2x'></i></span></a>";
            }
        }
    } elseif (substr($surat_id, 0, 3) == 'LMP') {
        $qlmp = $ci->db->query("SELECT lampiran_lain FROM surat_lampiran WHERE id='$surat_id'")->result();
        foreach ($qlmp as $key => $lmp) {
            if (empty($lmp->lampiran_lain)) {
                echo '';
            } else {
                echo "<a href=" . site_url('export/lampiran/' . $lmp->lampiran_lain) . " title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-file-pdf-o fa-stack-2x'></i></span></a>";
            }
        }
    }
}

function lihatsuratlink($surat_id)
{
	$url = ""; 
	if (substr($surat_id, 0,2) == 'SE') { 
	    $url = site_url('export/edaran/'.$surat_id); 
	}elseif (substr($surat_id, 0,2) == 'SB') { 
	    $url = site_url('export/biasa/'.$surat_id); 
	}elseif (substr($surat_id, 0,2) == 'SU') {
	    $url = site_url('export/undangan/'.$surat_id); 
	}elseif (substr($surat_id, 0,5) == 'PNGMN') {
	    $url = site_url('export/pengumuman/'.$surat_id); 
	}elseif (substr($surat_id, 0,3) == 'LAP') {
	    $url = site_url('export/laporan/'.$surat_id);
	}elseif (substr($surat_id, 0,3) == 'REK') {
	   $url = site_url('export/rekomendasi/'.$surat_id); 
	}elseif (substr($surat_id, 0,3) == 'INT') {
	    $url = site_url('export/instruksi/'.$surat_id); 
	}elseif (substr($surat_id, 0,3) == 'PNG') {
	    $url = site_url('export/pengantar/'.$surat_id); 
	}elseif (substr($surat_id, 0,5) == 'NODIN') {
	    $url = site_url('export/notadinas/'.$surat_id);  
	}elseif (substr($surat_id, 0,2) == 'SK') {
	    $url = site_url('export/keterangan/'.$surat_id); 
	}elseif (substr($surat_id, 0,3) == 'SPT') {
	//   $url = site_url('export/perintahtugas/'.$surat_id); 

	   $ci =& get_instance();
		$query=$ci->db->query("SELECT surat_perintahtugas.lampiran_lain, surat_perintahtugas.src FROM surat_perintahtugas WHERE surat_perintahtugas.id='$surat_id'")->row_array();
		

	    if($query['src'] == '2') {
			//	$url = "https://api-tnd.kotabogor.go.id/assets/lampiransurat/suratlainnya/".$query['lampiran']; 
			$url = "https://dev-tnd.kotabogor.go.id/assets/lampiransurat/perintahtugas/".$query['lampiran_lain']; 
			//
		}else{
			$url = site_url('export/perintahtugas/'.$surat_id); 
		}

	}elseif (substr($surat_id, 0,3) == 'SPH') {
		$ci =& get_instance();
		$query=$ci->db->query("SELECT surat_produkhukum.lampiran_lain FROM surat_produkhukum WHERE surat_produkhukum.id='$surat_id'")->row_array();
	   	//    $url = site_url('assets/lampiransurat/Suratprodukhukum/'.$query['lampiran_lain']);
	   	$url = "https://api-tnd.kotabogor.go.id/assets/lampiransurat/Suratprodukhukum/".$query['lampiran_lain']; 
	}elseif (substr($surat_id, 0,2) == 'SP') {
	   $url = site_url('export/perintah/'.$surat_id); 
	}elseif (substr($surat_id, 0,3) == 'IZN') {
	    $url = site_url('export/izin/'.$surat_id); 
	}elseif (substr($surat_id, 0,3) == 'PJL') {
	    $url = site_url('export/perjalanan/'.$surat_id); 
	}elseif (substr($surat_id, 0,3) == 'KSA') {
	    $url = site_url('export/kuasa/'.$surat_id); 
	}elseif (substr($surat_id, 0,3) == 'MKT') {
	    $url = site_url('export/melaksanakan_tugas/'.$surat_id); 
	}elseif (substr($surat_id, 0,3) == 'PGL') {
	    $url = site_url('export/panggilan/'.$surat_id); 
	}elseif (substr($surat_id, 0,3) == 'NTL') {
	    $url = site_url('export/notulen/'.$surat_id); 
	}elseif (substr($surat_id, 0,3) == 'MMO') {
	    $url = site_url('export/memo/'.$surat_id); 
	}elseif (substr($surat_id, 0,3) == 'LMP') {
	    // $url = site_url('export/lampiran/'.$surat_id); 
		$ci =& get_instance();
		$query=$ci->db->query("SELECT surat_lampiran.lampiran_lain FROM surat_lampiran WHERE surat_lampiran.id='$surat_id'")->row_array();
		$url = site_url('export/lampiran/'.$query['lampiran_lain']);
	}elseif (substr($surat_id, 0,2) == 'SL') {
		$ci =& get_instance();
		$query=$ci->db->query("SELECT surat_lainnya.lampiran, surat_lainnya.src FROM surat_lainnya WHERE surat_lainnya.id='$surat_id'")->row_array();
		
	    if($query['src'] == '1') {
			$url = site_url('assets/lampiransurat/suratlainnya/'.$query['lampiran']);
		}
		else {
			$url = "https://api-tnd.kotabogor.go.id/assets/lampiransurat/suratlainnya/".$query['lampiran']; 
		}
	}elseif (substr($surat_id, 0,2) == 'SW') {
		$ci =& get_instance();
		$query=$ci->db->query("SELECT surat_wilayah.lampiran_lain FROM surat_wilayah WHERE surat_wilayah.id='$surat_id'")->row_array();
	    // $url = site_url('uploads/backup/'.$query['lampiran_lain']); 
		// $url = site_url('assets/lampiransurat/suratwilayah/'.$query['lampiran_lain']); 
		$url = "https://api-tnd.kotabogor.go.id/assets/lampiransurat/suratwilayah/".$query['lampiran_lain']; 
	}
	return $url; 
}

// END
// Update @Mpik Egov 20/06/2022 : Penambahan method lihat lampiran surat keluar

function lihatsurat($surat_id)
{

	if (substr($surat_id, 0,2) == 'SE') { 
	    echo "<a href=".site_url('export/edaran/'.$surat_id)." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-eye fa-stack-2x'></i></span></a>"; 
	}elseif (substr($surat_id, 0,2) == 'SB') { 
	    echo "<a href=".site_url('export/biasa/'.$surat_id)." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-eye fa-stack-2x'></i></span></a>"; 
	}elseif (substr($surat_id, 0,2) == 'SU') {
	    echo "<a href=".site_url('export/undangan/'.$surat_id)." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-eye fa-stack-2x'></i></span></a>"; 
	}elseif (substr($surat_id, 0,5) == 'PNGMN') {
	    echo "<a href=".site_url('export/pengumuman/'.$surat_id)." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-eye fa-stack-2x'></i></span></a>"; 
	}elseif (substr($surat_id, 0,3) == 'LAP') {
	    echo "<a href=".site_url('export/laporan/'.$surat_id)." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-eye fa-stack-2x'></i></span></a>";
	}elseif (substr($surat_id, 0,3) == 'REK') {
	    echo "<a href=".site_url('export/rekomendasi/'.$surat_id)." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-eye fa-stack-2x'></i></span></a>"; 
	}elseif (substr($surat_id, 0,3) == 'INT') {
	    echo "<a href=".site_url('export/instruksi/'.$surat_id)." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-eye fa-stack-2x'></i></span></a>"; 
	}elseif (substr($surat_id, 0,3) == 'PNG') {
	    echo "<a href=".site_url('export/pengantar/'.$surat_id)." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-eye fa-stack-2x'></i></span></a>"; 
	}elseif (substr($surat_id, 0,5) == 'NODIN') {
	    echo "<a href=".site_url('export/notadinas/'.$surat_id)." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-eye fa-stack-2x'></i></span></a>"; 
	}elseif (substr($surat_id, 0,2) == 'SK') {
	    echo "<a href=".site_url('export/keterangan/'.$surat_id)." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-eye fa-stack-2x'></i></span></a>"; 
	}elseif (substr($surat_id, 0,3) == 'SPT') {
	    echo "<a href=".site_url('export/perintahtugas/'.$surat_id)." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-eye fa-stack-2x'></i></span></a>"; 
	}elseif (substr($surat_id, 0,2) == 'SP') {
	    echo "<a href=".site_url('export/perintah/'.$surat_id)." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-eye fa-stack-2x'></i></span></a>"; 
	}elseif (substr($surat_id, 0,3) == 'IZN') {
	    echo "<a href=".site_url('export/izin/'.$surat_id)." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-eye fa-stack-2x'></i></span></a>"; 
	}elseif (substr($surat_id, 0,3) == 'PJL') {
	    echo "<a href=".site_url('export/perjalanan/'.$surat_id)." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-eye fa-stack-2x'></i></span></a>"; 
	}elseif (substr($surat_id, 0,3) == 'KSA') {
	    echo "<a href=".site_url('export/kuasa/'.$surat_id)." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-eye fa-stack-2x'></i></span></a>"; 
	}elseif (substr($surat_id, 0,3) == 'MKT') {
	    echo "<a href=".site_url('export/melaksanakan_tugas/'.$surat_id)." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-eye fa-stack-2x'></i></span></a>"; 
	}elseif (substr($surat_id, 0,3) == 'PGL') {
	    echo "<a href=".site_url('export/panggilan/'.$surat_id)." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-eye fa-stack-2x'></i></span></a>"; 
	}elseif (substr($surat_id, 0,3) == 'NTL') {
	    echo "<a href=".site_url('export/notulen/'.$surat_id)." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-eye fa-stack-2x'></i></span></a>"; 
	}elseif (substr($surat_id, 0,3) == 'MMO') {
	    echo "<a href=".site_url('export/memo/'.$surat_id)." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-eye fa-stack-2x'></i></span></a>"; 
	}elseif (substr($surat_id, 0,3) == 'LMP') {
		$ci =& get_instance();
		$query=$ci->db->query("SELECT surat_lampiran.lampiran_lain FROM surat_lampiran WHERE surat_lampiran.id='$surat_id'")->row_array();
		$url = site_url('export/lampiran/'.$query['lampiran_lain']);
	    // echo "<a href=".site_url('uploads/SIGNED/'.$surat_id)." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-eye fa-stack-2x'></i></span></a>"; 
	}elseif (substr($surat_id, 0,2) == 'SL') {
		$ci =& get_instance();
		$query=$ci->db->query("SELECT surat_lainnya.lampiran FROM surat_lainnya WHERE surat_lainnya.id='$surat_id'")->row_array();
		// Uncomment by @MpikEgov
	   	echo "<a href=".site_url('assets/lampiransurat/suratlainnya/'.$query['lampiran'])." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-eye fa-stack-2x'></i></span></a> |"; 
	}


}

// Update @Mpik Egov 17/06/2022 08:19 : Penambahan method lihat surat yang sudah di TTE
function lihatsurattte($surat_id)
{

	if (substr($surat_id, 0,2) == 'SE') { 
	    echo "<a href=".site_url('uploads/SIGNED/'.$surat_id.'.pdf')." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-eye fa-stack-2x'></i></span></a>"; 
	}elseif (substr($surat_id, 0,2) == 'SB') { 
	    echo "<a href=".site_url('uploads/SIGNED/'.$surat_id.'.pdf')." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-eye fa-stack-2x'></i></span></a>"; 
// ======== START [UPDATE] Fikri Egov 4 Maret 2022 ======================================= [UPDATE] Fikri Egov ======================================= [UPDATE] Fikri Egov ======================================= [UPDATE] Fikri Egov =======================================
	}elseif (substr($surat_id, 0,3) == 'BRA') { 
	    echo "<a href=".site_url('uploads/SIGNED/'.$surat_id.'.pdf')." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-eye fa-stack-2x'></i></span></a>"; 
// ======== START [UPDATE] Fikri Egov 4 Maret 2022 ======================================= [UPDATE] Fikri Egov ======================================= [UPDATE] Fikri Egov ======================================= [UPDATE] Fikri Egov =======================================
	}elseif (substr($surat_id, 0,2) == 'SU') {
	    echo "<a href=".site_url('uploads/SIGNED/'.$surat_id.'.pdf')." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-eye fa-stack-2x'></i></span></a>"; 
	}elseif (substr($surat_id, 0,5) == 'PNGMN') {
	    echo "<a href=".site_url('uploads/SIGNED/'.$surat_id.'.pdf')." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-eye fa-stack-2x'></i></span></a>"; 
	}elseif (substr($surat_id, 0,3) == 'LAP') {
	    echo "<a href=".site_url('uploads/SIGNED/'.$surat_id.'.pdf')." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-eye fa-stack-2x'></i></span></a>";
	}elseif (substr($surat_id, 0,3) == 'REK') {
	    echo "<a href=".site_url('uploads/SIGNED/'.$surat_id.'.pdf')." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-eye fa-stack-2x'></i></span></a>"; 
	}elseif (substr($surat_id, 0,3) == 'INT') {
	    echo "<a href=".site_url('uploads/SIGNED/'.$surat_id.'.pdf')." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-eye fa-stack-2x'></i></span></a>"; 
	}elseif (substr($surat_id, 0,3) == 'PNG') {
	    echo "<a href=".site_url('uploads/SIGNED/'.$surat_id.'.pdf')." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-eye fa-stack-2x'></i></span></a>"; 
	}elseif (substr($surat_id, 0,5) == 'NODIN') {
	    echo "<a href=".site_url('uploads/SIGNED/'.$surat_id.'.pdf')." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-eye fa-stack-2x'></i></span></a>"; 
	}elseif (substr($surat_id, 0,3) == 'SKP') {
	    echo "<a href=".site_url('uploads/SIGNED/'.$surat_id.'.pdf')." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-eye fa-stack-2x'></i></span></a>"; 
	}elseif (substr($surat_id, 0,2) == 'SK') {
	    echo "<a href=".site_url('uploads/SIGNED/'.$surat_id.'.pdf')." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-eye fa-stack-2x'></i></span></a>";
	}elseif (substr($surat_id, 0,3) == 'SPT') {
	    echo "<a href=".site_url('uploads/SIGNED/'.$surat_id.'.pdf')." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-eye fa-stack-2x'></i></span></a>"; 
	}elseif (substr($surat_id, 0,2) == 'SP') {
	    echo "<a href=".site_url('uploads/SIGNED/'.$surat_id.'.pdf')." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-eye fa-stack-2x'></i></span></a>"; 
	}elseif (substr($surat_id, 0,3) == 'IZN') {
	    echo "<a href=".site_url('uploads/SIGNED/'.$surat_id.'.pdf')." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-eye fa-stack-2x'></i></span></a>"; 
	}elseif (substr($surat_id, 0,3) == 'PJL') {
	    echo "<a href=".site_url('uploads/SIGNED/'.$surat_id.'.pdf')." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-eye fa-stack-2x'></i></span></a>"; 
	}elseif (substr($surat_id, 0,3) == 'KSA') {
	    echo "<a href=".site_url('uploads/SIGNED/'.$surat_id.'.pdf')." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-eye fa-stack-2x'></i></span></a>"; 
	}elseif (substr($surat_id, 0,3) == 'MKT') {
	    echo "<a href=".site_url('uploads/SIGNED/'.$surat_id.'.pdf')." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-eye fa-stack-2x'></i></span></a>"; 
	}elseif (substr($surat_id, 0,3) == 'PGL') {
	    echo "<a href=".site_url('uploads/SIGNED/'.$surat_id.'.pdf')." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-eye fa-stack-2x'></i></span></a>"; 
	}elseif (substr($surat_id, 0,3) == 'NTL') {
	    echo "<a href=".site_url('uploads/SIGNED/'.$surat_id.'.pdf')." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-eye fa-stack-2x'></i></span></a>"; 
	}elseif (substr($surat_id, 0,3) == 'MMO') {
	    echo "<a href=".site_url('uploads/SIGNED/'.$surat_id.'.pdf')." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-eye fa-stack-2x'></i></span></a>"; 
	}elseif (substr($surat_id, 0,3) == 'LMP') {
	    echo "<a href=".site_url('uploads/SIGNED/'.$surat_id.'.pdf')." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-eye fa-stack-2x'></i></span></a>"; 
	}elseif (substr($surat_id, 0,2) == 'SW') {
	    echo "<a href=".site_url('uploads/SIGNED/'.$surat_id.'.pdf')." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-eye fa-stack-2x'></i></span></a>"; 
	}elseif (substr($surat_id, 0,2) == 'SL') {
	    $ci =& get_instance();
		$query=$ci->db->query("SELECT surat_lainnya.lampiran FROM surat_lainnya WHERE surat_lainnya.id='$surat_id'")->row_array();
	    echo "<a href=".site_url('assets/lampiransurat/suratlainnya/'.$query['lampiran'])." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-eye fa-stack-2x'></i></span></a> |"; 
	}


}
// END
// Update @Mpik Egov 17/06/2022 08:19 : Penambahan method lihat surat yang sudah di TTE

// function kirim_email_eksternal($surat_id,$filesurat)
// {
// 	$ci =& get_instance();

// 	// Konfigurasi email
// 	$config = [
// 	    'useragent' => 'Pemerintah Kota Bogor',
// 	    'protocol'  => 'smtp',
// 	    'mailpath'  => '/usr/sbin/sendmail',
// 	    'smtp_host' => 'mail.kotabogor.go.id',
// 	    'smtp_user' => 'tnd@kotabogor.go.id', // Ganti dengan email Anda
// 	    'smtp_pass' => 'k0m1nf0#', // Password email Anda
// 	    // 'smtp_user' => 'bag.humas@kotabogor.go.id', // Ganti dengan email Anda
// 	    // 'smtp_pass' => 'biar4man', // Password email Anda
// 	    'smtp_port' => 25,
// 	    'smtp_keepalive' => TRUE,
// 	    'smtp_crypto' => 'SSL',
// 	    'wordwrap'  => TRUE,
// 	    'wrapchars' => 80,
// 	    'mailtype'  => 'html',
// 	    'charset'   => 'utf-8',
// 	    'validate'  => TRUE,
// 	    'crlf'      => "\r\n",
// 	    'newline'   => "\r\n",
// 	];

// 	// Load library email dan konfigurasinya
// 	$ci->load->library('email', $config);

// 	// Email dan nama pengirim
// 	$ci->email->from('no-reply@tnd.kotabogor.go.id', 'Admin Tata Naskah Dinas Pemerintah Kota Bogor');
// 	// $ci->email->from('no-reply@humas.kotabogor.go.id', 'Admin Tata Naskah Dinas Pemerintah Kota Bogor');

// 	// Email penerima
// 	$qemail = $ci->db->query("
// 		SELECT * FROM disposisi_suratkeluar 
// 		JOIN eksternal_keluar ON users_id = id 
// 		WHERE surat_id = '$surat_id'
// 	")->result();
// 	$email = '';
// 	foreach ($qemail as $key => $e) {
// 		$email .= $e->email.', ';
// 	}
// 	$ci->email->to($email);

// 	// Subject email
// 	$ci->email->subject('Naskah Dinas Pemerintah Kota Bogor');
					  
// 	// Isi email
// 	$message = "Isi Surat";
// 	$ci->email->message($message);

// 	//file lampiran
// 	$ci->email->attach('./assets/surat/'.$filesurat);

// 	// Tampilkan pesan sukses atau error
// 	if ($ci->email->send()) {
		
// 		$kirim = array('status' => 'Selesai');
// 		$where = array('surat_id' => $surat_id);
// 		$ci->db->update('disposisi_suratkeluar', $kirim, $where);

// 		$ci->session->set_flashdata('success', 'Surat Berhasil Ditandatangan');
// 		redirect(site_url('suratkeluar/draft/signature'));

// 	}else{
// 		echo "Email Tidak Terkirim <br>";
// 		echo $ci->email->print_debugger();
// 	}
	
// }

// kirim email dengan email dev
function kirim_email_eksternal($surat_id,$filesurat)
{
	$ci =& get_instance();

	// Konfigurasi email
	$config = [
		'mailtype'  => 'html',
		'charset'   => 'utf-8',
		'protocol'  => 'smtp',
		'smtp_host' => 'smtp.gmail.com',
		'smtp_user' => 'devpemkotbogor@gmail.com',  // Email gmail
		'smtp_pass'   => 'Dev,andalan.pemkot-bogor;#',  // Password gmail
		'smtp_crypto' => 'ssl',
		'smtp_port'   => 465,
		'crlf'    => "\r\n",
		'newline' => "\r\n"
	];

	// Load library email dan konfigurasinya
	$ci->load->library('email', $config);

	// Email dan nama pengirim
	$ci->email->from('no-reply@tnd.kotabogor.go.id', 'Admin Tata Naskah Dinas Pemerintah Kota Bogor');
	// $ci->email->from('no-reply@humas.kotabogor.go.id', 'Admin Tata Naskah Dinas Pemerintah Kota Bogor');

	// Email penerima
	$qemail = $ci->db->query("
		SELECT * FROM disposisi_suratkeluar 
		JOIN eksternal_keluar ON users_id = id 
		WHERE surat_id = '$surat_id'
	")->result();
	$email = '';
	foreach ($qemail as $key => $e) {
		$email .= $e->email.', ';
	}
	$ci->email->to($email);

	// Subject email
	$ci->email->subject('Naskah Dinas Pemerintah Kota Bogor');
					  
	// Isi email
	$message = "Isi Surat";
	$ci->email->message($message);

	//file lampiran
	$ci->email->attach('./assets/surat/'.$filesurat);

	// Tampilkan pesan sukses atau error
	if ($ci->email->send()) {
		
		$kirim = array('status' => 'Selesai');
		$where = array('surat_id' => $surat_id);
		$ci->db->update('disposisi_suratkeluar', $kirim, $where);

		$ci->session->set_flashdata('success', 'Surat Berhasil Ditandatangan');
		redirect(site_url('suratkeluar/draft/signature'));

	}else{
		echo "Email Tidak Terkirim <br>";
		//echo $ci->email->print_debugger();
	}
}

// function sendTembusanInt()
// 	{
// 		$ci =& get_instance();
// 		$tembusan = "
// 			SELECT a.users_id, d.nama_jabatan FROM users a 
// 			JOIN aparatur b ON a.aparatur_id=b.aparatur_id 
// 			JOIN level c ON a.level_id=c.level_id 
// 			JOIN jabatan d ON b.jabatan_id=d.jabatan_id 
// 			WHERE c.level_id IN (9,5,10,11) 
// 			ORDER BY d.jabatan_id ASC
// 			";
// 		return $ci->db->query($tembusan)->result();
// 	}
	
// 	function get_tembusan($surat,$tahun,$opd_id,$jabatan_id,$tembusan,$id)
// 	{
// 		$ci =& get_instance();
		
// 		$query = $ci->db->query("
// 				SELECT * FROM draft
// 				LEFT JOIN $surat
// 				ON draft.surat_id = $surat.id
// 				LEFT JOIN opd
// 				ON opd.opd_id = $surat.opd_id
// 				LEFT JOIN aparatur
// 				ON aparatur.jabatan_id = draft.penandatangan_id
// 				WHERE $surat.opd_id = '$opd_id'
// 				AND LEFT($surat.tanggal, 4) = '$tahun'
// 				AND draft.dibuat_id = '$jabatan_id'
// 				AND $surat.id IN ($id)
// 			");
// 		return $query;
// 	}
// 	function sendTembusanEks()
// 	{
// 		$ci =& get_instance();
// 		return $ci->db->get('tembusan_keluar')->result();
// 	}

// @MpikEgov 20 Juni 2023
function countdisposisisuratmasukTU()
{
	$ci =& get_instance();
	
	$jabatan_id = $ci->session->userdata('jabatan_id');
	$tahun = $ci->session->userdata('tahun');
	$count = $ci->db->query("SELECT disposisi_suratmasuk.suratmasuk_id FROM disposisi_suratmasuk
	JOIN surat_masuk ON surat_masuk.suratmasuk_id = disposisi_suratmasuk.suratmasuk_id
	WHERE disposisi_suratmasuk.aparatur_id = '$jabatan_id' AND disposisi_suratmasuk.status='Belum Selesai'
	AND LEFT(surat_masuk.diterima, 4) = '$tahun'")->num_rows();

	return $count;
}

// @MpikEgov 20 Juni 2023
function countdisposisisuratmasukTND()
{
	$ci =& get_instance();
	
	$jabatan_id = $ci->session->userdata('jabatan_id');
	$tahun = $ci->session->userdata('tahun');
	$opd_id = $ci->session->userdata('opd_id');
	$count = $ci->db->query("SELECT *
	FROM disposisi_suratkeluar
	LEFT JOIN draft ON draft.surat_id = disposisi_suratkeluar.surat_id
	LEFT JOIN aparatur ON aparatur.jabatan_id = disposisi_suratkeluar.users_id
	WHERE disposisi_suratkeluar.users_id = '$jabatan_id'
	AND aparatur.opd_id = '$opd_id'
	AND LEFT(draft.tanggal, 4) = '$tahun'
	AND disposisi_suratkeluar.status = 'Selesai'")->num_rows();

	return $count;
}

// @MpikEgov 20 Juni 2023
function countsuratmasukTU()
{
	$ci =& get_instance();
	
	$jabatan_id = $ci->session->userdata('jabatan_id');
	$tahun = $ci->session->userdata('tahun');
	
	$count = $ci->db->query("SELECT * FROM surat_masuk
	WHERE dibuat_id = '$jabatan_id'
	AND LEFT(tanggal, 4) = '$tahun'")->num_rows();

	return $count;
}

// @MpikEgov 20 Juni 2023
function countsurattembusan()
{
	$ci =& get_instance();
	
	$jabatan_id = $ci->session->userdata('jabatan_id');
	$tahun = $ci->session->userdata('tahun');
	$opd_id = $ci->session->userdata('opd_id');

	$count = $ci->db->query("SELECT * FROM tembusan_surat
	LEFT JOIN draft ON draft.surat_id = tembusan_surat.surat_id
	LEFT JOIN aparatur ON aparatur.jabatan_id = tembusan_surat.users_id
	WHERE tembusan_surat.users_id = '$jabatan_id'
	AND aparatur.opd_id = '$opd_id'
	AND LEFT(draft.tanggal, 4) = '$tahun'
	AND tembusan_surat.status = 'Selesai'")->num_rows();

	return $count;
}

// @MpikEgov 20 Juni 2023
function countsuratmasukselesaiTU()
{
	$ci =& get_instance();
	
	$jabatan_id = $ci->session->userdata('jabatan_id');
	$tahun = $ci->session->userdata('tahun');

	$count = $ci->db->query("SELECT * FROM surat_masuk a
	LEFT JOIN disposisi_suratmasuk b ON b.suratmasuk_id=a.suratmasuk_id
	LEFT JOIN aparatur c ON c.jabatan_id=b.users_id
	WHERE b.`status`='Selesai' AND LEFT(a.diterima,4)='$tahun' AND a.dibuat_id='$jabatan_id'
	GROUP BY b.suratmasuk_id
	ORDER BY a.diterima desc, LENGTH(b.dsuratmasuk_id) DESC")->num_rows();

	return $count;
}

// @MpikEgov 20 Juni 2023
function countsuratmasukselesaiAparatur()
{
	$ci =& get_instance();
	
	$jabatan_id = $ci->session->userdata('jabatan_id');
	$tahun = $ci->session->userdata('tahun');

	$count = $ci->db->query("SELECT * FROM disposisi_suratmasuk a LEFT JOIN surat_masuk b ON b.suratmasuk_id=a.suratmasuk_id WHERE a.aparatur_id='$jabatan_id' AND left(b.tanggal, 4)='$tahun' AND a.status='Selesai'")->num_rows();

	return $count;
}