<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Geoport
 */
?>

</main>

<!-- Start of Footer 
============================================= -->
<?php do_action( 'geoport_footer_style' ); ?> 
<!-- End of  Footer 
============================================= -->

<?php
    if( function_exists( 'geoport_framework_init' ) ) {
        $scrollup = geoport_get_option('geoport_scroll_top');
        if (!empty($scrollup)) {
            do_action( 'geoport_scrollup' );
        } 
    } 
?>

<?php wp_footer();?>

</body>
</html>