<?php

require('../classes/Unsplash_API.php');
$unsplash = new Unsplash_API();
$username = $_POST['username'];

if ( !$username ) {
	http_response_code(400);
	die();
}

$default_query = array(
	'per_page'		=> 100,
);


// $merged_query = array_merge($default_query, $query);
$userInfo = $unsplash->fetch('/users' . $username);
$userPhotos = $unsplash->fetch('/users' . $username . '/photos', $default_query);

$found = true;
$text = "";

switch (count($userPhotos)) {
	case 0:
		$found = false;
		$text = "<h1>Sorry, $userInfo->name don't have any photo yet</h1>";
		break;
	default:
		$text = display_profile($userInfo,$userPhotos);
		break;
}

function display_profile($userInfo, $userPhotos) {
	ob_start(); ?>

	<div class="author-data" data-username="<?php echo $userInfo->username; ?>">
		<img
		class="author-image"
		src="<?php echo $userInfo->profile_image->large; ?>"
		alt="<?php echo $userInfo->name; ?>"
		/>
		<div class="author-info">
			<h1><?php echo $userInfo->name; ?></h1>

			<?php if(isset($userInfo->bio)) : ?>
				<p class="user-bio"><span class="icon icon-quote-left"></span><?php echo $userInfo->bio; ?></p>
			<?php endif; ?>

			<?php if(isset($userInfo->location)) : ?>
				<p class="user-location"><span class="icon icon-location"></span> <?php echo $userInfo->location; ?></p>
			<?php endif; ?>

			<?php if(isset($userInfo->portfolio_url)) : ?>
				<p class="user-portfolio">
					<span class="icon icon-earth"></span>
					<a href="<?php echo $userInfo->portfolio_url; ?>" target="_blank"><?php echo $userInfo->portfolio_url; ?></a>
				</p>
			<?php endif; ?>

		</div>
	</div>
	<div class="photo-grid user-grid-box">
		<h1>these are <?php echo $userInfo->first_name; ?> photos</h1>
		<?php echo display_photos($userPhotos); ?>
	</div>

	<?php return ob_get_clean();

}

function display_photos($userPhotos)
{
	ob_start();

	foreach ($userPhotos as $item) : ?>

		<div class="grid-item-box">
			<img class="photo" src="<?php echo $item->urls->small; ?>" alt="<?php echo $item->alt_description; ?>" />
			<span class="caption"><?php echo $item->alt_description; ?></span>
		</div>
<?php endforeach;

	return ob_get_clean();
}

echo json_encode(array(
	'found' 	=> $found,
	'html' 	=> $text
));
