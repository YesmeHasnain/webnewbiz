<?php

/**
 * Theme Mode
 */
Kirki::add_field( 'siberia_customize', [
    'type'        => 'radio-buttonset',
    'settings'    => 'theme_mode',
    'label'       => esc_html__( 'Select Website Mode', 'siberia' ),
    'section'     => 'colors_schemes',
    'default'     => 'light',
    'priority'    => $priority++,
    'choices'     => [
        'light'   => esc_html__( 'Light Mode', 'siberia' ),
        'dark'    => esc_html__( 'Dark Mode', 'siberia' ),
    ],
] );

/**
 * Header Color (Light Mode)
 */
$priority = 0;
Kirki::add_field( 'siberia_customize', array(
    'type' => 'color',
    'settings' => 'header_light_color',
    'section' => 'colors_schemes',
    'label' => esc_html__( 'Header Color', 'siberia' ),
    'description' => esc_html__( 'Select the Header Color', 'siberia' ),
    'priority' => $priority++,
    'choices' => array(
        'alpha' => true 
    ),
    'default' => 'hsla(0, 0%, 100%,0.8)',
    'output'    => [
        [
            'element'  => ':root, [data-theme="light"]',
            'property' => '--main-header-bg',
        ],
    ],
    'transport' => 'auto',
    'required'  => array( 
        array( 
            'setting'   => 'theme_mode',
            'operator'  => '==',
            'value'     => 'light'
        )
    ),
) );

/**
 * Accent Color (Light Mode)
 */
$priority = 0;
Kirki::add_field( 'siberia_customize', array(
    'type' => 'color',
    'settings' => 'accent_color',
    'section' => 'colors_schemes',
    'label' => esc_html__( 'Accent Color', 'siberia' ),
    'description' => esc_html__( 'Select the Accent Color', 'siberia' ),
    'priority' => $priority++,
    'choices' => array(
        'alpha' => false 
    ),
    'default' => 'hsl(225, 90%, 55%)',
    'output'    => [
        [
            'element'  => ':root, [data-theme="light"]',
            'property' => '--color-primary',
        ],
    ],
    'transport' => 'auto',
    'required'  => array( 
        array( 
            'setting'   => 'theme_mode',
            'operator'  => '==',
            'value'     => 'light'
        )
    ),
) );

/**
 * Header Color (Dark Mode)
 */
$priority = 0;
Kirki::add_field( 'siberia_customize', array(
    'type' => 'color',
    'settings' => 'header_color',
    'section' => 'colors_schemes',
    'label' => esc_html__( 'Header Color', 'siberia' ),
    'description' => esc_html__( 'Select the Header Color', 'siberia' ),
    'priority' => $priority++,
    'choices' => array(
        'alpha' => true 
    ),
    'default' => 'hsla(240, 8%, 12%,0.4)',
    'output'    => [
        [
            'element'  => ':root, [data-theme="dark"]',
            'property' => '--main-header-bg',
        ],
    ],
    'transport' => 'auto',
    'required'  => array( 
        array( 
            'setting'   => 'theme_mode',
            'operator'  => '==',
            'value'     => 'dark'
        )
    ),
) );

/**
 * Accent Color (Dark Mode)
 */
$priority = 0;
Kirki::add_field( 'siberia_customize', array(
    'type' => 'color',
    'settings' => 'accent_color_d',
    'section' => 'colors_schemes',
    'label' => esc_html__( 'Accent Color', 'siberia' ),
    'description' => esc_html__( 'Select the Accent Color', 'siberia' ),
    'priority' => $priority++,
    'choices' => array(
        'alpha' => false 
    ),
    'default' => 'hsl(213, 100%, 64%)',
    'output'    => [
        [
            'element'  => ':root, [data-theme="dark"]',
            'property' => '--color-primary',
        ],
    ],
    'transport' => 'auto',
    'required'  => array( 
        array( 
            'setting'   => 'theme_mode',
            'operator'  => '==',
            'value'     => 'dark'
        )
    ),
) );

$priority = 0;
Kirki::add_field( 'siberia_customize', array(
    'type'        => 'custom',
    'settings'    => 'separator',
    'section'     => 'colors_schemes',
    'default'     => '<hr>',
    'priority' => $priority++,
) );


/**
 * Primary Color (Light Mode)
 */
