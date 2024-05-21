<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="#">Beranda</a></li>
    <li><a href="#">Data Master</a></li>
    <li class="active">Pengguna</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-users"></span> Data Pengguna</h2>
</div>
<!-- END PAGE TITLE -->                

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">                

    <div class="row">
        <div class="col-md-12">

            <!-- START TABS -->                                
            <div class="panel panel-default tabs">                            
                <ul class="nav nav-tabs" role="tablist">
                    <li class="active"><a href="#tab-first" role="tab" data-toggle="tab">Data Pengguna Aparatur</a></li>
                    <?php if ($this->session->userdata('level') == 1) { ?>
                        <li><a href="#tab-second" role="tab" data-toggle="tab">Data Pengguna Administrator</a></li>
                    <?php } ?>
                </ul>                            
                <div class="panel-body tab-content">

                    <div class="tab-pane active" id="tab-first">
                        <a href="<?php echo site_url('master/users/add') ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Tambah Data</a> <br><br>
                        
			<div class="table-responsive">
			<table class="table datatable table-bordered">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>FOTO</th>
                                    <th>NIP (NAMA PENGGUNA)</th>
                                    <th>NAMA</th>
                                    <th>EMAIL</th>
                                    <th>AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $no = 1; 
                                    foreach ($users as $key => $h) {
                                ?>
                                <tr>
                                    <td><?php echo $no; ?></td>
                                    <td align="center">
                                        <?php if (empty($h->foto)) { ?>
                                            <img src="<?php echo base_url('assets/imagesusers/user-default.png') ?>" class="img-responsive img-text" width="100px" />
                                        <?php }else{ ?>
                                            <img src="<?php echo base_url('assets/imagesusers/'.$h->foto) ?>" class="img-responsive img-text" width="100px" />
                                        <?php } ?>
                                    </td>
                                    <td><?php echo $h->username; ?></td>
                                    <td><?php echo $h->nama; ?></td>
                                    <td><?php echo $h->email; ?></td>
                                    <td align="center">
                                        <div class="btn-group">
                                            <a href="#" data-toggle="dropdown" class="btn btn-primary dropdown-toggle"><i class="fa fa-cogs"></i> <span class="caret"></span></a>
                                            <ul class="dropdown-menu" role="menu">
                                                <!-- <li>
                                                    <a href="<?php echo site_url('master/users/akses/'.$h->username) ?>"><i class="fa fa-sign-in"> Akses</i></a>
                                                </li> -->
                                                <?php if ($this->session->userdata('level') == 1) { ?>
                                                            <li>
                                                                <a href="<?php echo site_url('master/users/akses/'.$h->username) ?>"><i class="fa fa-sign-in"> Akses</i></a>
                                                            </li>
                                                        <?php } ?>
                                                <li>
                                                    <a href="<?php echo site_url('master/users/edit/'.$h->users_id) ?>"><i class="fa fa-pencil"> Ubah</i></a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo site_url('master/users/delete/'.$h->users_id) ?>" onclick="return confirm('Apakah anda yakin akan menghapus?')"><i class="fa fa-trash-o"></i> Hapus</a>
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
                    
                    <div class="tab-pane" id="tab-second">
                        <a href="<?php echo site_url('master/users/adminadd') ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Tambah Data</a> <br><br>

                        <div class="table-responsive">
			<table class="table datatable table-bordered">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>NAMA PENGGUNA</th>
                                    <th>EMAIL</th>
                                    <th>AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $no = 1; 
                                    foreach ($admin as $key => $h) {
                                ?>
                                <tr>
                                    <td><?php echo $no; ?></td>
                                    <td><?php echo $h->username; ?></td>
                                    <td><?php echo $h->email; ?></td>
                                    <td align="center">
                                        <div class="btn-group">
                                            <a href="#" data-toggle="dropdown" class="btn btn-primary dropdown-toggle"><i class="fa fa-cogs"></i> <span class="caret"></span></a>
                                            <ul class="dropdown-menu" role="menu">
                                            <li>
                                                    <a href="<?php echo site_url('master/users/akses/'.$h->username) ?>"><i class="fa fa-sign-in"> Akses</i></a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo site_url('master/users/adminedit/'.$h->users_id) ?>"><i class="fa fa-pencil"> Edit</i></a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo site_url('master/users/admindelete/'.$h->users_id) ?>" onclick="return confirm('Apakah anda yakin akan menghapus?')"><i class="fa fa-trash-o"></i> Hapus</a>
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
            </div>                                                   
            <!-- END TABS -->

        </div>
    </div>
</div>