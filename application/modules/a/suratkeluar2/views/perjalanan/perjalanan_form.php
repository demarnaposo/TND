<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo site_url('home/dashboard') ?>">Dashboard</a></li>                    
    <li><a href="javascript:void(0)">Naskah Dinas Surat</a></li>
    <li><a href="<?php echo site_url('suratkeluar/perjalanan') ?>">Surat Perjalanan Dinas</a></li>
    <li class="active">Form</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-envelope"></span> Form Surat Perjalanan Dinas</h2>
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
                        <a href="javascript:history.back()" class="btn btn-default">&laquo; Kembali</a> <br><br>
                    </div>
                    <div class="panel-body">                                                                        
                        
                        <div class="row">
                            
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
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Kode Surat</label>
                                    <div class="col-md-10">
                                        <select name="kodesurat_id" class="form-control select" data-live-search="true" required>
                                            <option value="">Pilih Kode Surat</option>
                                            <?php foreach ($kodesurat as $key => $h) { ?> 
                                                <option value="<?php echo $h->kodesurat_id ?>"><?php echo $h->kode; ?> - <?php echo substr($h->tentang, 0,130); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Nama/NIP Pelaksana</label>
                                    <div class="col-md-10">
                                        <select name="nip_id" class="form-control select" data-live-search="true" required>
                                            <option value="">Pilih Aparatur</option>
                                            <?php foreach ($aparatur as $key => $h) { ?> 
                                                <option value="<?php echo $h->nip ?>"><?php echo $h->nip; ?> - <?php echo $h->nama ; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Tingkat Biaya</label>
                                    <div class="col-md-10">
                                        <input type="number" name="tingkat_biaya" class="form-control" required />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Maksud Perjalanan</label>
                                    <div class="col-md-10">
                                        <input type="text" name="maksud_perjalanan" class="form-control" required />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Alat Angkutan</label>
                                    <div class="col-md-10">
                                        <input type="text" name="alat_angkutan" class="form-control" required />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Tempat Berangkat</label>
                                    <div class="col-md-10">
                                        <input type="text" name="tmpt_berangkat" class="form-control" required />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Tempat Tujuan</label>
                                    <div class="col-md-10">
                                        <input type="text" name="tmpt_tujuan" class="form-control" required />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Lama Perjalanan</label>
                                    <div class="col-md-10">
                                        <input type="text" name="lama_perjalanan" class="form-control" required />
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
                                    <label class="col-md-2 control-label">Kegiatan</label>
                                    <div class="col-md-10">
                                        <input type="text" name="kegiatan" class="form-control" required />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Akun</label>
                                    <div class="col-md-10">
                                        <input type="text" name="akun" class="form-control" required />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Keterangan lain-lain</label>
                                    <div class="col-md-10">
                                        <input type="text" name="keterangan" class="form-control" required />
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
                                
                                <div class="form-group">
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
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Nama/NIP Pelaksanan</label>
                                    <div class="col-md-10">
                                        <select name="nip_id" class="form-control select" data-live-search="true" required>
                                            <option value="">Pilih Aparatur</option>
                                            <?php foreach ($aparatur as $key => $h) { ?> 
                                                <option value="<?php echo $h->nip ?>" <?php if ($h->nip == $e->nip_id) { echo "selected"; } ?>>
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
                                    <label class="col-md-2 control-label">Kegiatan</label>
                                    <div class="col-md-10">
                                        <input type="text" name="kegiatan" class="form-control" value="<?php echo $e->kegiatan ?>" required />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Akun</label>
                                    <div class="col-md-10">
                                        <input type="text" name="akun" class="form-control" value="<?php echo $e->akun ?>" required />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Keterangan lain-lain</label>
                                    <div class="col-md-10">
                                        <input type="text" name="keterangan" class="form-control" value="<?php echo $e->keterangan ?>" required />
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