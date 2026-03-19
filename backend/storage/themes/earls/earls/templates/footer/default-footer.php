<?php
/**
 * Footer Template  File
 *
 * @package EARLS
 * @author  Template Path
 * @version 1.0
 */

$options = earls_WSH()->option();
$allowed_html = wp_kses_allowed_html( 'post' );

$footer_logo = $options->get( 'footer_logo_image' );
$footer_logo = earls_set( $footer_logo, 'url');

?>
    
    <!-- main-footer -->
    <footer class="main-footer">
        
		<?php if($options->get('show_top_footer_v1')){ ?>
        <div class="auto-container">
            <div class="row">
                <?php if($options->get('show_footer_address_v1')){ ?>
                <div class="col-lg-6 col-md-6 col-sm-12 pr-0">
                    <div class="footer__title">
                        <div class="footer__title__icon">
                            <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/icons/map.png" alt="<?php esc_attr_e('Awesome Image', 'earls'); ?>">
                            <span class="sub____title"><?php echo wp_kses($options->get('footer_address_title_v1'), true); ?></span>
                        </div>
                        <div class="footer___title__text">
                            <p>
                                <?php echo wp_kses($options->get('footer_address_v1'), true); ?>
                            </p>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <?php if($options->get('show_footer_contact_info_v1')){ ?>
                <div class="col-lg-6 col-md-6 col-sm-12 pl-0">
                    <div class="footer__title">
                        <div class="footer__title__icon">
                            <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/icons/phone.png" alt="<?php esc_attr_e('Awesome Image', 'earls'); ?>">
                            <span class="sub____title"><?php echo wp_kses($options->get('footer_info_title_v1'), true); ?></span>
                        </div>
                        <div class="footer___title__text">
                            <p>
                               <?php echo wp_kses($options->get('footer_phone_no_v1'), true); ?> <br>
                               <?php echo wp_kses($options->get('footer_working_time_v1'), true); ?>
                            </p>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
        <?php } ?>
        
        <?php if($footer_logo): ?>
        <div class="footer-bottom">
            <div class="auto-container">
               
			   <?php if($footer_logo): ?>
               <div class="footer__logo">
                    <div class="logo-box">
                        <figure class="logo"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo esc_url($footer_logo); ?>" alt="<?php esc_attr_e('Awesome Image', 'earls'); ?>"></a></figure>
                    </div>
               </div>
               <?php endif; ?>
               
               <?php if($options->get('show_footer_menu_v1')){ ?>
               <div class="footer__menu">
                    <ul>
                    <?php wp_nav_menu( array( 'theme_location' => 'footer_menu', 'container_id' => 'navbar-collapse-1',
						'container_class'=>'navbar-collapse collapse navbar-right',
						'menu_class'=>'nav navbar-nav',
						'fallback_cb'=>false,
						'items_wrap' => '%3$s',
						'container'=>false,
						'depth'=>'1',
						'walker'=> new Bootstrap_walker()
					)); ?>
                    </ul>
               </div>
               <?php } ?>
            </div>
            
        </div>
        <?php endif; ?>
    </footer>
    <!-- main-footer end -->