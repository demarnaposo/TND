<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo site_url('home/dashboard') ?>">Beranda</a></li>                    
    <li><a href="javascript:void(0)">Naskah Dinas Surat</a></li>
    <li class="active">Tanda Tangan Surat</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-envelope"></span> Tanda Tangan Surat </h2>
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
                                <th>TANGGAL</th>
                                <th>NAMA PEMBUAT</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no = 1; 
                                foreach ($tandatangan as $key => $h) {
                            ?>
                            <tr>
                                <td><?php echo $no; ?></td>
                                <td><?php echo $h->nama_surat; ?></td>
                                <td><?php echo tanggal($h->tanggal) ?></td>
                                <td><?php echo $h->nama_jabatan; ?></td>
                                <td align="center">

                                    <?php lihatsurat($h->surat_id); ?>

                                    <form action="<?php echo site_url('suratkeluar/draft/signer') ?>" method="post"> <br>
                                        <input type="hidden" name="surat_id" value="<?php echo $h->surat_id ?>">
                                        <input type="submit" name="selesai" data-toggle="tooltip" data-placement="top" class="btn btn-warning" value="Tanda Tangani" onclick="return confirm('Apakah anda yakin akan menandatangani surat ini?')">
                                    </form>
                                    
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
            <!-- END DEFAULT DATATABLE -->

        </div>
    </div>
</div>