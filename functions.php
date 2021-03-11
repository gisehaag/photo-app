<?php

require('./classes/Unsplash.php');
$unsplash = new Unsplash();

function show_topics() {
	global $unsplash;
	$topics = $unsplash->topics;
	// todo: ver qué hago con los href

	foreach ($topics as $item) : ?>
		<div><a href="#"><?php echo $item->title; ?></a></div>

	<?php endforeach;
}

function show_slider() {
	global $unsplash;
	$slider = $unsplash->slider;

	//todo: hacer dinamica la seleccion de esas imagenes destacadas
	// creo que usando el data stored podría ser una manera de resolverlo

	foreach ($slider as $item) : ?>
		<div class="galleryData-item">
			<img
				class="galleryData-image"
				src="<?php echo $item->urls->regular; ?>"
				alt="<?php echo $item->alt_description; ?>"
			/>
			<div class="author-data info-gallery">
				<img
					class="author-image"
					src="<?php echo $item->user->profile_image->small; ?>"
					alt="<?php echo $item->user->name; ?>"
				/>
				<span class="author-name"><?php echo $item->user->name; ?></span>
			</div>
		</div>

	<?php endforeach;
}

function show_grid() {
	global $unsplash;
	$grid = $unsplash->grid;
	//todo: colorear resultados según el día de la semana
	// creo que usando el data stored podría ser una manera de resolverlo

	foreach ($grid as $item) : ?>
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
}