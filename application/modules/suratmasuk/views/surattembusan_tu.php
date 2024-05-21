<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo site_url('home/dashboard') ?>">Halaman Utama</a></li>                    
    <li><a href="<?php echo site_url('home/dashboard') ?>">Beranda</a></li>
    <li><a href="<?php echo site_url('home/dashboard/dashboardmasuk') ?>">Surat Masuk</a></li>
    <li><a href="active">Surat Tembusan</a></li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-envelope"></span> Surat Tembusan</h2>
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
                                <th>AKSI</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                                $no = 1; 
                                foreach ($surattembusan as $key => $h) {
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
                                }elseif(substr($h->surat_id, 0,3) == 'REK'){
                                    echo "<td>".$h->nomorrekomendasi."</td>";
                                    echo "<td>".$h->halrekomendasi."</td>";
                                }elseif(substr($h->surat_id, 0,3) == 'NTL'){
                                    echo "<td>".$h->nomornotulen."</td>";
                                    echo "<td>".$h->halnotulen."</td>";
                                }elseif(substr($h->surat_id, 0,3) == 'SPT'){
                                    echo "<td>".$h->nomorperintahtugas."</td>";
                                    echo "<td></td>";
                                }elseif(substr($h->surat_id, 0,3) == 'PNG'){
                                    echo "<td>".$h->nomorpengantar."</td>";
                                    echo "<td></td>";
                                }elseif(substr($h->surat_id, 0,3) == 'LMP'){
                                    echo "<td>".$h->nomorlampiran."</td>";
                                    echo "<td>".$h->hallampiran."</td>";
                                }elseif(substr($h->surat_id, 0,2) == 'SL'){
                                    echo "<td>".$h->nomorsuratlainnya."</td>";
                                    echo "<td>".$h->halsuratlainnya."</td>";
                                }else{
                                    echo "-";
                                }
                                
                                ?>
                                <td><?php echo tanggal($h->tglsurat) ?></td>

                                <td align="center">

                                    <?php lihatsurattte($h->surat_id) ?>
                                    
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