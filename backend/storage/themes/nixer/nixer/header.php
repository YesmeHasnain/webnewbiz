<?php 
$nixer_redux_demo = get_option('redux_demo'); 
$queried_object = get_queried_object();
$nixer_page_id = isset($queried_object->ID) ? $queried_object->ID : 0;

set_query_var('nixer_page_id', $nixer_page_id);

if (!is_search() && !is_home() && !is_tag() && !is_category() && !is_archive() && !is_date()) {
    $header_btn = get_post_meta($nixer_page_id, 'switch_header_btn', true);
    $header_style = get_post_meta($nixer_page_id, '_cmb_choose_page_header', true);
} else {
    $header_btn = 0;
}

$header_df = isset($nixer_redux_demo['choose-header-default']) && $nixer_redux_demo['choose-header-default'] != '' 
    ? $nixer_redux_demo['choose-header-default'] 
    : 'header';

$header_style_to_load = ($header_btn == 1) ? $header_style : $header_df;

$header_style_to_load = in_array($header_style_to_load, ['style0', 'style1', 'style2', 'style3', 'style4', 'style5', 'style6', 'style7', 'style8', 'style9', 'style10', 'header0', 'header1', 'header2', 'header3', 'header4', 'header5', 'header6', 'header7', 'header8', 'header9', 'header10']) 
    ? $header_style_to_load 
    : 'header0'; 

$header_parts = [
    'style0' => '0',
    'style1' => '1',
    'style2' => '2',
    'style3' => '3',
    'style4' => '4',
    'style5' => '5',
    'style6' => '6',
    'style7' => '7',
    'style8' => '8',
    'style9' => '9',
    'style10' => '10',
    'header0' => '0',
    'header1' => '1',
    'header2' => '2',
    'header3' => '3',
    'header4' => '4',
    'header5' => '5',
    'header6' => '6',
    'header7' => '7',
    'header8' => '8',
    'header9' => '9',
    'header10' => '10',
];

get_template_part('header/header', $header_parts[$header_style_to_load]);
?>