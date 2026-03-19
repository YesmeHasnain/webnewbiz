<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

add_filter('lastudio-kit/branding/name', 'zill_lakit_branding_name');
if(!function_exists('zill_lakit_branding_name')){
    function zill_lakit_branding_name( $name ){
        $name = esc_html__('Theme Options', 'zill');
        return $name;
    }
}

add_filter('lastudio-kit/branding/logo', 'zill_lakit_branding_logo');
if(!function_exists('zill_lakit_branding_logo')){
    function zill_lakit_branding_logo( $logo ){
        $logo = '';
        return $logo;
    }
}

add_filter('lastudio-kit/logo/attr/src', 'zill_lakit_logo_attr_src');
if(!function_exists('zill_lakit_logo_attr_src')){
    function zill_lakit_logo_attr_src( $src ){
        if(!$src){
	        $src = zill_get_theme_mod('logo_default', get_theme_file_uri('/assets/images/logo.svg'));
        }
        return $src;
    }
}

add_filter('lastudio-kit/logo/attr/src2x', 'zill_lakit_logo_attr_src2x');
if(!function_exists('zill_lakit_logo_attr_src2x')){
    function zill_lakit_logo_attr_src2x( $src ){
        if(!$src){
	        $src = zill_get_theme_mod('logo_transparency', '');
        }
        return $src;
    }
}

add_filter('lastudio-kit/logo/attr/width', 'zill_lakit_logo_attr_width');
if(!function_exists('zill_lakit_logo_attr_width')){
    function zill_lakit_logo_attr_width( $value ){
        if(!$value){
            $value = 105;
        }
        return $value;
    }
}

add_filter('lastudio-kit/logo/attr/height', 'zill_lakit_logo_attr_height');
if(!function_exists('zill_lakit_logo_attr_height')){
    function zill_lakit_logo_attr_height( $value ){
        if(!$value){
            $value = 105;
        }
        return $value;
    }
}

add_action('elementor/frontend/widget/before_render', 'zill_lakit_add_class_into_sidebar_widget');
if(!function_exists('zill_lakit_add_class_into_sidebar_widget')){
    function zill_lakit_add_class_into_sidebar_widget( $widget ){
        if('sidebar' == $widget->get_name()){
            $widget->add_render_attribute('_wrapper', 'class' , 'widget-area');
        }
    }
}

add_filter('lastudio-kit/products/control/grid_style', 'zill_lakit_add_product_grid_style');
if(!function_exists('zill_lakit_add_product_grid_style')){
    function zill_lakit_add_product_grid_style( $preset ){
        return $preset;
    }
}
add_filter('lastudio-kit/products/control/list_style', 'zill_lakit_add_product_list_style');
if(!function_exists('zill_lakit_add_product_list_style')){
    function zill_lakit_add_product_list_style( $preset ){
        return $preset;
    }
}

add_filter('lastudio-kit/products/box_selector', 'zill_lakit_product_change_box_selector');
if(!function_exists('zill_lakit_product_change_box_selector')){
    function zill_lakit_product_change_box_selector(){
        return '{{WRAPPER}} ul.products .product_item--inner';
    }
}

add_filter('lastudio-kit/posts/format-icon', 'zill_lakit_change_postformat_icon', 10, 2);
if(!function_exists('zill_lakit_change_postformat_icon')){
    function zill_lakit_change_postformat_icon( $icon, $type ){
        return $icon;
    }
}

/**
 * Modify Divider - Weight control
 */
add_action('elementor/element/lakit-portfolio/section_settings/before_section_end', function( $element ){
	$element->add_control(
		'enable_portfolio_lightbox',
		[
			'label'     => esc_html__( 'Enable Lightbox', 'zill' ),
			'type'      => \Elementor\Controls_Manager::SWITCHER,
			'label_on'  => esc_html__( 'Yes', 'zill' ),
			'label_off' => esc_html__( 'No', 'zill' ),
			'default'   => '',
			'return_value' => 'enable-pf-lightbox',
			'prefix_class' => '',
		]
	);
}, 10 );

