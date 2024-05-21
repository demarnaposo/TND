<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo site_url('home/dashboard') ?>">Beranda</a></li>                    
    <li><a href="javascript:void(0)">Naskah Dinas Surat</a></li>
    <li><a href="<?php echo site_url('suratkeluar/perintah') ?>">Surat Perintah</a></li>
    <li class="active">Formulir</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-envelope"></span> Formulir Surat Perintah</h2>
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
                        <a href="javascript:history.back()" class="btn btn-default">&laquo; Kembali</a>
                        <a href="<?= site_url()?>assets/reviewsurat/SURAT PERINTAH.pdf" target="_blank" class="btn btn-default" style="margin-left: 1%">Template Surat</a>
                    </div>
                    <div class="panel-body">                                                                        
                        
                        <div class="row">
                            
                            <div class="col-md-12">
                                <!-- Update @Mpik Egov 28/07/2022 -->
                                <div class="form-group">
                                    <label class="col-md-2 control-label"></label>
                                    <div class="col-md-10">
                                        <div class="alert alert-danger" role="alert">
                                          <h4 class="alert-heading"><b>Perhatian</b></h4>
                                          <p style="font-size:14px;">Jika tujuan surat kepada Internal Pemerintah Kota Bogor, <b>maka pilih di kolom Kepada Internal Pemkot</b>. Kolom Kepada Eksternal Pemkot hanya untuk diluar Pemerintah Kota Bogor </p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Update @Mpik Egov 28/07/2022 -->
                                <div class="form-group">
                                <label class="col-md-2 control-label">Kpd.Internal Pemkot</label>
                                    <div class="col-md-4">                           
                                        <select multiple name="jabatan_id[]" id="jabatan" class="form-control select" data-live-search="true">
                                                <option value="">Tidak Ada Yang Dipilih</option>
                                            <?php foreach (sendOpd() as $key => $h) { 
                                                $query = $this->db->query("SELECT nama_jabatan FROM jabatan WHERE jabatan_id = '$h->atasan_id'");
                                                foreach($query->result() as $ky => $q){
                                            ?> 
                                                <option value="<?php echo $h->jabatan_id ?>"><?php echo $h->nama_pd; echo ' - '.$q->nama_jabatan; ?></option>
                                            <?php } }?>
                                        </select>
                                        <span class="help-block"><font color="red"><b><?php echo $this->session->flashdata('value_tujuan') ?></b></font></span> 
                                    </div>
                                    <!-- <div class="col-md-1">
                                        <h3><input id="chekall" type="checkbox" style="width:17px; height:17px;"> Pilih Semua</h3>
                                    </div> -->
                                    <label class="col-md-2 control-label">Kpd.Eksternal Pemkot</label>
                                    <div class="col-md-4">                                                                              
                                        <select multiple name="eksternal_id[]" class="form-control select" data-live-search="true">                                                
                                            <?php $opdid=$this->session->userdata('opd_id'); foreach (sendEksternal($opdid) as $key => $h) { ?> 
                                                <option value="<?php echo $h->id ?>"><?php echo $h->nama; ?></option>
                                            <?php } ?>
                                        </select>
                                        <!-- <span class="help-block"><font color="red"> Kontak Admin</font> untuk menambahkan data perangkat eksternal</span> -->
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
                                                    <div class="alert alert-warning" role="alert">
                                                        <h4><b>Keterangan :</b> Untuk tujuan/kepada Pemerintah Kota Bogor tidak perlu dibuat eksternal. Pilih di kolom kepada internal Pemkot</h5>
                                                    </div>
                                                    <center><label class="control-label">Nama Eksternal</label></center>
                                                    <input type="text" class="form-control" name="nama">
                                                    <br>
                                                    <center><label class="control-label">Email Eksternal</label></center>
                                                    <input type="text" class="form-control" name="email"> 
                                                    <br>
                                                    <center><label class="control-label">Alamat Eksternal</label></center>
                                                    <input type="text" class="form-control" name="alamat_eksternal">
                                                    <br>
                                                    <center><label class="control-label">Nama Kota/Kab Eksternal</label></center>
                                                    <input type="text" class="form-control" name="tempat">
                                              </div>
                                              <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button class="btn btn-info" type="submit" name="simpan">Simpan</button>
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
                                                <option value="<?php echo $h->kop_id ?>" <?php if($h->kop_id == $this->session->flashdata('value_kop_id')) echo "selected"?>><?php echo $h->nama; ?></option>
                                            <?php } ?>
                                        </select>
                                        <span class="help-block"><font color="red"><?php echo $this->session->flashdata('kop_id') ?></font></span>
                                    </div>
                                </div>
                                <input type="hidden" name="kodesurat_id" value="" />
                                <!-- <div class="form-group">
                                    <label class="col-md-2 control-label">Kode Surat</label>
                                    <div class="col-md-10">
                                        <select name="kodesurat_id" class="form-control select" data-live-search="true" >
                                            <option value="">Pilih Kode Surat</option>
                                            <?php foreach ($kodesurat as $key => $h) { ?> 
                                                <option value="<?php echo $h->kodesurat_id ?>"><?php echo $h->kode; ?> - <?php echo substr($h->tentang, 0,130); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div> -->
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Tanggal</label>
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                            <input type="text" name="tanggal" class="form-control datepicker" value="<?php echo date('Y-m-d') ?>"/>
                                        </div>
                                    </div>
                                </div>
                                <!-- Update @Mpik Egov 28/07/2022 -->
                                <div class="form-group">
                                    <label class="col-md-2 control-label"></label>
                                    <div class="col-md-10">
                                        <div class="alert alert-danger" role="alert">
                                          <h4 class="alert-heading"><b>Perhatian</b></h4>
                                          <p style="font-size:14px;">Dimohon untuk <b>tidak melakukan <i>copy & paste</i></b> di isi surat dari <i>Microsoft Word</i> atau PDF. Guna menghasilkan <i>preview</i> surat yang rapih dan proporsional</p>
                                          <hr>
                                          <p style="font-size:14px;" class="mb-0"><b>Solusi</b> : Jika ingin melakukan <i>copy & paste</i> dari <i>Microsoft Word</i> atau PDF, <i>copy & paste</i> ke notepad dahulu. Lalu dari notepad <i>copy & paste</i> ke dalam isi surat TND</p>
                                        </div>
                                    </div>
                                </div>    
                                <!-- Update @Mpik Egov 28/07/2022 -->  
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Dasar</label>
                                    <div class="col-md-10">
										<textarea id="textarea1" name="dasar"><?php echo $this->session->flashdata('value_dasar') ?></textarea>
                                        <span class="help-block"><font color="red"><?php echo $this->session->flashdata('dasar') ?></font></span>
                                    </div>
                                </div>	
                                
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Kepada</label>
                                    <div class="col-md-5">
                                        <select id="pegawai" name="pegawai_id[]"  multiple="multiple" class="form-control select" data-live-search="true">
                                            <?php foreach ($pegawai as $key => $a) { ?> 
												<option value="<?php echo $a->aparatur_id ?>"><?php echo $a->nama; ?> - <?php echo $a->nama_jabatan; ?></option>
											 <?php } ?>
                                        </select>
                                        <span class="help-block"><font color="red"><b><?php echo $this->session->flashdata('value_pegawai') ?></b></font></span>
                                    </div>
                                    <div class="col-md-1">
                                        <h3><input id="chekall2" type="checkbox" style="width:17px; height:17px;"> Pilih Semua</h3>
                                    </div>
                                </div>

                                <div class="form-group">                                        
                                    <label class="col-md-2 control-label"></label>
                                    <h4><b>Apakah Anda ingin mengisi kolom Kepada secara manual ?</b>
                                    <label class="check"><input type="radio" onclick="javascript:yesnoCheck();" name="yesno" id="yesCheck"> Iya </label>
                                    <label class="check"><input type="radio" onclick="javascript:yesnoCheck();" name="yesno" id="noCheck"> Tidak</label></h1>
                                    <!-- <div class="row">
                                        <div class="col-md-6" align="right">
                                            <label class="check"><input type="radio" onclick="javascript:yesnoCheck();" name="yesno" id="yesCheck"> Iya </label>
                                        </div>
                                        <div class="col-md-6" align="left">
                                            <label class="check"><input type="radio" onclick="javascript:yesnoCheck();" name="yesno" id="noCheck"> Tidak</label>
                                        </div>
                                    </div> -->
                                </div>		
                                <div id="ifYes" style="display:none">
                                <div class="form-group">                                        
                                    <label class="col-md-2 control-label">Kepada</label>
                                    <div class="col-md-10">
                                        <input type="text" name="kepada" class="form-control" value="<?php echo $this->session->flashdata('value_kepada') ?>"/>
                                        <span class="help-block"><font color="red"><?php echo $this->session->flashdata('kepada') ?></font></span>
                                    </div>
                                </div><br>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Untuk</label>
                                    <div class="col-md-10">
					                <textarea id="textarea2" name="untuk"><?php echo $this->session->flashdata('value_untuk') ?></textarea>
                                    <span class="help-block"><font color="red"><?php echo $this->session->flashdata('untuk') ?></font></span>
                                    </div>
                                </div>
                                <div class="form-group">                                        
                                    <label class="col-md-2 control-label">Catatan</label>
                                    <div class="col-md-10">
                                        <textarea id="catatan" name="catatan"><?php echo $this->session->flashdata('value_catatan') ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">                                        
                                    <label class="col-md-2 control-label">Tembusan Internal Pemkot</label>
                                    <div class="col-md-4">
                                        <!--<input type="text" name="tembusan" class="tagsinput" />-->
                                        <select multiple name="tembusan_id[]" class="form-control select" data-live-search="true">
                                        <?php
                                            foreach(sendTembusanInt() as $key => $r){
                                                $query = $this->db->query("SELECT nama_jabatan FROM jabatan WHERE jabatan_id = '$r->atasan_id'");
                                                foreach($query->result() as $ky => $q){
                                        ?> 
                                                <option value="<?php echo $r->jabatan_id ?>"><?php echo $q->nama_jabatan; ?></option>
                                        <?php } }?>
                                        </select>
                                    </div>
                                    
                                    <label class="col-md-2 control-label">Tembusan Eksternal Pemkot</label>
                                    <div class="col-md-4">                                                                                
                                        
                                        <select multiple name="tembusaneks_id[]" class="form-control select" data-live-search="true">
                                            <?php $opdid=$this->session->userdata('opd_id'); foreach (sendTembusanEks($opdid) as $key => $h) { ?> 
                                                <option value="<?php echo $h->id ?>"><?php echo $h->nama_tembusan; ?></option>
                                            <?php } ?>
                                        </select>   
                                        <span class="help-block"><font color="red"> Kontak Admin</font> untuk menambahkan data tembusan eksternal</span>                                      
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Lampiran Lainnya</label>
                                    <div class="col-md-10">
                                        <input type="file" name="lampiran_lain" class="fileinput btn-primary" id="filename" title="Cari File" />
                                        <span class="help-block"><font color="red"><b><?php echo $this->session->flashdata('value_lain') ?></b></font></span>
                                        <span class="help-block">Format hanya berlaku pdf</span>
                                    </div>
                                </div>                                      
                                
                            </div>

                    </div>
                    </div>
                    </div>
                    <div class="panel-footer">
                        <button class="btn btn-danger" type="reset">Bersihkan Formulir</button>   
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="panel-body faq">
                                        <div class="faq-item">
                                            <div class="faq-title"><span class="fa fa-angle-down"></span>Lihat List Kepada Internal Pemkot</div>
                                            <div class="faq-text">
                                                <h5>List Kepada Internal Pemkot : </h5>
                                                <?php foreach (listOpd($this->uri->segment(4)) as $key => $o) {
                                                    $query = $this->db->query("SELECT nama_jabatan FROM jabatan WHERE jabatan_id = '$o->atasan_id'");
                                                    foreach($query->result() as $ky => $q){
                                                 ?>
                                                    <p>
                                                        - <?php echo $o->nama_pd ?> (<?= $q->nama_jabatan;?>)
                                                        <a href="<?php echo site_url('suratkeluar/perintahtugas/delete_kepada/'.$this->uri->segment(4).'/'.$o->dsuratkeluar_id) ?>" class='btn btn-danger btn-rounded' data-toggle='tooltip' data-placement='right' title='Hapus OPD' onclick="return confirm('Apakah anda yakin akan menghapus?')"><i class='fa fa-times'></i></a>
                                                    </p>
                                                <?php } }?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="panel-body faq">
                                        <div class="faq-item">
                                            <div class="faq-title"><span class="fa fa-angle-down"></span>Lihat List Kepada Eksternal Pemkot</div>
                                            <div class="faq-text">
                                                <h5>List Kepada Eksternal Pemkot: </h5>
                                                    <?php foreach (listEksternal($this->uri->segment(4)) as $key => $o) { ?>
                                                        <p>
                                                            - <?php echo $o->nama ?> 
                                                            <a href="<?php echo site_url('suratkeluar/perintahtugas/delete_kepada/'.$this->uri->segment(4).'/'.$o->dsuratkeluar_id) ?>" class='btn btn-danger btn-rounded' data-toggle='tooltip' data-placement='right' title='Hapus OPD' onclick="return confirm('Apakah anda yakin akan menghapus?')"><i class='fa fa-times'></i></a>
                                                        </p>
                                                    <?php } ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>    
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                
                            </div>
                        </div>
                            
                        <br><div class="col-md-12">                                
                        <div class="form-group">
                                <label class="col-md-2 control-label">Kpd.Internal Pemkot </label>
                                <div class="col-md-4">                           
                                        <select multiple name="jabatan_id[]" class="form-control select" data-live-search="true">                                        
                                            <?php 
                                                foreach (sendOpd() as $key => $h) { 
                                                    $query = $this->db->query("SELECT nama_jabatan FROM jabatan WHERE jabatan_id = '$h->atasan_id'");
                                                    foreach($query->result() as $ky => $q){
                                            ?> 
                                                <option value="<?php echo $h->jabatan_id ?>"><?php echo $h->nama_pd; echo ' - '.$q->nama_jabatan; ?></option>
                                            <?php } } ?>
                                        </select>   
                                    </div>
                                <label class="col-md-2 control-label">Kpd.Eksternal Pemkot</label>
                                <div class="col-md-4">                                                                 
                                    <select multiple name="eksternal_id[]" class="form-control select" data-live-search="true">
                                        <?php $opdid=$this->session->userdata('opd_id'); foreach (sendEksternal($opdid) as $key => $h) { ?> 
                                            <option value="<?php echo $h->id ?>"><?php echo $h->nama; ?></option>
                                        <?php } ?>
                                    </select>
                                    <!-- <span class="help-block"><font color="red"> Kontak Admin</font> untuk menambahkan data perangkat eksternal</span> -->
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
                                                    <div class="alert alert-warning" role="alert">
                                                        <h4><b>Keterangan :</b> Untuk tujuan/kepada Pemerintah Kota Bogor tidak perlu dibuat eksternal. Pilih di kolom kepada internal Pemkot</h5>
                                                    </div>
                                                    <center><label class="control-label">Nama Eksternal</label></center>
                                                    <input type="text" class="form-control" name="nama">
                                                    <br>
                                                    <center><label class="control-label">Email Eksternal</label></center>
                                                    <input type="text" class="form-control" name="email"> 
                                                    <br>
                                                    <center><label class="control-label">Alamat Eksternal</label></center>
                                                    <input type="text" class="form-control" name="alamat_eksternal">
                                                    <br>
                                                    <center><label class="control-label">Nama Kota/Kab Eksternal</label></center>
                                                    <input type="text" class="form-control" name="tempat">
                                              </div>
                                              <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button class="btn btn-info" type="submit" name="simpan">Simpan</button>
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
                                        <select name="kop_id" class="form-control select" data-live-search="true" required>
                                            <option value="">Pilih Kop Surat</option>
                                            <?php foreach ($kop as $key => $h) { ?> 
                                                <option value="<?php echo $h->kop_id ?>" <?php if ($h->kop_id == $e->kop_id) { echo "selected"; } ?>><?php echo $h->nama; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <input type="hidden" name="kodesurat_id" value="<?php echo $e->kodesurat_id ?>" />
                                <!-- <div class="form-group">
                                    <label class="col-md-2 control-label">Kode Surat</label>
                                    <div class="col-md-10">
                                        <select name="kodesurat_id" class="form-control select" data-live-search="true" required>
                                            <option value="">Pilih Kode Surat</option>
                                            <?php foreach ($kodesurat as $key => $h) { ?> 
                                                <option value="<?php echo $h->kodesurat_id ?>" <?php if ($h->kodesurat_id == $e->kodesurat_id) { echo "selected"; } ?>>
                                                    <?php echo $h->kode; ?> - <?php echo substr($h->tentang, 0,130); ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div> -->
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
                                    <label class="col-md-2 control-label">Dasar</label>
                                    <div class="col-md-10">
										<textarea id="textarea1" name="dasar"><?php echo $e->dasar ?></textarea>
                                    </div>
                                </div>	
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Daftar Pegawai</label>
                                    <div class="col-md-5">
                                        <select id="pegawai"name="pegawai_id[]"  multiple="multiple" class="form-control select" data-live-search="true">
                                               
                                            <?php foreach ($pegawai as $key => $a) { ?>
                                                <?php $tampil=$e->pegawai_id;
                                                $print=explode(',',$tampil);?>
                                                <option value="<?php echo $a->aparatur_id ?>" <?php 
                                                $tampil=$e->pegawai_id;
                                                $print=explode(',',$tampil);
                                                foreach($print as $p){
                                                if ($a->aparatur_id == $p) { echo "selected"; }} ?>><?php echo $a->nama; ?> - <?php echo $a->nama_jabatan; ?> </option>
											 <?php }?>
                                        </select>                                        
                                    </div>
                                    <div class="col-md-1">
                                        <h3><input id="chekall2" type="checkbox" style="width:17px; height:17px;"> Pilih Semua</h3>
                                    </div>                                    
							  </div>
                                <?php if (empty($e->pegawai_id)){?>
                                    <div class="form-group">                                        
                                    <label class="col-md-2 control-label">Kepada</label>
                                    <div class="col-md-10">
                                        <input type="text" name="kepada" class="form-control" value="<?=$e->kepada;?>"/>
                                    </div>
                                    </div>
                                <?php } else if (!empty($e->pegawai_id)){?>
                                <div class="form-group">
                                    <label class="col-md-2 control-label"></label>
                                    <div class="col-md-10">
									<div class="table-responsive">
									<table class="table table-bordered">
										<thead>
											<tr>
												<th width="10%">NIP</th>
												<th width="20%">NAMA</th>
												<th width="20%">PANGKAT/GOLONGAN</th>
												<th width="20%">JABATAN</th>
											</tr>
										</thead>
										<tbody>
									<?php				
										$datapegawai =	$this->db->query("
										SELECT aparatur_id, nip, nama, pangkat, golongan, nama_jabatan FROM aparatur
										LEFT JOIN jabatan ON aparatur.jabatan_id = jabatan.jabatan_id
										WHERE aparatur_id IN ($e->pegawai_id)")->result_array();
											
										foreach ($datapegawai as $key => $p) {	
									?>
										<tr>
												<th width="10%"><?php echo $p['nip']; ?></th>
												<th width="20%"><?php echo $p['nama']; ?></th>
												<th width="20%"><?php echo $p['pangkat']; ?> - <?php echo $p['golongan']; ?></th>
												<th width="20%"><?php echo $p['nama_jabatan']; ?></th>
										</tr>									
		
										<?php } ?>
										<tbody>
									</table>
									</div>	
                                    </div>
                                </div>	
                            <?php }?>				

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Untuk</label>
                                    <div class="col-md-10">
					                 <textarea id="textarea2" name="untuk"><?php echo $e->untuk ?></textarea>
                                    </div>
                                </div>	
                                <div class="form-group">                                        
                                    <label class="col-md-2 control-label">Catatan</label>
                                    <div class="col-md-10">
                                        <textarea id="catatan" name="catatan"><?php echo $e->catatan ?></textarea>
                                    </div>
                                </div>
                                
                                <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="panel-body faq">
                                        <div class="faq-item">
                                            <div class="faq-title"><span class="fa fa-angle-down"></span>Lihat List Tembusan Internal Pemkot</div>
                                            <div class="faq-text">
                                                <h5>List Tembusan Internal Pemkot : </h5>
                                                <?php foreach (listTembusan($this->uri->segment(4)) as $key => $o) {
                                                    $query = $this->db->query("SELECT nama_jabatan FROM jabatan WHERE jabatan_id = '$o->atasan_id'");
                                                    foreach($query->result() as $ky => $q){
                                                 ?>
                                                    <p>
                                                        - <?= $q->nama_jabatan;?>
                                                        <a href="<?php echo site_url('suratkeluar/perintahtugas/delete_tembusan/'.$this->uri->segment(4).'/'.$o->tembusansurat_id) ?>" class='btn btn-danger btn-rounded' data-toggle='tooltip' data-placement='right' title='Hapus OPD' onclick="return confirm('Apakah anda yakin akan menghapus?')"><i class='fa fa-times'></i></a>
                                                    </p>
                                                <?php } }?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="panel-body faq">
                                        <div class="faq-item">
                                            <div class="faq-title"><span class="fa fa-angle-down"></span>Lihat List Tembusan Eksternal Pemkot</div>
                                            <div class="faq-text">
                                                <h5>List Tembusan Eksternal Pemkot: </h5>
                                                    <?php foreach (listEksternalTembusan($this->uri->segment(4)) as $key => $o) { ?>
                                                        <p>
                                                            - <?php echo $o->nama_tembusan ?> 
                                                            <a href="<?php echo site_url('suratkeluar/perintahtugas/delete_tembusan/'.$this->uri->segment(4).'/'.$o->tembusansurat_id) ?>" class='btn btn-danger btn-rounded' data-toggle='tooltip' data-placement='right' title='Hapus OPD' onclick="return confirm('Apakah anda yakin akan menghapus?')"><i class='fa fa-times'></i></a>
                                                        </p>
                                                    <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>    
                        </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                
                            </div>
                        </div>
                            
                        <br><div class="col-md-12">
                            <div class="form-group">                                        
                                <label class="col-md-2 control-label">Tembusan Internal Pemkot</label>
                                <div class="col-md-4">
                                    <!--<input type="text" name="tembusan" class="tagsinput" />-->
                                    <select multiple name="tembusan_id[]" class="form-control select" data-live-search="true">
                                        <?php 
                                                foreach (sendTembusanInt() as $key => $r) { 
                                                    $query = $this->db->query("SELECT nama_jabatan FROM jabatan WHERE jabatan_id = '$r->atasan_id'");
                                                    foreach($query->result() as $ky => $q){
                                        ?> 
                                                    <option value="<?php echo $r->jabatan_id ?>"><?php echo $q->nama_jabatan; ?></option>
                                        <?php } } ?>
                                    </select>
                                </div>
                                
                                <label class="col-md-2 control-label">Tembusan Eksternal Pemkot</label>
                                <div class="col-md-4">                                                                                
                                    
                                    <select multiple name="tembusaneks_id[]" class="form-control select" data-live-search="true">
                                        <?php foreach (sendTembusanEks($opdid) as $key => $h) { ?> 
                                            <option value="<?php echo $h->id ?>"><?php echo $h->nama_tembusan; ?></option>
                                        <?php } ?>
                                    </select>   
                                    <span class="help-block"><font color="red"> Kontak Admin</font> untuk menambahkan data tembusan eksternal</span>                                    

                                </div>
                            </div>
                            
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Lampiran Lainnya</label>
                                    <div class="col-md-10">
                                        <input type="file" name="lampiran_lain" class="fileinput btn-primary" id="filename" title="Cari File"/>
                                        <?php if(empty($e->lampiran_lain)){
                                            echo " <font color='red'><b>Tidak ada lampiran</b></font>";
                                        }else{ ?>
                                        <br><b><?= $e->lampiran_lain?></b>
                                        <br><a href="<?= base_url('assets/lampiransurat/perintah/'.$e->lampiran_lain)?>"> Lihat Surat</a>
                                        <?php }?>
                                        <span class="help-block">Format hanya berlaku pdf</span>
                                    </div>
                                </div>  
                                
                            </div>
                                
                            </div>
                            
                        </div>

                    <div class="panel-footer">
                        <button class="btn btn-danger" type="reset">Bersihkan Formulir</button>   
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
                        <button class="btn btn-danger" type="reset">Bersihkan Formulir</button>
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
<script type="text/javascript">
$(document).ready(function() {
    $("#chkall").click(function(){
        if($("#chkall").is(':checked')){
            $("#pegawai > option").prop("selected", "selected");
            $("#pegawai").trigger("change");
        } else {
            $("#pegawai > option").removeAttr("selected");
            $("#pegawai").trigger("change");
        }
    });
});
</script>
<script>
    function yesnoCheck() {
    if (document.getElementById('yesCheck').checked) {
        document.getElementById('ifYes').style.display = 'block';
    }
    else document.getElementById('ifYes').style.display = 'none';

}
</script>
<!-- Select all pegawai yang diperintahkan -->
<script type="text/javascript">
$(document).ready(function() {
    $("#chekall2").click(function(){
        if($("#chekall2").is(':checked')){
            $("#pegawai > option").prop("selected", "selected");
            $("#pegawai").trigger("change");
        } else {
            $("#pegawai > option").removeAttr("selected");
            $("#pegawai").trigger("change");
        }
    });
});
</script>
<!-- Select all pegawai yang diperintahkan -->