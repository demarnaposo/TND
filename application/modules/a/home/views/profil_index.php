<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo site_url('home/dashboard') ?>">Dashboard</a></li>
    <li class="active">Profil</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-user"></span> Data Profil</h2>
</div>
<!-- END PAGE TITLE -->                

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">                

    <div class="row">

        <div class="col-md-12">

            <?php foreach ($profil as $key => $h) { ?>

            <div class="panel panel-default">
                <div class="panel-body profile" style="background: #000" style="background: url('assets/imagesusers/'<?php echo $h->foto ?>) center center no-repeat;">
                    <div class="profile-image">
                        <?php if (empty($h->foto)) { ?>
                            <img src="<?php echo base_url('assets/imagesusers/user-default.png') ?>" alt="<?php echo $h->nama ?>"/>
                        <?php }else{ ?>
                            <img src="<?php echo base_url('assets/imagesusers/'.$h->foto) ?>" alt="<?php echo $h->nama ?>"/>
                        <?php } ?>
                    </div>
                    <div class="profile-data">
                        <div class="profile-data-name"><?php echo $h->nama; ?></div>
                        <div class="profile-data-title" style="color: #FFF;"><?php echo $h->nama_jabatan; ?></div>
                        <div class="profile-data-title" style="color: #FFF;"><?php echo $h->nip; ?></div>
                        <div class="profile-data-title" style="color: #FFF;"><?php echo $h->eselon; ?></div>
                        <div class="profile-data-title" style="color: #FFF;"><?php echo $h->pangkat; ?></div>
                        <div class="profile-data-title" style="color: #FFF;"><?php echo $h->golongan; ?></div>
                    </div>
                </div>                                
                <div class="panel-body">                                    
                    <div class="row">
                        <table class="table datatable table-responsive table-bordered">
                            <thead>
                                <tr>
                                    <th>USERNAME</th>
                                    <th>EMAIL</th>
                                    <th>TELP</th>
                                    <th>AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($profil as $key => $h) { ?>
                                <tr>
                                    <td><?php echo $h->username ?></td>
                                    <td><?php echo $h->email; ?></td>
                                    <td><?php echo $h->telp; ?></td>
                                    <td align="center">
                                        <div class="btn-group">
                                            <a href="#" data-toggle="dropdown" class="btn btn-primary dropdown-toggle"><i class="fa fa-cogs"></i> <span class="caret"></span></a>
                                            <ul class="dropdown-menu" role="menu">
                                                <li>
                                                    <a href="<?php echo site_url('home/profil/edit/'.$h->users_id) ?>"><i class="fa fa-pencil"> Edit</i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>                            

            <?php } ?>
                                    
        </div>

    </div>
</div>