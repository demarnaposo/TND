<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="#">Beranda</a></li>                    
    <li><a href="#">Data Master</a></li>
    <li>Perangkat Daerah</li>
    <li class="active">Formulir</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-list-alt"></span> Formulir Data Perangkat Daerah</h2>
</div>
<!-- END PAGE TITLE -->                

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">                

    <div class="row">
        <div class="col-md-12">

            <?php if ($this->uri->segment(3) == 'add') { ?>
            
                <!-- START DEFAULT FORM -->
                <form class="form-horizontal" action="<?php echo site_url('master/perangkatdaerah/insert') ?>" method="post" enctype="multipart/form-data">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <a href="<?php echo site_url('master/perangkatdaerah') ?>" class="btn btn-default">&laquo; Kembali</a> <br><br>
                    </div>
                    <div class="panel-body">                                                                        
                        
                        <div class="row">
                            
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Nama Perangkat Daerah</label>
                                    <div class="col-md-10">
                                        <input type="text" name="nama_pd" class="form-control" required />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Kode Perangkat Daerah</label>
                                    <div class="col-md-10">
                                        <input type="text" name="kode_pd" class="form-control" required />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Nomenklatur Perangkat Daerah</label>
                                    <div class="col-md-10">
                                        <input type="text" name="nomenklatur_pd" class="form-control" required />
                                    </div>
                                </div>

                                <!-- Update @Mpik Egov 8 Agustus 2022 -->
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Perangkat Daerah Induk</label>
                                    <div class="col-md-10">
                                        <select name="opd_induk" class="form-control select" data-live-search="true">
                                            <option value="">-- Pilih Perangkat Daerah Induk --</option>
                                            <?php foreach ($opd as $key => $h) { ?> 
                                                <option value="<?php echo $h->opd_id ?>"><?php echo $h->nama_pd; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- END 8 Agustus 2022 -->

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Alamat</label>
                                    <div class="col-md-10">
                                        <input type="text" name="alamat" class="form-control" required />
                                    </div>
                                </div>

                                <div class="form-group">                                        
                                    <label class="col-md-2 control-label">Telepon</label>
                                    <div class="col-md-10">
                                        <input type="text" name="telp" class="form-control" required />
                                    </div>
                                </div>
                                <div class="form-group">                                        
                                    <label class="col-md-2 control-label">Faksimile</label>
                                    <div class="col-md-10">
                                        <input type="text" name="faksimile" class="form-control" required />
                                    </div>
                                </div>
                                <div class="form-group">                                        
                                    <label class="col-md-2 control-label">Email</label>
                                    <div class="col-md-10">
                                        <input type="email" name="email" class="form-control" required />
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Website</label>
                                    <div class="col-md-10">
                                        <input type="text" name="alamat_website" class="form-control" required />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Status</label>
                                    <div class="col-md-10">
                                        <select name="statusopd" class="form-control select" data-live-search="true" required>
                                            <option value="">Pilih Status</option>
                                            <option value="Aktif">Aktif</option>
					                        <option value="Tidak Aktif">Tidak Aktif</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Update @Mpik Egov 8 Agustus 2022 -->
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Urutan Perangkat Daerah</label>
                                    <div class="col-md-10">
                                        <select name="urutan_id" class="form-control select" data-live-search="true" required>
                                            <option value="">-- Pilih Urutan Perangkat Daerah--</option>
                                            <?php foreach ($urutanopd as $key => $u) { ?> 
                                                <option value="<?php echo $u->urutan_id ?>"><?php echo $u->nama_urutan; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- END 8 Agustus 2022 -->

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
                foreach ($perangkatdaerah as $key => $e) {
            ?>

                <!-- START DEFAULT FORM -->
                <form class="form-horizontal" action="<?php echo site_url('master/perangkatdaerah/update') ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="opd_id" value="<?php echo $e->opd_id ?>" />
                <div class="panel panel-default">
                    <div class="panel-body">
                        <a href="<?php echo site_url('master/perangkatdaerah') ?>" class="btn btn-default">&laquo; Kembali</a> <br><br>
                    </div>
                    <div class="panel-body">                                                                        
                        
                        <div class="row">
                            
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Nama Perangkat Daerah</label>
                                    <div class="col-md-10">
                                        <input type="text" name="nama_pd" class="form-control" value="<?php echo $e->nama_pd ?>" required />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Kode Perangkat Daerah</label>
                                    <div class="col-md-10">
                                        <input type="text" name="kode_pd" class="form-control" value="<?php echo $e->kode_pd ?>" required />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Nomenklatur Perangkat Daerah</label>
                                    <div class="col-md-10">
                                        <input type="text" name="nomenklatur_pd" class="form-control" value="<?php echo $e->nomenklatur_pd ?>" required />
                                    </div>
                                </div>

                                <!-- Update @Mpik Egov 8 Agustus 2022 -->
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Perangkat Daerah Induk</label>
                                    <div class="col-md-10">
                                        <select name="opd_induk" class="form-control select" data-live-search="true" >
                                            <option value="">-- Pilih Perangkat Daerah Induk--</option>
                                            <?php foreach ($opd as $key => $h) { ?> 
                                                <option value="<?php echo $h->opd_id ?>" <?php if ($h->opd_id == $e->opd_induk) { echo "selected"; } ?>>
                                                    <?php echo $h->nama_pd; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- END 8 Agustus 2022 -->

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Alamat</label>
                                    <div class="col-md-10">
                                        <input type="text" name="alamat" class="form-control" value="<?php echo $e->alamat ?>" required />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Telepon</label>
                                    <div class="col-md-10">
                                        <input type="text" name="telp" class="form-control" value="<?php echo $e->telp ?>" required />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Faksimile</label>
                                    <div class="col-md-10">
                                        <input type="text" name="faksimile" class="form-control" value="<?php echo $e->faksimile ?>" required />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Email</label>
                                    <div class="col-md-10">
                                        <input type="email" name="email" class="form-control" value="<?php echo $e->email ?>" required />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Alamat Website</label>
                                    <div class="col-md-10">
                                        <input type="text" name="alamat_website" class="form-control" value="<?php echo $e->alamat_website ?>" required />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Status</label>
                                    <div class="col-md-10">
                                        <select name="statusopd" class="form-control select" data-live-search="true" required />
                                            <option value="">Pilih Status</option>
                                            <?php foreach ($perangkatdaerah as $key => $h) { ?> 
                                                <?php if ($h->statusopd == "Aktif")
                                                {
                                                    echo "<option value='Aktif' selected>Aktif</option>";
                                                }   
                                                    else { echo "<option value='Aktif'>Aktif</option>";
                                                }
                                                ?>                                                
						                        <?php if ($h->statusopd == "Tidak Aktif")
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

                                <!-- Update @Mpik Egov 8 Agustus 2022 -->
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Urutan Perangkat Daerah</label>
                                    <div class="col-md-10">
                                        <select name="urutan_id" class="form-control select" data-live-search="true" >
                                            <option value="">--- Pilih Urutan Perangkat Daerah --</option>
                                            <?php foreach ($urutanopd as $key => $u) { ?> 
                                                <option value="<?php echo $u->urutan_id ?>" <?php if ($u->urutan_id == $e->urutan_id) { echo "selected"; } ?>>
                                                    <?php echo $u->nama_urutan; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- END 8 Agustus 2022 -->

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