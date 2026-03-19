<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package saasten
 */
 
get_header();

?>

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
                <?php printf(esc_html__('Search Results for: %s', 'saasten') , get_search_query()); ?>
              </h3>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- End BreadCrumb -->


	
	<!-- Search Breadcrumb -->
    <div class="theme-breadcrumb__Wrapper theme-breacrumb-area">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
					<h1 class="theme-breacrumb-title">
						<?php printf(esc_html__('Search Results for: %s', 'saasten') , get_search_query()); ?>
					</h1>
					<div class="breaccrumb-inner">
						<?php 
							if ( shortcode_exists( '[flexy_breadcrumb]' ) ) {
								echo do_shortcode( '[flexy_breadcrumb]');
							}
						?>
					</div>
                </div>
            </div>
        </div>
    </div>
    <!-- Search Breadcrumb End -->

	<section id="main-content" class="blog main-container blog-spacing" role="main">
		<div class="container">
			<div class="row">
				<div class="<?php if(is_active_sidebar('sidebar-1')) { echo "col-lg-8"; } else { echo "col-lg-12";}?> col-md-12">
					<div class="category-layout-two main-blog-layout blog-new-layout theme-layout-mainn">
					<?php if (have_posts()): ?>
					
						<?php while (have_posts()): the_post(); ?>
							<?php get_template_part('template-parts/content', get_post_format());?>
						<?php
						endwhile; ?>
						
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
				</div>

				<?php get_sidebar(); ?>

			</div>
		</div>
	</section>
	
<?php get_footer(); ?>
