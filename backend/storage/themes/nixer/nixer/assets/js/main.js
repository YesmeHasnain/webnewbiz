/***************************************************
==================== JS INDEX ======================
****************************************************
01. PreLoader Js
02. Back To Top Js
03. Sticky Header
04. Common Js
05. Mobile Menu Js
06. Offcanvas Js
07. Body overlay Js
08. MagnificPopup view
09. Hover to Active class
10. Counter Js
11. Hover to active Js
12. Accordion item active Js
13. Password Toggle Js
14. Plus Minus Js
15. Custom Select Js
****************************************************/


(function ($) {
	"use strict";


		var windowOn = $(window);


		////////////////////////////////////////////////////
		// 01. PreLoader Js
		windowOn.on('load', function () {
			$("#loading").fadeOut(500);
		});


		////////////////////////////////////////////////////
		// 02. Back To Top Js
		$(window).on('scroll', function() {
			if ($(this).scrollTop() > 300) {
				$('.back-to-top-wrapper').addClass('back-to-top-btn-show');
			} else {
				$('.back-to-top-wrapper').removeClass('back-to-top-btn-show');
			}
		});
	
	
		$('#back_to_top').on('click', function(e) {
			e.preventDefault();
			$('html, body').animate({scrollTop:0}, '300');
		});


		////////////////////////////////////////////////////
		// 03. Sticky Header
		$(window).on('scroll', function () {
			var scroll = $(window).scrollTop();
			if (scroll < 20) {
				$("#header-sticky").removeClass("header-sticky");
			} else {
				$("#header-sticky").addClass("header-sticky");
			}
		});


		////////////////////////////////////////////////////
		// 04. Common Js
		$("[data-background]").each(function () {
			$(this).css("background-image", "url( " + $(this).attr("data-background") + "  )");
		});
		$("[data-mask-img]").each(function () {
			$(this).css("-webkit-mask-image", "url( " + $(this).attr("data-mask-img") + "  )");
		});

		$("[data-width], [data-height]").each(function () {
			const $this = $(this);
			
			// Set the width if data-width is present
			if ($this.attr("data-width")) {
					$this.css("width", $this.attr("data-width"));
			}
	
			// Set the height if data-height is present
			if ($this.attr("data-height")) {
					$this.css("height", $this.attr("data-height"));
			}
		});
	

		$("[data-bg-color]").each(function () {
			$(this).css("background-color", $(this).attr("data-bg-color"));
		});

		$("[data-text-color]").each(function () {
			$(this).css("color", $(this).attr("data-text-color"));
		});


		////////////////////////////////////////////////////
		// 05. Mobile Menu Js 
		var tpMenuWrap = $('.tp-mobile-menu-active > ul').clone();
		var tpSideMenu = $('.tp-offcanvas-menu nav');
		tpSideMenu.append(tpMenuWrap);
		if ($(tpSideMenu).find('.sub-menu, .tp-mega-menu').length != 0) {
			$(tpSideMenu).find('.sub-menu, .tp-mega-menu').parent().append('<button class="tp-menu-close"><i class="fa-light fa-plus"></i></button>');
		}

		var sideMenuList = $('.tp-offcanvas-menu nav > ul > li button.tp-menu-close, .tp-offcanvas-menu nav > ul li.has-dropdown > a');
		$(sideMenuList).on('click', function (e) {
			e.preventDefault();
			if (!($(this).parent().hasClass('active'))) {
				$(this).parent().addClass('active');
				$(this).siblings('.sub-menu, .tp-mega-menu').slideDown();
			} else {
				$(this).siblings('.sub-menu, .tp-mega-menu').slideUp();
				$(this).parent().removeClass('active');
			}
		});
	
		if ($('.tp-menu-fullwidth').length > 0) {
			var currentElement = $('.tp-menu-fullwidth');
			var previousDiv = currentElement.parent().parent();
			previousDiv.addClass('has-homemenu');
		}

		///////////////////////////////////////////////////
		// 06. Offcanvas Js
		$(".offcanvas-open-btn").on("click", function () {
			$(".offcanvas__area").addClass("offcanvas-opened");
			$(".body-overlay").addClass("opened");
		});
		$(".offcanvas-close-btn ,.tp-main-menu-mobile .tp-onepage-menu li a  > *:not(button)").on("click", function () {
			$(".offcanvas__area").removeClass("offcanvas-opened");
			$(".body-overlay").removeClass("opened");
		});


		// style 2
		$(".offcanvas-open-btn").on("click", function () {
			$(".tp-offcanvas-2-area").addClass("opened");

			setTimeout(() => {
				$('.tp-text-hover-effect-word').addClass('animated-text');
			}, 900);
		});

		$(".tp-offcanvas-2-close-btn").on("click", function () {

			setTimeout(() => {
				$('.tp-text-hover-effect-word').removeClass('animated-text');
			}, 1200);

			$(".tp-offcanvas-2-area").removeClass("opened");
			$(".body-overlay").removeClass("opened");

		});


		////////////////////////////////////////////////////
		// 07. Body overlay Js
		$(".body-overlay").on("click", function () {
			$(".offcanvas__area").removeClass("offcanvas-opened");
			$(".tp-search-area").removeClass("opened");
			$(".cartmini__area").removeClass("cartmini-opened");
			$(".body-overlay").removeClass("opened");
		});

		$(".cartmini-open-btn").on("click", function () {
			$(".cartmini__area").addClass("cartmini-opened");
			$(".body-overlay").addClass("opened");
		});
		$(".cartmini-close-btn").on("click", function () {
			$(".cartmini__area").removeClass("cartmini-opened");
			$(".body-overlay").removeClass("opened");
		});

		
		///////////////////////////////////////////////
		// Testimonial slide active
		if ($('.tp-testimonial-active').length > 0) {
			var slider = new Swiper('.tp-testimonial-active', {
				slidesPerView: 1,
				speed:1500,
				loop: true,
				spaceBetween: 30,
				navigation: {
					nextEl: ".tp-testimonial-next",
					prevEl: ".tp-testimonial-prev",
				},
				autoplay: {
					delay: 4000,
					},
			});
		}
		

		///////////////////////////////////////////////
		// Testimonial 3 active
		if ($('.tp-testimonial-3-active').length > 0) {
			var slider = new Swiper('.tp-testimonial-3-active', {
				slidesPerView: 1,
				speed:1500,
				loop: true,
				spaceBetween: 30,
				pagination: {
					el: ".tp-testimonial-dot",
					clickable: true,
				},
				autoplay: {
				delay: 4000,
				},
			});
		}


		///////////////////////////////////////////////
		// Testimonial 5 active
		if ($('.tp-testimonial-5-active').length > 0) {
			var slider = new Swiper('.tp-testimonial-5-active', {
				slidesPerView: 3,
				speed:1500,
				loop: true,
				spaceBetween: 40,
				navigation: {
					nextEl: ".tp-testimonial-next",
					prevEl: ".tp-testimonial-prev",
				},
				autoplay: {
					delay: 4000,
				},
				breakpoints: {
					'1200': {
						slidesPerView: 3,
					},
					'992': {
						slidesPerView: 3,
						spaceBetween: 30,
					},
					'768': {
						slidesPerView: 2,
						spaceBetween: 30,
					},
					'576': {
						slidesPerView: 1,
						spaceBetween: 30,
					},
					'0': {
						slidesPerView: 1,
						spaceBetween: 30,
					},
				},
			});
		}

		
		///////////////////////////////////////////////
		// team slide active
		if ($('.tp-testimonial-team-active').length > 0) {
			var slider = new Swiper('.tp-testimonial-team-active', {
				slidesPerView: 4,
				loop: true,
				spaceBetween: 30,
				breakpoints: {
					'1200': {
						slidesPerView: 4,
					},
					'992': {
						slidesPerView: 3.5,
					},
					'768': {
						slidesPerView: 2.8,
					},
					'576': {
						slidesPerView: 2,
					},
					'0': {
						slidesPerView: 1.5,
					},
				},
			});
		}


		///////////////////////////////////////////////
		// Slider slide active
		if ($('.tp-slider-active').length > 0) {
			var slider = new Swiper('.tp-slider-active', {
				loop: true,
				freemode: true,
				slidesPerView: 'auto',
				spaceBetween: 15,
				centeredSlides: true,
				allowTouchMove: false,
				slidesPerView: 7,
				speed: 6000,
				autoplay: {
				  delay: 1,
				  disableOnInteraction: true,
				},
				breakpoints: {
					'1700': {
						slidesPerView: 7,
					},
					'1600': {
						slidesPerView: 6,
					},
					'1400': {
						slidesPerView: 5,
					},
					'1200': {
						slidesPerView: 4,
					},
					'992': {
						slidesPerView: 3.5,
					},
					'768': {
						slidesPerView: 2.8,
					},
					'576': {
						slidesPerView: 2,
					},
					'0': {
						slidesPerView: 1.5,
					},
				},
			});
		}


		///////////////////////////////////////////////
		// Slider instragram active
		if ($('.tp-instragram-active').length > 0) {
			var slider = new Swiper('.tp-instragram-active', {
				slidesPerView: 6,
				loop: true,
				spaceBetween: 30,
				breakpoints: {
					'1700': {
						slidesPerView: 6,
					},
					'1600': {
						slidesPerView: 6,
					},
					'1400': {
						slidesPerView: 5,
					},
					'1200': {
						slidesPerView: 4,
					},
					'992': {
						slidesPerView: 3.5,
					},
					'768': {
						slidesPerView: 2.8,
					},
					'576': {
						slidesPerView: 2,
					},
					'0': {
						slidesPerView: 1,
					},
				},
			});
		}


		//////////////////////////////////////////////
		// brand slide active
		if ($('.tp-brand-active').length > 0) {
			var slider = new Swiper('.tp-brand-active', {
				slidesPerView: 6,
				speed: 3000,
				loop: true,
				spaceBetween: 100,
				autoplay: {
				delay: 3000,
				},
				breakpoints: {
					'1700': {
						slidesPerView: 6,
					},
					'1600': {
						slidesPerView: 6,
					},
					'1400': {
						slidesPerView: 5,
					},
					'1200': {
						slidesPerView: 5,
					},
					'992': {
						slidesPerView: 4,
						spaceBetween: 50,
					},
					'768': {
						slidesPerView: 3,
						spaceBetween: 50,
					},
					'576': {
						slidesPerView: 3,
						spaceBetween: 80,
					},
					'0': {
						slidesPerView: 2,
						spaceBetween: 50,
					},
				},
			});
		}

		
		//////////////////////////////////////////////
		// Hero seven active
		if ($('#showcase-slider-wrappper').length > 0) {
			// Function to update the active slide
			const updateActiveSlide = () => {
					$('.tp-slider-dot').find('.swiper-pagination-bullet').each(function () {
							if (!$(this).hasClass("swiper-pagination-bullet-active")) {
								handleActiveSlideClick('#trigger-slides .swiper-slide-active');
								handleActiveSlideClick('#trigger-slides .swiper-slide-duplicate-active');
							}
					});
			};
	
			// Function to handle slide click events
			const handleActiveSlideClick = (selector) => {
					$(selector).find('div').first().each(function () {
							if (!$(this).hasClass("active")) {
								$(this).trigger('click');
							}
					});
			};
	
			// WebGL Shader Configuration
			const vertex = `
					varying vec2 vUv;
					void main() {
							vUv = uv;
							gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
					}
			`;
			const fragment = `
					varying vec2 vUv;
					uniform sampler2D currentImage;
					uniform sampler2D nextImage;
					uniform sampler2D disp;
					uniform float dispFactor;
					uniform float effectFactor;
					uniform vec4 resolution;
					void main() {
							vec2 uv = (vUv - vec2(0.5)) * resolution.zw + vec2(0.5);
							vec4 disp = texture2D(disp, uv);
							vec2 distortedPosition = vec2(uv.x + dispFactor * (disp.r * effectFactor), uv.y);
							vec2 distortedPosition2 = vec2(uv.x - (1.0 - dispFactor) * (disp.r * effectFactor), uv.y);
							vec4 _currentImage = texture2D(currentImage, distortedPosition);
							vec4 _nextImage = texture2D(nextImage, distortedPosition2);
							vec4 finalTexture = mix(_currentImage, _nextImage, dispFactor);
							gl_FragColor = finalTexture;
					}
			`;
	
			const gl_canvas = new WebGL({
				vertex: vertex,
				fragment: fragment,
			});

			// Add events for the slide triggers
			const addEvents = () => {
				const triggerSlide = Array.from(document.getElementById('trigger-slides').querySelectorAll('.slide-wrap'));
				gl_canvas.isRunning = false;

				triggerSlide.forEach((el) => {
					el.addEventListener('click', function () {
						if (!gl_canvas.isRunning) {
							gl_canvas.isRunning = true;

							document.getElementById('trigger-slides').querySelectorAll('.active')[0].className = '';
							this.className = 'active';

							const slideId = parseInt(this.dataset.slide, 10);

							gl_canvas.material.uniforms.nextImage.value = gl_canvas.textures[slideId];
							gl_canvas.material.uniforms.nextImage.needsUpdate = true;

							gsap.to(gl_canvas.material.uniforms.dispFactor, {
									duration: 1,
									value: 1,
									ease: 'Sine.easeInOut',
									onComplete: function () {
											gl_canvas.material.uniforms.currentImage.value = gl_canvas.textures[slideId];
											gl_canvas.material.uniforms.currentImage.needsUpdate = true;
											gl_canvas.material.uniforms.dispFactor.value = 0.0;
											gl_canvas.isRunning = false;
									},
							});
						}
					});
				});
			};

			// Initialize Swiper
			const showcaseSwiper = new Swiper('#showcase-slider', {
				direction: "horizontal",
				loop: true,
				slidesPerView: 'auto',
				touchStartPreventDefault: false,
				speed: 1000,
				mousewheel: true,
				autoplay: {
					delay: 5000,
				},
				effect: 'fade',
				simulateTouch: true,
				parallax: true,
				navigation: {
					clickable: true,
					prevEl: '.tp-hero-prev',
					nextEl: '.tp-hero-next',
				},
				pagination: {
					el: '.tp-slider-dot',
					clickable: true,
				},
				on: {
					slidePrevTransitionStart: function () {
						updateActiveSlide();
					},
					slideNextTransitionStart: function () {
						updateActiveSlide();
					},
					init: function () {
						updateSlideNumbers(this); // Update numbers on initial load
					},
					slideChange: function () {
						updateSlideNumbers(this); // Update numbers when slide changes
					}
				},
			});

			// Function to update slide numbers
			function updateSlideNumbers(swiper) {
				const current = swiper.realIndex + 1; // Get the real index of the current slide
				const numbers = document.querySelector('.tp-hero-7-slider-numbers');
				const formattedCurrent = current < 10 ? `0${current}` : current; // Add leading zero for single digits
				numbers.innerHTML = `${formattedCurrent}`;
			}

			addEvents();
		}


		///////////////////////////////////////////////
		// Hero 8 slide active
		if ($('.tp-hero-8-active').length > 0) {
			var slider = new Swiper('.tp-hero-8-active', {
				slidesPerView: 4,
				speed: 1000,
				loop: true,
				mousewheel: true,
				spaceBetween: 30,
				autoplay: {
					delay: 5000,
				},
				breakpoints: {
					'1400': {
						slidesPerView: 4,
					},
					'1200': {
						slidesPerView: 4,
					},
					'992': {
						slidesPerView: 4,
					},
					'768': {
						slidesPerView: 3,
					},
					'576': {
						slidesPerView: 2,
					},
					'0': {
						slidesPerView: 1,
					},
				},
			});
		}


		////Postbox-slider
		var testimonial = new Swiper('.postbox__thumb-slider-active', {
			slidesPerView: 1,
			loop: true,
			autoplay: false,
			arrow: false,
			spaceBetween: 0,
			speed: 1000,
			effect: 'fade',
			breakpoints: {
				'1400': {
					slidesPerView: 1,
				},
				'1200': {
					slidesPerView: 1,
				},
				'992': {
					slidesPerView: 1,
				},
				'768': {
					slidesPerView: 1,
				},
				'576': {
					slidesPerView: 1,
				},
				'0': {
					slidesPerView: 1,
				},
			},
			a11y: false,

			pagination: {
				el: ".blog-sidebar-dot",
				clickable: true,
			},

			// Navigation arrows
			navigation: {
				prevEl: '.postbox-arrow-prev',
				nextEl: '.postbox-arrow-next',
			},

		});


		///////////////////////////////////
		/* 08. MagnificPopup view */
		$('.popup-image').magnificPopup({
			type: 'image',
			gallery: {
				enabled: true
			}
		});

		$('.about-popup-image').magnificPopup({
			type: 'image',
			gallery: {
				enabled: true
			}
		});

		/* magnificPopup video view */
		$(".popup-video").magnificPopup({
			type: "iframe",
		});

		////////////////////////////////////
		// 09. Hover to Active class
		$('.tp-service-4-item, .tp-blog-5-wrapper').on('mouseenter', function () {
			$('.tp-service-4-item, .tp-blog-5-wrapper').removeClass('active');
			$(this).addClass('active');
		});


		////////////////////////////////////
		// 10. Counter Js
		new PureCounter();


		////////////////////////////////////
		// 11. Hover to active Js
		$(function() {
			function hoverWidgetAnimation() {
				const $activeBg = $(".tp-service-wrapper .active-bg");
				let $currentItem = $(".tp-service-wrapper .current");
				let $lastHoveredItem = null;

				// Update active-bg position and height
				function updateActiveBg($element) {
						if (!$element.length) return;
						const topOffset = $element.offset().top;
						const height = $element.outerHeight();
						const menuTop = $(".tp-service-wrapper").offset().top;

						$element.removeClass("mleave").siblings().addClass("mleave");

						$activeBg.css({
								top: topOffset - menuTop + "px",
								height: height + "px"
						});
				}

				// Handle hover event
				$(".tp-service-wrapper .tp-service-item").on("mouseenter", function () {
						$currentItem.removeClass("current");
						$lastHoveredItem = $(this);
						updateActiveBg($lastHoveredItem);
				});

				// Handle mouse leave event
				$(".tp-service-wrapper").on("mouseleave", function () {
						if ($lastHoveredItem) {
								$(".tp-service-wrapper .tp-service-item").removeClass("current");
								$lastHoveredItem.addClass("current");
								$currentItem = $lastHoveredItem;
						}
						updateActiveBg($currentItem);
				});

				// Handle click event to manually set current item
				$(".tp-service-wrapper .tp-service-item").on("click", function () {
						$(".tp-service-wrapper .tp-service-item").removeClass("current");
						$(this).addClass("current");
						$currentItem = $(this);
						updateActiveBg($currentItem);
				});

				// Initialize with the current item or the first item
				if (!$currentItem.length) {
						$currentItem = $(".tp-service-wrapper .tp-service-item").first().addClass("current");
				}
				updateActiveBg($currentItem);
			}
			hoverWidgetAnimation();
		});


		////////////////////////////////////
		// 12. Accordion item active Js
		document.querySelectorAll(".accordion-header").forEach((header) => {
			header.addEventListener("click", function () {
				// Remove 'active' class from all accordion-item elements
				document.querySelectorAll(".accordion-item").forEach((item) => {
					item.classList.remove("active");
				});
		
				// Add 'active' class to the parent accordion-item of the clicked header
				this.closest(".accordion-item").classList.add("active");
			});
		});


		///////////////////////////////////
		// 13. Password Toggle Js
		if ($('#password-show-toggle').length > 0) {
			var btn = document.getElementById('password-show-toggle');

			btn.addEventListener('click', function (e) {

				var inputType = document.getElementById('tp_password');
				var openEye = document.getElementById('open-eye');
				var closeEye = document.getElementById('close-eye');

				if (inputType.type === "password") {
					inputType.type = "text";
					openEye.style.display = 'block';
					closeEye.style.display = 'none';
				} else {
					inputType.type = "password";
					openEye.style.display = 'none';
					closeEye.style.display = 'block';
				}
			});
		}


		///////////////////////////////////
		// 14. Plus Minus Js
		function tp_ecommerce() {
			$('.tp-cart-minus').on('click', function () {
			  var $input = $(this).parent().find('input');
			  var count = parseInt($input.val()) - 1;
			  count = count < 1 ? 1 : count;
			  $input.val(count);
			  $input.change();
			  return false;
			});
		  
			$('.tp-cart-plus').on('click', function () {
			  var $input = $(this).parent().find('input');
			  $input.val(parseInt($input.val()) + 1);
			  $input.change();
			  return false;
			});

			//  tpReturnCustomerLoginForm //
			$('.tp-checkout-login-form-reveal-btn').on('click', function () {
				$('#tpReturnCustomerLoginForm').slideToggle(400);
			  });
		  
			//  Show Coupon Toggle Js //
			$('.tp-checkout-coupon-form-reveal-btn').on('click', function () {
				$('#tpCheckoutCouponForm').slideToggle(400);
			});
		
	
			// Create An Account Toggle Js //
			$('#cbox').on('click', function () {
				$('#cbox_info').slideToggle(900);
			});
		
			// Shipping Box Toggle Js //
			$('#ship-box').on('click', function () {
				$('#ship-box-info').slideToggle(1000);
			});
		  
		}
		tp_ecommerce();
		$(document.body).on("updated_wc_div", function () {
	        tp_ecommerce();
	    });


		/////////////////////////////////
		// 15. Custom Select Js
		document.addEventListener("DOMContentLoaded", () => {
			const customSelect = document.getElementById("customSelect");
		  
			if (!customSelect) {
			  return;
			}
		  
			const selected = customSelect.querySelector(".selected");
			const options = customSelect.querySelector(".options");
		  
			selected.addEventListener("click", (event) => {
			  event.stopPropagation();
			  selected.classList.toggle("open");
			});
		  
			options.addEventListener("click", (event) => {
			  if (event.target.tagName === "LI") {
				const selectedText = event.target.textContent;
				selected.firstChild.textContent = selectedText;
				selected.classList.remove("open");
			  }
			});
		  
			document.addEventListener("click", () => {
			  selected.classList.remove("open");
			});
		});
	

		////////////////////////////////
		// Gsap start from hare
		////////////////////////////////
		gsap.registerPlugin(ScrollTrigger, ScrollSmoother, TweenMax, ScrollToPlugin);

		gsap.config({
			nullTargetWarn: false,
		});


		////////////////////////////////////
		// Smoth scroll js
		////////////////////////////////////
		if($('#smooth-wrapper').length && $('#smooth-content').length){
		
			let smoother = ScrollSmoother.create({
				smooth: 2,
				effects: true,
				smoothTouch: true,
				normalizeScroll: false,
				ignoreMobileResize: true,
			});
		}


		/* --- Split the text, Burrowing Owl --- */
		function setupSplits() {
		// Target all elements with class "animate-text"
		const elements = document.querySelectorAll('.titleBurrowing');

		// Loop over each element and apply SplitText animation
		elements.forEach(element => {
			const tlSplit = gsap.timeline(),
				splitInstance = new SplitText(element, {type: "words,chars"}),
				chars = splitInstance.chars; // Array of divs wrapping each character
			
			tlSplit.from(chars, {
			duration: 0.8,
			opacity: 0,
			y: 10,
			ease: "circ.out",
			stagger: 0.02,
			scrollTrigger: {
				trigger: element,
				start: "top 75%",
				end: "bottom center",
				scrub: 1
			}
			});
		});
		}

		// Refresh and call setupSplits
		ScrollTrigger.addEventListener("refresh", setupSplits);
		setupSplits();


		////////////////////////////////////
		// Text hover Animation js
		////////////////////////////////////
		const style = document.createElement('style');
		style.setAttribute('type', 'text/css');
		style.innerHTML = `
		[data-tha] {
			/* border: 1px solid red; */
			display: inline-block;
			position: relative;
			overflow: hidden;
		}

		[data-tha-span-1],
		[data-tha-span-2] {
			display: inline-block;
		}

		[data-tha-span-2] {
			position: absolute;
			top: 100%;
			left: 0;
		}
		`;
		document.querySelector('head').append(style);

		const aaa = document.querySelectorAll('[data-tha]');

		aaa.forEach((a) => {
			const html = a.innerHTML;
			a.innerHTML = '';

			const span1 = document.createElement('span');
			const span2 = document.createElement('span');
			span1.setAttribute('data-tha-span-1', '');
			span2.setAttribute('data-tha-span-2', '');
			span1.innerHTML = html;
			span2.innerHTML = html;

			a.append(span1);
			a.append(span2);

			a.addEventListener('mouseenter', (e) => {
				const span1 = e.target.querySelector('[data-tha-span-1');
				const span2 = e.target.querySelector('[data-tha-span-2');
				gsap.to([span1, span2], { yPercent: -100 });
			});
			a.addEventListener('mouseleave', (e) => {
				const span1 = e.target.querySelector('[data-tha-span-1');
				const span2 = e.target.querySelector('[data-tha-span-2');
				gsap.to([span1, span2], { yPercent: 0 });
			});
		});
		//

		////////////////////////////////////
		// section to section upper
		let pr = gsap.matchMedia();
		pr.add("(min-width: 991px)", () => {

			let tl = gsap.timeline();
			let projectpanels = document.querySelectorAll('.project-panel')
			projectpanels.forEach((section, index) => {
				tl.to(section, {
					scrollTrigger: {
						trigger: section,
						pin: section,
						scrub: 1,
						start: 'top top',
						end: "bottom 100%",
						endTrigger: '.tp-project-panel',
						pinSpacing: false,
						markers: false,
					},
				})
			})
		});
		////////////////////////////////////


		////////////////////////////////////
		// Btn Bounce
		///////////////////////////////////
		if ($('.tp-btn-trigger').length > 0) {

			gsap.set(".tp-btn-bounce", { y: -100, opacity: 0 });
			var mybtn = gsap.utils.toArray(".tp-btn-bounce");
			mybtn.forEach((btn) => {
				var $this = $(btn);
				gsap.to(btn, {
					scrollTrigger: {
						trigger: $this.closest('.tp-btn-trigger'),
						start: "top center",
						markers: false
					},
					duration: 1,
					ease: "bounce.out",
					y: 0,
					opacity: 1,
				})
			});
	
		}
		//////////////////////////////////


		////////////////////////////////
		// Image Reveal Animation
		let tp_img_reveal = document.querySelectorAll(".tp_img_reveal");

		tp_img_reveal.forEach((img_reveal) => {
			let image = img_reveal.querySelector("img");
			let tl = gsap.timeline({
				scrollTrigger: {
					trigger: img_reveal,
					start: "top 70%",
				}
			});

			tl.set(img_reveal, { autoAlpha: 1 });
			tl.from(img_reveal, 1.5, {
				xPercent: -100,
				ease: Power2.out
			});
			tl.from(image, 1.5, {
				xPercent: 100,
				scale: 1.5,
				duration: 3,
				delay: -1.5,
				ease: Power2.out
			});
		});
		///////////////////////////////


		///////////////////////////////
		/* Text slide mouse scroll */
		///////////////////////////////
		let tl = gsap.timeline();
		
		tl.from([".text-slide-1"], { xPercent: 20 })
			.from([".text-slide-2"], { xPercent: -20 }, 0);

		ScrollTrigger.create({
			trigger: ".tp-project-2-ptb",
			start: "top 100%",
			end: "100%",
			scrub: 1,
			markers: false,
			animation: tl.play()
		});
		///////////////////////////////

		
		///////////////////////////////
		// text animation 
		if ($('.tp-char-animation').length > 0) {
			let char_come = gsap.utils.toArray(".tp-char-animation");
			char_come.forEach(splitTextLine => {
				const tl = gsap.timeline({
					scrollTrigger: {
						trigger: splitTextLine,
						start: 'top 90%',
						end: 'bottom 60%',
						scrub: false,
						markers: false,
						toggleActions: 'play none none none'
					}
				});

				const itemSplitted = new SplitText(splitTextLine, { type: "chars, words" });
				gsap.set(splitTextLine, { perspective: 300 });
				itemSplitted.split({ type: "chars, words" })
				tl.from(itemSplitted.chars,
					{
						duration: 1,
						delay: 0.5,
						x: 100,
						autoAlpha: 0,
						stagger: 0.05
					});
			});
		}

		///////////////////////////////////////////////////
		// text-animetion-gsap
		if ($('.tp-title-anim').length > 0) {
			let splitTitleLines = gsap.utils.toArray(".tp-title-anim");
			splitTitleLines.forEach(splitTextLine => {
				const tl = gsap.timeline({
				scrollTrigger: {
					trigger: splitTextLine,
					start: 'top 90%',
					end: 'bottom 60%',
					scrub: false,
					markers: false,
					toggleActions: 'play none none none'
				}
				});
	
				const itemSplitted = new SplitText(splitTextLine, { type: "words, lines" });
				gsap.set(splitTextLine, { perspective: 300});
				itemSplitted.split({ type: "lines" })
				tl.from(itemSplitted.lines, { duration: 1, delay: 0.3, opacity: 0, rotationX: -50, force3D: true, transformOrigin: "top center -50", stagger: 0.2 });
			});	
		}

		///////////////////////////////
		//Reveal Animation 
		const anim_reveal2 = document.querySelectorAll(".tp_reveal_anim-2");
		anim_reveal2.forEach(areveal => {

			var duration_value = 2
			var onscroll_value = 1
			var stagger_value = 0.05
			var data_delay = 0.1

			if (areveal.getAttribute("data-duration")) {
				duration_value = areveal.getAttribute("data-duration");
			}
			if (areveal.getAttribute("data-on-scroll")) {
				onscroll_value = areveal.getAttribute("data-on-scroll");
			}
			if (areveal.getAttribute("data-stagger")) {
				stagger_value = areveal.getAttribute("data-stagger");
			}
			if (areveal.getAttribute("data-delay")) {
				data_delay = areveal.getAttribute("data-delay");
			}

			areveal.split = new SplitText(areveal, {
				type: "lines,words,chars",
				linesClass: "tp-reveal-line-2"
			});

			if (onscroll_value == 1) {
				areveal.anim = gsap.from(areveal.split.chars, {
					scrollTrigger: {
						trigger: areveal,
						start: 'top 85%',
					},
					duration: duration_value,
					delay: data_delay,
					ease: "circ.out",
					y: 200,
					stagger: stagger_value,
					opacity: 0,
				});
			} else {
				areveal.anim = gsap.from(areveal.split.chars, {
					duration: duration_value,
					delay: data_delay,
					ease: "circ.out",
					y: 200,
					stagger: stagger_value,
					opacity: 0,
				});
			}

		});
		///////////////////////////////


		////////////////////////////
		// scroll text slide start
		if ($('.tp-about-me-text-ptb, .tp-service-details-ptb').length > 0) {
			gsap.set('.tp-text-effect', {
				x: '25%'
			});
	
			gsap.timeline({
				scrollTrigger: {
					trigger: '.tp-text-effect ',
					start: '-1500 10%',
					end: 'bottom 10%',
					scrub: true,
					invalidateOnRefresh: true
				}
			})
			.to('.tp-text-effect ', {
				x: '-80%'
			});
		}


		///////////////////////////////
		//Text Jumping Animation 
		if ($('.footer-big-text').length > 0) {

			let cta = gsap.timeline({
				repeat: -1,
				delay: 0.5,
				scrollTrigger: {
					trigger: '.footer-big-text',
					start: 'bottom 100%-=50px'
				}
			});
			gsap.set('.footer-big-text', {
				opacity: 0
			});
			gsap.to('.footer-big-text', {
				opacity: 1,
				duration: 1,
				ease: 'power1.out',
				scrollTrigger: {
					trigger: '.footer-big-text',
					start: 'bottom 100%-=50px',
					once: true
				}
			});
		
			let mySplitText = new SplitText(".footer-big-text", { type: "words,chars" });
			let chars = mySplitText.chars;
			let endGradient = chroma.scale(['#FFF', '#FFF', '#FFF', '#FFF', '#FFF']);
			cta.to(chars, {
				duration: 0.5,
				scaleY: 0.6,
				ease: "power1.out",
				stagger: 0.04,
				transformOrigin: 'center bottom'
			});
			cta.to(chars, {
				yPercent: -20,
				ease: "elastic",
				stagger: 0.03,
				duration: 0.8
			}, 0.5);
			cta.to(chars, {
				scaleY: 1,
				ease: "elastic.out",
				stagger: 0.03,
				duration: 1.5
			}, 0.5);
			cta.to(chars, {
				color: (i, el, arr) => {
					return endGradient(i / arr.length).hex();
				},
				ease: "power1.out",
				stagger: 0.03,
				duration: 0.3
			}, 0.5);
			cta.to(chars, {
				yPercent: 0,
				ease: "back",
				stagger: 0.03,
				duration: 0.8
			}, 0.7);
			cta.to(chars, {
				color: '#fff',
				duration: 1.4,
				stagger: 0.05
			});
		}
		//////////////////////////////


		////////////////////////////
		// webgl image effect
		$('img').imagesLoaded()
		.done(function(instance) {
		  allImagesLoaded();
		})
		.fail(function(instance) {
		  
		  handleFailedImages(instance);
		});
	  
		function allImagesLoaded() {
			
			$('.tp-hover-distort-wrapper').each(function(){
				var $this = $(this)
				var canvas = $this.find('.canvas')
				
				if($this.find('img.front')){
					$this.css({
						"width" : $this.find('img.front').width(),
						"height" : $this.find('img.front').height(),
					})
				}
			
				var frontImage = $this.find('img.front').attr('src')
				var backImage = $this.find('img.back').attr('src')
				var displacementImage = $this.find('.tp-hover-distort').attr('data-displacementImage')
		
				var distortEffect = new hoverEffect({
					parent: canvas[0],
					intensity: 3,
					speedIn: 2,
					speedOut: 2,
					angle: Math.PI / 3,
					angle2: -Math.PI / 3,
					image1: frontImage,
					image2: backImage,
					displacementImage: displacementImage,
					imagesRatio: $this.find('.tp-hover-distort').height()/$this.find('.tp-hover-distort').width()
				});
		
			});
		}
	
		function handleFailedImages(instance) {
			console.error('One or more images failed to load.');
	
			var failedImages = instance.images.filter(function(img) {
			return !img.isLoaded;
			});
	
			failedImages.forEach(function(failedImage) {
			console.error('Failed image source:', failedImage.img.src);
			});
		}


		/////////////////////////////
		// click to smoothly down
		function smoothScroll() {
			$('.smooth a').on('click', function (event) {
				if ($(this).is('#respond')) return;
				var target = $(this.getAttribute('href'));
				if (target.length) {
					event.preventDefault();
					$('html, body').stop().animate({
						scrollTop: target.offset().top - -60
					}, 1500);
				}
			});
		}
		smoothScroll();


		/////////////////////
		// zoom in
		$(".anim-zoomin").each(function() {

			// Add wrap <div>.
			$(this).wrap('<div class="anim-zoomin-wrap"></div>');

			// Add overflow hidden.
			$(".anim-zoomin-wrap").css({ "overflow": "hidden" })

			var $this = $(this);
			var $asiWrap = $this.parents(".anim-zoomin-wrap");

			let tp_ZoomIn = gsap.timeline({
				scrollTrigger: {
					trigger: $asiWrap,
					start: "top 100%",
					markers: false,
				}
			});
			tp_ZoomIn.from($this, { duration: 1.5, autoAlpha: 0, scale: 1.2, ease: Power2.easeOut, clearProps:"all" });

		});
		////////////////////////


		///////////////////////////////
		// Wow animation
		//////////////////////////////
		gsap.set(".tp_fade_bottom", { y: 100, opacity: 0 });
		const fadeArray = gsap.utils.toArray(".tp_fade_bottom")
		fadeArray.forEach((item, i) => {
			let fadeTl = gsap.timeline({
				scrollTrigger: {
					trigger: item,
					start: "top center+=400",
				}
			})
			fadeTl.to(item, {
				y: 0,
				opacity: 1,
				ease: "power2.out",
				duration: 1.5,
			})
		})

		gsap.set(".tp_fade_top", { y: -100, opacity: 0 });
		const fadetopArray = gsap.utils.toArray(".tp_fade_top")
		fadetopArray.forEach((item, i) => {
			let fadeTl = gsap.timeline({
				scrollTrigger: {
					trigger: item,
					start: "top center+=100",
				}
			})
			fadeTl.to(item, {
				y: 0,
				opacity: 1,
				ease: "power2.out",
				duration: 2.5,
			})
		})

		gsap.set(".tp_fade_left", { x: -100, opacity: 0 });
		const fadeleftArray = gsap.utils.toArray(".tp_fade_left")
		fadeleftArray.forEach((item, i) => {
			let fadeTl = gsap.timeline({
				scrollTrigger: {
					trigger: item,
					start: "top center+=100",
				}
			})
			fadeTl.to(item, {
				x: 0,
				opacity: 1,
				ease: "power2.out",
				duration: 2.5,
			})
		})

		gsap.set(".tp_fade_right", { x: 100, opacity: 0 });
		const faderightArray = gsap.utils.toArray(".tp_fade_right")
		faderightArray.forEach((item, i) => {
			let fadeTl = gsap.timeline({
				scrollTrigger: {
					trigger: item,
					start: "top center+=100",
				}
			})
			fadeTl.to(item, {
				x: 0,
				opacity: 1,
				ease: "power2.out",
				duration: 2.5,
			})
		})

		if ($(".tp_fade_anim").length > 0) {
			gsap.utils.toArray(".tp_fade_anim").forEach((item) => {
				let tp_fade_offset = item.getAttribute("data-fade-offset") || 40,
					tp_duration_value = item.getAttribute("data-duration") || 0.75,
					tp_fade_direction = item.getAttribute("data-fade-from") || "bottom",
					tp_onscroll_value = item.getAttribute("data-on-scroll") || 1,
					tp_delay_value = item.getAttribute("data-delay") || 0.15,
					tp_ease_value = item.getAttribute("data-ease") || "power2.out",
					tp_anim_setting = {
						opacity: 0,
						ease: tp_ease_value,
						duration: tp_duration_value,
						delay: tp_delay_value,
						x: (tp_fade_direction == "left" ? -tp_fade_offset : (tp_fade_direction == "right" ? tp_fade_offset : 0)),
						y: (tp_fade_direction == "top" ? -tp_fade_offset : (tp_fade_direction == "bottom" ? tp_fade_offset : 0)),
					};
	
				if (tp_onscroll_value == 1) {
					tp_anim_setting.scrollTrigger = {
						trigger: item,
						start: 'top 85%',
					};
				}
				gsap.from(item, tp_anim_setting);
			});
		}

		// Join the Conversation
		gsap.registerPlugin(ScrollToPlugin);
		document.querySelectorAll('h4 > a[href^="#"]').forEach(anchor => {
			anchor.addEventListener("click", function (e) {
				e.preventDefault();

				let targetId = this.getAttribute("href");
				let targetElement = document.querySelector(targetId);

				if (targetElement) {
					gsap.to(window, {
						duration: 1, 
						scrollTo: { y: targetElement, offsetY: 80 },
						ease: "power2.inOut"
					});
				}
			});
		});

		// Mobile Mega Menu
		var tpMenuWrap = $('.tp-mobile-menu-active ul.mega-menu').clone();
	    var tpSideMenu = $('.tp-offcanvas-menu nav');

	    if (tpMenuWrap.length && tpSideMenu.length) {
	        tpSideMenu.append(tpMenuWrap);

	        var megaSubMenus = tpSideMenu.find('ul.mega-menu > li.mega-menu-item > ul.mega-sub-menu');
	        if (megaSubMenus.length) {
	        	megaSubMenus.hide();
	            megaSubMenus.parent().append('<button class="tp-menu-close"><i class="fa-light fa-plus"></i></button>');
	        }
	    }

	    $('.tp-offcanvas-menu nav').on('click', 'ul.mega-menu button.tp-menu-close, ul.mega-menu > li.mega-menu-item > a.mega-menu-link', function (e) {
	        e.preventDefault();
	        var parent = $(this).parent();
	        var submenu = parent.children('ul.mega-sub-menu');

	        if (!parent.hasClass('active')) {
	            parent.addClass('active');
	            submenu.stop().slideDown();
	        } else {
	            submenu.stop().slideUp();
	            parent.removeClass('active');
	        }
	    });

	    if ($('.tp-menu-fullwidth').length) {
	        $('.tp-menu-fullwidth').closest('div').parent().addClass('has-homemenu');
	    }

	    jQuery(document).ready(function($) {
		    $('.woocommerce-ordering .custom-select').niceSelect();
		});

})(jQuery);
