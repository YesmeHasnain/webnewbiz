<?php
/**
 * Footer4 for gauthier
 */
global $b1ux_demo; 
?>
</div>
<div class="wrapper-footer four <?php if(isset($b1ux_demo['jp_footerscheme']) ){ ?><?php echo esc_html ($b1ux_demo['jp_footerscheme']); ?><?php } ?>">
<?php if (is_active_sidebar('gauthier-footer1') || is_active_sidebar('gauthier-footer2') || is_active_sidebar('gauthier-footer3')  || is_active_sidebar('gauthier-footer4') || is_active_sidebar('gauthier-footer5') ) :?>
 <div class="footer-wrapinside">
    <div class="footer-topinside style4">
    <div class="footer4-top">	
        <div class="widget-area">
          <?php dynamic_sidebar( 'gauthier-footer1' ); ?>
        </div>
	</div>	
    <div class="footer4-bottom">		
        <div class="widget-area">
          <?php dynamic_sidebar( 'gauthier-footer2' ); ?>
        </div>
        <div class="widget-area">
          <?php dynamic_sidebar( 'gauthier-footer3' ); ?>
        </div>
        <div class="widget-area">
          <?php dynamic_sidebar( 'gauthier-footer4' ); ?>
        </div>
        <div class="widget-area">
          <?php dynamic_sidebar( 'gauthier-footer5' ); ?>
        </div>
	</div>		
		   <div class="footerstyle6-nav">
				<nav id="site-footernavigation" class="gauthier-nav">
					 <?php wp_nav_menu( array( 'theme_location' => 'footer_menu', 'menu_id' => 'menu-footer', 'menu_class' => 'nav-menu', 'container' => 'ul','fallback_cb' =>'__return_false','depth' => 1 ) ); ?>
				</nav>
				<!-- #site-navigation --> 
		   </div>	
        <div id="back-top"><a href="#top"><span> <?php esc_html_e('Scroll To Top ', 'gauthier'); ?></span><i class="fa fa-long-arrow-right" aria-hidden="true"></i></a></div>
    </div>
  </div>
 <?php endif; ?>   
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
</div>
<div class="clear"></div>
<?php wp_footer(); ?>
</body></html>