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

<?php if ( have_posts() ) : ?>

<section class="ms-page-header">
    <div class="ms-sp--header">
        <h1 class="ms-sp--title"><?php esc_html_e( 'Search results: ', 'siberia' ); ?><i class="search-word"><?php printf( get_search_query() ); ?></i></h1>
    </div>
</section>

<div class="container">
    <div class="row">
        <div class="ms-posts--default ms-sp-list col">
            <div class="grid-sizer col-12@sm"></div>
                <?php while ( have_posts() ) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('grid-item col-12@sm'); ?>>
                        <div class="card__content">
                            <a href="<?php the_permalink(); ?>" class="card__title">
                                <h2 class=""><?php the_title(); ?></h2>
                            </a>
                            <div class="post-footer">
                                <div class="post-meta-date">
                                    <span><?php echo get_the_date(); ?></span>
                                    <span class="post-category link"><?php the_category(',&nbsp;'); ?></span>
                                </div>
                            </div>
                            <p class="text-sm post-excerpt">
                                <?php echo get_the_excerpt(); ?>
                                <a href="<?php the_permalink(); ?>"><?php esc_html_e('Read more', 'siberia'); ?></a>
                            </p>       
                        </div>
                    </article>
                <?php endwhile;
                wp_reset_postdata(); ?>
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
   
<?php else : get_template_part( 'template-parts/page/page', 'none' ); endif; ?>

<?php get_footer(); ?>