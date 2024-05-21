<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ApiTnd extends CI_Controller
{
    function __construct(){
        parent::__construct();
        $this->load->library('pdf');
    }

    public function postdatatandangan_asli()
    {
        $jabatanID = $this->input->post('jabatan_id'); // Get Jabatan id berapa yang membuat suratnya
        $opdID = $this->input->post('opd_id');

        if ($this->input->post('key') == 'postdatatandangan.222#.') {
            $getID = $this->db->query("SELECT id FROM surat_wilayah ORDER BY LENGTH(id) ASC, id ASC")->result();
            foreach ($getID as $key => $h) {
                $id = $h->id;
            }

            if (empty($id)) {
                $surat_id = 'SW-1';
            } else {
                $urut = substr($id, 3) + 1;
                $surat_id = 'SW-' . $urut;
            }

            // Entry Data Ke Tabel Surat Wilayah

            $file = $_FILES['lampiran_lain']['name'];

            $ambext = explode(".", $file);
            $ekstensi = end($ambext);
            $date = date('his');
            $nama_baru = "SuratWilayah-Lampiran-No-" . $surat_id . "-" . $date;
            $nama_file = $nama_baru . "." . $ekstensi;
            $config['upload_path'] = './assets/lampiransurat/suratwilayah/';

            // $nama_baru = $surat_id;
            // $nama_file = $nama_baru.".".$ekstensi;  
            // $config['upload_path'] = './uploads/backup/';

            //$config['allowed_types'] = 'docx';
            $config['allowed_types'] = 'pdf';
            $config['file_name'] = $nama_file;
            $this->upload->initialize($config);

            // 		var_dump($config);
            // 		die;
            if (!$this->upload->do_upload('lampiran_lain')) {
                $respons = array(
                    'status' => 'Gagal Upload',
                    'data' => 'cannot access, you dont have permission'
                );
                echo json_encode($respons);
            } else {
                $datasurat = array(
                    'id' => $surat_id,
                    'hal' => $this->input->post('hal'),
                    'tanggal' => $this->input->post('tanggal'),
                    'nomor' => $this->input->post('nomor'),
                    'jenis_surat' => $this->input->post('jenis_surat'),
                    'lampiran_lain' => $nama_file,
                );
            }

            $insertsurat = $this->db->insert('surat_wilayah', $datasurat);
            if ($insertsurat) {
                $getjabatanID = $this->input->post('penandatangan_id');
                $cekjabatan = $this->db->query("SELECT aparatur.nama, jabatan.nama_jabatan FROM jabatan LEFT JOIN aparatur ON aparatur.jabatan_id=jabatan.jabatan_id WHERE jabatan.jabatan_id='$getjabatanID'")->row_array();
                $gettteID = $this->db->get('penandatangan')->result();
                foreach ($gettteID as $key => $tte) {
                    $idtte = $tte->penandatangan_id;
                }
                if (empty($idtte)) {
                    $penandatangan_id = '1';
                } else {
                    $urut = $idtte + 1;
                    $penandatangan_id = $urut;
                }

                // Entry Data Ke Tabel Penandatangan
                $datatte = array(
                    'penandatangan_id' => $penandatangan_id,
                    'surat_id' => $surat_id,
                    'jabatan_id' => $getjabatanID,
                    'nama' => $cekjabatan['nama'],
                    'jabatan' => $cekjabatan['nama_jabatan'],
                    'status' => 'Belum Ditandatangani',
                );
                $this->db->insert('penandatangan', $datatte);


                // Update @Mpik Egov 27 Juli 2022
                $verifikasiid = $this->db->query("SELECT * FROM aparatur
            LEFT JOIN users ON users.aparatur_id=aparatur.aparatur_id
            LEFT JOIN opd ON opd.opd_id=aparatur.opd_id
            WHERE users.level_id=4 AND opd.opd_id='$opdID'")->row_array();
                // Entry Data Ke Tabel Draft
                $datadraft = array(
                    'surat_id' => $surat_id,
                    'tanggal' => $this->input->post('tanggal'),
                    'dibuat_id' => $jabatanID,
                    'penandatangan_id' => $penandatangan_id,
                    // 'verifikasi_id' =>'-1',
                    'verifikasi_id' => $verifikasiid['jabatan_id'], // Update @Mpik Egov 27 Juli 2022
                    'nama_surat' => $this->input->post('jenis_surat'),
                );
                $this->db->insert('draft', $datadraft);
            }
            $respons = array(
                'status' => 'Data Berhasil Disimpan',
                'surat_id' => $surat_id,
                'url' => 'https://tnd.kotabogor.go.id/suratkeluar/draft/signature',
            );
            echo json_encode($respons);
        } else {
            $respons = array(
                'status' => 'Akses Denied',
            );
            echo json_encode($respons);
        }
    }

    public function getjabatan()
    {
        if ($this->input->post('key') == 'getopd.222#.') {
            $queryjabatan = $this->db->query("SELECT jabatan_id,opd_id,nama_jabatan,jabatan,atasan_id FROM jabatan")->result_array();
            $arr = array();
            foreach ($queryjabatan as $key => $qj) {
                $datas[] = array(
                    'jabatan_id' => $qj['jabatan_id'],
                    'opd_id' => $qj['opd_id'],
                    'nama_jabatan' => $qj['nama_jabatan'],
                    'jabatan' => $qj['jabatan'],
                    'atasan_id' => $qj['atasan_id'],
                );
            }

            $datajabatan = array(
                'status' => 'success',
                'data' => $datas,
            );

            echo json_encode($datajabatan);
        } else {
            $respons = array(
                'status' => 'Akses Denied',
            );

            echo json_encode($respons);
        }
    }
    public function getopd()
    {
        if ($this->input->post('key') == 'getopd.222#.') {
            $queryopd = $this->db->query("SELECT opd_id,nama_pd,kode_pd,nomenklatur_pd,alamat,telp,faksimile,email,alamat_website,statusopd FROM opd")->result_array();
            $arr = array();
            foreach ($queryopd as $key => $qo) {
                $datas[] = array(
                    'opd_id' => $qo['opd_id'],
                    'nama_pd' => $qo['nama_pd'],
                    'kode_pd' => $qo['kode_pd'],
                    'nomenklatur__pd' => $qo['nomenklatur__pd'],
                    'pd_induk' => $qo['pd_induk'],
                    'alamat' => $qo['alamat'],
                    'telp' => $qo['telp'],
                    'faksimile' => $qo['faksimile'],
                    'email' => $qo['email'],
                    'alamat_website' => $qo['alamat_website'],
                    'statusopd' => $qo['statusopd'],
                );
            }

            $dataopd = array(
                'status' => 'success',
                'data' => $datas,
            );

            echo json_encode($dataopd);
        } else {
            $respons = array(
                'status' => 'Akses Denied',
            );

            echo json_encode($respons);
        }
    }


    public function getopd1()
    {
        // if ($this->input->post('key') == 'getopd.222#.') {
        $queryopd = $this->db->query("SELECT opd_id,nama_pd,kode_pd,nomenklatur_pd,alamat,telp,faksimile,email,alamat_website,statusopd FROM opd")->result_array();
        $arr = array();
        foreach ($queryopd as $key => $qo) {
            $datas[] = array(
                'opd_id' => $qo['opd_id'],
                'nama_pd' => $qo['nama_pd'],
                'kode_pd' => $qo['kode_pd'],
                'nomenklatur__pd' => $qo['nomenklatur__pd'],
                'pd_induk' => $qo['pd_induk'],
                'alamat' => $qo['alamat'],
                'telp' => $qo['telp'],
                'faksimile' => $qo['faksimile'],
                'email' => $qo['email'],
                'alamat_website' => $qo['alamat_website'],
                'statusopd' => $qo['statusopd'],
            );
        }

        $dataopd = array(
            'status' => 'success',
            'data' => $datas,
        );

        echo json_encode($dataopd);
    }

    function surat_masuk()
    {
        $jabatan_id = $this->input->post('jabatan_id');
        $opd_id = $this->input->post('opd_id');
        $tahun = $this->input->post('tahun');




        $agenda = $this->db->query("
                SELECT *,draft.tanggal as tglsurat, surat_undangan.hal as halundangan,surat_undangan.nomor as nomorundangan,
            surat_biasa.hal as halbiasa,surat_biasa.nomor as nomorbiasa,surat_perintahtugas.nomor as nomorperintahtugas, surat_keterangan.nomor as nomorketerangan, surat_perjalanan.nomor as nomorperjalanan,
            surat_edaran.nomor as nomoredaran, surat_edaran.tentang as haledaran,surat_pengantar.nomor as nomorpengantar, a.kode_pd as pdbiasa,b.kode_pd as pdundangan,c.kode_pd as pdperintahtugas, d.kode_pd as pdketerangan, e.kode_pd as pdperjalanan,
            f.kode_pd as pdedaran,g.kode_pd as pdizin,h.kode_pd as pdpanggilan,i.kode_pd as pdnotadinas,j.kode_pd as pdpengumuman,k.kode_pd as pdlaporan,l.kode_pd as pdrekomendasi,m.kode_pd as pdnotulen,surat_lainnya.nomor as nomorlainnya,surat_lainnya.perihal as hallainnya,p.kode_pd as pdlainnya,
            n.kode_pd as pdlampiran,o.kode_pd as pdpengantar,
            surat_notadinas.nomor as nomornotadinas, surat_notadinas.hal as halnotadinas,
            surat_lampiran.nomor as nomorlampiran,surat_lampiran.perihal as hallampiran
            FROM disposisi_suratkeluar
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
            WHERE disposisi_suratkeluar.users_id = '228'
            AND aparatur.opd_id = '29'
            AND LEFT(draft.tanggal, 4) = '2022'
            AND disposisi_suratkeluar.status = 'Selesai'
            ORDER BY draft.tanggal DESC, disposisi_suratkeluar.dsuratkeluar_id DESC")->result_array();
        $arr = array();


        foreach ($agenda as $key => $row) {
            $data[] = ($row);
            //preg_replace('/[^A-Za-z0-9\. ]/', '', $row);

        }
        $datasuratmasuk = array(
            'status' => 'success',
            'data' => $data,
        );

        echo json_encode($datasuratmasuk);
    }

    public function kepada_internal()
    {
        // if ($this->input->post('key') == 'getopd.222#.') {
        $opd = $this->input->get('opd_id');
        $queryopd = $this->db->query("SELECT b.nama_jabatan, b.jabatan_id FROM aparatur a LEFT JOIN jabatan b ON b.jabatan_id=a.jabatan_id LEFT JOIN users c ON c.aparatur_id=a.aparatur_id LEFT JOIN level d ON d.level_id=c.level_id WHERE d.level_id IN (5,6,7,8,9,10,11,12,13,14,15,16,17,19,20,21) AND a.opd_id=$opd ORDER BY b.jabatan_id ASC")->result_array();
        $arr = array();
        foreach ($queryopd as $key => $qo) {
            $datas[] = array(
                'jabatan_id' => $qo['jabatan_id'],
                'nama_jabatan' => $qo['nama_jabatan'],
            );
        }

        $dataopd = array(
            'status' => 'success',
            'data' => $datas,
        );

        echo json_encode($dataopd);
    }

    //iki

    public function nodin_tte()
    {
        $jabatanID = $this->input->post('jabatanID'); // Get Jabatan id berapa yang membuat suratnya
        $opdID = $this->input->post('opdID');

        if ($this->input->post('key') == 'n0t4d1n4s') {
            $getID = $this->db->query("SELECT id FROM surat_notadinas ORDER BY LENGTH(id) ASC, id ASC")->result();
            foreach ($getID as $key => $h) {
                $id = $h->id;
            }

            if (empty($id)) {
                $surat_id = 'NODIN-1';
            } else {
                $urut = substr($id, 6) + 1;
                $surat_id = 'NODIN-' . $urut;
            }



            // Entry Data Ke Tabel Surat Wilayah
            $datasurat1 = array(
                'id' => $surat_id,
                'opd_id' => $opdID,
                'kop_id' => '2',
                'kodesurat_id' => '0',
                'tanggal' => $this->input->post('tanggal'),
                'nomor' => '',
                'kepada_id' => '0',
                'dari_id' => '0',
                'sifat' => $this->input->post('sifat'),
                'lampiran' => $this->input->post('lampiran'),
                'hal' => $this->input->post('hal'),
                'tembusan' => $this->input->post('tembusan'),
                'lampiran_lain' => '',
                'isi' => $this->input->post('isi'),

            );
            //var_dump($datasurat1);


            $insertsurat = $this->db->insert('surat_notadinas', $datasurat1);
            if ($insertsurat) {

                $kepada = $this->input->post('kepada');
                // Entry Data Ke Tabel disposisi_suratkeluar
                $datatte = array(
                    // 'penandatangan_id' => '',
                    'surat_id' => $surat_id,
                    'users_id' => $kepada,
                    'status' => 'Belum Selesai',
                );
                $this->db->insert('disposisi_suratkeluar', $datatte);


                // Entry Data Ke Tabel Draft
                $datadraft = array(
                    'surat_id' => $surat_id,
                    'tanggal' => $this->input->post('tanggal'),
                    'dibuat_id' => $jabatanID,
                    'penandatangan_id' => '0',
                    'verifikasi_id' => '0',
                    'nama_surat' => $this->input->post('jenis_surat'),
                );
                $this->db->insert('draft', $datadraft);
            }
            $respons = array(
                'status' => 'Data Berhasil Disimpan',
                'surat_id' => $surat_id,
                'url' => 'https://tnd.kotabogor.go.id/suratkeluar/draft/signature',
            );
            echo json_encode($respons);
        } else {
            $respons = array(
                'status' => 'Akses Denied',
            );
            echo json_encode($respons);
        }
    }
    public function getStatusNotaDinas()
    {
        // if ($this->input->post('key') == 'getopd.222#.') {
        $surat_id = $this->input->get('surat_id');
        $queryopd = $this->db->query("SELECT * FROM surat_notadinas inner join penandatangan on surat_notadinas.id=penandatangan.surat_id WHERE surat_notadinas.id='$surat_id'")->result_array();
        //var_dump($queryopd);      
        $arr = array();
        foreach ($queryopd as $key => $qo) {
            $datas[] = array(
                'id' => $qo['id'],
                'nomor' => $qo['nomor'],
                'status' => $qo['status'],
                'link_pdf' => 'https://tnd.kotabogor.go.id/uploads/SIGNED/' . $qo['id'] . '.pdf',
                'link_lampiran' => 'https://tnd.kotabogor.go.id/assets/lampiransurat/notadinas/' . $qo['lampiran_lain'],
            );
        }

        $dataopd = array(
            'status' => 'success',
            'data' => $datas,
        );

        echo json_encode($dataopd);
    }

    public function perintahtugas_tte()
    {
        $jabatanID = $this->input->post('jabatanID'); // Get Jabatan id berapa yang membuat suratnya
        $opdID = $this->input->post('opdID');

        if ($this->input->post('key') == 'tug45') {
            $getID = $this->db->query("SELECT id FROM surat_perintahtugas ORDER BY LENGTH(id) ASC, id ASC")->result();
            foreach ($getID as $key => $h) {
                $id = $h->id;
            }

            if (empty($id)) {
                $surat_id = 'SPT-1';
            } else {
                $urut = substr($id, 4) + 1;
                $surat_id = 'SPT-' . $urut;
            }



            // Entry Data Ke Tabel Surat Wilayah
            $datasurat1 = array(
                'id' => $surat_id,
                'opd_id' => $opdID,
                'kop_id' => '2',
                'kodesurat_id' => $this->input->post('kode_surat'),
                'nomor' => '',
                'pegawai_id' => $this->input->post('pegawai_id'),
                'kepada' => $this->input->post('kepada'),
                'dasar' => $this->input->post('dasar'),
                'untuk' => $this->input->post('untuk'),
                'tanggal' => $this->input->post('tanggal'),
                'tembusan' => $this->input->post('tembusan'),
                'lampiran_lain' => '',

            );
            //var_dump($datasurat1);


            $insertsurat = $this->db->insert('surat_perintahtugas', $datasurat1);
            if ($insertsurat) {

                $kepada = $this->input->post('kepada');
                // Entry Data Ke Tabel disposisi_suratkeluar
                $datatte = array(
                    // 'penandatangan_id' => '',
                    'surat_id' => $surat_id,
                    'users_id' => $kepada,
                    'status' => 'Belum Selesai',
                );
                $this->db->insert('disposisi_suratkeluar', $datatte);


                // Entry Data Ke Tabel Draft
                $datadraft = array(
                    'surat_id' => $surat_id,
                    'kopId' => '2',
                    'tanggal' => $this->input->post('tanggal'),
                    'dibuat_id' => $jabatanID,
                    'penandatangan_id' => '0',
                    'verifikasi_id' => '0',
                    'nama_surat' => $this->input->post('jenis_surat'),
                );
                $this->db->insert('draft', $datadraft);
            }
            $respons = array(
                'status' => 'Data Berhasil Disimpan',
                'surat_id' => $surat_id,
                'url' => 'https://tnd.kotabogor.go.id/suratkeluar/draft/signature',
            );
            echo json_encode($respons);
        } else {
            $respons = array(
                'status' => 'Akses Denied',
            );
            echo json_encode($respons);
        }
    }

    public function kodesuratTND()
    {
        // if ($this->input->post('key') == 'getopd.222#.') {
        //$surat_id = $this->input->get('surat_id');
        $queryopd = $this->db->query("SELECT * FROM kode_surat")->result_array();
        //var_dump($queryopd);      
        $arr = array();
        foreach ($queryopd as $key => $qo) {
            $datas[] = array(
                'kodesurat_id' => $qo['kodesurat_id'],
                'kode' => $qo['kode'],
                'tentang' => $qo['tentang'],
            );
        }

        $dataopd = array(
            'status' => 'success',
            'data' => $datas,
        );

        echo json_encode($dataopd);
    }

    public function aparaturTND()
    {
        // if ($this->input->post('key') == 'getopd.222#.') {
        $opd_id = $this->input->get('opd_id');
        $queryopd = $this->db->query("SELECT * FROM aparatur INNER join jabatan on aparatur.jabatan_id=jabatan.jabatan_id WHERE aparatur.opd_id='$opd_id' and nip != '-'")->result_array();
        //var_dump($queryopd);      
        $arr = array();
        foreach ($queryopd as $key => $qo) {
            $datas[] = array(
                'aparatur_id' => $qo['aparatur_id'],
                'jabatan_id' => $qo['jabatan_id'],
                'opd_id' => $qo['opd_id'],
                'nama' => $qo['nama'],
                'nip' => $qo['nip'],
                'nama_jabatan' => $qo['nama_jabatan'],
            );
        }

        $dataopd = array(
            'status' => 'success',
            'data' => $datas,
        );

        echo json_encode($dataopd);
    }

    public function getStatusPerintahTugas()
    {
        // if ($this->input->post('key') == 'getopd.222#.') {
        $surat_id = $this->input->get('surat_id');
        $queryopd = $this->db->query("SELECT * FROM surat_perintahtugas inner join penandatangan on surat_perintahtugas.id=penandatangan.surat_id WHERE surat_perintahtugas.id='$surat_id'")->result_array();
        //var_dump($queryopd);      
        $arr = array();
        foreach ($queryopd as $key => $qo) {
            $datas[] = array(
                'id' => $qo['id'],
                'nomor' => $qo['nomor'],
                'status' => $qo['status'],
                'link_pdf' => 'https://tnd.kotabogor.go.id/uploads/SIGNED/' . $qo['id'] . '.pdf',
                'link_lampiran' => 'https://tnd.kotabogor.go.id/assets/lampiransurat/perintahtugas/' . $qo['lampiran_lain'],
            );
        }

        $dataopd = array(
            'status' => 'success',
            'data' => $datas,
        );

        echo json_encode($dataopd);
    }


    public function ttejdih()
    {
        $jabatanID = $this->input->post('jabatan_id'); // Get Jabatan id berapa yang membuat suratnya
        $opdID = $this->input->post('opd_id');


        $getID = $this->db->query("SELECT id FROM surat_produkhukum ORDER BY LENGTH(id) ASC, id ASC")->result();
        foreach ($getID as $key => $h) {
            $id = $h->id;
        }

        if (empty($id)) {
            $surat_id = 'SPH-1';
        } else {
            $urut = substr($id, 4) + 1;
            $surat_id = 'SPH-' . $urut;
        }

        // Entry Data Ke Tabel Surat

        $file = $_FILES['lampiran_lain']['name'];

        $ambext = explode(".", $file);
        $ekstensi = end($ambext);
        $date = date('his');
        $nama_baru = "Suratprodukhukum-Lampiran-No-" . $surat_id . "-" . $date;
        $nama_file = $nama_baru . "." . $ekstensi;
        $config['upload_path'] = './assets/lampiransurat/Suratprodukhukum/';

        // $nama_baru = $surat_id;
        // $nama_file = $nama_baru.".".$ekstensi;  
        // $config['upload_path'] = './uploads/backup/';

        //$config['allowed_types'] = 'docx';
        $config['allowed_types'] = 'pdf';
        $config['file_name'] = $nama_file;
        $this->upload->initialize($config);

        //      var_dump($config);
        //      die;
        if (!$this->upload->do_upload('lampiran_lain')) {
            $respons = array(
                'status' => 'Gagal Upload',
                'data' => 'cannot access, you dont have permission'
            );
            echo json_encode($respons);
        } else {
            $datasurat = array(
                'id' => $surat_id,
                'hal' => $this->input->post('hal'),
                'tanggal' => $this->input->post('tanggal'),
                'nomor' => $this->input->post('nomor'),
                'jenis_surat' => $this->input->post('jenis_surat'),
                'lampiran_lain' => $nama_file,
            );

        
            $this->pdf->addqrcode($_SERVER['DOCUMENT_ROOT'] .'/assets/lampiransurat/Suratprodukhukum/'.$nama_file, $nama_file, $surat_id);
        }

       

        // $insertsurat = $this->db->insert('surat_produkhukum', $datasurat);
        // if ($insertsurat) {
        //     $getjabatanID = $this->input->post('penandatangan_id');
        //     $cekjabatan = $this->db->query("SELECT aparatur.nama, jabatan.nama_jabatan FROM jabatan LEFT JOIN aparatur ON aparatur.jabatan_id=jabatan.jabatan_id WHERE jabatan.jabatan_id='$getjabatanID'")->row_array();
        //     $gettteID = $this->db->get('penandatangan')->result();
        //     foreach ($gettteID as $key => $tte) {
        //         $idtte = $tte->penandatangan_id;
        //     }
        //     if (empty($idtte)) {
        //         $penandatangan_id = '1';
        //     } else {
        //         $urut = $idtte + 1;
        //         $penandatangan_id = $urut;
        //     }

        //     // Entry Data Ke Tabel Penandatangan
        //     $datatte = array(
        //         'penandatangan_id' => $penandatangan_id,
        //         'surat_id' => $surat_id,
        //         'jabatan_id' => $getjabatanID,
        //         'nama' => $cekjabatan['nama'],
        //         'jabatan' => $cekjabatan['nama_jabatan'],
        //         'status' => 'Belum Ditandatangani',
        //     );
        //     $this->db->insert('penandatangan', $datatte);


        //     // Update @Mpik Egov 27 Juli 2022
        //     $verifikasiid = $this->db->query("SELECT * FROM aparatur
        //     LEFT JOIN users ON users.aparatur_id=aparatur.aparatur_id
        //     LEFT JOIN opd ON opd.opd_id=aparatur.opd_id
        //     WHERE users.level_id=4 AND opd.opd_id='$opdID'")->row_array();
        //     // Entry Data Ke Tabel Draft
        //     $datadraft = array(
        //         'surat_id' => $surat_id,
        //         'tanggal' => $this->input->post('tanggal'),
        //         'dibuat_id' => $jabatanID,
        //         'penandatangan_id' => $penandatangan_id,
        //         'verifikasi_id' => '-1',
        //         //'verifikasi_id' =>$verifikasiid['jabatan_id'], // Update @Mpik Egov 27 Juli 2022
        //         'nama_surat' => $this->input->post('jenis_surat'),
        //     );
        //     $this->db->insert('draft', $datadraft);
        //     $respons = array(
        //         'status' => 'Data Berhasil Disimpan',
        //         'surat_id' => $surat_id,
        //         'url' => 'https://tnd.kotabogor.go.id/suratkeluar/draft/signature',
        //     );
        // } else {
        //     $respons = array(
        //         'status' => 'Gagal Simpan',
        //     );
        // }

        echo json_encode($respons);
    }

    public function ttespt(){
    //jabatan_id
    //opd_id
    //kodesurat_id
    //nomor
    //pegawai_id dalam bentuk array
    //dasar
    //untuk
    //tanggal
    //tembusan
    //penandatangan_id (jabatan penandatangan)
    //kop_id



        $jabatanID=$this->input->post('jabatan_id'); // Get Jabatan id berapa yang membuat suratnya
        $opdID=$this->input->post('opd_id');
        
        
        $getID=$this->db->query("SELECT id FROM surat_perintahtugas ORDER BY LENGTH(id) ASC, id ASC")->result();
        foreach($getID as $key => $h){
            $id=$h->id;
        }
        
        if(empty($id)){
            $surat_id='SPT-1';
        }else{
            $urut=substr($id,4)+1;
            $surat_id='SPT-'.$urut;
        }
        
        // Entry Data Ke Tabel Surat Tugas
            $datasurat=array(
                'id' => $surat_id,
                'opd_id' => $this->input->post('opd_id'),
                'kop_id' => $this->input->post('kop_id'),
                //'hal' => $this->input->post('hal'),
                'kodesurat_id' => $this->input->post('kodesurat_id'),
                'nomor' => $this->input->post('nomor'),
                'pegawai_id' => $this->input->post('pegawai_id'),
                'kepada' => '',
                'dasar' => $this->input->post('dasar'),
                'untuk' => $this->input->post('untuk'),
                'tanggal' => $this->input->post('tanggal'),
                'tembusan' => $this->input->post('tembusan'),
                'lampiran_lain' => '',
            );
            //var_dump($datasurat);
        $insertsurat=$this->db->insert('surat_perintahtugas',$datasurat);
        
        if($insertsurat){
            $getjabatanID=$this->input->post('penandatangan_id');
            $cekjabatan=$this->db->query("SELECT aparatur.nama, jabatan.nama_jabatan FROM jabatan LEFT JOIN aparatur ON aparatur.jabatan_id=jabatan.jabatan_id WHERE jabatan.jabatan_id='$getjabatanID'")->row_array();
            $gettteID = $this->db->get('penandatangan')->result();
                foreach ($gettteID as $key => $tte) {
                    $idtte = $tte->penandatangan_id;
                }
                if (empty($idtte)) {
                    $penandatangan_id = '1';
                } else {
                    $urut = $idtte + 1;
                    $penandatangan_id = $urut;
                }
                
            // Entry Data Ke Tabel Penandatangan
            $datatte=array(
                'penandatangan_id' => $penandatangan_id,
                'surat_id' => $surat_id,
                'jabatan_id' => $getjabatanID, 
                'nama' =>$cekjabatan['nama'], 
                'jabatan' =>$cekjabatan['nama_jabatan'],
                'status' => 'Belum Ditandatangani', 
                );
            $this->db->insert('penandatangan', $datatte);
            

            // Update @Mpik Egov 27 Juli 2022
            $verifikasiid=$this->db->query("SELECT * FROM aparatur
            LEFT JOIN users ON users.aparatur_id=aparatur.aparatur_id
            LEFT JOIN opd ON opd.opd_id=aparatur.opd_id
            WHERE users.level_id=4 AND opd.opd_id='$opdID'")->row_array();
            // Entry Data Ke Tabel Draft
            $datadraft=array(
                'surat_id' => $surat_id,
                'tanggal' =>$this->input->post('tanggal'),
                'dibuat_id' =>$jabatanID, 
                'penandatangan_id' =>$penandatangan_id, 
                'verifikasi_id' =>'-1',
                'kopId' =>$this->input->post('kop_id'),
                //'verifikasi_id' =>$verifikasiid['jabatan_id'], 
                'nama_surat' =>'Surat Perintah Tugas', 
                );
            $this->db->insert('draft', $datadraft);
                $respons = array(
                    'status' => 'Data Berhasil Disimpan',
                    'surat_id' => $surat_id,
                    'url' => 'https://tnd.kotabogor.go.id/uploads/SIGNED/'.$surat_id.'.pdf',
                 );
                 echo json_encode($respons);
            }else{
                    $respons = array(
                        'status' => 'Gagal Simpan',
                    );
                    echo json_encode($respons);
    
        }
       
            
   
        
    }

    public function hapusSuratKeluar(){
        
        if ($this->input->get('key') == 'delSrtkeluar.222;') {

            $id     = $this->input->get('surat_id');
            $where  = array('id' => $id);

            // var_dump(substr($id, 0, 3) == 'SPH');die;

            if (substr($id, 0, 2) == 'SB') {
                $query = $this->db->query("SELECT * FROM surat_biasa WHERE id='$id'")->row_array();

                if(!empty($query)) {
                    @unlink('./assets/lampiransurat/biasa/'.$query['lampiran_lain']);
                    
                    $delete = $this->db->delete('surat_biasa', $where);
                }
            }
            elseif (substr($id, 0, 2) == 'SE') {
                $query = $this->db->query("SELECT * FROM surat_edaran WHERE id='$id'")->row_array();

                if(!empty($query)) {
                    @unlink('./assets/lampiransurat/edaran/'.$query['lampiran_lain']);
                    
                    $delete = $this->db->delete('surat_edaran', $where);
                }
            }
            elseif (substr($id, 0, 2) == 'SU') {
                $query = $this->db->query("SELECT * FROM surat_undangan WHERE id='$id'")->row_array();

                if(!empty($query)) {
                    @unlink('./assets/lampiransurat/undangan/'.$query['lampiran_lain']);
                    
                    $delete = $this->db->delete('surat_undangan', $where);
                }
            }
            elseif (substr($id, 0, 3) == 'LAP') {
                $query = $this->db->query("SELECT * FROM surat_laporan WHERE id='$id'")->row_array();

                if(!empty($query)) {
                    @unlink('./assets/lampiransurat/laporan/'.$query['lampiran_lain']);
                    
                    $delete = $this->db->delete('surat_laporan', $where);
                }
            }
            elseif (substr($id, 0, 5) == 'PNGMN') {
                $query = $this->db->query("SELECT * FROM surat_pengumuman WHERE id='$id'")->row_array();

                if(!empty($query)) {
                    @unlink('./assets/lampiransurat/pengumuman/'.$query['lampiran_lain']);
                    
                    $delete = $this->db->delete('surat_pengumuman', $where);
                }
            }
            elseif (substr($id, 0, 3) == 'REK') {
                $query = $this->db->query("SELECT * FROM surat_rekomendasi WHERE id='$id'")->row_array();

                if(!empty($query)) {
                    @unlink('./assets/lampiransurat/rekomendasi/'.$query['lampiran_lain']);
                    
                    $delete = $this->db->delete('surat_rekomendasi', $where);
                }
            }
            elseif (substr($id, 0, 3) == 'INT') {
                $query = $this->db->query("SELECT * FROM surat_instruksi WHERE id='$id'")->row_array();

                if(!empty($query)) {
                    @unlink('./assets/lampiransurat/instruksi/'.$query['lampiran_lain']);
                    
                    $delete = $this->db->delete('surat_instruksi', $where);
                }
            }
            elseif (substr($id, 0, 3) == 'PNG') {
                $query = $this->db->query("SELECT * FROM surat_pengantar WHERE id='$id'")->row_array();

                if(!empty($query)) {
                    @unlink('./assets/lampiransurat/pengantar/'.$query['lampiran_lain']);
                    
                    $delete = $this->db->delete('surat_pengantar', $where);
                }
            }
            elseif (substr($id, 0, 5) == 'NODIN') {
                $query = $this->db->query("SELECT * FROM surat_notadinas WHERE id='$id'")->row_array();

                if(!empty($query)) {
                    @unlink('./assets/lampiransurat/notadinas/'.$query['lampiran_lain']);
                    
                    $delete = $this->db->delete('surat_notadinas', $where);
                }
            }
            elseif (substr($id, 0, 2) == 'SK') {
                $query = $this->db->query("SELECT * FROM surat_keterangan WHERE id='$id'")->row_array();

                if(!empty($query)) {
                    @unlink('./assets/lampiransurat/keterangan/'.$query['lampiran_lain']);
                    
                    $delete = $this->db->delete('surat_keterangan', $where);
                }
            }
            elseif (substr($id, 0, 3) == 'SPT') {
                $query = $this->db->query("SELECT * FROM surat_perintahtugas WHERE id='$id'")->row_array();

                if(!empty($query)) {
                    @unlink('./assets/lampiransurat/perintahtugas/'.$query['lampiran_lain']);
                    
                    $delete = $this->db->delete('surat_perintahtugas', $where);
                }
            }
            elseif (substr($id, 0, 2) == 'SP') {
                $query = $this->db->query("SELECT * FROM surat_perintah WHERE id='$id'")->row_array();

                if(!empty($query)) {
                    @unlink('./assets/lampiransurat/perintah/'.$query['lampiran_lain']);
                    
                    $delete = $this->db->delete('surat_perintah', $where);
                }
            }
            elseif (substr($id, 0, 3) == 'IZN') {
                $query = $this->db->query("SELECT * FROM surat_izin WHERE id='$id'")->row_array();

                if(!empty($query)) {
                    @unlink('./assets/lampiransurat/izin/'.$query['lampiran_lain']);
                    
                    $delete = $this->db->delete('surat_izin', $where);
                }
            }
            elseif (substr($id, 0, 3) == 'PJL') {
                $query = $this->db->query("SELECT * FROM surat_perjalanan WHERE id='$id'")->row_array();

                if(!empty($query)) {
                    @unlink('./assets/lampiransurat/perjalanan/'.$query['lampiran_lain']);
                    
                    $delete = $this->db->delete('surat_perjalanan', $where);
                }
            }
            elseif (substr($id, 0, 3) == 'KSA') {
                $query = $this->db->query("SELECT * FROM surat_kuasa WHERE id='$id'")->row_array();

                if(!empty($query)) {
                    @unlink('./assets/lampiransurat/kuasa/'.$query['lampiran_lain']);
                    
                    $delete = $this->db->delete('surat_kuasa', $where);
                }
            }
            elseif (substr($id, 0, 3) == 'MKT') {
                $query = $this->db->query("SELECT * FROM surat_melaksanakantugas WHERE id='$id'")->row_array();

                if(!empty($query)) {
                    @unlink('./assets/lampiransurat/melaksanakantugas/'.$query['lampiran_lain']);
                    
                    $delete = $this->db->delete('surat_melaksanakantugas', $where);
                }
            }
            elseif (substr($id, 0, 3) == 'PGL') {
                $query = $this->db->query("SELECT * FROM surat_panggilan WHERE id='$id'")->row_array();

                if(!empty($query)) {
                    @unlink('./assets/lampiransurat/panggilan/'.$query['lampiran_lain']);
                    
                    $delete = $this->db->delete('surat_panggilan', $where);
                }
            }
            elseif (substr($id, 0, 3) == 'NTL') {
                $query = $this->db->query("SELECT * FROM surat_notulen WHERE id='$id'")->row_array();

                if(!empty($query)) {
                    @unlink('./assets/lampiransurat/notulen/'.$query['lampiran_lain']);
                    
                    $delete = $this->db->delete('surat_notulen', $where);
                }
            }
            elseif (substr($id, 0, 3) == 'MMO') {
                $query = $this->db->query("SELECT * FROM surat_memo WHERE id='$id'")->row_array();

                if(!empty($query)) {
                    @unlink('./assets/lampiransurat/memo/'.$query['lampiran_lain']);
                    
                    $delete = $this->db->delete('surat_memo', $where);
                }
            }
            elseif (substr($id, 0, 3) == 'LMP') {
                $query = $this->db->query("SELECT * FROM surat_lampiran WHERE id='$id'")->row_array();

                if(!empty($query)) {
                    @unlink('./assets/lampiransurat/lampiran/'.$query['lampiran_lain']);
                    
                    $delete = $this->db->delete('surat_lampiran', $where);
                }
            }
            elseif (substr($id, 0, 2) == 'SL') {
                $query = $this->db->query("SELECT * FROM surat_lainnya WHERE id='$id'")->row_array();

                if(!empty($query)) {
                    @unlink('./assets/lampiransurat/suratlainnya/'.$query['lampiran']);
                    
                    $delete = $this->db->delete('surat_lainnya', $where);
                }
            }

            /* START: Surat Terintegrasi */
            if (substr($id, 0, 2) == 'SW') {
                $query = $this->db->query("SELECT * FROM surat_wilayah WHERE id='$id'")->row_array();

                if(!empty($query)) {
                    // @unlink('./assets/lampiransurat/suratwilayah/'.$query['lampiran_lain']);
                    @unlink('./uploads/SIGNED/'.$id.'.pdf');
                    
                    $delete = $this->db->delete('surat_wilayah', $where);
                }
            }
            elseif (substr($id, 0, 3) == 'SPH') {
                $query = $this->db->query("SELECT * FROM surat_produkhukum WHERE id='$id'")->row_array();

                if(!empty($query)) {
                    @unlink('./uploads/SIGNED/'.$id.'.pdf');
                    
                    $delete = $this->db->delete('surat_produkhukum', $where);
                }
            }
            /* END: Surat Terintegrasi */
        
            if ($delete) {
                $whereId = array('surat_id' => $id);
                $this->db->delete('draft', $whereId);
                $this->db->delete('verifikasi', $whereId);
                $this->db->delete('disposisi_suratkeluar', $whereId);
                $this->db->delete('tembusan_surat', $whereId);
                $this->db->delete('penandatangan', $whereId);
                $this->db->delete('selesai', $whereId);
                $this->db->delete('retensi_arsip', $whereId);
                
                $hapusSurat=array(
                    'code'      => 200,
                    'status'    => 'Surat Berhasil Dihapus',
                );
        
                echo json_encode($hapusSurat);
            }else{
                $hapusSurat=array(
                    'code'      => 404,
                    'status'    => 'Surat Tidak Ada'
                );
        
                echo json_encode($hapusSurat);
            }

        }else{
            $respons = array(
            'status' => 'Akses Denied',
        );

        echo json_encode($respons);
        }
    }

    public function kembaliSuratKeluar(){
        
        if ($this->input->get('key') == 'kblSrtkeluar.222;') {

            $id         = $this->input->get('surat_id');
            $where      = array('id' => $id);
            $whereId    = array('surat_id' => $id);
            $statusTte  = $this->db->query("SELECT * FROM penandatangan WHERE surat_id='$id'")->row_array();

            $data = array(
                'kodesurat_id'  => '',
                'nomor'         => '',
            );

            $dataDraft = array(
                'penandatangan_id'  => 0,
                'verifikasi_id'     => 0,
            );

            if(empty($statusTte) || $statusTte['status'] == 'Belum Ditandatangani') {               

                if (substr($id, 0, 2) == 'SB') {
                    $query = $this->db->query("SELECT * FROM surat_biasa WHERE id='$id'")->row_array();

                    if(!empty($query)) {

                        $this->db->update('draft',$dataDraft,$whereId);
                        $update = $this->db->update('surat_biasa',$data,$where);
                    }
                }
                elseif (substr($id, 0, 2) == 'SE') {
                    $query = $this->db->query("SELECT * FROM surat_edaran WHERE id='$id'")->row_array();

                    if(!empty($query)) {

                        $this->db->update('draft',$dataDraft,$whereId);
                        $update = $this->db->update('surat_edaran',$data,$where);
                    }
                }
                elseif (substr($id, 0, 2) == 'SU') {
                    $query = $this->db->query("SELECT * FROM surat_undangan WHERE id='$id'")->row_array();

                    if(!empty($query)) {

                        $this->db->update('draft',$dataDraft,$whereId);
                        $update = $this->db->update('surat_undangan',$data,$where);
                    }
                }
                elseif (substr($id, 0, 3) == 'LAP') {
                    $query = $this->db->query("SELECT * FROM surat_laporan WHERE id='$id'")->row_array();

                    if(!empty($query)) {

                        $this->db->update('draft',$dataDraft,$whereId);
                        $update = $this->db->update('surat_laporan',$data,$where);
                    }
                }
                elseif (substr($id, 0, 5) == 'PNGMN') {
                    $query = $this->db->query("SELECT * FROM surat_pengumuman WHERE id='$id'")->row_array();

                    if(!empty($query)) {

                        $this->db->update('draft',$dataDraft,$whereId);
                        $update = $this->db->update('surat_pengumuman',$data,$where);
                    }
                }
                elseif (substr($id, 0, 3) == 'REK') {
                    $query = $this->db->query("SELECT * FROM surat_rekomendasi WHERE id='$id'")->row_array();

                    if(!empty($query)) {

                        $this->db->update('draft',$dataDraft,$whereId);
                        $update = $this->db->update('surat_rekomendasi',$data,$where);
                    }
                }
                elseif (substr($id, 0, 3) == 'INT') {
                    $query = $this->db->query("SELECT * FROM surat_instruksi WHERE id='$id'")->row_array();

                    if(!empty($query)) {

                        $this->db->update('draft',$dataDraft,$whereId);
                        $update = $this->db->update('surat_instruksi',$data,$where);
                    }
                }
                elseif (substr($id, 0, 3) == 'PNG') {
                    $query = $this->db->query("SELECT * FROM surat_pengantar WHERE id='$id'")->row_array();

                    if(!empty($query)) {

                        $this->db->update('draft',$dataDraft,$whereId);
                        $update = $this->db->update('surat_pengantar',$data,$where);
                    }
                }
                elseif (substr($id, 0, 5) == 'NODIN') {
                    $query = $this->db->query("SELECT * FROM surat_notadinas WHERE id='$id'")->row_array();

                    if(!empty($query)) {

                        $this->db->update('draft',$dataDraft,$whereId);
                        $update = $this->db->update('surat_notadinas',$data,$where);
                    }
                }
                elseif (substr($id, 0, 2) == 'SK') {
                    $query = $this->db->query("SELECT * FROM surat_keterangan WHERE id='$id'")->row_array();

                    if(!empty($query)) {

                        $this->db->update('draft',$dataDraft,$whereId);
                        $update = $this->db->update('surat_keterangan',$data,$where);
                    }
                }
                elseif (substr($id, 0, 3) == 'SPT') {
                    $query = $this->db->query("SELECT * FROM surat_perintahtugas WHERE id='$id'")->row_array();

                    if(!empty($query)) {

                        $this->db->update('draft',$dataDraft,$whereId);
                        $update = $this->db->update('surat_perintahtugas',$data,$where);
                    }
                }
                elseif (substr($id, 0, 2) == 'SP') {
                    $query = $this->db->query("SELECT * FROM surat_perintah WHERE id='$id'")->row_array();

                    if(!empty($query)) {

                        $this->db->update('draft',$dataDraft,$whereId);
                        $update = $this->db->update('surat_perintah',$data,$where);
                    }
                }
                elseif (substr($id, 0, 3) == 'IZN') {
                    $query = $this->db->query("SELECT * FROM surat_izin WHERE id='$id'")->row_array();

                    if(!empty($query)) {

                        $this->db->update('draft',$dataDraft,$whereId);
                        $update = $this->db->update('surat_izin',$data,$where);
                    }
                }
                elseif (substr($id, 0, 3) == 'PJL') {
                    $query = $this->db->query("SELECT * FROM surat_perjalanan WHERE id='$id'")->row_array();

                    if(!empty($query)) {

                        $this->db->update('draft',$dataDraft,$whereId);
                        $update = $this->db->update('surat_perjalanan',$data,$where);
                    }
                }
                elseif (substr($id, 0, 3) == 'KSA') {
                    $query = $this->db->query("SELECT * FROM surat_kuasa WHERE id='$id'")->row_array();

                    if(!empty($query)) {

                        $this->db->update('draft',$dataDraft,$whereId);
                        $update = $this->db->update('surat_kuasa',$data,$where);
                    }
                }
                elseif (substr($id, 0, 3) == 'MKT') {
                    $query = $this->db->query("SELECT * FROM surat_melaksanakantugas WHERE id='$id'")->row_array();

                    if(!empty($query)) {

                        $this->db->update('draft',$dataDraft,$whereId);
                        $update = $this->db->update('surat_melaksanakantugas',$data,$where);
                    }
                }
                elseif (substr($id, 0, 3) == 'PGL') {
                    $query = $this->db->query("SELECT * FROM surat_panggilan WHERE id='$id'")->row_array();

                    if(!empty($query)) {

                        $this->db->update('draft',$dataDraft,$whereId);
                        $update = $this->db->update('surat_panggilan',$data,$where);
                    }
                }
                elseif (substr($id, 0, 3) == 'NTL') {
                    $query = $this->db->query("SELECT * FROM surat_notulen WHERE id='$id'")->row_array();

                    if(!empty($query)) {

                        $this->db->update('draft',$dataDraft,$whereId);
                        $update = $this->db->update('surat_notulen',$data,$where);
                    }
                }
                elseif (substr($id, 0, 3) == 'MMO') {
                    $query = $this->db->query("SELECT * FROM surat_memo WHERE id='$id'")->row_array();

                    if(!empty($query)) {

                        $this->db->update('draft',$dataDraft,$whereId);
                        $update = $this->db->update('surat_memo',$data,$where);
                    }
                }
                elseif (substr($id, 0, 3) == 'LMP') {
                    $query = $this->db->query("SELECT * FROM surat_lampiran WHERE id='$id'")->row_array();

                    if(!empty($query)) {

                        $this->db->update('draft',$dataDraft,$whereId);
                        $update = $this->db->update('surat_lampiran',$data,$where);
                    }
                }
                elseif (substr($id, 0, 2) == 'SL') {
                    $query = $this->db->query("SELECT * FROM surat_lainnya WHERE id='$id'")->row_array();

                    if(!empty($query)) {

                        $this->db->update('draft',$dataDraft,$whereId);
                        $update = $this->db->update('surat_lainnya',$data,$where);
                    }
                }
                    
                if ($update) {
                    $this->db->delete('verifikasi', $whereId);
                    $this->db->delete('penandatangan', $whereId);
                    $this->db->delete('selesai', $whereId);
                    
                    $kembaliSurat=array(
                        'code'      => 200,
                        'status'    => 'Surat Berhasil Dikembalikan',
                    );
            
                    echo json_encode($kembaliSurat);
                }else{
                    $kembaliSurat=array(
                        'code'      => 404,
                        'status'    => 'Surat Tidak Ada'
                    );
            
                    echo json_encode($kembaliSurat);
                }

            }
            else {
                $respons=array(
                        'code'      => 404,
                        'status'    => 'Gagal Dikembalikan! Surat Sudah Ditandatangani'
                    );
            
                    echo json_encode($respons);
            }

        }else{
            $respons = array(
            'status' => 'Akses Denied',
        );

        echo json_encode($respons);
        }
    }

    public function hapusSuratMasuk(){
        
        if ($this->input->get('key') == 'delSrtmasuk.222;') {

            $id     = $this->input->get('surat_id');
            $where  = array('suratmasuk_id' => $id);

            $query = $this->db->query("SELECT * FROM surat_masuk WHERE suratmasuk_id='$id'")->row_array();

            if(!empty($query)) {
                @unlink('./assets/lampiransuratmasuk/'.$query['lampiran']);
                @unlink('./assets/lampiransuratmasuk1/'.$query['lampiran']);
                
                $delete     = $this->db->delete('surat_masuk', $where);
            }
        
            if ($delete) {
                $whereId    = array('surat_id' => $id);
                $this->db->delete('disposisi_suratmasuk', $where);
                $this->db->delete('retensi_arsip', $whereId);
                
                $hapusSurat=array(
                    'code'      => 200,
                    'status'    => 'Surat Masuk Berhasil Dihapus',
                );
        
                echo json_encode($hapusSurat);
            }else{
                $hapusSurat=array(
                    'code'      => 404,
                    'status'    => 'Surat Tidak Ada'
                );
        
                echo json_encode($hapusSurat);
            }

        }else{
            $respons = array(
            'status' => 'Akses Denied',
        );

        echo json_encode($respons);
        }
    }

    public function hapusFile()
	{
        if ($this->input->get('key') == 'delFile5rt.222;') {

            $surat_id   = $this->input->get('surat_id');
            $id         = $surat_id.'.pdf';

            $delete     = unlink(FCPATH . "uploads/SIGNED/" . $id);
        
            if ($delete) {
                
                $hapusFile=array(
                    'code'      => 200,
                    'status'    => 'File Berhasil Dihapus',
                );
        
                echo json_encode($hapusFile);
            }else{
                $hapusFile=array(
                    'code'      => 404,
                    'status'    => 'File Tidak Ada'
                );
        
                echo json_encode($hapusFile);
            }

        }else{
            $respons = array(
            'status' => 'Akses Denied',
        );

        echo json_encode($respons);
        }
		
	}

    public function jra($idJra)
	{ 
        $jra = $this->db->query("SELECT * FROM jra WHERE id = '$idJra'")->row();
        
        echo json_encode($jra);

        return;
	}

    public function cekprodukhukum()
	{ 
        $suratid = $this->input->post('suratid');
        $jra = $this->db->query("SELECT * FROM penandatangan WHERE surat_id = '$suratid'")->row();
        
        echo json_encode($jra);

        return;
	} 

    public function postsuratmasuk()
    {        

        $opd = $this->input->post('opd_id');
        $jabatan = $this->input->post('dibuat_id');


        if ($this->input->post('key') == 'p0stsur4tm4suk.224#.') {

            
            /* START Note-4 : mungkin perlu jika nomor suratnya emg ga ada  */
            // $cekNomor = htmlentities($this->input->post('nomor'));
            if (!(htmlentities($this->input->post('nomor')))) {
                $nomor = date("Ymdhis");
            }else{
                $nomor = htmlentities($this->input->post('nomor'));
            }

            // var_dump($nomor);
            // die;
            /* END Note-4 : mungkin perlu jika nomor suratnya emg ga ada */
            // $nomor      = $this->input->post('nomor'); // Note : Sesuaikan dengan note-4
            // $tanggal    = $this->input->post('tanggal'); // Note : Jika tidak digunakan, bisa dihapus

           

        

                
            $surat = $_FILES['lampiran']['name'];
		    $file = $_FILES['lampiran_lain']['name'];
            $ambext = explode(".", $surat);
			$ekstensi = end($ambext);
			$nama_baru = 'SuratMasuk' . date('YmdHis');
		    $nama_file = $nama_baru . "." . $ekstensi;
			$config['upload_path'] = './assets/lampiransuratmasuk/';
			$config['allowed_types'] = 'pdf|jpg|jpeg|png';
			$config['max_size'] = 40000;
			$config['file_name'] = $nama_file;

            $this->upload->initialize($config);
            
            // var_dump($nama_file);
            // die;

                

            

        /* END Note-2 : Fungsi input lampiran surat masuk baiknya dibuat setelah pengecekan data lampiran */

            if ($this->upload->do_upload('lampiran')) { 


            $cekdoubleinput = $this->db->query("SELECT * FROM surat_masuk WHERE nomor='$nomor' AND opd_id='$opd' and YEAR(tanggal) = YEAR(CURDATE())")->num_rows();

                            
                $dataSuratMasuk = array(
                        'dari'          => htmlentities($this->input->post('dari')),
						'nomor'         => $nomor,
						'tanggal'       => htmlentities($this->input->post('tanggal')),
						'lampiran'      => $nama_file,
						'hal'           => htmlentities($this->input->post('hal')),
						'diterima'      => htmlentities($this->input->post('diterima')),
						'penerima'      => htmlentities($this->input->post('penerima')),
						'opd_id'        => $opd,
						'indeks'        => '',
						'kodesurat_id'  => htmlentities($this->input->post('kodesurat_id')),
						'sifat'         => htmlentities($this->input->post('sifat')),
						'lampiran_lain' => htmlentities($this->input->post('lampiran_lain')) ?? '',
						'telp'          => $this->input->post('telp'),
						'isi'           => $this->input->post('isi'),
						'catatan'       => $this->input->post('catatan') ?? '-',
						'dibuat_id'     => $jabatan,
                        // 'status'        => 'Belum Diteruskan'
                );

		
                if ($cekdoubleinput == 1) {
	               
                   
                    $respons = array(
                        'status' => 'Surat Sudah Pernah Dimasukkan',
                    );
        
                    echo json_encode($respons); 
                    
                    return;
                    
                   
                    
                    } else {
                
                    $insert = $this->db->insert('surat_masuk', $dataSuratMasuk);

            }


                if($insert) {

                    $nomor  = $this->input->post('nomor'); 
                    
                    $surat  = $this->db->query("SELECT suratmasuk_id, diterima 
                                                FROM surat_masuk 
                                                WHERE nomor='$nomor' AND opd_id='$opd' AND dibuat_id='$jabatan'")
                                        ->row();

                    $dataRetensi = array(
                        'surat_id' 			=> $surat->suratmasuk_id,
                        'jenis_surat' 		=> 'Surat Masuk',
                        'jra_id'			=> htmlentities($this->input->post('jra_id'))
                    );
                    
                    $this->db->insert('retensi_arsip', $dataRetensi);
                }

                $respons = array(
                    "status" => "Berhasil diinput"
                );
    
                echo json_encode($respons); 
                
                return;


            } else {

                $respons = array(
                    "status" => "Lampiran Wajib diisi!"
                );
    
                echo json_encode($respons); 
                
                return;



            }
            
        }else{

            $respons = array(
                'status'    => 'Akses Denied',
            );

            echo json_encode($respons);
            return;

        }
    }
}
