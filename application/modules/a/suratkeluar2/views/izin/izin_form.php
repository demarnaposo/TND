<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo site_url('home/dashboard') ?>">Dashboard</a></li>                    
    <li><a href="javascript:void(0)">Naskah Dinas Surat</a></li>
    <li><a href="<?php echo site_url('suratkeluar/izin') ?>">Surat Izin</a></li>
    <li class="active">Form</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-envelope"></span> Form Surat Izin</h2>
</div>
<!-- END PAGE TITLE -->                

<!-- PAGE CONTENT WRAPPER --> 
<div class="page-content-wrap">                

    <div class="row">
        <div class="col-md-12">

            <?php if ($this->uri->segment(3) == 'add') { ?>
            
                <!-- START DEFAULT FORM -->
                <form class="form-horizontal" action="<?php echo site_url('suratkeluar/izin/insert') ?>" method="post" enctype="multipart/form-data">
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
                                    <label class="col-md-2 control-label">Tanggal</label>
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                            <input type="text" name="tanggal" class="form-control datepicker" value="<?php echo date('Y-m-d') ?>"/>
                                        </div>
                                    </div>
                                </div>								
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Tentang</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="tentang" />
                                    </div>
                                </div>
	
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Dasar</label>
                                    <div class="col-md-10">
										<textarea name="dasar" rows="3" class="summernote"></textarea>
                                    </div>
                                </div>	
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Daftar Pegawai</label>
                                    <div class="col-md-5">
                                        <select id="pegawai_id "name="pegawai_id" class="form-control select 2">
                                            <option value="">Pilih Pegawai</option>
                                            <?php foreach ($pegawai as $key => $a) { ?> 
												<option value="<?php echo $a->aparatur_id ?>"><?php echo $a->nip; ?> - <?php echo $a->nama; ?></option>
											 <?php } ?>
                                        </select>
                                    </div>
							  </div>						

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Untuk</label>
                                    <div class="col-md-10">
										<textarea name="untuk" rows="6" class="summernote"></textarea>
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
                foreach ($izin as $key => $e) {
            ?>

                <!-- START DEFAULT FORM -->
                <form class="form-horizontal" action="<?php echo site_url('suratkeluar/izin/update') ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $e->id ?>" />
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
                                                <option value="<?php echo $h->kop_id ?>" <?php if ($h->kop_id == $e->kop_id) { echo "selected"; } ?>><?php echo $h->nama; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Kode Surat</label>
                                    <div class="col-md-10">
                                        <select name="kodesurat_id" class="form-control select" data-live-search="true">
                                            <option value="">Pilih Kode Surat</option>
                                            <?php foreach ($kodesurat as $key => $h) { ?> 
                                                <option value="<?php echo $h->kodesurat_id ?>" <?php if ($h->kodesurat_id == $e->kodesurat_id) { echo "selected"; } ?>><?php echo $h->kode; ?> - <?php echo substr($h->tentang, 0,130); ?></option>
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
                                    <label class="col-md-2 control-label">Tentang</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="tentang" value="<?php echo $e->tentang ?>" />
                                    </div>
                                </div>  

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Dasar</label>
                                    <div class="col-md-10">
										<textarea name="dasar" rows="3" class="summernote"><?php echo $e->dasar ?></textarea>
                                    </div>
                                </div>	
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Daftar Pegawai</label>
                                    <div class="col-md-5">
                                        <select id="pegawai_id "name="pegawai_id" class="form-control select 2" data-live-search="true">
                                            <option value="">Pilih Pegawai</option>
                                            <?php foreach ($pegawai as $key => $a) { ?> 
												<option value="<?php echo $a->aparatur_id ?>"><?php if($a->aparatur_id==$e->aparatur_id){echo "selected";}?><?php echo $a->nip; ?> - <?php echo $a->nama; ?></option>
											 <?php } ?>
                                        </select>
                                    </div>
							  </div>								

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Untuk</label>
                                    <div class="col-md-10">
										<textarea name="untuk" rows="6" class="summernote"><?php echo $e->untuk ?></textarea>
                                    </div>
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
                }elseif ($this->uri->segment(3) == 'disposisi') {
            ?>

                <!-- START DEFAULT FORM -->
                <form class="form-horizontal" action="<?php echo site_url('suratkeluar/draft/disposisi') ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="surat_id" value="<?php echo $this->uri->segment(4) ?>" required>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <a href="javascript:history.back()" class="btn btn-default">&laquo; Kembali</a> <br><br>
                    </div>
                    <div class="panel-body">

                        <div class="row">
                            <div class="col-md-12">

                    <?php if ($cekListOPD < 1) { ?>

                                <div class="form-group">                                        
                                    <label class="col-md-2 control-label">Nomor Surat</label>
                                    <div class="col-md-10">                                                                                
                                        <input type="text" class="form-control" name="nomor" required>   
                                    </div>
                                </div>
                                <div class="form-group">                                        
                                    <label class="col-md-2 control-label">Perangkat Daerah</label>
                                    <div class="col-md-10">                                                                                
                                        <select multiple name="jabatan_id[]" class="form-control select" data-live-search="true">
                                            <?php foreach (sendOpd() as $key => $h) { ?> 
                                                <option value="<?php echo $h->jabatan_id ?>"><?php echo $h->nama_pd; ?></option>
                                            <?php } ?>
                                        </select>   
                                    </div>
                                </div>

                        <br><br> <center> <button class="btn btn-info" name="kirim" type="submit">Kirim</button> </center>

                    <?php }else{ ?>
                                <div class="form-group">
                                    <div class="panel-body faq">
                                        <div class="faq-item">
                                            <div class="faq-title"><span class="fa fa-angle-down"></span>Lihat List Perangkat Daerah yang dikirim</div>
                                            <div class="faq-text">
                                                <h5>List Perangkat Daerah yang dikirim : </h5>
                                                    <?php foreach ($listOPD as $key => $o) { ?>
                                                        <p>
                                                            - <?php echo $o->nama_pd ?> 
                                                            <a href="<?php echo site_url('suratkeluar/draft/delete_disposisi/'.$this->uri->segment(4).'/'.$o->dsuratkeluar_id) ?>" class='btn btn-danger btn-rounded' data-toggle='tooltip' data-placement='right' title='Hapus OPD' onclick="return confirm('Apakah anda yakin akan menghapus?')"><i class='fa fa-times'></i></a>
                                                        </p>
                                                    <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">                                        
                                    <label class="col-md-2 control-label">Nomor Surat</label>
                                    <div class="col-md-10">                                                                                
                                        <input type="text" class="form-control" name="nomor" value="<?php echo $nomor; ?>" readonly>   
                                    </div>
                                </div>
                                <div class="form-group">                                        
                                    <label class="col-md-2 control-label">Perangkat Daerah</label>
                                    <div class="col-md-10">                                                                                
                                        <select multiple name="jabatan_id[]" class="form-control select" data-live-search="true">
                                            <?php foreach (sendOpd() as $key => $o) { ?>
                                                <option value="<?php echo $o->jabatan_id ?>">
                                                    <?php echo $o->nama_pd; ?>
                                                </option>
                                            <?php } ?>
                                        </select>   
                                    </div>
                                </div>

                        <br><br> <center> <button class="btn btn-info" name="kirim" type="submit">Kirim</button> </center> <hr>

                                <div class="form-group">                                        
                                    <label class="col-md-12 control-label" style="font-size: 20px;"><center>Pengarsipan</center></label>
                                </div>
                                <div class="form-group">                                        
                                    <label class="col-md-2 control-label">No Rak</label>
                                    <div class="col-md-10">                                                                                
                                        <input type="text" name="no_rak" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">                                        
                                    <label class="col-md-2 control-label">No Sampul</label>
                                    <div class="col-md-10">                                                                                
                                        <input type="text" name="no_sampul" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">                                        
                                    <label class="col-md-2 control-label">No Book</label>
                                    <div class="col-md-10">                                                                                
                                        <input type="text" name="no_book" class="form-control">
                                    </div>
                                </div>

                        <br><br> <center> <button class="btn btn-primary" name="selesai" type="submit">Arsipkan</button> </center>
                    <?php } ?>

                            </div>
                        </div>
                    
                    </div>
                    <div class="panel-footer">
                        <button class="btn btn-danger" type="reset">Bersihkam Form</button>
                    </div>
                </div>
                </form>
                <!-- END DEFAULT FORM -->

            <?php
                }
            ?>

        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
  $("#tambah-pegawai-btn").click(function(){
    $("ol").append("<li>Appended item</li>");
  });
});
</script>