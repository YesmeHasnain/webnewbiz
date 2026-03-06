<?php if ( is_array( geoport_get_option('header3_left_list') ) ) { ?>
  <div class="header3-top-left-area">
    <div class="header-top-link">
      <ul>
        <?php foreach ( geoport_get_option('header3_left_list') as $key => $value ) {
          $data_type = $value['list_link'];
          if(filter_var($data_type, FILTER_VALIDATE_EMAIL)){
            $href_value = 'email';
          } elseif ( preg_match('/^[0-9\-\(\)\/\+\s]*$/', $data_type ) ) {
            $href_value = 'phone';
          } elseif (filter_var($data_type, FILTER_VALIDATE_URL)) {
            $href_value = 'url';
          } else {
            $href_value = '';
          }
          ?>
          <li>
            <?php if (!empty($href_value == 'email')) { ?>
            <a href="mailto:<?php echo sanitize_email( $data_type ); ?>">
            <?php } elseif (!empty( $href_value == 'phone' )) { 
              $phone_no = str_replace(" ", "", $data_type);
            ?>
             <a href="tel:<?php echo esc_attr($phone_no); ?>">
            <?php } else { ?>
              <a href="<?php echo esc_url( $data_type ); ?>">
            <?php } if ( ! empty( $value['list_icon'] ) ) { ?>
              <i class="<?php echo esc_attr( $value['list_icon'] ); ?>"></i>
              <?php } ?>
              <?php echo esc_attr( $value['list_text'] ); ?>
            </a>
          </li>
        <?php } ?>
      </ul>
    </div>
  </div>
<?php } ?>