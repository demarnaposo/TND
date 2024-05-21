<?php 

if ( ! function_exists('__isPostRequest')) {
	function __isPostRequest() {
		if ($_SERVER['REQUEST_METHOD'] == "POST") {
			return true; 
		}

		return false; 
	}
}

if ( ! function_exists('__show_404')) {
	function __show_404() {
		$codeIgniter = get_instance(); 
		$codeIgniter->output
			->set_status_header(404)
	        ->set_content_type('application/json')
	        ->set_output(json_encode(array('status' => 'Request not found')));
	}
}

if ( ! function_exists('__return_json')) {
	function __return_json($array, $statusCode=200) {
		$codeIgniter = get_instance(); 
		$codeIgniter->output
			->set_status_header($statusCode)
	        ->set_content_type('application/json')
	        ->set_output(json_encode($array));
	}
}