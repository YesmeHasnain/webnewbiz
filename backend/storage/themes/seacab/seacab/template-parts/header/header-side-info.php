<?php 

   /**
    * Template part for displaying header side information
    *
    * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
    *
    * @package seacab
   */

    $seacab_sticky_hide = get_theme_mod( 'seacab_sticky_hide', false );
    $seacab_side_hide = get_theme_mod( 'seacab_side_hide', false );
    $seacab_search = get_theme_mod( 'seacab_search', false );
    $seacab_side_logo = get_theme_mod( 'seacab_side_logo', get_template_directory_uri() . '/assets/images/resources/footer-logo.png' );

    $seacab_extra_email = get_theme_mod( 'seacab_extra_email', __( 'demo@example.com', 'seacab' ) );
    $seacab_extra_phone = get_theme_mod( 'seacab_extra_phone', __( '6668880000', 'seacab' ) );
    $seacab_extra_address = get_theme_mod( 'seacab_extra_address', __( '24/21, 2nd Rangpur, Sapla', 'seacab' ) );
?>

<?php if ( !empty( $seacab_sticky_hide ) ): ?>
<div class="sticky-header sticked-menu main-menu">
   <div class="sticky-header__content"></div><!-- /.sticky-header__content -->
</div><!-- /.sticky-header -->
<?php endif;?>

<div class="mobile-nav__wrapper">
   <div class="mobile-nav__overlay mobile-nav__toggler"></div>
   <!-- /.mobile-nav__overlay -->
   <div class="mobile-nav__content">
      <span class="mobile-nav__close mobile-nav__toggler"><i class="fa fa-times"></i></span>

      <?php if ( !empty( $seacab_side_logo ) ): ?>
         <div class="logo-box">
            <a href="<?php print esc_url( home_url( '/' ) );?>" aria-label="logo image">
               <img src="<?php print esc_url($seacab_side_logo); ?>" width="155" alt="<?php echo esc_attr__('logo','seacab'); ?>" />
            </a>
         </div>
      <?php endif;?>
      
      <!-- /.logo-box -->
      <div class="mobile-nav__container"></div>
      <!-- /.mobile-nav__container -->

      <?php if ( !empty( $seacab_side_hide ) ): ?>
      <ul class="mobile-nav__contact list-unstyled">
         <?php if ( !empty( $seacab_extra_email ) ): ?>
            <li>
               <i class="fa fa-envelope"></i>
               <a href="mailto:<?php echo esc_attr($seacab_extra_email); ?>"><?php echo seacab_kses($seacab_extra_email); ?></a>
            </li>
         <?php endif;?>
         <?php if ( !empty( $seacab_extra_phone ) ): ?>
            <li>
               <i class="fa fa-phone-alt"></i>
               <a href="tel:<?php echo esc_attr($seacab_extra_phone); ?>"><?php echo seacab_kses($seacab_extra_phone); ?></a>
            </li>
         <?php endif;?>
         <?php if ( !empty( $seacab_extra_address ) ): ?>
            <li>
               <i class="fas fa-map-marker-alt"></i>
               <?php echo seacab_kses($seacab_extra_address); ?>
            </li>
         <?php endif;?>
      </ul><!-- /.mobile-nav__contact -->
      <div class="mobile-nav__top">
         <div class="mobile-nav__social">
            <?php seacab_header_social_profiles() ?>
         </div><!-- /.mobile-nav__social -->
      </div><!-- /.mobile-nav__top -->
      <?php endif;?>
   </div>
   <!-- /.mobile-nav__content -->
</div>