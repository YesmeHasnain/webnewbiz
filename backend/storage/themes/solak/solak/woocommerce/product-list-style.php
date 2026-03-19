<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

    echo '<div class="th-product list-view">';
        echo '<div class="product-img">';
            if( has_post_thumbnail() ){
                    the_post_thumbnail( 'solak-shop-thumb' );
                echo '<div class="actions">';
                    // Cart Button
                    woocommerce_template_loop_add_to_cart();
                echo '</div>';
            }
        echo '</div>';

        echo '<div class="product-content">';
            $categories = get_the_terms($product->get_id(), 'product_cat');
            if ($categories && !is_wp_error($categories)) {
                $first_category = reset($categories);
                echo '<a class="product-category" href="' . esc_url(get_term_link($first_category)) . '">' . esc_html($first_category->name) . '</a>';
            } ;
            if( get_the_title() ){
                echo '<h3 class="product-title"><a href="'.esc_url( get_the_permalink() ).'">'.esc_html( get_the_title() ).'</a></h3>';
            }
            if( $product->get_type() == 'simple' ) {
                $rprice = $product->get_price_html();
                echo '<span class="price">'.$rprice.'</span>';
            }
            woocommerce_template_loop_rating();
        echo '</div>';
    echo '</div>';