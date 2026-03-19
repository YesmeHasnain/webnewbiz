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

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
get_header( 'shop' );
$widgets = wp_get_sidebars_widgets();
$sidebar = ( (1 == Nova_OP::getOption('shop_sidebar')) );
$shop_width_layout = ( ('wide' == Nova_OP::getOption('shop_layout_width')) );
$current_url = add_query_arg(null, null);
$per_page_array = nova_woo_get_product_per_page_array();
$per_page = nova_woo_get_product_per_page();
if (nova_is_custom_archive()):
	remove_action( 'woocommerce_product_loop_start', 'woocommerce_maybe_show_product_subcategories' );
endif;
$l_class = ( $shop_width_layout ) ? 'nova-container-fluid' : 'nova-container';
$l20class = ( $sidebar && is_active_sidebar( 'shop-widget-area' ) ) ? '' : ' no-sidebar';
$sidebar_class= ( $sidebar && is_active_sidebar( 'shop-widget-area' ) ) ? ' shop-has-sidebar' : '';
$l40class = ( $sidebar && is_active_sidebar( 'shop-widget-area' ) ) ? 'large-9' : 'large-12';
?>
<div class="<?php echo esc_attr($l_class); ?><?php echo esc_attr($l20class); ?>">
	<div class="grid-x">

		<div class="cell large-12">

			<div class="site-content<?php echo esc_attr($sidebar_class); ?>">

				<?php do_action( 'woocommerce_before_main_content' ); ?>
				<div class="grid-x">

					<?php if ( $sidebar && is_active_sidebar( 'shop-widget-area' ) && Nova_OP::getOption('shop_sidebar_position') == 'left') : ?>
						<div id="sidebar_primary" class="cell small-10 large-3 nova-sidebar sidebar-left">
							<div class="nova-sidebar__overlay js-sidebar-toogle"></div>
							<div class="nova-sidebar__container">
								<a class="nova-sidebar__toggle js-sidebar-toogle" href="javascript:void(0)"></a>
								<div class="woocommerce-sidebar-sticky sidebar-scrollable">
									<?php do_action( 'woocommerce_sidebar' ); ?>
								</div>
							</div>
						</div>

					<?php endif; ?>

					<div id="content_primary" class="cell small-12 <?php echo esc_attr($l40class); ?>">

						<div class="site-main-content-wrapper">
							<div class="shop_header_placeholder">
								<header class="woocommerce-archive-header">
									<?php do_action( 'woocommerce_before_shop_products_header' ); ?>
										<?php get_template_part( 'template-parts/global/page-header' ) ?>
										<?php
											if( (( woocommerce_get_loop_display_mode() != 'subcategories' ) && (wc_get_loop_prop( 'total' ) > 0 )) || nova_is_custom_archive() ): ?>
											<div class="woocommerce-archive-header-inside">
											<div class="woocommerce-archive-toolbar sh--color">
												<?php if(!empty($per_page_array)): ?>
					                    <div class="nova-custom-view">
																<label><?php echo esc_html__( 'Show','bazien' ); ?></label>
					                     <ul><?php
									            foreach ($per_page_array as $val){?><li
										            <?php if($per_page == $val) { echo ' class="active"'; } ?>><a href="<?php echo esc_url(add_query_arg('per_page', $val, $current_url)); ?>"><?php echo esc_html( $val ) ?></a></li>
									            <?php }
									            ?></ul>
					                    </div>
						            <?php endif ;?>
												<?php do_action( 'nova_woocommerce_catalog_ordering' ); ?>
											</div>
										</div>
										<?php endif; ?>

									<?php do_action( 'woocommerce_after_shop_products_header' ); ?>

								</header>

							</div>

							<?php do_action( 'woocommerce_archive_description' ); ?>

							<div class="grid-x">

								<div class="cell large-12">

									<div class="site-main-content">

										<?php if ( (function_exists('woocommerce_product_loop') && woocommerce_product_loop()) || have_posts() ) :

											do_action( 'woocommerce_before_shop_loop' );

												woocommerce_product_loop_start();
												if ( wc_get_loop_prop( 'total' ) || ( nova_is_custom_archive())) {
												?>
												<li class="grid-new-row"></li>
												<?php while ( have_posts() ) : the_post(); ?>

													<?php do_action( 'woocommerce_shop_loop' ); ?>
													<?php wc_get_template_part( 'content', 'product' ); ?>

												<?php endwhile; ?>
											<?php } ?>

											<?php woocommerce_product_loop_end(); ?>

											<?php do_action( 'woocommerce_after_shop_loop' ); ?>

										<?php else :  ?>

											<?php do_action( 'woocommerce_no_products_found' ); ?>

										<?php endif; ?>


									</div> <!-- end site-main-content -->

								</div> <!-- end large-12 -->

							</div><!-- end row -->

						</div> <!-- end site-main-content-wrapper -->

					</div>

					<?php if ( $sidebar && is_active_sidebar( 'shop-widget-area' ) && Nova_OP::getOption('shop_sidebar_position') == 'right') : ?>

						<div id="sidebar_primary" class="cell small-11 large-3 nova-sidebar sidebar-right">
							<div class="woocommerce-sidebar-sticky">
								<?php do_action( 'woocommerce_sidebar' ); ?>
							</div>
						</div>

					<?php endif; ?>

				</div> <!-- end row -->

				<?php do_action( 'woocommerce_after_main_content' ); ?>

			</div> <!-- end site-content -->

		</div> <!-- end large-12 -->

	</div> <!-- end row -->
</div>

<?php
get_footer( 'shop' );
