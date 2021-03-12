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
	'order_by'		=> 'lastest',
);

$merged_query = array_merge( $default_query, $query);
$search = $unsplash->fetch('/search/photos', $merged_query);
$results = json_decode($search)->results;

foreach ($results as $item) : ?>
	<div class="image">
		<img
			src="<?php echo $item->urls->small; ?>"
			alt="<?php echo $item->alt_description; ?>"
		/>
		<span class="caption"><?php echo $item->alt_description; ?></span>
		<div class="author-data">
			<a href="">
				<img
					class="author-image"
					src="<?php echo $item->user->profile_image->small; ?>"
					alt="<?php echo $item->user->name; ?>"
				/>
			</a>
			<span class="author-name"><?php echo $item->user->name; ?></span>
		</div>
	</div>
<?php endforeach;


