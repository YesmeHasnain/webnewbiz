<?php if ( !empty( geoport_get_option( 'menu1_btn_switch' ) ) ) { ?>
  <div class="header-btn">
    <a href="<?php echo esc_url( geoport_get_option( 'menu1_btn_link' ) ); ?>" target="<?php echo esc_attr( geoport_get_option('menu1_target_link') ); ?>" class="btn transparent-btn">
      <?php
        if ( !empty( geoport_get_option( 'menu1_btn_icon' ) ) ) {
          $btn_icon = '<i class="'.esc_attr( geoport_get_option( 'menu1_btn_icon' ) ).'"></i>';
        } else {
          $btn_icon = '';
        }
        echo wp_kses_stripslashes( $btn_icon );
        
        // Ensure the text is translatable
        $menu1_btn_text = geoport_get_option( 'menu1_btn_text' );
        echo esc_html__( $menu1_btn_text, 'geoport' );

      ?>
    </a>
  </div>
<?php } ?>