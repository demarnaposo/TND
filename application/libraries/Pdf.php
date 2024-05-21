<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH.'libraries/tcpdf/examples/lang/eng.php';
require_once APPPATH.'libraries/tcpdf/tcpdf.php';
require_once APPPATH.'libraries/tcpdf/tcpdf_barcodes_2d.php';
require_once APPPATH.'../vendor/autoload.php';


use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;

use NcJoes\OfficeConverter\OfficeConverter;

require_once APPPATH.'libraries/FPDI/src/autoload.php';
require_once APPPATH.'libraries/FPDI/src/PdfParser/StreamReader.php';
use setasign\Fpdi\Tcpdf\Fpdi;
use setasign\Fpdi\PdfParser\StreamReader;


class Pdf extends TCPDF
{
    function __construct()
    {
        parent::__construct();
    }
	
    public function create($content, $file_name, $qr, $content2, $status)
    {
        $pdf = new TCPDF('P', PDF_UNIT, 'F4', true, 'UTF-8', false);       
    	$pdf->SetTitle($file_name);
    	$pdf->SetAuthor('TNDE Kota Bogor');
    	$pdf->SetDisplayMode('fullpage', 'SinglePage', 'UseNone');

    	$pdf->setPrintHeader(false);
    	$pdf->setPrintFooter(true);

       
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		
     
        // set additional information
        $info = array(
            'Name' => 'TNDE',
            'Location' => 'Kota Bogor',
            'Reason' => 'Pemerintah Kota Bogor',
            'ContactInfo' => 'https://tnd.kotabogor.go.id',
        );

        // set document signature
        // $pdf->setSignature($certificate, $certificate, 'tcpdfdemo', '', 2, $info);
        $pdf->SetLeftMargin(17);
        $pdf->SetRightMargin(17);
        $pdf->AddPage();
        // set style for barcode
        $style1 = array(
            'border' => 1,
            'vpadding' => '1',
            'hpadding' => '1',
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );
        
        $style = array(
            // 'border' => 2,
            'position' => '',
            'align' => 'C',
            'stretch' => false,
            'fitwidth' => true,
            'cellfitalign' => '',
            'border' => true,
            'hpadding' => '1',
            'vpadding' => '1',
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => false, //array(255,255,255),
            'text' => true,
            'font' => 'helvetica',
            'fontsize' => 8,
            'stretchtext' => 4
        );
        // create content for barcode

		$pdf->writeHTML($content);
        // if ($status == 'Belum Ditandatangani') {
        //     $pdf->write2DBarcode($_SERVER['HTTP_HOST'] . '/uploads/SIGNED/'.$qr.'.pdf', 'QRCODE,H', 170, 275, 18, 18, $style, 'N');
        // }else {            
        //     $pdf->write2DBarcode($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 'QRCODE,H', 170, 275, 18, 18, $style, 'N');
        // }
        $pdf->writeHTML($content2);
		
// 		$pdf->lastPage();  
	
    	ob_clean();
        // $pdf->Output($_SERVER['DOCUMENT_ROOT'].'assets/surat/'.$file_name.'.pdf', 'F');
        
	//$pdf->Output($_SERVER['DOCUMENT_ROOT'].'uploads/PDF/'.$file_name.'.pdf', 'F');

	// $pdf->Output($file_name.'.pdf', 'I');
	
	$pdf->Output($_SERVER['DOCUMENT_ROOT'].'assets/surat/'.$file_name.'.pdf','I');
	$pdf->Output($_SERVER['DOCUMENT_ROOT'].'assets/surat/'.$file_name.'.pdf','F');
	$pdf->Output($file_name.'.pdf','F');
	
	

    }
    public function createlandscape($content, $file_name, $qr)
    {
        $pdf = new TCPDF('L', PDF_UNIT, 'F4', true, 'UTF-8', false);       
    	$pdf->SetTitle($file_name);
    	$pdf->SetAuthor('TNDE Kota Bogor');
    	$pdf->SetDisplayMode('fullpage', 'SinglePage', 'UseNone');

    	$pdf->setPrintHeader(false);
    	$pdf->setPrintFooter(true);

        //$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        // $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
         $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		
        // set certificate file
        // $certificate = 'file://'.realpath('application/libraries/tcpdf/examples/data/cert/tcpdf.crt');
        // $certificate = 'file://'.realpath('application/libraries/tcpdf/examples/data/cert/ttde/tnde.p12');
        // set additional information
        $info = array(
            'Name' => 'TNDE',
            'Location' => 'Kota Bogor',
            'Reason' => 'Pemerintah Kota Bogor',
            'ContactInfo' => 'https://tnd.kotabogor.go.id',
        );

        // set document signature
        // $pdf->setSignature($certificate, $certificate, 'tcpdfdemo', '', 2, $info);
        $pdf->SetLeftMargin(17);
        $pdf->SetRightMargin(17);
        $pdf->AddPage();
        // create content for barcode		
		$pdf->writeHTML($content);
	
		$barcodeobj = new TCPDF2DBarcode($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], 'QRCODE, H');
		$dir = 'assets/qrcodes';
		$ext = 'png';
		$file_png = $dir . '/' . $qr . '.' . $ext;		
		file_put_contents($file_png, $barcodeobj->getBarcodePngData(3, 3, array(255, 255, 255)));
	
