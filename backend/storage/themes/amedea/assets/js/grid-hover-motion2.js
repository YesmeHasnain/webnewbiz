(function($) {
	"use strict";

	const map = (x, a, b, c, d) => (x - a) * (d - c) / (b - a) + c;
	const lerp = (a, b, n) => (1 - n) * a + n * b;

	const calcWinsize = () => {
		return {width: window.innerWidth, height: window.innerHeight};
	};

	const getRandomNumber = (min, max) => Math.floor(Math.random() * (max - min + 1) + min);

	const getMousePos = e => {
		return { 
			x : e.clientX, 
			y : e.clientY 
		};
	};

	// Preload images
	const preloadImages = (selector) => {
		return new Promise((resolve, reject) => {
			imagesLoaded(document.querySelectorAll(selector), resolve);
		});
	};

	// Calculate the viewport size
	let winsize = calcWinsize();
	window.addEventListener('resize', () => winsize = calcWinsize());

	// Track the mouse position
	let mousepos = {x: winsize.width/2, y: winsize.height/2};
	window.addEventListener('mousemove', ev => mousepos = getMousePos(ev));

	class GridItem {
		constructor(el) {
			this.DOM = {el: el};
			this.move();
		}
		move() {
			let translationVals = {tx: 0, ty: 0, r: 0};
			const xstart = getRandomNumber(15,60);
			const ystart = getRandomNumber(30,90);
			const randR = getRandomNumber(-15,15);

			const render = () => {
				translationVals.tx = lerp(translationVals.tx, map(mousepos.x, 0, winsize.width, -xstart, xstart), 0.07);
				translationVals.ty = lerp(translationVals.ty, map(mousepos.y, 0, winsize.height, -ystart, ystart), 0.07);
				translationVals.r = lerp(translationVals.r, map(mousepos.x, 0, winsize.width, -randR, randR), 0.07);
				
				gsap.set(this.DOM.el, {
					x: translationVals.tx, 
					y: translationVals.ty,
					rotation: translationVals.r,
				});
				requestAnimationFrame(render);
			}
			requestAnimationFrame(render);
		}
	}

	class Grid {
		constructor(el) {
			this.DOM = {el: el};
			this.gridItems = [];
			this.items = [...this.DOM.el.querySelectorAll('.grid-hover-motion__item')];
			this.items.forEach(item => this.gridItems.push(new GridItem(item)));
			
			this.showItems();
		}
		// Initial animation to scale up and fade in the items
		 showItems() {
			gsap
			.timeline()
			.set(this.items, {scale: 0.7, opacity: 0}, 0)
			.to(this.items, {
				duration: 2,
				ease: 'Expo.easeOut',
				scale: 1,
				stagger: {amount: 0.6, grid: 'auto', from: 'center'}
			}, 0)
			.to(this.items, {
				duration: 3,
				ease: 'Power1.easeOut',
				opacity: 0.8,
				stagger: {amount: 0.6, grid: 'auto', from: 'center'}
			}, 0);
		}
	}

	// Preload  images
	preloadImages('.grid-hover-motion__item-img, .bigimg').then(() => {
		
		// Initialize grid
		const grid = new Grid(document.querySelector('.grid-hover-motion'));
	});


})(jQuery);