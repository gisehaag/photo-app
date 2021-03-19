<?php
$dir = dirname( dirname(__FILE__) );
require($dir.'/api-connect-data.php');

class Unsplash_API {
	private $client_id;
	private $api_base_url;

	function __construct() {
		global $client_id, $dir;
		$this->client_id = $client_id;
		$this->api_base_url = 'https://api.unsplash.com';
		$this->base_path = $dir;
	}

	private function fetch($endpoint, $params = [])	{
		$default_params = array(
			'client_id' 	=> $this->client_id,
			'per_page'		=> 21,
		);

		$curl_params = http_build_query( array_merge( $default_params, $params));

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->api_base_url . $endpoint . '?' . $curl_params,
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

		file_put_contents($this->base_path . '/data/' . str_replace('/','-',$endpoint) . '.json', json_encode($decode_response));
		return $decode_response;
	}

	public function get($endpoint, $params = [])	{
		if (!isset($endpoint)) {
			return false;
		}

		$file = $this->base_path . '/data/' . str_replace('/','-',$endpoint) . '.json';


		if( file_exists($file) ) {
			return json_decode(file_get_contents($file));
		}

		//Si llegue a este punto
		return $this->fetch($endpoint, $params);
	}
}
