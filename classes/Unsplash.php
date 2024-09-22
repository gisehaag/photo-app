<?php
require('./classes/Unsplash_API.php');

class Unsplash {
	//parameter stores API results
	public $topic;
	public $daily_topic;
	public $defaults;
	public $user_info;
	public $user_photos;

	function __construct() {
		$this->unsplash_api = new Unsplash_API();
		$this->set_defaults();
		$this->fetch_topics();

		if(isset($_GET['username'])) {
			$this->fetch_user();
		}

		if(isset($_GET['query'])) {
			$this->fetch_query();
		}
	}

	private function set_defaults() {
		$colors = array('black', 'white', 'yellow', 'orange', 'red', 'purple', 'magenta', 'green', 'teal', 'blue');
		$colors_index = rand(0, count($colors));

		$order = array('relevant', 'latest');

		$query = array( 'cactus', 'woman', 'milkyway', 'universe', 'blue sky', 'kitten', 'street', 'tuscany', 'landscape', 'london');
		$query_index = rand(0, count($query));

		$this->defaults = array(
			'color'			=> $colors[$colors_index],
			'query'			=> $query[$query_index],
			'order_by'		=> 'relevant',
		);
	}

	public function fetch_topics(){
		if(isset($this->topics) ){
			return $this->topics;
		}

		$topics = $this->unsplash_api->get('/topics', array(
			'per_page' => 30,
		));

		if(! isset($topics['error'])) {
			$this->topics = $topics;
			$this->set_daily_topic();
		}
	}

	function set_daily_topic() {
		$topics = $this->topics;
		$this->daily_topic = $topics[rand(0, count($topics))]->slug;
	}

	public function fetch_slider() {
		$slider = $this->unsplash_api->get("/topics/$this->daily_topic/photos", array(
			'id_or_slug'  => $this->daily_topic,
			'per_page'    => 21,
			'order_by'    => 'popular',
		));


		if(! isset($slider['error'])) {
			return $slider;
		}
	}

	public function fetch_grid() {
		$grid = $this->unsplash_api->get('/search/photos', array(
			'page'			=> 1,
			'per_page'		=>	9,
			'query'			=> $this->defaults['query'],
			'order_by'		=> $this->defaults['order_by'],
			'color'			=> $this->defaults['color'],
		));

		if(! isset($grid->error)) {
			return $grid->results;
		}
	}

	public function fetch_user() {
		$username = '/' . $_GET['username'];

		$user_info = $this->unsplash_api->get('/users' . $username);
		$user_photos = $this->unsplash_api->get('/users' . $username . '/photos', array(
			'per_page'		=> 100,
		));

		$this->user_info = $user_info;
		$this->user_photos = $user_photos;
	}

	public function fetch_query() {
		$results_photos = $this->unsplash_api->get('/search/photos', $_GET);
		$this->query = $_GET;
		return $results_photos;
	}
}

