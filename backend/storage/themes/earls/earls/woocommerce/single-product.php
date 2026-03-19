<?php
/**
 * The Template for displaying all single products.
 *
 * Override this template by copying it to yourtheme/woocommerce/single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.6.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
get_header( 'shop' );
$data    = \EARLS\Includes\Classes\Common::instance()->data( 'single' )->get();

$layout = $data->get( 'layout' );
$sidebar = $data->get( 'sidebar' );
if (is_active_sidebar( $sidebar )) {$layout = 'right';} else{$layout = 'full';}
$class = ( !$layout || $layout == 'full' ) ? 'col-xs-12 col-sm-12 col-md-12' : 'col-xs-12 col-sm-12 col-md-12 col-lg-7';

if ( class_exists( '\Elementor\Plugin' ) && $data->get( 'tpl-type' ) == 'e') {
	
	while(have_posts()) {
	   the_post();
	   the_content();
    }

} else {
?>

<?php if ( class_exists( '\Elementor\Plugin' )):?>
	<?php do_action( 'earls_banner', $data );?>
<?php else:?>
<!-- page-title --> 
<section class="page-title centred" >
   <div class="bg-layer parallax-bg" data-parallax='{"y": 150}' style="background-image: url('<?php echo esc_url( $data->get( 'banner' ) ); ?>');">
   </div>
   <div class="auto-container">
        <div class="content-box">
            <div class="title">
                <h1><?php if( $data->get( 'title' ) ) echo wp_kses( $data->get( 'title' ), true ); else( the_title( ) ); ?></h1>
            </div>
        </div>
    </div>
</section>
<!-- page-title end -->
<?php endif;?>

<!-- product-details -->
<section class="product-details product-details-1">
    <div class="medium-container">
        <div class="product-details-content">
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
                <div class="<?php echo esc_attr($class);?>">
                    <?php
                        /**
                         * woocommerce_before_main_content hook
                         *
                         * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
                         * @hooked woocommerce_breadcrumb - 20
                         */
                        do_action( 'woocommerce_before_main_content' );
                    ?>
                        <?php while ( have_posts() ) : the_post(); ?>
                            <?php wc_get_template_part( 'content', 'single-product' ); ?>
                        <?php endwhile; // end of the loop. ?>
                    <?php
                        /**
                         * woocommerce_after_main_content hook
                         *
                         * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
                         */
                        do_action( 'woocommerce_after_main_content' );
                    ?>                    
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
			</div>
        </div>
	</div>
</section>
<?php
}
get_footer( 'shop' );
