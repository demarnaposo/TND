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
		font-size: 15px;
		font-weight: bold;
		border-collapse: collapse;
	}

	table.logo td.kop3 {
		text-transform: capitalize;
		font-size: 10px;
		border-collapse: collapse;
		line-height: 1.2;
	}

	table.logo td.kop4 {
		font-size: 2px;
		border-collapse: collapse;
	}

	table.nomor-surat {
		border-spacing: 0px 0px;
		font-size: 10px;
	}

	table.isi-surat {
		border-spacing: 0px 4px;
		font-size: 10px;
	}

	table.jadwal {
		border-spacing: 0px 1px;
		font-size: 10px;
	}

	p {
		font-size: 10px;
		text-align: justify;
		text-justify: inter-word;
		text-indent: 28px;
		line-height: 1.3;
	}

	table.lampiran {
		border-spacing: 0px 0px;
		font-size: 10px;
	}

	div.tembusan {
		font-size: 9px;
		white-space: pre-line;
		text-align: justify-left;
	}

	div.lampiran {
		font-size: 10px;
		white-space: pre-line;
		text-align: left;
		page-break-before: always;
	}
</style>

<?php foreach ($lampiran as $key => $h) { ?>

	<?php
	$this->db->select('*, opd.nomenklatur_pd');
	$this->db->from('disposisi_suratkeluar');
	$this->db->join('jabatan', 'jabatan.jabatan_id = disposisi_suratkeluar.users_id', 'left');
	$this->db->join('aparatur', 'aparatur.jabatan_id = jabatan.atasan_id', 'left');
	$this->db->join('levelbaru', 'levelbaru.level_id = aparatur.level_id', 'left');
	$this->db->join('opd', 'opd.opd_id = jabatan.opd_id', 'left');
	$this->db->where('disposisi_suratkeluar.surat_id', $h->id);
	$this->db->where('aparatur.statusaparatur', 'Aktif');
	$this->db->where('aparatur.level_id !=', 0);	
	$this->db->order_by('levelbaru.level_id', 'asc');
	$this->db->order_by('opd.nama_pd', 'asc');
	$this->db->order_by('jabatan.nama_jabatan', 'asc');
	$kepada = $this->db->get();

	// Update @Mpik Egov 30 September 2022
	$this->db->select('*');
	$this->db->from('disposisi_suratkeluar');
	$this->db->join('eksternal_keluar', 'eksternal_keluar.id=disposisi_suratkeluar.users_id', 'LEFT');
	$this->db->where('disposisi_suratkeluar.surat_id', $h->id);
	$this->db->like('disposisi_suratkeluar.users_id', 'EKS');
	$kepadaeks = $this->db->get();
	// END Update @Mpik Egov 30 September 2022

	?>
	<!-- <?php if (!empty($h->tembusan)) { ?>
<div class="tembusan">
<b><u>Tembusan :</u></b><br>
<?php
				echo str_replace(",", ",<br>", $h->tembusan);
			} ?>
</div> -->

	<?php
	$numkepada = $kepada->num_rows();
	$numkepadaeks = $kepadaeks->num_rows();
	$totalkepada = $numkepada + $numkepadaeks;
	if ($kepada->num_rows() > 3 or $kepadaeks->num_rows() > 3 or $totalkepada > 3) { ?>
		<div class="lampiran">
			<b>LAMPIRAN :</b><br><br>
			<table border="0">
				<tr>
					<td width="10%">NOMOR</td>
					<td width="2%">:</td>
					<td width="85%"><?php echo $h->nomor; ?></td>
				</tr>
				<tr>
					<td width="10%">TANGGAL</td>
					<td width="2%">:</td>
					<td width="85%"><?php echo tanggal($h->tanggal); ?></td>
				</tr>
				<tr>
					<td width="10%">TENTANG</td>
					<td width="2%">:</td>
					<?php
					if ($h->tentang != '') {
						echo "<td width='85%'>" . $h->tentang . "</td>";
					} else {
						echo "<td width='85%'>" . $h->hal . "</td>";
					}
					?>
				</tr>
			</table><br><br>
		<?php
		$no = 1;
		foreach ($kepada->result() as $key => $k) {
			$query = $this->db->query("SELECT jabatan.nama_jabatan, jabatan.jabatan FROM aparatur
			LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
			LEFT JOIN levelbaru ON levelbaru.level_id=aparatur.level_id
			WHERE jabatan.jabatan_id= '$k->atasan_id' AND aparatur.statusaparatur='Aktif' AND aparatur.level_id !=0")->result();
			if (!empty($k->nama_pd)) {
				foreach ($query as $key => $q) {
					if ($k->nomenklatur_pd == 'Sekda') {

						if (substr($q->nama_jabatan, 0, 6) == 'Kepala' or substr($q->nama_jabatan, 0, 7) == 'Asisten' or substr($q->nama_jabatan, 0, 4) == 'Staf' or substr($q->nama_jabatan, 0, 11) == 'Plh. Kepala' or substr($q->nama_jabatan, 0, 11) == 'Plh.Asisten') {
							echo $no, '. ', $q->nama_jabatan, '<br>';
						} else {
							echo $no, '. ', "Sekretaris Daerah Kota Bogor", '<br>';
						}
						++$no;
					} elseif (substr($k->nama_jabatan, 0, 7) != 'admintu') {
						echo $no, '. ' . $k->jabatan . '<br>';
						++$no;
					} else {
						foreach ($query as $key => $q) {
							echo $no, '. ', $q->jabatan . '<br>';
							++$no;
						}
					}
				}
			}
		}
		foreach ($kepadaeks->result() as $key => $j) {
			echo $no, '. ', $j->nama . '<br>';
			++$no;
		}
	}
		?>
		</div>

	<?php } ?>