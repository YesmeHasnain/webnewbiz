<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */

$size = 'ziomm-1920x960';

$images[] = get_post_thumbnail_id( get_the_ID() );
$post_gallery_images = ziomm_get_field( 'post_gallery_images' );

if ( $post_gallery_images ) {

	foreach( $post_gallery_images as $image ) {
		$images[] = $image['ID'];
	}

}

?>

<div class="vlt-post-media-title vlt-post-media-title--style-3 jarallax">

	<div class="container">

		<div class="row">

			<div class="col-lg-10 offset-lg-1">

				<div class="vlt-post-media__gallery" data-gap="30">

					<div class="swiper-container swiper">

						<div class="swiper-wrapper">

							<?php

								if ( $images ) :

									foreach( $images as $image ) :

										echo '<div class="swiper-slide">';
										echo wp_get_attachment_image( $image, $size, false, array( 'loading' => 'lazy', 'data-swiper-parallax' => '-100' ) );
										echo '</div>';

									endforeach;

								endif;

							?>

						</div>

						<div class="vlt-swiper-button-prev"><i class="icon-arrow-left"></i></div>
						<div class="vlt-swiper-button-next"><i class="icon-arrow-right"></i></div>

					</div>

				</div>

			</div>

		</div>

	</div>

</div>
<!-- /.vlt-post-media-title -->



