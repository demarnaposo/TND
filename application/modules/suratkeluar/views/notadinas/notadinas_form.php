<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo site_url('home/dashboard') ?>">Beranda</a></li>                    
    <li><a href="javascript:void(0)">Naskah Dinas Surat</a></li>
    <li><a href="<?php echo site_url('suratkeluar/notadinas') ?>">Surat Nota Dinas</a></li>
    <li class="active">Formulir</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-envelope"></span> Formulir Surat Nota Dinas</h2>
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
                        <a href="javascript:history.back()" class="btn btn-default">&laquo; Kembali</a> 
                        <a href="<?= site_url()?>assets/reviewsurat/NOTA DINAS.pdf" target="_blank" class="btn btn-default" style="margin-left: 1%">Template Surat</a>
                    </div>
                    <div class="panel-body">                                                                         
                        <div class="row">
                            <div class="col-md-12">
                                <!-- [UPDATE] Fikri Egov 26012022 16:12Pm Pengkondisian Jika Surat Nota Dinas, Maka Penandatanganan Si Pembuatnya-->
                                <!-- Update @Mpik Egov 15/06/2022 09:49 : Penghapusan field kepada internal dan eksternal -->
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Kepada</label>
                                    <div class="col-md-10">
                                        <select multiple name="jabatan_id[]" class="form-control select" data-live-search="true" >
                                            <option value="">Pilih Jabatan</option>
                                            <?php foreach ($jabatan as $key => $h) { ?>
                                                <option value="<?php echo $h->jabatan_id ?>"><?php echo $h->nama; ?> - <?php echo $h->nama_jabatan; ?></option>
                                            <?php } ?>
                                        </select>
                                        <span class="help-block"><font color="red"><b><?php echo $this->session->flashdata('value_jabatan_tujuan') ?></b></font></span>
                                    </div>
                                </div>
                                <!-- END -->
                                <!-- Update @Mpik Egov 15/06/2022 09:49 : Penghapusan field kepada internal dan eksternal -->
                                <!-- [UPDATE] Fikri Egov 26012022 16:12Pm Pengkondisian Jika Surat Nota Dinas, Maka Penandatanganan Si Pembuatnya-->
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Kop Surat</label>
                                    <div class="col-md-10">
                                        <select name="kop_id" class="form-control select" data-live-search="true" />
                                            <option value="">Pilih Kop Surat</option>
                                            <?php foreach ($kop as $key => $h) { ?> 
                                                <option value="<?php echo $h->kop_id ?>" <?php if($h->kop_id == $this->session->flashdata('value_kop_id')) echo "selected"?>><?php echo $h->nama; ?></option>
                                            <?php } ?>
                                        </select>
                                        <span class="help-block"><font color="red"><?php echo $this->session->flashdata('kop_id') ?></font></span>
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
                                    <label class="col-md-2 control-label">Sifat</label>
                                    <div class="col-md-10">                                                                                
                                        <select class="form-control select" name="sifat" data-live-search="true" >
                                            <option value="">Pilih Sifat</option>
                                            <option value="Biasa" <?php if($this->session->flashdata('value_sifat') == "Biasa") echo "selected"?>>Biasa</option>
                                            <option value="Rahasia" <?php if($this->session->flashdata('value_sifat') == "Rahasia") echo "selected"?>>Rahasia</option>
                                            <option value="Segera" <?php if($this->session->flashdata('value_sifat') == "Segera") echo "selected"?>>Segera</option>
                                            <option value="Sangat Segera" <?php if($this->session->flashdata('value_sifat') == "Sangat Segera") echo "selected"?>>Sangat Segera</option>
                                            <option value="Rahasia dan Segera" <?php if($this->session->flashdata('value_sifat') == "Rahasia dan Segera") echo "selected"?>>Rahasia dan Segera</option>
                                            <option value="Rahasia dan Sangat Segera" <?php if($this->session->flashdata('value_sifat') == "Rahasia dan Sangat Segera") echo "selected"?>>Rahasia dan Sangat Segera</option>
                                        </select>
                                        <span class="help-block"><font color="red"><?php echo $this->session->flashdata('sifat') ?></font></span>
                                    </div>
                                </div>
                                <div class="form-group">                                        
                                    <label class="col-md-2 control-label">Lampiran</label>
                                    <div class="col-md-10">
                                        <input type="text" name="lampiran" class="form-control" value="<?php echo $this->session->flashdata('value_lampiran') ?>"/>
                                        <span class="help-block"><font color="red"><?php echo $this->session->flashdata('lampiran') ?></font></span>
                                    </div>
                                </div>
                                <div class="form-group">                                        
                                    <label class="col-md-2 control-label">Hal</label>
                                    <div class="col-md-10">
                                        <input type="text" name="hal" class="form-control" value="<?php echo $this->session->flashdata('value_hal') ?>"/>
                                        <span class="help-block"><font color="red"><?php echo $this->session->flashdata('hal') ?></font></span>
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
                                    <label class="col-md-2 control-label">Isi</label>
                                    <div class="col-md-10">
                                        <textarea id="textarea1" name="isi"><?php echo $this->session->flashdata('value_isi') ?></textarea>
                                        <span class="help-block"><font color="red"><?php echo $this->session->flashdata('isi') ?></font></span>
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
                                        <input type="file" name="lampiran_lain" class="fileinput btn-primary" id="filename" title="Cari file" accept="application/pdf" />
                                        <span class="help-block"><font color="red"><b><?php echo $this->session->flashdata('value_lain') ?></b></font></span>
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
                             <!-- [UPDATE] Fikri Egov 2 Feb 2022 -->
                             <!-- Update @Mpik Egov 15/06/2022 09:49 : Penghapusan field kepada internal dan eksternal -->
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Kepada</label>
                                    <div class="col-md-10">
                                        <select multiple name="jabatan_id[]" class="form-control select" data-live-search="true" >
                                            <option value="">Pilih Jabatan</option>
                                            <?php foreach ($jabatan as $key => $h) { ?>
                                                <option value="<?php echo $h->jabatan_id ?>"><?php echo $h->nama; ?> - <?php echo $h->nama_jabatan; ?></option>
                                            <?php } ?>
                                        </select><br><br>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Kepada Yang Dipilih</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $jabatanid=$this->db->query("SELECT * FROM disposisi_suratkeluar LEFT JOIN jabatan ON jabatan.jabatan_id=disposisi_suratkeluar.users_id LEFT JOIN aparatur ON aparatur.jabatan_id=jabatan.jabatan_id WHERE surat_id='$e->surat_id' AND aparatur.statusaparatur='Aktif'")->result();
                                                $no=1;
                                                foreach($jabatanid as $key => $j){
                                                if(substr($j->nama_jabatan, 0,7) == 'admintu'){ }else{
                                                ?>
                                                <tr>
                                                <td><?php echo $no; ?></td>
                                                <td><?php echo $j->nama ?> - <?php echo $j->nama_jabatan ?></td>
                                                <td>
                                                    <a href="<?php echo site_url('suratkeluar/notadinas/delete_kepada/'.$this->uri->segment(4).'/'.$j->dsuratkeluar_id) ?>" data-toggle="tooltip" data-placement="top" title="Hapus Data" onclick="return confirm('Apakah anda yakin akan menghapus?')"><span class="fa-stack"><i class="fa fa-trash-o fa-stack-2x"></i></span></a>
                                                </td>
                                                </tr>
                                                <?php $no++; } } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- END -->
                                <!-- Update @Mpik Egov 15/06/2022 09:49 : Penghapusan field kepada internal dan eksternal -->
                                 <!-- [UPDATE] Fikri Egov 2 Feb 2022 -->
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Kop Surat</label>
                                    <div class="col-md-10">
                                        <select name="kop_id" class="form-control select" data-live-search="true" required>
                                            <option value="">Pilih Kop Surat</option>
                                            <?php
                                            foreach ($kop as $key => $h) { ?> 
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
                                    <label class="col-md-2 control-label">Sifat</label>
                                    <div class="col-md-10">                                                                                
                                        <select class="form-control select" name="sifat" data-live-search="true" >
                                            <option value="">Pilih Sifat</option>
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
                                    <label class="col-md-2 control-label">Hal</label>
                                    <div class="col-md-10">
                                        <input type="text" name="hal" class="form-control" value="<?php echo $e->hal ?>"  />
                                    </div>
                                </div>            
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Isi</label>
                                    <div class="col-md-10">
                                        <textarea id="textarea1" name="isi"><?php echo $e->isi ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">                                        
                                    <label class="col-md-2 control-label">Catatan</label>
                                    <div class="col-md-10">
                                        <textarea id="catatan" name="catatan"><?php echo $e->catatan ?></textarea>
                                    </div>
                                </div>
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
                                                        <a href="<?php echo site_url('suratkeluar/notadinas/delete_tembusan/'.$this->uri->segment(4).'/'.$o->tembusansurat_id) ?>" class='btn btn-danger btn-rounded' data-toggle='tooltip' data-placement='right' title='Hapus OPD' onclick="return confirm('Apakah anda yakin akan menghapus?')"><i class='fa fa-times'></i></a>
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
                                                            <a href="<?php echo site_url('suratkeluar/notadinas/delete_tembusan/'.$this->uri->segment(4).'/'.$o->tembusansurat_id) ?>" class='btn btn-danger btn-rounded' data-toggle='tooltip' data-placement='right' title='Hapus OPD' onclick="return confirm('Apakah anda yakin akan menghapus?')"><i class='fa fa-times'></i></a>
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
                                        <input type="file" name="lampiran_lain" class="fileinput btn-primary" title="Cari file" accept="application/pdf" />
                                        <span class="help-block">Format hanya berlaku pdf : <a href="<?php echo base_url('assets/lampiransurat/notadinas/') . $e->lampiran_lain; ?>"><?= $e->lampiran_lain; ?></span>
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