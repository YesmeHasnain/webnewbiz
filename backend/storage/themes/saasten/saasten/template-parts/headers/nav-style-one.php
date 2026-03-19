<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$saasten_logo = saasten_get_option( 'theme_logo' );
$saasten_logo_id = isset($saasten_logo['id']) && !empty($saasten_logo['id']) ? $saasten_logo['id'] : '';
$saasten_logo_url = isset( $saasten_logo[ 'url' ] ) ? $saasten_logo[ 'url' ] : '';
$saasten_logo_alt = get_post_meta($saasten_logo_id,'_wp_attachment_image_alt',true);


$saasten_logom = saasten_get_option( 'theme_logo_mobile' );
$saasten_logom_id = isset($saasten_logom['id']) && !empty($saasten_logom['id']) ? $saasten_logom['id'] : '';
$saasten_logom_url = isset( $saasten_logom[ 'url' ] ) ? $saasten_logom[ 'url' ] : '';
$saasten_logom_alt = get_post_meta($saasten_logom_id,'_wp_attachment_image_alt',true);


$header_btn_text_1 = saasten_get_option( 'header_btn_text_1' );
$header_btn_link_1 = saasten_get_option( 'header_btn_link_1' );
$header_btn_text_2 = saasten_get_option( 'header_btn_text_2' );
$header_btn_link_2 = saasten_get_option( 'header_btn_link_2' );

?>


    <!-- Header -->
    <header
      id="active-sticky"
      class="saastain-header saastain-header--v3 fluid-space"
    >
      <div class="saastain-header__middle">
        <div class="container-fluid p-0">
          <div class="row align-items-center">
            <div class="col-12">
              <div class="saastain-header__inside flex-wrap">
                <div class="saastain-header__logo">

                  <?php  
					if ( has_custom_logo() || !empty( $saasten_logo_url ) ) {
						if( isset( $saasten_logo['url'] ) && !empty( $saasten_logo_url ) ) { 
							?>
								<a href="<?php echo esc_url( site_url('/')) ?>" class="logo logo-img">
									<img class="img-fluid" src="<?php echo esc_url( $saasten_logo_url ); ?>" alt="<?php echo esc_attr( $saasten_logo_alt  ) ?>">
								</a>
						    <?php 
						} else {
							 the_custom_logo();
						}

					} else {
						printf('<h1 class="text-logo"><a href="%1$s">%2$s</a></h1>',esc_url(site_url('/')),esc_html(get_bloginfo('name')));
					}
					?>

                </div>
                <div class="saastain-header__menu">

					<div class="navbar">

          <?php
						wp_nav_menu( array(
							'theme_location' => 'primary',
							'container' => 'div',
							'container_class' => 'nav-main-wrap nav-item',
							'menu_class' => 'theme-main-menu nav-menu menu navigation list-none',
							'menu_id'        => 'primary-menu',
							'fallback_cb'  => 'saasten_fallback_menu',
						) );
					?>



                
                  </div>

                </div>


                <button
                  type="button"
                  class="offcanvas-toggler"
                  data-bs-toggle="modal"
                  data-bs-target="#offcanvas-modal"
                >
                  <span class="line"></span><span class="line"></span
                  ><span class="line"></span>
                </button>
                <div class="saastain-header__button">
                  <a
                    href="<?php echo esc_html($header_btn_link_1); ?>"
                    class="saastain-btn saastain-btn__v3 border-radius-31"
                    ><?php echo esc_html($header_btn_text_1); ?></a
                  >
                  <a
                    href="<?php echo esc_html($header_btn_link_2); ?>"
                    class="saastain-btn primary-bg pr-lg border-radius-31"
                    ><?php echo esc_html($header_btn_text_2); ?></a
                  >
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </header>
    <!-- End Header -->




    <!-- Mobile Menu Modal -->
    <div
      class="modal offcanvas-modal inflanar-mobile-menu fade"
      id="offcanvas-modal"
    >
      <div class="modal-dialog offcanvas-dialog">
        <div class="modal-content">
          <div class="modal-header offcanvas-header">
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            >
              <i class="fas fa-times"></i>
            </button>
          </div>
          <!-- offcanvas-logo-start -->
          <div class="offcanvas-logo">

        <?php  
          if ( has_custom_logo() || !empty( $saasten_logom_url ) ) {
            if( isset( $saasten_logom['url'] ) && !empty( $saasten_logom_url ) ) { 
              ?>
                <a href="<?php echo esc_url( site_url('/')) ?>">
                  <img class="img-fluid" src="<?php echo esc_url( $saasten_logom_url ); ?>" alt="<?php echo esc_attr( $saasten_logom_alt  ) ?>">
                </a>
                <?php 
            } else {
               the_custom_logo();
            }

          } else {
            printf('<h1 class="text-logo"><a href="%1$s">%2$s</a></h1>',esc_url(site_url('/')),esc_html(get_bloginfo('name')));
          }
          ?>


          </div>
          <!-- offcanvas-logo-end -->


          <!-- offcanvas-menu start -->
          <nav id="offcanvas-menu" class="offcanvas-menu">


            <?php
            wp_nav_menu( array(
              'theme_location' => 'primary',
              'container' => 'div',
              'container_class' => 'nav-main-wrap nav-item',
              'menu_class' => 'theme-main-menu nav-menu menu navigation list-none',
              'menu_id'        => 'primary-menu',
              'fallback_cb'  => 'saasten_fallback_menu',
            ) );
          ?>



          </nav>
          <!-- offcanvas-menu end -->
        </div>
      </div>
    </div>
    <!-- End Mobile Menu Modal -->