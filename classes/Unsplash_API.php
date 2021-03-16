<?php
$dir = dirname( dirname(__FILE__) );
require($dir.'/api-connect-data.php');

class Unsplash_API {
	private $client_id;
	private $base_url;

	function __construct() {
		global $client_id;
		$this->client_id = $client_id;
		$this->base_url = 'https://api.unsplash.com';
	}

	public function fetch($endpoint, $params = [])	{
		if (!isset($endpoint)) {
			return false;
		}

		$default_params = array(
			'client_id' 	=> $this->client_id,
			'per_page'		=> 10,
		);

		$curl_params = http_build_query( array_merge( $default_params, $params));

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->base_url . $endpoint . '?' . $curl_params,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_POSTFIELDS => '{
				"author": "Gisela Haag"
			}',
			CURLOPT_HTTPHEADER => array(
				'Content-Type: application/json'
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);

		try {
			$decode_response = json_decode($response);

			if (is_null($decode_response)) {
			  throw ($response);
			}
		 } catch (Exception $e) {
			return json_decode(
				"{
					'error': true,
					'message': 'Caught exception: {$e->getMessage()}'
				}"
			);
		 }

		return $decode_response;
	}
}
