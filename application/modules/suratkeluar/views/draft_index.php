<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo site_url('home/dashboard') ?>">Beranda</a></li>
    <li><a href="javascript:void(0)">Naskah Dinas Surat</a></li>
    <li class="active">Draft Surat</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">
    <h2><span class="fa fa-envelope"></span> Draft Surat </h2>
</div>
<!-- END PAGE TITLE -->

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">

    <div class="row">
        <div class="col-md-12">
            <!--========= START [ADD] Fikri Egov 9 Maret 2022 ============================================ [ADD] Fikri Egov 9 Maret 2022 ============================================ [ADD] Fikri Egov 9 Maret 2022 ============================================ -->
            <?php if ($this->session->userdata('level') == 4 || $this->session->userdata('level') == 18) { ?>
                <div class="alert alert-danger" role="alert">
                    <h4><b>Keterangan : Pengarsipan</b> surat dilakukan apabila surat sudah <b>ditandatangani. Jangan arsipkan</b> surat yang belum <b>ditandatangani</b></h4>
                </div>
            <?php } ?>
            <!--========= END [ADD] Fikri Egov 9 Maret 2022 ============================================ [ADD] Fikri Egov 9 Maret 2022 ============================================ [ADD] Fikri Egov 9 Maret 2022 ============================================ -->

            <!-- START DEFAULT DATATABLE -->
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table datatable table-bordered">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>PERANGKAT DAERAH</th>
                                    <th>JENIS SURAT</th>
                                    <th>KOP</th>
                                    <th>NOMOR SURAT</th>
                                    <th>HAL</th>
                                    <th>TANGGAL</th>
                                    <th>NAMA PEMBUAT</th>
                                    <th>STATUS SURAT</th>
                                    <th>AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                foreach ($draft as $key => $h) {
                                ?>
                                    <tr>
                                        <td><?php echo $no; ?>.</td>
                                        <td><?php $id = $h->dibuat_id;
                                            $cekopd = $this->db->query("SELECT opd.nama_pd FROM jabatan LEFT JOIN opd ON opd.opd_id=jabatan.opd_id WHERE jabatan.jabatan_id='$id'")->row_array();
                                            echo $cekopd['nama_pd']; ?></td>
                                        <td><?php echo $h->nama_surat; ?></td>
                                        <!-- Update @Mpik Egov 09/06/2022 13:00 -->
                                        <!-- Penambahan kondisi jenis surat -->
                                        <?php
                                        if (substr($h->surat_id, 0, 2) == 'SU') {
                                            echo "<td>" . $h->namakopundangan . "</td>";
                                            echo "<td>" . $h->nomorundangan . "</td>";
                                            echo "<td>" . $h->halundangan . "</td>";
                                        } elseif (substr($h->surat_id, 0, 2) == 'SE') {
                                            echo "<td>" . $h->namakopedaran . "</td>";
                                            echo "<td>" . $h->nomoredaran . "</td>";
                                            echo "<td>" . $h->haledaran . "</td>";
                                        } elseif (substr($h->surat_id, 0, 2) == 'SB') {
                                            echo "<td>" . $h->namakopbiasa . "</td>";
                                            echo "<td>" . $h->nomorbiasa . "</td>";
                                            echo "<td>" . $h->halbiasa . "</td>";
                                        } elseif (substr($h->surat_id, 0, 3) == 'IZN') {
                                            echo "<td>" . $h->namakopizin . "</td>";
                                            echo "<td>" . $h->nomorizin . "</td>";
                                            echo "<td>" . $h->halizin . "</td>";
                                        } elseif (substr($h->surat_id, 0, 3) == 'PGL') {
                                            echo "<td>" . $h->namakoppanggilan . "</td>";
                                            echo "<td>" . $h->nomorpanggilan . "</td>";
                                            echo "<td>" . $h->halpanggilan . "</td>";
                                        } elseif (substr($h->surat_id, 0, 5) == 'NODIN') {
                                            echo "<td>" . $h->namakopnotadinas . "</td>";
                                            echo "<td>" . $h->nomornotadinas . "</td>";
                                            echo "<td>" . $h->halnotadinas . "</td>";
                                        } elseif (substr($h->surat_id, 0, 5) == 'PNGMN') {
                                            echo "<td>" . $h->namakoppengumuman . "</td>";
                                            echo "<td>" . $h->nomorpengumuman . "</td>";
                                            echo "<td>" . $h->halpengumuman . "</td>";
                                        } elseif (substr($h->surat_id, 0, 3) == 'LAP') {
                                            echo "<td>" . $h->namakoplaporan . "</td>";
                                            echo "<td>" . $h->nomorlaporan . "</td>";
                                            echo "<td>" . $h->hallaporan . "</td>";
                                        } elseif (substr($h->surat_id, 0, 3) == 'REK') {
                                            echo "<td>" . $h->namakoprekomendasi . "</td>";
                                            echo "<td>" . $h->nomorrekomendasi . "</td>";
                                            echo "<td>" . $h->halrekomendasi . "</td>";
                                        } elseif (substr($h->surat_id, 0, 3) == 'NTL') {
                                            echo "<td> </td>";
                                            echo "<td>" . $h->nomornotulen . "</td>";
                                            echo "<td>" . $h->halnotulen . "</td>";
                                        } elseif (substr($h->surat_id, 0, 3) == 'LMP') {
                                            echo "<td></td>";
                                            echo "<td>" . $h->nomorlampiran . "</td>";
                                            echo "<td>" . $h->hallampiran . "</td>";
                                        } elseif (substr($h->surat_id, 0, 2) == 'SL') {
                                            echo "<td>" . $h->namakoplainnya . "</td>";
                                            echo "<td>" . $h->nomorlainnya . "</td>";
                                            echo "<td>" . $h->halsuratlainnya . "</td>";
                                        } elseif (substr($h->surat_id, 0, 3) == 'SKP') {
                                            echo "<td>" . $h->namakopkeputusan . "</td>";
                                            echo "<td>" . $h->nomorkeputusan . "</td>";
                                            echo "<td>" . $h->halkeputusan . "</td>";
                                        } elseif (substr($h->surat_id, 0, 3) == 'SPT') {
                                            echo "<td>" . $h->namakopperintahtugas . "</td>";
                                            echo "<td>" . $h->nomorperintahtugas . "</td>";
                                            echo "<td></td>";
                                        } elseif (substr($h->surat_id, 0, 3) == 'PNG') {
                                            echo "<td>" . $h->namakoppengantar . "</td>";
                                            echo "<td>" . $h->nomorpengantar . "</td>";
                                            echo "<td></td>";
                                        } elseif (substr($h->surat_id, 0, 2) == 'SK') {
                                            echo "<td>" . $h->namakopketerangan . "</td>";
                                            echo "<td>" . $h->nomorketerangan . "</td>";
                                            echo "<td>" . $h->halketerangan . "</td>";
                                        } elseif (substr($h->surat_id, 0, 3) == 'MKT') {
                                            echo "<td>" . $h->namakopmelaksanakantugas . "</td>";
                                            echo "<td>" . $h->nomormelaksanakantugas . "</td>";
                                            echo "<td></td>";
                                        } elseif (substr($h->surat_id, 0, 3) == 'BRA') {
                                            echo "<td>" . $h->namakopberitaacara . "</td>";
                                            echo "<td>" . $h->nomorberitaacara . "</td>";
                                            echo "<td></td>";
                                        } elseif (substr($h->surat_id, 0, 3) == 'MMO') {
                                            echo "<td>" . $h->namakopmemo . "</td>";
                                            echo "<td>" . $h->nomormemo . "</td>";
                                            echo "<td></td>";
                                        } elseif (substr($h->surat_id, 0, 3) == 'INT') {
                                            echo "<td>Wali Kota</td>";
                                            echo "<td>" . $h->nomorinstruksi . "</td>";
                                            echo "<td>" . $h->halinstruksi . "</td>";
                                        } elseif (substr($h->surat_id, 0, 3) == 'KSA') {
                                            echo "<td>" . $h->namakopkuasa . "</td>";
                                            echo "<td>" . $h->nomorkuasa . "</td>";
                                            echo "<td></td>";
                                            // Update @Mpik Egov 2 Agust 2022
                                        } elseif (substr($h->surat_id, 0, 3) == 'PJL') {
                                            echo "<td></td>";
                                            echo "<td>" . $h->nomorperjalanan . "</td>";
                                            echo "<td></td>";
                                        } elseif (substr($h->surat_id, 0, 2) == 'SW') {
                                            echo "<td></td>";
                                            echo "<td>" . $h->nomorwilayah . "</td>";
                                            echo "<td>" . $h->halwilayah . "</td>";
                                            // Update @Mpik Egov 2 Agust 2022
                                        } elseif (substr($h->surat_id, 0, 2) == 'SP') {
                                            echo "<td></td>";
                                            echo "<td>" . $h->nomorwilayah . "</td>";
                                            echo "<td>" . $h->halwilayah . "</td>";
                                            // Update @Rama Egov 21 Sept 2023
                                        } else {
                                            echo "-";
                                        }

                                        ?>
                                        <!-- Update @Mpik Egov 09/06/2022 13:00 -->
                                        <!-- Penambahan kondisi jenis surat -->
                                        <td><?php echo tanggal($h->tanggal) ?></td>
                                        <td><?php echo $h->nama . " - " . $h->nama_jabatan ?></td>
                                        <td style="font-weight: bold;">

                                            <?php
                                            $cekDisposisi = $this->db->query("
                                        SELECT * FROM users 
                                        JOIN aparatur ON users.aparatur_id = aparatur.aparatur_id 
                                        JOIN draft ON draft.verifikasi_id = aparatur.jabatan_id
                                        WHERE draft.id = '$h->surat_id'
                                    ")->row_array();

                                            if (($this->session->userdata('level') == 4) || ($this->session->userdata('level') == 18)) {
                                                $ttd = $this->db->get_where('penandatangan', array('surat_id' => $h->surat_id));
                                                if (empty($ttd->num_rows())) {
                                                    echo "Penomoran Surat Belum Diisi dan Penandatangan belum Dipilih";
                                                } else {
                                                    foreach ($ttd->result() as $key => $t) {
                                                        if ($t->status == 'Belum Ditandatangani') {
                                                            echo "Surat Dalam Proses Penandatanganan";
                                                        } elseif ($t->status == 'Sudah Ditandatangani') {
                                                            echo $t->status;
                                                        }
                                                    }
                                                }
                                            } else {
                                                $qverifikasi = $this->db->query("
                                                SELECT * FROM verifikasi 
                                                WHERE verifikasi.surat_id = '$h->surat_id'
                                            ")->num_rows();
                                                if (empty($qverifikasi)) {
                                                    echo "<p style='color:red; text-align:center;'> KONSEP SURAT </p>";
                                                } else {
                                            ?>

                                                    <center><a href="javascript:void()" data-toggle="modal" data-target="#modalStatus<?php echo $h->surat_id ?>" title="Lihat Status Surat" class="btn btn-info">Lihat</a></center>

                                                    <!-- Modal Status Surat -->
                                                    <div class="modal fade" id="modalStatus<?php echo $h->surat_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Status Surat</h5>
                                                                </div>
                                                                <div class="modal-body">

                                                                    <?php
                                                                    // Update @Mpik Egov 28/07/2022
                                                                    if (($this->session->userdata('level') != 4) || ($this->session->userdata('level') != 18)) {
                                                                        $berada = $this->db->query("
                                                                        SELECT * FROM draft
                                                                        JOIN aparatur ON aparatur.jabatan_id = draft.verifikasi_id
                                                                        JOIN jabatan ON jabatan.jabatan_id = aparatur.jabatan_id
                                                                        WHERE draft.surat_id = '$h->surat_id' AND aparatur.statusaparatur='Aktif'
                                                                    ")->row_array();
                                                                        // Update @Mpik Egov 28/07/2022
                                                                        $sudahdiarsipkan = $this->db->get_where('draft', array('surat_id' => $h->surat_id))->row_array();
                                                                        if ($berada['verifikasi_id'] == '-1' or $sudahdiarsipkan['verifikasi_id'] == '-1') {
                                                                            echo "<center>SURAT SUDAH DIARSIPKAN</center>";
                                                                        } else {
                                                                            echo "SURAT BERADA DI : <br>";
                                                                            echo $berada['nama'] . ' - ' . $berada['nama_jabatan'];
                                                                        }
                                                                    ?>
                                                                        <br><br>
                                                                    <?php } ?>
                                                                    KETERANGAN SURAT : <br>

                                                                    <?php
                                                                    $qketver = $this->db->order_by('verifikasi_id', 'ASC')->get_where('verifikasi', array('surat_id' => $h->surat_id))->result();
                                                                    $nmr = 1;
                                                                    foreach ($qketver as $key => $kv) {
                                                                    ?>

                                                                        <?php echo $nmr; ?>. Dari : <?php echo $kv->dari; ?><br>
                                                                        Keterangan : <?php echo $kv->keterangan; ?> <br>Tanggl : <?= tanggal($kv->tanggal); ?> <br><br> <!-- Update @Mpik Egov 15 Sep 2022-->

                                                                    <?php $nmr++;
                                                                    } ?>

                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- End Modal Status Surat -->

                                                <?php } ?>

                                            <?php } ?>

                                        </td>
                                        <td align="center">

                                            <!-- Kondisi Jika Surat belum ditandatangan dan jika surat memiliki/tidak memiliki lampiran surat => Fikri E-Gov 01 Okt 2021 -->
                                            <?php
                                            $ttd = $this->db->query("SELECT * FROM penandatangan WHERE surat_id='$h->surat_id'")->row_array();
                                            if ($ttd['status'] == 'Belum Ditandatangani') { // Update @Mpik Egov 17/06/2022 08:14 : Penambahan method lihat surat yang sudah TTE
                                                // if (substr($h->surat_id, 0, 3) == 'LMP') {
                                                //     echo "";
                                                // } else {
                                                //     lihatsurat($h->surat_id);
                                                //     lihatlampiransuratkeluar($h->surat_id);
                                                // }

                                                // Update @Mpik Egov 17/06/2022 08:19 : Penambahan method lihat surat yang sudah di TTE
                                            } elseif ($ttd['status'] == 'Sudah Ditandatangani') {
                                                lihatsurattte($h->surat_id);
                                                lihatlampiransuratkeluar($h->surat_id);
                                            } else {
                                                // if (substr($h->surat_id, 0, 3) == 'LMP') {
                                                //     echo "";
                                                //     lihatsurat($h->surat_id);
                                                // } else {
                                                lihatsurat($h->surat_id);
                                                lihatlampiransuratkeluar($h->surat_id);
                                                // }
                                            }
                                            // Update @Mpik Egov 17/06/2022 08:19 : Penambahan method lihat surat yang sudah di TTE
                                            ?>
                                            <!-- Kondisi Jika Surat belum ditandatangan dan jika surat memiliki/tidak memiliki lampiran surat => Fikri E-Gov 01 Okt 2021 -->
                                            <?php
                                            $opdid = $this->session->userdata('opd_id');
                                            if (($cekDisposisi['level_id'] != 4) and ($cekDisposisi['level_id'] != 18)) {
                                                if ($h->verifikasi_id != -1) {
                                                    if (($this->session->userdata('level') != 4) and ($this->session->userdata('level') != 18)) {
                                            ?>
                                                        <?php if (substr($h->surat_id, 0, 2) == 'SB') {
                                                            $cekopd = $this->db->query("SELECT opd_id FROM surat_biasa WHERE opd_id='$opdid' AND id='$h->surat_id'")->row_array();
                                                            if ($cekopd['opd_id'] != $opdid) {
                                                            } else {
                                                        ?>
                                                                | <a href="<?php echo site_url('suratkeluar/biasa/edit/' . $h->surat_id) ?>" data-toggle="tooltip" data-placement="top" title="Ubah Surat"><span class="fa-stack"><i class="fa fa-pencil fa-stack-2x"></i></span></a>
                                                            <?php }
                                                        } elseif (substr($h->surat_id, 0, 2) == 'SE') {
                                                            $cekopd = $this->db->query("SELECT opd_id FROM surat_edaran WHERE opd_id='$opdid' AND id='$h->surat_id'")->row_array();
                                                            if ($cekopd['opd_id'] != $opdid) {
                                                            } else {
                                                            ?>
                                                                | <a href="<?php echo site_url('suratkeluar/edaran/edit/' . $h->surat_id) ?>" data-toggle="tooltip" data-placement="top" title="Ubah Surat"><span class="fa-stack"><i class="fa fa-pencil fa-stack-2x"></i></span></a>
                                                            <?php  }
                                                        } elseif (substr($h->surat_id, 0, 2) == 'SU') {
                                                            $cekopd = $this->db->query("SELECT opd_id FROM surat_undangan WHERE opd_id='$opdid' AND id='$h->surat_id'")->row_array();
                                                            if ($cekopd['opd_id'] != $opdid) {
                                                            } else {
                                                            ?>
                                                                | <a href="<?php echo site_url('suratkeluar/undangan/edit/' . $h->surat_id) ?>" data-toggle="tooltip" data-placement="top" title="Ubah Surat"><span class="fa-stack"><i class="fa fa-pencil fa-stack-2x"></i></span></a>
                                                            <?php }
                                                        } elseif (substr($h->surat_id, 0, 5) == 'PNGMN') {
                                                            $cekopd = $this->db->query("SELECT opd_id FROM surat_pengumuman WHERE opd_id='$opdid' AND id='$h->surat_id'")->row_array();
                                                            if ($cekopd['opd_id'] != $opdid) {
                                                            } else {
                                                            ?>
                                                                | <a href="<?php echo site_url('suratkeluar/pengumuman/edit/' . $h->surat_id) ?>" data-toggle="tooltip" data-placement="top" title="Ubah Surat"><span class="fa-stack"><i class="fa fa-pencil fa-stack-2x"></i></span></a>
                                                            <?php }
                                                        } elseif (substr($h->surat_id, 0, 3) == 'LAP') {
                                                            $cekopd = $this->db->query("SELECT opd_id FROM surat_laporan WHERE opd_id='$opdid' AND id='$h->surat_id'")->row_array();
                                                            if ($cekopd['opd_id'] != $opdid) {
                                                            } else {
                                                            ?>
                                                                | <a href="<?php echo site_url('suratkeluar/laporan/edit/' . $h->surat_id) ?>" data-toggle="tooltip" data-placement="top" title="Ubah Surat"><span class="fa-stack"><i class="fa fa-pencil fa-stack-2x"></i></span></a>
                                                            <?php }
                                                        } elseif (substr($h->surat_id, 0, 3) == 'REK') {
                                                            $cekopd = $this->db->query("SELECT opd_id FROM surat_rekomendasi WHERE opd_id='$opdid' AND id='$h->surat_id'")->row_array();
                                                            if ($cekopd['opd_id'] != $opdid) {
                                                            } else { ?>
                                                                | <a href="<?php echo site_url('suratkeluar/rekomendasi/edit/' . $h->surat_id) ?>" data-toggle="tooltip" data-placement="top" title="Ubah Surat"><span class="fa-stack"><i class="fa fa-pencil fa-stack-2x"></i></span></a>
                                                            <?php }
                                                        } elseif (substr($h->surat_id, 0, 3) == 'INT') {
                                                            $cekopd = $this->db->query("SELECT opd_id FROM surat_instruksi WHERE opd_id='$opdid' AND id='$h->surat_id'")->row_array();
                                                            if ($cekopd['opd_id'] != $opdid) {
                                                            } else { ?>
                                                                | <a href="<?php echo site_url('suratkeluar/instruksi/edit/' . $h->surat_id) ?>" data-toggle="tooltip" data-placement="top" title="Ubah Surat"><span class="fa-stack"><i class="fa fa-pencil fa-stack-2x"></i></span></a>
                                                            <?php }
                                                        } elseif (substr($h->surat_id, 0, 3) == 'PNG') {
                                                            $cekopd = $this->db->query("SELECT opd_id FROM surat_pengantar WHERE opd_id='$opdid' AND id='$h->surat_id'")->row_array();
                                                            if ($cekopd['opd_id'] != $opdid) {
                                                            } else { ?>
                                                                | <a href="<?php echo site_url('suratkeluar/pengantar/edit/' . $h->surat_id) ?>" data-toggle="tooltip" data-placement="top" title="Ubah Surat"><span class="fa-stack"><i class="fa fa-pencil fa-stack-2x"></i></span></a>
                                                            <?php }
                                                        } elseif (substr($h->surat_id, 0, 5) == 'NODIN') {
                                                            $cekopd = $this->db->query("SELECT opd_id FROM surat_notadinas WHERE opd_id='$opdid' AND id='$h->surat_id'")->row_array();
                                                            if ($cekopd['opd_id'] != $opdid) {
                                                            } else {
                                                            ?>
                                                                | <a href="<?php echo site_url('suratkeluar/notadinas/edit/' . $h->surat_id) ?>" data-toggle="tooltip" data-placement="top" title="Ubah Surat"><span class="fa-stack"><i class="fa fa-pencil fa-stack-2x"></i></span></a>
                                                            <?php }
                                                        } elseif (substr($h->surat_id, 0, 2) == 'SK') {
                                                            $cekopd = $this->db->query("SELECT opd_id FROM surat_keterangan WHERE opd_id='$opdid' AND id='$h->surat_id'")->row_array();
                                                            if ($cekopd['opd_id'] != $opdid) {
                                                            } else { ?>
                                                                | <a href="<?php echo site_url('suratkeluar/keterangan/edit/' . $h->surat_id) ?>" data-toggle="tooltip" data-placement="top" title="Ubah Surat"><span class="fa-stack"><i class="fa fa-pencil fa-stack-2x"></i></span></a>
                                                            <?php }
                                                        } elseif (substr($h->surat_id, 0, 3) == 'SPT') { ?>
                                                            | <a href="<?php echo site_url('suratkeluar/perintahtugas/edit/' . $h->surat_id) ?>" data-toggle="tooltip" data-placement="top" title="Ubah Surat"><span class="fa-stack"><i class="fa fa-pencil fa-stack-2x"></i></span></a>
                                                            <?php } elseif (substr($h->surat_id, 0, 2) == 'SP') {
                                                            $cekopd = $this->db->query("SELECT opd_id FROM surat_perintah WHERE opd_id='$opdid' AND id='$h->surat_id'")->row_array();
                                                            if ($cekopd['opd_id'] != $opdid) {
                                                            } else { ?>
                                                                | <a href="<?php echo site_url('suratkeluar/perintah/edit/' . $h->surat_id) ?>" data-toggle="tooltip" data-placement="top" title="Ubah Surat"><span class="fa-stack"><i class="fa fa-pencil fa-stack-2x"></i></span></a>
                                                            <?php }
                                                        } elseif (substr($h->surat_id, 0, 3) == 'IZN') {
                                                            $cekopd = $this->db->query("SELECT opd_id FROM surat_izin WHERE opd_id='$opdid' AND id='$h->surat_id'")->row_array();
                                                            if ($cekopd['opd_id'] != $opdid) {
                                                            } else { ?>
                                                                | <a href="<?php echo site_url('suratkeluar/izin/edit/' . $h->surat_id) ?>" data-toggle="tooltip" data-placement="top" title="Edit Surat"><span class="fa-stack"><i class="fa fa-pencil fa-stack-2x"></i></span></a>
                                                            <?php }
                                                        } elseif (substr($h->surat_id, 0, 3) == 'PJL') {
                                                            $cekopd = $this->db->query("SELECT opd_id FROM surat_perjalanan WHERE opd_id='$opdid' AND id='$h->surat_id'")->row_array();
                                                            if ($cekopd['opd_id'] != $opdid) {
                                                            } else { ?>
                                                                | <a href="<?php echo site_url('suratkeluar/perjalanan/edit/' . $h->surat_id) ?>" data-toggle="tooltip" data-placement="top" title="Ubah Surat"><span class="fa-stack"><i class="fa fa-pencil fa-stack-2x"></i></span></a>
                                                            <?php }
                                                        } elseif (substr($h->surat_id, 0, 3) == 'KSA') {
                                                            $cekopd = $this->db->query("SELECT opd_id FROM surat_kuasa WHERE opd_id='$opdid' AND id='$h->surat_id'")->row_array();
                                                            if ($cekopd['opd_id'] != $opdid) {
                                                            } else { ?>
                                                                | <a href="<?php echo site_url('suratkeluar/kuasa/edit/' . $h->surat_id) ?>" data-toggle="tooltip" data-placement="top" title="Ubah Surat"><span class="fa-stack"><i class="fa fa-pencil fa-stack-2x"></i></span></a>
                                                            <?php }
                                                        } elseif (substr($h->surat_id, 0, 3) == 'MKT') {
                                                            $cekopd = $this->db->query("SELECT opd_id FROM surat_melaksanakantugas WHERE opd_id='$opdid' AND id='$h->surat_id'")->row_array();
                                                            if ($cekopd['opd_id'] != $opdid) {
                                                            } else { ?>
                                                                | <a href="<?php echo site_url('suratkeluar/melaksanakan_tugas/edit/' . $h->surat_id) ?>" data-toggle="tooltip" data-placement="top" title="Ubah Surat"><span class="fa-stack"><i class="fa fa-pencil fa-stack-2x"></i></span></a>
                                                            <?php }
                                                        } elseif (substr($h->surat_id, 0, 3) == 'PGL') {
                                                            $cekopd = $this->db->query("SELECT opd_id FROM surat_panggilan WHERE opd_id='$opdid' AND id='$h->surat_id'")->row_array();
                                                            if ($cekopd['opd_id'] != $opdid) {
                                                            } else { ?>
                                                                | <a href="<?php echo site_url('suratkeluar/panggilan/edit/' . $h->surat_id) ?>" data-toggle="tooltip" data-placement="top" title="Ubah Surat"><span class="fa-stack"><i class="fa fa-pencil fa-stack-2x"></i></span></a>
                                                            <?php }
                                                        } elseif (substr($h->surat_id, 0, 3) == 'NTL') {
                                                            $cekopd = $this->db->query("SELECT opd_id FROM surat_notulen WHERE opd_id='$opdid' AND id='$h->surat_id'")->row_array();
                                                            if ($cekopd['opd_id'] != $opdid) {
                                                            } else { ?>
                                                                | <a href="<?php echo site_url('suratkeluar/notulen/edit/' . $h->surat_id) ?>" data-toggle="tooltip" data-placement="top" title="Ubah Surat"><span class="fa-stack"><i class="fa fa-pencil fa-stack-2x"></i></span></a>
                                                            <?php }
                                                        } elseif (substr($h->surat_id, 0, 3) == 'MMO') { ?>
                                                            | <a href="<?php echo site_url('suratkeluar/memo/edit/' . $h->surat_id) ?>" data-toggle="tooltip" data-placement="top" title="Ubah Surat"><span class="fa-stack"><i class="fa fa-pencil fa-stack-2x"></i></span></a>
                                                            <?php } elseif (substr($h->surat_id, 0, 3) == 'LMP') {
                                                            $cekopd = $this->db->query("SELECT opd_id FROM surat_lampiran WHERE opd_id='$opdid' AND id='$h->surat_id'")->row_array();
                                                            if ($cekopd['opd_id'] != $opdid) {
                                                            } else { ?>
                                                                <a href="<?php echo site_url('suratkeluar/lampiran/edit/' . $h->surat_id) ?>" data-toggle="tooltip" data-placement="top" title="Ubah Surat"><span class="fa-stack"><i class="fa fa-pencil fa-stack-2x"></i></span></a>
                                                            <?php }
                                                        } elseif (substr($h->surat_id, 0, 2) == 'SL') {
                                                            $cekopd = $this->db->query("SELECT opd_id FROM surat_lainnya WHERE opd_id='$opdid' AND id='$h->surat_id'")->row_array();
                                                            if ($cekopd['opd_id'] != $opdid) {
                                                            } else { ?>
                                                                <a href="<?php echo site_url('suratkeluar/suratlainnya/edit/' . $h->surat_id) ?>" data-toggle="tooltip" data-placement="top" title="Ubah Surat"><span class="fa-stack"><i class="fa fa-pencil fa-stack-2x"></i></span></a>
                                                        <?php }
                                                        } ?>
                                                    <?php
                                                    }
                                                    if ($h->dibuat_id == $this->session->userdata('jabatan_id')) {
                                                    ?>
                                                        | <a href="<?php echo site_url('suratkeluar/biasa/delete/' . $h->surat_id) ?>" data-toggle="tooltip" data-placement="top" title="Hapus Surat" onclick="return confirm('Apakah anda yakin akan menghapus?')"><span class="fa-stack"><i class="fa fa-trash-o fa-stack-2x"></i></span></a>
                                            <?php
                                                    }
                                                }
                                            }
                                            ?>

                                            <?php if ($h->dibuat_id != $this->session->userdata('jabatan_id') and $this->session->userdata('level') != '4' and $this->session->userdata('level') != '18') { ?>
                                                | <a href="javascript:void(0)" data-toggle="modal" data-target="#modalKembalikan<?php echo $h->surat_id ?>" title="Kembalikan Surat"><span class="fa-stack"><i class="fa fa-mail-reply fa-stack-2x"></i></span></a>
                                            <?php } ?>

                                            <!-- [UPDATE] Fikri Egov -->
                                            <?php
                                            if ($this->session->userdata('level') != 5 and $this->session->userdata('level') != 9 and $this->session->userdata('level') != 22 and $this->session->userdata('level') != 24 and $this->session->userdata('level') != 26 and $this->session->userdata('level') != 10 and $this->session->userdata('level') != 4 and $this->session->userdata('level') != 18 and $this->session->userdata('jabatan_id') == $h->verifikasi_id or $h->verifikasi_id == 0) {
                                            ?>
                                                | <a href="javascript:void(0)" data-toggle="modal" data-target="#modalPengajuan<?php echo $h->surat_id ?>" title="Teruskan Surat"><span class="fa-stack"><i class="fa fa-mail-forward fa-stack-2x"></i></span></a>
                                                <?php
                                                // Update @Demar Egov 23/02/2024
                                                // Penambahan menghilangkan teruskan surat untuk surat pengantar
                                            } elseif ($this->session->userdata('level') == 5 or $this->session->userdata('level') == 22) {
                                                if (
                                                    $h->kopedaran == 4 or $h->kopbiasa == 4 or $h->kopundangan == 4
                                                    or $h->kopizin == 4 or $h->koppanggilan == 4 or $h->koppengantar == 4 or $h->kopnodin == 4 or $h->koppengumuman == 4 or $h->koplaporan == 4
                                                    or $h->koprekomendasi == 4 or $h->kopketerangan == 4 or $h->kopperintahtugas == 4 or $h->kopmelaksanakantugas == 4
                                                    or $h->kopkuasa == 4 or $h->kopberitaacara == 4 or $h->kopmemo == 4 or $h->koplainnya == 4 or  $h->kopedaran == 3 or $h->kopbiasa == 3 or $h->kopundangan == 3
                                                    or $h->kopizin == 3 or $h->koppanggilan == 3 or $h->koppengantar == 3 or $h->kopnodin == 3 or $h->koppengumuman == 3 or $h->koplaporan == 3
                                                    or $h->koprekomendasi == 3 or $h->kopketerangan == 3 or $h->kopperintahtugas == 3 or $h->kopmelaksanakantugas == 3
                                                    or $h->kopkuasa == 3 or $h->kopberitaacara == 3 or $h->kopmemo == 3 or $h->koplainnya == 3  or $h->kopedaran == 1 or $h->kopbiasa == 1 or $h->kopundangan == 1
                                                    or $h->kopizin == 1 or $h->koppanggilan == 1  or $h->koppengantar == 1 or $h->kopnodin == 1 or $h->koppengumuman == 1 or $h->koplaporan == 1
                                                    or $h->koprekomendasi == 1 or $h->kopketerangan == 1 or $h->kopperintahtugas == 1 or $h->kopmelaksanakantugas == 1
                                                    or $h->kopkuasa == 1 or $h->kopberitaacara == 1 or $h->kopmemo == 1 or $h->koplainnya == 1 or $h->kopId == 1 
                                                ) {
                                                    // END
                                                    // Update @Demar Egov 23/02/2024
                                                ?>
                                                    | <a href="javascript:void(0)" data-toggle="modal" data-target="#modalPengajuan<?php echo $h->surat_id ?>" title="Teruskan Surat"><span class="fa-stack"><i class="fa fa-mail-forward fa-stack-2x"></i></span></a>
                                                <?php
                                                }
                                            } elseif ($this->session->userdata('level') == 10) {
                                                if (
                                                    $h->kopedaran == 1 or $h->kopbiasa == 1 or $h->kopundangan == 1
                                                    or $h->kopizin == 1 or $h->koppanggilan == 1 or $h->kopnodin == 1 or $h->koppengumuman == 1 or $h->koplaporan == 1 or $h->kopId == 1
                                                    or $h->koprekomendasi == 1 or $h->kopketerangan == 1 or $h->kopperintahtugas == 1 or $h->kopmelaksanakantugas == 1
                                                    or $h->kopkuasa == 1 or $h->kopberitaacara == 1 or $h->kopmemo == 1 or $h->koplainnya == 1 or $h->kopedaran == 3 or $h->kopbiasa == 3 or $h->kopundangan == 3
                                                    or $h->kopizin == 3 or $h->koppanggilan == 3 or $h->kopnodin == 3 or $h->koppengumuman == 3 or $h->koplaporan == 3
                                                    or $h->koprekomendasi == 3 or $h->kopketerangan == 3 or $h->kopperintahtugas == 3 or $h->kopmelaksanakantugas == 3
                                                    or $h->kopkuasa == 3 or $h->kopberitaacara == 3 or $h->kopmemo == 3 or $h->koplainnya == 3
                                                ) { ?>
                                                    | <a href="javascript:void(0)" data-toggle="modal" data-target="#modalPengajuan<?php echo $h->surat_id ?>" title="Teruskan Surat"><span class="fa-stack"><i class="fa fa-mail-forward fa-stack-2x"></i></span></a>
                                                    <!-- Update @Mpik Egov 3 Oktober 2022 -->
                                                <?php
                                                }
                                            } elseif ($this->session->userdata('level') == 24 or $this->session->userdata('level') == 26) {
                                                if (
                                                    $h->kopedaran == 2 or $h->kopbiasa == 2 or $h->kopundangan == 2
                                                    or $h->kopizin == 2 or $h->koppanggilan == 2 or $h->kopnodin == 2 or $h->koppengumuman == 2 or $h->koplaporan == 2
                                                    or $h->koprekomendasi == 2 or $h->kopketerangan == 2 or $h->kopperintahtugas == 2 or $h->kopmelaksanakantugas == 2
                                                    or $h->kopkuasa == 2 or $h->kopberitaacara == 2 or $h->kopmemo == 2
                                                ) { ?>
                                                    | <a href="javascript:void(0)" data-toggle="modal" data-target="#modalPengajuan<?php echo $h->surat_id ?>" title="Teruskan Surat"><span class="fa-stack"><i class="fa fa-mail-forward fa-stack-2x"></i></span></a>
                                                    <!-- END Update @Mpik Egov 3 Oktober 2022 -->
                                            <?php
                                                }
                                            } else {
                                                echo "";
                                            }
                                            // =============== END [UPDATE] Fikri Egov 2 Maret 2022 ======================================================== [UPDATE] Fikri Egov 2 Maret 2022 ======================================================== [UPDATE] Fikri Egov 2 Maret 2022 ======================================================== [UPDATE] Fikri Egov 2 Maret 2022 ========================================================
                                            ?>
                                            <!-- [UPDATE] Fikri Egov -->

                                            <?php if ($this->session->userdata('level') == 5 or $this->session->userdata('level') == 6 or $this->session->userdata('level') == 22 or $this->session->userdata('level') == 23) { ?>
                                                <form action="<?php echo site_url('suratkeluar/draft/verify') ?>" method="post"> <br>
                                                    <input type="hidden" name="surat_id" value="<?php echo $h->surat_id ?>">
                                                    <!-- Update @Demar Egov 23/02/2024 : Pengkondisian Jika Kop SEKDA dan Walikota Tombol Selesai Hilang -->
                                                    <!-- Penambahan menghilangkan teruskan surat untuk surat pengantar -->
                                                    <?php
                                                    if (
                                                        $h->kopedaran == 4 or $h->kopbiasa == 4 or $h->kopundangan == 4
                                                        or $h->kopizin == 4 or $h->koppanggilan == 4 or $h->koppengantar == 4 or $h->kopnodin == 4 or $h->koppengumuman == 4 or $h->koplaporan == 4
                                                        or $h->koprekomendasi == 4 or $h->kopketerangan == 4 or $h->kopperintahtugas == 4 or $h->kopmelaksanakantugas == 4
                                                        or $h->kopkuasa == 4 or $h->kopberitaacara == 4 or $h->kopmemo == 4 or $h->koplainnya == 4 or $h->kopedaran == 3 or $h->kopbiasa == 3 or $h->kopundangan == 3
                                                        or $h->kopizin == 3 or $h->koppanggilan == 3 or $h->koppengantar == 3 or $h->kopnodin == 3 or $h->koppengumuman == 3 or $h->koplaporan == 3
                                                        or $h->koprekomendasi == 3 or $h->kopketerangan == 3 or $h->kopperintahtugas == 3 or $h->kopmelaksanakantugas == 3
                                                        or $h->kopkuasa == 3 or $h->kopberitaacara == 3 or $h->kopmemo == 3 or $h->koplainnya == 3 or $h->kopedaran == 1 or $h->kopbiasa == 1 or $h->kopundangan == 1
                                                        or $h->kopizin == 1 or $h->koppanggilan == 1 or $h->koppengantar == 1 or $h->kopnodin == 1 or $h->koppengumuman == 1 or $h->koplaporan == 1
                                                        or $h->koprekomendasi == 1 or $h->kopketerangan == 1 or $h->kopperintahtugas == 1 or $h->kopmelaksanakantugas == 1
                                                        or $h->kopkuasa == 1 or $h->kopberitaacara == 1 or $h->kopmemo == 1 or $h->koplainnya == 1 or $h->kopId == 1
                                                    ) {
                                                        echo "";
                                                    } else { ?>
                                                        <input type="submit" name="selesai" data-toggle="tooltip" data-placement="top" class="btn btn-warning" value="Selesai" onclick="return confirm('Apakah anda yakin akan menyelesaikan surat ini?')">
                                                    <?php } ?>
                                                    <!-- END -->
                                                    <!-- Update @Demar Egov 23/02/2024 : Pengkondisian Jika Kop SEKDA dan Walikota Tombol Selesai Hilang -->
                                                </form>
                                                <!-- Update @Mpik Egov 2/07/2022 -->
                                            <?php } elseif ($this->session->userdata('level') == 10 or $this->session->userdata('level') == 11) { ?>
                                                <form action="<?php echo site_url('suratkeluar/draft/verify') ?>" method="post"> <br>
                                                    <input type="hidden" name="surat_id" value="<?php echo $h->surat_id ?>">
                                                    <!-- Update @Mpik Egov 29/06/2022 : Pengkondisian Jika Kop SEKDA dan Walikota Tombol Selesai Hilang -->
                                                    <?php
                                                    if (
                                                        $h->kopedaran == 3 or $h->kopbiasa == 3 or $h->kopundangan == 3
                                                        or $h->kopizin == 3 or $h->koppanggilan == 3 or $h->kopnodin == 3 or $h->koppengumuman == 3 or $h->koplaporan == 3
                                                        or $h->koprekomendasi == 3 or $h->kopketerangan == 3 or $h->kopperintahtugas == 3 or $h->kopmelaksanakantugas == 3
                                                        or $h->kopkuasa == 3 or $h->kopberitaacara == 3 or $h->kopmemo == 3 or $h->koplainnya == 3 or $h->kopedaran == 1 or $h->kopbiasa == 1 or $h->kopundangan == 1
                                                        or $h->kopizin == 1 or $h->koppanggilan == 1 or $h->kopnodin == 1 or $h->koppengumuman == 1 or $h->koplaporan == 1
                                                        or $h->koprekomendasi == 1 or $h->kopketerangan == 1 or $h->kopperintahtugas == 1 or $h->kopmelaksanakantugas == 1
                                                        or $h->kopkuasa == 1 or $h->kopberitaacara == 1 or $h->kopmemo == 1 or $h->koplainnya == 1 or $h->kopId == 1
                                                    ) {
                                                        echo "";
                                                    } else { ?>
                                                        <input type="submit" name="selesai" data-toggle="tooltip" data-placement="top" class="btn btn-warning" value="Selesai" onclick="return confirm('Apakah anda yakin akan menyelesaikan surat ini?')">
                                                    <?php } ?>
                                                    <!-- END -->
                                                    <!-- Update @Mpik Egov 29/06/2022 : Pengkondisian Jika Kop SEKDA dan Walikota Tombol Selesai Hilang -->
                                                </form>
                                            <?php } elseif ($this->session->userdata('level') == 24 or $this->session->userdata('level') == 25 or $this->session->userdata('level') == 26 or $this->session->userdata('level') == 27) { ?>
                                                <form action="<?php echo site_url('suratkeluar/draft/verify') ?>" method="post"> <br>
                                                    <input type="hidden" name="surat_id" value="<?php echo $h->surat_id ?>">
                                                    <!-- Update @Mpik Egov 29/06/2022 : Pengkondisian Jika Kop SEKDA dan Walikota Tombol Selesai Hilang -->
                                                    <?php
                                                    if (
                                                        $h->kopedaran == 3 or $h->kopbiasa == 3 or $h->kopundangan == 3
                                                        or $h->kopizin == 3 or $h->koppanggilan == 3 or $h->kopnodin == 3 or $h->koppengumuman == 3 or $h->koplaporan == 3
                                                        or $h->koprekomendasi == 3 or $h->kopketerangan == 3 or $h->kopperintahtugas == 3 or $h->kopmelaksanakantugas == 3
                                                        or $h->kopkuasa == 3 or $h->kopberitaacara == 3 or $h->kopmemo == 3 or $h->kopedaran == 2 or $h->kopbiasa == 2 or $h->kopundangan == 2
                                                        or $h->kopizin == 2 or $h->koppanggilan == 2 or $h->kopnodin == 2 or $h->koppengumuman == 2 or $h->koplaporan == 2
                                                        or $h->koprekomendasi == 2 or $h->kopketerangan == 2 or $h->kopperintahtugas == 2 or $h->kopmelaksanakantugas == 2
                                                        or $h->kopkuasa == 2 or $h->kopberitaacara == 2 or $h->kopmemo == 2 or $h->kopedaran == 4 or $h->kopbiasa == 4 or $h->kopundangan == 4
                                                        or $h->kopizin == 4 or $h->koppanggilan == 4 or $h->kopnodin == 4 or $h->koppengumuman == 2 or $h->koplaporan == 4
                                                        or $h->koprekomendasi == 4 or $h->kopketerangan == 4 or $h->kopperintahtugas == 4 or $h->kopmelaksanakantugas == 4
                                                        or $h->kopkuasa == 4 or $h->kopberitaacara == 4 or $h->kopmemo == 4 or $h->kopedaran == 1 or $h->kopbiasa == 1 or $h->kopundangan == 1
                                                        or $h->kopizin == 1 or $h->koppanggilan == 1 or $h->kopnodin == 1 or $h->koppengumuman == 1 or $h->koplaporan == 1
                                                        or $h->koprekomendasi == 1 or $h->kopketerangan == 1 or $h->kopperintahtugas == 1 or $h->kopmelaksanakantugas == 1
                                                        or $h->kopkuasa == 1 or $h->kopberitaacara == 1 or $h->kopmemo == 1
                                                    ) {
                                                        echo "";
                                                    } else { ?>
                                                        <input type="submit" name="selesai" data-toggle="tooltip" data-placement="top" class="btn btn-warning" value="Selesai" onclick="return confirm('Apakah anda yakin akan menyelesaikan surat ini?')">
                                                    <?php } ?>
                                                    <!-- END -->
                                                    <!-- Update @Mpik Egov 29/06/2022 : Pengkondisian Jika Kop SEKDA dan Walikota Tombol Selesai Hilang -->
                                                </form>
                                            <?php } elseif ($this->session->userdata('level') == 9 or $this->session->userdata('level') == 19) { ?>
                                                <form action="<?php echo site_url('suratkeluar/draft/verify') ?>" method="post"> <br>
                                                    <input type="hidden" name="surat_id" value="<?php echo $h->surat_id ?>">
                                                    <input type="submit" name="selesai" data-toggle="tooltip" data-placement="top" class="btn btn-warning" value="Selesai" onclick="return confirm('Apakah anda yakin akan menyelesaikan surat ini?')">
                                                </form>

                                            <?php } ?>

                                            <!-- Update @Mpik Egov 2/07/2022 -->

                                            <?php if (($this->session->userdata('level') == 4) || ($this->session->userdata('level') == 18)) {
                                                $ttd = $this->db->get_where('penandatangan', array('surat_id' => $h->surat_id));
                                                foreach ($ttd->result() as $key => $t) {
                                                    $status = $t->status;
                                                    if ($status == "Belum Ditandatangani") {
                                            ?>

                                                        <!-- Ubah Penomoran dan Penandatangan Surat-->
                                                        <a href="<?php echo site_url('suratkeluar/draft/edit/' . $h->surat_id) ?>" data-toggle="tooltip" data-placement="bottom" title="Edit Penomoran/ Penandatangan"><span class="fa-stack"><i class="fa fa-pencil fa-stack-2x"></i></a>

                                                <?php }
                                                } ?>

                                                | <a href="<?php echo site_url('suratkeluar/draft/disposisi/' . $h->surat_id) ?>" data-toggle="tooltip" data-placement="bottom" title="Penomoran dan Pengarsipan Surat"><span class="fa-stack"><i class="fa fa-mail-forward fa-stack-2x""></i></a>

                                    <?php } ?>                                   
                                </td>
                            </tr>

                            <!-- Modal Verifikasi -->
                            <div class=" modal fade" id="modalPengajuan<?php echo $h->surat_id ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">
                                                                            <?php
                                                                            if ($this->session->userdata('level') != 4 and $this->session->userdata('level') != 18) {
                                                                                echo "Teruskan Surat";
                                                                            } else {
                                                                                echo "Pengajuan Surat";
                                                                            }
                                                                            ?>
                                                                        </h5>
                                                                    </div>
                                                                    <form action="<?php echo site_url('suratkeluar/draft/verify') ?>" method="post">
                                                                        <div class="modal-body">
                                                                            <input type="hidden" name="uri_segment" value="<?php echo $this->uri->segment(2) ?>">
                                                                            <input type="hidden" name="surat_id" value="<?php echo $h->surat_id ?>">
                                                                            <input type="hidden" name="jabatan_id" value="<?php echo $this->session->userdata('jabatan_id') ?>">
                                                                            <label class="control-label">Keterangan</label>
                                                                            <textarea type="text" name="keterangan" class="form-control"></textarea>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                                            <input type="submit" name="verifikasi" class="btn btn-primary" value="Teruskan">
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                    </div>
                    <!-- End Modal Verifikasi -->

                    <!-- Modal Kembalikan -->
                    <div class="modal fade" id="modalKembalikan<?php echo $h->surat_id ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Kembalikan Surat</h5>
                                </div>
                                <form action="<?php echo site_url('suratkeluar/draft/verify') ?>" method="post">
                                    <div class="modal-body">
                                        <input type="hidden" name="uri_segment" value="<?php echo $this->uri->segment(2) ?>">
                                        <input type="hidden" name="surat_id" value="<?php echo $h->surat_id ?>">
                                        <input type="hidden" name="jabatan_id" value="<?php echo $this->session->userdata('jabatan_id') ?>">
                                        <label class="control-label">Keterangan</label>
                                        <textarea type="text" name="keterangan" class="form-control"></textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                        <input type="submit" name="kembalikan" class="btn btn-primary" value="Kembalikan">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- End Modal Kembalikan -->

                <?php
                                    $no++;
                                }
                ?>
                </tbody>
                </table>
                </div>
            </div>
        </div>
        <!-- END DEFAULT DATATABLE -->

    </div>
</div>
</div>