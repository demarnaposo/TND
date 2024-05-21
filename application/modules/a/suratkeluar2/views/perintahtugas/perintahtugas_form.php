<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo site_url('home/dashboard') ?>">Dashboard</a></li>                    
    <li><a href="javascript:void(0)">Naskah Dinas Surat</a></li>
    <li><a href="<?php echo site_url('suratkeluar/perintahtugas') ?>">Surat Perintah Tugas</a></li>
    <li class="active">Form</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-envelope"></span> Form Surat Perintah Tugas</h2>
</div>
<!-- END PAGE TITLE -->                

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">                

    <div class="row">
        <div class="col-md-12">

            <?php if ($this->uri->segment(3) == 'add') { ?>
            
                <!-- START DEFAULT FORM -->
                <form class="form-horizontal" action="<?php echo site_url('suratkeluar/perintahtugas/insert') ?>" method="post" enctype="multipart/form-data">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <a href="javascript:history.back()" class="btn btn-default">&laquo; Kembali</a> <br><br>
                    </div>
                    <div class="panel-body">                                                                        
                        
                        <div class="row">
                            
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label class="col-md-1 control-label">Kop Surat</label>
                                    <div class="col-md-11">
                                        <select name="kop_id" class="form-control select" data-live-search="true">
                                            <option value="">Pilih Kop Surat</option>
                                            <?php foreach ($kop as $key => $h) { ?> 
                                                <option value="<?php echo $h->kop_id ?>"><?php echo $h->nama; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-1 control-label">Kode Surat</label>
                                    <div class="col-md-11">
                                        <select name="kodesurat_id" class="form-control select" data-live-search="true">
                                            <option value="">Pilih Kode Surat</option>
                                            <?php foreach ($kodesurat as $key => $h) { ?> 
                                                <option value="<?php echo $h->kodesurat_id ?>"><?php echo $h->kode; ?> - <?php echo substr($h->tentang, 0,130); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">                                        
                                    <label class="col-md-1 control-label">Dasar</label>
                                    <div class="col-md-11">
                                        <input type="text" name="dasar" class="form-control" required />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-1 control-label">Tembusan</label>
                                    <div class="col-md-11">
                                        <input type="text" class="tagsinput" name="tembusan" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-1 control-label">Tanggal</label>
                                    <div class="col-md-11">
                                        <div class="input-group">
                                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                            <input type="text" name="tanggal" class="form-control datepicker" value="<?php echo date('Y-m-d') ?>"/>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>

                            <div class="col-md-12"><br>
                                <label>Isi</label>
                                <textarea name="isi" class="summernote" required>  
                                    <p style="text-align: justify; ">
                                        <table width="100%">
                                            <tr>
                                                <td width="50">Kepada</td>
                                                <td width="150"></td>
                                                <td width="10">:</td>
                                                <tdtd>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;&nbsp;&nbsp;&nbsp;1.</td>
                                                <td>Nama</td>
                                                <td>:</td>
                                                <td>...........................</td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                <td>NIP.</td>
                                                <td>:</td>
                                                <td>...........................</td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                <td>Pangkat/Golongan</td>
                                                <td>:</td>
                                                <td>...........................</td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                <td>Jabatan</td>
                                                <td>:</td>
                                                <td>...........................</td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;&nbsp;&nbsp;&nbsp;2.</td>
                                                <td>Nama</td>
                                                <td>:</td>
                                                <td>...........................</td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                <td>NIP.</td>
                                                <td>:</td>
                                                <td>...........................</td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                <td>Pangkat/Golongan</td>
                                                <td>:</td>
                                                <td>...........................</td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                <td>Jabatan</td>
                                                <td>:</td>
                                                <td>...........................</td>
                                            </tr>
                                        </table>
                                    </p>
                                    <p style="text-align: justify;" border="">
                                        &nbsp;&nbsp;&nbsp;&nbsp;<table width="100%">
                                            <tr>
                                                <td width="50">Untuk</td>
                                                <td width="10">:</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;&nbsp;&nbsp;&nbsp;a.</td>
                                                <td width="10"></td>
                                                <td>......................................................</td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;&nbsp;&nbsp;&nbsp;b.</td>
                                                <td width="10"></td>
                                                <td>......................................................</td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;&nbsp;&nbsp;&nbsp;c.</td>
                                                <td width="10"></td>
                                                <td>......................................................</td>
                                            </tr>
                                        </table>
                                    </p>
                                </textarea>             
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
                foreach ($perintahtugas as $key => $e) {
            ?>

                <!-- START DEFAULT FORM -->
                <form class="form-horizontal" action="<?php echo site_url('suratkeluar/perintahtugas/update') ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $e->id ?>" />
                <div class="panel panel-default">
                    <div class="panel-body">
                        <a href="javascript:history.back()" class="btn btn-default">&laquo; Kembali</a> <br><br>
                    </div>
                    <div class="panel-body">                                                                        
                        
                        <div class="row">
                            
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label class="col-md-1 control-label">Kop Surat</label>
                                    <div class="col-md-11">
                                        <select name="kop_id" class="form-control select" data-live-search="true">
                                            <option value="">Pilih Kop Surat</option>
                                            <?php foreach ($kop as $key => $h) { ?> 
                                                <option value="<?php echo $h->kop_id ?>" <?php if ($h->kop_id == $e->kop_id) { echo "selected"; } ?>><?php echo $h->nama; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-1 control-label">Kode Surat</label>
                                    <div class="col-md-11">
                                        <select name="kodesurat_id" class="form-control select" data-live-search="true">
                                            <option value="">Pilih Kode Surat</option>
                                            <?php foreach ($kodesurat as $key => $h) { ?> 
                                                <option value="<?php echo $h->kodesurat_id ?>" <?php if ($h->kodesurat_id == $e->kodesurat_id) { echo "selected"; } ?>><?php echo $h->kode; ?> - <?php echo substr($h->tentang, 0,130); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">                                        
                                    <label class="col-md-1 control-label">Dasar</label>
                                    <div class="col-md-11">
                                        <input type="text" name="dasar" class="form-control" required value="<?php echo $e->dasar ?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-1 control-label">Tembusan</label>
                                    <div class="col-md-11">
                                        <input type="text" class="tagsinput" name="tembusan" value="<?php echo $e->tembusan ?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-1 control-label">Tanggal</label>
                                    <div class="col-md-11">
                                        <div class="input-group">
                                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                            <input type="text" name="tanggal" class="form-control datepicker" value="<?php echo $e->tanggal ?>"/>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>

                            <div class="col-md-12"><br>
                                <label>Isi</label>
                                <textarea name="isi" class="summernote" required> <?php echo $e->isi ?> </textarea>
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