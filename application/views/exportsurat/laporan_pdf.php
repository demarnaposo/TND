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
	font-size:11px;
	text-align: left;
}

table.surat-edaran{
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

<?php foreach ($laporan as $key => $h) { ?>
<!-- @MpikEgov 20 Juni 2023 -->
<?php $this->load->view('exportsurat/kopsurat_conf'); ?>

<table class="kepada" border="0">
	<tr>
		<td width="58%"></td>
		<td width="38%" colspan="3">Bogor, <?php echo tanggal($h->tanggal) ?></td>
	</tr>
	<tr>
		<td width="65%"></td>
	</tr>
</table>

<table class="surat-edaran" border="0">
	<tr>
		<td>LAPORAN</td>
	</tr>	
	<tr>
		<td>TENTANG</td>
	</tr>	
	
	<tr>
		<td><?php echo strtoupper($h->tentang); ?></td>
	</tr>		
</table><br><br>
<table class="isi-surat" border="0">
	<tr>
		<td width="1%"></td>
		<td width="99%">I. Pendahuluan</td>
	</tr>
	<tr>
		<td width="3%"></td>
		<td width="97%">a.Umum/Latar Belakang</td>
	</tr>
	<tr>
		<td width="3%"></td>
		<td width="97%"><?= $h->latarbelakang ?></td>
	</tr>
	<tr>
		<td width="3%"></td>
		<td width="97%">b.Landasan Hukum</td>
	</tr>
	<tr>
		<td width="3%"></td>
		<td width="97%"><?= $h->landasanhukum ?></td>
	</tr>
	<tr>
		<td width="3%"></td>
		<td width="97%">c.Maksud dan Tujuan</td>
	</tr>
	<tr>
		<td width="3%"></td>
		<td width="97%"><?= $h->maksud ?></td>
	</tr>
	<tr>
		<td width="1%"></td>
		<td width="99%">II. Kegiatan yang Dilaksanakan</td>
	</tr>
	<tr>
		<td width="3%"></td>
		<td width="97%"><?= $h->kegiatan ?></td>
	</tr>
	<tr>
		<td width="1%"></td>
		<td width="99%">III. Hasil yang dicapai</td>
	</tr>
	<tr>
		<td width="3%"></td>
		<td width="97%"><?= $h->hasil ?></td>
	</tr>
	<tr>
		<td width="1%"></td>
		<td width="99%">IV. Kesimpulan dan Saran</td>
	</tr>
	<tr>
		<td width="3%"></td>
		<td width="97%"><?= $h->kesimpulan ?></td>
	</tr>
	<tr>
		<td width="1%"></td>
		<td width="99%">V. Penutup</td>
	</tr>
	<tr>
		<td width="3%"></td>
		<td width="97%"><?= $h->penutup ?></td>
	</tr>
</table>
<br><br>
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
<!-- <?php if (!empty($h->tembusan)) { ?>
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

<?php  } ?>