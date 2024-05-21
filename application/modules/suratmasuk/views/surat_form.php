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
            <!-- Update @Mpik Egov 13/06/2022 09:10 -->
            <!-- Penambahan Generate Nomor Surat Masuk -->
            <div class="alert alert-danger" role="alert">
                <h4><i class="fa fa-info-circle"></i> <b>Keterangan :</b> Jika surat tidak memiliki nomor surat, maka <b>pilih tidak</b>. Sistem akan <b>men-generate nomor</b> dengan format : <b>tanggal-bulan-tahun-jam-menit-detik</b>, sesuai penginputan surat masuk</h4>
            </div>
            <!-- END -->
            <!-- Update @Mpik Egov 13/06/2022 09:10 -->
            <?php if ($this->uri->segment(3) == 'add') { ?>
                <!-- START DEFAULT FORM -->
                <form class="form-horizontal" action="<?php echo site_url('suratmasuk/surat/insert') ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="surat_id" value="<?php echo $this->uri->segment(4) ?>">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <a href="javascript:history.back()" class="btn btn-default">&laquo; Kembali</a> <br><br>
                        </div>
                        <div class="panel-body">

                            <div class="row">

                                <?php if (empty($this->uri->segment(4))) { ?>

                                    <!-- START:Identitas dan Retensi Surat [@Dam-Egov 10/01/2024] -->
                                    <div>
                                        <!-- START:Identitas Surat -->
                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label> IDENTITAS SURAT</label><br>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2 control-label required">Dari</label>
                                                <div class="col-md-10">
                                                    <input type="text" name="dari" class="form-control" required />
                                                </div>
                                            </div>

                                            <script>
                                                function yesnoCheck() {
                                                    if (document.getElementById('yesCheck').checked) {
                                                        document.getElementById('ifYes').style.display = 'block';
                                                    } else document.getElementById('ifYes').style.display = 'none';

                                                }
                                            </script>
                                            <div class="form-group">
                                                <div class="col-md-2"></div>
                                                <div class="col-md-10">
                                                    <div class="alert alert-warning" role="alert">
                                                        <p><b>Apakah surat memiliki nomor surat?</b>
                                                            <input type="radio" onclick="javascript:yesnoCheck();" name="yesno" id="yesCheck"> Iya /
                                                            <input type="radio" onclick="javascript:yesnoCheck();" name="yesno" id="noCheck"> Tidak
                                                        </p>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="form-group" id="ifYes" style="display:none">
                                                <label class="col-md-2 control-label">Nomor</label>
                                                <div class="col-md-10">
                                                    <input type="text" name="nomor" class="form-control" />
                                                </div>
                                            </div>
                                            <!-- END -->
                                            <!-- Update @Mpik Egov 13/06/2022 09:02 -->
                                            <div class="form-group">
                                                <label class="col-md-2 control-label required">Tanggal Surat</label>
                                                <div class="col-md-10">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                        <input type="text" name="tanggal" class="form-control datepicker" value="<?php echo date('Y-m-d') ?>" required />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label required">Lampiran Surat</label>
                                                <div class="col-md-10">
                                                    <input type="file" name="lampiran" class="fileinput btn-primary" title="Cari file" required />
                                                    <span class="help-block">Format hanya berlaku pdf,jpg,png</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label required">Perihal</label>
                                                <div class="col-md-10">
                                                    <input type="text" name="hal" class="form-control" required />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label required">Tanggal diterima</label>
                                                <div class="col-md-10">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                        <input type="text" name="diterima" id="diterima" class="form-control datepicker" value="<?php echo date('Y-m-d') ?>" onblur="changeDateAccept()" required />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2 control-label required">Penerima</label>
                                                <div class="col-md-10">
                                                    <?php if ($this->session->userdata('level') == 18) { ?>
                                                        <input type="text" name="penerima" class="form-control" value="<?= $this->session->userdata('nama'); ?>" />
                                                    <?php } else { ?>
                                                        <input type="text" name="penerima" class="form-control" required />
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <!-- <div class="form-group">
                                                <label class="col-md-2 control-label">Indeks</label>
                                                <div class="col-md-10">
                                                    <input type="text" name="indeks" class="form-control" required />
                                                </div>
                                            </div> -->
                                            <div class="form-group">
                                                <label class="col-md-2 control-label required">Kode Surat</label>
                                                <div class="col-md-10">
                                                    <select name="kodesurat_id" id="kodesurat_id" class="form-control select" data-live-search="true" required>
                                                        <option value="">Pilih Kode Surat</option>
                                                        <?php foreach ($kodesurat as $key => $h) { ?>
                                                            <option value="<?php echo $h->kodesurat_id ?>"><?php echo $h->kode; ?> - <?php echo substr($h->tentang, 0, 90); ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label required">Sifat</label>
                                                <div class="col-md-10">
                                                    <select class="form-control select" name="sifat" data-live-search="true" required>
                                                        <option value="Biasa">Biasa</option>
                                                        <option value="Segera">Segera</option>
                                                        <option value="Sangat Segera">Sangat Segera</option>
                                                        <option value="Penting">Penting</option>
                                                        <option value="Rahasia">Rahasia</option>
                                                    </select>
                                                </div>
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
                                        <!-- END:Identitas Surat -->

                                        <!-- START:Feat Retensi Arsip Surat Masuk -->
                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label> JADWAL RETENSI ARSIP</label><br>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-md-2"></div>
                                                <div class="col-md-10">
                                                    <div class="alert alert-info" role="alert">
                                                        <p>
                                                            <i class="fa fa-info-circle"></i>
                                                            <b>Lampiran File Series JRA : 
                                                                <a href="<?php echo site_url('assets/surat/Lampiran-JRA.pdf') ?>" target="_blank"> 
                                                                    Lihat Disini
                                                                </a>
                                                            </b>
                                                        </p>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2 control-label required">Series</label>
                                                <div class="col-md-10">
                                                    <select name="jra_id" id="jra_id" class="form-control select" data-live-search="true" required>
                                                        <option value=""> -- Pilih Series -- </option>
                                                        <?php foreach ($jra as $key => $j) { ?>
                                                            <option value="<?php echo $j->id ?>">
                                                                <?php echo $j->series;
                                                                if ($j->sub_series) { ?>
                                                                    - <?php echo $j->sub_series;
                                                                    } ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Retensi Aktif (Tahun)</label>
                                                <div class="col-md-10">
                                                    <input type="text" name="retensi_aktif" id="retensi_aktif" class="form-control" onblur="getTahunAktif()" readonly />
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Tahun Aktif</label>
                                                <div class="col-md-10">
                                                    <input type="number" name="tahun_aktif" id="tahun_aktif" class="form-control" readonly />
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Retensi In-Aktif (Tahun)</label>
                                                <div class="col-md-10">
                                                    <input type="text" name="retensi_inaktif" id="retensi_inaktif" class="form-control" onblur="getTahunInaktif()" readonly />
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Tahun In-Aktif</label>
                                                <div class="col-md-10">
                                                    <input type="number" name="tahun_inaktif" id="tahun_inaktif" class="form-control" readonly />
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Keterangan JRA</label>
                                                <div class="col-md-10">
                                                    <input type="text" name="ket_jra" id="ket_jra" class="form-control" readonly />
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Nilai Guna</label>
                                                <div class="col-md-10">
                                                    <input type="text" name="nilai_guna" id="nilai_guna" class="form-control" readonly />
                                                </div>
                                            </div>

                                        </div>
                                        <!-- END:Feat Retensi Arsip Surat Masuk -->
                                    </div>
                                    <!-- END:Identitas dan Retensi Surat -->

                                    <!-- START:Isi dan Catatan Surat [@Dam-Egov 10/01/2024] -->
                                    <div>
                                        <div class="col-md-12"><br>
                                            <label class="required">Isi</label>
                                            <textarea id="textarea1" name="isi"></textarea>
                                        </div>

                                        <div class="col-md-12"><br>
                                            <label>Catatan</label>
                                            <textarea id="textarea2" name="catatan"></textarea>
                                        </div>
                                    </div>
                                    <!-- END:Isi dan Catatan Surat -->

                                <?php } else { ?>

                                    <?php foreach ($surat as $key => $h) { ?>

                                        <!-- START:Identitas dan Retensi Surat [@Dam-Egov 10/01/2024] -->
                                        <div>
                                            <!-- START:Identitas Surat -->
                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label> IDENTITAS SURAT</label><br>
                                                </div>

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
                                                    <label class="col-md-2 control-label required">Perihal</label>
                                                    <div class="col-md-10">
                                                        <input type="text" name="hal" class="form-control" value="<?php if (!empty($h->hal)) {
                                                                                                                        echo $h->hal;
                                                                                                                    } ?>" required />
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-md-2 control-label required">Tanggal diterima</label>
                                                    <div class="col-md-10">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                            <input type="text" name="diterima" id="diterima" class="form-control datepicker" value="<?php echo date('Y-m-d') ?>" onblur="changeDateAccept()" required />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-md-2 control-label required">Penerima</label>
                                                    <div class="col-md-10">
                                                        <input type="text" name="penerima" class="form-control" required />
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Lampiran Surat</label>
                                                    <div class="col-md-10">
                                                        <input type="hidden" name="lampiran" class="form-control" value="<?= $h->id . '.pdf' ?>" />
                                                        <span class="help-block"><a href="<?php echo base_url('uploads/SIGNED/' . $h->id . '.pdf') ?>" class="btn btn-primary" target="_blank">Lihat Surat</a></span>
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
                                                                <input type="text" class="form-control" value="<?php echo $k->kode . ' - ' . $k->tentang ?>" readonly required />
                                                        <?php }
                                                        } ?>
                                                    </div>
                                                </div>

                                                <?php if (!empty($h->sifat)) { ?>
                                                    <div class="form-group">
                                                        <label class="col-md-2 control-label required">Sifat</label>
                                                        <div class="col-md-10">
                                                            <input type="text" name="sifat" class="form-control" required />
                                                        </div>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="form-group">
                                                        <label class="col-md-2 control-label required">Sifat</label>
                                                        <div class="col-md-10">
                                                            <select class="form-control select" name="sifat" data-live-search="true" required>
                                                                <option>Pilih Sifat</option>
                                                                <option value="Biasa">Biasa</option>
                                                                <option value="Segera">Segera</option>
                                                                <option value="Sangat Segera">Sangat Segera</option>
                                                                <option value="Penting">Penting</option>
                                                                <option value="Rahasia">Rahasia</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                                <!-- Pengkondisian lihat lampiran lain : Fikri Egov 3 April 2022 -->
                                                <?php if (empty($h->lampiran_lain)) {
                                                } else { ?>
                                                    <div class="form-group">
                                                        <label class="col-md-2 control-label required">Lampiran Lainnya</label>
                                                        <div class="col-md-10">
                                                            <input type="hidden" name="lampiran_lain" class="form-control" value="<?php echo $h->lampiran_lain ?>" />
                                                            <?php
                                                            $surat_id = $this->uri->segment(4);
                                                            if (substr($surat_id, 0, 2) == 'SB') { ?>
                                                                <input type="hidden" name="lampiran_lain" class="form-control" value="<?php echo $h->lampiran_lain ?>" />
                                                                <span class="help-block"><a href="<?php echo base_url('assets/lampiransurat/biasa/' . $h->lampiran_lain) ?>" class="btn btn-primary" target="_blank">Lihat Surat</a></span>
                                                                <span class="help-block">Format hanya berlaku pdf</span>
                                                            <?php } elseif (substr($surat_id, 0, 2) == 'SE') { ?>
                                                                <span class="help-block"><a href="<?php echo base_url('assets/lampiransurat/edaran/' . $h->lampiran_lain) ?>" class="btn btn-primary" target="_blank">Lihat Surat</a></span>
                                                                <span class="help-block">Format hanya berlaku pdf</span>
                                                            <?php } elseif (substr($surat_id, 0, 2) == 'SU') { ?>
                                                                <span class="help-block"><a href="<?php echo base_url('assets/lampiransurat/undangan/' . $h->lampiran_lain) ?>" class="btn btn-primary" target="_blank">Lihat Surat</a></span>
                                                                <span class="help-block">Format hanya berlaku pdf</span>
                                                            <?php } elseif (substr($surat_id, 0, 5) == 'PNGMN') { ?>
                                                                <span class="help-block"><a href="<?php echo base_url('assets/lampiransurat/pengumuman/' . $h->lampiran_lain) ?>" class="btn btn-primary" target="_blank">Lihat Surat</a></span>
                                                                <span class="help-block">Format hanya berlaku pdf</span>
                                                            <?php } elseif (substr($surat_id, 0, 3) == 'LAP') { ?>
                                                                <span class="help-block"><a href="<?php echo base_url('assets/lampiransurat/laporan/' . $h->lampiran_lain) ?>" class="btn btn-primary" target="_blank">Lihat Surat</a></span>
                                                                <span class="help-block">Format hanya berlaku pdf</span>
                                                            <?php } elseif (substr($surat_id, 0, 3) == 'REK') { ?>
                                                                <span class="help-block"><a href="<?php echo base_url('assets/lampiransurat/rekomendasi/' . $h->lampiran_lain) ?>" class="btn btn-primary" target="_blank">Lihat Surat</a></span>
                                                                <span class="help-block">Format hanya berlaku pdf</span>
                                                            <?php } elseif (substr($surat_id, 0, 3) == 'INT') { ?>
                                                                <span class="help-block"><a href="<?php echo base_url('assets/lampiransurat/instruksi/' . $h->lampiran_lain) ?>" class="btn btn-primary" target="_blank">Lihat Surat</a></span>
                                                                <span class="help-block">Format hanya berlaku pdf</span>
                                                            <?php } elseif (substr($surat_id, 0, 3) == 'PNG') { ?>
                                                                <span class="help-block"><a href="<?php echo base_url('assets/lampiransurat/pengantar/' . $h->lampiran_lain) ?>" class="btn btn-primary" target="_blank">Lihat Surat</a></span>
                                                                <span class="help-block">Format hanya berlaku pdf</span>
                                                            <?php } elseif (substr($surat_id, 0, 5) == 'NODIN') { ?>
                                                                <span class="help-block"><a href="<?php echo base_url('assets/lampiransurat/notadinas/' . $h->lampiran_lain) ?>" class="btn btn-primary" target="_blank">Lihat Surat</a></span>
                                                                <span class="help-block">Format hanya berlaku pdf</span>
                                                            <?php } elseif (substr($surat_id, 0, 2) == 'SK') { ?>
                                                                <span class="help-block"><a href="<?php echo base_url('assets/lampiransurat/keterangan/' . $h->lampiran_lain) ?>" class="btn btn-primary" target="_blank">Lihat Surat</a></span>
                                                                <span class="help-block">Format hanya berlaku pdf</span>
                                                            <?php } elseif (substr($surat_id, 0, 3) == 'SPT') { ?>
                                                                <span class="help-block"><a href="<?php echo base_url('assets/lampiransurat/perintahtugas/' . $h->lampiran_lain) ?>" class="btn btn-primary" target="_blank">Lihat Surat</a></span>
                                                                <span class="help-block">Format hanya berlaku pdf</span>
                                                            <?php } elseif (substr($surat_id, 0, 2) == 'SP') { ?>
                                                                <span class="help-block"><a href="<?php echo base_url('assets/lampiransurat/perintah/' . $h->lampiran_lain) ?>" class="btn btn-primary" target="_blank">Lihat Surat</a></span>
                                                                <span class="help-block">Format hanya berlaku pdf</span>
                                                            <?php } elseif (substr($surat_id, 0, 3) == 'IZN') { ?>
                                                                <span class="help-block"><a href="<?php echo base_url('assets/lampiransurat/izin/' . $h->lampiran_lain) ?>" class="btn btn-primary" target="_blank">Lihat Surat</a></span>
                                                                <span class="help-block">Format hanya berlaku pdf</span>
                                                            <?php } elseif (substr($surat_id, 0, 3) == 'PJL') { ?>
                                                                <span class="help-block"><a href="<?php echo base_url('assets/lampiransurat/perjalanandinas/' . $h->lampiran_lain) ?>" class="btn btn-primary" target="_blank">Lihat Surat</a></span>
                                                                <span class="help-block">Format hanya berlaku pdf</span>
                                                            <?php } elseif (substr($surat_id, 0, 3) == 'KSA') { ?>
                                                                <span class="help-block"><a href="<?php echo base_url('assets/lampiransurat/kuasa/' . $h->lampiran_lain) ?>" class="btn btn-primary" target="_blank">Lihat Surat</a></span>
                                                                <span class="help-block">Format hanya berlaku pdf</span>
                                                            <?php } elseif (substr($surat_id, 0, 3) == 'MKT') { ?>
                                                                <span class="help-block"><a href="<?php echo base_url('assets/lampiransurat/melaksanakantugas/' . $h->lampiran_lain) ?>" class="btn btn-primary" target="_blank">Lihat Surat</a></span>
                                                                <span class="help-block">Format hanya berlaku pdf</span>
                                                            <?php } elseif (substr($surat_id, 0, 3) == 'PGL') { ?>
                                                                <span class="help-block"><a href="<?php echo base_url('assets/lampiransurat/panggilan/' . $h->lampiran_lain) ?>" class="btn btn-primary" target="_blank">Lihat Surat</a></span>
                                                                <span class="help-block">Format hanya berlaku pdf</span>
                                                            <?php } elseif (substr($surat_id, 0, 3) == 'NTL') { ?>
                                                                <span class="help-block"><a href="<?php echo base_url('assets/lampiransurat/notulen/' . $h->lampiran_lain) ?>" class="btn btn-primary" target="_blank">Lihat Surat</a></span>
                                                                <span class="help-block">Format hanya berlaku pdf</span>
                                                            <?php } elseif (substr($surat_id, 0, 3) == 'MMO') { ?>
                                                                <span class="help-block"><a href="<?php echo base_url('assets/lampiransurat/memo/' . $h->lampiran_lain) ?>" class="btn btn-primary" target="_blank">Lihat Surat</a></span>
                                                                <span class="help-block">Format hanya berlaku pdf</span>
                                                            <?php } elseif (substr($surat_id, 0, 3) == 'LMP') { ?>
                                                                <span class="help-block"><a href="<?php echo base_url('assets/lampiransurat/lampiran/' . $h->lampiran_lain) ?>" class="btn btn-primary" target="_blank">Lihat Surat</a></span>
                                                                <span class="help-block">Format hanya berlaku pdf</span>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                                <!-- Pengkondisian lihat lampiran lain : Fikri Egov 3 April 2022 -->
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Telp</label>
                                                    <div class="col-md-10">
                                                        <input type="text" name="telp" class="form-control" required />
                                                    </div>
                                                </div>

                                            </div>
                                            <!-- END:Identitas Surat -->

                                            <!-- START:Feat Retensi Arsip Surat Masuk -->
                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label> JADWAL RETENSI ARSIP</label><br>
                                                </div>

                                                <div class="form-group">
                                                    <div class="col-md-2"></div>
                                                    <div class="col-md-10">
                                                        <div class="alert alert-info" role="alert">
                                                            <p>
                                                                <i class="fa fa-info-circle"></i>
                                                                <b>Lampiran File Series JRA : 
                                                                    <a href="<?php echo site_url('assets/surat/Lampiran-JRA.pdf') ?>" target="_blank"> 
                                                                        Lihat Disini
                                                                    </a>
                                                                </b>
                                                            </p>
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-md-2 control-label required">Series</label>
                                                    <div class="col-md-10">
                                                        <select name="jra_id" id="jra_id" class="form-control select" data-live-search="true" required>
                                                            <option value=""> -- Pilih Series -- </option>
                                                            <?php foreach ($jra as $key => $j) { ?>
                                                                <option value="<?php echo $j->id ?>">
                                                                    <?php echo $j->series;
                                                                    if ($j->sub_series) { ?>
                                                                        - <?php echo $j->sub_series;
                                                                        } ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Retensi Aktif (Tahun)</label>
                                                    <div class="col-md-10">
                                                        <input type="text" name="retensi_aktif" id="retensi_aktif" class="form-control" onblur="getTahunAktif()" readonly />
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Tahun Aktif</label>
                                                    <div class="col-md-10">
                                                        <input type="number" name="tahun_aktif" id="tahun_aktif" class="form-control" readonly />
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Retensi In-Aktif (Tahun)</label>
                                                    <div class="col-md-10">
                                                        <input type="text" name="retensi_inaktif" id="retensi_inaktif" class="form-control" onblur="getTahunInaktif()" readonly />
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Tahun In-Aktif</label>
                                                    <div class="col-md-10">
                                                        <input type="number" name="tahun_inaktif" id="tahun_inaktif" class="form-control" readonly />
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Keterangan JRA</label>
                                                    <div class="col-md-10">
                                                        <input type="text" name="ket_jra" id="ket_jra" class="form-control" readonly />
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Nilai Guna</label>
                                                    <div class="col-md-10">
                                                        <input type="text" name="nilai_guna" id="nilai_guna" class="form-control" readonly />
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            <!-- END:Feat Retensi Arsip Surat Masuk -->
                                        </div>
                                        <!-- END:Identitas dan Retensi Surat -->

                                        <!-- START:Isi dan Catatan Surat [@Dam-Egov 10/01/2024] -->
                                        <div>
                                            <div class="col-md-12"><br>
                                                <label class="required"><b>Isi</b></label>
                                                <textarea id="textarea1" name="isi" value="<?php echo $h->isi; ?>"></textarea>
                                            </div>

                                            <div class="col-md-12"><br>
                                                <label>Catatan</label>
                                                <textarea id="textarea2" name="catatan"></textarea>
                                            </div>
                                        </div>
                                        <!-- END:Isi dan Catatan Surat -->

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
                <?php } elseif ($this->uri->segment(3) == 'edit') {
                foreach ($suratmasuk as $key => $sm) { ?>
                    <form class="form-horizontal" action="<?php echo site_url('suratmasuk/surat/update') ?>" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="suratmasuk_id" value="<?php echo $sm->suratmasuk_id ?>" />
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <a href="javascript:history.back()" class="btn btn-default">&laquo; Kembali</a> <br><br>
                            </div>
                            <div class="panel-body">

                                <div class="row">

                                    <!-- START:Identitas dan Retensi Surat [@Dam-Egov 10/01/2024] -->
                                    <div>
                                        <!-- START:Identitas Surat -->
                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label> IDENTITAS SURAT</label><br>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2 control-label required">Dari</label>
                                                <div class="col-md-10">
                                                    <input type="text" name="dari" class="form-control" value="<?= $sm->dari; ?>" required />
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2 control-label required">Nomor</label>
                                                <div class="col-md-10">
                                                    <input type="text" name="nomor" class="form-control" value="<?= $sm->nomor; ?>" required />
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2 control-label required">Tanggal Surat</label>
                                                <div class="col-md-10">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                        <input type="text" name="tanggal" class="form-control datepicker" value="<?= $sm->tanggal; ?>" required />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2 control-label required">Lampiran Surat</label>
                                                <div class="col-md-10">
                                                    <input type="file" name="lampiran" class="fileinput btn-primary" id="filename" title="Cari file" />
                                                    <span class="help-block">Format hanya berlaku pdf,jpg,pdf : <a href="<?php echo base_url('assets/lampiransuratmasuk/') . $sm->lampiran; ?>"><?= $sm->lampiran; ?></a></span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2 control-label required">Perihal</label>
                                                <div class="col-md-10">
                                                    <input type="text" name="hal" class="form-control" value="<?= $sm->hal; ?>" required />
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2 control-label required">Tanggal diterima</label>
                                                <div class="col-md-10">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                        <input type="text" name="diterima" id="diterima" class="form-control datepicker" value="<?= $sm->diterima; ?>" onblur="changeDateAccept()" required />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2 control-label required">Penerima</label>
                                                <div class="col-md-10">
                                                    <input type="text" name="penerima" class="form-control" value="<?= $sm->penerima; ?>" required />
                                                </div>
                                            </div>

                                            <!-- <div class="form-group">
                                                <label class="col-md-2 control-label">Indeks</label>
                                                <div class="col-md-10">
                                                    <input type="text" name="indeks" class="form-control" value="<?= $sm->indeks; ?>" required />
                                                </div>
                                            </div> -->

                                            <div class="form-group">
                                                <label class="col-md-2 control-label required">Kode Surat</label>
                                                <div class="col-md-10">
                                                    <select name="kodesurat_id" id="kodesurat_id" class="form-control select" data-live-search="true" required>
                                                        <option value="">Pilih Kode Surat</option>
                                                        <?php foreach ($kodesurat as $key => $h) { ?>
                                                            <option value="<?php echo $h->kodesurat_id ?>" <?php if ($h->kodesurat_id == $sm->kodesurat_id) {
                                                                echo "selected";
                                                            } ?>>
                                                                <?php echo $h->kode; ?> - <?php echo substr($h->tentang, 0, 80); ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2 control-label required">Sifat</label>
                                                <div class="col-md-10">
                                                    <select class="form-control select" name="sifat" data-live-search="true" required>
                                                        <option><?= $sm->sifat; ?></option>
                                                        <option value="Biasa">Biasa</option>
                                                        <option value="Segera">Segera</option>
                                                        <option value="Sangat Segera">Sangat Segera</option>
                                                        <option value="Penting">Penting</option>
                                                        <option value="Rahasia">Rahasia</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Lampiran Lainnya</label>
                                                <div class="col-md-10">
                                                    <input type="file" name="lampiran_lain" class="fileinput btn-primary" id="filename" title="Cari file" accept="application/pdf" />
                                                    <span class="help-block">Format hanya berlaku pdf : <?= $sm->lampiran_lain; ?></span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Telp</label>
                                                <div class="col-md-10">
                                                    <input type="text" name="telp" class="form-control" value="<?= $sm->telp; ?>" required />
                                                </div>
                                            </div>

                                        </div>
                                        <!-- END:Identitas Surat -->

                                        <!-- START:Feat Retensi Arsip Surat Masuk -->
                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label> JADWAL RETENSI ARSIP</label><br>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-md-2"></div>
                                                <div class="col-md-10">
                                                    <div class="alert alert-info" role="alert">
                                                        <p>
                                                            <i class="fa fa-info-circle"></i>
                                                            <b>Lampiran File Series JRA : 
                                                                <a href="<?php echo site_url('assets/surat/Lampiran-JRA.pdf') ?>" target="_blank"> 
                                                                    Lihat Disini
                                                                </a>
                                                            </b>
                                                        </p>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2 control-label required">Series</label>
                                                <div class="col-md-10">
                                                    <select name="jra_id" id="jra_id" class="form-control select" data-live-search="true" required>
                                                        <option value="">-- Pilih Series --</option>
                                                        <?php foreach ($jra as $key => $j) { ?>
                                                            <option value="<?php echo $j->id ?>" <?php if ($j->id == $sm->jra_id) {
                                                                                                        echo "selected";
                                                                                                    } ?>>
                                                                <?php echo $j->series;
                                                                if ($j->sub_series) { ?>
                                                                    - <?php echo $j->sub_series;
                                                                    } ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Retensi Aktif (Tahun)</label>
                                                <div class="col-md-10">
                                                    <input type="text" name="retensi_aktif" id="retensi_aktif" class="form-control" onblur="getTahunAktif()" value="<?= $sm->retensi_aktif ?> Tahun" readonly />
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Tahun Aktif</label>
                                                <div class="col-md-10">
                                                    <input type="number" name="tahun_aktif" id="tahun_aktif" class="form-control" value="<?= date('Y', strtotime($sm->diterima)) + $sm->retensi_aktif; ?>" readonly />
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Retensi In-Aktif (Tahun)</label>
                                                <div class="col-md-10">
                                                    <input type="text" name="retensi_inaktif" id="retensi_inaktif" class="form-control" onblur="getTahunAktif()" value="<?= $sm->retensi_inaktif ?> Tahun" readonly />
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Tahun In-Aktif</label>
                                                <div class="col-md-10">
                                                    <input type="number" name="tahun_inaktif" id="tahun_inaktif" class="form-control" value="<?= date('Y', strtotime($sm->diterima)) + $sm->retensi_aktif + $sm->retensi_inaktif; ?>" readonly />
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Keterangan JRA</label>
                                                <div class="col-md-10">
                                                    <input type="text" name="ket_jra" id="ket_jra" class="form-control" value="<?= $sm->jra ?>" readonly />
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Nilai Guna</label>
                                                <div class="col-md-10">
                                                    <input type="text" name="nilai_guna" id="nilai_guna" class="form-control" value="<?= $sm->nilai_guna ?>" readonly />
                                                </div>
                                            </div>

                                        </div>
                                        <!-- END:Feat Retensi Arsip Surat Masuk -->
                                    </div>
                                    <!-- END:Identitas dan Retensi Surat -->

                                    <!-- START:Isi dan Catatan Surat [@Dam-Egov 10/01/2024] -->
                                    <div>
                                        <div class="col-md-12"><br>
                                            <label class="required">Isi</label>
                                            <textarea id="textarea1" name="isi"><?= $sm->isi; ?></textarea>
                                        </div>

                                        <div class="col-md-12"><br>
                                            <label>Catatan</label>
                                            <textarea id="textarea1" name="catatan"><?= $sm->catatan; ?></textarea>
                                        </div>
                                    </div>
                                    <!-- END:Isi dan Catatan Surat -->

                                </div>

                            </div>
                            <div class="panel-footer">
                                <button class="btn btn-danger" type="reset">Bersihkan Formulir</button>
                                <button class="btn btn-primary pull-right" type="submit">Simpan</button>
                            </div>
                        </div>
                    </form>
            <?php }
            } ?>
            <!-- END DEFAULT FORM -->

        </div>
    </div>
</div>

<!-- RETENSI SCRIPT -->
<style>
    .dropdown-menu>li>a {
        white-space: normal !important;
    }
</style>
<script>
    $(document).ready(function() {
        $('#jra_id').on('change', function() {
            var jraID = $(this).val();
            if (jraID) {
                $.ajax({
                    url: '<?php echo base_url(); ?>ApiTnd/jra/' + jraID,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#retensi_aktif').val(data.retensi_aktif + ' Tahun');
                        $('#retensi_inaktif').val(data.retensi_inaktif + ' Tahun');
                        $('#ket_jra').val(data.jra);
                        $('#nilai_guna').val(data.nilai_guna);

                        var t_diterima = document.getElementById('diterima').value;

                        var d = new Date(t_diterima);
                        let dt = d.getFullYear();

                        var tr_aktif = parseInt(dt) + parseInt(data.retensi_aktif);
                        var tr_inaktif = parseInt(tr_aktif) + parseInt(data.retensi_inaktif);

                        $('#tahun_aktif').val(tr_aktif);
                        $('#tahun_inaktif').val(tr_inaktif);

                    },
                    error: function(data) {
                        console.log('error');
                    }
                });
            }
        });
    });
</script>
<script type="text/javascript" src="<?php echo base_url('assets/js/retensisurat.js') ?>"></script>