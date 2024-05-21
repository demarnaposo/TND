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
	font-size:9px;
	padding-top: 5px;
	padding-bottom: 5px;
	padding-left: 5px;
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

<?php foreach ($notulen as $key => $h) { ?>
	<table class="logo" border="0">
	<tr>
		<td width="13%" rowspan="3" align="center"><img src="assets/img/logokbr.png" width="55px"></td>
		<td class="kop1" width="87%">PEMERINTAH DAERAH KOTA BOGOR</td>
	</tr>
	<tr>
		<td class="kop1"><?php echo strtoupper($h->nama_pd); ?></td>
	</tr>
	<tr>
		<td class="kop3" ><?php echo $h->alamat; ?><br>
		Telp. <?php echo $h->telp; ?>, Faksimile <?php echo $h->faksimile; ?><br>
		Situs web : https://<?php echo $h->alamat_website; ?> Email : <?php echo $h->email; ?> 
		</td>
	</tr>	
</table>
<img src="assets/img/line.png" width="650px"><br>
<table class="surat-keterangan" border="0">
	<tr>
		<td>NOTULEN</td>
	</tr>	
</table><br><br>
<table class="isi" border="0">
	<tr>
		<td width="20%">Sidang/Rapat</td>
		<td width="2%">:</td>
		<td width="72%"><?= $h->rapat;?></td>
	</tr>
	<tr>
		<td width="20%">Hari/Tanggal</td>
		<td width="2%">:</td>
		<td width="72%"><?= tanggal($h->tanggal);?></td>
	</tr>
	<tr>
		<td width="20%">Waktu Panggilan</td>
		<td width="2%">:</td>
		<td width="72%"><?= $h->waktu_pgl;?></td>
	</tr>
	<tr>
		<td width="20%">Waktu Sidang/Rapat</td>
		<td width="2%">:</td>
		<td width="72%"><?= $h->wakturapat;?></td>
	</tr>
	<tr>
		<td width="20%">Acara</td>
		<td width="2%">:</td>
		<td width="72%"><?= $h->acara; ?></td>
	</tr>
	<tr>
		<td colspan="3">Pimpinan Sidang/Rapat</td>
	</tr>
	<tr>
		<td width="20%">Ketua</td>
		<td width="2%">:</td>
		<td width="72%"><?= $h->namaketua;?></td>
	</tr>
	<tr>
		<td width="20%">Sekertaris</td>
		<td width="2%">:</td>
		<td width="72%"><?= $h->namasekertaris;?></td>
	</tr>
	<tr>
		<td width="20%">Pencatat</td>
		<td width="2%">:</td>
		<td width="72%"><?= $h->namapencatat;?></td>
	</tr>
	<?php
	$datapeserta=$this->db->query("SELECT * FROM aparatur LEFT JOIN jabatan ON aparatur.jabatan_id=jabatan.jabatan_id WHERE aparatur.aparatur_id IN ($h->peserta_id)");?>
	<tr>
		<td width="20%">Peserta Sidang/Rapat</td>
		<td width="2%">:</td>
		<td width="72%">
		<?php
		$no=1;
		foreach($datapeserta->result() as $key => $p){
		?>
			<?= $no;?>. <?= $p->nama;?><br>
		<?php $no++;} ?>
		</td>
	</tr>
	<tr>
		<td width="20%">Kegiatan Sidang/Rapat</td>
		<td width="2%">:</td>
		<td width="72%"><?= $h->kegiatan_rapat;?></td>
	</tr>
	<tr>
		<td width="20%">1. Kata Pembukaan</td>
		<td width="2%">:</td>
		<td width="72%"><?= $h->pembukaan;?></td>
	</tr>
	<tr>
		<td width="20%">2. Pembahasan</td>
		<td width="2%">:</td>
		<td width="72%"><?= $h->pembahasan;?></td>
	</tr>
	<tr>
		<td width="20%">3. Peraturan</td>
		<td width="2%">:</td>
		<td width="72%"><?= $h->peraturan;?></td>
	</tr>
</table>
<table class="isi-surat" border="0">
	<tr>
		<td width="48%"></td>
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
							<td width="200%">
							<?php if($h->status == NULL){
								echo "";
								}elseif($h->status == 'Belum Ditandatangani' || $h->status == 'Sudah Ditandatangani'){
									echo "Ditandatangani secara elektronik oleh :";
								}
							?><br>
							<b><?php
								if ($h->namajabatanpejabat == NULL) {
									echo "NAMA JABATAN";
								}else{
									echo strtoupper($h->namajabatanpejabat);
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
								if (empty($h->pangkatpejabat)) {
									echo "Pangkat";
								}else{
									echo $h->pangkatpejabat;
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
</table><br><br><br>

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

<?php  } ?>