<?php
// Block direct access
if( !defined( 'ABSPATH' ) ){
    exit();
}
/**
 * @Packge     : Barab
 * @Version    : 1.0
 * @Author     : Themeholy
 * @Author URI : https://themeforest.net/user/themeholy
 *
 */

// enqueue css
function barab_common_custom_css(){
	wp_enqueue_style( 'barab-color-schemes', get_template_directory_uri().'/assets/css/color.schemes.css' );

    $CustomCssOpt  = barab_opt( 'barab_css_editor' );
	if( $CustomCssOpt ){
		$CustomCssOpt = $CustomCssOpt;
	}else{
		$CustomCssOpt = '';
	}

    $customcss = "";
    
    if( get_header_image() ){
        $barab_header_bg =  get_header_image();
    }else{
        if( barab_meta( 'page_breadcrumb_settings' ) == 'page' ){
            if( ! empty( barab_meta( 'breadcumb_image' ) ) ){
                $barab_header_bg = barab_meta( 'breadcumb_image' );
            }
        }
    }
    
    if( !empty( $barab_header_bg ) ){
        $customcss .= ".breadcumb-wrapper{
            background-image:url('{$barab_header_bg}')!important;
        }";
    }
    
	// Theme color
	$barabthemecolor = barab_opt('barab_theme_color'); 
    if( !empty( $barabthemecolor ) ){
        list($r, $g, $b) = sscanf( $barabthemecolor, "#%02x%02x%02x");

        $barab_real_color = $r.','.$g.','.$b;
        if( !empty( $barabthemecolor ) ) {
            $customcss .= ":root {
            --theme-color: rgb({$barab_real_color});
            }";
        }
    }
	// Theme color 2
	$barabthemecolor2 = barab_opt('barab_theme_color2'); 
    if( !empty( $barabthemecolor2 ) ){
        list($r, $g, $b) = sscanf( $barabthemecolor2, "#%02x%02x%02x");

        $barab_real_color2 = $r.','.$g.','.$b;
        if( !empty( $barabthemecolor2 ) ) {
            $customcss .= ":root {
            --theme-color2: rgb({$barab_real_color2});
            }";
        }
    }
	// Theme color 3
	$barabthemecolor3 = barab_opt('barab_theme_color3'); 
    if( !empty( $barabthemecolor3 ) ){
        list($r, $g, $b) = sscanf( $barabthemecolor3, "#%02x%02x%02x");

        $barab_real_color3 = $r.','.$g.','.$b;
        if( !empty( $barabthemecolor3 ) ) {
            $customcss .= ":root {
            --theme-color3: rgb({$barab_real_color3});
            }";
        }
    }

    // Heading  color
	$barabheadingcolor = barab_opt('barab_heading_color');
    if( !empty( $barabheadingcolor ) ){
        list($r, $g, $b) = sscanf( $barabheadingcolor, "#%02x%02x%02x");

        $barab_real_color = $r.','.$g.','.$b;
        if( !empty( $barabheadingcolor ) ) {
            $customcss .= ":root {
                --title-color: rgb({$barab_real_color});
            }";
        }
    }
    // Body color
	$barabbodycolor = barab_opt('barab_body_color');
    if( !empty( $barabbodycolor ) ){
        list($r, $g, $b) = sscanf( $barabbodycolor, "#%02x%02x%02x");

        $barab_real_color = $r.','.$g.','.$b;
        if( !empty( $barabbodycolor ) ) {
            $customcss .= ":root {
                --body-color: rgb({$barab_real_color});
            }";
        }
    }

     // Body font
     $barabbodyfont = barab_opt('barab_theme_body_font', 'font-family');
     if( !empty( $barabbodyfont ) ) {
         $customcss .= ":root {
             --body-font: $barabbodyfont ;
         }";
     }
 
     // Heading font
     $barabheadingfont = barab_opt('barab_theme_heading_font', 'font-family');
     if( !empty( $barabheadingfont ) ) {
         $customcss .= ":root {
             --title-font: $barabheadingfont ;
         }";
     }


    if(barab_opt('barab_menu_icon_class')){
        $menu_icon_class = barab_opt( 'barab_menu_icon_class' );
    }else{
        $menu_icon_class = 'f2e7';
    }

    if( !empty( $menu_icon_class ) ) {
        $customcss .= ".main-menu ul.sub-menu li a:before {
                content: \"\\$menu_icon_class\" !important;
            }";
    }

	if( !empty( $CustomCssOpt ) ){
		$customcss .= $CustomCssOpt;
	}

    wp_add_inline_style( 'barab-color-schemes', $customcss );
}
add_action( 'wp_enqueue_scripts', 'barab_common_custom_css', 100 );