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
		<link rel="stylesheet" href="style.css" />
		<title>Photo Gallery</title>
	</head>
	<body>
		<header>
			<h1>Photo Gallery</h1>
		</header>

		<div class="modal"></div>

		<div class="app-box">
			<div class="menu">
				<div class="topic-list"><?php show_topics(); ?></div>
			</div>
			<div class="gallery owl-carousel owl-theme">
				<?php show_slider(); ?>
			</div>
			<div class="search-box">
				<form action="" id="search-form">
					<label for="parameter"
						><input type="text" name="parameter" id="input" placeholder="look for any pic toy want"
					/></label>
					<input type="submit" value="go fot it!" id="search" />
				</form>
			</div>
			<div
				class="photo-grid"
				data-color="<?php echo $unsplash->defaults['color']; ?>"
				data-orientation="<?php echo $unsplash->defaults['orientation']; ?>"
				data-query="<?php echo $unsplash->defaults['query']; ?>"
				data-order-by="<?php echo $unsplash->defaults['order_by']; ?>"
			>
				<?php show_grid(); ?>
			</div>
			<button id="more-results">loading more photos</button>
		</div>

		<script
			src="https://code.jquery.com/jquery-3.6.0.min.js"
			integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
			crossorigin="anonymous"
		></script>
		<script
			src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"
			integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw=="
			crossorigin="anonymous"
		></script>
		<script src="https://test.gisehaag.com/assets/js/footer.js"></script>
		<script src="./js/scritp.js"></script>
	</body>
</html>
