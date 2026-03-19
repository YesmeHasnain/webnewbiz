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
        <header class="main-header style-two five">
            <!-- header-lower -->
            <div class="header-lower">
                <div class="outer-box">
                    <div class="logo-box">
                        <figure class="logo">
                        	<?php echo earls_logo( $logo_type, $main_logo, $main_logo_dimension, $logo_text, $logo_typography ); ?>
                        </figure>
                    </div>
                    <div class="menu-area">
                        <!--Mobile Navigation Toggler-->
                        <div class="mobile-nav-toggler">
                            <i class="icon-bar"></i>
                            <i class="icon-bar"></i>
                            <i class="icon-bar"></i>
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
                    </div>
                    <div class="header__right">
                        <?php 
							if ($options->get('show_shopping_cart_icon_v5')):
							if( function_exists( 'WC' ) ): global $woocommerce;
						?>
                        <div class="shopping__bag">
                            <span class="icon-shopping-bag"><i class="product-count"><?php echo wp_kses( $woocommerce->cart->cart_contents_count, true ); ?></i></span>
                            <div class="cart-content">
                                <?php get_template_part('templates/widgets/cart_items'); ?>
                                
								<?php if (WC()->cart->get_cart()): ?>
                                <div class="total clearfix">
                                    <h6><?php esc_html_e('Total: ', 'earls'); ?></h6>
                                    <span><?php echo wp_kses( $woocommerce->cart->cart_contents_count, true ); ?></span>
                                </div>
                                
                                <div class="btn-box">
                                    <?php
										$cart_url = wc_get_cart_url();
										if ($cart_url) :
									?>
                                    <a href="<?php echo esc_url($cart_url); ?>" class="theme-btn-two cart-btn"><span><?php esc_html_e('View Cart', 'earls'); ?></span></a>
                                    <?php endif; ?>
									<?php
                                        $checkout_url = wc_get_checkout_url();
                                        if ($checkout_url) :
                                    ?>
                                    <a href="<?php echo esc_url($checkout_url); ?>" class="theme-btn-two checkout-btn"><span><?php esc_html_e('Checkout', 'earls'); ?></span></a>
                                	<?php endif; ?>
                                </div>
                                <?php endif; ?> 
                                
                            </div>
                        </div>
                        <?php endif; endif;?>
                        
                        <div class="side-nav">
                            <div class="single-header-right sidenav-btn-box">
                                <a href="#" class="side-nav-open side-nav-opener">
                                    <figure>
                                        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/icons/bar.png" alt="<?php esc_attr_e('Awesome Image', 'earls'); ?>">
                                    </figure>
                                </a>
                            </div>
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
    	
        <!-- Header Sidebar Settings -->
		<?php if($options->get('show_sidebar_setting')){ ?>
            <!-- hidden-sidebar -->
    		<section class="hidden-sidebar side-navigation two">
				<?php get_template_part('templates/header/sidebar_settings'); ?>
            </section>
        <?php } ?>
