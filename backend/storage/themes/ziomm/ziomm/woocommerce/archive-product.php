<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

$column_sidebar_class = 'col-lg-4';
$column_content_class = is_active_sidebar( 'shop_sidebar' ) ? 'col-lg-8' : 'col-lg-8 offset-lg-2';

if ( ziomm_get_theme_mod( 'sticky_sidebar' ) == 'enable' ) {
	$column_sidebar_class .= ' sticky-parent';
}

$sidebar_class = 'vlt-sidebar vlt-sidebar--right';

remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

add_action( 'ziomm_woocommerce_result_count', 'woocommerce_result_count' );
add_action( 'ziomm_woocommerce_catalog_ordering', 'woocommerce_catalog_ordering' );

?>

<main class="vlt-main">

	<?php

		/**
		* Hook: woocommerce_before_main_content.
		*
		* @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		* @hooked woocommerce_breadcrumb - 20
		* @hooked WC_Structured_Data::generate_website_data() - 30
		*/
		do_action( 'woocommerce_before_main_content' );

		if ( apply_filters( 'woocommerce_show_page_title', true ) ) {
			get_template_part( 'template-parts/page-title/page-title', 'shop' );
		}

	?>

	<div class="vlt-page-content vlt-page-content--padding">

		<div class="container">

			<div class="row">

				<div class="<?php echo ziomm_sanitize_class( $column_content_class ); ?>">

					<?php

						if ( woocommerce_product_loop() ) {

							/**
							* Hook: woocommerce_before_shop_loop.
							*
							* @hooked woocommerce_output_all_notices - 10
							* @hooked woocommerce_result_count - 20
							* @hooked woocommerce_catalog_ordering - 30
							*/
							do_action( 'woocommerce_before_shop_loop' );

							wc_get_template_part( 'content-loop', 'header' );

							woocommerce_product_loop_start();

							if ( wc_get_loop_prop( 'total' ) ) {

								while ( have_posts() ) {

									the_post();

									/**
									* Hook: woocommerce_shop_loop.
									*/
									do_action( 'woocommerce_shop_loop' );

									wc_get_template_part( 'content', 'product' );

								}

							}

							woocommerce_product_loop_end();

							/**
							* Hook: woocommerce_after_shop_loop.
							*
							* @hooked woocommerce_pagination - 10
							*/
							do_action( 'woocommerce_after_shop_loop' );

						} else {

							/**
							* Hook: woocommerce_no_products_found.
							*
							* @hooked wc_no_products_found - 10
							*/
							do_action( 'woocommerce_no_products_found' );

						}

					?>

				</div>

				<?php if ( is_active_sidebar( 'shop_sidebar' ) ) : ?>

					<div class="<?php echo ziomm_sanitize_class( $column_sidebar_class ); ?>">

						<?php if ( ziomm_get_theme_mod( 'sticky_sidebar' ) == 'enable' ) : ?>

						<div class="sticky-column">

						<?php endif; ?>

							<div class="<?php echo ziomm_sanitize_class( $sidebar_class ); ?>">

								<?php

									/**
									* Hook: woocommerce_sidebar.
									*
									* @hooked woocommerce_get_sidebar - 10
									*/
									do_action( 'woocommerce_sidebar' );

								?>

							</div>

						<?php if ( ziomm_get_theme_mod( 'sticky_sidebar' ) == 'enable' ) : ?>

						</div>

						<?php endif; ?>

					</div>

				<?php endif; ?>

			</div>

		</div>

	</div>
	<!-- /.vlt-page-content -->

</main>
<!-- /.vlt-main -->

<?php get_footer( 'shop' ); ?>