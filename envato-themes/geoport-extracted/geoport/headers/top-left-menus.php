<div class="header-top-link">
  <ul>
    <?php foreach ( geoport_get_option('header1_left_list') as $key => $value ) { ?>
      <li>
        <a href="<?php echo esc_url( $value['list_link'] ); ?>">
          <?php if ( ! empty( $value['list_icon'] ) ) { ?><i class="<?php echo esc_attr( $value['list_icon'] ); ?>"></i> <?php } ?>
          <?php echo esc_html__( $value['list_text'] ); ?>
        </a>
      </li>
    <?php } ?>
  </ul>
</div>