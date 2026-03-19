<?php

/**
 * Blog Content Template
 *
 * @package    WordPress
 * @subpackage EARLS
 * @author     Template Path
 * @version    1.0
 */

$options = earls_WSH()->option();
$allowed_tags = wp_kses_allowed_html('post');

?>

<div <?php post_class(); ?>>

	<div class="news-block-one wow slideInUp animated animated" data-wow-delay="00ms" data-wow-duration="1500ms" style="visibility: visible; animation-duration: 1500ms; animation-delay: 0ms; animation-name: slideInUp;">
        <div class="inner-box p_relative d_block">
            <?php if(has_post_thumbnail()){ ?>
            <figure class="image-box"><a href="<?php echo esc_url( the_permalink( get_the_id() ) );?>"><?php the_post_thumbnail('earls_1170x470'); ?></a></figure>
			<?php } ?>
            <div class="lower-content p_relative d_block">
                <?php if($options->get('blog_post_author') || $options->get('blog_post_date') || $options->get('blog_post_comments')){ ?>
                <ul class="post-info clearfix">
                    <?php if($options->get('blog_post_author')){ ?><li><a href="<?php echo esc_url(get_author_posts_url( get_the_author_meta('ID') )); ?>"><i class="fal fa-user"></i> <?php the_author(); ?></a> </li><?php } ?>
                    <?php if($options->get('blog_post_date')){ ?><li><a href="<?php echo get_month_link(get_the_date('Y'), get_the_date('m')); ?>"><i class="fal fa-calendar"></i> <?php echo get_the_date(); ?> </a> </li><?php } ?>
                    <?php if($options->get('blog_post_comments')){ ?><li><i class="far fa-comments-alt"></i> <?php comments_number( wp_kses(__('0 Comments' , 'earls'), true), wp_kses(__('01 Comment' , 'earls'), true), wp_kses(__('0% Comments' , 'earls'), true)); ?></li><?php } ?>
                </ul>
                <?php } ?>
                <h3><a href="<?php echo esc_url( the_permalink( get_the_id() ) );?>"><?php the_title(); ?></a></h3>
                <div class="short__des">
                    <?php the_excerpt(); ?>
                </div>
                <div class="link"><a href="<?php echo esc_url( the_permalink( get_the_id() ) );?>"><?php esc_html_e('view more', 'earls'); ?></a></div>
            </div>
        </div>
    </div>
        
</div>