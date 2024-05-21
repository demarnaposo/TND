<style>
table.logo {
	vertical-align:middle;
	color: #000000;
	text-align: center;
}
table.logo td.kop1{	
   	text-transform:capitalize;
	font-size:13px;
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
	font-size:10px;   	
	border-collapse: collapse;
	line-height: 1.2;
}
table.logo td.kop4{
	font-size:2px;   	
	border-collapse: collapse;
	
}

table.nomor-surat{
	font-size:10px;
}

table.isi-surat{
	border-spacing: 0px 0px;
	font-size:10px;
}

table.jadwal{
	border-spacing: 0px 1px;
	font-size:10px;
}
table.perjalanan{
	border-spacing: 0px 5px;
	font-size:11px;
	text-align: center;
	font-weight: bold;
}
p { 
	font-size:10px;
	text-align: justify;
	text-justify: inter-word;	
	text-indent: 28px;
	line-height: 1.3;
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
table.ttd{
	border-spacing: 0px 0px;
	font-size:9px;
	padding-top: 5px;
	padding-bottom: 5px;
	padding-left: 5px;
}

</style>
<?php foreach($lampiran as $key => $h){?>
<div class="terlampir">
	<u>BAGIAN BELAKANG</u><br><br> 
	<table class="lampiran" border="1">
		<tr>
			<td width="50%"></td>
			<td width="50%">
				<table class="isi-surat" border="0">
					<tr>
						<td width="5%">I.</td>
						<td width="38%">Berangkat dari</td>
						<td width="4%">:</td>
						<td width="54%"></td>
					</tr>
					<tr>
						<td width="5%"></td>
						<td width="42%">(Tempat Kedudukan)</td>
						<td width="54%"></td>
					</tr>
					<tr>
						<td width="5%"></td>
						<td width="38%">Ke</td>
						<td width="4%">:</td>
						<td width="54%"></td>
					</tr>
					<tr>
						<td width="5%"></td>
						<td width="38%">Pada Tanggal</td>
						<td width="4%">:</td>
						<td width="54%"></td>
					</tr><br>
					<tr>
						<td width="5%"></td>
						<td colspan width="95%" align="center">Pengguna Anggaran/Kuasa Pengguna Anggaran<br></td>
					</tr>
					<tr>
						<td width="5%"></td>
						<td colspan width="95%">
							<?php if($h->status == NULL){?>
							<table border="0">
							<?php }elseif($h->status == 'Belum Ditandatangani' || $h->status == 'Sudah Ditandatangani'){?>
							<table border="0.5">
							<?php }?>
								<tr>
								<td width="95%">
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
					<tr>
						<td width="5%"></td>
						<td colspan width="95%" align="center"></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td width="50%">
			<table class="isi-surat" border="0">
					<tr>
						<td width="6%">II.</td>
						<td width="38%">Tiba di</td>
						<td width="4%">:</td>
						<td width="48%"></td>
					</tr>
					<tr>
						<td width="6%"></td>
						<td width="38%">Pada Tanggal</td>
						<td width="4%">:</td>
						<td width="48%"></td>
					</tr>
					<tr>
						<td width="6%"></td>
						<td width="38%">Kepala</td>
						<td width="4%">:</td>
						<td width="48%"></td>
					</tr>
					<tr>
						<td width="6%"></td>
						<td colspan width="95%" align="center"></td>
					</tr>
					<tr>
						<td width="6%"></td>
						<td colspan width="95%" align="center"></td>
					</tr>
					<tr>
						<td width="6%"></td>
						<td colspan width="95%" align="center">
						<br><br>		
						</td>
					</tr>
					<tr>
						<td width="6%"></td>
						<td colspan width="95%" align="center">
						________________________		
						</td>
					</tr>
					<tr>
						<td width="6%"></td>
						<td colspan width="95%" align="center"></td>
					</tr>
				</table>
			</td>
			<td width="50%">
				<table class="isi-surat" border="0">
					<tr>
						<td width="5%"></td>
						<td width="38%">Berangkat dari</td>
						<td width="4%">:</td>
						<td width="54%"></td>
					</tr>
					<tr>
						<td width="5%"></td>
						<td width="42%">(Tempat Kedudukan)</td>
						<td width="54%"></td>
					</tr>
					<tr>
						<td width="5%"></td>
						<td width="38%">Ke</td>
						<td width="4%">:</td>
						<td width="54%"></td>
					</tr>
					<tr>
						<td width="5%"></td>
						<td width="38%">Pada Tanggal</td>
						<td width="4%">:</td>
						<td width="54%"></td>
					</tr>
					<tr>
						<td width="5%"></td>
						<td width="38%">Kepala</td>
						<td width="4%">:</td>
						<td width="54%"></td>
					</tr>
					<tr>
						<td width="5%"></td>
						<td width="95%" align="center">
						<br><br>
						</td>
					</tr>
					<tr>
						<td width="5%"></td>
						<td width="95%" align="center">
						________________________		
						</td>
					</tr>
					<tr>
						<td width="5%"></td>
						<td width="95%" align="center"></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td width="50%">
			<table class="isi-surat" border="0">
					<tr>
						<td width="8%">III.</td>
						<td width="38%">Tiba di</td>
						<td width="4%">:</td>
						<td width="48%"></td>
					</tr>
					<tr>
						<td width="8%"></td>
						<td width="38%">Pada Tanggal</td>
						<td width="4%">:</td>
						<td width="48%"></td>
					</tr>
					<tr>
						<td width="8%"></td>
						<td width="38%">Kepala</td>
						<td width="4%">:</td>
						<td width="48%"></td>
					</tr>
					<tr>
						<td width="8%"></td>
						<td colspan width="95%" align="center"></td>
					</tr>
					<tr>
						<td width="8%"></td>
						<td colspan width="95%" align="center"></td>
					</tr>
					<tr>
						<td width="8%"></td>
						<td colspan width="95%" align="center">
						<br><br>		
						</td>
					</tr>
					<tr>
						<td width="8%"></td>
						<td colspan width="95%" align="center">
						________________________		
						</td>
					</tr>
					<tr>
						<td width="8%"></td>
						<td colspan width="95%" align="center"></td>
					</tr>
				</table>
			</td>
			<td width="50%">
				<table class="isi-surat" border="0">
					<tr>
						<td width="5%"></td>
						<td width="38%">Berangkat dari</td>
						<td width="4%">:</td>
						<td width="54%"></td>
					</tr>
					<tr>
						<td width="5%"></td>
						<td width="42%">(Tempat Kedudukan)</td>
						<td width="54%"></td>
					</tr>
					<tr>
						<td width="5%"></td>
						<td width="38%">Ke</td>
						<td width="4%">:</td>
						<td width="54%"></td>
					</tr>
					<tr>
						<td width="5%"></td>
						<td width="38%">Pada Tanggal</td>
						<td width="4%">:</td>
						<td width="54%"></td>
					</tr>
					<tr>
						<td width="5%"></td>
						<td width="38%">Kepala</td>
						<td width="4%">:</td>
						<td width="54%"></td>
					</tr>
					<tr>
						<td width="5%"></td>
						<td colspan width="95%" align="center">
						<br><br>		
						</td>
					</tr>
					<tr>
						<td width="5%"></td>
						<td colspan width="95%" align="center">
						________________________		
						</td>
					</tr>
					<tr>
						<td width="5%"></td>
						<td colspan width="95%" align="center"></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td width="50%">
			<table class="isi-surat" border="0">
					<tr>
						<td width="8%">IV.</td>
						<td width="38%">Tiba di</td>
						<td width="4%">:</td>
						<td width="48%"></td>
					</tr>
					<tr>
						<td width="8%"></td>
						<td width="38%">Pada Tanggal</td>
						<td width="4%">:</td>
						<td width="48%"></td>
					</tr>
					<tr>
						<td width="8%"></td>
						<td width="38%">Kepala</td>
						<td width="4%">:</td>
						<td width="48%"></td>
					</tr>
					<tr>
						<td width="8%"></td>
						<td colspan width="95%" align="center"></td>
					</tr>
					<tr>
						<td width="8%"></td>
						<td colspan width="95%" align="center"></td>
					</tr>
					<tr>
						<td width="8%"></td>
						<td colspan width="95%" align="center">
						<br><br>		
						</td>
					</tr>
					<tr>
						<td width="8%"></td>
						<td colspan width="95%" align="center">
						________________________		
						</td>
					</tr>
					<tr>
						<td width="8%"></td>
						<td colspan width="95%" align="center"></td>
					</tr>
				</table>
			</td>
			<td width="50%">
				<table class="isi-surat" border="0">
					<tr>
						<td width="5%"></td>
						<td width="38%">Berangkat dari</td>
						<td width="4%">:</td>
						<td width="54%"></td>
					</tr>
					<tr>
						<td width="5%"></td>
						<td width="42%">(Tempat Kedudukan)</td>
						<td width="54%"></td>
					</tr>
					<tr>
						<td width="5%"></td>
						<td width="38%">Ke</td>
						<td width="4%">:</td>
						<td width="54%"></td>
					</tr>
					<tr>
						<td width="5%"></td>
						<td width="38%">Pada Tanggal</td>
						<td width="4%">:</td>
						<td width="54%"></td>
					</tr>
					<tr>
						<td width="5%"></td>
						<td width="38%">Kepala</td>
						<td width="4%">:</td>
						<td width="54%"></td>
					</tr>
					<tr>
						<td width="5%"></td>
						<td colspan width="95%" align="center">
						<br><br>		
						</td>
					</tr>
					<tr>
						<td width="5%"></td>
						<td colspan width="95%" align="center">
						________________________		
						</td>
					</tr>
					<tr>
						<td width="5%"></td>
						<td colspan width="95%" align="center"></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td width="50%">
			<table class="isi-surat" border="0">
					<tr>
						<td width="8%">V.</td>
						<td width="38%">Tiba di <br>(Tempat Kedudukan)</td>
						<td width="4%">:</td>
						<td width="48%"></td>
					</tr>
					<tr>
						<td width="8%"></td>
						<td width="38%">Pada Tanggal</td>
						<td width="4%">:</td>
						<td width="48%"></td>
					</tr>
					<tr>
						<td width="100"></td>
					</tr>
					<tr>
						<td width="5%"></td>
						<td colspan width="95%" align="center">Pengguna Anggaran/Kuasa Pengguna Anggaran<br></td>
					</tr>
					<tr>
						<td width="5%"></td>
						<td colspan width="95%">
							<?php if($h->status == NULL){?>
							<table border="0">
							<?php }elseif($h->status == 'Belum Ditandatangani' || $h->status == 'Sudah Ditandatangani'){?>
							<table border="0.5">
							<?php }?>
								<tr>
								<td width="95%">
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
					<tr>
						<td width="5%"></td>
						<td colspan width="95%" align="center"></td>
					</tr>
				</table>
			</td>
			<td width="50%">
				<table class="isi-surat" border="0">
					<tr>
						<td width="100%" align="justify">Telah diperiksa dengan keterangan bahwa perjalanan tersebut di atas perintahnya
						dan semata-mata untuk kepentingan jawaban dalam waktu yang sesingkat – singkatnya</td>
					</tr>
					<tr>
						<td width="100"></td>
					</tr>
					<tr>
						<td width="5%"></td>
						<td colspan width="95%" align="center">Pengguna Anggaran/Kuasa Pengguna Anggaran<br></td>
					</tr>
					<tr>
						<td width="5%"></td>
						<td colspan width="95%">
							<?php if($h->status == NULL){?>
							<table border="0">
							<?php }elseif($h->status == 'Belum Ditandatangani' || $h->status == 'Sudah Ditandatangani'){?>
							<table border="0.5">
							<?php }?>
								<tr>
								<td width="95%">
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
					<tr>
						<td width="5%"></td>
						<td colspan width="95%" align="center"></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2">VI. Catatan Lain</td>
		</tr>
		<tr>
			<td colspan="2" align="justify">VII. PERHATIAN<br>
				Pengguna Anggaran/Kuasa Pengguna Anggaran yang menerbitkan SPD, pegawai yang melakukan perjalanan dinas, para pejabat yang mengesahkan tanggal berangkat/tiba, serta bendahara pengeluaran
				bertanggung jawab berdasarkan peraturan- peraturan Keuangan Negara apabila Negara menderita rugi akibat kesalahan, kelalaian dan kealpaannya.</td>
		</tr>
	</table>
</div>
<?php }?>