<?php
if( function_exists( 'geoport_framework_init' ) ) {
  $geoport_breadcrumb_switch = geoport_get_option('geoport_breadcrumb_switch');
  $sticky_menu_switch        = geoport_get_option('sticky_menu_switch');
  $top_header                = geoport_get_option('top_header1');
} else {
  $geoport_breadcrumb_switch = '';
  $all_default_height = 'all-default-h2';
  $sticky_menu_switch = '';
  $top_header = '';
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

$geoport_header_settings = get_post_meta( get_the_ID(), '_custom_page_header_options', true );
if(!empty($geoport_header_settings['header_style'])) {
  $all_default_height = '';
} elseif(function_exists( 'geoport_framework_init' ) ) {
  $default_header_style = geoport_get_option('default_header_style');
  if($default_header_style == 'style1') {
    $all_default_height = 'all-default-h1';
  } elseif ($default_header_style == 'style2')  {
    $all_default_height = 'all-default-h2';
  } elseif ($default_header_style == 'style3')  {
    $all_default_height = 'all-default-h3';
  } else {
    $all_default_height = 'all-default-h1';
  }
} else {
  $all_default_height = 'all-default-h1';
}

if (!empty($sticky_menu_switch)) {
  $sticky_id = 'header-sticky';
} else {
  $sticky_id = 'header-sticky-none';  
}
if (display_header_text()==true) {
  $auto_class = ' have-site-desc';
} else {
  $auto_class = ' none-site-desc';
} 

$dynamic_classes = $breadcrumb_height.' '.$all_default_height.$auto_class; 

?>
<!-- header-area -->
<header id="<?php echo esc_attr( $sticky_id ); ?>" class="transparent-header header-style-two <?php echo esc_attr( $dynamic_classes ); ?>">
  <?php if (!empty( $top_header )) {
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

    if ( ! empty( geoport_get_option('header1_left_list') ) || ( ! empty( geoport_get_option('header1_right_list') ) || ! empty( geoport_get_option('header1_social_btn') ) ) ) {
    ?>
      <div class="header-top-area <?php echo esc_attr($h1320);?> <?php echo esc_attr($h1480);?> <?php echo esc_attr($h1767);?>">
        <div class="container-fluid header-full-width">
          <div class="row">
            <div class="col-lg-12">
              <?php if ( !empty( geoport_get_option( 'top_header_left_menus' ) ) ) { ?>
                <?php if ( is_array( geoport_get_option( 'header1_left_list' ) ) ) { ?>
                  <div class="header2-top-left-area">
                    <?php get_template_part('headers/top-left-menus'); ?>
                  </div>
                <?php } ?>
              <?php } ?>
              <!-- Top Right Info -->

              <div class="header2-top-right">
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
        </div>
      </div>
    <?php }
    }
    if ( display_header_text()==true ) {
      $auto_class = ' have-site-desc';
    } else {
      $auto_class = ' none-site-desc';
    }
    $dynamic_class =  $breadcrumb_height.$auto_class;
  ?>
  <div class="container-fluid header-full-width <?php echo esc_attr( $dynamic_class ); ?>">
    <div class="menu-area">
      <div class="row">
        <div class="col-lg-12">
          <div class="header2-logo-area">
            <?php get_template_part( 'headers/logo' ); ?>
          </div>
 
          <div class="header2-menu-area">
            <div class="main-menu">
              <nav id="mobile-menu">
                  <?php get_template_part('headers/menu'); ?>
              </nav>
            </div>

            <?php if(function_exists( 'geoport_framework_init' ) ) { ?>
              <?php if ( !empty( geoport_get_option( 'lan1_btn_switch' ) ) || !empty( geoport_get_option( 'menu1_btn_switch' ) ) ) { ?>
                <div class="header2-trackorder-area">
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

<div class="stricky-height-fix header2"></div>