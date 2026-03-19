<?php 

	/**
	 * Template part for displaying header layout one
	 *
	 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
	 *
	 * @package seacab
	*/

	// info
   $seacab_topbar_switch = get_theme_mod( 'seacab_topbar_switch', false );
   $seacab_phone_num = get_theme_mod( 'seacab_phone_num', __( '+5204654544', 'seacab' ) );
   $seacab_mail_id = get_theme_mod( 'seacab_mail_id', __( 'demo@example.com', 'seacab' ) );
   $seacab_address = get_theme_mod( 'seacab_address', __( '24/21, 2nd Rangpur, Sapla', 'seacab' ) );

   // header right
   $seacab_header_right = get_theme_mod( 'seacab_header_right', false );
   
   // contact button
	$seacab_button_text = get_theme_mod( 'seacab_button_text', __( 'Contact Us', 'seacab' ) );
   $seacab_button_link = get_theme_mod( 'seacab_button_link', __( '#', 'seacab' ) );

?>

<header class="main-header clearfix">
   <?php if ( !empty( $seacab_topbar_switch ) ): ?>
      <div class="main-header__top clearfix">
         <div class="container clearfix">
            <div class="main-header__top-inner clearfix">
               <div class="main-header__top-left">
                     <ul class="list-unstyled main-header__top-address">
                        <?php if ( !empty( $seacab_phone_num ) ): ?>
                        <li>
                           <div class="icon">
                                 <span class="icon-telephone"></span>
                           </div>
                           <div class="text">
                                 <p><a href="tel:<?php echo esc_attr($seacab_phone_num); ?>"><?php echo esc_html($seacab_phone_num); ?></a></p>
                           </div>
                        </li>
                        <?php endif; ?>
                        <?php if ( !empty( $seacab_mail_id ) ): ?>	
                           <li>
                              <div class="icon">
                                    <span class="icon-envelope"></span>
                              </div>
                              <div class="text">
                                    <p><a href="mailto:<?php echo esc_attr($seacab_mail_id); ?>"><?php echo esc_html($seacab_mail_id); ?></a></p>
                              </div>
                           </li>
                        <?php endif; ?>
                        <?php if ( !empty( $seacab_address ) ): ?>
                        <li>
                           <div class="icon">
                                 <span class="icon-location"></span>
                           </div>
                           <div class="text">
                                 <p><?php echo esc_html($seacab_address); ?></p>
                           </div>
                        </li>
                        <?php endif; ?>
                     </ul>
               </div>
               <div class="main-header__top-right">
                  <div class="main-header__top-right-social">
                     <?php seacab_header_social_profiles() ?>
                  </div>
                  <div class="header__lang">
                     <?php seacab_header_lang_default() ?>
                  </div>
               </div>
            </div>
         </div>
      </div>
   <?php endif; ?>
   <nav class="main-menu clearfix">
         <div class="container clearfix">
            <div class="main-menu-wrapper clearfix">
               <div class="main-menu-wrapper__left">
                     <div class="main-menu-wrapper__logo">
                        <?php seacab_header_logo();?>
                     </div>
               </div>
               <div class="main-menu-wrapper__right">
                     <div class="main-menu-wrapper__main-menu">
                        <a href="#" class="mobile-nav__toggler"><i class="fa fa-bars"></i></a>
                        <?php seacab_header_menu();?>
                     </div>
                     <?php if ( !empty( $seacab_header_right ) ): ?>
                        <?php if ( !empty( $seacab_button_text ) ): ?>
                           <a href="<?php echo esc_url($seacab_button_link); ?>" class="thm-btn main-header__btn"><?php echo esc_html($seacab_button_text); ?></a>
                        <?php endif; ?>
                     <?php endif; ?>
               </div>
            </div>
         </div>
   </nav>
</header>

<?php get_template_part( 'template-parts/header/header-side-info' ); ?>