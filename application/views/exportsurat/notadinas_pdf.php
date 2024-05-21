<style>
	table.logo {
		vertical-align: middle;
		color: #000000;
		text-align: center;
	}

	table.logo td.kop1 {
		text-transform: capitalize;
		font-size: 13px;
		font-weight: bold;
		border-collapse: collapse;
	}

	table.logo td.kop2 {
		text-transform: uppercase;
		font-size: 13px;
		font-weight: bold;
		border-collapse: collapse;
	}

	table.logo td.kop3 {
		text-transform: capitalize;
		font-size: 12px;
		border-collapse: collapse;
		line-height: 1.2;
	}

	table.logo td.kop4 {
		font-size: 12px;
		border-collapse: collapse;
	}

	table.nomor-surat {
		font-size: 12px;
	}

	table.isi-surat {
		border-spacing: 0px 4px;
		font-size: 12px;
	}

	table.jadwal {
		border-spacing: 0px 1px;
		font-size: 12px;
	}

	table.notadinas {
		border-spacing: 0px 5px;
		font-size: 12px;
		text-align: center;
		font-weight: bold;
	}

	p {
		font-size: 12px;
		text-align: justify;
		text-justify: inter-word;
		text-indent: 28px;
		line-height: 1.3;
	}

	table.lampiran {
		border-spacing: 0px 0px;
		font-size: 12px;
	}

	div.tembusan {
		font-size: 11px;
		white-space: pre-line;
		text-align: justify;
	}

	div.lampiran {
		font-size: 12px;
		white-space: pre-line;
		text-align: left;
		page-break-before: always;
	}

	table.ttd {
		border-spacing: 0px 0px;
		font-size: 10px;
		padding-top: 5px;
		padding-bottom: 5px;
		padding-left: 5px;
	}
</style>

