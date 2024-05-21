<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="#">Dashboard</a></li>                    
    <li><a href="#">Data Master</a></li>
    <li>Jabatan</li>
    <li class="active">Form</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-bookmark"></span> Form Data Jabatan</h2>
</div>
<!-- END PAGE TITLE -->                

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">                

    <div class="row">
        <div class="col-md-12">

            <?php if ($this->uri->segment(3) == 'add') { ?>
            
                <!-- START DEFAULT FORM -->
                <form class="form-horizontal" action="<?php echo site_url('master/jabatan/insert') ?>" method="post" enctype="multipart/form-data">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <a href="<?php echo site_url('master/jabatan') ?>" class="btn btn-default">&laquo; Kembali</a> <br><br>
                    </div>
                    <div class="panel-body">                                                                        
                        
                        <div class="row">
                            
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Perangkat Daerah</label>
                                    <div class="col-md-10">
                                        <select name="opd_id" class="form-control select" data-live-search="true">
                                            <option value="">Pilih Perangkat Daerah</option>
                                            <?php foreach ($opd as $key => $h) { ?> 
                                                <option value="<?php echo $h->opd_id ?>"><?php echo $h->nama_pd; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Nama Jabatan</label>
                                    <div class="col-md-10">
                                        <input type="text" name="nama_jabatan" class="form-control" required />
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Atasan</label>
                                    <div class="col-md-10">
                                        <select name="atasan_id" class="form-control select" data-live-search="true">
                                            <option value="">Pilih Atasan</option>
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
                }elseif ($this->uri->segment(3) == 'edit') { 
                foreach ($jabatan as $key => $e) {
            ?>

                <!-- START DEFAULT FORM -->
                <form class="form-horizontal" action="<?php echo site_url('master/jabatan/update') ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="jabatan_id" value="<?php echo $e->jabatan_id ?>" />
                <div class="panel panel-default">
                    <div class="panel-body">
                        <a href="<?php echo site_url('master/jabatan') ?>" class="btn btn-default">&laquo; Kembali</a> <br><br>
                    </div>
                    <div class="panel-body">                                                                        
                        
                        <div class="row">
                            
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Perangkat Daerah</label>
                                    <div class="col-md-10">
                                        <select name="opd_id" class="form-control select" data-live-search="true" />
                                            <option value="">Pilih Perangkat Daerah</option>
                                            <?php foreach ($opd as $key => $h) { ?> 
                                                <option value="<?php echo $h->opd_id ?>" <?php if ($h->opd_id == $e->opd_id) { echo "selected"; } ?> >
                                                    <?php echo $h->nama_pd; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Nama Jabatan</label>
                                    <div class="col-md-10">
                                        <input type="text" name="nama_jabatan" class="form-control" value="<?php echo $e->nama_jabatan ?>" required />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Atasan</label>
                                    <div class="col-md-10">
                                        <select name="atasan_id" class="form-control select" data-live-search="true" />
                                            <option value="">Pilih Atasan</option>
                                            <?php foreach ($jabatanoption as $key => $h) { ?> 
                                                <option value="<?php echo $h->jabatan_id ?>" <?php if ($h->jabatan_id == $e->atasan_id) { echo "selected"; } ?>>
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

            <?php } } ?>

        </div>
    </div>
</div>