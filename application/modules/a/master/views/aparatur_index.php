<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="#">Dashboard</a></li>
    <li><a href="#">Data Master</a></li>
    <li class="active">Aparatur</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-user"></span> Data Aparatur</h2>
</div>
<!-- END PAGE TITLE -->                

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">                

    <div class="row">
        <div class="col-md-12">

            <!-- START TABS -->                                
            <div class="panel panel-default tabs">                            
                <ul class="nav nav-tabs" role="tablist">
                    <li class="active"><a href="#tab-first" role="tab" data-toggle="tab">Data Aparatur</a></li>
                    <?php if ($this->session->userdata('level') == 1) { ?>
                        <li><a href="#tab-second" role="tab" data-toggle="tab">Data Aparatur Administrator</a></li>
                    <?php } ?>
                </ul>                            
                <div class="panel-body tab-content">

                    <div class="tab-pane active" id="tab-first">
                        <a href="<?php echo site_url('master/aparatur/add') ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Tambah Data</a> <br><br>
                        <table class="table datatable table-responsive table-bordered">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>NIP</th>
                                    <th>NAMA</th>
                                    <th>PERANGKAT DAERAH</th>
                                    <th>JABATAN</th>
                                    <th>AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $no = 1; 
                                    foreach ($aparatur as $key => $h) {
                                ?>
                                <tr>
                                    <td><?php echo $no; ?></td>
                                    <td><?php echo $h->nip ?></td>
                                    <td><?php echo $h->nama; ?></td>
                                    <td><?php echo $h->nama_pd; ?></td>
                                    <td><?php echo $h->nama_jabatan; ?></td>
                                    <td align="center">
                                        <div class="btn-group">
                                            <a href="#" data-toggle="dropdown" class="btn btn-primary dropdown-toggle"><i class="fa fa-cogs"></i> <span class="caret"></span></a>
                                            <ul class="dropdown-menu" role="menu">
                                                <li>
                                                    <a href="<?php echo site_url('master/aparatur/edit/'.$h->aparatur_id) ?>"><i class="fa fa-pencil"> Edit</i></a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo site_url('master/aparatur/delete/'.$h->aparatur_id) ?>" onclick="return confirm('Apakah anda yakin akan menghapus?')"><i class="fa fa-trash-o"></i> Hapus</a>
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

                    <div class="tab-pane" id="tab-second">
                        <a href="<?php echo site_url('master/aparatur/addadmin') ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Tambah Data</a> <br><br>
                        <table class="table datatable table-responsive table-bordered">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>NAMA</th>
                                    <th>PERANGKAT DAERAH</th>
                                    <th>JABATAN</th>
                                    <th>AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $no = 1; 
                                    foreach ($aparaturadmin as $key => $h) {
                                ?>
                                <tr>
                                    <td><?php echo $no; ?></td>
                                    <td><?php echo $h->nama; ?></td>
                                    <td><?php echo $h->nama_pd; ?></td>
                                    <td><?php echo $h->nama_jabatan; ?></td>
                                    <td align="center">
                                        <div class="btn-group">
                                            <a href="#" data-toggle="dropdown" class="btn btn-primary dropdown-toggle"><i class="fa fa-cogs"></i> <span class="caret"></span></a>
                                            <ul class="dropdown-menu" role="menu">
                                                <li>
                                                    <a href="<?php echo site_url('master/aparatur/editadmin/'.$h->aparatur_id) ?>"><i class="fa fa-pencil"> Edit</i></a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo site_url('master/aparatur/deleteadmin/'.$h->aparatur_id) ?>" onclick="return confirm('Apakah anda yakin akan menghapus?')"><i class="fa fa-trash-o"></i> Hapus</a>
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
            <!-- END TABS -->

        </div>
    </div>
</div>