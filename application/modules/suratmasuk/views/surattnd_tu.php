<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo site_url('home/dashboard') ?>">Halaman Utama</a></li>                    
    <li><a href="<?php echo site_url('home/dashboard') ?>">Beranda</a></li>
    <li><a href="<?php echo site_url('home/dashboard/dashboardmasuk') ?>">Surat Masuk</a></li>
    <li><a href="active">Surat TND</a></li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-envelope"></span> Surat TND</h2>
</div>
<!-- END PAGE TITLE -->                

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">                

    <div class="row">
        <div class="col-md-12">
            
            <!-- START DEFAULT DATATABLE -->                                
            <div class="panel panel-default">           
                <div class="panel-body">

                    <table class="table datatable table-responsive table-bordered">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>JENIS SURAT</th>
                                <th>PERANGKAT DAERAH</th>
                                <th>NOMOR SURAT</th>
                                <th>HAL</th>
                                <th>TANGGAL SURAT</th>
                                <th>STATUS SURAT</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                                $no = 1; 
                                foreach ($surattnd as $key => $h) {
                            ?>
                            <tr>
                                <td><?php echo $no; ?></td>
                                <td><?php echo $h->nama_surat; ?></td>
                                <?php
                                if(substr($h->surat_id, 0,2) == 'SU'){
                                    echo "<td>".$h->pdundangan."</td>";
                                    echo "<td>".$h->nomorundangan."</td>";
                                    echo "<td>".$h->halundangan."</td>";
                                }elseif(substr($h->surat_id, 0,2) == 'SE'){
                                    echo "<td>".$h->pdedaran."</td>";
                                    echo "<td>".$h->nomoredaran."</td>";
                                    echo "<td>".$h->haledaran."</td>";
                                }elseif(substr($h->surat_id, 0,2) == 'SB'){
                                    echo "<td>".$h->pdbiasa."</td>";
                                    echo "<td>".$h->nomorbiasa."</td>";
                                    echo "<td>".$h->halbiasa."</td>";
                                }elseif(substr($h->surat_id, 0,3) == 'IZN'){
                                    echo "<td>".$h->pdizin."</td>";
                                    echo "<td>".$h->nomorizin."</td>";
                                    echo "<td>".$h->halizin."</td>";
                                }elseif(substr($h->surat_id, 0,3) == 'PGL'){
                                    echo "<td>".$h->pdpanggilan."</td>";
                                    echo "<td>".$h->nomorpanggilan."</td>";
                                    echo "<td>".$h->halpanggilan."</td>";
                                }elseif(substr($h->surat_id, 0,5) == 'NODIN'){
                                    echo "<td>".$h->pdnotadinas."</td>";
                                    echo "<td>".$h->nomornotadinas."</td>";
                                    echo "<td>".$h->halnotadinas."</td>";
                                }elseif(substr($h->surat_id, 0,5) == 'PNGMN'){
                                    echo "<td>".$h->pdpengumuman."</td>";
                                    echo "<td>".$h->nomorpengumuman."</td>";
                                    echo "<td>".$h->halpengumuman."</td>";
                                }elseif(substr($h->surat_id, 0,3) == 'LAP'){
                                    echo "<td>".$h->pdlaporan."</td>";
                                    echo "<td>".$h->nomorlaporan."</td>";
                                    echo "<td>".$h->hallaporan."</td>";
                                }elseif(substr($h->surat_id, 0,3) == 'REK'){
                                    echo "<td>".$h->pdrekomendasi."</td>";
                                    echo "<td>".$h->nomorrekomendasi."</td>";
                                    echo "<td>".$h->halrekomendasi."</td>";
                                }elseif(substr($h->surat_id, 0,3) == 'NTL'){
                                    echo "<td>".$h->pdnotulen."</td>";
                                    echo "<td>".$h->nomornotulen."</td>";
                                    echo "<td>".$h->halnotulen."</td>";
                                }elseif(substr($h->surat_id, 0,3) == 'INT'){
                                    echo "<td>".$h->pdinstruksi."</td>";
                                    echo "<td>".$h->nomorinstruksi."</td>";
                                    echo "<td>".$h->halinstruksi."</td>";
                                }elseif(substr($h->surat_id, 0,3) == 'LMP'){
                                    echo "<td>".$h->pdlampiran."</td>";
                                    echo "<td>".$h->nomorlampiran."</td>";
                                    echo "<td>".$h->hallampiran."</td>";
                                }elseif(substr($h->surat_id, 0,3) == 'SPT'){
                                    echo "<td>".$h->pdperintahtugas."</td>";
                                    echo "<td>".$h->nomorperintahtugas."</td>";
                                    echo "<td></td>";
                                }elseif(substr($h->surat_id, 0,2) == 'SK'){
                                    echo "<td>".$h->pdketerangan."</td>";
                                    echo "<td>".$h->nomorketerangan."</td>";
                                    echo "<td></td>";
                                }elseif(substr($h->surat_id, 0,3) == 'PJL'){
                                    echo "<td>".$h->pdperjalanan."</td>";
                                    echo "<td>".$h->nomorperjalanan."</td>";
                                    echo "<td></td>";
                                }elseif(substr($h->surat_id, 0,3) == 'MKT'){
                                    echo "<td>".$h->pdmelaksanakantugas."</td>";
                                    echo "<td>".$h->nomormelaksanakantugas."</td>";
                                    echo "<td>Pernyataan Melaksanakan Tugas</td>";
                                }elseif(substr($h->surat_id, 0,3) == 'PNG'){
                                    echo "<td>".$h->pdpengantar."</td>";
                                    echo "<td>".$h->nomorpengantar."</td>";
                                    echo "<td></td>";
                                }elseif(substr($h->surat_id, 0,2) == 'SL'){
                                    echo "<td>".$h->pdlainnya."</td>";
                                    echo "<td>".$h->nomorlainnya."</td>";
                                    echo "<td>".$h->hallainnya."</td>";
                                }else{
                                    echo "-";
                                }
                                
                                ?>
                                <td><?php echo tanggal($h->tglsurat) ?></td>
                                
                                <td>
                                    <?php
                                        $opd        = $this->session->userdata('opd_id');
                                        $jabatan    = $this->session->userdata('jabatan_id');
                                        if($opd == 4) {
                                            $cekDisposisi = $this->db->query("SELECT * FROM surat_masuk WHERE lampiran LIKE '%$h->surat_id%' AND dibuat_id = $jabatan")->num_rows();
                                        }
                                        else {
                                            $cekDisposisi = $this->db->query("SELECT * FROM surat_masuk WHERE lampiran LIKE '%$h->surat_id%' AND opd_id = $opd")->num_rows();
                                        }
                                        
                                        if ($cekDisposisi > 0) {
                                            echo "<p style='color:green; text-align:center;'> SURAT SUDAH DIINPUTKAN KE SURAT MASUK </p>";
                                        }else{
                                            echo "<p style='color:red; text-align:center;'> SURAT BELUM DIINPUTKAN KE SURAT MASUK </p>";
                                        }
                                    ?>
                                </td>

                                <td align="center">

                                    <?php lihatsurattte($h->surat_id);
                                    if (substr($h->surat_id, 0,2) == 'SU') { 
                                        $qundangan=$this->db->query("SELECT lampiran_lain FROM surat_undangan WHERE id='$h->surat_id'")->result();
                                        foreach($qundangan as $key => $qu){
                                            if(empty($qu->lampiran_lain)){
                                                echo '';
                                            }else{
                                                echo "| <a href=".site_url('assets/lampiransurat/undangan/'.$qu->lampiran_lain)." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-file-pdf-o fa-stack-2x'></i></span></a>";  
                                            }
                                        }
                                    }elseif(substr($h->surat_id, 0,2) == 'SB'){
                                        $qbiasa=$this->db->query("SELECT lampiran_lain FROM surat_biasa WHERE id='$h->surat_id'")->result();
                                        foreach($qbiasa as $key => $qb){
                                            if(empty($qb->lampiran_lain)){
                                                echo '';
                                            }else{
                                                echo "| <a href=".site_url('assets/lampiransurat/biasa/'.$qb->lampiran_lain)." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-file-pdf-o fa-stack-2x'></i></span></a>";  
                                            }
                                        }
                                    }elseif(substr($h->surat_id, 0,2) == 'SE'){
                                        $qedaran=$this->db->query("SELECT lampiran_lain FROM surat_edaran WHERE id='$h->surat_id'")->result();
                                        foreach($qedaran as $key => $qe){
                                            if(empty($qe->lampiran_lain)){
                                                echo '';
                                            }else{
                                                echo "| <a href=".site_url('assets/lampiransurat/edaran/'.$qe->lampiran_lain)." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-file-pdf-o fa-stack-2x'></i></span></a>";  
                                            }
                                        }
                                    }elseif(substr($h->surat_id, 0,5) == 'PNGMN'){
                                        $qpengumuman=$this->db->query("SELECT lampiran_lain FROM surat_edaran WHERE id='$h->surat_id'")->result();
                                        foreach($qpengumuman as $key => $qpngmn){
                                            if(empty($qpngmn->lampiran_lain)){
                                                echo '';
                                            }else{
                                                echo "| <a href=".site_url('assets/lampiransurat/edaran/'.$qpngmn->lampiran_lain)." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-file-pdf-o fa-stack-2x'></i></span></a>";  
                                            }
                                        }
                                    }elseif(substr($h->surat_id, 0,5) == 'PNGMN'){
                                        $qpengumuman=$this->db->query("SELECT lampiran_lain FROM surat_pengumuman WHERE id='$h->surat_id'")->result();
                                        foreach($qpengumuman as $key => $qpngmn){
                                            if(empty($qpngmn->lampiran_lain)){
                                                echo '';
                                            }else{
                                                echo "| <a href=".site_url('assets/lampiransurat/pengumuman/'.$qpngmn->lampiran_lain)." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-file-pdf-o fa-stack-2x'></i></span></a>";  
                                            }
                                        }
                                    }elseif(substr($h->surat_id, 0,3) == 'LAP'){
                                        $qlaporan=$this->db->query("SELECT lampiran_lain FROM surat_laporan WHERE id='$h->surat_id'")->result();
                                        foreach($qlaporan as $key => $qlap){
                                            if(empty($qlap->lampiran_lain)){
                                                echo '';
                                            }else{
                                                echo "| <a href=".site_url('assets/lampiransurat/pengumuman/'.$qlap->lampiran_lain)." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-file-pdf-o fa-stack-2x'></i></span></a>";  
                                            }
                                        }
                                    }elseif(substr($h->surat_id, 0,3) == 'REK'){
                                        $qrekomendasi=$this->db->query("SELECT lampiran_lain FROM surat_rekomendasi WHERE id='$h->surat_id'")->result();
                                        foreach($qrekomendasi as $key => $qrek){
                                            if(empty($qrek->lampiran_lain)){
                                                echo '';
                                            }else{
                                                echo "| <a href=".site_url('assets/lampiransurat/rekomendasi/'.$qrek->lampiran_lain)." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-file-pdf-o fa-stack-2x'></i></span></a>";  
                                            }
                                        }
                                    }elseif(substr($h->surat_id, 0,3) == 'INT'){
                                        $qinstruksi=$this->db->query("SELECT lampiran_lain FROM surat_instruksi WHERE id='$h->surat_id'")->result();
                                        foreach($qinstruksi as $key => $qint){
                                            if(empty($qint->lampiran_lain)){
                                                echo '';
                                            }else{
                                                echo "| <a href=".site_url('assets/lampiransurat/instruksi/'.$qrek->lampiran_lain)." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-file-pdf-o fa-stack-2x'></i></span></a>";  
                                            }
                                        }
                                    }elseif(substr($h->surat_id, 0,3) == 'PNG'){
                                        $qpengantar=$this->db->query("SELECT lampiran_lain FROM surat_pengantar WHERE id='$h->surat_id'")->result();
                                        foreach($qpengantar as $key => $qpng){
                                            if(empty($qpng->lampiran_lain)){
                                                echo '';
                                            }else{
                                                echo "| <a href=".site_url('assets/lampiransurat/pengantar/'.$qpng->lampiran_lain)." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-file-pdf-o fa-stack-2x'></i></span></a>";  
                                            }
                                        }
                                    }elseif(substr($h->surat_id, 0,5) == 'NODIN'){
                                        $qnotadinas=$this->db->query("SELECT lampiran_lain FROM surat_notadinas WHERE id='$h->surat_id'")->result();
                                        foreach($qnotadinas as $key => $qnodin){
                                            if(empty($qnodin->lampiran_lain)){
                                                echo '';
                                            }else{
                                                echo "| <a href=".site_url('assets/lampiransurat/notadinas/'.$qnodin->lampiran_lain)." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-file-pdf-o fa-stack-2x'></i></span></a>";  
                                            }
                                        }
                                    }elseif(substr($h->surat_id, 0,3) == 'SKP'){
                                        $qsuratkeputusan=$this->db->query("SELECT lampiran_lain FROM surat_keputusan WHERE id='$h->surat_id'")->result();
                                        foreach($qsuratkeputusan as $key => $qskp){
                                            if(empty($qskp->lampiran_lain)){
                                                echo '';
                                            }else{
                                                echo "| <a href=".site_url('assets/lampiransurat/suratkeputusan/'.$qskp->lampiran_lain)." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-file-pdf-o fa-stack-2x'></i></span></a>";  
                                            }
                                        }
                                    }elseif(substr($h->surat_id, 0,2) == 'SK'){
                                        $qketerangan=$this->db->query("SELECT lampiran_lain FROM surat_keterangan WHERE id='$h->surat_id'")->result();
                                        foreach($qketerangan as $key => $qsk){
                                            if(empty($qsk->lampiran_lain)){
                                                echo '';
                                            }else{
                                                echo "| <a href=".site_url('assets/lampiransurat/keterangan/'.$qsk->lampiran_lain)." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-file-pdf-o fa-stack-2x'></i></span></a>";  
                                            }
                                        }
                                    }elseif(substr($h->surat_id, 0,3) == 'SPT'){
                                        $qperintahtugas=$this->db->query("SELECT lampiran_lain FROM surat_perintahtugas WHERE id='$h->surat_id'")->result();
                                        foreach($qperintahtugas as $key => $qspt){
                                            if(empty($qspt->lampiran_lain)){
                                                echo '';
                                            }else{
                                                echo "| <a href=".site_url('assets/lampiransurat/perintahtugas/'.$qspt->lampiran_lain)." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-file-pdf-o fa-stack-2x'></i></span></a>";  
                                            }
                                        }
                                    }elseif(substr($h->surat_id, 0,2) == 'SP'){
                                        $qperintah=$this->db->query("SELECT lampiran_lain FROM surat_perintah WHERE id='$h->surat_id'")->result();
                                        foreach($qperintah as $key => $qsp){
                                            if(empty($qsp->lampiran_lain)){
                                                echo '';
                                            }else{
                                                echo "| <a href=".site_url('assets/lampiransurat/perintah/'.$qsp->lampiran_lain)." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-file-pdf-o fa-stack-2x'></i></span></a>";  
                                            }
                                        }
                                    }elseif(substr($h->surat_id, 0,3) == 'IZN'){
                                        $qizin=$this->db->query("SELECT lampiran_lain FROM surat_izin WHERE id='$h->surat_id'")->result();
                                        foreach($qizin as $key => $qizn){
                                            if(empty($qizn->lampiran_lain)){
                                                echo '';
                                            }else{
                                                echo "| <a href=".site_url('assets/lampiransurat/izin/'.$qizn->lampiran_lain)." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-file-pdf-o fa-stack-2x'></i></span></a>";  
                                            }
                                        }
                                    }elseif(substr($h->surat_id, 0,3) == 'KSA'){
                                        $qkuasa=$this->db->query("SELECT lampiran_lain FROM surat_kuasa WHERE id='$h->surat_id'")->result();
                                        foreach($qkuasa as $key => $qksa){
                                            if(empty($qlsa->lampiran_lain)){
                                                echo '';
                                            }else{
                                                echo "| <a href=".site_url('assets/lampiransurat/kuasa/'.$qksa->lampiran_lain)." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-file-pdf-o fa-stack-2x'></i></span></a>";  
                                            }
                                        }
                                    }elseif(substr($h->surat_id, 0,3) == 'MKT'){
                                        $qmelaksanakantugas=$this->db->query("SELECT lampiran_lain FROM surat_melaksanakantugas WHERE id='$h->surat_id'")->result();
                                        foreach($qmelaksanakantugas as $key => $qmkt){
                                            if(empty($qmkt->lampiran_lain)){
                                                echo '';
                                            }else{
                                                echo "| <a href=".site_url('assets/lampiransurat/melaksanakantugas/'.$qmkt->lampiran_lain)." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-file-pdf-o fa-stack-2x'></i></span></a>";  
                                            }
                                        }
                                    }elseif(substr($h->surat_id, 0,3) == 'PGL'){
                                        $qpanggilan=$this->db->query("SELECT lampiran_lain FROM surat_panggilan WHERE id='$h->surat_id'")->result();
                                        foreach($qpanggilan as $key => $qpgl){
                                            if(empty($qpgl->lampiran_lain)){
                                                echo '';
                                            }else{
                                                echo "| <a href=".site_url('assets/lampiransurat/panggilan/'.$qpgl->lampiran_lain)." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-file-pdf-o fa-stack-2x'></i></span></a>";  
                                            }
                                        }
                                    }elseif(substr($h->surat_id, 0,3) == 'NTL'){
                                        $qnotulen=$this->db->query("SELECT lampiran_lain FROM surat_notulen WHERE id='$h->surat_id'")->result();
                                        foreach($qnotulen as $key => $qntl){
                                            if(empty($qntl->lampiran_lain)){
                                                echo '';
                                            }else{
                                                echo "| <a href=".site_url('assets/lampiransurat/notulen/'.$qntl->lampiran_lain)." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-file-pdf-o fa-stack-2x'></i></span></a>";  
                                            }
                                        }
                                    }elseif(substr($h->surat_id, 0,3) == 'MMO'){
                                        $qmemo=$this->db->query("SELECT lampiran_lain FROM surat_memo WHERE id='$h->surat_id'")->result();
                                        foreach($qmemo as $key => $qmmo){
                                            if(empty($qmmo->lampiran_lain)){
                                                echo '';
                                            }else{
                                                echo "| <a href=".site_url('assets/lampiransurat/memo/'.$qmmo->lampiran_lain)." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-file-pdf-o fa-stack-2x'></i></span></a>";  
                                            }
                                        }
                                    }elseif(substr($h->surat_id, 0,3) == 'LMP'){
                                        $qlmp=$this->db->query("SELECT lampiran_lain FROM surat_lampiran WHERE id='$h->surat_id'")->result();
                                        foreach($qlmp as $key => $lmp){
                                            if(empty($lmp->lampiran_lain)){
                                                echo '';
                                            }else{
                                                echo "<a href=".site_url('export/lampiran/'.$h->surat_id)." title='Lihat Surat' target='_blank'><span class='fa-stack'><i class='fa fa-file-pdf-o fa-stack-2x'></i></span></a> |";  
                                            }
                                        }
                                    }
                                    ?>
                                    
                                    <?php if ($cekDisposisi == 0) { ?>
                                        | <a href="<?php echo site_url('suratmasuk/surat/add/'.$h->surat_id) ?>" title="Disposisi Surat"><span class="fa-stack"><i class="fa fa-mail-forward fa-stack-2x"></i></span></a>
                                    <?php } ?>

                                </td>

                            </tr>

                            <?php
                                $no++; 
                                } 
                            ?>
                        </tbody>

                    </table>

                </div>
            </div>
            <!-- END TABS -->

        </div>
    </div>
</div>