<?php

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

function xcency_inline_style() {

	wp_enqueue_style('xcency-inline-style', get_theme_file_uri('assets/css/inline-style.css'), array(), XCENCY_VERSION, 'all');

	$xcency_inline_css = '
        .elementor-inner {margin-left: -10px;margin-right: -10px;}.elementor-inner .elementor-section-wrap > section:first-of-type .elementor-editor-element-settings {display: block !important;}.elementor-inner .elementor-section-wrap > section:first-of-type .elementor-editor-element-settings li {display: inline-block !important;}.elementor-editor-active .elementor-editor-element-setting{height: 25px;line-height: 25px;text-align: center;}.elementor-section.elementor-section-boxed>.elementor-container {max-width: 1320px !important;}.elementor-section-stretched.elementor-section-boxed .elementor-row{padding-left: 5px;padding-right: 5px;}.elementor-section-stretched.elementor-section-boxed .elementor-container.elementor-column-gap-extended {margin-left: auto;margin-right: auto;}.elementor-section-wrap > section:first-of-type .elementor-editor-element-settings {display: inline-flex !important;}';

	$primary_color = xcency_option('theme_primary_color', '');
	if(!empty($primary_color)){
		$xcency_inline_css .= '
			:root {
			    --xcency-primary-color-one: '.$primary_color.';
			    --xcency-primary-color-two: '.$primary_color.';
			    --xcency-primary-color-three: '.$primary_color.';
			}
		';
	}

	$custom_css = xcency_option('xcency_custom_css');
	$xcency_inline_css .= ''.$custom_css.'';

	wp_add_inline_style('xcency-inline-style', $xcency_inline_css);
}

add_action('wp_enqueue_scripts', 'xcency_inline_style');