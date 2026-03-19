<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package seacab
 */

if ( is_single() ) : ?>
    <article id="post-<?php the_ID();?>" <?php post_class( 'postbox__item format-search' );?>>
        <?php if ( has_post_thumbnail() ): ?>
            <div class="blog-details__img">
                <?php the_post_thumbnail( 'full', ['class' => 'img-responsive'] );?>
                <div class="blog-details__date">
                    <p><?php the_time( 'd M' ); ?></p>
                </div>
            </div>
        <?php endif;?>
        <div class="blog-details__content">
            <?php get_template_part( 'template-parts/blog/blog-meta-single' ); ?>
            <h3 class="blog-details__title"><?php the_title();?></h3>
            <div class="postbox__text">
                <?php the_content();?>
                <?php
                    wp_link_pages( [
                        'before'      => '<div class="page-links">' . esc_html__( 'Pages:', 'seacab' ),
                        'after'       => '</div>',
                        'link_before' => '<span class="page-number">',
                        'link_after'  => '</span>',
                    ] );
                ?>
            </div>
        </div>
        <div class="blog-details__bottom">
            <div class="blog-details__border">
                <div class="row">
                    <div class="col-xl-9 col-lg-8 col-md-7 col-sm-12">
                        <?php print seacab_get_tag();?>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-5 col-sm-12 text-end">
                        <?php get_template_part( 'template-parts/blog/blog-share' ); ?>
                    </div>
                </div>
            </div>
        </div>
    </article>
<?php else: ?>

    <article id="post-<?php the_ID();?>" <?php post_class( 'blog-one__single postbox__item format-search' );?>>
        <?php if ( has_post_thumbnail() ): ?>
            <div class="blog-one__img">
                <div class="blog-one__img-box">
                    <?php the_post_thumbnail( 'full', ['class' => 'img-responsive'] );?>
                    <a href="<?php the_permalink();?>"><i class="icon-link"></i></a>
                </div>
            </div>
        <?php endif;?>
        <div class="blog-one__content">
            <h3 class="blog-one__title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
            <?php get_template_part( 'template-parts/blog/blog-meta' ); ?>
            <div class="postbox__text">
                <?php the_excerpt();?>
            </div>
            <?php get_template_part( 'template-parts/blog/blog-btn' ); ?>
        </div>
    </article>
    
<?php endif;?>