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
	font-size:13px;
	font-weight: bold;
	border-collapse: collapse;
}
table.logo td.kop3{
	text-transform:capitalize;
	font-size:12px;   	
	border-collapse: collapse;
	line-height: 1.2;
}
table.logo td.kop4{
	font-size:12px;   	
	border-collapse: collapse;
}
table.surat-keterangan{
	border-spacing: 0px 5px;
	font-size:12px;
	text-align: center;
	font-weight: bold;
}
table.isi{
	border-spacing: 0px 1px;
	font-size:12px;
}
table.isi-surat{
	border-spacing: 0px 4px;
	font-size:12px;
	text-indent: 0.5in;
}
table.ttd{
	border-spacing: 0px 0px;
	font-size:10px;
	padding-top: 5px;
	padding-bottom: 5px;
	padding-left: 5px;
}
div.tembusan{ 
	font-size:11px;
	white-space: pre-line;
	text-align: justify;
}

</style>

<?php foreach ($keterangan as $key => $h) { ?>
<!-- @MpikEgov 20 Juni 2023 -->
<?php $this->load->view('exportsurat/kopsurat_conf'); ?>
<!-- <img src="assets/img/line.png" width="650px"> -->
<br>
<table class="surat-keterangan" border="0">
	<tr>
		<td>SURAT KETERANGAN</td>
	</tr>	
	<tr>
		<td>NOMOR: <?php echo $h->nomor; ?></td>
	</tr>	
</table><br><br><br>

<table class="isi" border="0">	
	<tr>
		<td width="100%" colspan="4">Yang bertandatangan di bawah ini:</td>
	</tr>
	<tr>
		<td width="4%">a.</td>
		<td width="20%">Nama</td>
		<td width="2%">:</td>
		<td width="75%">
		<?php
		if ($h->namapejabat == NULL) {
			echo "Nama Penandatangan";
		}else{
			echo $h->namapejabat;
		}
		?>		
		</td>
	</tr>
	<tr>
		<td width="4%">b.</td>
		<td width="20%">Jabatan</td>
		<td width="2%">:</td>
		<td width="75%">
		<?php
		if ($h->namajabatanpejabat == NULL) {
			echo "Jabatan Penandatangan";
		}else{
			echo $h->namajabatanpejabat;
		}
		?>
		<br></td>
	</tr>
	<tr>
		<td width="100%" colspan="4">dengan ini menerangkan bahwa:</td>
	</tr>	
	<tr>
		<td width="4%">a.</td>
		<td width="20%">Nama/NIP</td>
		<td width="2%">:</td>
		<td width="75%"><?php echo $h->namapegawai; ?> / <?php echo $h->nippegawai; ?></td>
	</tr>
	<tr>
		<td width="4%">b.</td>
		<td width="20%">Pangkat/ Golongan</td>
		<td width="2%">:</td>
		<td width="75%"><?php echo $h->pangkatpegawai; ?> / <?php echo $h->golonganpegawai; ?></td>
	</tr>
	<tr>
		<td width="4%">c.</td>
		<td width="20%">Jabatan</td>
		<td width="2%">:</td>
		<td width="75%"><?php echo $h->jabatanpegawai; ?></td>
	</tr>
	<tr>
		<td width="4%">d.</td>
		<td width="20%">Maksud</td>
		<td width="2%">:</td>
		<td width="75%"><?php echo $h->maksud; ?><br><br></td>
	</tr>	
	
</table>
<table class="isi-surat" border="0">

	<?php if (!empty($h->penutup)) { ?>		
	<tr>
		<td width="100%" colspan="4"><?php echo $h->penutup; ?></td>
		
	</tr>
	<?php } ?>
	
	<tr>
		<td width="100%" colspan="4">Demikian Surat Keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.<br></td>
	</tr>
</table>

<br><br>
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
							<?php 
								if (empty($tte['pangkat'])) {
									echo "Pangkat";
								}else{
									echo $tte['pangkat'];
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
	$this->db->order_by('tembusan_surat.tembusansurat_id', 'ASC');  //Update @mpikegov 7/06/2022 14:00 
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
				}elseif($k->nomenklatur_pd == 'Walikota' OR $k->nomenklatur_pd == 'Wakil Walikota'){
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
				}elseif($k->nomenklatur_pd == 'Walikota' OR $k->nomenklatur_pd == 'Wakil Walikota'){
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