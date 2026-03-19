<?php
$bottom_footer_content = array(
	'none' => esc_html__( 'None', 'vasia' ),
	'copyright' => esc_html__( 'Copyright', 'vasia' ),
	'footer-menu' => esc_html__( 'Footer menu', 'vasia' ),
	'social' => esc_html__( 'Social list', 'vasia' ),
	'payment' => esc_html__( 'Payment logos', 'vasia' ),
);
Kirki::add_section( 'footer', array(
    'priority'    => 58,
    'title'       => esc_html__( 'Footer', 'vasia' ),
) );
Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'footer_before',
	'section'     => 'footer',
	'default'         => '<div class="customize-title-divider">' . __( 'Before footer', 'vasia' ) . '</div>',
] );
Kirki::add_field( 'option', [
	'type'     => 'textarea',
	'settings' => 'footer_before_content',
	'label'    => esc_html__( 'Custom content', 'vasia' ),
	'description'    => esc_html__( 'Use HTML or shortcode', 'vasia' ),
	'section'  => 'footer',
	'default'  => '',
] );

Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'footer_main',
	'section'     => 'footer',
	'default'         => '<div class="customize-title-divider">' . __( 'Main footer', 'vasia' ) . '</div>',
] );
Kirki::add_field( 'option', [
	'type'        => 'select',
	'settings'    => 'footer_style',
	'label'       => esc_html__( 'Footer Style', 'vasia' ),
	'section'     => 'footer',
	'default'     => '1',
	'multiple'    => 1,
	'choices'     => [
		'1' => esc_html__( 'Style 1', 'vasia' ),
		'2' => esc_html__( 'Style 2', 'vasia' ),
	],
] );
Kirki::add_field( 'option', [
	'type'        => 'radio-image',
	'settings'    => 'footer_layout',
	'label'       => esc_html__( 'Footer layout', 'vasia' ),
	'section'     => 'footer',
	'default'     => 'layout-4',
	'choices'     => [
		'layout-1' => get_template_directory_uri() . '/assets/images/customizer/footer-1.png',
		'layout-2' => get_template_directory_uri() . '/assets/images/customizer/footer-2.png',
		'layout-3' => get_template_directory_uri() . '/assets/images/customizer/footer-3.png',
		'layout-4' => get_template_directory_uri() . '/assets/images/customizer/footer-4.png',
		'layout-5' => get_template_directory_uri() . '/assets/images/customizer/footer-5.png',
		'layout-6' => get_template_directory_uri() . '/assets/images/customizer/footer-6.png',
		'layout-7' => get_template_directory_uri() . '/assets/images/customizer/footer-7.png',
	],
	'transport'   => 'postMessage',
] );
Kirki::add_field( 'option', [
	'type'        => 'radio-image',
	'settings'    => 'footer_text',
	'label'       => esc_html__( 'Text footer', 'vasia' ),
	'section'     => 'footer',
	'default'     => 'dark',
	'choices'     => [
		'dark'   => get_template_directory_uri() . '/assets/images/customizer/text-dark.svg',
		'light' => get_template_directory_uri() . '/assets/images/customizer/text-light.svg',
	],
	'transport'   => 'postMessage',
] );
Kirki::add_field( 'option', [
	'type'        => 'background',
	'settings'    => 'footer_main_background',
	'label'       => esc_html__( 'Background', 'vasia' ),
	'section'     => 'footer',
	'default'     => [
		'background-color'      => '#f4f4f4',
		'background-image'      => '',
		'background-repeat'     => 'repeat',
		'background-position'   => 'center center',
		'background-size'       => 'cover',
		'background-attachment' => 'scroll',
	],
	'transport'   => 'postMessage',
] );

Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'footer_bottom',
	'section'     => 'footer',
	'default'         => '<div class="customize-title-divider">' . __( 'Bottom footer', 'vasia' ) . '</div>',
] );
Kirki::add_field( 'option', [
	'type'        => 'switch',
	'settings'    => 'footer_bottom_active',
	'label'       => esc_html__( 'Active footer bottom', 'vasia' ),
	'section'     => 'footer',
	'default'     => '1',
	'choices'     => [
		'on'  => esc_html__( 'Yes', 'vasia' ),
		'off' => esc_html__( 'No', 'vasia' ),
	],
	'transport'   => 'postMessage',
] );
Kirki::add_field( 'option', [
	'type'        => 'select',
	'settings'    => 'footer_bottom_left',
	'label'       => esc_html__( 'Left content', 'vasia' ),
	'section'     => 'footer',
	'default'     => 'copyright',
	'multiple'    => 1,
	'choices'     => $bottom_footer_content,
	'transport'   => 'postMessage',
] );
Kirki::add_field( 'option', [
	'type'        => 'select',
	'settings'    => 'footer_bottom_center',
	'label'       => esc_html__( 'Center content', 'vasia' ),
	'section'     => 'footer',
	'default'     => 'none',
	'multiple'    => 1,
	'choices'     => $bottom_footer_content,
	'transport'   => 'postMessage',
] );
Kirki::add_field( 'option', [
	'type'        => 'select',
	'settings'    => 'footer_bottom_right',
	'label'       => esc_html__( 'Right content', 'vasia' ),
	'section'     => 'footer',
	'default'     => 'payment',
	'multiple'    => 1,
	'choices'     => $bottom_footer_content,
	'transport'   => 'postMessage',
] );
Kirki::add_field( 'option', [
	'type'        => 'radio-image',
	'settings'    => 'footer_bottom_text',
	'label'       => esc_html__( 'Text color', 'vasia' ),
	'section'     => 'footer',
	'default'     => 'light',
	'choices'     => [
		'dark'   => get_template_directory_uri() . '/assets/images/customizer/text-dark.svg',
		'light' => get_template_directory_uri() . '/assets/images/customizer/text-light.svg',
	],
	'transport'   => 'postMessage',
] );
Kirki::add_field( 'option', [
	'type'        => 'color',
	'settings'    => 'footer_bottom_background',
	'label'       => esc_html__( 'Background', 'vasia' ),
	'section'     => 'footer',
	'choices'     => [
		'alpha' => true,
	],
	'default'     => '#313030',
	'transport'   => 'postMessage',
] );
Kirki::add_field( 'option', [
	'type'     => 'textarea',
	'settings' => 'footer_bottom_copyright',
	'label'    => esc_html__( 'Copyright', 'vasia' ),
	'section'  => 'footer',
	'default'  => esc_html__( 'Copyright by Plazathemes', 'vasia' ),
	'transport'   => 'postMessage',
] );
Kirki::add_field( 'option', [
	'type'        => 'image',
	'settings'    => 'footer_bottom_payment',
	'label'       => esc_html__( 'Payment logo', 'vasia' ),
	'section'     => 'footer',
	'default'     => '',
	'transport'   => 'postMessage',
] );
function vasia_refresh_footer_partials( WP_Customize_Manager $wp_customize ) {

	if ( ! isset( $wp_customize->selective_refresh ) ) {
	      return;
	}
	$wp_customize->selective_refresh->add_partial( 'footer-main', array(
	    'selector' => '.footer-main',
	    'settings' => array('footer_main','footer_layout','footer_text'),
	    'container_inclusive' => true,
	    'render_callback' => 'vasia_main_footer',
	) );
	$wp_customize->selective_refresh->add_partial( 'footer-botom', array(
	    'selector' => '.footer-bottom',
	    'settings' => array('footer_bottom_active','footer_bottom_left','footer_bottom_center','footer_bottom_right','footer_bottom_text','footer_bottom_copyright', 'footer_bottom_payment'),
	    'container_inclusive' => true,
	    'render_callback' => 'vasia_bottom_footer',
	) );
	

}
add_action( 'customize_register', 'vasia_refresh_footer_partials' );