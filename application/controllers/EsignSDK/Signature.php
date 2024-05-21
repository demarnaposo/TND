<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 

class Signature extends CI_Controller
{
	/**
	 * Construct 
	 */
	public function __construct()
	{
		parent::__construct(); 

		$this->load->library('esign'); 
		$this->load->model('signature_model'); 
	}

	/**
	 * Do Signature Document 
	 * 
	 * @return json
	 */
	public function index()
	{
		// Check Request Method
		if ($_SERVER['REQUEST_METHOD'] !== "POST")
			$this->output->set_status_header('403'); // Show Forbidden
		
		// Generate URL To File PDF 
		$pdf = $this->generatePDF($this->input->post('pdf_path')); 
		// Set UNSIGNED PDF 
		$this->esign->setDocument($pdf);
		// Config Apperance
		$this->setAppearance([]); 
		// Sign 
		$sign = $this->esign->sign('3201290705750004', $_POST['passphrase']); 

		if ($sign) 
		{
			// Show Succes Output 
			$this->output
			    ->set_content_type('application/json')
			    ->set_output(json_encode(array('status' => 'success')));
		}
		else
		{
			// Log To Database 
			$this->signature_model->create([
				'file' => $pdf, 
				'error_response' => $this->esign->getError(), 
				'created_at' => date('YmdHis')
			]); 

			// Show Error Output
			$this->output
				->set_status_header('500')
				->set_content_type('application/json')
			    ->set_output(json_encode([
			    	'status' 	=> 'error', 
			    	'message' 	=> $this->esign->getError()
			    ]));
		}
	}

	/**
	 * Set Appearance 
	 *
	 * @param 	array $config 
	 * @return 	void
	 */
	protected function setAppearance($config = [])
	{
		$this->esign->setAppearance(
			$x = 43,
			$y = 28,
			$width = 550,
			$height = 130,
			$page = 1,
			$spesimen = null,
			$qr = site_url('verification')
		);
	}

	/**
	 * Generate PDD 
	 *
	 * @param 	string
	 * @return 	string
	 */
	protected function generatePDF($path)
	{
		// Get Binnary Content 
		$contents = file_get_contents($path); 
		// Set Random File Name 
		$fileName = "./uploads/EMPTY_SIG/" . date('YmdHis').rand(1000,9999) . '.pdf';
		// Put File PDF 
		file_put_contents($fileName, $contents); 

		return $fileName; 
	}
}