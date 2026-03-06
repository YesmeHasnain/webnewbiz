<?php
/**
 * Template part for displaying posts.
 *
 * @package geoport
 */

if( function_exists( 'geoport_framework_init' ) ) {

  $content_excerpt        = geoport_get_option('geoport_post_excerpt_length');
  $blog_post_col_layout   = geoport_get_option('blog_post_col_layout');
  $blog_post_date         = geoport_get_option('blog_post_date');
  $blog_post_views        = geoport_get_option('blog_post_views');
  $blog_post_comments     = geoport_get_option('blog_post_comments');
  $blog_post_admin        = geoport_get_option('blog_post_admin');
  $blog_post_cats_admin   = geoport_get_option('blog_post_cats_admin');
  $blog_post_readm_admin  = geoport_get_option('blog_post_readm_admin');

  if ( $blog_post_col_layout == 'col_2' ) {
    $col_layout = '6';
  } elseif ( $blog_post_col_layout == 'col_3' ) {
    $col_layout = '4';
  } elseif ( $blog_post_col_layout == 'col_4' ) {
    $col_layout = '3';
  } else {
    $col_layout = '12';
    $post_title_class = 'big-title';
  }
  $calendar = "fal fa-calendar-alt";
  $comments = "fal fa-comments";
  $readmore = "fal fa-arrow-right";
} else {
  $col_layout = '12';
  $blog_post_date = 'true';
  $blog_post_views = 'true';
  $blog_post_comments = 'true';
  $blog_post_admin = 'true';
  $blog_post_cats_admin = 'true';
  $blog_post_readm_admin = 'true';
  $post_title_class = 'big-title';
  $content_excerpt = '50';
  $calendar = "dashicons dashicons-calendar-alt";
  $comments = "dashicons dashicons-admin-comments";
  $readmore = "dashicons dashicons-arrow-right-alt";
}

if ($blog_post_readm_admin == 'true'){
  $readmore_none = '';
}else{
  $readmore_none = 'readmore-is-hidden';  
}

if ($col_layout == '6') {
  $crop_img = 'geoport-770-460';
} elseif ($col_layout == '4') {
  $crop_img = 'geoport-770-460';
} elseif ($col_layout == '3') {
  $crop_img = 'geoport-770-460';
} else {
  $crop_img = 'full';
}

$post_title = get_the_title();
$post_content = get_the_content();

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
if (!empty($default_post_metadata['geoport_quote_icon'] )) {
  $geoport_quote_icon = $default_post_metadata['geoport_quote_icon'];
} else {
  $geoport_quote_icon = '';
}  

if (!empty($default_post_metadata['gallery_list'] )) {
  $gallery_list = $default_post_metadata['gallery_list'];
} else {
  $gallery_list = '';
} 

if ($gallery_list) {
  $ids = explode(",", $gallery_list);
} else {
  $ids = '';
} 

?>