$priority = 0;
Kirki::add_field( 'siberia_customize', array(
    'type' => 'color',
    'settings' => 'primary_color1',
    'section' => 'colors_schemes',
    'label' => esc_html__( 'Primary Text Color', 'siberia' ),
    'description' => esc_html__( 'Text color', 'siberia' ),
    'priority' => $priority++,
    'choices' => array(
        'alpha' => false 
    ),
    'default' => 'hsl(231, 15%, 27%)',
    'output'    => [
        [
            'element'  => ':root, [data-theme="light"]',
            'property' => '--color-contrast-high',
        ],
    ],
    'transport' => 'auto',
    'required'  => array( 
        array( 
            'setting'   => 'theme_mode',
            'operator'  => '==',
            'value'     => 'light'
        )
    ),
) );

/**
 * Primary Color Contrast (Light Mode)
 */
$priority = 0;
Kirki::add_field( 'siberia_customize', array(
    'type' => 'color',
    'settings' => 'contrast_color2',
    'section' => 'colors_schemes',
    'label' => esc_html__( '', 'siberia' ),
    'description' => esc_html__( 'Color Contrast Medium', 'siberia' ),
    'priority' => $priority++,
    'choices' => array(
        'alpha' => true 
    ),
    'default' => 'hsl(229, 6%, 61%)',
    'output'    => [
        [
            'element'  => ':root, [data-theme="light"]',
            'property' => '--color-contrast-medium',
        ],
    ],
    'transport' => 'auto',
    'required'  => array( 
        array( 
            'setting'   => 'theme_mode',
            'operator'  => '==',
            'value'     => 'light'
        )
    ),
) );

/**
 * Low Color Contrast (Light Mode)
 */
$priority = 0;
Kirki::add_field( 'siberia_customize', array(
    'type' => 'color',
    'settings' => 'contrast_low',
    'section' => 'colors_schemes',
    'label' => esc_html__( '', 'siberia' ),
    'description' => esc_html__( 'Color Contrast Low', 'siberia' ),
    'priority' => $priority++,
    'choices' => array(
        'alpha' => true 
    ),
    'default' => 'hsl(240, 1%, 83%)',
    'output'    => [
        [
            'element'  => ':root, [data-theme="light"]',
            'property' => '--color-contrast-low',
        ],
    ],
    'transport' => 'auto',
    'required'  => array( 
        array( 
            'setting'   => 'theme_mode',
            'operator'  => '==',
            'value'     => 'light'
        )
    ),
) );

/**
 * Lower Color Contrast (Light Mode)
 */
$priority = 0;
Kirki::add_field( 'siberia_customize', array(
    'type' => 'color',
    'settings' => 'contrast_lower',
    'section' => 'colors_schemes',
    'label' => esc_html__( '', 'siberia' ),
    'description' => esc_html__( 'Color Contrast Lower', 'siberia' ),
    'priority' => $priority++,
    'choices' => array(
        'alpha' => true 
    ),
    'default' => 'hsl(220, 23%, 97%)',
    'output'    => [
        [
            'element'  => ':root, [data-theme="light"]',
            'property' => '--color-contrast-lower',
        ],
    ],
    'transport' => 'auto',
    'required'  => array( 
        array( 
            'setting'   => 'theme_mode',
            'operator'  => '==',
            'value'     => 'light'
        )
    ),
) );

/**
 * Primary Color (Dark Mode)
 */
$priority = 0;
Kirki::add_field( 'siberia_customize', array(
    'type' => 'color',
    'settings' => 'primary_color_dark_1',
    'section' => 'colors_schemes',
    'label' => esc_html__( 'Primary Text Color', 'siberia' ),
    'description' => esc_html__( 'Text color', 'siberia' ),
    'priority' => $priority++,
    'choices' => array(
        'alpha' => false 
    ),
    'default' => 'hsl(240, 2%, 87%)',
    'output'    => [
        [
            'element'  => ':root, [data-theme="dark"]',
            'property' => '--color-contrast-high',
        ],
    ],
    'transport' => 'auto',
    'required'  => array( 
        array( 
            'setting'   => 'theme_mode',
            'operator'  => '==',
            'value'     => 'dark'
        )
    ),
) );

/**
 * Medium Color Contrast (Dark Mode)
 */
