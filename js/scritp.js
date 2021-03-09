$(document).ready(function () {
	$(".gallery").owlCarousel({
		center: true,
		items: 2,
		loop: true,
		margin: 0,
		dots: false,
		// responsive:{
		// 	 600:{
		// 		  items:4
		// 	 }
		// }
	});
});


const apiKey = '2f56450a';
const movie = 'tt3896198';
const year = '2020';

// const url = `http://www.omdbapi.com/?i=${movie}&apikey=${apiKey}`;
// const url = `http://www.omdbapi.com/?y=${year}&apikey=${apiKey}`
// const url = `http://www.omdbapi.com/?y=2018&apikey=${apiKey}`

const url = `http://www.omdbapi.com/?t=harry&y=2018&apikey=${apiKey}`;

fetch(url)
	.then(response => response.json())
	.then(data => {
		// if (data.cod == '404') {
		// 	this.displayErrorMessage(data.message);
		// 	return;
		// }
		console.log(data);
	})
	.catch(e => {
		console.error(e);
	});


const poster = `http://img.omdbapi.com/?apikey=${apiKey}&i=${movie}`;
const posterBox = document.querySelector('.poster');

let dataAPI = {};

const miPHP = './get-API-info.php'
fetch(miPHP)
	// .then(response => response.json())
	.then(response =>
		// if (data.cod == '404') {
		// 	this.displayErrorMessage(data.message);
		// 	return;
		// }
		response.json()
	).then((data) => {
		console.log(data);
		dataAPI = data;
		// posterBox.innerHTML += `<img src="${data.urls.regular}" alt="" width=600>`;
	})

	.catch(e => {
		console.error(e);
	});


// "This product uses the TMDb API but is not endorsed or certified by TMDb."



