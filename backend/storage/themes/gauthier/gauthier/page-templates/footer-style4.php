<?php
/**
* Footer for gauthier
*/
?>
</div>
<div class="footer-wrapinside style6 <?php if(isset($redux_gauthier['jp_footerscheme']) ){ ?>
    <?php echo esc_html ($redux_gauthier['jp_footerscheme']); ?>
    <?php } ?>">
    <div class="footer-topinside">
        <div class="footer7-topinside">
            <div class="j_maintitle2">
                <div class="footer7-subwrapper">
                    <div class="footer7-subtitle2">
                        <?php if ( isset($redux_gauthier['opt_footer_logo']['url']) && !empty($redux_gauthier['opt_footer_logo']['url']) ){ ?>
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"> <img alt="<?php echo get_bloginfo('name'); ?>" src="<?php echo esc_url($redux_gauthier['opt_footer_logo']['url']); ?>"> </a>
                            <?php } else if ( isset($redux_gauthier['opt_header_text']) && !empty($redux_gauthier['opt_header_text']) ){ ?>
                            <h1> <a href="<?php echo esc_url( home_url( '/' ) ); ?>"> <?php echo esc_html($redux_gauthier['opt_header_text']); ?></a></h1>
                            <?php }else { ?>
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img alt="<?php echo get_bloginfo('name'); ?>" src="<?php echo esc_url( get_template_directory_uri() . '/images/logo.png' ); ?>"></a>
                            <?php } ?>
                    </div>
                </div>
            </div>
		   <div class="footer-line">
				<div class="col-md-3 left">
					<?php dynamic_sidebar( 'gauthier-footer1' ); ?>
				</div>
				<div class="col-md-6">
					<?php dynamic_sidebar( 'gauthier-footer2' ); ?>
				</div>
				<div class="col-md-3 right">
					<?php dynamic_sidebar( 'gauthier-footer3' ); ?>
				</div>
		   </div>
		   <div class="footerstyle6-nav">
				<nav id="site-footernavigation" class="gauthier-nav">
					 <?php wp_nav_menu( array( 'theme_location' => 'footer_menu', 'menu_id' => 'menu-footer', 'menu_class' => 'nav-menu', 'container' => 'ul','fallback_cb' =>'__return_false','depth' => 1 ) ); ?>
				</nav>
				<!-- #site-navigation --> 
		   </div>
            <div id="back-top"><a href="#top"><span> <?php esc_html_e('Scroll To Top ', 'gauthier'); ?></span></a></div>
        </div>
    </div>
</div>
<div class="footer-bottom-wrapper">
     <div class="footer-bottominside">
          <div class="footerstyle6 site-wordpress">
                <?php global $redux_gauthier;
                if (isset($redux_gauthier['footer_text']) && !empty($redux_gauthier['footer_text'])) {
                    echo wp_kses_post($redux_gauthier['footer_text']);
                } else {
                    echo '&copy; ' . date('Y') . ' ' . get_bloginfo('name') . ' All rights reserved.';
                }
                ?>
          </div>
          <!-- .site-info --> 
     </div>
</div>
<div class="clear"></div>
<?php wp_footer(); ?>
</body></html>