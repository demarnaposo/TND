<?php foreach ($disposisi as $key => $h) { ?>

<p style="text-align: center; font-weight: bold;">LEMBAR DISPOSISI</p> 

<hr>

<table width="100%">
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
		<td width="60px">Perihal</td>
		<td width="5px">:</td>
		<td><?php echo $h->hal; ?></td>
	</tr>
</table>

<br><hr>

<?php
	$disposisi = $this->db->query("
		SELECT * FROM disposisi_suratmasuk
		LEFT JOIN aparatur ON aparatur.jabatan_id = disposisi_suratmasuk.aparatur_id
		LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
		LEFT JOIN users ON users.aparatur_id = aparatur.aparatur_id
		WHERE disposisi_suratmasuk.status = 'Riwayat' AND disposisi_suratmasuk.suratmasuk_id = '$h->suratmasuk_id' AND users.level_id != 4 GROUP BY disposisi_suratmasuk.aparatur_id ORDER BY disposisi_suratmasuk.dsuratmasuk_id ASC
	")->result();
?>

<table width="100%">
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
		$no = 1;
		foreach ($disposisi as $key => $d) { 
	?>
	<tr>
		<td><?php echo $no.'. '.$d->nama_jabatan; ?></td>
		<td><?php echo $d->harap; ?></td>
	</tr>
	<?php 
		$no++;
		} 
	?>
</table>

<br><hr>

<table width="100%">
	<tr>
		<td></td>
	</tr>
	<tr>
		<td width="60px">Catatan</td>
		<td width="5px">:</td>
		<td width="475px"> 
			<?php 
				$ketdisposisi = $this->db->query("
					SELECT * FROM disposisi_suratmasuk
					LEFT JOIN aparatur
					ON aparatur.jabatan_id = disposisi_suratmasuk.users_id
					LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
					WHERE disposisi_suratmasuk.status = 'Riwayat' AND disposisi_suratmasuk.suratmasuk_id = '$h->suratmasuk_id' GROUP BY disposisi_suratmasuk.users_id ORDER BY disposisi_suratmasuk.dsuratmasuk_id ASC
				")->result();

				foreach ($ketdisposisi as $key => $d) {
					if (!empty($d->keterangan)) {
						echo '- '.$d->nama_jabatan.' : <u>'.$d->keterangan.'</u><br>  ';
					}
				} 
			?>
		</td>
	</tr>
</table>

<table>
	<tr>
		<td width="300" align="right">
			<?php 
				$diterima = $this->db->get_where('disposisi_suratmasuk', array('suratmasuk_id' => $h->suratmasuk_id))->row_array(); 
				if ($diterima['status'] == 'Selesai') {
			?>
			<br><img src="<?php echo base_url('assets/img/ttde.png') ?>" width="50">
			<?php } ?>
			
		</td>
		<td>
			Bogor,
			<?php echo tanggal($h->diterima); ?> <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<br><br>
			<p>
			<?php
				$jabatan_id = $this->session->userdata('jabatan_id');
				$ttd = $this->db->query("
					SELECT * FROM disposisi_suratmasuk
					LEFT JOIN aparatur ON aparatur.jabatan_id = disposisi_suratmasuk.aparatur_id
					WHERE disposisi_suratmasuk.status = 'Riwayat' AND disposisi_suratmasuk.suratmasuk_id = '$h->suratmasuk_id' ORDER BY disposisi_suratmasuk.dsuratmasuk_id ASC LIMIT 1
				")->row_array();
				echo $ttd['nama'];
			?>
			</p>
		</td>
	</tr>
</table>

<?php } ?>