<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package saasten
 */

get_header(); 


if(get_post_meta($post->ID, 'saasten_post_meta', true)) {
    $page_meta = get_post_meta($post->ID, 'saasten_post_meta', true);
} else {
    $page_meta = array();
}

if( array_key_exists( 'page_title_enable', $page_meta )) {
    $enable_title = $page_meta['page_title_enable'];
} else {
    $enable_title = true;
}



?>

    <!-- Page Breadcrumb -->
	
	<?php if( $enable_title == true ) : ?>



    <!-- BreadCrumb -->
    <div class="breadcrumb-area saastain-bg__cover" style="background-image: url('<?php echo SAASTEN_IMG ."/breadcrumb.png"; ?>')">
      <div class="container">
        <div class="row">
          <div class="col-12 col-sm-12 col-md-12">
            <div class="page-banner-content text-center">
              <h3
                class="page-banner-heading saastain-gsap-anim3"
                data-aos="fade-in"
              >
                <?php the_title(); ?>
              </h3>
              <!-- Breadcrumb Start-->
              <nav aria-label="breadcrumb">
                <ol
                  class="breadcrumb justify-content-center"
                  data-aos="fade-in"
                >
                  <li class="breadcrumb-item"><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'saasten'); ?></a></li>
                  <li class="breadcrumb-item active" aria-current="page">
                    <?php the_title(); ?>
                  </li>
                </ol>
              </nav>
              <!-- Breadcrumb End-->
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- End BreadCrumb -->
	
	<?php endif; ?>
	
    <!-- Page Breadcrumb End -->

	<div id="main-content" class="main-container theme-page-spacing" role="main">
		<div class="container">   
			<div class="row">
				<div class="col-lg-12">
					<?php while ( have_posts() ) : the_post(); ?>
						<div class="single-content">
							<div class="entry-content">
								<?php get_template_part( 'template-parts/content', 'page' ); ?>
								
								<?php 
									// If comments are open or we have at least one comment, load up the comment template.
									if ( comments_open() || get_comments_number() ) :
										comments_template();
									endif;
								?>	
								
							</div>
						</div>
						
					<?php endwhile; ?>
				</div> 
			</div> 
		</div> 
	</div> 
	
	<?php get_footer(); ?>