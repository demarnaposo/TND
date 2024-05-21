<style>
table.logo {
	vertical-align:middle;
	color: #000000;
	text-align: center;
}
table.logo td.kop1{	
	text-transform:capitalize;
	font-size:15px;
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
	font-size:12px;   	
	border-collapse: collapse;
	line-height: 1.2;
}
table.logo td.kop4{
	font-size:12px;   	
	border-collapse: collapse;
	
}

table.nomor-surat{
	font-size:12px;
}

table.isi-surat{
	border-spacing: 0px 0px;
	font-size:12px;
}

table.jadwal{
	border-spacing: 0px 1px;
	font-size:12px;
}
table.perjalanan{
	border-spacing: 0px 5px;
	font-size:12px;
	text-align: center;
	font-weight: bold;
}
p { 
	font-size:12px;
	text-align: justify;
	text-justify: inter-word;	
	text-indent: 28px;
	line-height: 1.3;
}
div.tembusan{ 
	font-size:11px;
	white-space: pre-line;
	text-align: justify;
}

div.terlampir{ 
	font-size:11px;
	white-space: pre-line;
	text-align:left;
	page-break-before: always;	
}
table.lampiran{
	border-spacing: 0px 0px;
	font-size:11px;
}

table.ttd{
	border-spacing: 0px 0px;
	font-size:11px;
	padding-top: 5px;
	padding-bottom: 5px;
	padding-left: 5px;
}

</style>

<?php foreach ($perjalanan as $key => $h) { ?>
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
<table class="perjalanan" border="0">
	<tr>
		<td><u>SURAT PERJALANAN DINAS (SPD)</u></td>
	</tr>
	<tr>
		<td>Nomor: <?= $h->nomor;?></td>
	</tr>
</table>
<table class="isi-surat" border="1">
	<tr>
		<td width="4%">1.</td>
		<td width="45%">Pengguna Anggaran/Kuasa Pengguna Anggaran</td>
		<td width="51%"><?= $h->kuasaanggaran;?></td>
	</tr>
	<tr>
		<td width="4%">2.</td>
		<td width="45%">Nama/NIP Pegawai yang melakukan perjalanan dinas</td>
		<td width="51%"><?= $h->namapelaksana;?>/<?= $h->nippelaksana;?></td>
	</tr>
	<tr>
		<td width="4%">3.</td>
		<td width="45%">
			a.Pangkat dan Golongan<br>
			b.Jabatan/Instansi<br>
			c.Tingkat biaya perjalanan dinas
		</td>
		<td width="51%">
			a.<?= $h->pangkatpelaksana;?><br>
			b.<?= $h->jabatanpelaksana;?><br>
			c.Rp.<?= $h->tingkat_biaya; ?>
		</td>
	</tr>
	<tr>
		<td width="4%">4.</td>
		<td width="45%">Maksud Perjalanan Dinas</td>
		<td width="51%"><?= $h->maksud_perjalanan; ?></td>
	</tr>
	<tr>
		<td width="4%">5.</td>
		<td width="45%">Alat angkutan yang dipergunakan</td>
		<td width="51%"><?= $h->alat_angkutan;?></td>
	</tr>
	<tr>
		<td width="4%">6.</td>
		<td width="45%">
			a.Tempat berangkat<br>
			b.Tempat tujuan
		</td>
		<td width="51%">
			a.<?= $h->tmpt_berangkat;?><br>
			b.<?= $h->tmpt_tujuan;?>
		</td>
	</tr>
	<tr>
		<td width="4%">7.</td>
		<td width="45%">
			a.Lamanya perjalanan dinas<br>
			b.Tanggal berangkat<br>
			c.Tanggal harus kembali/tiba di tempat baru*)
		</td>
		<td width="51%">
			a.<?= $h->lama_perjalanan;?><br>
			b.<?= tanggal($h->tgl_berangkat);?><br>
			c.<?= tanggal($h->tgl_pulang);?>
		</td>
	</tr>
	<tr>
		<td width="4%">8.</td>
		<td width="45%">Pengikut : Nama</td>
		<td width="25%">Tanggal Lahir</td>
		<td width="26%">Keterangan</td>
	</tr>
	<tr>
		<td width="4%"></td>
		<td width="45%">
			<?php $datapengikut=$this->db->query(" SELECT * FROM aparatur LEFT JOIN jabatan ON aparatur.jabatan_id=jabatan.jabatan_id WHERE aparatur.aparatur_id IN($h->pengikut_id)");
			
			if (!empty($datapengikut)) {
			
			$no=1;
			foreach($datapengikut->result() as $key => $p){ 
			?>
			<?=$no;?>.<?=$p->nama;?><br>
			<?php $no++; } } ?>
		</td>
		<td width="25%"></td>
		<td width="26%"></td>
	</tr>
	<tr>
		<td width="4%">9.</td>
		<td width="45%">
			Pembebanan Anggaran<br>
			a.Perangkat Daerah<br>
			b.Akun
		</td>
		<td width="51%">
		<br><br>a.<?= $h->namaperangkat;?><br>
			b.<?= $h->akun;?>
		</td>
	</tr>
	<tr>
		<td width="4%">10.</td>
		<td width="45%">Keterangan lain-lain</td>
		<td width="51%"><?= $h->keterangan;?></td>
	</tr>
	*)Coret yang tidak perlu
</table>

<br><br>
<table class="lampiran" border="0">
	<tr>
		<td width="5%"></td>
		<td width="19%"></td>
		<td width="15%"></td>
		<td width="15%"></td>
		<td width="45%">Dikeluarkan di : Bogor
		</td>
	</tr>
	<tr>
		<td width="5%"></td>
		<td width="19%"></td>
		<td width="15%"></td>
		<td width="15%"></td>
		<td width="45%">Tanggal : <?= tanggal($h->tanggal) ?>
		</td>
	</tr>
	<tr>
		<td width="5%"></td>
		</td>
	</tr>
	<tr>
		<td width="19%"></td>
		<td width="15%"></td>
		<td width="15%"></td>
		<td width="50%">Pengguna Anggaran / Kuasa Pengguna Anggaran
		</td>
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