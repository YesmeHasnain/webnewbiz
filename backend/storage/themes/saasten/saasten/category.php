<?php
/**
 * The template for displaying catgeory pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package saasten
 */

get_header();

$saasten_cat_style = get_term_meta( get_queried_object_id(), 'saasten', true );
$saasten_cat_style_template = !empty( $saasten_cat_style['saasten_cat_layout'] )? $saasten_cat_style['saasten_cat_layout'] : '';
	
?>

	<!-- Category Breadcrumb -->

    <div class="breadcrumb-area saastain-bg__cover" style="background-image: url('<?php echo SAASTEN_IMG ."/breadcrumb.png"; ?>')">
      <div class="container">
        <div class="row">
          <div class="col-12 col-sm-12 col-md-12">
            <div class="page-banner-content text-center">
              <h3
                class="page-banner-heading saastain-gsap-anim3"
                data-aos="fade-in"
              >
                <?php echo esc_html__('Category','saasten').' :'; ?>  <?php single_cat_title(); ?>
              </h3>
              <!-- Breadcrumb Start-->
              <nav aria-label="breadcrumb">
                <ol
                  class="breadcrumb justify-content-center"
                  data-aos="fade-in"
                >
                  <li class="breadcrumb-item"><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'saasten'); ?></a></li>
                  <li class="breadcrumb-item active" aria-current="page">
                    <?php single_cat_title(); ?>
                  </li>
                </ol>
              </nav>
              <!-- Breadcrumb End-->
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Category Breadcrumb End -->

	<section id="main-content" class="blog main-container cat-page-spacing" role="main">
		<div class="container">
			<div class="row">
				<div class="<?php if(is_active_sidebar('sidebar-1')) { echo "col-lg-8"; } else { echo "col-lg-12";}?> col-md-12">
				
					<?php if (have_posts() ): ?>
					
					<?php 
				
						$saasten_cat_global = saasten_get_option( 'saasten_cat_layout' ); //for global	  
						
						if( is_category() && !empty( $saasten_cat_style  ) ) {
						 
						get_template_part( 'template-parts/category-templates/'.$saasten_cat_style_template.'' ); 
						
						} elseif ( class_exists( 'CSF' ) && !empty( $saasten_cat_global ) ) {
							
							get_template_part( 'template-parts/category-templates/'.$saasten_cat_global.'' );
							
						} else {
							
							get_template_part( 'template-parts/category-templates/catt-one' ); 
						}
					?>
		
					<div class="theme-pagination-style">
						<?php
							the_posts_pagination(array(
							'next_text' => '<i class="fa fa-long-arrow-right"></i>',
							'prev_text' => '<i class="fa fa-long-arrow-left"></i>',
							'screen_reader_text' => ' ',
							'type'                => 'list'
						));
						?>
					</div>
					
					<?php else: ?>
						<?php get_template_part('template-parts/content', 'none'); ?>
					<?php endif; ?>
					
				</div>
				
				<?php get_sidebar(); ?>
				
			</div>
		</div>
	</section>

<?php get_footer(); ?>
