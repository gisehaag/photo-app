<?php require('header.php'); ?>
	<div class="user-container" id="user-container">
		<?php show_profile(); ?>
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
		data-query="<?php echo $unsplash->defaults['query']; ?>"
		data-order-by="<?php echo $unsplash->defaults['order_by']; ?>"
		id="photo-grid"
	>
	</div>

	<button id="more-results" style="display: none">loading more photos</button>
<?php require('footer.php'); ?>
