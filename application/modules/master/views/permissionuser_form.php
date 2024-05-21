<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="#">Beranda</a></li>                    
    <li><a href="#">Data Master</a></li>
    <li>Permission User</li>
    <li class="active">Form</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-chain"></span> Formulir Data Permission User</h2>
</div>
<!-- END PAGE TITLE -->                

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">                

    <div class="row">
        <div class="col-md-12">

            <?php if ($this->uri->segment(3) == 'add') { ?>
            
                <!-- START DEFAULT FORM -->
                <form class="form-horizontal" action="<?php echo site_url('master/PermissionUser/insert') ?>" method="post" enctype="multipart/form-data">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <a href="<?php echo site_url('master/permissionuser') ?>" class="btn btn-default">&laquo; Kembali</a> <br><br>
                    </div>
                    <div class="panel-body">                                                                        
                        
                        <div class="row">
                            
                            <div class="col-md-12">

                            <div class="form-group">
                                    <label class="col-md-2 control-label">User</label>
                                    <div class="col-md-10">
                                        <select name="users_id" class="form-control select" data-live-search="true" required>
                                            <option value="">Pilih User</option>
                                            <?php foreach ($users as $key => $h) { ?> 
                                                <option value="<?php echo $h->users_id ?>"><?php echo $h->username; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            <div class="form-group">
                                    <label class="col-md-2 control-label">Status Permission</label>
                                    <div class="col-md-10">
                                        <select name="status" class="form-control select" data-live-search="true" required>
                                            <option value="">Pilih Status</option>
                                            <option value="0">Tidak Aktif</option>
                                            <option value="1">Aktif</option>
                                        </select>
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
                foreach ($permissionuser as $key => $e) {
            ?>

                <!-- START DEFAULT FORM -->
                <form class="form-horizontal" action="<?php echo site_url('master/PermissionUser/update') ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $e->id ?>" />
                <div class="panel panel-default">
                    <div class="panel-body">
                        <a href="<?php echo site_url('master/PermissionUser') ?>" class="btn btn-default">&laquo; Kembali</a> <br><br>
                    </div>
                    <div class="panel-body">                                                                        
                        
                        <div class="row">
                            
                            <div class="col-md-12">

                            <div class="form-group">
                                    <label class="col-md-2 control-label">User</label>
                                    <div class="col-md-10">
                                        <select name="users_id" class="form-control select" data-live-search="true" required />
                                            <option value="">Pilih User</option>
                                            <?php foreach ($users as $key => $h) { ?> 
                                                <option value="<?php echo $h->users_id ?>" <?php if ($h->users_id == $e->users_id) { echo "selected"; } ?>>
                                                    <?php echo $h->username; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Status</label>
                                    <div class="col-md-10">
                                        <select name="status" class="form-control select" data-live-search="true" required />
                                            <option value="">Pilih Status</option>
                                                <?php if ($e->status == 1)
                                                {
                                                    echo "<option value='1' selected>Aktif</option>";
                                                }   
                                                    else { echo "<option value='1'>Aktif</option>";
                                                }
                                                ?>
						                        <?php if ($e->status == 0)
                                                {
                                                    echo "<option value='0' selected>Tidak Aktif</option>";
                                                }    
                                                    else { echo "<option value='0'>Tidak Aktif</option>";
                                                }
                                                ?>
                                        </select>
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