<?php 
/**
 * @author: MadSparrow
 * @version: 1.0
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) { exit( 'Direct script access denied.' ); }

$thumb_size = 'siberia-default-post-thumb';
$alt = get_the_author_meta('display_name'); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class('grid-item col-12'); ?>>
    <?php if ( has_post_thumbnail() ) : ?>

      <a href="<?php the_permalink(); ?>">
        <figure class="media-wrapper media-wrapper--21:9">         
          <img src="<?php the_post_thumbnail_url($size = $thumb_size); ?>" alt="<?php the_title_attribute (); ?>">
            <?php if ( is_sticky() ) : ?>
              <div class="ms-sticky">
                <span class="ms-sticky--icon">
                  <svg version="1.1" viewBox="0 0 460 460" style="enable-background:new 0 0 460 460;" xml:space="preserve">
                  <path d="M421.5,2.9c-3.5-3.5-9-3.9-12.9-1l-303,220c-3.5,2.5-5,7.1-3.6,11.2c1.3,4.1,5.2,6.9,9.5,6.9h72.8L37.4,444.2
                    c-2.9,4-2.4,9.5,1.1,12.9c1.9,1.9,4.5,2.9,7.1,2.9c2,0,4.1-0.6,5.9-1.9l303-220c3.5-2.5,5-7.1,3.6-11.2c-1.3-4.1-5.2-6.9-9.5-6.9
                    h-72.8L422.6,15.8C425.4,11.9,425,6.4,421.5,2.9z"/>
                  </svg>
                </span>
                <span><?php esc_html_e('Featured', 'siberia'); ?></span>
              </div>
            <?php endif;?>
        </figure>
      </a>

      <?php else: ?>

        <?php if ( is_sticky() ) : ?>
          <div class="ms-sticky no-thumbnail">
            <span class="ms-sticky--icon">
              <svg version="1.1" viewBox="0 0 460 460" style="enable-background:new 0 0 460 460;" xml:space="preserve">
                <path d="M421.5,2.9c-3.5-3.5-9-3.9-12.9-1l-303,220c-3.5,2.5-5,7.1-3.6,11.2c1.3,4.1,5.2,6.9,9.5,6.9h72.8L37.4,444.2
                    c-2.9,4-2.4,9.5,1.1,12.9c1.9,1.9,4.5,2.9,7.1,2.9c2,0,4.1-0.6,5.9-1.9l303-220c3.5-2.5,5-7.1,3.6-11.2c-1.3-4.1-5.2-6.9-9.5-6.9
                    h-72.8L422.6,15.8C425.4,11.9,425,6.4,421.5,2.9z"/>
              </svg>
            </span>
            <span><?php esc_html_e('Featured', 'siberia'); ?></span>
          </div>
        <?php endif;?>

    <?php endif; ?>

    <div class="post-content">
      <a href="<?php the_permalink(); ?>">
        <h2><?php the_title(); ?></h2>
      </a>
      <div class="post-meta">
        <div class="post-meta-date">
          <span><?php echo get_the_date(); ?></span>
          <span class="post-category link"><?php the_category(',&nbsp;'); ?></span>
        </div>
      </div>
      <p class="post-excerpt"><?php echo get_the_excerpt(); ?></p>
      <div class="post-footer">
        <span class="post-footer--author">
          <img src="<?php echo get_avatar_url( get_the_author_meta('email'), array("size"=>40)); ?>" alt="<?php echo get_the_author(); ?>">
          <span><?php esc_html_e('By ', 'siberia'); ?><?php echo get_the_author(); ?></span>
        </span>
        <span class="post-footer--link link">
          <a href="<?php the_permalink(); ?>"><?php esc_html_e('Read More', 'siberia'); ?></a>
        </span>
      </div>
    </div>
</article>