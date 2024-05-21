<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo site_url('home/dashboard') ?>">Beranda</a></li>
    <li class="active">Disposisi</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-envelope"></span> Data Disposisi</h2>
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
                                <th>NO SURAT</th>
                                <th>TANGGAL</th>
                                <th>HAL</th>
                                <th>STATUS SURAT</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no = 1; 
                                foreach ($disposisi as $key => $h) {
                            ?>
                            <tr>
                                <td><?php echo $no; ?></td>
                                <td><?php echo $h->nomor; ?></td>
                                <td><?php echo tanggal($h->diterima) ?></td>
                                <td><?php echo $h->hal; ?></td>
                                
                                <td align="center">

                                    <a href="<?php echo base_url('assets/lampiransuratmasuk/'.$h->lampiran) ?>" title="Lihat Surat" target="_blank"><span class="fa-stack"><i class="fa fa-file-pdf-o fa-stack-2x"></i></span></a> 

                                    | <a href="<?php echo site_url('export/lembar_disposisi/'.$h->suratmasuk_id) ?>" title="Lihat Lembar Disposisi" target="_blank"><span class="fa-stack"><i class="fa fa-file-text-o fa-stack-2x"></i></a> 

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