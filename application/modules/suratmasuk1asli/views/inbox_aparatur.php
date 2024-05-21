<!-- START BREADCRUMB -->
<ul class="breadcrumb">
        <li><a href="<?php echo site_url('home/dashboard') ?>">Beranda</a></li>                    
    <li><a href="javascript:void(0)">Naskah Dinas Surat</a></li>
    <li class="active">Terusan Surat</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-envelope"></span> Terusan Surat </h2>
</div>
<!-- END PAGE TITLE -->                

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">                
   
        <div class="col-md-12">
            <div class="alert alert-primary" role="alert">
              <h4><i class="fa fa-info-circle"></i> <b>Keterangan :</b> Untuk melihat surat | lembar disposisi | dan aksi lainnya bisa klik tombol detail data surat</h4>
            </div>
            <!-- START DEFAULT DATATABLE -->
            <div class="panel panel-default">
                <div class="panel-body">
                <div class="row">
                    <div class="col-lg-9">
                    </div>
                    <?php echo form_open_multipart('suratmasuk1/inbox')?>
                    <div class="col-lg-3">
                    <div class="input-group mb-3">
                      <input type="text" class="form-control" placeholder="Cari Surat" aria-label="Cari Surat" name="cari" aria-describedby="basic-addon2">
                      <div class="input-group-append">
                        <input type="submit" name="submit" value="Cari" class="btn btn-primary">
                        <!--<button class="btn btn-primary" name="submit" type="submit">Cari</button>-->
                      </div>
                    </div>
                   </div>
                    <?php echo form_close();?>
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
                                <th>AKSI</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                                if($inbox == NULL){
                            ?>
                            <tr>
                            <?= $this->input->post('cari');?>
                                <td colspan="8" align="center">Tidak Ada Data</td>
                            </tr>
                            <?php
                            }else{
                                foreach ($inbox as $key3 => $h) {
                            ?>
                            <tr>
                                <td><?php echo ++$start; ?></td>
                                <td><b><?php echo $h->penerima; ?></b></td>
                                <td><?php echo $h->dari; ?></td>
                                <td><?php echo $h->nomor; ?></td>
                                <td><?php echo $h->hal; ?></td>
                                <td><b>Tanggal Surat :</b> <?php echo tanggal($h->tglsurat) ?><br><b>Tanggal Diterima :</b> <?php echo tanggal($h->diterima) ?></td>
                                <td>
                                    <a href="<?php echo site_url('suratmasuk1/inbox/detaildata/'.$h->suratmasuk_id) ?>" class="btn btn-warning"><i class="fa fa-info-circle"></i> Detail Data Surat</a>
                                </td>
                            </tr>

                            <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
		    </div>
                    <?php
                      echo $this->pagination->create_links();
                    ?>
                </div>
            </div>
            <!-- END DEFAULT DATATABLE -->

        </div>
    </div>
</div>
