<!-- START BREADCRUMB -->
<ul class="breadcrumb">
        <li><a href="<?php echo site_url('home/dashboard') ?>">Beranda</a></li>                    
    <li><a href="javascript:void(0)">Naskah Dinas Surat</a></li>
    <li class="active">Disposisi Surat</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-envelope"></span> Disposisi Surat </h2>
</div>
<!-- END PAGE TITLE -->                

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">                

    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-primary" role="alert">
              <h4><i class="fa fa-info-circle"></i> <b>Keterangan :</b> Untuk melihat surat | lembar disposisi | dan aksi lainnya bisa klik tombol aksi lalu pilih tombol detail data surat</h4>
            </div>
            <div class="alert alert-warning" role="alert">
              <h4><i class="fa fa-info-circle"></i> <b>Keterangan :</b> Untuk melihat alur penerusan surat pilih klik tombol aksi -> klik tombol detail data surat</h4>
            </div>
            <!-- START DEFAULT DATATABLE -->
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <form action="<?= base_url('suratmasuk/inbox')?>" method="get">
                            <div class="col-lg-9">
                            </div>
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
                                <th>DARI</th>
                                <th>TANGGAL</th>
                                <th>HAL</th>
                                <th>STATUS SURAT</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                                $no = 1;
                                foreach ($inbox as $key1 => $h) {
                            ?>
                            <tr>
                                <td><?php echo ++$start; ?></td>
                                <td><?php echo $h->dari; ?></td>
                                <td><?php echo tanggal($h->tanggal) ?></td>
                                <td><?php echo $h->hal; ?></td>
                                <td>
                                    <?php 
                                        $cekSelesai = $this->db->order_by('dsuratmasuk_id', 'DESC')->get_where('disposisi_suratmasuk', array('suratmasuk_id' => $h->suratmasuk_id, 'status' => 'Selesai'))->num_rows();

                                        $qdisposisi = $this->db->limit(1)->order_by('dsuratmasuk_id', 'DESC')->get_where('disposisi_suratmasuk', array('suratmasuk_id' => $h->suratmasuk_id));

                                        if ($cekSelesai == 0) {

                                            if ($qdisposisi->num_rows() == 0) {
                                    ?>

                                                <p style='color:red; text-align:center;'><b>SURAT BELUM DIDISPOSISIKAN</b></p>

                                        <?php }else{ ?>
                                          <p style='color:orange; text-align:center;'><b>SURAT SUDAH DITERUSKAN</b></p>
                                    <?php 
                                            }
                                        }else{
                                    ?>
                                            <p style='color:green; text-align:center;'><b>SURAT SUDAH DISELESAIKAN</b></p>

                                    <?php } ?>

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
                                    <!-- Detail Data --><a href="<?php echo site_url('suratmasuk/inbox/detaildata/'.$h->suratmasuk_id) ?>" title="Detail Data"><span class="fa-stack"><i class="fa fa-info-circle fa-stack-2x"></i></span></a>
                                    <?php }else{ ?>
                                        <a href="<?php echo site_url('suratmasuk/inbox/detaildata/'.$h->suratmasuk_id) ?>" class="btn btn-warning"><i class="fa fa-info-circle"></i> Detail Data Surat</a>
                                    <?php } ?>
                                </td>
                                
                            </tr>

                            <?php
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








<!-- $result = array();
$group = $result[$h->suratmasuk_id][] = $h;
foreach ($group as $key => $pp) {
    echo $pp;
}
    // echo "<pre>";
    // var_dump($pp);
    // echo "</pre>"; -->