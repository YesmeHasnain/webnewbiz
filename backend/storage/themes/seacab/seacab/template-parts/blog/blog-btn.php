<?php

/**
 * Template part for displaying post btn
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package seacab
 */

$seacab_blog_btn = get_theme_mod( 'seacab_blog_btn', 'Read More' );
$seacab_blog_btn_switch = get_theme_mod( 'seacab_blog_btn_switch', true );

?>

<?php if ( !empty( $seacab_blog_btn_switch ) ): ?>
<div class="postbox__read-more">
    <a href="<?php the_permalink();?>" class="tp-btn postbox__more-btn"><?php print esc_html( $seacab_blog_btn );?><i class="fas fa-arrow-right"></i></a>
</div>
<?php endif;?>