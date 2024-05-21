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

table.nomor-surat{
	border-spacing: 0px 0px;
	font-size:12px;
}

table.isi-surat{
	border-spacing: 0px 4px;
	font-size:12px;
}

table.jadwal{
	border-spacing: 0px 4px;
	font-size:12px;
}

p { 
	font-size:12px;
	text-align: justify;
	text-justify: inter-word;	
	text-indent: 28px;
	line-height: 1.3;
}
table.lampiran{
	border-spacing: 0px 0px;
	font-size:12px;
}
div.tembusan{ 
	font-size:10px;
	white-space: pre-line;
	text-align: justify;
}

div.lampiran{ 
	font-size:12px;
	white-space: pre-line;
	text-align:left;
	page-break-before: always;	
}

table.ttd{
	border-spacing: 0px 0px;
	font-size:10px;
	padding-top: 5px;
	padding-bottom: 5px;
	padding-left: 5px;
}

</style>

<?php foreach ($panggilan as $key => $h) { ?>
<!-- @MpikEgov 20 Juni 2023 -->
<?php $this->load->view('exportsurat/kopsurat_conf'); ?>
<table class="nomor-surat" border="0">
	<tr>
		<td width="10%"></td>
		<td width="2%"></td>
		<td width="50%"></td>
		<td width="38%" colspan="3">Bogor, <?php echo tanggal($h->tanggal) ?></td>
	</tr>
	<tr>
		<td></td>
	</tr>	
	<tr>
		<td width="50%">
			<table border="0">
				<tr>
					<td width="21%">Nomor</td>
					<td width="3%">:</td>
					<td width="79%"><?php echo $h->nomor; ?></td>
				</tr>
				<tr>
					<td width="21%">Sifat</td>
					<td width="3%">:</td>
					<td width="79%"><?php echo $h->sifat; ?></td>
				</tr>
				<tr>
					<td width="21%">Lampiran</td>
					<td width="3%">:</td>
					<td width="79%"><?php echo $h->lampiran; ?></td>
				</tr>
				<tr>
					<td width="21%">Hal</td>
					<td width="3%">:</td>
					<td width="79%"><?php echo $h->hal; ?></td>
				</tr>
			</table>
		</td>
		<td width="50%">
			<table border="0">
				<tr>
					<td width="24%"></td>
					<td width="20%">Kepada</td>
					<td width="3%"></td>
					<td width="54%"></td>
				</tr>
				<tr>
					<td width="24%"></td>
					<td width="9%">Yth.</td>
					<td width="69%">
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
    											echo $no,'. ',$q->nama_jabatan.'<br>';
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
					<td width="24%"></td>
					<td width="9%"></td>
					<td width="69%">di <?php
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
<table class="isi-surat" border="0">
	<tr>
		<td width="12%"></td>
		<td width="88%"><p>Dengan ini diminta kedatangan Saudara di Kantor <?php echo $h->kantor; ?> pada:</p></td>
	</tr>
</table>
<table class="jadwal" border="0">	
	<tr>
		<td width="12%"></td>
		<td width="20%">Hari</td>
		<td width="2%">:</td>
		<td width="71%"><?php echo hari($h->tgl_acara); ?></td>
	</tr>
	<tr>
		<td width="12%"></td>
		<td width="20%">Tanggal</td>
		<td width="2%">:</td>
		<td width="71%"><?php echo tanggal($h->tgl_acara); ?></td>
	</tr>
	<tr>
		<td width="12%"></td>
		<td width="20%">Pukul</td>
		<td width="2%">:</td>
		<td width="71%"><?php echo $h->pukul; ?></td>
	</tr>
	<tr>
		<td width="12%"></td>
		<td width="20%">Tempat</td>
		<td width="2%">:</td>
		<td width="71%"><?php echo $h->tempat; ?></td>
	</tr>
	<tr>
		<td width="12%"></td>
		<td width="20%">Menghadap Kepada</td>
		<td width="2%">:</td>
		<td width="71%"><?php echo $h->namajabatanb; ?></td>
	</tr>	
	<tr>
		<td width="12%"></td>
		<td width="20%">Alamat</td>
		<td width="2%">:</td>
		<td width="71%"><?php echo $h->alamat; ?></td>
	</tr>
	<tr>
		<td width="12%"></td>
		<td width="20%">Untuk</td>
		<td width="2%">:</td>
		<td width="71%"><?php echo $h->untuk; ?></td>
	</tr>	
</table>
<table class="isi-surat" border="0">		
	<tr>
		<td width="12%"></td>
		<td width="88%"><p>Demikian  untuk  dilaksanakan  dan  menjadi  perhatian sepenuhnya.</p></td>
	</tr>
</table><br><br>
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

<?php  } ?>