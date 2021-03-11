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


class PhotoGallery {
	constructor() {
		this.appBox = document.querySelector('.app-box');
		// this.callAPI();
		// this.getTopics();
		// this.getSlider();

		this.assingElements();
		this.addEvents();

	}

	// getTopics() {
	// 	this.callAPI('topics', this.displayTopics.bind(this));
	// }

	getSlider() {
		this.callAPI('gallery', this.displaySlider.bind(this));
	}

	assingElements() {
		this.formSearch = this.appBox.querySelector('form');
		this.input = this.appBox.querySelector('#input');
		this.input.focus();
		this.moreResults = this.appBox.querySelector('#more-results');
	}

	addEvents() {
		this.formSearch.addEventListener('submit', this.getSearch.bind(this));
		this.moreResults.addEventListener('click', this.getSearch.bind(this));
	}

	getSearch(e, count) {
		e.preventDefault();
		const formData = new FormData();

		formData.append('query', JSON.stringify({
			'per_page': count,
			'page': 4,
			'query': this.input.value,
		}));

		this.callAPI('search', this.displayGrid.bind(this), formData);
	}

	displayGrid(data) {
		console.log(data);
		const photos = data.results;
		const photoGrid = this.appBox.querySelector('.photo-grid');

		photoGrid.innerHTML = `<h1>more images like that</h1>`

		for (let key in photos) {
			// console.log(photos[key]);
			photoGrid.innerHTML += `
			<div class="image">
					<img
						src="${photos[key].urls.small}"
						alt="${photos[key].alt_description}"
					/>
					<span class="caption">${photos[key].alt_description}</span>
					<div class="author-data">
						<a href="">
							<img
								class="author-image"
								src="${photos[key].user.profile_image.small}"
								alt="${photos[key].user.name}"
							/>
						</a>
						<span class="author-name">${photos[key].user.name}</span>
					</div>
				</div>
			`
		}

	}

	// displayTopics(data) {
	// 	const topicList = data;
	// 	const list = this.appBox.querySelector('.topic-list');

	// 	for (let key in topicList) {
	// 		// console.log(topicList[key].title);
	// 		list.innerHTML += `<div><a href="#" >${topicList[key].title}</a></div>`
	// 	}
	// }

	displaySlider(data) {
		const galleryData = data;
		const gallery = this.appBox.querySelector('.gallery');

		for (let key in galleryData) {
			// console.log(galleryData[key]);

			gallery.innerHTML += `
				<div class="galleryData-item">
					<img
						class="galleryData-image"
						src="${galleryData[key].urls.regular}"
						alt="${galleryData[key].alt_description}"
					/>
					<div class="author-data info-gallery">
						<img
							class="author-image"
							src="${galleryData[key].user.profile_image.small}"
							alt="${galleryData[key].user.name}"
						/>
						<span class="author-name">${galleryData[key].user.name}</span>
					</div>
				</div>
			`
		}



	}

	callAPI(endpoint, callback, data = {}, callbackData = {}) {
		const requestsOptions = {
			method: "POST",
			body: data,
		}

		const myFetch = `./endpoints/${endpoint}.php`
		fetch(myFetch, requestsOptions)
			.then((response) => {
				if (response.status != '200') {
					throw response.statusText;
					// this.displayErrorMessage(response.statusText);
				}
				return response.json();
			})
			.then((data) => {
				callback(data, callbackData)
			})
			.catch(e => {
				console.error(e);
			});
	}
}

const app = new PhotoGallery();





