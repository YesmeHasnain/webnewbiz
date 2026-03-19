<?php

//Register widget area
function xcency_widgets_init() {
	register_sidebar(array(
		'name'          => esc_html__('Sidebar', 'xcency'),
		'id'            => 'sidebar',
		'description'   => esc_html__('Add widgets here.', 'xcency'),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	));

	register_sidebar(array(
		'name'          => esc_html__('Service Sidebar', 'xcency'),
		'id'            => 'service-sidebar',
		'description'   => esc_html__('Add service widgets here.', 'xcency'),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	));

	register_sidebar(array(
		'name'          => esc_html__('Team Sidebar', 'xcency'),
		'id'            => 'team-sidebar',
		'description'   => esc_html__('Add Team widgets here.', 'xcency'),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	));

	register_sidebar(array(
		'name'          => esc_html__('Portfolio Sidebar', 'xcency'),
		'id'            => 'portfolio-sidebar',
		'description'   => esc_html__('Add Portfolio widgets here.', 'xcency'),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	));

	$footer_widget_column = xcency_option('footer_widget_column', 'col-lg-3');
	register_sidebar(array(
		'name'          => esc_html__('Footer Widget', 'xcency'),
		'id'            => 'footer-widget',
		'description'   => esc_html__('Add footer widgets here.', 'xcency'),
		'before_widget' => '<div id="%1$s" class="widget '.esc_attr($footer_widget_column).' col-md-6 %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	));
}

add_action('widgets_init', 'xcency_widgets_init');