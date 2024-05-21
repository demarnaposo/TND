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
	font-size:12px;
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
	text-align: justify-left;
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

<?php foreach ($kuasa as $key => $h) { ?>
<!-- @MpikEgov 20 Juni 2023 -->
<?php $this->load->view('exportsurat/kopsurat_conf'); ?>
<table class="surat-keterangan" border="0">
	<tr>
		<td>SURAT KUASA</td>
	</tr>	
	<tr>
		<td>NOMOR <?php echo $h->nomor; ?></td>
	</tr>	
</table><br><br><br>



<table class="isi" border="0">	
	<tr>
		<td width="100%">Yang bertandatangan dibawah ini:</td>
	</tr>	
  <tr></tr>
  <tr></tr>
	<tr>
		<td width="20%">Nama Jabatan</td>
		<td width="2%">:</td>
		<?php if($h->namapejabat == NULL){ ?>
		<td width="78%">NAMA PENANDATANGAN</td>
		<?php }else{?>
		<td width="78%"><?= $h->namapejabat;?></td>
		<?php } ?>
	</tr>
  <tr></tr>
  <tr></tr>
	<tr>
		<td width="20%">Tempat Kedudukan</td>
		<td width="2%">:</td>
		<?php if($h->jabatanpejabat == NULL){ ?>
		<td width="78%">KEDUDUKAN PENANDATANGAN</td>
		<?php }else{?>
		<td width="78%"><?= $h->jabatanpejabat;?></td>
		<?php } ?>
	</tr>	
</table><br><br>
<table class="surat-keterangan" border="0">
	<tr>
		<td>MEMBERIKAN KUASA</td>
	</tr>	
</table><br>
	<?php
		$datapegawai =	$this->db->query("
		SELECT * FROM aparatur
		LEFT JOIN jabatan ON aparatur.jabatan_id = jabatan.jabatan_id
		WHERE aparatur.aparatur_id IN ($h->pegawai_id)");//->result_array();
		
		if ($datapegawai->num_rows() > 3) {
		?>
<table class="isi" border="0">	
	<tr>
		<td width="20%" colspan="4">Kepada</td>
		<td width="2%">:</td>
		<td width="78%">Terlampir</td>		
	</tr>	
</table><br><br>
<?php
	}else {
?>	
<table class="isi" border="0">	
	<tr>
		<td width="100%" colspan="4">Kepada:</td>
	</tr>
	<tr></tr>
	<tr></tr>
	<?php	
	$no = 1;
	foreach ($datapegawai->result() as $key => $p) {
	?>	
	<tr>
		<td width="3%">a.</td>
		<td width="20%">Nama</td>
		<td width="2%">:</td>
		<td width="75%"><?php echo $p->nama; ?></td>
	</tr>
  <tr></tr>
	<tr></tr>
	<tr>
		<td width="3%">b.</td>
		<td width="20%">Jabatan</td>
		<td width="2%">:</td>
		<td width="75%"><?php echo $p->nama_jabatan; ?></td>
	</tr>
  <tr></tr>
	<tr></tr>
	<tr>
		<td width="3%">c.</td>
		<td width="20%">NIP</td>
		<td width="2%">:</td>
		<td width="75%"><?php echo $p->nip; ?></td>
	</tr>
  <tr></tr>
	<tr></tr>
	<tr>
		<td width="3%">d.</td>
		<td width="20%">Untuk</td>
		<td width="2%">:</td>
		<td width="75%"><?= $h->untuk;?></td>
	</tr>
  
  <tr></tr>
  <tr></tr>
  <tr></tr>
  <tr>
    <td width="3%"></td>
    <td width="97%" colspan="4"><p>&nbsp;&nbsp;&nbsp;Demikian Surat Kuasa/Surat Kuasa Khusus ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p></td>
  </tr>

<?php $no++;  } ?>

</table><br><br><br><br>

<?php } ?> 
<?php
    $penerima =	$this->db->query("
		SELECT * FROM aparatur
		LEFT JOIN jabatan ON aparatur.jabatan_id = jabatan.jabatan_id
		WHERE aparatur.aparatur_id IN ($h->pegawai_id)")->row_array();
?>
<table class="ttd" border="0">
	<tr>
		<td width="61%" colspan="2"></td>	
		<td width="39%">Tempat di Bogor</td>
	</tr>	<tr>
		<td width="61%" colspan="2"></td>	
		<td width="39%">Pada Tanggal <?php echo tanggal($h->tanggal) ?></td>
	</tr>
	<tr>
		<td width="75%" colspan="2"></td>	
	</tr>	
	<tr>
		<td width="61%">Yang Diberi Kuasa</td>
		<td width="39%">Yang Memberi Kuasa</td>
	</tr>
	<tr>
		<td width="61%" colspan="2"><b><?= strtoupper($penerima['nama_jabatan']) ?></b></td>	
		<td width="39%"><b><?php
					if ($h->jabatanpejabat == NULL) {
						echo "NAMA JABATAN";
					}else{
						echo strtoupper($h->jabatanpejabat);
					}
				?>,</b></td>
	</tr>
	<tr>
		<td width="46%"><?php
			if ($h->status == NULL) {
				echo "<br><br>DRAFT<br>"; 
			}			 
			else if ($h->status == 'Belum Ditandatangani') {
				echo "<br><br>BELUM DITANDATANGANI<br>";
			}else{
				?><img src="assets/img/ttd_digital.png" width="53px">
			<?php }
		?>			</td>		
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
		<td width="61%" colspan="2"><u><b><?= strtoupper($penerima['nama']) ?></b></u></td>	
		<td width="39%"><u><b><?php 
					if ($h->namapejabat == NULL) {
						echo "NAMA JELAS DAN GELAR";
					}else{
						echo strtoupper($h->namapejabat);
					}
				?></b></u>
		</td>
	</tr>	
	<tr>
		<td width="61%" colspan="2"><?= $penerima['pangkat'] ?></td>	
		<td width="39%"><?php 
				if (empty($h->pangkatpejabat)) {
				 	echo "Pangkat";
				 }else{
				 	echo $h->pangkatpejabat;
				 }
			?></td>
	</tr>	
	<tr>
		<td width="61%" colspan="2">NIP. <?php echo $penerima['nip']; ?></td>		
		<td width="39%">NIP. <?php echo $h->nippejabat; ?></td>
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
    $this->db->from('tembusan_surat');
    $this->db->join('jabatan', 'jabatan.jabatan_id = tembusan_surat.users_id', 'left'); 
    $this->db->join('opd', 'opd.opd_id = jabatan.opd_id', 'left'); 
    $this->db->join('tembusan_keluar', 'tembusan_keluar.id = tembusan_surat.users_id', 'left'); 
    $this->db->where('tembusan_surat.surat_id', $h->id);
	$this->db->order_by('tembusan_surat.tembusansurat_id', 'ASC');  
	$tembusan = $this->db->get();
	$no = 1;
	
	if($tembusan->num_rows() > 0){ ?>
		<div class="tembusan">
		<b><u>Tembusan :</u></b><br>

		<?php
		}
		foreach ($tembusan->result() as $key => $k){
		$query = $this->db->query("SELECT nama_jabatan FROM jabatan WHERE jabatan_id = '$k->atasan_id'")->result();
		if ($tembusan->num_rows() == 1) {
			if (!empty($k->nama_pd)){ 

				if($k->nomenklatur_pd == 'Sekda'){
					foreach($query as $key => $q){
						echo $q->nama_jabatan;
					}
				}elseif($k->nomenklatur_pd == 'Wali Kota Bogor' OR $k->nomenklatur_pd == 'Wakil Wali Kota Bogor'){
					foreach($query as $key => $q){
						echo 'Bapak '.$q->nama_jabatan;
					}
					
				}else{
				foreach($query as $key => $q){
						echo $q->nama_jabatan;
					}
				}
			}else{
				echo $k->nama_tembusan;
			}
		} 
		elseif ($tembusan->num_rows() > 1) {
			if (!empty($k->nama_pd)){ 

				if($k->nomenklatur_pd == 'Sekda'){
					foreach($query as $key => $q){
						echo $no,'. ',$q->nama_jabatan.'<br>';
						++$no;
					}
				}elseif($k->nomenklatur_pd == 'Wali Kota Bogor' OR $k->nomenklatur_pd == 'Wakil Wali Kota Bogor'){
					foreach($query as $key => $q){
						echo $no,'. ', 'Bapak '.$q->nama_jabatan.'<br>';
						++$no;
					}
					
				}else{
				foreach($query as $key => $q){
						echo $no,'. '.$q->nama_jabatan.'<br>';
						++$no;
					}
				}
			}else{
				echo $no, '. ', $k->nama_tembusan.'<br>';
				++$no;
			}
		}
		
	}
		?>

    </div>

<?php
	if ($datapegawai->num_rows() > 3) { ?>
	<div class="terlampir">	
<b>LAMPIRAN SURAT kuasa:</b><br><br>
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
		<td width="73%"><?php echo $p->nama_jabatan; ?></td>
	</tr>

<?php $no++;  } ?>

</table><br><br>

<?php } ?> 
</div>



<?php  } ?>