<?php foreach ($notadinas as $key => $h) { ?>
	<!-- @MpikEgov 20 Juni 2023 -->
	<?php $this->load->view('exportsurat/kopsurat_conf'); ?>
	<table class="notadinas" border="0">
		<tr>
			<td>NOTA DINAS</td>
		</tr>
	</table>
	<table class="nomor-surat" border="0">
		<tr>
			<td width="20%">Kepada</td>
			<td width="2%">:</td>
			<!-- [UPDATE] Fikri Egov 28 Jan 2022-->
			<td width="78%"><?php
							$this->db->from('disposisi_suratkeluar');
							$this->db->select('opd.nomenklatur_pd,opd.nama_pd,users.level_id as leveluser,jabatan.nama_jabatan,jabatan.jabatan,jabatan.atasan_id');
							$this->db->join('jabatan', 'jabatan.jabatan_id = disposisi_suratkeluar.users_id', 'left');
							$this->db->join('aparatur', 'aparatur.jabatan_id = jabatan.jabatan_id', 'left');
							$this->db->join('users', 'users.aparatur_id = aparatur.aparatur_id', 'left');
							$this->db->join('level', 'level.level_id = users.level_id', 'left');
							$this->db->join('opd', 'opd.opd_id = jabatan.opd_id', 'left');
							$this->db->where('disposisi_suratkeluar.surat_id', $h->id);
							$this->db->where('aparatur.statusaparatur', 'Aktif');
							$this->db->order_by('level.level_id');
							$kepada = $this->db->get();
							$jmlkepada = $kepada->num_rows();
							$no = 1;
							if ($kepada->num_rows() == 1) {
								foreach ($kepada->result() as $key => $k) {
									$query = $this->db->query("SELECT nama_jabatan,jabatan_id FROM jabatan WHERE jabatan_id = '$k->atasan_id'")->row_array();
									if ($k->nomenklatur_pd == 'Sekda') {
										// foreach($query as $key => $q){
										// var_dump($q->nama_jabatan);die;
										// if(substr($k->nama_jabatan, 0,6) == 'Kepala') {
										// echo "Sekretaris Daerah Kota Bogor<br>Up ".substr($k->nama_jabatan, 7);
										// }
										// elseif($q->nama_jabatan != 'Sekretaris Daerah Kota Bogor') {
										if ($q->nama_jabatan != 'Sekretaris Daerah Kota Bogor') {		// Perbaikan UP @Dam Egov 11/07/2023
											echo "Sekretaris Daerah Kota Bogor<br>Up. " . $k->nama_jabatan;
										} else {
											echo "Sekretaris Daerah Kota Bogor";
										}
										// }
									} elseif ($k->nomenklatur_pd == 'Sekwan') {
										foreach ($query as $key => $q) {
											echo $q->nama_jabatan;
										}
									} elseif ($k->nomenklatur_pd == 'Walikota') {
										echo 'Bapak ' . $k->nama_pd;
									} elseif ($k->nomenklatur_pd == 'Dirut. RSUD') {
										echo 'Direktur ' . $k->nama_pd;
									} elseif (substr($k->jabatan, 0, 6) == 'Kepala') {
										echo $k->nama_jabatan; // Update @Mpik Egov 27/02/2023
									} elseif (substr($k->nama_jabatan, 0, 7) != 'admintu') {
										echo $k->jabatan; // Update @Mpik Egov 29/06/2022
									} elseif ($k->leveluser == 5) {
										echo $k->nama_jabatan; // Update @Mpik Egov 29/06/2022
									} else {
										echo 'Kepala ' . $k->nama_pd;
									}
								}
							} elseif ($kepada->num_rows() <= 3) {
								foreach ($kepada->result() as $key => $k) {
									$query = $this->db->query("SELECT nama_jabatan FROM jabatan WHERE jabatan_id = '$k->atasan_id'")->result();
									if ($k->nomenklatur_pd == 'Sekda') {
										foreach ($query as $key => $q) {
											if (substr($q->nama_jabatan, 0, 6) == 'Kepala') {
												echo $no, '. ' . "Sekretaris Daerah Kota Bogor<br>Up. " . substr($q->nama_jabatan, 7) . '<br>';
											} elseif ($q->nama_jabatan != 'Sekretaris Daerah Kota Bogor') {
												echo $no, '. ' . "Sekretaris Daerah Kota Bogor<br>Up. " . $q->nama_jabatan . '<br>';
											} else {
												echo $no, '. ' . "Sekretaris Daerah Kota Bogor" . '<br>';
											}
											++$no;
										}
									} elseif ($k->leveluser == 5) {
										echo $no, '. ' . $k->nama_jabatan . '<br>';
										++$no; // Update @Mpik Egov 29/06/2022
									} elseif (substr($k->jabatan, 0, 6) == 'Kepala') {
										echo $no, '. ' . $k->nama_jabatan . '<br>'; //Update @Mpik Egov 27/02/2023
										++$no;
									} elseif (substr($k->nama_jabatan, 0, 7) != 'admintu') {
										echo $no, '. ' . $k->jabatan . '<br>'; //Update @Mpik Egov 29/06/2022
										++$no;
									} elseif (substr($k->nomenklatur_pd, 0, 5) == 'Lurah' || substr($k->nomenklatur_pd, 0, 5) == 'Camat') {
										foreach ($query as $key => $q) {
											echo $no, '. ', $q->nama_jabatan . ',<br>';
											++$no;
										}
									} else {
										echo $no, '. ' . $k->kode_pd . '<br>';
										++$no;
									}
								}
							} elseif ($kepada->num_rows() >= 3) {
								echo "Terlampir";
							}
							?></td>
			<!-- [UPDATE] Fikri Egov 28 Jan 2022-->
		</tr>
		<tr>
			<td width="20%">Dari</td>
			<td width="2%">:</td>
			<!-- <td width="78%"><?php if (empty($h->jabatanaparatur)) {
										echo $h->pembuatsurat;
									} else {
										echo $h->jabatanaparatur;
									} ?></td> [@dam | 13-05-2022] -->
			<td width="78%"><?php echo $h->pembuatsurat; ?></td>
		</tr>
		<tr>
			<td width="20%">Tembusan</td>
			<td width="2%">:</td>
			<td width="80%"><?php
							$this->db->from('tembusan_surat');
							$this->db->join('jabatan', 'jabatan.jabatan_id = tembusan_surat.users_id', 'left');
							$this->db->join('opd', 'opd.opd_id = jabatan.opd_id', 'left');
							$this->db->join('tembusan_keluar', 'tembusan_keluar.id = tembusan_surat.users_id', 'left');
							$this->db->where('tembusan_surat.surat_id', $h->id);
							$this->db->order_by('tembusan_surat.tembusansurat_id', 'ASC');  //Update @mpikegov 7/06/2022 14:00  
							$tembusan = $this->db->get();
							$no = 1;

							if ($tembusan->num_rows() > 0) { ?>

				<?php
								foreach ($tembusan->result() as $key => $k) {
									$query = $this->db->query("SELECT nama_jabatan FROM jabatan WHERE jabatan_id = '$k->atasan_id'")->result();
									if ($tembusan->num_rows() == 1) {
										if (!empty($k->nama_pd)) {

											if ($k->nomenklatur_pd == 'Sekda') {
												foreach ($query as $key => $q) {
													echo $q->nama_jabatan;
												}
											} elseif ($k->nomenklatur_pd == 'Walikota' or $k->nomenklatur_pd == 'Wakil Walikota') {
												foreach ($query as $key => $q) {
													echo 'Bapak ' . $q->nama_jabatan;
												}
											} else {
												foreach ($query as $key => $q) {
													echo $q->nama_jabatan;
												}
											}
										} else {
											echo $k->nama_tembusan;
										}
									} elseif ($tembusan->num_rows() > 1) {
										if (!empty($k->nama_pd)) {

											if ($k->nomenklatur_pd == 'Sekda') {
												foreach ($query as $key => $q) {
													echo $no, '. ', $q->nama_jabatan . '<br>';
													++$no;
												}
											} elseif ($k->nomenklatur_pd == 'Walikota' or $k->nomenklatur_pd == 'Wakil Walikota') {
												foreach ($query as $key => $q) {
													echo $no, '. ', 'Bapak ' . $q->nama_jabatan . '<br>';
													++$no;
												}
											} else {
												foreach ($query as $key => $q) {
													echo $no, '. ' . $q->nama_jabatan . '<br>';
													++$no;
												}
											}
										} else {
											echo $no, '. ', $k->nama_tembusan . '<br>';
											++$no;
										}
									}
								}
							} else {
								echo '-';
							}
				?>

			</td>
		</tr>
		<tr>
			<td width="20%">Tanggal</td>
			<td width="2%">:</td>
			<td width="80%"><?php echo tanggal($h->tanggal); ?></td>
		</tr>
		<tr>
			<td width="20%">Nomor</td>
			<td width="2%">:</td>
			<td width="80%"><?php echo $h->nomor; ?></td>

		</tr>
		<tr>
			<td width="20%">Sifat</td>
			<td width="2%">:</td>
			<td width="80%"><?php echo $h->sifat; ?></td>

		</tr>
		<tr>
			<td width="20%">Lampiran</td>
			<td width="2%">:</td>
			<td width="80%"><?php echo $h->lampiran; ?></td>

		</tr>
		<tr>
			<td width="20%">Hal</td>
			<td width="2%">:</td>
			<td width="80%"><?php echo $h->hal; ?></td>
		</tr>
	</table><br>
	<img src="assets/img/line2.png" width="650px"><br>
	<table class="isi-surat" border="0">
		<tr>
			<td width="100%" colspan="4"><?php echo $h->isi; ?></td>
		</tr>
	</table>

	<br><br>
	<table class="isi-surat" border="0">
		<tr>
			<td width="46%"></td>
			<td width="55%">
				<?php if ($h->status == NULL) { ?>
					<table border="0">
					<?php } elseif ($h->status == 'Belum Ditandatangani' || $h->status == 'Sudah Ditandatangani') { ?>
						<table border="0.5">
						<?php } ?>
						<tr>
							<td width="100%">
								<table class="ttd" border="0">
									<tr>
										<td width="20%">
											<?php if ($h->status == NULL) {
												echo "";
											} else if ($h->status == 'Belum Ditandatangani' || $h->status == 'Sudah Ditandatangani') {
											?>
												<img src="assets/img/logokbr.png" width="50px">
											<?php } ?>
										</td>
										<td width="80%">
											<?php if ($h->status == NULL) {
												echo "";
											} elseif ($h->status == 'Belum Ditandatangani' || $h->status == 'Sudah Ditandatangani') {
												echo "Ditandatangani secara elektronik oleh :";
											}
											?><br>
											<b><?php
												if ($h->jabatanpejabat == NULL) {
													echo "NAMA JABATAN";
												} else {
													echo strtoupper($h->jabatanpejabat);
												}
												?>,</b><br>
											<?php if ($h->status == NULL) {
												echo "DRAFT";
											} ?><br>
											<u><b><?php
													if ($h->namapejabat == NULL) {
														echo "NAMA JELAS DAN GELAR";
													} elseif ($h->nip == 196906021993032007) {
														echo "Rr. JUNIARTI ESTININGSIH S.E., M.M.";
													} else {
														echo strtoupper($tte['nama']);
													}
													?></b></u><br>
											<?php
											if (empty($tte['pangkat'])) {
												echo "Pangkat";
											} else {
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
		echo str_replace(",", ",<br>", $h->tembusan);
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


	<?php  } ?>