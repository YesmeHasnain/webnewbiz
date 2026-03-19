<?php
/**
 * Footer Main File.
 *
 * @package EARLS
 * @author  Template Path
 * @version 1.0
 */
global $wp_query;
$page_id = ( $wp_query->is_posts_page ) ? $wp_query->queried_object->ID : get_the_ID();
?>

	<div class="clearfix"></div>

	<?php earls_template_load( 'templates/footer/footer.php', compact( 'page_id' ) );?>

    <!--Scroll to top-->
    <button class="scroll-top scroll-to-target" data-target="html">
        <span class="icon-short-arrow-up"></span>
    </button>
</div>

<?php wp_footer(); ?>
</body>
</html>
