<?php if ( has_nav_menu( 'primary' ) ) {
  wp_nav_menu(array(
    'theme_location' => 'primary',
    'container'       => false,
    'menu_class'      => '',
    'echo'            => true,
    'depth'             => 3,
    'items_wrap'      => '<ul class="geoport-main-menu">%3$s</ul>',
    'walker' => new Geoport_Navwalker()
  ));
} else {
  if ( is_user_logged_in() ) {
    echo '<ul id="menu" class="nav navbar-nav navbar-right nav-sideb fallbackcd-menu-item"><li><a class="fallbackcd" href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">' . esc_html__( 'Add a menu', 'geoport' ) . '</a></li></ul>';
  }
}
?>