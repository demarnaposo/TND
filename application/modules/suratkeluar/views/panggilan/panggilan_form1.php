<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo site_url('home/dashboard') ?>">Dashboard</a></li>                    
    <li><a href="javascript:void(0)">Naskah Dinas Surat</a></li>
    <li><a href="<?php echo site_url('suratkeluar/panggilan') ?>">Surat panggilan</a></li>
    <li class="active">Form</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-envelope"></span> Form Surat Panggilan</h2>
</div>
<!-- END PAGE TITLE -->                

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">                

    <div class="row"> 
        <div class="col-md-12">

            <?php if ($this->uri->segment(3) == 'add') { ?>
            
                <!-- START DEFAULT FORM -->
                <form class="form-horizontal" action="<?php echo site_url('suratkeluar/panggilan/insert') ?>" method="post" enctype="multipart/form-data">
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
                                        <select name="kodesurat_id" class="form-control select" data-live-search="true" />
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
                                        <select class="form-control select" name="sifat" data-live-search="true" />
                                            <option>Pilih Sifat</option>
                                            <option value="Biasa">Biasa</option>
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
                                        <input type="text" name="lampiran" class="form-control" / />
                                    </div>
                                </div>
                                <div class="form-group">                                        
                                    <label class="col-md-2 control-label">Hal</label>
                                    <div class="col-md-10">
                                        <input type="text" name="hal" class="form-control" / />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Lampiran Lainnya</label>
                                    <div class="col-md-10">
                                        <input type="file" name="lampiran_lain" class="fileinput btn-primary" name="filename" id="filename" title="Browse file" accept="application/pdf" />
                                        <span class="help-block">Format hanya berlaku pdf</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Kepada Yth.</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="kepada" />
                                    </div>
                                </div>								
                                <div class="form-group">
                                    <label class="col-md-2 control-label">untuk datang di Kantor</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="kantor" />
                                    </div>
                                </div>                              
								<div class="form-group">
                                    <label class="col-md-2 control-label">Hari</label>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control" name="hari" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Tanggal</label>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control" name="tgl" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Pukul</label>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control" name="pukul" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Tempat</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="tempat" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Menghadap Kepada</label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" name="menghadapkepada" />
                                    </div>
                                </div>									
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Alamat</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="alamat" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Untuk</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="untuk" />
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
                foreach ($panggilan as $key => $e) {
            ?>

                <!-- START DEFAULT FORM -->
                <form class="form-horizontal" action="<?php echo site_url('suratkeluar/panggilan/update') ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $e->id ?>" />
                <div class="panel panel-default">
                    <div class="panel-body">
                        <a href="javascript:history.back()" class="btn btn-default">&laquo; Kembali</a> <br><br>
                    </div>
                    <div class="panel-body">                  
                        <div class="row">       
                            <br><div class="col-md-12">
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
                                        <select name="kodesurat_id" class="form-control select" data-live-search="true" />
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
                                        <select class="form-control select" name="sifat" data-live-search="true" />
                                            <option>Pilih Sifat</option>
                                            <option value="Biasa" <?php if($e->sifat == 'Biasa'){ echo "selected"; } ?>>Biasa</option>
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
                                        <input type="text" name="hal" class="form-control" value="<?php echo $e->hal ?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Lampiran Lainnya</label>
                                    <div class="col-md-10">
                                        <input type="file" name="lampiran_lain" class="fileinput btn-primary" name="filename" id="filename" title="Browse file" accept="application/pdf" />
                                        <span class="help-block">Format hanya berlaku pdf</span>
                                    </div>
                                </div>
                               <div class="form-group">
                                    <label class="col-md-2 control-label">Kepada Yth.</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="kepada"  value="<?php echo $e->kepada ?>"/>
                                    </div>
                                </div>                              
								<div class="form-group">
                                    <label class="col-md-2 control-label">untuk datang di Kantor</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="kantor"  value="<?php echo $e->kantor ?>"/>
                                    </div>
                                </div>                              
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Hari</label>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control" name="hari" value="<?php echo $e->hari ?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Tanggal</label>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control" name="tgl" value="<?php echo $e->tgl ?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Pukul</label>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control" name="pukul" value="<?php echo $e->pukul ?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Tempat</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="tempat" value="<?php echo $e->tempat ?>"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Menghadap Kepada</label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" name="menghadapkepada"  value="<?php echo $e->menghadapkepada ?>"/>
                                    </div>
                                </div>									
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Alamat</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="alamat"  value="<?php echo $e->alamat ?>"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Untuk</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="untuk"  value="<?php echo $e->untuk ?>"/>
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