<?php defined('BASEPATH') OR exit('no direct script access allowed'); 

class Esign_api_gateway
{
	/**
	 * [$url description]
	 * 
	 * @var [type]
	 */
	protected $url; 

	/**
	 * [$token description]
	 * 
	 * @var [type]
	 */
	protected $token; 

	/**
	 * [$codeIgniter description]
	 * 
	 * @var [type]
	 */
	protected $codeIgniter; 

	/**
	 * [__construct description]
	 */
	public function __construct()
	{
		$this->url = 'http://103.14.229.27/api/sign/pdf'; 
		$this->token = 'a29taW5mbzprb21pbmZv'; 

		$this->codeIgniter = get_instance(); 
	}

	/**
	 * [actionSuccess description]
	 * 
	 * @return [type] [description]
	 */
	protected function actionSuccess($response)
	{
		// Generate Random String for File Name
		$newFileName = random_string('alpha', 30) . '.pdf';
		$isUnique = false; 
		while ( ! $isUnique) {
			if (file_exists("./uploads/SIGNED/$newFileName")) {
				$newFileName = random_string('alpha', 30) . '.pdf';
			} else {
				$isUnique = true;
			}
		} 

		file_put_contents("./uploads/SIGNED/$newFileName", $response); 

		$this->storeDatabase($newFileName); 
	}

	/**
	 * [storeDatabase description]
	 * 
	 * @param  [type] $fileName [description]
	 * @return [type]           [description]
	 */
	protected function storeDatabase($fileName)
	{
		$penandatangan_id = $this->codeIgniter->input->post('penandatangan_id'); 

		$this->codeIgniter->db->where('penandatangan_id', $penandatangan_id); 
		$this->codeIgniter->db->update('penandatangan', [
			'file_path' => $fileName, 
			'status' => 'Sudah Ditandatangani'
		]);
	}

	/**
	 * [actionResponse description]
	 * 
	 * @param  [type] $response [description]
	 * @return [type]           [description]
	 */
	protected function actionResponse($response)
	{
		$json = json_decode($response); 

		if (is_null($json)) {
			// Success Response
			$this->actionSuccess($response); 
		} else{
			/**
			 * Store Error Log 
			 */
			$data = $this->codeIgniter->db->insert('penandatangan_error_log', [
				'penandatangan_id' => $this->codeIgniter->input->post('penandatangan_id'), 
				'username' => $this->codeIgniter->session->userdata('username'), 
				'error' => $json->error, 
				'created_at' => date('Y-m-d H:i:s')
			]); 

			$this->codeIgniter->output
				->set_status_header(400)
		        ->set_content_type('application/json')
		        ->set_output(json_encode($json)); 
		}
	}

	/**
	 * [generatePDF description]
	 * 
	 * @return [type] [description]
	 */
	protected function generatePDF()
	{
		$pdfPath = $this->codeIgniter->input->post('pdf_path'); 
		$binnaryContent = file_get_contents($pdfPath);

		$newFileName = random_string('alpha', 30) . '.pdf';
		$isUnique = false; 
		while ( ! $isUnique) {
			if (file_exists("./uploads/PDF/$newFileName")) {
				$newFileName = random_string('alpha', 30) . '.pdf';
			} else {
				$isUnique = true;
			}
		}

		file_put_contents("uploads/PDF/$newFileName", $binnaryContent);

		return $newFileName; 
	}

	/**
	 * [execute description]
	 * 
	 * @return [type] [description]
	 */
	public function execute()
	{
		try {
			$curl = curl_init();

			$pdf = $this->generatePDF(); 

			curl_setopt_array($curl, array(
				CURLOPT_URL => $this->url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS => array(
					'file' => new CurlFile("./uploads/PDF/$pdf", 'application/pdf', $pdf),
					'nik' => $this->codeIgniter->session->userdata('username'), 
					'passphrase' => $this->codeIgniter->input->post('passphrase'),
					'tampilan' => 'invisible'
				),
				CURLOPT_HTTPHEADER => array(
					"authorization: Basic $this->token",
					"cache-control: no-cache",
					"content-type: multipart/form-data",
					"postman-token: a6a7a0f8-0198-4a74-52c7-9b569f0b676d"
				),
			));


			$response = curl_exec($curl);
			$err = curl_error($curl);

			if ($response == false) {
				throw new Exception(curl_error($curl), curl_errno($curl));
			}

			curl_close($curl);

			// Delete Trash File 
			if (file_exists("./uploads/PDF/$pdf")) {
				unlink("./uploads/PDF/$pdf"); 
			}

			$this->actionResponse($response);


		} catch (Exception $e) {
			trigger_error(sprintf(
	        'Curl failed with error #%d: %s',
	        $e->getCode(), $e->getMessage()),
	        E_USER_ERROR);
		}
 
	}
}