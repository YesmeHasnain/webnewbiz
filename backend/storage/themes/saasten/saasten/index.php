<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package saasten
 */

$blog_title = saasten_get_option('blog_title', true);
$blog_breadcrumb = saasten_get_option('blog_breadcrumb_enable', true);

get_header();

?>

	<?php if($blog_breadcrumb == true) :?>

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
                <?php esc_html_e('Blog Page', 'saasten'); ?>
              </h3>
              <!-- Breadcrumb Start-->
              <nav aria-label="breadcrumb">
                <ol
                  class="breadcrumb justify-content-center"
                  data-aos="fade-in"
                >
                  <li class="breadcrumb-item"><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'saasten'); ?></a></li>
                  <li class="breadcrumb-item active" aria-current="page">
                    <?php esc_html_e('Blog', 'saasten'); ?>
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
	
	<section id="main-content" class="blog main-container blog-spacing" role="main">
		<div class="container">
			<div class="row">
				<div class="<?php if(is_active_sidebar('sidebar-1')) { echo "col-lg-8"; } else { echo "col-lg-12";}?> col-md-12">
					<div class="category-layout-two main-blog-layout blog-new-layout theme-layout-mainn">
					<?php if (have_posts()): ?>
					
						<div class="main-content-inner category-layout-one <?php if(has_post_thumbnail()) { echo "has-fblog"; } else { echo "no-fblog"; } ?>">
						<?php while (have_posts()): the_post(); ?>
							<?php get_template_part('template-parts/content', get_post_format());?>
						<?php
						endwhile; ?>
						</div>	
						
						<div class="theme-pagination-style">
							<?php
								the_posts_pagination(array(
								'next_text' => '<i class="icofont-long-arrow-right"></i>',
								'prev_text' => '<i class="icofont-long-arrow-left"></i>',
								'screen_reader_text' => ' ',
								'type'               => 'list'
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
