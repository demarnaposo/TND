<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="#">Dashboard</a></li>
    <li><a href="#">Data Master</a></li>
    <li class="active">Kode Surat</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-code-fork"></span> Data Kode Surat</h2>
</div>
<!-- END PAGE TITLE -->                

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">                

    <div class="row">
        <div class="col-md-12">

            <!-- START DEFAULT DATATABLE -->
            <div class="panel panel-default">
                <div class="panel-body">
                    <a href="<?php echo site_url('master/kodesurat/add') ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Tambah Data</a> <br><br>
                    <table class="table datatable table-responsive table-bordered">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>KODE</th>
                                <th>TENTANG</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no = 1; 
                                foreach ($kodesurat as $key => $h) {
                            ?>
                            <tr>
                                <td><?php echo $no; ?></td>
                                <td><?php echo $h->kode ?></td>
                                <td><?php echo $h->tentang; ?></td>
                                <td align="center">
                                    <div class="btn-group">
                                        <a href="#" data-toggle="dropdown" class="btn btn-primary dropdown-toggle"><i class="fa fa-cogs"></i> <span class="caret"></span></a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li>
                                                <a href="<?php echo site_url('master/kodesurat/edit/'.$h->kodesurat_id) ?>"><i class="fa fa-pencil"> Edit</i></a>
                                            </li>
                                            <li>
                                                <a href="<?php echo site_url('master/kodesurat/delete/'.$h->kodesurat_id) ?>" onclick="return confirm('Apakah anda yakin akan menghapus?')"><i class="fa fa-trash-o"></i> Hapus</a>
                                            </li>
                                        </ul>
                                    </div>
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