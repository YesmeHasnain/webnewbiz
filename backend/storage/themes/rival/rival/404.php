<?php
 $rival_redux_demo = get_option('redux_demo');
get_header(); ?>
<section class="notfound section-padding">
   <div class="v-middle">
      <div class="container">
         <div class="row">
            <div class="col-md-12 text-center">
               <h1 class="display-2 fw-medium mb-0">
                  <?php if(isset($rival_redux_demo['404_heading']) && $rival_redux_demo['404_heading']!=''){?>
                  <?php echo esc_attr($rival_redux_demo['404_heading']);?>
                  <?php }else{?>
                  <?php echo esc_html__( '404', 'rival' );}?>
               </h1>
               <h1 class="display-3 fw-normal mb-0">
                  <?php if(isset($rival_redux_demo['404_title']) && $rival_redux_demo['404_title']!=''){?>
                  <?php echo esc_attr($rival_redux_demo['404_title']);?>
                  <?php }else{?>
                  <?php echo esc_html__( 'Page Not Found!', 'rival' );}?>
               </h1>
            </div>
         </div>
         <div class="row text-center">
            <div class="col-md-6 offset-md-3 text-center">
               <p>
                  <?php if(isset($rival_redux_demo['404_desc'])){?>
                  <?php echo esc_attr($rival_redux_demo['404_desc']);?>
                  <?php }else{?>
                  <?php echo esc_html__( 'Sorry, but the page you are looking for does not exist or has been removed', 'rival' ); }?>
               </p>
               <a href="<?php echo esc_url(home_url('/')); ?>" class="site-button">
                  <?php if(isset($rival_redux_demo['404_btn'])){?>
                  <?php echo esc_attr($rival_redux_demo['404_btn']);?>
                  <?php }else{?>
                  <?php echo esc_html__( 'Back To Home', 'rival' ); }?>
               </a>
            </div>
         </div>
      </div>
   </div>
</section>
<?php
get_footer(); ?> 