$priority = 0;
Kirki::add_field( 'siberia_customize', array(
    'type' => 'color',
    'settings' => 'contrast_color_dark_2',
    'section' => 'colors_schemes',
    'label' => esc_html__( '', 'siberia' ),
    'description' => esc_html__( 'Color Contrast Medium', 'siberia' ),
    'priority' => $priority++,
    'choices' => array(
        'alpha' => true 
    ),
    'default' => 'hsl(240, 1%, 56%)',
    'output'    => [
        [
            'element'  => ':root, [data-theme="dark"]',
            'property' => '--color-contrast-medium',
        ],
    ],
    'transport' => 'auto',
    'required'  => array( 
        array( 
            'setting'   => 'theme_mode',
            'operator'  => '==',
            'value'     => 'dark'
        )
    ),
) );

/**
 * Low Color Contrast (Dark Mode)
 */
$priority = 0;
Kirki::add_field( 'siberia_customize', array(
    'type' => 'color',
    'settings' => 'contrast_low_2',
    'section' => 'colors_schemes',
    'label' => esc_html__( '', 'siberia' ),
    'description' => esc_html__( 'Color Contrast Low', 'siberia' ),
    'priority' => $priority++,
    'choices' => array(
        'alpha' => true 
    ),
    'default' => 'hsl(240, 3%, 24%)',
    'output'    => [
        [
            'element'  => ':root, [data-theme="dark"]',
            'property' => '--color-contrast-low',
        ],
    ],
    'transport' => 'auto',
    'required'  => array( 
        array( 
            'setting'   => 'theme_mode',
            'operator'  => '==',
            'value'     => 'dark'
        )
    ),
) );

/**
 * Lower Color Contrast (Dark Mode)
 */
$priority = 0;
Kirki::add_field( 'siberia_customize', array(
    'type' => 'color',
    'settings' => 'contrast_lower_2',
    'section' => 'colors_schemes',
    'label' => esc_html__( '', 'siberia' ),
    'description' => esc_html__( 'Color Contrast Lower', 'siberia' ),
    'priority' => $priority++,
    'choices' => array(
        'alpha' => true 
    ),
    'default' => 'hsl(240, 6%, 15%)',
    'output'    => [
        [
            'element'  => ':root, [data-theme="dark"]',
            'property' => '--color-contrast-lower',
        ],
    ],
    'transport' => 'auto',
    'required'  => array( 
        array( 
            'setting'   => 'theme_mode',
            'operator'  => '==',
            'value'     => 'dark'
        )
    ),
) );

$priority = 0;
Kirki::add_field( 'siberia_customize', array(
    'type'        => 'custom',
    'settings'    => 'separator2',
    'section'     => 'colors_schemes',
    'default'     => '<hr>',
    'priority' => $priority++,
) );

/**
 * Background Color Light
 */
$priority = 0;
Kirki::add_field( 'siberia_customize', array(
    'type' => 'color',
    'settings' => 'bg_color_light',
    'section' => 'colors_schemes',
    'label' => esc_html__( 'Background Color', 'siberia' ),
    'description' => esc_html__( 'Select the background color', 'siberia' ),
    'priority' => $priority++,
    'choices' => array(
        'alpha' => false 
    ),
    'default' => '#FFFFFF',
        'output'    => [
        [
            'element'  => ':root, [data-theme="light"]',
            'property' => '--color-bg',
        ],
    ],
    'transport' => 'auto',
    'required'  => array( 
        array( 
            'setting'   => 'theme_mode',
            'operator'  => '==',
            'value'     => 'light'
        )
    ),
) );

/**
 * Background Color Dark
 */
$priority = 0;
Kirki::add_field( 'siberia_customize', array(
    'type' => 'color',
    'settings' => 'bg_color',
    'section' => 'colors_schemes',
    'label' => esc_html__( 'Background Color', 'siberia' ),
    'description' => esc_html__( 'Select the background color', 'siberia' ),
    'priority' => $priority++,
    'choices' => array(
        'alpha' => false 
    ),
    'default' => 'hsl(240, 8%, 12%)',
        'output'    => [
        [
            'element'  => ':root, [data-theme="dark"]',
            'property' => '--color-bg',
        ],
    ],
    'transport' => 'auto',
    'required'  => array( 
        array( 
            'setting'   => 'theme_mode',
            'operator'  => '==',
            'value'     => 'dark'
        )
    ),
) );

$priority = 0;
Kirki::add_field( 'siberia_customize', array(
    'type'        => 'custom',
    'settings'    => 'separator3',
    'section'     => 'colors_schemes',
    'default'     => '<hr>',
    'priority' => $priority++,
) );