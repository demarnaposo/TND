<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="#">Dashboard</a></li>                    
    <li><a href="#">Data Master</a></li>
    <li>Kode Surat</li>
    <li class="active">Form</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-code-fork"></span> Form Data Kode Surat</h2>
</div>
<!-- END PAGE TITLE -->                

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">                

    <div class="row">
        <div class="col-md-12">

            <?php if ($this->uri->segment(3) == 'add') { ?>
            
                <!-- START DEFAULT FORM -->
                <form class="form-horizontal" action="<?php echo site_url('master/kodesurat/insert') ?>" method="post" enctype="multipart/form-data">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <a href="<?php echo site_url('master/kodesurat') ?>" class="btn btn-default">&laquo; Kembali</a> <br><br>
                    </div>
                    <div class="panel-body">                                                                        
                        
                        <div class="row">
                            
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Kode</label>
                                    <div class="col-md-10">
                                        <input type="text" name="kode" class="form-control" required />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Tentang</label>
                                    <div class="col-md-10">
                                        <input type="text" name="tentang" class="form-control" required />
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
                foreach ($kodesurat as $key => $e) {
            ?>

                <!-- START DEFAULT FORM -->
                <form class="form-horizontal" action="<?php echo site_url('master/kodesurat/update') ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="kodesurat_id" value="<?php echo $e->kodesurat_id ?>" />
                <div class="panel panel-default">
                    <div class="panel-body">
                        <a href="<?php echo site_url('master/kodesurat') ?>" class="btn btn-default">&laquo; Kembali</a> <br><br>
                    </div>
                    <div class="panel-body">                                                                        
                        
                        <div class="row">
                            
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Kode</label>
                                    <div class="col-md-10">
                                        <input type="text" name="kode" class="form-control" value="<?php echo $e->kode ?>" required />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Tentang</label>
                                    <div class="col-md-10">
                                        <input type="text" name="tentang" class="form-control" value="<?php echo $e->tentang ?>" required />
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