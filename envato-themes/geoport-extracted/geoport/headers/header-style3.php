<?php
if( function_exists( 'geoport_framework_init' ) ) {
  $geoport_breadcrumb_switch = geoport_get_option('geoport_breadcrumb_switch');
  $top_header                = geoport_get_option('top_header1');
  $sticky_menu_switch        = geoport_get_option('sticky_menu_switch');
} else {
  $geoport_breadcrumb_switch = '';
  $top_header = '';
  $sticky_menu_switch = '';
}
if( function_exists( 'geoport_framework_init' ) ) {
  if ($geoport_breadcrumb_switch == true) {
    $breadcrumb_height = 'breadcrumb_height';
  } else {
    $breadcrumb_height = 'breadcrumb_menu_height';
  }
} else {
  $breadcrumb_height = 'breadcrumb_height';
}

if (!empty($sticky_menu_switch)) {
  $sticky_id = 'header-sticky';
} else {
  $sticky_id = 'header-sticky-none';  
}

?>

<!-- header-area -->
<header id="<?php echo esc_attr( $sticky_id ); ?>" class="transparent-header header3">
  <div class="container-fluid header-full-width">
    <?php if (!empty($top_header)) {
      if ( ! empty( geoport_get_option( 'top_header1_1exsmall_device' ) ) || ( ! empty( geoport_get_option( 'top_header1_2exsmall_device' ) ) || ! empty( geoport_get_option( 'top_header1_3exsmall_device' ) ) ) ) {
        $h1320 = '';
      }else{
        $h1320 = 'h1-320';
      }

      if ( ! empty( geoport_get_option( 'top_header1_1small_device' ) ) || ( ! empty( geoport_get_option( 'top_header1_2small_device' ) ) || ! empty( geoport_get_option( 'top_header1_3small_device' ) ) ) ) {
        $h1480 = '';
      }else{
        $h1480 = 'h1-480';
      }

      if ( ! empty( geoport_get_option( 'top_header1_1medium_device' ) ) || ( ! empty( geoport_get_option( 'top_header1_2medium_device' ) ) || ! empty( geoport_get_option( 'top_header1_3medium_device' ) ) ) ) {
        $h1767 = '';
      }else{
        $h1767 = 'h1-767';
      }

      if ( ! empty( geoport_get_option('header3_left_list') ) || ( ! empty( geoport_get_option('header1_right_list') ) || ! empty( geoport_get_option('header1_social_btn') ) ) ) {
      ?>
      <div class="header-top-area <?php echo esc_attr($h1320);?> <?php echo esc_attr($h1480);?> <?php echo esc_attr($h1767);?>">
          <div class="row">
            <div class="col-lg-12">
              <!-- Top Left Contact Info -->
              <?php if ( !empty( geoport_get_option( 'top_header_left_off' ) ) ) { ?>
                <?php get_template_part('headers/top-left-contact'); ?>
              <?php } ?>

              <div class="header-top-right">
                <!-- Top Right Info -->
                <?php if ( !empty( geoport_get_option( 'top_header_rightlist' ) ) ) { ?>
                  <?php get_template_part('headers/top-right'); ?>
                <?php } ?>

                <!-- Top Right Social -->
                <?php if ( !empty( geoport_get_option( 'top_header_off' ) ) ) { ?>
                  <?php get_template_part('headers/social-profiles'); ?>
                <?php } ?>
              </div>

            </div>
          </div>
      </div>
      <?php }
    } 
    ?>
    <?php
    if ( display_header_text()==true ) {
      $auto_class = ' have-site-desc';
    } else {
      $auto_class = ' none-site-desc';
    }
    $dynamic_class =  $breadcrumb_height.$auto_class;
    ?>
    <div class="menu-area <?php echo esc_attr( $dynamic_class ); ?>">
      <div class="row">
        <div class="col-lg-12">
          <div class="header3-logo-area">
            <?php get_template_part( 'headers/logo' ); ?>
          </div>

          <div class="header3-menu-area">
            <div class="main-menu">
              <nav id="mobile-menu">
                <?php get_template_part('headers/menu'); ?>
              </nav>
            </div>

            <?php if(function_exists( 'geoport_framework_init' ) ) { ?>
              <?php if ( !empty( geoport_get_option( 'lan1_btn_switch' ) ) || !empty( geoport_get_option( 'menu1_btn_switch' ) ) ) { ?>
                <div class="header3-trackorder-area">
                  <div class="header-action">

                    <!-- Menu Language Button -->
                    <?php get_template_part('headers/menu-language-button'); ?>

                    <!-- Menu Quote Button -->
                    <?php get_template_part('headers/menu-quote-button'); ?>

                  </div>
                </div>
              <?php } ?>
            <?php } ?>
          </div>

          <div class="col-12 <?php echo esc_attr( $auto_class ); ?>">
            <div class="mobile-menu"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>
<!-- header-area-end -->