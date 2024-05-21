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
table.lampiran{
	border-spacing: 0px 0px;
	font-size:12px;
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

table.ttd{
	border-spacing: 0px 0px;
	font-size:10px;
	padding-top: 5px;
	padding-bottom: 5px;
	padding-left: 5px;
}

</style>

<?php foreach ($undangan as $key => $h) { ?>
<!-- @MpikEgov 20 Juni 2023 -->
<?php $this->load->view('exportsurat/kopsurat_conf'); ?>
<table class="nomor-surat" border="0">
	<tr>
		<td width="10%"></td>
		<td width="2%"></td>
		<td width="47%"></td>
		<td width="38%" colspan="3">Bogor, <?php echo tanggal($h->tanggal) ?></td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td width="6%"></td>
	</tr>	
	<tr>
		<td width="50%">
			<table border="0">
				<tr>
					<td width="23%">Nomor</td>
					<td width="3%">:</td>
					<td width="79%"><?php echo $h->nomor; ?></td>
				</tr>
				<tr>
					<td width="23%">Sifat</td>
					<td width="3%">:</td>
					<td width="79%"><?php echo $h->sifat; ?></td>
				</tr>
				<tr>
					<td width="23%">Lampiran</td>
					<td width="3%">:</td>
					<td width="79%"><?php echo $h->lampiran; ?></td>
				</tr>
				<tr>
					<td width="23%">Hal</td>
					<td width="3%">:</td>
					<td width="79%"><?php echo $h->hal; ?></td>
				</tr>
			</table>
		</td>
		<td width="50%">
			<table border="0">
				<tr>
					<td width="20%"></td>
					<td width="20%">Kepada</td>
					<td width="3%"></td>
					<td width="54%"></td>
				</tr>
				<tr>
					<td width="20%"></td>
					<td width="11%">Yth.</td>
					<td width="69%">
						<?php
						if($h->id == 'SU-5835'){
							echo "1. Inspektur Daerah<br>2. Kepala BKAD <br>3. Kepala BAPENDA";
						}else{
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
									}elseif($k->nomenklatur_pd == 'Wali Kota Bogor' || $k->nomenklatur_pd == 'Wakil Wali Kota Bogor'){
											echo 'Bapak PJ '.$k->nama_pd; //Penambahan PJ sementara @Demar 03/05/2024
											++$no;
									}elseif($k->kode_pd == 'RSUD'){
											echo 'Direktur '.$k->nama_pd;
											++$no;
									}elseif($k->nomenklatur_pd == 'Inspektur'){
											echo 'Inspektur Daerah';
									}else{
									echo 'Kepala '.$k->nama_pd.'<br>';
									++$no;
									}
									}else{
										 echo $k->nama.'<br>';
										//echo 'a';
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

										}elseif($k->nomenklatur_pd == 'Wali Kota Bogor' || $k->nomenklatur_pd == 'Wakil Wali Kota Bogor'){
											echo $no,'. Bapak '.$k->kode_pd.'<br>';
											++$no;
										}elseif($k->kode_pd == 'RSUD'){
											echo $no,'. Direktur '.$k->kode_pd.'<br>';
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
						}
							?>
							</td>
					</tr>
				
					<!-- Untuk menampilkan alamat eksternal -->
					<!-- <//?php if(!empty($k->alamat_eksternal)){ ?> -->
					            <!-- <tr>
						            <td width="24%"></td>
						            <td width="8%"></td>
						            <td width="69%"><//?php echo $k->alamat_eksternal; ?></td>
					            </tr> -->
					<!-- <//?php } ?> -->

					<tr>
						<td width="20%"></td>
						<td width="11%"></td>
						<td width="69%">di <?php
						if($h->id == 'SU-7371' || $h->id == 'SU-11817' || $h->id == 'SU-12042'){
						echo "Tempat";
						}elseif(!empty($k->tempat)){
						$this->db->from('disposisi_suratkeluar');
            			$this->db->join('jabatan', 'jabatan.jabatan_id = disposisi_suratkeluar.users_id', 'left'); 
            			$this->db->join('opd', 'opd.opd_id = jabatan.opd_id', 'left'); 
            			$this->db->join('eksternal_keluar', 'eksternal_keluar.id = disposisi_suratkeluar.users_id', 'left'); 
            			$this->db->where('disposisi_suratkeluar.surat_id', $h->id);
            			$this->db->where('disposisi_suratkeluar.users_id LIKE', '%EKS%');
            			$tempat = $this->db->get();
            			    if($tempat->num_rows() > 1){
            			    echo "Tempat";
            			    }
            			    else{
						    echo $k->tempat;
            			    }
						}
						else{
						echo "Bogor";
						}
						?></td>
					</tr>
			</table>
		</td>
	</tr>
</table><br><br>
<table class="isi-surat" border="0">
	<tr>
		<td width="12%"></td>
		<td width="87%" colspan="4"><?php echo $h->p1; ?></td>
	</tr>
</table>
<table class="jadwal" border="0">	
	<tr>
		<td width="12%"></td>
		<td width="5%"></td>
		<td width="10%">Hari</td>
		<td width="2%">:</td>
		<td width="70%">
		<?php
		if(!empty($h->hari)){
			echo $h->hari;
		}else{
			$tanggal = $h->tgl_acara;
			$day = date('D', strtotime($tanggal));
			$dayList = array(
				'Sun' => 'Minggu',
				'Mon' => 'Senin',
				'Tue' => 'Selasa',
				'Wed' => 'Rabu',
				'Thu' => 'Kamis',
				'Fri' => 'Jumat',
				'Sat' => 'Sabtu'
			);
			if($h->id == 'SU-225'){
				echo "Senin - Jumat";
			}else{
				echo $dayList[$day]; }
		}?>
		
		</td>
	</tr>
	<tr>
		<td width="12%"></td>
		<td width="5%"></td>
		<td width="10%">Tanggal</td>
		<td width="2%">:</td>
		<td width="70%"><?php echo $h->tgl_acara;?></td>
	</tr>
	<tr>
		<td width="12%"></td>
		<td width="5%"></td>
		<td width="10%">Pukul</td>
		<td width="2%">:</td>
		<td width="70%"><?php echo $h->pukul; ?></td>
	</tr>
	<tr>
		<td width="12%"></td>
		<td width="5%"></td>
		<td width="10%">Tempat</td>
		<td width="2%">:</td>
		<td width="70%"><?php 
		echo $h->tempat;
		?></td>
	</tr>
	<tr>
		<td width="12%"></td>
		<td width="5%"></td>
		<td width="10%">Acara</td>
		<td width="2%">:</td>
		<td width="70%"><?php echo $h->acara; ?></td>
	</tr>	
</table>
<table class="isi-surat" border="0">		
	<tr>
		<td width="12%"></td>
		<td width="87%" colspan="4"><?php echo $h->p2; ?></td>
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
	// $this->db->order_by('opd.opd_id', 'DESC');
	$this->db->order_by('tembusan_surat.tembusansurat_id', 'ASC'); //perbaikan query @dam -->07/06/2022--<  
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
// 			   echo $no, '.  ', $k->nomenklatur_pd.',<br>';
// 			   ++$no;
// 			}
// 		}
// 	}
?>
</div>

<?php  } ?>