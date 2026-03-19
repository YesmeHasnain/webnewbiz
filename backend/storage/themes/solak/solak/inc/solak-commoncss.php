<?php
// Block direct access
if( !defined( 'ABSPATH' ) ){
    exit();
}
/**
 * @Packge    : Solak
 * @Version   : 1.0
 * @Author    : Themeholy
 * @Author URI: https://themeforest.net/user/themeholy
 *
 */

// enqueue css
function solak_common_custom_css(){
	wp_enqueue_style( 'solak-color-schemes', get_template_directory_uri().'/assets/css/color.schemes.css' );

    $CustomCssOpt  = solak_opt( 'solak_css_editor' );
	if( $CustomCssOpt ){
		$CustomCssOpt = $CustomCssOpt;
	}else{
		$CustomCssOpt = '';
	}

    $customcss = "";
    
    if( get_header_image() ){
        $solak_header_bg =  get_header_image();
    }else{
        if( solak_meta( 'page_breadcrumb_settings' ) == 'page' ){
            if( ! empty( solak_meta( 'breadcumb_image' ) ) ){
                $solak_header_bg = solak_meta( 'breadcumb_image' );
            }
        }
    }
    
    if( !empty( $solak_header_bg ) ){
        $customcss .= ".breadcumb-wrapper{
            background-image:url('{$solak_header_bg}')!important;
        }";
    }
    
	// Theme color
	$solakthemecolor = solak_opt('solak_theme_color'); 
    if( !empty( $solakthemecolor ) ){
        list($r, $g, $b) = sscanf( $solakthemecolor, "#%02x%02x%02x");

        $solak_real_color = $r.','.$g.','.$b;
        if( !empty( $solakthemecolor ) ) {
            $customcss .= ":root {
            --theme-color: rgb({$solak_real_color});
            }";
        }
    }

    // Heading  color
	$solakheadingcolor = solak_opt('solak_heading_color');
    if( !empty( $solakheadingcolor ) ){
        list($r, $g, $b) = sscanf( $solakheadingcolor, "#%02x%02x%02x");

        $solak_real_color = $r.','.$g.','.$b;
        if( !empty( $solakheadingcolor ) ) {
            $customcss .= ":root {
                --title-color: rgb({$solak_real_color});
            }";
        }
    }
    // Body color
	$solakbodycolor = solak_opt('solak_body_color');
    if( !empty( $solakbodycolor ) ){
        list($r, $g, $b) = sscanf( $solakbodycolor, "#%02x%02x%02x");

        $solak_real_color = $r.','.$g.','.$b;
        if( !empty( $solakbodycolor ) ) {
            $customcss .= ":root {
                --body-color: rgb({$solak_real_color});
            }";
        }
    }

     // Body font
     $solakbodyfont = solak_opt('solak_theme_body_font', 'font-family');
     if( !empty( $solakbodyfont ) ) {
         $customcss .= ":root {
             --body-font: $solakbodyfont ;
         }";
     }
 
     // Heading font
     $solakheadingfont = solak_opt('solak_theme_heading_font', 'font-family');
     if( !empty( $solakheadingfont ) ) {
         $customcss .= ":root {
             --title-font: $solakheadingfont ;
         }";
     }


    if(solak_opt('solak_menu_icon_class')){
        $menu_icon_class = solak_opt( 'solak_menu_icon_class' );
    }else{
        $menu_icon_class = 'f185';
    }

    if( !empty( $menu_icon_class ) ) {
        $customcss .= ".main-menu ul.sub-menu li a:before {
                content: \"\\$menu_icon_class\" !important;
            }";
    }

	if( !empty( $CustomCssOpt ) ){
		$customcss .= $CustomCssOpt;
	}

    wp_add_inline_style( 'solak-color-schemes', $customcss );
}
add_action( 'wp_enqueue_scripts', 'solak_common_custom_css', 100 );