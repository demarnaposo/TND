<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo site_url('home/dashboard') ?>">Beranda</a></li>                    
    <li><a href="javascript:void(0)">Naskah Dinas Surat</a></li>
    <li class="active">Surat Masuk</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <!-- <h2><span class="fa fa-envelope"></span> Surat Masuk </h2> -->
    <h2><img src="<?= site_url('assets/img/icons/icon-surat-masuk.png')?>" width="30px"> Surat Masuk </h2>
</div>
<!-- END PAGE TITLE -->                

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">                

    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-warning" role="alert">
              <h4><b>Keterangan :</b> Untuk melihat surat/lampiran | lembar disposisi | riwayat terusan | aksi lainnya bisa klik tombol aksi <i class="fa fa-info-circle"></i> untuk melihat detail data</h4>
            </div>

            <!-- START DEFAULT DATATABLE -->
            <div class="panel panel-default">
                <div class="panel-body">
                <div class="row">
                    <div class="col-lg-9">
                        <a href="<?php echo site_url('suratmasuk/surat/add') ?>" class="btn btn-primary"><i class="fa fa-plus"></i>Tambah Surat</a>
                    </div>
                    <form method="get" action="<?php echo base_url('suratmasuk/surat')?>">
                    <div class="col-lg-3">
                    <div class="input-group mb-3">
                      <input type="text" class="form-control" placeholder="Cari Surat" aria-label="Cari Surat" name="cari" aria-describedby="basic-addon2">
                      <div class="input-group-append">
                        <input type="submit" name="submit" value="Cari" class="btn btn-primary">
                        <!--<button class="btn btn-primary" name="submit" type="submit">Cari</button>-->
                      </div>
                    </div>
                   </div>
                    </form>
                </div><br>
		    <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>PENERIMA</th>
                                <th>DARI</th>
                                <th>NOMOR SURAT</th>
                                <th>HAL</th>
                                <th>TANGGAL</th>
                                <th>STATUS SURAT</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if($suratmasuk == NULL){?>
                            <tr>
                                <td colspan="8" align="center">Tidak Ada Data</td>
                            </tr>
                            <?php
                            }else{
                                foreach ($suratmasuk as $key => $h) {
                            ?>
                            <tr>
                                <td><?php echo ++$start; ?></td>
                                <td><b><?php echo $h->penerima; ?></b></td>
                                <td><?php echo $h->dari; ?></td>
                                <td><?php echo $h->nomor; ?></td>
                                <td><?php echo $h->hal; ?></td>
                                <td><b>Tanggal Surat :</b> <?php echo tanggal($h->tanggal) ?><br><b>Tanggal Diterima :</b> <?php echo tanggal($h->diterima) ?></td>
                                <td>
                                    <?php 
                                        $cekSelesai = $this->db->order_by('dsuratmasuk_id', 'DESC')->get_where('disposisi_suratmasuk', array('suratmasuk_id' => $h->suratmasuk_id, 'status' => 'Selesai'))->num_rows();
                                        // $cekselesai = $this->db->query("SELECT COUNT(dsuratmasuk_id) FROM disposisi_suratmasuk WHERE suratmasuk_id='$h->suratmasuk_id' AND status='Belum Selesai'")->num_rows();
                                        $dikembalikan = $this->db->order_by('dsuratmasuk_id', 'DESC')->get_where('disposisi_suratmasuk', array('suratmasuk_id' => $h->suratmasuk_id, 'status' => 'Dikembalikan'))->num_rows();
                                        // $dikembalikan = $this->db->limit(1)->order_by('dsuratmasuk_id', 'DESC')->get_where('disposisi_suratmasuk', array('suratmasuk_id' => $h->suratmasuk_id,'status'=> 'Dikembalikan'));
                                        $qdisposisi = $this->db->limit(1)->order_by('dsuratmasuk_id', 'DESC')->get_where('disposisi_suratmasuk', array('suratmasuk_id' => $h->suratmasuk_id));
                                        
                                        if ($cekSelesai == 0) {
                                            if ($dikembalikan == 0) {
                                                if ($qdisposisi->num_rows() == 0) {
                                    ?>

                                                <font style='color:red; text-align:center;'> <b>SURAT BELUM DITERUSKAN</b></font>

                                        <?php }else{ ?>
                                            <font style='color:orange; text-align:center;'> <b>SURAT SUDAH DITERUSKAN</b></font>
                                        <?php 
                                        }
                                        }else{
                                    ?>
                                        <font style='text-align:center;'><font color="red"><b>SURAT DIKEMBALIKAN</b></font><br><a href="javascript:void()" data-toggle="modal" data-target="#modalKet<?php echo $h->suratmasuk_id ?>" title="Lihat Keterangan" >Klik disini</a> untuk lihat keterangan</font>
                                         <!-- Modal Disposisi -->
                                    <div class="modal fade" id="modalKet<?php echo $h->suratmasuk_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                      <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel"><center>Keterangan Surat</center></h5>
                                          </div>
                                          <div class="modal-body">
                                            <?php
                                                $qketdis = $this->db->query("
                                                    SELECT * FROM disposisi_suratmasuk
                                                    JOIN aparatur ON disposisi_suratmasuk.users_id = aparatur.jabatan_id
                                                    JOIN users ON users.aparatur_id = aparatur.aparatur_id
                                                    WHERE disposisi_suratmasuk.suratmasuk_id = '$h->suratmasuk_id' AND disposisi_suratmasuk.status != 'Riwayat' AND users.level_id != 4 GROUP BY disposisi_suratmasuk.users_id ORDER BY dsuratmasuk_id ASC
                                                ")->result();
                                                $nmr = 1;
                                                foreach ($qketdis as $key => $kd) {
                                            ?>

                                            <?php echo $nmr; ?>. Dari : <?php echo $kd->nama; ?> <br>
                                            Keterangan : <?php echo $kd->keterangan; ?> <br>Didisposisikan Tanggal : <?= tanggal($kd->tanggal);?><br>

                                            <?php $nmr++;  } ?>
                                          </div>
                                          <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <!-- End Modal Disposisi -->
                                    <?php }
                                    }else{ ?>
                                        <font style='color:green; text-align:center;'><b>SURAT SUDAH DISELESAIKAN</b></font>
                                    <?php }?>
                                </td>
                                <td>
                                    <!-- Example single danger button -->
                                    <?php if ($cekSelesai == 0) { ?>
                                    <!-- <div class="btn-group">
                                      <button type="button" class="btn btn-primary dropdown-toggle" style="width:140px;" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa fa-cog"></i> Aksi
                                      </button>
                                      <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="<?php echo site_url('suratmasuk/surat/edit/'.$h->suratmasuk_id) ?>"><i class="fa fa-pencil"></i> Ubah</a></li>
                                            <li><a class="dropdown-item" href="<?php echo site_url('suratmasuk/surat/delete/'.$h->suratmasuk_id) ?>" onclick="return confirm('Apakah anda yakin akan menghapus?')"><i class="fa fa-trash-o"></i> Hapus</a></li>
                                        <li><a class="dropdown-item" href="<?php echo site_url('suratmasuk/surat/detaildata/'.$h->suratmasuk_id) ?>"><i class="fa fa-info-circle"></i> Detail Data Surat</a></li>
                                      </ul>
                                    </div> -->
                                    <!-- Ubah Data --><a href="<?php echo site_url('suratmasuk/surat/edit/'.$h->suratmasuk_id) ?>" title="Ubah Data"><span class="fa-stack"><i class="fa fa-pencil fa-stack-2x"></i></span></a> |
                                    <!-- Hapus Data --><a href="<?php echo site_url('suratmasuk/surat/delete/'.$h->suratmasuk_id) ?>" onclick="return confirm('Apakah anda yakin akan menghapus?')" title="Hapus Data"><span class="fa-stack"><i class="fa fa-trash-o fa-stack-2x"></i></span></a> |
                                    <!-- Detail Data --><a href="<?php echo site_url('suratmasuk/surat/detaildata/'.$h->suratmasuk_id) ?>" title="Detail Data"><span class="fa-stack"><i class="fa fa-info-circle fa-stack-2x"></i></span></a>
                                    <?php }else{ ?>
                                        <a href="<?php echo site_url('suratmasuk/surat/detaildata/'.$h->suratmasuk_id) ?>" class="btn btn-warning"><i class="fa fa-info-circle"></i> Detail Data Surat</a>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php
                                // ++$start; 
                                } 
                            }
                            ?>
                        </tbody>
                    </table>
		    </div>
                    <!-- Pagination Boostrap -->
                    <?php
                        $resultcari=$this->input->get('cari');
                        if(!empty($resultcari)){
                    ?>
                    <div class="alert alert-danger" role="alert">
                        <h4><b><i class="fa fa-info-circle"></i> Apakah data yang anda cari tidak ada ?</b> Coba ketik dikolom pencarian dengan rinci dan detail seperti (Nomor Surat/Perihal Surat)</h4>
                    </div>
                    <?php
                        }else{
                            echo $this->pagination->create_links();
                        }
                    ?>
                    <!-- Pagination Boostrap -->
                </div>
            </div>
            <!-- END DEFAULT DATATABLE -->
        </div>
    </div>
</div>
<script>
    function yesnoCheck() {
    if (document.getElementById('noCheck').checked) {
        document.getElementById('ifNo').style.display = 'block';
    }else document.getElementById('ifNo').style.display = 'none';
    if (document.getElementById('yesCheck').checked) {
        document.getElementById('ifYes').style.display = 'block';
    }else document.getElementById('ifYes').style.display = 'none';
    if (document.getElementById('2Check').checked) {
        document.getElementById('if2').style.display = 'block';
        document.getElementById('if2').style.display = 'block';
    }else document.getElementById('if2').style.display = 'none';

}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
