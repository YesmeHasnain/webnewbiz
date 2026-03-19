<?php


function bazien_theme_register_required_plugins() {

  $plugins = array(
    'novaworks' => array(
      'name'               => esc_html__('Novaworks','bazien'),
      'slug'               => 'novaworks',
      'source'             => 'http://assets.novaworks.net/plugins/bazien/novaworks.zip',
      'required'           => true,
      'description'        => esc_html__('Extends the functionality of Bazien with theme specific shortcodes and page builder elements.','bazien'),
      'demo_required'      => true,
      'version'            => '1.0.5'
    ),
    'elementor' => array(
      'name'               => esc_html__('Elementor Page Builder','bazien'),
      'slug'               => 'elementor',
      'required'           => true,
      'description'        => esc_html__('The most advanced frontend drag & drop page builder. Create high-end, pixel perfect websites at record speeds. Any theme, any page, any design.','bazien'),
      'demo_required'      => true
    ),
    'woocommerce' => array(
      'name'               => esc_html__('WooCommerce','bazien'),
      'slug'               => 'woocommerce',
      'required'           => true,
      'description'        => esc_html__('The eCommerce engine','bazien'),
      'demo_required'      => true
    ),
    'kirki' => array(
      'name'               => esc_html__('Kirki Customizer Framework','robeto'),
      'slug'               => 'kirki',
      'source'             => 'http://assets.novaworks.net/plugins/kirki.zip',
      'required'           => true,
      'description'        => esc_html__('Theme Options','robeto'),
      'demo_required'      => true,
      'version'            => '5.2.0'
    ),
    'yith-woocommerce-wishlist' => array(
      'name'               => esc_html__('YITH WooCommerce Wishlist','bazien'),
      'slug'               => 'yith-woocommerce-wishlist',
      'required'           => false,
      'description'        => esc_html__('YITH WooCommerce Wishlist gives your users the possibility to create, fill, manage and share their wishlists allowing you to analyze their interests and needs to improve your marketing strategies.','bazien'),
      'demo_required'      => true
    ),
    'bazien-demo-plugin' => array(
      'name'               => esc_html__('Bazien Package Demo Data','bazien'),
      'slug'               => 'bazien-demo-data',
      'source'             => 'http://assets.novaworks.net/plugins/bazien/bazien-demo-data.zip',
      'required'           => false,
      'description'        => esc_html__('This plugin use only for Novaworks Theme.','nyture'),
      'demo_required'      => true,
      'version'            => '1.0.1'
    ),
    'envato-market' => array(
      'name'               => esc_html__('Envato Market','bazien'),
      'slug'               => 'envato-market',
      'source'             => 'https://envato.github.io/wp-envato-market/dist/envato-market.zip',
      'required'           => false,
      'description'        => esc_html__('Automatically update your Envato theme','bazien'),
      'demo_required'      => true
    ),
    'revslider' => array(
      'name'               => esc_html__('Slider Revolution','bazien'),
      'slug'               => 'revslider',
      'source'				     => 'http://assets.novaworks.net/plugins/revslider.zip',
      'required'           => false,
      'version'            => '6.7.37',
      'demo_required'      => true
    ),
    'contact-form-7' => array(
      'name'               => esc_html__('Contact Form 7','bazien'),
      'slug'               => 'contact-form-7',
      'required'           => false,
      'description'        => esc_html__('Just another contact form plugin. Simple but flexible.','bazien'),
      'demo_required'      => true
    ),
  );

	$config = array(
	  'id'                => 'bazien',
		'default_path'      => '',
		'parent_slug'       => 'themes.php',
		'menu'              => 'tgmpa-install-plugins',
		'has_notices'       => true,
		'is_automatic'      => true,
	);

	tgmpa( $plugins, $config );
}

add_action( 'tgmpa_register', 'bazien_theme_register_required_plugins' );
