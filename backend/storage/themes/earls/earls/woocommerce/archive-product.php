<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.6.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header();
global $wp_query;
$data  = \EARLS\Includes\Classes\Common::instance()->data( 'product' )->get();

$layout = $data->get( 'layout' );
$sidebar = $data->get( 'sidebar' );
if (is_active_sidebar( $sidebar )) {$layout = 'right';} else{$layout = 'full';}
$class = ( !$layout || $layout == 'full' ) ? 'col-xs-12 col-sm-12 col-md-12' : 'col-lg-7 col-md-12 col-sm-12';

if ( class_exists( '\Elementor\Plugin' ) AND $data->get( 'tpl-type' ) == 'e' AND $data->get( 'tpl-elementor' ) ) {
	echo Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $data->get( 'tpl-elementor' ) );
} else {
?>

<?php if ( class_exists( '\Elementor\Plugin' )): ?>
	<?php do_action( 'earls_banner', $data ); ?>
<?php else: ?>
<!-- page-title --> 
<section class="page-title centred" >
   <div class="bg-layer parallax-bg" data-parallax='{"y": 150}' style="background-image: url('<?php echo esc_url( $data->get( 'banner' ) ); ?>');">
   </div>
   <div class="auto-container">
        <div class="content-box">
            <div class="title">
                <h1><?php if( $data->get( 'title' ) ) echo wp_kses( $data->get( 'title' ), true ); else( the_title()); ?></h1>
            </div>
        </div>
    </div>
</section>
<!-- page-title end -->
<?php endif; ?>

<!-- shop-page-section -->
<section class="shop-page-section sidebar-page-container shop-page-1">
    <div class="medium-container">
        <div class="row clearfix">
			
			<!-- sidebar area -->
            <?php
				if ( $data->get( 'layout' ) == 'left' ) {
					do_action( 'earls_sidebar', $data );
					
					/**
					 * woocommerce_sidebar hook
					 *
					 * @hooked woocommerce_get_sidebar - 10
					 */
					do_action( 'woocommerce_sidebar' );
				}
			?>
			<!-- sidebar area -->
			
			<div class="content-side <?php echo esc_attr($class); ?> ">
				
				<div class="our-shop">                
					<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
                            
                    <!--Sort By-->
                    <div class="items-sorting clearfix">
                        <div class="text">
                            <?php woocommerce_result_count(); ?>
                        </div>
                        <div class="woocommerce-form">
                            <?php
                                /**
                                 * woocommerce_before_shop_loop hook
                                 *
                                 * @hooked woocommerce_result_count - 20
                                 * @hooked woocommerce_catalog_ordering - 30
                                 */
                                do_action( 'woocommerce_before_shop_loop' );
                            ?>
                        </div>
                    </div>   
                    <?php endif; ?>
                    
                    <?php
                        /**
                         * woocommerce_before_main_content hook
                         *
                         * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
                         * @hooked woocommerce_breadcrumb - 20
                         */
                        do_action( 'woocommerce_before_main_content' );
                    ?>
                    
                    <?php
                        /**
                         * woocommerce_archive_description hook
                         *
                         * @hooked woocommerce_taxonomy_archive_description - 10
                         * @hooked woocommerce_product_archive_description - 10
                         */
                        do_action( 'woocommerce_archive_description' );
                    ?>
                    
                    <?php if ( have_posts() ) : ?>
                
                        <?php woocommerce_product_loop_start(); ?>
            
                            <?php woocommerce_product_subcategories(); ?>
            
                            <?php while ( have_posts() ) : the_post(); ?>
            
                                <?php wc_get_template_part( 'content', 'product' ); ?>
            
                            <?php endwhile; // end of the loop. ?>
            
                        <?php woocommerce_product_loop_end(); ?>
            
                        <?php
                            /**
                             * woocommerce_after_shop_loop hook
                             *
                             * @hooked woocommerce_pagination - 10
                             */
                            do_action( 'woocommerce_after_shop_loop' );
                        ?>
            
                    <?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>
            
                        <?php wc_get_template( 'loop/no-products-found.php' ); ?>
            
                    <?php endif; ?>
                    
                    <?php
                        /**
                         * woocommerce_after_main_content hook
                         *
                         * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
                         */
                        do_action( 'woocommerce_after_main_content' );
                    ?>
            	</div>
            </div>
            <!-- sidebar area -->
            <?php
				if ( $data->get( 'layout' ) == 'right' ) {
					do_action( 'earls_sidebar', $data );
					
					/**
					 * woocommerce_sidebar hook
					 *
					 * @hooked woocommerce_get_sidebar - 10
					 */
					do_action( 'woocommerce_sidebar' );
				}
			?>
    		<!--Sidebar-->
    
		</div>
	</div>
</section>

<?php
}
get_footer( 'shop' );
