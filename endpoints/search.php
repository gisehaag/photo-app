<?php

require('../classes/Unsplash_API.php');
$unsplash = new Unsplash_API();
$query = json_decode($_POST['query'], true);
$endpoint = $_POST['endpoint'];

$havent_endpoint =  (!isset($endpoint) or empty(trim($endpoint)));
$havent_query = (!isset($query['query']) or empty(trim($query['query'])));

if ( $havent_query && $havent_endpoint ) {
	http_response_code(400);
	die();
}

$default_query = [];
$search_word = '';
$topic = '';

if(isset($query['query'])) {
	$search_word = $query['query'];
}

if ($havent_query) {
	$default_query = array(
		'per_page'		=> 9,
		'id_or_slug'	=> $query['id_or_slug'],
	);
	$topic = $query['id_or_slug'];
}

if($havent_endpoint) {
	$default_query = array(
		'per_page'		=> 8,
		'query'			=> $query['query'],
	);
}

$merged_query = array_merge($default_query, $query);
$search = $unsplash->get($endpoint . '/photos', $merged_query);
$search_string = json_encode($search);


if( isset($search->results) ) {
	$results = $search->results;
} else {
	$results = $search;
}

// var_dump($results); // trae ok los 8 resultados del topic

$found = true;
$title = "";
$text = "";

switch (count($results)) {
	case 0:
		$found = false;
		$title = "<h1>Sorry, I don't found any results</h1>";
		break;
	case 8:
		$text = "<h1>those are the results</h1>";
		$text = display_results($results);
		break;
	default:
		$title = "<h1>Here's the resuls for <span class=\"color-text\"> {$topic} </span></h1>";
		$text = display_results($results);
		break;
}

function display_results($results)
{
	ob_start();

	foreach ($results as $item) : ?>

		<div class="grid-item-box">
			<img class="photo" src="<?php echo $item->urls->small; ?>" alt="<?php echo $item->alt_description; ?>" />
			<span class="caption"><?php echo $item->alt_description; ?></span>
			<div class="author-data" data-username="<?php echo $item->user->username; ?>">
				<img class="author-image small" src="<?php echo $item->user->profile_image->small; ?>" alt="<?php echo $item->user->name; ?>" />
				<span class="author-name"><?php echo $item->user->name; ?></span>
			</div>
		</div>
<?php endforeach;

	return ob_get_clean();
}

echo json_encode(array(
	'found' 			=> $found,
	'title'  		=> $title,
	'html' 			=> $text,
	'topic'			=> $topic,
	'search_word' 	=> $search_word,
));
