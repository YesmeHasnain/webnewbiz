<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */

/**
 * Disable FontAwesome 5
 */
add_filter( 'vpf_enqueue_plugin_font_awesome', '__return_false' );

/**
 * Add default controls
 */
if ( ! function_exists( 'ziomm_vpf_after_register_controls' ) ) {
	function ziomm_vpf_after_register_controls() {
		Visual_Portfolio_Controls::register( [
			'category' => 'items-style',
			'type' => 'checkbox',
			'alongside' => esc_html__( 'Tilt Effect', 'ziomm' ),
			'name' => 'tilt_effect',
			'default' => false,
		] );
	}
}
add_filter( 'vpf_after_register_controls', 'ziomm_vpf_after_register_controls' );

/**
* Extend slider controls
*/
if ( ! function_exists( 'ziomm_extend_layout_slider_controls' ) ) {
	function ziomm_extend_layout_slider_controls( $controls ) {
		return array_merge( $controls, array(
			array(
				'alongside' => esc_html__( 'Stretch to container', 'ziomm' ),
				'name' => 'stretch_to_container',
				'type' => 'checkbox',
				'default' => false,
			),
			array(
				'type' => 'text',
				'label' => esc_html__( 'Navigation anchor', 'ziomm' ),
				'name' => 'navigation_anchor',
				'default' => '',
			)
		) );
	}
}
add_filter( 'vpf_extend_layout_slider_controls', 'ziomm_extend_layout_slider_controls' );

/**
 * Add data attributes
 */
if ( ! function_exists( 'ziomm_vpf_extend_portfolio_data_attributes' ) ) {
	function ziomm_vpf_extend_portfolio_data_attributes( $attrs, $options ) {

		$attrs[ 'data-vp-tilt-effect' ] = $options[ 'tilt_effect' ] ? 'true' : 'false';
		$attrs[ 'data-vp-slider-stretch-to-container' ] = $options[ 'slider_stretch_to_container' ] ? 'true' : 'false';
		$attrs[ 'data-vp-slider-navigation-anchor' ] = $options[ 'slider_navigation_anchor' ] ? $options[ 'slider_navigation_anchor' ] : '';

		return $attrs;

	}
}
add_filter( 'vpf_extend_portfolio_data_attributes', 'ziomm_vpf_extend_portfolio_data_attributes', 10, 2 );

/**
 * Add new item styles
 */
