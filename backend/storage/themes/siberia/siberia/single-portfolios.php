<?php 
/**
 * @author: MadSparrow
 * @version: 1.0
 */

get_header();

$info = get_the_ID(); ?>

<div class="ms-content--portfolio">
    <?php while ( have_posts() ) : the_post();
    	the_content();
    endwhile; ?>
    <?php if ( get_edit_post_link() ) : ?>
        <span class="admin-edit">
            <?php edit_post_link( sprintf( wp_kses( __( '<span class="dashicons dashicons-edit"></span>Edit<span class="screen-reader-text">%s</span>', 'siberia' ), array( 'span' => array( 'class' => array(), ), ) ), get_the_title() ), '<span class="edit-link">', '</span>' ); ?>
        </span>
    <?php endif; ?>
</div>
<div class="ms-spn--wrap">
    <div class="ms-spn--content row">
        <div class="ms-spn--prev col-lg-6">
            <?php echo siberia_portfolio_nav_next(); ?>
        </div>
        <div class="ms-spn--next col-lg-6">
            <?php echo siberia_portfolio_nav_prev( $info ); ?>
        </div>
    </div>
    <div class="ms-spn--separator"></div>
</div>

<?php get_footer();