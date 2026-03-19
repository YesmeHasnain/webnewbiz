<?php
if(is_page() || is_singular('post') || xcency_custom_post_types() && get_post_meta($post->ID, 'xcency_common_meta', true)) {
	$common_meta = get_post_meta($post->ID, 'xcency_common_meta', true);
}else{
	$common_meta = array();
}

if (is_array($common_meta) && array_key_exists('main_menu_meta', $common_meta)) {
	$selected_menu = $common_meta['main_menu_meta'];
} else  {
	$selected_menu = '';
}

?>

<nav id="site-navigation" class="main-navigation xcency-list-style">
	<?php
	wp_nav_menu( array(
		'menu' => $selected_menu,
		'theme_location' => 'main-menu',
		'menu_id'        => 'main-menu',
		'container_class'        => 'main-menu-container',
	) );
	?>
</nav><!-- #site-navigation -->