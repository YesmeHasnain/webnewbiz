<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */

$post_link_text = ziomm_get_field( 'post_link_text' );
$post_link_url = ! empty( ziomm_get_field( 'post_link_url' ) ) ? ziomm_get_field( 'post_link_url' ) : get_permalink();

?>

<div class="vlt-post-link">

	<h5 class="vlt-post-link__text"><?php echo esc_html( $post_link_text ); ?></h5>
	<a href="<?php echo esc_url( $post_link_url ); ?>" class="vlt-post-link__link" title="<?php the_title_attribute(); ?>"></a>

</div>