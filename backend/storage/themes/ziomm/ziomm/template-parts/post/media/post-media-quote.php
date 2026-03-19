<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */

$post_quote_text = ziomm_get_field( 'post_quote_text' );
$post_quote_author = ziomm_get_field( 'post_quote_author' );

?>

<div class="vlt-post-quote">

	<h5 class="vlt-post-quote__text"><?php echo esc_html( $post_quote_text ); ?></h5>

	<?php if ( ! empty( $post_quote_author ) ) : ?>
		<span class="vlt-post-quote__author"><?php echo esc_html( $post_quote_author ); ?></span>
	<?php endif; ?>

</div>