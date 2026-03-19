<?php
/**
 * Footer3 for gauthier
 */
?>
</div>
<div class="wrapper-footer one <?php if(isset($redux_gauthier['jp_footerscheme']) ){ ?><?php echo esc_html ($redux_gauthier['jp_footerscheme']); ?><?php } ?>">
<?php if (is_active_sidebar('gauthier-footer1') || is_active_sidebar('gauthier-footer2') || is_active_sidebar('gauthier-footer3')  || is_active_sidebar('gauthier-footer4') || is_active_sidebar('gauthier-footer5') ) :?>
 <div class="footer-wrapinside">
    <div class="footer-topinside">
      <footer id="colophon" role="contentinfo">
        <div class="footer3">
        <div class="footer-line">
            <div class="col-md-8">
              <div class="col-md-4">
                <?php dynamic_sidebar( 'gauthier-footer1' ); ?>
              </div>
              <div class="col-md-4">
                <?php dynamic_sidebar( 'gauthier-footer2' ); ?>
              </div>
              <div class="col-md-4">
                <?php dynamic_sidebar( 'gauthier-footer3' ); ?>
              </div>
            </div>
            <div class="col-md-4">
              <?php dynamic_sidebar( 'gauthier-footer4' ); ?>
            </div>
          </div>
        <div class="footer-line">
            <div class="col-md-12">
              <?php dynamic_sidebar( 'gauthier-footer5' ); ?>
            </div>
          </div>
        </div>
        <div id="back-top"><a href="#top"><span><i class="fa fa-angle-up fa-2x"></i></span></a></div>
      </footer>
    </div>
  </div>
 <?php endif; ?>   
  <div class="footer-bottom-wrapper">
    <div class="footer-topinside">
        <div class="col-md-8 widget-area">
      <div class="footer-nav">		
        <nav id="site-footernavigation" class="gauthier-nav">
          <?php wp_nav_menu( array( 'theme_location' => 'footer_menu', 'menu_id' => 'menu-footer', 'menu_class' => 'nav-menu', 'container' => 'ul','fallback_cb'    => '__return_false','depth' => 1 ) ); ?>
        </nav>
      </div>
	  </div>
        <div class="col-md-4 widget-area">  
		  <div class="site-wordpress">
                <?php global $redux_gauthier;
                if (isset($redux_gauthier['footer_text']) && !empty($redux_gauthier['footer_text'])) {
                    echo wp_kses_post($redux_gauthier['footer_text']);
                } else {
                    echo '&copy; ' . date('Y') . ' ' . get_bloginfo('name') . ' All rights reserved.';
                }
                ?>  
		  </div>
	  </div>
      <!-- .site-info -->
    </div>
  </div>
</div>
<div class="clear"></div>
<?php wp_footer(); ?>
</body></html>