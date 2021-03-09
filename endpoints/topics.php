<?php

require('../classes/Unsplash_API.php');
$unsplash = new Unsplash_API();

$topics = $unsplash->fetch('/topics', array('per_page' => 30));
echo $topics;


