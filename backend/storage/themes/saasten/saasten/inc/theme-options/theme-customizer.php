<?php

/*
 * Theme Customize Options
 * @package saasten
 * @since 1.0.0
 * */

if ( !defined('ABSPATH') ){
	exit(); // exit if access directly
}

if (class_exists('CSF') ){
	$prefix = 'saasten';

	CSF::createCustomizeOptions($prefix.'_customize_options');


	/*-------------------------------------
     ** Color Settings
     -------------------------------------*/
    CSF::createSection($prefix . '_customize_options', array(
		'id' => 'theme_settings', // Set a unique slug-like ID
        'title' => esc_html__('Saasten Color Settings', 'saasten') ,
        'priority' => 10,
        'fields' => array(

            array(
                'type' => 'subheading',
                'content' => '<h3>' . esc_html__('Choose Theme Color', 'saasten') . '</h3>',
            ) ,

            array(
                'id' => 'theme_main_color',
                'type' => 'color',
                'title' => esc_html__('Theme Main Color', 'saasten') ,
                'default' => '#bca3eaE',
            ) ,
			
			array(
                'id' => 'theme_secondary_color',
                'type' => 'color',
                'title' => esc_html__('Theme Seconday or Hover Color', 'saasten') ,
                'default' => '#2660FF',
            ) ,

            array(
                'id' => 'theme_preloader_bg',
                'type' => 'color',
                'title' => esc_html__('Set Preloader Background Color', 'saasten') ,
                'default' => '#2660FF',
				'output'      => '#preloader',
				'output_mode' => 'background-color'
				
            ) ,
			
			array(
                'id' => 'theme_body_bg',
                'type' => 'color',
                'title' => esc_html__('Body Background Color', 'saasten') ,
                'default' => '#fff',
				'output'      => 'body',
				'output_mode' => 'background-color'
				
            ) ,

            array(
                'id' => 'theme_body_text',
                'type' => 'color',
                'title' => esc_html__('Body Text Color', 'saasten') ,
                'default' => '#4E4E4E',
				'output'      => 'body',
				'output_mode' => 'color'
            ) ,
			
		

			
            array(
                'type' => 'subheading',
                'content' => '<h3>' . esc_html__('Footer', 'saasten') . '</h3>'
            ) ,

			

        )

    ));






}//endif