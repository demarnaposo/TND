<style>
table.logo {
	vertical-align:middle;
	color: #000000;
	text-align: center;
}
table.logo td.kop1{	
   	text-transform:capitalize;
	font-size:12px;
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

table.kepada{
	border-spacing: 0px 0px;
	font-size:10px;
	text-align: left;
}

table.surat-edaran{
	border-spacing: 0px 5px;
	font-size:11px;
	text-align: center;
	font-weight: bold;
}

table.isi-surat{
	border-spacing: 0px 4px;
	font-size:11px;
}

table.jadwal{
	border-spacing: 0px 1px;
	font-size:10px;
}

p { 
	font-size:10px;
	text-align: justify;
	text-justify: inter-word;	
	text-indent: 28px;
	line-height: 1.3;
}
table.ttd{
	border-spacing: 0px 0px;
	font-size:10px;
}
div.tembusan{ 
	font-size:9px;
	white-space: pre-line;
	text-align: justify;:left;
}

div.lampiran{ 
	font-size:10px;
	white-space: pre-line;
	text-align:left;
	page-break-before: always;
}

</style>

<?php foreach ($tandaterima as $key => $h) { ?>
<table class="logo" border="0">
	<tr>
		<td width="13%" rowspan="4" align="center"><img src="assets/img/logokbr.png" width="75px"></td>
		<td class="kop1" width="87%"></td>
	</tr>	
    <tr><td rowspan="2" class="kop1"><?= $h->nama_pd?></td></tr>
    <tr><td></td></tr>
    <tr><td></td></tr>
</table>
<br><br>
<table border="0">
    <tr>
        <td colspan="3"></td>
    </tr>
    <tr>
        <td align="center" colspan="3"><u>Tanda Terima Surat</u></td>
    </tr>
    <tr>
        <td colspan="3"></td>
    </tr>
    <tr>
        <td width="5%"></td>
        <td width="20%">Surat Dari</td>
        <td width="1%">:</td>
        <td width="74%"><?= $h->dari;?></td>
    </tr>
    <tr>
        <td width="5%"></td>
        <td width="20%">Nomor Surat</td>
        <td width="1%">:</td>
        <td width="74%"><?= $h->nomor;?></td>
    </tr>
    <tr>
        <td width="5%"></td>
        <td width="20%">Tanggal Surat</td>
        <td width="1%">:</td>
        <td width="74%"><?= tanggal($h->diterima);?></td>
    </tr>
    <tr>
        <td width="5%"></td>
        <td width="20%">Lampiran</td>
        <td width="1%">:</td>
        <td width="74%"><?= $h->lampiran;?></td>
    </tr>
    <tr>
        <td width="5%"></td>
        <td width="20%">Prihal</td>
        <td width="1%">:</td>
        <td width="74%"><?= $h->hal;?></td>
    </tr>
    <tr>
        <td colspan="4"></td>
    </tr>
    <tr>
        <td colspan="4"></td>
    </tr>
    <tr>
        <td width="50%"></td>
        <td width="20%"></td>
        <td width="30%">Bogor,<?= tanggal($h->tanggal);?></td>
    </tr>
    <tr>
        <td colspan="4"></td>
    </tr>
    <tr>
        <td colspan="4"></td>
    </tr>
    <tr>
        <td width="50%"></td>
        <td width="20%"></td>
        <td width="30%" align="center">Penerima Surat,</td>
    </tr>
    <tr>
        <td rowspan="3"></td>
        <td rowspan="3"></td>
        <td rowspan="3"></td>
    </tr>
    <tr>
        <td colspan="3"></td>
    </tr>
    <tr>
        <td colspan="3"></td>
    </tr>
    <tr>
        <td width="50%">Informasi lebih lanjut silakan</td>
        <td width="20%"></td>
        <td width="30%" align="center">(<?= $h->penerima;?>)</td>
    </tr>
    <tr>
        <td colspan="3">menghubungi Nomor Telepon</td>
    </tr>
    <tr>
        <td colspan="3"><b><?= $h->telp;?></b></td>
    </tr>
    <tr>
        <td colspan="3"></td>
    </tr>
</table>

<?php  } ?>