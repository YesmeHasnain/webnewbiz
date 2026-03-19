<?php
if (!defined('ABSPATH')){
	exit; // Exit if accessed directly
}

if (!function_exists('glint_theme_inline_style')):

	function glint_theme_inline_style()
	{

		wp_enqueue_style('glint-custom-style', get_template_directory_uri() . '/assets/css/custom-style.css');

		$glint_func       = glint_function('Functions');
		$theme_main_color      = cs_get_option('theme_main_color');
		$theme_secondary_color = cs_get_option('theme_secondary_color');
		$theme_preloader       = cs_get_option('preloader_bg_color');
		$theme_header_bg       = cs_get_option('header_background');
		$theme_logo_height     = cs_get_option('logo_height');
		
		$custom_css = '';

		if (!empty($theme_preloader))
		{
			$custom_css .= '
		    	.preloader {background: ' . esc_attr($theme_preloader) . ';}
		    ';
		}		
		
		if (!empty($theme_header_bg))
		{
			$custom_css .= '
				.header-area {background-color: ' . esc_attr($theme_header_bg) . ';}
			';
		}
		
		if (!empty($theme_logo_height))
		{
			$custom_css .= '
				img.custom-logo {height: ' . esc_attr($theme_logo_height) . 'px;}
			';
		}

		if (!empty($theme_main_color))
		{
			$custom_css .= '
			a.contact-btn, a.cbtn.cbnt1, a.subscribe-btn, a.up-btn, .banner-icon, .stellarnav > ul > li:after, .blog-area .owl-nav > div:hover, .blog-area .owl-nav > div:focus, .sinlge-social-hover:after, .single-blog-section:hover span.single-blog-section-img-tag, .border-effect:after, .glint-pagination a.page-numbers:hover, .glint-pagination span.current:hover,.glint-pagination span.current, .side-social li a:hover, .side-close:hover, .da-thumbs li a div, .cta-form input.subscribe-btn, .comment-submit, .tagcloud a:hover {background-color: ' . esc_attr($theme_main_color) . ';}


			.header-search button.submit-btn i, a.cbtn.cbnt1 i, a.cbtn.cbnt1:hover, .primery-heading h2 span, .primery-heading small, .primery-info-content p, a.readmore-btn, .copyright span, a.up-btn:hover, .blog-area .owl-nav > div, .header-banner-area .owl-nav > div:hover, .banner-social li a:hover, .single-blog-section-author li a i, a:hover, .blog-box span, .single-blog-section-author li:last-child a i, .glint-pagination span.current, .glint-pagination a.page-numbers, .footer-menu li a:hover, .navs-tag li a:hover {color: ' . esc_attr($theme_main_color) . ';}

			a.cbtn.cbnt1:hover, a.up-btn:hover, .wlc-title h6, .primery-info-content, .blog-area .owl-nav > div:hover, .blog-area .owl-nav > div:focus, .blog-area .owl-nav > div, a.blog-author img, .glint-pagination span.current, .glint-pagination a.page-numbers, .side-social li a:hover, .side-close:hover {border-color: ' . esc_attr($theme_main_color) . ';}

			';
		}

		if (!empty($theme_secondary_color))
		{
			$custom_css .= '
				.header-banner-area {background-color: ' . esc_attr($theme_secondary_color) . ';}
			';
		}

		$body_font = cs_get_option('body_typography');
		$heading_font = cs_get_option('heading_typography');

		if (!empty($body_font))
		{
			$custom_css .= "
				body{
					font-family: {$body_font['font-family']};
				}
			";
		}

		if (!empty($heading_font))
		{
			$custom_css .= "
				h1, h2, h3, h4, h5, h6{
					font-family: {$heading_font['font-family']};
				}
			";
		}

		wp_add_inline_style('glint-custom-style', $custom_css);
	}
	add_action('wp_enqueue_scripts', 'glint_theme_inline_style');

endif;