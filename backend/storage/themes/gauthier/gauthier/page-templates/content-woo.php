<?php
/*
 * Content display WOO product
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <header class="entry-header">
    <?php if ( is_single() ) : ?>
    <h1 class="entry-title">
      <?php the_title(); ?>
    </h1>
    <?php else : ?>
    <h2 class="entry-title"> <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( esc_html__( 'Permalink to %s', 'gauthier' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark">
      <?php the_title(); ?>
      </a> </h2>
    <?php endif; // is_single() ?>
  </header>
  <?php if ( is_home() && ( get_theme_mod( 'gauthier_one_full_post' , '1' ) == '1' ) ) : // Check Live Customizer for Full/Excerpts Post Settings ?>
  <?php gauthier_one_excerpts() ?>
  <?php elseif( is_search() || is_category() || is_tag() || is_author() || is_archive()  ): ?>
  <?php gauthier_one_excerpts() ?>
  <?php else : ?>
  <div class="entry-content">
    <?php the_content( esc_html__( 'Continue reading <span class="meta-nav">&rarr;</span>', 'gauthier' ) ); ?>
    <?php wp_link_pages( array( 'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'gauthier' ), 'after' => '</div>' ) ); ?>
  </div>
  <?php endif; ?>
</article>