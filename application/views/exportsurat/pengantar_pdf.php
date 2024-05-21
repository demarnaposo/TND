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

table.kepada{
	border-spacing: 0px 0px;
	font-size:10px;
	text-align: left;
}

table.surat-pengantar{
	border-spacing: 0px 5px;
	font-size:11px;
	text-align: center;
	font-weight: bold;
}

table.isi-surat{
	border-spacing: 0px 4px;
	font-size:11px;
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
table.ttd{
	border-spacing: 0px 0px;
	font-size:9px;
	padding-top: 5px;
	padding-bottom: 5px;
	padding-left: 5px;
}
div.tembusan{ 
	font-size:9px;
	white-space: pre-line;
	text-align: justify;
}

div.lampiran{ 
	font-size:10px;
	white-space: pre-line;
	text-align:left;
	page-break-before: always;
}

</style>

<?php foreach ($pengantar as $key => $h) { ?>
<?php $this->load->view('exportsurat/kopsurat_conf'); ?>
<!-- <?php if(($h->nama_pd == 'Badan Perencanaan Pembangunan, Riset dan Inovasi Daerah')) { ?>
<table class="logo" border="0">
	<tr>
		<td width="13%" rowspan="3" align="center"><img src="assets/img/logokbr.png" width="55px"></td>
		<td class="kop1" width="95%">PEMERINTAH DAERAH KOTA BOGOR</td>
	</tr>
	<tr>
		<td class="kop1" width="95%">		
			<?php echo strtoupper($h->nama_pd); ?>
		</td>
	</tr>
	<tr>
		<td class="kop3" width="95%"><?php echo $h->alamat; ?><br>
		Telp. <?php echo $h->telp; ?>, Faksimile <?php echo $h->faksimile; ?><br>
		Situs web : https://<?php echo $h->alamat_website; ?> Email : <?php echo $h->email; ?> 
		</td>
	</tr>	
</table>
<?php } else { ?>
<table class="logo" border="0">
	<tr>
		<td width="13%" rowspan="3" align="center"><img src="assets/img/logokbr.png" width="55px"></td>
		<td class="kop1" width="87%">PEMERINTAH DAERAH KOTA BOGOR</td>
	</tr>
	<tr>
		<td class="kop1">
			<?php echo strtoupper($h->nama_pd); ?>
		</td>
	</tr>
	<tr>
		<td class="kop3" ><?php echo $h->alamat; ?><br>
		Telp. <?php echo $h->telp; ?>, Faksimile <?php echo $h->faksimile; ?><br>
		Situs web : https://<?php echo $h->alamat_website; ?> Email : <?php echo $h->email; ?> 
		</td>
	</tr>	
</table>
<?php } ?>
<img src="assets/img/line.png" width="650px"><br> -->

<table class="kepada" border="0">
	<tr>
		<td width="62%"></td>
		<td width="38%" colspan="3">Bogor, <?php echo tanggal($h->tanggal) ?></td>
	</tr>
	<tr>
		<td width="69%"></td>
	</tr>	
	<tr>
		<td width="62%"></td>
		<td width="38%" colspan="3">Kepada</td>
	</tr>
	<tr>
		<td width="62%"></td>
		<td width="5%">Yth.</td>
		<td width="33%">
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
						elseif($q->nama_jabatan != 'Sekretaris Daerah Kota Bogor') {
						echo "Sekretaris Daerah Kota Bogor<br>Up. ".$q->nama_jabatan;
						}
						else {
						echo "Sekretaris Daerah Kota Bogor";
						}
						++$no;
					}
				}elseif(substr($k->nomenklatur_pd, 0,5) == 'Lurah' || substr($k->nomenklatur_pd, 0,5) == 'Camat'){
					foreach($query as $key => $q){
						echo $q->nama_jabatan;
						++$no;
					}
				}elseif($k->nomenklatur_pd == 'Sekwan'){
					foreach($query as $key => $q){
						echo $q->nama_jabatan;
						++$no;
					}
				}elseif($k->nomenklatur_pd == 'Wali Kota Bogor'){
						echo 'PJ '.$k->nama_pd; // ubah sementara ke PJ walikota 29/04/2023 @Demar
				}elseif($k->nomenklatur_pd == 'Dirut. RSUD'){
						echo 'Direktur '.$k->nama_pd;
				}elseif($k->nomenklatur_pd == 'Inspektur'){
						echo "Inspektur Daerah";
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
						elseif($q->nama_jabatan != 'Sekretaris Daerah Kota Bogor') {
						echo $no,'. '."Sekretaris Daerah Kota Bogor<br>Up. ".$q->nama_jabatan.'<br>';
						}
						else {
						echo $no,'. '."Sekretaris Daerah Kota Bogor".'<br>';
						}
						++$no;
						}
					}elseif(substr($k->nomenklatur_pd, 0,5) == 'Lurah' || substr($k->nomenklatur_pd, 0,5) == 'Camat'){
						foreach($query as $key => $q){
							echo $q->nama_jabatan;
							++$no;
						}
					}else{
					echo $no,'. Kepala '.$k->kode_pd.'<br>';
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
				<tr>
					<td width="62%"></td>
					<td width="5%"></td>
					<td width="33%"><br/><br/>di <?php
					if(!empty($k->tempat)){
					echo $k->tempat;
					}
					else{
					echo "Bogor";
					}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table><br><br>

<table class="surat-pengantar" border="0">
	<tr>
		<td>SURAT PENGANTAR</td>
	</tr>	
	<tr>
		<td>NOMOR <?php echo $h->nomor; ?></td>
	</tr>	
	
	<tr>
		<td><?php echo strtoupper($h->tentang); ?></td>
	</tr>		
</table><br><br>
<table border="1px" style="width: 100%;">
	<tr style="text-align: center;">
		<th width="5%">No</th>
		<th width="30%">Jenis Yang Dikirim</th>
		<th width="30%">Banyaknya</th>
		<th width="35%">Keterangan</th>
	</tr>
	<?php 
		$no = 1;
		foreach($sp_detail as $sp){
	?>
	<tr>
		<td style="text-align: center;"><?= $no ?></td>
		<td><?= $sp->jenis ?></td>
		<td><?= $sp->banyak ?></td>
		<td><?= $sp->keterangan ?></td>
	</tr>
	<?php $no++; } ?>
</table><br><br>
<table class="isi-surat" border="0">
	<tr>
			<td>Diterima pada tanggal :</td>
	</tr>
</table>
<br><br>
<table class="isi-surat" border="">
	<tr>
		<td width="48%">Penerima, <?php echo $h->nama_penerima ?></td>
		<td width="55%">
			<?php if($h->status == NULL){?>
				<table border="0">
			<?php }elseif($h->status == 'Belum Ditandatangani' || $h->status == 'Sudah Ditandatangani'){?>
				<table border="0.5">
			<?php }?>
				<tr>
					<td width="98%">
						<table class="ttd" border="0">
						<tr>
							<td width="20%">
								<?php if($h->status == NULL){
									echo "";
								}else if($h->status == 'Belum Ditandatangani' || $h->status == 'Sudah Ditandatangani'){
								?>
								<img src="assets/img/logokbr.png" width="45px">
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
									echo strtoupper($h->namapejabat);
								}
							?></b></u><br>
							<?php 
								if (empty($h->pangkat)) {
									echo "Pangkat";
								}else{
									echo $h->pangkat;
								}
							?>
							</td>
						</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table><br><br>
<br><br>
<table class="ttd">
    <tr>
		<td width="50%">Nomor Telepon : <?php echo $h->tlpn ?><br><br></td>
	</tr>
</table>

<!-- <?php  } ?>

<?php if (!empty($h->tembusan)) { ?>
<div class="tembusan">
<b><u>Tembusan :</u></b><br>
<?php  
	echo str_replace(",",",<br>",$h->tembusan);
 } ?>
</div> -->

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