<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="#">Beranda</a></li>
    <li><a href="#">Data Master</a></li>
    <li class="active">Perangkat Eksternal</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-external-link"></span> Data Perangkat Eksternal</h2>
</div>
<!-- END PAGE TITLE -->                

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">                

    <div class="row">
        <div class="col-md-12">

            <!-- START DEFAULT DATATABLE -->
            <div class="panel panel-default">
                <div class="panel-body">
                    <a href="<?php echo site_url('master/eksternal/add') ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Tambah Data</a> <br><br>
                    
		    <div class="table-responsive">
		    <table class="table datatable table-bordered">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>NAMA</th>
                                <th>EMAIL</th>
				<th>ALAMAT</th>
                                <th>TEMPAT (NAMA KOTA/KAB)</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no = 1; 
                                foreach ($eksternal as $key => $h) {
                            ?>
                            <tr>
                                <td><?php echo $no; ?></td>
                                <td><?php echo $h->nama ?></td>
                                <td><?php echo $h->email ?></td>
				<td><?php echo $h->alamat_eksternal ?></td>
                                <td><?php echo $h->tempat ?></td>
                                <td align="center">
                                    <div class="btn-group">
                                        <a href="#" data-toggle="dropdown" class="btn btn-primary dropdown-toggle"><i class="fa fa-cogs"></i> <span class="caret"></span></a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li>
                                                <a href="<?php echo site_url('master/eksternal/edit/'.$h->id) ?>"><i class="fa fa-pencil"> Ubah</i></a>
                                            </li>
                                            <li>
                                                <a href="<?php echo site_url('master/eksternal/delete/'.$h->id) ?>" onclick="return confirm('Apakah anda yakin akan menghapus?')"><i class="fa fa-trash-o"></i> Hapus</a>
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
            </div>
            <!-- END DEFAULT DATATABLE -->

        </div>
    </div>
</div>