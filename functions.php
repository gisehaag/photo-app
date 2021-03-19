<?php

require('./classes/Unsplash.php');
$unsplash = new Unsplash();

function show_topics() {
	global $unsplash;

	$topics = $unsplash->topics;
	// todo: ver qué hago con los href


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
	$slider = $unsplash->slider;

	//todo: hacer dinamica la seleccion de esas imagenes destacadas
	// creo que usando el data stored podría ser una manera de resolverlo
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
	$grid = $unsplash->grid;
	//todo: colorear resultados según el día de la semana
	// creo que usando el data stored podría ser una manera de resolverlo
	if($grid) {
		foreach ($grid as $item) : ?>
			<div class="grid-item-box">
				<img
				class="photo"
						src="<?php echo $item->urls->small; ?>"
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
	<div class="photo-grid user-grid-box">
		<h1>these are <?php echo $user_info->first_name; ?>'s photos</h1>
		<?php foreach ($user_photos as $item) : ?>
			<div class="grid-item-box">
				<img class="photo" src="<?php echo $item->urls->small; ?>" alt="<?php echo $item->alt_description; ?>" />
				<span class="caption"><?php echo $item->alt_description; ?></span>
			</div>
		<?php endforeach; ?>
	</div> <?php
}

function show_results() {
	global $unsplash;
	$results_photos = $unsplash->results_photos;

	if(isset($unsplash->query['order_by']) ) {
		$order = $unsplash->query['order_by'];
	}

	if(isset($unsplash->query['orientation'])) {
		$orientation = $unsplash->query['orientation'];
	}

	$query = $unsplash->query['query'];

	$total = $results_photos->total;
	$total_pages = $results_photos->total_pages;
	$photos = $results_photos->results; ?>

	<p class="left-align title">Here's the resuls for <span class="color-text"><?php echo $query; ?> </span>search</p>
  	<h1 class="left-align title"><?php echo $total; ?> photos found...</h1>
	<div class="photo-grid user-grid-box"

		<?php if(isset($unsplash->query['order_by']) ) : ?>
			data-order-by="<?php echo $order; ?>"
		<?php endif;  ?>

		data-color=""

		<?php if(isset($unsplash->query['orientation']) ) : ?>
			data-orientation="<?php echo$orientation; ?>"
		<?php endif;  ?>

		data-query="<?php echo $query; ?>" id="photo-grid">

		<?php foreach ($photos as $item) : ?>
			<div class="grid-item-box">
				<img class="photo" src="<?php echo $item->urls->small; ?>" alt="<?php echo $item->alt_description; ?>" />
				<span class="caption"><?php echo $item->alt_description; ?></span>
				<div class="author-data" data-username="<?php echo $item->user->username; ?>">
					<img class="author-image small" src="<?php echo $item->user->profile_image->small; ?>" alt="<?php echo $item->user->name; ?>" />
					<span class="author-name"><?php echo $item->user->name; ?></span>
				</div>
			</div>
		<?php endforeach; ?>
	</div> <?php
}