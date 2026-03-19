<?php

/**
 * Include required assets (css, js etc.)
 */

class SiberiaEnqueueAssets{

	public function __construct() {
		$theme_info = wp_get_theme();
		$this->assets_dir = SIBERIA_THEME_DIRECTORY . 'assets/';
		$this->theme_version = $theme_info[ 'Version' ];
		$this->init_assets();
	}

	public function init_assets(){
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_gutenberg_editor_styles' ) );
	}

	public function enqueue_gutenberg_editor_styles() {
		wp_enqueue_style( 'siberia-gutenberg', $this->assets_dir .'css/siberia-gutenberg-style.css', array(), $this->theme_version );
	}

	public function fonts_url() { 
		$fonts_url = ''; 
		$fonts = array(); 
		$subsets = 'latin-ext';
		$fonts[] = 'Noto Sans:wght@0,400;0,700;1,400;1,700';
		if ( $fonts ) { 
			$fonts_url = add_query_arg( array( 
				'family' => urlencode( implode( '|', $fonts ) ),
				'subset' => urlencode( $subsets ), 
			), 'https://fonts.googleapis.com/css' ); 
		} 
		return $fonts_url; 
	}

	public function enqueue_styles() {
		wp_enqueue_style( 'bootstrap-grid', $this->assets_dir .'css/vendor/bootstrap.min.css', array(), $this->theme_version );
		wp_enqueue_style( 'swiper', $this->assets_dir .'css/vendor/swiper.min.css', array(), $this->theme_version );
		wp_enqueue_style( 'magnific-popup', $this->assets_dir .'css/vendor/magnific-popup.css', array(), $this->theme_version );
		wp_enqueue_style( 'socicon', $this->assets_dir .'css/vendor/socicon.css', array(), $this->theme_version );
		wp_enqueue_style( 'google-font', $this->fonts_url(), false, $this->theme_version );
		wp_enqueue_style( 'siberia-main-style', $this->assets_dir .'css/main.css', array(), $this->theme_version );
	}

	public function enqueue_scripts() {

		if( is_singular() && comments_open() ) {
			wp_enqueue_script( 'comment-reply' );
		}

		wp_enqueue_script( 'imagesloaded' );

		if ( get_theme_mod( 'google_map_api_key' ) ) {
			wp_register_script( 'gmap-api-key', 'https://maps.googleapis.com/maps/api/js?key=' . get_theme_mod( 'google_map_api_key' ) , [], $this->theme_version, true );
		}

		wp_enqueue_script( 'modernizr', $this->assets_dir .'js/vendor/modernizr.js', [ 'jquery' ], $this->theme_version, true );
		wp_enqueue_script( 'isotope', $this->assets_dir .'js/vendor/isotope.pkgd.min.js', [ 'jquery' ], $this->theme_version, true );
		wp_enqueue_script( 'swiper-bundle', $this->assets_dir . 'js/vendor/swiper-bundle.min.js', [ 'jquery' ], $this->theme_version, true );
		wp_enqueue_script( 'jarallax', $this->assets_dir .'js/vendor/jarallax.min.js', [ 'jquery' ], $this->theme_version, true );
		wp_enqueue_script( 'jarallax-video', $this->assets_dir .'js/vendor/jarallax-video.min.js', [ 'jquery' ], $this->theme_version, true );
		wp_enqueue_script( 'justified-gallery', $this->assets_dir .'js/vendor/jquery.justifiedGallery.min.js', [ 'jquery' ], $this->theme_version, true );
		wp_enqueue_script( 'fslightbox', $this->assets_dir .'js/vendor/fslightbox.js', [ 'jquery' ], $this->theme_version, true );
		wp_enqueue_script( 'gsap', $this->assets_dir .'js/vendor/gsap.min.js', [ 'jquery' ], $this->theme_version, true );
		wp_enqueue_script( 'siberia-main-script', $this->assets_dir .'js/app.min.js', [ 'jquery' ], $this->theme_version, true );		
	}
	
}

new SiberiaEnqueueAssets;