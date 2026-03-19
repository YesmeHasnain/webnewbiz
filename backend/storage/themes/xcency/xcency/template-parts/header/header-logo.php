<?php
if(is_page() || is_singular('post') || xcency_custom_post_types() && get_post_meta($post->ID, 'xcency_common_meta', true)) {
	$common_meta = get_post_meta($post->ID, 'xcency_common_meta', true);
}else{
	$common_meta = array();
}

if (is_array($common_meta) && array_key_exists('header_logo_meta', $common_meta) && !empty($common_meta['header_logo_meta']['url'])) {
	$site_logo_img = $common_meta['header_logo_meta'];
} else  {
	$site_logo_img = xcency_option('header_default_logo','');
}

?>

<div class="site-branding">
	<div class="logo-wrap">
		<?php
		if(has_custom_logo()){
			the_custom_logo();
		}else{
			if(!empty($site_logo_img['url'])){ ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<img src="<?php echo esc_url($site_logo_img['url']); ?>" alt="<?php echo esc_attr( get_post_meta( $site_logo_img['id'], '_wp_attachment_image_alt', true )); ?>">
				</a>

				<?php

			}else{ ?>
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>

				<?php

			}
		}
		?>
	</div>
</div>
