<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo site_url('home/dashboard') ?>">Dashboard</a></li>                    
    <li><a href="javascript:void(0)">Naskah Dinas Surat</a></li>
    <li><a href="<?php echo site_url('suratkeluar/undangan') ?>">Surat Undangan</a></li>
    <li class="active">Form</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-envelope"></span> Form Surat Undangan</h2>
</div>
<!-- END PAGE TITLE -->                

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">                

    <div class="row">
        <div class="col-md-12">

            <?php if ($this->uri->segment(3) == 'add') { ?>
            
                <!-- START DEFAULT FORM -->
                <form class="form-horizontal" action="<?php echo site_url('suratkeluar/undangan/insert') ?>" method="post" enctype="multipart/form-data">
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
                                    <label class="col-md-2 control-label">Kepada Internal</label>
                                    <div class="col-md-4">                           
                                        <select multiple name="jabatan_id[]" class="form-control select" data-live-search="true">
                                            <?php foreach (sendOpd() as $key => $h) { ?> 
                                                <option value="<?php echo $h->jabatan_id ?>"><?php echo $h->nama_pd; ?></option>
                                            <?php } ?>
                                        </select>   
                                    </div>
                                    <label class="col-md-2 control-label">Kepada Eksternal</label>
                                    <div class="col-md-4">                                                                                
                                        
                                        <select multiple name="eksternal_id[]" class="form-control select" data-live-search="true">
                                            <?php foreach (sendEksternal() as $key => $h) { ?> 
                                                <option value="<?php echo $h->id ?>"><?php echo $h->nama; ?></option>
                                            <?php } ?>
                                        </select>   
                                        <span class="help-block"><a href="javascript:void(0)" data-toggle="modal" data-target="#modalEksternal">Klik Disini</a> untuk menambahkan data eksternal</span> 
                                        <span class="help-block">Kosongkan jika tidak ingin mengirim ke eksternal</span> 

                                        <!-- Modal Eksternal -->
                                        <div class="modal fade" id="modalEksternal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                          <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                              <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Form Data Pengiriman Eksternal</h5>
                                              </div>
                                              <div class="modal-body">
                                                <input type="hidden" name="surat_id" value="<?php echo $this->uri->segment(4) ?>"> 
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="col-md-2 control-label">Nama Eksternal</label>
                                                            <div class="col-md-10">
                                                                <input type="text" class="form-control" name="nama"> 
                                                            </div>
                                                        </div>
                                                    </div><br><br><br>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="col-md-2 control-label">Email Eksternal</label>
                                                            <div class="col-md-10">
                                                                <input type="email" class="form-control" name="email"> 
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <br><br> <center> <button class="btn btn-info" type="submit" name="simpan">Simpan</button></center>
                                                    </div>
                                                </div>
                                              </div>
                                              <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <!-- End Modal Eksternal -->

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
                                        <select name="kodesurat_id" class="form-control select" data-live-search="true" >
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
                                        <input type="file" name="lampiran_lain" class="fileinput btn-primary" name="filename" id="filename" title="Browse file" accept="application/pdf" />
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
                                    <label class="col-md-2 control-label">Paragrap Pembuka</label>
                                    <div class="col-md-10">
										<textarea name="p1" rows="6" class="summernote"></textarea>
                                    </div>
                                </div>	
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Hari</label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" name="hari" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Tanggal</label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" name="tgl_acara" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Pukul</label>
                                    <div class="col-md-4">
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
                                    <label class="col-md-2 control-label">Acara</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="acara" />
                                    </div>
                                </div>																
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Paragrap Penutup</label>
                                    <div class="col-md-10">
										<textarea name="p2" rows="6" class="summernote"></textarea>
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
                foreach ($undangan as $key => $e) {
            ?>

                <!-- START DEFAULT FORM -->
                <form class="form-horizontal" action="<?php echo site_url('suratkeluar/undangan/update') ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $e->id ?>" />
                <input type="hidden" name="verifikasi_id" value="<?php echo $e->verifikasi_id ?>" />
                <input type="hidden" name="penandatangan_id" value="<?php echo $e->penandatangan_id ?>" />
                <div class="panel panel-default">
                    <div class="panel-body">
                        <a href="javascript:history.back()" class="btn btn-default">&laquo; Kembali</a> <br><br>
                    </div>
                    <div class="panel-body">                                                                        
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="panel-body faq">
                                        <div class="faq-item">
                                            <div class="faq-title"><span class="fa fa-angle-down"></span>Lihat List Kepada Internal</div>
                                            <div class="faq-text">
                                                <h5>List Kepada Internal : </h5>
                                                <?php foreach (listOpd($this->uri->segment(4)) as $key => $o) { ?>
                                                    <p>
                                                        - <?php echo $o->nama_pd ?> 
                                                        <a href="<?php echo site_url('suratkeluar/undangan/delete_kepada/'.$this->uri->segment(4).'/'.$o->dsuratkeluar_id) ?>" class='btn btn-danger btn-rounded' data-toggle='tooltip' data-placement='right' title='Hapus OPD' onclick="return confirm('Apakah anda yakin akan menghapus?')"><i class='fa fa-times'></i></a>
                                                    </p>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="panel-body faq">
                                        <div class="faq-item">
                                            <div class="faq-title"><span class="fa fa-angle-down"></span>Lihat List Kepada Eksternal</div>
                                            <div class="faq-text">
                                                <h5>List Kepada Eksternal : </h5>
                                                    <?php foreach (listEksternal($this->uri->segment(4)) as $key => $o) { ?>
                                                        <p>
                                                            - <?php echo $o->nama ?> 
                                                            <a href="<?php echo site_url('suratkeluar/undangan/delete_kepada/'.$this->uri->segment(4).'/'.$o->dsuratkeluar_id) ?>" class='btn btn-danger btn-rounded' data-toggle='tooltip' data-placement='right' title='Hapus OPD' onclick="return confirm('Apakah anda yakin akan menghapus?')"><i class='fa fa-times'></i></a>
                                                        </p>
                                                    <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>    
                        </div> <br>   

                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Kepada Internal</label>
                                    <div class="col-md-4">                           
                                        <select multiple name="jabatan_id[]" class="form-control select" data-live-search="true">
                                            <?php foreach (sendOpd() as $key => $h) { ?> 
                                                <option value="<?php echo $h->jabatan_id ?>"><?php echo $h->nama_pd; ?></option>
                                            <?php } ?>
                                        </select>   
                                    </div>
                                    <label class="col-md-2 control-label">Kepada Eksternal</label>
                                    <div class="col-md-4">                                                                                
                                        
                                        <select multiple name="eksternal_id[]" class="form-control select" data-live-search="true">
                                            <?php foreach (sendEksternal() as $key => $h) { ?> 
                                                <option value="<?php echo $h->id ?>"><?php echo $h->nama; ?></option>
                                            <?php } ?>
                                        </select>   
                                        <span class="help-block"><a href="javascript:void(0)" data-toggle="modal" data-target="#modalEksternal">Klik Disini</a> untuk menambahkan data eksternal</span> 
                                        <span class="help-block">Kosongkan jika tidak ingin mengirim ke eksternal</span> 

                                        <!-- Modal Eksternal -->
                                        <div class="modal fade" id="modalEksternal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                          <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                              <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Form Data Pengiriman Eksternal</h5>
                                              </div>
                                              <div class="modal-body">
                                                <input type="hidden" name="surat_id" value="<?php echo $this->uri->segment(4) ?>"> 
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="col-md-2 control-label">Nama Eksternal</label>
                                                            <div class="col-md-10">
                                                                <input type="text" class="form-control" name="nama"> 
                                                            </div>
                                                        </div>
                                                    </div><br><br><br>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="col-md-2 control-label">Email Eksternal</label>
                                                            <div class="col-md-10">
                                                                <input type="email" class="form-control" name="email"> 
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <br><br> <center> <button class="btn btn-info" type="submit" name="simpan">Simpan</button></center>
                                                    </div>
                                                </div>
                                              </div>
                                              <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <!-- End Modal Eksternal -->

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
                                        <input type="text" name="hal" class="form-control" value="<?php echo $e->hal ?>"  />
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
                                    <label class="col-md-2 control-label">Tembusan</label>
                                    <div class="col-md-10">
                                        <input type="text" class="tagsinput" name="tembusan" value="<?php echo $e->tembusan ?>" />
                                    </div>
                                </div>                        

                               <div class="form-group">
                                    <label class="col-md-2 control-label">Paragrap Pembuka</label>
                                    <div class="col-md-10">
										<textarea name="p1" rows="6" class="summernote"><?php echo $e->p1 ?></textarea>
                                    </div>
                                </div>	
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Hari</label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" name="hari" value="<?php echo $e->hari ?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Tanggal</label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" name="tgl_acara" value="<?php echo $e->tgl_acara ?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Pukul</label>
                                    <div class="col-md-4">
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
                                    <label class="col-md-2 control-label">Acara</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="acara" value="<?php echo $e->acara ?>" />
                                    </div>
                                </div>																
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Paragrap Penutup</label>
                                    <div class="col-md-10">
										<textarea name="p2" rows="6" class="summernote"><?php echo $e->p2 ?></textarea>
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