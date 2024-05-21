<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller
{
	public function __construct()
	{
		parent::__construct(); 

		$this->load->library('token'); 
	}

	public function index()
	{
		$file = file_get_contents('https://tnd.kotabogor.go.id/uploads/SIGNED_BACKUP/gSbkdZyTiHjhBlwmNrxuIpOLKMFQve.pdf'); 
	}	

	public function in()
	{
		$pdfUrl = 'https://tnd.kotabogor.go.id/uploads/SIGNED_BACKUP/gSbkdZyTiHjhBlwmNrxuIpOLKMFQve.pdf'; 

		$arrContextOptions=array(
	    	"ssl"=>array(
		        "verify_peer"=>false,
		        "verify_peer_name"=>false,
		    ),
		);  

		$binnaryContent = file_get_contents($pdfUrl, false, stream_context_create($arrContextOptions));

		var_dump($binnaryContent); 
	}

	public function out()
	{
		$pdfUrl = 'https://tnd.w3b-project.com/uploads/PDF/INT-4.pdf'; 

		$arrContextOptions=array(
	    	"ssl"=>array(
		        "verify_peer"=>false,
		        "verify_peer_name"=>false,
		    ),
		);  

		$binnaryContent = file_get_contents($pdfUrl, false, stream_context_create($arrContextOptions));

		var_dump($binnaryContent); 
	}

}