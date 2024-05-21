<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use NcJoes\OfficeConverter\OfficeConverter;
use PhpOffice\PhpWord\TemplateProcessor;


class Docprocessor{

    public function convertToPdf($sourceFile, $destinationFile){
        
        $converter = new Officeconverter($_SERVER['DOCUMENT_ROOT'].'/assets/docs/'.$sourceFile);
		$converter->convertTo($destinationFile.'.pdf');

    }

    public function fillTemplate($data){

        $templateProcessor = new TemplateProcessor($_SERVER['DOCUMENT_ROOT'].'/assets/lampiransurat/lampiran/'.$data["lampiran_lain"]);

		$templateProcessor->setValue('nomorsurat', $data["nomorsurat"]);
		$templateProcessor->setValue('tanggal', $data["tanggal"]);
		$templateProcessor->setValue('jenissurat', $data["jenissurat"]);
		$templateProcessor->setValue('namajabatan', $data["namajabatan"]);
		$templateProcessor->setValue('namapejabat', $data["namapejabat"]);
		$templateProcessor->setValue('pangkat', $data["pangkat"]);
 
		$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/assets/lampiransurat/lampiran/'.$data["lampiran_lain"]);
    }

    
}