<?php
/**
 * Default post entry layout
 *
 * @package Zill WordPress theme
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$title_tag = 'h2';

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( ['post_item'] ); ?>>
    <div class="post_item-inner">
        <?php
        if(has_post_thumbnail()):
            ?>
            <div class="post_item-thumbnail">
                <a href="<?php the_permalink(); ?>" class="post_item-thumbnail-link">
                    <figure class="post_item-thumbnail-figure figure__object_fit">
                        <?php the_post_thumbnail('full'); ?>
                    </figure>
                </a>
            </div>
        <?php
        endif;
        ?>
        <div class="post_item-content">
            <?php
            echo sprintf(
                '<header class="post_item-content-header"><%1$s class="post_item-content-title lakit-posts__title"><a href="%2$s" rel="bookmark">%3$s</a></%1$s></header>',
                esc_attr($title_tag),
                esc_url(get_the_permalink()),
                get_the_title()
            );
            ?>
            <div class="entry-meta">
                <?php

                if(is_sticky() && !is_paged()){
                    echo sprintf('<span class="sticky-post">%s</span>', esc_html__('Featured', 'zill'));
                }

                zill_entry_meta_item_category_list('<div class="post__terms entry-meta__item">', '</div>');

                ?>
                <div class="posted__date entry-meta__item"><?php the_date(); ?></div>
            </div>
            <div class="entry-excerpt">
                <?php the_excerpt(); ?>
            </div>
            <div class="entry-more">
                <a href="<?php the_permalink(); ?>" class="button button-readmore"><?php echo get_post_type() === 'post' ? esc_html__('Read more', 'zill') : esc_html__('View more', 'zill'); ?></a>
            </div>
        </div>
    </div>
</article>