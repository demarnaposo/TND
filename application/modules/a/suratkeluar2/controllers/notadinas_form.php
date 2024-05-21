<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo site_url('home/dashboard') ?>">Dashboard</a></li>                    
    <li><a href="javascript:void(0)">Naskah Dinas Surat</a></li>
    <li><a href="<?php echo site_url('suratkeluar/notadinas') ?>">Surat Nota Dinas</a></li>
    <li class="active">Form</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-envelope"></span> Form Surat Nota Dinas</h2>
</div>
<!-- END PAGE TITLE -->                

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">                

    <div class="row"> 
        <div class="col-md-12">

            <?php if ($this->uri->segment(3) == 'add') { ?>
            
                <!-- START DEFAULT FORM -->
                <form class="form-horizontal" action="<?php echo site_url('suratkeluar/notadinas/insert') ?>" method="post" enctype="multipart/form-data">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <a href="javascript:history.back()" class="btn btn-default">&laquo; Kembali</a> <br><br>
                    </div>
                    <div class="panel-body">                                                                        
                        
                        <div class="row">
                            
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Kop Surat</label>
                                    <div class="col-md-10">
                                        <select name="kop_id" class="form-control select" data-live-search="true">
                                            <option value="">Pilih Kop Surat</option>
                                            <?php foreach ($kop as $key => $h) { ?> 
                                                <option value="<?php echo $h->kop_id ?>"><?php echo $h->nama; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Kepada</label>
                                    <div class="col-md-10">                           
                                        <select name="kepada" class="form-control select 2" data-live-search="true">
                                            <option value="">-- Pilih --</option>
                                            <?php foreach ($jabatan as $key => $h) { ?> 
                                                <option value="<?php echo $h->jabatan_id ?>"><?php echo $h->nama_jabatan; ?></option>
                                            <?php } ?>
                                        </select>   
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Dari</label>
                                    <div class="col-md-10">                           
                                        <select name="dari" class="form-control select 2" data-live-search="true">
                                            <option value="">-- Pilih --</option>
                                            <?php foreach ($jabatan as $key => $h) { ?> 
                                                <option value="<?php echo $h->jabatan_id ?>"><?php echo $h->nama_jabatan; ?></option>
                                            <?php } ?>
                                        </select>   
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12"><br></div>

                            <div class="col-md-12"> 

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Tanggal</label>
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                            <input type="text" name="tanggal" class="form-control datepicker" value="<?php echo date('Y-m-d') ?>"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Kode Surat</label>
                                    <div class="col-md-10">
                                        <select name="kodesurat_id" class="form-control select" data-live-search="true">
                                            <option value="">Pilih Kode Surat</option>
                                            <?php foreach ($kodesurat as $key => $h) { ?> 
                                                <option value="<?php echo $h->kodesurat_id ?>"><?php echo $h->kode; ?> - <?php echo substr($h->tentang, 0,130); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Sifat</label>
                                    <div class="col-md-10">                                                                                
                                        <select class="form-control select" name="sifat" data-live-search="true" >
                                            <option>Pilih Sifat</option>
                                            <option value="notadinas">Biasa</option>
                                            <option value="Rahasia">Rahasia</option>
                                            <option value="Segera">Segera</option>
                                            <option value="Sangat Segera">Sangat Segera</option>
                                            <option value="Rahasia dan Segera">Rahasia dan Segera</option>
                                            <option value="Rahasia dan Sangat Segera">Rahasia dan Sangat Segera</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">                                        
                                    <label class="col-md-2 control-label">Lampiran</label>
                                    <div class="col-md-10">
                                        <input type="text" name="lampiran" class="form-control"  />
                                    </div>
                                </div>
                                <div class="form-group">                                        
                                    <label class="col-md-2 control-label">Perihal</label>
                                    <div class="col-md-10">
                                        <input type="text" name="hal" class="form-control"  />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Lampiran Lainnya</label>
                                    <div class="col-md-10">
                                        <input type="file" name="lampiran_lain" class="fileinput btn-primary" id="filename" title="Browse file" accept="application/pdf" />
                                        <span class="help-block">Format hanya berlaku pdf</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Tembusan</label>
                                    <div class="col-md-10">
                                        <input type="text" class="tagsinput" name="tembusan" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Isi</label>
                                    <div class="col-md-10">
                                        <textarea name="isi" class="summernote" ></textarea>
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
                foreach ($notadinas as $key => $e) {
            ?>

                <!-- START DEFAULT FORM -->
                <form class="form-horizontal" action="<?php echo site_url('suratkeluar/notadinas/update') ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $e->id ?>" />
                <div class="panel panel-default">
                    <div class="panel-body">
                        <a href="javascript:history.back()" class="btn btn-default">&laquo; Kembali</a> <br><br>
                    </div>
                    <div class="panel-body">                                                                        
                        <div class="row">
                            <div class="col-md-12">
                            <div class="form-group">
                                    <label class="col-md-2 control-label">Kepada</label>
                                    <div class="col-md-10">                           
                                        <select name="kepada" class="form-control select 2" data-live-search="true">
                                            <option value="">-- Pilih --</option>
                                            <?php foreach ($jabatan as $key => $h) { ?> 
                                                <option value="<?php echo $h->jabatan_id ?>"<?php if($h->jabatan_id==$e->jabatan_id){echo"selected";} ?>><?php echo $h->nama_jabatan; ?></option>
                                            <?php } ?>
                                        </select>   
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Kop Surat</label>
                                    <div class="col-md-10">
                                        <select name="kop_id" class="form-control select" data-live-search="true">
                                            <option value="">Pilih Kop Surat</option>
                                            <?php foreach ($kop as $key => $h) { ?> 
                                                <option value="<?php echo $h->kop_id ?>" <?php if ($h->kop_id == $e->kop_id) { echo "selected"; } ?>><?php echo $h->nama; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12"><br></div>
                            
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Tanggal</label>
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                            <input type="text" name="tanggal" class="form-control datepicker" value="<?php echo $e->tanggal ?>"/>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Kode Surat</label>
                                    <div class="col-md-10">
                                        <select name="kodesurat_id" class="form-control select" data-live-search="true" >
                                            <option value="">Pilih Kode Surat</option>
                                            <?php foreach ($kodesurat as $key => $h) { ?> 
                                                <option value="<?php echo $h->kodesurat_id ?>" <?php if ($h->kodesurat_id == $e->kodesurat_id) { echo "selected"; } ?>>
                                                    <?php echo $h->kode; ?> - <?php echo substr($h->tentang, 0,130); ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Sifat</label>
                                    <div class="col-md-10">                                                                                
                                        <select class="form-control select" name="sifat" data-live-search="true" >
                                            <option>Pilih Sifat</option>
                                            <option value="notadinas" <?php if($e->sifat == 'notadinas'){ echo "selected"; } ?>>notadinas</option>
                                            <option value="Rahasia" <?php if($e->sifat == 'Rahasia'){ echo "selected"; } ?>>Rahasia</option>
                                            <option value="Segera" <?php if($e->sifat == 'Segera'){ echo "selected"; } ?>>Segera</option>
                                            <option value="Sangat Segera" <?php if($e->sifat == 'Sangat Segera'){ echo "selected"; } ?>>Sangat Segera</option>
                                            <option value="Rahasia dan Segera" <?php if($e->sifat == 'Rahasia dan Segera'){ echo "selected"; } ?>>Rahasia dan Segera</option>
                                            <option value="Rahasia dan Sangat Segera" <?php if($e->sifat == 'Rahasia dan Sangat Segera'){ echo "selected"; } ?>>Rahasia dan Sangat Segera</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">                                        
                                    <label class="col-md-2 control-label">Lampiran</label>
                                    <div class="col-md-10">
                                        <input type="text" name="lampiran" class="form-control" value="<?php echo $e->lampiran ?>"  />
                                    </div>
                                </div>

                                <div class="form-group">                                        
                                    <label class="col-md-2 control-label">Perihal</label>
                                    <div class="col-md-10">
                                        <input type="text" name="hal" class="form-control" value="<?php echo $e->hal ?>"  />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Lampiran Lainnya</label>
                                    <div class="col-md-10">
                                        <input type="file" name="lampiran_lain" class="fileinput btn-primary" title="Browse file" accept="application/pdf" />
                                        <span class="help-block">Format hanya berlaku pdf</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Tembusan</label>
                                    <div class="col-md-10">
                                        <input type="text" class="tagsinput" name="tembusan" value="<?php echo $e->tembusan ?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Isi</label>
                                    <div class="col-md-10">
                                        <textarea name="isi" class="summernote" ><?php echo $e->isi ?></textarea>
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