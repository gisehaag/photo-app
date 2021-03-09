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


class PhotoGallery {
	constructor() {
		this.appBox = document.querySelector('.app-box');
		// this.callAPI();
		this.getTopics();
		this.getGallery();
	}

	getTopics() {
		this.callAPI('topics', this.displayTopics.bind(this));
		// this.topicList =
	}

	getGallery() {
		this.callAPI('gallery', this.displayGallery.bind(this));
	}

	displayTopics(data) {
		const topicList = data;
		this.list = this.appBox.querySelector('.topic-list');

		for (let key in topicList) {
			// console.log(topicList[key].title);
			this.list.innerHTML += `<div><a href="#" >${topicList[key].title}</a></div>`
		}
	}

	displayGallery(data) {
		const gallery = data;
		this.gallery = this.appBox.querySelector('.gallery');

		for (let key in gallery) {
			this.gallery.innerHTML += `
				<div class="gallery-item">
					<img
						class="gallery-image"
						src="${gallery[key].urls.regular}"
						alt="${gallery[key].alt_description}"
					/>
					<div class="author-data info-gallery">
						<img
							class="author-image"
							src="${gallery[key].user.profile_image.small}"
							alt="${gallery[key].first_name}"
						/>
						<span class="author-name">${gallery[key].user.name}</span>
					</div>
				</div>
			`
		}
	}

	callAPI(endpoint, callback) {
		const myFetch = `./endpoints/${endpoint}.php`
		fetch(myFetch)
			.then(response =>
				// if (data.cod == '404') {
				// 	this.displayErrorMessage(data.message);
				// 	return;
				// }
				response.json()
			).then((data) => {
				// console.log(data);
				callback(data);
			})

			.catch(e => {
				console.error(e);
			});
	}
}

const app = new PhotoGallery();





