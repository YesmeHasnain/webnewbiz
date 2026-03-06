<?php
/**
 * Template part for displaying single posts.
 *
 * @package geoport
 */

if( function_exists( 'geoport_framework_init' ) ) {

  $blog_single_post_admin     = geoport_get_option('blog_single_post_admin');
  $blog_single_post_comments  = geoport_get_option('blog_single_post_comments');
  $blog_single_post_cats      = geoport_get_option('blog_single_post_cats');
  $blog_single_post_date  = geoport_get_option('blog_single_post_date');
  $blog_single_post_tags  = geoport_get_option('blog_single_post_tags');
  $blog_single_rp_switch  = geoport_get_option('blog_single_rp_switch');
  $blog_single_rp_title   = geoport_get_option('blog_single_rp_title');
  $rp_grid_columns        = geoport_get_option('rp_grid_columns');

  $users = "fal fa-user";
  $calendar = "fal fa-calendar-alt";
  $comments = "fal fa-comments";
  $tax_cat = "fal fa-chart-area";
  $readmore = "fal fa-arrow-right";
} else {
  $blog_single_post_admin = 'true';
  $blog_single_post_comments = 'true';
  $blog_single_post_cats = 'true';

  $blog_single_post_date = 'true';
  $blog_single_post_tags = 'true';
  $blog_single_rp_switch = '';
  $rp_grid_columns = '6';
  $blog_single_rp_title = __( 'Releted Post', 'geoport' );

  $users = "dashicons dashicons-admin-users";
  $calendar = "dashicons dashicons-calendar-alt";
  $comments = "dashicons dashicons-admin-comments";
  $tax_cat = "dashicons dashicons-category";
  $readmore = "dashicons dashicons-arrow-right-alt";
}

$tags_list = get_the_tag_list(); 
if( function_exists( 'geoport_framework_init' ) ) {
  $post_share_enable = geoport_get_option('geoport_post_details_share_enable');
  $geoport_post_details_tag_enable = geoport_get_option('geoport_post_details_tag_enable');
} else {
  $post_share_enable = '';
  $geoport_post_details_tag_enable = '';
}

$default_post_metadata = get_post_meta( get_the_ID(), '_geoport_post', true);

if (!empty($default_post_metadata['post_format_type'] )) {
  $post_format_type = $default_post_metadata['post_format_type'];
} else {
  $post_format_type = '';
}

if (!empty($default_post_metadata['video_type'] )) {
  $video_type = $default_post_metadata['video_type'];
} else {
  $video_type = '';
}
if (!empty($default_post_metadata['video_link'] )) {
  $video_link = $default_post_metadata['video_link'];
} else {
  $video_link = '';
}
if (!empty($default_post_metadata['audio_link'] )) {
  $audio_link = $default_post_metadata['audio_link'];
} else {
  $audio_link = '';
}

if (!empty($default_post_metadata['gallery_list'] )) {
  $gallery_list = $default_post_metadata['gallery_list'];
} else {
  $gallery_list = '';
} 

if ($gallery_list) {
    $ids = explode(",",$gallery_list);
} else {
    $ids = '';
} 

