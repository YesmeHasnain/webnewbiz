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
 * @version 50.4.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

$classes = '';
$shop_layout = rdt_get_option('catalog_product_layout', 'left-sidebar');

// Start demo
if(isset($_GET['sidebar']) && $_GET['sidebar'] != '') {
	if($_GET['sidebar'] == 'left') {
		$shop_layout = 'left-sidebar';
	}elseif($_GET['sidebar'] == 'right') {
		$shop_layout = 'right-sidebar';
	}elseif($_GET['sidebar'] == 'no') {
		$shop_layout = 'no-sidebar';
	}
}
// End demo
if ( !is_active_sidebar('shop-filter') && !is_active_sidebar('column-shop')) {
	$shop_layout = 'no-sidebar';
}
if($shop_layout == 'left-sidebar') {
	$sidebar_class = 'left-sidebar';
	$content_class = 'col-lg-9 col-12 order-first order-lg-last';
}
if($shop_layout == 'right-sidebar') {
	$sidebar_class = 'right-sidebar';
	$content_class = 'col-lg-9 col-12 order-first';
}


$filter_class = '';
$filter_selection = rdt_get_option('catalog_product_filter_posistion', 'top');

// Start demo
if(isset($_GET['filter']) && $_GET['filter'] != '') {
	$filter_selection = $_GET['filter'];
}
// End demo

if($filter_selection == 'side' && $shop_layout == 'no-sidebar') {
	$filter_class = 'filter-button-show';
}else{
	$filter_class = 'filter-button-hide';
}

$page_title_design = rdt_get_option('page_title_design' , '2');
if(isset($_GET['page_title']) && $_GET['page_title'] != '') {
   $page_title_design = $_GET['page_title'];
}
$page_title_align = rdt_get_option('page_title_align' , 'left');
$page_title_size = rdt_get_option('page_title_size' , 'large');
$page_title_color = rdt_get_option('page_title_color' , 'dark');

if(is_product_category()) {
	$category = get_queried_object();
	$custom_page_title_bground = get_term_meta( $category->term_id, 'woo_category_image_heading', '' );
} elseif (is_shop()){
	$custom_page_title_bground = get_post_meta( get_option( 'woocommerce_shop_page_id' ), 'page_custom_title_image', '' );
} else {
	$custom_page_title_bground = '';
}

if($page_title_design == '1') : ?>
	<div class="page-title-section text-<?php echo esc_attr($page_title_align); ?> page-title-<?php echo esc_attr($page_title_size); ?> text-<?php echo esc_attr($page_title_color); ?>" 
		<?php if($custom_page_title_bground) : ?> style="background-image: url('<?php echo esc_url($custom_page_title_bground[0]); ?>');" <?php endif; ?>
		>
		
		<?php if ( ! is_front_page() ) :
			?>
			<div class="container">
				<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
					<h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
				<?php endif; ?>
			</div>
				<?php
			
			do_action( 'vasia_woocommerce_breadcrumb' );
		endif; ?>
		
	</div>
	<div class="container">
		<?php do_action( 'vasia_before_shop_content' ); ?>
	</div>
<?php endif;

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action( 'woocommerce_before_main_content' );
if($page_title_design == '2') : ?>
	
		<?php 
		/**
		 * Hook: woocommerce_before_main_content.
		 *
		 * @hooked woocommerce_breadcrumb
		 */
		do_action( 'vasia_woocommerce_breadcrumb' ); ?>
	
<?php endif; ?>
<div id="content">
	<div class="container">
		<div class="row">
			<div class="main-content <?php if($shop_layout != 'no-sidebar') { echo esc_attr($content_class); }else{ ?>col-12<?php } ?>">
				<?php if($page_title_design == '2') : ?>
				<div class="page-title-wrapper">
					<div>
						<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
							<h1 class="woocommerce-products-header__title page-title "><?php woocommerce_page_title(); ?></h1>
						<?php endif; ?>
						<?php do_action( 'vasia_before_shop_content' ); ?>
					</div>
				</div>
				<?php endif; ?>
				<?php do_action('vasia_before_shop_toolbar'); ?>
				<div class="vasia-shop-toolbar toolbar">
					
					<?php
					do_action( 'vasia_shop_toolbar' );
					?>
					<button class="button-show-filter <?php echo esc_attr($filter_class); ?>"><i class="icon-rt-options-outline"></i><?php echo esc_html__('Filters', 'vasia'); ?></button>
					<?php if( $shop_layout != 'no-sidebar' || ($shop_layout == 'no-sidebar' && $filter_selection == 'top')): ?>
						<div id="_mobile_filters_" class="filter-side"></div>
					<?php endif; ?>
				</div>
				<?php do_action('vasia_after_shop_toolbar'); ?>

				<?php if($shop_layout == 'no-sidebar') do_action( 'vasia_shop_filters' ); ?>

				<?php do_action('vasia_active_filters'); ?>
				
				<div class="archive-products-wrapper">
					<?php
						if ( woocommerce_product_loop() ) {
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
				<?php 
				/**
				 * Hook: woocommerce_after_shop_loop.
				 *
				 * @hooked woocommerce_pagination - 10
				 */
				do_action( 'woocommerce_after_shop_loop' );

				do_action( 'vasia_after_shop_content' );
				?>
			</div>
			<?php if($shop_layout != 'no-sidebar') { ?>
				<aside class="sidebar widget-area-side col-lg-3 col-12 <?php echo esc_attr($sidebar_class); ?>">
					<?php
					do_action( 'woocommerce_sidebar' );
					?>
				</aside>
			<?php } ?>
		</div>
	</div>
</div>
<?php

/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' );

get_footer( 'shop' );
