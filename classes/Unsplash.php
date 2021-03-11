<?php
require('./classes/Unsplash_API.php');

class Unsplash {
	//parameter stores API results
	public $topic;
	public $daily_topic;
	public $slider;

	function __construct() {
		$this->unsplash_api = new Unsplash_API();
		//todo: guardar los json de las consultas un determinado tiempo en local
		// $this->get_stored_data();
		$this->fetch_topics();
		$this->fetch_slider();
		$this->fetch_grid();
	}

	public function get_stored_data() {
		// todo: los pasos de la función:
		// se fija si el archivo existe,
		// si existe, se fija qué tan viejo es y si es muy viejo lo borra,
		// y como no va a existir, entra en el else que describo abajo:
		// sino existe lo crea y hace el fetch para completar los datos
		// despues asigna el contenido a la variable en cuestion: topics, slider, etc.
	}

	public function fetch($endpoint, $params = []) {
		$this->unsplash_api->fetch($endpoint, $params);
	}

	public function fetch_topics(){
		if(isset($this->topics) ){
			return $this->topics;
		}

		$topics = $this->unsplash_api->fetch('/topics', array(
			'per_page' => 30,
		));

		$this->topics =json_decode($topics);
		$this->set_daily_topic();

		return $this->topics;
	}

	function set_daily_topic() {
		$topics = $this->topics;
		$this->daily_topic = $topics[rand(0, count($topics))]->slug;
	}

	public function fetch_slider() {
		if(isset($this->slider) ){
			return $this->slider;
		}

		$slider = $this->unsplash_api->fetch("/topics/$this->daily_topic/photos", array(
			'id_or_slug'  => $this->daily_topic,
			'orientation' => 'landscape',
			'per_page'    => 3,
			'order_by'    => 'popular',
		));

		$this->slider = json_decode($slider);
		return $this->slider;
	}

	public function fetch_grid() {
		if(isset($this->grid) ){
			return $this->grid;
		}

		$grid = $this->unsplash_api->fetch('/search/photos', array(
			'query'			=> 'photography',
			'page'			=> 1,
			'per_page'		=>	9,
			'order_by'		=> 'lastest', //por default 'relevant'
			'color'			=> 'purple', //black_and_white, black, white, yellow, orange, red, purple, magenta, green, teal, blue
			'orientation' 	=> 'squarish' //landscape, portrait, squarish
		));

		$this->grid = json_decode($grid)->results;
		return $this->grid;
	}
}