<?php

class Unsplash_API {

	public $client_id;
	public $base_url;

	function __construct() {
		$this->client_id = 'rwZ7P33T4NDZTlXDps5LuvCa-ePEjz89ZCHGZT14Esk';
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
		return $response;
	}
}


// $data = array(
// 	'status' 	=> 200,
// 	'response' 	=> array(
// 		'title' 	=> 'avengers',
// 		'year'	=> 2012,
// 		)
// 	);

// $endpoint = 'photos/';
// $endpoint = 'search/photos/';
// $endpoint = '/collections';
// $endpoint = '/photos/random';

// para buscar una foto random
// https://api.unsplash.com/photos/random?client_id=rwZ7P33T4NDZTlXDps5LuvCa-ePEjz89ZCHGZT14Esk

// para buscar por *username*
// https://api.unsplash.com/users/*jerry_318*?client_id=rwZ7P33T4NDZTlXDps5LuvCa-ePEjz89ZCHGZT14Esk
