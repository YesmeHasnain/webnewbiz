<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     19.9.9
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if( class_exists('ReduxFramework') ) {
    $barab_woo_relproduct_display = barab_opt('barab_woo_relproduct_display');
    $barab_woo_relproduct_num = barab_opt('barab_woo_relproduct_num');
    $barab_woo_relproduct_slider = barab_opt('barab_woo_relproduct_slider');
    $barab_woo_relproduct_slider_arrow = barab_opt('barab_woo_relproduct_slider_arrow');

    $subtitle = barab_opt('barab_woo_relproduct_subtitle');
    $title = barab_opt('barab_woo_relproduct_title');
}else{
    $barab_woo_relproduct_display ='';
    $barab_woo_relproduct_num = '';
    $barab_woo_relproduct_slider = '';

    $subtitle = esc_html__('Similar Products','barab');
    $title = esc_html__('Related products','barab');
}

if ( $related_products && $barab_woo_relproduct_display){

    if( class_exists('ReduxFramework') ) {
        $barab_woo_related_product_col = barab_opt('barab_woo_related_product_col');
        if( $barab_woo_related_product_col == '2' ) {
            $barab_woo_product_col_val = 'col-xl-2 col-lg-4 col-sm-6 mb-30';
        } elseif( $barab_woo_related_product_col == '3' ) {
            $barab_woo_product_col_val = 'col-xl-3 col-lg-4 col-sm-6 mb-30';
        } elseif( $barab_woo_related_product_col == '4' ) {
            $barab_woo_product_col_val = 'col-xl-4 col-lg-4 col-sm-6 mb-30';
        } elseif( $barab_woo_related_product_col == '6' ) {
            $barab_woo_product_col_val = 'col-lg-6 col-sm-6 mb-30';
        }
    } else {
        $barab_woo_product_col_val = 'col-xl-3 col-lg-4 col-sm-6 mb-30';
    }
    
    $slider_active = $barab_woo_relproduct_slider ? 'swiper-slide style2' : $barab_woo_product_col_val ;

    echo '<div class="product-details space-extra-top">';
        if($barab_woo_relproduct_slider){
            if($barab_woo_relproduct_slider_arrow){
                echo '<div class="row justify-content-between align-items-center">';
                    echo '<div class="col-md-auto">';
                        if(!empty($subtitle)){
                            echo '<span class="sub-title style-2 text-anime-style-1">'.wp_kses_post($subtitle).'</span>';
                        }
                        if(!empty($title)){
                            echo '<h2 class="sec-title text-anime-style-2">'.esc_html($title).'</h2>';
                        }
                    echo '</div>';

                    echo '<div class="col-md d-none d-sm-block">';
                        echo '<hr class="title-line">';
                    echo '</div>';

                    echo '<div class="col-md-auto d-none d-md-block">';
                        echo '<div class="sec-btn">';
                            echo '<div class="icon-box">';
                                echo '<button data-slider-prev="#productSlider1" class="slider-arrow default"><i class="far fa-arrow-left"></i></button>';
                                echo '<button data-slider-next="#productSlider1" class="slider-arrow default"><i class="far fa-arrow-right"></i></button>';
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            }else{ 
                echo '<div class="row">';
                    echo '<div class="title-area mb-25 text-center">';
                        if(!empty($subtitle)){
                            echo '<span class="sub-title style-2 text-anime-style-1">'.wp_kses_post($subtitle).'</span>';
                        }
                        if(!empty($title)){
                            echo '<h2 class="sec-title text-anime-style-2">'.esc_html($title).'</h2>';
                        }
                    echo '</div>';
                echo '</div>';
            }
        }else{
            echo '<div class="row">';
                echo '<div class="title-area mb-25 text-center">';
                    if(!empty($subtitle)){
                        echo '<span class="sub-title">'.wp_kses_post($subtitle).'</span>';
                    }
                    if(!empty($title)){
                        echo '<h2 class="sec-title">'.esc_html($title).'</h2>';
                    }
                echo '</div>';
            echo '</div>';
        }

        if($barab_woo_relproduct_slider){
        echo '<div class="swiper th-slider has-shadow" id="productSlider1" data-slider-options=\'{"breakpoints":{"0":{"slidesPerView":1},"576":{"slidesPerView":"2"},"768":{"slidesPerView":"2"},"992":{"slidesPerView":"3"},"1200":{"slidesPerView":"4"}}}\'>';
            echo '<div class="swiper-wrapper">';
        }else{
            echo '<div class="row">';
        }
            foreach ( $related_products as $related_product ){
                echo '<div class="'.esc_attr($slider_active).'">';
                    $post_object = get_post( $related_product->get_id() );

                    setup_postdata( $GLOBALS['post'] =& $post_object );

                    wc_get_template_part( 'content', 'product' );
                echo '</div>';
            }
        if($barab_woo_relproduct_slider){
            echo '</div>';
        echo '</div>';
        }else{
            echo '</div>';
        }
        echo '<div class="d-block d-md-none mt-40 text-center">';
            echo '<div class="icon-box">';
                echo '<button data-slider-prev="#productSlider1" class="slider-arrow default"><i class="far fa-arrow-left"></i></button>';
                echo '<button data-slider-next="#productSlider1" class="slider-arrow default"><i class="far fa-arrow-right"></i></button>';
            echo '</div>';
        echo '</div>';
    echo '</div>';


}

wp_reset_postdata();