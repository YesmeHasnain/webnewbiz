<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */

$format = get_post_format();

if ( false == $format ) {
	$format = 'standard';
}

switch( $format ) {
	case 'link':
		get_template_part( 'template-parts/post/media/post-media', 'link' );
		break;
	case 'quote':
		get_template_part( 'template-parts/post/media/post-media', 'quote' );
		break;
	case 'video':
		get_template_part( 'template-parts/post/media/post-media', 'video' );
		break;
	case 'audio':
		get_template_part( 'template-parts/post/media/post-media', 'audio' );
		break;
}