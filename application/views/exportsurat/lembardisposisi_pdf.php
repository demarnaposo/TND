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
	font-size:13px;   	
	border-collapse: collapse;
	line-height: 1.2;
}
table.logo td.kop4{
	font-size:2px;   	
	border-collapse: collapse;
}

</style>
<?php foreach ($disposisi as $key => $h) { 
//Start Lembar Disposisi Wali Kota Editor : Muhamad Idham (21 Februari 2022)
if($this->session->userdata('jabatan_id') == 1 || $this->session->userdata('jabatan_id') == 2) {
?>

<table class="logo" border="0">
	<tr>
		<td align="center"><img src="assets/img/logogarudaemas.png" width="70px"></td>
	</tr>
	<tr>
		<td class="kop2" align="center">WALI KOTA BOGOR</td>
	</tr>	
	</tr>	
</table>
<p style="text-align: center; font-weight: bold;">LEMBAR DISPOSISI WALI KOTA</p> 
<?php }elseif($this->session->userdata('jabatan_id') == 5){ ?>
<table class="logo" border="0">
	<tr>
		<td width="13%" rowspan="3" align="center"><img src="assets/img/logokbr.png" width="55px"></td>
		<td class="kop1" width="87%">PEMERINTAH DAERAH KOTA BOGOR</td>
	</tr>
	<tr>
		<td class="kop1">SEKRETARIAT DAERAH KOTA BOGOR</td>
	</tr>
	<tr>
		<td class="kop3" ><?php echo $h->alamat; ?><br>
		Telp. <?php echo $h->telp; ?> Faksimile <?php echo $h->faksimile; ?><br>
		www.<?php echo $h->alamat_website; ?>
		</td>
	</tr>	
</table>
<img src="assets/img/line.png" width="650px"><br>
<p style="text-align: center; font-weight: bold;">LEMBAR DISPOSISI</p> 
<?php }else{ ?>
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
		Telp. <?php echo $h->telp; ?> Faksimile <?php echo $h->faksimile; ?><br>
		www.<?php echo $h->alamat_website; ?>
		</td>
	</tr>	
</table>
<img src="assets/img/line.png" width="650px"><br>
<p style="text-align: center; font-weight: bold;">LEMBAR DISPOSISI</p> 
<?php }
//End Lembar Disposisi Wali Kota Editor : Muhamad Idham (21 Februari 2022) ?>

<hr>

<table width="100%" border="0">
	<tr>
		<td></td>
	</tr>
	<tr>
		<td width="60px">Surat dari</td>
		<td width="5px">:</td>
		<td width="190px"><?php echo $h->dari; ?></td>
		<td width="100px">Diterima Tgl.</td>
		<td width="5px">:</td>
		<td width="180px">
		<?php echo tanggal($h->diterima); ?>
		</td>
	</tr>
	<tr>
		<td>No. Surat</td>
		<td>:</td>
		<td><?php echo $h->nomor; ?></td>
		<td>  No. Kode Surat</td>
		<td>:</td>
		<td><?php echo $h->kode; ?></td>
	</tr>
	<tr>
		<td>Tgl. Surat</td>
		<td>:</td>
		<td><?php echo tanggal($h->tanggal); ?></td>
		<td>  Sifat</td>
		<td>:</td>
		<td></td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td colspan="3"><input type="checkbox" name="sifat" value="Sangat Segera" /> Sangat Segera <input type="checkbox" name="sifat" value="Segera" /> Segera <input type="checkbox" name="sifat" value="Rahasia" /> Rahasia</td>
	</tr>
</table>

<br><hr>

<table width="100%">
	<tr>
		<td></td>
	</tr>
	<tr>
		<td width="60px">Hal</td>
		<td width="5px">:</td>
		<td><?php echo $h->hal; ?></td>
	</tr>
</table>

<br><hr>
<table width="100%" border="0">
	<tr>
		<td></td>
	</tr>
	<tr>
		<td width="269px">Diteruskan kepada :</td>
		<td width="269px">Dengan hormat harap :</td>
	</tr>
	<tr>
		<td></td>
	</tr>
	<?php
    if($this->session->userdata('jabatan_id')==5 || $this->session->userdata('jabatan_id')==1473 || $this->session->userdata('jabatan_id')== 1){
        $qdinas = $this->db->query("
		SELECT aparatur.nama,opd.nama_pd,disposisi_suratmasuk.harap, opd.opd_induk,jabatan.nama_jabatan,disposisi_suratmasuk.tanggal,disposisi_suratmasuk.keterangan,disposisi_suratmasuk.users_id,disposisi_suratmasuk.status, disposisi_suratmasuk.waktudisposisi FROM disposisi_suratmasuk
         LEFT JOIN aparatur ON aparatur.jabatan_id = disposisi_suratmasuk.aparatur_id
         LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
         LEFT JOIN opd ON opd.opd_id=jabatan.opd_id
         LEFT JOIN users ON users.aparatur_id = aparatur.aparatur_id
         WHERE disposisi_suratmasuk.suratmasuk_id ='$h->suratmasuk_id' AND disposisi_suratmasuk.status !='Riwayat' AND opd.opd_id NOT IN (2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19) AND aparatur.statusaparatur='Aktif' GROUP BY opd.opd_id ORDER BY disposisi_suratmasuk.dsuratmasuk_id ASC
	")->result(); 
       $disposisi = $this->db->query("
		SELECT aparatur.nama,disposisi_suratmasuk.harap, opd.opd_induk,jabatan.nama_jabatan,disposisi_suratmasuk.tanggal,disposisi_suratmasuk.keterangan,disposisi_suratmasuk.users_id,disposisi_suratmasuk.status, disposisi_suratmasuk.waktudisposisi FROM disposisi_suratmasuk
         LEFT JOIN aparatur ON aparatur.jabatan_id = disposisi_suratmasuk.aparatur_id
         LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
         LEFT JOIN opd ON opd.opd_id=jabatan.opd_id
         LEFT JOIN users ON users.aparatur_id = aparatur.aparatur_id
         LEFT JOIN levelbaru ON levelbaru.level_id=aparatur.level_id
         WHERE disposisi_suratmasuk.suratmasuk_id = '$h->suratmasuk_id' AND disposisi_suratmasuk.status !='Riwayat' AND users.level_id != 4 AND users.level_id != 18 AND opd.opd_id IN (2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19) AND aparatur.statusaparatur='Aktif' GROUP BY disposisi_suratmasuk.aparatur_id ORDER BY levelbaru.level_id ASC
	")->result(); 
	$nomor = 1;
		foreach ($disposisi as $key => $d) { 
    ?>
    <tr>
		<td width="17px"><?php echo $nomor; ?>.</td>
		<td width="240px"><b><?php echo $d->nama; ?></b><br><?php echo $d->nama_jabatan; ?></td>
		<td width="241px"><?php echo $d->harap; ?></td>
	</tr>
    <?php
    $nomor++;
		}
		foreach ($qdinas as $key => $qd) { 
	?>
	<tr>
		<td width="17px"><?php echo $nomor; ?>.</td>
		<td width="241px"><b><?php echo $qd->nama_pd; ?></b></td>
		<td width="241px"><?php echo $qd->harap; ?></td>
	</tr>
	<?php
	    $nomor++;
		}
    }else{
        $opdid=$this->session->userdata('opd_id');
     	$disposisi1 = $this->db->query("
		SELECT * FROM disposisi_suratmasuk
		LEFT JOIN aparatur ON aparatur.jabatan_id = disposisi_suratmasuk.aparatur_id
		LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
		LEFT JOIN users ON users.aparatur_id = aparatur.aparatur_id
		WHERE disposisi_suratmasuk.suratmasuk_id = '$h->suratmasuk_id' AND disposisi_suratmasuk.status !='Riwayat' AND users.level_id != 4 AND users.level_id != 18 AND aparatur.statusaparatur='Aktif' GROUP BY disposisi_suratmasuk.aparatur_id ORDER BY disposisi_suratmasuk.dsuratmasuk_id ASC
	")->result();   
    ?>
	<?php 
		$no = 1;
		foreach ($disposisi1 as $key => $d) { 
	?>
	<tr>
		<td width="17px"><?php echo $no; ?>.</td>
		<td width="269px"><b><?php if (empty($d->nama_penerima)) {
												echo $d->nama;
											} else {
												echo $d->nama; //edited Waldemar Naposo
											} ?></b><br><?php echo $d->nama_jabatan; ?></td>
		<td width="269px"><?php echo $d->harap; ?></td>
	</tr>
	<?php 
		$no++;
		} 
    }
	?>
</table>

<br><hr>
	<?php 
    if($this->session->userdata('jabatan_id')==5 || $this->session->userdata('jabatan_id')==1473){
        $catatan=$this->db->query("SELECT * FROM disposisi_suratmasuk
		LEFT JOIN aparatur ON aparatur.jabatan_id = disposisi_suratmasuk.aparatur_id
		LEFT JOIN jabatan ON jabatan.jabatan_id = aparatur.jabatan_id
		LEFT JOIN users ON users.aparatur_id = aparatur.aparatur_id
		LEFT JOIN opd ON opd.opd_id=jabatan.opd_id
		WHERE disposisi_suratmasuk.suratmasuk_id = '$h->suratmasuk_id' AND disposisi_suratmasuk.status !='Riwayat' AND users.level_id != 4 AND opd.opd_induk=4 AND users.level_id != 18 AND aparatur.statusaparatur='Aktif' GROUP BY disposisi_suratmasuk.aparatur_id ORDER BY disposisi_suratmasuk.dsuratmasuk_id ASC")->result();
    }else{
        $catatan=$this->db->query("SELECT * FROM disposisi_suratmasuk
		LEFT JOIN aparatur ON aparatur.jabatan_id = disposisi_suratmasuk.aparatur_id
		LEFT JOIN jabatan ON jabatan.jabatan_id = aparatur.jabatan_id
		LEFT JOIN users ON users.aparatur_id = aparatur.aparatur_id
		WHERE disposisi_suratmasuk.suratmasuk_id = '$h->suratmasuk_id' AND disposisi_suratmasuk.status !='Riwayat' AND users.level_id != 4 AND users.level_id != 18 AND aparatur.statusaparatur='Aktif' GROUP BY disposisi_suratmasuk.aparatur_id ORDER BY disposisi_suratmasuk.dsuratmasuk_id ASC")->result();
    }
    ?>
<table border="0">
	<tr>
		<td colspan="2">Catatan :</td>
	</tr>
	<?php
	    foreach($catatan as $key => $d){
	        if(!empty($d->keterangan)){
	?>
	<tr>
	    <td width="5%">-</td>
	    <td width="90%">
	            <?= $d->nama ?> (<?= $d->nama_jabatan ?>) : <u><?= $d->keterangan; ?></u>
	    </td>
	</tr>
	<?php }elseif(!empty($d->catatan)){ ?>
	    <tr>
	        <td width="5%">-</td>
	        <td width="90%">
	            <?= $d->nama ?> (<?= $d->nama_jabatan ?>) : <u><?= $d->catatan; ?></u>
	        </td>
	    </tr>
	<?php } } ?>
</table>
<br><br>
<table border="0">
	<tr>
		<td width="300" align="right">
			<?php 
				$diterima = $this->db->get_where('disposisi_suratmasuk', array('suratmasuk_id' => $h->suratmasuk_id))->row_array(); 
				if ($diterima['status'] == 'Selesai') {
			?>
			<br>
			<!-- <img src="<?php echo base_url('assets/img/ttde.png') ?>" width="50"> -->
			<?php } ?>
			
		</td>
		<td>
			<?php
				$jabatan_id = $this->session->userdata('jabatan_id');
				$ttd = $this->db->query("
					SELECT * FROM disposisi_suratmasuk
					LEFT JOIN aparatur ON aparatur.jabatan_id = disposisi_suratmasuk.aparatur_id
					LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
					WHERE disposisi_suratmasuk.suratmasuk_id = '$h->suratmasuk_id' AND aparatur.statusaparatur='Aktif' ORDER BY disposisi_suratmasuk.dsuratmasuk_id ASC LIMIT 1
				")->row_array();
				// $ttd = $this->db->query("
				// 	SELECT * FROM disposisi_suratmasuk
				// 	LEFT JOIN aparatur ON aparatur.jabatan_id = disposisi_suratmasuk.aparatur_id
				// 	LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
				// 	WHERE jabatan.jabatan_id='$jabatan_id' AND disposisi_suratmasuk.suratmasuk_id = '$h->suratmasuk_id' AND aparatur.statusaparatur='Aktif' LIMIT 1
				// ")->row_array();
			?>
			<b><?= $ttd['jabatan'];?>,</b><br>
			Bogor,
			<?php $tanggaldisposisi=$this->db->query("SELECT tanggal FROM disposisi_suratmasuk WHERE suratmasuk_id='$h->suratmasuk_id' ORDER BY dsuratmasuk_id DESC LIMIT 1")->result();
			foreach($tanggaldisposisi as $key => $tgl){ ?>
				<?php echo tanggal($tgl->tanggal); ?> <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<?php } ?>
			<br><br>
			<p>
			<?php
				echo $ttd['nama'];
			?>
			</p>
		</td>
	</tr>
</table>

<?php } ?>