<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo site_url('home/dashboard') ?>">Beranda</a></li>                    
    <li><a href="javascript:void(0)">Naskah Dinas Surat</a></li>
    <li><a href="<?php echo site_url('suratkeluar/instruksi') ?>">Surat instruksi</a></li>
    <li class="active">Formulir</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-envelope"></span> Formulir Surat Instruksi Wali Kota</h2>
</div>
<!-- END PAGE TITLE -->                

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">                

    <div class="row"> 
        <div class="col-md-12">

            <?php if ($this->uri->segment(3) == 'add') { ?>
            
                <!-- START DEFAULT FORM -->
                <form class="form-horizontal" action="<?php echo site_url('suratkeluar/instruksi/insert') ?>" method="post" enctype="multipart/form-data">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <a href="javascript:history.back()" class="btn btn-default">&laquo; Kembali</a>
                        <a href="<?= site_url()?>assets/reviewsurat/FORMAT INSTRUKSI WALI KOTA.pdf" target="_blank" class="btn btn-default" style="margin-left: 1%">Template Surat</a>
                    </div>
                    <div class="panel-body">                                                                        
                        
                        <div class="row">
                            
                            <div class="col-md-12">
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
                                    <label class="col-md-2 control-label">Tentang</label>
                                    <div class="col-md-10">
                                        <input type="text" name="tentang" class="form-control tentanglist" value="<?php echo $this->session->flashdata('value_tentang') ?>"/>
                                        <span class="help-block"><font color="red"><?php echo $this->session->flashdata('tentang') ?></font></span>
                                    </div>
                                </div>
				                <div class="form-group">                                        
                                    <label class="col-md-2 control-label">Dalam Rangka</label>
                                    <div class="col-md-10">
                                        <input type="text" name="dalamrangka" class="form-control" value="<?php echo $this->session->flashdata('value_dalamrangka') ?>"/>
                                        <span class="help-block"><font color="red"><?php echo $this->session->flashdata('dalamrangka') ?></font></span>
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
                                <div class="form-group">
                                    <label class="col-md-2 control-label">ISI SURAT</label>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <textarea id="textarea1" name="isi" rows="6"></textarea>
                                        </div>
                                        <div class="col-md-1">
                                            <button name="add" id="add" class="btn btn-primary pull-left" type="button">Tambah</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">                                        
                                    <label class="col-md-2 control-label">Catatan</label>
                                    <div class="col-md-8">
                                        <textarea id="catatan" name="catatan"><?php echo $this->session->flashdata('value_catatan') ?></textarea>
                                    </div>
                                </div>
                                <div id="dynamic_field"></div>
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
                $no=1;
                foreach ($instruksi as $key => $e) {
            ?>

                <!-- START DEFAULT FORM -->
                <form class="form-horizontal" action="<?php echo site_url('suratkeluar/instruksi/update') ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $e->id ?>" />
                <div class="panel panel-default">
                    <div class="panel-body">
                        <a href="javascript:history.back()" class="btn btn-default">&laquo; Kembali</a> 
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
                                                <?php foreach (listOpd($this->uri->segment(4)) as $key => $o) { ?>
                                                    <p>
                                                        - <?php echo $o->nama_pd ?> 
                                                        <a href="<?php echo site_url('suratkeluar/instruksi/delete_kepada/'.$this->uri->segment(4).'/'.$o->dsuratkeluar_id) ?>" class='btn btn-danger btn-rounded' data-toggle='tooltip' data-placement='right' title='Hapus OPD' onclick="return confirm('Apakah anda yakin akan menghapus?')"><i class='fa fa-times'></i></a>
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
                                            <div class="faq-title"><span class="fa fa-angle-down"></span>Lihat List Kepada Eksternal Pemkot</div>
                                            <div class="faq-text">
                                                <h5>List Kepada Eksternal Pemkot : </h5>
                                                    <?php foreach (listEksternal($this->uri->segment(4)) as $key => $o) { ?>
                                                        <p>
                                                            - <?php echo $o->nama ?> 
                                                            <a href="<?php echo site_url('suratkeluar/instruksi/delete_kepada/'.$this->uri->segment(4).'/'.$o->dsuratkeluar_id) ?>" class='btn btn-danger btn-rounded' data-toggle='tooltip' data-placement='right' title='Hapus OPD' onclick="return confirm('Apakah anda yakin akan menghapus?')"><i class='fa fa-times'></i></a>
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
                                    <label class="col-md-2 control-label">Kpd.Internal Pemkot</label>
                                    <div class="col-md-3">                           
                                        <select multiple name="jabatan_id[]" class="form-control select" data-live-search="true">
                                            <?php foreach (sendOpd() as $key => $h) { 
                                                $query = $this->db->query("SELECT nama_jabatan FROM jabatan WHERE jabatan_id = '$h->atasan_id'");
                                                foreach($query->result() as $ky => $q){
                                            ?> 
                                                <option value="<?php echo $h->jabatan_id ?>"><?php echo $h->nama_pd; echo ' - '.$q->nama_jabatan; ?></option>
                                            <?php } }?>
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
                                    <label class="col-md-2 control-label">Tentang</label>
                                    <div class="col-md-10">
                                        <input type="text" name="tentang" class="form-control" value="<?php echo $e->tentang ?>"  />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Lampiran Lainnya</label>
                                    <div class="col-md-10">
                                        <input type="file" name="lampiran_lain" class="fileinput btn-primary" title="Cari file" accept="application/pdf" />
                                        <span class="help-block">Format hanya berlaku pdf</span>
                                    </div>
                                </div>
                                <?php foreach($isi_surat as $key => $s){?>
                                <input type="hidden" name="isi_id[]" value="<?= $s->isi_id ?>">
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Untuk</label>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <textarea id="summernote<?= $no++;?>" name="isi[]" rows="6"><?= $s->isi;?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">                                        
                                    <label class="col-md-2 control-label">Catatan</label>
                                    <div class="col-md-8">
                                        <textarea id="catatan" name="catatan"><?php echo $e->catatan ?></textarea>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-2 control-label"></label>
                                    <div class="col-md-4">
                                        <a href="<?php echo site_url('suratkeluar/instruksi/delete_data/'.$this->uri->segment(4).'/'.$s->isi_id) ?>" class="btn btn-primary">HAPUS</a>
                                    </div>
                                </div>
                                <?php }?>
                                
                                <div class="form-group">
                                    <label class="col-md-2 control-label"></label>
                                    <div class="col-md-1">
                                        <a href="#" class="btn btn-primary pull-left" data-toggle="modal" data-target="#staticBackdrop">Tambah</a>
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
                
                <!-- Modal -->
                <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Penambahan Data</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="<?php echo site_url('suratkeluar/instruksi/insert_isi') ?>" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="surat_id" value="<?= $e->id ?>">
                            <div class="form-group">
                                <label class="col-md-2 control-label">Untuk</label>
                                <div class="row">
                                    <div class="col-md-8">
                                        <textarea id="isi" name="isi" rows="6"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">                                        
                                    <label class="col-md-2 control-label">Catatan</label>
                                    <div class="col-md-8">
                                        <textarea id="catatan" name="catatan"><?php echo $e->catatan ?></textarea>
                                    </div>
                                </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </form>
                    </div>
                </div>
                </div>
                <!-- End Modal -->

            <?php } } ?>

        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo base_url('assets/js/plugins/jquery/jquery.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/plugins/summernote/summernote.js') ?>"></script>

<script>  
 $(document).ready(function(){  
      var i=1; 
        // $(document).ready(function() {
        //     $('.textarea').summernote();
        // });
      $('#add').click(function(){  
          i++;  
          if(i == 1){
              urut = "KESATU";
          }else if(i == 2){
                urut = "KEDUA";
          }else if(i == 3){
                urut = "KETIGA";
          }else if(i == 4){
                urut = "KEEMPAT";
          }else if(i == 5){
                urut = "KELIMA";
          }else if(i == 6){
                urut = "KEENAM";
          }else if(i == 7){
                urut = "KETUJUH";
          }else if(i == 8){
                urut = "KEDELAPAN";
          }else if(i == 9){
                urut = "KESEMBILAN";
          }else if(i == 10){
                urut = "KESEPULUH";
          }else{
                urut ="";
          }
          var html=["<div class='form-group' id='row"+i+"'><label class='col-md-2 control-label'></label><div class='row'><div class='col-md-8'><textarea id='summernote"+i+"' name='isi[]' rows='6'></textarea></div> <div class='col-md-1'><button name='remove' id='"+i+"' class='btn btn-primary pull-left btn_remove' type='button'>Hapus</button> </div> </div></div>"]
            
           
          $('#dynamic_field').append(html);
          $('#summernote' + i).summernote({
                placeholder: 'Hello stand alone ui',
                tabsize: 2,
                height: 120,
                toolbar: [
                  ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
            i++;
        //   $('#showeditor'+i).summernote();
            // $('#showeditor').find('.textarea').summernote()
      });  
      $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");
           $('#row'+button_id+'').remove();  
      });    
 });  
 </script>

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