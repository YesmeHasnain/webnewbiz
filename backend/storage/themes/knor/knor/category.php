<?php
/**
 * The template for displaying catgeory pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package knor
 */

get_header();

$knor_cat_style = get_term_meta( get_queried_object_id(), 'knor', true );
$knor_cat_style_template = !empty( $knor_cat_style['knor_cat_layout'] )? $knor_cat_style['knor_cat_layout'] : '';
	
?>

	<!-- Category Breadcrumb -->

	<div class="theme-breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-inner">
                        <ul>
                            <li><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'knor'); ?></a></li><span class="breadcrumb-divider">/</span>
                            <li><?php esc_html_e('Category', 'knor'); ?></li>
                        </ul>
                    </div>
                    <h1 class="theme-breacrumb-title">
						<?php echo esc_html__('Category','knor').' :'; ?>  <?php single_cat_title(); ?>
					</h1>

					<h5 class="page-short-description">
						<?php esc_html_e('We will help a client\'s problems to develop the products they have with high quality Change the appearance.', 'knor'); ?>
					</h5>

                </div>
            </div>
        </div>
    </div>
    <!-- Category Breadcrumb End -->

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
