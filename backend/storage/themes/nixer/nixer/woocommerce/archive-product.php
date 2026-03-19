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
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.6.0
 */

defined( 'ABSPATH' ) || exit;
$nixer_redux_demo = get_option('redux_demo');
get_template_part( 'header/header', '9' ); ?>
<main>
	<section class="tp-shop-breadcrumb-ptb pt-160 pb-100 p-relative">
		<?php if (!empty($nixer_redux_demo['our-shop-bg']['url'])): ?>
			<div class="tp-about-me-bg" data-background="<?php echo esc_url($nixer_redux_demo['our-shop-bg']['url']); ?>"></div>
		<?php endif ?>
		<div class="container">
			<div class="row">
				<div class="col-xl-12">
					<div class="tp-about-us-heading">
						<?php if (!empty($nixer_redux_demo['our-shop-heading'])): ?>
							<span class="tp-breadcrumb-subtitle"><?php echo esc_html($nixer_redux_demo['our-shop-heading']); ?></span>
						<?php endif ?>
						<h3 class="tp-breadcrumb-title tp-title-anim">
							<?php if ((isset($nixer_redux_demo['our-shop-title'])) && ('' != $nixer_redux_demo['our-shop-title'])) { ?>
								<?php echo esc_attr($nixer_redux_demo['our-shop-title']); ?>
							<?php } else { ?>
								<?php echo esc_html__( 'Our Shop', 'nixer' )?>
							<?php } ?>
						</h3>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section class="tp-shop-area pt-120 pb-120">
		<div class="container">
			<div class="tp-shop-top">
				<div class="row">
					<?php
					/**
					 * Hook: woocommerce_before_shop_loop.
					 *
					 * @hooked woocommerce_output_all_notices - 10
					 * @hooked woocommerce_result_count - 20
					 * @hooked woocommerce_catalog_ordering - 30
					 */
					do_action( 'woocommerce_before_shop_loop' );
					?>
				</div>
			</div>
			<div class="row">
				<?php
				if ( woocommerce_product_loop() ) {
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
				} else {
					/**
					 * Hook: woocommerce_no_products_found.
					 *
					 * @hooked wc_no_products_found - 10
					 */
					do_action( 'woocommerce_no_products_found' );
				} ?>
				<?php 
				/**
				 * Hook: woocommerce_after_shop_loop.
				 *
				 * @hooked woocommerce_pagination - 10
				 */
				do_action( 'woocommerce_after_shop_loop' );
				?>
			</div>
		</div>
	</section>
</main>
<?php
get_footer( 'shop' );