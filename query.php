<?php require('header.php'); ?>
	<div class="menu">
		<div class="topic-list"><?php show_topics(); ?></div>
	</div>

	<div class="results-container" id="result-container">
		<?php  show_results(); ?>
	</div>

	<button id="load-more">load more</button>

	<div class="search-box">
		<form action="<?php echo BASE_URL . 'query.php'?>" id="search-form">
			<label for="parameter"
				><input type="text" name="parameter" id="input" placeholder="look for any pic toy want"
			/></label>
			<input type="submit" value="go fot it!" id="search" />
		</form>
	</div>

	<button id="more-results" style="display: none">loading more photos</button>

<?php require('footer.php'); ?>
