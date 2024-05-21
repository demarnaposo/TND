<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="#">Dashboard</a></li>                    
    <li><a href="#">Data Master</a></li>
    <li>Users</li>
    <li class="active">Form</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-users"></span> Form Data Users</h2>
</div>
<!-- END PAGE TITLE -->                

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">                

    <div class="row">
        <div class="col-md-12">

            <?php if ($this->uri->segment(3) == 'add') { ?>
            
                <!-- START DEFAULT FORM -->
                <form class="form-horizontal" action="<?php echo site_url('master/users/insert') ?>" method="post" enctype="multipart/form-data">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <a href="<?php echo site_url('master/users') ?>" class="btn btn-default">&laquo; Kembali</a> <br><br>
                    </div>
                    <div class="panel-body">                                                                        
                        
                        <div class="row">
                            
                            <div class="col-md-12">
                                
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Aparatur</label>
                                    <div class="col-md-10">
                                        <select name="aparatur_id" class="form-control select" data-live-search="true" required>
                                            <option value="">Pilih Aparatur</option>
                                            <?php foreach ($aparatur as $key => $h) { ?> 
                                                <option value="<?php echo $h->aparatur_id ?>"><?php echo $h->nama; ?> - <?php echo $h->nama_jabatan; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Email</label>
                                    <div class="col-md-10">
                                        <input type="email" name="email" class="form-control" required />
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Level</label>
                                    <div class="col-md-10">
                                        <select name="level_id" class="form-control select" data-live-search="true" required>
                                            <option value="">Pilih Level</option>
                                            <?php foreach ($level as $key => $h) { ?> 
                                                <option value="<?php echo $h->level_id ?>"><?php echo $h->level; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">                                        
                                    <label class="col-md-2 control-label">Telp</label>
                                    <div class="col-md-10">
                                        <input type="text" name="telp" class="form-control" />
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

            <?php 
                }elseif ($this->uri->segment(3) == 'edit') { 
                foreach ($users as $key => $e) {
            ?>

                <!-- START DEFAULT FORM -->
                <form class="form-horizontal" action="<?php echo site_url('master/users/update') ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="users_id" value="<?php echo $e->users_id ?>" />
                <div class="panel panel-default">
                    <div class="panel-body">
                        <a href="<?php echo site_url('master/users') ?>" class="btn btn-default">&laquo; Kembali</a> <br><br>
                    </div>
                    <div class="panel-body">                                                                        
                        
                        <div class="row">
                            
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Aparatur</label>
                                    <div class="col-md-10">
                                        <select name="aparatur_id" class="form-control select" data-live-search="true" required />
                                            <option value="">Pilih Aparatur</option>
                                            <?php foreach ($aparatur as $key => $h) { ?> 
                                                <option value="<?php echo $h->aparatur_id ?>" <?php if ($h->aparatur_id == $e->aparatur_id) { echo "selected"; } ?>>
                                                    <?php echo $h->nama; ?> - <?php echo $h->nama_jabatan; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Email</label>
                                    <div class="col-md-10">
                                        <input type="email" name="email" class="form-control" value="<?php echo $e->email ?>" required />
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Level</label>
                                    <div class="col-md-10">
                                        <select name="level_id" class="form-control select" data-live-search="true" required />
                                            <option value="">Pilih Level</option>
                                            <?php foreach ($level as $key => $h) { ?> 
                                                <option value="<?php echo $h->level_id ?>" <?php if ($h->level_id == $e->level_id) { echo "selected"; } ?>>
                                                    <?php echo $h->level; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
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

            <?php 
                } 
            }elseif ($this->uri->segment(3) == 'adminadd') { 
            ?>
            
            <!-- START DEFAULT FORM -->
            <form class="form-horizontal" action="<?php echo site_url('master/users/admininsert') ?>" method="post" enctype="multipart/form-data">
            <div class="panel panel-default">
                <div class="panel-body">
                    <a href="<?php echo site_url('master/users') ?>" class="btn btn-default">&laquo; Kembali</a> <br><br>
                </div>
                <div class="panel-body">                                                                        
                    
                    <div class="row">
                        
                        <div class="col-md-12">

                            <div class="form-group">
                                <label class="col-md-2 control-label">Aparatur Admin</label>
                                <div class="col-md-10">
                                    <select name="aparatur_id" class="form-control select" data-live-search="true">
                                        <option value="">Pilih Aparatur Admin</option>
                                        <?php foreach ($aparatur as $key => $h) { ?> 
                                            <option value="<?php echo $h->aparatur_id ?>"><?php echo $h->nama; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Username</label>
                                <div class="col-md-10">
                                    <input type="text" name="username" class="form-control" required />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Email</label>
                                <div class="col-md-10">
                                    <input type="email" name="email" class="form-control" required />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Level</label>
                                <div class="col-md-10">
                                    <select name="level_id" class="form-control select" data-live-search="true" required>
                                        <option value="">Pilih Level</option>
                                        <?php foreach ($level as $key => $h) { ?> 
                                            <option value="<?php echo $h->level_id ?>"><?php echo $h->level; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">                                        
                                <label class="col-md-2 control-label">Telp</label>
                                <div class="col-md-10">
                                    <input type="text" name="telp" class="form-control" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Password</label>
                                <div class="col-md-10">
                                    <input type="password" name="password" class="form-control" />
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

            <?php 
                }elseif ($this->uri->segment(3) == 'adminedit') { 
                    foreach ($users as $key => $e) {
            ?>
            
            <!-- START DEFAULT FORM -->
            <form class="form-horizontal" action="<?php echo site_url('master/users/adminupdate') ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="users_id" value="<?php echo $e->users_id ?>" />
            <input type="hidden" name="aparatur_id" value="<?php echo $e->aparatur_id ?>" />
            <div class="panel panel-default">
                <div class="panel-body">
                    <a href="<?php echo site_url('master/users') ?>" class="btn btn-default">&laquo; Kembali</a> <br><br>
                </div>
                <div class="panel-body">                                                                        
                    
                    <div class="row">
                        
                        <div class="col-md-12">

                            <div class="form-group">
                                <label class="col-md-2 control-label">Aparatur Admin</label>
                                <div class="col-md-10">
                                    <select name="aparatur_id" class="form-control select" data-live-search="true" required />
                                        <option value="">Pilih Aparatur Admin</option>
                                        <?php foreach ($aparatur as $key => $h) { ?> 
                                            <option value="<?php echo $h->aparatur_id ?>" <?php if ($h->aparatur_id == $e->aparatur_id) { echo "selected"; } ?>>
                                                <?php echo $h->nama; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-md-2 control-label">Username</label>
                                <div class="col-md-10">
                                    <input type="text" name="username" class="form-control" value="<?php echo $e->username ?>" required />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Email</label>
                                <div class="col-md-10">
                                    <input type="email" name="email" class="form-control" value="<?php echo $e->email ?>" required />
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-md-2 control-label">Level</label>
                                <div class="col-md-10">
                                    <select name="level_id" class="form-control select" data-live-search="true" required />
                                        <option value="">Pilih Level</option>
                                        <?php foreach ($level as $key => $h) { ?> 
                                            <option value="<?php echo $h->level_id ?>" <?php if ($h->level_id == $e->level_id) { echo "selected"; } ?>>
                                                <?php echo $h->level; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Telp</label>
                                <div class="col-md-10">
                                    <input type="text" name="telp" class="form-control" value="<?php echo $e->telp ?>" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Password</label>
                                <div class="col-md-10">
                                    <input type="password" name="password" class="form-control" />
                                    <span class="help-block">Kosongkan Password Jika tidak ingin diganti</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Foto Lama</label>
                                <div class="col-md-10">
                                    <?php if (empty($h->foto)) { ?>
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

            <?php } } ?>

        </div>
    </div>
</div>