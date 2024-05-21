<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo site_url('home/dashboard') ?>">Dashboard</a></li>                    
    <li><a href="javascript:void(0)">Naskah Dinas Surat</a></li>
    <li><a href="<?php echo site_url('suratkeluar/perintah') ?>">Surat Perintah</a></li>
    <li class="active">Form</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-envelope"></span> Form Surat Perintah</h2>
</div>
<!-- END PAGE TITLE -->                

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">                

    <div class="row">
        <div class="col-md-12">

            <?php if ($this->uri->segment(3) == 'add') { ?>
            
                <!-- START DEFAULT FORM -->
                <form class="form-horizontal" action="<?php echo site_url('suratkeluar/perintah/insert') ?>" method="post" enctype="multipart/form-data">
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
                                    <label class="col-md-2 control-label">Nama (yang memberikan perintah)</label>
                                    <div class="col-md-10">
                                        <input type="text" name="nama_pejabat" class="form-control" required />
                                    </div>
                                </div>

                                <div class="form-group">                                        
                                    <label class="col-md-2 control-label">Jabatan</label>
                                    <div class="col-md-10">
                                        <input type="text" name="jabatan" class="form-control" required />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Tembusan</label>
                                    <div class="col-md-10">
                                        <input type="text" class="tagsinput" name="tembusan" />
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
                foreach ($perintah as $key => $e) {
            ?>

                <!-- START DEFAULT FORM -->
                <form class="form-horizontal" action="<?php echo site_url('suratkeluar/perintah/update') ?>" method="post" enctype="multipart/form-data">
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
                                    <label class="col-md-2 control-label">Nama (yang memberikan perintah)</label>
                                    <div class="col-md-10">
                                        <input type="text" name="nama_pejabat" class="form-control" value="<?php echo $e->nama_pejabat ?>" required />
                                    </div>
                                </div>

                                <div class="form-group">                                        
                                    <label class="col-md-2 control-label">Jabatan</label>
                                    <div class="col-md-10">
                                        <input type="text" name="jabatan" class="form-control" value="<?php echo $e->jabatan ?>" required />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Tembusan</label>
                                    <div class="col-md-10">
                                        <input type="text" class="tagsinput" name="tembusan" value="<?php echo $e->tembusan ?>" />
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