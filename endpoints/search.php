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

$default_query = array(
	'per_page'		=> 8,
);


$merged_query = array_merge($default_query, $query);
$search = $unsplash->fetch($endpoint . '/photos', $merged_query);

$search_string = json_encode($search);

if( isset($search->results) ) {
	$results = $search->results;
} else {
	$results = $search;
}

$found = true;
$text = "";

switch (count($results)) {
	case 0:
		$found = false;
		$text = "<h1>Sorry, I don't found any results</h1>";
		break;
	case 8:
		$text = "<h1>those are the results</h1>" . display_results($results);
		break;
	case 9:
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
	'found' 	=> $found,
	'html' 	=> $text
));
