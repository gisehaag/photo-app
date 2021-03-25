class PhotoGallery {
	constructor() {
		this.appBox = document.querySelector('.app-box');
		this.BASE_URL = window.location.origin;
		this.addEvents();
		this.initSlider();
		this.initMacy();
	}

	initSlider() {
		// library documentation http://owlcarousel2.github.io/OwlCarousel2/docs/started-welcome.html
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
	}

	initMacy() {
		// library documentation https://github.com/bigbite/macy.js
		if (window.outerWidth > 780) {
			this.macy = Macy({
				// See below for all available options.
				container: '#photo-grid',
				columns: 3,
				margin: {
					x: 15,
					y: 15
				}
			});
		}
	}

	addEvents() {
		const formSearch = this.appBox.querySelector('#search-form');
		const topics = this.appBox.querySelectorAll('.topic');
		const loadMoreButton = this.appBox.querySelector('#load-more');

		formSearch.addEventListener('submit', this.getQueryResults.bind(this));

		this.appBox.addEventListener('click', (e) => {
			this.doBiggerImage(e);
			this.getUserProfile(e);
		});

		if (loadMoreButton) {
			loadMoreButton.addEventListener('click', this.getSearch.bind(this));
		};

		topics.forEach(topic => {
			topic.addEventListener('click', this.getSearch.bind(this));
		});

		this.observeLoadMore();
	}

	doBiggerImage(e) {
		if (!e.target.matches('.photo')) {
			return;
		}

		const photo = e.target;
		const modalBox = document.querySelector('.modal');

		modalBox.style.display = 'grid';
		modalBox.innerHTML = `
			<div class="modal-bg"></div>
			<div class="modal-container">
				<a href="#" class="icon-close" id="closebutton"></a>
				<img id="image-modal" src="${photo.src}" alt="${photo.alt}" />
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
		const photoGrid = this.appBox.querySelector('#photo-grid');

		query = {
			'per_page': 9,
			'page': ++pageNumber,
			'query': photoGrid.dataset.query,
			// 'color': photoGrid.dataset.color,
			'order_by': photoGrid.dataset.orderBy,
		}
		this.insertType = 'append';
		this.pageNumber = pageNumber;
		this.setFetch('/search', query);
	}

	getSearch(e) {
		e.preventDefault();
		const title = this.appBox.querySelector('h1, .title');
		const input = this.appBox.querySelector('#input');
		const photoGrid = this.appBox.querySelector('#photo-grid');
		const topic = e.currentTarget.dataset.slug;
		const buttonID = e.currentTarget.id;

		let query = {};
		let pageNumber = this.pageNumber || 1;

		query = {
			'page': pageNumber,
			// 'color': photoGrid.dataset.color,
			'order_by': photoGrid.dataset.orderBy,
		}

		this.insertType = 'innerHTML';
		this.pageNumber = pageNumber;

		if (topic) {
			query.id_or_slug = topic;
			photoGrid.dataset.query = topic;
			this.setFetch(`/topics/${topic}`, query);
			title.scrollIntoView({ behavior: "smooth" });
		} else if (buttonID) {
			query.query = photoGrid.dataset.query;
			query.page = ++pageNumber,
				query.per_page = 21,
				this.insertType = 'append';
			this.showMoreResults = false;
			this.pageNumber = query.page;
			this.setFetch('/search', query);
		} else {
			photoGrid.dataset.query = input.value;
			query.query = input.value;
		}
	}

	getUserProfile(e) {
		if (!e.target.matches('[class^=author-]')) {
			return;
		}

		let username = e.target.dataset.username;
		if (!username) {
			username = e.target.parentNode.dataset.username;
		}

		window.location.href = `${this.BASE_URL}/users/${username}`;
	}

	getQueryResults(e) {
		e.preventDefault();

		const input = this.appBox.querySelector('#input');
		const photoGrid = this.appBox.querySelector('#photo-grid');
		let query = {};

		query = {
			'page': 1,
			'per_page': 21,
			'order_by': photoGrid.dataset.orderBy || 'latest',
			// 'color': photoGrid.dataset.color,
		}

		let queryString = Object.entries(query).map(([key, value]) => {
			return `${encodeURIComponent(key)}=${encodeURIComponent(value)}`;
		}).join('&');

		window.location.href = `${this.BASE_URL}/search/${input.value}/?${queryString}`;
	}

	setFetch(endpoint, query) {
		const formData = new FormData();
		formData.append('endpoint', endpoint)
		formData.append('query', JSON.stringify(query));

		this.callAPI('search', this.displayGrid.bind(this), formData);
	}

	displayGrid(data) {
		const photoWrapper = this.appBox.querySelector('.photo-grid-wrapper');
		const title = this.appBox.querySelector('.highline');
		const photoGrid = this.appBox.querySelector('#photo-grid');
		const moreResults = this.appBox.querySelector('#more-results');


		if (!data.found) {
			moreResults.style.display = 'none';
			photoGrid.classList.add('not-found');
		} else {
			if (this.showMoreResults) {
				moreResults.style.display = 'block';
			}
			photoGrid.classList.remove('not-found');
		}

		switch (this.insertType) {
			case 'append':
				photoGrid.innerHTML += data.html;
				break;

			case 'innerHTML':
				title.innerHTML = data.title;
				photoGrid.innerHTML = data.html;
				break;
		}

		if (this.macy) {
			this.macy.runOnImageLoad(() => {
				this.macy.recalculate(true);
			}, true);
		}

		title.setAttribute("data-topic", data.topic);
		title.setAttribute("data-search", data.search_word);
	}

	callAPI(endpoint, callback, data = {}) {
		const requestsOptions = {
			method: "POST",
			body: data,
		}

		const myFetch = `${this.BASE_URL}/endpoints/${endpoint}.php`
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





