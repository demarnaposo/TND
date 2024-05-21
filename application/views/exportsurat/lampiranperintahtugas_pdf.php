<style>
table.logo {
	vertical-align:middle;
	color: #000000;
	text-align: center;
}
table.logo td.kop1{	
   	text-transform:capitalize;
	font-size:12px;
	font-weight: bold;
	border-collapse: collapse;
}
table.logo td.kop2{
   	text-transform: uppercase;
	font-size:15px;
	font-weight: bold;
	border-collapse: collapse;
}
table.logo td.kop3{
	text-transform:capitalize;
	font-size:10px;   	
	border-collapse: collapse;
	line-height: 1.2;
}
table.logo td.kop4{
	font-size:2px;   	
	border-collapse: collapse;
}
table.surat-keterangan{
	border-spacing: 0px 5px;
	font-size:10px;
	text-align: center;
	font-weight: bold;
}
table.isi{
	border-spacing: 0px 1px;
	font-size:10px;
}
p { 
	font-size:10px;
	text-align: justify;
	text-justify: inter-word;	
	line-height: 1.3;
}
table.ttd{
	border-spacing: 0px 0px;
	font-size:10px;
}
div.tembusan{ 
	font-size:9px;
	white-space: pre-line;
	text-align: left;
	page-break-after: always;	
}
div.terlampir{ 
	font-size:10px;
	white-space: pre-line;
	text-align:left;
	page-break-before: always;	
}
table.lampiran{
	border-spacing: 0px 0px;
	font-size:10px;
}

</style>

<?php foreach ($lampiran as $key => $h) { ?>

<?php
	if(empty($h->pegawai_id)){
		// echo $h->kepada;
	}else{
	
	//Perbaikan Query Lampiran Daftar Pegawai Berulang @dam (Kamis, 24 Maret 2022)
	// $datapegawai =	$this->db->query("
	// 	SELECT * FROM aparatur
	// 	LEFT JOIN jabatan ON aparatur.jabatan_id = jabatan.jabatan_id
	// 	LEFT JOIN levelbaru ON levelbaru.level_id=aparatur.level_id
	// 	WHERE aparatur.aparatur_id IN ($h->pegawai_id) AND aparatur.level_id !=0 ORDER BY levelbaru.level_id ASC, golongan DESC");
	$datapegawai =	$this->db->query("
		SELECT * FROM aparatur
		LEFT JOIN jabatan ON aparatur.jabatan_id = jabatan.jabatan_id
		LEFT JOIN users ON aparatur.aparatur_id = users.aparatur_id
		LEFT JOIN level ON level.level_id=users.level_id
		WHERE aparatur.aparatur_id IN ($h->pegawai_id) AND aparatur.level_id !=0 ORDER BY aparatur.level_id ASC");
		
	// $datapegawai =	$this->db->query("
	// SELECT * FROM aparatur
	// LEFT JOIN jabatan ON aparatur.jabatan_id = jabatan.jabatan_id
	// LEFT JOIN users ON aparatur.nip = users.username
	// LEFT JOIN level ON level.level_id=users.level_id
	// WHERE aparatur.aparatur_id IN ($h->pegawai_id) ORDER BY level.level_id ASC");
?>
<?php
	if ($datapegawai->num_rows() > 3) { ?>
	<div class="terlampir">	
<b>LAMPIRAN :</b><br><br>
<table border="0">	
	<tr>
		<td width="10%">NOMOR</td>
		<td width="2%">:</td>
		<td width="85%"><?php echo $h->nomor; ?></td>
	</tr>
	<tr>
		<td width="10%">TANGGAL</td>
		<td width="2%">:</td>
		<td width="85%"><?php echo tanggal($h->tanggal); ?></td>
	</tr>
</table><br><br>

<table class="lampiran" border="1">	
<?php
	$no = 1;
	foreach ($datapegawai->result() as $key => $p) {
	?>	
	<tr>
		<td width="5%" align="center"><?php echo $no; ?></td>
		<td width="20%">Nama</td>
		<td width="2%">:</td>
		<td width="73%"><?php echo $p->nama; ?></td>
	</tr>
	<tr>
		<td width="5%"></td>
		<td width="20%">NIP</td>
		<td width="2%">:</td>
		<td width="73%"><?php echo $p->nip; ?></td>
	</tr>
	<tr>
		<td width="5%"></td>
		<td width="20%">Pangkat/Golongan</td>
		<td width="2%">:</td>
		<td width="73%"><?php echo $p->pangkat; ?> - <?php echo $p->golongan; ?></td>
	</tr>
	<tr>
		<td width="5%"></td>
		<td width="20%">Jabatan</td>
		<td width="2%">:</td>
		<td width="73%"><?php echo $p->jabatan; ?></td>
	</tr>

		<!-- Start perbaikan format break page table [@dam | 10-05-2022] -->
	<?php if($no == 15 ) { ?>
        </table><br><br>
        <table class="lampiran" border="1">
    <?php } elseif($no == 31 || $no == 47 || $no == 63 || $no == 79 || $no == 95 || $no == 111 || $no == 127) { ?>
        </table><br><br><br>
        <table class="lampiran" border="1">
    <?php } ?>
    <!-- End perbaikan format break page table [@dam | 10-05-2022] -->

<?php $no++;  } }?>

</table><br><br>

<?php } ?> 

<?php } ?>