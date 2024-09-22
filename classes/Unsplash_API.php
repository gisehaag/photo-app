<?php
require('./env.php');

class Unsplash_API {
	private $client_id;
	private $api_base_url;

	function __construct() {
		global $client_id, $dir;
		$this->client_id = $client_id;
		$this->api_base_url = 'https://api.unsplash.com';
		$this->base_path = $dir;
	}

	private function save_to_file($endpoint, $decode_response, $params) {
		$file_name = $this->get_file_name($endpoint, $params);
		$dirname = dirname($file_name);
		if(!is_dir($dirname)) {
			mkdir($dirname);
		}
		file_put_contents( $file_name, json_encode($decode_response));
	}

	private function get_file_name($endpoint, $params = []) {
		$file_name = str_replace('/','-',$endpoint);
		$file_name = substr($file_name,1);

		if(strpos($file_name, 'search') !== false) {
			$query_term = isset($params['query'])? $params['query'] : 'default';
			$page = isset($params['page']) ? $params['page'] : 1;
			$file_name .= '-' . $query_term . '-' . $page;
		}

 		return $this->base_path . '/data/' . $file_name .'.json';
	}

	private function fetch($endpoint, $params = [])	{
		$default_params = array(
			'client_id' 	=> $this->client_id,
			'per_page'		=> 21,
		);

		$curl_params = http_build_query( array_merge( $default_params, $params) );

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

		$this->save_to_file($endpoint, $decode_response, $params);
		return $decode_response;
	}

	public function get($endpoint, $params = [])	{
		if (!isset($endpoint)) {
			return false;
		}

		$file_path = $this->get_file_name($endpoint, $params);
		$this->delete_if_old_file($file_path);

		if( file_exists($file_path) ) {
			return $this->get_file_content($file_path);
		}

		return $this->fetch($endpoint, $params);
	}

	private function get_file_content($file) {
		return json_decode(file_get_contents($file));
	}

	public function get_file_age($file) {
		$today = getdate(time())[0];

		if( isset( $file ) ) {
			$date_creation = filemtime( $file );
			$interval_days = ( $today - $date_creation ) / 3600 / 24;
			return $interval_days;
		}
	}

	public function delete_if_old_file( $file ) {
		$DEFAULT_MAX_AGE = 30;

		$max_age_per_file = array(
			'topics-'		=> 7,
			'users-'			=> 7,
			'search-'		=> 1,
		);

		if( file_exists($file) ) {
			$file_age = $this->get_file_age($file);

			foreach($max_age_per_file as $name => $limit_age) {
				if(strpos($file, $name) !== false) {
					if($file_age > $limit_age){
						unlink($file);
					};
				} else if($file_age > $DEFAULT_MAX_AGE){
					unlink($file);
				}
			}
		}
	}


}
