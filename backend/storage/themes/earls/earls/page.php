<?php
/**
 * Default Template Main File.
 *
 * @package EARLS
 * @author  TemplatePath
 * @version 1.0
 */

get_header();
$data  = \EARLS\Includes\Classes\Common::instance()->data( 'single' )->get();
$layout = $data->get( 'layout' );
$sidebar = $data->get( 'sidebar' );
if (is_active_sidebar( $sidebar )) {$layout = 'right';} else{$layout = 'full';}
$class = ( !$layout || $layout == 'full' ) ? 'col-lg-12 col-sm-12 col-md-12' : 'col-lg-7 col-md-12 col-sm-12';
?>

<?php if ( $data->get( 'enable_banner' ) ) : ?>
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

<!-- sidebar-page-container -->
<section class="sidebar-page-container pt_120 pb_120">
    <div class="medium-container">
        <div class="row">
		
        	<?php
				if ( $data->get( 'layout' ) == 'left' ) {
					do_action( 'earls_sidebar', $data );
				}
            ?>
            <div class="content-side <?php echo esc_attr( $class ); ?> <?php if ( $data->get( 'layout' ) == 'left' ) echo 'pl-0'; elseif ( $data->get( 'layout' ) == 'right' ) echo ''; ?>">
            	<div class="blog-page-content">
                    <div class="thm-unit-test">
                            
                        <?php while ( have_posts() ): the_post(); ?>
                            <?php the_content(); ?>
                        <?php endwhile; ?>
                        
                        <div class="clearfix"></div>
                        <?php
                        $defaults = array(
                            'before' => '<div class="paginate-links">' . esc_html__( 'Pages:', 'earls' ),
                            'after'  => '</div>',
        
                        );
                        wp_link_pages( $defaults );
                        ?>
                        <?php comments_template() ?>
                     
                     </div>
                 </div>
            </div>
            <?php
				if ( $layout == 'right' ) {
					$data->set('sidebar', 'default-sidebar');
					do_action( 'earls_sidebar', $data );
				}
            ?>
        
        </div>
	</div>
</section><!-- blog section with pagination -->
<?php get_footer(); ?>
