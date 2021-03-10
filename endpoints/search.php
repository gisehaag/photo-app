<?php

require('../classes/Unsplash_API.php');
$unsplash = new Unsplash_API();

if( !isset($_POST['parameter']) || empty(trim($_POST['parameter']))) {
	http_response_code(400);
	die();
}

$query = $_POST['parameter'];

$search = $unsplash->fetch('/search/photos', array(
	'per_page' 	=> 8,
	'query'		=> $query,
));

echo $search;


