<!DOCTYPE html>
<html class="no-js fade-in" <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php if (!function_exists('has_site_icon') || !has_site_icon()) {$redux_gauthier = get_option('redux_gauthier');
    $favicon_url = isset($redux_gauthier['jp_favicon']['url']) ? esc_url($redux_gauthier['jp_favicon']['url']) : '';
    if ($favicon_url) { echo '<link rel="shortcut icon" href="' . $favicon_url . '">'; 
}}?>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div class="wrapper-header">
    <header id="masthead" class="site-header">
        <div class="header7-wrapper">
            <div class="header7-marqueewrapper">
                <div class="header7-marquee">
                    <div class="simple-marquee-container">
                        <div class="marquee-wrapper">
                            <div class="marquee-sibling2"> <a class="btn btn-customized open-menu" href="#" role="button"> </a> </div>
                        </div>
                        <div class="marquee">
                            <ul class="marquee-content-items">
                                <?php  get_template_part( 'inc/header-marquee' ); ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="header7-slidebar">
                    <div class="switch">
                        <input class="switch__input" type="checkbox" id="themeSwitch">
                        <label aria-hidden="true" class="switch__label" for="themeSwitch"> </label>
                        <div aria-hidden="true" class="switch__marker"></div>
                    </div>
                </div>
            </div>
        </div>
		<?php if ( is_single() && is_main_query() ) : ?>		
        <?php if(get_post_meta( get_the_ID(), 'adv_header_id' , true )){ ?>
        <div class="advheader-wrapper"> <a href="<?php echo esc_html(get_post_meta(get_the_ID(), "adv_headerlink", true) ); ?>" target="_blank"><?php echo  ''.$image_two = wp_get_attachment_image( get_post_meta( get_the_ID(), 'adv_header_id', 1 ), 'poster' ).''; ?></a> </div>
        <?php }	?>
		<?php endif; ?>		
        <div class="header4-topwrapper">
            <div class="header7-widgetheader1">
                <?php dynamic_sidebar( 'gauthier-header1' ); ?>
            </div>
            <div class="header7-logo">
                <?php global $redux_gauthier; if ( isset($redux_gauthier['opt_header_logo']['url']) && !empty($redux_gauthier['opt_header_logo']['url']) ){ ?>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img alt="<?php echo get_bloginfo('name'); ?>" src="<?php echo esc_url($redux_gauthier['opt_header_logo']['url']); ?>"></a>
                <?php } else if ( isset($redux_gauthier['opt_header_text']) && !empty($redux_gauthier['opt_header_text']) ){ ?>
                <h1> <a href="<?php echo esc_url( home_url( '/' ) ); ?>"> <?php echo esc_html($redux_gauthier['opt_header_text']); ?></a></h1>
                <?php }else { ?>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img alt="<?php echo get_bloginfo('name'); ?>" src="<?php echo esc_url( get_template_directory_uri() . '/images/logo.png' ); ?>"></a>
                <?php } ?>
                <span><?php echo esc_html (get_option('blogdescription')); ?></span> </div>
            <div class="header7-widgetheader2">
                <?php dynamic_sidebar( 'gauthier-header2' ); ?>
            </div>
        </div>
        <div class="nav-mainwrapper">
            <nav id="site-navigation" class="gauthier-nav">
                <?php wp_nav_menu(["theme_location" => "primary_menu", "menu_id" => "myTopnav", "menu_class" => "topnav", "container" => "ul", "fallback_cb" => "__return_false",]); ?>
            </nav>
            <!-- #site-navigation -->
        </div>
    </header>
    <div class="Sidebar1">
        <div class="dismiss"></div>
        <div class="logo">
            <div class="gauthierlogo">
                <?php global $redux_gauthier; if ( isset($redux_gauthier['opt_header_logo']['url']) && !empty($redux_gauthier['opt_header_logo']['url']) ){ ?>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>"> <img alt="<?php echo get_bloginfo('name'); ?>" src="<?php echo esc_url($redux_gauthier['opt_header_logo']['url']); ?>"></a>
                <?php } else if ( isset($redux_gauthier['opt_header_text']) && !empty($redux_gauthier['opt_header_text']) ){ ?>
                <h1> <a href="<?php echo esc_url( home_url( '/' ) ); ?>"> <?php echo esc_html($redux_gauthier['opt_header_text']); ?></a></h1>
                <?php }else { ?>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img alt="<?php echo get_bloginfo('name'); ?>" src="<?php echo esc_url( get_template_directory_uri() . '/images/logo.png' ); ?>"></a>
                <?php } ?>
            </div>
        </div>
        <div class="sidebar1-insidewrapper">
            <?php if ( is_active_sidebar( 'gauthier-slidemenu' ) ) : ?>
            <div class="widget-area" role="complementary">
                <?php dynamic_sidebar( 'gauthier-slidemenu' ); ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="overlay"></div>
</div>
<div class="wrapper-body">
<?php if (is_single() || is_page() || is_product()) { ?>
<?php edit_post_link(esc_attr__("Edit", "gauthier"),'<span class="edit-link">', "</span>"); ?>
<?php } ?>