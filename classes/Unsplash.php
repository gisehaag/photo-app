<?php
require('./classes/Unsplash_API.php');

class Unsplash {
	//parameter stores API results
	public $topic;
	public $daily_topic;
	public $slider;
	public $defaults;
	public $user_info;
	public $user_photos;

	function __construct() {
		$this->unsplash_api = new Unsplash_API();
		//todo: guardar los json de las consultas un determinado tiempo en local
		// $this->get_stored_data();
		$this->set_defaults();
		$this->fetch_topics();
		$this->fetch_slider();
		$this->fetch_grid();

		if(isset($_GET['username'])) {
			$this->fetch_user();
		}

		if(isset($_GET['query'])) {
			$this->fetch_query();
		}
	}

	private function set_defaults() {

		// todo: dinamic query
		$colors = array('black', 'white', 'yellow', 'orange', 'red', 'purple', 'magenta', 'green', 'teal', 'blue');
		$orientations = array('landscape', 'portrait', 'squarish');
		$order = array('relevant', 'latest');
		$query = array('cactus', 'woman', 'milkyway', 'universe', 'blue sky', 'kitten', 'street', 'tuscany', 'landscape', 'london');

		$this->defaults = array(
			'color'			=> 'blue',
			'orientation' 	=> 'landscape',
			'query'			=> 'milkyway',
			'order_by'		=> 'relevant',
		);
	}

	public function get_stored_data() {
		// todo: los pasos de la función:
		// se fija si el archivo existe,
		// si existe, se fija qué tan viejo es y si es muy viejo lo borra,
		// y como no va a existir, entra en el else que describo abajo:
		// sino existe lo crea y hace el fetch para completar los datos
		// despues asigna el contenido a la variable en cuestion: topics, slider, etc.
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
		if(isset($this->slider) ){
			return $this->slider;
		}

		$slider = $this->unsplash_api->get("/topics/$this->daily_topic/photos", array(
			'id_or_slug'  => $this->daily_topic,
			'orientation' => 'landscape',
			'per_page'    => 3,
			'order_by'    => 'popular',
		));

		if(! isset($slider['error'])) {
			$this->slider = $slider;
		}
	}

	public function fetch_grid() {
		if(isset($this->grid) ){
			return $this->grid;
		}

		$grid = $this->unsplash_api->get('/search/photos', array(
			'page'			=> 1,
			'per_page'		=>	9,
			'query'			=> $this->defaults['query'],
			'order_by'		=> $this->defaults['order_by'],
			'color'			=> $this->defaults['color'],
			'orientation' 	=> $this->defaults['orientation'],
		));

		if(! isset($grid->error)) {
			$this->grid = $grid->results;
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
		$this->results_photos = $results_photos;
		$this->query = $_GET;
	}
}

