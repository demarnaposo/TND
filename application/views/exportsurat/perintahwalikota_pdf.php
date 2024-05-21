<style>
table.logo {
	vertical-align:middle;
	color: #000000;
	text-align: center;
}
table.logo td.kop1{	
   	text-transform:capitalize;
	font-size:13px;
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

<?php foreach ($perintah as $key => $h) { ?>
	<table class="logo" border="0">
	<tr>
		<td align="center"><img src="assets/img/logogarudaemas.png" width="70px"></td>
	</tr>
	<tr>
		<td class="kop1" align="center">WALI KOTA BOGOR</td>
	</tr>
	<tr><td></td></tr>	
	<tr>
		<td class="kop1" align="center">SURAT PERINTAH TUGAS</td>
	</tr>	
	<tr>
		<td class="kop1" align="center">NOMOR</td>
	</tr>	
</table><br><br><br>
<table class="isi" border="0">	
	<tr>
	<td width="100%" colspan="4">Yang bertandatangan di bawah ini :</td>
	</tr>
	<tr><td></td></tr>
	<tr>
		<td width="23%">Nama</td>
		<td width="2%">:</td>
		<td width="75%">Dr. Bima Arya Sugiarto, S.Hum., M.A.</td>
	</tr>
	<tr>
		<td width="23%">Jabatan</td>
		<td width="2%">:</td>
		<td width="75%">Wali Kota Bogor</td>
	</tr>
	<tr><td></td></tr>
	<tr>
		<td width="100%" colspan="4" align="center"><b>MEMERINTAHKAN :</b></td>
	</tr>
	<tr><td></td></tr>
	<?php
		$datapegawai =	$this->db->query("
		SELECT * FROM aparatur
		LEFT JOIN jabatan ON aparatur.jabatan_id = jabatan.jabatan_id
		WHERE aparatur.aparatur_id IN ($h->pegawai_id)");//->result_array();?>
	<?php if($datapegawai->num_rows() > 3){ ?>
	<tr>
		<td width="23%">Kepada</td>
		<td width="2%">:</td>
		<td width="75%">Terlampir</td>
	</tr>
	<?php }else{?>
	<tr>
		<td width="100%" colspan="4">Kepada :</td>
	</tr>
	<?php	
	$no = 1;
	foreach ($datapegawai->result() as $key => $p) {
	?>	
	<tr>
		<td width="3%"><?php echo $no; ?>.</td>
		<td width="20%">Nama</td>
		<td width="2%">:</td>
		<td width="75%"><?php echo $p->nama; ?></td>
	</tr>
	<tr>
		<td width="3%"></td>
		<td width="20%">NIP</td>
		<td width="2%">:</td>
		<td width="75%"><?php echo $p->nip; ?></td>
	</tr>
	<tr>
		<td width="3%"></td>
		<td width="20%">Pangkat/Golongan</td>
		<td width="2%">:</td>
		<td width="75%"><?php echo $p->pangkat; ?> - <?php echo $p->golongan; ?></td>
	</tr>
	<tr>
		<td width="3%"></td>
		<td width="20%">Jabatan</td>
		<td width="2%">:</td>
		<td width="75%"><?php echo $p->nama_jabatan; ?></td>
	</tr>

<?php $no++;  } }?>
<tr><td></td></tr>
<tr>
		<td width="23%">Untuk </td>
		<td width="2%">:</td>
		<td width="75%"><?=$h->untuk?></td>
	</tr>

</table><br><br><br>


<table class="ttd" border="0">
	<tr>
		<td width="61%" colspan="2"></td>	
		<td width="39%">Ditetapkan di Bogor</td>
	</tr>	<tr>
		<td width="61%" colspan="2"></td>	
		<td width="39%">Pada Tanggal <u><?php echo tanggal($h->tanggal) ?> M</u></td>
	</tr>
	<tr>
		<td width="75%" colspan="2"></td>	
		<td width="25%"><?php echo hijriah($h->tanggal); ?><br></td>
	</tr>	
	<tr>
		<td width="61%" colspan="2"></td>	
		<td width="39%"><b>WALI KOTA BOGOR,</b></td>
	</tr>
	<tr>
		<td width="46%"></td>		
		<td width="15%">
		
		</td>
		<td width="39%">
		<?php
			if ($h->status == NULL) {
				echo "<br><br>DRAFT<br>"; 
			}			 
			else if ($h->status == 'Belum Ditandatangani') {
				echo "<br><br>BELUM DITANDATANGANI<br>";
			}else{
				?><img src="assets/img/ttd_digital.png" width="53px">
			<?php }
		?>			
		</td>
	</tr>
	<tr>
		<td width="61%" colspan="2"></td>	
		<td width="39%"><><b>Dr. Bima Arya Sugiarto, S.Hum., M.A.</b>
		</td>
	</tr>
</table>

<?php
	if ($datapegawai->num_rows() > 3) { ?>
	<div class="terlampir">	
<b>LAMPIRAN :</b><br><br>
<table border="0">	
	<tr>
		<td width="10%">NOMOR</td>
		<td width="2%">:</td>
		<td width="85%"><?php echo $h->nomor; ?></td>
	</tr>x
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
		<td width="73%"><?php echo $p->nama_jabatan; ?></td>
	</tr>

<?php $no++;  } ?>

</table><br><br>

<?php } ?> 
</div>



<?php  } ?>