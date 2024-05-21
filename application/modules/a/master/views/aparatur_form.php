<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="#">Dashboard</a></li>                    
    <li><a href="#">Data Master</a></li>
    <li>Aparatur</li>
    <li class="active">Form</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-user"></span> Form Data Aparatur</h2>
</div>
<!-- END PAGE TITLE -->                

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">                

    <div class="row">
        <div class="col-md-12">

            <?php if ($this->uri->segment(3) == 'add') { ?>
            
                <!-- START DEFAULT FORM -->
                <form class="form-horizontal" action="<?php echo site_url('master/aparatur/insert') ?>" method="post" enctype="multipart/form-data">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <a href="<?php echo site_url('master/aparatur') ?>" class="btn btn-default">&laquo; Kembali</a> <br><br>
                    </div>
                    <div class="panel-body">                                                                        
                        
                        <div class="row">
                            
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Nip</label>
                                    <div class="col-md-10">
                                        <input type="text" name="nip" class="form-control" required />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Nama</label>
                                    <div class="col-md-10">
                                        <input type="text" name="nama" class="form-control" required />
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Jabatan</label>
                                    <div class="col-md-10">
                                        <select name="jabatan_id" class="form-control select" data-live-search="true" required>
                                            <option value="">Pilih Jabatan</option>
                                            <?php foreach ($jabatan as $key => $h) { ?> 
                                                <option value="<?php echo $h->jabatan_id ?>"><?php echo $h->nama_jabatan; ?> - <?php echo $h->nama_pd; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">                                        
                                    <label class="col-md-2 control-label">Eselon</label>
                                    <div class="col-md-10">
                                        <input type="text" name="eselon" class="form-control" />
                                    </div>
                                </div>

                                <div class="form-group">                                        
                                    <label class="col-md-2 control-label">Pangkat</label>
                                    <div class="col-md-10">
                                        <input type="text" name="pangkat" class="form-control" />
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Golongan</label>
                                    <div class="col-md-10">
                                        <input type="text" name="golongan" class="form-control" />
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
                foreach ($aparatur as $key => $e) {
            ?>

                <!-- START DEFAULT FORM -->
                <form class="form-horizontal" action="<?php echo site_url('master/aparatur/update') ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="aparatur_id" value="<?php echo $e->aparatur_id ?>" />
                <div class="panel panel-default">
                    <div class="panel-body">
                        <a href="<?php echo site_url('master/aparatur') ?>" class="btn btn-default">&laquo; Kembali</a> <br><br>
                    </div>
                    <div class="panel-body">                                                                        
                        
                        <div class="row">
                            
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Nip</label>
                                    <div class="col-md-10">
                                        <input type="text" name="nip" class="form-control" value="<?php echo $e->nip ?>" required />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Nama</label>
                                    <div class="col-md-10">
                                        <input type="text" name="nama" class="form-control" value="<?php echo $e->nama ?>" required />
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Jabatan</label>
                                    <div class="col-md-10">
                                        <select name="jabatan_id" class="form-control select" data-live-search="true" required />
                                            <option value="">Pilih Jabatan</option>
                                            <?php foreach ($jabatan as $key => $h) { ?> 
                                                <option value="<?php echo $h->jabatan_id ?>" <?php if ($h->jabatan_id == $e->jabatan_id) { echo "selected"; } ?>>
                                                    <?php echo $h->nama_jabatan; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Eselon</label>
                                    <div class="col-md-10">
                                        <input type="text" name="eselon" class="form-control" value="<?php echo $e->eselon ?>" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Pangkat</label>
                                    <div class="col-md-10">
                                        <input type="text" name="pangkat" class="form-control" value="<?php echo $e->pangkat ?>" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Golongan</label>
                                    <div class="col-md-10">
                                        <input type="text" name="golongan" class="form-control" value="<?php echo $e->golongan ?>" />
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
                }elseif ($this->uri->segment(3) == 'addadmin') {
            ?>

                <!-- START DEFAULT FORM -->
                <form class="form-horizontal" action="<?php echo site_url('master/aparatur/insertadmin') ?>" method="post" enctype="multipart/form-data">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <a href="<?php echo site_url('master/aparatur') ?>" class="btn btn-default">&laquo; Kembali</a> <br><br>
                    </div>
                    <div class="panel-body">                                                                        
                        
                        <div class="row">
                            
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Nama</label>
                                    <div class="col-md-10">
                                        <input type="text" name="nama" class="form-control" required />
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Jabatan</label>
                                    <div class="col-md-10">
                                        <select name="jabatan_id" class="form-control select" data-live-search="true">
                                            <option value="">Pilih Jabatan</option>
                                            <?php foreach ($jabatan as $key => $h) { ?> 
                                                <option value="<?php echo $h->jabatan_id ?>"><?php echo $h->nama_jabatan; ?></option>
                                            <?php } ?>
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
                }elseif ($this->uri->segment(3) == 'editadmin') {
                    foreach ($aparatur as $key => $e) {
            ?>

                <!-- START DEFAULT FORM -->
                <form class="form-horizontal" action="<?php echo site_url('master/aparatur/updateadmin') ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="aparatur_id" value="<?php echo $e->aparatur_id ?>" />
                <div class="panel panel-default">
                    <div class="panel-body">
                        <a href="<?php echo site_url('master/aparatur') ?>" class="btn btn-default">&laquo; Kembali</a> <br><br>
                    </div>
                    <div class="panel-body">                                                                        
                        
                        <div class="row">
                            
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Nama</label>
                                    <div class="col-md-10">
                                        <input type="text" name="nama" class="form-control" value="<?php echo $e->nama ?>" required />
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Jabatan</label>
                                    <div class="col-md-10">
                                        <select name="jabatan_id" class="form-control select" data-live-search="true" />
                                            <option value="">Pilih Jabatan</option>
                                            <?php foreach ($jabatan as $key => $h) { ?> 
                                                <option value="<?php echo $h->jabatan_id ?>" <?php if ($h->jabatan_id == $e->jabatan_id) { echo "selected"; } ?>>
                                                    <?php echo $h->nama_jabatan; ?>
                                                </option>
                                            <?php } ?>
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
                    }
                }
            ?>

        </div>
    </div>
</div>