<div class="col-lg-<?php echo esc_attr( $col_layout ); ?>">
  <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="bsingle-post mb-30">
      <?php if ($post_format_type == 'geoport-video') { ?>
        <div class="bpost-thumb position-relative">
          <?php the_post_thumbnail( $crop_img ); ?>
          <a href="<?php echo esc_url( $video_link ); ?>" class="video-i popup-video"><i class="fas fa-play"></i></a>
        </div>
        <div class="bpost-content <?php echo esc_attr($readmore_none); ?>">
          <?php if ( $blog_post_cats_admin == 'true') { ?>
            <div class="b-tag">
              <?php the_category(' '); ?>
            </div>
          <?php } ?>
          <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
          <div class="bpost-meta mb-20">
            <ul>
              <?php if (!empty($blog_post_date)) { ?>
                <li><span><i class="<?php echo esc_attr(  $calendar ); ?>"></i><?php echo esc_html ( get_the_date() ); ?></span></li>
              <?php } if (!empty($blog_post_comments)) { ?>
                <li><a href="<?php the_permalink(); ?>"><i class="<?php echo esc_attr( $comments ); ?>"></i><?php comments_number( '0 Comments', '1 Comment', '% Comments' ); ?></a></li>
              <?php } ?>
            </ul>
          </div>
          <p><?php echo geoport_excerpt( $content_excerpt ); ?></p>
          <?php if ( $blog_post_admin == 'true') { ?>
            <div class="bpost-avatar">
              <div class="bavatar-img">
                <?php echo get_avatar( get_the_author_meta('email'), '40' ); ?>
              </div>
              <div class="bavatar-info">
                <p><?php esc_html_e('By ', 'geoport'); ?><?php the_author_posts_link(); ?></p>
              </div>
            </div>
          <?php } ?>
          <?php if( $blog_post_readm_admin == 'true'){ ?>
            <div class="b-readmore">
              <a href="<?php the_permalink(); ?>"><i class="fal fa-arrow-right"></i><?php esc_html_e( 'Read More', 'geoport' ); ?></a>
            </div>
          <?php } ?>
        </div>
      <?php } elseif ($post_format_type == 'geoport-gallery') { ?>
        <div class="bpost-thumb blog-thumb-active">
          <div class="slide-post">
            <?php the_post_thumbnail( $crop_img ); ?>
          </div>
          <?php 
            if (!empty($ids)) {
              foreach ($ids as $key => $value) {
                $src = wp_get_attachment_image_src( $value, "geoport-770-460" ); ?>
          <div class="slide-post">
              <img src="<?php echo esc_url($src[0]); ?>" alt="<?php esc_attr_e( 'post gallery image', 'geoport' ); ?>">
          </div>
          <?php } 
          } ?>
        </div>
        <div class="bpost-content <?php echo esc_attr($readmore_none); ?>">
          <?php if ( $blog_post_cats_admin == 'true') { ?>
            <div class="b-tag">
              <?php the_category(' '); ?>
            </div>
          <?php } ?>
          <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
          <div class="bpost-meta mb-20">
            <ul>
              <?php if (!empty($blog_post_date)) { ?>
                <li><span><i class="<?php echo esc_attr(  $calendar ); ?>"></i><?php echo esc_html ( get_the_date() ); ?></span></li>
              <?php } if (!empty($blog_post_comments)) { ?>
                <li><a href="<?php the_permalink(); ?>"><i class="<?php echo esc_attr( $comments ); ?>"></i><?php comments_number( '0 Comments', '1 Comment', '% Comments' ); ?></a></li>
              <?php } ?>
            </ul>
          </div>
          <p><?php echo geoport_excerpt( $content_excerpt ); ?></p>
          <?php if ( $blog_post_admin == 'true') { ?>
            <div class="bpost-avatar">
              <div class="bavatar-img">
                <?php echo get_avatar( get_the_author_meta('email'), '40' ); ?>
              </div>
              <div class="bavatar-info">
                <p><?php esc_html_e('By ', 'geoport'); ?><?php the_author_posts_link(); ?></p>
              </div>
            </div>
          <?php } ?>
          <?php if( $blog_post_readm_admin == 'true'){ ?>
            <div class="b-readmore">
              <a href="<?php the_permalink(); ?>"><i class="fal fa-arrow-right"></i><?php esc_html_e( 'Read More', 'geoport' ); ?></a>
            </div>
          <?php } ?>
        </div>
      <?php } elseif ($post_format_type == 'geoport-audio') { ?>
        <div class="bpost-thumb embed-responsive embed-responsive-16by9">
            <iframe src="<?php echo esc_url( $audio_link ); ?>"></iframe>
        </div>
        <div class="bpost-content <?php echo esc_attr($readmore_none); ?>">
          <?php if ( $blog_post_cats_admin == 'true') { ?>
            <div class="b-tag">
              <?php the_category(' '); ?>
            </div>
          <?php } ?>
          <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
          <div class="bpost-meta mb-20">
            <ul>
              <?php if (!empty($blog_post_date)) { ?>
                <li><span><i class="<?php echo esc_attr(  $calendar ); ?>"></i><?php echo esc_html ( get_the_date() ); ?></span></li>
              <?php } if (!empty($blog_post_comments)) { ?>
                <li><a href="<?php the_permalink(); ?>"><i class="<?php echo esc_attr( $comments ); ?>"></i><?php comments_number( '0 Comments', '1 Comment', '% Comments' ); ?></a></li>
              <?php } ?>
            </ul>
          </div>
          <p><?php echo geoport_excerpt( $content_excerpt ); ?></p>
          <?php if ( $blog_post_admin == 'true') { ?>
            <div class="bpost-avatar">
              <div class="bavatar-img">
                <?php echo get_avatar( get_the_author_meta('email'), '40' ); ?>
              </div>
              <div class="bavatar-info">
                <p><?php esc_html_e('By ', 'geoport'); ?><?php the_author_posts_link(); ?></p>
              </div>
            </div>
          <?php } ?>
          <?php if( $blog_post_readm_admin == 'true'){ ?>
            <div class="b-readmore">
              <a href="<?php the_permalink(); ?>"><i class="fal fa-arrow-right"></i><?php esc_html_e( 'Read More', 'geoport' ); ?></a>
            </div>
          <?php } ?>
        </div>
      <?php } elseif ($post_format_type == 'geoport-quote') { 
        $src = get_the_post_thumbnail_url();
        $attachment = wp_get_attachment_image_src( $geoport_quote_icon, 'full' );
        $icon_img    = ($attachment) ? $attachment[0] : $geoport_quote_icon;
      ?>
        <div class="bpost-content quote-post" data-background="<?php echo esc_url( $src ); ?>">
          <div class="quote-icon">
            <img src="<?php echo esc_url( $icon_img ); ?>" alt="<?php esc_html_e( 'quote icon', 'geoport' ); ?>">
          </div>
          <div class="fix">
            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <div class="bpost-meta">
              <ul>
                <?php if (!empty($blog_post_date)) { ?>
                  <li><span><i class="<?php echo esc_attr(  $calendar ); ?>"></i><?php echo esc_html ( get_the_date() ); ?></span></li>
                <?php } if (!empty($blog_post_comments)) { ?>
                  <li><a href="<?php the_permalink(); ?>"><i class="<?php echo esc_attr( $comments ); ?>"></i><?php comments_number( '0 Comments', '1 Comment', '% Comments' ); ?></a></li>
                <?php } ?>
              </ul>
            </div>
          </div>
        </div>
      <?php } else { ?>
        <?php if(has_post_thumbnail()) { ?>
          <div class="blog-thumb">
            <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( $crop_img ); ?></a>
          </div>                                                                                        
        <?php } ?>
        <div class="bpost-content <?php echo esc_attr($readmore_none); ?>">
          <?php if ( $blog_post_cats_admin == 'true') { ?>
            <div class="b-tag">
              <?php the_category(' '); ?>
            </div>
          <?php } ?>
          <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
          <div class="bpost-meta mb-20">
            <ul>
              <?php if (!empty($blog_post_date)) { ?>
                <li><span><i class="<?php echo esc_attr(  $calendar ); ?>"></i><?php echo esc_html ( get_the_date() ); ?></span></li>
              <?php } if (!empty($blog_post_comments)) { ?>
                <li><a href="<?php the_permalink(); ?>"><i class="<?php echo esc_attr( $comments ); ?>"></i><?php comments_number( '0 Comments', '1 Comment', '% Comments' ); ?></a></li>
              <?php } ?>
            </ul>
          </div>
          <p><?php echo geoport_excerpt( $content_excerpt ); ?></p>
          <?php if ( $blog_post_admin == 'true') { ?>
            <div class="bpost-avatar">
              <div class="bavatar-img">
                <?php echo get_avatar( get_the_author_meta('email'), '40' ); ?>
              </div>
              <div class="bavatar-info">
                <p><?php esc_html_e('By ', 'geoport'); ?><?php the_author_posts_link(); ?></p>
              </div>
            </div>
          <?php } ?>
          <?php if( $blog_post_readm_admin == 'true'){ ?>
            <div class="b-readmore">
              <a href="<?php the_permalink(); ?>"><i class="fal fa-arrow-right"></i><?php esc_html_e( 'Read More', 'geoport' ); ?></a>
            </div>
          <?php } ?>
        </div>
      <?php } ?>
      <?php if ( is_sticky() ) {
        echo '<sup class="meta-featured-post"> <i class="fal fa-thumbtack"></i> ' . esc_html__( 'Sticky', 'geoport' ) . ' </sup>';
      } ?>
    </div>
  </article>
</div>