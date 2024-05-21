<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo site_url('home/dashboard') ?>">Beranda</a></li>                    
    <li><a href="javascript:void(0)">Naskah Dinas Surat</a></li>
    <li><a href="<?php echo site_url('suratkeluar/perjalanan') ?>">Surat Perjalanan Dinas</a></li>
    <li class="active">Formulir</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-envelope"></span> Formulir Surat Perjalanan Dinas</h2>
</div>
<!-- END PAGE TITLE -->                

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">                

    <div class="row">
        <div class="col-md-12"> 

            <?php if ($this->uri->segment(3) == 'add') { ?>
            
                <!-- START DEFAULT FORM -->
                <form class="form-horizontal" action="<?php echo site_url('suratkeluar/perjalanan/insert') ?>" method="post" enctype="multipart/form-data">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <a href="javascript:history.back()" class="btn btn-default">&laquo; Kembali</a> 
                        <a href="<?= site_url()?>assets/reviewsurat/PERJALANAN DINAS.pdf" target="_blank" class="btn btn-default" style="margin-left: 1%">Template Surat</a>
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
                                    <div class="col-md-3">                           
                                        <select multiple name="jabatan_id[]" id="jabatan" class="form-control select" data-live-search="true">
                                            <?php foreach (sendOpd() as $key => $h) { 
                                                $query = $this->db->query("SELECT nama_jabatan FROM jabatan WHERE jabatan_id = '$h->atasan_id'");
                                                foreach($query->result() as $ky => $q){
                                            ?> 
                                                <option value="<?php echo $h->jabatan_id ?>"><?php echo $h->nama_pd; echo ' - '.$q->nama_jabatan; ?></option>
                                            <?php } }?>
                                        </select>
                                        <span class="help-block"><font color="red"><b><?php echo $this->session->flashdata('value_tujuan') ?></b></font></span> 
                                    </div>
                                    <div class="col-md-1">
                                        <h3><input id="chkall" type="checkbox" style="width:17px; height:17px;"> Pilih Semua</h3>
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
                                    <label class="col-md-2 control-label">Tanggal</label>
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                            <input type="text" name="tanggal" class="form-control datepicker" value="<?php echo date('Y-m-d') ?>"/>
                                        </div>
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
                                    <label class="col-md-2 control-label">Nama/NIP Pelaksana</label>
                                    <div class="col-md-10">
                                        <select name="pegawai_id" class="form-control select" data-live-search="true" >
                                            <option value="">Pilih Aparatur</option>
                                            <?php foreach ($aparatur as $key => $h) { ?> 
                                                <option value="<?php echo $h->nip ?>" <?php if($h->nip == $this->session->flashdata('value_pegawai_id')) echo "selected"?>><?php echo $h->nip; ?> - <?php echo $h->nama ; ?></option>
                                            <?php } ?>
                                        </select>
                                        <span class="help-block"><font color="red"><?php echo $this->session->flashdata('pegawai_id') ?></font></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Tingkat Biaya</label>
                                    <div class="col-md-10">
                                        <input type="number" name="tingkat_biaya" class="form-control" value="<?php echo $this->session->flashdata('value_tingkat_biaya') ?>"/>
                                        <span class="help-block"><font color="red"><?php echo $this->session->flashdata('tingkat_biaya') ?></font></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Maksud Perjalanan</label>
                                    <div class="col-md-10">
                                        <input type="text" name="maksud_perjalanan" class="form-control" value="<?php echo $this->session->flashdata('value_maksud_perjalanan') ?>"/>
                                        <span class="help-block"><font color="red"><?php echo $this->session->flashdata('maksud_perjalanan') ?></font></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Alat Angkutan</label>
                                    <div class="col-md-10">
                                        <input type="text" name="alat_angkutan" class="form-control" value="<?php echo $this->session->flashdata('value_alat_angkutan') ?>"/>
                                        <span class="help-block"><font color="red"><?php echo $this->session->flashdata('alat_angkutan') ?></font></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Tempat Berangkat</label>
                                    <div class="col-md-10">
                                        <input type="text" name="tmpt_berangkat" class="form-control" value="<?php echo $this->session->flashdata('value_tmpt_berangkat') ?>"/>
                                        <span class="help-block"><font color="red"><?php echo $this->session->flashdata('tmpt_berangkat') ?></font></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Tempat Tujuan</label>
                                    <div class="col-md-10">
                                        <input type="text" name="tmpt_tujuan" class="form-control" value="<?php echo $this->session->flashdata('value_tmpt_tujuan') ?>"/>
                                        <span class="help-block"><font color="red"><?php echo $this->session->flashdata('tmpt_tujuan') ?></font></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Lama Perjalanan</label>
                                    <div class="col-md-10">
                                        <input type="text" name="lama_perjalanan" class="form-control" value="<?php echo $this->session->flashdata('value_lama_perjalanan') ?>"/>
                                        <span class="help-block"><font color="red"><?php echo $this->session->flashdata('lama_perjalanan') ?></font></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Tanggal Berangkat</label>
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                            <input type="text" name="tgl_berangkat" class="form-control datepicker" value="<?php echo date('Y-m-d') ?>"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Tanggal Pulang</label>
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                            <input type="text" name="tgl_pulang" class="form-control datepicker" value="<?php echo date('Y-m-d') ?>"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Pengikut</label>
                                    <div class="col-md-10">
                                        <select id="pengikut_id "name="pengikut_id[]"  multiple="multiple" class="form-control select" data-live-search="true">
                                            <option value="">Pilih Pegawai</option>
                                            <?php foreach ($pegawai as $key => $a) { ?> 
												<option value="<?php echo $a->aparatur_id ?>"><?php echo $a->nip; ?> - <?php echo $a->nama; ?></option>
											 <?php } ?>
                                        </select>
                                        <span class="help-block"><font color="red"><b><?php echo $this->session->flashdata('value_pengikut') ?></b></font></span>
                                    </div>
							  </div>								

                                <!-- <div class="form-group">
                                    <label class="col-md-2 control-label"></label>
                                    <div class="col-md-10">
									<table class="table table-responsive table-bordered">
										<thead>
											<tr>
												<th width="10%">NIP</th>
												<th width="20%">NAMA</th>
												<th width="20%">JABATAN</th>
											</tr>
										</thead>
										<tbody>

										<tbody>
									</table>	
                                    </div>
                                </div> -->                                
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Perangkat Daerah</label>
                                    <div class="col-md-10">
                                        <select name="perangkatdaerah_id" class="form-control select" data-live-search="true">
                                            <option value="">Pilih Perangkat Daerah</option>
                                            <?php foreach ($opd as $key => $h) { ?> 
                                                <option value="<?php echo $h->opd_id ?>" <?php if($h->opd_id == $this->session->flashdata('value_perangkatdaerah_id')) echo "selected"?>><?php echo $h->nama_pd ; ?></option>
                                            <?php } ?>
                                        </select>
                                        <span class="help-block"><font color="red"><?php echo $this->session->flashdata('perangkatdaerah_id') ?></font></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Keterangan lain-lain</label>
                                    <div class="col-md-10">
                                        <input type="text" name="keterangan" class="form-control" value="<?php echo $this->session->flashdata('value_keterangan') ?>"/>
                                        <span class="help-block"><font color="red"><?php echo $this->session->flashdata('keterangan') ?></font></span>
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
                foreach ($perjalanan as $key => $e) {
            ?>

                <!-- START DEFAULT FORM -->
                <form class="form-horizontal" action="<?php echo site_url('suratkeluar/perjalanan/update') ?>" method="post" enctype="multipart/form-data">
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
                                                        <a href="<?php echo site_url('suratkeluar/perjalanan/delete_kepada/'.$this->uri->segment(4).'/'.$o->dsuratkeluar_id) ?>" class='btn btn-danger btn-rounded' data-toggle='tooltip' data-placement='right' title='Hapus OPD' onclick="return confirm('Apakah anda yakin akan menghapus?')"><i class='fa fa-times'></i></a>
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
                                                            <a href="<?php echo site_url('suratkeluar/perjalanan/delete_kepada/'.$this->uri->segment(4).'/'.$o->dsuratkeluar_id) ?>" class='btn btn-danger btn-rounded' data-toggle='tooltip' data-placement='right' title='Hapus OPD' onclick="return confirm('Apakah anda yakin akan menghapus?')"><i class='fa fa-times'></i></a>
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
                                    <label class="col-md-2 control-label">Tanggal</label>
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                            <input type="text" name="tanggal" class="form-control datepicker" value="<?php echo $e->tanggal ?>"/>
                                        </div>
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
                                    <label class="col-md-2 control-label">Nama/NIP Pelaksana</label>
                                    <div class="col-md-10">
                                        <select name="pegawai_id" class="form-control select" data-live-search="true" required>
                                            <option value="">Pilih Aparatur</option>
                                            <?php foreach ($aparatur as $key => $h) { ?> 
                                                <option value="<?php echo $h->nip ?>" <?php if ($h->nip == $e->pegawai_id) { echo "selected"; } ?>>
                                                    <?php echo $h->nip; ?> - <?php echo $h->nama; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Tingkat Biaya</label>
                                    <div class="col-md-10">
                                        <input type="number" name="tingkat_biaya" class="form-control" value="<?php echo $e->tingkat_biaya ?>" required />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Maksud Perjalanan</label>
                                    <div class="col-md-10">
                                        <input type="text" name="maksud_perjalanan" class="form-control" value="<?php echo $e->maksud_perjalanan ?>" required />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Alat Angkutan</label>
                                    <div class="col-md-10">
                                        <input type="text" name="alat_angkutan" class="form-control" value="<?php echo $e->alat_angkutan ?>" required />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Tempat Berangkat</label>
                                    <div class="col-md-10">
                                        <input type="text" name="tmpt_berangkat" class="form-control" value="<?php echo $e->tmpt_berangkat ?>" required />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Tempat Tujuan</label>
                                    <div class="col-md-10">
                                        <input type="text" name="tmpt_tujuan" class="form-control" value="<?php echo $e->tmpt_tujuan ?>" required />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Lama Perjalanan</label>
                                    <div class="col-md-10">
                                        <input type="text" name="lama_perjalanan" class="form-control"  value="<?php echo $e->lama_perjalanan ?>"required />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Tanggal Berangkat</label>
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                            <input type="text" name="tgl_berangkat" class="form-control datepicker" value="<?php echo $e->tgl_berangkat ?>"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Tanggal Pulang</label>
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                            <input type="text" name="tgl_pulang" class="form-control datepicker" value="<?php echo $e->tgl_pulang ?>"/>
                                        </div>
                                    </div>
                                </div>
				                <div class="form-group">
                                    <label class="col-md-2 control-label">Pengikut</label>
                                    <div class="col-md-5">
                                        <select id="pengikut_id "name="pengikut_id[]" multiple="multiple" class="form-control select" data-live-search="true">
                                            <option value="">Pilih Pegawai</option>
                                            <?php foreach ($pegawai as $key => $a) { ?> 
												<?php $tampil=$e->pengikut_id;
                                                $print=explode(',',$tampil);?>
                                                <option value="<?php echo $a->aparatur_id ?>" <?php 
                                                $tampil=$e->pengikut_id;
                                                $print=explode(',',$tampil);
                                                foreach($print as $p){
                                                if ($a->aparatur_id == $p) { echo "selected"; }} ?>><?php echo $a->nama; ?> - <?php echo $a->nama_jabatan; ?> </option>
											 <?php }?>
                                        </select>
                                    </div>
				</div>								
                              	
				<?php if (empty($e->pengikut_id)) {?>
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
							</table>
						</div>	
                                    </div>
                                </div>

                                <?php }else{?>

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
								WHERE aparatur_id IN ($e->pengikut_id)")->result_array();
											
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
                                    <label class="col-md-2 control-label">Perangkat Daerah</label>
                                    <div class="col-md-10">
                                        <select name="perangkatdaerah_id" class="form-control select" data-live-search="true" required>
                                            <option value="">Pilih Perangkat Daerah</option>
                                            <?php foreach ($opd as $key => $h) { ?> 
                                                <option value="<?php echo $h->opd_id ?>" <?php if ($h->opd_id == $e->perangkatdaerah_id) { echo "selected"; } ?>><?php echo $h->nama_pd; ?> </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Keterangan lain-lain</label>
                                    <div class="col-md-10">
                                        <input type="text" name="keterangan" class="form-control" value="<?php echo $e->keterangan ?>" required />
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
                                                        <a href="<?php echo site_url('suratkeluar/perjalanan/delete_tembusan/'.$this->uri->segment(4).'/'.$o->tembusansurat_id) ?>" class='btn btn-danger btn-rounded' data-toggle='tooltip' data-placement='right' title='Hapus OPD' onclick="return confirm('Apakah anda yakin akan menghapus?')"><i class='fa fa-times'></i></a>
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
                                                            <a href="<?php echo site_url('suratkeluar/perjalanan/delete_tembusan/'.$this->uri->segment(4).'/'.$o->tembusansurat_id) ?>" class='btn btn-danger btn-rounded' data-toggle='tooltip' data-placement='right' title='Hapus OPD' onclick="return confirm('Apakah anda yakin akan menghapus?')"><i class='fa fa-times'></i></a>
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

            <?php } } ?>

        </div>
    </div>
</div>

<!-- Select all kepada internal -->
<script type="text/javascript">
$(document).ready(function() {
    $("#chkall").click(function(){
        if($("#chkall").is(':checked')){
            $("#jabatan > option").prop("selected", "selected");
            $("#jabatan").trigger("change");
        } else {
            $("#jabatan > option").removeAttr("selected");
            $("#jabatan").trigger("change");
        }
    });
});
</script>
<!-- Select all kepada internal -->