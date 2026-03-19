<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */

wp_nav_menu( array(
	'theme_location' => 'primary-menu',
	'container' => false,
	'depth' => 3,
	'link_before' => '<span>',
	'link_after' => '</span>',
	'menu_class' => 'sf-menu',
	'fallback_cb' => 'ziomm_fallback_menu'
) );