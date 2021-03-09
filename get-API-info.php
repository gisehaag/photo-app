<?php

$data = array(
	'status' 	=> 200,
	'response' 	=> array(
		'title' 	=> 'avengers',
		'year'	=> 2012,
		)
	);

	$base_url = 'https://api.unsplash.com/';
	// $endpoint = 'photos/';
	// $endpoint = 'search/photos/';
	// $endpoint = '/collections';
	// $endpoint = '/photos/random';
	$endpoint = '/topics';
	$params = http_build_query(array(
		'client_id' 	=> 'rwZ7P33T4NDZTlXDps5LuvCa-ePEjz89ZCHGZT14Esk',
		'per_page'		=> 30,
		'query'			=> 'dogs',
		'id'				=> 'MwsVNzhFD_o',
		'orientation'	=> 'landscape'

	));


$curl = curl_init();

curl_setopt_array($curl, array(
	CURLOPT_URL => $base_url.$endpoint.'?'.$params,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'GET',
	CURLOPT_POSTFIELDS =>'{
		"author": "Gisela Haag"
	}',
	CURLOPT_HTTPHEADER => array(
		'Content-Type: application/json'
	),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;

// para buscar una foto random
// https://api.unsplash.com/photos/random?client_id=rwZ7P33T4NDZTlXDps5LuvCa-ePEjz89ZCHGZT14Esk

// para buscar por *username*
// https://api.unsplash.com/users/*jerry_318*?client_id=rwZ7P33T4NDZTlXDps5LuvCa-ePEjz89ZCHGZT14Esk
