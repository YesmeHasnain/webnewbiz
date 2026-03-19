<?php $nixer_redux_demo = get_option('redux_demo'); 
$nixer_page_id = get_query_var('nixer_page_id', 0); ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php if ( ! function_exists( 'has_site_icon' ) || ! has_site_icon() ) { ?>
		<?php if(isset($nixer_redux_demo['favicon']['url'])){?>
		<link rel="shortcut icon" href="<?php echo esc_url($nixer_redux_demo['favicon']['url']); ?>">
		<?php }?>
	<?php }?>
	<?php wp_head(); ?>
</head>
<body id="body" <?php body_class('tp-magic-cursor'); ?>>
	<?php
		wp_body_open();
	?>

	<div id="loading">
		<div class="loader-mask">
			<div class="loader">
			  <div></div>
			  <div></div>
			</div>
		</div>
	</div>

	<div id="magic-cursor">
		<div id="ball"></div>
	</div>

	<div class="back-to-top-wrapper">
		<button id="back_to_top" type="button" class="back-to-top-btn">
			<svg width="12" height="7" viewBox="0 0 12 7" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M11 6L6 1L1 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
			</svg>               
		</button>
	</div>

	<div id="smooth-wrapper">
		<div id="smooth-content">