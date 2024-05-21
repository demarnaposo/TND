<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo site_url('home/dashboard') ?>">Dashboard</a></li>                    
    <li><a href="javascript:void(0)">Naskah Dinas Surat</a></li>
    <li><a href="<?php echo site_url('suratkeluar/laporan') ?>">Laporan</a></li>
    <li class="active">Form</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-envelope"></span> Form Laporan</h2>
</div>
<!-- END PAGE TITLE -->                

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">                

    <div class="row">
        <div class="col-md-12">

            <?php if ($this->uri->segment(3) == 'add') { ?>
            
                <!-- START DEFAULT FORM -->
                <form class="form-horizontal" action="<?php echo site_url('suratkeluar/laporan/insert') ?>" method="post" enctype="multipart/form-data">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <a href="javascript:history.back()" class="btn btn-default">&laquo; Kembali</a> <br><br>
                    </div>
                    <div class="panel-body">                                                                        
                        
                        <div class="row">
                            
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label class="col-md-1 control-label">Tanggal</label>
                                    <div class="col-md-11">
                                        <div class="input-group">
                                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                            <input type="text" name="tanggal" class="form-control datepicker" value="<?php echo date('Y-m-d') ?>"/>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-1 control-label">Kode Surat</label>
                                    <div class="col-md-11">
                                        <select name="kodesurat_id" class="form-control select" data-live-search="true" >
                                            <option value="">Pilih Kode Surat</option>
                                            <?php foreach ($kodesurat as $key => $h) { ?> 
                                                <option value="<?php echo $h->kodesurat_id ?>"><?php echo $h->kode; ?> - <?php echo substr($h->tentang, 0,130); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-group">                                        
                                    <label class="col-md-1 control-label">Tentang</label>
                                    <div class="col-md-11">
                                        <input type="text" name="tentang" class="form-control"  />
                                    </div>
                                </div>
                                
                                <div class="form-group">                                        
                                    <label class="col-md-1 control-label">Isi</label>
                                    <div class="col-md-11">
                                        <textarea name="isi" class="summernote" >
                                             <b>I. Pendahuluan</b><br>
                                                <p>A. Umum/Latar Belakang</p>
                                                <p>Latar Belakang...</p>
                                                <p>B. Landasan Hukum</p>
                                                <p>Adapun landasan hukum dari kegiatan ini adalah:</p>
                                                <p>C. Maksud dan Tujuan</p>
                                                <p>Maksud dan Tujuan kegiatan ini adalah:</p>
                                                <b>II. Kegiatan yang Dilaksanakan</b><br>
                                                <p>Kegiatan yang dilaksanakan meliputi...</p>
                                                <b>III. Hasil yang Dicapai</b><br>
                                                <p>Hasil yang dicapai adalah...</p>
                                                <b>IV. Kesimpulan dan Saran</b><br>
                                                <p>Kesimpulan</p>
                                                <p>Saran</p>
                                                <b>V. Penutup</b><br>
                                                <p>Sebagai penutup...</p>

                                        </textarea>
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
                foreach ($laporan as $key => $e) {
            ?>

                <!-- START DEFAULT FORM -->
                <form class="form-horizontal" action="<?php echo site_url('suratkeluar/laporan/update') ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $e->id ?>" />
                <div class="panel panel-default">
                    <div class="panel-body">
                        <a href="javascript:history.back()" class="btn btn-default">&laquo; Kembali</a> <br><br>
                    </div>
                    <div class="panel-body">
                            
                        <div class="col-md-12">

                            <div class="form-group">
                                <label class="col-md-1 control-label">Tanggal</label>
                                <div class="col-md-11">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                        <input type="text" name="tanggal" class="form-control datepicker" value="<?php echo $e->tanggal ?>"/>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-md-1 control-label">Kode Surat</label>
                                <div class="col-md-11">
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
                                <label class="col-md-1 control-label">Tentang</label>
                                <div class="col-md-11">
                                    <input type="text" name="tentang" class="form-control"  value="<?php echo $e->tentang ?>" />
                                </div>
                            </div>

                            <div class="form-group">                                        
                                <label class="col-md-1 control-label">Isi</label>
                                <div class="col-md-11">
                                    <textarea name="isi" class="summernote" >
                                        <?php echo $e->isi; ?>
                                    </textarea>
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