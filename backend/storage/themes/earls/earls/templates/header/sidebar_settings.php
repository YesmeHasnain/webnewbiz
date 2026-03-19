<?php
$options = earls_WSH()->option();
$allowed_html = wp_kses_allowed_html( 'post' );

//Mian Logo Settings
$main_logo = $options->get( 'main_color_logo' );
$main_logo_dimension = $options->get( 'main_color_logo_dimension' );

$logo_type = '';
$logo_text = '';
$logo_typography = ''; ?>

	<?php if( $options->get( 'show_sidebar_setting' ) ):?>
    <span class="close-button side-navigation-close-btn icofont-close"></span><!-- /.close-button -->
    
    <div class="sidebar-content">
        <div class="nav-logo">
            <?php echo earls_logo( $logo_type, $main_logo, $main_logo_dimension, $logo_text, $logo_typography ); ?>
        </div>
    </div>
    
    <?php if($options->get('sidebar_title_v1') || $options->get('sidebar_text_v1')){ ?>
    <div class="sidebar-text">
        <h4><?php echo wp_kses($options->get('sidebar_title_v1'), true); ?></h4>
        <p><?php echo wp_kses($options->get('sidebar_text_v1'), true); ?></p>
    </div>
    <?php } ?>
    
    <?php if( $options->get( 'show_quote_form_v1' )){ ?>
    <div class="sidebar-from">
        <h4><?php echo wp_kses($options->get('sidebar_form_title_v1'), true); ?></h4>
        <div id="contact-form"> 
            <?php echo do_shortcode($options->get('sidebar_form_url_v1'), true); ?>
        </div>
    </div>
    <?php } ?>
    
    <?php
        if( $options->get('show_sidebar_social_icon_v1') ):
        $icons = $options->get( 'sidebar_header_social_icon_v1' );
        if ( ! empty( $icons ) ) :
    ?>
    <div class="sidebar-social-network">
        <?php if($options->get('sidebar_social_title_v1')){ ?>
        <div class="title__social">
            <h6><?php echo wp_kses($options->get('sidebar_social_title_v1'), true); ?></h6>
        </div>
        <?php } ?>
        <div class="sidebar__media">
            <ul class="social____media">
                <?php
                    foreach ( $icons as $h_icon ) :
                    $header_social_icons = json_decode( urldecode( earls_set( $h_icon, 'data' ) ) );
                    if ( earls_set( $header_social_icons, 'enable' ) == '' ) {
                        continue;
                    }
                    $icon_class = explode( '-', earls_set( $header_social_icons, 'icon' ) );
                ?>
                <li class="sidebar__media__icon"><a href="<?php echo esc_url(earls_set( $header_social_icons, 'url' )); ?>" <?php if( earls_set( $header_social_icons, 'background' ) || earls_set( $header_social_icons, 'color' ) ):?>style="background-color:<?php echo esc_attr(earls_set( $header_social_icons, 'background' )); ?>; color: <?php echo esc_attr(earls_set( $header_social_icons, 'color' )); ?>"<?php endif;?>><i class="fab <?php echo esc_attr( earls_set( $header_social_icons, 'icon' ) ); ?>"></i></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <?php endif; endif; ?>
    
<?php endif; ?>
