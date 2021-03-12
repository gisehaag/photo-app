$(function () {
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
})

class PhotoGallery {
	constructor() {
		this.appBox = document.querySelector('.app-box');
		this.addEvents();
	}

	addEvents() {
		const formSearch = this.appBox.querySelector('#search-form');
		const moreResults = this.appBox.querySelector('#more-results');

		formSearch.addEventListener('submit', this.getSearch.bind(this));
		moreResults.addEventListener('click', this.getSearch.bind(this));
	}

	getSearch(e) {
		e.preventDefault();
		const input = this.appBox.querySelector('#input');
		const photoGrid = this.appBox.querySelector('.photo-grid');

		let query = {};
		let pageNumber = this.pageNumber || 1;

		switch (e.srcElement.id) {
			case 'more-results':
				query = {
					'per_page': 9,
					'page': ++pageNumber,
					'query': photoGrid.dataset.query,
					'orientation': photoGrid.dataset.orientation,
					// 'color': photoGrid.dataset.color,
					'order_by': photoGrid.dataset.orderBy,
				}
				this.insertType = 'append';
				break;

			case 'search-form':
				pageNumber = 1;
				query = {
					'per_page': 8,
					'page': pageNumber,
					'query': input.value,
					'orientation': photoGrid.dataset.orientation,
					// 'color': photoGrid.dataset.color,
					'order_by': photoGrid.dataset.orderBy,
				}
				this.insertType = 'innerHTML';
				photoGrid.dataset.query = input.value;
				break;
		}

		this.pageNumber = pageNumber;
		this.setFetch(query);
	}

	setFetch(query) {
		const formData = new FormData();
		formData.append('query', JSON.stringify(query));

		this.callAPI('search', this.displayGrid.bind(this), formData);
	}


	displayGrid(photos) {
		const photoGrid = this.appBox.querySelector('.photo-grid');
		switch (this.insertType) {
			case 'append':
				photoGrid.innerHTML += `${photos}`;
				break;

			case 'innerHTML':
				photoGrid.innerHTML = `<h1>those are the results</h1>${photos}`;
				break;
		}
	}

	callAPI(endpoint, callback, data = {}) {
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
				// console.log(response.text());
				return response.text();
				// return console.log(response);
			})
			// .then(response => response.json())
			.then((data) => {
				// console.log(data);
				callback(data);
			})
			.catch(e => {
				console.error(e);
			});
	}
}

const app = new PhotoGallery();





