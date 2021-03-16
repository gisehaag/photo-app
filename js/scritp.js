$(function () {
	$(".gallery").owlCarousel({
		center: true,
		items: 2,
		loop: true,
		margin: 0,
		dots: false,
		responsive: {
			0: {
				items: 1
			},
			600: {
				items: 2
			},
		}
	});
})

class PhotoGallery {
	constructor() {
		this.appBox = document.querySelector('.app-box');
		this.addEvents();
	}

	addEvents() {
		const formSearch = this.appBox.querySelector('#search-form');
		const photos = this.appBox.querySelectorAll('.photo');

		formSearch.addEventListener('submit', this.getSearch.bind(this));
		this.appBox.addEventListener('click', this.doBiggerImage.bind(this));
		this.observeLoadMore();
	}

	doBiggerImage(e) {
		if (!e.target.matches('.photo')) {
			return;
		}

		const photo = e.target;
		const modalBox = document.querySelector('.modal');

		modalBox.style.display = 'block';
		modalBox.innerHTML = `
			<div class="modal-bg"></div>
			<div class="modal-container">
				<a href="#" id="closebutton"><span class="icon-close"></span></a>
				<div>
					<img id="image-modal" src="${photo.src}" alt="${photo.alt}" />
				</div>
			</div>
		`;

		this.closeModal(modalBox);
	}

	closeModal(modalBox) {
		const closeButton = modalBox.querySelectorAll('#closebutton, .modal-bg');

		closeButton.forEach((button) => {
			button.addEventListener('click', e => {
				e.preventDefault();
				modalBox.style.display = 'none';
			});
		});

		window.addEventListener('keydown', e => {
			if (e.key == 'Escape') {
				modalBox.style.display = 'none';
			}
		});

	}

	observeLoadMore() {
		const moreResults = this.appBox.querySelector('#more-results');
		let options = {
			// root: this.appBox, //ver porqué esto nunca funcionó
			threshold: 1,
		}

		let observer = new IntersectionObserver((entries) => {
			const entry = entries[0];
			let isVisible = entry.intersectionRatio >= options.threshold;

			if (isVisible) {
				this.infiniteScroll();
			}
		}, options);

		observer.observe(moreResults);
	}

	infiniteScroll() {
		let query = {};
		let pageNumber = this.pageNumber || 1;
		const photoGrid = this.appBox.querySelector('.photo-grid');

		query = {
			'per_page': 9,
			'page': ++pageNumber,
			'query': photoGrid.dataset.query,
			'orientation': photoGrid.dataset.orientation,
			// 'color': photoGrid.dataset.color,
			'order_by': photoGrid.dataset.orderBy,
		}
		this.insertType = 'append';
		this.pageNumber = pageNumber;
		this.setFetch(query);
	}

	getSearch(e) {
		e.preventDefault();
		const input = this.appBox.querySelector('#input');
		const photoGrid = this.appBox.querySelector('.photo-grid');

		let query = {};
		let pageNumber = this.pageNumber || 1;

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
		this.pageNumber = pageNumber;

		this.setFetch(query);
	}

	setFetch(query) {
		const formData = new FormData();
		formData.append('query', JSON.stringify(query));

		this.callAPI('search', this.displayGrid.bind(this), formData);
	}


	displayGrid(data) {
		const photoGrid = this.appBox.querySelector('.photo-grid');
		const moreResults = this.appBox.querySelector('#more-results');

		if (!data.found) {
			moreResults.style.display = 'none';
			photoGrid.classList.add('not-found');
		} else {
			moreResults.style.display = 'block';
			photoGrid.classList.remove('not-found');
		}

		switch (this.insertType) {
			case 'append':
				photoGrid.innerHTML += data.html;
				break;

			case 'innerHTML':
				photoGrid.innerHTML = data.html;
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
				return response.json();
			})
			// .then(response => response.json())
			.then((data) => {
				callback(data);
			})
			.catch(e => {
				console.error(e);
			});
	}
}

const app = new PhotoGallery();





