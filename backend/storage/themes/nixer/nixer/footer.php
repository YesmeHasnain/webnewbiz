<?php 
$nixer_redux_demo = get_option('redux_demo'); 
$nixer_page_id = get_query_var('nixer_page_id');

if (!is_search() && !is_home() && !is_tag() && !is_category() && !is_archive() && !is_date()) {
	$footer_btn = get_post_meta($nixer_page_id, 'switch_footer_btn', true);
	$footer_show = get_post_meta($nixer_page_id, 'switch_footer_show', true);
	$footer_style = get_post_meta($nixer_page_id, '_cmb_choose_page_footer', true);
} else {
	$footer_btn = 0;
}

$footer_df = isset($nixer_redux_demo['choose-footer-default']) && $nixer_redux_demo['choose-footer-default'] != '' 
	? $nixer_redux_demo['choose-footer-default'] 
	: 'footer';

$footer_style_to_load = ($footer_btn == 1) ? $footer_style : $footer_df;

$footer_style_to_load = in_array($footer_style_to_load, ['style0', 'style1', 'style2', 'style3', 'style4', 'style5', 'style6', 'footer1', 'footer2', 'footer3', 'footer4', 'footer5', 'footer6']) 
	? $footer_style_to_load 
	: 'footer3'; 
$footer_parts = [
	'style0' => '0',
	'style1' => '1',
	'style2' => '2',
	'style3' => '3',
	'style4' => '4',
	'style5' => '5',
	'style6' => '6',
	'footer1' => '1',
	'footer2' => '2',
	'footer3' => '3',
	'footer4' => '4',
	'footer5' => '5',
	'footer6' => '6',
];


get_template_part('footer/footer', $footer_parts[$footer_style_to_load]);

?>