		$pdf->lastPage();  
	
    	ob_clean();
        // $pdf->Output($_SERVER['DOCUMENT_ROOT'].'assets/surat/'.$file_name.'.pdf', 'I');
		// $pdf->Output($_SERVER['DOCUMENT_ROOT'].'assets/surat/'.$file_name.'.pdf', 'F');
    	$pdf->Output($file_name.'.pdf', 'I');
    }

     public function convertDocToPdf($filelampiran){

        $path_to_pdf = $_SERVER['DOCUMENT_ROOT'] . 'application/libraries/tcpdf';
        Settings::setPdfRendererName(Settings::PDF_RENDERER_TCPDF);
        Settings::setPdfRendererPath($path_to_pdf);

        $phpWord = IOFactory::load($_SERVER['DOCUMENT_ROOT'] .'assets/lampiransurat/lampiran/'.$filelampiran, "Word2007");

        $outputName = $_SERVER['DOCUMENT_ROOT'] .'assets/lampiransurat/lampiran/'.$filelampiran.'.pdf';
        $phpWord->save($outputName, 'PDF');

        return $outputName;
    }

 public function convertlampiran($filelampiran){

       $newname = pathinfo($filelampiran, PATHINFO_FILENAME);
        $converter = new Officeconverter($_SERVER['DOCUMENT_ROOT'].'assets/lampiransurat/lampiran/'.$filelampiran);
	$converter->convertTo($newname.'.pdf');


        return $newname;
    }

public function convertlampiran2($filelampiran){

       
        log_message('INFO',$newname);
       
        $orifile = $_SERVER['DOCUMENT_ROOT'].'assets/lampiransurat/lampiran/'.$filelampiran;
        //$orifile = '/Users/ineza/Sites/tnde/assets/lampiransurat/lampiran/Lampiran-Surat-Lampiran-No-LMP-79-034259.docx';
        log_message('DEBUG', 'ORIFILE: '.$orifile);
        $outputdir =$_SERVER['DOCUMENT_ROOT'].'assets/lampiransurat/lampiran/';
       // $outputdir = '/Users/ineza/Sites/tnde/assets/lampiransurat/lampiran/';

        $command = "export HOME=/tmp && soffice --headless --convert-to pdf:writer_pdf_Export --outdir '$outputdir' '$orifile'" ;
        log_message('DEBUG', 'command: '.$command);
        exec($command, $output, $return);
        log_message('DEBUG', "OUTPUT " .$output);
        log_message('DEBUG', "RETURN ".$return);
        

        return $newname;
    }

    public function addqrcode($sourcefile,  $newfilename, $suratid){

        //$pdf = new TCPDF('P', PDF_UNIT, 'F4', true, 'UTF-8', false);       
        $pdf = new Fpdi();
        $pdf->setPageUnit(PDF_UNIT);
    	$pdf->SetTitle($newfilename);
    	$pdf->SetAuthor('TNDE Kota Bogor');
    	$pdf->SetDisplayMode('fullpage', 'SinglePage', 'UseNone');

    	$pdf->setPrintHeader(false);
    	$pdf->setPrintFooter(true);

        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        $pdf->setFilename($newfilename);
        $pdf->setSuratid($suratid);
		
        // set additional information
        $info = array(
            'Name' => 'TNDE',
            'Location' => 'Kota Bogor',
            'Reason' => 'Pemerintah Kota Bogor',
            'ContactInfo' => 'https://tnd.kotabogor.go.id',
        );

        $pdf->SetLeftMargin(17);
        $pdf->SetRightMargin(17);

      
      /*
        $pages_count = $pdf->setSourceFile($sourcefile);

        for ($i = 1; $i <= $pages_count; $i ++) {
            $pdf->AddPage();
            $tplIdx = $pdf->importPage($i);
            $pdf->useTemplate($tplIdx, 0, 0);
    
        }
        */

        $pages_count = $pdf->setSourceFile(StreamReader::createByFile($sourcefile));
        
        for ($i = 1; $i <= $pages_count; $i ++) {

            //import page
            $templateId = $pdf->importPage($i);

            //get the size of imported page
            $size = $pdf->getTemplateSize($templateId);
            
            $pdf->AddPage($size['orientation'], 'F4', false, false);

            $pdf->useTemplate($templateId, ['adjustPageSize' => true]);

            $pdf->setPageOrientation($size['orientation']);
    
        }

        ob_end_clean();

        if(file_exists($sourcefile))
            unlink($sourcefile);

        /*    @$rawImage = file_get_contents($sourcefile, false, $streamContext);
			if ($rawImage) {
				if (file_exists("./uploads/backup/$newFileName")) {
					unlink("./uploads/backup/$newFileName");
					file_put_contents("./uploads/backup/" . $newFileName, $rawImage);
				} else {
					file_put_contents("./uploads/backup/" . $newFileName, $rawImage);
				}
            }*/
        $pdf->Output($sourcefile, 'F');
        $pdf->Output($_SERVER['DOCUMENT_ROOT'].'/uploads/backup/'.$suratid.'.pdf', 'F');
       
	    
    }
}