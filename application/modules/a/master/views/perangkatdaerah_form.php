<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="#">Dashboard</a></li>                    
    <li><a href="#">Data Master</a></li>
    <li>Perangkat Daerah</li>
    <li class="active">Form</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-list-alt"></span> Form Data Perangkat Daerah</h2>
</div>
<!-- END PAGE TITLE -->                

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">                

    <div class="row">
        <div class="col-md-12">

            <?php if ($this->uri->segment(3) == 'add') { ?>
            
                <!-- START DEFAULT FORM -->
                <form class="form-horizontal" action="<?php echo site_url('master/perangkatdaerah/insert') ?>" method="post" enctype="multipart/form-data">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <a href="<?php echo site_url('master/perangkatdaerah') ?>" class="btn btn-default">&laquo; Kembali</a> <br><br>
                    </div>
                    <div class="panel-body">                                                                        
                        
                        <div class="row">
                            
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Nama</label>
                                    <div class="col-md-10">
                                        <input type="text" name="nama_pd" class="form-control" required />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Kode</label>
                                    <div class="col-md-10">
                                        <input type="text" name="kode_pd" class="form-control" required />
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Alamat</label>
                                    <div class="col-md-10">
                                        <input type="text" name="alamat" class="form-control" required />
                                    </div>
                                </div>

                                <div class="form-group">                                        
                                    <label class="col-md-2 control-label">Telepon</label>
                                    <div class="col-md-10">
                                        <input type="text" name="telp" class="form-control" required />
                                    </div>
                                </div>

                                <div class="form-group">                                        
                                    <label class="col-md-2 control-label">Email</label>
                                    <div class="col-md-10">
                                        <input type="email" name="email" class="form-control" required />
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Website</label>
                                    <div class="col-md-10">
                                        <input type="text" name="alamat_website" class="form-control" required />
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
                foreach ($perangkatdaerah as $key => $e) {
            ?>

                <!-- START DEFAULT FORM -->
                <form class="form-horizontal" action="<?php echo site_url('master/perangkatdaerah/update') ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="opd_id" value="<?php echo $e->opd_id ?>" />
                <div class="panel panel-default">
                    <div class="panel-body">
                        <a href="<?php echo site_url('master/perangkatdaerah') ?>" class="btn btn-default">&laquo; Kembali</a> <br><br>
                    </div>
                    <div class="panel-body">                                                                        
                        
                        <div class="row">
                            
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Nama</label>
                                    <div class="col-md-10">
                                        <input type="text" name="nama_pd" class="form-control" value="<?php echo $e->nama_pd ?>" required />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Kode</label>
                                    <div class="col-md-10">
                                        <input type="text" name="kode_pd" class="form-control" value="<?php echo $e->kode_pd ?>" required />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Alamat</label>
                                    <div class="col-md-10">
                                        <input type="text" name="alamat" class="form-control" value="<?php echo $e->alamat ?>" required />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Telepon</label>
                                    <div class="col-md-10">
                                        <input type="text" name="telp" class="form-control" value="<?php echo $e->telp ?>" required />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Email</label>
                                    <div class="col-md-10">
                                        <input type="email" name="email" class="form-control" value="<?php echo $e->email ?>" required />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Alamat Website</label>
                                    <div class="col-md-10">
                                        <input type="text" name="alamat_website" class="form-control" value="<?php echo $e->alamat_website ?>" required />
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