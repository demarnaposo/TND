<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo site_url('home/dashboard') ?>">Beranda</a></li>
    <li><a href="javascript:void(0)">Naskah Dinas Surat</a></li>
    <li><a href="<?php echo site_url('suratkeluar/draft') ?>">Draft Surat</a></li>
    <li class="active">Formulir</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">
    <h2><span class="fa fa-envelope"></span> Formulir Penomoran dan Pengarsipan Surat</h2>
</div>
<!-- END PAGE TITLE -->

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">

    <div class="row">
        <div class="col-md-12">

            <!-- START DEFAULT FORM -->
            <form class="form-horizontal" action="<?php echo site_url('suratkeluar/draft/disposisi') ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="surat_id" value="<?php echo $this->uri->segment(4) ?>" required>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <a href="javascript:history.back()" class="btn btn-default">&laquo; Kembali</a> <br><br>
                    </div>
                    <div class="panel-body">

                        <div class="row">

                            <?php


                            // iki 
                            $suratid = $this->uri->segment(4);
                            $suratselesai = $this->db->query("SELECT ket_selesai FROM selesai WHERE selesai.surat_id='$suratid'")->row_array();
                            $suratpem = $this->db->query("SELECT dari FROM verifikasi WHERE verifikasi.surat_id= '$suratid' limit 1")->row_array();
                            // $nik=$nodin['nik'];

                            // echo "<center>SURAT SUDAH DIARSIPKAN</center>";

                            $tanggal = $this->db->query("SELECT tanggal FROM draft WHERE surat_id='$suratid'")->row();

                            ?>


                            <?php if (empty($nomor)) { ?>
                                <!-- iki -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label> Surat Dibuat Oleh : <?php echo $suratpem['dari'];  ?> </label><br>
                                        <label> Surat diselesaikan Oleh : <?php echo $suratselesai['ket_selesai'];  ?> </label><br> <br>
                                    </div>

                                </div>
                                <br>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label required">Kode Surat</label>
                                        <div class="col-md-10">
                                            <select name="kodesurat_id" class="form-control select" data-live-search="true" required>
                                                <option value="">Pilih Kode Surat</option>
                                                <?php foreach ($kodesurat as $key => $h) { ?>
                                                    <option value="<?php echo $h->kodesurat_id ?>"><?php echo $h->kode; ?> - <?php echo substr($h->tentang, 0, 130); ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label required">Nomor Urut Surat</label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" name="no_urut" value="000" maxlength="5" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label required">Kode Bidang</label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" name="kode_bidang" required>
                                        </div>
                                    </div>
                                    <!-- [UPDATE] Fikri Egov 26012022 16:12Pm Pengkondisian Jika Surat Nota Dinas, Maka Penandatanganan Si Pembuatnya-->
                                    <?php
                                    $nodinid = $this->uri->segment(4);
                                    if (substr($nodinid, 0, 5) == 'NODIN') {
                                        $nodin = $this->db->query("SELECT aparatur.nik, aparatur.nama,jabatan.nama_jabatan, jabatan.jabatan_id FROM draft
                                    LEFT join aparatur ON aparatur.jabatan_id=draft.dibuat_id
                                    LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
                                    WHERE draft.surat_id='$nodinid' AND aparatur.statusaparatur='Aktif'")->row_array();
                                        $nik = $nodin['nik'];
                                        // $cekpenandatangan=$this->db->query("SELECT * FROM ttd_history WHERE nip='$nip'")->num_rows();
                                    ?>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label required">Penandatangan Pembuat Surat</label>
                                            <div class="col-md-10">
                                                <?php if ($nik == 0) { ?>
                                                    <div class="alert alert-danger" role="alert">
                                                        <b><?= $nodin['nama']; ?></b> Pembuat Surat Tidak Terdaftar TTE. Silahkan hubungi Admin/Pimpinan
                                                    </div>
                                                    <select name="jabatan_id" class="form-control select" data-live-search="true" required>
                                                        <?php foreach ($ttdnodin as $key => $t) { ?>
                                                            <option value="<?php echo $t->jabatan_id ?>"><?php echo $t->nama; ?> - <?php echo $t->nama_jabatan; ?></option>
                                                        <?php } ?>
                                                        <!-- <option value="<?php echo $ttdnodin['jabatan_id'] ?>"><?php echo $ttdnodin['nama'] ?> - <?php echo $ttdnodin['nama_jabatan']; ?></option> -->
                                                    </select>
                                                <?php } else { ?>
                                                    <select name="jabatan_id" class="form-control select" data-live-search="true" required>
                                                        <?php foreach ($ttdnodin as $key => $t) { ?>
                                                            <option value="<?php echo $t->jabatan_id ?>"><?php echo $t->nama; ?> - <?php echo $t->nama_jabatan; ?></option>
                                                        <?php } ?>
                                                        <!-- <option value="<?php echo $ttdnodin['jabatan_id'] ?>"><?php echo $ttdnodin['nama'] ?> -  <?php echo $ttdnodin['nama_jabatan']; ?></option> -->

                                                        <option value="<?php echo $nodin['jabatan_id'] ?>"><?php echo $nodin['nama'] ?> - <?php echo $nodin['nama_jabatan']; ?></option>
                                                    </select>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label required">Penandatangan</label>
                                            <div class="col-md-10">
                                                <select name="jabatan_id" class="form-control select" data-live-search="true" required>
                                                    <?php foreach ($penandatangan as $key => $h) { ?>
                                                        <option value="<?php echo $h->jabatan_id ?>"><?php echo $h->nama; ?> - <?php echo $h->nama_jabatan; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>

                                            <!-- [UPDATE] Fikri Egov 26012022 16:12Pm Pengkondisian Jika Surat Nota Dinas, Maka Penandatanganan Si Pembuatnya-->
                                        </div>
                                        <!-- </div> -->
                                    <?php } ?>
                                    <!-- START:Feat Retensi Arsip Surat Keluar -->
                                    <div>

                                        <div class="form-group">
                                            <label class="col-md-2 control-label"> JADWAL RETENSI ARSIP</label>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-2 control-label">Tanggal Surat</label>
                                            <div class="col-md-10">
                                                <input type="date" name="tanggal" id="tanggal" class="form-control datepicker" value="<?php echo $tanggal->tanggal ?>" readonly />
                                            </div>
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
                                </div>
                                <!-- END:Feat Retensi Arsip Surat Keluar -->
                                <div class="col-md-12">
                                    <br><br>
                                    <center> <button class="btn btn-info" name="simpan" type="submit">Simpan</button> </center>
                                </div>

                                <?php } elseif ($this->uri->segment(3) == 'edit') {
                                foreach ($tandatangan as $key => $e) { ?>
                                    <input type="hidden" name="penandatangan_id" value="<?php echo $e->penandatangan_id ?>" />
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-md-2 control-label required">Kode Surat</label>
                                            <div class="col-md-10">
                                                <select name="kodesurat_id" id="kodesurat_id" class="form-control select" data-live-search="true" required>
                                                    <option value="">Pilih Kode Surat</option>
                                                    <?php foreach ($kodesurat as $key => $h) { ?>
                                                        <option value="<?php echo $h->kodesurat_id ?>" <?php if ($h->kodesurat_id == $kodesurat_id) {
                                                                                                            echo "selected";
                                                                                                        } ?>>
                                                            <?php echo $h->kode; ?> - <?php echo substr($h->tentang, 0, 130); ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <!-- Start Update 13/03/2024 Penambahan jika ingin mengubah nomor urut dan kode bidang -->
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">Nomor Surat</label>
                                            <div class="col-md-10">
                                                <input type="text" name="nomor" class="form-control" value="<?php echo $nomor ?>" readonly />
                                            </div>
                                        </div>

                                        <script>
                                            function yesnoCheck() {
                                                if (document.getElementById('yesCheck').checked) {
                                                    document.getElementById('ifYes').style.display = 'block';
                                                    $("#no_urut").attr('required', '');
                                                    $("#kode_bidang").attr('required', '');
                                                } else
                                                    document.getElementById('ifYes').style.display = 'none';
                                                $("#no_urut").removeAttr('required');
                                                $("#kode_bidang").removeAttr('required');
                                            }
                                        </script>

                                        <div class="form-group">
                                            <div class="col-md-2"></div>
                                            <div class="col-md-10">
                                                <div class="alert alert-warning" role="alert">
                                                    <p><b>Apakah ingin mengubah nomor urut dan kode bidang?</b>
                                                        <input type="radio" onclick="javascript:yesnoCheck();" name="yesno" id="yesCheck"> Iya /
                                                        <input type="radio" onclick="javascript:yesnoCheck();" name="yesno" id="noCheck"> Tidak
                                                    </p>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="form-group" id="ifYes" style="display:none">
                                            <div>
                                                <label class="col-md-2 control-label required">Nomor Urut Surat</label>
                                                <div class="col-md-10">
                                                    <input type="number" class="form-control" name="no_urut" id="no_urut" max="9999" required>
                                                </div>
                                            </div><br /><br /><br />
                                            <div>
                                                <label class="col-md-2 control-label required">Kode Bidang </label>
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control" name="kode_bidang" id="kode_bidang" required>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- End Update 13/03/2024 Penambahan jika ingin mengubah nomor urut dan kode bidang -->
                                        <!-- [UPDATE] Idham Egov 20042022 16:05 pm Pengkondisian Jika Surat Nota Dinas, Maka Penandatanganan Kadis dan Si Pembuatnya-->
                                        <?php
                                        $nodinid = $this->uri->segment(4);
                                        if (substr($nodinid, 0, 5) == 'NODIN') {
                                            $nodin = $this->db->query("SELECT aparatur.nik, aparatur.nama,jabatan.nama_jabatan, jabatan.jabatan_id FROM draft
                                    LEFT join aparatur ON aparatur.jabatan_id=draft.dibuat_id
                                    LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
                                    WHERE draft.surat_id='$nodinid'")->row_array();
                                            $nik = $nodin['nik'];
                                            // $cekpenandatangan=$this->db->query("SELECT * FROM aparatur WHERE nik='$nik'")->num_rows();
                                        ?>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label required">Penandatangan Pembuat Surat</label>
                                                <div class="col-md-10">
                                                    <?php if ($nik == 0) { ?>
                                                        <div class="alert alert-danger" role="alert">
                                                            <b><?= $nodin['nama']; ?></b> Pembuat Surat Tidak Terdaftar TTE. Silahkan hubungi Admin/Pimpinan
                                                        </div>
                                                        <select name="jabatan_id" class="form-control select" data-live-search="true" required>
                                                            <option value="<?php echo $ttdnodin['jabatan_id'] ?>"><?php echo $ttdnodin['nama'] ?> - <?php echo $ttdnodin['nama_jabatan']; ?></option>
                                                        </select>
                                                    <?php } else { ?>
                                                        <select name="jabatan_id" class="form-control select" data-live-search="true" required>
                                                            <option value="<?php echo $ttdnodin['jabatan_id'] ?>" <?php if ($ttdnodin['jabatan_id'] == $e->jabatan_id) {
                                                                                                                        echo "selected";
                                                                                                                    } ?>><?php echo $ttdnodin['nama'] ?> - <?php echo $ttdnodin['nama_jabatan']; ?></option>
                                                            <option value="<?php echo $nodin['jabatan_id'] ?>" <?php if ($nodin['jabatan_id'] == $e->jabatan_id) {
                                                                                                                    echo "selected";
                                                                                                                } ?>><?php echo $nodin['nama'] ?> - <?php echo $nodin['nama_jabatan']; ?></option>
                                                        </select>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        <?php } else { ?>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label required">Penandatangan</label>
                                                <div class="col-md-10">
                                                    <select name="jabatan_id" class="form-control select" data-live-search="true" required>
                                                        <?php foreach ($penandatangan as $key => $h) { ?>
                                                            <option value="<?php echo $h->jabatan_id ?>" <?php if ($h->jabatan_id == $e->jabatan_id) {
                                                                                                                echo "selected";
                                                                                                            } ?>>
                                                                <?php echo $h->nama; ?> - <?php echo $h->nama_jabatan; ?>
                                                            </option>
                                                    <?php }
                                                    } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- START:Feat Retensi Arsip Surat Keluar -->
                                            <div>

                                                <div class="form-group">
                                                    <label class="col-md-2 control-label"> JADWAL RETENSI ARSIP</label>
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
                                                    <label class="col-md-2 control-label">Tanggal Surat</label>
                                                    <div class="col-md-10">
                                                        <input type="date" name="tanggal" id="tanggal" class="form-control datepicker" value="<?php echo $tanggal->tanggal ?>" readonly />
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-md-2 control-label required">Series</label>
                                                    <div class="col-md-10">
                                                        <select name="jra_id" id="jra_id" class="form-control select" data-live-search="true" required>
                                                            <option value="">-- Pilih Series --</option>
                                                            <?php foreach ($series as $key => $j) { ?>
                                                                <option value="<?php echo $j->id ?>" <?php if ($j->id == $jra->jra_id) {
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
                                                        <input type="text" name="retensi_aktif" id="retensi_aktif" class="form-control" onblur="getTahunAktif()" value="<?= $jra->retensi_aktif ?> Tahun" readonly />
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Tahun Aktif</label>
                                                    <div class="col-md-10">
                                                        <input type="number" name="tahun_aktif" id="tahun_aktif" class="form-control" value="<?= date('Y', strtotime($e->tanggal)) + $jra->retensi_aktif; ?>" readonly />
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Retensi In-Aktif (Tahun)</label>
                                                    <div class="col-md-10">
                                                        <input type="text" name="retensi_inaktif" id="retensi_inaktif" class="form-control" onblur="getTahunAktif()" value="<?= $jra->retensi_inaktif ?> Tahun" readonly />
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Tahun In-Aktif</label>
                                                    <div class="col-md-10">
                                                        <input type="number" name="tahun_inaktif" id="tahun_inaktif" class="form-control" value="<?= date('Y', strtotime($e->tanggal)) + $jra->retensi_aktif + $jra->retensi_inaktif; ?>" readonly />
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Keterangan JRA</label>
                                                    <div class="col-md-10">
                                                        <input type="text" name="ket_jra" id="ket_jra" class="form-control" value="<?= $jra->jra ?>" readonly />
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Nilai Guna</label>
                                                    <div class="col-md-10">
                                                        <input type="text" name="nilai_guna" id="nilai_guna" class="form-control" value="<?= $jra->nilai_guna ?>" readonly />
                                                    </div>
                                                </div>

                                            </div>
                                            <!-- END:Feat Retensi Arsip Surat Keluar -->
                                        <?php } ?>
                                        <!-- [UPDATE] Idham Egov 20042022 16:05 pm Pengkondisian Jika Surat Nota Dinas, Maka Penandatanganan Kadis dan Si Pembuatnya-->
                                    </div>

                                    <div class="col-md-12">
                                        <br><br>
                                        <center> <button class="btn btn-info" name="ubah" type="submit">Simpan</button> </center>
                                    </div>

                                <?php } else { ?>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label> Nomor Surat : <?php echo $nomor ?> </label>
                                        </div>
                                        <div class="form-group">
                                            <label> Penandatangan Surat : <?php echo $ttd ?> </label>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-md-12 control-label" style="font-size: 20px;">
                                                <center>Pengarsipan</center>
                                            </label>
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
                                            <label class="col-md-2 control-label">No Buku</label>
                                            <div class="col-md-10">
                                                <input type="text" name="no_book" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <br>
                                        <center> <button class="btn btn-primary" name="arsipkan" type="submit">Arsipkan</button> </center>
                                    </div>

                                <?php } ?>

                        </div>

                    </div>
                </div>
                <div class="panel-footer">
                    <button class="btn btn-danger" type="reset">Bersihkan Formulir</button>
                </div>
            </form>
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

                        var t_surat = document.getElementById('tanggal').value;

                        var d = new Date(t_surat);
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
<!-- <script type="text/javascript" src="<?php echo base_url('assets/js/retensisurat.js') ?>"></script> -->