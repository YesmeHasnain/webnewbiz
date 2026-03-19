<?php $rival_redux_demo = get_option('redux_demo');?>
   <footer class="footer">
      <div class="container clearfix">
         <div class="eight columns">
            <p>
               <?php if(isset($rival_redux_demo['footer_copyright'])){?>
               <?php echo esc_attr($rival_redux_demo['footer_copyright']);?>
               <?php }else{?>
               <?php echo esc_html__( '© Copyright 2024 Rival | All Rights Reserved.', 'rival' );}?>
            </p>
         </div>
         <div class="eight columns ">
            <ul class="right">
               <?php if ( is_active_sidebar( 'footer-area-1' ) ) : ?>
                  <?php dynamic_sidebar( 'footer-area-1' ); ?>
               <?php endif; ?>
            </ul>
         </div>
      </div>
   </footer>
</div>
<?php wp_footer(); ?>
</body>
</html>