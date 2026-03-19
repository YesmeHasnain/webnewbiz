<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */

$acf_footer = ziomm_get_theme_mod( 'page_custom_footer', true );

if ( ziomm_get_theme_mod( 'footer_show', $acf_footer ) == 'show' ) {
	get_template_part( 'template-parts/footer/footer', 'template' );
}