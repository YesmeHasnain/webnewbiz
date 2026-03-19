<?php 
/**
 * @author: MadSparrow
 * @version: 1.0
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) { exit( 'Direct script access denied.' ); } ?>

<article id="post-<?php the_ID(); ?>" <?php post_class('grid-item'); ?>>
  <a href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">

    <div class="post-meta-date">
      <span class="post__date"><?php echo get_the_date(); ?></span>
    </div>
    
    <div class="post-content">
      
      <h3><?php the_title(); ?></h3>

      <p class="post-excerpt"><?php echo siberia_get_excerpt(get_the_ID(),'110'); ?></p>
      
    </div>

    <?php if ( has_post_thumbnail() ) : ?>
        <div class="ms-pl--thumb">
          <figure class="media-wrapper media-wrapper--16:9">          
            <img src="<?php the_post_thumbnail_url(); ?>" alt="<?php the_title_attribute() ?>">

            <?php if ( is_sticky() ) : ?>

              <figcaption class="bl sticky">

              </figcaption>

            <?php endif;?>

          </figure>
        </div>
    <?php else: ?>

      <?php if ( is_sticky() ) : ?>

          <figcaption class="bs sticky-no">

          </figcaption>

      <?php endif;?>

    <?php endif; ?>

    <div class="post-ai">
      <svg class="x-nsx icon x-vy x-mo" viewBox="0 0 48 48">
        <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
          <line x1="37" y1="14" x2="47" y2="24"></line>
          <line x1="47" y1="24" x2="37" y2="34"></line>
          <line x1="47" y1="24" x2="1.5" y2="24"></line>
        </g>
      </svg>
    </div>

  </a>

</article>