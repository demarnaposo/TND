<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="#">Beranda</a></li>                    
    <li><a href="#">Data Master</a></li>
    <li>Aparatur</li>
    <li class="active">Formulir</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-user"></span> Formulir Data Aparatur</h2>
</div>
<!-- END PAGE TITLE -->                

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">                

    <div class="row">
        <div class="col-md-12">

            <div class="alert alert-danger" role="alert">
                <h4><b>Keterangan : NIP dan NIK harus diisi harap tidak</b> input dengan tanda garis strip (-), bisa input angka 0 untuk mengosongkan data.</h4>
            </div>

            <?php if ($this->uri->segment(3) == 'add') { ?>
            
                <!-- START DEFAULT FORM -->
                <form class="form-horizontal" action="<?php echo site_url('master/aparatur/insert') ?>" method="post" enctype="multipart/form-data">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <a href="<?php echo site_url('master/aparatur') ?>" class="btn btn-default">&laquo; Kembali</a> <br><br>
                    </div>
                    <div class="panel-body">                                                                        
                        
                        <div class="row">
                            
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Nip</label>
                                    <div class="col-md-10">
                                        <input type="text" name="nip" class="form-control" value="0" required />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">NIK</label>
                                    <div class="col-md-10">
                                        <input type="number" name="nik" class="form-control" required />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Nama</label>
                                    <div class="col-md-10">
                                        <input type="text" name="nama" class="form-control" required />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">No. Telepon</label>
                                    <div class="col-md-10">
                                        <input type="text" name="nohp" class="form-control" required />
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Jabatan</label>
                                    <div class="col-md-10">
                                        <select name="jabatan_id" class="form-control select" data-live-search="true" required>
                                            <option value="">Pilih Jabatan</option>
                                            <?php foreach ($jabatan as $key => $h) { ?> 
                                                <option value="<?php echo $h->jabatan_id ?>"><?php echo $h->nama_jabatan; ?> - <?php echo $h->nama_pd; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">                                        
                                    <label class="col-md-2 control-label">Eselon</label>
                                    <div class="col-md-10">
                                        <input type="text" name="eselon" class="form-control" />
                                    </div>
                                </div>

                                <div class="form-group">                                        
                                    <label class="col-md-2 control-label">Pangkat</label>
                                    <div class="col-md-10">
                                        <input type="text" name="pangkat" class="form-control" />
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Golongan</label>
                                    <div class="col-md-10">
                                        <input type="text" name="golongan" class="form-control" />
                                    </div>
                                </div>

				                <div class="form-group">
                                    <label class="col-md-2 control-label">Status</label>
                                    <div class="col-md-10">
                                        <select name="statusaparatur" class="form-control select" data-live-search="true" required>
                                            <option value="">Pilih Status</option>
                                            <option value="Aktif">Aktif</option>
                                            <option value="Pensiun">Pensiun</option>
                                            <option value="Meninggal">Meninggal</option>
					                        <option value="Tidak Aktif">Tidak Aktif</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Level</label>
                                    <div class="col-md-10">
                                        <select name="level_id" class="form-control select" data-live-search="true" required>
                                            <option value="">Pilih Level</option>
                                            <?php foreach ($level as $key => $lvl) { ?> 
                                                <option value="<?php echo $lvl->level_id ?>"><?php echo $lvl->level; ?></option>
                                            <?php } ?>
                                        </select>
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
                }elseif ($this->uri->segment(3) == 'pindah') { 
            ?>
            
            <!-- START DEFAULT FORM -->
                <form class="form-horizontal" action="<?php echo site_url('master/aparatur/pindahaparatur') ?>" method="post" enctype="multipart/form-data">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <a href="<?php echo site_url('master/aparatur') ?>" class="btn btn-default">&laquo; Kembali</a> <br><br>
                    </div>
                    <div class="panel-body">                                                                        
                        
                        <div class="row">
                            
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Perangkat Daerah Asal</label>
                                    <div class="col-md-10">
                                        <select name="opd_id" id="opd_id" class="form-control select" data-live-search="true" required>
                                        <option value="" selected disabled> Pilih Perangkat Daerah Asal</option>
                                        <?php foreach ($pindahopd as $key => $o) { ?>
                                        <option value="<?php echo $o->opd_id ?>"><?php echo $o->nama_pd; ?></option>
                                        <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group" >
                                    <label class="col-md-2 control-label">Daftar Aparatur</label>
                                    <div class="col-md-10">
                                        <select name="aparatur_id" id="aparatur_id" class="form-control "  data-live-search="true" required>
                                        <option value=""> Pilih Aparatur </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Jabatan</label></center>
                                    <div class="col-md-10">
                                        <select name="jabatan_id" class="form-control select" data-live-search="true" required>
                                        <option value="" selected disabled> Pilih Jabatan </option>
                                        <?php foreach ($pindahjabatan as $key => $j) { ?>
                                        <option value="<?php echo $j->jabatan_id ?>"><?php echo $j->nama_jabatan; ?></option>
                                        <?php } ?>
                                        </select>
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
                foreach ($aparatur as $key => $e) {
            ?>

                <!-- START DEFAULT FORM -->
                <form class="form-horizontal" action="<?php echo site_url('master/aparatur/update') ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="aparatur_id" value="<?php echo $e->aparatur_id ?>" />
                <div class="panel panel-default">
                    <div class="panel-body">
                        <a href="<?php echo site_url('master/aparatur') ?>" class="btn btn-default">&laquo; Kembali</a> <br><br>
                    </div>
                    <div class="panel-body">                                                                        
                        
                        <div class="row">
                            
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Nip</label>
                                    <div class="col-md-10">
                                        <input type="text" name="nip" class="form-control" value="<?php echo $e->nip ?>" required />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">NIK</label>
                                    <div class="col-md-10">
                                        <input type="number" name="nik" class="form-control" value="<?php echo $e->nik ?>" required />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Nama</label>
                                    <div class="col-md-10">
                                        <input type="text" name="nama" class="form-control" value="<?php echo $e->nama ?>" required />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">No. Telepon</label>
                                    <div class="col-md-10">
                                        <input type="text" name="nohp" class="form-control" value="<?php echo $e->nohp ?>" required />
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Jabatan</label>
                                    <div class="col-md-10">
                                        <select name="jabatan_id" class="form-control select" data-live-search="true" required />
                                            <option value="">Pilih Jabatan</option>
                                            <?php foreach ($jabatan as $key => $h) { ?> 
                                                <option value="<?php echo $h->jabatan_id ?>" <?php if ($h->jabatan_id == $e->jabatan_id) { echo "selected"; } ?>>
                                                <?php echo $h->nama_jabatan; ?> - <?php echo $h->nama_pd; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Eselon</label>
                                    <div class="col-md-10">
                                        <input type="text" name="eselon" class="form-control" value="<?php echo $e->eselon ?>" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Pangkat</label>
                                    <div class="col-md-10">
                                        <input type="text" name="pangkat" class="form-control" value="<?php echo $e->pangkat ?>" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Golongan</label>
                                    <div class="col-md-10">
                                        <input type="text" name="golongan" class="form-control" value="<?php echo $e->golongan ?>" />
                                    </div>
                                </div>

				                <div class="form-group">
                                    <label class="col-md-2 control-label">Status</label>
                                    <div class="col-md-10">
                                        <select name="statusaparatur" class="form-control select" data-live-search="true" required />
                                            <option value="">Pilih Status</option>
                                            <?php foreach ($aparatur as $key => $h) { ?> 
                                                <?php if ($h->statusaparatur == "Aktif")
                                                {
                                                    echo "<option value='Aktif' selected>Aktif</option>";
                                                }   
                                                    else { echo "<option value='Aktif'>Aktif</option>";
                                                }
                                                ?>
                                                <?php if ($h->statusaparatur == "Pensiun")
                                                {
                                                    echo "<option value='Pensiun' selected>Pensiun</option>";
                                                }
                                                    else { echo "<option value='Pensiun'>Pensiun</option>";
                                                }
                                                ?>
                                                <?php if ($h->statusaparatur == "Meninggal")
                                                {
                                                    echo "<option value='Meninggal' selected>Meninggal</option>";
                                                }    
                                                    else { echo "<option value='Meninggal'>Meninggal</option>";
                                                }
                                                ?>
						                        <?php if ($h->statusaparatur == "Tidak Aktif")
                                                {
                                                    echo "<option value='Tidak Aktif' selected>Tidak Aktif</option>";
                                                }    
                                                    else { echo "<option value='Tidak Aktif'>Tidak Aktif</option>";
                                                }
                                                ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Level</label>
                                    <div class="col-md-10">
                                        <select name="level_id" class="form-control select" data-live-search="true" required />
                                            <option value="">Pilih Level</option>
                                            <?php foreach ($level as $key => $lvl) { ?> 
                                                <option value="<?php echo $lvl->level_id ?>" <?php if ($lvl->level_id == $e->level_id) { echo "selected"; } ?>>
                                                    <?php echo $lvl->level; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
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
                }elseif ($this->uri->segment(3) == 'addadmin') {
            ?>

                <!-- START DEFAULT FORM -->
                <form class="form-horizontal" action="<?php echo site_url('master/aparatur/insertadmin') ?>" method="post" enctype="multipart/form-data">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <a href="<?php echo site_url('master/aparatur') ?>" class="btn btn-default">&laquo; Kembali</a> <br><br>
                    </div>
                    <div class="panel-body">                                                                        
                        
                        <div class="row">
                            
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Nama</label>
                                    <div class="col-md-10">
                                        <input type="text" name="nama" class="form-control" required />
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Jabatan</label>
                                    <div class="col-md-10">
                                        <select name="jabatan_id" class="form-control select" data-live-search="true">
                                            <option value="">Pilih Jabatan</option>
                                            <?php foreach ($jabatan as $key => $h) { ?> 
                                                <option value="<?php echo $h->jabatan_id ?>"><?php echo $h->nama_jabatan; ?> - <?php echo $h->nama_pd; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

				<div class="form-group">
                                    <label class="col-md-2 control-label">Status</label>
                                    <div class="col-md-10">
                                        <select name="statusaparatur" class="form-control select" data-live-search="true" required>
                                            <option value="">Pilih Status</option>
                                            <option value="Aktif">Aktif</option>
                                            <option value="Pensiun">Pensiun</option>
                                            <option value="Meninggal">Meninggal</option>
					    <option value="Tidak Aktif">Tidak Aktif</option>
                                        </select>
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
                }elseif ($this->uri->segment(3) == 'editadmin') {
                    foreach ($aparatur as $key => $e) {
            ?>

                <!-- START DEFAULT FORM -->
                <form class="form-horizontal" action="<?php echo site_url('master/aparatur/updateadmin') ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="aparatur_id" value="<?php echo $e->aparatur_id ?>" />
                <div class="panel panel-default">
                    <div class="panel-body">
                        <a href="<?php echo site_url('master/aparatur') ?>" class="btn btn-default">&laquo; Kembali</a> <br><br>
                    </div>
                    <div class="panel-body">                                                                        
                        
                        <div class="row">
                            
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Nama</label>
                                    <div class="col-md-10">
                                        <input type="text" name="nama" class="form-control" value="<?php echo $e->nama ?>" required />
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Jabatan</label>
                                    <div class="col-md-10">
                                        <select name="jabatan_id" class="form-control select" data-live-search="true" />
                                            <option value="">Pilih Jabatan</option>
                                            <?php foreach ($jabatan as $key => $h) { ?> 
                                                <option value="<?php echo $h->jabatan_id ?>" <?php if ($h->jabatan_id == $e->jabatan_id) { echo "selected"; } ?>>
                                                    <?php echo $h->nama_jabatan; ?> - <?php echo $h->nama_pd; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

				<div class="form-group">
                                    <label class="col-md-2 control-label">Status</label>
                                    <div class="col-md-10">
                                        <select name="statusaparatur" class="form-control select" data-live-search="true" required />
                                            <option value="">Pilih Status</option>
                                            <?php foreach ($aparatur as $key => $h) { ?> 
                                                <?php if ($h->statusaparatur == "Aktif")
                                                {
                                                    echo "<option value='Aktif' selected>Aktif</option>";
                                                }   
                                                    else { echo "<option value='Aktif'>Aktif</option>";
                                                }
                                                ?>
                                                <?php if ($h->statusaparatur == "Pensiun")
                                                {
                                                    echo "<option value='Pensiun' selected>Pensiun</option>";
                                                }
                                                    else { echo "<option value='Pensiun'>Pensiun</option>";
                                                }
                                                ?>
                                                <?php if ($h->statusaparatur == "Meninggal")
                                                {
                                                    echo "<option value='Meninggal' selected>Meninggal</option>";
                                                }    
                                                    else { echo "<option value='Meninggal'>Meninggal</option>";
                                                }
                                                ?>
						<?php if ($h->statusaparatur == "Tidak Aktif")
                                                {
                                                    echo "<option value='Tidak Aktif' selected>Tidak Aktif</option>";
                                                }    
                                                    else { echo "<option value='Tidak Aktif'>Tidak Aktif</option>";
                                                }
                                                ?>
                                            <?php } ?>
                                        </select>
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
                }
            ?>

        </div>
    </div>
</div>

<script src="<?php echo base_url('/assets/js/plugins/jquery/jquery.min.js'); ?>"></script>
<script type="text/javascript">
        $(document).ready(function(){
 
            $('#opd_id').change(function(){ 
                var opd_id=$(this).val();
                if(opd_id !=''){
                  $.ajax({
                      url : "<?php echo site_url('master/aparatur/get_pindahaparatur')?>",
                      method : "POST",
                      data : {opd_id: opd_id},
                      async : true,
                      dataType : 'json',
                      success: function(data){
                           
                          var html = '';
                          var i;
                          for(i=0; i<data.length; i++){
                              html += '<option value='+data[i].aparatur_id+'>'+data[i].nama+'</option>';
                          }
                          $('#aparatur_id').html(html);
   
                      }
                  });
                }else{
                  $('#aparatur_id').html('<option value="">Pilih Aparatur</option>');
                }
                return false;
            }); 
        });
</script>