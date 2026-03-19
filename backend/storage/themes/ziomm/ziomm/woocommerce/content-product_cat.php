<?php
/**
 * The template for displaying product category thumbnails within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product_cat.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 4.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


echo '<div class="grid-item">';

?>

<article <?php wc_product_cat_class( 'vlt-product vlt-product--style-1', $category ); ?>>

	<?php

		/**
		* woocommerce_before_subcategory_title hook.
		*
		* @hooked woocommerce_subcategory_thumbnail - 10
		*/
		do_action( 'woocommerce_before_subcategory_title', $category );

		/**
		* woocommerce_shop_loop_subcategory_title hook.
		*
		* @hooked woocommerce_template_loop_category_title - 10
		*/
		do_action( 'woocommerce_shop_loop_subcategory_title', $category );

		/**
		* woocommerce_after_subcategory_title hook.
		*/
		do_action( 'woocommerce_after_subcategory_title', $category );

	?>

</article>

<?php

echo '</div>';