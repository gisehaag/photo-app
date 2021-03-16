<?php

require('../classes/Unsplash_API.php');
$unsplash = new Unsplash_API();
$query = json_decode($_POST['query'], true);

if (!isset($query['query']) || empty(trim($query['query']))) {
	http_response_code(400);
	die();
}

$default_query = array(
	'per_page'		=> 8,
);

$merged_query = array_merge($default_query, $query);
$search = $unsplash->fetch('/search/photos', $merged_query);

$results = $search->results;
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
			<div class="author-data">
				<a href="">
					<img class="author-image" src="<?php echo $item->user->profile_image->small; ?>" alt="<?php echo $item->user->name; ?>" />
				</a>
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
