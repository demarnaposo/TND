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
	text-align: justify;
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

<?php foreach ($rekomendasi as $key => $h) { ?>
<!-- @MpikEgov 20 Juni 2023 -->
<?php $this->load->view('exportsurat/kopsurat_conf'); ?>
<table class="surat-keterangan" border="0">
	<tr>
		<td>REKOMENDASI</td>
	</tr>	
	<tr>
		<td>NOMOR <?php echo $h->nomor; ?></td>
	</tr>	
	<tr>
		<td>TENTANG</td>
	</tr>	
	<tr>
		<td><?php echo $h->tentang; ?></td>
	</tr>	
</table><br><br><br>

<table class="isi" border="0">
	<tr>
		<td width="20%">Dasar</td>
		<td width="2%">:</td>
		<td width="78%"><?= $h->dasar; ?></td>
	</tr>
	<tr>
		<td width="20%">Menimbang</td>
		<td width="2%">:</td>
		<td width="78%"><?= $h->menimbang; ?></td>
	</tr>
	<tr>
		<td colspan="3"></td>
	</tr>
	<?php if ($h->id != 'REK-173') { ?>
	<tr>
		<td colspan="3">
			
			<?php if (substr($h->nama_pd, 0, 5) == 'Dinas'){ 
				echo "Kepala ".$h->nama_pd;
			}elseif(substr($h->nama_pd, 0, 5) == 'Badan'){
				echo "Kepala ".$h->nama_pd;
			}elseif(substr($h->nama_pd, 0, 5) == 'Sekre'){
				echo 'Sekretaris Daerah';
			}
			?>, 
			memberikan rekomendasi :
		</td>
	</tr>
	<?php } ?>
	<tr>
		<td colspan="3"><?= $h->isi; ?></td>
	</tr>
	<tr>
		<td width="5%"></td>
		<td width="95%">Demikian rekomendasi ini dapat dipergunakan sebagaimana mestinya</td>
	</tr>
	<br><br><br>
	<tr>
		<td width="59%" colspan="2"></td>	
		<td width="41%">Bogor, <?php echo tanggal($h->tanggal) ?></td>
	</tr>
	<tr>
		<td width="65%" colspan="2"></td>	
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