?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <div class="bpost-content b-details-content">
    <?php if ($post_format_type == 'geoport-video') { ?>
      <div class="bpost-thumb position-relative">
        <?php the_post_thumbnail(); ?>
        <a href="<?php echo esc_url( $video_link ); ?>" class="video-i popup-video"><i class="fas fa-play"></i></a>
      </div>
    <?php } elseif ($post_format_type == 'geoport-gallery') { ?>
      <div class="bpost-thumb blog-thumb-active">
        <div class="slide-post">
          <?php the_post_thumbnail(); ?>
        </div>
        <?php 
          if (!empty($ids)) {
            foreach ($ids as $key => $value) {
              $src = wp_get_attachment_image_src( $value, "full" ); ?>
              <div class="slide-post">
                <img src="<?php echo esc_url($src[0]); ?>" alt="<?php esc_attr_e( 'post gallery image', 'geoport' ); ?>">
              </div>
        <?php } 
        } ?>
      </div>
    <?php } elseif ($post_format_type == 'geoport-audio') { ?>
      <div class="bpost-thumb embed-responsive embed-responsive-16by9">
        <iframe src="<?php echo esc_url( $audio_link ); ?>"></iframe>
      </div>
    <?php } elseif ($post_format_type == 'geoport-tag') { ?>
    <?php } else { ?>
    <?php if(has_post_thumbnail()) { ?>
      <div class="blog-thumb">
        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
      </div>                                                                                        
    <?php }
    } ?>
    <div class="details-content">
      <div class="bpost-meta mb-20">
        <ul>
          <?php if (!empty( $blog_single_post_admin )) { ?><li class="author"><i class="<?php echo esc_attr( $users ); ?>"></i><span><?php esc_html_e('By ', 'geoport'); ?></span><?php the_author_posts_link(); ?></li><?php } ?>
          <?php if (!empty( $blog_single_post_date )) { ?><li><span><i class="<?php echo esc_attr( $calendar ); ?>"></i><?php echo esc_html ( get_the_date() ); ?></span></li><?php } ?>
          <?php if (!empty( $blog_single_post_cats )) { ?><li><span><i class="<?php echo esc_attr( $tax_cat ); ?>"></i><?php the_category(', '); ?></span></li><?php } ?>
          <?php if (!empty( $blog_single_post_comments )) { ?><li><a><i class="<?php echo esc_attr( $comments ); ?>"></i><?php comments_number( '0 Comments', '1 Comment', '% Comments' ); ?></a></li><?php } ?>
        </ul>
      </div>
      <div class="the-content">
        <?php 
          the_content(); 
          wp_link_pages( array(
            'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'geoport' ),
            'after'  => '</div>',
            'link_before' => '<span>',
            'link_after'  => '</span>',
          ) );
        ?>
      </div>
      <?php if (!empty($tags_list) || !empty($post_share_enable) || !empty($geoport_post_details_tag_enable) ) { ?>
      <div class="post-footer-meta">
        <div class="row">
          <?php
            if (!empty($post_share_enable)) {
              $post_meta_col = '6'; 
              $text_class = 'text-right';
            } else {
              $post_meta_col = '12';
              $text_class = 'text-left';
            }
            if(!empty($tags_list)){
              if ( !empty($geoport_post_details_tag_enable) ) {
          ?>
            <div class="col-xl-<?php echo esc_attr( $post_meta_col ); ?> col-md-7">
              <div class="d-post-tag">
                <h5><?php esc_html_e( 'Tags :', 'geoport' ) ?></h5>
                <?php the_tags( '<ul class="tags-list"><li>', ' </li> <li>', '</li></ul>' ); ?>
              </div>
            </div>
          <?php } } if ( !empty($post_share_enable) ) { ?>
            <div class="col-xl-<?php echo esc_attr( $post_meta_col ); ?> col-md-5">
              <?php do_action( 'geoport_social_share_media' ); ?>
            </div>
          <?php } ?>
        </div>
      </div>
      <?php } ?>
      <?php geoport_post_nav(); ?>
      <?php if (!empty($blog_single_rp_switch)) { ?>
      <div class="releted-post mt-45">
        <h3><?php echo esc_html (  $blog_single_rp_title ) ?></h3>
        <div class="row">
          <?php
            $related = new WP_Query(
              array(
                'category__in'   => wp_get_post_categories( $post->ID ),
                'posts_per_page' => 2,
                'post__not_in'   => array( $post->ID )
              )
            );
            if( $related->have_posts() ) { 
              while( $related->have_posts() ) { 
                $related->the_post(); ?>
                  <div class="col-lg-<?php echo esc_attr( $rp_grid_columns ); ?> col-md-6">
                    <div class="single-rp">
                      <?php if(has_post_thumbnail()) { ?>
                      <div class="rp-thumb">
                        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'geoport-770-460' ); ?></a>
                      </div>
                      <?php } ?>
                      <div class="rp-content">
                        <span class="rp-date"><i class="<?php echo esc_attr( $calendar ); ?>"></i><?php echo esc_html ( get_the_date() ); ?></span>
                        <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                        <p><?php echo geoport_excerpt( 10 ); ?></p>
                      </div>
                    </div>
                  </div>
              <?php }
              wp_reset_postdata();
            }
          ?>
        </div>
      </div>
      <?php } if (get_the_author_meta('description')) : // Checking if the user has added any author descript or not. If it is added only, then lets move ahead ?>
        <div class="avatar-wrap mb-45">
          <div class="avatar-img">
            <?php echo get_avatar(get_the_author_meta('user_email'), '180'); // Display the author gravatar image with the size of 120 ?>
          </div>
          <div class="bd-avatar-info">
            <span><?php echo esc_html_e( 'Written by', 'geoport' ); ?></span>
            <h4><?php esc_html(the_author_meta('display_name')); // Displays the author name of the posts ?></h4>
            <p><?php esc_textarea(the_author_meta('description')); // Displays the author description added in Biographical Info ?></p>
          </div>
        </div>
      <?php endif; ?>
      <!-- blog Comment Section
      ============================== -->
      <?php // If comments are open or we have at least one comment, load up the comment template.
        if ( comments_open() || get_comments_number() ) :
          comments_template();
        endif; 
      ?>
    </div>
  </div>
</div>