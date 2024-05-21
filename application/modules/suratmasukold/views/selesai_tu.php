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
                                <th>DARI</th>
                                <th>NOMOR</th>
                                <th>HAL</th>
                                <th>TANGGAL</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                                $no = 1;
                                foreach ($selesai as $key => $s) {
                            ?>
                            <tr>
                                <td><?php echo $no; ?></td>
                                <td><?php echo $s->dari; ?></td>
                                <td><?php echo $s->nomor; ?></td>
                                <td><?php echo $s->hal; ?></td>
                                <td><b>Tanggal Surat :</b> <?php echo tanggal($s->tanggal) ?><br><b>Tanggal Diterima :</b> <?php echo tanggal($s->diterima) ?></td>
                                <td align="center">
                                    
                                    <a href="<?php echo base_url('assets/lampiransuratmasuk/'.$s->lampiran) ?>" title="Lihat Surat" target="_blank"><span class="fa-stack"><i class="fa fa-eye fa-stack-2x"></i></span></a> 

                                    | <a href="<?php echo site_url('export/lembar_disposisi/'.$s->suratmasuk_id) ?>" title="Lihat Lembar Disposisi" target="_blank"><span class="fa-stack"><i class="fa fa-file-text-o fa-stack-2x"></i></span></a> 

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