if(!function_exists('zill_elementor_register_custom_widgets')){
	function zill_elementor_register_custom_widgets(){
		require_once get_theme_file_path('/framework/third/elementor/postformat-widget.php');
		\Elementor\Plugin::instance()->widgets_manager->register( new Zill_Elementor_PostFormat_Content_Widget() );
	}
}

add_action( 'elementor/widgets/register', 'zill_elementor_register_custom_widgets' );

if(!function_exists('zill_remove_unwanted_metaboxes')){
    function zill_remove_unwanted_metaboxes( $type, $context, $post ){
        remove_meta_box('slider_revolution_metabox', $type, $context);
    }
}

add_action( 'do_meta_boxes', 'zill_remove_unwanted_metaboxes', 10, 3 );

if(!function_exists('zill_elementor_modify_widget_args')){
    function zill_elementor_modify_widget_args( $default_args, $widget ){
        $new_args = [
            'after_widget'	=> '</div>',
            'before_title'	=> '<div class="widget-title"><span>',
            'after_title'	=> '</span></div>',
        ];
        $widget_cssclass = sprintf('widget widget_%1$s %1$s', $widget->get_widget_instance()->id_base);
        if( !empty($widget->get_widget_instance()->widget_cssclass) ){
            $widget_cssclass = 'widget ' . $widget->get_widget_instance()->widget_cssclass;
        }
        $new_args['before_widget'] = sprintf('<div class="%1$s lakit-wp--widget" data-id="%2$s">', $widget_cssclass, $widget->get_id());

        return array_merge($default_args, $new_args);
    }
}

add_filter('elementor/widgets/wordpress/widget_args', 'zill_elementor_modify_widget_args', 20, 2);

add_filter('lastudio-kit/products/loop/wishlist-button/class', function( $cssClass ){
    global $product;
    if(Zill_WooCommerce_Wishlist::is_product_in_wishlist($product->get_id())){
        $cssClass .= ' added';
    }
    return $cssClass;
});

add_filter('lastudio-kit/products/loop/compare-button/class', function( $cssClass ){
    global $product;
    if(Zill_WooCommerce_Compare::is_product_in_compare($product->get_id())){
        $cssClass .= ' added';
    }
    return $cssClass;
});

add_filter('lastudio-kit/wishlist/settings', function ( $settings, $product, $product_id ){
    if(Zill_WooCommerce_Wishlist::is_product_in_wishlist($product_id)){
        $settings['link'] = [
            'url' => zill_get_wishlist_url(),
            'is_external' => '',
            'nofollow' => true,
            'custom_attributes' => 'class|added'
        ];
        $settings['text'] = esc_html_x('View Wishlist', 'front-view', 'zill');
    }
    else{
        $settings['link'] = [
            'url' => get_permalink($product_id),
            'is_external' => '',
            'nofollow' => true,
        ];
    }
    return $settings;
}, 20, 3);

add_filter('lastudio-kit/compare/settings', function ( $settings, $product, $product_id ){
    if(Zill_WooCommerce_Compare::is_product_in_compare($product_id)){
        $settings['link'] = [
            'url' => zill_get_compare_url(),
            'is_external' => '',
            'nofollow' => true,
            'custom_attributes' => 'class|added'
        ];
        $settings['text'] = esc_html_x('Compare List', 'front-view', 'zill');
    }
    else{
        $settings['link'] = [
            'url' => get_permalink($product_id),
            'is_external' => '',
            'nofollow' => true,
        ];
    }
    return $settings;
}, 20, 3);

add_filter('pre_set_transient_wc_system_status_theme_info', function ($value, $transient){
	$value['has_outdated_templates'] = '';
	if( !empty($value['overrides']) ){
		$theme_name = wp_get_theme()->get_template() . '/';
		foreach ( $value['overrides'] as &$template ){
			if(str_starts_with($template['file'], $theme_name)){
				$template['version'] = $template['core_version'];
			}
		}
	}
	return $value;
}, 1000, 2);
add_filter('woocommerce_show_admin_notice', function ($show, $notice){
	if($notice === 'template_files'){
		$show = false;
	}
	return $show;
}, 1000, 2);
