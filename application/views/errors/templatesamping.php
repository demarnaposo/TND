<!-- START PAGE SIDEBAR -->
<div class="page-sidebar">
    
    <!-- START X-NAVIGATION -->
    <ul class="x-navigation">
        
        <li class="xn-logo">
            <a href="<?php echo site_url('home/dashboard') ?>"><b>TND</b></a>
            <a href="#" class="x-navigation-control"></a>
        </li>
        <li class="xn-profile">
            <a href="#" class="profile-mini">
                <?php if (empty($this->session->userdata('foto'))) { ?>
                    <img src="<?php echo base_url('assets/imagesusers/user-default.png'); ?>" alt="<?php echo $this->session->userdata('nama') ?>"/>
                <?php }else{ ?>
                    <img src="<?php echo base_url('assets/imagesusers/'.$this->session->userdata('foto')); ?>" alt="<?php echo $this->session->userdata('nama') ?>"/>
                <?php } ?>
            </a>
            <div class="profile">
                <div class="profile-image">
                    <?php if (empty($this->session->userdata('foto'))) { ?>
                        <img src="<?php echo base_url('assets/imagesusers/user-default.png'); ?>" alt="<?php echo $this->session->userdata('nama') ?>"/>
                    <?php }else{ ?>
                        <img src="<?php echo base_url('assets/imagesusers/'.$this->session->userdata('foto')); ?>" alt="<?php echo $this->session->userdata('nama') ?>"/>
                    <?php } ?>
                </div>
                <div class="profile-data">
                    <?php if ($this->session->userdata('level') == 1 OR $this->session->userdata('level') == 2) { ?>
                        <div class="profile-data-name"><?php echo $this->session->userdata('username'); ?></div>
                        <div class="profile-data-title"><?php echo $this->session->userdata('email'); ?></div>
                    <?php }else{ ?>
                        <div class="profile-data-name"><?php echo $this->session->userdata('nama'); ?></div>
                        <div class="profile-data-title"><?php echo $this->session->userdata('nama_jabatan'); ?></div>
                    <?php } ?>
                </div>
            </div>                                                                        
        </li>

        <!-- START MENU -->
        <li class="xn-title">Menu Utama</li>
        <li class="<?php if($this->uri->segment(2) == 'dashboard'){ echo 'active'; } ?>"> 
            <a href="<?php echo site_url('home/dashboard') ?>"><span class="fa fa-desktop"></span> <span class="xn-text">Beranda </span></a> 
        </li>
        <li class="<?php if($this->uri->segment(2) == 'dashboard'){ echo 'active'; } ?>"> 
            <a href="https://wa.me/6287789819311"><span class="fa fa-desktop"></span> <span class="xn-text">Helpdesk TND </span></a> 
        </li>
        
        
        <!-- START MENU SURAT -->

        <?php if($this->uri->segment(1) == 'suratkeluar' AND $this->uri->segment(2) != 'draft'){ ?>
        
            <li class="xn-title">Naskah Dinas Surat</li>

            <!--  Surat Instruksi -->
            <li class="<?php if($this->uri->segment(2) == 'instruksi'){ echo 'active'; } ?>"> 
                <a href="
                    <?php 
                        if($this->uri->segment(1) == 'suratkeluar'){ 
                            echo site_url('suratkeluar/instruksi'); 
                        }
                    ?>
                "><span class="fa fa-envelope-o"></span> <span class="xn-text">Surat Instruksi</span>
                <?php
                $jabatanid=$this->session->userdata('jabatan_id');
                $instruksi=$this->db->query("SELECT count(a.nama_surat) as jumlah FROM draft a WHERE a.nama_surat='Surat Instruksi' and a.dibuat_id='$jabatanid'")->result();
                foreach($instruksi as $key => $ins){
                    if($ins->jumlah == 0){
                        echo "";
                    }else{
                        echo "<span class='badge bg-warning text-dark'>".$ins->jumlah."</span>";
                    }
                    }
                ?></a> 
            </li>
            <!-- Surat Instruksi -->

            <li class="<?php if($this->uri->segment(2) == 'edaran'){ echo 'active'; } ?>">
                <a href="
                    <?php 
                        if($this->uri->segment(1) == 'suratkeluar'){ 
                            echo site_url('suratkeluar/edaran'); 
                        } 
                    ?>
                "><span class="fa fa-envelope-o"></span> <span class="xn-text">Surat Edaran</span>
                <?php
                    $jabatanid=$this->session->userdata('jabatan_id');
                    $edaran=$this->db->query("SELECT count(a.nama_surat) as jumlah FROM draft a WHERE a.nama_surat='Surat Edaran' and a.dibuat_id='$jabatanid'")->result();
                    foreach($edaran as $key => $edr){
                         if($edr->jumlah == 0){
                             echo "";
                         }else{
                            echo "<span class='badge bg-warning text-dark'>".$edr->jumlah."</span>";
                         }
                    }
                
                ?></a>
            </li>
            <li class="<?php if($this->uri->segment(2) == 'biasa'){ echo 'active'; } ?>">
                <a href="
                    <?php 
                        if($this->uri->segment(1) == 'suratkeluar'){ 
                            echo site_url('suratkeluar/biasa'); 
                        }
                    ?>
                "><span class="fa fa-envelope-o"></span> <span class="xn-text">Surat Biasa</span>
                <?php $jabatanid=$this->session->userdata('jabatan_id');
                $biasa=$this->db->query("SELECT count(a.nama_surat) as jumlah FROM draft a WHERE a.nama_surat='Surat Biasa' and a.dibuat_id='$jabatanid'")->result();
                foreach($biasa as $key => $bia){
                    if($bia->jumlah == 0){
                        echo "";
                    }else{
                       echo "<span class='badge bg-warning text-dark'>".$bia->jumlah."</span>";
                    }
                } 
                ?></a>
            </li>
            <li class="<?php if($this->uri->segment(2) == 'keterangan'){ echo 'active'; } ?>"> 
                <a href="
                    <?php 
                        if($this->uri->segment(1) == 'suratkeluar'){ 
                            echo site_url('suratkeluar/keterangan'); 
                        }
                    ?>
                "><span class="fa fa-envelope-o"></span> <span class="xn-text">Surat Keterangan</span>
                <?php $jabatanid=$this->session->userdata('jabatan_id');
                $keterangan=$this->db->query("SELECT count(a.nama_surat) as jumlah FROM draft a WHERE a.nama_surat='Surat Keterangan' and a.dibuat_id='$jabatanid'")->result();
                foreach($keterangan as $key => $ket){ 
                    if($ket->jumlah == 0){
                        echo "";
                    }else{
                       echo "<span class='badge bg-warning text-dark'>".$ket->jumlah."</span>";
                    }
                }?></a> 
            </li>
            <li class="<?php if($this->uri->segment(2) == 'perintahtugas'){ echo 'active'; } ?>"> 
                <a href="
                    <?php 
                        if($this->uri->segment(1) == 'suratkeluar'){ 
                            echo site_url('suratkeluar/perintahtugas'); 
                        }
                    ?>
                "><span class="fa fa-envelope-o"></span> <span class="xn-text">Surat Perintah Tugas</span>
                <?php $jabatanid=$this->session->userdata('jabatan_id');
                $perintahtugas=$this->db->query("SELECT count(a.nama_surat) as jumlah FROM draft a WHERE a.nama_surat='Surat Perintah Tugas' and a.dibuat_id='$jabatanid'")->result();
                foreach($perintahtugas as $key => $pt){
                    if($pt->jumlah == 0){
                        echo "";
                    }else{
                       echo "<span class='badge bg-warning text-dark'>".$pt->jumlah."</span>";
                    }
                }?></a> 
            </li>
            <li class="<?php if($this->uri->segment(2) == 'perintah'){ echo 'active'; } ?>"> 
                <a href="
                    <?php 
                        if($this->uri->segment(1) == 'suratkeluar'){ 
                            echo site_url('suratkeluar/perintah'); 
                        }
                    ?>
                "><span class="fa fa-envelope-o"></span> <span class="xn-text">Surat Perintah</span>
                <?php $jabatanid=$this->session->userdata('jabatan_id');
                $perintah=$this->db->query("SELECT count(a.nama_surat) as jumlah FROM draft a WHERE a.nama_surat='Surat Perintah' and a.dibuat_id='$jabatanid'")->result();
                foreach($perintah as $key => $p){
                    if($p->jumlah == 0){
                        echo "";
                    }else{
                       echo "<span class='badge bg-warning text-dark'>".$p->jumlah."</span>";
                    }
                }?></a> 
            </li>
            <li class="<?php if($this->uri->segment(2) == 'izin'){ echo 'active'; } ?>"> 
                <a href="
                    <?php 
                        if($this->uri->segment(1) == 'suratkeluar'){ 
                            echo site_url('suratkeluar/izin'); 
                        }
                    ?>
                "><span class="fa fa-envelope-o"></span> <span class="xn-text">Surat Izin</span>
                <?php $jabatanid=$this->session->userdata('jabatan_id');
                $izin=$this->db->query("SELECT count(a.nama_surat) as jumlah FROM draft a WHERE a.nama_surat='Surat Izin' and a.dibuat_id='$jabatanid'")->result();
                foreach($izin as $key => $iz){
                    if($iz->jumlah == 0){
                        echo "";
                    }else{
                       echo "<span class='badge bg-warning text-dark'>".$iz->jumlah."</span>";
                    }
                }?></a> 
            </li>
            <li class="<?php if($this->uri->segment(2) == 'perjalanan'){ echo 'active'; } ?>"> 
                <a href="
                    <?php 
                        if($this->uri->segment(1) == 'suratkeluar'){ 
                            echo site_url('suratkeluar/perjalanan'); 
                        }
                    ?>
                "><span class="fa fa-envelope-o"></span> <span class="xn-text">Surat Perjalanan Dinas</span>
                <?php $jabatanid=$this->session->userdata('jabatan_id');
                $spd=$this->db->query("SELECT count(a.nama_surat) as jumlah FROM draft a WHERE a.nama_surat='Surat Perjalanan Dinas' and a.dibuat_id='$jabatanid'")->result();
                foreach($spd as $key => $s){
                    if($s->jumlah == 0){
                        echo "";
                    }else{
                       echo "<span class='badge bg-warning text-dark'>".$s->jumlah."</span>";
                    }
                }?></a> 
            </li>
            <li class="<?php if($this->uri->segment(2) == 'kuasa'){ echo 'active'; } ?>"> 
                <a href="
                    <?php 
                        if($this->uri->segment(1) == 'suratkeluar'){ 
                            echo site_url('suratkeluar/kuasa'); 
                        }
                    ?>
                "><span class="fa fa-envelope-o"></span> <span class="xn-text">Surat Kuasa/Surat Kuasa Khusus</span>
                <?php $jabatanid=$this->session->userdata('jabatan_id');
                $kuasa=$this->db->query("SELECT count(a.nama_surat) as jumlah FROM draft a WHERE a.nama_surat='Surat Kuasa' and a.dibuat_id='$jabatanid'")->result();
                foreach($kuasa as $key => $k){
                    if($k->jumlah == 0){
                        echo "";
                    }else{
                       echo "<span class='badge bg-warning text-dark'>".$k->jumlah."</span>";
                    }
                }?></a> 
            </li>
            <li class="<?php if($this->uri->segment(2) == 'undangan'){ echo 'active'; } ?>"> 
                <a href="
                    <?php 
                        if($this->uri->segment(1) == 'suratkeluar'){ 
                            echo site_url('suratkeluar/undangan'); 
                        }
                    ?>
                "><span class="fa fa-envelope-o"></span> <span class="xn-text">Surat Undangan</span>
                <?php $jabatanid=$this->session->userdata('jabatan_id');
                $undangan=$this->db->query("SELECT count(a.nama_surat) as jumlah FROM draft a WHERE a.nama_surat='Surat Undangan' and a.dibuat_id='$jabatanid'")->result();
                foreach($undangan as $key => $ud){ 
                    if($ud->jumlah == 0){
                        echo "";
                    }else{
                       echo "<span class='badge bg-warning text-dark'>".$ud->jumlah."</span>";
                    }
                }?></a> 
            </li>
            <li class="<?php if($this->uri->segment(2) == 'melaksanakan_tugas'){ echo 'active'; } ?>"> 
                <a href="
                    <?php 
                        if($this->uri->segment(1) == 'suratkeluar'){ 
                            echo site_url('suratkeluar/melaksanakan_tugas'); 
                        }
                    ?>
                "><span class="fa fa-envelope-o"></span> <span class="xn-text">Surat Pernyataan Melaksanakan Tugas</span>
                <?php $jabatanid=$this->session->userdata('jabatan_id');
                $melaksanakantgs=$this->db->query("SELECT count(a.nama_surat) as jumlah FROM draft a WHERE a.nama_surat='Surat Melaksanakan Tugas' and a.dibuat_id='$jabatanid'")->result();
                foreach($melaksanakantgs as $key => $tgs){
                    if($tgs->jumlah == 0){
                        echo "";
                    }else{
                       echo "<span class='badge bg-warning text-dark'>".$tgs->jumlah."</span>";
                    }
                }?></a> 
            </li>
            <li class="<?php if($this->uri->segment(2) == 'panggilan'){ echo 'active'; } ?>"> 
                <a href="
                    <?php 
                        if($this->uri->segment(1) == 'suratkeluar'){ 
                            echo site_url('suratkeluar/panggilan'); 
                        }
                    ?>
                "><span class="fa fa-envelope-o"></span> <span class="xn-text">Surat Panggilan</span>
                <?php $jabatanid=$this->session->userdata('jabatan_id');
                $panggilan=$this->db->query("SELECT count(a.nama_surat) as jumlah FROM draft a WHERE a.nama_surat='Surat Panggilan' and a.dibuat_id='$jabatanid'")->result();
                foreach($panggilan as $key => $pg){
                    if($pg->jumlah == 0){
                        echo "";
                    }else{
                       echo "<span class='badge bg-warning text-dark'>".$pg->jumlah."</span>";
                    }
                }?></a> 
            </li>
            <li class="<?php if($this->uri->segment(2) == 'notadinas'){ echo 'active'; } ?>"> 
                <a href="
                    <?php 
                        if($this->uri->segment(1) == 'suratkeluar'){ 
                            echo site_url('suratkeluar/notadinas'); 
                        }
                    ?>
                "><span class="fa fa-envelope-o"></span> <span class="xn-text">Nota Dinas</span>
                <?php $jabatanid=$this->session->userdata('jabatan_id');
                $notadinas=$this->db->query("SELECT count(a.nama_surat) as jumlah FROM draft a WHERE a.nama_surat='Surat Nota Dinas' and a.dibuat_id='$jabatanid'")->result();
                foreach($notadinas as $key => $nd){
                    if($nd->jumlah == 0){
                        echo "";
                    }else{
                       echo "<span class='badge bg-warning text-dark'>".$nd->jumlah."</span>";
                    }
                }?></a> 
            </li>
            <li class="<?php if($this->uri->segment(2) == 'pengumuman'){ echo 'active'; } ?>"> 
                <a href="
                    <?php 
                        if($this->uri->segment(1) == 'suratkeluar'){ 
                            echo site_url('suratkeluar/pengumuman'); 
                        }
                    ?>
                "><span class="fa fa-envelope-o"></span> <span class="xn-text">Pengumuman</span>
                <?php $jabatanid=$this->session->userdata('jabatan_id');
                $pengumuman=$this->db->query("SELECT count(a.nama_surat) as jumlah FROM draft a WHERE a.nama_surat='Surat Pengumuman' and a.dibuat_id='$jabatanid'")->result();
                foreach($pengumuman as $key => $pm){
                    if($pm->jumlah == 0){
                        echo "";
                    }else{
                       echo "<span class='badge bg-warning text-dark'>".$pm->jumlah."</span>";
                    }
                }?></a> 
            </li>
            <li class="<?php if($this->uri->segment(2) == 'laporan'){ echo 'active'; } ?>"> 
                <a href="
                    <?php 
                        if($this->uri->segment(1) == 'suratkeluar'){ 
                            echo site_url('suratkeluar/laporan'); 
                        }
                    ?>
                "><span class="fa fa-envelope-o"></span> <span class="xn-text">Laporan</span> 
                <?php $jabatanid=$this->session->userdata('jabatan_id');
                $laporan=$this->db->query("SELECT count(a.nama_surat) as jumlah FROM draft a WHERE a.nama_surat='Surat Laporan' and a.dibuat_id='$jabatanid'")->result();
                foreach($laporan as $key => $lp){
                    if($lp->jumlah == 0){
                        echo "";
                    }else{
                       echo "<span class='badge bg-warning text-dark'>".$lp->jumlah."</span>";
                    }
                }?></a> 
            </li>
            <li class="<?php if($this->uri->segment(2) == 'rekomendasi'){ echo 'active'; } ?>"> 
                <a href="
                    <?php 
                        if($this->uri->segment(1) == 'suratkeluar'){ 
                            echo site_url('suratkeluar/rekomendasi'); 
                        }
                    ?>
                "><span class="fa fa-envelope-o"></span> <span class="xn-text">Surat Rekomendasi</span>
                <?php $jabatanid=$this->session->userdata('jabatan_id');
                $rekomendasi=$this->db->query("SELECT count(a.nama_surat) as jumlah FROM draft a WHERE a.nama_surat='Surat Rekomendasi' and a.dibuat_id='$jabatanid'")->result();
                foreach($rekomendasi as $key => $rk){
                    if($rk->jumlah == 0){
                        echo "";
                    }else{
                       echo "<span class='badge bg-warning text-dark'>".$rk->jumlah."</span>";
                    }
                }?></a> 
            </li>
            <li class="<?php if($this->uri->segment(2) == 'pengantar'){ echo 'active'; } ?>"> 
                <a href="
                    <?php 
                        if($this->uri->segment(1) == 'suratkeluar'){ 
                            echo site_url('suratkeluar/pengantar'); 
                        }
                    ?>
                "><span class="fa fa-envelope-o"></span> <span class="xn-text">Surat Pengantar</span>
                <?php $jabatanid=$this->session->userdata('jabatan_id');
                $pengantar=$this->db->query("SELECT count(a.nama_surat) as jumlah FROM draft a WHERE a.nama_surat='Surat Pengantar' and a.dibuat_id='$jabatanid'")->result();
                foreach($pengantar as $key => $pgn){
                    if($pgn->jumlah == 0){
                        echo "";
                    }else{
                       echo "<span class='badge bg-warning text-dark'>".$pgn->jumlah."</span>";
                    }
                }?></a> 
            </li>
            <li class="<?php if($this->uri->segment(2) == 'beritaacara'){ echo 'active'; } ?>"> 
                <a href="
                    <?php 
                        if($this->uri->segment(1) == 'suratkeluar'){ 
                            echo site_url('suratkeluar/beritaacara'); 
                        }
                    ?>
                "><span class="fa fa-envelope-o"></span> <span class="xn-text">Berita Acara</span>
                <?php $jabatanid=$this->session->userdata('jabatan_id');
                $beritaacara=$this->db->query("SELECT count(a.nama_surat) as jumlah FROM draft a WHERE a.nama_surat='Surat Berita Acara' and a.dibuat_id='$jabatanid'")->result();
                foreach($beritaacara as $key => $ba){
                    if($ba->jumlah == 0){
                        echo "";
                    }else{
                       echo "<span class='badge bg-warning text-dark'>".$ba->jumlah."</span>";
                    }
                }?></a> 
            </li>
            <li class="<?php if($this->uri->segment(2) == 'notulen'){ echo 'active'; } ?>"> 
                <a href="
                    <?php 
                        if($this->uri->segment(1) == 'suratkeluar'){ 
                            echo site_url('suratkeluar/notulen'); 
                        }
                    ?>
                "><span class="fa fa-envelope-o"></span> <span class="xn-text">Notulen</span>
                <?php $jabatanid=$this->session->userdata('jabatan_id');
                $notulen=$this->db->query("SELECT count(a.nama_surat) as jumlah FROM draft a WHERE a.nama_surat='Surat Notulen' and a.dibuat_id='$jabatanid'")->result();
                foreach($notulen as $key => $nt){
                    if($nt->jumlah == 0){
                        echo "";
                    }else{
                       echo "<span class='badge bg-warning text-dark'>".$nt->jumlah."</span>";
                    }
                }?></a> 
            </li>
            <li class="<?php if($this->uri->segment(2) == 'memo'){ echo 'active'; } ?>"> 
                <a href="
                    <?php 
                        if($this->uri->segment(1) == 'suratkeluar'){ 
                            echo site_url('suratkeluar/memo'); 
                        }
                    ?>
                "><span class="fa fa-envelope-o"></span> <span class="xn-text">Memo</span>
                <?php $jabatanid=$this->session->userdata('jabatan_id');
                $memo=$this->db->query("SELECT count(a.nama_surat) as jumlah FROM draft a WHERE a.nama_surat='Surat Memo' and a.dibuat_id='$jabatanid'")->result();
                foreach($memo as $key => $mm){
                    if($mm->jumlah == 0){
                        echo "";
                    }else{
                       echo "<span class='badge bg-warning text-dark'>".$mm->jumlah."</span>";
                    }
                }?></a> 
            </li>
        
        <?php }elseif($this->uri->segment(2) == 'draft'){ ?>

            <li class="xn-title">Naskah Dinas Surat</li>
            <li class="<?php if($this->uri->segment(2) == 'draft'){ echo 'active'; } ?>"> 
                <a href="<?php echo site_url('suratkeluar/draft'); ?>"><span class="fa fa-envelope-o"></span> <span class="xn-text">Draft Surat</span></a> 
            </li>

        <?php }elseif($this->uri->segment(1) == 'suratmasuk'){ ?>
                
            <li class="xn-title">Naskah Dinas Surat</li>
            <li class="<?php if($this->uri->segment(2) == 'inbox' AND $this->uri->segment(3) == ''){ echo 'active'; } ?>"> 
                <a href="<?php echo site_url('suratmasuk/inbox'); ?>"><span class="fa fa-envelope-o"></span> <span class="xn-text">Disposisi Surat</span></a> 
            </li>
            <?php if ($this->session->userdata('level') == 4) { ?>
                <li class="<?php if($this->uri->segment(2) == 'surat'){ echo 'active'; } ?>"> 
                    <a href="<?php echo site_url('suratmasuk/surat'); ?>"><span class="fa fa-envelope-o"></span> <span class="xn-text">Surat Masuk</span></a> 
                </li>
            <?php }else{ ?>
            <li class="<?php if($this->uri->segment(3) == 'sudahdisposisi'){ echo 'active'; } ?>"> 
                <a href="<?php echo site_url('suratmasuk/inbox/sudahdisposisi'); ?>"><span class="fa fa-envelope-o"></span> <span class="xn-text">Riwayat Disposisi</span></a> 
            </li>
            <?php }?>
            <li class="<?php if($this->uri->segment(3) == 'selesai'){ echo 'active'; } ?>"> 
                <a href="<?php echo site_url('suratmasuk/inbox/selesai'); ?>"><span class="fa fa-envelope-o"></span> <span class="xn-text">Surat Selesai</span></a> 
            </li>

        <?php } ?>

        <!-- END MENU SURAT-->

        <!-- START MENU PENGARSIPAN-->
        <?php if ($this->uri->segment(1) == 'pengarsipan') { ?>
        <li class="xn-title">Pengarsipan Surat</li>
        <li class="<?php if($this->uri->segment(2) == 'suratkeluar'){ echo 'active'; } ?>"> 
            <a href="<?php echo site_url('pengarsipan/suratkeluar') ?>"><span class="fa fa-archive"></span> <span class="xn-text">Surat Keluar</span></a> 
        </li>
        <li class="<?php if($this->uri->segment(2) == 'suratmasuk'){ echo 'active'; } ?>"> 
            <a href="<?php echo site_url('pengarsipan/suratmasuk') ?>"><span class="fa fa-archive"></span> <span class="xn-text">Surat Masuk</span></a> 
        </li>
        <?php } ?>
        <!-- END MENU PENGARSIPAN-->

        <!-- START MENU ADMINISTRATOR-->
        <?php 
        if ($this->session->userdata('level') == 1) { 
            if ($this->uri->segment(1) == 'master' OR $this->uri->segment(1) == 'home') { 
        ?>
        
        <li class="xn-title">Data Master</li>
        
        <li class="<?php if($this->uri->segment(2) == 'perangkatdaerah'){ echo 'active'; } ?>"> 
            <a href="<?php echo site_url('master/perangkatdaerah') ?>"><span class="fa fa-list-alt"></span> <span class="xn-text">Perangkat Daerah</span></a> 
        </li>
        <li class="<?php if($this->uri->segment(2) == 'jabatan'){ echo 'active'; } ?>"> 
            <a href="<?php echo site_url('master/jabatan') ?>"><span class="fa fa-bookmark"></span> <span class="xn-text">Jabatan</span></a> 
        </li>
        <li class="<?php if($this->uri->segment(2) == 'aparatur'){ echo 'active'; } ?>"> 
            <a href="<?php echo site_url('master/aparatur') ?>"><span class="fa fa-user"></span> <span class="xn-text">Aparatur</span></a> 
        </li>
        <li class="<?php if($this->uri->segment(2) == 'users'){ echo 'active'; } ?>"> 
            <a href="<?php echo site_url('master/users') ?>"><span class="fa fa-users"></span> <span class="xn-text">Users</span></a> 
        </li>
        <li class="<?php if($this->uri->segment(2) == 'kodesurat'){ echo 'active'; } ?>"> 
            <a href="<?php echo site_url('master/kodesurat') ?>"><span class="fa fa-code-fork"></span> <span class="xn-text">Kode Surat</span></a> 
        </li>
        <li class="<?php if($this->uri->segment(2) == 'level'){ echo 'active'; } ?>"> 
            <a href="<?php echo site_url('master/level') ?>"><span class="fa fa-chain"></span> <span class="xn-text">Level</span></a> 
        </li>
        <li class="<?php if($this->uri->segment(2) == 'informasi'){ echo 'active'; } ?>"> 
            <a href="<?php echo site_url('master/informasi') ?>"><span class="fa fa-info"></span> <span class="xn-text">Informasi</span></a> 
        </li>

        <?php 
            } 
        }elseif ($this->session->userdata('level') == 2) { 
            if ($this->uri->segment(1) == 'master' OR $this->uri->segment(1) == 'home') { 
        ?>
        
        <li class="xn-title">Data Master</li>

        <li class="<?php if($this->uri->segment(2) == 'jabatan'){ echo 'active'; } ?>"> 
            <a href="<?php echo site_url('master/jabatan') ?>"><span class="fa fa-bookmark"></span> <span class="xn-text">Jabatan</span></a> 
        </li>
        <li class="<?php if($this->uri->segment(2) == 'aparatur'){ echo 'active'; } ?>"> 
            <a href="<?php echo site_url('master/aparatur') ?>"><span class="fa fa-user"></span> <span class="xn-text">Aparatur</span></a> 
        </li>
        <li class="<?php if($this->uri->segment(2) == 'users'){ echo 'active'; } ?>"> 
            <a href="<?php echo site_url('master/users') ?>"><span class="fa fa-users"></span> <span class="xn-text">Users</span></a> 
        </li>
        <li class="<?php if($this->uri->segment(2) == 'eksternal'){ echo 'active'; } ?>"> 
            <a href="<?php echo site_url('master/eksternal') ?>"><span class="fa fa-external-link-square"></span> <span class="xn-text">Perangkat Eksternal</span></a> 
        </li>

        <?php 
            }
        }
        ?>

        <!-- END MENU ADMINISTRATOR-->

        <?php if ($this->uri->segment(2) == 'informasi' AND $this->uri->segment(1) != 'master') { ?>
        
        <li class="xn-title">Informasi</li>
        <li class="active"> 
            <a href="<?php echo site_url('home/informasi') ?>"><span class="fa fa-info"></span> <span class="xn-text">Informasi</span></a> 
        </li>

        <?php }elseif ($this->uri->segment(2) == 'statistik') { ?>
        
        <li class="xn-title">Statistik</li>
        <li class="active"> 
            <a href="<?php echo site_url('home/statistik') ?>"><span class="fa fa-bar-chart-o"></span> <span class="xn-text">Statistik</span></a> 
        </li>
        
        <?php }elseif ($this->uri->segment(2) == 'profil') { ?>
        
        <li class="xn-title">Profil</li>
        <li class="active"> 
            <a href="<?php echo site_url('home/profil') ?>"><span class="fa fa-user"></span> <span class="xn-text">Profil</span></a> 
        </li>
        
        <?php }elseif ($this->uri->segment(2) == 'disposisi') { ?>
        
        <li class="xn-title">Disposisi</li>
        <li class="active"> 
            <a href="<?php echo site_url('home/disposisi') ?>"><span class="fa fa-envelope-o"></span> <span class="xn-text">Disposisi</span></a> 
        </li>
        
        <?php } ?>

    </ul>
    <!-- END X-NAVIGATION -->

</div>
<!-- END PAGE SIDEBAR -->