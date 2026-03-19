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
 * @package seacab
 */

get_header();

$class_main = 'col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12';
$classes_post_grid = 'col-xl-4 col-lg-6 col-md-6';
$seacab_blog_page_sidebar_hide = get_theme_mod( 'seacab_blog_page_sidebar_hide', false );
if( !empty($seacab_blog_page_sidebar_hide) ){ 
	if(is_active_sidebar('blog-sidebar')){ 
		$class_main = 'col-xl-8 col-lg-8 col-md-12 col-sm-12 col-xs-12';
		$classes_post_grid = 'col-xl-6 col-lg-6 col-md-6';
	}
}

?>

<section class="tp-blog-area blog-girde">
    <div class="container">
        <div class="row">
			<div class="<?php echo esc_attr($class_main) ?> blog-post-items blog-padding">
				<div class="row">
							<?php
								if ( have_posts() ):
								if ( is_home() && !is_front_page() ):
							?>
							
							<header>
								<h1 class="page-title screen-reader-text"><?php single_post_title();?></h1>
							</header>
							<?php
								endif;?>
							<?php
								/* Start the Loop */
								while ( have_posts() ): the_post(); ?>
					<div class="<?php echo esc_attr($classes_post_grid) ?>">
								<?php
									/*
									* Include the Post-Type-specific template for the content.
									* If you want to override this in a child theme, then include a file
									* called content-___.php (where ___ is the Post Type name) and that will be used instead.
									*/
									get_template_part( 'template-parts/content' );?>
									
					</div>
								<?php
									endwhile;
								?>
									<div class="basic-pagination">
										<?php seacab_pagination( '<i class="fas fa-arrow-left"></i>', '<i class="fas fa-arrow-right"></i>', '', ['class' => ''] );?>
									</div>
								<?php
								else:
									get_template_part( 'template-parts/content', 'none' );
								endif;
							?>
							

						</div>
			</div>

			<?php if ( !empty( $seacab_blog_page_sidebar_hide ) ): ?>
			<?php if ( is_active_sidebar( 'blog-sidebar' ) ): ?>
		        <div class="col-lg-4">
		        	<div class="blog__sidebar pl-70">
						<?php get_sidebar();?>
	            	</div>
	            </div>
			<?php endif;?>
			<?php endif;?>
        </div>
    </div>
</section>

<?php
get_footer();
