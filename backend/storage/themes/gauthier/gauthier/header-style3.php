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
		<?php if ( is_single() && is_main_query() ) : ?>		
        <?php if(get_post_meta( get_the_ID(), 'adv_header_id' , true )){ ?>
        <div class="advheader-wrapper"> <a href="<?php echo esc_html(get_post_meta(get_the_ID(), "adv_headerlink", true) ); ?>" target="_blank"><?php echo  ''.$image_two = wp_get_attachment_image( get_post_meta( get_the_ID(), 'adv_header_id', 1 ), 'poster' ).''; ?></a> </div>
        <?php }	?>
		<?php endif; ?>		
                <div class="header-top1"> 
                    <!-- open Sidebar -->
                    <div <?php body_class(); ?>> <a class="btn btn-customized open-menu" href="#" role="button"></a> </div>
                    <div class="topheader4-left1">
                        <div class="switch">
                            <input class="switch__input" type="checkbox" id="themeSwitch">
                            <label aria-hidden="true" class="switch__label" for="themeSwitch"> </label>
                            <div aria-hidden="true" class="switch__marker"></div>
                        </div>
                    </div>
                    <div class="topheader4-right2">
                        <?php global $redux_gauthier; if ( isset($redux_gauthier['jp_socmedlink1']) ){ ?>
                        <?php if(isset($redux_gauthier['jp_socmedlink1']) && $redux_gauthier['jp_socmedlink1']): ?>
                        <div class="sosmed"><a href="<?php echo esc_url($redux_gauthier['jp_socmedlink1']); ?>"> <?php echo esc_html($redux_gauthier['jp_socmedimg1']); ?> </a><span class="tooltiptext"><?php echo esc_attr($redux_gauthier['jp_socmedalt1']); ?></span> </div>
                        <?php endif; ?>
                        <?php if(isset($redux_gauthier['jp_socmedlink2']) && $redux_gauthier['jp_socmedlink2']): ?>
                        <div class="sosmed"> <a href="<?php echo esc_url($redux_gauthier['jp_socmedlink2']); ?>"> <?php echo esc_html($redux_gauthier['jp_socmedimg2']); ?> </a><span class="tooltiptext"><?php echo esc_attr($redux_gauthier['jp_socmedalt2']); ?></span> </div>
                        <?php endif; ?>
                        <?php if(isset($redux_gauthier['jp_socmedlink3']) && $redux_gauthier['jp_socmedlink3']): ?>
                        <div class="sosmed"> <a href="<?php echo esc_url($redux_gauthier['jp_socmedlink3']); ?>"> <?php echo esc_html($redux_gauthier['jp_socmedimg3']); ?> </a><span class="tooltiptext"><?php echo esc_attr($redux_gauthier['jp_socmedalt3']); ?></span> </div>
                        <?php endif; ?>
                        <?php if(isset($redux_gauthier['jp_socmedlink4']) && $redux_gauthier['jp_socmedlink4']): ?>
                        <div class="sosmed"> <a href="<?php echo esc_url($redux_gauthier['jp_socmedlink4']); ?>"> <?php echo esc_html($redux_gauthier['jp_socmedimg4']); ?> </a><span class="tooltiptext"><?php echo esc_attr($redux_gauthier['jp_socmedalt4']); ?></span> </div>
                        <?php endif; ?>
                        <?php if(isset($redux_gauthier['jp_socmedlink5']) && $redux_gauthier['jp_socmedlink5']): ?>
                        <div class="sosmed"> <a href="<?php echo esc_url($redux_gauthier['jp_socmedlink5']); ?>"> <?php echo esc_html($redux_gauthier['jp_socmedimg5']); ?> </a><span class="tooltiptext"><?php echo esc_attr($redux_gauthier['jp_socmedalt5']); ?></span> </div>
                        <?php endif; ?>
                        <?php if(isset($redux_gauthier['jp_socmedlink6']) && $redux_gauthier['jp_socmedlink6']): ?>
                        <div class="sosmed"> <a href="<?php echo esc_url($redux_gauthier['jp_socmedlink6']); ?>"> <?php echo esc_html($redux_gauthier['jp_socmedimg6']); ?> </a><span class="tooltiptext"><?php echo esc_attr($redux_gauthier['jp_socmedalt6']); ?></span> </div>
                        <?php endif; ?>
                        <?php } ?>
                    </div>
                </div>		
        <div class="header3-topwrapper">
            <div class="header3-left">
                <?php global $redux_gauthier; if ( isset($redux_gauthier["opt_header_logo"]["url"]) && !empty($redux_gauthier["opt_header_logo"]["url"])) { ?>
                <a href="<?php echo esc_url(home_url("/")); ?>"><img alt="<?php echo get_bloginfo("name"); ?>" src="<?php echo esc_url($redux_gauthier["opt_header_logo"]["url"]); ?>"></a>
                <?php } elseif (
					isset($redux_gauthier["opt_header_text"]) && !empty($redux_gauthier["opt_header_text"]) ) { ?>
                <h1><a href="<?php echo esc_url(home_url("/")); ?>"> <?php echo esc_html($redux_gauthier["opt_header_text"]); ?></a></h1>
                <?php } else { ?>
                <a href="<?php echo esc_url(home_url("/")); ?>"><img alt="<?php echo get_bloginfo("name"); ?>" src="<?php echo esc_url(get_template_directory_uri() . "/images/logo.png"); ?>"></a>
                <?php } ?>
                <div class="header3-desc"><?php echo get_option("blogdescription"); ?></div>				
            </div>
            <div class="header3-right">
                <div class="header3-widget">
                    <div class="header3-widgetleft">
                        <?php dynamic_sidebar("gauthier-header1"); ?>
                    </div>
                    <div class="header3-widgetright">
                        <?php dynamic_sidebar("gauthier-header2"); ?>
                    </div>
                </div>
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
                <?php global $redux_gauthier; if ( isset($redux_gauthier["opt_header_logo"]["url"]) && !empty($redux_gauthier["opt_header_logo"]["url"])) { ?>
                <a href="<?php echo esc_url(home_url("/")); ?>"> <img alt="<?php echo get_bloginfo("name"); ?>" src="<?php echo esc_url($redux_gauthier["opt_header_logo"]["url"]); ?>"> </a>
                <?php } elseif (
				isset($redux_gauthier["opt_header_text"]) && !empty($redux_gauthier["opt_header_text"]) ) { ?>
                <h1><a href="<?php echo esc_url(home_url("/")); ?>"> <?php echo esc_html($redux_gauthier["opt_header_text"]); ?></a></h1>
                <?php } else { ?>
                <a href="<?php echo esc_url(home_url("/")); ?>"> <img alt="<?php echo get_bloginfo("name"); ?>" src="<?php echo esc_url(get_template_directory_uri() . "/images/logo.png"); ?>"> </a>
                <?php } ?>
            </div>
        </div>
        <div class="sidebar1-insidewrapper">
            <?php if (is_active_sidebar("gauthier-slidemenu")): ?>
            <div class="widget-area" role="complementary">
                <?php dynamic_sidebar("gauthier-slidemenu"); ?>
            </div>
            <!-- #secondary -->
            <?php endif; ?>
        </div>
    </div>
    <!-- End Sidebar1 -->
    <div class="overlay"></div>
    <!-- Dark overlay --> 
</div>
<div class="wrapper-body">
<?php if (is_single() || is_page() || is_product()) { ?>
<?php edit_post_link(esc_attr__("Edit", "gauthier"),'<span class="edit-link">', "</span>"); ?>
<?php } ?>