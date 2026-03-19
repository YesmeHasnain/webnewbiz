<?php
if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly
	
}

if (!function_exists('knor_theme_inline_style')):

	function knor_theme_inline_style()
	{

		wp_enqueue_style('knor-custom-style', get_template_directory_uri() . '/assets/css/custom-style.css');
		
		
		// $theme_main_color = knor_get_customize_option('theme_main_color');
		// $theme_secondary_color = knor_get_customize_option('theme_secondary_color');

		$preloader_bg_color = knor_get_option('preloader_bg_color');
		$preloader_color = knor_get_option('preloader_color');

		$btn_1_style_border_size = knor_get_option('btn_1_style_border_size');
		$btn_1_style_border_color = knor_get_option('btn_1_style_border_color');


		$custom_css = '';


		if (!empty($preloader_bg_color)) {


			 $custom_css .= '#preloader { background-color: ' . esc_attr($preloader_bg_color) . '!important;}';


		}


		if (!empty($preloader_color)) {


			 $custom_css .= '#preloader .spinner { border-color: ' . esc_attr($preloader_color) . '!important;}';


		}

		if (!empty($btn_1_style_border_size)) {


			 $custom_css .= '.header-login-btn.header-btn-bordered a { 

			 	border-width: ' . esc_attr($btn_1_style_border_size) . 'px!important;}';

		}

		if (!empty($btn_1_style_border_color)) {


			 $custom_css .= '.header-login-btn.header-btn-bordered a { border-color: ' . esc_attr($btn_1_style_border_color) . '!important;}';


		}
		
		
		
		// if (!empty($theme_main_color))
		// {


		// 	$custom_css .= ' .header-signup-btn a, .custom-subscribe-form-wrapper input[type="submit"], a.custom-author-btn, .footer-submit, .backto, .search-popup .search-form .submit-btn, .main-container .theme-pagination-style ul.page-numbers li span.current, .blog-post-comment .comment-respond .comment-form .btn-comments, .custom-themee-contactt .fsubmitt, a.slicknav_btn, .slicknav_nav li:hover, .comments-list .comment-reply-link:hover { background: ' . esc_attr($theme_main_color) . ';}';
			
		// 	$custom_css .= '.blog-sidebar .widget_block.widget_search .wp-block-search__button { background: ' . esc_attr($theme_main_color) . '!important;}';
		
			
		// 	$custom_css .= ' .slide-arrow-left.slick-arrow:hover, .slide-arrow-right.slick-arrow:hover, .news_tab_Block .nav-tabs .nav-link.active, .blog-sidebar .widget_search form button, .blog-sidebar .widget ul li::before, .main-container .theme-pagination-style ul.page-numbers li a.page-numbers:hover {background-color: ' . esc_attr($theme_main_color) . ';}';
				
		// 	$custom_css .= 'ul.footer-nav li a:hover, .htop_social a:hover, .footer-social a:hover, a.search-box-btn:hover, .blog-sidebar .widget ul li a:hover, .main-container .theme-pagination-style ul.page-numbers li i, .blog-details-content ul li::marker, .theme_blog_nav_Title a:hover, a.search-box-btn:hover i, #cancel-comment-reply-link, h1.text-logo a {color: ' . esc_attr($theme_main_color) . ';}';
				
				
		// 	$custom_css .= '.slide-arrow-left.slick-arrow:hover, .slide-arrow-right.slick-arrow:hover, .main-container .theme-pagination-style ul.page-numbers li span.current, .main-container .theme-pagination-style ul.page-numbers li a.page-numbers:hover, .wp-block-search .wp-block-search__button {border-color: ' . esc_attr($theme_main_color) . ';}';
			
		// 	$custom_css .= '.wp-block-search .wp-block-search__button {border-color: ' . esc_attr($theme_main_color) . '!important;}';
			

		// }
		
		
		// if (!empty($theme_secondary_color))
		// {


		// 	$custom_css .= '.header-signup-btn a:hover, .custom-subscribe-form-wrapper input[type="submit"]:hover, a.custom-author-btn:hover, .footer-submit:hover, .backto:hover, .search-popup .search-form .submit-btn:hover, .blog-post-comment .comment-respond .comment-form .btn-comments:hover, .custom-themee-contactt .fsubmitt:hover { background: ' . esc_attr($theme_secondary_color) . ';}';
			
		// 	$custom_css .= '.widget_block.widget_search .wp-block-search__button:hover, .blog-sidebar .widget_search form button:hover {background-color: ' . esc_attr($theme_secondary_color) . '!important;}';
					
		// 	$custom_css .= '.wp-block-search .wp-block-search__button:hover, .widget_block.widget_search .wp-block-search__button:hover {border-color: ' . esc_attr($theme_secondary_color) . '!important;}';
			

		// }
		
		
		

		// Get Category Color for List Widget
		
		$categories_widget_color = get_terms('category');
		
        if ($categories_widget_color) {
            foreach( $categories_widget_color as $tag) {
				$tag_link = get_category_link($tag->term_id);
				$title_bg_Color = get_term_meta($tag->term_id, 'knor', true);
				$catColor = !empty( $title_bg_Color['cat-color'] )? $title_bg_Color['cat-color'] : '#0073FF';
				$custom_css .= '
					.cat-item-'.$tag->term_id.' span.post_count {background-color : '.$catColor.' !important;} 
				';
			}
        }	
		
		
	
				


		wp_add_inline_style('knor-custom-style', $custom_css);
	}
	add_action('wp_enqueue_scripts', 'knor_theme_inline_style');

endif;

