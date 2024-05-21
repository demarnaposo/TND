<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo site_url('home/dashboard') ?>">Beranda</a></li>                    
    <li><a href="javascript:void(0)">Naskah Dinas Surat</a></li>
    <li><a href="<?php echo site_url('suratmasuk/inbox/surat') ?>">Surat Masuk</a></li>
    <li class="active">Formulir</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-envelope"></span> Formulir Surat Masuk</h2>
</div>
<!-- END PAGE TITLE -->                

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">                

    <div class="row">
        <div class="col-md-12">
                <?php if($this->uri->segment(3) == 'add'){?>
                <!-- START DEFAULT FORM -->
                <form class="form-horizontal" action="<?php echo site_url('suratmasuk1/surat/insert') ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="surat_id" value="<?php echo $this->uri->segment(4) ?>">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <a href="javascript:history.back()" class="btn btn-default">&laquo; Kembali</a> <br><br>
                    </div>
                    <div class="panel-body">                                                                        
                        
                        <div class="row">

                            <?php if (empty($this->uri->segment(4))) { ?>

                                <div class="col-md-6"> 

                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Dari</label>
                                        <div class="col-md-10">
                                            <input type="text" name="dari" class="form-control" required />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Nomor</label>
                                        <div class="col-md-10">
                                            <input type="text" name="nomor" class="form-control" required />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Tanggal Surat</label>
                                        <div class="col-md-10">
                                            <div class="input-group">
                                                <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                <input type="text" name="tanggal" class="form-control datepicker" value="<?php echo date('Y-m-d') ?>" required />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Lampiran Surat</label>
                                        <div class="col-md-10">
                                            <input type="file" name="lampiran" class=" fileinput btn-primary" title="Cari file" required />
                                            <span class="help-block">Format hanya berlaku pdf,jpg,png</span>
                                        </div>
                                    </div>
                                    <div class="form-group">                                        
                                        <label class="col-md-2 control-label">Perihal</label>
                                        <div class="col-md-10">
                                            <input type="text" name="hal" class="form-control" required />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Tanggal diterima</label>
                                        <div class="col-md-10">
                                            <div class="input-group">
                                                <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                <input type="text" name="diterima" class="form-control datepicker" value="<?php echo date('Y-m-d') ?>" required />
                                            </div>
                                        </div>
                                    </div> 
                                    
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Penerima</label>
                                        <div class="col-md-10">
                                        <?php if($this->session->userdata('level') == 18){?>
                                            <input type="text" name="penerima" class="form-control" value="<?= $this->session->userdata('nama');?>" />
                                        <?php }else{?>
                                            <input type="text" name="penerima" class="form-control" required />
                                        <?php }?>
                                        </div>
                                    </div>
                                    <!-- <div class="form-group">
                                        <label class="col-md-2 control-label">Indeks</label>
                                        <div class="col-md-10">
                                            <input type="text" name="indeks" class="form-control" required />
                                        </div>
                                    </div> -->
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Kode Surat</label>
                                        <div class="col-md-10">
                                            <select name="kodesurat_id" class="form-control select" data-live-search="true" required>
                                                <option value="">Pilih Kode Surat</option>
                                                <?php foreach ($kodesurat as $key => $h) { ?> 
                                                    <option value="<?php echo $h->kodesurat_id ?>"><?php echo $h->kode; ?> - <?php echo substr($h->tentang, 0,90); ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Sifat</label>
                                        <div class="col-md-10">                                                                                
                                            <select class="form-control select" name="sifat" data-live-search="true" required>
                                                <option value="Biasa">Biasa</option>
                                                <option value="Segera">Segera</option>
                                                <option value="Sangat Segera">Sangat Segera</option>
                                                <option value="Penting">Penting</option>
                                                <option value="Rahasia">Rahasia</option>
                                            </select>                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Lampiran Lainnya</label>
                                        <div class="col-md-10">
                                            <input type="file" name="lampiran_lain" class="fileinput btn-primary" id="filename" title="Cari file" />
                                            <span class="help-block">Format hanya berlaku pdf</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Telepon</label>
                                        <div class="col-md-10">
                                            <input type="text" name="telp" class="form-control" required />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12"><br>
                                    <label>Isi</label>
                                    <textarea id="isi" name="isi"></textarea>
                                </div>

                                <div class="col-md-12"><br>
                                    <label>Catatan</label>
                                    <textarea id="isi" name="catatan"></textarea>
                                </div>

                            <?php }else{ ?>

                                <?php foreach ($surat as $key => $h) { ?>

                                <div class="col-md-6"> 

                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Dari</label>
                                        <div class="col-md-10">
                                            <?php $dari = $this->db->get_where('opd', array('opd_id' => $h->opd_id))->row_array(); ?>
                                            <input type="text" name="dari" class="form-control" value="<?php echo $dari['nama_pd'] ?>" readonly required />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Nomor</label>
                                        <div class="col-md-10">
                                            <input type="text" name="nomor" class="form-control" value="<?php echo $h->nomor; ?>" readonly required />
                                        </div>
                                    </div>
                                    <div class="form-group">                                        
                                        <label class="col-md-2 control-label">Tanggal Surat</label>
                                        <div class="col-md-10">
                                            <input type="text" name="tanggal" class="form-control" value="<?php echo $h->tanggal ?>" readonly required />
                                        </div>
                                    </div>
                                    <div class="form-group">                                        
                                        <label class="col-md-2 control-label">Perihal</label>
                                        <div class="col-md-10">
                                            <input type="text" name="hal" class="form-control" value="<?php if(!empty($h->hal)){ echo $h->hal; } ?>" required />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Tanggal diterima</label>
                                        <div class="col-md-10">
                                            <div class="input-group">
                                                <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                <input type="text" name="diterima" class="form-control datepicker" value="<?php echo date('Y-m-d') ?>" required />
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Penerima</label>
                                        <div class="col-md-10">
                                            <input type="text" name="penerima" class="form-control" required />
                                        </div>
                                    </div>
                                    
                                </div>

                                <div class="col-md-6">
                                    <!-- <div class="form-group">
                                        <label class="col-md-2 control-label">Indeks</label>
                                        <div class="col-md-10">
                                            <input type="text" name="indeks" class="form-control" required />
                                        </div>
                                    </div> -->
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Lampiran Surat</label>
                                        <div class="col-md-10">
                                            <input type="hidden" name="lampiran" class="form-control" value="<?= $h->id.'.pdf'?>"/>
                                            <span class="help-block"><a href="<?php echo base_url('uploads/SIGNED/'.$h->id.'.pdf')?>" class="btn btn-primary" target="_blank">Lihat Surat</a></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Kode Surat</label>
                                        <div class="col-md-10">
                                            <?php 
                                                foreach ($kodesurat as $key => $k) { 
                                                if ($h->kodesurat_id == $k->kodesurat_id) {
                                            ?>
                                            <input type="hidden" name="kodesurat_id" class="form-control" value="<?php echo $k->kodesurat_id ?>" readonly required />
                                            <input type="text" class="form-control" value="<?php echo $k->kode.' - '.$k->tentang ?>" readonly required />
                                            <?php } } ?>
                                        </div>
                                    </div>
                                    <?php if(!empty($h->sifat)){ ?>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">Sifat</label>
                                            <div class="col-md-10">
                                                <input type="text" name="sifat" class="form-control" required />
                                            </div>
                                        </div>
                                    <?php }else{ ?>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">Sifat</label>
                                            <div class="col-md-10">                                                     
                                                <select class="form-control select" name="sifat" data-live-search="true" required>
                                                <option>Pilih Sifat</option>
                                                <option value="Biasa">Biasa</option>
                                                <option value="Segera">Segera</option>
                                                <option value="Sangat Segera">Sangat Segera</option>
                                                <option value="Penting">Penting</option>
                                                <option value="Rahasia">Rahasia</option>
                                            </select>                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Lampiran Lainnya</label>
                                        <div class="col-md-10">
                                            <input type="file" name="lampiran_lain" class="fileinput btn-primary" id="filename" title="Cari file" />
                                            <span class="help-block">Format hanya berlaku pdf</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Telp</label>
                                        <div class="col-md-10">
                                            <input type="text" name="telp" class="form-control" required />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12"><br>
                                    <label><b>Isi</b></label>
                                    <textarea id="isi" name="isi" value="<?php echo $h->isi; ?>"></textarea>
                                </div>

                                <div class="col-md-12"><br>
                                    <label>Catatan</label>
                                    <textarea id="isi" name="catatan"></textarea>
                                </div>

                                <?php } ?>

                            <?php } ?>
                            
                        </div>

                    </div>
                    <div class="panel-footer">
                        <button class="btn btn-danger" type="reset">Bersihkan Formulir</button>   
                        <button class="btn btn-primary pull-right" type="submit">Simpan</button>
                    </div>
                </div>
                </form>
                <?php }elseif ($this->uri->segment(3) == 'edit') { 
                foreach ($suratmasuk as $key => $sm){ ?>
                    <form class="form-horizontal" action="<?php echo site_url('suratmasuk/surat/update') ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="suratmasuk_id" value="<?php echo $sm->suratmasuk_id ?>" />
                <div class="panel panel-default">
                    <div class="panel-body">
                        <a href="javascript:history.back()" class="btn btn-default">&laquo; Kembali</a> <br><br>
                    </div>
                    <div class="panel-body">                                                                        
                        
                        <div class="row">

                                <div class="col-md-6"> 

                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Dari</label>
                                        <div class="col-md-10">
                                            <input type="text" name="dari" class="form-control" value="<?= $sm->dari;?>" required />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Nomor</label>
                                        <div class="col-md-10">
                                            <input type="text" name="nomor" class="form-control" value="<?= $sm->nomor;?>" required />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Tanggal Surat</label>
                                        <div class="col-md-10">
                                            <div class="input-group">
                                                <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                <input type="text" name="tanggal" class="form-control datepicker" value="<?= $sm->tanggal;?>" required />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Lampiran Surat</label>
                                        <div class="col-md-10">
                                            <input type="file" name="lampiran" class="fileinput btn-primary" id="filename" title="Cari file"/>
                                            <span class="help-block">Format hanya berlaku pdf,jpg,pdf : <a href="<?php echo base_url('assets/lampiransuratmasuk/').$sm->lampiran;?>"><?= $sm->lampiran;?></a></span>
                                        </div>
                                    </div>
                                    <div class="form-group">                                        
                                        <label class="col-md-2 control-label">Perihal</label>
                                        <div class="col-md-10">
                                            <input type="text" name="hal" class="form-control" value="<?= $sm->hal;?>" required />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Tanggal diterima</label>
                                        <div class="col-md-10">
                                            <div class="input-group">
                                                <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                <input type="text" name="diterima" class="form-control datepicker" value="<?= $sm->diterima;?>"required />
                                            </div>
                                        </div>
                                    </div> 
                                    
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Penerima</label>
                                        <div class="col-md-10">
                                            <input type="text" name="penerima" class="form-control" value="<?= $sm->penerima;?>" required />
                                        </div>
                                    </div>
                                    <!-- <div class="form-group">
                                        <label class="col-md-2 control-label">Indeks</label>
                                        <div class="col-md-10">
                                            <input type="text" name="indeks" class="form-control" value="<?= $sm->indeks;?>" required />
                                        </div>
                                    </div> -->
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Kode Surat</label>
                                        <div class="col-md-10">
                                            <select name="kodesurat_id" class="form-control select" data-live-search="true" required>
                                                <option value="">Pilih Kode Surat</option>
                                                <?php foreach ($kodesurat as $key => $h) { ?> 
                                                    <option value="<?php echo $h->kodesurat_id ?>" <?php if ($h->kodesurat_id == $sm->kodesurat_id) { echo "selected"; } ?>>
                                                    <?php echo $h->kode; ?> - <?php echo substr($h->tentang, 0,80); ?>
                                                </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Sifat</label>
                                        <div class="col-md-10">                                                                                
                                            <select class="form-control select" name="sifat" data-live-search="true" required>
                                                <option><?= $sm->sifat; ?></option>
                                                <option value="Biasa">Biasa</option>
                                                <option value="Segera">Segera</option>
                                                <option value="Sangat Segera">Sangat Segera</option>
                                                <option value="Penting">Penting</option>
                                                <option value="Rahasia">Rahasia</option>
                                            </select>                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Lampiran Lainnya</label>
                                        <div class="col-md-10">
                                            <input type="file" name="lampiran_lain" class="fileinput btn-primary" id="filename" title="Cari file" accept="application/pdf" />
                                            <span class="help-block">Format hanya berlaku pdf : <?= $sm->lampiran_lain;?></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Telp</label>
                                        <div class="col-md-10">
                                            <input type="text" name="telp" class="form-control" value="<?= $sm->telp;?>"  required />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12"><br>
                                    <label>Isi</label>
                                    <textarea id="isi" name="isi"><?= $sm->isi;?></textarea>
                                </div>

                                <div class="col-md-12"><br>
                                    <label>Catatan</label>
                                    <textarea id="isi" name="catatan"><?= $sm->catatan;?></textarea>
                                </div>
                            
                        </div>

                    </div>
                    <div class="panel-footer">
                        <button class="btn btn-danger" type="reset">Bersihkan Formulir</button>   
                        <button class="btn btn-primary pull-right" type="submit">Simpan</button>
                    </div>
                </div>
                </form>
                <?php } }?>
                <!-- END DEFAULT FORM -->

        </div>
    </div>
</div>