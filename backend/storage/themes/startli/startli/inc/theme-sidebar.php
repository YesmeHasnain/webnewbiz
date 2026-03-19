<?php
function startli_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'startli' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'This is sidebar area for blog post and single post.', 'startli' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	if(class_exists('WooCommerce')): 
		register_sidebar( array(
			'name'          => esc_html__( 'WooCommerce Sidebar', 'startli' ),
			'id'            => 'woocommerce',
			'description'   => esc_html__( 'This is sidebar area for woocommerces shop page.', 'startli' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		) );
	endif;
			
}
add_action( 'widgets_init', 'startli_widgets_init' );