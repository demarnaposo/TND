<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo site_url('home/dashboard') ?>">Dashboard</a></li> 
    <li><a href="<?php echo site_url('home/profil') ?>">Profil</a></li>
    <li class="active">Form</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-user"></span> Form Data Profil</h2>
</div>
<!-- END PAGE TITLE -->                

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">                

    <div class="row">
        <div class="col-md-12">

            <?php foreach ($profil as $key => $e) { ?>

                <!-- START DEFAULT FORM -->
                <form class="form-horizontal" action="<?php echo site_url('home/profil/update') ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="users_id" value="<?php echo $e->users_id ?>" />
                <div class="panel panel-default">
                    <div class="panel-body">
                        <a href="javascript:history.back()" class="btn btn-default">&laquo; Kembali</a> <br><br>
                    </div>
                    <div class="panel-body">                                                                        
                        
                        <div class="row">
                            
                            <div class="col-md-12">
                                
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Username</label>
                                    <div class="col-md-10">
                                        <input type="text" name="username" class="form-control" value="<?php echo $e->username ?>" required />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Password</label>
                                    <div class="col-md-10">
                                        <input type="password" name="password" class="form-control" />
                                        <span class="help-block">Kosongkan password jika tidak ingin diganti</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Email</label>
                                    <div class="col-md-10">
                                        <input type="email" name="email" class="form-control" value="<?php echo $e->email ?>" required />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Telp</label>
                                    <div class="col-md-10">
                                        <input type="text" name="telp" class="form-control" value="<?php echo $e->telp ?>" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Foto Lama</label>
                                    <div class="col-md-10">
                                        <?php if (empty($e->foto)) { ?>
                                            <img src="<?php echo base_url('assets/imagesusers/user-default.png') ?>" class="img-responsive img-text" width="100px" />
                                        <?php }else{ ?>
                                            <img src="<?php echo base_url('assets/imagesusers/'.$e->foto) ?>" class="img-responsive img-text" width="100px" />
                                        <?php } ?>
                                        <span class="help-block">Kosongkan Foto Jika tidak ingin diganti</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Foto</label>
                                    <div class="col-md-10">
                                        <input type="file" name="foto" class="form-control" accept="image/*" />
                                        <span class="help-block">Maksimal File 2MB</span>
                                    </div>
                                </div>

                            </div>
                            
                        </div>

                    </div>
                    <div class="panel-footer">
                        <button class="btn btn-danger" type="reset">Bersihkan Form</button>   
                        <button class="btn btn-primary pull-right" type="submit">Simpan</button>
                    </div>
                </div>
                </form>
                <!-- END DEFAULT FORM -->

            <?php } ?>

        </div>
    </div>
</div>