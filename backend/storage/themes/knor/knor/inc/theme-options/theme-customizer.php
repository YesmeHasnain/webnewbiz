<?php

/*
 * Theme Customize Options
 * @package knor
 * @since 1.0.0
 * */

if ( !defined('ABSPATH') ){
	exit(); // exit if access directly
}

if (class_exists('CSF') ){
	$prefix = 'knor';

	CSF::createCustomizeOptions($prefix.'_customize_options');


	/*-------------------------------------
     ** Color Settings
     -------------------------------------*/
    CSF::createSection($prefix . '_customize_options', array(
		'id' => 'theme_settings', // Set a unique slug-like ID
        'title' => esc_html__('Knor Color Settings', 'knor') ,
        'priority' => 10,
        'fields' => array(

            array(
                'type' => 'subheading',
                'content' => '<h3>' . esc_html__('Choose Theme Color', 'knor') . '</h3>',
            ) ,

            array(
                'id' => 'theme_main_color',
                'type' => 'color',
                'title' => esc_html__('Theme Main Color', 'knor') ,
                'default' => '#FF184E',
            ) ,
			
			array(
                'id' => 'theme_secondary_color',
                'type' => 'color',
                'title' => esc_html__('Theme Seconday or Hover Color', 'knor') ,
                'default' => '#2660FF',
            ) ,

            array(
                'id' => 'theme_preloader_bg',
                'type' => 'color',
                'title' => esc_html__('Set Preloader Background Color', 'knor') ,
                'default' => '#2660FF',
				'output'      => '#preloader',
				'output_mode' => 'background-color'
				
            ) ,
			
			array(
                'id' => 'theme_body_bg',
                'type' => 'color',
                'title' => esc_html__('Body Background Color', 'knor') ,
                'default' => '#fff',
				'output'      => 'body',
				'output_mode' => 'background-color'
				
            ) ,

            array(
                'id' => 'theme_body_text',
                'type' => 'color',
                'title' => esc_html__('Body Text Color', 'knor') ,
                'default' => '#4E4E4E',
				'output'      => 'body',
				'output_mode' => 'color'
            ) ,
			
		

			
            array(
                'type' => 'subheading',
                'content' => '<h3>' . esc_html__('Footer', 'knor') . '</h3>'
            ) ,

			

        )

    ));






}//endif