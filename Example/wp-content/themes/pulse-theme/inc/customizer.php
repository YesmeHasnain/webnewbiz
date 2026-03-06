<?php
function pulse_theme_customize_register( $wp_customize ) {
    $wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
    $wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
    $wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

    if ( isset( $wp_customize->selective_refresh ) ) {
        $wp_customize->selective_refresh->add_partial( 'blogname', array(
            'selector'        => '.site-title a',
            'render_callback' => 'pulse_theme_customize_partial_blogname',
        ) );
        $wp_customize->selective_refresh->add_partial( 'blogdescription', array(
            'selector'        => '.site-description',
            'render_callback' => 'pulse_theme_customize_partial_blogdescription',
        ) );
    }
}
add_action( 'customize_register', 'pulse_theme_customize_register' );

function pulse_theme_customize_partial_blogname() {
    bloginfo( 'name' );
}

function pulse_theme_customize_partial_blogdescription() {
    bloginfo( 'description' );
}

function pulse_theme_customize_preview_js() {
    wp_enqueue_script( 'pulse-theme-customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), PULSE_THEME_VERSION, true );
}
add_action( 'customize_preview_init', 'pulse_theme_customize_preview_js' );
