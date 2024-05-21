<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo site_url('home/dashboard') ?>">Beranda</a></li>                    
    <li><a href="javascript:void(0)">Naskah Dinas Surat</a></li>
    <li class="active">Surat Selesai</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-envelope"></span> Surat Selesai </h2>
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
                                <th>HAL</th>
                                <th>DARI</th>
                                <th>DITERIMA</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                                $selesaiaparatur=$this->db->query("SELECT *,b.tanggal as tglsurat FROM disposisi_suratmasuk a LEFT JOIN surat_masuk b ON b.suratmasuk_id=a.suratmasuk_id WHERE a.aparatur_id='$jabatanid' AND left(b.tanggal, 4)='$tahun' AND a.status='Selesai'")->result();
                                if(empty($selesaiaparatur)){
                            ?>
                            <?php
                                }else{
                                $no = 1;
                                foreach ($selesaiaparatur as $key => $s) {
                            ?>
                            <tr>
                                <td><?php echo $no; ?></td>
                                <td><?php echo $s->hal; ?></td>
                                <td><?php echo $s->dari; ?></td>
                                <td><?php echo tanggal($s->tglsurat); ?></td>
                                <td align="center">
                                    <!-- Fikri Egov 26022022 - 9:31 Kondisi Preview Surat dari Surat TND -->
                                    <?php if(substr($s->lampiran, 0,2) == 'SB'){ ?>
                                        <a href="<?php echo site_url('uploads/SIGNED/'.$s->lampiran) ?>" title="Lihat Surat" target="_blank"><span class="fa-stack"><i class="fa fa-eye fa-stack-2x"></i></span></a>
                                        <?php if($s->lampiran_lain != NULL){ ?>
                                            | <a href="<?php echo site_url('assets/lampiransurat/biasa/'.$s->lampiran_lain) ?>" title="Lihat Lampiran" target="_blank"><span class="fa-stack"><i class="fa fa-file-pdf-o fa-stack-2x"></i></span></a>
                                            <?php } ?>
                                        <?php }elseif(substr($s->lampiran, 0,2) == 'SL'){ ?>
                                        <a href="<?php echo site_url('uploads/SIGNED/'.$s->lampiran) ?>" title="Lihat Surat" target="_blank"><span class="fa-stack"><i class="fa fa-eye fa-stack-2x"></i></span></a>
                                    <?php }elseif(substr($s->lampiran, 0,2) == 'SE'){ ?>
                                        <a href="<?php echo site_url('uploads/SIGNED/'.$s->lampiran) ?>" title="Lihat Surat" target="_blank"><span class="fa-stack"><i class="fa fa-eye fa-stack-2x"></i></span></a>
                                        <?php if($s->lampiran_lain != NULL){ ?>
                                            | <a href="<?php echo site_url('assets/lampiransurat/edaran/'.$s->lampiran_lain) ?>" title="Lihat Lampiran" target="_blank"><span class="fa-stack"><i class="fa fa-file-pdf-o fa-stack-2x"></i></span></a>
                                        <?php } ?>
                                    <?php }elseif(substr($s->lampiran, 0,2) == 'SU'){ ?>
                                        <a href="<?php echo site_url('uploads/SIGNED/'.$s->lampiran) ?>" title="Lihat Surat" target="_blank"><span class="fa-stack"><i class="fa fa-eye fa-stack-2x"></i></span></a>
                                        <?php if($s->lampiran_lain != NULL){ ?>
                                            | <a href="<?php echo site_url('assets/lampiransurat/undangan/'.$s->lampiran_lain) ?>" title="Lihat Lampiran" target="_blank"><span class="fa-stack"><i class="fa fa-file-pdf-o fa-stack-2x"></i></span></a>
                                        <?php } ?>
                                    <?php }elseif(substr($s->lampiran, 0,5) == 'PNGMN'){ ?>
                                        <a href="<?php echo site_url('uploads/SIGNED/'.$s->lampiran) ?>" title="Lihat Surat" target="_blank"><span class="fa-stack"><i class="fa fa-eye fa-stack-2x"></i></span></a>
                                        <?php if($s->lampiran_lain != NULL){ ?>
                                            | <a href="<?php echo site_url('assets/lampiransurat/pengumuman/'.$s->lampiran_lain) ?>" title="Lihat Lampiran" target="_blank"><span class="fa-stack"><i class="fa fa-file-pdf-o fa-stack-2x"></i></span></a>
                                        <?php } ?>
                                    <?php }elseif(substr($s->lampiran, 0,3) == 'LAP'){ ?>
                                        <a href="<?php echo site_url('uploads/SIGNED/'.$s->lampiran) ?>" title="Lihat Surat" target="_blank"><span class="fa-stack"><i class="fa fa-eye fa-stack-2x"></i></span></a>
                                        <?php if($s->lampiran_lain != NULL){ ?>
                                            | <a href="<?php echo site_url('assets/lampiransurat/laporan/'.$s->lampiran_lain) ?>" title="Lihat Lampiran" target="_blank"><span class="fa-stack"><i class="fa fa-file-pdf-o fa-stack-2x"></i></span></a>
                                        <?php } ?>
                                    <?php }elseif(substr($s->lampiran, 0,3) == 'REK'){ ?>
                                        <a href="<?php echo site_url('uploads/SIGNED/'.$s->lampiran) ?>" title="Lihat Surat" target="_blank"><span class="fa-stack"><i class="fa fa-eye fa-stack-2x"></i></span></a>
                                        <?php if($s->lampiran_lain != NULL){ ?>
                                            | <a href="<?php echo site_url('assets/lampiransurat/rekomendasi/'.$s->lampiran_lain) ?>" title="Lihat Lampiran" target="_blank"><span class="fa-stack"><i class="fa fa-file-pdf-o fa-stack-2x"></i></span></a>
                                        <?php } ?>
                                    <?php }elseif(substr($s->lampiran, 0,3) == 'INT'){ ?>
                                        <a href="<?php echo site_url('uploads/SIGNED/'.$s->lampiran) ?>" title="Lihat Surat" target="_blank"><span class="fa-stack"><i class="fa fa-eye fa-stack-2x"></i></span></a>
                                        <?php if($s->lampiran_lain != NULL){ ?>
                                            | <a href="<?php echo site_url('assets/lampiransurat/instruksi/'.$s->lampiran_lain) ?>" title="Lihat Lampiran" target="_blank"><span class="fa-stack"><i class="fa fa-file-pdf-o fa-stack-2x"></i></span></a>
                                        <?php } ?>
                                    <?php }elseif(substr($s->lampiran, 0,3) == 'PNG'){ ?>
                                        <a href="<?php echo site_url('uploads/SIGNED/'.$s->lampiran) ?>" title="Lihat Surat" target="_blank"><span class="fa-stack"><i class="fa fa-eye fa-stack-2x"></i></span></a>
                                        <?php if($s->lampiran_lain != NULL){ ?>
                                            | <a href="<?php echo site_url('assets/lampiransurat/pengantar/'.$s->lampiran_lain) ?>" title="Lihat Lampiran" target="_blank"><span class="fa-stack"><i class="fa fa-file-pdf-o fa-stack-2x"></i></span></a>
                                        <?php } ?>
                                    <?php }elseif(substr($s->lampiran, 0,5) == 'NODIN'){ ?>
                                        <a href="<?php echo site_url('uploads/SIGNED/'.$s->lampiran) ?>" title="Lihat Surat" target="_blank"><span class="fa-stack"><i class="fa fa-eye fa-stack-2x"></i></span></a>
                                        <?php if($s->lampiran_lain != NULL){ ?>
                                            | <a href="<?php echo site_url('assets/lampiransurat/notadinas/'.$s->lampiran_lain) ?>" title="Lihat Lampiran" target="_blank"><span class="fa-stack"><i class="fa fa-file-pdf-o fa-stack-2x"></i></span></a>
                                        <?php } ?>
                                    <?php }elseif(substr($s->lampiran, 0,2) == 'SK'){ ?>
                                        <a href="<?php echo site_url('uploads/SIGNED/'.$s->lampiran) ?>" title="Lihat Surat" target="_blank"><span class="fa-stack"><i class="fa fa-eye fa-stack-2x"></i></span></a>
                                        <?php if($s->lampiran_lain != NULL){ ?>
                                            | <a href="<?php echo site_url('assets/lampiransurat/keterangan/'.$s->lampiran_lain) ?>" title="Lihat Lampiran" target="_blank"><span class="fa-stack"><i class="fa fa-file-pdf-o fa-stack-2x"></i></span></a>
                                        <?php } ?>
                                    <?php }elseif(substr($s->lampiran, 0,3) == 'SPT'){ ?>
                                        <a href="<?php echo site_url('uploads/SIGNED/'.$s->lampiran) ?>" title="Lihat Surat" target="_blank"><span class="fa-stack"><i class="fa fa-eye fa-stack-2x"></i></span></a>
                                        <?php if($s->lampiran_lain != NULL){ ?>
                                            | <a href="<?php echo site_url('assets/lampiransurat/perintahtugas/'.$s->lampiran_lain) ?>" title="Lihat Lampiran" target="_blank"><span class="fa-stack"><i class="fa fa-file-pdf-o fa-stack-2x"></i></span></a>
                                        <?php } ?>
                                    <?php }elseif(substr($s->lampiran, 0,2) == 'SP'){ ?>
                                        <a href="<?php echo site_url('uploads/SIGNED/'.$s->lampiran) ?>" title="Lihat Surat" target="_blank"><span class="fa-stack"><i class="fa fa-eye fa-stack-2x"></i></span></a>
                                        <?php if($s->lampiran_lain != NULL){ ?>
                                            | <a href="<?php echo site_url('assets/lampiransurat/perintah/'.$s->lampiran_lain) ?>" title="Lihat Lampiran" target="_blank"><span class="fa-stack"><i class="fa fa-file-pdf-o fa-stack-2x"></i></span></a>
                                        <?php } ?>
                                    <?php }elseif(substr($s->lampiran, 0,3) == 'IZN'){ ?>
                                        <a href="<?php echo site_url('uploads/SIGNED/'.$s->lampiran) ?>" title="Lihat Surat" target="_blank"><span class="fa-stack"><i class="fa fa-eye fa-stack-2x"></i></span></a>
                                        <?php if($s->lampiran_lain != NULL){ ?>
                                            | <a href="<?php echo site_url('assets/lampiransurat/izin/'.$s->lampiran_lain) ?>" title="Lihat Lampiran" target="_blank"><span class="fa-stack"><i class="fa fa-file-pdf-o fa-stack-2x"></i></span></a>
                                        <?php } ?>
                                    <?php }elseif(substr($s->lampiran, 0,3) == 'PJL'){ ?>
                                        <a href="<?php echo site_url('uploads/SIGNED/'.$s->lampiran) ?>" title="Lihat Surat" target="_blank"><span class="fa-stack"><i class="fa fa-eye fa-stack-2x"></i></span></a>
                                        <?php if($s->lampiran_lain != NULL){ ?>
                                            | <a href="<?php echo site_url('assets/lampiransurat/perjalanandinas/'.$s->lampiran_lain) ?>" title="Lihat Lampiran" target="_blank"><span class="fa-stack"><i class="fa fa-file-pdf-o fa-stack-2x"></i></span></a>
                                        <?php } ?>
                                    <?php }elseif(substr($s->lampiran, 0,3) == 'KSA'){ ?>
                                        <a href="<?php echo site_url('uploads/SIGNED/'.$s->lampiran) ?>" title="Lihat Surat" target="_blank"><span class="fa-stack"><i class="fa fa-eye fa-stack-2x"></i></span></a>
                                        <?php if($s->lampiran_lain != NULL){ ?>
                                            | <a href="<?php echo site_url('assets/lampiransurat/kuasa/'.$s->lampiran_lain) ?>" title="Lihat Lampiran" target="_blank"><span class="fa-stack"><i class="fa fa-file-pdf-o fa-stack-2x"></i></span></a>
                                        <?php } ?>
                                    <?php }elseif(substr($s->lampiran, 0,3) == 'MKT'){ ?>
                                        <a href="<?php echo site_url('uploads/SIGNED/'.$s->lampiran) ?>" title="Lihat Surat" target="_blank"><span class="fa-stack"><i class="fa fa-eye fa-stack-2x"></i></span></a>
                                        <?php if($s->lampiran_lain != NULL){ ?>
                                            | <a href="<?php echo site_url('assets/lampiransurat/melaksanakantugas/'.$s->lampiran_lain) ?>" title="Lihat Lampiran" target="_blank"><span class="fa-stack"><i class="fa fa-file-pdf-o fa-stack-2x"></i></span></a>
                                        <?php } ?>
                                    <?php }elseif(substr($s->lampiran, 0,3) == 'PGL'){ ?>
                                        <a href="<?php echo site_url('uploads/SIGNED/'.$s->lampiran) ?>" title="Lihat Surat" target="_blank"><span class="fa-stack"><i class="fa fa-eye fa-stack-2x"></i></span></a>
                                        <?php if($s->lampiran_lain != NULL){ ?>
                                            | <a href="<?php echo site_url('assets/lampiransurat/panggilan/'.$s->lampiran_lain) ?>" title="Lihat Lampiran" target="_blank"><span class="fa-stack"><i class="fa fa-file-pdf-o fa-stack-2x"></i></span></a>
                                        <?php } ?>
                                    <?php }elseif(substr($s->lampiran, 0,3) == 'NTL'){ ?>
                                        <a href="<?php echo site_url('uploads/SIGNED/'.$s->lampiran) ?>" title="Lihat Surat" target="_blank"><span class="fa-stack"><i class="fa fa-eye fa-stack-2x"></i></span></a>
                                        <?php if($s->lampiran_lain != NULL){ ?>
                                            | <a href="<?php echo site_url('assets/lampiransurat/notulen/'.$s->lampiran_lain) ?>" title="Lihat Lampiran" target="_blank"><span class="fa-stack"><i class="fa fa-file-pdf-o fa-stack-2x"></i></span></a>
                                        <?php } ?>
                                    <?php }elseif(substr($s->lampiran, 0,3) == 'MMO'){ ?>
                                        <a href="<?php echo site_url('uploads/SIGNED/'.$s->lampiran) ?>" title="Lihat Surat" target="_blank"><span class="fa-stack"><i class="fa fa-eye fa-stack-2x"></i></span></a>
                                        <?php if($s->lampiran_lain != NULL){ ?>
                                            | <a href="<?php echo site_url('assets/lampiransurat/memo/'.$s->lampiran_lain) ?>" title="Lihat Lampiran" target="_blank"><span class="fa-stack"><i class="fa fa-file-pdf-o fa-stack-2x"></i></span></a>
                                        <?php } ?>
                                    <?php }elseif(substr($s->lampiran, 0,3) == 'LMP'){ ?>
                                        <a href="<?php echo site_url('uploads/SIGNED/'.$s->lampiran) ?>" title="Lihat Surat" target="_blank"><span class="fa-stack"><i class="fa fa-eye fa-stack-2x"></i></span></a>
                                    <?php }elseif($s->lampiran_lain != NULL){
                                            if(file_exists(FCPATH."assets/lampiransuratmasuk/".$s->lampiran)){
                                    ?>
                                        <a href="<?php echo base_url('assets/lampiransuratmasuk/'.$s->lampiran) ?>" title="Lihat Surat" target="_blank"><span class="fa-stack"><i class="fa fa-eye fa-stack-2x"></i></span></a> 
                                        | <a href="<?php echo base_url('assets/lampiransuratmasuk/'.$s->lampiran_lain) ?>" title="Lihat Lampiran" target="_blank"><span class="fa-stack"><i class="fa fa-file-pdf-o fa-stack-2x"></i></span></a> 
                                    <?php }else{?>
                                        <a href="<?php echo base_url('assets/lampiransuratmasuk1/'.$s->lampiran) ?>" title="Lihat Surat" target="_blank"><span class="fa-stack"><i class="fa fa-eye fa-stack-2x"></i></span></a> 
                                        | <a href="<?php echo base_url('assets/lampiransuratmasuk1/'.$s->lampiran_lain) ?>" title="Lihat Lampiran" target="_blank"><span class="fa-stack"><i class="fa fa-file-pdf-o fa-stack-2x"></i></span></a> 
                                    <?php } }else{
                                            if(file_exists(FCPATH."assets/lampiransuratmasuk/".$s->lampiran)){
                                    ?>
                                            <a href="<?php echo base_url('assets/lampiransuratmasuk/'.$s->lampiran) ?>" title="Lihat Surat" target="_blank"><span class="fa-stack"><i class="fa fa-eye fa-stack-2x"></i></span></a> 
                                    <?php }else{ ?>
                                            <a href="<?php echo base_url('assets/lampiransuratmasuk1/'.$s->lampiran) ?>" title="Lihat Surat" target="_blank"><span class="fa-stack"><i class="fa fa-eye fa-stack-2x"></i></span></a> 
                                    <?php } } ?>
                                    <!-- Fikri Egov 26022022 - 9:31 Kondisi Preview Surat dari Surat TND -->

                                    | <a href="<?php echo site_url('export/lembar_disposisi/'.$s->suratmasuk_id) ?>" title="Lihat Lembar Disposisi" target="_blank"><span class="fa-stack"><i class="fa fa-file-text-o fa-stack-2x"></i></span></a> 

                                </td>
                            </tr>

                            <?php
                                $no++; 
                                } }
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