if ( ! function_exists( 'ziomm_vpf_extend_items_styles' ) ) {
	function ziomm_vpf_extend_items_styles( $items_styles ) {

		$custom_style = [];

		$custom_style[ 'ziomm_post_style_1' ] = array(
			'title' => esc_html__( 'Post 1', 'ziomm' ),
			'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 21 21"><defs/><g fill="#000" fill-rule="nonzero"><path d="M18.25.25h-16a2 2 0 00-2 2v16.125a2 2 0 002 2h16a2 2 0 002-2V2.25a2 2 0 00-2-2zm-16 1.5h16a.5.5 0 01.5.5v16.125a.5.5 0 01-.5.5h-16a.5.5 0 01-.5-.5V2.25a.5.5 0 01.5-.5z"/><path d="M14.183 16v-1.902h-5.46l5.45-7.638V5.09H5.82v1.903h5.455L5.83 14.63V16z"/></g></svg>',
			'builtin_controls' => array(
				'show_title' => false,
				'show_categories' => false,
				'show_date' => false,
				'show_excerpt' => false,
				'show_icons' => false,
				'align' => false,
			),
			'controls' => array(
				array(
					'type' => 'checkbox',
					'alongside' => esc_html__( 'Show Excerpt', 'ziomm' ),
					'name' => 'post_1_show_excerpt',
					'default' => true,
				),
				array(
					'type' => 'range',
					'label' => esc_html__( 'Excerpt Length', 'ziomm' ),
					'name' => 'post_1_excerpt',
					'min' => 1,
					'max' => 200,
					'step' => 1,
					'default' => 56,
					'condition' => array(
						array(
							'control' => 'post_1_show_excerpt',
							'operator' => '==',
							'value' => true
						)
					),
				),
				array(
					'type' => 'checkbox',
					'alongside' => esc_html__( 'Show Read More', 'ziomm' ),
					'name' => 'post_1_show_read_more',
					'default' => true,
				)
			)
		);

		$custom_style[ 'ziomm_post_style_2' ] = array(
			'title' => esc_html__( 'Post 2', 'ziomm' ),
			'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 21 21"><defs/><g fill="#000" fill-rule="nonzero"><path d="M18.25.25h-16a2 2 0 00-2 2v16.125a2 2 0 002 2h16a2 2 0 002-2V2.25a2 2 0 00-2-2zm-16 1.5h16a.5.5 0 01.5.5v16.125a.5.5 0 01-.5.5h-16a.5.5 0 01-.5-.5V2.25a.5.5 0 01.5-.5z"/><path d="M14.183 16v-1.902h-5.46l5.45-7.638V5.09H5.82v1.903h5.455L5.83 14.63V16z"/></g></svg>',
			'builtin_controls' => array(
				'show_title' => false,
				'show_categories' => false,
				'show_date' => false,
				'show_excerpt' => false,
				'show_icons' => false,
				'align' => false,
			),
			'controls' => array(
				array(
					'type' => 'checkbox',
					'alongside' => esc_html__( 'Show Excerpt', 'ziomm' ),
					'name' => 'post_2_show_excerpt',
					'default' => true,
				),
				array(
					'type' => 'range',
					'label' => esc_html__( 'Excerpt Length', 'ziomm' ),
					'name' => 'post_2_excerpt',
					'min' => 1,
					'max' => 200,
					'step' => 1,
					'default' => 22,
					'condition' => array(
						array(
							'control' => 'post_2_show_excerpt',
							'operator' => '==',
							'value' => true
						)
					),
				),
				array(
					'type' => 'checkbox',
					'alongside' => esc_html__( 'Show Read More', 'ziomm' ),
					'name' => 'post_2_show_read_more',
					'default' => true,
				)
			)
		);

		$custom_style[ 'ziomm_post_style_3' ] = array(
			'title' => esc_html__( 'Post 3', 'ziomm' ),
			'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 21 21"><defs/><g fill="#000" fill-rule="nonzero"><path d="M18.25.25h-16a2 2 0 00-2 2v16.125a2 2 0 002 2h16a2 2 0 002-2V2.25a2 2 0 00-2-2zm-16 1.5h16a.5.5 0 01.5.5v16.125a.5.5 0 01-.5.5h-16a.5.5 0 01-.5-.5V2.25a.5.5 0 01.5-.5z"/><path d="M14.183 16v-1.902h-5.46l5.45-7.638V5.09H5.82v1.903h5.455L5.83 14.63V16z"/></g></svg>',
			'builtin_controls' => array(
				'show_title' => false,
				'show_categories' => false,
				'show_date' => false,
				'show_excerpt' => false,
				'show_icons' => false,
				'align' => false,
			)
		);

		$custom_style[ 'ziomm_post_style_4' ] = array(
			'title' => esc_html__( 'Post 4', 'ziomm' ),
			'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 21 21"><defs/><g fill="#000" fill-rule="nonzero"><path d="M18.25.25h-16a2 2 0 00-2 2v16.125a2 2 0 002 2h16a2 2 0 002-2V2.25a2 2 0 00-2-2zm-16 1.5h16a.5.5 0 01.5.5v16.125a.5.5 0 01-.5.5h-16a.5.5 0 01-.5-.5V2.25a.5.5 0 01.5-.5z"/><path d="M14.183 16v-1.902h-5.46l5.45-7.638V5.09H5.82v1.903h5.455L5.83 14.63V16z"/></g></svg>',
			'builtin_controls' => array(
				'show_title' => false,
				'show_categories' => false,
				'show_date' => false,
				'show_excerpt' => false,
				'show_icons' => false,
				'align' => false,
			),
			'controls' => array(
				array(
					'type' => 'checkbox',
					'alongside' => esc_html__( 'Show Excerpt', 'ziomm' ),
					'name' => 'post_4_show_excerpt',
					'default' => true,
				),
				array(
					'type' => 'range',
					'label' => esc_html__( 'Excerpt Length', 'ziomm' ),
					'name' => 'post_4_excerpt',
					'min' => 1,
					'max' => 200,
					'step' => 1,
					'default' => 22,
					'condition' => array(
						array(
							'control' => 'post_4_show_excerpt',
							'operator' => '==',
							'value' => true
						)
					),
				),
				array(
					'type' => 'checkbox',
					'alongside' => esc_html__( 'Show Read More', 'ziomm' ),
					'name' => 'post_4_show_read_more',
					'default' => true,
				)
			)
		);

		$custom_style[ 'ziomm_work_style_1' ] = array(
			'title' => esc_html__( 'Work 1', 'ziomm' ),
			'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 21 21"><defs/><g fill="#000" fill-rule="nonzero"><path d="M18.25.25h-16a2 2 0 00-2 2v16.125a2 2 0 002 2h16a2 2 0 002-2V2.25a2 2 0 00-2-2zm-16 1.5h16a.5.5 0 01.5.5v16.125a.5.5 0 01-.5.5h-16a.5.5 0 01-.5-.5V2.25a.5.5 0 01.5-.5z"/><path d="M14.183 16v-1.902h-5.46l5.45-7.638V5.09H5.82v1.903h5.455L5.83 14.63V16z"/></g></svg>',
			'builtin_controls' => array(
				'show_title' => true,
				'show_categories' => true,
				'show_date' => false,
				'show_excerpt' => false,
				'show_icons' => false,
				'align' => false,
			)
		);

		$custom_style[ 'ziomm_work_style_2' ] = array(
			'title' => esc_html__( 'Work 2', 'ziomm' ),
			'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 21 21"><defs/><g fill="#000" fill-rule="nonzero"><path d="M18.25.25h-16a2 2 0 00-2 2v16.125a2 2 0 002 2h16a2 2 0 002-2V2.25a2 2 0 00-2-2zm-16 1.5h16a.5.5 0 01.5.5v16.125a.5.5 0 01-.5.5h-16a.5.5 0 01-.5-.5V2.25a.5.5 0 01.5-.5z"/><path d="M14.183 16v-1.902h-5.46l5.45-7.638V5.09H5.82v1.903h5.455L5.83 14.63V16z"/></g></svg>',
			'builtin_controls' => array(
				'show_title' => true,
				'show_categories' => true,
				'show_date' => true,
				'show_excerpt' => false,
				'show_icons' => false,
				'align' => false,
			),
			'controls' => array(
				array(
					'type' => 'radio',
					'label' => esc_html__( 'Display Read More Button', 'ziomm' ),
					'name' => 'show_read_more',
					'default' => 'false',
					'options' => array(
						'false' => esc_html__( 'Hide', 'ziomm' ),
						'true' => esc_html__( 'Always Display', 'ziomm' ),
					),
				),
				array(
					'type' => 'text',
					'name' => 'read_more_label',
					'placeholder' => 'View Project',
					'default' => 'View Project',
					'hint' => esc_attr__( 'View Project Button Label', 'ziomm' ),
					'hint_place' => 'left',
					'wpml' => true,
					'condition' => array(
						array(
							'control' => 'show_read_more',
							'operator' => '!=',
							'value' => 'false',
						),
					),
				),
			)
		);

		$custom_style[ 'ziomm_work_instagram' ] = array(
			'title' => esc_html__( 'Instagram', 'ziomm' ),
			'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 21 21"><defs/><g fill="#000" fill-rule="nonzero"><path d="M18.25.25h-16a2 2 0 00-2 2v16.125a2 2 0 002 2h16a2 2 0 002-2V2.25a2 2 0 00-2-2zm-16 1.5h16a.5.5 0 01.5.5v16.125a.5.5 0 01-.5.5h-16a.5.5 0 01-.5-.5V2.25a.5.5 0 01.5-.5z"/><path d="M14.183 16v-1.902h-5.46l5.45-7.638V5.09H5.82v1.903h5.455L5.83 14.63V16z"/></g></svg>',
			'builtin_controls' => array(
				'images_rounded_corners' => true,
				'show_title' => false,
				'show_categories' => false,
				'show_date' => false,
				'show_excerpt' => false,
				'show_icons' => false,
				'align' => false,
			)
		);

		$custom_style[ 'ziomm_product_style_1' ] = array(
			'title' => esc_html__( 'Product 1', 'ziomm' ),
			'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 21 21"><defs/><g fill="#000" fill-rule="nonzero"><path d="M18.25.25h-16a2 2 0 00-2 2v16.125a2 2 0 002 2h16a2 2 0 002-2V2.25a2 2 0 00-2-2zm-16 1.5h16a.5.5 0 01.5.5v16.125a.5.5 0 01-.5.5h-16a.5.5 0 01-.5-.5V2.25a.5.5 0 01.5-.5z"/><path d="M14.183 16v-1.902h-5.46l5.45-7.638V5.09H5.82v1.903h5.455L5.83 14.63V16z"/></g></svg>',
			'builtin_controls' => array(
				'show_title' => false,
				'show_categories' => false,
				'show_date' => false,
				'show_excerpt' => false,
				'show_icons' => false,
				'align' => false,
			)
		);

		return array_merge( $items_styles, $custom_style );

	}
}
add_filter( 'vpf_extend_items_styles', 'ziomm_vpf_extend_items_styles' );

