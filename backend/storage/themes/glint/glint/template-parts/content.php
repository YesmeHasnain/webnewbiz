<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Glint
 * @since 1.0.0
 */

$glintTag = glint_function('Tags');
$glint_func = glint_function('Functions');


?>

<div class="single-blog-wrapper">
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <!-- blog-item-->
        <div class="single-blog-section ">
            <?php if ( has_post_thumbnail() ) : ?>
                <div class="single-blog-section-img">
                    <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(),'glint-blog')?>" alt="<?php the_title_attribute(); ?>" />
                    <div class="single-blog-section-img-tag blog-single-boxc"><?php the_category(); ?></div>
                </div>
            <?php endif; ?>
            <div class="single-blog-section-description blog-box">
            	<?php if ( get_the_title() ) : ?>
                <h2><a href="<?php the_permalink( ); ?>"><?php the_title(); ?></a></h2>
            	<?php endif; ?>
                <div class="single-blog-section-author">
                    <ul class="bmeta-wrap">
                        <li><?php $glintTag->posted_by(); ?></li>
                        <li><i class="fa fa-calendar"></i> <?php $glintTag->posted_on(); ?></li>
                    </ul>
                </div>
                <?php glint_excerpt( '35', $more=false ); ?>
                <div class="space-30"></div>
                <div class="blog-readmore-social">
                    <div class="row">
                        <div class="col-sm-6 align-self-center">
                            <a href="<?php the_permalink( ); ?>" class="cbtn cbnt1 fadeInDown animated"><?php esc_html_e('Read More', 'glint'); ?> <i class="fa fa-angle-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </article><!-- #post-<?php the_ID(); ?> -->
</div>