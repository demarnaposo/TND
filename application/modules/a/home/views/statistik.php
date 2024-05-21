<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="#">Dashboard</a></li>
    <li class="active">Statistik</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-bar-chart-o"></span> Data Statistik</h2>
</div>
<!-- END PAGE TITLE -->                

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">                

    <div class="row">
        <div class="col-md-12">

            <!-- START DEFAULT DATATABLE -->
            <div class="panel panel-default">
                <div class="panel-body">
                    
                    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
                    <style type="text/css">
                        ${demo.css}
                    </style>

                    <?php 
                        $tahun = date('Y');
                        $tahunAwal = date('Y')-5; 

                        $opd = $this->session->userdata('opd_id');
                    ?>

                    <script type="text/javascript">
                        jQuery(document).ready(function( $ ) {
                            $(function () {
                                $('#container').highcharts({
                                    chart: {
                                        type: 'column'
                                    },
                                    title: {
                                        text: 'Data Statistik Surat Keluar dan Masuk'
                                    },
                                    subtitle: {
                                        text: 'Source: tnde.kotabogor.go.id'
                                    },
                                    xAxis: {
                                        categories: [
                            
                                        <?php for ($thn=$tahunAwal; $thn <= $tahun; $thn++) { ?>
                                            '<?php echo $thn; ?>',
                                        <?php } ?>
                                        ],
                                        crosshair: true
                                    },
                                    yAxis: {
                                        min: 0,
                                        title: {
                                            text: 'Jumlah'
                                        }
                                    },
                                    tooltip: {
                                        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                                        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                                            '<td style="padding:0"><b>{point.y:1f} Surat</b></td></tr>',
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
                                        name: 'Surat Keluar',
                                        // data:   [49, 71, 106, 129, 144, 176]
                                        data:   [
                                                    <?php
                                                        for ($thn=$tahunAwal; $thn <= $tahun; $thn++) {
                                                            $suratkeluar = $this->db->query("SELECT * FROM draft LEFT JOIN jabatan ON jabatan.jabatan_id = draft.dibuat_id WHERE LEFT(tanggal, 4) = '$thn' AND jabatan.opd_id = '$opd'")->num_rows();
                                                            echo $suratkeluar.',';
                                                        }
                                                    ?>
                                                ]

                                    }, {
                                        name: 'Surat Masuk',
                                        // data: [42, 33, 34, 39, 52, 75]
                                        data: [
                                                    <?php
                                                        for ($thn=$tahunAwal; $thn <= $tahun; $thn++) {
                                                            $suratmasuk = $this->db->query("
                                                                    SELECT * FROM disposisi_suratmasuk 
                                                                    JOIN surat_masuk ON surat_masuk.suratmasuk_id = disposisi_suratmasuk.suratmasuk_id 
                                                                    JOIN jabatan ON jabatan.jabatan_id = disposisi_suratmasuk.users_id 
                                                                    WHERE LEFT(diterima, 4) = '$thn' 
                                                                    AND jabatan.opd_id = '$opd' 
                                                                    AND status = 'Selesai' 
                                                                    GROUP BY disposisi_suratmasuk.suratmasuk_id
                                                                ")->num_rows();
                                                            echo $suratmasuk.',';
                                                        }
                                                    ?>
                                                ]

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
            <!-- END DEFAULT DATATABLE -->

        </div>
    </div>
</div>