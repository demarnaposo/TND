<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo site_url('home/dashboard') ?>">Dashboard</a></li>
    <li class="active">Informasi</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-info"></span> Data Informasi</h2>
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
                                <th>DESKRIPSI</th>
                                <th>FILE</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no = 1; 
                                foreach ($informasi as $key => $h) {
                            ?>
                            <tr>
                                <td><?php echo $no; ?></td>
                                <td><?php echo substr($h->deskripsi, 0,100) ?> ...</td>
                                <td>
                                    <?php
                                        if (empty($h->file)) {
                                            echo "<p style='color: red;'>Tidak Ada</p>";
                                        }else{
                                    ?>
                                    <center><a href="<?php echo base_url('assets/fileinformasi/'.$h->file) ?>" target="_blank" title="Lihat File" class="btn btn-info">Lihat</a></center>
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
            <!-- END DEFAULT DATATABLE -->

        </div>
    </div>
</div>