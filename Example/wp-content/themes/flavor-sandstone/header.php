<?php
$is_search = is_search();
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'flavor-sandstone' ); ?></a>
	<header id="masthead" class="site-header <?php echo $is_search ? 'site-header-search' : ''; ?>">
    <div class="site-header-container">
      <div class="site-branding">
        <?php
        the_custom_logo();
        if ( is_front_page() && is_home() ) :
          ?>
          <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
          <?php
        else :
          ?>
          <p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
          <?php
        endif;
        ?>
      </div>
      <nav id="site-navigation" class="main-navigation">
        <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
          <span class="hamburger-line"></span>
          <span class="hamburger-line"></span>
          <span class="hamburger-line"></span>
        </button>
        <?php
        wp_nav_menu( array(
          'theme_location' => 'header_menu',
          'menu_id'        => 'primary-menu',
          'container'      => false,
          'fallback_cb'    => false,
          'depth'          => 2,
        ) );
        ?>
      </nav>
      <?php if ( $is_search ) { get_search_form(); } ?>
    </div>
  </header>
	<div id="content" class="site-content">
