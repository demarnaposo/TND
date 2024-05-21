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
	border-spacing: 0px 0px;
	font-size:10px;
}

table.isi-surat{
	border-spacing: 0px 4px;
	font-size:10px;
}

table.jadwal{
	border-spacing: 0px 1px;
	font-size:10px;
}

table.ttd{
	border-spacing: 0px 0px;
	font-size:8px;
	padding-top: 5px;
	padding-bottom: 5px;
	padding-left: 5px;
}

p { 
	font-size:10px;
	text-align: justify;
	text-justify: inter-word;	
	text-indent: 28px;
	line-height: 1.3;
}
table.lampiran{
	border-spacing: 0px 0px;
	font-size:10px;
}
div.tembusan{ 
	font-size:9px;
	white-space: pre-line;
	text-align: justify;
}

div.lampiran{ 
	font-size:10px;
	white-space: pre-line;
	text-align:left;
	page-break-before: always;	
}

</style>
<?php foreach($tte as $key => $t){ ?>

<table class="isi-surat" border="0">
	<tr>
		<td width="38%"></td>
		<td width="55%">
				<table border="0.5">
				<tr>
					<td width="60%">
						<table class="ttd" border="0">
						<tr>
							<td width="20%">
								<img src="assets/img/logokbr.png" width="45px">
							</td>
							<td width="80%">
							Ditandatangani secara elektronik oleh :<br>
							<b><?php
									echo strtoupper($t->jabatan);
								
							?>,</b><br><br><br>
							<u><b><?php 
								if ($t->nip == 196906021993032007) {
									echo "Rr. JUNIARTI ESTININGSIH S.E., M.M.";
								}else{
									echo strtoupper($t->nama);
								}
							?></b></u><br>
							<?php echo $t->pangkat; ?>
							</td>
						</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<?php } ?>

<?php foreach($spesimen as $key => $t){ ?>

<table class="isi-surat" border="0">
	<tr>
		<td width="38%"></td>
		<td width="55%">
				<table border="0.5">
				<tr>
					<td width="60%">
						<table class="ttd" border="0">
						<tr>
							<td width="20%">
								<img src="assets/img/logokbr.png" width="45px">
							</td>
							<td width="80%">
							Ditandatangani secara elektronik oleh :<br>
							<b><?php
								echo strtoupper($t->jabatan);
								
							?>,</b><br><br><br>
							<u><b><?php 
								echo strtoupper($t->nama);
							?></b></u><br>
							<?php echo $t->pangkat; ?>
							</td>
						</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<?php } ?>