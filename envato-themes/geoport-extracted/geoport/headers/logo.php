<?php
if (is_home() && !is_front_page() || is_home() && is_front_page()){
  $page_custom_logo_data = get_post_meta( get_queried_object_id(), '_custom_page_options', true );
  if (!empty($page_custom_logo_data['page_custom_logo_switch'])) {
      $page_custom_logo_switch = $page_custom_logo_data['page_custom_logo_switch'];
  } else {
      $page_custom_logo_switch = '';
  }
  if (!empty($page_custom_logo_data['page_custom_logo_upload'])) {
      $custom_img_id  = $page_custom_logo_data['page_custom_logo_upload'];
      $attachment = wp_get_attachment_image_src( $custom_img_id, 'full' );
      $custom_page_logo     = ($attachment) ? $attachment[0] : $custom_img_id;
  }
  if (!empty($page_custom_logo_data['page_custom_sticky_logo_upload'])) {
      $custom_img_id  = $page_custom_logo_data['page_custom_sticky_logo_upload'];
      $attachment = wp_get_attachment_image_src( $custom_img_id, 'full' );
      $page_custom_sticky_logo_upload     = ($attachment) ? $attachment[0] : $custom_img_id;
  }
} else {
  $page_custom_logo_data = get_post_meta( get_queried_object_id(), '_custom_page_options', true );
  if (!empty($page_custom_logo_data['page_custom_logo_switch'])) {
      $page_custom_logo_switch = $page_custom_logo_data['page_custom_logo_switch'];
  } else {
      $page_custom_logo_switch = '';
  }
  if (!empty($page_custom_logo_data['page_custom_logo_upload'])) {
      $custom_img_id  = $page_custom_logo_data['page_custom_logo_upload'];
      $attachment = wp_get_attachment_image_src( $custom_img_id, 'full' );
      $custom_page_logo     = ($attachment) ? $attachment[0] : $custom_img_id;
  }
  if (!empty($page_custom_logo_data['page_custom_sticky_logo_upload'])) {
      $custom_img_id  = $page_custom_logo_data['page_custom_sticky_logo_upload'];
      $attachment = wp_get_attachment_image_src( $custom_img_id, 'full' );
      $page_custom_sticky_logo_upload     = ($attachment) ? $attachment[0] : $custom_img_id;
  }
}
?>

<div class="logo">
  <?php
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    $logo           = wp_get_attachment_image_src( $custom_logo_id , 'full' );
    if ( has_custom_logo() ) {
      echo '<a href="'.esc_url(home_url('/')).'" class="brand-logo"><img src="'. esc_url( $logo[0] ) .'" alt="'.esc_attr__( 'Geoport logo', 'geoport' ).'"></a>';
    } elseif(function_exists( 'geoport_framework_init' ) ) {
      if ( $page_custom_logo_switch == 1 && !empty( $custom_page_logo ) ){
        echo'<a href="'.esc_url(home_url('/')).'" class="navbar-brand-logo brand-logo no-sticky"><img alt="'.esc_attr__( 'Chariton logo', 'geoport' ).'" src="'.esc_url( $custom_page_logo ).'"></a>';
        if ( !empty( $page_custom_sticky_logo_upload ) ) {
          echo'<a href="'.esc_url( home_url('/') ).'" class="yes-sticky"><img alt="'.esc_attr__( 'Chariton logo', 'geoport' ).'" src="'.esc_url( $page_custom_sticky_logo_upload ).'"></a>';
        } else {
           echo'<a href="'.esc_url( home_url('/') ).'" class="navbar-brand-logo brand-logo yes-sticky"><img alt="'.esc_attr__( 'Chariton logo', 'geoport' ).'" src="'.esc_url( $custom_page_logo ).'"></a>';
        }
      }elseif( geoport_get_option( 'geoport_logo1_img' ) ){
        $site_logo_id        = geoport_get_option('geoport_logo1_img');
        $attachment          = wp_get_attachment_image_src( $site_logo_id, 'full' );
        $site_logo           = ($attachment) ? $attachment[0] : $site_logo_id;

        $site_sticky_logo_id = geoport_get_option( 'geoport_logo1_sticky' );
        $sticky_attachment   = wp_get_attachment_image_src( $site_sticky_logo_id, 'full' );
        $site_sticky_logo    = ( $sticky_attachment ) ? $sticky_attachment[0] : $site_sticky_logo_id;

        if ( !empty( $site_logo ) ) {
          echo'<a href="'.esc_url(home_url('/')).'" class="navbar-brand-logo brand-logo no-sticky"><img alt="'.esc_attr__( 'Geoport logo', 'geoport' ).'" src="'.esc_url( $site_logo ).'"></a>';
          if ( !empty( $site_sticky_logo ) ) {
            echo'<a href="'.esc_url( home_url('/') ).'" class="yes-sticky"><img alt="'.esc_attr__( 'Geoport logo', 'geoport' ).'" src="'.esc_url( $site_sticky_logo ).'"></a>';
          } else {
             echo'<a href="'.esc_url(home_url('/')).'" class="navbar-brand-logo brand-logo yes-sticky"><img alt="'.esc_attr__( 'Geoport logo', 'geoport' ).'" src="'.esc_url( $site_logo ).'"></a>';
          }
        }else{
          echo '<div class="default-logo"><a href="'.esc_url(home_url('/')).'" class="navbar-brand-logo brand-logo">'. get_bloginfo( 'name' ) .'</a></div>';
        }
      }else{
        echo '<div class="default-logo"><a href="'.esc_url(home_url('/')).'" class="navbar-brand-logo brand-logo">'. get_bloginfo( 'name' ) .'</a></div>';
      }
    } else {
      echo '<div class="default-logo"><a href="'.esc_url(home_url('/')).'" class="navbar-brand-logo brand-logo">'. get_bloginfo( 'name' ) .'</a></div>';
    }
  ?>
  <?php
    if ( display_header_text() == true ) {
      $description = get_bloginfo( 'description', 'display' );
      if ( $description || is_customize_preview() ) : ?>
        <div class="site-description"><?php echo esc_attr( $description ); ?></div>
      <?php endif;
    }
  ?>
</div>