/**
* Add new tiles
*/
if ( ! function_exists( 'ziomm_vpf_extend_tiles' ) ) {
	function ziomm_vpf_extend_tiles( $tiles ) {

		$tiles[] = array(
			'value' => '3|1,0.75|1,0.75|1,0.75|'
		);

		$tiles[] = array(
			'value' => '3|1,0.85|1,0.85|1,0.85|'
		);

		$tiles[] = array(
			'value' => '3|1,0.77|1,1.54|1,0.77|1,0.77|'
		);

		$tiles[] = array(
			'value' => '4|2,1|1,1|1,1|2,0.5|1,1|1,1|2,0.5|2,1|'
		);

		$tiles[] = array(
			'value' => '3|1,0.7|1,1.02|1,0.7|1,1.02|1,1.02|1,0.7|'
		);

		$tiles[] = array(
			'value' => '2|2,0.45|1,0.9|1,0.9|'
		);

		$tiles[] = array(
			'value' => '4|2,1|2,0.5|1,1|1,1|1,1|2,0.5|1,1|1,2|1,1|2,1|1,1|'
		);

		$tiles[] = array(
			'value' => '2|1,1.08|1,1.08|'
		);

		$tiles[] = array(
			'value' => '3|1,1.08|1,1.08|1,1.08|'
		);

		$tiles[] = array(
			'value' => '4|1,1.6|1,1.03|1,1.32|1,0.73|1,1.32|1,1.6|1,1.03|1,0.73|1,1.03|1,1.32|1,1.6|1,0.73|1,1.6|1,1.32|1,1.03|1,0.73|'
		);

		$tiles[] = array(
			'value' => '4|2,1|1,1|1,1|2,0.5|1,1|1,1|2,1|2,0.5|'
		);

		return $tiles;

	}
}
add_filter( 'vpf_extend_tiles', 'ziomm_vpf_extend_tiles' );

/**
* Add new sort
*/
function ziomm_vpf_registered_controls( $controls ) {
	$controls[ 'posts_order_by' ][ 'options' ][ 'post__in' ] = esc_attr__( 'Post in', 'ziomm' );
	return $controls;
}
add_filter( 'vpf_registered_controls', 'ziomm_vpf_registered_controls', 10, 1 );