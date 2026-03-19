<!doctype html>
<html <?php language_attributes(); ?>>
<?php $rival_redux_demo = get_option('redux_demo'); ?>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php if ( ! function_exists( 'has_site_icon' ) || ! has_site_icon() ) { ?>
        <?php if(isset($rival_redux_demo['favicon']['url'])){?>
        <link rel="shortcut icon" href="<?php echo esc_url($rival_redux_demo['favicon']['url']); ?>">
        <?php }?>
    <?php }?>
    <?php wp_head(); ?> 
</head>
<body <?php body_class(); ?>>
<?php
    wp_body_open();
?>
<div id="loader">
  <div id="loaderInner"></div>
</div>
<div id="wrapper">
   <header id="header" class="default">
      <div class="mainHeader">
      <div class="container clearfix">
         <a href="#" class="mobileBtn" ><i class="icon-menu"></i></a>
         <div class="six columns nav first">
            <nav class="mainNav" >
               <?php 
                  wp_nav_menu( 
                  array( 
                     'theme_location'  => 'primary',
                     'container'       => '', 
                     'menu_class'      => '',
                     'menu_id'         => '',
                     'menu'            => '',
                     'container_class' => '',
                     'container_id'    => '',
                     'echo'            => true,
                     'fallback_cb'     => 'wp_bootstrap_navwalker::fallback',
                     'walker'          => new rival_wp_bootstrap_navwalker(),
                     'before'          => '',
                     'after'           => '',
                     'link_before'     => '',
                     'link_after'      => '',
                     'items_wrap'      => '<ul class=" %2$s"> %3$s </ul>',
                     'depth'           => 0, 
                     )
               ); ?>
            </nav>   
         </div>
      <div class="four columns logo">
         <a href="<?php echo esc_url(home_url('/')); ?>">
            <?php if (isset($rival_redux_demo['logo_header']['url']) && $rival_redux_demo['logo_header']['url'] != '') {?>
            <img src="<?php echo esc_url($rival_redux_demo['logo_header']['url']); ?>" alt="<?php bloginfo( 'name' ); ?>">
            <?php } else { ?>
            <img src="<?php echo esc_url(get_template_directory_uri());?>/assets/images/logo.png" alt="<?php bloginfo( 'name' ); ?>"/>
            <?php } ?>
         </a>
      </div>
         <div class="six columns nav second">
            <nav class="mainNav" >
               <?php 
                  wp_nav_menu( 
                  array( 
                     'theme_location'  => 'primary_right',
                     'container'       => '', 
                     'menu_class'      => '',
                     'menu_id'         => '',
                     'menu'            => '',
                     'container_class' => '',
                     'container_id'    => '',
                     'echo'            => true,
                     'fallback_cb'     => 'wp_bootstrap_navwalker::fallback',
                     'walker'          => new rival_wp_bootstrap_navwalker(),
                     'before'          => '',
                     'after'           => '',
                     'link_before'     => '',
                     'link_after'      => '',
                     'items_wrap'      => '<ul class=" %2$s"> %3$s </ul>',
                     'depth'           => 0, 
                     )
               ); ?>
            </nav>
         </div>
      </div>
      </div>
   </header>