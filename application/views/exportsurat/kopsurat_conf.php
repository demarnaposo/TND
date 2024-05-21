<!-- @MpikEgov 10 Juni 2023 : Alternatif selain dibuat di helper -->
<?php
    $suratID=$this->uri->segment(3);
    $query=$this->db->query("SELECT * FROM draft d LEFT JOIN jabatan j ON j.jabatan_id=d.dibuat_id LEFT JOIN opd o ON o.opd_id=j.opd_id WHERE d.surat_id='$suratID'")->row_array();
    $opdindukID=$query['opd_induk'];
    $opdinduk=$this->db->query("SELECT * FROM opd WHERE opd_id='$opdindukID'")->row_array();
    $sekda=$this->db->query("SELECT * FROM opd where opd_id='4'")->row_array();
?>
<!-- KOP BAPPERIDA -->
    <?php if($query['opd_id'] == 43 && $query['kopId'] == 2){ ?>
        <table class="logo" border="0">
            <tr>
                <td width="13%" rowspan="4" align="center"><img src="assets/img/logokbr.png" width="55px"/></td>
                <td class="kop1" width="95%">PEMERINTAH DAERAH KOTA BOGOR</td>
            </tr>
            <?php if(empty($query['opd_induk'])){ ?>
                <tr>
                    <td class="kop1" width="95%"><?php echo strtoupper($query['nama_pd']); ?></td>
                </tr>
                <tr>
                    <td class="kop3" width="95%"><?php echo $query['alamat']; ?><br>
                    Telp. <?php echo $query['telp']; ?>, Faksimile <?php echo $query['faksimile']; ?><br>
                    Situs web : https://<?php echo $query['alamat_website']; ?> Email : <?php echo $query['email']; ?> 
                    </td>
                </tr>
            <?php } ?>	
        </table>
        <img src="assets/img/line.png" width="650px"/><br>
<!-- KOP BAPPERIDA -->


<!-- KOP PERANGKAT DAERAH -->
    <?php }elseif($query['kopId'] == 2){ ?>
        <table class="logo" border="0">
            <tr>
                <td width="13%" rowspan="4" align="center"><img src="assets/img/logokbr.png" width="55px"/></td>
                <td class="kop1" width="87%">PEMERINTAH DAERAH KOTA BOGOR</td>
            </tr>
            <?php if(empty($query['opd_induk'])){ ?>
                <tr>
                    <td class="kop1"><?php echo strtoupper($query['nama_pd']); ?></td>
                </tr>
                <tr>
                    <td class="kop3" ><?php echo $query['alamat']; ?><br>
                    Telp. <?php echo $query['telp']; ?>, Faksimile <?php echo $query['faksimile']; ?><br>
                    Situs web : https://<?php echo $query['alamat_website']; ?> Email : <?php echo $query['email']; ?> 
                    </td>
                </tr>
            <?php }else{ ?>
                <tr>
                    <td class="kop1"><?php echo strtoupper($opdinduk['nama_pd']); ?></td>
                </tr>
                <tr>
                    <td class="kop3" ><?php echo $opdinduk['alamat']; ?><br>
                    Telp. <?php echo $opdinduk['telp']; ?>, Faksimile <?php echo $opdinduk['faksimile']; ?><br>
                    Situs web : https://<?php echo $opdinduk['alamat_website']; ?> Email : <?php echo $opdinduk['email']; ?> 
                    </td>
                </tr>
            <?php } ?>	
        </table>
        <img src="assets/img/line.png" width="650px"/><br>
<!-- SELESAI KOP PERANGKAT DAERAH -->


<!-- KOP WALI KOTA -->
        <?php }elseif($query['kopId'] == 1 || $query['kopId'] == 3){?>
            <table class="logo" border="0">
                <tr>
                    <td align="center"><img src="assets/img/logogarudaemas.png" width="70px"></td>
                </tr>
                <tr>
                    <td class="kop1" align="center">WALI KOTA BOGOR</td>
                </tr>	
                </tr>	
            </table><br><br><br>
<!-- SELESAI KOP WALI KOTA -->
            
<!-- KOP SEKRETARIAT DAERAH -->
            <?php }elseif($query['kopId'] == 4){ ?>
                <table class="logo" border="0">
                        <tr>
                            <td width="13%" rowspan="3" align="center"><img src="assets/img/logokbr.png" width="55px"></td>
                            <td class="kop1" width="87%">PEMERINTAH DAERAH KOTA BOGOR</td>
                        </tr>
                        <!-- Start Kop Surat Sekretariat Daerah
                            Editor : @Muhamad Idham (Kamis, 17 Februari 2022)-->
                        <tr>
                            <td class="kop1">SEKRETARIAT DAERAH</td>
                        </tr>
                        <tr>
                        <td class="kop3" ><?php echo $sekda['alamat']; ?><br>
                        Telp. <?php echo $sekda['telp']; ?>, Faksimile <?php echo $sekda['faksimile']; ?><br>
                        Situs web : https://<?php echo $sekda['alamat_website']; ?> Email : <?php echo $sekda['email']; ?> 
                        </td>
                        </tr>
                        <!-- End Kop Surat Sekretariat Daerah -->
                    </table>
                    <img src="assets/img/line.png" width="650px"><br>
<!-- SELESAI KOP SEKRETARIAT DAERAH -->

<!-- KOP KELURAHAN/UPTD -->
            <?php }elseif($query['kopId'] == 5){ ?>
                <table class="logo" border="0">
                    <tr>
                        <td width="13%" rowspan="4" align="center"><img src="assets/img/logokbr.png" width="55px"></td>
                        <td class="kop1" width="87%">PEMERINTAH DAERAH KOTA BOGOR</td>
                    </tr>
                    <tr>
                        <td class="kop1"><?= strtoupper($opdinduk['nama_pd'])?></td>
                    </tr>
                    <tr>
                        <td class="kop1"><?= strtoupper($query['nama_pd'])?></td>
                    </tr>
                    <tr>
                        <td class="kop3" ><?php echo $query['alamat']; ?><br>
                        Telp. <?php echo $query['telp']; ?>, Faksimile <?php echo $query['faksimile']; ?><br>
                        Situs web : https://<?php echo $query['alamat_website']; ?> Email : <?php echo $query['email']; ?> 
                        </td>
                    </tr>	
                </table>
                <img src="assets/img/line.png" width="650px"><br>
            <?php } ?>
<!-- SELESAI KOP KELURAHAN/UPTD -->
