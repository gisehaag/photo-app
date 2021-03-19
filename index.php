<?php require('header.php'); ?>

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

	<div class="photo-grid-wrapper">
		<h1 class="title left-align">Featured photos of the week: <span class="color-text"><?php echo $unsplash->defaults['query']; ?></span></h1>
		<div
			class="photo-grid"
			data-color="<?php echo $unsplash->defaults['color']; ?>"
			data-orientation="<?php echo $unsplash->defaults['orientation']; ?>"
			data-query="<?php echo $unsplash->defaults['query']; ?>"
			data-order-by="<?php echo $unsplash->defaults['order_by']; ?>"
			id="photo-grid"
		>
			<?php show_grid(); ?>
		</div>
	</div>
	<button id="more-results">loading more photos</button>

<?php require('footer.php'); ?>
