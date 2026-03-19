<?php 

/**
 * Template part for displaying post meta
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package seacab
 */

 $categories = get_the_terms( $post->ID, 'category' );
 $seacab_blog_cat = get_theme_mod( 'seacab_blog_cat', false );
 $seacab_blog_comments = get_theme_mod( 'seacab_blog_comments', true );

?>

<div class="blog-one__bottom">
    <?php if ( !empty($seacab_blog_cat) ): ?>
    <?php if ( !empty( $categories[0]->name ) ): ?>  
        <p class="blog-one__category"><?php echo esc_html($categories[0]->name); ?></p>
    <?php endif;?>
    <?php endif;?>

    <?php if ( !empty($seacab_blog_comments) ): ?>
    <ul class="blog-one__meta list-unstyled">
        <li><a href="<?php comments_link();?>"><i class="icon-conversation"></i><span><?php comments_number(); ?></span></a></li>
    </ul>
    <?php endif;?>
</div>