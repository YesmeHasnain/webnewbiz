<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */

$acf_header = ziomm_get_theme_mod( 'page_custom_navigation', true );

if ( ziomm_get_theme_mod( 'navigation_show', $acf_header ) == 'show' ) {
	get_template_part( 'template-parts/header/header', ziomm_get_theme_mod( 'navigation_type', $acf_header ) );
	get_template_part( 'template-parts/header/header', 'mobile' );
}

?>

<div class="vlt-site-overlay"></div>
<!-- /.vlt-site-overlay -->