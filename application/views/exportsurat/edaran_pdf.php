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

table.kepada{
	border-spacing: 0px 0px;
	font-size:12px;
	text-align: left;
}

table.surat-edaran{
	border-spacing: 0px 5px;
	font-size:12px;
	text-align: center;
	font-weight: bold;
}

table.isi-surat{
	border-spacing: 0px 4px;
	font-size:12px;
}

table.jadwal{
	border-spacing: 0px 1px;
	font-size:12px;
}

p { 
	font-size:12px;
	text-align: justify;
	text-justify: inter-word;	
	text-indent: 28px;
	line-height: 1.3;
}
table.ttd{
	border-spacing: 0px 0px;
	font-size:10px;
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
	font-size:12px;
	white-space: pre-line;
	text-align:left;
	page-break-before: always;
}

</style>

<?php foreach ($edaran as $key => $h) { ?>
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
	<tr>
		<td width="58%"></td>
		<td width="38%" colspan="3">Kepada :</td>
	</tr>
	<tr>
		<td width="58%"></td>
		<td width="6%">Yth.</td>
		<td width="35%">
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
									}elseif(substr($k->nomenklatur_pd, 0,5) == 'Lurah' || substr($k->nomenklatur_pd, 0,5) == 'Camat'){
									    foreach($query as $key => $q){
											echo $q->nama_jabatan;
											++$no;
										}
									}elseif($k->nomenklatur_pd == 'Wali Kota Bogor' || $k->nomenklatur_pd == 'Wakil Wali Kota Bogor'){
										echo 'Bapak '.$k->nama_pd;
										++$no;
									}elseif($k->nomenklatur_pd == 'Sekwan'){
										foreach($query as $key => $q){
											echo $q->nama_jabatan;
											++$no;
										}
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
										}elseif($k->nomenklatur_pd == 'Wali Kota Bogor' || $k->nomenklatur_pd == 'Wakil Wali Kota Bogor'){
											echo $no,'. Bapak '.$k->nama_pd;
											++$no;
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
					<!-- <//?php if(!empty($k->alamat_eksternal)){ ?>
					<tr>
						<td width="62%"></td>
						<td width="5%"></td>
						<td width="33%"><//?php echo $k->alamat_eksternal; ?></td>
					</tr>
					<//?php } ?> -->

					<tr>
						<td width="58%"></td>
						<td width="6%"></td>
						<td width="38%">di <?php
						if(!empty($k->tempat)){
						echo $k->tempat;
						}
						else{
						echo "Bogor";
						}
						?></td>
					</tr>

</table><br><br>

<table class="surat-edaran" border="0">
	<tr>
		<td>SURAT EDARAN</td>
	</tr>	
	<tr>
		<td>NOMOR <?php echo $h->nomor; ?></td>
	</tr>
	<tr>
		<td>TENTANG</td>
	</tr>	
	<tr>
		<td><?php echo strtoupper($h->tentang);
		?></td>
	</tr>		
</table><br><br>
<table class="isi-surat" border="0">
	<tr>
		<td><?php echo $h->isi; ?></td>
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

<?php
	//if ($kepada->num_rows() > 1) { ?>
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
<!--		<td width="85%"><?php echo $h->hal; ?></td>-->
<!--	</tr>-->
<!--</table><br><br>-->
<?php	
// 		$no = 1;
// 		foreach ($kepada->result() as $key => $k) {
// 			if (empty($k->nama_pd)){
// 			   echo $no, '.  ', $k->nama.',<br>';
// 			   ++$no;
// 			}else{
// 			   echo $no, '.  ', $k->nama_pd.',<br>';
// 			   ++$no;
// 			}
// 		}
// 	}
?>
</div>

<?php  } ?>