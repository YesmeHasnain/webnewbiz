<?php
/*
dynamic css file. please don't edit it. it's update automatically when settins changed
*/
add_action('wp_head', 'startli_custom_colors', 160);
function startli_custom_colors() { 
global $startli_option;	
/***styling options
------------------*/
	if(!empty($startli_option['body_bg_color']))
	{
	 $body_bg          = $startli_option['body_bg_color'];
	}

	$site_color       = !empty($startli_option['primary_color']) ? $startli_option['primary_color'] : '';
	$secondary_color  = !empty($startli_option['secondary_color']) ? $startli_option['secondary_color'] : '';	
	$link_color       = !empty($startli_option['link_text_color']) ? $startli_option['link_text_color'] : '';
	$link_hover_color = !empty($startli_option['link_hover_text_color']) ? $startli_option['link_hover_text_color'] : '';
	
	//typography extract for body
		
	$body_typography_font      = !empty($startli_option['opt-typography-body']['font-family']) ? $startli_option['opt-typography-body']['font-family'] : '';
	$body_typography_font_size = !empty($startli_option['opt-typography-body']['font-size']) ? $startli_option['opt-typography-body']['font-size'] : '' ;

	//typography extract for menu
	$menu_typography_color       = !empty($startli_option['opt-typography-menu']['color']) ? $startli_option['opt-typography-menu']['color'] : '' ;	
	$menu_typography_weight      = !empty($startli_option['opt-typography-menu']['font-weight']) ? $startli_option['opt-typography-menu']['font-weight']: '';	
	$menu_typography_font_family = !empty($startli_option['opt-typography-menu']['font-family']) ? $startli_option['opt-typography-menu']['font-family'] : '';
	$menu_typography_font_fsize  = !empty($startli_option['opt-typography-menu']['font-size']) ? $startli_option['opt-typography-menu']['font-size'] : '';

	//typography extract for heading
	
	$h1_typography_color= !empty($startli_option['opt-typography-h1']['color'])? $startli_option['opt-typography-h1']['color']: '';
	if(!empty($startli_option['opt-typography-h1']['font-weight'])) {
		$h1_typography_weight=$startli_option['opt-typography-h1']['font-weight'];
	}
		
	$h1_typography_font_family = !empty($startli_option['opt-typography-h1']['font-family']) ? $startli_option['opt-typography-h1']['font-family'] : '' ;
	$h1_typography_font_fsize = !empty($startli_option['opt-typography-h1']['font-size']) ? $startli_option['opt-typography-h1']['font-size'] : '';	

	if(!empty($startli_option['opt-typography-h1']['line-height'])) {
		$h1_typography_line_height=$startli_option['opt-typography-h1']['line-height'];
	}
	
	$h2_typography_color = !empty($startli_option['opt-typography-h2']['color']) ? $startli_option['opt-typography-h2']['color'] : '';	

	$h2_typography_font_fsize = !empty($startli_option['opt-typography-h2']['font-size']) ? $startli_option['opt-typography-h2']['font-size'] : '';	
	if(!empty($startli_option['opt-typography-h2']['font-weight'])){
		$h2_typography_font_weight=$startli_option['opt-typography-h2']['font-weight'];
	}	

	$h2_typography_font_family = !empty($startli_option['opt-typography-h2']['font-family']) ? $startli_option['opt-typography-h2']['font-family'] : '' ;

	$h2_typography_font_fsize = !empty($startli_option['opt-typography-h2']['font-size']) ? $startli_option['opt-typography-h2']['font-size'] : '';	

	if(!empty($startli_option['opt-typography-h2']['line-height'])){
		$h2_typography_line_height=$startli_option['opt-typography-h2']['line-height'];
	}
	
	$h3_typography_color = !empty($startli_option['opt-typography-h3']['color']) ? $startli_option['opt-typography-h3']['color'] : '';	

	if(!empty($startli_option['opt-typography-h3']['font-weight'])){
		$h3_typography_font_weightt=$startli_option['opt-typography-h3']['font-weight'];
	}	

	$h3_typography_font_family = !empty($startli_option['opt-typography-h3']['font-family']) ? $startli_option['opt-typography-h3']['font-family']: '';

	$h3_typography_font_fsize  = !empty($startli_option['opt-typography-h3']['font-size']) ? $startli_option['opt-typography-h3']['font-size'] : '';	

	if(!empty($startli_option['opt-typography-h3']['line-height'])){
		$h3_typography_line_height = $startli_option['opt-typography-h3']['line-height'];
	}

	$h4_typography_color = !empty($startli_option['opt-typography-h4']['color']) ? $startli_option['opt-typography-h4']['color'] : '';	

	if(!empty($startli_option['opt-typography-h4']['font-weight'])){
		$h4_typography_font_weight = $startli_option['opt-typography-h4']['font-weight'];
	}	

	$h4_typography_font_family = !empty($startli_option['opt-typography-h4']['font-family']) ? $startli_option['opt-typography-h4']['font-family'] : '';

	$h4_typography_font_fsize  = !empty($startli_option['opt-typography-h4']['font-size']) ? $startli_option['opt-typography-h4']['font-size'] : '';	

	if(!empty($startli_option['opt-typography-h4']['line-height'])) {
		$h4_typography_line_height = $startli_option['opt-typography-h4']['line-height'];
	}
	
	$h5_typography_color = !empty($startli_option['opt-typography-h5']['color']) ? $startli_option['opt-typography-h5']['color'] : '';	

	if(!empty($startli_option['opt-typography-h5']['font-weight'])) {
		$h5_typography_font_weight = $startli_option['opt-typography-h5']['font-weight'];
	}	

	$h5_typography_font_family = !empty($startli_option['opt-typography-h5']['font-family']) ? $startli_option['opt-typography-h5']['font-family'] : '';

	$h5_typography_font_fsize  = !empty($startli_option['opt-typography-h5']['font-size']) ? $startli_option['opt-typography-h5']['font-size'] : '';	

	if(!empty($startli_option['opt-typography-h5']['line-height'])) {
		$h5_typography_line_height = $startli_option['opt-typography-h5']['line-height'];
	}
	
	$h6_typography_color = !empty($startli_option['opt-typography-6']['color']) ? $startli_option['opt-typography-6']['color'] : '';	

	if(!empty($startli_option['opt-typography-6']['font-weight'])) {
		$h6_typography_font_weight = $startli_option['opt-typography-6']['font-weight'];
	}

	$h6_typography_font_family = !empty($startli_option['opt-typography-6']['font-family']) ? $startli_option['opt-typography-6']['font-family'] : '';

	$h6_typography_font_fsize  = !empty($startli_option['opt-typography-6']['font-size']) ? $startli_option['opt-typography-6']['font-size'] : '';

	if(!empty($startli_option['opt-typography-6']['line-height'])) {
		$h6_typography_line_height = $startli_option['opt-typography-6']['line-height'];
	}
	

$body_color  = !empty($startli_option['body_text_color']) ? $startli_option['body_text_color'] : '' ;	?>

<!-- Typography -->
<?php if(!empty($body_color)){
	global $startli_option;
?>
<style>	

	body{
		background:<?php echo sanitize_hex_color($body_bg); ?>;
		color:<?php echo sanitize_hex_color($body_color); ?> !important;
		<?php if(!empty($body_typography_font)){ ?>
			font-family: <?php echo esc_attr($body_typography_font);?> !important;   
		<?php } ?> 
	    font-size: <?php echo esc_attr($body_typography_font_size);?> !important;
	}	

	h1{
		<?php if(!empty($h1_typography_color)) { ?>
			 color: <?php echo sanitize_hex_color($h1_typography_color);?>;
		<?php
	 }?>
		<?php if(!empty($h1_typography_font_family)){ ?>
			font-family: <?php echo esc_attr($h1_typography_font_family);?>;   
		<?php } ?>
		font-size:<?php echo esc_attr($h1_typography_font_fsize);?>;
		<?php if(!empty($h1_typography_weight)){
		?>
		font-weight:<?php echo esc_attr($h1_typography_weight);?>;
		<?php }?>
		
		<?php if(!empty($h1_typography_line_height)){
		?>
			line-height:<?php echo esc_attr($h1_typography_line_height);?>;
		<?php }?>		
	}
	h2{
		color:<?php echo sanitize_hex_color($h2_typography_color);?>;
		<?php if(!empty($h2_typography_font_family)){ ?>
			font-family: <?php echo esc_attr($h2_typography_font_family);?> !important;   
		<?php } ?> 
		font-size:<?php echo esc_attr($h2_typography_font_fsize);?>;
		<?php if(!empty($h2_typography_font_weight)){
		?>
		font-weight:<?php echo esc_attr($h2_typography_font_weight);?>;
		<?php }?>
		
		<?php if(!empty($h2_typography_line_height)){
		?>
			line-height:<?php echo esc_attr($h2_typography_line_height);?>
		<?php }?>
	}
	h3{
		color:<?php echo sanitize_hex_color($h3_typography_color);?> ;
		<?php if(!empty($h3_typography_font_family)){ ?>
			font-family: <?php echo esc_attr($h3_typography_font_family);?> !important;   
		<?php } ?> 
		font-size:<?php echo esc_attr($h3_typography_font_fsize);?>;
		<?php if(!empty($h3_typography_font_weight)){
		?>
		font-weight:<?php echo esc_attr($h3_typography_font_weight);?>;
		<?php }?>
		
		<?php if(!empty($h3_typography_line_height)){
		?>
			line-height:<?php echo esc_attr($h3_typography_line_height);?>;
		<?php }?>
	}
	h4{
		color:<?php echo sanitize_hex_color($h4_typography_color);?>;
		<?php if(!empty($h4_typography_font_family)){ ?>
			font-family: <?php echo esc_attr($h4_typography_font_family);?> !important;   
		<?php } ?>
		font-size:<?php echo esc_attr($h4_typography_font_fsize);?>;
		<?php if(!empty($h4_typography_font_weight)){
		?>
		font-weight:<?php echo esc_attr($h4_typography_font_weight);?>;
		<?php }?>
		
		<?php if(!empty($h4_typography_line_height)){
		?>
			line-height:<?php echo esc_attr($h4_typography_line_height);?>;
		<?php }?>		
	}
	h5{
		color:<?php echo sanitize_hex_color($h5_typography_color);?>;
		<?php if(!empty($h5_typography_font_family)){ ?>
			font-family: <?php echo esc_attr($h5_typography_font_family);?> !important;   
		<?php } ?>
		font-size:<?php echo esc_attr($h5_typography_font_fsize);?>;
		<?php if(!empty($h5_typography_font_weight)){
		?>
		font-weight:<?php echo esc_attr($h5_typography_font_weight);?>;
		<?php }?>
		
		<?php if(!empty($h5_typography_line_height)){
		?>
			line-height:<?php echo esc_attr($h5_typography_line_height);?>;
		<?php }?>
	}
	h6{
		color:<?php echo sanitize_hex_color($h6_typography_color);?> ;
		<?php if(!empty($h6_typography_font_family)){ ?>
			font-family: <?php echo esc_attr($h6_typography_font_family);?> !important;   
		<?php } ?>
		font-size:<?php echo esc_attr($h6_typography_font_fsize);?>;
		<?php if(!empty($h6_typography_font_weight)){
		?>
		font-weight:<?php echo esc_attr($h6_typography_font_weight);?>;
		<?php }?>
		
		<?php if(!empty($h6_typography_line_height)){
		?>
			line-height:<?php echo esc_attr($h6_typography_line_height);?>;
		<?php }?>
	}
	.menu-area .navbar ul li > a,
	.sidenav .widget_nav_menu ul li a{
		<?php if(!empty($menu_typography_weight)){ ?>
			font-weight: <?php echo esc_attr($menu_typography_weight);?>;   
		<?php } ?>
		<?php if(!empty($menu_typography_font_family)){ ?>
			font-family: <?php echo esc_attr($menu_typography_font_family);?>;   
		<?php } ?>
		font-size:<?php echo esc_attr($menu_typography_font_fsize); ?>;
	}
	:root{
		--primaryColor: <?php echo sanitize_hex_color($site_color);?> !important;
		--secondarycolor: <?php echo sanitize_hex_color($secondary_color);?> !important;
	} 
	

	<?php if(!empty($startli_option['breadcrumb_top_gap']) && !empty($startli_option['breadcrumb_bottom_gap'])) : ?>
		.reactheme-breadcrumbs .breadcrumbs-inner,
		#reactheme-header.header-style-3 .reactheme-breadcrumbs .breadcrumbs-inner{
			padding-top:<?php echo esc_attr($startli_option['breadcrumb_top_gap']); ?>;			
			padding-bottom:<?php echo esc_attr($startli_option['breadcrumb_bottom_gap']); ?>;			
	}
	<?php endif; ?>

	
	<?php if(!empty($startli_option['breadcrumb_top_gap']) && !empty($startli_option['breadcrumb_bottom_gap'])) : ?>
		.reactheme-breadcrumbs .breadcrumbs-inner,
		#reactheme-header.header-style-3 .reactheme-breadcrumbs .breadcrumbs-inner{
			padding-top:<?php echo esc_attr($startli_option['breadcrumb_top_gap']); ?>;			
			padding-bottom:<?php echo esc_attr($startli_option['breadcrumb_bottom_gap']); ?>;			
	}
	<?php endif; ?>
	<?php if(!empty($startli_option['breadcrumb_position'])) : ?>
		
		.reactheme-breadcrumbs{
			margin-top:<?php echo esc_attr($startli_option['breadcrumb_position']); ?>;		
		}
	<?php endif; ?>
	

	<?php if(!empty($startli_option['container_size'])) : ?>
		@media only screen and (min-width: 1300px) {
			.container{
				max-width:<?php echo esc_attr($startli_option['container_size']); ?>;
			}
		}
	<?php endif; ?>

	<?php if(!empty($startli_option['preloader_bg_color'])) : ?>
		#startli-load{
			background: <?php echo sanitize_hex_color($startli_option['preloader_bg_color']); ?>;  
		}
	<?php endif; ?>

	<?php if(!empty($startli_option['preloader_animate_color2'])) : ?>
		#startli-load .lds-ring div{
			border-color: <?php echo sanitize_hex_color($startli_option['preloader_animate_color2']); ?> transparent transparent transparent;  
		}
	<?php endif; ?>

	<?php if(!empty($startli_option['page_title_color'])) : ?>
		.reactheme-breadcrumbs .page-title{
			color: <?php echo sanitize_hex_color($startli_option['page_title_color']); ?> !important;  
		}
	<?php endif; ?>
	
	<?php if(!empty($startli_option['body_bg_color'])) : ?>
		body.archive.tax-product_cat{
			background: <?php echo sanitize_hex_color($startli_option['body_bg_color']); ?> !important;  
		}
	<?php endif; ?>
