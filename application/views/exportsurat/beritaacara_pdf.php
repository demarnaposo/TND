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

<?php foreach ($beritaacara as $key => $h) { ?>
<!-- @MpikEgov 20 Juni 2023 -->
<?php $this->load->view('exportsurat/kopsurat_conf'); ?>
<table class="surat-keterangan" border="0">
	<tr>
		<td>BERITA ACARA</td>
	</tr>	
	<tr>
		<td>NOMOR : <?php echo $h->nomor; ?></td>
	</tr>	
</table><br><br><br>



<table class="isi" border="0">	
	<tr>
        <td width="4%"></td>
		<td width="90%">Pada hari ini tanggal <?= tanggal($h->tanggal);?> kami yang bertanda tangan dibawah ini :</td>
    </tr>	
    <tr>
        <td colspan="4"></td>
    </tr>	
</table>    
<table class="isi" border="0">	
	<tr>
		<td width="3%">1.</td>
		<td width="20%">Nama</td>
		<td width="2%">:</td>
		<td width="75%"><?php echo $h->nama1; ?></td>
	</tr>
	<tr>
		<td width="3%"></td>
		<td width="20%">Jabatan</td>
		<td width="2%">:</td>
		<td width="75%"><?php echo $h->jabatanpegawai1; ?></td>
	</tr>
	<tr>
		<td width="3%"></td>
		<td width="20%">Kedudukan</td>
		<td width="2%">:</td>
		<td width="75%"><?php echo $h->pangkat1; ?></td>
    </tr>
    <tr>   
        <td></td>
        <td colspan="3">Yang selanjutnya disebut pihak Pertama</td>
    </tr><br>
	<tr>
		<td width="3%">2.</td>
		<td width="20%">Nama</td>
		<td width="2%">:</td>
		<td width="75%"><?php echo $h->nama2; ?></td>
	</tr>
	<tr>
		<td width="3%"></td>
		<td width="20%">Jabatan</td>
		<td width="2%">:</td>
		<td width="75%"><?php echo $h->jabatanpegawai2; ?></td>
	</tr>
	<tr>
		<td width="3%"></td>
		<td width="20%">Kedudukan</td>
		<td width="2%">:</td>
		<td width="75%"><?php echo $h->pangkat2; ?></td>
    </tr>
    <tr>   
        <td></td>
        <td colspan="3">Yang selanjutnya disebut pihak Kedua</td>
    </tr>
    <tr>
        <td colspan="4"></td>
    </tr>
</table>

<table class="isi" border="0">	
	<tr>
		<td width="100%"><?php echo $h->isi; ?></td>
	</tr>		
	<tr>  
        <td width="5%"></td>                           
		<td width="95%">Demikian Berita Acara ini dibuat dengan sesungguhnya dan ditandatangani.</td>
	</tr>		
</table><br><br>


<table class="ttd" border="0">
	<tr>
		<td width="61%" colspan="2"></td>	
		<td width="39%">Ditetapkan di Bogor</td>
    </tr>	
	<tr>
		<td width="61%" colspan="2">Pihak Kedua,</td>	
		<td width="39%">Pihak Kesatu,</td>
	</tr>
	<tr>
		<td width="46%">
        <?php
			if ($h->status == NULL) {
				echo "<br><br>DRAFT<br>"; 
			}			
			else if ($h->status == 'Belum Ditandatangani') {
				echo "<br><br>DRAFT"; 
			}else{
				?><img src="assets/img/ttd_digital.png" width="53px">
			<?php }
		?>
        </td>		
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
		<td width="61%" colspan="2"><u><b><?= $h->nama2 ?></b></u>
        </td>	
		<td width="39%"><u><b><?= $h->nama1 ?></b></u>
		</td>
	</tr>	
	<tr>
		<td width="61%" colspan="2"><?= $h->pangkat2 ?>
        </td>	
		<td width="39%"><?= $h->pangkat1 ?></td>
	</tr>	
	<tr>
		<td width="61%" colspan="2">NIP. <?php echo $h->nip2; ?></td>		
		<td width="39%">NIP. <?php echo $h->nip1; ?></td>
    </tr>
    <tr>
        <td colspan="4"></td>
    </tr>
    <tr>
        <td colspan="4"></td>
    </tr>
    <tr>
        <td colspan="4"></td>
    </tr>
    <tr>
        <td colspan="4"></td>
    </tr>
</table>
<table class="ttd" border="0" align="center">
    <tr>
        <td>Mengetahui/Mengesahkan</td>
    </tr>
    <tr>
    <td>
        <?php
			if ($h->status == NULL) {
				echo "<br><br>DRAFT<br>"; 
			}			
			else if ($h->status == 'Belum Ditandatangani') {
				echo "<br><br>DRAFT"; 
			}else{
				?><img src="assets/qrcodes/<?php echo $h->id; ?>.png" width="53px">
			<?php }
		?>
        </td>
    </tr>
    <tr>
    <td><u><b><?php 
					if ($h->namapejabat == NULL) {
						echo "NAMA JELAS DAN GELAR";
					}else{
						echo strtoupper($h->namapejabat);
					}
				?></b></u>
        </td>	
    </tr>
    <tr>
    <td><?php 
				if (empty($h->pangkatpejabat)) {
				 	echo "Pangkat";
				 }else{
				 	echo $h->pangkatpejabat;
				 }
			?>
        </td>	
    </tr>
    <tr>
        <td>NIP. <?php echo $h->nippejabat; ?></td>
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