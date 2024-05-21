<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="#">Halaman Utama</a></li>                    
    <li class="active">Beranda</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">

    <!-- START WIDGETS -->                    
    <div class="row">

        <?php if ($this->session->userdata('level') == 4 || $this->session->userdata('level') == 18 ){ ?>

            <div class="col-md-4">
                <!-- START WIDGET SURAT MASUK -->
                <a href="<?php echo site_url('suratmasuk/inbox') ?>">
                <div class="widget widget-suratmasuk widget-item-icon">
                    <div class="widget-item-left">
                        <!-- <span class="fa fa-envelope"></span> -->
                        <img src="<?= site_url('/assets/img/icons/icon-surat-masuk.png')?>" width="64px">
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

                                // $jumDis = array();
                                // foreach ($didisposisikan as $key => $d) {
                                //     $qDis = $this->db->limit(1)->order_by('dsuratmasuk_id', 'DESC')->get_where('disposisi_suratmasuk', array('suratmasuk_id != ' => $d->suratmasuk_id))->num_rows();
                                //     if ($qDis == 0) {
                                //         $jumDis[] = $this->db->limit(1)->order_by('dsuratmasuk_id', 'DESC')->get_where('disposisi_suratmasuk', array('suratmasuk_id != ' => $d->suratmasuk_id))->num_rows();
                                //     }
                                // }
                                // echo count($jumInput)+count($jumDis);
                                // echo $suratmasuk;

                            ?>    
                            <br>
                        </div>
                        <div class="widget-title"><b>Surat Masuk</b></div>
                        <div class="widget-subtitle">Terdapat menu disposisi surat masuk, surat TND, surat masuk, surat tembusan, surat selesai</div>
                    </div>
                </div>                            
                </a>                         
                <!-- END WIDGET SURAT MASUK -->
            </div>

            <div class="col-md-4">
                <!-- START WIDGET PENGAJUAN SURAT -->
                <a href="<?php echo site_url('suratkeluar/draft/penomoran') ?>">
                <div class="widget widget-suratkeluar widget-item-icon">
                    <div class="widget-item-left">
                        <!-- <span class="fa fa-envelope"></span> -->
                        <img src="<?= site_url('/assets/img/icons/icon-surat-keluar.png')?>" width="64px">
                    </div>                             
                    <div class="widget-data">
                        <div class="widget-int num-count"><?php echo ($draft); ?></div>
                        <div class="widget-title"><b>Surat Keluar</b></div>
                        <div class="widget-subtitle">Saat ini total Surat Keluar terdapat <?php echo ($draft); ?> Surat <br><br></div>
                    </div>
                </div>
                </a>
                <!-- END WIDGET PENGAJUAN SURAT -->
            </div>

            <div class="col-md-4">
                <!-- START WIDGET SURAT DISPOSISI -->
                <a href="<?php echo site_url('home/disposisi') ?>">
                <div class="widget widget-disposisi widget-item-icon">
                    <div class="widget-item-left">
                        <!-- <span class="fa fa-envelope"></span> -->
                        <img src="<?= site_url('/assets/img/icons/icon-disposisi.png')?>" width="64px">
                    </div>                             
                    <div class="widget-data">
                        <div class="widget-int num-count"><?php echo $disposisi; ?></div>
                        <div class="widget-title"><b>Disposisi Surat</b></div>
                        <div class="widget-subtitle">Terdapat <?php echo $disposisi; ?> Surat masuk yang sudah diselesaikan<br><br></div>
                    </div>
                </div>
                </a>                        
                <!-- END WIDGET SURAT DISPOSISI -->
            </div>
            
            <div class="col-md-4">
                <!-- START WIDGET PENGARSIPAN -->
                <a href="<?php echo site_url('pengarsipan/suratkeluar') ?>">
                <div class="widget widget-arsip widget-item-icon">
                    <div class="widget-item-left">
                        <!-- <span class="fa fa-archive"></span> -->
                        <img src="<?= site_url('/assets/img/icons/icon-pengarsipan.png')?>" width="64px">
                    </div>                             
                    <div class="widget-data">
                        <!-- <div class="widget-int num-count">50</div> -->
                        <div class="widget-title"><b>Pengarsipan</b></div>
                        <div class="widget-subtitle">Data Pengarsipan Surat.</div>
                    </div>
                </div>
                </a>                            
                <!-- END WIDGET PENGARSIPAN -->
            </div>

            <div class="col-md-4">
                <!-- START WIDGET INFORMASI -->
                <div class="widget widget-informasi widget-item-icon">
                    <div class="widget-item-left">
                        <!-- <span class="fa fa-info"></span> -->
                        <img src="<?= site_url('/assets/img/icons/icon-informasi.png')?>" width="64px">
                    </div>                             
                    <div class="widget-data">
                        <div class="widget-title"><b>Informasi</b></div>
                        <div class="widget-subtitle"><?php echo tanggal($tanggal); ?> <br> <?php echo substr($deskripsi, 0,50); ?> <a href="<?php echo site_url('home/informasi') ?>"><i>Selengkapnya ..</i></a></div>
                    </div>
                </div>
                <!-- END WIDGET INFORMASI -->
            </div>

            <div class="col-md-4">
                <!-- START WIDGET STATISTIK -->
                <a href="<?php echo site_url('home/statistik') ?>">
                <div class="widget widget-statistik widget-item-icon">
                    <div class="widget-item-left">
                        <!-- <span class="fa fa-bar-chart-o"></span> -->
                        <img src="<?= site_url('/assets/img/icons/icon-statistik.png')?>" width="64px">
                    </div>                             
                    <div class="widget-data">
                        <!-- <div class="widget-int num-count">50</div> -->
                        <div class="widget-title"><b>Statistik</b></div>
                        <div class="widget-subtitle">Statistik Surat Masuk dan Keluar pada Perangkat Daerah</div>
                    </div>
                </div>
                </a>
                <!-- END WIDGET STATISTIK -->
            </div>
            <div class="col-md-12">
                <!-- START BAR CHART -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Grafik Surat Keluar</h3>                                
                    </div>
                    <div class="panel-body">
                        <style type="text/css">
                            ${demo.css}
                        </style>
                        <!-- Update @Mpik Egov 19 Juli 2022-->
                        <script type="text/javascript">
                            jQuery(document).ready(function( $ ) {
                                $(function () {
                                    $('#container1').highcharts({
                                        chart: {
                                            type: 'column'
                                        },
                                        title: {
                                            align: 'left',
                                            text: 'Jumlah total pembuatan permasing-masing jenis surat keluar tahun <?php echo $this->session->userdata('tahun'); ?>'
                                        },
                                        subtitle: {
                                            align: 'left',
                                            text: 'Sumber : <a href="http://tnd.kotabogor.go.id" target="_blank">tnd.kotabogor.go.id</a>'
                                        },
                                        accessibility: {
                                            announceNewData: {
                                                enabled: true
                                            }
                                        },
                                        xAxis: {
                                            type: 'category'
                                        },
                                        yAxis: {
                                            title: {
                                                text: 'Total Surat'
                                            }
                                    
                                        },
                                        legend: {
                                            enabled: false
                                        },
                                        plotOptions: {
                                            series: {
                                                borderWidth: 0,
                                                dataLabels: {
                                                    enabled: true,
                                                    format: '{point.y:1f}'
                                                }
                                            }
                                        },
                                    
                                        tooltip: {
                                            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                                            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:1f}</b> total surat<br/>'
                                        },
                                    
                                        series: [
                                            {
                                                name: "Surat Keluar",
                                                colorByPoint: true,
                                                data: [
                                                    {
                                                        name: "Surat Instruksi",
                                                        y: <?= $suratinstruksi; ?>,
                                                        drilldown: null
                                                    },
                                                    {
                                                        name: "Surat Edaran",
                                                        y: <?= $suratedaran; ?>,
                                                        drilldown: null
                                                    },
                                                    {
                                                        name: "Surat Biasa",
                                                        y: <?= $suratbiasa; ?>,
                                                        drilldown: null
                                                    },
                                                    {
                                                        name: "Surat Undangan",
                                                        y: <?= $suratundangan; ?>,
                                                        drilldown: null
                                                    },
                                                    {
                                                        name: "Surat Keterangan",
                                                        y: <?= $suratketerangan; ?>,
                                                        drilldown: null
                                                    },
                                                    {
                                                        name: "Surat Perintah",
                                                        y: <?= $suratperintah; ?>,
                                                        drilldown: null
                                                    },
                                                    {
                                                        name: "Surat Perintah Khusus",
                                                        y: <?= $suratperintahtugas; ?>,
                                                        drilldown: null
                                                    },
                                                    {
                                                        name: "Nota Dinas",
                                                        y: <?= $suratnotadinas; ?>,
                                                        drilldown: null
                                                    },
                                                    {
                                                        name: "Lampiran",
                                                        y: <?= $suratlampiran; ?>,
                                                        drilldown: null
                                                    },
                                                    {
                                                        name: "Surat Izin",
                                                        y: <?= $suratizin; ?>,
                                                        drilldown: null
                                                    },
                                                    {
                                                        name: "Surat Perjalanan Dinas",
                                                        y: <?= $suratperjalanandinas; ?>,
                                                        drilldown: null
                                                    },
                                                    {
                                                        name: "Surat Pernyataan Melaksanakan Tugas",
                                                        y: <?= $suratmelaksanakantugas; ?>,
                                                        drilldown: null
                                                    },
                                                    {
                                                        name: "Surat Panggilan",
                                                        y: <?= $suratpanggilan; ?>,
                                                        drilldown: null
                                                    },
                                                    {
                                                        name: "Surat Pengumuman",
                                                        y: <?= $suratpengumuman; ?>,
                                                        drilldown: null
                                                    },
                                                    {
                                                        name: "Laporan",
                                                        y: <?= $suratlaporan; ?>,
                                                        drilldown: null
                                                    },
                                                    {
                                                        name: "Surat Rekomendasi",
                                                        y: <?= $suratrekomendasi; ?>,
                                                        drilldown: null
                                                    },
                                                    {
                                                        name: "Surat Pengantar",
                                                        y: <?= $suratpengantar; ?>,
                                                        drilldown: null
                                                    },
                                                    {
                                                        name: "Surat Kuasa",
                                                        y: <?= $suratkuasa; ?>,
                                                        drilldown: null
                                                    },
                                                    {
                                                        name: "Surat Berita Acara",
                                                        y: <?= $suratberitaacara; ?>,
                                                        drilldown: null
                                                    },
                                                    {
                                                        name: "Notulen",
                                                        y: <?= $suratnotulen; ?>,
                                                        drilldown: null
                                                    },
                                                    {
                                                        name: "Memo",
                                                        y: <?= $suratmemo; ?>,
                                                        drilldown: null
                                                    },
                                                    {
                                                        name: "Surat Keputusan",
                                                        y: <?= $suratkeputusan; ?>,
                                                        drilldown: null
                                                    },
                                                    {
                                                        name: "Surat Lainnya",
                                                        y: <?= $suratlainnya; ?>,
                                                        drilldown: null
                                                    }
                                                    
                                                ]
                                            }
                                        ]
                                    });
                                });
                            });
                        </script>
                        <div id="container1" style="min-width: 310px; height: 600px; margin: 0 auto"></div>
                        <!-- END -->
                        <!-- Update @Mpik Egov 19 Juli 2022-->

                    </div>
                </div>
                <!-- END BAR CHART -->
            </div>

        <?php }elseif ($this->session->userdata('level') == 2){ ?>

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
        <?php }elseif ($this->session->userdata('level') == 1){ ?>
            <div class="col-md-12">
                <!-- START BAR CHART -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Grafik Surat</h3>                                
                    </div>
                    <div class="panel-body">
                        <style type="text/css">
                            ${demo.css}
                        </style>
                        <!-- Update @Mpik Egov 19 Juli 2022-->
                        <script type="text/javascript">
                            jQuery(document).ready(function( $ ) {
                                $(function () {
                                    $('#container2').highcharts({
                                    chart: {
                                        type: 'bar'
                                    },
                                    title: {
                                        text: 'Jumlah Total Surat Keluar - Surat Masuk Tahun <?php echo $this->session->userdata('tahun'); ?>'
                                    },
                                    subtitle: {
                                        text: 'Source: <a href="https://tnd.kotabogor.go.id">tnd.kotabogor.go.id</a>'
                                    },
                                    xAxis: {
                                        categories: [<?php foreach($listopd as $lopd){ ?>
                                            '<?= $lopd->kode_pd ?>',
                                             <?php } ?>],
                                        title: {
                                            text: null
                                        }
                                    },
                                    yAxis: {
                                        min: 0,
                                        title: {
                                            text: 'Jumlah Surat ',
                                            align: 'high'
                                        },
                                        labels: {
                                            overflow: 'justify'
                                        }
                                    },
                                    tooltip: {
                                        valueSuffix: ' Surat'
                                    },
                                    plotOptions: {
                                        bar: {
                                            dataLabels: {
                                                enabled: true
                                            }
                                        }
                                    },
                                    legend: {
                                        layout: 'vertical',
                                        align: 'right',
                                        verticalAlign: 'top',
                                        x: -40,
                                        y: 80,
                                        floating: true,
                                        borderWidth: 1,
                                        backgroundColor:
                                            Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
                                        shadow: true
                                    },
                                    credits: {
                                        enabled: false
                                    },
                                    series: [{
                                        name: 'Surat Masuk',
                                        data: [
                                                <?php
                                                $tahun = $this->session->userdata('tahun');
                                                foreach($listopd1 as $lopd){
                                                    $jmlsuratmasuk=$this->db->query("SELECT opd.kode_pd, SUM(YEAR(surat_masuk.tanggal)='$tahun') as jmlsuratmasuk FROM opd
                                                    LEFT JOIN surat_masuk ON surat_masuk.opd_id=opd.opd_id
                                                    LEFT JOIN urutan_opd ON urutan_opd.urutan_id=opd.urutan_id
                                                    WHERE opd.statusopd='Aktif' AND opd.urutan_id != 0 AND opd.opd_id='$lopd->opd_id'")->result();
                                                    foreach($jmlsuratmasuk as $key => $jmlsm){
                                                        if($jmlsm->jmlsuratmasuk == null){
                                                            $jmlsuratmasuk='0';
                                                        }else{
                                                        $jmlsuratmasuk=$jmlsm->jmlsuratmasuk;
                                                        }
                                                        
                                                        echo $jmlsuratmasuk.',';
                                                    }
                                                }
                                            ?>
                                            ]
                                    }, {
                                        name: 'Surat Keluar',
                                        data: [
                                            <?php
                                                $tahun = $this->session->userdata('tahun');
                                                foreach($listopd2 as $lopd){
                                                    $jmlsuratkeluar=$this->db->query("SELECT COUNT(penandatangan.surat_id) as jmlsuratkeluar FROM draft
                                                    LEFT JOIN jabatan ON jabatan.jabatan_id=draft.dibuat_id
                                                    LEFT JOIN opd ON opd.opd_id=jabatan.opd_id
                                                    LEFT JOIN penandatangan ON penandatangan.surat_id=draft.surat_id
                                                    WHERE opd.opd_id='$lopd->opd_id' AND penandatangan.status='Sudah Ditandatangani' AND YEAR(draft.tanggal)='$tahun'")->result();
                                                    foreach($jmlsuratkeluar as $key => $jmlsk){
                                                        if($jmlsk->jmlsuratkeluar == null){
                                                            $jmlsuratkeluar='0';
                                                        }else{
                                                        $jmlsuratkeluar=$jmlsk->jmlsuratkeluar;
                                                        }
                                                        
                                                        echo $jmlsuratkeluar.',';
                                                    }
                                                }
                                            ?>
                                            ]
                                    }]
                                    });
                                });
                            });
                        </script>
                        <div id="container2" style="min-width: 310px; height: 4100px; margin: 0 auto"></div>
                        
                    </div>
                </div>
                <!-- END BAR CHART -->
            </div>
        <?php }else{ ?>

            <div class="col-md-4">
                <!-- START WIDGET SURAT MASUK -->
                <a href="<?php echo site_url('suratmasuk/inbox') ?>">
                <div class="widget widget-suratmasuk widget-item-icon">
                    <div class="widget-item-left">
                        <!-- <span class="fa fa-envelope"></span> -->
                        <img src="<?= site_url('/assets/img/icons/icon-surat-masuk.png')?>" width="64px">
                    </div>                             
                    <div class="widget-data">
                        <div class="widget-int num-count"> <?php echo $suratmasuk ?> </div>
                        <div class="widget-title"><b>Surat Masuk</b></div>
                        <div class="widget-subtitle">Terdapat <?php echo $suratmasuk; ?> Total Surat yang didisposisikan</div>
                    </div>
                </div>                            
                </a>                         
                <!-- END WIDGET SURAT MASUK -->
            </div>

            <div class="col-md-4">
                <!-- START WIDGET PENGAJUAN SURAT -->
                <a href="<?php echo site_url('home/dashboard_keluar') ?>">
                <div class="widget widget-suratkeluar widget-item-icon">
                    <div class="widget-item-left">
                        <!-- <span class="fa fa-envelope"></span> -->
                        <img src="<?= site_url('/assets/img/icons/icon-surat-keluar.png')?>" width="64px">
                    </div>                             
                    <div class="widget-data">
                        <div class="widget-int num-count"><?php echo ($pengajuansurat + $draft + $tandatangan); ?></div>
                        <div class="widget-title"><b>Surat Keluar</b></div>
                        <div class="widget-subtitle">Saat ini total Pengajuan Surat terdapat <?php echo ($pengajuansurat + $draft + $tandatangan); ?> Surat</div>
                    </div>
                </div>
                </a>                         
                <!-- END WIDGET PENGAJUAN SURAT -->
            </div>

            <div class="col-md-4">
                <!-- START WIDGET INFORMASI -->
                <div class="widget widget-informasi widget-item-icon">
                    <div class="widget-item-left">
                        <!-- <span class="fa fa-info"></span> -->
                        <img src="<?= site_url('/assets/img/icons/icon-informasi.png')?>" width="64px">
                    </div>                             
                    <div class="widget-data">
                        <!-- <div class="widget-int num-count">50</div> -->
                        <div class="widget-title"><b>Informasi</b></div>
                        <div class="widget-subtitle"><?php echo tanggal($tanggal); ?> <br> <?php echo substr($deskripsi, 0,50); ?> <a href="<?php echo site_url('home/informasi') ?>"><i>Selengkapnya ..</i></a></div>
                    </div>
                </div>
                <!-- END WIDGET INFORMASI -->
            </div>

        <?php } ?>

    </div>
    <!-- END WIDGETS -->  

</div>

<!-- Start Update @Demar E-Gov 29/02/2024 add Modal  -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel" style="display: flex; justify-content: center; align-items: center;">Peringatan ⚠️</h5>
      </div>
      <div class="modal-body">
        <div style="display: flex; justify-content: center; align-items: center;">
            <img src="/assets/img/password.jpg" width="100%" height="auto" alt="">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal" style="margin:auto; display:block;">Tutup</button>
      </div>
    </div>
  </div>
</div>
<!-- End Update @Demar E-Gov 29/02/2024 add Modal  -->
<!-- END PAGE CONTENT WRAPPER -->
<!-- END PAGE CONTENT WRAPPER -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- Script automatic show modal -->
<script>
    $(window).ready(() => {
	$('#exampleModal').modal('show');
	// setTimeout(() => $('#myModal').modal('show'), 1000);
	// setTimeout(() => $('#myModal').modal('hide'), 4000);
})
const show = () => {
	$('#myModal').modal('show');
}
</script>
<!-- Script automatic show modal -->
