<?php require('./functions.php'); ?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />

		<link rel="stylesheet" href="https://test.gisehaag.com/assets/css/icons.css" />
		<link
			rel="stylesheet"
			href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"
			integrity="sha512-tS3S5qG0BlhnQROyJXvNjeEM4UpMXHrQfTGmbQ1gKmelCxlSEBUaxhRBj/EFTzpbP4RVSrpEikbmdJobCvhE3g=="
			crossorigin="anonymous"
		/>
		<link
			rel="stylesheet"
			href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.css"
			integrity="sha512-OTcub78R3msOCtY3Tc6FzeDJ8N9qvQn1Ph49ou13xgA9VsH9+LRxoFU6EqLhW4+PKRfU+/HReXmSZXHEkpYoOA=="
			crossorigin="anonymous"
		/>
		<link rel="stylesheet" href="<?php echo BASE_URL . 'style.css'; ?>" />
		<title>Photo Gallery</title>
	</head>
	<body>
		<header class="header-wrapper flex">
			<a href="/">
				<span class="icon-camera-retro"></span>
			</a>
				<div>
						<h1 class="">My photo gallery app</h1>
					<p>Here's a wide source of freely-usable images.</p>
					<p class="text">You can use those images for all propose and it is free, you can check the license
						<a class="color-text" href=" https://unsplash.com/license" target="_blank">here</a>. </p>
					<p class="text">This app is using the <a class="color-text" href="https://unsplash.com/documentation" target="_blank">Unsplash API</a>.</p>
				</div>
		</header>
		<div class="modal"></div>
		<div class="app-box">