<?php

require('./classes/Unsplash.php');
$unsplash = new Unsplash();
define('PATH', dirname(__FILE__));
define('BASE_URL', $_SERVER['REQUEST_SCHEME']. '://'. $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']) . '/');

function show_topics() {
	global $unsplash;
	$topics = $unsplash->topics;

	if($topics) {
		foreach ($topics as $item) : ?>
			<div class="topic" data-slug="<?php echo $item->slug; ?>"><a href="#photo-grid"><?php echo $item->title; ?></a></div>
			<?php endforeach;
	} else {
		?> <div>No data 😥</div> <?php
	}
}

function show_slider() {
	global $unsplash;
	$slider = $unsplash->fetch_slider();

	if($slider) {
		foreach ($slider as $item) : ?>
			<div class="galleryData-item">
				<img
				class="galleryData-image photo"
				src="<?php echo $item->urls->regular; ?>"
				alt="<?php echo $item->alt_description; ?>"
				/>
				<div class="author-data info-gallery" data-username="<?php echo $item->user->username; ?>">
					<img
					class="author-image small"
					src="<?php echo $item->user->profile_image->small; ?>"
					alt="<?php echo $item->user->name; ?>"
					/>
					<span class="author-name"><?php echo $item->user->name; ?></span>
				</div>
			</div>
			<?php endforeach;
	} else {
		?> <div>No data 😥</div> <?php
	}
}

function show_grid() {
	global $unsplash;
	$grid = $unsplash->fetch_grid();

	if($grid) {
		foreach ($grid as $item) : ?>
			<div class="grid-item-box">
				<img
				class="photo"
						src="<?php echo $item->urls->regular; ?>"
						alt="<?php echo $item->alt_description; ?>"
					/>
					<span class="caption"><?php echo $item->alt_description; ?></span>
					<div class="author-data" data-username="<?php echo $item->user->username; ?>">
						<img
							class="author-image small"
							src="<?php echo $item->user->profile_image->small; ?>"
							alt="<?php echo $item->user->name; ?>"
						/>
						<span class="author-name"><?php echo $item->user->name; ?></span>
					</div>
				</div>
		<?php endforeach;
	} else {
		?> <div>No data 😥</div> <?php
	}
}

function show_profile() {
	global $unsplash;
	$user_info = $unsplash->user_info;
	$user_photos = $unsplash->user_photos; ?>

	<div class="author-data" data-username="<?php echo $user_info->username; ?>">
		<img
		class="author-image"
		src="<?php echo $user_info->profile_image->large; ?>"
		alt="<?php echo $user_info->name; ?>"
		/>
		<div class="author-info">
			<h1><?php echo $user_info->name; ?></h1>

			<?php if(isset($user_info->bio)) : ?>
				<p class="user-bio"><span class="icon icon-quote-left"></span><?php echo $user_info->bio; ?></p>
			<?php endif; ?>

			<?php if(isset($user_info->location)) : ?>
				<p class="user-location"><span class="icon icon-location"></span> <?php echo $user_info->location; ?></p>
			<?php endif; ?>

			<?php if(isset($user_info->portfolio_url)) : ?>
				<p class="user-portfolio">
					<span class="icon icon-earth"></span>
					<a href="<?php echo $user_info->portfolio_url; ?>" target="_blank"><?php echo $user_info->portfolio_url; ?></a>
				</p>
			<?php endif; ?>
		</div>
	</div>
	<div class="photo-grid user-grid-box" id="photo-grid">
		<h1>these are <?php echo $user_info->first_name; ?>'s photos</h1>
		<?php foreach ($user_photos as $item) : ?>
			<div class="grid-item-box">
				<img class="photo" src="<?php echo $item->urls->regular; ?>" alt="<?php echo $item->alt_description; ?>" />
				<span class="caption"><?php echo $item->alt_description; ?></span>
			</div>
		<?php endforeach; ?>
	</div> <?php
}

function show_results() {
	global $unsplash;
	$results_photos = $unsplash->fetch_query();

	if(isset($unsplash->query['order_by']) ) {
		$order = $unsplash->query['order_by'];
	}

	$query = $unsplash->query['query'];
	$total = $results_photos->total;
	$photos = $results_photos->results; ?>

	<div class="highline title left-align">
		<p>Here's the resuls for <span class="color-text"><?php echo $query; ?> </span>search</p>
		<h1><?php echo $total; ?> photos found...</h1>
	</div>
	<div class="photo-grid user-grid-box"
		<?php if(isset($unsplash->query['order_by']) ) : ?>
			data-order-by="<?php echo $order; ?>"
		<?php endif;  ?>
		data-color=""
		data-query="<?php echo $query; ?>" id="photo-grid">

		<?php foreach ($photos as $item) : ?>
			<div class="grid-item-box">
				<img class="photo" src="<?php echo $item->urls->regular; ?>" alt="<?php echo $item->alt_description; ?>" />
				<span class="caption"><?php echo $item->alt_description; ?></span>
				<div class="author-data" data-username="<?php echo $item->user->username; ?>">
					<img class="author-image small" src="<?php echo $item->user->profile_image->small; ?>" alt="<?php echo $item->user->name; ?>" />
					<span class="author-name"><?php echo $item->user->name; ?></span>
				</div>
			</div>
		<?php endforeach; ?>
	</div> <?php
}