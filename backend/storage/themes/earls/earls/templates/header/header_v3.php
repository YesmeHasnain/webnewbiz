<?php
$options = earls_WSH()->option();
$allowed_html = wp_kses_allowed_html( 'post' );

//Mian Logo Settings
$main_logo = $options->get( 'main_color_logo' );
$main_logo_dimension = $options->get( 'main_color_logo_dimension' );

$logo_type = '';
$logo_text = '';
$logo_typography = ''; ?>
	
    
    <div class="boxed_wrapper two">
        
        <?php if( $options->get( 'theme_preloader' ) ):?>
        <!-- preloader -->
        <div class="loader-wrap">
            <div class="preloader">
                <?php if($options->get('preloader_btn_text')){ ?><div class="preloader-close"><?php echo wp_kses($options->get('preloader_btn_text'), true); ?></div><?php } ?>
                <?php if($options->get('preloader_text')){ ?>
                <div id="handle-preloader" class="handle-preloader">
                    <div class="animation-preloader">
                        <div class="spinner"></div>
                        <div class="txt-loading">
                            <?php echo wp_kses($options->get('preloader_text'), true); ?>
                        </div>
                    </div>  
                </div>
                <?php } ?>
            </div>
        </div>
        <!-- preloader end -->
		<?php endif; ?>
        
        <!-- main header -->
        <header class="main-header style-three">
            <!-- header-lower -->
            <div class="header-lower">
                <div class="header__three__content">
                    <div class="outer-box">
                        <div class="logo-box">
                            <figure class="logo">
                            	<?php echo earls_logo( $logo_type, $main_logo, $main_logo_dimension, $logo_text, $logo_typography ); ?>
                            </figure>
                        </div>
                        <div class="menu-area">
                            <!--Mobile Navigation Toggler-->
                            <div class="mobile-nav-toggler">
                                <figure>
                                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/icons/bar.png" alt="<?php esc_attr_e('Awesome Image', 'earls'); ?>">
                                </figure>
                            </div>
                            <nav class="main-menu navbar-expand-md navbar-light">
                                <div class="collapse navbar-collapse show clearfix" id="navbarSupportedContent">
                                    <ul class="navigation clearfix">
									<?php wp_nav_menu( array( 'theme_location' => 'main_menu', 'container_id' => 'navbar-collapse-1',
                                        'container_class'=>'navbar-collapse collapse navbar-right',
                                        'menu_class'=>'nav navbar-nav',
                                        'fallback_cb'=>false,
                                        'items_wrap' => '%3$s',
                                        'container'=>false,
                                        'depth'=>'3',
                                        'walker'=> new Bootstrap_walker()
                                    )); ?>     
                                    </ul>
                                </div>
                            </nav>
                            <?php if( $options->get( 'show_button_v3' )){ ?>
                            <div class="menu__right__site">
                                <a href="<?php echo esc_url($options->get('btn_link_v3')); ?>"><?php echo wp_kses($options->get('btn_title_v3'), true); ?></a>
                            </div>
                            <?php } ?>
                            
                            <?php if( $options->get( 'show_phone_no_v3' )){ ?>
                            <div class="header__right">
                                <span><?php echo wp_kses($options->get('phone_title_v3'), true); ?> <a href="tel:<?php echo esc_attr($options->get('phone_no_v3')); ?>"> <?php echo wp_kses($options->get('phone_no_v3'), true); ?> </a></span>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>

            <!--sticky Header-->
            <div class="sticky-header">
                <div class="auto-container">
                    <div class="outer-box">
                        <div class="logo-box">
                            <figure class="logo">
                            	<?php echo earls_logo( $logo_type, $main_logo, $main_logo_dimension, $logo_text, $logo_typography ); ?>
                            </figure>
                        </div>
                        <div class="menu-area">
                            <nav class="main-menu clearfix">
                                <!--Keep This Empty / Menu will come through Javascript-->
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- main-header end -->
		
        <!-- Header Mobile Settings -->
		<?php get_template_part('templates/header/mobile_settings'); ?>
    