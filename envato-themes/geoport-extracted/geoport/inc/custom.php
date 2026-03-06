<?php
/*
dynamic css file. please don't edit it. it's update automatically when settins changed
*/
add_action('wp_head', 'geoport_main_custom_colors', 160);
function geoport_main_custom_colors() {

	// styling options
  $menu1_bg_color = geoport_get_option( 'menu1_bg_color' );
  if (!empty($menu1_bg_color)) {
    $menu1_bg_color = $menu1_bg_color;
  } else {
    $menu1_bg_color = 'transparent';
  }
  $menu1_font_color = geoport_get_option( 'menu1_font_color' );
  if (!empty($menu1_font_color)) {
    $menu1_font_color = $menu1_font_color;
  } else {
    $menu1_font_color = '#ffffff';
  }
  $menu1_hover_font_color = geoport_get_option( 'menu1_hover_font_color' );
  if (!empty($menu1_hover_font_color)) {
    $menu1_hover_font_color = $menu1_hover_font_color;
  } else {
    $menu1_hover_font_color = '#39bdb2';
  }
  $menu1_active_font_color = geoport_get_option( 'menu1_active_font_color' );
  if (!empty($menu1_active_font_color)) {
    $menu1_active_font_color = $menu1_active_font_color;
  } else {
    $menu1_active_font_color = '#ff5e14';
  }
  $menu_font_size       = geoport_get_option('menu_font_size');
  if (!empty($menu_font_size)) {
    $menu_font_size = $menu_font_size;
  } else {
    $menu_font_size = '16';
  }
  $menu_font_weight       = geoport_get_option('menu_font_weight');
  if (!empty($menu_font_weight)) {
    $menu_font_weight = $menu_font_weight;
  } else {
    $menu_font_weight = '700';
  }
  $menu_text_transform = geoport_get_option('menu_text_transform');
  if (!empty($menu_text_transform)) {
    $menu_text_transform = $menu_text_transform;
  } else {
    $menu_text_transform = 'capitalize';
  }
  $submenu1_bg_color = geoport_get_option( 'submenu1_bg_color' );
  if (!empty($submenu1_bg_color)) {
    $submenu1_bg_color = $submenu1_bg_color;
  } else {
    $submenu1_bg_color = '#ffffff';
  }
  $submenu1_color = geoport_get_option( 'submenu1_color' );
  if (!empty($submenu1_color)) {
    $submenu1_color = $submenu1_color;
  } else {
    $submenu1_color = '#000d38';
  }
  $menu_drop_transform  = geoport_get_option('menu_drop_transform');
  if (!empty($menu_drop_transform)) {
    $menu_drop_transform  = $menu_drop_transform;
  } else {
    $menu_drop_transform  = 'capitalize';
  }
  $drops1_menus_hover_bg_color  = geoport_get_option('drops1_menus_hover_bg_color');
  if ( !empty( $drops1_menus_hover_bg_color ) ) {
    $drops1_menus_hover_bg_color = $drops1_menus_hover_bg_color;
  } else {
    $drops1_menus_hover_bg_color = '#ffffff';
  }
  $submenu1_hover_color = geoport_get_option( 'submenu1_hover_color' );
  if (!empty($submenu1_hover_color)) {
    $submenu1_hover_color = $submenu1_hover_color;
  } else {
    $submenu1_hover_color = '#39bdb2';
  }
  $submenu1_border_color = geoport_get_option( 'submenu1_border_color' );
  if (!empty($submenu1_border_color)) {
    $submenu1_border_color = $submenu1_border_color;
  } else {
    $submenu1_border_color = '#eceef0';
  }
  $menu1_sticky_menu_bg_color = geoport_get_option( 'menu1_sticky_menu_bg_color' );
  if (!empty($menu1_sticky_menu_bg_color)) {
    $menu1_sticky_menu_bg_color = $menu1_sticky_menu_bg_color;
  } else {
    $menu1_sticky_menu_bg_color = '#ffffff';
  }
  $sticky_menu1_font_color = geoport_get_option( 'sticky_menu1_font_color' );
  if (!empty($sticky_menu1_font_color)) {
    $sticky_menu1_font_color = $sticky_menu1_font_color;
  } else {
    $sticky_menu1_font_color = '#568ea5';
  }
  $sticky_menu1_hover_font_color = geoport_get_option( 'sticky_menu1_hover_font_color' );
  if (!empty($sticky_menu1_hover_font_color)) {
    $sticky_menu1_hover_font_color = $sticky_menu1_hover_font_color;
  } else {
    $sticky_menu1_hover_font_color = '#ff5e14';
  }
  $sticky_submenu1_bg_color = geoport_get_option( 'sticky_submenu1_bg_color' );
  if (!empty($sticky_submenu1_bg_color)) {
    $sticky_submenu1_bg_color = $sticky_submenu1_bg_color;
  } else {
    $sticky_submenu1_bg_color = '#ffffff';
  }
  $sticky_submenu1_color = geoport_get_option( 'sticky_submenu1_color' );
  if (!empty($sticky_submenu1_color)) {
    $sticky_submenu1_color = $sticky_submenu1_color;
  } else {
    $sticky_submenu1_color = '#568ea5';
  }
  $sticky_drops1_menus_hover_bg_color = geoport_get_option( 'sticky_drops1_menus_hover_bg_color' );
  if (!empty($sticky_drops1_menus_hover_bg_color)) {
    $sticky_drops1_menus_hover_bg_color = $sticky_drops1_menus_hover_bg_color;
  } else {
    $sticky_drops1_menus_hover_bg_color = '#ffffff';
  }
  $sticky_submenu1_hover_color = geoport_get_option( 'sticky_submenu1_hover_color' );
  if (!empty($sticky_submenu1_hover_color)) {
    $sticky_submenu1_hover_color = $sticky_submenu1_hover_color;
  } else {
    $sticky_submenu1_hover_color = '#ff5e14';
  }
  $sticky_submenu1_border_color = geoport_get_option( 'sticky_submenu1_border_color' );
  if (!empty($sticky_submenu1_border_color)) {
    $sticky_submenu1_border_color = $sticky_submenu1_border_color;
  } else {
    $sticky_submenu1_border_color = '#eceef0';
  }
  $menu1_btn_bg_color = geoport_get_option( 'menu1_btn_bg_color' );
  if (!empty($menu1_btn_bg_color)) {
    $menu1_btn_bg_color = $menu1_btn_bg_color;
  } else {
    $menu1_btn_bg_color = 'transparent';
  }
  $menu1_btn_font_color = geoport_get_option( 'menu1_btn_font_color' );
  if (!empty($menu1_btn_font_color)) {
    $menu1_btn_font_color = $menu1_btn_font_color;
  } else {
    $menu1_btn_font_color = '#ffffff';
  }
  $menu1_btn_border_color = geoport_get_option( 'menu1_btn_border_color' );
  if (!empty($menu1_btn_border_color)) {
    $menu1_btn_border_color = $menu1_btn_border_color;
  } else {
    $menu1_btn_border_color = 'rgba(255, 255, 255, 0.3)';
  }
  $menu1_btn_hf_color = geoport_get_option( 'menu1_btn_hf_color' );
  if (!empty($menu1_btn_hf_color)) {
    $menu1_btn_hf_color = $menu1_btn_hf_color;
  } else {
    $menu1_btn_hf_color = '#ffffff';
  }
  $menu1_btn_hb_color = geoport_get_option( 'menu1_btn_hb_color' );
  if (!empty($menu1_btn_hb_color)) {
    $menu1_btn_hb_color = $menu1_btn_hb_color;
  } else {
    $menu1_btn_hb_color = '#ff5e14';
  }
  $menu1_btn_hb_boxshadow_color = geoport_get_option( 'menu1_btn_hb_boxshadow_color' );
  if (!empty($menu1_btn_hb_boxshadow_color)) {
    $menu1_btn_hb_boxshadow_color = $menu1_btn_hb_boxshadow_color;
  } else {
    $menu1_btn_hb_boxshadow_color = 'rgba(255, 94, 20, 0.4)';
  }
  $menu1_stickybtn_font_color = geoport_get_option( 'menu1_stickybtn_font_color' );
  if (!empty($menu1_stickybtn_font_color)) {
    $menu1_stickybtn_font_color = $menu1_stickybtn_font_color;
  } else {
    $menu1_stickybtn_font_color = '#568ea5';
  }
  $menu1_stickybtn_hbg_color = geoport_get_option( 'menu1_stickybtn_hbg_color' );
  if (!empty($menu1_stickybtn_hbg_color)) {
    $menu1_stickybtn_hbg_color = $menu1_stickybtn_hbg_color;
  } else {
    $menu1_stickybtn_hbg_color = '#568ea5';
  }
  $h1top_bg_color = geoport_get_option( 'h1top_bg_color' );
  if ( !empty($h1top_bg_color )) {
    $h1top_bg_color = $h1top_bg_color;
  } else {
    $h1top_bg_color = 'transparent';
  }
  $h1top_font_color = geoport_get_option( 'h1top_font_color' );
  if (!empty($h1top_font_color)) {
    $h1top_font_color = $h1top_font_color;
  } else {
    $h1top_font_color = '#ffffff';
  }
  $h1top_hover_font_color = geoport_get_option( 'h1top_hover_font_color' );
  if (!empty($h1top_hover_font_color)) {
    $h1top_hover_font_color = $h1top_hover_font_color;
  } else {
    $h1top_hover_font_color = '#ff5e14';
  }
  $h1top_border_color = geoport_get_option( 'h1top_border_color' );
  if (!empty($h1top_border_color)) {
    $h1top_border_color = $h1top_border_color;
  } else {
    $h1top_border_color = 'rgba(255,255,255,.2)';
  }
  $responsive_menu1_bacg_color = geoport_get_option( 'responsive_menu1_bacg_color' );
  if (!empty($responsive_menu1_bacg_color)) {
    $responsive_menu1_bacg_color = $responsive_menu1_bacg_color;
  } else {
    $responsive_menu1_bacg_color = '#001d67';
  }
  $responsive_menu1_bgtext_color = geoport_get_option( 'responsive_menu1_bgtext_color' );
  if (!empty($responsive_menu1_bgtext_color)) {
    $responsive_menu1_bgtext_color = $responsive_menu1_bgtext_color;
  } else {
    $responsive_menu1_bgtext_color = '#ffffff';
  }
  $responsive_menu1_icon_color = geoport_get_option( 'responsive_menu1_icon_color' );
  if (!empty($responsive_menu1_icon_color)) {
    $responsive_menu1_icon_color = $responsive_menu1_icon_color;
  } else {
    $responsive_menu1_icon_color = '#ffffff';
  }
  $stickyresponsive_menu1_icon_color = geoport_get_option( 'stickyresponsive_menu1_icon_color' );
  if (!empty($stickyresponsive_menu1_icon_color)) {
    $stickyresponsive_menu1_icon_color = $stickyresponsive_menu1_icon_color;
  } else {
    $stickyresponsive_menu1_icon_color = '#001d67';
  }
  $lan1_btn_sitem_color = geoport_get_option( 'lan1_btn_sitem_color' );
  if (!empty($lan1_btn_sitem_color)) {
    $lan1_btn_sitem_color = $lan1_btn_sitem_color;
  } else {
    $lan1_btn_sitem_color = '#ded6d6';
  }

	if( function_exists( 'geoport_framework_init' ) ) { ?>
		<style>
      .transparent-header.header-style-two{
        background: <?php echo esc_attr($menu1_bg_color); ?>;
      }
      .main-menu ul li > a{
        text-transform: <?php echo esc_attr($menu_text_transform); ?>;
        color: <?php echo esc_attr($menu1_font_color); ?>;
        font-size: <?php echo esc_attr($menu_font_size); ?>;
        font-weight: <?php echo esc_attr($menu_font_weight); ?>;
      }
      .main-menu ul li:hover a,
      .main-menu ul li > a:hover{
        color: <?php echo esc_attr($menu1_hover_font_color); ?>;
      }
      .menu-area ul li > .submenu li.current-menu-item>a,
      .menu-area ul li.current-menu-ancestor>a,
      .menu-area ul li.current-menu-item>a,
      .menu-area ul li.current-menu-item>a,
      .menu-area > nav > ul > li:hover > a,
      .menu-area ul li.active>a{
        color: <?php echo esc_attr($menu1_hover_font_color); ?>;
      }
      .main-menu ul li > .submenu{
        background-color: <?php echo esc_attr($submenu1_bg_color); ?>;
      }
      .main-menu ul li > .submenu li a{
        color: <?php echo esc_attr($submenu1_color); ?>;
        text-transform: <?php echo esc_attr($menu_drop_transform); ?>;
      }
      .main-menu ul li .submenu > li:hover > a{
        color: <?php echo esc_attr($submenu1_hover_color); ?>;
      }
      .main-menu ul ul a:hover,
      .main-menu ul ul ul a:hover{
        background-color: <?php echo esc_attr($drops1_menus_hover_bg_color); ?>;
      }
      .transparent-header.header-style-two.sticky-header,
      .sticky-header{
        background-color: <?php echo esc_attr($menu1_sticky_menu_bg_color); ?>;
      }
      .sticky-header .main-menu ul li > a{
        color: <?php echo esc_attr($sticky_menu1_font_color); ?>;
      }
      .sticky-header .main-menu ul li:hover a,
      .sticky-header .main-menu ul li > a:hover{
        color: <?php echo esc_attr($sticky_menu1_hover_font_color); ?>;
      }
      .sticky-header .menu-area ul li > .submenu li.current-menu-item>a,
      .sticky-header .menu-area ul li.current-menu-ancestor>a,
      .sticky-header .menu-area ul li.current-menu-item>a,
      .sticky-header .menu-area ul li.current-menu-item>a,
      .sticky-header .menu-area > nav > ul > li:hover > a,
      .sticky-header .menu-area ul li.active>a,
      .sticky-header.default-header .main-menu ul li.active > a,
      .sticky-header.default-header .main-menu ul li:hover > a,
      .sticky-header.header-style-two .main-menu ul li.active > a,
      .sticky-header.header-style-two .main-menu ul li:hover > a{
        color: <?php echo esc_attr($sticky_menu1_hover_font_color); ?>;
      }
      .sticky-header .main-menu ul li > .submenu{
        background-color: <?php echo esc_attr($sticky_submenu1_bg_color); ?>;
      }
      .sticky-header .main-menu ul li > .submenu li a{
        color: <?php echo esc_attr($sticky_submenu1_color); ?>;
      }
      .sticky-header .main-menu ul li .submenu > li:hover > a{
        color: <?php echo esc_attr($sticky_submenu1_hover_color); ?>;
      }
      .sticky-header .main-menu ul ul a:hover,
      .sticky-header .main-menu ul ul ul a:hover{
        background-color: <?php echo esc_attr($sticky_drops1_menus_hover_bg_color); ?>;
      }
      .header-action .header-btn .btn.transparent-btn {
        color: <?php echo esc_attr( $menu1_btn_font_color); ?>;
        background: <?php echo esc_attr( $menu1_btn_bg_color); ?>;
        border-color: <?php echo esc_attr( $menu1_btn_border_color); ?>;
      }
      .header-action .header-btn .btn.transparent-btn:hover {
        color: <?php echo esc_attr( $menu1_btn_hf_color); ?>;
        background: <?php echo esc_attr( $menu1_btn_hb_color); ?>;
        border-color: <?php echo esc_attr( $menu1_btn_hb_color); ?>;
        box-shadow: 0px 8px 16px 0px <?php echo esc_attr( $menu1_btn_hb_boxshadow_color ); ?>;
      }
      .sticky-header .header-action .header-btn .btn.transparent-btn{
        color: <?php echo esc_attr( $menu1_stickybtn_font_color ); ?>;
        background-color: <?php echo esc_attr( $menu1_stickybtn_hbg_color ); ?>;
        border: 2px solid <?php echo esc_attr( $menu1_stickybtn_hbg_color ); ?>;
      }
      .header-top-area {
        background-color: <?php echo esc_attr( $h1top_bg_color ); ?>
      }
      .header-top-link ul li a,
      .header-social a{
        color: <?php echo esc_attr( $h1top_font_color ); ?> !important;
      }
      .header-top-link ul li a:hover,
      .header-social a:hover{
        color: <?php echo esc_attr( $h1top_hover_font_color ); ?> !important;
      }
      .header-top-area {
        border-bottom: 2px solid <?php echo esc_attr( $h1top_border_color ); ?>;
      }
      .mean-container a.meanmenu-reveal {
        border: 1px solid <?php echo esc_attr( $responsive_menu1_icon_color ); ?> !important;
        color: <?php echo esc_attr( $responsive_menu1_icon_color ); ?> !important;
      }
      .mean-container a.meanmenu-reveal span {
        background: <?php echo esc_attr( $responsive_menu1_icon_color ); ?> !important;
      }
      .sticky-header .mean-container a.meanmenu-reveal {
        border: 1px solid <?php echo esc_attr( $stickyresponsive_menu1_icon_color ); ?> !important;
        color: <?php echo esc_attr( $stickyresponsive_menu1_icon_color ); ?> !important;
      }
      .sticky-header .mean-container a.meanmenu-reveal span {
        background: <?php echo esc_attr( $stickyresponsive_menu1_icon_color ); ?> !important;
      }
      .mobile-menu.mean-container .mean-nav {
        background-color: <?php echo esc_attr( $responsive_menu1_bacg_color ); ?>;
      }
      .mobile-menu.mean-container .mean-nav a{
        color: <?php echo esc_attr( $responsive_menu1_bgtext_color ); ?>;
      }
      ul.lang-sub-menu li.current-lang {
        background-color: <?php echo esc_attr( $lan1_btn_sitem_color ); ?>;
      }
		</style>

	<?php } ?>
  
  	<?php if(function_exists( 'geoport_framework_init' ) ) {
  		$geoport_custom_color = get_post_meta( get_the_ID(), '_custom_page_options', true );

		?>
		<?php if( !empty( $geoport_custom_color['header_custom_style'] ) ) { ?>
			<?php if( $geoport_custom_color['header_custom_style'] == 'customcolor1' ) { ?>
				<style>
					<?php if( !empty( $geoport_custom_color['header_custom_bgcolor'] ) ) { ?>
					body .h1-navigation-area,
					body .h3-navigation-area,
					body .h2-navigation-area {
						background:<?php echo esc_attr($geoport_custom_color['header_custom_bgcolor']); ?>;
					}
					<?php } ?>
          <?php if( !empty( $geoport_custom_color['header_custom_menucolor'] ) ) { ?>
          body .main-menu ul li > a{
            color:<?php echo esc_attr($geoport_custom_color['header_custom_menucolor']); ?>;
          }
          <?php } ?>
          <?php if( !empty( $geoport_custom_color['header_custom_menu_hover_color'] ) ) { ?>
          body .main-menu ul li:hover> a,
          body .main-menu ul li > a:hover{
            color:<?php echo esc_attr($geoport_custom_color['header_custom_menu_hover_color']); ?>;
          }
          <?php } ?>
          <?php if( !empty( $geoport_custom_color['header_custom_menu_active_hover_color'] ) ) { ?>
          body .menu-area ul li > .submenu li.current-menu-item>a,
          body .menu-area ul li.current-menu-ancestor>a,
          body .menu-area ul li.current-menu-item>a,
          body .menu-area ul li.current-menu-item>a,
          body .menu-area > nav > ul > li:hover > a,
          body .menu-area ul li.active>a{
            color: <?php echo esc_attr($geoport_custom_color['header_custom_menu_active_hover_color']); ?>;
          }
          <?php } ?>
          <?php if( !empty( $geoport_custom_color['header_custom_dropmenu_hover_color'] ) ) { ?>
          body .main-menu ul li .submenu > li:hover > a{
            color: <?php echo esc_attr($geoport_custom_color['header_custom_dropmenu_hover_color']); ?>;
          }
          <?php } ?>
          <?php if( !empty( $geoport_custom_color['header_custom_menu_buttonbg_color'] ) ) { ?>
          body .header-action .header-btn .btn.transparent-btn{
            background-color:<?php echo esc_attr($geoport_custom_color['header_custom_menu_buttonbg_color']); ?>;
          }
          <?php } ?>
          <?php if( !empty( $geoport_custom_color['header_custom_menu_button_color'] ) ) { ?>
          body .header-action .header-btn .btn.transparent-btn{
            color:<?php echo esc_attr($geoport_custom_color['header_custom_menu_button_color']); ?>;
          }
          <?php } ?>
          <?php if( !empty( $geoport_custom_color['header_custom_menu_button_border_color'] ) ) { ?>
          body .header-action .header-btn .btn.transparent-btn{
            border-color:<?php echo esc_attr($geoport_custom_color['header_custom_menu_button_border_color']); ?>;
          }
          <?php } ?>
          <?php if( !empty( $geoport_custom_color['header_custom_menu_hover_buttonbg_color'] ) ) { ?>
          body .header-action .header-btn .btn.transparent-btn:hover{
            background-color:<?php echo esc_attr($geoport_custom_color['header_custom_menu_hover_buttonbg_color']); ?>;
          }
          <?php } ?>
          <?php if( !empty( $geoport_custom_color['header_custom_menu_hover_button_color'] ) ) { ?>
          body .header-action .header-btn .btn.transparent-btn:hover{
            color:<?php echo esc_attr($geoport_custom_color['header_custom_menu_hover_button_color']); ?>;
          }
          <?php } ?>
          <?php if( !empty( $geoport_custom_color['header_custom_menu_button_hover_border_color'] ) ) { ?>
          body .header-action .header-btn .btn.transparent-btn:hover{
            border-color:<?php echo esc_attr($geoport_custom_color['header_custom_menu_button_hover_border_color']); ?>;
          }
          <?php } ?>
          <?php if( !empty( $geoport_custom_color['header_custom_menu_button_boxshadow_color'] ) ) { ?>
          body .header-action .header-btn .btn.transparent-btn:hover{
            box-shadow: 0px 8px 16px 0px <?php echo esc_attr($geoport_custom_color['header_custom_menu_button_boxshadow_color']); ?>;
          }
          <?php } ?>
          <?php if( !empty( $geoport_custom_color['header_custom_menu_button_sticky_bg_color'] ) ) { ?>
          body .sticky-header .header-action .header-btn .btn.transparent-btn{
            background-color:<?php echo esc_attr($geoport_custom_color['header_custom_menu_button_sticky_bg_color']); ?>;
            border:2px solid <?php echo esc_attr($geoport_custom_color['header_custom_menu_button_sticky_bg_color']); ?>;
          }
          <?php } ?>
          <?php if( !empty( $geoport_custom_color['header_custom_menu_button_sticky_color'] ) ) { ?>
          body .sticky-header .header-action .header-btn .btn.transparent-btn{
            color:<?php echo esc_attr($geoport_custom_color['header_custom_menu_button_sticky_color']); ?>;
          }
          <?php } ?>
          <?php if( !empty( $geoport_custom_color['sticky_header_custom_bgcolor'] ) ) { ?>
          body .sticky-header{
            background-color:<?php echo esc_attr($geoport_custom_color['sticky_header_custom_bgcolor']); ?>;
          }
          <?php } ?>
          <?php if( !empty( $geoport_custom_color['sticky_header_custom_textcolor'] ) ) { ?>
          body .sticky-header .main-menu ul li > a{
            color:<?php echo esc_attr($geoport_custom_color['sticky_header_custom_textcolor']); ?>;
          }
          <?php } ?>
          <?php if( !empty( $geoport_custom_color['sticky_header_custom_text_dropcolor'] ) ) { ?>
          body .sticky-header .main-menu ul li > .submenu li a{
            color:<?php echo esc_attr($geoport_custom_color['sticky_header_custom_text_dropcolor']); ?>;
          }
          <?php } ?>
          <?php if( !empty( $geoport_custom_color['sticky_header_custom_text_activecolor'] ) ) { ?>
          body .sticky-header .main-menu ul li .submenu > li:hover > a{
            color: <?php echo esc_attr($geoport_custom_color['sticky_header_custom_text_activecolor']); ?>;
          }
          <?php } ?>
          <?php if( !empty( $geoport_custom_color['sticky_header_custom_text_hover_color'] ) ) { ?>
          body .sticky-header .main-menu ul li:hover a,
          body .sticky-header .main-menu ul li > a:hover{
            color:<?php echo esc_attr($geoport_custom_color['sticky_header_custom_text_hover_color']); ?>;
          }
          <?php } ?>
          <?php if( !empty( $geoport_custom_color['sticky_header_custom_text_hover_color'] ) ) { ?>
          body .sticky-header .menu-area ul li > .submenu li.current-menu-item>a,
          body .sticky-header .menu-area ul li.current-menu-ancestor>a,
          body .sticky-header .menu-area ul li.current-menu-item>a,
          body .sticky-header .menu-area ul li.current-menu-item>a,
          body .sticky-header .menu-area > nav > ul > li:hover > a,
          body .sticky-header .menu-area ul li.active>a{
            color:<?php echo esc_attr($geoport_custom_color['sticky_header_custom_text_hover_color']); ?>;
          }
          <?php } ?>
          <?php if( !empty( $geoport_custom_color['header_custom_responsive_icon_color'] ) ) { ?>
           body .mean-container a.meanmenu-reveal {
              border: 1px solid <?php echo esc_attr($geoport_custom_color['header_custom_responsive_icon_color']); ?> !important;
              color: <?php echo esc_attr($geoport_custom_color['header_custom_responsive_icon_color']); ?> !important;
          }
          <?php } ?>
          <?php if( !empty( $geoport_custom_color['header_custom_responsive_icon_color'] ) ) { ?>
           body .mean-container a.meanmenu-reveal span {
            background: <?php echo esc_attr($geoport_custom_color['header_custom_responsive_icon_color']); ?> !important;
          }
          <?php } ?>
          <?php if( !empty( $geoport_custom_color['sticky_header_custom_responsive_icon_color'] ) ) { ?>
           body .sticky-header .mean-container a.meanmenu-reveal {
              border: 1px solid <?php echo esc_attr($geoport_custom_color['sticky_header_custom_responsive_icon_color']); ?> !important;
              color: <?php echo esc_attr($geoport_custom_color['sticky_header_custom_responsive_icon_color']); ?> !important;
          }
          <?php } ?>          
          <?php if( !empty( $geoport_custom_color['sticky_header_custom_responsive_icon_color'] ) ) { ?>
           body .sticky-header .mean-container a.meanmenu-reveal span {
            background: <?php echo esc_attr($geoport_custom_color['sticky_header_custom_responsive_icon_color']); ?> !important;
          }
          <?php } ?>
					<?php if( !empty( $geoport_custom_color['header_top_parts_bgcolor'] ) ) { ?>
					body .header-style-two .header-top-area,
					body .h2-header-top-area,
					body .h3-header-top-area {
						background:<?php echo esc_attr($geoport_custom_color['header_top_parts_bgcolor']); ?>;
					}
					<?php } ?>
					<?php if( !empty( $geoport_custom_color['header_top_parts_txtcolor'] ) ) { ?>
					body .header-top-link ul li a {
						color:<?php echo esc_attr($geoport_custom_color['header_top_parts_txtcolor']); ?>;
					}
					<?php } ?>
					<?php if( !empty( $geoport_custom_color['header_top_parts_txtcolor'] ) ) { ?>
		      body .header-social a {
		        color: <?php echo esc_attr($geoport_custom_color['header_top_parts_txtcolor']); ?>;
		      }
					<?php } ?>
          <?php if( !empty( $geoport_custom_color['header_top_parts_hovercolor'] ) ) { ?>
          body .header-top-link ul li a:hover,
          body .header-social a:hover{
            color: <?php echo esc_attr($geoport_custom_color['header_top_parts_hovercolor']); ?>;
          }
          <?php } ?>
				</style>
			<?php } ?>
		<?php } ?>
	<?php } ?>
<?php } ?>