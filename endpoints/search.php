<?php

require('../classes/Unsplash_API.php');
$unsplash = new Unsplash_API();
$query = json_decode($_POST['query'], true);

if( !isset($query['query']) || empty(trim($query['query']))) {
	http_response_code(400);
	die();
}

$default_query = array(
	'per_page'		=> 8,
);

$merged_query = array_merge( $default_query, $query);

$search = $unsplash->fetch('/search/photos', $merged_query);

echo $search;


