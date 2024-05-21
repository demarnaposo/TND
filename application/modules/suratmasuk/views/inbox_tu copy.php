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

            <!-- START DEFAULT DATATABLE -->                                
            <div class="panel panel-default">           
                <div class="panel-body">

                    <table class="table datatable table-responsive table-bordered">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>JENIS SURAT</th>
                                <th>TANGGAL</th>
                                <th>STATUS SURAT</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                                $no = 1; 
                                foreach ($inbox as $key => $h) {
                            ?>
                            <tr>
                                <td><?php echo $no; ?></td>
                                <td><?php echo $h->nama_surat; ?></td>
                                <td><?php echo tanggal($h->tanggal) ?></td>
                                
                                <td>
                                    <?php
                                        $cekDisposisi = $this->db->query("SELECT * FROM surat_masuk WHERE lampiran LIKE '%$h->surat_id%'")->num_rows();
                                        if ($cekDisposisi > 0) {
                                            echo "<p style='color:green; text-align:center;'> SURAT SUDAH DIINPUTKAN KE SURAT MASUK </p>";
                                        }else{
                                            echo "<p style='color:red; text-align:center;'> SURAT BELUM DIINPUTKAN KE SURAT MASUK </p>";
                                        }
                                    ?>
                                </td>

                                <td align="center">

                                    <?php lihatsurat($h->surat_id) ?>
                                    
                                    <?php if ($cekDisposisi == 0) { ?>
                                        | <a href="<?php echo site_url('suratmasuk/surat/add/'.$h->surat_id) ?>" title="Disposisi Surat"><i class="fa fa-mail-forward"></i></a>
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