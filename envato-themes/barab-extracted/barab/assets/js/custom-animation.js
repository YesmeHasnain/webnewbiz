jQuery(document).ready(function($) {
     
    /*---------- Wow Active ----------*/
    var wow = new WOW({
        boxClass: 'wow',
        animateClass: 'animated',
        offset: 0,
        mobile: false,
        live: true
    });
    wow.init();

   /*---------------------------------------------
         GSAP Animation Effect Start Here 
------------------------------------------------*/

    /*------- Smooth Scroll -------*/
    gsap.registerPlugin(ScrollTrigger);

    let lenis;
    let tickerId = null;

    const prefersReducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

    function initializeLenis() {
        lenis = new Lenis({
            lerp: 0.07, // Smoothing factor
            smooth: true,
        });

        lenis.on("scroll", ScrollTrigger.update);

        // Use GSAP's ticker to sync with Lenis
        tickerId = gsap.ticker.add((time) => {
            if (lenis) lenis.raf(time * 1000);
        });

        // Allow native scroll inside certain elements
        document.querySelectorAll(".allow-natural-scroll").forEach((el) => {
            el.addEventListener("wheel", (e) => e.stopPropagation(), { passive: true });
            el.addEventListener("touchmove", (e) => e.stopPropagation(), { passive: true });
        });
    }

    function enableOrDisableLenis() {
        if (prefersReducedMotion) return;

        if (window.innerWidth > 991) {
            if (!lenis) initializeLenis();
            lenis.start();
        } else {
            if (lenis) {
                lenis.stop();
                lenis = null;
            }
            // Clean up the GSAP ticker if it was added
            if (tickerId) {
                gsap.ticker.remove(tickerId);
                tickerId = null;
            }
        }
    }

    // Call on load
    enableOrDisableLenis();

    // Optional: Re-check on resize
    window.addEventListener("resize", () => {
        enableOrDisableLenis();
    });


    // Only run animations if screen is more than 575px
    if (window.innerWidth > 575) {
    
        // Animate: .gsap-scroll-float-down
        gsap.utils.toArray(".gsap-scroll-float-down").forEach((el) => {
        if (el) {
            gsap.to(el, {
            y: 80,
            ease: "none",
            scrollTrigger: {
                trigger: el,
                start: "top 20%",
                end: "bottom top",
                scrub: 2
            }
            });
        }
        });

        // Animate: .gsap-scroll-float-down2
        gsap.utils.toArray(".gsap-scroll-float-down2").forEach((el) => {
        if (el) {
            gsap.to(el, {
            y: 250,
            ease: "none",
            scrollTrigger: {
                trigger: el,
                start: "top 30%",
                end: "bottom top",
                scrub: 2
            }
            });
        }
        });

        // Animate: .gsap-scroll-float-up
        gsap.utils.toArray(".gsap-scroll-float-up").forEach((el) => {
        if (el) {
            gsap.to(el, {
            y: -250,
            ease: "none",
            scrollTrigger: {
                trigger: el,
                start: "top 100%",
                end: "bottom top",
                scrub: 2
            }
            });
        }
        });

        // Animate: .gsap-fade-left
        gsap.utils.toArray(".gsap-fade-left").forEach((el) => {
            if (el) {
                gsap.from(el, {
                x: -150,
                opacity: 0,
                scrollTrigger: {
                    trigger: el,
                    start: "top 80%",
                    end: "bottom top"
                }
                });
            }
        });

        // Animate: .gsap-scroll-rotate
        if (document.querySelector(".gsap-scroll-rotate")) {
        gsap.to(".gsap-scroll-rotate", {
            rotate: 30,
            scrollTrigger: {
            trigger: ".gsap-scroll-rotate",
            start: "top 30%",
            end: "bottom top",
            scrub: 2
            }
        });
        }

        // Banner Timeline
        let bannerTl = gsap.timeline();

        // Animate: .gsap-scale-down-fade
        if (document.querySelector(".gsap-scale-down-fade")) {
        bannerTl.from(".gsap-scale-down-fade", {
            y: -500,
            scale: 0,
            opacity: 0,
            duration: 1,
            delay: 0.5
        });
        }

        // Animate: .gsap-scale-up-fade
        if (document.querySelector(".gsap-scale-up-fade")) {
        bannerTl.from(".gsap-scale-up-fade", {
            y: 400,
            scale: 0,
            opacity: 0,
            duration: 1,
            delay: 0.3
        });
        }

        // Animate: .gsap-fade-up
        if (document.querySelector(".gsap-fade-up")) {
        bannerTl.from(".gsap-fade-up", {
            y: 100,
            opacity: 0,
            duration: 0.3
        });
        }

        // Animate: .gsap-width-up
        if (document.querySelector(".gsap-width-up")) {
        bannerTl.from(".gsap-width-up", {
            width: 0,
            opacity: 0,
            duration: 0.4
        });
        }

        
        // ===== 1. Animate Subtitle (text-anime-style-1)
        document.querySelectorAll(".text-anime-style-1").forEach((subTitle) => {
        gsap.from(subTitle, {
            y: 50,
            opacity: 0,
            duration: 0.6,
            ease: "power4.out",
            scrollTrigger: {
            trigger: subTitle,
            start: "top 80%",
            toggleActions: "play none none none",
            },
        });
        });

        // ===== 2. Animate Title with split letters (text-anime-style-2)

        document.querySelectorAll(".text-anime-style-2").forEach((secTitle) => {
        const childNodes = Array.from(secTitle.childNodes);
        let finalHTML = "";

        childNodes.forEach((node) => {
            if (node.nodeType === Node.TEXT_NODE) {
            // Split letters from plain text
            const letters = node.textContent.split("");
            letters.forEach((ch) => {
                finalHTML += `<span>${ch === " " ? "&nbsp;" : ch}</span>`;
            });
            } else {
            // Preserve existing span or tag
            finalHTML += node.outerHTML;
            }
        });

        secTitle.innerHTML = finalHTML;

        // Animate only the added spans (not the text-theme span)
            gsap.from(secTitle.querySelectorAll(":scope > span"), {
                y: 20,
                opacity: 0,
                stagger: 0.03,
                duration: 0.7,
                ease: "power4.out",
                scrollTrigger: {
                trigger: secTitle,
                start: "top 80%",
                toggleActions: "play none none none",
                },
            });
        });

        // ===== 3. Animate Image (img-anime-style-1)
        document.querySelectorAll(".text-anime-style-3").forEach((img) => {
            gsap.from(img, {
                y: 80,
                scale: 0.8,
                opacity: 0,
                duration: 0.8,
                ease: "power4.out",
                scrollTrigger: {
                trigger: img,
                start: "top 80%",
                toggleActions: "play none none none",
                },
            });
        });

        // ===== 4. Animate Image (img-anime-style-1)
        document.querySelectorAll(".img-anime-style-1").forEach((img) => {
            gsap.from(img, {
                y: 80,
                scale: 0.8,
                opacity: 0,
                duration: 0.8,
                ease: "power4.out",
                scrollTrigger: {
                trigger: img,
                start: "top 80%",
                toggleActions: "play none none none",
                },
            });
        });

    }


    /*---------------------------------------------
                GSAP Animation Effect  End Here 
    ------------------------------------------------*/

    /*----------- 19. Tilt Active ----------*/
    $(".tilt-active").tilt({
        maxTilt: 7,
        perspective: 1000,
    });

 
});
 
 
jQuery(window).on('load', function() {
    // Delay refresh to ensure all images and layout are stable
    setTimeout(function() {
        ScrollTrigger.refresh();
    }, 500); // Adjust timing if needed
});