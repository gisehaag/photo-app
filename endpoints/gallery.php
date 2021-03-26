<?php

require('../classes/Unsplash_API.php');
$unsplash = new Unsplash_API();

$gallery = $unsplash->fetch('/photos/random', array(
	'count' => 3,

));
echo $gallery;


