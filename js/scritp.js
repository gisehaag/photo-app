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
		const topics = this.appBox.querySelectorAll('.topic');
		const authorsData = this.appBox.querySelectorAll('.author-data');

		formSearch.addEventListener('submit', this.getSearch.bind(this));
		this.appBox.addEventListener('click', this.doBiggerImage.bind(this));
		this.appBox.addEventListener('click', this.getUserProfile.bind(this));

		this.observeLoadMore();

		topics.forEach(topic => {
			topic.addEventListener('click', this.getSearch.bind(this));
		})
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
			'orientation': photoGrid.dataset.orientation,
			// 'color': photoGrid.dataset.color,
			'order_by': photoGrid.dataset.orderBy,
		}
		this.insertType = 'append';
		this.pageNumber = pageNumber;
		this.setFetch('/search', query);
	}

	getSearch(e) {
		e.preventDefault();
		const input = this.appBox.querySelector('#input');
		const photoGrid = this.appBox.querySelector('#photo-grid');
		const topic = e.currentTarget.dataset.slug;
		let query = {};
		let pageNumber = this.pageNumber || 1;

		pageNumber = 1;
		query = {
			'per_page': 8,
			'page': pageNumber,
			'orientation': photoGrid.dataset.orientation,
			// 'color': photoGrid.dataset.color,
			'order_by': photoGrid.dataset.orderBy,
		}
		this.insertType = 'innerHTML';
		this.pageNumber = pageNumber;

		if (topic) {
			query.id_or_slug = topic;
			photoGrid.dataset.query = topic;
			this.setFetch(`/topics/${topic}`, query);
		} else {
			query.query = input.value;
			photoGrid.dataset.query = input.value;
			this.setFetch('/search', query);
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

		window.location.href = `users.php?username=${username}`;

		// const formData = new FormData();
		// formData.append('username', `/${username}`);

		// this.callAPI('/users', this.displayUserPage.bind(this), formData);
	}

	setFetch(endpoint, query) {
		const formData = new FormData();
		formData.append('endpoint', endpoint)
		formData.append('query', JSON.stringify(query));

		this.callAPI('search', this.displayGrid.bind(this), formData);
	}

	displayUserPage(data) {
		const userGridBox = this.appBox.querySelector('#user-container');
		userGridBox.innerHTML = data.html;
	}

	displayGrid(data) {
		const photoGrid = this.appBox.querySelector('#photo-grid');
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





