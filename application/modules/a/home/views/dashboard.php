<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="#">Home</a></li>                    
    <li class="active">Dashboard</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">

    <!-- START WIDGETS -->                    
    <div class="row">

        <?php if ($this->session->userdata('level') == 4) { ?>

            <div class="col-md-4">
                <!-- START WIDGET SURAT MASUK -->
                <a href="<?php echo site_url('suratmasuk/inbox') ?>">
                <div class="widget widget-default widget-item-icon">
                    <div class="widget-item-left">
                        <span class="fa fa-envelope"></span>
                    </div>                             
                    <div class="widget-data">
                        <div class="widget-int num-count">
                            <?php
                                //query untuk menghitung jumlah surat yg belum diinputkan ke surat masuk
                                // $jumInput = array();
                                // foreach ($suratmasuk as $key => $h) {
                                //     $qInput = $this->db->query("SELECT COUNT(*) AS jumlah FROM surat_masuk WHERE lampiran NOT LIKE '%$h->surat_id%'")->row_array()['jumlah'];
                                //     if ($qInput == 0) {
                                //         $jumInput[] = $this->db->query("SELECT COUNT(*) AS jumlah FROM surat_masuk WHERE lampiran NOT LIKE '%$h->surat_id%'")->row_array()['jumlah'];
                                //     }

                                // }

                                $jumDis = array();
                                foreach ($didisposisikan as $key => $d) {
                                    $qDis = $this->db->limit(1)->order_by('dsuratmasuk_id', 'DESC')->get_where('disposisi_suratmasuk', array('suratmasuk_id != ' => $d->suratmasuk_id))->num_rows();
                                    if ($qDis == 0) {
                                        $jumDis[] = $this->db->limit(1)->order_by('dsuratmasuk_id', 'DESC')->get_where('disposisi_suratmasuk', array('suratmasuk_id != ' => $d->suratmasuk_id))->num_rows();
                                    }
                                }
                                // echo count($jumInput)+count($jumDis);
                                echo count($jumDis);

                            ?>    
                        </div>
                        <div class="widget-title">Surat Masuk</div>
                        <div class="widget-subtitle">Terdapat <?php echo count($jumDis); ?> total surat yang harus didisposisikan</div>
                    </div>
                </div>                            
                </a>                         
                <!-- END WIDGET SURAT MASUK -->
            </div>

            <div class="col-md-4">
                <!-- START WIDGET PENGAJUAN SURAT -->
                <a href="<?php echo site_url('suratkeluar/draft') ?>">
                <div class="widget widget-default widget-item-icon">
                    <div class="widget-item-left">
                        <span class="fa fa-envelope"></span>
                    </div>                             
                    <div class="widget-data">
                        <div class="widget-int num-count"><?php echo $pengajuansurat; ?></div>
                        <div class="widget-title">Surat Keluar</div>
                        <div class="widget-subtitle">Saat ini total Surat Keluar terdapat <?php echo $pengajuansurat; ?> Surat <br><br><br></div>
                    </div>
                </div>
                </a>
                <!-- END WIDGET PENGAJUAN SURAT -->
            </div>

            <div class="col-md-4">
                <!-- START WIDGET SURAT DISPOSISI -->
                <a href="<?php echo site_url('home/disposisi') ?>">
                <div class="widget widget-default widget-item-icon">
                    <div class="widget-item-left">
                        <span class="fa fa-envelope"></span>
                    </div>                             
                    <div class="widget-data">
                        <div class="widget-int num-count"><?php echo $disposisi; ?></div>
                        <div class="widget-title">Disposisi Surat</div>
                        <div class="widget-subtitle">Terdapat <?php echo $disposisi; ?> Surat masuk yang sudah diselesaikan<br><br></div>
                    </div>
                </div>
                </a>                        
                <!-- END WIDGET SURAT DISPOSISI -->
            </div>
            
            <div class="col-md-4">
                <!-- START WIDGET PENGARSIPAN -->
                <a href="<?php echo site_url('pengarsipan/suratkeluar') ?>">
                <div class="widget widget-default widget-item-icon">
                    <div class="widget-item-left">
                        <span class="fa fa-archive"></span>
                    </div>                             
                    <div class="widget-data">
                        <!-- <div class="widget-int num-count">50</div> -->
                        <div class="widget-title">Pengarsipan</div>
                        <div class="widget-subtitle">Data Pengarsipan Surat.</div>
                    </div>
                </div>
                </a>                            
                <!-- END WIDGET PENGARSIPAN -->
            </div>

            <div class="col-md-4">
                <!-- START WIDGET INFORMASI -->
                <div class="widget widget-default widget-item-icon">
                    <div class="widget-item-left">
                        <span class="fa fa-info"></span>
                    </div>                             
                    <div class="widget-data">
                        <div class="widget-title">Informasi</div>
                        <div class="widget-subtitle"><?php echo tanggal($tanggal); ?> <br> <?php echo substr($deskripsi, 0,50); ?> <a href="<?php echo site_url('home/informasi') ?>"><i>Selengkapnya ..</i></a></div>
                    </div>
                </div>
                <!-- END WIDGET INFORMASI -->
            </div>

            <div class="col-md-4">
                <!-- START WIDGET STATISTIK -->
                <a href="<?php echo site_url('home/statistik') ?>">
                <div class="widget widget-default widget-item-icon">
                    <div class="widget-item-left">
                        <span class="fa fa-bar-chart-o"></span>
                    </div>                             
                    <div class="widget-data">
                        <!-- <div class="widget-int num-count">50</div> -->
                        <div class="widget-title">Statistik</div>
                        <div class="widget-subtitle">Statistik Surat Masuk dan Keluar pada Perangkat Daerah</div>
                    </div>
                </div>
                </a>
                <!-- END WIDGET STATISTIK -->
            </div>

        <?php }elseif ($this->session->userdata('level') == 1 OR $this->session->userdata('level') == 2){ ?>

            <div class="col-md-12">
                <!-- START BAR CHART -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Grafik Surat</h3>                                
                    </div>
                    <div class="panel-body">

                        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
                        <style type="text/css">
                            ${demo.css}
                        </style>
                        <script type="text/javascript">
                            jQuery(document).ready(function( $ ) {
                                $(function () {
                                    $('#container').highcharts({
                                        chart: {
                                            type: 'column'
                                        },
                                        title: {
                                            text: 'Tata Naskah Dinas Elektronik Kota Bogor'
                                        },
                                        subtitle: {
                                            text: 'Source: tnde.kotabogor.go.id'
                                        },
                                        xAxis: {
                                            categories: [
                                                'Grafik Surat'
                                            ],
                                            crosshair: true
                                        },
                                        yAxis: {
                                            min: 0,
                                            title: {
                                                text: 'Jumlah Surat'
                                            }
                                        },
                                        tooltip: {
                                            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                                            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                                                '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
                                            footerFormat: '</table>',
                                            shared: true,
                                            useHTML: true
                                        },
                                        plotOptions: {
                                            column: {
                                                pointPadding: 0.2,
                                                borderWidth: 0
                                            }
                                        },
                                        series: [{
                                            name: 'Pengajuan Surat',
                                            data: [<?php echo $pengajuansurat; ?>]

                                        }, {
                                            name: 'Surat Keluar',
                                            data: [<?php echo $suratkeluar; ?>]

                                        }, {
                                            name: 'Surat Masuk',
                                            data: [<?php echo $suratmasuk; ?>]

                                        }]
                                    });
                                });
                            });
                        </script>

                        <script src="https://code.highcharts.com/highcharts.js"></script>
                        <script src="https://code.highcharts.com/modules/exporting.js"></script>
                        <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

                    </div>
                </div>
                <!-- END BAR CHART -->
            </div>

        <?php }else{ ?>

            <div class="col-md-4">
                <!-- START WIDGET SURAT MASUK -->
                <a href="<?php echo site_url('suratmasuk/inbox') ?>">
                <div class="widget widget-default widget-item-icon">
                    <div class="widget-item-left">
                        <span class="fa fa-envelope"></span>
                    </div>                             
                    <div class="widget-data">
                        <div class="widget-int num-count"> <?php echo $suratmasuk ?> </div>
                        <div class="widget-title">Disposisi Surat</div>
                        <div class="widget-subtitle">Terdapat <?php echo $suratmasuk; ?> Total Surat yang didisposisikan</div>
                    </div>
                </div>                            
                </a>                         
                <!-- END WIDGET SURAT MASUK -->
            </div>

            <div class="col-md-4">
                <!-- START WIDGET PENGAJUAN SURAT -->
                <a href="<?php echo site_url('suratkeluar/edaran') ?>">
                <div class="widget widget-default widget-item-icon">
                    <div class="widget-item-left">
                        <span class="fa fa-envelope"></span>
                    </div>                             
                    <div class="widget-data">
                        <div class="widget-int num-count"><?php echo $pengajuansurat; ?></div>
                        <div class="widget-title">Pengajuan Surat</div>
                        <div class="widget-subtitle">Saat ini total Pengajuan Surat terdapat <?php echo $pengajuansurat; ?> Surat</div>
                    </div>
                </div>
                </a>                         
                <!-- END WIDGET PENGAJUAN SURAT -->
            </div>

            <div class="col-md-4">
                <!-- START WIDGET DRAFT SURAT -->
                <a href="<?php echo site_url('suratkeluar/draft') ?>">
                <div class="widget widget-default widget-item-icon">
                    <div class="widget-item-left">
                        <span class="fa fa-envelope"></span>
                    </div>                             
                    <div class="widget-data">
                        <div class="widget-int num-count"><?php echo $draft; ?></div>
                        <div class="widget-title">Draft Surat</div>
                        <div class="widget-subtitle">Saat ini total Draft Surat terdapat <?php echo $draft; ?> Surat</div>
                    </div>
                </div>
                </a>                         
                <!-- END WIDGET DRAFT SURAT -->
            </div>

            <div class="col-md-4">
                <!-- START WIDGET INFORMASI -->
                <div class="widget widget-default widget-item-icon">
                    <div class="widget-item-left">
                        <span class="fa fa-info"></span>
                    </div>                             
                    <div class="widget-data">
                        <!-- <div class="widget-int num-count">50</div> -->
                        <div class="widget-title">Informasi</div>
                        <div class="widget-subtitle"><?php echo tanggal($tanggal); ?> <br> <?php echo substr($deskripsi, 0,50); ?> <a href="<?php echo site_url('home/informasi') ?>"><i>Selengkapnya ..</i></a></div>
                    </div>
                </div>
                <!-- END WIDGET INFORMASI -->
            </div>

            <div class="col-md-4">
                <!-- START WIDGET DRAFT SURAT -->
                <a href="<?php echo site_url('suratkeluar/draft/signature') ?>">
                <div class="widget widget-default widget-item-icon">
                    <div class="widget-item-left">
                        <span class="fa fa-envelope"></span>
                    </div>                             
                    <div class="widget-data">
                        <div class="widget-int num-count"><?php echo $tandatangan; ?></div>
                        <div class="widget-title">Tanda Tangan Surat</div>
                        <div class="widget-subtitle">Saat ini total surat yang harus ditandatangani terdapat <?php echo $tandatangan; ?> Surat</div>
                    </div>
                </div>
                </a>                         
                <!-- END WIDGET DRAFT SURAT -->
            </div>

        <?php } ?>

    </div>
    <!-- END WIDGETS -->  

</div>
<!-- END PAGE CONTENT WRAPPER -->