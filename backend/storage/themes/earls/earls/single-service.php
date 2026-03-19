<?php get_header();
    $data    = \EARLS\Includes\Classes\Common::instance()->data('single-service')->get();

    $layout = $data->get('layout');
    $sidebar = $data->get('sidebar');
    $layout = ($layout) ? $layout : 'right';
    $sidebar = ($sidebar) ? $sidebar : 'default-sidebar';
    if (is_active_sidebar($sidebar)) {
        $layout = 'right';
    } else {
        $layout = 'full';
    }
    $class = (!$layout || $layout == 'full') ? 'col-xl-12 col-lg-12 col-sm-12 col-md-12' : 'col-xl-9 col-lg-8 col-md-12 col-sm-12';
    $options = earls_WSH()->option();

    do_action('earls_banner', $data);
    $allowed_tags = wp_kses_allowed_html('post');
?>

<!--Start Service Details Page1-->
<section class="service-details-page1">
    <div class="container">
        <div class="row">
            
            <?php if( $data->get( 'layout' ) == 'left' ): ?>
			<div class="col-xl-3 col-lg-4">
            	<div class="service-details-sidebar-box">
                    <?php dynamic_sidebar( $sidebar ); ?>
                </div>
            </div>
			<?php endif; ?>
            
            <?php 
				while (have_posts()) : the_post(); 
				$service_image = get_post_meta(get_the_id(), 'service_image', true);
			?>
            <div class="<?php echo esc_attr( $class ); ?>">
                <div class="service-details-page1__content <?php if ( $data->get( 'layout' ) == 'left' ) echo 'pl-0'; elseif ( $data->get( 'layout' ) == 'right' ) echo ''; ?>">
                    <?php if($service_image['url']){ ?>
                    <div class="img-box">
                        <img src="<?php echo esc_url($service_image['url']);?>" alt="<?php esc_attr_e('Awesome Image', 'earls'); ?>" />
                    </div>
                    <?php } ?>
                    
                    <?php the_content(); ?>
    
                </div>
            </div>
			<?php endwhile; ?>
            
            <?php if( $data->get( 'layout' ) == 'right' ): ?>
			<div class="col-xl-3 col-lg-4">
            	<div class="service-details-sidebar-box">
                    <?php dynamic_sidebar( $sidebar ); ?>
                </div>
            </div>
			<?php endif; ?>
            
        </div>
    </div>
</section>

<?php get_footer(); ?>
