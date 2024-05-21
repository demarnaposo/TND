<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo site_url('home/dashboard') ?>">Beranda</a></li>
    <li><a href="javascript:void(0)">Pengarsipan</a></li>
    <li class="active">Surat Keluar</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">
    <h2><span class="fa fa-archive"></span> Pengarsipan Surat Keluar</h2>
</div>
<!-- END PAGE TITLE -->

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">

    <div class="row">
        <div class="col-md-12">

            <!-- START DEFAULT DATATABLE -->
            <div class="panel panel-default">
                <div class="panel-body">

                    <div class="table-responsive">
                        <table class="table datatable table-bordered">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>JENIS SURAT</th>
                                    <th>NOMOR SURAT</th> <!-- Update @Mpik Egov 10/06/2022 10/45 -->
                                    <th>PERIHAL SURAT</th> <!-- Update @Mpik Egov 10/06/2022 10/45 -->
                                    <th>TANGGAL</th>
                                    <th>NO RAK/NO SAMPUL/NO BOOK</th>
                                    <th>AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                foreach ($suratkeluar as $key => $h) {
                                ?>
                                    <tr>
                                        <td><?php echo $no; ?></td>
                                        <td><?php echo $h->nama_surat; ?></td>
                                        <!-- Update @Mpik Egov 10/06/2022 10/45 -->
                                        <?php
                                        if (substr($h->surat_id, 0, 2) == 'SU') {
                                            echo "<td>" . $h->nomorundangan . "</td>";
                                            echo "<td>" . $h->halundangan . "</td>";
                                        } elseif (substr($h->surat_id, 0, 2) == 'SE') {
                                            echo "<td>" . $h->nomoredaran . "</td>";
                                            echo "<td>" . $h->haledaran . "</td>";
                                        } elseif (substr($h->surat_id, 0, 2) == 'SB') {
                                            echo "<td>" . $h->nomorbiasa . "</td>";
                                            echo "<td>" . $h->halbiasa . "</td>";
                                        } elseif (substr($h->surat_id, 0, 3) == 'IZN') {
                                            echo "<td>" . $h->nomorizin . "</td>";
                                            echo "<td>" . $h->halizin . "</td>";
                                        } elseif (substr($h->surat_id, 0, 3) == 'PGL') {
                                            echo "<td>" . $h->nomorpanggilan . "</td>";
                                            echo "<td>" . $h->halpanggilan . "</td>";
                                        } elseif (substr($h->surat_id, 0, 5) == 'NODIN') {
                                            echo "<td>" . $h->nomornotadinas . "</td>";
                                            echo "<td>" . $h->halnotadinas . "</td>";
                                        } elseif (substr($h->surat_id, 0, 5) == 'PNGMN') {
                                            echo "<td>" . $h->nomorpengumuman . "</td>";
                                            echo "<td>" . $h->halpengumuman . "</td>";
                                        } elseif (substr($h->surat_id, 0, 3) == 'LAP') {
                                            echo "<td>" . $h->nomorlaporan . "</td>";
                                            echo "<td>" . $h->hallaporan . "</td>";
                                        } elseif (substr($h->surat_id, 0, 3) == 'REK') {
                                            echo "<td>" . $h->nomorrekomendasi . "</td>";
                                            echo "<td>" . $h->halrekomendasi . "</td>";
                                        } elseif (substr($h->surat_id, 0, 3) == 'NTL') {
                                            echo "<td>" . $h->nomornotulen . "</td>";
                                            echo "<td>" . $h->halnotulen . "</td>";
                                        } elseif (substr($h->surat_id, 0, 3) == 'LMP') {
                                            echo "<td>" . $h->nomorlampiran . "</td>";
                                            echo "<td>" . $h->hallampiran . "</td>";
                                        } elseif (substr($h->surat_id, 0, 2) == 'SL') {
                                            echo "<td>" . $h->nomorlainnya . "</td>";
                                            echo "<td>" . $h->halsuratlainnya . "</td>";
                                        } elseif (substr($h->surat_id, 0, 3) == 'SKP') {
                                            echo "<td>" . $h->nomorkeputusan . "</td>";
                                            echo "<td>" . $h->halkeputusan . "</td>";
                                        } elseif (substr($h->surat_id, 0, 3) == 'SPT') {
                                            echo "<td>" . $h->nomorperintahtugas . "</td>";
                                            echo "<td></td>";
                                        } elseif (substr($h->surat_id, 0, 3) == 'PNG') {
                                            echo "<td>" . $h->nomorpengantar . "</td>";
                                            echo "<td></td>";
                                        } elseif (substr($h->surat_id, 0, 2) == 'SK') {
                                            echo "<td>" . $h->nomorketerangan . "</td>";
                                            echo "<td>" . $h->halketerangan . "</td>";
                                        } else {
                                            echo "-";
                                        }

                                        ?>
                                        <!-- End -->
                                        <!-- Update @Mpik Egov 10/06/2022 10/45 -->
                                        <td><?php echo tanggal($h->tanggal) ?></td>
                                        <td><?php echo $h->no_rak; ?>/<?php echo $h->no_sampul; ?>/<?php echo $h->no_book; ?></td> <!-- Update @Mpik Egov 10/06/2022 10/45 -->
                                        <td align="center"><?php 
                                        // Update @Mpik Egov 20/06/2020 : Menambahkan method lihat lampiran surat keluar
                                        if($h->status == 'Sudah Ditandatangani') { 
                                            lihatsurat($h->surat_id); 
                                            lihatlampiransuratkeluar($h->surat_id); 
                                        } else {
                                            echo "Surat Belum Ditandatangani"; 
                                        } 
                                        // END
                                        // Update @Mpik Egov 20/06/2020 : Menambahkan method lihat lampiran surat keluar
                                        ?>
                                        </td>
                                    </tr>

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