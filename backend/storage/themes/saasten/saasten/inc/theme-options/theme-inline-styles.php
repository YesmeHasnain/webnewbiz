<?php
if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly
	
}

if (!function_exists('saasten_theme_inline_style')):

	function saasten_theme_inline_style()
	{

		wp_enqueue_style('saasten-custom-style', get_template_directory_uri() . '/assets/css/custom-style.css');
		
		
		$theme_main_color = saasten_get_customize_option('theme_main_color');
		$theme_secondary_color = saasten_get_customize_option('theme_secondary_color');

		$custom_css = '';
		

		

		// Get Category Color for List Widget
		
		$categories_widget_color = get_terms('category');
		
        if ($categories_widget_color) {
            foreach( $categories_widget_color as $tag) {
				$tag_link = get_category_link($tag->term_id);
				$title_bg_Color = get_term_meta($tag->term_id, 'saasten', true);
				$catColor = !empty( $title_bg_Color['cat-color'] )? $title_bg_Color['cat-color'] : '#0073FF';
				$custom_css .= '
					.cat-item-'.$tag->term_id.' span.post_count {background-color : '.$catColor.' !important;} 
				';
			}
        }	
		
		
	
				


		wp_add_inline_style('saasten-custom-style', $custom_css);
	}
	add_action('wp_enqueue_scripts', 'saasten_theme_inline_style');

endif;