</style>

<?php
	}
	 
	if(is_home() && !is_front_page() || is_home() && is_front_page()){
		$padding_top        = get_post_meta(get_queried_object_id(), 'content_top', true);
		$padding_bottom     = get_post_meta(get_queried_object_id(), 'content_bottom', true);		
		$footer_padd_top    = get_post_meta(get_queried_object_id(), 'footer_padd_top', true);
		$footer_padd_bottom = get_post_meta(get_queried_object_id(), 'footer_padd_bottom', true);
  		if($padding_top != '' || $padding_bottom != ''){
	  	?>
	  	  <style>
	  	  	.main-contain #content,
	  	  	body.reactheme-pages-btm-gap .main-contain #content{
	  	  		<?php if(!empty($padding_top)): ?>padding-top:<?php echo esc_attr($padding_top); endif;?>;
	  	  		<?php if(!empty($padding_bottom)): ?>padding-bottom:<?php echo esc_attr($padding_bottom); endif;?>;
	  	  	}
	  	  </style>	
	  	<?php
	  	}
   		if($footer_padd_top != '' || $footer_padd_bottom != ''){
 	  	?>
 	  	  <style>
 	  	  	.reactheme-footer .footer-top{
 	  	  		<?php if(!empty($footer_padd_top)): ?>padding-top:<?php echo esc_attr($footer_padd_top); endif;?>;
 	  	  		<?php if(!empty($footer_padd_bottom)): ?>padding-bottom:<?php echo esc_attr($footer_padd_bottom); endif;?>;
 	  	  	}
 	  	  </style>	
 	  	  <?php
 	 	} 		
  }
  	else{ 
		$padding_top        = get_post_meta(get_the_ID(), 'content_top', true);
		$padding_bottom     = get_post_meta(get_the_ID(), 'content_bottom', true);		
		$footer_padd_top    = get_post_meta(get_the_ID(), 'footer_padd_top', true);
		$footer_padd_bottom = get_post_meta(get_the_ID(), 'footer_padd_bottom', true);
  		if($padding_top != '' || $padding_bottom != ''){
	  	?>
	  	  <style>
	  	  	.main-contain #content,
	  	  	body.reactheme-pages-btm-gap .main-contain #content{
	  	  		<?php if(!empty($padding_top)): ?>padding-top:<?php echo esc_attr($padding_top); endif;?>;
	  	  		<?php if(!empty($padding_bottom)): ?>padding-bottom:<?php echo esc_attr($padding_bottom); endif;?>;
	  	  	}
	  	  </style>	
	  	<?php
	  }

		if($footer_padd_top != '' || $footer_padd_bottom != ''){
	  	?>
	  	  <style>
	  	  	.reactheme-footer .footer-top{
	  	  		<?php if(!empty($footer_padd_top)): ?>padding-top:<?php echo esc_attr($footer_padd_top); endif;?> !important;
	  	  		<?php if(!empty($footer_padd_bottom)): ?>padding-bottom:<?php echo esc_attr($footer_padd_bottom); endif;?> !important;
	  	  	}
	  	  </style>	
	  	  <?php
	 	} 
}	

if ( !class_exists('ReduxFrameworkPlugin') ) {  ?>		

	<style>@media only screen and (max-width: 1024px){
		.sidebarmenu-area.primary-menu.mobilehum {
			display: block !important;
		}
	} </style>
<?php }
}