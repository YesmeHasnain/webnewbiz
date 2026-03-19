<?php 
/**
 * @author: MadSparrow
 * @version: 1.0
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) { exit( 'Direct script access denied.' ); }

get_header();

$item = null;
$post_cat = null;
$post_id = null;
$order = null;
$orderby = null;
$query = siberia_posts_loop($item, $post_cat, $post_id, $order, $orderby); ?>

<header class="ms-sp--header">
	<h1 class="ms-sp--title"><?php esc_html_e('Latest Stories', 'siberia'); ?></h1>
</header>

<div class="container">
	<div class="row">
		<div class="ms-posts--default col">
			<div class="grid-sizer"></div>
			<?php if(have_posts()) :
				while($query->have_posts()) : $query->the_post();
					get_template_part( 'template-parts/post/default', get_post_format() );
				endwhile;
				wp_reset_postdata();
			endif; ?>
            <?php if ( $query->max_num_pages > 1 ) : ?>
                <div class="grid-item ms-pagination col">
                    <?php echo siberia_posts_pagination( $query ); ?>
                </div>
            <?php endif; ?>
		</div>
		<?php if ( is_active_sidebar( 'blog_sidebar' ) ) : ?>
			<div class="pl-lg-5 col-lg-4 ms-sidebar">
				<?php get_sidebar(); ?>
			</div>
		<?php endif; ?>
	</div>
</div>

<?php get_footer(); ?>