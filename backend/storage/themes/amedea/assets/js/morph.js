(function($) {
	"use strict";

	/**
	 * Preloads images specified by the CSS selector.
	 * @function
	 * @param {string} [selector='img'] - CSS selector for target images.
	 * @returns {Promise} - Resolves when all specified images are loaded.
	 */
	const preloadImages = (selector = 'img') => {
		return new Promise((resolve) => {
			// The imagesLoaded library is used to ensure all images (including backgrounds) are fully loaded.
			imagesLoaded(document.querySelectorAll(selector), {background: true}, resolve);
		});
	};

	/**
	 * Linear interpolation
	 * @param {Number} a - first value to interpolate
	 * @param {Number} b - second value to interpolate 
	 * @param {Number} n - amount to interpolate
	 */
	const lerp = (a, b, n) => (1 - n) * a + n * b;

	 /**
	  * Gets the cursor position
	  * @param {Event} ev - mousemove event
	  */
	const getCursorPos = ev => {
		 return { 
			 x : ev.clientX, 
			 y : ev.clientY 
		 };
	 };

	/**
	 * Map number x from range [a, b] to [c, d] 
	 */
	const map = (x, a, b, c, d) => (x - a) * (d - c) / (b - a) + c;

	/**
	 * Calculates the viewport size
	 */
	const calcWinsize = () => {
		return {
			width: window.innerWidth, 
			height: window.innerHeight
		}
	}

	// Calculate the viewport size
	let winsize = calcWinsize();
	// Add an event listener to re-calculate the viewport size when the window is resized
	window.addEventListener('resize', () => winsize = calcWinsize());

	// Initialize the cursor position to the center of the viewport
	let cursor = {x: winsize.width/2, y: winsize.height/2};
	// Update the cursor position on mouse move
	window.addEventListener('mousemove', ev => cursor = getCursorPos(ev));

	// Export the InteractiveTilt class
	class InteractiveTilt {
		// Object to hold references to DOM elements
		DOM = {
			el: null, // Main element (.content)
			wrapEl: null // Wrap element (.content_morph__img-wrap)
		};

		// Default options for the InteractiveTilt effect
		defaults = {
			perspective: 800, // CSS perspective value for the 3D effect
			// Range of values for translation and rotation on x and y axes
			valuesFromTo: {
				x: [-35, 35],
				y: [-35, 35],
				rx: [-18, 18], // rotation on the X-axis
				ry: [-10, 10], // rotation on the Y-axis
				rz: [-4, 4]   // rotation on the Z-axis
			},
			// Amount to interpolate values for smooth animation (higher value, less smoothing)
			amt: 0.1
		};

		// Object to store the current transform values for the image element
		imgTransforms = {x: 0, y: 0, rx: 0, ry: 0, rz: 0};

		/**
		 * Constructor for the InteractiveTilt class.
		 * @param {Element} DOM_el - The .content element to be animated
		 * @param {Object} options - Custom options for the effect
		 */
		constructor(DOM_el, options) {
			// Assign DOM elements to the DOM object
			this.DOM.el = DOM_el;
			this.DOM.wrapEl = this.DOM.el.querySelector('.content_morph__img-wrap');
			// Merge the default options with any user-provided options
			this.options = Object.assign(this.defaults, options);
			
			// If a perspective value is provided, apply it to the main element
			if (this.options.perspective) {
				this.DOM.el.style.perspective = `${this.options.perspective}px`;
			}

			// Start the rendering loop for the animation
			requestAnimationFrame(() => this.render());
		}
		
		/**
		 * Animation loop that applies the tilt effect based on the cursor position.
		 */
		render() {
			// Interpolate the current transform values towards the target values
			// based on the cursor's position on the screen
			this.imgTransforms.x = lerp(this.imgTransforms.x, map(cursor.x, 0, winsize.width, this.options.valuesFromTo.x[0], this.options.valuesFromTo.x[1]), this.options.amt);
			this.imgTransforms.y = lerp(this.imgTransforms.y, map(cursor.y, 0, winsize.height, this.options.valuesFromTo.y[0], this.options.valuesFromTo.y[1]), this.options.amt);
			this.imgTransforms.rz = lerp(this.imgTransforms.rz, map(cursor.x, 0, winsize.width, this.options.valuesFromTo.rz[0], this.options.valuesFromTo.rz[1]), this.options.amt);

			// Apply rotation on the X and Y-axis only if perspective is enabled
			this.imgTransforms.rx = !this.options.perspective ? 0 : lerp(this.imgTransforms.rx, map(cursor.y, 0, winsize.height, this.options.valuesFromTo.rx[0], this.options.valuesFromTo.rx[1]), this.options.amt);
			this.imgTransforms.ry = !this.options.perspective ? 0 : lerp(this.imgTransforms.ry, map(cursor.x, 0, winsize.width, this.options.valuesFromTo.ry[0], this.options.valuesFromTo.ry[1]), this.options.amt);
			
			// Apply the calculated transform values to the wrap element to create the 3D tilt effect
			this.DOM.wrapEl.style.transform = `translateX(${this.imgTransforms.x}px) translateY(${this.imgTransforms.y}px) rotateX(${this.imgTransforms.rx}deg) rotateY(${this.imgTransforms.ry}deg) rotateZ(${this.imgTransforms.rz}deg)`;
			
			// Continue the loop with the next animation frame
			requestAnimationFrame(() => this.render());
		} 
	}

	// Define a variable to store the Lenis smooth scrolling object
	let lenis;

	// Initializes Lenis for smooth scrolling with specific properties
	const initSmoothScrolling = () => {
		// Instantiate the Lenis object with specified properties
		lenis = new Lenis({
			lerp: 0.1, // Lower values create a smoother scroll effect
			smoothWheel: true // Enables smooth scrolling for mouse wheel events
		});

		// Update ScrollTrigger each time the user scrolls
		lenis.on('scroll', () => ScrollTrigger.update());

		// Define a function to run at each animation frame
		const scrollFn = (time) => {
			lenis.raf(time); // Run Lenis' requestAnimationFrame method
			requestAnimationFrame(scrollFn); // Recursively call scrollFn on each frame
		};
		// Start the animation frame loop
		requestAnimationFrame(scrollFn);
	};

	// Sets up default animation settings and merges with options
	const setupAnimationDefaults = (itemElement, options) => {
		// Default settings for clip paths and scroll trigger
		let defaults = {
			clipPaths: {
				step1: {
					initial: 'polygon(0% 0%, 100% 0%, 100% 100%, 0% 100%)',
					final: 'polygon(50% 0%, 50% 50%, 50% 50%, 50% 100%)',
				},
				step2: {
					initial: 'polygon(50% 50%, 50% 0%, 50% 100%, 50% 50%)',
					final: 'polygon(0% 0%, 100% 0%, 100% 100%, 0% 100%)',
				}
			},
			// Default scroll trigger settings
			scrollTrigger: {
				trigger: itemElement,
				start: 'top 50%',
				end: '+=50%',
				scrub: true,
			},
			// Default perspective setting
			perspective: false
		};
	  
		// Merge defaults with options provided
		if (options && options.scrollTrigger) {
			defaults.scrollTrigger = {
				...defaults.scrollTrigger,
				...options.scrollTrigger
			};
		}
	  
		// Merge and return the complete settings
		return {
			...defaults,
			...options,
			scrollTrigger: defaults.scrollTrigger
		};
	};  

	// Prepares text within an element for animation by splitting it into characters and setting their initial opacity.
	const prepareTextForAnimation = itemElement => {
		// Query for the span elements within the itemElement
		const textSpans = itemElement.querySelectorAll('.content_morph__text > span');
	  
		// Perform the splitting operation
		Splitting({ target: textSpans });
	  
		// Initialize an array to hold arrays of characters for each span
		const charsArray = Array.from(textSpans).map(span => {
		  // Query for chars inside this span and return them as an array
		  return Array.from(span.querySelectorAll('.char'));
		});
	  
		// Set the opacity of all characters to 0 using GSAP
		charsArray.forEach(charArray => {
			gsap.set(charArray, { opacity: 0 });
		});
		
		// Return the charsArray
		return charsArray;
	};

	// Animation profiles for each item

	// First animation effect
	const fx1 = (itemElement, options) => {
		// Set up the animation settings
		const settings = setupAnimationDefaults(itemElement, options);
		// Select the elements to animate
		const imageElement = itemElement.querySelector('.content_morph__img');
		const innerElements = imageElement.querySelectorAll('.content_morph__img-inner'); // Now it selects both inners
		// Prepare the text for animation and retrieve the character arrays
		const charsArray = prepareTextForAnimation(itemElement);

		// Define and return the GSAP timeline for the animation
		const tl = gsap.timeline({
			// Default easing for all animations in this timeline
			defaults: {
				ease: 'none'
			},
			// Event hook for when the timeline starts
			onStart: () => {
				// Apply perspective if specified
				if ( settings.perspective ) {
					gsap.set(imageElement, {
						perspective: settings.perspective
					});
				}
			},
			// ScrollTrigger configuration for this timeline
			scrollTrigger: settings.scrollTrigger
		})
		// The sequence of animation steps
		.fromTo(imageElement, {
			// Initial state for the animation
			filter: 'brightness(100%)',
			'clip-path': settings.clipPaths.step1.initial
		}, {
			ease: 'sine.in',
			// Final state for the animation
			filter: 'brightness(800%)',
			'clip-path': settings.clipPaths.step1.final
		}, 0)
		.to(innerElements[0], {
			ease: 'sine.in',
			// Rotation and scale effect for the inner element
			rotationY: -40,
			scale: 1.4,
		}, 0)
		// Switch image
		.add(() => {
			// Toggle the visibility of the inner elements
			innerElements[0].classList.toggle('content_morph__img-inner--hidden');
			innerElements[1].classList.toggle('content_morph__img-inner--hidden');
		})
		.to(imageElement, {
			// Start state for the next animation step
			startAt: {
				'clip-path': settings.clipPaths.step2.initial
			},
			// Final state for the next animation step
			'clip-path': settings.clipPaths.step2.final,
			filter: 'brightness(100%)'
		})
		.to(innerElements[1], {
			// Start state for rotation and scale reset
			startAt: {rotationY: 40, scale: 1.4},
			// Reset rotation and scale to original
			rotationY: 0,
			scale: 1,
		}, '<') // '<' indicates that this step starts at the same time as the previous one
		.addLabel('texts', '<-=0.3');

		charsArray.forEach((charArray, index) => {
			const staggerDirection = index % 2 === 0 ? 1 : -1; // Alternate stagger direction

			tl.to(charArray, {
				startAt: {opacity: 1, scale: .2},
				opacity: 1,
				scale: 1,
				yPercent: -staggerDirection*40,
				stagger: staggerDirection*0.04
			}, 'texts');
		});

		return tl;
	}

	const fx2 = (itemElement, options) => {
		// Set up the animation settings
		const settings = setupAnimationDefaults(itemElement, options);
		// Select the elements to animate
		const imageElement = itemElement.querySelector('.content_morph__img');
		const innerElements = imageElement.querySelectorAll('.content_morph__img-inner'); // Now it selects both inners
		const charsArray = prepareTextForAnimation(itemElement);

		const tl = gsap.timeline({
			defaults: {
				ease: 'none'
			},
			onStart: () => {
				if ( settings.perspective ) {
					gsap.set([imageElement, itemElement], {
						perspective: settings.perspective
					});
				}
			},
			scrollTrigger: settings.scrollTrigger
		})
		.fromTo(imageElement, {
			filter: 'brightness(100%) hue-rotate(0deg)',
			'clip-path': settings.clipPaths.step1.initial
		}, {
			filter: 'brightness(800%) hue-rotate(90deg)',
			'clip-path': settings.clipPaths.step1.final
		}, 0)
		.to(innerElements[0], {
			rotationZ: -5,
			scaleX: 1.8,
		}, 0)
		// Switch image
		.add(() => {
			// Toggle the visibility of the inner elements
			innerElements[0].classList.toggle('content_morph__img-inner--hidden');
			innerElements[1].classList.toggle('content_morph__img-inner--hidden');
		})
		.to(imageElement, {
			startAt: {
				'clip-path': settings.clipPaths.step2.initial
			},
			'clip-path': settings.clipPaths.step2.final,
			filter: 'brightness(100%) hue-rotate(0deg)'
		})
		.to(innerElements[1], {
			startAt: {rotationZ: 5, scaleX: 1.8},
			rotationZ: 0,
			scaleX: 1,
		}, '<')
		.addLabel('texts', '<-=0.3');

		charsArray.forEach((charArray, index) => {
			charArray.sort(() => Math.random() - 0.5);
			const staggerDirection = index % 2 === 0 ? 1 : -1; // Alternate stagger direction

			tl.to(charArray, {
				duration: 0.1,
				opacity: 1,
				stagger: staggerDirection*0.04
			}, 'texts');
		});

		return tl;
	}

	const fx3 = (itemElement, options) => {
		// Set up the animation settings
		const settings = setupAnimationDefaults(itemElement, options);
		// Select the elements to animate
		const imageElement = itemElement.querySelector('.content_morph__img');
		const innerElements = imageElement.querySelectorAll('.content_morph__img-inner'); // Now it selects both inners
		const text = itemElement.querySelector('.content_morph__text');
		
		return gsap.timeline({
			defaults: {
				ease: 'none'
			},
			onStart: () => {
				if ( settings.perspective ) {
					gsap.set([imageElement, itemElement], {
						perspective: settings.perspective
					});
				}
			},
			scrollTrigger: settings.scrollTrigger
		})
		.fromTo(imageElement, {
			scale: 0.3,
			filter: 'brightness(100%) contrast(100%)',
			'clip-path': settings.clipPaths.step1.initial
		}, {
			ease: 'sine',
			rotationX: -35,
			rotationY: 35,
			filter: 'brightness(60%) contrast(400%)',
			scale: 0.7,
			'clip-path': settings.clipPaths.step1.final
		}, 0)
		.to(innerElements[0], {
			ease: 'sine',
			skewY: 10,
			scaleY: 1.2,
		}, 0)
		
		// Switch image
		.add(() => {
			// Toggle the visibility of the inner elements
			innerElements[0].classList.toggle('content_morph__img-inner--hidden');
			innerElements[1].classList.toggle('content_morph__img-inner--hidden');
		}, '>')
		.to(imageElement, {
			ease: 'sine.in',
			startAt: {
				'clip-path': settings.clipPaths.step2.initial
			},
			'clip-path': settings.clipPaths.step2.final,
			filter: 'brightness(100%) contrast(100%)',
			scale: 1,
			rotationX: 0,
			rotationY: 0,
		}, '<')
		.to(innerElements[1], {
			ease: 'sine.in',
			startAt: {skewY: 10, scaleY: 2},
			skewY: 0,
			scaleY: 1,
		}, '<')

		.fromTo(text, {
			opacity: 0,
			yPercent: 40,
		}, {
			opacity: 1,
			yPercent: 0,
		}, '>')
		.to(imageElement, {
			ease: 'sine',
			startAt: {filter: 'brightness(100%) contrast(100%) opacity(100%)'},
			filter: 'brightness(60%) contrast(400%) opacity(0%)',
			rotationX: 25,
			rotationY: 2,
			scale: 1.2
		}, '<')
	}

	const fx4 = (itemElement, options) => {
		// Set up the animation settings
		const settings = setupAnimationDefaults(itemElement, options);
		// Select the elements to animate
		const imageElement = itemElement.querySelector('.content_morph__img');
		const innerElements = imageElement.querySelectorAll('.content_morph__img-inner'); // Now it selects both inners
		// Prepare the text for animation and retrieve the character arrays
		const charsArray = prepareTextForAnimation(itemElement);

		const tl = gsap.timeline({
			defaults: {
				ease: 'power1.inOut'
			},
			onStart: () => {
				if ( settings.perspective ) {
					gsap.set([imageElement, itemElement], {
						perspective: settings.perspective
					});
				}
			},
			scrollTrigger: settings.scrollTrigger
		})
		.fromTo(imageElement, {
			filter: 'brightness(100%) grayscale(0%)',
			'clip-path': settings.clipPaths.step1.initial
		}, {
			rotationZ: 90,
			scale: 0.6,
			filter: 'brightness(800%) grayscale(100%)',
			'clip-path': settings.clipPaths.step1.final
		}, 0)
		.to(innerElements[0], {
			rotationZ: -5,
			scaleX: 1.4,
		}, 0)
		// Switch image
		.add(() => {
			// Toggle the visibility of the inner elements
			innerElements[0].classList.toggle('content_morph__img-inner--hidden');
			innerElements[1].classList.toggle('content_morph__img-inner--hidden');
		})
		.to(imageElement, {
			startAt: {
				'clip-path': settings.clipPaths.step1.final, rotationZ: -90
			},
			'clip-path': settings.clipPaths.step2.final,
			filter: 'brightness(100%) grayscale(0%)',
			rotationZ: 0,
			scale: 1,
		})
		.to(innerElements[1], {
			startAt: {rotationZ: -350, scaleX: 1.4},
			rotationZ: -360,
			scaleX: 1,
		}, '<')
		.addLabel('texts', '<-=0.3');

		charsArray.forEach((charArray, index) => {
			const staggerDirection = index % 2 === 0 ? 1 : -1; // Alternate stagger direction

			tl.to(charArray, {
				startAt: {opacity: 1, scale: .2},
				opacity: 1,
				scale: 1,
				yPercent: staggerDirection*400,
				stagger: staggerDirection*0.02
			}, 'texts');
		});

		return tl;
	}

	const fx5 = (itemElement, options) => {
		// Set up the animation settings
		const settings = setupAnimationDefaults(itemElement, options);
		// Select the elements to animate
		const imageElement = itemElement.querySelector('.content_morph__img');
		const innerElements = imageElement.querySelectorAll('.content_morph__img-inner'); // Now it selects both inners
		const charsArray = prepareTextForAnimation(itemElement);

		const tl = gsap.timeline({
			defaults: {
				ease: 'back.out(1.5)'
			},
			onStart: () => {
				if ( settings.perspective ) {
					gsap.set([imageElement, itemElement], {
						perspective: settings.perspective
					});
				}
			},
			scrollTrigger: settings.scrollTrigger
		})
		.fromTo(imageElement, {
			filter: 'brightness(100%) saturate(100%)',
			'clip-path': settings.clipPaths.step1.initial
		}, {
			ease: 'back.in(1.5)',
			rotationZ: 90,
			scale: 0.6,
			filter: 'brightness(300%) saturate(200%)',
			'clip-path': settings.clipPaths.step1.final
		}, 0)
		.to(innerElements[0], {
			ease: 'back.in(1.5)',
			scaleX: 1.4,
		}, 0)
		// Switch image
		.add(() => {
			// Toggle the visibility of the inner elements
			innerElements[0].classList.toggle('content_morph__img-inner--hidden');
			innerElements[1].classList.toggle('content_morph__img-inner--hidden');
		})
		.to(imageElement, {
			startAt: {
				'clip-path': settings.clipPaths.step1.final, rotationZ: -90
			},
			'clip-path': settings.clipPaths.step2.final,
			filter: 'brightness(100%) saturate(100%)',
			rotationZ: 0,
			scale: 1,
		})
		.to(innerElements[1], {
			startAt: {scaleX: 1.4},
			scaleX: 1,
		}, '<')
		.addLabel('texts', '<-=0.3');

		charsArray.forEach((charArray, index) => {
			charArray.sort(() => Math.random() - 0.5);
			const staggerDirection = index % 2 === 0 ? 1 : -1; // Alternate stagger direction

			tl.fromTo(charArray, {
				opacity: 1, 
				transformOrigin: `50% ${staggerDirection < 0 ? 100 : 0}%`, 
				scaleY: 0
			}, {
				duration: 0.1,
				ease: 'none',
				scaleY: 1,
				stagger: staggerDirection*0.02
			}, 'texts');
		});

		return tl;
	}

	const fx6 = (itemElement, options) => {
		// Set up the animation settings
		const settings = setupAnimationDefaults(itemElement, options);
		// Select the elements to animate
		const imageElement = itemElement.querySelector('.content_morph__img');
		const inner = imageElement.querySelector('.content_morph__img-inner');
		const charsArray = prepareTextForAnimation(itemElement);

		// Define and return the GSAP timeline for the animation
		const tl = gsap.timeline({
			// Default easing for all animations in this timeline
			defaults: {
				ease: 'power2.inOut'
			},
			// Event hook for when the timeline starts
			onStart: () => {
				// Apply perspective if specified
				if ( settings.perspective ) {
					gsap.set(imageElement, {
						perspective: settings.perspective
					});
				}
			},
			// ScrollTrigger configuration for this timeline
			scrollTrigger: settings.scrollTrigger
		})
		// The sequence of animation steps
		.fromTo(imageElement, {
			// Initial state for the animation
			scale: 0.2,
			filter: 'brightness(50%)',
			'clip-path': settings.clipPaths.step1.initial,
			transformOrigin: '75% 50%'
		}, {
			// Final state for the animation
			scale: 1,
			filter: 'brightness(100%)',
			'clip-path': settings.clipPaths.step1.final
		}, 0)
		.fromTo(inner, {
			// Rotation and scale effect for the inner element
			rotationY: 40,
			scale: 2,
		}, {
			// Rotation and scale effect for the inner element
			rotationY: 0,
			scale: 1,
		}, 0)

		charsArray.forEach((charArray, index) => {
			const staggerDirection = index % 2 === 0 ? 1 : -1; // Alternate stagger direction

			tl.fromTo(charArray, {
				opacity: 0, 
				scale: 1.2
			}, {
				opacity: 1,
				scale: 1,
				yPercent: staggerDirection*100,
				stagger: staggerDirection*-0.02
			}, 0);
		});

		return tl;
	}

	// First animation effect
	const fxIntro = (itemElement, options) => {
		// Set up the animation settings
		const settings = setupAnimationDefaults(itemElement, options);
		// Select the elements to animate
		const imageElement = itemElement.querySelector('.content_morph__img');
		const inner = imageElement.querySelector('.content_morph__img-inner');
		
		// Define and return the GSAP timeline for the animation
		return gsap.timeline({
			// Default easing for all animations in this timeline
			defaults: {
				ease: 'none'
			},
			// Event hook for when the timeline starts
			onStart: () => {
				// Apply perspective if specified
				if ( settings.perspective ) {
					gsap.set(imageElement, {
						perspective: settings.perspective
					});
				}
			},
			// ScrollTrigger configuration for this timeline
			scrollTrigger: settings.scrollTrigger
		})
		// The sequence of animation steps
		.fromTo(imageElement, {
			scale: 1,
			xPercent: 0,
			filter: 'brightness(100%)',
			'clip-path': settings.clipPaths.step1.initial
		}, {
			scale: 0.5,
			xPercent: -50,
			'clip-path': settings.clipPaths.step1.final,
			filter: 'brightness(500%)',
		}, 0)
		.to(inner, {
			rotationY: -40,
			scale: 1.4,
		}, 0)
		.to(imageElement, {
			startAt: {
				'clip-path': settings.clipPaths.step2.initial
			},
			scale: 0,
			xPercent: -100,
			'clip-path': settings.clipPaths.step2.final,
			filter: 'brightness(100%)'
		})
		.to(inner, {
			startAt: {rotationY: 40},
			rotationY: 0,
			scale: 1,
		}, '<');
	}

	// Main function to apply scroll-triggered animations
	const scroll = () => {
		// Define items and associate them with their animation profiles and options
		const items = [
			{
				id: '#morph-intro', 
				animationProfile: fxIntro,
				interactiveTilt: true, // This item should have the InteractiveTilt effect
				options: {
					scrollTrigger: {
						start: 'clamp(top bottom)',
						end: 'center top'
					},
					perspective: 1000
				} 
			},
			{
				id: '#morph-switch', 
				animationProfile: fx1,
				interactiveTilt: true, // This item should have the InteractiveTilt effect
				options: {
					perspective: 1000
				} 
			},
			{
				id: '#morph-perspective',
				animationProfile: fx2,
				interactiveTilt: true, // This item should have the InteractiveTilt effect
				options: {
					clipPaths: {
						step1: {
							initial: 'polygon(0% 0%, 100% 0%, 100% 100%, 0% 100%)',
							final: 'polygon(40% 50%, 60% 50%, 80% 50%, 20% 50%)',
						},
						step2: {
							initial: 'polygon(20% 50%, 80% 50%, 60% 50%, 40% 50%)',
							final: 'polygon(0% 0%, 100% 0%, 100% 100%, 0% 100%)',
						}
					},
					scrollTrigger: {
						start: 'center bottom',
						end: 'top top'
					},
					perspective: 500
				} 
			},
			{
				id: '#morph-fullscreen', 
				animationProfile: fx3,
				options: {
					clipPaths: {
						step1: {
							initial: 'polygon(0% 0%, 100% 0%, 100% 100%, 0% 100%)',
							final: 'polygon(50% 0%, 50% 50%, 50% 50%, 50% 100%)',
						},
						step2: {
							initial: 'polygon(50% 50%, 50% 0%, 50% 100%, 50% 50%)',
							final: 'polygon(0% 0%, 100% 0%, 100% 100%, 0% 100%)',
						}
					},
					scrollTrigger: {
						start: 'center center',
						end: '+=150%',
						pin: true
					},
					perspective: 400
				} 
			},
			{
				id: '#morph-cinemascope',
				animationProfile: fx4,
				interactiveTilt: true, // This item should have the InteractiveTilt effect
				options: {
					clipPaths: {
						step1: {
							initial: 'polygon(0% 0%, 100% 0%, 100% 100%, 0% 100%)',
							final: 'polygon(40% 50%, 60% 50%, 80% 50%, 20% 50%)',
						},
						step2: {
							initial: 'polygon(20% 50%, 80% 50%, 60% 50%, 40% 50%)',
							final: 'polygon(0% 0%, 100% 0%, 100% 100%, 0% 100%)',
						}
					},
					scrollTrigger: {
						start: 'center bottom',
						end: 'top top-=10%'
					},
					perspective: 500
				} 
			},
			{
				id: '#morph-octagon',
				animationProfile: fx5,
				interactiveTilt: true, // This item should have the InteractiveTilt effect
				options: {
					clipPaths: {
						step1: {
							initial: 'polygon(50% 0%, 80% 10%, 100% 35%, 100% 70%, 80% 90%, 50% 100%, 20% 90%, 0% 70%, 0% 35%, 20% 10%)',
							final: 'polygon(50% 50%, 50% 50%, 50% 50%, 50% 50%, 50% 50%, 50% 50%, 50% 50%, 50% 50%, 50% 50%, 50% 50%)',
						},
						step2: {
							initial: 'polygon(50% 50%, 50% 50%, 50% 50%, 50% 50%, 50% 50%, 50% 50%, 50% 50%, 50% 50%, 50% 50%, 50% 50%)',
							final: 'polygon(50% 0%, 80% 10%, 100% 35%, 100% 70%, 80% 90%, 50% 100%, 20% 90%, 0% 70%, 0% 35%, 20% 10%)',
						}
					},
					scrollTrigger: {
						start: 'top bottom+=20%',
						end: 'bottom top'
					},
					perspective: 500
				} 
			},
			{
				id: '#morph-fadein', 
				animationProfile: fx6,
				interactiveTilt: true, // This item should have the InteractiveTilt effect
				options: {
					clipPaths: {
						step1: {
							initial: 'polygon(50% 0%, 50% 50%, 50% 50%, 50% 100%)',
							final: 'polygon(0% 0%, 100% 0%, 100% 100%, 0% 100%)',
						},
					},
					scrollTrigger: {
						start: 'center bottom',
						end: '+=80%'
					},
					perspective: 1000
				} 
			},
		];

		// Iterate over the items and apply their animations
		items.forEach(item => {
			const itemElement = document.querySelector(item.id);
			// Check if element exists and has an animation profile
			if ( itemElement && item.animationProfile ) {
				// Apply the animation profile to the element with the specified options
				item.animationProfile(itemElement, item.options);

				// Check if the interactive tilt effect should be applied
				if ( item.interactiveTilt ) {
					// Instantiate the InteractiveTilt object for this item
					new InteractiveTilt(itemElement);
				}
			} else {
				// Warn if the element or animation profile is not found
				console.warn(`Element with ID ${item.id} or its animation profile is not defined.`);
			}
		});
	}

	// Preloading all images specified by the selector
	preloadImages('.content_morph__img-inner').then(() => {
		// Initialize Lenis
		initSmoothScrolling();
		// Apply scroll-triggered animations to each item
		scroll();
	});
	
})(jQuery);