<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo site_url('home/dashboard') ?>">Halaman Utama</a></li>                    
    <li><a href="<?php echo site_url('home/dashboard') ?>">Beranda</a></li>
    <li><a href="<?php echo site_url('home/dashboard/dashboardmasuk') ?>">Surat Masuk</a></li>
    <li><a href="active">Surat TND</a></li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-envelope"></span> Surat TND</h2>
</div>
<!-- END PAGE TITLE -->                

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">                

    <div class="row">
        <div class="col-md-12">
            
            <!-- START DEFAULT DATATABLE -->                                
            <div class="panel panel-default">           
                <div class="panel-body">

                    <table class="table datatable table-responsive table-bordered">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>JENIS SURAT</th>
                                <th>NOMOR SURAT</th>
                                <th>HAL</th>
                                <th>TANGGAL SURAT</th>
                                <th>STATUS SURAT</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                                $no = 1; 
                                foreach ($surattnd as $key => $h) {
                            ?>
                            <tr>
                                <td><?php echo $no; ?></td>
                                <td><?php echo $h->nama_surat; ?></td>
                                <?php
                                if(substr($h->surat_id, 0,2) == 'SU'){
                                    echo "<td>".$h->nomorundangan."</td>";
                                    echo "<td>".$h->halundangan."</td>";
                                }elseif(substr($h->surat_id, 0,2) == 'SE'){
                                    echo "<td>".$h->nomoredaran."</td>";
                                    echo "<td>".$h->haledaran."</td>";
                                }elseif(substr($h->surat_id, 0,2) == 'SK'){
                                    echo "<td>".$h->nomorketerangan."</td>";
                                    echo "<td></td>";
                                }elseif(substr($h->surat_id, 0,2) == 'SB'){
                                    echo "<td>".$h->nomorbiasa."</td>";
                                    echo "<td>".$h->halbiasa."</td>";
                                }elseif(substr($h->surat_id, 0,3) == 'IZN'){
                                    echo "<td>".$h->nomorizin."</td>";
                                    echo "<td>".$h->halizin."</td>";
                                }elseif(substr($h->surat_id, 0,3) == 'PGL'){
                                    echo "<td>".$h->nomorpanggilan."</td>";
                                    echo "<td>".$h->halpanggilan."</td>";
                                }elseif(substr($h->surat_id, 0,5) == 'NODIN'){
                                    echo "<td>".$h->nomornotadinas."</td>";
                                    echo "<td>".$h->halnotadinas."</td>";
                                }elseif(substr($h->surat_id, 0,5) == 'PNGMN'){
                                    echo "<td>".$h->nomorpengumuman."</td>";
                                    echo "<td>".$h->halpengumuman."</td>";
                                }elseif(substr($h->surat_id, 0,3) == 'LAP'){
                                    echo "<td>".$h->nomorlaporan."</td>";
                                    echo "<td>".$h->hallaporan."</td>";
                                }elseif(substr($h->surat_id, 0,3) == 'INT'){
                                    echo "<td>".$h->nomorinstruksi."</td>";
                                    echo "<td>".$h->halinstruksi."</td>";
                                }elseif(substr($h->surat_id, 0,3) == 'REK'){
                                    echo "<td>".$h->nomorrekomendasi."</td>";
                                    echo "<td>".$h->halrekomendasi."</td>";
                                }elseif(substr($h->surat_id, 0,3) == 'NTL'){
                                    echo "<td>".$h->nomornotulen."</td>";
                                    echo "<td>".$h->halnotulen."</td>";
                                }elseif(substr($h->surat_id, 0,3) == 'LMP'){
                                    echo "<td>".$h->nomorlampiran."</td>";
                                    echo "<td>".$h->hallampiran."</td>";
                                }elseif(substr($h->surat_id, 0,3) == 'PNG'){
                                    echo "<td>".$h->nomorpengantar."</td>";
                                    echo "<td></td>";
                                }elseif(substr($h->surat_id, 0,3) == 'SPT'){
                                    echo "<td>".$h->nomorperintahtugas."</td>";
                                    echo "<td></td>";
                                }elseif(substr($h->surat_id, 0,2) == 'SP'){
                                    echo "<td>".$h->nomorperintah."</td>";
                                    echo "<td></td>";
                                }elseif(substr($h->surat_id, 0,3) == 'KSA'){
                                    echo "<td>".$h->nomorkuasa."</td>";
                                    echo "<td></td>";
                                }elseif(substr($h->surat_id, 0,3) == 'MKT'){
                                    echo "<td>".$h->nomormelaksanakantugas."</td>";
                                    echo "<td></td>";
                                }elseif(substr($h->surat_id, 0,3) == 'PJL'){
                                    echo "<td>".$h->nomorperjalanan."</td>";
                                    echo "<td>".$h->kegiatanperjalanan."</td>";
                                }else{
                                    echo "-";
                                }
                                
                                ?>
                                <td><?php echo tanggal($h->tglsurat) ?></td>
                                
                                <td>
                                    <?php
                                        $opd = $this->session->userdata('opd_id');
                                        $cekDisposisi = $this->db->query("SELECT * FROM surat_masuk WHERE lampiran LIKE '%$h->surat_id%' AND opd_id = $opd")->num_rows();
                                        if ($cekDisposisi > 0) {
                                            echo "<p style='color:green; text-align:center;'> SURAT SUDAH DIINPUTKAN KE SURAT MASUK </p>";
                                        }else{
                                            echo "<p style='color:red; text-align:center;'> SURAT BELUM DIINPUTKAN KE SURAT MASUK </p>";
                                        }
                                    ?>
                                </td>

                                <td align="center">

                                    <!-- <?php lihatsurat($h->surat_id) ?> -->
                                    <a href="<?= site_url('uploads/SIGNED/'.$h->surat_id) ?>" title="Lihat Surat" target="_blank"><span class="fa-stack"><i class="fa fa-eye fa-stack-2x"></i></span></a>
                                    
                                    <?php if ($cekDisposisi == 0) { ?>
                                        | <a href="<?php echo site_url('suratmasuk/surat/add/'.$h->surat_id) ?>" title="Disposisi Surat"><span class="fa-stack"><i class="fa fa-mail-forward fa-stack-2x"></i></span></a>
                                    <?php } ?>

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
            <!-- END TABS -->

        </div>
    </div>
</div>