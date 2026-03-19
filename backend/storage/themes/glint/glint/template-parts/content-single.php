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

$glint_enable_meta = cs_get_option('enable_post_meta_single');
$glint_enable_post_tags = cs_get_option('enable_post_tags_single');
$glint_enable_post_nav = cs_get_option('enable_post_nav_single');


?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="single-blog-section">
        <?php if ( has_post_thumbnail() ) : ?>
            <div class="single-blog-section-img">
                <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(),'glint-blog')?>" alt="<?php the_title_attribute(); ?>" />
                <div class="single-blog-section-img-tag blog-single-boxc"><?php the_category(); ?></div>
            </div>
        <?php endif; ?>

        <div class="single-blog-section-description blog-box blog-detail-wrapp">
        	<?php if ( get_the_title() ) :?>
            <h2 class="btitle-top"><?php the_title(); ?></h2>
        	<?php endif; ?>
            <div class="single-blog-section-author">
                <ul class="bmeta-wrap">
                    <li><?php $glintTag->posted_by(); ?></li>
                    <li><i class="fa fa-calendar"></i> <?php $glintTag->posted_on(); ?></li>
                </ul>
            </div>
            <?php
                the_content( esc_html__( 'Continue reading', 'glint' ));
                wp_link_pages();
            ?>
        </div>
        <?php if( has_tag() || class_exists( 'Sassy_Social_Share' ) ) : ?>
	        <div class="related_tags">
	            <div class="row">
	            	
	            	<?php if( has_tag() ) : ?>
	                <div class="<?php if( class_exists( 'Sassy_Social_Share' ) ): echo "col-lg-6"; else: echo 'col-lg-12'; endif; ?> align-self-center">
	                    <h3><?php esc_html_e('Tags:', 'glint'); ?></h3>
	                    <div class="space-10"></div>
	                    <!--content buttom-->
	                    <div class="blog-details_bottom">
	                        <?php $glintTag->tags(); //post tags ?>
	                    </div>
	                </div>
	            	<?php endif; ?>

	                <?php if( class_exists( 'Sassy_Social_Share' ) ): ?>
	                <div class="<?php if( has_tag() ): echo "col-lg-6"; else: echo 'col-lg-12'; endif; ?> align-self-center">
	                	<h3><?php esc_html_e('Share:', 'glint'); ?></h3>
	                    <div class="space-10"></div>
	                	<?php echo do_shortcode( '[Sassy_Social_Share]' ); ?>
	                </div>
	            	<?php endif; ?>

	            </div>
	        </div>
        <?php endif; ?>
        <div class="space-30"></div>
        <div class="border-top"></div>
        <div class="space-30"></div>
        <?php if( $glint_enable_post_nav == true ) :?>
	        <div class="post_prev_next">
	            <div class="row">
					<div class="col-md-12">
		                <?php previous_post_link('<div class="align-self-center left-postt"><h5>%link</h5></div>'); ?>
		                <div class="text-center align-self-center center_btn_grid">
		                    <div class="pre_next_grid"><i class="fa fa-th"></i></div>
		                </div>
		                <?php next_post_link('<div class="text-right align-self-center right-postt"><h5>%link</h5></div>'); ?>
					</div>
	            </div>
	        </div>
        <?php endif; ?>
    </div>
</article><!-- #post-<?php the_ID(); ?> -->