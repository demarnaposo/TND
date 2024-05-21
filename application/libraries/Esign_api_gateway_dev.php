<?php defined('BASEPATH') or exit('no direct script access allowed');

class Esign_api_gateway_dev
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
		$this->url = 'https://esign.kotabogor.go.id/api/sign/pdf';
		//$this->token = 'a29taW5mb190bmQ6a29taW5mb190bmQ7'; 
		$this->token = 'a29taW5mb190bmQ6a29taW5mb190bmQ7';


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
		// 		$newFileName = random_string('alpha', 30) . '.pdf';
		$newFileName = $this->codeIgniter->input->post('surat_id') . '.pdf';
		$surt = $this->codeIgniter->input->post('surat_id');
		//lala


		$isUnique = false;
		while (!$isUnique) {
			if (file_exists("./uploads/SIGNED/$newFileName")) {
				$newFileName = $this->codeIgniter->input->post('surat_id') . '.pdf';
				// $newFileName = random_string('alpha', 30) . '.pdf';


			} else {
				$isUnique = true;
			}
		}

		//file_put_contents("./uploads/PDF/$newFileName", $response); 

		$this->storeDatabase($newFileName);
		file_put_contents("./uploads/SIGNED/$newFileName", $response);

		//$newFileName = $this->codeIgniter->input->post('surat_id') . '.pdf';
		$newFileName = $this->codeIgniter->input->post('surat_id') . '.pdf';
		$surt = $this->codeIgniter->input->post('surat_id');
		//lala
		$curl = curl_init();

		curl_setopt_array($curl, array(
			//  CURLOPT_URL => 'https://api.lite.smartgov.teknolab.id/integration/client/ticket/document-signed',
			CURLOPT_URL => 'https://ticket-api.citigov.id/integration/client/ticket/document-signed',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => array('symetric_key' => 'AHm0GDp3vL2vytYkEzmgyL3ZPD6shUD0', 'surat_id' => $surt, 'dokumen_url[0]' => 'https://tnd.kotabogor.go.id/uploads/SIGNED/' . $newFileName),

		));

		$response = curl_exec($curl);


		curl_close($curl);
		$data = $this->codeIgniter->db->insert('asinan_log', [
			//'penandatangan_id' => $this->codeIgniter->input->post('penandatangan_id'),
			'username' => $this->codeIgniter->session->userdata('username'),
			'error' => $response,
			'surat_id' => $surt,
			'created_at' => date('Y-m-d H:i:s')
		]);
	}

	/*protected function actionSuccess($response)
	{
		// Generate Random String for File Name
// 		$newFileName = random_string('alpha', 30) . '.pdf';
        	$newFileName = $this->codeIgniter->input->post('surat_id') . '.pdf';
		$isUnique = false; 
		while ( ! $isUnique) {
			if (file_exists("./uploads/SIGNED/$newFileName")) {
				// $newFileName = random_string('alpha', 30) . '.pdf';
				$newFileName = $this->codeIgniter->input->post('surat_id') . '.pdf';
			} else {
				$isUnique = true;
			}
		} 

		//file_put_contents("./uploads/PDF/$newFileName", $response); 
		//file_put_contents("./uploads/SIGNED/$newFileName", $response); 
		
		$this->storeDatabase($newFileName); 
		file_put_contents("./uploads/SIGNED/$newFileName", $response); 
	}*/

	/**
	 * [storeDatabase description]
	 * 
	 * @param  [type] $fileName [description]
	 * @return [type]           [description]
	 */
	protected function storeDatabase($fileName)
	{
		$penandatangan_id = $this->codeIgniter->input->post('penandatangan_id');
		$nip =  $this->codeIgniter->session->userdata('username');
		$hash = $this->codeIgniter->input->post('passphrase');
		$ss = $this->codeIgniter->input->post('surat_id');
		$created_at = date('Y-m-d H:i:s');

		$this->codeIgniter->db->where('penandatangan_id', $penandatangan_id);
		$this->codeIgniter->db->update('penandatangan', [
			//'file_path' => $fileName, 
			'status' => 'Sudah Ditandatangani'
		]);
		$this->codeIgniter->db->where('nip', $nip);
		$this->codeIgniter->db->update('ttd_history', [
			'hash' => $hash,
			'created_at' => $created_at
		]);

		$this->codeIgniter->db->where('surat_id', $ss);
		$this->codeIgniter->db->update('disposisi_suratkeluar', [
			//'file_path' => $fileName, 
			'status' => 'Selesai'
		]);

		$this->codeIgniter->db->where('surat_id', $ss);
		$this->codeIgniter->db->update('tembusan_surat', [
			//'file_path' => $fileName, 
			'status' => 'Selesai'
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
		} else {
			/**
			 * Store Error Log 
			 */
			$data = $this->codeIgniter->db->insert('penandatangan_error_log', [
				'penandatangan_id' => $this->codeIgniter->input->post('penandatangan_id'),
				'username' => $this->codeIgniter->session->userdata('username'),
				'error' => $json->error,
				'surat_id' =>  $this->codeIgniter->input->post('surat_id'),
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
		// *fungsi lama
		// //$pdfPath = $this->codeIgniter->input->post('pdf_path'); 
		// $pdfUrl = $this->codeIgniter->input->post('pdf_path');


		// $arrContextOptions = array(
		// 	"ssl" => array(
		// 		"verify_peer" => false,
		// 		"verify_peer_name" => false,
		// 	),
		// );

		// $binnaryContent = file_get_contents($pdfUrl, false, stream_context_create($arrContextOptions));

		// //$binnaryContent = file_get_contents($pdfPath);

		// //	$newFileName = random_string('alpha', 30) . '.pdf';
		// $newFileName = $this->codeIgniter->input->post('surat_id') . '.pdf';
		// $isUnique = false;
		// while (!$isUnique) {
		// 	if (file_exists("./uploads/PDF/$newFileName")) {
		// 		// $newFileName = random_string('alpha', 30) . '.pdf';
		// 		$newFileName = $this->codeIgniter->input->post('surat_id') . '.pdf';
		// 	} else {
		// 		$isUnique = true;
		// 	}
		// }

		// file_put_contents("./uploads/PDF/$newFileName", $binnaryContent);

		// return $newFileName;
		// *end fungsi lama

		// fungsi baru
		if ($this->codeIgniter->input->post('surat_id') && $this->codeIgniter->input->post('pdf_path')) {
			$imageUrl = $this->codeIgniter->input->post('pdf_path');
			$newFileName = $this->codeIgniter->input->post('surat_id') . '.pdf';
			$streamContext = stream_context_create([
				'ssl' => [
					'verify_peer' => false,
					'verify_peer_name' => false
				]
			]);
			@$rawImage = file_get_contents($imageUrl, false, $streamContext);
			if ($rawImage) {
				if (file_exists("./uploads/backup/$newFileName")) {
					unlink("./uploads/backup/$newFileName");
					file_put_contents("./uploads/backup/" . $newFileName, $rawImage);
				} else {
					file_put_contents("./uploads/backup/" . $newFileName, $rawImage);
				}
				return $newFileName;
			} else {
				$error = error_get_last();
				return "HTTP request failed. Error was: " . $error['message'];
			}
		}
		return "error";
		// end fungsi baru
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

			// $pdf = $this->codeIgniter->input->post('surat_id') . '.pdf';
			$pdf = $this->generatePDF();

			curl_setopt_array($curl, array(
				CURLOPT_URL => $this->url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS => array(
					'file' => new CurlFile("./uploads/backup/$pdf", 'application/pdf', $pdf),
					'nik' => $this->codeIgniter->session->userdata('nik'),
					'passphrase' => $this->codeIgniter->input->post('passphrase'),
					'tampilan' => 'invisible'
				),
				CURLOPT_HTTPHEADER => array(
					"authorization: Basic $this->token",
					"cache-control: no-cache",
					"content-type: multipart/form-data",
					"postman-token: a6a7a0f8-0198-4a74-52c7-9b569f0b676d"
				),
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_SSL_VERIFYHOST => false,
				//CURLOPT_SSL_VERIFYPEER => true,
				//CURLOPT_SSL_VERIFYHOST => 2,
				// CURLOPT_CAINFO => getcwd() . "./uploads/kotabogor-go-id.pem",
			));



			$response = curl_exec($curl);
			$err = curl_error($curl);
			// var_dump($response);

			if ($response == false) {
				throw new Exception(curl_error($curl), curl_errno($curl));
			}

			curl_close($curl);

			// Delete Trash File 
			// if (file_exists("./uploads/PDF/$pdf")) {
			// 	unlink("./uploads/PDF/$pdf"); 
			// }

			$this->actionResponse($response);
		} catch (Exception $e) {
			trigger_error(
				sprintf(
					'Curl failed with error #%d: %s',
					$e->getCode(),
					$e->getMessage()
				),
				E_USER_ERROR
			);
		}
	}
}
