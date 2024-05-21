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

table.nomor-surat{
	border-spacing: 0px 0px;
	font-size:10px;
}

table.isi-surat{
	border-spacing: 0px 4px;
	font-size:10px;
}

table.jadwal{
	border-spacing: 0px 1px;
	font-size:10px;
}

p { 
	font-size:10px;
	text-align: justify;
	text-justify: inter-word;	
	text-indent: 28px;
	line-height: 1.3;
}
table.lampiran{
	border-spacing: 0px 0px;
	font-size:10px;
}
div.tembusan{ 
	font-size:9px;
	white-space: pre-line;
	text-align: justify;:left;
}

div.lampiran{ 
	font-size:10px;
	white-space: pre-line;
	text-align: left;
	page-break-before: always;
}

</style>

<?php foreach ($instruksi as $key => $h) { ?>
<table class="logo" border="0">
	<tr>
		<td align="center"><img src="assets/img/logogarudaemas.png" width="70px"></td>
	</tr>
	<tr>
		<td class="kop1" align="center">WALI KOTA BOGOR</td>
	</tr><br>	
	<tr>
		<td class="kop1" align="center"><b>INSTRUKSI WALI KOTA</b></td>
	</tr>	
	<tr>
		<td class="kop1" align="center"><b>NOMOR <?= $h->nomor; ?></b></td>
	</tr>
	<tr>
		<td></td>
	</tr>	
	<tr>
		<td class="kop1" align="center"><b>TENTANG</b></td>
	</tr>	
	<tr>
		<td class="kop1" align="center"><b><?= $h->tentang; ?></b></td>
	</tr>
	<tr>
		<td></td>
	</tr>	
	<tr>
		<td class="kop1"><b>WALI KOTA BOGOR</b></td>
	</tr>	
	</tr>	
</table>
<br><br><br>
<table class="nomor-surat" border="0">	
	<tr>
		<td>Dalam Rangka <?= $h->tentang;?> dengan ini menginstruksikan :</td>
	</tr>
	<tr>
		<td width="15%">Kepada</td>
		<td width="2%">:</td>
		<td width="83%">
		<?php
            				$this->db->from('disposisi_suratkeluar');
            				$this->db->join('jabatan', 'jabatan.jabatan_id = disposisi_suratkeluar.users_id', 'left'); 
            				$this->db->join('opd', 'opd.opd_id = jabatan.opd_id', 'left'); 
            				$this->db->join('eksternal_keluar', 'eksternal_keluar.id = disposisi_suratkeluar.users_id', 'left'); 
            				$this->db->where('disposisi_suratkeluar.surat_id', $h->id);
            				$kepada = $this->db->get();
							$no = 1;
            				if ($kepada->num_rows() < 2) {
								foreach ($kepada->result() as $key => $k){
								$query = $this->db->query("SELECT nama_jabatan FROM jabatan WHERE jabatan_id = '$k->atasan_id'")->result();
								if (!empty($k->nama_pd)){
								if($k->nomenklatur_pd == 'Sekda'){
									foreach($query as $key => $q){
										if(substr($q->nama_jabatan, 0,6) == 'Kepala') {
										echo "Sekretaris Daerah Kota Bogor<br>Up. ".substr($q->nama_jabatan, 7);
										} 
										elseif($q->nama_jabatan != 'Sekretaris Daerah') {
										echo "Sekretaris Daerah Kota Bogor<br>Up. ".$q->nama_jabatan;
										}
										else {
										echo "Sekretaris Daerah Kota Bogor";
										}
										++$no;
									}
								}elseif(substr($k->nomenklatur_pd, 0,3) == 'Lurah' || substr($k->nomenklatur_pd, 0,5) == 'Camat'){
									foreach($query as $key => $q){
										echo $q->nama_jabatan;
										++$no;
									}
								}elseif($k->nomenklatur_pd == 'Sekwan'){
									foreach($query as $key => $q){
										echo $q->nama_jabatan;
										++$no;
									}
								}elseif($k->nomenklatur_pd == 'Walikota'){
										echo 'Bapak '.$k->nama_pd;
										++$no;
								}elseif($k->nomenklatur_pd == 'Dirut. RSUD'){
										echo 'Direktur '.$k->nama_pd;
										++$no;
								}else{
								echo 'Kepala '.$k->nama_pd.'<br>';
								++$no;
								}
								}else{
									 echo $k->nama.'<br>';
									 ++$no;
								 }
							}
							}elseif($kepada->num_rows() < 4){
								foreach ($kepada->result() as $key => $k){
								$query = $this->db->query("SELECT nama_jabatan FROM jabatan WHERE jabatan_id = '$k->atasan_id'")->result();
								if (!empty($k->nama_pd)){
									if($k->nomenklatur_pd == 'Sekda'){
										foreach($query as $key => $q){
										if(substr($q->nama_jabatan, 0,6) == 'Kepala') {
										echo $no,'. '."Sekretaris Daerah Kota Bogor<br>Up. ".substr($q->nama_jabatan, 7).'<br>';
										} 
										elseif($q->nama_jabatan != 'Sekretaris Daerah') {
										echo $no,'. '."Sekretaris Daerah Kota Bogor<br>Up. ".$q->nama_jabatan.'<br>';
										}
										else {
										echo $no,'. '."Sekretaris Daerah Kota Bogor".'<br>';
										}
										++$no;
										}
									}elseif(substr($k->nomenklatur_pd, 0,5) == 'Lurah' || substr($k->nomenklatur_pd, 0,5) == 'Camat'){
										foreach($query as $key => $q){
											echo $no,'. ',$q->nama_jabatan.'<br>';
											++$no;
										}
									}else{
									echo $no,'. '.$k->kode_pd.'<br>';
									++$no;
									}
								}else{
									echo $no, '. ', $k->nama.'<br>';
									++$no;
								}
							}
							}elseif($kepada->num_rows() > 3){
								echo "Terlampir";
							}
            			?>
			</td>
	</tr>
</table><br>
<table class="nomor-surat" border="0">
	<tr>
		<td width="15%">Untuk</td>
		<td width="2%">:</td>
		<td width="83%"></td>
	</tr>
<?php 	$angka=1;
		foreach($isisurat as $key => $i){ ?>
		
	<tr>
		<td width="15%"><?php 
		if ($angka==1){ echo "<b>KESATU</b>"; }
		elseif($angka==2){echo "<b>KEDUA</b>";}
		elseif($angka==3){echo "<b>KETIGA</b>";}
		elseif($angka==4){echo "<b>KEEMPAT</b>";}
		elseif($angka==5){echo "<b>KELIMA</b>";}
		elseif($angka==6){echo "<b>KEENAM</b>";}
		elseif($angka==7){echo "<b>KETUJUH</b>";}
		elseif($angka==8){echo "<b>KEDELAPAN</b>";}
		elseif($angka==9){echo "<b>KESEMBILAN</b>";}
		elseif($angka==10){echo "<b>KESEPULUH</b>";}?></td>
		<td width="2%">:</td>
		<td width="83%"><?= $i->isi;?></td>
	</tr>
<?php $angka++;   }?>
	<tr>
	<td></td>
	</tr>
	<tr>
		<td width="100%">Instruksi ini mulai berlaku pada tanggal ditetapkan.</td>
	</tr>
</table>
<br><br><br><br>
<table class="isi-surat" border="0">
	<tr>
		<td width="46%"></td>
		<td width="55%">
			<?php if($h->status == NULL){?>
				<table border="0">
			<?php }elseif($h->status == 'Belum Ditandatangani' || $h->status == 'Sudah Ditandatangani'){?>
				<table border="0.5">
			<?php }?>
				<tr>
					<td width="100%">
						<table class="ttd" border="0">
						<tr>
							<td width="20%">
								<?php if($h->status == NULL){
									echo "";
								}else if($h->status == 'Belum Ditandatangani' || $h->status == 'Sudah Ditandatangani'){
								?>
								<img src="assets/img/logokbr.png" width="50px">
								<?php }?>
							</td>
							<td width="80%">
							<?php if($h->status == NULL){
								echo "";
								}elseif($h->status == 'Belum Ditandatangani' || $h->status == 'Sudah Ditandatangani'){
									echo "Ditandatangani secara elektronik oleh :";
								}
							?><br>
							<b><?php
								if ($h->jabatanpejabat == NULL) {
									echo "NAMA JABATAN";
								}else{
									echo strtoupper($h->jabatanpejabat);
								}
							?>,</b><br>
							<?php if($h->status == NULL){
								echo "DRAFT";
							}?><br>
							<u><b><?php 
								if ($h->namapejabat == NULL) {
									echo "NAMA JELAS DAN GELAR";
								}elseif ($h->nip == 196906021993032007) {
									echo "Rr. JUNIARTI ESTININGSIH S.E., M.M.";
								}else{
									echo strtoupper($tte['nama']);
								}
							?></b></u><br>
							</td>
						</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table><br><br>
<?php if(!empty($h->catatan)){ ?>
<div class="tembusan">
	<b><u>Catatan :</u></b><br>
	<?php 
		$catatan=str_ireplace('<p>','',$h->catatan);
		$catatan=str_ireplace('</p>','<br/>',$catatan); 
		
		echo $catatan 
	?>
</div>
<?php } ?>

<?php
// 	if ($kepada->num_rows() > 3) { 
?>
<!--<div class="lampiran">	-->
<!--<b>LAMPIRAN :</b><br><br>-->
<!--<table border="0">	-->
<!--	<tr>-->
<!--		<td width="10%">NOMOR</td>-->
<!--		<td width="2%">:</td>-->
<!--		<td width="85%"><?php echo $h->nomor; ?></td>-->
<!--	</tr>-->
<!--	<tr>-->
<!--		<td width="10%">TANGGAL</td>-->
<!--		<td width="2%">:</td>-->
<!--		<td width="85%"><?php echo tanggal($h->tanggal); ?></td>-->
<!--	</tr>-->
<!--	<tr>-->
<!--		<td width="10%">TENTANG</td>-->
<!--		<td width="2%">:</td>-->
<!--		<td width="85%"><?php echo $h->tentang; ?></td>-->
<!--	</tr>-->
<!--</table><br><br>-->
<?php	
// 		$no = 1;
// 		foreach ($kepada->result() as $key => $k) {
// 			if (empty($k->nama_pd)){
// 			   echo $no, '.  ', $k->nama.',<br>';
// 			   ++$no;
// 			}else{
// 			   echo $no, '. ', $k->nomenklatur_pd.',<br>';
// 			   ++$no;
// 			}
// 		}
// 	}
?>
</div>

<?php  } ?>
