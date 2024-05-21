<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo site_url('home/dashboard') ?>">Dashboard</a></li>                    
    <li><a href="javascript:void(0)">Naskah Dinas Surat</a></li>
    <li><a href="<?php echo site_url('suratkeluar/notulen') ?>">Surat notulen</a></li>
    <li class="active">Form</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-envelope"></span> Form Surat Notulen</h2>
</div>
<!-- END PAGE TITLE -->                

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">                

    <div class="row">
        <div class="col-md-12">

            <?php if ($this->uri->segment(3) == 'add') { ?>
            
                <!-- START DEFAULT FORM -->
                <form class="form-horizontal" action="<?php echo site_url('suratkeluar/notulen/insert') ?>" method="post" enctype="multipart/form-data">
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
                                        <select name="kodesurat_id" class="form-control select" data-live-search="true" required>
                                            <option value="">Pilih Kode Surat</option>
                                            <?php foreach ($kodesurat as $key => $h) { ?> 
                                                <option value="<?php echo $h->kodesurat_id ?>"><?php echo $h->kode; ?> - <?php echo substr($h->tentang, 0,130); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-group">                                        
                                    <label class="col-md-1 control-label">Isi</label>
                                    <div class="col-md-11">
                                        <textarea name="isi" class="summernote" required>
                                    <table>
                                        <tr>
                                            <td width="50%">Sidang/Rapat</td>
                                            <td>:</td>
                                            <td>...................................</td>
                                        </tr>
                                        <tr>
                                            <td width="50%">Hari/Tanggal</td>
                                            <td>:</td>
                                            <td>...................................</td>
                                        </tr>
                                        <tr>
                                            <td width="50%">Waktu Panggilan</td>
                                            <td>:</td>
                                            <td>...................................</td>
                                        </tr>
                                        <tr>
                                            <td width="50%">Waktu Sidang/Rapat</td>
                                            <td>:</td>
                                            <td>...................................</td>
                                        </tr>
                                        <tr>
                                            <td width="50%">Acara</td>
                                            <td>:</td>
                                            <td>1. ...................................</td>
                                        </tr>
                                        <tr>
                                            <td width="50%"></td>
                                            <td></td>
                                            <td>2. ...................................</td>
                                        </tr>
                                        <tr>
                                            <td width="50%"></td>
                                            <td></td>
                                            <td>3. ...................................</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3  ">Pimpinan Sidang/Rapat</td>
                                        </tr>
                                        <tr>
                                            <td width="50%">Ketua</td>
                                            <td>:</td>
                                            <td>...................................</td>
                                        </tr>
                                        <tr>
                                            <td width="50%">Sekertaris</td>
                                            <td>:</td>
                                            <td>...................................</td>
                                        </tr>
                                        <tr>
                                            <td width="50%">Pencatat</td>
                                            <td>:</td>
                                            <td>...................................</td>
                                        </tr>
                                        <tr>
                                            <td width="50%">Peserta Sidang/Rapat</td>
                                            <td>:</td>
                                            <td>1. ...................................</td>
                                        </tr>
                                        <tr>
                                            <td width="50%"></td>
                                            <td></td>
                                            <td>2. ...................................</td>
                                        </tr>
                                        <tr>
                                            <td width="50%"></td>
                                            <td></td>
                                            <td>3. ...................................</td>
                                        </tr>
                                        <tr>
                                            <td width="50%">Kegiatan Sidang/Rapat</td>
                                            <td>:</td>
                                            <td>1. ...................................</td>
                                        </tr>
                                        <tr>
                                            <td width="50%"></td>
                                            <td></td>
                                            <td>2. ...................................</td>
                                        </tr>
                                        <tr>
                                            <td width="50%">1. Kata Pembukaan</td>
                                            <td>:</td>
                                            <td>...................................</td>
                                        </tr>
                                        <tr>
                                            <td width="50%">2. Pembahasan</td>
                                            <td>:</td>
                                            <td>...................................</td>
                                        </tr>
                                        <tr>
                                            <td width="50%">3. Peraturan</td>
                                            <td>:</td>
                                            <td>...................................</td>
                                        </tr>
                                    </table>
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
                foreach ($notulen as $key => $e) {
            ?>

                <!-- START DEFAULT FORM -->
                <form class="form-horizontal" action="<?php echo site_url('suratkeluar/notulen/update') ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $e->id ?>" />
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
                                            <input type="text" name="tanggal" class="form-control datepicker" value="<?php echo $e->tanggal ?>"/>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-1 control-label">Kode Surat</label>
                                    <div class="col-md-11">
                                        <select name="kodesurat_id" class="form-control select" data-live-search="true" required>
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
                                    <label class="col-md-1 control-label">Isi</label>
                                    <div class="col-md-11">
                                        <textarea name="isi" class="summernote" required>
                                            <?php echo $e->isi; ?>
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

            <?php } } ?>

        </div>
    </div>
</div>