<?php if ( is_array( geoport_get_option('header1_social_btn') ) ) { ?>
  <div class="header-social">
    <?php foreach ( geoport_get_option('header1_social_btn') as $key => $value ) { ?>
      <a href="<?php echo esc_url( $value['social_link'] ); ?>"><i class="<?php echo esc_attr( $value['social_icon'] ); ?>"></i></a>
    <?php } ?>
  </div>
<?php } ?>